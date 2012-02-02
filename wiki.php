<?php include_once( 'includes/header.php' ); ?>
<?php $uid = $_SESSION['uid']; ?>

<?php StatsManager::poll( "view", "wiki", "", "", "", $uid ); ?>

<script type="text/javascript">
function calcHeight()
{
  var height=
    document.getElementById('frame').contentWindow.
      document.body.scrollHeight + 60;

  document.getElementById('frame').height=
      height;
}
</script>

<iframe 
   src="wiki" // location of external resource 
   width="100%"              // width of iframe should match the width of containing div
   marginwidth="0"          // width of iframe margin
   marginheight="0"         // height of iframe margin   
   frameborder="no"         // frame border preference
   scrolling="no"          // instructs iframe to scroll overflow content
   onLoad="calcHeight();"
   style="
      border-style: solid;  // border style
      border-color: #333;   // border color
      border-width: 0px;    // border width
      background: #FFF;     // background color
   "
   name="frame"
   id="frame">
</iframe>

</div>

<?php include_once( 'includes/footer.php' ); ?>
