<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: 9077 $:
 * @author      $Author: philippe $:
 * @date        $Date: 2012-12-10 16:55:50 +0100 (lun., 10 déc. 2012) $:
 */

namespace Maniaplanet\WebServices;

class Links extends HTTPClient
{

	const CATEGORY_URL = 1;
	const CATEGORY_SOCIAL = 2;
	const CATEGORY_MANIALINK = 3;
	const CATEGORY_EMAIL = 4;
	const CATEGORY_IRC = 5;
	const CATEGORY_VOD = 6;
	const CATEGORY_STREAM = 7;
	const CATEGORY_SERVER_LOGIN = 8;
	const CATEGORY_REGISTRATION = 9;

	function createForTeam($teamId, $link, $name, $category, $isFeatured = false)
	{
		$obj = array(
			'link' => $link,
			'name' => ($name ? : $link),
			'category' => $category,
			'featured' => $isFeatured,
			'teamId' => $teamId
		);

		return $this->execute('POST', '/teams/%d/links/', array($teamId, array($obj)));
	}

	function createForCompetition($competitionId, $link, $name, $category, $isFeatured = false)
	{
		$obj = array(
			'link' => $link,
			'name' => ($name ? : $link),
			'category' => $category,
			'featured' => $isFeatured,
			'competitionId' => $competitionId,
		);
		
		return $this->execute('POST', '/competitions/%d/links/', array($competitionId, $obj));
	}

}

?>