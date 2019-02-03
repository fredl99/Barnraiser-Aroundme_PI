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


// START SESSION
session_name($core_config['node']['php_session_name']);
session_start();


define("AM_DATA_PATH", dirname(__FILE__) . '/../../' . $core_config['data']['dir']);


// SETUP AROUNDMe CORE -----------------------------------------------------------------------
require_once('../../core/class/Storage.class.php');
$am_core = new Storage($core_config);


if (isset($_POST['insert_log_entry']) && isset($_SESSION['permission']) && $_SESSION['permission'] >= 64) {
	
	$log_entry = array();
	$log_entry['title'] = 'someone says something';
	$log_entry['description'] = '<a href="' . $_SESSION['openid_identity'] . '">' . $_SESSION['openid_nickname'] . "</a> says: " . $_POST['log_entry'];
	$log_entry['link'] = $_SESSION['openid_identity'];
	
	$am_core->writeLogEntry($log_entry);

}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;

?>