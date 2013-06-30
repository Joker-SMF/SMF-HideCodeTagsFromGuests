<?php

/**
* @package manifest file for Hide the content of [code] tags from guests
* @version 1.2
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

function HC_addAdminPanel(&$admin_areas) {
	global $txt;

	loadLanguage('HideCodeTags');
	loadtemplate('HideCodeTags');
	$admin_areas['config']['areas']['hidecodetags'] = array(
		'label' => $txt['hc_admin_menu'],
		'file' => 'HideCodeTagsAdmin.php',
		'function' => 'HideCodeTagsAdminPanel',
		'icon' => 'administration.gif',
		'subsections' => array(),
	);
}

?>