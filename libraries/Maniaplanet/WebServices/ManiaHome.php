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

	/**
	 * In the class the constructor is a bit different: there is a 3rd param to
	 * specify the Manialink from which the notifications are sent.
	 *
	 * IMPORTANT NOTE:
	 *
	 * The API username must be allowed to post notifications for the Manialink!
	 * If you're the Manialink owner, go in ManiaHome's Manager to the "API user"
	 * page to set it.
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $manialink
	 */
	function __construct($username = null, $password = null, $manialink = null)
	{
		parent::__construct($username, $password);

		// If you're using ManiaLib, credentials can be automatically loaded
		if(!$manialink && class_exists('\ManiaLib\Application\Config'))
		{
			$config = \ManiaLib\Application\Config::getInstance();
			$manialink = $config->manialink;
		}

		$this->manialink = $manialink;
	}

	/**
	 * Please use the other methods which are more robust
	 * @deprecated
	 */
	function postNotification(Notification $n)
	{
		$n->senderName = $this->manialink;
		return $this->execute('POST', '/maniahome/notification/', array($n));
	}

	/**
	 * Send a public notification to every player that bookmarked your Manialink.
	 * @param Notification $n
	 */
	function postPublicNotification(Notification $n)
	{
		$n->senderName = $this->manialink;
		return $this->execute('POST', '/maniahome/notification/public/', array($n));
	}

	/**
	 * Send a public notification to a player (specified in
	 * Notification::$receiverName). The message will be prepended with its
	 * nickname and will be visible by all its buddies.
	 * @param Notification $n
	 */
	function postPersonalNotification(Notification $n)
	{
		$n->senderName = $this->manialink;
		return $this->execute('POST', '/maniahome/notification/personal/', array($n));
	}

	/**
	 * Send a private message to a player (specified in
	 * Notification::$receiverName).
	 * @param Notification $n
	 */
	function postPrivateNotification(Notification $n)
	{
		$n->senderName = $this->manialink;
		$n->isPrivate = true;
		return $this->execute('POST', '/maniahome/notification/private/', array($n));
	}

	function postPrivateEvent(Event $e)
	{
		$e->senderName = $this->manialink;
		$e->isPrivate = true;
		return $this->execute('POST', '/maniahome/event/private/', array($e));
	}

	function postPublicEvent(Event $e)
	{
		$e->senderName = $this->manialink;
		return $this->execute('POST', '/maniahome/event/public/', array($e));
	}

	/**
	 *
	 * @param int $notificationId
	 * @return int number of parameters
	 */
	function getCommentsCount($notificationId)
	{
		return $this->execute('GET', sprintf('/maniahome/notifications/%d/comments/count/',$notificationId));
	}

}

?>