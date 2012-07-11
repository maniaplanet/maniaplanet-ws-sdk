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

class Config extends \ManiaLib\Utils\Singleton
{

	public $URL = 'http://ws.maniaplanet.com';
	public $loginURL = 'http://ws.maniaplanet.com/oauth2/authorize/';
	public $logoutURL = 'http://ws.maniaplanet.com/oauth2/authorize/logout/';

}

?>
