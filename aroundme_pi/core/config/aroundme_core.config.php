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


// RELEASE NOTES --------------------------------------------------
$core_config['release']['version'] = 					"version 1.3";
$core_config['release']['release_date'] = 				"01-16-2008"; // MM-DD-YYYY
$core_config['release']['install_date'] = 				"";



// PHP CONFIGURATION -----------------------------------------------------
ini_set('error_reporting', E_ALL); 						// error handling in development environment.
//ini_set('error_reporting', 0); 						// error handling in production environment



// OPENID ACCOUNT ----------------------------------------------------
$core_config['openid_account'] = 						"http://domain.example.com";
$core_config['openid_md5'] = 							"";



// SET LOCALIZATION -----------------------------------------------------
// debian note: go to aptitude and install -language-pack-*-base, the restart webserver
// locale -a to display list of installed packs
// Key entries must be uppercase
$installed_server_language_packs = array(
	'EN' => 'en_US'
);

$core_config['aroundme_language_code'] = 					"en";



// SYSTEM CONFIGURATION -----------------------------------------------
// PHP keeps data in a session. The session is called "PHPSESSID" as standard. If you 
// have more than one instance of this software you should create a unique session name.
// recomended is characters A-Z (uppercase),0-9 with no spaces. DO NOT use a dot (.).
$core_config['node']['php_session_name'] = "PHPSESSIDAMP9400";



// PATH CONFIGURATION ----------------------------------------------------
$core_config['data']['dir'] = "../data9400/";
$core_config['network']['dir'] = "/var/www/am_pi_dev/aroundme_pi/aroundme_pi/";
$core_config['asset']['dir'] = 								"/asset/";


// FILE CONFIGURATION ----------------------------------------------------
$core_config['file']['mime_suffix']['jpg'] = "image/jpeg";
$core_config['file']['mime_suffix']['png'] = "image/png";
$core_config['file']['mime_suffix']['gif'] = "image/gif";
$core_config['file']['thumb_size'][0] = 100;



// IDENTITY FIELD CONFIGURATION ---------------------------------------------------
// options = text, textarea, select, checkbox, radio, avatar
$core_config['identity_field']['nickname'] = 			'text';
$core_config['identity_field']['fullname'] = 			'text';
$core_config['identity_field']['email'] = 				'text';
$core_config['identity_field']['dob'] = 				'text'; // date of birth
//$core_config['identity_field']['address'] = 			'textarea';
$core_config['identity_field']['postcode'] = 			'text';
$core_config['identity_field']['gender'] = 				'radio';
$core_config['identity_field']['country'] = 			'select';
$core_config['identity_field']['timezone'] = 			'select';
$core_config['identity_field']['language'] = 			'select';
$core_config['identity_field']['avatar'] = 				'avatar';
//$core_config['identity_field']['description'] = 		'textarea';
// OPTIONS
// Any options must reside in identity_field_options.lang.php



// EMAIL CONFIGURATION ---------------------------------------
$mail_config['host'] = 						"smtp@your_mail_host.org";
$mail_config['port'] = 						"25";
$mail_config['email_address'] = 			"you@your_mail.org";
$mail_config['mailer'] = 					"smtp";
$mail_config['wordwrap'] = 					"80";
//if you need a username and password to access SMTP then uncomment these
// and add your username and password
//$mail_config['smtp']['username'] = 		"your_mailserver_username";
//$$mail_config['smtp']['password'] = 		"your_mailserver_password";



// DISPLAY CONFIGURATION ---------------------------------------------------
$core_config['display']['max_list_rows'] = 				25;



// END OF CONFIG FILE ----------------------------------------------------

?>