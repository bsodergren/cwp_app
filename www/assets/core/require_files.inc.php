<?php

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;

function logger($text,$var='')
{
    bdump($var, $text);
}

function toint($string)
{
    
    $string_ret = str_replace(",","",$string);
    return $string_ret;
}
function first_run()
{
        $file         = 'firstrun.inc.php';
        $replacement  = '<?php';
        $replacement .= ' #skip';
        $__db_string  = FileSystem::read(__INC_CORE_DIR__.'/'.$file);
        $__db_write   = str_replace('<?php', $replacement, $__db_string);
        FileSystem::write(__INC_CORE_DIR__.'/'.$file, $__db_write);

}//end first_run()


$const = get_defined_constants(true);

foreach ($const['user'] as $name => $value) {
    if (Strings::contains($name, '_INC_')) {
        if ($all = opendir($value)) {
            while ($file = readdir($all)) {
                if (!is_dir($value.'/'.$file)) {
                    if (preg_match('/(php)$/', $file)) {
                        $require_fileArray[] = filesystem::normalizePath($value.'/'.$file);
                    }//end if
                }//end if
            }//end while

            closedir($all);
        }//end if
    }//end if
}//end foreach


foreach ($require_fileArray as $include) {
    $f    = fopen($include, 'r');
    $line = fgets($f);
    fclose($f);
    if (strpos($line, '#skip') == false) {
        include_once $include;
        if (basename($include) == 'firstrun.inc.php') {
            first_run();
        }
    }

    $line = '';
}
