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
	$config_vars[] = $txt['mA_coverOptions'];
	$config_vars[] = array('check', 'mA_edit');
	$config_vars[] = array('check', 'mA_delete');
	$config_vars[] = array('check', 'mA_sticky');
	$config_vars[] = array('check', 'mA_move');
	$config_vars[] = array('check', 'mA_lock');
	$config_vars[] = array('check', 'mA_merge');
	$config_vars[] = '';

}

function mA_isAdmin($userID)
{
	global $smcFunc, $modSettings, $user_info;

	/**
	* Load the text strings, we're going to use the error ones
	* Disclaimer, I don't actually use any text string here... I'm only loading the language file here.
	* This is to avoid having to load the language file on every call to this function, it saves some code lines, yes I'm lazy... 
	*/
	loadLanguage('moderateAdmin');

	$queryWhere = '';
	$idGroup = 1; // @todo make this an admin setting and allow more admin groups

	// Use the cache
	if (($Admins = cache_get_data('mA-Admins-List', 360)) == null)
	{
		if (!empty($modSettings['mA_adminOptions']))
			switch ($modSettings['mA_adminOptions'])
			{
				case'primary':
					$queryWhere .= 'id_group = {int:idGroup}';
					break;
				case 'all':
				// Been single makes things soo much easier :P
				case 'single':
					$queryWhere .= 'id_group = {int:idGroup} OR FIND_IN_SET({int:idGroup}, additional_groups)';
					break;
			}

		// Set it as empty
		$Admins = array();

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
			$Admins[] = $row['id_member'];

		$smcFunc['db_free_result']($result);

		cache_put_data('mA-Admins-List', $Admins, 360);
	}

	return in_array($userID, $Admins);
}

function mA_displayButtons(&$mod_buttons)
{
	global $context;

	// This is easy, we unset the var if the topic starter is an admin...
	if (!empty($context['topic_starter_id']) && ma_isAdmin($context['topic_starter_id']))
		unset($mod_buttons['move'], $mod_buttons['delete'], $mod_buttons['lock'], $mod_buttons['sticky'], $mod_buttons['merge']);
}
