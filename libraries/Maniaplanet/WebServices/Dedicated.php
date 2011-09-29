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

class Dedicated extends HTTPClient
{

	/**
	 * Returns information about the specified dedicated server
	 * 
	 * @param string $login 
	 * @return object
	 */
	function get($login)
	{
		return $this->execute('GET', '/dedicated/%s/', array($login));
	}

}

?>
