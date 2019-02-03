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


include_once ("core/config/aroundme_core.config.php");
include_once ("core/inc/functions.inc.php");


// CHECK INSTALLED
if (is_readable("../installation/installer.php")) {
	require ("../installation/installer.php");
	exit;
}


// SESSION HANDLER ----------------------------------------------------------------------------
// sets up all session and global vars 
session_name($core_config['node']['php_session_name']);
session_start();


// ERROR HANDLING ----------------------------------------------------------------------------
// this is accessed and updated with all errors thoughtout this build
// processing regularly checks if empty before continuing
$GLOBALS['am_error_log'] = array();


if (is_readable('../installation/installer.php')) {
	$GLOBALS['am_error_log'][] = array('AROUNDMe is insecure because anyone can run the installer script. Either set installation/installer.php to not be executable. If that does not work delete the installation directory.');
}


define("AM_DATA_PATH", dirname(__FILE__) . '/' . $core_config['data']['dir']);


if (isset($_REQUEST['disconnect'])) {
	session_unset();
	session_destroy();
	session_write_close();
	header("Location: index.php");
	exit;
}

// TEMPORARY UNTIL INSTALLER IS WRITTEN
if (!isset($_SESSION['openid_nickname'])) {
	$_SESSION['openid_nickname'] = "";
}


// SETUP AROUNDMe CORE -----------------------------------------------------------------------
require_once('core/class/Storage.class.php');
$am_core = new Storage($core_config);


// SETUP TEMPLATES ---------------------------------------------------------------------------
define("AM_TEMPLATE_PATH", "core/template/");
require_once('core/class/Template.class.php');
$tpl = new Template(); // outer template
$body = new Template(); // inner template


// INCLUDE OPENID CONSUMER -------------------------------------------------------------------
require_once('core/inc/consumer.inc.php');

// SETUP LANGUAGE ----------------------------------------------------------------------------
$_SESSION['language_code'] = $core_config['aroundme_language_code'];

if (array_key_exists(strtoupper($_SESSION['language_code']), $installed_server_language_packs)) {
	$locale_code = $installed_server_language_packs[strtoupper($_SESSION['language_code'])];

	setlocale(LC_ALL, $locale_code);
}

define("AM_LANGUAGE_PATH", "core/language/" . $_SESSION['language_code'] . "/");

include_once(AM_LANGUAGE_PATH . 'common.lang.php');


// SETUP WEBSPACE ----------------------------------------------------------------------------
$output_webspace = $am_core->getData(AM_DATA_PATH . 'webspace.data.php', 1);


// SETUP INNER TEMPLATE ----------------------------------------------------------------------
// An innner template can be either a user created webpage ($_REQUEST['wp']) or 
// from a plugin template ($_REQUEST['p'] / $_REQUEST['t'] or a core template 
// $_REQUEST['t']. First we test that the received vars are actual files:

