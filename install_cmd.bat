@echo off

echo %UserProfile%

SET Source=D:\development\cwp_app
echo directory to install Media 
set /p var=Enter destination folder for /MediaCreator (Default %USERPROFILE%/Desktop) Use "'s if there are spaces" || SET var=%USERPROFILE%\Desktop
SET Target=%var%\MediaCreator
if NOT EXIST %Target% (mkdir %Target%)

SetLocal EnableDelayedExpansion

Set "exFList=%Source%\excludeFiles.txt"
Set "exDList=%Source%\excludeDir.txt"
Set "xFiles="
Set "xDirs="

For /F UseBackQDelims^=^ EOL^= %%A In ("%exDList%"
) Do If Not Defined xDirs (Set xDirs="%%~A") Else Set xDirs=!xDirs! "%%~A"

For /F UseBackQDelims^=^ EOL^= %%A In ("%exFList%"
) Do If Not Defined xFiles (Set xFiles="%%~A") Else Set xFiles=!xFiles! "%%~A"

robocopy %Source%  %Target% /v /mir /XF %xFiles% /XD %xDirs% /np >nul 2>&1
php -n -f edit.php "%Source%" "%Target%"

