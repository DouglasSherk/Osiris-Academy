<?php include 'includes/header.php' ?>
<?php
  $uid = $_SESSION['uid'];
  $limitTerm = $_GET['term'];
  $limitCourse = $_GET['course'];
  $limitCategory = $_GET['category'];
	
  echo "<h2>";
  switch ( $limitCategory ) {
    case 2 : 
      echo "Gold Packs";
      break;
    case 3 :
      echo "Books";
      break;
    default :
      echo "Notes";
      break;
  }
  echo "</h2>";
	
  include_once( 'includes/filter.php' );

  if ( $limitTerm == '' || $limitTerm == null ) {
    $limitTerm = $_SESSION['class'];
    $result = mysql_query( "SELECT * FROM " . Database::$table_files . " WHERE category='" . mysql_real_escape_string( $limitCategory ) . "' AND term='" . $limitTerm . "';" )
      or die( "Error: " . mysql_error() );
    if ( mysql_num_rows( $result ) == 0 ) {
      $limitTerm = 'All';
    }
    $result = null;
  }
	
  if ( $limitTerm == 'All' ) {
    $limitTerm = '';
  }
  if ( $limitCourse == 'All' ) {
    $limitCourse = '';
  }
	
  StatsManager::poll( "view", "notes", $limitCategory, $limitTerm, $limitCourse, $uid );
	
  $and = false;
  $config = " ";
  if ( $limitTerm != "" || $limitCourse != "" || $limitCategory != "" ) {
    $config = " WHERE ";
    if ( $limitTerm != "" ) {
      $config .= "term='" . mysql_real_escape_string( $limitTerm ) . "'";
      $and = true;
    }
    if ( $limitCourse != "" ) {
      if ( $and ) {
        $config .= " AND ";
      }
      $config .= "course='" . mysql_real_escape_string( $limitCourse ) . "' ";
      $and = true;
    }
    if ( $limitCategory != "" ) {
      if ( $and ) {
        $config .= " AND ";
      }
      $config .= "category='" . mysql_real_escape_string( $limitCategory ) . "' ";
    }
  }
	
  $result = mysql_query( "SELECT * FROM " . Database::$table_files . $config . " ORDER BY `term` DESC, `course` ASC, `time_created` ASC;" ) 
    or die( "Error: " . mysql_error() );
	  
  $lastTerm = "";
  $lastCourse = "";
  $andCategory = "";
  if ( $limitCategory != "" ) {
    $andCategory = '&category=' . $limitCategory;
  }
  while ( $row = mysql_fetch_assoc( $result ) ) {
    $title = $row['title'];
    $path = $row['path'];
    $term = $row['term'];
    $course = $row['course'];
    if ( $term != $lastTerm ) {
      if ( $lastTerm != "" ) {
        echo "</ul></ul>";
      }
      echo "<h2><a href=\"notes.php?term=$term$andCategory\">$term</a></h2><ul><h3><a href=\"notes.php?term=$term&course=$course$andCategory\">$course</a></h3><ul>";
      $lastTerm = $term;
      $lastCourse = $course;
    } else if ( $course != $lastCourse ) {
      echo "</ul><h3><a href=\"notes.php?term=$term&course=$course$andCategory\">$course</a></h3><ul>";
      $lastCourse = $course;
    }
    echo '<li><a href="file.php?path=' . $path . '">' . $title . '</a></li>';
  }
  if ( mysql_num_rows( $result ) == 0 ) {
    echo '<div id="noresults">There were no results for your settings. Please widen your search parameters.</div>';
  }
  ?>
  </ul></ul>
<?php include 'includes/footer.php' ?>
