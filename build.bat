@echo off
setlocal EnableDelayedExpansion

set build_dir=D:\development\cwp_app
set old_dir=D:\development\media
set bin_dir=%build_dir%\bin
set web_dir=%build_dir%\public
set updateDir=%build_dir%\public\www\updater
set zip_dir=%updateDir%\versions


set build_bin=%bin_dir%\builder.exe
set patch_bin=%bin_dir%\patcher.exe
set build_options=%bin_dir%\sample.cfg

set version_file=%updateDir%\version.txt
set current_file=%updateDir%\current.txt


FOR /F %%i IN ( %current_file%) DO set string=%%i

for /F "tokens=1,2,3 delims=. " %%a in ("%string%") do (
   set number=%%a%%b%%c
   set /a number+=1
)


set versionNumber=%number:~0,1%.%number:~1,1%.%number:~2,1%


set zip_file=update_%versionNumber%.zip
set patchzip=%zip_dir%\%zip_file%

if exist %patchzip%  echo Error: Already did Update>&2&exit /b 1

if not exist %zip_dir% mkdir %zip_dir%
echo Updating to version %versionNumber% 

echo %versionNumber% >> %version_file%
echo %versionNumber% > %current_file%

%build_bin% -N %build_dir% -O %old_dir% -C %build_options% -P %patchzip%

chdir /d %web_dir%

git add %patchzip% %version_file% %current_file%
git commit -m "Updates to v %versionNumber%"
git push

chdir /d %old_dir%
%patch_bin% -O %old_dir% -P %patchzip%