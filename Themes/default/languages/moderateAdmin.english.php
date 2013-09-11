<?php
/**
 *
 * @package moderateAdmin mod
 * @version 1.0
 * @author Jessica González <suki@missallsunday.com>
 * @copyright Copyright (c) 2013, Jessica González
 * @license http://www.mozilla.org/MPL/ MPL 2.0
 */

global $txt;

$txt['mA_main'] = 'moderate admin';

// Permissions
$txt['cannot_moderateAdmin'] = 'I\'m sorry, you are not allowed to moderate Admin\'s message.';
$txt['permissionname_moderateAdmin'] = 'Moderate Admin\'s messages';

// Settings
$txt['mA_adminOptions'] = 'Who are going to be protected by the moderate permission?';
$txt['mA_adminOptions_sub'] = 'If you select the "single admin" option make sure to specify a valid ID in the setting below, if no setting is provided, the mod will use ID 1';
$txt['mA_singleAdmin'] = 'A single admin';
$txt['mA_primaryAdmin'] = 'All users who have an admin account as primary group.';
$txt['mA_allAdmins'] = 'All admins, including users who have an admin group as secondary.';
$txt['ma_uniqueAdmin'] = 'Set the user ID for the single admin who will be protected by the moderate permission.';
$txt['ma_uniqueAdmin_sub'] = 'Must be a valid user ID, if its not set the mod will use the ID 1.';