<?php

// ---------------------------------------------------------------------
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
// --------------------------------------------------------------------

if (isset($_GET['image'])) {
	
	if (isset($_GET['layout'])) {
		$image = '../installation/webspace/img/' . $_GET['image'];
	}
	else {
		$image = '../installation/template/img/' . $_GET['image'] . '.png';
	}
	header("Content-type: image/png");
	readfile($image);
	exit;
}


include_once ("core/config/aroundme_core.config.php");
include_once ("core/inc/functions.inc.php");


define("AM_DATA_PATH", $core_config['data']['dir']);

// SESSION HANDLER ----------------------------------------------------------------------------
// sets up all session and global vars 
session_name($core_config['node']['php_session_name']);
session_start();


// ERROR HANDLING ----------------------------------------------------------------------------
// this is accessed and updated with all errors thoughtout this build
// processing regularly checks if empty before continuing
$GLOBALS['am_error_log'] = array();


// SETUP AROUNDMe CORE -----------------------------------------------------------------------
require_once('core/class/Storage.class.php');
$am_core = new Storage($core_config);


// SETUP TEMPLATES ---------------------------------------------------------------------------
define("AM_TEMPLATE_PATH", "../installation/template/");
define("AM_TEMPLATE_PATH_CORE", "aroundme_pi/core/template/");
require_once('core/class/Template.class.php');
$tpl = new Template(); // outer template
$body = new Template(); // inner template



// SETUP LANGUAGE ----------------------------------------------------------------------------
$_SESSION['language_code'] = $core_config['aroundme_language_code'];

if (array_key_exists(strtoupper($_SESSION['language_code']), $installed_server_language_packs)) {
	$locale_code = $installed_server_language_packs[strtoupper($_SESSION['language_code'])];

	setlocale(LC_ALL, $locale_code);
}

$lang = array();

include_once('core/language/' . $_SESSION['language_code'] . '/common.lang.php');
include_once('core/language/' . $_SESSION['language_code'] . '/identity_field_options.lang.php');
include_once('../installation/language/' . $_SESSION['language_code'] . '/installer.lang.php');


// ADD ANY PROCESSING FORM SCRIPTS HERE

