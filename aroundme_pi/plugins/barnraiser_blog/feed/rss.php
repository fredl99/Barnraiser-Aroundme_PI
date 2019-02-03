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


include ("../../../core/config/aroundme_core.config.php");
include ("../../../core/inc/functions.inc.php");


define("AM_DATA_PATH", $core_config['data']['dir']);

// SESSION HANDLER ----------------------------------------------------------------------------
// sets up all session and global vars 
session_name($core_config['node']['php_session_name']);
session_start();


// SETUP AROUNDMe CORE -----------------------------------------------------------------------
require_once('../../../core/class/Storage.class.php');
$am_core = new Storage($core_config);


// SETUP RSS -------------------------------------------------
$preferences = $am_core->getData('../../../' . AM_DATA_PATH . 'plugins/barnraiser_blog/rss_preferences.data.php', 1);

if (empty($preferences['language_code'])) {
	$preferences['language_code'] = "en";
}

if (empty($preferences['rss_title'])) {
	$preferences['rss_title'] = "RSS feed";
}

if (empty($preferences['rss_description'])) {
	$preferences['rss_description'] = "";
}

if (empty($preferences['rss_author'])) {
	$preferences['rss_author'] = "site owner";
}

if (empty($preferences['default_webpage_name'])) {
	$preferences['default_webpage_name'] = $_REQUEST['wp'];
}


// GET ENTRIES -------------------------------------------------
$blog_entry_filenames = $am_core->amscandir('../../../' . AM_DATA_PATH . 'plugins/barnraiser_blog/entries/');

// sort to get newest at the top
rsort($blog_entry_filenames);

// get each guestbook entry and append single array
$blog_entries = array();

foreach ($blog_entry_filenames as $key => $i):

	unset($blog_entry);

	$blog_entry = $am_core->getdata('../../../' . AM_DATA_PATH . 'plugins/barnraiser_blog/entries/' . $i, 1);

	if (!empty($blog_entry)) {
		
		$tmp = explode('.', $i);
		
		$blog_entry['link'] = $core_config['openid_account'] . "/index.php?wp=" . $preferences['default_webpage_name'] . "&amp;blog_id=" . $tmp[0];
		$blog_entry['body'] = strip_tags($blog_entry['body']);
		
		$blog_entry['body'] = trim($blog_entry['body']);
		$blog_entry['body'] = mb_substr($blog_entry['body'], 0, 200, 'UTF-8');
		
		array_push($blog_entries, $blog_entry);
	}
endforeach;








header("Content-Type: application/xml; charset=ISO-8859-1");

echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
echo "<?xml-stylesheet title=\"XSL_formatting\" type=\"text/xsl\" href=\"nolsol.xsl\"?>\n";
echo "<rss version=\"2.0\">\n";
echo "<channel>\n";
echo "<title>" . utf8_decode($preferences['rss_title']) . "</title>\n";
echo "<link>" . phpself() . "</link>\n";
echo "<description>" . utf8_decode($preferences['rss_description']) . "</description>\n";
echo "<language>" . $preferences['language_code'] . "</language>\n";
echo "<lastBuildDate>" . date("r") . "</lastBuildDate>\n";

if (!empty($blog_entries)) {
	foreach ($blog_entries as $key => $i):
		echo "<item>\n";
		echo "<title>" . utf8_decode($i['title']) . "</title>\n";
		echo "<description>" . utf8_decode($i['body']) . "</description>\n";
		echo "<link>" . $i['link'] . "</link>\n";
		echo "<author>" . utf8_decode($preferences['rss_author']) . "</author>\n";
		echo "<pubDate>" . date("r", $i['datetime']) . "</pubDate>\n";
		echo "</item>\n";
	endforeach;
}

echo "</channel>\n";
echo "</rss>";


?>