if (isset($_REQUEST['t']) && isset($_REQUEST['p'])) {
	// a plugin template (typically the plugin admin screen)
	if (is_file('plugins/' . $_REQUEST['p'] . '/template/' . $_REQUEST['t'] . '.tpl.php')) {

		define("AM_SCRIPT_NAME", $_REQUEST['t']);
		define("AM_PLUGIN_NAME", $_REQUEST['p']);
		
		// load script, language file and template
		require_once('plugins/' . AM_PLUGIN_NAME . '/language/' . $_SESSION['language_code'] . '/' . AM_SCRIPT_NAME . '.lang.php');
		require_once('plugins/' . AM_PLUGIN_NAME . '/' . AM_SCRIPT_NAME . '.php');

		$inner_template_body = $am_core->getData('plugins/' . AM_PLUGIN_NAME . '/template/' . AM_SCRIPT_NAME . '.tpl.php');
	}
}
elseif (isset($_REQUEST['t']) && $_REQUEST['t'] != "webspace") { // "webspace" is the name of the outer template
	// a core file (typically the connect or admin screens)
	if (is_file('core/template/' . $_REQUEST['t'] . '.tpl.php')) {
		define("AM_SCRIPT_NAME", $_REQUEST['t']);
		
		// load script, language file and template
		require_once('core/language/' . $_SESSION['language_code'] . '/' . AM_SCRIPT_NAME . '.lang.php');
		require_once('core/' . AM_SCRIPT_NAME . '.php');

		$inner_template_body = $am_core->getData('core/template/' . AM_SCRIPT_NAME . '.tpl.php');
	}
}
elseif (isset($_REQUEST['wp'])) {
	if (is_file(AM_DATA_PATH . 'webpages/' . $_REQUEST['wp'] . '.wp.php')) {
		define("AM_WEBPAGE_NAME", $_REQUEST['wp']);

		$inner_template_body = $am_core->getData(AM_DATA_PATH . 'webpages/' . AM_WEBPAGE_NAME . '.wp.php');
	}
	elseif (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {
		// We are the owner so we go to create the webpage
		header("Location: index.php?t=webpage&wp=" . $_REQUEST['wp']);
		exit;	
	}
	else {
		$inner_template_body = "Sorry, this page is unavailable at this time.";
	}
}
else {
	if (is_file(AM_DATA_PATH . 'webpages/' . $output_webspace['default_webpage_name'] . '.wp.php')) {
		define("AM_WEBPAGE_NAME", $output_webspace['default_webpage_name']);

		$inner_template_body = $am_core->getData(AM_DATA_PATH . 'webpages/' . AM_WEBPAGE_NAME . '.wp.php');
	}
	else {
		$inner_template_body = "Sorry, this page is unavailable at this time.";
	}
}


// check that we have a valid template loaded
if (!defined('AM_SCRIPT_NAME') && !defined('AM_WEBPAGE_NAME')) {
	$GLOBALS['am_error_log'][] = array('webpage does not exist'); // something went wrong, so we error
}



// GET AND FORMAT WEBPAGE ----------------------------------------------------------
// If we have a webpage defined we get it, parse it and apply blocks to it and create a CSS
// example: <AM_BLOCK plugin="blog" name="list" limit="6" trim="300" />
if (defined('AM_WEBPAGE_NAME')) {

	// INITIATE PLUGIN CLASSES ------------------
	$plugins = $am_core->amscandir('plugins');

	if (!empty($plugins)) {
		foreach ($plugins as $key => $i):
			if (is_file('plugins/' . $i. '/plugin.class.php')) {
				require('plugins/' . $i. '/plugin.class.php');
			}
		endforeach;
	}

	// OBTAIN BLOCKS AND RUN ASSOCIATED METHODS
	$pattern = "/<AM_BLOCK(.*?)\/>/";
	
	if (preg_match_all($pattern, $inner_template_body, $plugin_blocks)) {
		
		if (!empty($plugin_blocks[1])) {
			
			foreach ($plugin_blocks[1] as $key => $i):
				
				unset($block_html);
				
				$block = array();
				
				// get attributes
				$attribute_arr = trim($i);
				
				$attribute_pattern = '/(\w+)(\s*=\s*"(.*?)"|\s*=\s*\'(.*?)\'|(\s*=\s*\w+)|())/s';

				if(preg_match_all($attribute_pattern, $attribute_arr, $matches, PREG_PATTERN_ORDER)) {

					if (!empty($matches[1])) {
						foreach ($matches[1] as $key_attr => $at):
							
							if (!empty($matches[3][$key_attr])) {
								$block[$at] = $matches[3][$key_attr];
							}
							elseif (!empty($matches[4][$key_attr])) {
								$block[$at] = $matches[4][$key_attr];
							}
							elseif (!empty($matches[5][$key_attr])) {
								$block[$at] = $matches[5][$key_attr];
							}
						endforeach;
					}
				
					if (isset($block['plugin']) && isset($block['name'])) {
					
						unset($object_name, $method_name);
						
						// We include any language pack additions
						if (is_file('plugins/' . $block['plugin'] . '/language/' . $_SESSION['language_code'] . '/plugin_common.lang.php')) {
							include_once('plugins/' . $block['plugin'] . '/language/' . $_SESSION['language_code'] . '/plugin_common.lang.php');
						}

						// we attempt to run the class instance method
						$object_name = "plugin_" . $block['plugin'];
						$method_name = "block_" . $block['name'];

						if (class_exists($object_name) && method_exists($$object_name,$method_name)) {
							// move all block declaration attributes to the instance of the plugin class
							$$object_name->attributes = $block;

							// run the method
							$$object_name->$method_name();

							// insert the block into the template. If there is no
							// block we look for a source block in the plugin dir
							$block_name = $block['plugin'] . '_' . $block['name'] . '.block.php';

							if (is_file(AM_DATA_PATH . 'blocks/' . $block_name)) {

								$block_html = $am_core->getData(AM_DATA_PATH . 'blocks/' . $block_name);
							}
							elseif (is_file('plugins/' . $block['plugin'] . '/source_blocks/'. $block_name)) {

								$block_html = $am_core->getData('plugins/' . $block['plugin'] . '/source_blocks/'. $block_name);
								// we create a block
								$am_core->saveData(AM_DATA_PATH . 'blocks/' . $block_name, $block_html);
							}
						}
					}
				}	
					
				// replace the block
				if (isset($block_html)) {
					$inner_template_body = str_replace($plugin_blocks[0][$key], $block_html, $inner_template_body);
				}
				else {
					$inner_template_body = str_replace($plugin_blocks[0][$key], '', $inner_template_body);
				}
			endforeach;
		}
	}
}


// GET STYLESHEET ----------------------------------------------------------------------
$output_style = $am_core->getData(AM_DATA_PATH . 'styles/' . $output_webspace['webspace_css'] . '.css', 1);
$output_webspace['webspace_css'] = $output_style['css'];


// OUTPUT TO TEMPLATE ------------------------------------------------------------------

$body->set('lang', $lang);
$tpl->set('lang', $lang);
$body->set('core_config', $core_config);
$tpl->set('core_config', $core_config);
$body->set('config_identity_fields', $core_config['identity_field']);
$tpl->set('webspace', $output_webspace);

if (!empty($body->header_link_tag_arr)) { // move it to the outer template
	$tpl->header_link_tag_arr = $body->header_link_tag_arr;
}

$tpl->set('content', $body->parse($inner_template_body));


echo $tpl->fetch(AM_TEMPLATE_PATH . 'webspace.tpl.php');

?>