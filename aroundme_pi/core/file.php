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
	include_once('class/Image.class.php');
	$image = new Image($core_config);
	
	if (isset($_POST['submit_file_upload'])) {
		if (isset($_POST['file_width']) && !empty($_POST['file_width'])) {
			if (is_numeric($_POST['file_width'])) {
				$image->width = $_POST['file_width'];
			}
			else {
				$GLOBALS['am_error_log'][] = array('width_not_numeric');
			}
		}
		
		if (empty($GLOBALS['am_error_log'])) {
			$image->uploadImage(1);
		}
	}
	elseif (isset($_POST['delete_file'])) {
		if (!empty($_POST['file_to_delete'])) {
			$image->deleteImages(array($_POST['file_to_delete']));
		}
	}

	// fetch all pictures
	$output_pictures = $am_core->amscandir(AM_DATA_PATH . $image->path);
	$output_pictures_thumbnails = array();

	foreach($output_pictures as $key => $val) {
	
		if (strrpos($val, '.') && strrpos($val, '_')) {
			$thumb = substr($val, strrpos($val, '_')+1, strlen($val) - strrpos($val, '.')-1);
		
			if ($thumb == '100') {
				$output_pictures_thumbnails[$key] = $val;
			}
		}
		
		if (isset($_GET['file_md5_name'])) {
			if ($_GET['file_md5_name'] == $val) {
 				$picture['thumb_100'] = $val;
 				$picture['file_md5_name'] = $output_pictures[$key-1];
			}
		}
	}
	
	if (!empty($output_pictures_thumbnails)) {
		$body->set('pictures_thumbnails', $output_pictures_thumbnails);
		$body->set('pictures', $output_pictures);
	}
	
	if (isset($picture)) {
		$body->set('picture', $picture);
	}

}
else {
	header("Location: index.php");
	exit;
}


?>