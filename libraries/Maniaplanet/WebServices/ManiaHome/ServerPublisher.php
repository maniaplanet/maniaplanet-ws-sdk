<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 */

namespace Maniaplanet\WebServices\ManiaHome;

class ServerPublisher extends \Maniaplanet\WebServices\HTTPClient
{

	protected $serverLogin;

	/**
	 * In the class the constructor is a bit different: there is a 3rd param to
	 * specify the server login from which the notifications are sent.
	 *
	 * IMPORTANT NOTE:
	 *
	 * The API username must be allowed to post notifications for the Server!
	 * If you're the Server owner, go in ManiaHome's Manager to the "API user"
	 * page to set it.
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $serverLogin
	 */
	function __construct($username = null, $password = null, $serverLogin = null)
	{
		parent::__construct($username, $password);

		$this->serverLogin = $serverLogin;
	}

	/**
	 * Send a public notification to every player that bookmarked your server.
	 * @param string $message The message itself. If you send a public notification to a player, the 
	 * message will be prepended with its nickname. Max length is 255 chars, you
	 * can use Maniaplanet special chars.
	 * @param string $link  Link when the player clicks on the notification
	 * @param string $iconStyle Icon style (from the Manialink styles)
	 * @param string $iconSubstyle Icon substyle (from the Manialink styles)
     * @param string $titleIdString the titleIdString where the notification will be visible. Leave empty to post for ManiaPlanet
     * @param string $mediaURL Link to a picture (jpg,png or dds) or a video (webm)
     * @param string $group Group on the notification (http://maniapla.net/maniahome-group)
	 * @return int
	 */
	function postPublicNotification($message, $link = null, $iconStyle = null, $iconSubstyle = null, $titleIdString = null, $mediaURL = null, $group = null)
	{
		$n = new Notification();
		$n->senderName = (object) array('serverLogin' => $this->serverLogin);
		$n->message = $message;
		$n->link = $link;
		$n->iconStyle = $iconStyle;
		$n->iconSubStyle = $iconSubstyle;
		$n->titleId = $titleIdString;
		$n->mediaURL = $mediaURL;
        $n->group = $group;
		return $this->execute('POST', '/maniahome/notification/public/', array($n));
	}

	/**
	 * Send a public notification to a player (specified in
	 * Notification::$receiverName). The message will be prepended with its
	 * nickname and will be visible by all its buddies.
	 * @param string $message The message itself. If you send a public notification to a player, the 
	 * message will be prepended with its nickname. Max length is 255 chars, you
	 * can use Maniaplanet special chars.
	 * @param string $receiverName The receiver of the notification.
	 * @param string $link  Link when the player clicks on the notification
	 * @param string $iconStyle Icon style (from the Manialink styles)
	 * @param string $iconSubstyle Icon substyle (from the Manialink styles)
     * @param string $titleIdString the titleIdString where the notification will be visible. Leave empty to post for ManiaPlanet
     * @param string $mediaURL Link to a picture (jpg,png or dds) or a video (webm)
     * @param string $group Group on the notification (http://maniapla.net/maniahome-group)
	 * @return int
	 */
	function postPersonalNotification($message, $receiverName, $link = null, $iconStyle = null, $iconSubstyle = null, $titleIdString = null, $mediaURL = null, $group = null)
	{
		$n = new Notification();
		$n->senderName = (object) array('serverLogin' => $this->serverLogin);
		$n->message = $message;
		$n->link = $link;
		$n->iconStyle = $iconStyle;
		$n->iconSubStyle = $iconSubstyle;
		$n->receiverName = $receiverName;
		$n->titleId = $titleIdString;
		$n->mediaURL = $mediaURL;
        $n->group = $group;
		return $this->execute('POST', '/maniahome/notification/personal/', array($n));
	}

	/**
	 * Send a private message to a player (specified in
	 * Notification::$receiverName).
	 * @param string $message The message itself. If you send a public notification to a player, the 
	 * message will be prepended with its nickname. Max length is 255 chars, you
	 * can use Maniaplanet special chars.
	 * @param string $receiverName The receiver of the notification.
	 * @param string $link  Link when the player clicks on the notification
	 * @return int
	 */
	function postPrivateNotification($message, $receiverName, $link = null, $titleIdString = null)
	{
		$n = new Notification();
		$n->senderName = (object) array('serverLogin' => $this->serverLogin);
		$n->message = $message;
		$n->link = $link;
		$n->receiverName = $receiverName;
		$n->isPrivate = true;
		$n->titleId = $titleIdString;
		return $this->execute('POST', '/maniahome/notification/private/', array($n));
	}

	/**
	 * Create an event visible only by the receivers. To create an event for many players just give an array of login as
	 * receiverName.
	 * 
	 * @param string $message The message itself. If you send a public notification to a player, the 
	 * message will be prepended with its nickname. Max length is 255 chars, you
	 * can use Maniaplanet special chars.
	 * @param string|string[] $receiverName The receiver(s) of the notification.
	 * @param int $eventDate The UNIX Timestamp of the date of the event
	 * @param string $link  Link when the player clicks on the notification
	 * @return int
	 */
	function postPrivateEvent($message, $eventDate, $receiverName, $link = null, $titleIdString = null, $mediaURL = null)
	{
		$e = new Event();
		$n->senderName = (object) array('serverLogin' => $this->serverLogin);
		$e->message = $message;
		$e->link = $link;
		$e->receiverName = $receiverName;
		$e->eventDate = $eventDate;
		$e->isPrivate = true;
		$n->titleId = $titleIdString;
		$n->mediaURL = $mediaURL;
		return $this->execute('POST', '/maniahome/event/private/', array($e));
	}

	/**
	 * Create an event visible by all players who bookmarked your server
	 * @param string $message The message itself. If you send a public notification to a player, the 
	 * @param string message will be prepended with its nickname. Max length is 255 chars, you
	 * can use Maniaplanet special chars.
	 * @param int $eventDate The UNIX Timestamp of the date of the event
	 * @param string $link  Link when the player clicks on the notification
	 * @return int
	 */
	function postPublicEvent($message, $eventDate, $link = null, $titleIdString = null, $mediaURL = null)
	{
		$e = new Event();
		$n->senderName = (object) array('serverLogin' => $this->serverLogin);
		$e->message = $message;
		$e->link = $link;
		$e->eventDate = $eventDate;
		$n->titleId = $titleIdString;
		$n->mediaURL = $mediaURL;
		return $this->execute('POST', '/maniahome/event/public/', array($e));
	}

}

?>