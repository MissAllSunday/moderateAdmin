<?php
/**
 *
 * @package moderateAdmin mod
 * @version 1.0
 * @author Jessica González <suki@missallsunday.com>
 * @copyright Copyright (c) 2013, Jessica González
 * @license http://www.mozilla.org/MPL/ MPL 2.0
 */

if (!defined('SMF'))
	die('No direct access...');

function mApermissions(&$permissionGroups, &$permissionList)
{
	$permissionList['board']['moderateAdmin'] = array(false, 'general_board', 'moderate');
}

function isAdmin($userID)
{
	global $smcfunc, $modSettings;

	$queryWhere = 'id_group = {int:userID}';

	if (empty($modSettings['mA_onlySuperAdmin']))
		$queryWhere .= 'OR ';
}