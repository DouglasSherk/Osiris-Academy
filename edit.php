<?php
	require_once( 'includes/config.php' );
	$db = Database::connect();
	require_once( 'includes/facebook_main.php' );
    $uid = $_SESSION['uid'];

    $class = $_SESSION['class'];

	if ( isset( $_GET['done'] ) ) {
	  $text = $_POST['text'];
	  
	  if ( $text != "" && $text != null ) {	    
		$text = mysql_real_escape_string( $text );
	  
	    mysql_query( "INSERT INTO " . Database::$table_motd . " ( text, uid, time, class ) VALUES( '$text', '$uid', CURRENT_TIMESTAMP, '$class'  );" )
		  or die( "Error: " . mysql_error() );
	    header( "Location: ." );  

	    StatsManager::poll( "edit", "motd", "set", "", "", $uid );
	  } else {
	    StatsManager::poll( "edit", "motd", "fail", "", "", $uid );
	  }
	} else {
	  StatsManager::poll( "edit", "motd", "start", "", "", $uid );
	}
?>
<?php include 'includes/header.php' ?>
    <div id="motd">Message of the Day</div>
    <div id="edit"><a href="edit.php">Edit</a></div>
    <div class="spacer"></div>
    <p>
    Feel free to use HTML, JavaScript or whatever else you can throw in here. Do not abuse it as we have your Facebook login information and will ban you permanently from this site if you do anything out of line.
    <?php
	  $result = mysql_query( "SELECT * FROM " . Database::$table_motd . " WHERE class='$class' ORDER BY `time` DESC LIMIT 1;" ) 
	    or die( "Error: " . mysql_error() );
	  $row = mysql_fetch_assoc( $result );
	  $text = htmlentities( $row['text'] );
	?>
    <form action="edit.php?done=1" method="post">
    <textarea name="text" style="width: 100%; height: 16em;"><?php echo stripslashes( $text ); ?></textarea>
    <input type="hidden" name="done" value="1" />
    <input type="submit" value="Update MOTD" />
    </form>
    </p>	  
    <p class="time">Last updated: <?php echo $row['time']; ?> </p>
<?php include 'includes/footer.php' ?>
