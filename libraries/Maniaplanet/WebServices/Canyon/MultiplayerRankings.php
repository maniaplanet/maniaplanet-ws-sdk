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

class MultiplayerRankings extends \Maniaplanet\WebServices\HTTPClient
{

	function getPlayer($login)
	{
		return $this->execute('GET', '/canyon/rankings/multiplayer/player/%s/',
				array($login));
	}

}

?>
