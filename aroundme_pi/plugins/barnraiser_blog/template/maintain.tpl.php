<?php

// ---------------------------------------------------------------------
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
// --------------------------------------------------------------------

?>

<form action="index.php?p=barnraiser_blog&amp;t=maintain" method="POST">
<input type="hidden" name="blog_entry_id" value="<?php if (isset($blog_entry['blog_entry_id'])) { echo $blog_entry['blog_entry_id'];}?>" />
<input type="hidden" name="block_name" value="<?php if(isset($_REQUEST['block'])) { echo $_REQUEST['block'];}?>" />
<input type="hidden" name="wp" value="<?php if (isset($_REQUEST['wp'])) { echo $_REQUEST['wp'];}?>" />

<div class="am_administration">
	<?php
	if (isset($blog_entry) || isset($_REQUEST['add_blog_entry'])) {
	?>
		<div class="box">
		<div class="box_header">
			<h1>Blog entry</h1>
		</div>
		
		<div class="box_body">
			<p>
				<label for="id_title">Title</label>
				<input type="text" name="title" id="id_title" value="<?php if (isset($blog_entry['title'])) { echo $blog_entry['title'];}?>" />
			</p>

			<p>
				<label for="id_body">Body</label>
				<textarea name="body" id="id_body" cols="80" rows="20"><?php if (isset($blog_entry['body'])) { echo $blog_entry['body'];}?></textarea>
			</p>


			<p>
				<label for="id_tags">Tags</label>
				<input type="text" name="tags" value="<?php if (isset($blog_entry['tags'])) { echo implode(', ', $blog_entry['tags']);}?>" />
			</p>
	
			<div class="id_blog_level">
				<label for="id_blog_level">display to </label>
				<select id="id_blog_level" name="level">
					<option value="0" selected="selected"><?php echo $lang['arr_permission_level_selector'][0];?></option>
					<option value="16"<?php if(isset($blog_entry['level']) && $blog_entry['level'] == 16) { echo " selected=\"selected\"";}?>><?php echo $lang['arr_permission_level_selector'][16];?></option>
					<option value="32"<?php if(isset($blog_entry['level']) && $blog_entry['level'] == 32) { echo " selected=\"selected\"";}?>><?php echo $lang['arr_permission_level_selector'][32];?></option>
					<option value="64"<?php if(isset($blog_entry['level']) && $blog_entry['level'] == 64) { echo " selected=\"selected\"";}?>><?php echo $lang['arr_permission_level_selector'][64];?></option>
				</select>
			</div>
	
			<p align="right">
				<input type="submit" name="save_blog_entry" value="save" />
				<input type="submit" name="save_blog_entry_and_go" value="save and go" />
			</p>
			
			<p>
				<a href="#" onclick="javascript:objShowHide('core_webpage_linker');">Add links to webpages</a>

				&nbsp;&#124;&nbsp;
	
				<a href="#" onclick="javascript:objShowHide('core_picture_selector');">Add a picture</a>
				<br />
			</p>
		</div>
	</div>
	
	<?php
	include ('core/template/inc/picture_selector.inc.tpl.php');
	?>
	
	<?php
	include ('core/template/inc/webpage_linker.inc.tpl.php');
	?>
	
	<?php
	}
	else {
	?>
	<div id="am_administration_left">
		<?php
		if (isset($comment_to_delete)) {
		?>
		<div class="box">
			<div class="box_header">
				<h1>Delete comment</h1>
			</div>

			<div class="box_body">
				<p>
					You have requested to delete the following comment: "<i><?php echo strip_tags($comment_to_delete['comment']);?></i>".
				</p>

				<p>
 					<input type="hidden" name="blog_entry_id" value="<?php if (isset($comment_to_delete['blog_entry_id'])) { echo $comment_to_delete['blog_entry_id'];}?>" />
					<input type="hidden" name="del_comment_id" value="<?php if (isset($comment_to_delete['datetime'])) { echo $comment_to_delete['datetime'];}?>" />
					<input type="hidden" name="wp" value="<?php if (isset($comment_to_delete['wp'])) { echo $comment_to_delete['wp'];}?>" />
					<input type="submit" name="delete_comment" value="delete this comment" />
				</p>
			</div>
		</div>
		<?php }?>
		
		
		<div class="box">
			<div class="box_header">
				<h1>Blog entries</h1>
			</div>

			<div class="box_body">
				<?php
				if (isset($blog_entries)) {
				foreach ($blog_entries as $key => $i):
				?>
				<a href="index.php?p=barnraiser_blog&amp;t=maintain&amp;blog_entry_id=<?php echo $i['blog_entry_id'];?>"><?php echo $i['title'];?></a><br />
				<?php
				endforeach;
				}
				?>

				<p align="right">
					<a href="index.php?p=barnraiser_blog&amp;t=maintain&amp;add_blog_entry=1">Add a blog entry</a>
				</p>
			</div>
		</div>

		<div class="box">
			<div class="box_header">
				<h1>RSS preferences</h1>
			</div>

			<div class="box_body">
				<p>
					<label for="id_rss_title">RSS title</label>
					<input type="text" name="rss_title" id="id_rss_title" value="<?php if (isset($preferences['rss_title'])) { echo $preferences['rss_title'];}?>" />
				</p>

				<p>
					<label for="id_rss_description">RSS description</label>
					<input type="text" name="rss_description" id="id_rss_description" value="<?php if (isset($preferences['rss_description'])) { echo $preferences['rss_description'];}?>" />
				</p>
				
				<p>
					<label for="id_rss_author">RSS author</label>
					<input type="text" name="rss_author" id="id_rss_author" value="<?php if (isset($preferences['rss_author'])) { echo $preferences['rss_author'];}?>" />
				</p>

				<p>
					<label for="id_webpage_name">default webpage</label>
					<select id="id_webpage_name" name="default_webpage_name">
						<?php
						if (isset($webpages)) {
						foreach ($webpages as $key => $i):

						$selected = "";
						if (isset($preferences['default_webpage_name']) && $preferences['default_webpage_name'] == $i) {
							$selected = " selected=\"selected\"";
						}
						?>
						<option value="<?php echo $i;?>"<?php echo $selected;?>><?php echo $i;?></option>
						<?php
						endforeach;
						}
						?>
					</select>
				</p>

				<p align="right">
					<input type="submit" name="save_preferences" value="Save RSS preferences" />
				</p>
			</div>
		</div>
	</div>
	
	<div id="am_administration_right">
		<div class="box">
			<div class="box_header">
				<h1>Tags</h1>
			</div>

			<div class="box_body">
				<?php
				if (isset($tags)) {
				?>
				<table cellspacing="0" cellpadding="2" border="0" width="100%">
				<?php
				foreach ($tags as $key => $i):
				?>
				<tr>
					<td valign="top">
						<?php echo $i['name'];?><br />
					</td>
					<td valign="top" align="right">
						<input type="checkbox" name="delete_tag_names[]" value="<?php echo $i['name'];?>" />
					</td>
				</tr>
				<?php
				endforeach;
				?>
				</table>

				<p align="right">
					<input type="submit" name="delete_tags" value="Delete selected tags" />
				</p>
				<?php }?>
			</div>
		</div>
	</div>
	
	<?php }?>
</div>
</form>