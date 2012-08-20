<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace Maniaplanet\WebServices\ManiaHome;

class ManialinkPublisher extends \Maniaplanet\WebServices\HTTPClient
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
	 * Send a public notification to every player that bookmarked your Manialink.
	 * @param string $message The message itself. If you send a public notification to a player, the
	 * message will be prepended with its nickname. Max length is 255 chars, you
	 * can use Maniaplanet special chars.
	 * @param string $link  Link when the player clicks on the notification
	 * @param string $iconStyle Icon style (from the Manialink styles)
	 * @param string $iconSubstyle Icon substyle (from the Manialink styles)
	 * @return int
	 */
	function postPublicNotification($message, $link = null, $iconStyle = null, $iconSubstyle = null)
	{
		$n = new Notification();
		$n->senderName = $this->manialink;
		$n->message = $message;
		$n->link = $link;
		$n->iconStyle = $iconStyle;
		$n->iconSubStyle = $iconSubstyle;
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
	 * @return int
	 */
	function postPersonalNotification($message, $receiverName, $link = null, $iconStyle = null, $iconSubstyle = null)
	{
		$n = new Notification();
		$n->senderName = $this->manialink;
		$n->message = $message;
		$n->link = $link;
		$n->iconStyle = $iconStyle;
		$n->iconSubStyle = $iconSubstyle;
		$n->receiverName = $receiverName;
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
	function postPrivateNotification($message, $receiverName, $link = null)
	{
		$n = new Notification();
		$n->senderName = $this->manialink;
		$n->message = $message;
		$n->link = $link;
		$n->receiverName = $receiverName;
		$n->isPrivate = true;
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
	function postPrivateEvent($message, $eventDate, $receiverName, $link = null)
	{
		$e = new Event();
		$e->senderName = $this->manialink;
		$e->message = $message;
		$e->link = $link;
		$e->receiverName = $receiverName;
		$e->eventDate = $eventDate;
		$e->isPrivate = true;
		return $this->execute('POST', '/maniahome/event/private/', array($e));
	}

	/**
	 * Create an event visible by all players who bookmarked your Manialink
	 * @param string $message The message itself. If you send a public notification to a player, the
	 * @param string message will be prepended with its nickname. Max length is 255 chars, you
	 * can use Maniaplanet special chars.
	 * @param int $eventDate The UNIX Timestamp of the date of the event
	 * @param string $link  Link when the player clicks on the notification
	 * @return int
	 */
	function postPublicEvent($message, $eventDate, $link = null)
	{
		$e = new Event();
		$e->senderName = $this->manialink;
		$e->message = $message;
		$e->link = $link;
		$e->eventDate = $eventDate;
		return $this->execute('POST', '/maniahome/event/public/', array($e));
	}

}

?>