<?php include 'includes/header.php' ?>
<p>This page contains all content from each term.</p>
<p>These are rebuilt every day at 7 PM EST.</p>
<?php
  $uid = $_SESSION['uid'];

  $result = mysql_query( "SELECT * FROM " . Database::$table_files . " WHERE category='5' ORDER BY `term` DESC;" )
    or die( "Error: " . mysql_error() );
	
  StatsManager::poll( "view", "notes", "all", "", "", $uid );

  echo "<ul>";
  while ( $row = mysql_fetch_assoc( $result ) ) {
    $title = $row['title'];
    $path = $row['path'];
    $term = $row['term'];
    $course = $row['course'];
    echo '<li><a href="file.php?path=' . $path . '">' . $title . '</a></li>';
  }
?>
<?php include 'includes/footer.php' ?>
