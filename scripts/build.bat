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

@REM set zip_file=%versionNumber%.zip
@REM set patchzip=%app_updatesr%\%zip_file%


@REM if EXIST "%update_script%" (%php_exec%  -f %update_script% "%update_dir%" "%versionNumber%")

chdir /d %web_dir%

git add  .
git commit -m "Updates to v %versionNumber%"
git push
git checkout -b %versionNumber% develop
git push --set-upstream origin %versionNumber%
git checkout main
git merge %versionNumber%
git tag -a %versionNumber% -m "%versionNumber% public release" main
git push --tags
git push
git checkout develop
git merge %versionNumber%
git push
git branch -d %versionNumber%
