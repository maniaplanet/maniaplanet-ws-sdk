<?php
/**
 * Maniaplanet Web Services SDK for PHP
 *
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace Maniaplanet\WebServices;

/**
 * Container object to send events to ManiaHome
 */
class Event
{

	/**
	 * Manialink that sends the notification. You don't need to set that as
	 * the ManiaHome class will automatically set it for you when you send a
	 * notification.
	 * @var string
	 */
	public $senderName;

	/**
	 * The receiver of the notification. It can either be a Maniaplanet login or
	 * null.
	 * @var string
	 */
	public $receiverName;

	/**
	 * The message itself. If you send a public notification to a player, the
	 * message will be prepended with its nickname. Max length is 255 chars, you
	 * can use Maniaplanet special chars.
	 * @var string
	 */
	public $message;

	/**
	 * The timestamp of the event. It must be in the futur
	 * @var int
	 */
	public $creationDate;

	/**
	 * (optional)
	 * Link when the player clicks on the notification. Has to be a Manialink,
	 * not a Web URL.
	 * @var string
	 */
	public $link;

	/**
	 * (optional)
	 * Whether the Notification is private or not.
	 * @var boolean
	 */
	public $isPrivate;

	/**
	 * (optional)
	 * Icon style (from the Manialink styles)
	 * @var string
	 */
	public $iconStyle;

	/**
	 * (optional)
	 * Icon substyle (from the Manialink styles)
	 * @var string
	 */
	public $iconSubStyle;

}

?>