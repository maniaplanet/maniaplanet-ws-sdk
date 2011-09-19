<?php
/**
 * Maniaplanet Web Services SDK for PHP
 *
 * @see		    http://code.google.com/p/maniaplanet-ws-sdk/
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @author      $Author$:
 * @version     $Revision$:
 * @date        $Date$:
 */
define('APP_LIBRARIES_PATH', __DIR__.DIRECTORY_SEPARATOR);

/**
 * ManiaLib class loader
 * It is registered with spl_autoload_register, so if you are already using the
 * __autoload() function don't forget to register it too in the spl autoloader 
 * stack.
 * 
 * @see http://php.net/manual/en/function.spl-autoload-register.php
 */
function manialib_autoload($className)
{
	$className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
	$path = APP_LIBRARIES_PATH.$className.'.php';
	if(file_exists($path))
	{
		require_once $path;
	}
}

spl_autoload_register('manialib_autoload');
?>