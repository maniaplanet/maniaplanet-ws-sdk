<?php
/**
 * Maniaplanet Web Services SDK for PHP
 * Minimal ManiaConnect example
 *
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @author      $Author$:
 * @version     $Revision$:
 * @date        $Date$:
 */
require_once __DIR__.'/../../../libraries/autoload.php';

define('API_USERNAME', 'api_username');
define('API_PASSWORD', 'api_password');

try
{
	$maniaconnect = new \Maniaplanet\WebServices\ManiaConnect\Player(API_USERNAME, API_PASSWORD);
	$loginURL = $maniaconnect->getLoginURL();
	$player = $maniaconnect->getPlayer();
}
catch(\Maniaplanet\WebServices\Exception $e) 
{
	// Errors are ignored
}

if($player)
{
	// Player is logged in, do whatever you want
}
else
{
	// Player is not logged in, link or redirect to $loginURL
}


?>