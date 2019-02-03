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


function cmp($a=null, $b=null) {
	if (isset($a['last_connected_datetime'], $b['last_connected_datetime'])) {
		return $a['last_connected_datetime'] > $b['last_connected_datetime'] ? -1 : 1;
	}
	elseif (isset($a['last_connected_datetime'])) {
		return -1;
	}
	return 1;
}

if (!function_exists('scandir')) { // PHP5 only function, hence we declare for <PHP 5 installs
   function scandir($dir) {
       $files = array();
       $dh  = opendir($dir);
       while (false !== ($filename = readdir($dh))) {
           $files[] = $filename;
       }
       return $files;
   }
}





// content parser
// Main parse-function
function am_parse($str) {
	//if (!get_magic_quotes_gpc()) { // not needed when using files (no database) / TOM
		$str = stripslashes($str);
	//}

	
	$str = str_replace("\r", "", $str);

	
	// process <code>
	$pattern = "/<code>(.*?)?<\/code>/s";

 	if (preg_match_all($pattern, $str, $code_blocks)) {
 		
		if (!empty($code_blocks[1])) {
			foreach ($code_blocks[1] as $key => $i):
				$replace = $code_blocks[1][$key];
				$replace = trim($replace);
				$replace = htmlspecialchars($replace);
				$replace = "<code>\n" . $replace . "\r</code>";
				$str = str_replace($code_blocks[0][$key], $replace, $str);
			endforeach;
		}
	}
	
	$str = _nl2br(nls2p($str));


	// Making links active
    $pattern = '#(^|[^"\'=\]]{1})(http|HTTP|ftp)(s|S)?://([^\s<>\.]+)\.([^\s<>]+)#sm';
    $replace = '\\1<a href="\\2\\3://\\4.\\5">\\2\\3://\\4.\\5</a>';
    $str = preg_replace($pattern, $replace, $str);
	
	// any trailing <br />
	
	if (substr($str, -6) == '<br />') {
		$str = substr_replace($str, '', -6);
	}
	
	return $str;
}


// content parser
function nls2p($str) {
	// temporary - we need to do something clever here to ignore inside code tags and
	// no wrap paras around html tags
	$str = str_replace('<p></p>', '', '<p>' . preg_replace('#([\r\n]\s*?[\r\n]){1,}#', '</p>$0<p>', $str) . '</p><br />');
	
	return $str;
}


// content parser
function _nl2br($str) {
	$str = preg_replace( "/([0-9A-Za-z.!?])\n/", "$1<br />", $str);

	return $str;
}


// content parser
function am_render($str) {

	$str = str_replace("<p>", "", $str);
	$str = str_replace("</p>", "", $str);

	// process <code>
	$pattern = "/<code>(.*?)?<\/code>/s";

 	if (preg_match_all($pattern, $str, $code_blocks)) {
 	
		if (!empty($code_blocks[1])) {
			foreach ($code_blocks[1] as $key => $i):
				$replace = str_replace("<br />", "", $code_blocks[1][$key]);
				$replace = "<code>" . $replace . "</code>";
				$str = str_replace($code_blocks[0][$key], $replace, $str);
			endforeach;
		}
	}
	$str = str_replace("<br />", "\n", $str);

	return trim($str);
}

// build full path
function phpself($urlencode = null) {
	$host  = $_SERVER['HTTP_HOST'];
	$uri  = rtrim($_SERVER['PHP_SELF'], "/\\");
	
	$url = "http://".$host.$uri;
	
	if (isset($urlencode)) {
		$url = urlencode($url);
	}
	
	return $url;
}

// We check that a proposed file name is OK (A_Z, a-z or 0-9)
function checkFileName ($filename) {

	if (empty($filename)) {
		$GLOBALS['am_error_log'][] = array('unix_name_empty');
	}
	elseif (!preg_match('/^[a-z0-9.~_]+$/', $filename)) {
		$GLOBALS['am_error_log'][] = array('unix_name_incorrect');
	}
	elseif (strlen($filename) < 3) {
		$GLOBALS['am_error_log'][] = array('unix_name_short');
	}
}

function createNetwork($config) {
	if (!defined("AM_NETWORK_DIR")) {
		define("AM_NETWORK_DIR", $config['network']['dir']);
	}
	
	global $am_core;
	// we create aroundme.xml - the network file
	$network = "<xml version=\"1.0\">\n";
	
	$identity = $am_core->getData(AM_DATA_PATH . 'identity.data.php', 1);
 	//print_r($identity);
	$network .= "<me_meta>";
	
	if (isset($identity['level']) && !empty($identity['level'])) {
		foreach($identity['level'] as $key => $val) {
			if ($val == 0 && isset($identity[$key])) {
				$network .= "<me_" . $key . ">" . htmlspecialchars($identity[$key]) . "</me_" . $key . ">\n" ;
			}
		}
	}
	
	$network .= "</me_meta>";
	
	$network .= "<network>";
	foreach($am_core->amscandir(AM_DATA_PATH . 'connections/inbound') as $connection) {
	
		if (md5($config['openid_account']) != $connection) {
			$friend = $am_core->getData(AM_DATA_PATH . 'connections/inbound/' . $connection, 1);
			
			if (isset($friend['identity'], $friend['nickname'], $friend['connections'])) {
				if ($friend['permission'] != 4) {
					$network .= "<inbound>\n";
					$network .= "\t<identity>" . $friend['identity'] . "</identity>\n";
					$network .= "\t<nickname>" . $friend['nickname'] . "</nickname>\n";
					$network .= "\t<connections>" . $friend['connections'] . "</connections>\n";
					
					if (isset($friend['is_vouched'])) {
						$network .= "\t<is_vouched>" . $friend['is_vouched'] . "</is_vouched>\n";
					}
					else {
						$network .= "\t<is_vouched>0</is_vouched>\n";
					}
					
					if (isset($friend['reference'])) {
						$network .= "\t<reference>" . $friend['reference'] . "</reference>\n";
					}
					else {
						$network .= "\t<reference></reference>\n";
					}
					$network .= "</inbound>\n";
				}
			}
		}
	}
	foreach($am_core->amscandir(AM_DATA_PATH . 'connections/outbound') as $connection) {
	
		if (md5($config['openid_account']) != $connection) {
			$friend = $am_core->getData(AM_DATA_PATH . 'connections/outbound/' . $connection, 1);
			
			if (isset($friend['realm'], $friend['title'], $friend['is_human']) && $friend['is_human'] == "1") {
				$network .= "<outbound>\n";
				$network .= "\t<realm>" . $friend['realm'] . "</realm>\n";
				$network .= "\t<title>" . $friend['title'] . "</title>\n";
				$network .= "</outbound>\n";
			}
		}
	}
	$network .= "</network>\n";
	$network .= "<poplog>\n";
	
	$poplog = $am_core->getData(AM_DATA_PATH . 'connections/log.data.php', 1);
	foreach($poplog as $p) { // maybe we should put a limit here?
		$network .= "<logentry>\n";
		$network .= "\t\t<datetime>" . date('r', $p['datetime']) . "</datetime>\n";
		$network .= "\t\t<timestamp>" . $p['datetime'] . "</timestamp>\n";
		$network .= "\t\t<entry>" . htmlspecialchars($p['entry']) . "</entry>\n";
		$network .= "\t\t<level>" . $p['level'] . "</level>\n";
		$network .= "</logentry>\n";
	}
	
	$network .= "</poplog>\n";
	$network .= "</xml>";

	file_put_contents(AM_NETWORK_DIR . 'aroundme.xml', $network);

}

?>