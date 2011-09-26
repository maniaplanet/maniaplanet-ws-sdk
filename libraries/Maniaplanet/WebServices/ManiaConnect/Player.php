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

namespace Maniaplanet\WebServices\ManiaConnect;

/**
 * The base class for using OAuth2.
 */
class Player extends Client
{

	/**
	 * This is the first method to call when you have an authorization code. 
	 * It will retrieve an access token if possible and then call the service to
	 * retrieve a basic object about the authentified player. 
	 * 
	 * Return struct is:
	 * <code>
	 * Object 
	 * ( 
	 *    [id] => int
	 *    [login] => string
	 *    [nickname] => string
	 *    [united] => int, 0 for a nations account or 1 for a united one
	 *    [path] => string, eg. "World|France|Ile-de-France|Paris"
	 *    [idZone] => int
	 * )
	 * </code>
	 * 
	 * You do not need any special scope to call this service, as long as you 
	 * have an access token.
	 * 
	 * If an access token is not found, it will return false
	 * 
	 * @return object A player object or false if no access token is found
	 */
	function getPlayer()
	{
		$player = $this->getVariable('player');
		if(!$player)
		{
			if($this->getAccessToken())
			{
				$player = $this->executeOAuth2('GET', '/player/');
				$this->setVariable('player', $player);
			}
		}
		return $player;
	}

	/**
	 * Returns the buddies of the player as an array of player 
	 * objects. See self::player() for the struct.
	 * 
	 * Scope needed: buddies
	 * 
	 * @return array[object]
	 */
	function getBuddies()
	{
		return $this->execute('GET', '/player/buddies/');
	}
	
	/**
	 *Return the server list of the player as an array of dedicated
	 * objectcts. See \ManiaPlanet\WebServices\Dedicated::get($login) for the struct.
	 * 
	 * Scope needed: dedicated
	 * 
	 * @return array[object]
	 */
	function getDedicated()
	{
		return $this->execute('GET', '/player/dedicated/');
	}

}

?>