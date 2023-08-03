@echo off
setlocal EnableDelayedExpansion

set build_dir=D:\development\cwp_app
set old_dir=D:\development\media
set bin_dir=%build_dir%\bin
set web_dir=%build_dir%\public


 chdir %web_dir%
echo %cd% 

set composer=%bin_dir%\composer.phar

echo %build_dir%/php/php  -f %composer% update
