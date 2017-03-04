<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: 7307 $:
 * @author      $Author: gwendal $:
 * @date        $Date: 2012-06-01 11:05:31 +0200 (ven., 01 juin 2012) $:
 */

namespace Maniaplanet\WebServices;

class TrustCircles extends HTTPClient
{
	/**
	 * Get how many times a player has been blacklisted and whitelisted within a circle
	 * @param string $circle
	 * @param string $player
	 * @return object Numbers of blacklistings and whitelistings
	 * @throws Exception
	 */
	function getKarma($circle, $player)
	{
		if(!$circle)
		{
			throw new Exception('Invalid circle');
		}
		if(!$player)
		{
			throw new Exception('Invalid player');
		}

		return $this->execute('GET', '/trust/%s/karma/%s/', array($circle, $player));
	}

	/**
	 * Get your own blacklist or a shared one
	 * @param string|null $circle A circle name or null to get your own
	 * @return string[]|object[] An array of strings for your own blacklist or an array of objects with 2 fields:
	 * login of the player, number of blacklistings
	 * @throws Exception
	 */
	function getBlackList($circle = null)
	{
		if($circle)
		{
			return $this->execute('GET', '/trust/%s/black/', array($circle));
		}
		else
		{
			return $this->execute('GET', '/trust/black/');
		}
	}

	/**
	 * Get your own whitelist or a shared one
	 * @param string|null $circle A circle name or null to get your own
	 * @return string[]|object[] An array of strings for your own whitelist or an array of objects with 2 fields:
	 * login of the player, number of whitelistings
	 * @throws Exception
	 */
	function getWhiteList($circle = null)
	{
		if($circle)
		{
			return $this->execute('GET', '/trust/%s/white/', array($circle));
		}
		else
		{
			return $this->execute('GET', '/trust/white/');
		}
	}

	/**
	 * Blacklist a player
	 * @param string $player
	 * @return mixed
	 * @throws Exception
	 */
	function blackList($player)
	{
		if(!$player)
		{
			throw new Exception('Invalid player');
		}

		return $this->execute('POST', '/trust/black/', array($player));
	}

	/**
	 * Whitelist a player
	 * @param string $player
	 * @return mixed
	 * @throws Exception
	 */
	function whiteList($player)
	{
		if(!$player)
		{
			throw new Exception('Invalid player');
		}

		return $this->execute('POST', '/trust/white/', array($player));
	}

	/**
	 * Unblacklist a player
	 * @param string $player
	 * @return mixed
	 * @throws Exception
	 */
	function unBlackList($player)
	{
		if(!$player)
		{
			throw new Exception('Invalid player');
		}

		return $this->execute('POST', '/trust/unblack/', array($player));
	}

	/**
	 * Unwhitelist a player
	 * @param string $player
	 * @return mixed
	 * @throws Exception
	 */
	function unWhiteList($player)
	{
		if(!$player)
		{
			throw new Exception('Invalid player');
		}

		return $this->execute('POST', '/trust/unwhite/', array($player));
	}
}

?>
