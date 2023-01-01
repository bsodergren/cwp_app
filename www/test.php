<?php
require_once(".config.inc.php");
define('TITLE', "Test page");

include __LAYOUT_HEADER__;
$form = new Formr\Formr('','hush');
$html = $form->open("", '', __URL_HOME__ . "/action.php", 'post', '', $hidden);

//$html = $form->text('name');


echo $html;
?>


<?php include __LAYOUT_FOOTER__; ?>