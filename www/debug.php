<?php


define('__WEB_ROOT__', $_SERVER['SERVER_ROOT']);
define('__COMPOSER_DIR__', __WEB_ROOT__.'/library/vendor');

set_include_path(get_include_path().PATH_SEPARATOR.__COMPOSER_DIR__);
require_once __COMPOSER_DIR__.'/autoload.php';
// require_once(".config.inc.php");
define("ERROR_LOG_FILE", __WEB_ROOT__.'/debug.log');

?>
<html>
<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script>

jQuery(document).ready(function(){
jQuery('body,html').animate({scrollTop: 1000000}, 800);
})

</script>
</head>
<body>    
<div style="background-color: #CCCCFF;     padding: 10px 15px; font-family: monospace; font-size: 150%; ">
    
<?php 
    $log_file=ERROR_LOG_FILE;
    if (($handle = fopen($log_file, "r")) !== FALSE)
    {
		$pos = -2; // Skip final new line character (Set to -1 if not present)
		$idx=0;
	    while (($str_data = fgets($handle, 5000)) !== FALSE)
        {
			$idx++;            
            echo  $str_data;
        }			
    }
    
      
    
    ?>
    </div>
</body>
</html>