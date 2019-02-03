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
	// check that our webpage name is valid ------------------------------
	if (isset($_REQUEST['wp'])) {
	
		$pattern = "/^[a-zA-Z0-9]*$/";
	
		if (!preg_match($pattern, $_REQUEST['wp'])) {
			header("Location: index.php");
			exit;
		}
	
		if (strlen($_REQUEST['wp']) > 30) { // link too long
			header("Location: index.php");
			exit;
		}
	
		define("AM_WEBPAGE_NAME", $_REQUEST['wp']);
	}
	else {
		// no webpage - we error
		header("Location: index.php");
		exit;
	}


	// update webpage ----------------------------------------------------
	if (isset($_POST['save_webpage']) || isset($_POST['save_go_webpage'])) {
	
		$webpage_body = stripslashes($_POST['webpage_body']);

		// cannot do this now that PHP is included - need better solution
		//$webpage_body = str_replace("&amp;", "&", $webpage_body);
		//$webpage_body = str_replace("&", "&amp;", $webpage_body);
		
		$am_core->saveData(AM_DATA_PATH . 'webpages/' . AM_WEBPAGE_NAME . '.wp.php', $webpage_body);

		if (isset($_POST['save_go_webpage'])) {
			header("Location: index.php?wp=" . AM_WEBPAGE_NAME);
			exit;
		}
		else {
			header("Location: index.php?t=webpage&wp=" . AM_WEBPAGE_NAME);
			exit;
		}
	}



	// ascertain if we have a webpage or not
	if (is_file(AM_DATA_PATH . 'webpages/' . AM_WEBPAGE_NAME . '.wp.php')) {
		$output_webpage = $am_core->getData(AM_DATA_PATH . 'webpages/' . AM_WEBPAGE_NAME . '.wp.php');
	}
	else {
		$output_webpage = "";
	}

	$body->set('webpage', $output_webpage);



	// BUILD EDITOR HELPERS
	// SELECT PLUGINS
	$plugins = $am_core->amscandir('plugins');

	if (!empty($plugins)) {
		foreach ($plugins as $key => $i):
			if (is_file('plugins/' . $i . '/language/' . $_SESSION['language_code'] . '/plugin_common.lang.php')) {
				include_once('plugins/' . $i . '/language/' . $_SESSION['language_code'] . '/plugin_common.lang.php');
			}
		endforeach;
	}
	
	$body->set('plugins', $plugins);


	$output_webpages = $am_core->amscandir(AM_DATA_PATH . 'webpages');
	
	if (!empty($output_webpages)) {
		foreach ($output_webpages as $key => $i):
			$output_webpages[$key] = str_replace('.wp.php', '', $i);
		endforeach;
		
		$body->set('webpages', $output_webpages);
	}
	
	$webpage_layouts = $am_core->amscandir('layouts');
	
	if (!empty($webpage_layouts)) {
		$body->set('webpage_layouts', $webpage_layouts);
	}
	
	
	// GET IMAGES ----------------------------------
	include_once('core/class/Image.class.php');
	$image = new Image($core_config);
	
	// fetch all pictures
	$output_picture_filenames = $am_core->amscandir(AM_DATA_PATH . $image->path);
	
	
	if (!empty($output_picture_filenames)) {
		
		$output_pictures = array();
		
		foreach($output_picture_filenames as $key => $val) {
			$thumb = substr($val, -7, 3);
			if ($thumb == '100') {
				$output_pictures[$key]['thumb'] = $val;
				$output_pictures[$key]['src'] = str_replace('_100', '', $val);
			}
		}
	
		if (!empty($output_pictures)) {
			$body->set('pictures', $output_pictures);
		}
	}

}
else {
	header("Location: index.php");
	exit;
}

?>