if (isset($_POST['start_install'])) {
	// we add openid to config
	
	$openid_url = $_POST['openid_url'];
	if ($openid_url == "0") {
		include_once('core/class/OpenidCommon.class.php');
	
		$openid_url = OpenidCommon::normalize($_POST['openid_url_4']);
		if (!preg_match('@^(?:http://)?([^/]+)@i', $openid_url)) {
			$GLOBALS['am_error_log'][] = array('This is not a valid url');
		}
	}
		
	if (empty($GLOBALS['am_error_log'])) {
		writeToConfig('$core_config[\'openid_account\']', $openid_url);
		$tpl->set('display', 'step1');
	}
	else {
		$output_openid_url['openid_url_1'] = 'http://' . $_SERVER['SERVER_NAME'];
		$tpl->set('openid_url', $output_openid_url);
	}
}
elseif (isset($_POST['step1'])) {
	// We copy the CSS and WP from /installation/webspace/ to /data9648
	// We create webspace.data.php and store it in the /data9648 dir
	
	if (empty($_POST['webspace_title'])) {
		$GLOBALS['am_error_log'][] = array("Webspace title not set");
	}
	
	if (empty($GLOBALS['am_error_log'])) {
	
		if (!is_dir(AM_DATA_PATH . 'webpages/')) {
			mkdir(AM_DATA_PATH . 'webpages/', 0770, 1);
		}
		
		if (!is_dir(AM_DATA_PATH . 'styles/')) {
			mkdir(AM_DATA_PATH . 'styles/', 0770, 1);
		}
	
		@copy('../installation/webspace/layouts/' . $_POST['layout'] . '.wp.php', AM_DATA_PATH . 'webpages/' . $_POST['layout'] . '.wp.php');
		$output_webspace['webspace_title'] = htmlspecialchars(strip_tags($_POST['webspace_title']));
		$output_webspace['default_webpage_name'] = $_POST['layout'];
		$output_webspace['webspace_css'] = 'barnraiser_' . $_POST['css'];
	
		$am_core->saveData(AM_DATA_PATH . 'webspace.data.php', $output_webspace, 1);

		// create css
		$css = array();
		$css['css'] = $am_core->getData('../installation/webspace/css/barnraiser_' . $_POST['css'] . '.css');
		$css['name'] = $_POST['css'];
		$am_core->saveData(AM_DATA_PATH . 'styles/barnraiser_' . $_POST['css'] . '.css', $css, 1);
		
		$tpl->set('display', 'step2');
	}
	else {
		$tpl->set('display', 'step1');
	}
}
elseif (isset($_POST['step2'])) {
	// we create indentity.data.php and put it in the /data9648 dir
	// we upload an avatar to  the /data9648 dir
	// we store PW
	if (empty($_POST['identity']['nickname'])) {
		$GLOBALS['am_error_log'][] = array("Nickname not set");
	}
	
	if (empty($_POST['password1'])) {
		$GLOBALS['am_error_log'][] = array("Password not set");
	}
	
	if (empty($_POST['password2'])) {
		$GLOBALS['am_error_log'][] = array("Password not confirmed");
	}
	
	if (empty($GLOBALS['am_error_log'])) {
		if ($_POST['password1'] != $_POST['password2']) {
			$GLOBALS['am_error_log'][] = array("Password not verified");
		}
	}
	
	if (isset($_FILES['frm_file']) && !empty($_FILES['frm_file']['tmp_name']) && empty($GLOBALS['am_error_log'])) {
		// SETUP IMAGE ----------------------------------------------
		include_once('core/class/Image.class.php');
		$image = new Image($core_config);
		$image->path = "avatars/";
		$image->uploadImage();
	}
	
	if (empty ($GLOBALS['am_error_log'])) {
		
		writeToConfig('$core_config[\'openid_md5\']', md5($_POST['password1']));
		
		$output_identity = array();
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
			$output_identity['level'][$key] = 64;
		
			// update session data
			if ($key == "nickname") {
				$_SESSION['openid_nickname'] = $i;
			}
			elseif ($key == "fullname") {
				$_SESSION['openid_fullname'] = $i;
			}
		endforeach;
		
		$new_avatar = $am_core->amscandir(AM_DATA_PATH . 'avatars');
		
		if (!empty($new_avatar[0])) {
			$output_identity['avatar'] = $new_avatar[0];
			$output_identity['level']['avatar'] = 64;
		}
		$am_core->saveData(AM_DATA_PATH . 'identity.data.php', $output_identity, 1);
		
		$tpl->set('display', 'step3');
	}
	else {
		$tpl->set('display', 'step2');
	}
}
elseif (isset($_POST['step3'])) {
	// we store email vars in config
	
	// will chmod the installer.php to something so that no one can use.
	// We make this script so that If I chmod it to 770 I can run the script and for instance, change the password.
	
	// we should set the session so that we are 'logged in, , then we go to.
	
	if (!empty($_POST['email']['email_host'])) {
		writeToConfig('$mail_config[\'host\']', $_POST['email']['email_host']);
	}
	
	if (!empty($_POST['email']['email_address'])) {
		writeToConfig('$mail_config[\'email_address\']', $_POST['email']['email_address']);
	}
	
	if (!empty($_POST['email']['smtp_user'])) {
		writeToConfig('$mail_config[\'smtp\'][\'username\']', $_POST['email']['smtp_user']);
	}
	
	if (!empty($_POST['email']['smtp_password'])) {
		writeToConfig('$mail_config[\'smtp\'][\'password\']', $_POST['email']['smtp_password']);
	}
	
	// user is set as logged in
	$_SESSION['logged_in'] = 1;
	$_SESSION['permission'] = 64;
	$_SESSION['openid_identity'] = $core_config['openid_account'];
	
	$output_identity = $am_core->getData(AM_DATA_PATH . 'identity.data.php', 1);
	if (isset($output_identity) && !empty($output_identity)) {
		foreach($output_identity as $key => $val) {
			$_SESSION['openid_' . $key] = $val;
		}
	}
	
	$log_entry = array();
	$log_entry['title'] = 'someone connected';
	$log_entry['description'] = '<a href="' . $_SESSION['openid_identity'] . '">' . $_SESSION['openid_nickname'] . '</a> connected.';
	$log_entry['link'] = $_SESSION['openid_identity'];
	
	$am_core->writeLogEntry($log_entry);
	
	$tpl->set('openid_url', $core_config['openid_account']);

	// set the installation date MM-DD-YYYY
	$date = date("m-d-Y");
	writeToConfig('$core_config[\'release\'][\'install_date\']', $date);

	// set this file to not readable
	chmod ('../installation/installer.php', 0000); // disable this installer
	
	$tpl->set('display', 'complete');
}
else { // welcome screen
	
	// We check chmod
	// We check for PHP5 and Curl
	// If errors occur we display via $GLOBALS['am_error_log']
	// We set to $core_config['node']['php_session_name'] to ++ random set of say 4 numbers - example = PHPSESSIDAMP9648
	// using the same random number we rename /data to /data9648
	if ( (int) phpversion() < 5) {
		$GLOBALS['am_error_log'][] = array('AROUNDMe Personal Identity needs php 5.0 or greater. Your php version is ' . phpversion());
	}
	
	if (!function_exists('curl_init') || !function_exists('curl_setopt') || !function_exists('curl_exec')) {
		$GLOBALS['am_error_log'][] = array('AROUNDMe Personal Identity needs curl. Please add curl to PHP');
	}
	
	if (!extension_loaded ('bcmath')) {
		$GLOBALS['am_error_log'][] = array('AROUNDMe Personal Identity needs bcmath. Please add bcmath to PHP');
	}
	
	if (function_exists('gd_info')) {
		$gd_info = gd_info();
		
		if (!isset($gd_info['GD Version'])) {
			$GLOBALS['am_error_log'][] = array('AROUNDMe Personal Identity needs gd library. Please add gd library to PHP');
		}
	}
	else {
		$GLOBALS['am_error_log'][] = array('AROUNDMe Personal Identity needs gd library. Please add gd library to PHP');
	}
	
	if (!is_dir('../')) {
		$GLOBALS['am_error_log'][] = array('Directory structure not intact. You need to upload the entire release directory structure');
	}

	// check that we can write
	if (!is_writable("../aroundme_pi/core/config/aroundme_core.config.php")) {
		$GLOBALS['am_error_log'][] = array('AROUNDMe Personal identity server cannot write to its config file. Please check your permissions');
	}

	if (empty($GLOBALS['am_error_log'])) {
		$php_session_name = 'PHPSESSIDAMP';
		$data = '../data';
		for($i = 0; $i < 4; $i++) {
			$n = rand(0, 9);
			$php_session_name .= $n;
			$data .= $n;
		}
		$data .= '/';
			
		writeToConfig('$core_config[\'node\'][\'php_session_name\']', $php_session_name);
		writeToConfig('$core_config[\'data\'][\'dir\']', $data);
		writeToConfig('$core_config[\'network\'][\'dir\']', dirname($_SERVER['SCRIPT_FILENAME']) . '/');
		
		$openid_url['openid_url_1'] = 'http://' . $_SERVER['SERVER_NAME'];
			
		$tpl->set('openid_url', $openid_url);
			
		$_SESSION['php_session_name'] = $php_session_name;
		$_SESSION['data'] = $data;
			
		$dirs = glob('../data*');
		rename($dirs[0], $data);
	}
}


// OUTPUT TO TEMPLATE ------------------------------------------------------------------

$tpl->set('lang', $lang);
$tpl->set('config', $core_config);

//$tpl->set('display', 'step3');

echo $tpl->fetch(AM_TEMPLATE_PATH . 'installer.tpl.php');

function writeToConfig($where, $what) {
	$config = file('../aroundme_pi/core/config/aroundme_core.config.php');
	foreach($config as $key => $val) {
		if (strstr($val, $where)) {
			$config[$key] = $where . ' = "' . $what . "\";\n";
			file_put_contents('../aroundme_pi/core/config/aroundme_core.config.php', implode($config));
			break;
		}
	}
}

?>