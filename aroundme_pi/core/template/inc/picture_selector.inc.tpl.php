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


<script type="text/javascript">

function buildPictureTag (filename, thumb) {
	tag = '<img src="core/get_file.php?file='+filename+'"  alt="" />';
	thumb_tag = '<img src="core/get_file.php?file='+thumb+'"  alt="" />';
	
	document.getElementById('picture_selector_tag_display').value = tag;
	document.getElementById('picture_selector_tag_display_thumb').value = thumb_tag;
	document.getElementById('picture_selector_tag').style.display = 'block';
}

</script>

<a name="picture_selector"></a>
<div class="box" id="core_picture_selector" style="display:none;">
	<div class="box_header">
	    <h1>Picture selector</h1>
	</div>

	<div class="box_body">
		<div id="picture_selector_tag" style="display:none;">
			<input type="text" size="60" id="picture_selector_tag_display" value="" style="width:60em;" onclick="javascript:this.focus();this.select();" readonly="true" /><br />
			<input type="text" size="60" id="picture_selector_tag_display_thumb" value="" style="width:60em;" onclick="javascript:this.focus();this.select();" readonly="true" /> (thumb)<br />
		</div>
		
		<?php 
		if (isset($pictures)) {
		foreach($pictures as $key => $i):
		?>
		<div class="gallery_item" style="margin-right: 5px;">
			<img src="core/get_file.php?file=<?php echo $i['thumb']; ?>" alt="" onClick="javascript:buildPictureTag('<?php echo $i['src']; ?>', '<?php echo $i['thumb']; ?>');" class="cursor_hand" style="border: 1px solid black;" />
		</div>
		<?php
		endforeach;
		}
		else {
		?>
		<p>
			You need to add pictures from the pictures plugin first. Select 'Webspace' then 'Pictures'.
		</p>
		<?php }?>
		
		<div style="clear: both;"></div>
	</div>
</div>