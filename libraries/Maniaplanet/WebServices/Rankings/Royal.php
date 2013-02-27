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


class Royal extends \Maniaplanet\WebServices\HTTPClient
{
	function getMultiplayerPlayer($login)
	{
		if(!$login)
		{
			throw new Exception('Invalid login');
		}
		return $this->execute('GET', '/royal/rankings/multiplayer/player/%s/', array($login));
	}

	function getMultiplayerWorld($offset = 0, $length = 100)
	{
		return $this->execute('GET', '/royal/rankings/multiplayer/zone/?offset=%d&length=%d', array($offset, $length));
	}

	function getMultiplayerZone($path, $offset = 0, $length = 100)
	{
		if(!$path)
		{
			throw new Exception('Invalid zone path');
		}
		return $this->execute('GET', '/royal/rankings/multiplayer/zone/%s/?offset=%d&length=%d',
						array($path, $offset, $length));
	}
}

?>