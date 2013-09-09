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
	$permissionGroups['membergroup']['simple'] = array('mA_per_simple');
	$permissionGroups['membergroup']['classic'] = array('mA_per_classic');
	$permissionList['membergroup']['moderateAdmin'] = array(
		false,
		'mA_per_classic',
		'mA_per_simple');
}