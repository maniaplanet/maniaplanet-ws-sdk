<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace Maniaplanet\WebServices\ManiaHome;

class Comments extends \Maniaplanet\WebServices\HTTPClient
{

	/**
	 *
	 * @param int $notificationId
	 * @return int number of parameters
	 */
	function count($notificationId)
	{
		return $this->execute('GET', sprintf('/maniahome/notifications/%d/comments/count/', $notificationId));
	}

}

?>