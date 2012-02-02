<?php include 'includes/header.php'; ?>
<?php $uid = $_SESSION['uid']; ?>
<?php StatsManager::poll( "logout", "", "", "", "", $uid ); ?>

<?php
    require_once( 'includes/facebook_main.php' );

/*    $_SESSION['uid'] = null;
    $_SESSION['me'] = null;
unset( $_SESSION['ingroup'] ); */
    header( 'Location: ' . $_SESSION['logouturl'] );
    session_destroy();
?>

<?php include 'includes/footer.php'; ?>
