<?php include "includes/header.php" ?>
<?php $uid = $_SESSION['uid']; ?>

<?php
  $term = $_GET['term'];
  if ( $term == null || $term == "" ) {
    $term = $_SESSION['class'];
  }

  // Find all classes that actually have a calendar.
  $terms = array();
  foreach ( Classes::$calendars as $class => $gcal ) {
    if ( $gcal != null && $gcal != "" ) {
      $terms[$class] = $gcal;
    }
  }
?>

<?php StatsManager::poll( "view", "calendar", $term, "", "", $uid ); ?>

<link rel='stylesheet' type='text/css' href='js/fullcalendar/fullcalendar.css' />
<script type='text/javascript' src='js/jquery/jquery-1.5.min.js'></script>
<script type='text/javascript' src='js/fullcalendar/fullcalendar.js'></script>
<script type='text/javascript' src='js/fullcalendar/gcal.js'></script>

<script type='text/javascript'>
	$(document).ready(function() {
	
		$('#calendar').fullCalendar({
			header: {
				left: 'prev, next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			eventSources: [
				<?php
					if ( $_GET['events'] != true && Classes::$calendars[$term] != null && Classes::$calendars[$term] != "" ) {
						echo "'" . Classes::$calendars[$term] . "',";
					}
					
					if ( $_GET['notes'] != true ) {
						echo "
				{
					url: '/feeds/notes.php',
					type: 'GET',
					data: {
						category: '1',
						term: '$term'
					},
					//error: function() {
					//	alert( 'There was an error while fetching events.' );
					//},
					color: 'green',
					text: 'white'
				},";
					}

					if ( $_GET['gold_packs'] != true ) {
						echo "
				{
					url: '/feeds/notes.php',
					type: 'GET',
					data: {
						category: '2',
						term: '$term'
					},
					//error: function() {
					//	alert( 'There was an error while fetching events.' );
					//},
					color: 'orange',
					text: 'black'
				},";
					}

					if ( $_GET['books'] != true ) {
						echo "
				{
					url: '/feeds/notes.php',
					type: 'GET',
					data: {
						category: '3',
						term: '$term'
					},
					//error: function() {
					//	alert( 'There was an error while fetching events.' );
					//},
					color: 'red',
					text: 'white'
				},";
					}

					if ( $_GET['misc'] != true ) {
						echo "
				{
					url: '/feeds/notes.php',
					type: 'GET',
					data: {
						category: '4',
						term: '$term'
					},
					//error: function() {
					//	alert( 'There was an error while fetching events.' );
					//},
					color: 'purple',
					text: 'white'
				}";
					}
				?>
			]
		});
	
	});
</script>
<div id="motd">Calendar</div>
<div id="color-right-align">
  <div id="color-codes">
    <form action="" mode="GET" name="filters">
      <div class="term-selection">Term: 
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
      </div>
      <div class="events">Events <input type="checkbox" name="events" value="true" onchange="document['filters'].submit(); return false" <?php if ( $_GET['events'] == true ) echo 'checked' ?> /></div>
      <div class="notes">Notes <input type="checkbox" name="notes" value="true" onchange="document['filters'].submit(); return false" <?php if ( $_GET['notes'] == true ) echo 'checked' ?> /></div>
      <div class="misc">Misc <input type="checkbox" name="misc" value="true" onchange="document['filters'].submit(); return false" <?php if ( $_GET['misc'] == true ) echo 'checked' ?> /></div>
      <div class="gold_packs">Gold Packs <input type="checkbox" name="gold_packs" value="true" onchange="document['filters'].submit(); return false" <?php if ( $_GET['gold_packs'] == true ) echo 'checked' ?> /></div>
      <div class="books">Books <input type="checkbox" name="books" value="true" onchange="document['filters'].submit(); return false" <?php if ( $_GET['books'] == true ) echo 'checked' ?> /></div>
    </form>
  </div>
</div>
<div id="clearboth"></div>
<?php
  if ( Classes::$calendars[$term] == null || Classes::$calendars[$term] == "" ) {
    echo '<div id="noresults">There is no calendar for your term. Please send an email to <a href="mailto: doug@sherk.me">doug@sherk.me</a> to report this.</div>'; 
  }
?>
<div id="calendar"></div>
<div id="fb-comments">
  <div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=159304640795306&amp;xfbml=1"></script><fb:comments href="<?php echo __server . '/calendar.php'; ?>" num_posts="10" width="500"></fb:comments>
</div>

<?php include "includes/footer.php" ?>
