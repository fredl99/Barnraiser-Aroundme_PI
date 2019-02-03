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

// we read in the log
$log = $am_core->getData('../../../' . AM_DATA_PATH . 'connections/log.data.php', 1);


// some temp stuff
$output_webspace['language_code'] = "en";
$output_webspace['webspace_title'] = "AMPi RSS log feed";
$output_webspace['webspace_description'] = "alpha testing";


header("Content-Type: application/xml; charset=ISO-8859-1");

echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
echo "<?xml-stylesheet title=\"XSL_formatting\" type=\"text/xsl\" href=\"nolsol.xsl\"?>\n";
echo "<rss version=\"2.0\">\n";
echo "<channel>\n";
echo "<title>" . utf8_decode($output_webspace['webspace_title']) . "</title>\n";
echo "<link>" . phpself() . "</link>\n";
echo "<description>" . utf8_decode($output_webspace['webspace_description']) . "</description>\n";
echo "<language>" . $output_webspace['language_code'] . "</language>\n";
echo "<lastBuildDate>" . date("r") . "</lastBuildDate>\n";

if (!empty($log)) {
	foreach ($log as $key => $i):
		echo "<item>\n";
		echo "<title>" . utf8_decode($i['title']) . "</title>\n";
		echo "<link>" . $i['link']. "</link>\n";
		echo "<description>" .  utf8_decode(htmlspecialchars($i['entry'])) . "</description>\n";
		echo "<pubDate>" . date('r', $i['datetime']) . "</pubDate>\n";
		echo "</item>";
	endforeach;
}

echo "</channel>\n";
echo "</rss>";


?>