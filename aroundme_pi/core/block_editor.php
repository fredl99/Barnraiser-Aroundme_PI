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


if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64 && isset($_REQUEST['src']) && isset($_REQUEST['block'])) {
	
	$block_name = $_REQUEST['src'] . '_' . $_REQUEST['block'] . '.block.php';
	
	
	// update block ----------------------------------------------------
	if (isset($_POST['save_block'])) {
		
		$block_body = stripslashes($_POST['block_body']);

		// cannot do this now that PHP is included - need better solution
		//$block_body = str_replace("&amp;", "&", $block_body);
		//$block_body = str_replace("&", "&amp;", $block_body);
		
		$am_core->saveData(AM_DATA_PATH . 'blocks/' . $block_name, $block_body);

		
		header("Location: index.php?t=block_editor&src=" . $_REQUEST['src'] . "&block=" . $_REQUEST['block']);
		exit;
	}
	
	if(is_file(AM_DATA_PATH . 'blocks/' . $block_name)) {
		if (isset($_POST['reset_block'])) {
			// reset gets the source code and presents it to screen to save
			$output_block = $am_core->getData('plugins/' . $_REQUEST['src'] . '/source_blocks/' . $block_name);
		}
		else {
			$output_block = $am_core->getData(AM_DATA_PATH . 'blocks/' . $block_name);
		}
	}
	else {
		// we obtain the block from the source location
		$output_block = $am_core->getData('plugins/' . $_REQUEST['src'] . '/source_blocks/' . $block_name);
	}


	if (!empty($output_block)) {

		$output_block = htmlspecialchars($output_block);
		
		$body->set('block', $output_block);
	}
	else {
		header("Location: index.php");
		exit;
	}
	
	// GET IMAGES ----------------------------------
	include_once('core/class/Image.class.php');
	$image = new Image();
	$image->path = 'plugins/barnraiser_pictures';

	// fetch all pictures
	$output_picture_filenames = $am_core->amscandir(AM_DATA_PATH . $image->path);

	if (!empty($output_picture_filenames)) {

		$output_pictures = array();

		foreach($output_picture_filenames as $key => $val) {
			$thumb = substr($val, -7, 3);
			if ($thumb == '100') {
				$output_pictures[$key]['thumb'] = $val;
				$output_pictures[$key]['src'] = str_replace('_100', '', $val);
			}
		}

		if (!empty($output_pictures)) {
			$body->set('pictures', $output_pictures);
		}
	}
	
	// WEBPAGES
	$output_webpages = $am_core->amscandir(AM_DATA_PATH . 'webpages');

	if (!empty($output_webpages)) {
		foreach ($output_webpages as $key => $i):
			$output_webpages[$key] = str_replace('.wp.php', '', $i);
		endforeach;

		$body->set('webpages', $output_webpages);
	}
}
else {
	header("Location: index.php");
	exit;
}

?>