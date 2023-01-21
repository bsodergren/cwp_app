<?php

$source_dir = $argv[1];
$target_dir = $argv[2];

$php_file = $source_dir . "/.php/php.ini";
$target_php_ini = $target_dir . "/.php/php.ini";


if (file_exists($target_php_ini)) {
    unlink($target_php_ini);
}

$file_contents = file_get_contents($php_file);

$file_contents = str_replace("zend_extension", ";zend_extension", $file_contents);
$file_contents = str_replace("xdebug", ";xdebug", $file_contents);
file_put_contents($target_php_ini, $file_contents);
