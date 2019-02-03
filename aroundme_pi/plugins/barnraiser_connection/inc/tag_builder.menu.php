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


<li>Connections</li>


<ul>
	<li><a href="#tag_builder" onclick="javascript:loadPluginTagBuilder('plugins/barnraiser_connection/block_tag_builder.php?tag=gallery');">Create an inbound avatar gallery</a></li>
	<li><a href="#tag_builder" onclick="javascript:loadPluginTagBuilder('plugins/barnraiser_connection/block_tag_builder.php?tag=gallery_extended');">Create an extended inbound avatar gallery</a></li>
	<li><a href="#tag_builder" onclick="javascript:loadPluginTagBuilder('plugins/barnraiser_connection/block_tag_builder.php?tag=inbound_list');">Create an inbound list</a></li>
	<li><a href="#tag_builder" onclick="javascript:loadPluginTagBuilder('plugins/barnraiser_connection/block_tag_builder.php?tag=outbound_list');">Create an outbound list</a></li>
	<li><a href="#tag_builder" onclick="javascript:loadPluginTagBuilder('plugins/barnraiser_connection/block_tag_builder.php?tag=vouched_list');">Create a vouched list</a></li>
	<li><a href="#tag_builder" onclick="javascript:loadPluginTagBuilder('plugins/barnraiser_connection/block_tag_builder.php?tag=summary');">Create a summary</a></li>
	<li><a href="#tag_builder" onclick="javascript:loadPluginTagBuilder('plugins/barnraiser_connection/block_tag_builder.php?tag=log');">Display your poplog</a></li>
</ul>


<script type="text/javascript">

function buildPluginBarnraiserConnectionTag(name) {
	tag = '<AM_BLOCK plugin="barnraiser_connection" name="'+name+'" ';
	
	if (document.getElementById('tag_builder_form').tag_builder_element_limit && document.getElementById('tag_builder_form').tag_builder_element_limit.value) {
		tag += 'limit="'+document.getElementById('tag_builder_form').tag_builder_element_limit.value+'" ';
	}

	if (document.getElementById('tag_builder_form').tag_builder_element_level && document.getElementById('tag_builder_form').tag_builder_element_level.value != 0) {
		tag += 'level="'+document.getElementById('tag_builder_form').tag_builder_element_level.value+'" ';
	}
	
	if (document.getElementById('tag_builder_form').tag_builder_element_ifilter && document.getElementById('tag_builder_form').tag_builder_element_ifilter.value != 0) {
		tag += 'ifilter="'+document.getElementById('tag_builder_form').tag_builder_element_ifilter.value+'" ';
	}

	if (document.getElementById('tag_builder_form').tag_builder_element_ofilter && document.getElementById('tag_builder_form').tag_builder_element_ofilter.value != 0) {
		tag += 'ofilter="'+document.getElementById('tag_builder_form').tag_builder_element_ofilter.value+'" ';
	}
	
	tag += ' />';
	
	document.getElementById('plugin_builder_display_tag').style.display = 'block';
	document.getElementById('input_builder_display_tag').value = tag;
}
</script>