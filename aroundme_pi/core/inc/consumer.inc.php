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

require_once ('core/class/OpenidConsumer.class.php');

$openid_consumer = new OpenidConsumer;

if (isset($_POST['login'])) {
	if (md5($_POST['passwd']) == $core_config['openid_md5']) { // verify password
	
		$output_identity = $am_core->getData(AM_DATA_PATH . 'identity.data.php', 1);
	
		$_SESSION['logged_in'] = 1;
		$_SESSION['permission'] = 64;
		$_SESSION['openid_identity'] = $core_config['openid_account'];
		
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

		header('Location: index.php');
		exit;
	}
	else {
		// log error here
		$GLOBALS['am_error_log'][] = array('Your password appears incorrect');
		$_REQUEST['t'] = 'login'; //still display the login-screen
	}
}
elseif (isset($_POST['connect'])) { // we connect

	$_POST['openid_login'] = $openid_consumer->normalize($_POST['openid_login']);

	if ($_POST['openid_login'] == $core_config['openid_account'] || $_POST['openid_login'] == $core_config['openid_account'] . '/index.php') {
		//local login
		$_REQUEST['t'] = 'login'; // we display login-box
	}
	else {
		unset($_SESSION['openid_login']);
		$_SESSION['openid_login'] = $_POST['openid_login'];
	
		$openid_consumer->required_fields = array('nickname');
		$openid_consumer->optional_fields = array('fullname', 'email', 'dob', 'postcode', 'gender', 'country', 'timezone', 'language'); // add to optional fields and required fields
		$openid_consumer->optional_fields[] = 'avatar';
		
		if ($openid_consumer->discover($_POST['openid_login'])) { // we did discover a server
			if($openid_consumer->associate()) { // association is ok
				$openid_consumer->checkid_setup(); // do the setup
			}
			else {
				$GLOBALS['am_error_log'][] = array('Failed to associate with server');
			}
		}
		else {
			$GLOBALS['am_error_log'][] = array('Failed to localize openid server');
		}
	}
}
elseif (isset($_GET['openid_mode']) && $_GET['openid_mode'] == 'id_res') { // we get data back from the server
	if ($openid_consumer->id_res()) { // was the result ok?

		if (!empty($_GET['openid_sreg_avatar'])) {
			if (substr($_GET['openid_sreg_avatar'], 0,4) != "http") {
				$_GET['openid_sreg_avatar'] = $_SESSION['openid_login'] . "/" . $_GET['openid_sreg_avatar'];
			}
		}
		
		foreach($_GET as $key => $val) {
			if (stristr($key, 'openid_sreg_') && !empty($val)) {
				$_SESSION['openid_' . substr($key, 12)] = $val;
			}
		}
		
		//$_SESSION['openid_identity'] = $_GET['openid_identity'];
		$_SESSION['openid_identity'] = $_SESSION['openid_login'];
		$_SESSION['permission'] = 16;
		
		$file_name = md5($_SESSION['openid_identity']) . '.data.php';
		
		if (is_file(AM_DATA_PATH . 'connections/inbound/' . $file_name)) {
			$rec = $am_core->getData(AM_DATA_PATH . 'connections/inbound/' . $file_name, 1);
			if (isset($rec['connections']) && !empty($rec['connections'])) {
				$_SESSION['connections'] = (int) $rec['connections'] + 1;
			}
			else {
				$_SESSION['connections'] = 1;
			}
		}
		else {
			$_SESSION['connections'] = 1;
		}
		
		unset($rec);
		$rec = array();
		
		foreach($_SESSION as $key => $val) {
			if (stristr($key, 'openid_')) {
				$rec[substr($key, 7)] = $val;
			}
		}
		$rec['connections'] = $_SESSION['connections'];
		$rec['permission'] = $_SESSION['permission'];
		$rec['openid'] = $_SESSION['openid_identity'];
		$rec['last_connected_datetime'] = time();

		
		$am_core->saveData(AM_DATA_PATH . 'connections/inbound/' . $file_name, $rec, 1);

		createNetwork($am_core->config);

		
		if (empty($_SESSION['openid_nickname'])) {
			if (!empty($_GET['openid_return_to'])) {
				header("Location: index.php?t=login&no_sreg=1&return_to=" . urlencode($_GET['openid_return_to']));
			}
			else {
				header("Location: index.php?t=login&no_sreg=1");
			}
			exit;
		}
				
		$log_entry = array();
		$log_entry['title'] = $_SESSION['openid_nickname'] . ' connected';
		$log_entry['description'] = '<a href="' . $_SESSION['openid_identity'] . '">' . $_SESSION['openid_nickname'] . '</a> connected.';
		$log_entry['link'] = $_SESSION['openid_identity'];
		$am_core->writeLogEntry($log_entry);
		
		header('Location: index.php');
		exit;
	}
	else {
		// error-log here
	}

	// clean up
	unset($_SESSION['openid_login']);
}
$output_openid['server'] = $core_config['openid_account'] . '/op.php';
$output_openid['delegate'] = $core_config['openid_account'] . '/op.php';
$output_openid['network'] = 'aroundme.xml'; // network-file

$tpl->set('openid', $output_openid);
?>