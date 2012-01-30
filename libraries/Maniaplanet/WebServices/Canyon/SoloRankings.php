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

namespace Maniaplanet\WebServices\Canyon;

use Maniaplanet\WebServices\Exception;

class SoloRankings extends \Maniaplanet\WebServices\HTTPClient
{

	function getPlayer($login)
	{
		if(!$login)
		{
			throw new Exception('Invalid login');
		}
		return $this->execute('GET', '/canyon/rankings/solo/player/%s/', array($login));
	}

	function getWorld($offset = 0, $length = 10)
	{
		return $this->execute('GET', '/canyon/rankings/solo/zone/?offset=%d&length=%d',
				array($offset, $length));
	}

	function getZone($path, $offset = 0, $length = 10)
	{
		if(!$path)
		{
			throw new Exception('Invalid zone path');
		}
		return $this->execute('GET', '/canyon/rankings/solo/zone/%s/?offset=%d&length=%d',
				array($path, $offset, $length));
	}

	function getChallengeWorld($challengeuid, $offset = 0, $length = 10)
	{
		if(!$challengeuid)
		{
			throw new Exception('Invalid challenge UID');
		}
		return $this->execute('GET', '/canyon/rankings/solo/challenge/%s/?offset=%d&length=%d',
				array($challengeuid, $offset, $length));
	}

	function getChallengeZone($challengeuid, $path, $offset = 0, $length = 10)
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