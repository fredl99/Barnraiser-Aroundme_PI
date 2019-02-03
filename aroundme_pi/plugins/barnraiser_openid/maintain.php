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
	
	require_once(AM_LANGUAGE_PATH . 'identity_field_options.lang.php');
	
	
	// SETUP IDENTITY -------------------------------------------
	$output_identity = $am_core->getData(AM_DATA_PATH . 'identity.data.php', 1);

	if (!empty($output_identity)) {
		$body->set('identity', $output_identity);
	}


	if (isset($_POST['save_identity_levels'])) {
		// 64 = just me, 32 = friends, 16= friends+allies, 0= everyone
		foreach ($_POST['identity_level'] as $key => $i):
			$output_identity['level'][$key] = $i;
		endforeach;

		$am_core->saveData(AM_DATA_PATH . 'identity.data.php', $output_identity, 1);
		
		header("Location: index.php?p=barnraiser_openid&t=maintain");
		exit;
	}
}
else {
	header("Location: index.php");
	exit;
}

?>