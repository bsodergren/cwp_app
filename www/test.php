<?php

require_once(".config.inc.php");
$latte = new Latte\Engine;
$latte->setTempDirectory(__TEMP_DIR__);

$latte->render(__LATTE_TEMPLATE__.'/template.latte');//, $params);
//define('TITLE', "Form Editor");
//include __LAYOUT_HEADER__;

?>
