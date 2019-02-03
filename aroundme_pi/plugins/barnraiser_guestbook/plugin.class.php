<?php

// -----------------------------------------------------------------------
// This file is part of AROUNDMe
// 
// Copyright (C) 2003-2007 Barnraiser
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


class Plugin_barnraiser_guestbook {
	// storage and template instances should be passed by reference to this class
	
	var $level = 0; // the permission level requied to see an item
	var $attributes; // any block attributes passed to the class


	function block_list () {
		
		// we get the guestbook entries
		$guestbook_entry_filenames = $this->am_storage->amscandir(AM_DATA_PATH . 'plugins/barnraiser_guestbook/entries/');

		if (!empty($guestbook_entry_filenames)) {
			// sort to get newest at the top
			rsort($guestbook_entry_filenames);
			
			if (isset($this->attributes['limit']) && count($guestbook_entry_filenames) > (int) $this->attributes['limit']) {
				// trim the array
				$guestbook_entry_filenames = array_slice($guestbook_entry_filenames, 0, (int) $this->attributes['limit']);
			}
			
			// get each guestbook entry and append single array
			$guestbook_entries = array();
			
			foreach ($guestbook_entry_filenames as $key => $i):
			
				unset($guestbook_entry, $guestbook_connection);
	
				$guestbook_entry = $this->am_storage->getData(AM_DATA_PATH . 'plugins/barnraiser_guestbook/entries/' . $i, 1);
				
				if (!empty($guestbook_entry)) {
					
					// get the connection and append guestbook entry
					if ($guestbook_entry['openid'] == $this->am_storage->config['openid_account']) {
						$guestbook_connection = $this->am_storage->getData(AM_DATA_PATH . 'identity.data.php', 1);
					}
					else {
						$guestbook_connection = $this->am_storage->getData(AM_DATA_PATH . 'connections/inbound/' . md5($guestbook_entry['openid']) . '.data.php', 1);
					}
				
					if (!empty($guestbook_connection)) {
						
						$guestbook_entry['connection'] = $guestbook_connection;
					
						array_push($guestbook_entries, $guestbook_entry);
					}
				}
			endforeach;
		
			$this->am_template->set('guestbook_entries', $guestbook_entries);
		}
	}
}

$plugin_barnraiser_guestbook = new Plugin_barnraiser_guestbook();
$plugin_barnraiser_guestbook->am_storage = &$am_core;
$plugin_barnraiser_guestbook->am_template = &$body;

?>