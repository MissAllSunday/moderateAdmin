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

function mA_permissions(&$permissionGroups, &$permissionList)
{
	$permissionList['board']['moderateAdmin'] = array(false, 'general_board', 'moderate');
}

function mA_settings(&$config_vars)
{
	global $txt;

	loadLanguage('moderateAdmin');

	$config_vars[] = $txt['mA_main'];
	array( 'select', 'mA_adminOptions',
			array(
				'single' => $txt['mA_singleAdmin'],
				'primary' => $txt['mA_primaryAdmin'],
				'all' => $txt['mA_allAdmins'],
			),
			'subtext' => $txt['mA_adminOptions_sub']
		),
	$config_vars[] = '';

}

function isAdmin($userID)
{
	global $smcfunc, $modSettings;

	$queryWhere = 'id_group = {int:userID}';

	if (empty($modSettings['mA_onlySuperAdmin']))
		$queryWhere .= 'OR ';
}