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

	const ALL_MODES = -1;
	const OFFICIAL_MODES = -2;
	const CUSTOM_MODES = -3;

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

	/**
	 * Return the report abuses created on the given login
	 * @param string $login
	 * @return object[]
	 * @throws Exception
	 */
	function getReportAbuses($login)
	{
		if(!$login)
		{
			throw new Exception('Invalid login');
		}
		return $this->execute('GET', '/report-abuse/list/%s/', array($login));
	}

	/**
	 * @param array[] $filters This should be an associative array, it can contain any of the following values with the 
	 * correct key:
	 * environment Canyon or Storm
	 * title the Title idString
	 * playersMin	int		Minimum number of player connected on the server
	 * playersMax	int		Maximum number of player connected on the server
	 * hideFull		bool	If true, full servers will not be displayed
	 * visibility	string	It can take one of those 3 values: all, public or private
	 * zone			string	The path to a zone, for example: World|France. It will display every server in France subzones
	 * mode			mixed	it can be one of class constant, or directly the game mode name itself (TimeAttack, Melee, ...)
	 * offset		int		The offset in the list where the results will start
	 * length		int		Number of elements returned
	 * @return object[]
	 */
	function getFilteredList(array $filters = array())
	{
		$params = http_build_query($filters, '', '&');
		return $this->execute('GET', '/servers/?'.$params);
	}

}

?>
