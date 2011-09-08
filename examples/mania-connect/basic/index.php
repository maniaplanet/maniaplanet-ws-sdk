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
require_once __DIR__.'/../../../libraries/autoload.php';

define('API_USERNAME', 'api_username');
define('API_PASSWORD', 'api_password');
define('SCOPE', '');

try
{
	$trackmania = new \Maniaplanet\WebServices\ManiaConnect\Player(API_USERNAME, API_PASSWORD);

	// URLs to log in and out
	$loginURL = $trackmania->getLoginURL(SCOPE);
	$logoutURL = $trackmania->getLogoutURL();
	
	if(isset($_POST['logout']))
	{
		$trackmania->logout();
		header('Location: '.$logoutURL);
		exit;
	}

	// Retrive player information. If the user is not logged in, it will return false
	$player = $trackmania->getPlayer();
	
}
catch(\Maniaplanet\WebServices\Exception $e)
{
	$player = null;
}
?>
<?php if(array_key_exists('HTTP_USER_AGENT', $_SERVER) && substr($_SERVER['HTTP_USER_AGENT'],
		0, 11) == 'ManiaPlanet'): ?>
<manialink version="0">
	<timeout>0</timeout>
	<frame posn="0 25 0">
		<label sizen="70 3"  halign="center" 
			   text="$o$ff0ManiaConnect example" />
		<?php if($player): ?>
		<label sizen="70 3" posn="0 -5 0"  halign="center" text="Hello <?php echo $player->login ?>" />
		<label sizen="70 70" posn="0 -10 0" autonewline="1" halign="center" 
			   text="<?php echo print_r($player, true) ?>" />
		<?php else: ?>
			<label sizen="40 3" posn="0 -5 0"  halign="center" text="Login" manialink="<?php echo htmlentities($loginURL) ?>"/>
		<?php endif ?>
	</frame>
</manialink>
<?php else: ?>
	<html> 
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>ManiaConnect example</title>
		</head>
		<body>
			<h1>ManiaConnect example</h1>

			<?php if($player): ?>
				<h3>Hello <?php echo $player->login ?></h3>
				<p>You are connected with your Maniaplanet account.</p>
				<pre><?php print_r($player) ?></pre>
				<p>
					<form action="" method="post">
						<input type="hidden" name="logout" value="1" />
						<input type="submit" name="submit" value="Logout" />
					</form>
				</p>

			<?php else: ?>
				<p>
					<a href="<?php echo $loginURL ?>">Login with your Maniaplanet account</a>
				</p>
			<?php endif ?>

		</body>
	</html>
<?php endif ?>
