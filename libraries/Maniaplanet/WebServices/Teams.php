<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace Maniaplanet\WebServices;

class Teams extends HTTPClient
{
	/**
	 * @param int $id Id of the team
	 * @return object
	 * @throws Exception
	 */
	function get($id)
	{
		if (!$id)
		{
			throw new Exception('Invalid id');
		}

		return $this->execute('GET', '/teams/%d/', array($id));
	}

	/**
	 * @param int $id Id of the team
	 * @return object
	 * @throws Exception
	 */
	function getContracts($id)
	{
		if (!$id)
		{
			throw new Exception('Invalid id');
		}

		return $this->execute('GET', '/teams/%d/contracts/', array($id));
	}


	/**
	 * @param int $id Id of the team
	 * @return object
	 * @throws Exception
	 */
	function getAdmins($id)
	{
		if (!$id)
		{
			throw new Exception('Invalid id');
		}

		return $this->execute('GET', '/teams/%d/admins/', array($id));
	}

	function getRank($id)
	{
		if (!$id)
		{
			throw new Exception('Invalid id');
		}

		return $this->execute('GET', '/teams/%d/rank/', array($id));
	}
}