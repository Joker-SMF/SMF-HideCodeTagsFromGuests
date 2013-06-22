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

function hc_getAllBoard($checkSelected = false) {
    global $smcFunc, $modSettings;

    $request = $smcFunc['db_query']('', '
		SELECT b.id_board, b.board_order, b.name as board_name, c.id_cat, c.name AS cat_name
		FROM {db_prefix}boards as b
            LEFT JOIN {db_prefix}categories AS c ON (c.id_cat = b.id_cat)
        WHERE redirect = {string:empty_string}',
		array(
			'empty_string' => '',
		)
	);

	$data = array();
    if(isset($checkSelected)) {
        $boardSelectedArray = isset($modSettings['hc_board_ids']) && !empty($modSettings['hc_board_ids']) ? explode(',', $modSettings['hc_board_ids']) : array();
    }

	while ($row = $smcFunc['db_fetch_assoc']($request)) {
        if(!isset($data[$row['id_cat']])) {
            $data[$row['id_cat']] = array(
                'name' => $row['cat_name'],
            );
        }
        $data[$row['id_cat']]['boards'][$row['id_board']] = array(
            'id_board' => $row['id_board'],
            'board_name' => $row['board_name'],
        );

        if(isset($checkSelected)) {
            $needToHide = checkIfIdExist($row['id_board'], $boardSelectedArray);
            if(!empty($needToHide))
                $data[$row['id_cat']]['boards'][$row['id_board']]['is_selected'] = true;
            else
                $data[$row['id_cat']]['boards'][$row['id_board']]['is_selected'] = false;
        }
	}
	$smcFunc['db_free_result']($request);

	return $data;
}

?>