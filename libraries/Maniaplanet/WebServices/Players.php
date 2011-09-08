<?php
/**
 * Maniaplanet Web Services SDK for PHP
 *
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @author      $Author$:
 * @version     $Revision$:
 * @date        $Date$:
 */

namespace Maniaplanet\WebServices;

class Players extends HTTPClient
{
	/**
	 * @param string $login Login of a Maniaplanet account
	 * @return object
	 * @throws \Maniaplanet\WebServices\Exception 
	 */
	function get($login)
	{
		return $this->execute('GET', '/players/%s/', array($login));
	}
}

?>