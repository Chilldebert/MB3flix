@echo off
::Path to php.exe
set php=C:\\xampp\php\php.exe 
::Path to MB3flix scripts folder
set path=C:\\xampp\htdocs\df\scripts\

:START
echo ________________________________________________________________
echo ___________________Caching-Script for MB3flix___________________
echo ________________________________________________________________
echo.
echo [1]Cache all movies (slow!)
echo [2]Update latest 100 movies
echo [3]Cache all TV shows (slow!)
echo [4]Update TV Shows
echo [5]Cache all people (very slow!)
echo [6]Update people
echo [7]Exit
echo.

set asw=0
set /p asw="Choose: "

if %asw%==1 %php% %path%cachemovies.php
if %asw%==2 %php% %path%updatemovies.php
if %asw%==3 %php% %path%cacheseries.php
if %asw%==4 %php% %path%updateseries.php
if %asw%==5 %php% %path%cachepeople.php
if %asw%==6 %php% %path%updatepeople.php
if %asw%==7 goto END
goto START
:END
