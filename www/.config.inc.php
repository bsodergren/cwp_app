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

define('__WEB_ROOT__', $_SERVER['SERVER_ROOT']);
define('__PROJECT_ROOT__', $_SERVER['SERVER_ROOT'] . "/..");
//define('__WEB_ROOT__', ".");

define('__ROOT_BIN_DIR__', __PROJECT_ROOT__ . "/bin");


/*
 * Default constants for include path structure.
 *
 */
define('__ASSETS_DIR__', __WEB_ROOT__ . '/assets');
define('__INC_CORE_DIR__', __ASSETS_DIR__ . '/core');
define('__INC_CLASS_DIR__', __ASSETS_DIR__ . '/class');
define('__INC_PDF_DIR__', __ASSETS_DIR__ . '/pdf_parser');
define('__INC_XLSX_DIR__', __ASSETS_DIR__ . '/xlsx_parser');
define('__PROCESS_DIR__', __ASSETS_DIR__ . '/form_processor');
define('__UPDATES_DIR__', __ASSETS_DIR__ . "/config/updates");
define('__COMPOSER_DIR__', __WEB_ROOT__ . '/library/vendor');
define('__ERROR_LOG_DIRECTORY__', __WEB_ROOT__. '/logs');

define('__TEMP_DIR__', sys_get_temp_dir());

define('__SQLITE_DIR__', __PROJECT_ROOT__ . '/.database');
define('__SQLITE_DATABASE__', __SQLITE_DIR__ . '/cwp_sqlite.db');
define('__DATABASE_DSN__', 'sqlite:' . __SQLITE_DATABASE__);


/*
 * Layout path structure in assets directory.
 */

define('__LAYOUT_DIR__', '/assets/layout');
define('__LAYOUT_ROOT__', __WEB_ROOT__ . __LAYOUT_DIR__);
define('__TEMPLATE_DIR__', __LAYOUT_ROOT__ . '/template');
define('__LATTE_TEMPLATE__', __TEMPLATE_DIR__ . '/latte');

define('__LAYOUT_HEADER__', __LAYOUT_ROOT__ . '/header.php');
define('__LAYOUT_NAVBAR__', __LAYOUT_ROOT__ . '/navbar.php');
define('__LAYOUT_FOOTER__', __LAYOUT_ROOT__ . '/footer.php');


/*
 * URL defaults.
 */
define('__URL_PATH__', '');
define('__URL_HOME__', 'http://' . $_SERVER['HTTP_HOST'] . __URL_PATH__);
define('__URL_LAYOUT__', __URL_HOME__ . __LAYOUT_DIR__);

set_include_path(get_include_path() . PATH_SEPARATOR . __COMPOSER_DIR__);
require_once __COMPOSER_DIR__ . '/autoload.php';


require_once __ASSETS_DIR__ . "/includes.inc.php";

require_once __ASSETS_DIR__ . "/settings.inc.php";

use Tracy\Debugger;

if (settingTrue('__SHOW_TRACY__')) {
        Debugger::enable();
        Debugger::$dumpTheme    = 'dark';
        //        Debugger::$editor = null;.
        //        Debugger::$strictMode =  ~E_DEPRECATED | E_WARNING;
        Debugger::$showLocation = (Tracy\Dumper::LOCATION_CLASS | Tracy\Dumper::LOCATION_LINK);
        Debugger::$showBar = 1;
}

if (settingTrue('__SHOW_DEBUG_PANEL__')) {
        $onLoad = "onLoad=\"popup('/debug.php', 'logs',800,400)\"";

}

if (!function_exists('dump')) {
    function dump($var)
    {
        return 0;
    }
}

if (!function_exists('bdump')) {
    function bdump($bdump)
    {
        return 0;
    }
}

define("__MEDIA_FILES_DIR__", "/Media Load Flags");

if (settingTrue('__USE_LOCAL_XLSX__')) {
        if (
            settingTrue('__USER_XLSX_DIR__') &&
            is_dir(__USER_XLSX_DIR__)
        ) {
            define("__FILES_DIR__", __USER_XLSX_DIR__ . __MEDIA_FILES_DIR__);
        }
    
}

if (!defined('__FILES_DIR__')) {
    define("__FILES_DIR__", __PROJECT_ROOT__ . __MEDIA_FILES_DIR__);
}



define("__XLSX_EXTRAS__", 0);

define("__PDF_UPLOAD_DIR__", "/pdf");
define("__ZIP_FILE_DIR__", "/zip");
define("__XLSX_DIRECTORY__", "/xlsx");

define('__lang_bindery', "Bindery");
