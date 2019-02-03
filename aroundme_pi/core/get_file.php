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


include "config/aroundme_core.config.php";

$allowable_mime_types = $core_config['file']['mime_suffix'];


if (isset($_REQUEST['avatar'])) {
	$file = "../" . $core_config['data']['dir'] . "avatars/" . $_REQUEST['avatar'];
}
else {
	$file = "../" . $core_config['data']['dir'] . ltrim($core_config['asset']['dir'], '/') . $_REQUEST['file'];
}


if (isset($file)) {
	if (is_file($file)) {
		$suffix = substr($file, -3);
	}
	
	if (isset($suffix) && array_key_exists($suffix, $allowable_mime_types)) {
	
		header("Content-type: ".$allowable_mime_types[$suffix]);

		readfile($file);
		
	}
	else {
		header("Content-type: image/png");
		
		readfile('core/template/img/no_avatar.png');
	}
}

?>