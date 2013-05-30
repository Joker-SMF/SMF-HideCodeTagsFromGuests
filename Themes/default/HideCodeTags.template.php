<?php

/**
* @package manifest file for Live clock in header
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

function template_rp_admin_info() {
	global $context, $txt, $scripturl;

	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			', $txt['hc_admin_panel'] ,'
		</h3>
	</div>
	<p class="windowbg description">', isset($context['hide_code']['tab_desc']) ? $context['hide_code']['tab_desc'] : $txt['hc_general_desc'] ,'</p>';
	
	// The admin tabs.
		echo '
	<div id="adm_submenus">
		<ul class="dropmenu">';
	
		// Print out all the items in this tab.
		$menu_buttons = $context[$context['admin_menu_name']]['tab_data'];
		foreach ($menu_buttons['tabs'] as $sa => $tab)
		{
			echo '
			<li>
				<a class="', ($menu_buttons['active_button'] == $tab['url']) ? 'active ' : '', 'firstlevel" href="', $scripturl, '?action=admin;area=hidecodetags;sa=', $tab['url'],'"><span class="firstlevel">', $tab['label'], '</span></a>
			</li>';
		}
	
		// the end of tabs
		echo '
		</ul>
	</div><br class="clear" />';
}

function template_hc_admin_board_setting_panel() {
	global $context, $txt;

	template_rp_admin_info();

	echo '
	<div id="admincenter" class="hide_code">
		<form action="'. $scripturl .'?action=admin;area=restrictposts;sa=savepostsettings" method="post" accept-charset="UTF-8">
			<div class="windowbg2">
				<span class="topslice"><span></span></span>';
				//echo $category['name'] . ' : ' . $board['board_name'] . '<br />';

				//echo json_encode($context['hc_board_setting_data']);
				foreach($context['hc_board_setting_data'] as $category_key => $category) {
					echo '
					<div class="hc_category" style="padding: 0 15px;">';

					echo '
						<p style="padding: 0; margin: 4px 0;">
							<a href="#">', $category['name'], '</a>
						</p>';

					foreach($category['boards'] as $board_key => $board) {
						echo '
						<span>', $board['board_name'], '</span>
						<input type="checkbox" id="', $board['id_board'], '" name="', $board['id_board'], '" value="', $board['id_board'],'" checked="checked" class="input_check">';
						//echo $category['name'] . ' : ' . $board['board_name'] . '<br />';
					}

					echo '
					</div>';
				}

				echo '
				<input type="submit" name="submit" value="', $txt['hc_submit'], '" tabindex="', $context['tabindex']++, '" class="button_submit" />';
	
				echo '
				<span class="botslice"><span></span></span>
			</div>
	
		</form>
	</div>
	<br class="clear">';
}

?>