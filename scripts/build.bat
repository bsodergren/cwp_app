@echo off
setlocal EnableDelayedExpansion

set script_path=%~dp0
call %script_path%/paths.bat


FOR /F %%i IN ( %current_file%) DO set string=%%i

for /F "tokens=1,2,3 delims=. " %%a in ("%string%") do (
   set number=%%a%%b%%c
   set /a number+=1
)


set versionNumber=%number:~0,1%.%number:~1,1%.%number:~2,1%

echo %versionNumber% > %current_file%

set zip_file=%versionNumber%.zip
set patchzip=%app_updatesr%\%zip_file%


if EXIST "%update_script%" (%php_exec%  -f %update_script% "%update_dir%" "%versionNumber%")

 chdir /d %web_dir%

  git add  %current_file% %json_file%  %patchzip%
  git commit -m "Updates to v %versionNumber%"
  git push
