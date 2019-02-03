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
	
	// SETUP WEBSPACE -------------------------------------------
	$output_webspace = $am_core->getData(AM_DATA_PATH . 'webspace.data.php', 1);
	
	if (!empty($output_webspace)) {
		$body->set('webspace', $output_webspace);
	}


	// PROCESS FORM ---------------------------------------------
	if (isset($_POST['save_webspace_metadata'])) {

		$output_webspace['webspace_title'] = htmlspecialchars(strip_tags($_POST['webspace_title']));
	
		$am_core->saveData(AM_DATA_PATH . 'webspace.data.php', $output_webspace, 1);

		header("Location: index.php?t=setup");
		exit;
	}
	elseif (isset($_POST['set_default_webpage'])) {

		$output_webspace['default_webpage_name'] = $_POST['default_webpage_name'];

		$am_core->saveData(AM_DATA_PATH . 'webspace.data.php', $output_webspace, 1);
		
		header("Location: index.php?t=setup");
		exit;
	}
	elseif (isset($_POST['delete_webpages'])) {
		if (!empty($_POST['delete_webpage_names'])) {
			foreach($_POST['delete_webpage_names'] as $key => $i):
				$am_core->deleteData(AM_DATA_PATH . 'webpages/' . $i . '.wp.php');
			endforeach;
		}
		
		header("Location: index.php?t=setup");
		exit;
	}
	

	// SELECT WEBPAGES
	$output_webpages = $am_core->amscandir(AM_DATA_PATH . 'webpages');

	if (!empty($output_webpages)) {
		foreach ($output_webpages as $key => $i):
			$output_webpages[$key] = str_replace('.wp.php', '', $i);
		endforeach;
		
		$body->set('webpages', $output_webpages);
	}

	// SELECT STYLES
	$style_blocks = $am_core->amscandir(AM_DATA_PATH . 'styles');

	if (!empty($style_blocks)) {
		// scroll through obtaining names

		$styles = array();

		sort($style_blocks);

		foreach ($style_blocks as $key => $i):

			unset($style);

			$style = $am_core->getData(AM_DATA_PATH . 'styles/' . $i, 1);

			if (!empty($style)) {

				$style['filename'] = $i;

				array_push($styles, $style);
			}
		endforeach;

		$body->set('styles', $styles);
		
	}

	// SELECT PLUGINS
	$plugin_names = $am_core->amscandir('plugins');
	$plugins = array();
	
	if (!empty($plugin_names)) {
		foreach ($plugin_names as $key => $i):
			if (is_file('plugins/' . $i . '/language/' . $_SESSION['language_code'] . '/plugin_common.lang.php')) {
				//include_once('plugins/' . $i . '/language/' . $_SESSION['language_code'] . '/plugin_common.lang.php');
			}
			
			$plugin = array();
			$plugin['name'] = $i;
			$plugin['blocks'] = $am_core->amscandir('plugins/' . $i . '/source_blocks');
			
			array_push($plugins, $plugin);
		endforeach;
	}
	
	$body->set('plugins', $plugins);
}
else {
	header("Location: index.php");
	exit;
}

?>