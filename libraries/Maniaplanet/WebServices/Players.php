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

class Players extends HTTPClient
{

	/**
	 * @param string $login Login of a Maniaplanet account
	 * @return object
	 * @throws \Maniaplanet\WebServices\Exception
	 */
	function get($login)
	{
		if(!$login)
		{
			throw new Exception('Invalid login');
		}
		return $this->execute('GET', '/players/%s/', array($login));
	}

	/**
	 * List all teams contracts of the given player
	 * @param string $login Login of a Maniaplanet account
	 * @return object
	 * @throws Exception
	 */
	function getTeams($login)
	{
		if(!$login)
		{
			throw new Exception('Invalid login');
		}
		return $this->execute('GET', '/players/%s/teams/', array($login));
	}

}

?>