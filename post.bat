@echo off
setlocal EnableDelayedExpansion
set root=%~1

set phpexec="%root%\php\php.exe"
::echo %phpexec%
%phpexec% "%root%\edit.php" "%root%"

pause

exit /b