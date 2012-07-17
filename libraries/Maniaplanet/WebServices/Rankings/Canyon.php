<?php
/**
 * Maniaplanet Web Services SDK for PHP
 *
 * @see		    http://code.google.com/p/maniaplanet-ws-sdk/
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @author      $Author$:
 * @version     $Revision$:
 * @date        $Dat
 */

namespace Maniaplanet\WebServices\Rankings;

use Maniaplanet\WebServices\Exception;


class Canyon extends \Maniaplanet\WebServices\HTTPClient
{

	// MULTIPLAYER
	function getMultiplayerPlayer($login)
	{
		if(!$login)
		{
			throw new Exception('Invalid login');
		}
		return $this->execute('GET', '/canyon/rankings/multiplayer/player/%s/', array($login));
	}

	function getMultiplayerWorld($offset = 0, $length = 100)
	{
		return $this->execute('GET', '/canyon/rankings/multiplayer/zone/?offset=%d&length=%d', array($offset, $length));
	}

	function getMultiplayerZone($path, $offset = 0, $length = 100)
	{
		if(!$path)
		{
			throw new Exception('Invalid zone path');
		}
		return $this->execute('GET', '/canyon/rankings/multiplayer/zone/%s/?offset=%d&length=%d',
						array($path, $offset, $length));
	}

	// SOLO
	function getSoloPlayer($login)
	{
		if(!$login)
		{
			throw new Exception('Invalid login');
		}
		return $this->execute('GET', '/canyon/rankings/solo/player/%s/', array($login));
	}

	function getSoloWorld($offset = 0, $length = 100)
	{
		return $this->execute('GET', '/canyon/rankings/solo/zone/?offset=%d&length=%d', array($offset, $length));
	}

	function getSoloZone($path, $offset = 0, $length = 100)
	{
		if(!$path)
		{
			throw new Exception('Invalid zone path');
		}
		return $this->execute('GET', '/canyon/rankings/solo/zone/%s/?offset=%d&length=%d', array($path, $offset, $length));
	}

	function getSoloChallengeWorld($challengeuid, $offset = 0, $length = 100)
	{
		if(!$challengeuid)
		{
			throw new Exception('Invalid challenge UID');
		}
		return $this->execute('GET', '/canyon/rankings/solo/challenge/%s/?offset=%d&length=%d',
						array($challengeuid, $offset, $length));
	}

	function getSoloChallengeZone($challengeuid, $path, $offset = 0, $length = 100)
	{
		if(!$challengeuid)
		{
			throw new Exception('Invalid challenge UID');
		}
		return $this->execute('GET', '/canyon/rankings/solo/challenge/%s/%s/?offset=%d&length=%d',
						array($challengeuid, $path, $offset, $length));
	}

}

?>