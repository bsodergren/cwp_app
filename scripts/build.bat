@echo off
setlocal EnableDelayedExpansion

set build_dir=D:\development\cwp_app
set old_dir=D:\development\media
set bin_dir=%build_dir%\bin
set web_dir=%build_dir%\public
set updateDir=%build_dir%\public\AppUpdates


set build_options=%build_dir%\scripts\sample.cfg

set current_file=%web_dir%\current.txt
set json_file=%updateDir%\update.json

FOR /F %%i IN ( %current_file%) DO set string=%%i

for /F "tokens=1,2,3 delims=. " %%a in ("%string%") do (
   set number=%%a%%b%%c
   set /a number+=1
)


set versionNumber=%number:~0,1%.%number:~1,1%.%number:~2,1%

echo %versionNumber% > %current_file%

set zip_file=%versionNumber%.zip
set patchzip=%updateDir%\%zip_file%


if EXIST "%build_dir%/scripts/update.php" (%build_dir%/php/php  -f %build_dir%/scripts/update.php "%build_dir%" "%versionNumber%")

 chdir /d %web_dir%

  git add  %current_file% %json_file%  %patchzip%
  git commit -m "Updates to v %versionNumber%"
  git push
