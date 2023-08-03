@echo off
setlocal EnableDelayedExpansion
set script_path=%~dp0
call %script_path%/paths.bat

chdir %web_dir%
echo %php_exec% -f %composer% update
