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

if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {

	if (isset($_POST['delete_guestbook_entries'])) {
		if (!empty($_POST['delete_guestbook_entry_id'])) {
			foreach($_POST['delete_guestbook_entry_id'] as $key => $i):
				$am_core->deleteData(AM_DATA_PATH . 'plugins/barnraiser_guestbook/entries/' . $i . '.data.php');
			endforeach;
		}
	}
	
	// we get the guestbook entries
	$guestbook_entry_filenames = $am_core->amscandir(AM_DATA_PATH . 'plugins/barnraiser_guestbook/entries/');
	
	if (!empty($guestbook_entry_filenames)) {
		// sort to get newest at the top
		rsort($guestbook_entry_filenames);
		
		// get each guestbook entry and append single array
		$guestbook_entries = array();
		
		foreach ($guestbook_entry_filenames as $key => $i):
		
			unset($guestbook_entry, $guestbook_connection);
			
			$guestbook_entry = $am_core->getData(AM_DATA_PATH . 'plugins/barnraiser_guestbook/entries/' . $i, 1);
			
			if (isset($guestbook_entry)) {
				$guestbook_entry['filename'] = str_replace('.data.php', '', $i);
				
				if ($guestbook_entry['openid'] == $core_config['openid_account']) { // owner wrote entry
					$guestbook_entry['connection']['nickname'] = $_SESSION['openid_nickname'];
				}
				else {
					// get the connection and append guestbook entry
					$guestbook_connection = $am_core->getData(AM_DATA_PATH . 'connections/inbound/' . md5($guestbook_entry['openid']) . '.data.php', 1);
			
					if (!empty($guestbook_connection)) {
						$guestbook_entry['connection'] = $guestbook_connection;
					}
				}
				
				array_push($guestbook_entries, $guestbook_entry);
			}
		endforeach;
		
		if (!empty($guestbook_entries)) {
			$body->set('guestbook_entries', $guestbook_entries);
		}
	}
}
else {
	header("Location: index.php");
	exit;
}

?>