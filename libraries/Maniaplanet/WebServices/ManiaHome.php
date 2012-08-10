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

/**
 * @deprecated since version 3.1.0
 */
class ManiaHome
{

	protected $username;
	protected $password;
	protected $manialinkPublisher;

	/**
	 * @deprecated since version 3.1.0
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
	 * @deprecated since version 3.1.0
	 */
	function postNotification(Notification $n)
	{
		if($n->receiverName)
		{
			if($n->isPrivate)
			{
				return $this->manialinkPublisher->postPrivateNotification($n->message, $n->receiverName);
			}
			return $this->manialinkPublisher->postPersonalNotification($n->message, $n->link, $n->iconStyle, $n->iconSubStyle);
		}
		return $this->manialinkPublisher->postPublicNotification($n->message, $n->link, $n->iconStyle, $n->iconSubStyle);
	}

	/**
	 * Send a public notification to every player that bookmarked your Manialink.
	 * @param Notification $n
	 * @deprecated since version 3.1.0
	 */
	function postPublicNotification(Notification $n)
	{
		return $this->manialinkPublisher->postPublicNotification($n->message, $n->link, $n->iconStyle, $n->iconSubStyle);
	}

	/**
	 * Send a public notification to a player (specified in
	 * Notification::$receiverName). The message will be prepended with its
	 * nickname and will be visible by all its buddies.
	 * @param Notification $n
	 * @deprecated since version 3.1.0
	 */
	function postPersonalNotification(Notification $n)
	{
		return $this->manialinkPublisher->postPersonalNotification($n->message, $n->link, $n->iconStyle, $n->iconSubStyle);
	}

	/**
	 * Send a private message to a player (specified in
	 * Notification::$receiverName).
	 * @param Notification $n
	 * @deprecated since version 3.1.0
	 */
	function postPrivateNotification(Notification $n)
	{
		return $this->manialinkPublisher->postPrivateNotification($n->message, $n->receiverName, $n->link);
	}

	/**
	 * @deprecated since version 3.1.0
	 */
	function postPrivateEvent(Event $e)
	{
		return $this->manialinkPublisher->postPrivateEvent($e->message, $e->eventDate, $e->receiverName, $e->link);
	}

	/**
	 * @deprecated since version 3.1.0
	 */
	function postPublicEvent(Event $e)
	{
		return $this->manialinkPublisher->postPublicEvent($e->message, $e->eventDate, $e->link);
	}

	/**
	 *
	 * @param int $notificationId
	 * @return int number of parameters
	 * @deprecated since version 3.1.0
	 */
	function getCommentsCount($notificationId)
	{
		$service = new ManiaHome\Comments($this->username,$this->password);
		return $service->count($notificationId);
	}

}

?>