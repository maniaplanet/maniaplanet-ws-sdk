<?php
/**
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */
namespace Maniaplanet\WebServices;

class Rankings extends HTTPClient
{
	/**
	 * Get player ranking for the give title
	 * @param string $titleId
	 * @param string $login
	 * @return Object
	 * @throws Exception
	 */
	function getMultiplayerPlayer($titleId, $login)
	{
		if(!$login)
		{
			throw new Exception('Invalid login');
		}
		return $this->execute('GET', $this->getPrefixEndpoint($titleId).'/rankings/multiplayer/player/%s/?title=%s', array($login, $titleId));
	}

	/**
	 *
	 * @param string $titleId
	 * @param int $offset
	 * @param int $length
	 * @return Object
	 */
	function getMultiplayerWorld($titleId, $offset = 0, $length = 100)
	{
		return $this->execute('GET', $this->getPrefixEndpoint($titleId).'/rankings/multiplayer/zone/?offset=%d&length=%d&title=%s', array($offset, $length, $titleId));
	}

	/**
	 * @param string $titleId
	 * @param string $path
	 * @param int $offset
	 * @param int $length
	 * @return Object
	 * @throws Exception
	 */
	function getMultiplayerZone($titleId, $path, $offset = 0, $length = 100)
	{
		if(!$path)
		{
			throw new Exception('Invalid zone path');
		}
		return $this->execute('GET', $this->getPrefixEndpoint($titleId).'/rankings/multiplayer/zone/%s/?offset=%d&length=%d&title=%s',
						array($path, $offset, $length, $titleId));
	}

	/**
	 * @param string $titleId
	 * @param string $login
	 * @return Object
	 * @throws Exception
	 */
	function getSoloPlayer($titleId, $login)
	{
		if(!$login)
		{
			throw new Exception('Invalid login');
		}
		return $this->execute('GET', $this->getPrefixEndpoint($titleId).'/rankings/solo/player/%s/?title=%s', array($login, $titleId));
	}

	/**
	 * @param string $titleId
	 * @param int $offset
	 * @param int $length
	 * @return Object
	 */
	function getSoloWorld($titleId, $offset = 0, $length = 100)
	{
		return $this->execute('GET', $this->getPrefixEndpoint($titleId).'/rankings/solo/zone/?offset=%d&length=%d&title=%s', array($offset, $length, $titleId));
	}

	/**
	 * @param string $titleId
	 * @param string $path
	 * @param int $offset
	 * @param int $length
	 * @return Object
	 * @throws Exception
	 */
	function getSoloZone($titleId, $path, $offset = 0, $length = 100)
	{
		if(!$path)
		{
			throw new Exception('Invalid zone path');
		}
		return $this->execute('GET', $this->getPrefixEndpoint($titleId).'/rankings/solo/zone/%s/?offset=%d&length=%d&title=%s', array($path, $offset, $length, $titleId));
	}

	/**
	 * @param string $titleId
	 * @param string $challengeuid
	 * @param int $offset
	 * @param int $length
	 * @return type
	 * @throws Exception
	 */
	function getSoloChallengeWorld($titleId, $challengeuid, $offset = 0, $length = 100)
	{
		if(!$challengeuid)
		{
			throw new Exception('Invalid challenge UID');
		}
		return $this->execute('GET', $this->getPrefixEndpoint($titleId).'/rankings/solo/challenge/%s/?offset=%d&length=%d&title=%s',
						array($challengeuid, $offset, $length, $titleId));
	}

	/**
	 * @param string $titleId
	 * @param string $challengeuid
	 * @param string $path
	 * @param int $offset
	 * @param int $length
	 * @return type
	 * @throws Exception
	 */
	function getSoloChallengeZone($titleId, $challengeuid, $path, $offset = 0, $length = 100)
	{
		if(!$challengeuid)
		{
			throw new Exception('Invalid challenge UID');
		}
		return $this->execute('GET', $this->getPrefixEndpoint($titleId).'/rankings/solo/challenge/%s/%s/?offset=%d&length=%d&title=%s',
						array($challengeuid, $path, $offset, $length, $titleId));
	}

	protected function getPrefixEndpoint($titleId)
	{
		switch($titleId)
		{
			case 'SMStorm':
				return '/storm';

			case 'TMCanyon':
				return '/canyon';

			case 'SMStormRoyal@nadeolabs':
				return '/royal';

			case 'TMStadium':
				return '/stadium';

			case 'SMStormElite@nadeolabs':
				return '/elite';

			default:
				return '/titles';
		}
	}
}
?>