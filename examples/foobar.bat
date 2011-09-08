@ECHO OFF

REM ****************************************************************************
REM *
REM * ManiaLib - Lightweight PHP framework for Manialinks
REM *
REM * This source file is subject to the LGPL License 3. It is available through 
REM * the world-wide-web at this URL:  http://www.gnu.org/licenses/lgpl.html
REM *
REM * Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
REM *
REM * Usage:
REM * Rename the file to "script_name.bat" and run it.
REM * It will try to execute "php.exe script_name.php".
REM *
REM ****************************************************************************

SET filename=%0
FOR %%F IN (%filename%) DO SET dirname=%%~dpF
SET filename=%~nx0
SET filename=%filename:.bat=%
php.exe "%dirname%%filename%.php"

PAUSE