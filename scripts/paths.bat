set app_dir=D:\development\cwp_app
set bin_dir=%app_dir%\bin
set update_dir=%app_dir%
set web_dir=%app_dir%\public
set app_updatesr=%app_dir%\public\AppUpdates


set build_options=%app_dir%\scripts\sample.cfg

set current_file=%web_dir%\current.txt
set json_file=%app_updatesr%\update.json

set update_script=%app_dir%/scripts/update.php
set composer=%bin_dir%\composer.phar
set php_exec=%build_dir%/php/php 