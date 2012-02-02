<?php
	// Entry point.
	include_once( 'includes/facebookLib.php' );
	include_once( 'includes/statsmanager.php' );

    $forceRefresh = $_GET['refresh'];

    if ($forceRefresh == 1) {
        $me = null;
        $facebook = null;
        $_SESSION['uid'] = null;
        $_SESSION['me'] = null;
        session_destroy();
    }

	// Create our Application instance (replace this with your appId and secret).
    $uid = $_SESSION['uid'];
    $me = $_SESSION['me'];
    if ($me == null) {
        $facebook = new facebookLib(array(
            'appId'  => FBLogin::$appId,
            'secret' => FBLogin::$secret,
            'cookie' => true,
        ));

        // Session based API call.
        $uid = $facebook->getUser();
        if ($uid) {
            try {
                $me = $facebook->api('/me');
                // Retry it
                if ($me == null) {
                    $me = $facebook->api('/me');
                }
                $_SESSION['me'] = $me;

                if ( !isset( $_SESSION['uid'] ) ) {
                    Database::connect();
                    $_SESSION['uid'] = $uid;
                    $result = mysql_query( "SELECT last_login FROM " . Database::$table_users . " WHERE uid='" . $uid . "';" )
                      or die( "Error: " . mysql_error() );
                    if ( mysql_num_rows( $result ) > 0 ) {
                        $row = mysql_fetch_assoc( $result );
                        $_SESSION['last_login'] = $row['last_login'];
                    }	
                    $name = $me['name'];
                    $email = $me['email'];
                    $group = Classes::inGroup( $facebook, $me );
                    if ( $name != 'n' ) {	
                        mysql_query( "INSERT INTO " . Database::$table_users . " VALUES ( '$uid', '$name', '$email', default, '$group' ) ON DUPLICATE KEY " .
                         "UPDATE name = VALUES( name ), email = VALUES( email ), last_login = CURRENT_TIMESTAMP, class = VALUES( class );" )
                         or die( "Error: " . mysql_error() );
                    }
                    StatsManager::poll( "login", $group, "", "", "", $uid );
                }

                // Check if the user is in a proper group.
                if ( $group == null && Classes::inGroup( $facebook, $me ) == null ) {
                    $_SESSION['ingroup'] = false;
                }

                $_SESSION['logouturl'] = $facebook->getLogoutUrl();
            } catch (FacebookApiException $e) {
                error_log($e);
            }
        }
    }
	
	// login or logout url will be needed depending on current user state.
	if ($me != null) {
		$logoutUrl = $_SESSION['logouturl'];
	} else {
		$loginUrl = $facebook->getLoginUrl();
		Database::connect();
		StatsManager::poll( "view", "landing", "", "", "", $_SERVER['REMOTE_ADDR'] );
		include_once( "landing.php" );
	    //if ( !isset( $_SESSION['refreshed'] ) ) {
        //    header( "Location: ." );
        //    $_SESSION['refreshed'] = true;
        //}
	    exit;
	}
	
    if (isset($_SESSION['ingroup']) && $_SESSION['ingroup'] == false) {
        Database::connect();
        StatsManager::poll( "view", "invalid", "", "", "", $uid );
        include_once( "invalid.php" );
        exit;
    }


	// Check if user is banned.
	if ( in_array( $uid, Users::$banned ) ) {
		Database::connect();
		StatsManager::poll( "view", "banned", "", "", "", $uid );
		include_once( "banned.php" );
		exit;
	}
?>
