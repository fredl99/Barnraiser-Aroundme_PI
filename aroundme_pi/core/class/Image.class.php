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

class Image {

	var $width;

	// the constructor
	// Sebastian Öblom, 27th August 2007
	//
	function Image($config = null) {
		if (isset($config)) {
			$this->path = ltrim($config['asset']['dir'], '/'); 
			$this->allowable_mime_types = $config['file']['mime_suffix'];
			$this->mime_type_suffixes = $config['file']['mime_suffix'];
			$this->thumbnail_width = $config['file']['thumb_size'];
			$this->thumbnail_height = $config['file']['thumb_size'];
		}
	}
	
	
	function uploadImage($normal_upload = null) {
		
		if (!isset($_FILES['frm_file']) || empty($_FILES['frm_file']['tmp_name'])) {
			$GLOBALS['am_error_log'][] = array('file_not_set');
		}

		// find out thie mime-type
		if (function_exists('finfo_open')) {
			$resource = finfo_open(FILEINFO_MIME);
			$mime_type = finfo_file($resource, $_FILES['frm_file']['tmp_name']);
			finfo_close($resource);
		}
		elseif (function_exists('mime_content_type')) {
			$mime_type = mime_content_type($_FILES['frm_file']['tmp_name']);
		}
		else {
			$mime_type = $_FILES['frm_file']['type'];
		}

		// We use this to map IE-mimetype to standard mimetype
		$mime_map = array(array("from" => "image/pjpeg", "to" => "image/jpeg"));

		foreach($mime_map as $i):
			if ($i['from'] == $mime_type) {
				$mime_type = $i['to'];
			}
		endforeach;

		// Is the mime-type allowed?
		if (!$this->validateMimeType($this->allowable_mime_types, $mime_type)) {
			$GLOBALS['am_error_log'][] = array('not_valid_mime');
		}
		
		if (empty($GLOBALS['am_error_log'])) {
			$destination = AM_DATA_PATH . $this->path . "/";

			// create file name
			foreach($this->mime_type_suffixes as $key => $mts) {
				if ($mts == $mime_type) {
					$suffix = $key;
				}
			}
			
			$stamp = microtime();
			$md5_name = md5($stamp); // we name the file to this
			$md5_name .= "." . $suffix;
			
			if (!is_dir($destination)) {
				$oldumask = umask(0);
				@mkdir ($destination, 0770, 1);
				umask($oldumask);
			}
			
			if (@move_uploaded_file($_FILES['frm_file']['tmp_name'], $destination . $md5_name)) {

				if ($mime_type == "image/gif" || $mime_type == "image/jpeg" || $mime_type == "image/png") {
					$image_size = getimagesize($destination . $md5_name);
					
					// we create an avatar
					$type  = explode('/', $mime_type);
					$imagecreatefrom = 'imagecreatefrom' . $type[1];
					$image           = 'image' . $type[1];
					$new_image   = $imagecreatefrom($destination . $md5_name);
					
					foreach($this->thumbnail_width as $key => $t) {
					
						$md5_name_new = md5($stamp) . '_' . $t . '.' . $suffix;
					
						if ($image_size[0] >= $image_size[1]) { // width > height
							// scale the image to new height
							$height = $this->thumbnail_height[$key];
							$width = $image_size[0] * ($height / $image_size[1]);

							$blank_image = ImageCreateTrueColor($width, $height);
							$col         = imagecolorallocate($blank_image, 255, 255, 255);
							imagefilledrectangle($blank_image, 0, 0, $width, $height, $col);
							$newimage    = ImageCopyResampled($blank_image, $new_image, 0, 0, 0, 0, $width, $height, $image_size[0], $image_size[1]);
							$image($blank_image, $destination . $md5_name);
							$new_image_2 = $imagecreatefrom($destination . $md5_name);
							$blank_image = ImageCreateTrueColor($this->thumbnail_width[$key], $this->thumbnail_height[$key]);
							$col         = imagecolorallocate($blank_image, 255, 255, 255);
							imagefilledrectangle($blank_image, 0, 0, $this->thumbnail_width[$key], $this->thumbnail_height[$key], $col);
							$newimage    = imagecopy($blank_image, $new_image_2, 0, 0, ($width - $this->thumbnail_width[$key]) / 2, 0, $this->thumbnail_width[$key], $this->thumbnail_height[$key]);
							@unlink($destination . $md5_name);
							$image($blank_image, $destination . $md5_name_new);
						}
						else {
							// scale the image to new width
							$width = $this->thumbnail_width[$key];
							$height = $image_size[1] * ($width / $image_size[0]);

							$blank_image = ImageCreateTrueColor($width, $height);
							$col         = imagecolorallocate($blank_image, 255, 255, 255);
							imagefilledrectangle($blank_image, 0, 0, $width, $height, $col);
							$newimage    = ImageCopyResampled($blank_image, $new_image, 0, 0, 0, 0, $width, $height, $image_size[0], $image_size[1]);
							$image($blank_image, $destination . $md5_name);
							$new_image_2 = $imagecreatefrom($destination . $md5_name);
							$blank_image = ImageCreateTrueColor($this->thumbnail_width[$key], $this->thumbnail_height[$key]);
							$col         = imagecolorallocate($blank_image, 255, 255, 255);
							imagefilledrectangle($blank_image, 0, 0, $this->thumbnail_width[$key], $this->thumbnail_height[$key], $col);
							$newimage    = imagecopy($blank_image, $new_image_2, 0, 0, 0,($height - $this->thumbnail_height[$key]) / 2 , $this->thumbnail_width[$key], $this->thumbnail_height[$key]);
							@unlink($destination . $md5_name);
							$image($blank_image, $destination . $md5_name_new);
						}
					}
					
					if (isset($this->width)) {
						if (is_numeric($this->width)) {
							$width = $this->width;
							$height = $image_size[1] * ($width / $image_size[0]);
    			
							//$new_image   = $imagecreatefrom($destination . $md5_name);
							$blank_image = ImageCreateTrueColor($width, $height);
							$col         = imagecolorallocate($blank_image, 255, 255, 255);
							imagefilledrectangle($blank_image, 0, 0, $width, $height, $col);
							$newimage    = ImageCopyResampled($blank_image, $new_image, 0, 0, 0, 0, $width, $height, $image_size[0], $image_size[1]);
							@unlink($destination . $md5_name);
							$image($blank_image, $destination . $md5_name);
						}
					}
					elseif (isset($normal_upload)) {
						$image($new_image, $destination . $md5_name);
					}
				}
			}
			else {
				$GLOBALS['am_error_log'][] = array('file_not_uploaded');
			}
		}
	}
	
	function deleteImages($images) {
		foreach($images as $v) {
			$tmp = explode('.', $v);

			foreach(glob(AM_DATA_PATH . $this->path . $tmp[0] . '*') as $i) {
				@unlink($i);
			}

		}
	}

	function validateMimeType($mimes, $mime_type) {
		foreach($mimes as $m) {
			if ($m == $mime_type) {
				return 1;
			}
		}
		return 0;
	}

}

?>