<?php set_include_path( '..' ); ?>
<?php include_once 'admin/auth.php'; ?>
<?php include_once 'includes/header.php'; ?>
<?php $uid = $_SESSION['uid']; ?>

<?php StatsManager::poll( "admin", "indexfiles", "", "", "", $uid ) ); ?>

<?php
	echo shell_exec( "lynx -dump " . __server . __assets . "cron/indexfiles.php" );
?>

<?php include_once 'includes/footer.php'; ?>
