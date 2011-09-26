<?php

/**
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */
namespace Maniaplanet\WebServices;

class Dedicated extends HTTPClient
{
	/**
	 * Get information about the server. Return a Dedicated object.
	 * Return struct is:
	 * <code>
	 * Object 
	 * ( 
	 *    [login] => string
	 *    [owner] => string
	 *    [serverName] => string
	 *    [isOnline] => int, 0 for an offline server or 1 for a online one
	 *    [isDedicated] => int, 0 for non dedicated account or 1 for a dedicated one
	 *    [path] => string, eg. "World|France|Ile-de-France|Paris"
	 *    [idZone] => int
	 * )
	 * </code>
	 * 
	 * @param string $login 
	 */
	function get($login)
	{
		$this->execute('GET', '/dedicated/%s/', array($login));
	}
}

?>
