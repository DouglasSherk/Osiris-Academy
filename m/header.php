<?php
  session_start();
  require_once( "../includes/config.php" );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta property="fb:app_id" content="129338740471924" />
<title><?php echo General::$name; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo __server . __assets; ?>m/css/mobile.css" />
</head>

<?php
  require_once( "../includes/statsmanager.php" );
  $db = Database::connect();
?>

<body>
