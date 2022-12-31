<?php 
require_once(".config.inc.php");

define('TITLE', "404 not found");
include __LAYOUT_HEADER__;
?>

<main role="main" class="container">
<?php echo $_SERVER['REQUEST_URI']; ?> does not exist, sorry.
</main>
<?php include __LAYOUT_FOOTER__; ?>