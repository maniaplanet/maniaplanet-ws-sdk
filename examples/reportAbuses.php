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
require_once __DIR__.'/../libraries/autoload.php';
?>
<html>
	<head></head>
	<body>
		<?php if(!isset($_POST['username']) && !isset($_POST['password'])): ?>
			<form method="post" action="">
				<label for="username">Enter your API Username</label>
				<input type="text" id="username" name="username"/>
				<br/>
				<label for="password">Enter your API Password</label>	
				<input type="password" id="password" name="password"/>
				<br/>
				<input type="submit" value="send"/>
			</form>
		<?php else: ?>
			<form method="post" action="">
				<input type="hidden" name="username" value="<?php echo $_POST['username'] ?>"/>
				<input type="hidden" name="password" value="<?php echo $_POST['password'] ?>"/>
				<label for="login">Enter one of your server login</label>	
				<input type="text" id="login" name="login"/><br/>
				<input type="submit" value="send"/>
			</form>
			<?php if(isset($_POST['login'])): ?>
				<?php
				$service = new \Maniaplanet\WebServices\Servers($_POST['username'], $_POST['password']);
				$reports = $service->getReportAbuses($_POST['login']);
				?>
		<h2>Reports for: <?php echo $_POST['login']?></h2>
				<table border="1" rules="cols">
					<?php foreach($reports as $report): ?>
						<tr><td>
								<ul>
									<li><strong>Reason</strong>&nbsp;<? echo $report->reason ?></li>
									<li><strong>Reporter</strong>&nbsp<?= $report->reporterLogin ?></li>
									<li><strong>Report date</strong>&nbsp<?= $report->reportDate ?></li>
									<?php foreach($report->details as $key => $value): ?>
										<?php if($value): ?>
											<li>
												<strong><?=
						str_replace('_', ' ', $key)
											?>:</strong> 
												<?php if($key == 'chat'): ?>
													<?=
													str_replace('[', '<br/>[', $value)
													?>
												<?php elseif($key == 'nickname' || $key == 'challenge_name'): ?>
													<?= $value ?>
												<?php else: ?>
													<?= $value ?>
												<?php endif; ?>
											</li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							</td></tr>
					<?php endforeach; ?>
				</table>
			<?php endif; ?>
		<?php endif; ?>
	</body>
</html>