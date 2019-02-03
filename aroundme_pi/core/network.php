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

if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {
	
	$outbound_filenames = $am_core->amscandir(AM_DATA_PATH . 'connections/outbound/');
	
	if (isset($_POST['set_trust_human'])) {
	
		// we remove trust on all sites
		if (!empty($outbound_filenames)) {

			foreach ($outbound_filenames as $key => $i):
			
				unset($site);

				$site = $am_core->getData(AM_DATA_PATH . 'connections/outbound/' . $i, 1);
			
				if (!empty($site) && !empty($site['is_human'])) {

					$site['trusted'] = 0;

					$am_core->saveData(AM_DATA_PATH . 'connections/outbound/' . $i, $site, 1);
				}
			endforeach;
		}
		
		if (!empty($_POST['trusted_humans'])) {
			foreach ($_POST['trusted_humans'] as $key => $i):
				
				unset($site);

				$site = $am_core->getData(AM_DATA_PATH . 'connections/outbound/' . $i . '.data.php', 1);
			
				if (!empty($site)) {

					$site['trusted'] = 1;

					$am_core->saveData(AM_DATA_PATH . 'connections/outbound/' . $i . '.data.php', $site, 1);
				}
			endforeach;
		}
		
		header("Location: index.php?t=network&v=outbound_human");
		exit;
	
	}
	elseif (isset($_POST['set_trust_sites'])) {
		
		// we remove trust on all sites
		if (!empty($outbound_filenames)) {

			foreach ($outbound_filenames as $key => $i):
			
				unset($site);

				$site = $am_core->getData(AM_DATA_PATH . 'connections/outbound/' . $i, 1);
			
				if (!empty($site) && empty($site['is_human'])) {

					$site['trusted'] = 0;

					$am_core->saveData(AM_DATA_PATH . 'connections/outbound/' . $i, $site, 1);
				}
			endforeach;
		}
	
		if (!empty($_POST['trusted_sites'])) {
			foreach ($_POST['trusted_sites'] as $key => $i):
				
				unset($site);

				$site = $am_core->getData(AM_DATA_PATH . 'connections/outbound/' . $i . '.data.php', 1);
			
				if (!empty($site)) {

					$site['trusted'] = 1;

					$am_core->saveData(AM_DATA_PATH . 'connections/outbound/' . $i . '.data.php', $site, 1);
				}
			endforeach;
		}
		
		header("Location: index.php?t=network&v=outbound_sites");
		exit;
		
		
	}
	elseif (isset($_POST['save_connection'])) {
		
		if (isset($_POST['connection_id'])) {
			$connection = $am_core->getData(AM_DATA_PATH . 'connections/inbound/' . $_POST['connection_id'] . '.data.php', 1);
			
			if (!empty($connection)) {
				$connection['permission'] = $_POST['connection_permission'];
				
				if ($connection['permission'] == 4) { // you cannot vouch for a foe
					unset ($_POST['connection_is_vouched']);
				}
				
				if (!empty($_POST['connection_is_vouched'])) {
					$connection['is_vouched'] = 1;
		
					$log_entry = array();
					$log_entry['title'] = 'vouching';
					$log_entry['description'] = '<a href="' . $_SESSION['openid_identity'] . '">' . $_SESSION['openid_nickname'] . "</a> vouched for  " . $connection['nickname'];
					$log_entry['link'] = $_SESSION['openid_identity'];
					
					$am_core->writeLogEntry($log_entry);
				}
				else {
					unset($connection['is_vouched']);
				}
				
				if (!empty($_POST['connection_reference'])) {
					$connection['reference'] = $_POST['connection_reference'];
					$connection['reference_datetime'] = time();
				}
				else {
					unset($connection['reference'], $connection['reference_datetime']);
				}
				
				$connection['edit_datetime'] = time();
				
				$am_core->saveData(AM_DATA_PATH . 'connections/inbound/' . $_POST['connection_id'] . '.data.php', $connection, 1);
				
				createNetwork($core_config);
				
				header("Location: index.php?t=network&inbound_connection_id=" . $_POST['connection_id']);
				exit;
			}
			
			
		}
		
		header("Location: index.php?t=network");
		exit;
	}
	
	
	
	
	
	
	// Do we have a connection?
	if (isset($_REQUEST['inbound_connection_id'])) {

		$inbound_connection = $am_core->getData(AM_DATA_PATH . 'connections/inbound/' . $_REQUEST['inbound_connection_id'] . '.data.php', 1);

		if (!empty($inbound_connection)) {
			$inbound_connection['filename'] = $_REQUEST['inbound_connection_id'];

			$body->set('inbound_connection', $inbound_connection);
		}
		
	}
	
	
	// we don't have a connection so we display network page
	if (!isset($inbound_connection)) {
	
		// SETUP CONNECTIONS -------------------------------------------
		include_once(AM_LANGUAGE_PATH . 'identity_field_options.lang.php');

		$connection_filenames = $am_core->amscandir(AM_DATA_PATH . 'connections/inbound');

		if (!empty($connection_filenames)) {
	
			$inbound_connections = array();
	
			foreach ($connection_filenames as $key => $i):
				unset ($inbound_connection);

				$inbound_connection = $am_core->getData(AM_DATA_PATH . 'connections/inbound/' . $i, 1);
		
				if (isset($inbound_connection)) {
					$inbound_connection['filename'] = str_replace('.data.php', '',$i);
				
					$inbound_connections['connections'][] = $inbound_connection;
				
					// we build array based on level
					if (isset($inbound_connection['permission']) && $inbound_connection['permission'] == 4) {
						$inbound_connections['foes'][] = $inbound_connection;
					}
					elseif (isset($inbound_connection['permission']) && $inbound_connection['permission'] == 32) {
						$inbound_connections['trusted_connections'][] = $inbound_connection;
					}
				
					if (!empty($inbound_connection['is_vouched'])) {
						$inbound_connections['vouched_connections'][] = $inbound_connection;
					}
				}
		
			endforeach;
	
			$body->set('inbound_connections', $inbound_connections);
		}
	
		
		if (!empty($outbound_filenames)) {

			// get each blog entry and append single array
			$outbound_connections = array();

			foreach ($outbound_filenames as $key => $i):

				unset($site);

				$outbound_connection = $am_core->getData(AM_DATA_PATH . 'connections/outbound/' . $i, 1);

				if (!empty($outbound_connection)) {

					$outbound_connection['filename'] = str_replace('.data.php', '',$i);
					
					if (empty($outbound_connection['title'])) {
						$outbound_connection['title'] = "no webspace title given";
					}
					
					if (!empty($outbound_connection['is_human'])) {
						$outbound_connections['humans'][] = $outbound_connection;
					}
					else {
						$outbound_connections['sites'][] = $outbound_connection;
					}
				}
			endforeach;

			$body->set('outbound_connections', $outbound_connections);
		}
	}
}
else {
	header("Location: index.php");
	exit;
}

?>