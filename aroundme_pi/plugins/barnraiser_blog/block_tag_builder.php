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


include ("../../core/config/aroundme_core.config.php");
include ("../../core/inc/functions.inc.php");


define("AM_DATA_PATH", "../../" . $core_config['data']['dir']);


// START SESSION -----------------------------------------------------------
session_name($core_config['node']['php_session_name']);
session_start();


if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {

	// SETUP STORAGE ---------------------------------------------------------------------------
	require_once('../../core/class/Storage.class.php');
	$am_core = new Storage($core_config);


	// SETUP TEMPLATE -------------------------------------------
	define("AM_TEMPLATE_PATH", "template/");

	require_once('../../core/class/Template.class.php');
	$tpl = new Template();


	// SETUP LANGUAGE ------------------------------------------------------
	$_SESSION['language_code'] = $core_config['aroundme_language_code'];

	if (array_key_exists(strtoupper($_SESSION['language_code']), $installed_server_language_packs)) {
		$locale_code = $installed_server_language_packs[strtoupper($_SESSION['language_code'])];

		setlocale(LC_ALL, $locale_code);
	}

	define("AM_LANGUAGE_PATH", "language/" . $_SESSION['language_code'] . "/");

	$lang = array();
	include_once('../../core/' . AM_LANGUAGE_PATH . 'common.lang.php');
	include_once(AM_LANGUAGE_PATH . 'block_tag_builder.lang.php');


	

	// CREATE A LIST OF WEBPAGES
	$webpages = $am_core->amscandir(AM_DATA_PATH . 'webpages');
	
	if (!empty($webpages)) {
		
		sort($webpages);
		
		foreach ($webpages as $key => $i):
			$webpages[$key] = str_replace('.wp.php', '', $i);
		endforeach;

		$tpl->set('webpages', $webpages);
	}
	
	$tpl->set('lang', $lang);


	echo $tpl->fetch(AM_TEMPLATE_PATH . 'block_tag_builder.tpl.php');

}
?>