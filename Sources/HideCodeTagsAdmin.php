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
	$default_action_func = 'hc_boardSettings';

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
		'boardsettings' => 'hc_boardSettings',
		'saveboardsettings' => 'hc_saveBoardSettings',
		'groupsettings' => 'hc_groupSettings',
		'savegroupsettings' => 'hc_saveGroupSettings',
	);

	//wakey wakey, call the func you lazy
	if (isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) && function_exists($subActions[$_REQUEST['sa']]))
		return $subActions[$_REQUEST['sa']]();
	$default_action_func();
}

function hc_boardSettings() {
	global $txt, $context, $sourcedir;

	/* I can has Adminz? */
	isAllowedTo('admin_forum');
	loadLanguage('HideCodeTags');
	loadtemplate('HideCodeTags');

	require_once($sourcedir . '/Subs-HideCodeTags.php');
	$context['hc_board_setting_data'] = hc_getAllBoard(true);

	$context['page_title'] = $txt['hc_admin_panel'];
	$context['sub_template'] = 'hc_admin_board_setting_panel';
	$context['hide_code']['tab_name'] = $txt['hc_board_settings'];
	$context['hide_code']['tab_desc'] = $txt['hc_board_settings_desc'];
}

function hc_saveBoardSettings() {
	global $sourcedir;

	isAllowedTo('admin_forum');
	loadLanguage('HideCodeTags');

	if (isset($_POST['submit'])) {
		checkSession();
		$_POST['hc_board_ids'] = implode(',', $_POST['hc_board_ids']);

		$general_settings = array(
			array('text', 'hc_board_ids')
		);

		require_once($sourcedir . '/ManageServer.php');
		saveDBSettings($general_settings);
		redirectexit('action=admin;area=hidecodetags;sa=boardsettings');
	}
}

function hc_groupSettings() {
	global $txt, $context, $sourcedir, $modSettings;

	/* I can has Adminz? */
	isAllowedTo('admin_forum');
	loadLanguage('HideCodeTags');
	loadtemplate('HideCodeTags');

	require_once($sourcedir . '/ManageServer.php');

	require_once($sourcedir . '/Subs-Membergroups.php');
	$context['hide_code_tag']['groups'][0] = array(
		'id_group' => 0,
		'group_name' => $txt['hc_regular_members'],
	);
	$context['hide_code_tag']['groups'] += list_getMembergroups(null, null, 'id_group', 'regular');
	unset($context['hide_code_tag']['groups'][3]);
	unset($context['hide_code_tag']['groups'][1]);

	$data = isset($modSettings['hc_group_ids']) && !empty($modSettings['hc_group_ids']) ? explode(',', $modSettings['hc_group_ids']) : array();
	foreach($context['hide_code_tag']['groups'] as $key => $group) {
		$isGroupSelected = checkIfIdExist($group['id_group'], $data);
		if(!empty($isGroupSelected))
            $context['hide_code_tag']['groups'][$key]['is_selected'] = true;
		else
			$context['hide_code_tag']['groups'][$key]['is_selected'] = false;
	}

	$context['page_title'] = $txt['hc_admin_panel'];
	$context['sub_template'] = 'hc_admin_group_setting_panel';
	$context['hide_code']['tab_name'] = $txt['hc_group_settings'];
	$context['hide_code']['tab_desc'] = $txt['hc_group_settings_desc'];
}

function hc_saveGroupSettings() {
	global $sourcedir;

	isAllowedTo('admin_forum');
	loadLanguage('HideCodeTags');

	if (isset($_POST['submit'])) {
		checkSession();
		$_POST['hc_group_ids'] = implode(',', $_POST['hc_group_ids']);

		$general_settings = array(
			array('text', 'hc_group_ids')
		);

		require_once($sourcedir . '/ManageServer.php');
		saveDBSettings($general_settings);
		redirectexit('action=admin;area=hidecodetags;sa=groupsettings');
	}
}

//Few utils for admin itself
function checkIfIdExist($itemId, $dataArray, $settingToCheck = null) {
    global $modSettings;

    if(!is_array($dataArray)) {
        $dataArray = explode(',', $modSettings[$settingToCheck]);
    }

	if(empty($dataArray)) return false;
    elseif(in_array($itemId, $dataArray)) return true;
    else return false;
}

?>