::copyFlat sourcePath  TargetPath
@echo off

set patchbin=%cd%\patcher.exe
set olddir=%cd%\public
::set patchzip=%cd%\Latest.zip
set patchzip=D:\development\cwp_app\Latest.zip

%patchbin% -O %olddir% -P %patchzip% -q
