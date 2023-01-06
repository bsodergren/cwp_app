<?php
require_once(".config.inc.php");
define('TITLE', "Test page");

include __LAYOUT_HEADER__;

echo Media::get_exists("zip",1);

echo Media::get_exists("xlsx",1);

include __LAYOUT_FOOTER__; ?>