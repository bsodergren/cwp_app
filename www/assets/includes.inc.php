<?php
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;


require_once __INC_CORE_DIR__ . "/html_func.inc.php";


if (!file_exists(__SQLITE_DATABASE__))
{
    output("Creating Database in ".__SQLITE_DATABASE__);

    require_once __ASSETS_DIR__ . '/config/firstrun.inc.php';
    
    $file         = '/config/firstrun.inc.php';
    $replacement  = '<?php';
    $replacement .= ' #skip';
    $__db_string  = FileSystem::read(__ASSETS_DIR__.$file);
    $__db_write   = str_replace('<?php', $replacement, $__db_string);
    FileSystem::write(__INC_CORE_DIR__.'/'.$file, $__db_write);

}

$upddate_file = __ASSETS_DIR__ . '/config/update.inc.php';
if (file_exists($upddate_file))
{
    require_once $upddate_file;
}

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
    }
    $line = '';
}

$template = new Template();
