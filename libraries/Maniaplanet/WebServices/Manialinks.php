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

/**
 * Access to public Manialink data
 */
class Manialinks extends HTTPClient
{

	/**
	 * Retrieves information about a Manialink code.
	 * 
	 * @param string $code Short Manialink code
	 * @return object
	 * @throws \TrackMania\WebServices\Exception 
	 */
	function get($code)
	{
		return $this->execute('GET', '/manialinks/%s/', array($code));
	}

}

?>