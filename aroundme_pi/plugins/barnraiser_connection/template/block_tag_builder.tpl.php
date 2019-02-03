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
if (isset($_REQUEST['tag']) && $_REQUEST['tag'] == "gallery") {
?>

	<p>
		This tag displays a gallery of avatars of people who have connected to your site. Clicking an avatar goes to their site.
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
	
	<p>
		<label for="id_level">display only </label>
		<select id="id_level" name="tag_builder_element_ifilter">
			<option value="0" selected="selected"><?php echo $lang['arr_permission_level'][0];?></option>
			<option value="16"><?php echo $lang['arr_permission_level'][16];?></option>
			<option value="32"><?php echo $lang['arr_permission_level'][32];?></option>
		</select>
	</p>

	<p align="right">
		<input type="button" value="Create tag" onClick="javascript:buildPluginBarnraiserConnectionTag('gallery');" />
	</p>
<?php
}
elseif (isset($_REQUEST['tag']) && $_REQUEST['tag'] == "gallery_extended") {
?>
	<p>
		This tag displays a gallery of avatars of people who have connected to your site. Clicking an avatar displays more information about them including their poplog.
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
	
	<p>
		<label for="id_level">display only </label>
		<select id="id_level" name="tag_builder_element_ifilter">
			<option value="0" selected="selected"><?php echo $lang['arr_permission_level'][0];?></option>
			<option value="16"><?php echo $lang['arr_permission_level'][16];?></option>
			<option value="32"><?php echo $lang['arr_permission_level'][32];?></option>
		</select>
	</p>

	<p align="right">
		<input type="button" value="Create tag" onClick="javascript:buildPluginBarnraiserConnectionTag('gallery_extended');" />
	</p>

<?php
}
elseif (isset($_REQUEST['tag']) && $_REQUEST['tag'] == "inbound_list") {
?>
	<p>
		This tag displays a list of people who have connected to your site.
	</p>
	
	<p>
		<label for="id_limit">limit</label>
		<input type="text" name="tag_builder_element_limit" id="id_limit" value="" />
	</p>

	<p>
		<label for="id_level">display to</label>
		<select id="id_level" name="tag_builder_element_level">
			<option value="0" selected="selected"><?php echo $lang['arr_permission_level_selector'][0];?></option>
			<option value="16"><?php echo $lang['arr_permission_level_selector'][16];?></option>
			<option value="32"><?php echo $lang['arr_permission_level_selector'][32];?></option>
			<option value="64"><?php echo $lang['arr_permission_level_selector'][64];?></option>
		</select>
	</p>
	
	<p>
		<label for="id_level">display only</label>
		<select id="id_level" name="tag_builder_element_ifilter">
			<option value="0" selected="selected"><?php echo $lang['arr_permission_level'][0];?></option>
			<option value="16"><?php echo $lang['arr_permission_level'][16];?></option>
			<option value="32"><?php echo $lang['arr_permission_level'][32];?></option>
		</select>
	</p>

	<p align="right">
		<input type="button" value="Create tag" onClick="javascript:buildPluginBarnraiserConnectionTag('incoming');" />
	</p>
<?php
}
elseif (isset($_REQUEST['tag']) && $_REQUEST['tag'] == "outbound_list") {
?>
	<p>
		This tag displays a list of people who have connected to your site.
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

	<p>
		<label for="id_level">display only</label>
		<select id="id_level" name="tag_builder_element_ofilter">
			<option value="0" selected="selected">everything</option>
			<option value="humans">nerds</option>
			<option value="sites">sites</option>
		</select>
	</p>

	<p align="right">
		<input type="button" value="Create tag" onClick="javascript:buildPluginBarnraiserConnectionTag('outbound_list');" />
	</p>
<?php
}
elseif (isset($_REQUEST['tag']) && $_REQUEST['tag'] == "log") {
?>
	<p>
		Your poplog is a log of events which has happened on this site. This tag displays your poplog.
	</p>

	<p>
		<label for="id_limit">limit</label>
		<input type="text" name="tag_builder_element_limit" id="id_limit" value="" />
	</p>

	<p>
		<label for="id_level">display to</label>
		<select id="id_level" name="tag_builder_element_level">
			<option value="0" selected="selected"><?php echo $lang['arr_permission_level_selector'][0];?></option>
			<option value="16"><?php echo $lang['arr_permission_level_selector'][16];?></option>
			<option value="32"><?php echo $lang['arr_permission_level_selector'][32];?></option>
			<option value="64"><?php echo $lang['arr_permission_level_selector'][64];?></option>
		</select>
	</p>

	<p align="right">
		<input type="button" value="Create tag" onClick="javascript:buildPluginBarnraiserConnectionTag('log');" />
	</p>
<?php
}
elseif (isset($_REQUEST['tag']) && $_REQUEST['tag'] == "summary") {
?>
	<p>
		This tag displays a summary of activity of sites and people.
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
		<input type="button" value="Create tag" onClick="javascript:buildPluginBarnraiserConnectionTag('summary');" />
	</p>
	
<?php
}
elseif (isset($_REQUEST['tag']) && $_REQUEST['tag'] == "vouched_list") {
?>
		<p>
			This tag displays a list of activity of people that you have vouched for.
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
			<input type="button" value="Create tag" onClick="javascript:buildPluginBarnraiserConnectionTag('vouched_list');" />
		</p>

<?php }?>


</form>