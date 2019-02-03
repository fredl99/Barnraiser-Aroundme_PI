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

<li>Blog</li>


<ul>
	<li><a href="#tag_builder" onclick="javascript:loadPluginTagBuilder('plugins/barnraiser_blog/block_tag_builder.php?tag=entry');">Create a blog entry</a></li>
	<li><a href="#tag_builder" onclick="javascript:loadPluginTagBuilder('plugins/barnraiser_blog/block_tag_builder.php?tag=list');">Create a simple list of blog entries</a></li>
	<li><a href="#tag_builder" onclick="javascript:loadPluginTagBuilder('plugins/barnraiser_blog/block_tag_builder.php?tag=tagcloud');">Create a tagcloud</a></li>
	<li><a href="#tag_builder" onclick="javascript:loadPluginTagBuilder('plugins/barnraiser_blog/block_tag_builder.php?tag=blogroll');">Create a blogroll</a></li>
</ul>


<script type="text/javascript">

function buildPluginBarnraiserBlogTag(name) {
	tag = '<AM_BLOCK plugin="barnraiser_blog" name="'+name+'" ';
	
	if (document.getElementById('tag_builder_form').tag_builder_element_limit && document.getElementById('tag_builder_form').tag_builder_element_limit.value) {
		tag += 'limit="'+document.getElementById('tag_builder_form').tag_builder_element_limit.value+'" ';
	}
	
	if (document.getElementById('tag_builder_form').tag_builder_element_trim && document.getElementById('tag_builder_form').tag_builder_element_trim.value) {
		tag += 'trim="'+document.getElementById('tag_builder_form').tag_builder_element_trim.value+'" ';
	}
	
	if (document.getElementById('tag_builder_form').tag_builder_element_webpage && document.getElementById('tag_builder_form').tag_builder_element_webpage.value != 0) {
		tag += 'wp="'+document.getElementById('tag_builder_form').tag_builder_element_webpage.value+'" ';
	}

		if (document.getElementById('tag_builder_form').tag_builder_element_level && document.getElementById('tag_builder_form').tag_builder_element_level.value != 0) {
			tag += 'level="'+document.getElementById('tag_builder_form').tag_builder_element_level.value+'" ';
		}
	
	tag += ' />';
	
	document.getElementById('plugin_builder_display_tag').style.display = 'block';
	document.getElementById('input_builder_display_tag').value = tag;
	
	
	
	//console.debug("message", document.getElementById("tag_builder_form").tag_builder_element_limit.value);
}
</script>