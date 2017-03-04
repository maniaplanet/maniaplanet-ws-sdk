<?php
require_once __DIR__.'/../../../libraries/autoload.php';

try
{
	$maniaconnect = new \Maniaplanet\WebServices\ManiaConnect\Player('your_api_username', 'your_api_password');
	$loginURL = $maniaconnect->getLoginURL('basic');
	$player = $maniaconnect->getPlayer();
}
catch(\Maniaplanet\WebServices\Exception $e)
{
	// We ignore errors
}

if($player)
{
	// Player is logged in, do whatever you want
	echo $player->login;
}
else
{
	// Player is not logged in, link or redirect to $loginURL
	printf('<a href="%s">Login</a>', htmlentities($loginURL));
}
