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

namespace Maniaplanet\WebServices\Rankings;

use Maniaplanet\WebServices\Exception;

class Title extends \Maniaplanet\WebServices\HTTPClient
{

	// MULTIPLAYER
	function getMultiplayerPlayer($title, $login)
	{
		if(!$login)
		{
			throw new Exception('Invalid login');
		}
		return $this->execute('GET', '/titles/rankings/multiplayer/player/%s/?title=%s', array($login, $title));
	}

	function getMultiplayerWorld($title, $offset = 0, $length = 100)
	{
		return $this->execute('GET', '/titles/rankings/multiplayer/zone/?title=%s&offset=%d&length=%d', array($title, $offset, $length));
	}

	function getMultiplayerZone($title, $path, $offset = 0, $length = 100)
	{
		if(!$path)
		{
			throw new Exception('Invalid zone path');
		}
		return $this->execute('GET', '/titles/rankings/multiplayer/zone/%s/?title=%s&offset=%d&length=%d',
						array($path, $title, $offset, $length));
	}
}

?>