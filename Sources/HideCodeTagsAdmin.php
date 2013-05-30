<?php

/**
* @package manifest file for Hide the content of [code] tags from guests
* @version 1.1
* @author Joker (http://www.simplemachines.org/community/index.php?action=profile;u=226111)
* @copyright Copyright (c) 2012, Siddhartha Gupta
* @license http://www.mozilla.org/MPL/MPL-1.1.html
*/

/*
* Version: MPL 1.1
*
* The contents of this file are subject to the Mozilla Public License Version
* 1.1 (the "License"); you may not use this file except in compliance with
* the License. You may obtain a copy of the License at
* http://www.mozilla.org/MPL/
*
* Software distributed under the License is distributed on an "AS IS" basis,
* WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
* for the specific language governing rights and limitations under the
* License.
*
* The Initial Developer of the Original Code is
*  Joker (http://www.simplemachines.org/community/index.php?action=profile;u=226111)
* Portions created by the Initial Developer are Copyright (C) 2012
* the Initial Developer. All Rights Reserved.
*
* Contributor(s):
*
*/

if (!defined('SMF'))
	die('Hacking attempt...');

function HideCodeTagsAdminPanel($return_config = false) {
	global $txt, $context;

	/* I can has Adminz? */
	isAllowedTo('admin_forum');
	loadLanguage('HideCodeTags');

	$context['page_title'] = $txt['hc_admin_panel'];
	$default_action_func = 'hC_boardSettings';

	$context[$context['admin_menu_name']]['tab_data'] = array(
		'title' => $txt['hc_admin_panel'],
		'tabs' => array(
			'boardsettings' => array(
				'label' => $txt['hc_board_settings'],
				'url' => 'boardsettings',
			),
			'groupsettings' => array(
				'label' => $txt['hc_group_settings'],
				'url' => 'groupsettings',
			),
		),
	);
	$context[$context['admin_menu_name']]['tab_data']['active_button'] = isset($_REQUEST['sa']) ? $_REQUEST['sa'] : 'boardsettings';

	$subActions = array(
		'boardsettings' => 'hC_boardSettings',
		'saveboardsettings' => 'hc_saveBoardSettings',
		'groupsettings' => 'hC_groupSettings',
		'savegroupsettings' => 'hc_saveGroupSettings',
	);

	//wakey wakey, call the func you lazy
	if (isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) && function_exists($subActions[$_REQUEST['sa']]))
		return $subActions[$_REQUEST['sa']]();
	$default_action_func();
}

function hC_boardSettings() {
	global $txt, $context, $sourcedir;

	/* I can has Adminz? */
	isAllowedTo('admin_forum');
	loadLanguage('HideCodeTags');
	loadtemplate('HideCodeTags');

	require_once($sourcedir . '/Subs-HideCodeTags.php');
	$context['hc_board_setting_data'] = hc_getAllBoard();

	$general_settings = array(
		array('check', 'hc_board_ids'),
	);

	$context['page_title'] = $txt['hc_admin_panel'];
	$context['sub_template'] = 'hc_admin_board_setting_panel';
	$context['hide_code']['tab_name'] = $txt['hc_board_settings'];
	$context['hide_code']['tab_desc'] = $txt['hc_board_settings_desc'];
}

function hc_saveBasicSettings() {
	global $sourcedir;

	isAllowedTo('admin_forum');
	loadLanguage('HideCodeTags');

	if (isset($_POST['submit'])) {
		checkSession();

		$general_settings = array(
			array('check', 'lc_mod_enable'),
			array('check', 'lc_show_timezone_dropdown'),
			array('check', 'lc_24_hr_format'),
			array('check', 'lc_show_date'),
		);

		require_once($sourcedir . '/ManageServer.php');
		saveDBSettings($general_settings);
		redirectexit('action=admin;area=hidecodetags;sa=boardsettings');
	}
}

?>