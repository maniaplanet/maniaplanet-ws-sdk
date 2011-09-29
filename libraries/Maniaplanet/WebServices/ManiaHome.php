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

class ManiaHome extends HTTPClient
{

	protected $manialink;

	function __construct($username = null, $password = null, $manialink = null)
	{
		parent::__construct($username, $password);
		$this->manialink = $manialink;
	}

	/**
	 * DO NOT USE IT
	 * @deprecated
	 */
	function postNotification(Notification $n)
	{
		$n->senderName = $this->manialink;
		return $this->execute('POST', '/maniahome/notification/', array($n));
	}
	
	/**
	 * Send a Notification visible to every player with the manialink in his
	 * bookmarks
	 * @param Notification $n
	 */
	function postPublicNotification(Notification $n)
	{
		$n->senderName = $this->manialink;
		$this->execute('POST', '/maniahome/notification/public/', array($n));
	}
	
	/**
	 * Send a Notification to the specified player in receiverName property
	 * @param Notification $n
	 */
	function postPersonalNotification(Notification $n)
	{
		$n->senderName = $this->manialink;
		$this->execute('POST', '/maniahome/notification/personal/', array($n));
	}
	
	/**
	 * Send a private Notification to the player specified in receiverName property
	 * @param Notification $n 
	 */
	function postPrivateNotification(Notification $n)
	{
		$n->senderName = $this->manialink;
		$this->execute('POST', '/maniahome/notification/private/', array($n));
	}

}

?>