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

class ManiaFlash extends HTTPClient
{
	/**
	 * Return informations about a ManiaFlash
	 * @param string $id
	 * @return Object
	 *			- name
	 *			- description
	 *			- id
	 * @throws Exception
	 */
	public function getChannel($id)
	{
		if(!$id)
		{
			throw new Exception('Invalid login');
		}
		return $this->execute('GET', '/maniaflash/channels/%s/', array($id));
	}

	/**
	 * Return latest messages of a channel
	 * @param string $id
	 * @return Object[]
	 *				- id
	 *				- author
	 *				- dateCreated
	 *				- message
	 *				- link
	 * @throws Exception
	 */
	public function getMessages($id, $offset = 0, $length = 10)
	{
		if(!$id)
		{
			throw new Exception('Invalid login');
		}
		return $this->execute('GET', '/maniaflash/channels/%s/messages/?offset=%d&length=%d', array($id, $offset, $length));
	}

	/**
	 * Publish a message on a maniaflash channel
	 * @param string $channelId
	 * @param string $message
	 * @param string $longMessage
	 * @param string $mediaUrl
	 * @return type
	 */
	public function postMessage($channelId, $message, $longMessage = null, $mediaURL = null)
	{
		return $this->execute('POST', '/maniaflash/channels/%s/', array(
			$channelId,
			array(
				'message' => $message,
				'longMessage' => $longMessage,
				'mediaURL' => $mediaURL)));
	}
}