@echo off

SET Source=%cd%
echo directory to install Media 
set /p var=Enter destination folder for /MediaCreator (Default %USERPROFILE%/Desktop) Use "'s if there are spaces" || SET var=%USERPROFILE%\Desktop
SET Target=%var%\MediaCreator
if NOT EXIST %Target% (mkdir %Target%)

SetLocal EnableDelayedExpansion

Set "exFList=%Source%\scripts\excludeFiles.txt"
Set "exDList=%Source%\scripts\excludeDir.txt"
Set "xFiles="
Set "xDirs="

For /F UseBackQDelims^=^ EOL^= %%D In ("%exDList%"
) Do If Not Defined xDirs (Set xDirs="%Source%\%%~D") Else Set xDirs=!xDirs! "%Source%\%%~D"

For /F UseBackQDelims^=^ EOL^= %%F In ("%exFList%"
) Do If Not Defined xFiles (Set xFiles="%Source%\%%~F") Else Set xFiles=!xFiles! "%Source%\%%~F"

REM echo robocopy %Source%  %Target% /v /mir /XF %xFiles% /XD %xDirs%

robocopy %Source%  %Target% /V /mir /XF %xFiles% /XD %xDirs%

REM /L /NP
REM /np >nul 2>&1

if EXIST "%Source%/scripts/edit.php" (%Source%/php/php -n -f %Source%/scripts/edit.php "%Target%")
