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
	$config_vars[] = array('int', 'mA_uniqueAdmin', 'subtext' => $txt['mA_uniqueAdmin_sub']);
	$config_vars[] = '';

}

function mA_isAdmin($userID)
{
	global $smcfunc, $modSettings, $user_info;

	$queryWhere = '';
	$idGroup = 1;
	$admins = array();

	if (!empty($modSettings['mA_adminOptions']))
		switch ($modSettings['mA_adminOptions'])
		{
			// Been single makes things soo much easier :P
			case 'single':
				return $user_info['id'] == $userID;
				break;
			case'primary':
				$queryWhere .= 'id_group = {int:idGroup}';
				break;
			case 'all':
				$queryWhere .= 'id_group = {int:idGroup} OR FIND_IN_SET({int:idGroup}, additional_groups)';
				break;
		}

	// Get all possible admins
	$result = $this->_smcFunc['db_query']('', '
		SELECT id_member
		FROM {db_prefix}members
		WHERE '. ($queryWhere),
		array(
			'idGroup' => $idGroup
		)
	);

	while ($row = $smcFunc['db_fetch_assoc']($result))
		$Admins[] = $row['id_member'];

	$smcFunc['db_free_result']($result);

	return in_array($userID, $admins);
}