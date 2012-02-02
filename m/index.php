<?php 
  require_once( "header.php" ); 
?>

<?php
  Database::connect();

  // Find all classes that actually have a calendar.
  $terms = array();
  $term = $_GET['term'];

  if ( $term == null || $term == "" ) {
    $result = mysql_query( "SELECT * FROM " . Database::$table_mobile . " WHERE ip='" . $_SERVER['REMOTE_ADDR'] . "' AND last_login > DATE_SUB( NOW(), INTERVAL 7 DAY )" )
      or die( "Error: " . mysql_error() );

    $row = mysql_fetch_assoc( $result );
    $term = $row['term'];
  }

  foreach ( Classes::$calendars as $class => $gcal ) {
    if ( $gcal != null && $gcal != "" ) {
      if ( $term == null || $term == "" ) {
        $term = $class;
      }
      $terms[$class] = $gcal;
    }
  }
?>

<?php StatsManager::poll( "m_view", "calendar", $term, "", "", $_SERVER['REMOTE_ADDR'] ); ?>

<?php
  $calendars = array( 
    "3B" => "https://www.google.com/calendar/htmlembed?mode=agenda&src=bsvvl5f44c128u3h6vcdtu3plc%40group.calendar.google.com&ctz=America/Toronto",
    "3A" => "https://www.google.com/calendar/htmlembed?mode=agenda&src=mechatronics2014%40gmail.com&ctz=America/Toronto"
  );
?>

<?php
  mysql_query( "INSERT INTO " . Database::$table_mobile . " VALUES( '" . $_SERVER['REMOTE_ADDR'] . "', '" . mysql_real_escape_string( $term ) . "', default ) ON DUPLICATE KEY UPDATE term = VALUES( term ), last_login =  CURRENT_TIMESTAMP;" )
    or die( "Error: " . mysql_error() );
?>

<form action="" mode="GET" name="filters">
  <select name="term" onchange="document['filters'].submit(); return false">
    <?php
      foreach ( $terms as $class => $gcal ) {
        $selected = false;
        if ( $class == $term ) {
          $selected = 'selected';
        }
        echo '<option ' . $selected . '>' . $class . '</option>';
      }
    ?>
  </select>
</form>

<iframe id="calendar" src="<?php echo $calendars[$term]; ?>" frameborder="0">
  <p><a href="<?php echo $calendars[$term]; ?>">Click here for the calendar.</a></p>
</iframe>

<?php require_once( "footer.php" ); ?>
