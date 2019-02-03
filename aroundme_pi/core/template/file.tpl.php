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

<form name="upload_file" action="index.php?t=file<?php if (isset($_REQUEST['view']) && $_REQUEST['view'] == 'list') echo '&view=list';?>" method="POST" enctype="multipart/form-data">
<input type="hidden" name="webpage_id" value="<?php if (isset($webpage['webpage_id'])) { echo $webpage['webpage_id'];}?>" />


	<?php
if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {
?>

<div class="box">
	<div class="box_header">
		<h1><?php echo $lang['hdr_upload_file'];?></h1>
	</div>
	<div class="box_body">
		<p>
			<label for="frm_file"><?php echo $lang['txt_label_file']; ?></label>
			<input type="file" name="frm_file" id="frm_file" />
		</p>
	
		<p>
			<label for="frm_file_name"><?php echo $lang['txt_label_filename']; ?></label>
			<input type="text" name="frm_file_name" id="frm_file_name" value=""/>
		</p>
					
		<p>
			<i><?php echo $lang['txt_width_intro']; ?></i>
		</p>
					
		<p>
			<label for="frm_file_name"><?php echo $lang['txt_label_width']; ?></label>
			<input type="text" name="file_width" size="4" value=""/>
			&nbsp;<img src="<?php echo AM_TEMPLATE_PATH;?>img/measure.png" width="150" height="12" border="0" alt="" />
			&nbsp;<?php echo $lang['txt_pixels'];?>
		</p>
	
		<p align="right">
			<input type="submit" name="submit_file_upload" value="<?php echo $lang['sub_upload'];?>" />
		</p>
	</div>
</div>
<?php }?>

<?php if (isset($picture)) { ?>
<div class="box" id="id_selected_file">
	<div class="box_header">
		<h1><?php echo $lang['hdr_selected_file'];?></h1>
	</div>
	<div class="box_body">
		<table>
			<tr>
				<td align="left" valign="top">
					<?php if (isset($picture['thumb_100'])) { ?>
					<table width="100%" cellspacing="4">
						<tr>
							<td align="left" valign="top" colspan="2">
								<div class="view_image"><img id="id_file_1" src="core/get_file.php?file=<?php echo $picture['file_md5_name']; ?>" class="picture" title="click to view img tag" onclick="viewTag('id_file_1', 1);"/></div>
							</td>
						</tr>
						<tr>
							<td align="left" valign="top">
								<img id="id_file_3" src="core/get_file.php?file=<?php echo $picture['thumb_100']; ?>" class="picture" title="click to view img tag" onclick="viewTag('id_file_3', 1);"/>
							</td>
						</tr>
					</table>
					<?php } ?>
				</td>
				<td valign="top" align="left">
					<b>Tag</b>: <input type="text" value="" id="file_tag" onclick="javascript:this.focus();this.select();" readonly="true"/><br />
					<b>View</b>: <a href="core/get_file.php?file=<?php echo $picture['file_md5_name']; ?>"><?php echo $picture['file_md5_name']; ?></a><br />
					<input type="hidden" name="file_to_delete" value="<?php echo $picture['file_md5_name'];?>"/>
					<input type="submit" name="delete_file" value="<?php echo $lang['sub_delete']; ?>" />
				</td>
			</tr>
		</table>
	</div>
</div>
<script type="text/javascript">
	function viewTag(id, t) {
		if (t == 1) {
			path = document.getElementById(id).src;
			document.getElementById('file_tag').value = "<img src=\"" + path + "\" alt=\"\" />";
		}
		else {
			document.getElementById('file_tag').value = "<a href=\"core/get_file.php?file=<?php echo $picture['file_md5_name']; ?>\"><?php echo $picture['file_md5_name']; ?></a>";
		}
	}
	viewTag('id_file_1', '1');
</script>
<?php } ?>

<div class="box">
	<div class="box_header">
		<h1>files</h1>
		
	</div>
	<div class="box_body">
		<?php if (isset($pictures_thumbnails)) { ?>
				<?php foreach($pictures_thumbnails as $i): ?>
					<div class="image_thumb">
						<a href="index.php?t=file&amp;file_md5_name=<?php echo $i; ?>">
						<?php if (isset($i)) { ?>
							<img src="core/get_file.php?file=<?php echo $i; ?>" title="<?php echo $i; ?>. <?php echo $lang['txt_click_view']; ?>" />
						<?php } else { ?>
							<img src="<?php echo AM_TEMPLATE_PATH; ?><?php echo $core_config['file']['type'][$i['file_type']]['image'][1];?>" title="<?php echo $i['file_title']; ?>. <?php echo $lang['txt_file_uploaded']; ?> <?php echo $i['file_create_datetime']; ?>. <?php echo $lang['txt_click_view']; ?>" />
						<?php } ?>
						</a>
						<br />
						<span><?php echo wordwrap($i, 11,"<br />\n", 1); ?></span>
					</div>
				<?php endforeach; ?>
			<?php } ?>
		<div style="clear: both;"></div>
	</div>
</div>