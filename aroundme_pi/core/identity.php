<?php

// -----------------------------------------------------------------------
// This file is part of AROUNDMe
// 
// Copyright (C) 2003-2008 Barnraiser
// http://www.barnraiser.org/
// info@barnraiser.org
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program; see the file COPYING.txt.  If not, see
// <http://www.gnu.org/licenses/>
// -----------------------------------------------------------------------


if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {

	// SETUP IDENTITY -------------------------------------------
	include_once(AM_LANGUAGE_PATH . 'identity_field_options.lang.php');

	$output_identity = $am_core->getData(AM_DATA_PATH . 'identity.data.php', 1);
	
	// SETUP IMAGE ----------------------------------------------
	include_once('class/Image.class.php');
	$image = new Image($core_config);
	$image->path = "avatars/";

	if (isset($_POST['save_identity'])) {
		// levels: 0 = just me, 1 = friends, 2= friends+allies, 3= everyone
		if (!empty($_POST['identity'])) {
			foreach ($_POST['identity'] as $key => $i):
		
				if ($core_config['identity_field'][$key] == "select" || $core_config['identity_field'][$key] == "radio") {
					if ($i != "0") {
						$output_identity[$key] = $i;
					}
					else {
						unset($output_identity[$key]);
					}
				}
				elseif ($core_config['identity_field'][$key] == "textarea") {
					$output_identity[$key] = am_parse($i);
				}
				else { // text 
					$output_identity[$key] = htmlspecialchars($i);
				}
				
				// update session data
				if ($key == "nickname") {
					$_SESSION['openid_nickname'] = $i;
				}
				elseif ($key == "fullname") {
					$_SESSION['openid_fullname'] = $i;
				}
			endforeach;
		}

		$am_core->saveData(AM_DATA_PATH . 'identity.data.php', $output_identity, 1);
		
		header("Location: index.php?t=identity");
		exit;
	}
	elseif (isset($_POST['submit_upload_avatar'])) {

		if (isset($_FILES['frm_file']) && !empty($_FILES['frm_file']['tmp_name'])) {
			$image->uploadImage();
			
			// if it is out first avatar we automatically set this to current
			if (empty($output_identity['avatar'])) {
				$new_avatar = $am_core->amscandir(AM_DATA_PATH . 'avatars');
				
				if (!empty($new_avatar[0])) {
					$output_identity['avatar'] = $new_avatar[0];
					$am_core->saveData(AM_DATA_PATH . 'identity.data.php', $output_identity, 1);
				}
			}

			header("Location: index.php?t=identity");
			exit;
		}
	}
	elseif (isset($_POST['submit_set_avatar'])) {
	
		if (!empty($_POST['current_avatar_name'])) {
			$output_identity['avatar'] = $_POST['current_avatar_name'];
	
			$am_core->saveData(AM_DATA_PATH . 'identity.data.php', $output_identity, 1);
		}
	
		header("Location: index.php?t=identity");
		exit;
	}
	elseif (isset($_POST['submit_delete_avatar'])) {

		if (!empty($_POST['delete_avatar_name'])) {
			$image->deleteImages($_POST['delete_avatar_name']);

		}

		header("Location: index.php?t=identity");
		exit;
	}

	// Render textareas for forms
	foreach ($core_config['identity_field'] as $key => $i):
	
		if ($i == "textarea" && !empty($output_identity[$key])) {
			$output_identity[$key] = am_render($output_identity[$key]);
		}
	endforeach;
	
	if (!empty($output_identity)) {
		$body->set('identity', $output_identity);
	}

	
	// SETUP AVATARS ------------------------------------------------------
	$avatars = array();
	$output_avatars = $am_core->amscandir(AM_DATA_PATH . 'avatars');

	
	foreach($output_avatars as $key => $val) {
		$avatar_file = substr($val, -7, 3);
		if ($avatar_file == '100') {
			array_push($avatars, $val);
		}
	}
	
	if (!empty($avatars)) {
		$body->set('avatars', $avatars);
	}
	
	require_once(AM_LANGUAGE_PATH . 'identity_field_options.lang.php');
	
	// CUSTIOMISE LANGUAGE
	$lang['txt_identity_intro'] = str_replace('SYS_KEYWORD_OPENID', $core_config['openid_account'], $lang['txt_identity_intro']);
	
}
else {
	header("Location: index.php");
	exit;
}


?>