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


if (isset($_POST['insert_blog_comment']) && isset($_SESSION['permission']) && $_SESSION['permission'] >= 16) {

	// GET THE BLOG ENTRY
	$blog_entry = $am_core->getData(AM_DATA_PATH . 'plugins/barnraiser_blog/entries/' . $_REQUEST['blog_entry_id'] . '.data.php', 1);

	if (!empty($blog_entry)) {
		if (!isset($blog_entry['comments'])) {
			$blog_entry['comments'] = array();
		}
		
		// INSERT COMMENT
		$rec = array();
		$rec['openid'] = $_SESSION['openid_identity'];
		$rec['comment'] = am_parse(stripslashes($_POST['comment_body']));
		$rec['datetime'] = time();

		array_push($blog_entry['comments'], $rec);

		$am_core->saveData(AM_DATA_PATH . 'plugins/barnraiser_blog/entries/' . $_REQUEST['blog_entry_id'] . '.data.php', $blog_entry, 1);

		$log_entry = array();
		$log_entry['title'] = 'comment added';
		$log_entry['description'] = '<a href="' . $_SESSION['openid_identity'] . '">' . $_SESSION['openid_nickname'] . '</a> added a <a href="index.php?wp=' . $_REQUEST['wp'] . '&amp;blog_entry_id=' . $_REQUEST['blog_entry_id'] . '#blog_comment' . $rec['datetime'] . '">comment</a> to my blog.';
		$log_entry['link'] = 'index.php?wp=' . $_REQUEST['wp'] . '&amp;blog_entry_id=' . $_REQUEST['blog_entry_id'] . '#blog_comment' . $rec['datetime'];

		$log_entry = 
	
		$am_core->writeLogEntry($log_entry);
	}
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;

?>