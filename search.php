<?php include 'includes/header.php' ?>

<?php
  $uid = $_SESSION['uid'];
  $search = $_GET['search'];
  $termSearch = mysql_real_escape_string( $_GET['term'] );
  $courseSearch = mysql_real_escape_string( $_GET['course'] );
  StatsManager::poll( "search", $search, "", "", "", $uid );
?>

<script type="text/javascript">
  var searchBar = document.getElementById( "searchtext" );
  searchBar.value = "<?php echo $search; ?>";
</script>

<h3>Search - "<?php echo $search; ?>"</h3>

<?php	
  include_once( 'includes/filter.php' );
  echo '<h1></h1>'; // Spacer.

  $search_array = explode( ' ', $search );
  $query = "";
  $first = true;
  foreach ( $search_array as $keyword ) {
    if ( $first == true ) {
      $query .= "plaintext LIKE '%" . mysql_real_escape_string( $keyword ) . "%'";
      $first = false;
    } else {
      $query .= " OR plaintext LIKE '%" . mysql_real_escape_string( $keyword ) . "%'";
    }
  }
  if ( $termSearch == "" || $termSearch == null ) {
    $termSearch = $_SESSION['class'];
  }
  if ( $termSearch == "All" ) {
    $termSearch = "";
  }  
  if ( $courseSearch == "All" ) {
    $courseSearch = "";
  }
  if ( $termSearch != null && $termSearch != "" ) {
    $query .= " AND term='$termSearch' ";
  }
  if ( $courseSearch != null && $courseSearch != "" ) {
    $query .= " AND course='$courseSearch' ";
  }
  
  $result = mysql_query( "SELECT * FROM " . Database::$table_files . " WHERE $query ORDER BY term DESC, course ASC;" ) 
    or die( "Error: " . mysql_error() );
  $length = 300;
  while ( $row = mysql_fetch_assoc( $result ) ) {
    $text = $row['plaintext'];
    $pos = 99999;
    foreach ( $search_array as $keyword ) {
      $text = str_ireplace( $keyword, '<b>' . $keyword . '</b>' , $text );
      $newPos = stripos( $text, $keyword );
      if ( $newPos < $pos && $newPos !== false ) {
        $pos = $newPos;
        $sub = $pos;
        if ( $sub <= $length / 2 ) {
          $sub = 0;
        } else {
          $sub -= $length / 2;
        }
        $text = substr( $text, $sub, $length );
      }
	}
    $term = $row['term'];
    $course = $row['course'];
    echo '<div id="search-result">';
    echo "<h2><a href=\"notes.php?term=$term\">$term</a> > <a href=\"notes.php?term=$term&course=$course\">$course</a> > <a href=\"file.php?path=" . $row['path'] . '">' . $row['title'] . '</a></h2>';
    if ( $sub > 0 ) {
      echo " ... ";
    }
    echo $text;
    if ( $sub + $length < strlen( $row['plaintext'] ) ) {
      echo " ... ";
    }
    echo '</div>';
  }
  if ( mysql_num_rows( $result ) == 0 ) {
    echo '<div id="noresults">There were no results for your search query. Please try a different search pattern.</div>';
  }
?>
<?php include 'includes/footer.php' ?>
