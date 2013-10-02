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
	loadLanguage('moderateAdmin');

	$permissionList['board']['moderateAdmin'] = array(false, 'general_board', 'moderate');
}

function mA_settings(&$config_vars)
{
	global $txt;

	loadLanguage('moderateAdmin');

	$config_vars[] = $txt['mA_main'];
	$config_vars[] = array( 'select', 'mA_adminOptions',
			array(
				'single' => $txt['mA_singleAdmin'],
				'primary' => $txt['mA_primaryAdmin'],
				'all' => $txt['mA_allAdmins'],
			),
			'subtext' => $txt['mA_adminOptions_sub']
		);
	$config_vars[] = array('int', 'mA_uniqueAdmin', 'subtext' => $txt['mA_uniqueAdmin_sub']);
	$config_vars[] = '';
}

function mA_isAdmin($userID)
{
	global $smcFunc, $modSettings, $user_info;

	if (empty($userID) || empty($modSettings['mA_adminOptions']))
		return false;

	/**
	* Load the text strings, we're going to use the error ones
	* Disclaimer, I don't actually use any text string here... I'm only loading the language file.
	* This is to avoid having to load the language file on every call to this function, it saves some code lines, yes I'm lazy...
	*/
	loadLanguage('moderateAdmin');

	$queryWhere = '';
	$idGroup = 1; // @todo make this an admin setting and allow more admin groups
	$ma_uniqueAdmin = !empty($modSettings['mA_uniqueAdmin']) ? $modSettings['mA_uniqueAdmin'] : 1;

	// Avoid doing a query if we only need to check for a single user
	if ($modSettings['mA_adminOptions'] == 'single')
		return $ma_uniqueAdmin == $userID;

	// Use the cache
	if (($admins = cache_get_data('mA-Admins-List', 360)) == null)
	{
		if (!empty($modSettings['mA_adminOptions']))
			switch ($modSettings['mA_adminOptions'])
			{
				case'primary':
					$queryWhere .= 'id_group = {int:idGroup}';
					break;
				case 'all':
					$queryWhere .= 'id_group = {int:idGroup} OR FIND_IN_SET({int:idGroup}, additional_groups)';
					break;
			}

		else
			return false;

		// Set it as empty
		$admins = array();

		// Get all possible admins
		$result = $smcFunc['db_query']('', '
			SELECT id_member
			FROM {db_prefix}members
			WHERE '. ($queryWhere),
			array(
				'idGroup' => $idGroup
			)
		);

		while ($row = $smcFunc['db_fetch_assoc']($result))
			$admins[] = $row['id_member'];

		$smcFunc['db_free_result']($result);

		cache_put_data('mA-Admins-List', $admins, 360);
	}

	return in_array($userID, $admins);
}

function mA_displayButtons(&$mod_buttons)
{
	global $context, $modSettings;

	// This is easy, we unset the var if the topic starter is an admin...
	if (!empty($context['topic_starter_id']) && ma_isAdmin($context['topic_starter_id']))
		unset($mod_buttons['delete'], $mod_buttons['lock'], $mod_buttons['sticky'], $mod_buttons['merge']);
}
