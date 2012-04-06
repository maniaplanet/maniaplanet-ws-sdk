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

class Payments extends HTTPClient
{

	/**
	 * @param Transaction $t
	 * @return int ID of the created transaction
	 */
	function create(Transaction $t)
	{
		return $this->execute('POST', '/transactions/', array($t));
	}

	/**
	 * Pay a transaction without ingame validation
	 * Works only to give coppers from your account
	 * @param Transaction $t
	 * @return int ID of the created transaction
	 */
	function pay(Transaction $t)
	{
		return $this->execute('POST', '/transactions/pay/', array($t));
	}

	/**
	 * @param int $id
	 * @return bool Whether the specified transaction was paid by the player
	 */
	function isPaid($id)
	{
		return $this->execute('GET', '/transactions/%d/ispaid/', array($id));
	}

}

?>