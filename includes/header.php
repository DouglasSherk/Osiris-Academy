<?php
  // Disabled until re-evaluated.
  require_once( 'includes/mobile.php' );
  detect_mobile_device( true, true, true, true, 'm', false );

  session_start();
  
  require_once( 'includes/config.php' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta property="fb:app_id" content="129338740471924" />
<title><?php echo General::$name; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo __server . __assets; ?>/css/main.css" />
<script type="text/javascript" src="js/main.js"></script>
</head>

<?php
  require_once( 'includes/facebook_main.php' );
  $db = Database::connect();
?>

<body id="main" class="twoColElsLtHdr">

<div id="container">  
  <div id="header">
    <div id="title" onclick="location.href='.';" style="cursor:pointer;">
    </div>
    <script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
    <div id="fb-like">
      <div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like-box href="http://www.facebook.com/apps/application.php?id=129338740471924" width="250" show_faces="false" border_color="" stream="false" header="false"></fb:like-box>
    </div>
    <div id="search">
      <?php include 'includes/search.php' ?>
    </div>
  </div>
  <div class="clearfloat"></div>
  <?php include 'includes/sidebar.php' ?>
  <div id="mainContent">
