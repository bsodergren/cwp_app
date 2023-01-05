<?php

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;


require_once __INC_CORE_DIR__ . "/html_func.inc.php";

$_runone_file = __ASSETS_DIR__ . '/config/firstrun.inc.php';
$_update_file = __ASSETS_DIR__ . '/config/update.inc.php';
$refresh=false;

if (!file_exists(__SQLITE_DATABASE__)) {
    output("Creating Database in " . __SQLITE_DATABASE__);
    require_once $_runone_file;
    skipFile($_runone_file);
    define('__FIRST_RUN__',true);
}

require_once $_update_file;



if($refresh == true)
{
   echo JavaRefresh("index.php",5);
    ob_flush();
}

$refresh = false;
$storage = new Nette\Caching\Storages\FileStorage(sys_get_temp_dir());
$connection = new Nette\Database\Connection(__DATABASE_DSN__);
$structure = new Nette\Database\Structure($connection, $storage);
$conventions = new Nette\Database\Conventions\DiscoveredConventions($structure);
$explorer = new Nette\Database\Explorer($connection, $structure, $conventions, $storage);

/*
$const = get_defined_constants(true);
$loader = new Nette\Loaders\RobotLoader;
foreach ($const['user'] as $name => $value) {
    if (Strings::contains($name, '_INC_')) {
        $loader->addDirectory($value);
    }
}
$loader->register();
*/

$const = get_defined_constants(true);

foreach ($const['user'] as $name => $value) {
    if (Strings::contains($name, '_INC_')) {
        if ($all = opendir($value)) {
            while ($file = readdir($all)) {
                if (!is_dir($value . '/' . $file)) {
                    if (preg_match('/(php)$/', $file)) {
                        $require_fileArray[] = filesystem::normalizePath($value . '/' . $file);
                    } //end if
                } //end if
            } //end while

            closedir($all);
        } //end if
    } //end if
} //end foreach


foreach ($require_fileArray as $include) {
    if (!check_skipFile($include)) {
        require_once $include;
    }
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
    unset($file);
    unset($filename);
    unset($k);
    unset($error_array);
*/