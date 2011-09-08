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

class ManiaHome extends HTTPClient
{

	protected $manialink;

	function __construct($username = null, $password = null, $manialink = null)
	{
		parent::__construct($username, $password);
		$this->manialink = $manialink;
	}

	function postNotification(Notification $n)
	{
		$n->senderName = $this->manialink;
		return $this->execute('POST', '/maniahome/notification/');
	}

}

?>