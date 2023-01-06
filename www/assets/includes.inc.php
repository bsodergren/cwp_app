<?php

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;

$const = get_defined_constants(true);

$include_array = [];

foreach ($const['user'] as $name => $value) {
    if (Strings::contains($name, '_INC_')) {
       $include_array = array_merge($include_array,mediaUpdate::get_filelist($value, 'php', 1));
    } //end if
} //end foreach

foreach ($include_array as $required_file) {
        require_once $required_file;
}

$template = new Template();



/*
if (__SCRIPT_NAME__ != 'debug') {
    $error_array =getErrorLogs();
    foreach($error_array as $k => $file){

        $filename = basename($file);        
        logger("=============".__SCRIPT_NAME__."============================",'',$filename);
    }
}
*/
    unset($file);
    unset($filename);
    unset($k);
    unset($error_array);
