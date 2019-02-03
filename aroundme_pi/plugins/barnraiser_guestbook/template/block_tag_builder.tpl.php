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

?>

<form id="tag_builder_form">

<?php
if (isset($_REQUEST['tag']) && $_REQUEST['tag'] == "list") {
?>

	<p>
		This tag creates a wall (guestbook) and an option for people to post a message on your wall.
	</p>
	
	<p>
		<label for="id_limit">limit</label>
		<input type="text" name="tag_builder_element_limit" id="id_limit" value="" />
	</p>

	<p>
		<label for="id_level">display to </label>
		<select id="id_level" name="tag_builder_element_level">
			<option value="0" selected="selected"><?php echo $lang['arr_permission_level_selector'][0];?></option>
			<option value="16"><?php echo $lang['arr_permission_level_selector'][16];?></option>
			<option value="32"><?php echo $lang['arr_permission_level_selector'][32];?></option>
			<option value="64"><?php echo $lang['arr_permission_level_selector'][64];?></option>
		</select>
	</p>

	<p align="right">
		<input type="button" value="Create tag" onClick="javascript:buildPluginBarnaiserGuestbookTag('list');" />
	</p>
<?php }?>
</form>