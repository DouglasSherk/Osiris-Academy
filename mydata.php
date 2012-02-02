<?php 
  $bare = $_GET['bare'];
  if ( $bare != '1' ) {
    include 'includes/header.php';
  } else {
    require_once( 'includes/config.php' );
    require_once( 'includes/facebook_main.php' );
    $db = Database::connect();
  }

  $uid = $_SESSION['uid']; 
  if ( $uid == null || $uid == '' || $uid == ' ' ) {
    die( "There has been an error loading your data and you cannot access this page." );
  }
?>

<h1>My Data</h1>

<p>This page has all information that Megatrons stores about you. You can download it at any time or delete it from our servers, though we appreciate it if you don't do that given that this is a free service with no ads or anything and we don't sell or give your information to anyone.</p>

<?php
  $delete = $_GET['delete'];
  if ( $delete == "1" ) {
    mysql_query( "DELETE FROM " . Database::$table_stats . " WHERE genus=$uid;" )
      or die( "Error: " . mysql_error() );
  }
?>

<?php
  $param = "";
  if ( $delete == "1" ) {
    $param .= "delete";
  }
  if ( $bare == "1" ) {
    $param .= "bare";
  }

  $result = mysql_query( "SELECT * FROM " . Database::$table_stats . " WHERE genus='$uid' ORDER BY time DESC;" )
    or die( "Error: " . mysql_error() );
  echo "<p>There are currently " . mysql_num_rows( $result ) . " records stored about you or your activity.";
?>

<div style="margin-bottom: 12px;">
  <a href="mydata.php?bare=1">View Plain Text</a> | <a href="mydata.php?delete=1">Delete My Data</a>
</div>

<?php StatsManager::poll( "view", "mydata", $param, "", "", $uid ); ?>
   <table style="border: 1px solid;">
	  <tr>
	    <b>
	    <td>Counter</td>
		<td>Kingdom</td>
		<td>Phylum</td>
		<td>Class</td>
		<td>Family</td>
		<td>Genus</td>
		<td>Value</td>
		<td>Time</td>
		</b>
	  </tr>
	  <?php
	    while ( $row = mysql_fetch_assoc( $result ) ) {
		  echo '<tr>';
		  echo '<td>' . $row['counter'] . '</td>';
		  echo '<td>' . $row['kingdom'] . '</td>';
		  echo '<td>' . $row['phylum'] . '</td>';
		  echo '<td>' . $row['class'] . '</td>';
		  echo '<td>' . $row['family'] . '</td>';
		  echo '<td>' . $row['genus'] . '</td>';
		  echo '<td>' . $row['value'] . '</td>';
		  echo '<td>' . $row['time'] . '</td>';
		  echo '</tr>';
	    }
	  ?>
	</table>
<?php if ( $bare != '1' ) { ?>
    <div id="fb-comments">
      <div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=159304640795306&amp;xfbml=1"></script><fb:comments        href="<?php echo __server . __assets . "stats.php"; ?>" num_posts="10" width="500"></fb:comments>
    </div>
<?php } ?>

<?php if ( $bare != '1' ) { include 'includes/footer.php'; } ?>
