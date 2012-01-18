<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace Maniaplanet\WebServices;

class Paiements extends HTTPClient
{
	/**
	 * Properties id, and message are optionnal to create Transaction
	 * This method return the transaction thas has to be keep to be paied
	 * @param Transaction $t
	 * @return int
	 */
	function create(Transaction $t)
	{
		return $this->execute('POST', '/transactions/', array($t));
	}

	/**
	 * Use this method to check if the paiement is made
	 * @param int $id
	 * @return bool
	 */
	function isPaid($id)
	{
		return $this->execute('GET', '/transactions/%d/ispaid/', array($id));
	}

}

?>