@echo off

echo %UserProfile%

SET Source=D:\development\cwp_app
echo directory to install Media 
set /p var=Enter destination folder for /MediaCreator (Default %USERPROFILE%/Desktop) Use "'s if there are spaces" || SET var=%USERPROFILE%\Desktop
SET Target=%var%\MediaCreator
if NOT EXIST %Target% (mkdir %Target%)



xcopy %Source% %Target% /E /Y /EXCLUDE:%Source%\exclude.txt