<?php set_include_path( '..' ); ?>
<?php include_once( 'includes/header.php' ); ?>

<?php require_once( 'admin/auth.php' ); ?>

<h3>.</h3>
<div id="fb-comments">
  <div id="fb-root"></div>
  <script src="http://connect.facebook.net/en_US/all.js#appId=159304640795306&amp;xfbml=1"></script>
  <fb:comments 
    href="<?php echo __server; ?>" 
	num_posts="10" 
	width="500">
  </fb:comments>
</div>

<h3>calendar.php</h3>
<div id="fb-comments">
  <div id="fb-root"></div>
  <script src="http://connect.facebook.net/en_US/all.js#appId=159304640795306&amp;xfbml=1"></script>
  <fb:comments 
    href="<?php echo __server . __assets . "calendar.php"; ?>" 
	num_posts="10" 
	width="500">
  </fb:comments>
</div>

<?php
  Database::connect();
  $result = mysql_query( "SELECT * FROM " . Database::$table_files . ";" )
    or die( "Error: " . mysql_error() );
  while ( $row = mysql_fetch_assoc( $result ) ) {
    $path = __server . __assets . 'file.php?path=' . $row['path'];
    echo '<h3>' . $row['title'] . '</h3>
<iframe src="http://www.facebook.com/plugins/comments.php?href=' . $path . '&permalink=1" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:130px; height:16px;" allowTransparency="true"></iframe>';
  }
?>

<?php include_once( 'includes/footer.php' ); ?>