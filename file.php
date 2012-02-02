<?php include 'includes/header.php' ?>
<?php $uid = $_SESSION['uid']; ?>
  <?php 
    $path = $_GET['path'];
    $result = mysql_query( "SELECT * FROM " . Database::$table_files . " WHERE path='" . mysql_real_escape_string( $path ) . "';" )
	 or die( "Error: " . mysql_error() );
	$row = mysql_fetch_assoc( $result );
	if ( $row == null ) {
          echo '<div id="noresults">This file either never existed or is no longer available.</div>';
	  include 'includes/footer.php';
          exit;
	} else {
	  $title = $row['title'];
	  $text = $row['plaintext'];
	  $extension = $row['extension'];
	  $term = $row['term'];
	  $course = $row['course'];
	  $timeCreated = $row['time_created'];
	  $timeIndexed = $row['time_indexed'];
	  $pos = strlen( "../" );
	  $path = substr( $path, $pos );
	  $statsPath = str_replace( "../", __assets, $row['path'] );
	  StatsManager::poll( "view", "file", $statsPath, "", "", $uid );
	  
	  $myDownloads = StatsManager::sum( "download", "file", $statsPath, "", "", $uid );
	  $allDownloads = StatsManager::sum( "download", "file", $statsPath );
	  $myViews = StatsManager::sum( "view", "file", $statsPath, "", "", $uid );
	  $allViews = StatsManager::sum( "view", "file", $statsPath );
	}
    $secure = "";
    if ( strstr( $path, 'Secure' ) !== false ) {
      $secure = "alertSecure(); ";
    }
  ?>
  <div id="download-title"><a href="notes.php?term=<?php echo $term; ?>"><?php echo $term ?></a> > <a href="notes.php?term=<?php echo $term; ?>&course=<?php echo $course; ?>"><?php echo $course?></a> > <?php echo $title; ?></div>
  <div id="download-image" onclick="<?php echo $secure; ?>location.href='<?php echo $path; ?>';" style="cursor:pointer;"></div>
  <div id="download">Download File (<?php echo $extension; ?>)</div>
  <div id="clearfloat"></div>
  <table id="file-info">
    <tr>
       <td>Your Downloads:</td>
       <td><?php echo $myDownloads; ?></td>
     </tr>
     <tr class="alt">
       <td>All Downloads:</td>
       <td><?php echo $allDownloads; ?></td>
     </tr>
     <tr>
       <td>Your Views:</td>
       <td><?php echo $myViews; ?></td>
     </tr>
     <tr class="alt">
       <td>All Views:</td>
       <td><?php echo $allViews; ?></td>
     </tr>
     <tr>
       <td>Created:</td>
       <td><?php echo $timeCreated; ?></td>
     </tr>
     <tr class="alt">
       <td>Last Updated:</td>
       <td><?php echo $timeIndexed; ?></td>
     </tr>
   </table>
  <?php 
    if ( $text != null && $text != "" ) {
      echo '<h3>Preview:</h3>';
	  echo '<div id="preview">' . substr( $text, 0, 750 ) . ' ...</div>';
	}
  ?>
  <div id="fb-comments">
    <div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=159304640795306&amp;xfbml=1"></script><fb:comments      href="<?php echo __server . __assets . 'file.php?path=' . $_GET['path']; ?>" num_posts="10" width="500"></fb:comments>
  </div>
<?php include 'includes/footer.php' ?>
