<?php

define('__SCRIPT_NAME__', basename($_SERVER['PHP_SELF'], '.php'));

define('__WEB_ROOT__', $_SERVER['SERVER_ROOT']);
define('__COMPOSER_DIR__', __WEB_ROOT__.'/library/vendor');
define('__ASSETS_DIR__', __WEB_ROOT__ . '/assets');
define('__INC_CORE_DIR__', __ASSETS_DIR__ . '/core');
define('__ERROR_LOG_DIRECTORY__', __WEB_ROOT__. '/logs');

require_once __INC_CORE_DIR__ . "/html_func.inc.php";


set_include_path(get_include_path().PATH_SEPARATOR.__COMPOSER_DIR__);
require_once __COMPOSER_DIR__.'/autoload.php';
// require_once(".config.inc.php");
//define("ERROR_LOG_FILE", __WEB_ROOT__.'/debug.log');

$errorArray = getErrorLogs();

foreach($errorArray as $k =>$file){
    $key = str_replace(".","_",basename($file));
    $logArray[$key] = $file;
}


?>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>    
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

<script type="text/javascript">
$(function() {

$(".btn").on("click", function() {
  //hide all sections
  $(".content-section").hide();
  //show the section depending on which button was clicked
  $("#" + $(this).attr("data-section")).show();
});



});

$(function() {

    $(window).on("load", function() {
  //hide all sections
  $(".content-section").hide();
  //show the section depending on which button was clicked
  
  $("#default_log").show();
});
});


jQuery(document).ready(function(){
jQuery('body,html').animate({scrollTop: 1000000}, 800);
})

</script>
<style>
    .content-section {
     display: none;
    }   
</style>
</head>
<body>
<div class="btn-group btn-group-lg">
    <?php
    foreach($logArray as $key => $file){
        echo '<button type="button" data-section="'.$key.'" class="btn btn-primary segmentedButton ">'.$key.'</button>'."\n";
    }
    ?>
</div>
    
<?php 

foreach($logArray as $key => $file){
 

    echo '<div class="content-section" id="'.$key.'">';
    echo '<h1>'.$key.' </h1>';
    echo '<p>';
    
    if (($handle = fopen($file, "r")) !== FALSE)
    {
		$pos = -2; // Skip final new line character (Set to -1 if not present)
		$idx=0;
	    while (($str_data = fgets($handle, 5000)) !== FALSE)
        {
			$idx++;            
            echo  $str_data;
        }
        fclose($handle)			;
    }

    echo '</p></div>';
    
}      
    
    ?>
    
</body>
</html>