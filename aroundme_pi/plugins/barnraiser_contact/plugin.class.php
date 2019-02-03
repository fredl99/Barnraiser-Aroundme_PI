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


class Plugin_barnraiser_contact {
	// storage and template instances should be passed by reference to this class
	
	var $level = 0; // the permission level requied to see an item
	var $attributes; // any block attributes passed to the class


	function block_email () {
		
	}
}

$plugin_barnraiser_contact = new Plugin_barnraiser_contact();
$plugin_barnraiser_contact->am_storage = &$am_core;
$plugin_barnraiser_contact->am_template = &$body;

?>