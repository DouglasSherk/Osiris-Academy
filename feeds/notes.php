<?php
	
	set_include_path( '..' );
	require_once( 'includes/config.php' );
	require_once( 'includes/facebook_main.php' );

	$start = date( 'Y-m-d', intval( $_GET['start'] ) );
	$end = date( 'Y-m-d', intval( $_GET['end'] ) );
	$category = intval( $_GET['category'] );
	$term = mysql_real_escape_string( $_GET['term'] );

	if ( $term == null || $term == "" ) {
		$term = $_SESSION['class'];
	}

	if ( $category < 1 || $category > 4 ) {
		die( "Invalid category" );
	}

	Database::connect();
	$result = mysql_query( "SELECT * FROM " . Database::$table_files . " WHERE category='$category' AND term='$term' AND time_created between '$start' and '$end' ORDER BY course;" )
		or die( "Error: " . mysql_error() );

	$tags = array( 
		1 => ' Note: ',
		2 => ' Gold Pack: ',
		3 => ' Book: ',
		4 => ' '
	);

	$events = array();
	while ( $row = mysql_fetch_assoc( $result ) ) {
		//$time = strtotime( $row['time_created'] );
		
		$event = array();
		$event['title'] = $row['course'] . $tags[$category] . $row['title'];
		$event['allDay'] = true;
		$event['start'] = strtotime( $row['time_created'] );
		$event['url'] = 'file.php?path=' . $row['path'];
		array_push( $events, $event );
	}

	
	echo json_encode( $events );

	//echo "[ { title : 'Lecture Slides', allDay : true, start : new Date( 05, 02, 2011 ) }, { title : 'Circuits Review', allDay : true, start : new Date( 05, 03, 2011 ) } ]";
?>
