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


include ("config/aroundme_core.config.php");
include ("inc/functions.inc.php");


define("AM_DATA_PATH", "../" . $core_config['data']['dir']);


// START SESSION -----------------------------------------------------------
session_name($core_config['node']['php_session_name']);
session_start();


if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {

	// SETUP STORAGE ---------------------------------------------------------------------------
	require_once('class/Storage.class.php');
	$am_core = new Storage($core_config);


	// SETUP TEMPLATE -------------------------------------------
	define("AM_TEMPLATE_PATH", "template/");
	
	require_once('class/Template.class.php');
	$tpl = new Template();


	// SETUP LANGUAGE ------------------------------------------------------
	$_SESSION['language_code'] = $core_config['aroundme_language_code'];

	if (array_key_exists(strtoupper($_SESSION['language_code']), $installed_server_language_packs)) {
		$locale_code = $installed_server_language_packs[strtoupper($_SESSION['language_code'])];

		setlocale(LC_ALL, $locale_code);
	}

	define("AM_LANGUAGE_PATH", "language/" . $_SESSION['language_code'] . "/");

	$lang = array();
	include_once(AM_LANGUAGE_PATH . 'common.lang.php');
	include_once(AM_LANGUAGE_PATH . 'stylesheet_editor.lang.php');


	// SETUP WEBSPACE -------------------------------------------
	$output_webspace = $am_core->getData(AM_DATA_PATH . 'webspace.data.php', 1);

	

	// Selects and displays styles
	// If no styles are present then we skip to create a style
	// if no $_REQUEST['style'] we display default style
	if (isset($_POST['save_stylesheet'])) {

		checkFileName($_POST['style_filename']);

		$style = array();
		
		if (get_magic_quotes_gpc()) {
			$_POST['style_name'] = stripslashes($_POST['style_name']);
			$_POST['style_css'] = stripslashes($_POST['style_css']);
		}
		
		$style['name'] = $_POST['style_name'];
		$style['css'] = $_POST['style_css'];
	
		
		if (empty($GLOBALS['am_error_log'])) {
		
			$am_core->saveData(AM_DATA_PATH . 'styles/' . $_POST['style_filename'] . '.css', $style, 1);
		
			$tpl->set('update_mother', 1);

			$_REQUEST['style'] = $_POST['style_filename'];
		}
		else {
			// the filename is incorrect so we error
			$style['tmp_filename'] = $_POST['style_filename'];
			
			$tpl->set('style', $style);
		} 
	}
	elseif (isset($_POST['set_current_webspace_style'])) {

		$output_webspace['webspace_css'] = $_POST['default_style_name'];

		$am_core->saveData(AM_DATA_PATH . 'webspace.data.php', $output_webspace, 1);

		$tpl->set('update_mother', 1);
	}
	elseif (isset($_POST['delete_webspace_styles'])) {
		if (!empty($_POST['delete_style_names'])) {
			foreach($_POST['delete_style_names'] as $key => $i):
				$am_core->deleteData(AM_DATA_PATH . 'styles/' . $i . '.css');
			endforeach;
		}

		header("Location: stylesheet_editor.php");
		exit;
	}


	// GET STYLESHEET FOR TEMPLATE display only ----------------
	$output_style = $am_core->getData(AM_DATA_PATH . 'styles/' . $output_webspace['webspace_css'] . '.css', 1);
	$output_webspace['css'] = $output_style['css'];


	if (isset($_REQUEST['style'])) {
		$style = $am_core->getData(AM_DATA_PATH . 'styles/' . $_REQUEST['style'] . '.css', 1);

		if (isset($style)) {
			$style['filename'] = $_REQUEST['style'];
			
			$tpl->set('style', $style);
		}
	}
	else {
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

					$style['filename'] = str_replace('.css', '', $i);

					array_push($styles, $style);
				}
			endforeach;

			$tpl->set('styles', $styles);
		}
	}

	$tpl->set('lang', $lang);

	$tpl->set('webspace', $output_webspace);


	echo $tpl->fetch(AM_TEMPLATE_PATH . 'stylesheet_editor.tpl.php');
}
else {
	echo '<html><head><body onload="javascript:self.close();">closing...</body></head></html>';
	exit;
}

?>