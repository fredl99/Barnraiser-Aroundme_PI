<?php

// -----------------------------------------------------------------------
// This file is part of AROUNDMe
//
// Copyright (C) 2003 - 2008 Barnraiser
// http://www.barnraiser.org/
// info@barnraiser.org
//
// This program is free software; you can redistribute it and/or modify it
// under the terms of the GNU General Public License as published by the
// Free Software Foundation; either version 2, or (at your option) any
// later version.
//
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
// General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with program; see the file COPYING. If not, write to the Free
// Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA
// 02110-1301, USA.
// -----------------------------------------------------------------------


class Storage {
	// Stored data in files
	// Data held in $this->core_config['data']['dir'];
	

	// the constructor
	// Tom Calthrop, 13th August 2008
	//
	function Storage($config) {
		$this->config = $config;
	} //EO Storage
	

	function getData ($file, $unserialize = null) {

		$rec = @file_get_contents($file);
		
		if (!empty($rec) && isset($unserialize)) {
			$rec = unserialize($rec);
		}

		return $rec;
	}

	function saveData ($file, $rec, $serialize=null) {

		if (isset($serialize)) {
			$rec = serialize($rec);
		}

		// We look to see if directory is present and if not we attempt to create it
		if (!is_dir(dirname($file))) {
			$oldumask = umask(0);
			@mkdir (dirname($file), 0770, 1);
			umask($oldumask); 
		}

		if (is_dir(dirname($file))) {
			file_put_contents($file, $rec);
		}
		else {
			$GLOBALS['am_error_log'][] = array('AROUNDMe was unable to create a directory. Please make it manually.', dirname($file));
		}
		

	}

	function deleteData ($record_id) {
		@unlink($record_id);
	}

	// Inserts the given line into a current log file as an array item
	// Note: last 60 records held
	// the level is the permission level to read this log file entry
	function writeLogEntry ($entry_arr, $level = null) {
		
		$log = $this->getData(AM_DATA_PATH . 'connections/log.data.php', 1);
		
		if (empty($log)) {
			$log = array();
		}
	
		if (empty($level)) {
			$level = "0";
		}
		
		$entry = array();
		$entry['datetime'] = time();
		$entry['entry'] = stripslashes($entry_arr['description']);
		$entry['link'] = $entry_arr['link'];
		$entry['title'] = $entry_arr['title'];
		$entry['level'] = $level;
		
		array_push($log, $entry);
	
		// Cut to 60 records
		$log = array_slice($log, -60, 60);
	
		$this->saveData(AM_DATA_PATH . 'connections/log.data.php', $log, 1);
		createNetwork($this->config);
	}

	// scan a director for directory names
	function amscandir($dir) {

		$dirnames = array();
		
		$entries = @scandir($dir);
		
		if (!empty($entries)) {
			foreach($entries as $i):
				if ($i != '.' && $i != '..' && $i != 'CVS' && $i != '.DS_Store') {
					array_push($dirnames, $i);
				}
			endforeach;
		}
	
		return $dirnames;
	}
}
?>