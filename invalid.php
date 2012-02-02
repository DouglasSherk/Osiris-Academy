<?php require_once( "includes/config.php" ); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo General::$name; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo __server . __assets; ?>/css/main.css" />
</head>

<body class="twoColElsLtHdr">

<div id="container-invalid">  
  <div id="header-login">
    <div id="title" onclick="location.href='.';" style="cursor:pointer;">
    </div>
  </div>
  <div class="clearfloat"></div>
  <div id="invalid-text">
    <p>You are not a registered member of any Facebook group endorsed by <?php echo General::$name; ?>. This is required to see <?php echo General::$name; ?> content. Please contact an administrator for the group relevant to you. If you require assistance, please send an email to <a href="mailto:<?php echo General::$contactEmail; ?>"><?php echo General::$contactEmail; ?></a>.</p>
    <div id="fb-root"></div>
  </div>
</div>
</body>
</html>
