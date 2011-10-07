<?php
/**
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace Maniaplanet\WebServices\Canyon;

class Rankings extends \Maniaplanet\WebServices\HTTPClient
{

	function getSoloPlayer($login)
	{
		return $this->execute('GET', '/canyon/rankings/solo/player/%s/', array($login));
	}

	function getSoloWorld($offset = 0, $length = 10)
	{
		return $this->execute('GET', '/canyon/rankings/solo/zone/',
				array($offset, $length));
	}

	function getSoloZone($path, $offset = 0, $length = 10)
	{
		return $this->execute('GET', '/canyon/rankings/solo/zone/%s/',
				array($path, $offset, $length));
	}

	function getSoloChallengeWorld($challengeuid, $offset = 0, $length = 10)
	{
		return $this->execute('GET', '/canyon/rankings/solo/challenge/%s/',
				array($challengeuid, $offset, $length));
	}

	function getSoloChallengeZone($challengeuid, $path, $offset = 0, $length = 10)
	{
		return $this->execute('GET', '/canyon/rankings/solo/challenge/%s/%s/',
				array($challengeuid, $path, $offset, $length));
	}

	function getMultiplayerPlayer($login)
	{
		return $this->execute('GET', '/canyon/rankings/multiplayer/player/%s/',
				array($login));
	}

}

?>
