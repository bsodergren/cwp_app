<?php
/**
 * CWP Media tool
 */

$drive_letter = substr($argv[1], 0, 2);
$target_dir = str_replace($drive_letter, '', $argv[1]);
$bin_dir = $target_dir.\DIRECTORY_SEPARATOR.'bin';
$config_ini = $argv[1].\DIRECTORY_SEPARATOR.'public'.\DIRECTORY_SEPARATOR.'config.ini';
$json_settings = $argv[1].\DIRECTORY_SEPARATOR.'settings.json';
$php_ini_file = $argv[1].\DIRECTORY_SEPARATOR.'php'.\DIRECTORY_SEPARATOR.'php.ini';

$str = <<<EOD
[application]
name=CWP Media Creator
debug=false
[email]
enable=1
imap={imap.gmail.com:993/imap/ssl}
username=bjorn.sodergren@gmail.com
password=lhdezcpzgxpuultg
folder=CWP
[db]
type=sqlite
dbname=cwp
host=
username=
password=
[server]
root_dir=$target_dir
bin_dir=$bin_dir
#project_root=\public
web_root=\public\www
url_root=
file_root=\\files\media
EOD;

file_put_contents($config_ini, $str);

$json_str = <<<EOD
{
    "application": {
        "single_instance_guid": "",
        "dpi_aware": true
    },
    "debugging": {
        "show_console": false,
        "subprocess_show_console": false,
        "log_level": "info",
        "log_file": ""
    },
    "main_window": {
        "title": "Media Flag Creator",
        "icon": "",
        "default_size": [1280, 800],
        "minimum_size": [1024, 480],
        "maximum_size": [0, 0],
        "disable_maximize_button": false,
        "center_on_screen": true,
        "start_maximized": false,
        "start_fullscreen": false,
        "always_on_top": false,
        "minimize_to_tray": false,
        "minimize_to_tray_message": "Minimized to tray"
    },
    "popup_window": {
        "icon": "",
        "fixed_title": "Media Popup",
        "center_relative_to_parent": true,
        "default_size": [800, 600]
    },
    "web_server": {
        "listen_on": ["127.0.0.1", 0],
        "www_directory": "public/www",
        "index_files": ["index.php"],
        "cgi_interpreter": "php/php-cgi.exe",
        "cgi_extensions": ["php"],
        "cgi_temp_dir": "",
        "404_handler": "/error.php",
        "hide_files": []
    },
    "chrome": {
        "log_file": "",
        "log_severity": "default",
        "cache_path": "",
        "external_drag": true,
        "external_navigation": true,
        "reload_page_F5": true,
        "devtools_F12": true,
        "remote_debugging_port": 0,
        "command_line_switches": {},
        "enable_downloads": true,
        "context_menu": {
            "enable_menu": true,
            "navigation": true,
            "print": true,
            "view_source": true,
            "open_in_external_browser": true,
            "devtools": true
        }
    }
}
EOD;

file_put_contents($json_settings, $json_str);

$php_ini_str = <<<EOD
; Extensions
extension_dir = "ext/"
extension=php_curl.dll
extension=php_mysqli.dll
extension=php_openssl.dll
extension=php_pdo_sqlite.dll
extension=php_sqlite3.dll
extension=php_com_dotnet.dll
extension=php_zip.dll
extension=php_fileinfo.dll
extension=php_gd.dll
extension=php_mbstring.dll
extension=php_imap
zlib.output_compression = Off

; Date
date.timezone=America/Chicago

; Errors
error_reporting= E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING & ~E_NOTICE
display_errors=On
display_startup_errors=On
log_errors=Off
; error_log = php_errors.log

report_memleaks=On
html_errors = On
error_prepend_string = "<pre><span style='color: #ff0000'>"
error_append_string = "</span></pre><br>"


;report_zend_debug=On
;zend_extension=xdebug

; General
short_open_tag = On
ignore_user_abort = Off
implicit_flush = Off
output_buffering = Off
default_charset = "UTF-8"

; Execution time
max_execution_time=3600

; Memory
memory_limit=2G

; File uploads
; "post_max_size" must be equal or bigger than "upload_max_filesize"
max_file_uploads=20
upload_max_filesize=2048M
post_max_size=2048M

EOD;

file_put_contents($php_ini_file, $php_ini_str);
