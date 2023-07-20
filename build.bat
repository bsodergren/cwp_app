@echo off

set build_bin=D:\development\cwp_app\builder.exe
set build_dir=D:\development\cwp_app
set old_dir=D:\media
set build_options=D:\development\cwp_app\sample.cfg
set zip_diff=D:\development\cwp_app\public\www\updater\Latest.zip

%build_bin% -N %build_dir% -O %old_dir% -C %build_options% -P %zip_diff% 