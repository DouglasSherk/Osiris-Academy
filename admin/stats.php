<?php set_include_path( '..' ); ?>
<?php include 'admin/auth.php'; ?>
<?php include 'includes/header.php'; ?>
<?php $uid = $_SESSION['uid']; ?>

<?php StatsManager::poll( "admin", "stats", "", "", "", $uid ); ?>
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
	    $users = array();
	    $result = mysql_query( "SELECT * FROM " . Database::$table_users . ";" )
		  or die( "Error: " . mysql_error() );
		while ( $row = mysql_fetch_assoc( $result ) ) {
		  $users[$row['uid']] = $row['name'];
		}
	    $result = mysql_query( "SELECT * FROM " . Database::$table_stats . " WHERE time > CURDATE() ORDER BY time DESC;" )
	      or die( "Error: " . mysql_error() );
	    while ( $row = mysql_fetch_assoc( $result ) ) {
		  echo '<tr>';
		  echo '<td>' . $row['counter'] . '</td>';
		  echo '<td>' . $row['kingdom'] . '</td>';
		  echo '<td>' . $row['phylum'] . '</td>';
		  echo '<td>' . $row['class'] . '</td>';
		  echo '<td>' . $row['family'] . '</td>';
		  if ( isset( $users[$row['genus']] ) ) {
		    echo '<td>' . $users[$row['genus']] . '</td>';
		  } else {
		    echo '<td>' . $row['genus'] . '</td>';
		  }
		  echo '<td>' . $row['value'] . '</td>';
		  echo '<td>' . $row['time'] . '</td>';
		  echo '</tr>';
	    }
	  ?>
	</table>
    <div id="fb-comments">
      <div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=159304640795306&amp;xfbml=1"></script><fb:comments        href="<?php echo __server . __assets . "stats.php"; ?>" num_posts="10" width="500"></fb:comments>
    </div>
<?php include 'includes/footer.php' ?>
