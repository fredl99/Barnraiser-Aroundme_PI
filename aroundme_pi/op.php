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

include ("core/config/aroundme_core.config.php");
include ("core/inc/functions.inc.php");

session_name($core_config['node']['php_session_name']);
session_start();


// ERROR HANDLING ----------------------------------------------------------------------------
// this is accessed and updated with all errors thoughtout this build
// processing regularly checks if empty before continuing
$GLOBALS['am_error_log'] = array();


define("AM_DATA_PATH", dirname(__FILE__) . '/' . $core_config['data']['dir']);
define("AM_TEMPLATE_PATH", "core/template/");

// SETUP AROUNDMe CORE -----------------------------------------------------------------------
require_once('core/class/Storage.class.php');
$am_core = new Storage($core_config);

require_once('core/class/OpenidServer.class.php');
require_once('core/class/Template.class.php');
$tpl = new Template(); // outer template
$body = new Template(); // inner template

// SETUP LANGUAGE ----------------------------------------------------------------------------
$_SESSION['language_code'] = $core_config['aroundme_language_code'];

if (array_key_exists(strtoupper($_SESSION['language_code']), $installed_server_language_packs)) {
	$locale_code = $installed_server_language_packs[strtoupper($_SESSION['language_code'])];

	setlocale(LC_ALL, $locale_code);
}

define("AM_LANGUAGE_PATH", "core/language/" . $_SESSION['language_code'] . "/");

include_once(AM_LANGUAGE_PATH . 'common.lang.php');
include_once(AM_LANGUAGE_PATH . 'identity_field_options.lang.php');

$template = 'login.tpl.php';
if (isset($_POST['login']) || isset($_POST['trust'])) {

	$server = new OpenidServer($core_config);
	
	// we enforce trust screen
	if (!empty($_POST['reset_trust']) && !empty($_REQUEST['openid_trust_root'])) {
		$file_name =  AM_DATA_PATH . 'connections/outbound/' . md5($server->normalize($_REQUEST['openid_trust_root'])) . '.data.php';
		if (is_file($file_name)) {
			$rec = unserialize (file_get_contents($file_name));
			$rec['trusted'] = 0;
			
			file_put_contents($file_name, serialize($rec));
		}
	}
	
	if (!empty($_POST['save_identity'])) {
		$output_identity = $am_core->getData(AM_DATA_PATH . 'identity.data.php', 1);
		
		foreach($_POST as $key => $value):
			if (isset($core_config['identity_field'][$key])) {
				if ($core_config['identity_field'][$key] == "select" || $core_config['identity_field'][$key] == "radio") {
					if ($value != "0") {
						$output_identity[$key] = $value;
					}
					else {
						unset($output_identity[$key]);
					}
				}
				elseif ($core_config['identity_field'][$key] == "textarea") {
					$output_identity[$key] = am_parse($value);
				}
				else { // text 
					$output_identity[$key] = htmlspecialchars($value);
				}
			}
		endforeach;
		
		$am_core->saveData(AM_DATA_PATH . 'identity.data.php', $output_identity, 1);
	}
	
	$server->checkid_setup(1);

	$template = isset($server->login_ok) ? 'trust.tpl.php' : 'login.tpl.php';
}

if (isset($_POST['openid_mode'])) {
	$openid_mode = $_POST['openid_mode'];
}
elseif (isset($_GET['openid_mode']) && !isset($_POST['login'])) {
	$openid_mode = $_GET['openid_mode'];
}

if (isset($openid_mode) && !isset($_POST['login']) && !isset($_POST['trust'])) {

	require_once('core/class/OpenidServer.class.php');
	$server = new OpenidServer($core_config);

	switch($openid_mode) {
		case 'associate':
			$server->associate();
		break;
		case 'checkid_setup':
			$server->checkid_setup();
			$template = isset($server->login_ok) ? 'trust.tpl.php' : 'login.tpl.php';
		break;
		case 'check_authentication':
			$server->check_authentication();
		break;
		case 'checkid_immediate':
			$server->checkid_immediate();
		break;
		default:
	}
}

$output_identity = $am_core->getData(AM_DATA_PATH . 'identity.data.php', 1);

$openid_trusted_root = isset($_GET['openid_trust_root']) ? $_GET['openid_trust_root'] : $_GET['openid_return_to'];
$openid_trusted_root_title = @file_get_contents($openid_trusted_root);

if (isset($openid_trusted_root_title)) {
	if (preg_match("/<title>(.*?)<\/title>/s", $openid_trusted_root_title, $matches)) {
		$openid_trusted_root_title = $matches[1];
	}
	else {
		$openid_trusted_root_title = 'no title';
	}
}

// GET STYLESHEET ----------------------------------------------------------------------
$output_webspace = $am_core->getData(AM_DATA_PATH . 'webspace.data.php', 1);
$output_style = $am_core->getData(AM_DATA_PATH . 'styles/' . $output_webspace['webspace_css'] . '.css', 1);
$output_webspace['webspace_css'] = $output_style['css'];

$body->set('config_identity_fields', $core_config['identity_field']);
$body->set('identity', $output_identity);
$body->set('trusted_root', $openid_trusted_root);
$body->set('trusted_root_title', $openid_trusted_root_title);

$output_openid['server'] = $core_config['openid_account'] . '/op.php';
$output_openid['delegate'] = $core_config['openid_account'] . '/op.php';
$output_openid['network'] = 'aroundme.xml'; // network-file

$tpl->set('openid', $output_openid);

$tpl->set('webspace', $output_webspace);
$tpl->set('lang', $lang);
$body->set('lang', $lang);

$output_openid['server'] = $core_config['openid_account'] . '/op.php';
$output_openid['delegate'] = $core_config['openid_account'] . '/op.php';

$tpl->set('openid', $output_openid);

$tpl->set('content', $body->fetch(AM_TEMPLATE_PATH . $template));
echo $tpl->fetch(AM_TEMPLATE_PATH . 'webspace.tpl.php');
?>