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

class ManiaHome
{

	protected $username;
	protected $password;
	protected $manialinkPublisher;

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

		// If you're using ManiaLib, credentials can be automatically loaded
		if(!$manialink && class_exists('\ManiaLib\Application\Config'))
		{
			$config = \ManiaLib\Application\Config::getInstance();
			$manialink = $config->manialink;
		}

		$this->username = $username;
		$this->password = $password;
		$this->manialinkPublisher = new ManiaHome\ManialinkPublisher($username, $password, $manialink);
	}

	/**
	 * Please use the other methods which are more robust
	 * @deprecated
	 */
	function postNotification(Notification $n)
	{
		if($n->receiverName)
		{
			if($n->isPrivate)
			{
				return $this->manialinkPublisher->postPrivateNotification($n->message, $n->receiverName);
			}
			return $this->manialinkPublisher->postPersonalNotification($n->message, $n->link, $n->iconStyle, $n->iconSubstyle,
					$n->group, $n->priority);
		}
		return $this->manialinkPublisher->postPublicNotification($n->message, $n->link, $n->iconStyle, $n->iconSubstyle,
				$n->group, $n->priority);
	}

	/**
	 * Send a public notification to every player that bookmarked your Manialink.
	 * @param Notification $n
	 */
	function postPublicNotification(Notification $n)
	{
		return $this->manialinkPublisher->postPublicNotification($n->message, $n->link, $n->iconStyle, $n->iconSubstyle,
				$n->group, $n->priority);
	}

	/**
	 * Send a public notification to a player (specified in
	 * Notification::$receiverName). The message will be prepended with its
	 * nickname and will be visible by all its buddies.
	 * @param Notification $n
	 */
	function postPersonalNotification(Notification $n)
	{
		return $this->manialinkPublisher->postPersonalNotification($n->message, $n->link, $n->iconStyle, $n->iconSubstyle,
				$n->group, $n->priority);
	}

	/**
	 * Send a private message to a player (specified in
	 * Notification::$receiverName).
	 * @param Notification $n
	 */
	function postPrivateNotification(Notification $n)
	{
		return $this->manialinkPublisher->postPrivateNotification($n->message, $n->receiverName);
	}

	function postPrivateEvent(Event $e)
	{
		return $this->manialinkPublisher->postPrivateEvent($e->message, $e->eventDate, $e->receiverName, $e->link);
	}

	function postPublicEvent(Event $e)
	{
		return $this->manialinkPublisher->postPublicEvent($e->message, $e->eventDate, $e->link);
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
		$service = new ManiaHome\Comments($this->username,$this->password);
		return $service->count($notificationId);
	}

}

?>