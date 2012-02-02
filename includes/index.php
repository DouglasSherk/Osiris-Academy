<?php include 'includes/header.php' ?>
<?php StatsManager::poll( "view", "index", "", "", "", $facebook->getUser() ); ?>
    <?php
	  $uid = $facebook->getUser();
	  $result = mysql_query( "SELECT * FROM " . Database::$table_files . " WHERE time_indexed > ( SELECT last_login FROM " . Database::$table_users . " WHERE uid='$uid' ) ORDER BY `term` DESC, `course` ASC;" )
	    or die( "Error: " . mysql_error() );
	  if ( mysql_num_rows( $result ) ) {
	    echo '<h2>Updated Content</h2>';
		echo 'These notes, books and gold packs have been updated since your last visit.';
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
	      echo "<h2>$term</h2><ul><h3>$course</h3><ul>";
		  $lastTerm = $term;
		  $lastCourse = $course;
	    } else if ( $course != $lastCourse ) {
	      echo "</ul><h3>$course</h3><ul>";
		  $lastCourse = $course;
	    }
	    echo '<li><a href="file.php?path=' . $path . '">' . $title . '</a></li>';
	  }
	  if ( mysql_num_rows( $result ) ) {
	    echo '</ul></ul>';
		echo '<div id="separator"></div>';
	  }
	?>
    
    <div id="motd">Message of the Day</div>
    <div id="edit"><a href="edit.php">Edit</a></div>
    <div class="spacer"></div>
    <p>
	<?php
	  $result = mysql_query( "SELECT * FROM " . Database::$table_motd . " ORDER BY `time` DESC LIMIT 1;" ) 
	    or die( "Error: " . mysql_error() );
	  $row = mysql_fetch_assoc( $result );
	  echo nl2br( stripslashes( $row['text'] ) );
	  $uid = $row['uid'];
	  $result2 = mysql_query( "SELECT * FROM " . Database::$table_users . " WHERE uid='$uid';" )
	    or die( "Error: " . mysql_error() );
	  $row2 = mysql_fetch_assoc( $result2 );
	?>
    </p>	  
    <p class="time">Last updated: <?php echo $row['time']; ?> by <?php echo $row2['name']; ?> </p>
    <div id="fb-comments">
      <div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=159304640795306&amp;xfbml=1"></script><fb:comments        href="<?php __server . __assets ?>" num_posts="10" width="500"></fb:comments>
    </div>
<?php include 'includes/footer.php' ?>