<?php
use Nette\Utils\FileSystem;

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

//define('__PROJECT_ROOT__', ".");
define('__PROJECT_ROOT__', $_SERVER['SERVER_ROOT']."/..");
define('__WEB_ROOT__', ".");

define('__ROOT_BIN_DIR__', __PROJECT_ROOT__."/bin");


/*
 * Default constants for include path structure.
 *
 */
define('__ASSETS_DIR__', __WEB_ROOT__.'/assets');
define('__INC_CORE_DIR__', __ASSETS_DIR__.'/core');
define('__INC_CLASS_DIR__', __ASSETS_DIR__.'/class');
define('__INC_PDF_DIR__', __ASSETS_DIR__.'/pdf_parser');
define('__INC_XLSX_DIR__', __ASSETS_DIR__.'/xlsx_parser');
define('__COMPOSER_DIR__', __WEB_ROOT__.'/library/vendor');

define('__TEMP_DIR__', sys_get_temp_dir());

define('__SQLITE_DIR__', __PROJECT_ROOT__.'/database');
define('__SQLITE_DATABASE__', __SQLITE_DIR__.'/cwp_sqlite.db');
define('__DATABASE_DSN__', 'sqlite:'.__SQLITE_DATABASE__);


/*
 * Layout path structure in assets directory.
 */

define('__LAYOUT_DIR__', '/assets/layout');
define('__LAYOUT_ROOT__', __WEB_ROOT__.__LAYOUT_DIR__);
define('__TEMPLATE_DIR__', __LAYOUT_ROOT__.'/template');
define('__LATTE_TEMPLATE__', __TEMPLATE_DIR__.'/latte');

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
use Tracy\Debugger;

Debugger::enable();
Debugger::$dumpTheme    = 'dark';
Debugger::$showLocation = (Tracy\Dumper::LOCATION_CLASS | Tracy\Dumper::LOCATION_LINK);
Debugger::$showBar = 1;

require_once __INC_CORE_DIR__ . "/require_files.inc.php";



define("__XLSX_EXTRAS__", 0);





require_once __ASSETS_DIR__ . "/settings.inc.php";

if (defined('__USE_LOCAL_XLSX__'))
{
    if (__USE_LOCAL_XLSX__ == 1) {
        if (
            defined('__USER_XLSX_DIR__') &&
            is_dir(__USER_XLSX_DIR__)
        ) {
            define("__FILES_DIR__", __USER_XLSX_DIR__ . "/files");
        }
    }
}

if(!defined('__FILES_DIR__')){
    define("__FILES_DIR__", __WEB_ROOT__ . "/files");
}

logger("Default file dir", __FILES_DIR__);

define("__PDF_UPLOAD_DIR__", "/pdf" );
define("__ZIP_FILE_DIR__", "/zip" );
define("__XLSX_DIRECTORY__", "/xlsx" );

/*
FileSystem::createDir(__PDF_UPLOAD_DIR__);
FileSystem::createDir(__ZIP_FILE_DIR__);
FileSystem::createDir(__XLSX_DIRECTORY__);
*/

define('__lang_bindery', "Bindery");