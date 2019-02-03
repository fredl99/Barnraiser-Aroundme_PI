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


class Plugin_barnraiser_connection {
	// storage and template instances should be passed by reference to this class
	
	var $level = 0; // the permission level requied to see an item
	var $attributes; // any block attributes passed to the class


	function block_gallery () {
		// creates avatar gallery with links directly to persons site
		$inbound_connections = array();
		
		$inbound_connection_filenames = $this->am_storage->amscandir(AM_DATA_PATH . 'connections/inbound');
		
		if (!empty($inbound_connection_filenames)) {
			foreach ($inbound_connection_filenames as $key => $i):
				
				$inbound_connection = $this->am_storage->getData(AM_DATA_PATH . 'connections/inbound/' . $i, 1);
				
				// a filter can be applied to limit the output based upon a permission level of 32 or 16
				if (!empty($inbound_connection) && isset($this->attributes['ifilter'])) {
					if ($inbound_connection['permission'] != (int) $this->attributes['ifilter']) {
						unset($inbound_connection);
					}
				}
					
				if (!empty($inbound_connection)) {
						
					$outbound_connection = $this->am_storage->getData(AM_DATA_PATH . 'connections/outbound/' . $i, 1);
					
					array_push(	$inbound_connections, 	$inbound_connection);
				}
			endforeach;
		}
	

		if (!empty($inbound_connections)) {
			usort($inbound_connections, "cmp");

			if (isset($this->attributes['limit']) && count($inbound_connections) > (int) $this->attributes['limit']) {
				$inbound_connections = array_slice($inbound_connections, 0, (int) $this->attributes['limit']);
			}

			//if there is a limit we fill the rest of the array with empty fields
			if (isset($this->attributes['limit'])) {
				for($i = count($inbound_connections); $i < (int) $this->attributes['limit']; $i++) {
					array_push($inbound_connections, array('empty' => 1));
				}
			}
		
			$this->am_template->set('barnraiser_connection_inbound_connections', $inbound_connections);
		}
		
	}

	function block_gallery_extended () {
		
		$inbound_connections = array();
		
		$inbound_connection_filenames = $this->am_storage->amscandir(AM_DATA_PATH . 'connections/inbound');
		
		if (!empty($inbound_connection_filenames)) {
			foreach ($inbound_connection_filenames as $key => $i):
				
				$inbound_connection = $this->am_storage->getData(AM_DATA_PATH . 'connections/inbound/' . $i, 1);
				
				// a filter can be applied to limit the output based upon a permission level of 32 or 16
				if (!empty($inbound_connection) && isset($this->attributes['ifilter'])) {
					if ($inbound_connection['permission'] != (int) $this->attributes['ifilter']) {
						unset($inbound_connection);
					}
				}
					
				if (!empty($inbound_connection)) {
						
					$outbound_connection = $this->am_storage->getData(AM_DATA_PATH . 'connections/outbound/' . $i, 1);
					
					if (!empty($outbound_connection)) {
						$inbound_connection['datetime_last_visit'] = date('d M H:i', $outbound_connection['datetime_last_visit']);
						$inbound_connection['trusted'] = $outbound_connection['trusted'];
					}
					
					if (!isset($inbound_connection['trusted'])) {
						$inbound_connection['datetime_last_visit'] = '0'; // no connection;
						
					}

					array_push(	$inbound_connections, 	$inbound_connection);
				}
			endforeach;
		}
	

		if (!empty($inbound_connections)) {
			usort($inbound_connections, "cmp");

			if (isset($this->attributes['limit']) && count($inbound_connections) > (int) $this->attributes['limit']) {
				$inbound_connections = array_slice($inbound_connections, 0, (int) $this->attributes['limit']);
			}

			//if there is a limit we fill the rest of the array with empty fields
			if (isset($this->attributes['limit'])) {
				for($i = count($inbound_connections); $i < (int) $this->attributes['limit']; $i++) {
					array_push($inbound_connections, array('empty' => 1));
				}
			}
		
			$this->am_template->set('inbound_connections', $inbound_connections);
		}
		
		$owner = $this->am_storage->getData(AM_DATA_PATH . 'identity.data.php', 1);
		$owner['identity'] = $this->am_storage->config['openid_account'];
		
		$this->am_template->set('owner', $owner);
	}
	
	function block_log () {
		
		$log = $this->am_storage->getData(AM_DATA_PATH . 'connections/log.data.php', 1);

		if (!empty($log)) {
			// sort to get newest at the top
			rsort($log);
		
			if (isset($this->attributes['limit']) && count($log) > (int) $this->attributes['limit']) {
				// trim the array
				$log = array_slice($log, 0, (int) $this->attributes['limit']);
			}
		
			$this->am_template->set('connection_log', $log);
		}
	}

