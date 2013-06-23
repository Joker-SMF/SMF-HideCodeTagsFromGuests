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
	global $context, $txt, $scripturl;

	template_rp_admin_info();

	echo '
	<div id="admincenter" class="hide_code">
		<form action="'. $scripturl .'?action=admin;area=hidecodetags;sa=saveboardsettings" method="post" accept-charset="UTF-8">
			<div class="windowbg2">
				<span class="topslice"><span></span></span>';

				foreach($context['hc_board_setting_data'] as $category_key => $category) {
					echo '
					<div class="hct_category">';

					echo '
						<p class="hct_category_title">
							<a href="javascript:void(0)" id="', $category_key, '" onclick="selectBoards([', implode(', ', array_keys($category['boards'])), '], ', $category_key, '); return false;">', $category['name'], '</a>
						</p>';

					foreach($category['boards'] as $board_key => $board) {
						echo '
						<div class="board_desc">
							<span>', $board['board_name'], '</span>
							<input type="checkbox" id="board_', $board['id_board'], '" name="hc_board_ids[]"', (isset($board['is_selected']) && !empty($board['is_selected']) ? ' checked="checked"' : ''), ' value="', $board['id_board'],'" class="input_check" />
						</div>';
					}

					echo '
					</div>';
				}

				echo '
				<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
				<input type="submit" class="submit_button" name="submit" value="', $txt['hc_submit'], '" tabindex="', $context['tabindex']++, '" class="button_submit" />';
	
				echo '
				<span class="botslice"><span></span></span>
			</div>
	
		</form>
	</div>
	<br class="clear">';

	echo '
	<script type="text/javascript"><!-- // --><![CDATA[
		if(!Array.indexOf){
			Array.prototype.indexOf = function(input) {
				for(var i = 0; i < this.length; i++) {
					if(this[i] == input) {
						return i;
					}
				}
				return -1;
			}
		}

		function selectBoards(ids, catId) {
			var isChecked = true;

			for (i = 0; i < ids.length; i++) {
				if(document.getElementById("board_" + ids[i]).checked == true && isChecked) {
					isChecked = true;
				} else {
					isChecked = false;
				}
			}
			for (i = 0; i < ids.length; i++) {
				if(!isChecked) document.getElementById("board_" + ids[i]).checked = true;
				else document.getElementById("board_" + ids[i]).checked = false;
			}
		}
	// ]]></script>';
}

function template_hc_admin_basic_setting_panel() {
	global $context, $txt, $scripturl;

	template_rp_admin_info();

	echo '
	<div id="admincenter" class="hide_code">
		<form action="'. $scripturl .'?action=admin;area=hidecodetags;sa=savebasicsettings" method="post" accept-charset="UTF-8">
			<div class="windowbg2">
				<span class="topslice"><span></span></span>';

				foreach ($context['config_vars'] as $config_var) {
					echo '
					<dl class="settings hct_settings">
						<dt>
							<span>'. $txt[$config_var['name']] .'</span>';
							if (isset($config_var['subtext']) && !empty($config_var['subtext'])) {
								echo '
								<br /><span class="smalltext">', $config_var['subtext'] ,'</span>';
							}
						echo '
						</dt>
						<dd>';

						if ($config_var['type'] == 'check')
							echo '
							<input type="checkbox" name="', $config_var['name'], '" id="', $config_var['name'], '"', ($config_var['value'] ? ' checked="checked"' : ''), ' value="1" class="input_check" />';

						elseif ($config_var['type'] == 'large_text')
							echo '
							<textarea rows="4" cols="50" name="', $config_var['name'], '">', $config_var['value'], '</textarea>';

						echo '
						</dd>
					</dl>';
				}

				echo '
				<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
				<input type="submit" class="submit_button" name="submit" value="', $txt['hc_submit'], '" tabindex="', $context['tabindex']++, '" class="button_submit" />';
	
				echo '
				<span class="botslice"><span></span></span>
			</div>
	
		</form>
	</div>
	<br class="clear">';
}

?>