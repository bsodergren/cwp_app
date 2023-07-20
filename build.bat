@echo off
setlocal EnableDelayedExpansion

if "%~1" equ "" echo Error: Insufficient arguments>&2&exit /b 1

echo %1
set build_bin=D:\development\cwp_app\.bin\builder.exe
set patch_bin=D:\development\cwp_app\.bin\patcher.exe

set web_dir=D:\development\cwp_app\public

set version_file=D:\development\cwp_app\public\www\updater\version.txt
set current_file=D:\development\cwp_app\public\www\updater\current.txt
set build_dir=D:\development\cwp_app
set old_dir=D:\development\media
set build_options=D:\development\cwp_app\.bin\sample.cfg
set zip_dir=D:\development\cwp_app\public\www\updater\versions
set zip_file=update_%1.zip
set patchzip=%zip_dir%\%zip_file%

if exist %patchzip%  echo Error: Already did Update>&2&exit /b 1

if not exist %zip_dir% mkdir %zip_dir%

echo %1 >> %version_file%
echo %1 > %current_file%

%build_bin% -N %build_dir% -O %old_dir% -C %build_options% -P %patchzip%

chdir /d %web_dir%

git add %patchzip% %version_file% %current_file%
git commit -m "Updates to v %1"
git push

chdir /d %old_dir%
%patch_bin% -O %old_dir% -P %patchzip%