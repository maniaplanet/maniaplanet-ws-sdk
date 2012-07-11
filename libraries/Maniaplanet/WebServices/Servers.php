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

namespace Maniaplanet\WebServices;

class Servers extends HTTPClient
{

	/**
	 * Returns information about the specified server
	 *
	 * @param string $login
	 * @return object
	 */
	function get($login)
	{
		if(!$login)
		{
			throw new Exception('Invalid login');
		}
		return $this->execute('GET', '/servers/%s/', array($login));
	}

	/**
	 * Return online players for the given server
	 * @param string $login
	 * @return object[]
	 * @throws Exception
	 */
	function getOnlinePlayers($login)
	{
		if(!$login)
		{
			throw new Exception('Invalid login');
		}
		return $this->execute('GET', '/servers/%s/players/', array($login));
	}

	/**
	 * Return the number of persons that have favorited the server
	 * @param string $login
	 * @return int
	 * @throws Exception
	 */
	function getFavoritedCount($login)
	{
		if(!$login)
		{
			throw new Exception('Invalid login');
		}
		return $this->execute('GET', '/servers/%s/favorited/', array($login));
	}


}

?>
