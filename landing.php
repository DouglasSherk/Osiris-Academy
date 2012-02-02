<?php require_once( "includes/config.php" ); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo General::$name; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo __server . __assets; ?>/css/main.css" />
</head>

<body class="twoColElsLtHdr">

<div id="container-login">  
  <div id="header-login">
    <div id="title" onclick="location.href='.';" style="cursor:pointer;">
    </div>
  </div>
  <div class="clearfloat"></div>
  <div id="login-text">
    Welcome to the <?php echo General::$name; ?>  website. Please log in with Facebook to continue.
  </div>
  <div id="fb-login">      

  <div id="fb-root"></div>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '<?php echo FBLogin::$appId; ?>',
          status     : true, 
          cookie     : true,
          xfbml      : true,
          oauth      : true,
        });

        FB.Event.subscribe('auth.login', function(response) {
            setTimeout( function() { window.location.reload() }, 500 );
        });
      };

      (function(d){
         var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
         js = d.createElement('script'); js.id = id; js.async = true;
         js.src = "//connect.facebook.net/en_US/all.js";
         d.getElementsByTagName('head')[0].appendChild(js);
       }(document));
    </script>      
    <div class="fb-login-button" data-scope="email,user_groups,publish_stream">Login with Facebook</div>
    </div>
</div>
</body>
</html>
