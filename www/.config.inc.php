<?php

/**
 *  Basic constants for application that are displayed in the output
 */
define('APP_NAME', 'Media Import');
define('APP_ORGANIZATION', 'KLiK');
define('APP_OWNER', 'bjorn');
define('APP_DESCRIPTION', 'Embeddable PHP Login System');

/*
 * base directory and script name.
 */
define('__SCRIPT_NAME__', basename($_SERVER['PHP_SELF'], '.php'));
define('__PROJECT_ROOT__', $_SERVER['SERVER_ROOT']);

/*
 * Default constants for include path structure.
 *
 */
define('__ASSETS_DIR__', __PROJECT_ROOT__.'/assets');
define('__INC_CORE_DIR__', __ASSETS_DIR__.'/core');
define('__INC_CLASS_DIR__', __ASSETS_DIR__.'/class');
define('__INC_PDF_DIR__', __ASSETS_DIR__.'/pdf_parser');
define('__INC_XLSX_DIR__', __ASSETS_DIR__.'/xlsx_parser');
define('__COMPOSER_DIR__', __PROJECT_ROOT__.'/library/vendor');

define('__SQLITE_DIR__', __ASSETS_DIR__.'/database');
define('__SQLITE_DATABASE__', __SQLITE_DIR__.'/cwp_sqlite.db');
define('__DATABASE_DSN__', 'sqlite:'.__SQLITE_DATABASE__);


/*
 * Layout path structure in assets directory.
 */

define('__LAYOUT_DIR__', '/assets/layout');
define('__LAYOUT_ROOT__', __PROJECT_ROOT__.__LAYOUT_DIR__);
define('__TEMPLATE_DIR__', __LAYOUT_ROOT__.'/template');

define('__LAYOUT_HEADER__', __LAYOUT_ROOT__.'/header.php');
define('__LAYOUT_NAVBAR__', __LAYOUT_ROOT__.'/navbar.php');
define('__LAYOUT_FOOTER__', __LAYOUT_ROOT__.'/footer.php');

/*
 * URL defaults.
 */
define('__URL_PATH__', '');
define('__URL_HOME__', 'http://'.$_SERVER['HTTP_HOST'].__URL_PATH__);
define('__URL_LAYOUT__', __URL_HOME__.__LAYOUT_DIR__);

set_include_path(get_include_path().PATH_SEPARATOR.__COMPOSER_DIR__);
require_once __COMPOSER_DIR__.'/autoload.php';


/*
 * Tracy Debugger
 */

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Tracy\Debugger;

Debugger::enable();
Debugger::$dumpTheme    = 'dark';
Debugger::$showLocation = (Tracy\Dumper::LOCATION_CLASS | Tracy\Dumper::LOCATION_LINK);



function first_run()
{
        $file = 'firstrun.inc.php';
        $replacement  = '<?php';
        $replacement .= ' #skip';
        $__db_string  = FileSystem::read(__INC_CORE_DIR__.'/'.$file);
        $__db_write   = str_replace('<?php', $replacement, $__db_string);
        FileSystem::write(__INC_CORE_DIR__.'/'.$file, $__db_write);
    
}



// require_once __INC_CLASS_DIR__ . "/strings.class.php";
/*
 *  Include all files from the include directories.
 *
 *
 */


$const = get_defined_constants(true);
foreach ($const['user'] as $name => $value) {
    if (Strings::contains($name, '_INC_')) {
        if ($all = opendir($value)) {
            while ($file = readdir($all)) {
                if (!is_dir($value.'/'.$file)) {
                    if (preg_match('/(php)$/', $file)) {
                        $f    = fopen($value.'/'.$file, 'r');
                        $line = fgets($f);
                        fclose($f);
                        if (strpos($line, '#skip') == false)
                        {
                            include_once $value.'/'.$file;
                            if ($file == 'firstrun.inc.php') {
                                first_run();
                            }
                        }
                    }//end if
                }//end if
            }//end while

            closedir($all);
        }//end if
    }//end if
}//end foreach
