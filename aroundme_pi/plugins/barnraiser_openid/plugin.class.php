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


include_once(AM_LANGUAGE_PATH . 'identity_field_options.lang.php');


class Plugin_barnraiser_openid {
	// storage and template instances should be passed by reference to this class
	
	var $level = 0; // the permission level requied to see an item
	var $attributes; // any block attributes passed to the class


	function block_connect () {
		if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {
			// later we should fetch new connections and list some kind of summary here
			$this->am_template->set('is_me', 1);
		}
		else {
			if (isset($_SESSION['openid_identity']) && !empty($_SESSION['openid_identity'])) {
				$inbound_connection = $this->am_storage->getData(AM_DATA_PATH . 'connections/inbound/' . md5($_SESSION['openid_identity']) . '.data.php', 1);
				$outbound_connection = $this->am_storage->getData(AM_DATA_PATH . 'connections/outbound/' . md5($_SESSION['openid_identity']) . '.data.php', 1);
				$identity = $this->am_storage->getData(AM_DATA_PATH . 'identity.data.php', 1);
				
				$relation = "You have connected to " . $identity['nickname'] . " " . $inbound_connection['connections'] . " times.<br />";
				if (!empty($outbound_connection)) {
					$relation .= $identity['nickname'] . " connected to you last time " . date('r', $outbound_connection['datetime_last_visit']) . "<br />";
				}
				else {
					$relation .= $identity['nickname'] . " has not connected to you yet.<br />";
				}
				global $lang;
				$relation .= " You are a " . $lang['arr_permission_level'][$_SESSION['permission']] . " here!";
				
				$this->am_template->set('relation', $relation);
			}
		}
	}
	
	function block_card () {
		$identity = $this->am_storage->getData(AM_DATA_PATH . 'identity.data.php', 1);
	
		if (!empty($identity)) {
			$this->am_template->set('identity', $identity);
		}
		
	}
}

$plugin_barnraiser_openid = new Plugin_barnraiser_openid();
$plugin_barnraiser_openid->am_storage = &$am_core;
$plugin_barnraiser_openid->am_template = &$body;

?>