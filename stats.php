<?php include_once( 'includes/header.php' ); ?>
<?php
  Database::connect();

  function getSingleResult( $query, $var ) {
    $result = mysql_query( $query )
      or die( "Error: " . mysql_error() );
    $row = mysql_fetch_assoc( $result );
    return $row[$var];
  }

  $numUsers = getSingleResult( "SELECT COUNT( * ) AS num_users FROM " . Database::$table_users . ";", 'num_users' );

  echo '<p><table style="width: 40%;"><tr><td>Total Users</td>';  
  echo "<td>" . $numUsers . "</td></tr>";

  foreach ( Classes::$classes as $class => $garbage ) {
    $numClassUsers = getSingleResult( "SELECT COUNT( * ) AS num_users FROM " . Database::$table_users . " WHERE class='" . $class . "';", 'num_users' );
    if ( $numClassUsers > 0 ) {
      echo "<tr><td>Total " . $class . "</td><td>" . $numClassUsers . "</td></tr>";
    }
  }

  echo "</table></p>";

  echo '<p><table style="width: 40%"><tr><td>DAU</td>';
  $numDAU = getSingleResult( "SELECT COUNT( DISTINCT genus ) AS num_dau FROM " . Database::$table_stats . " WHERE counter='login' AND time > CURDATE();", 'num_dau' );
  echo "<td>" . $numDAU . "</td></tr>";

  foreach ( Classes::$classes as $class => $garbage ) {
    $numClassDAU = getSingleResult( "SELECT COUNT( DISTINCT genus ) AS num_dau FROM " . Database::$table_stats . " WHERE counter='login' AND kingdom='" . $class . "' AND time > CURDATE();", 'num_dau' );
    if ( $numClassDAU > 0 ) {
      echo "<tr><td>" . $class . " DAU</td><td>" . $numClassDAU . "</td></tr>";
    }
  }
  echo "</table></p>";

  echo "<p>";
  $numBanned = getSingleResult( "SELECT COUNT( * ) AS num_banned FROM " . Database::$table_stats . " WHERE kingdom='banned';", 'num_banned' );
//  echo "Banned: " . $numBanned;
  echo "</p>";
?>
<?php include_once( 'includes/footer.php' ); ?>
