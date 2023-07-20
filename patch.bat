@echo off

set patchbin=%cd%\.bin\patcher.exe
set olddir=%cd%

::set patchzip=%cd%\public\www\updater\Latest.zip
set patchzip=D:\development\cwp_app\public\www\updater\Latest.zip

%patchbin% -O %olddir% -P %patchzip%
