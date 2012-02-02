<?php
	include_once( 'includes/config.php' );
	include_once( 'includes/facebook_main.php' );

	$uid = $_SESSION['uid'];
	if ( !in_array( $uid, Users::$admin ) ) {
		Database::connect();
		StatsManager::poll( "view", "invalid", "admin", "", "", $uid );
		include_once( 'admin/invalid.php' );
		exit;
	}
?>
