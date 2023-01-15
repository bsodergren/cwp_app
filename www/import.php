<?php

use Nette\Utils\FileSystem;

require_once '.config.inc.php';
define('TITLE', 'Media Import');
$template = new Template();

require_once __LAYOUT_HEADER__;

$template->render('import/main',[]);

require_once __LAYOUT_FOOTER__;