	function block_outbound_list () {
		// a list of recently visited people
		$outbound_filenames = $this->am_storage->amscandir(AM_DATA_PATH . 'connections/outbound');
		
		if (!empty($outbound_filenames)) {
		
			$outbound_connections = array();
				
			foreach ($outbound_filenames as $key => $i):
		
				unset($outbound_connection);
		
				$outbound_connection = $this->am_storage->getData(AM_DATA_PATH . 'connections/outbound/' . $i, 1);
				
				if (!empty($outbound_connection) && isset($this->attributes['ofilter'])) { // options = sites / humans
					if ($this->attributes['ofilter'] == "sites" && !empty($outbound_connection['is_human'])) {
						unset($outbound_connection);
					}
					elseif ($this->attributes['ofilter'] == "humans" && !isset($outbound_connection['is_human'])) {
						unset($outbound_connection);
					}
				}
				
				if (!empty($outbound_connection)) {
						$outbound_connection['filename'] = str_replace('.data.php', '',$i);
						
						if (empty($outbound_connection['title'])) {
							$outbound_connection['title'] = "no webspace title given";
						}
				
					array_push($outbound_connections, $outbound_connection);
				}
				
			endforeach;
			
			if (!empty($outbound_connections)) {
				$this->am_template->set('barnraiser_connection_outbound_connections', $outbound_connections);
			}
		}
	}
	
	function block_vouched_list () {
		// a list of people I have vouched for
		$vouched_connections = array();
		
		$inbound_connection_filenames = $this->am_storage->amscandir(AM_DATA_PATH . 'connections/inbound');
		
		if (!empty($inbound_connection_filenames)) {
			foreach ($inbound_connection_filenames as $key => $i):
				
				$vouched_connection = $this->am_storage->getData(AM_DATA_PATH . 'connections/inbound/' . $i, 1);
				
				if (!empty($vouched_connection['is_vouched'])) {
					array_push($vouched_connections, $vouched_connection);
				}
			endforeach;
			
			if (!empty($vouched_connections)) {
				$this->am_template->set('barnraiser_connection_vouched_connections', $vouched_connections);
			}
		}
	}
	
	function block_summary () {
		$statistics = array();
		
		$inbound_connection_filenames = $this->am_storage->amscandir(AM_DATA_PATH . 'connections/inbound');
		
		if (!empty($inbound_connection_filenames)) {
			// count of incoming connections
			$statistics['connections_inbound_total'] = count($inbound_connection_filenames);
			
			
			// Number of vouched conenctions
			$vouched_connections = array();
			
			foreach ($inbound_connection_filenames as $key => $i):
				
				$vouched_connection = $this->am_storage->getData(AM_DATA_PATH . 'connections/inbound/' . $i, 1);
				
				if (!empty($vouched_connection['is_vouched'])) {
					array_push($vouched_connections, $vouched_connection);
				}
			endforeach;
			
			if (!empty($vouched_connections)) {
				$statistics['connections_inbound_vouched_total'] = count($vouched_connections);
			}
		}
		
		$outbound_filenames = $this->am_storage->amscandir(AM_DATA_PATH . 'connections/outbound');
		
		if (!empty($outbound_filenames)) {
		
			$outbound_connections = array();
				
			foreach ($outbound_filenames as $key => $i):
		
				unset($outbound_connection);
		
				$outbound_connection = $this->am_storage->getData(AM_DATA_PATH . 'connections/outbound/' . $i, 1);
				
				if (!empty($outbound_connection['is_human'])) {
					$outbound_connections['humans'][] = $outbound_connection;
				}
				else {
					$outbound_connections['sites'][] = $outbound_connection;
				}
			endforeach;
			
			if (!empty($outbound_connections['humans'])) {
				$statistics['connections_outbound_humans_total'] = count($outbound_connections['humans']);
			}
			
			if (!empty($outbound_connections['sites'])) {
				$statistics['connections_outbound_sites_total'] = count($outbound_connections['sites']);
			}
		}
		
		
		if (!empty($statistics)) {
			$this->am_template->set('barnraiser_connection_statistics', $statistics);
		}
	}
}

$plugin_barnraiser_connection = new Plugin_barnraiser_connection();
$plugin_barnraiser_connection->am_storage = &$am_core;
$plugin_barnraiser_connection->am_template = &$body;

?>