<?php
$apiUsername = '';
$apiPassword = '';
?>

<html>
	<head></head>
	<body>
		<?php
		require_once __DIR__.'/../../../libraries/autoload.php';

		try
		{
			$maniaconnect = new \Maniaplanet\WebServices\ManiaConnect\Player($apiUsername, $apiPassword);
			$loginURL = $maniaconnect->getLoginURL('dedicated');
			$maniaconnect->getPlayer();
			$servers = $maniaconnect->getDedicated();
			if($servers)
			{
				echo '<form method="post" action="">'.
				'<label for="login">Choose on your server login</label>'.
				'<select name="serverLogin">';
				foreach($servers as $server)
				{
					$isSelected = isset($_POST['serverLogin']) && $server->login == $_POST['serverLogin'];
					echo '<option value="'.$server->login.'" '.($isSelected ? 'selected="selected"' : '').'>'.$server->serverName.'</option>';
				}
				echo '</select>'.
				'<input type="submit" value="send"/>'.
				'</form>';
				if(isset($_POST['serverLogin']))
				{
					echo '<h2>Reports for: '.$_POST['serverLogin'].'</h2>';
					$reports = $maniaconnect->getReportAbuses($_POST['serverLogin']);
					print_r($reports);
				}
			}
		}
		catch(\Maniaplanet\WebServices\Exception $e)
		{
			print_r($e);
			$servers = null;
			// We ignore errors
		}
		?>
	</body>
</html>