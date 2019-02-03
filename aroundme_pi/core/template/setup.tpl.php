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

<div id="am_administration">

	<form action="index.php?t=setup" method="POST">

	<div id="am_administration_left">
		<div class="box">
			<div class="box_header">
				<h1>Webspace information</h1>
			</div>

			<div class="box_body">
			
				<p>
					<label for="id_webspace_title"><?php echo $lang['txt_label_webspace_title'];?></label>
					<input type="text" id="id_webspace_title" name="webspace_title" value="<?php if(isset($webspace['webspace_title'])) { echo $webspace['webspace_title'];}?>" style="width:380px;" /><br />
				</p>
			
				<p align="right">
					<input type="submit" name="save_webspace_metadata" value="save" />
				</p>
			</div>
		</div>
	</div>

	<div id="am_administration_right">
		<?php
		if (isset($webpages)) {
		?>
		<div class="box">
			<div class="box_header">
				<h1>Web pages</h1>
			</div>

			<div class="box_body">
				<p>
					You can copy the HTML tag into any web page to create a link to your chosen web page.
				</p>
			
				<table cellspacing="0" cellpadding="2" border="0" width="100%">
					<tr>
						<td valign="top">
							<b>Name</b><br />
						</td>
						<td valign="top">
							<b>HTML Tag</b><br />
						</td>
						<td align="center" valign="top">
							<b>Start</b><br />
						</td>
						<td align="center" valign="top">
							<b>Del</b><br />
						</td>
					</tr>
					<?php
					foreach ($webpages as $key => $i):
					?>
					<tr>
						<td valign="top">
							<a href="index.php?wp=<?php echo $i;?>"><?php echo $i;?></a><br />
						</td>
						<td>
							<input type="text" name="show_tag" value='<a href="index.php?wp=<?php echo $i;?>">link description</a>' onclick="javascript:this.focus();this.select();" readonly="true"/><br />
						</td>
						<td align="center" valign="top">
							<?php
							$checked = "";
							if (isset($webspace['default_webpage_name']) && $webspace['default_webpage_name'] == $i) {
								$checked = " checked=\"checked\"";
							}
							?>
							<input type="radio" name="default_webpage_name" value="<?php echo $i;?>"<?php echo $checked;?> /><br />
						</td>
						<td align="right" valign="top">
							<?php
							if (isset($webspace['default_webpage_name']) && $webspace['default_webpage_name'] != $i) {
							?>
							<input type="checkbox" name="delete_webpage_names[]" value="<?php echo $i;?>" />
							<?php }?>
							<br />
						</td>
					</tr>
					<?php
					endforeach;
					?>
				</table>
			
				<p align="right">
					<input type="submit" name="set_default_webpage" value="set start page" />&nbsp;
					<input type="submit" name="delete_webpages" value="delete selected" />
				</p>
			</div>	
		</div>
		<?php }?>

		
		<div class="box">
			<div class="box_header">
				<h1>Plugin blocks</h1>
			</div>
		
			<div class="box_body">
				<ul>
					<?php
					if (isset($plugins)) {
						foreach($plugins as $keyp => $p):
							if (isset($p['blocks'])) {
								foreach($p['blocks'] as $key => $i):
								if (is_file(AM_DATA_PATH . 'blocks/' . $i)) {
								$block_name = str_replace('.block.php', '', $i);
								$block_name = str_replace($p['name'] . '_', '', $block_name);
								?>
								<li><a href="index.php?t=block_editor&amp;src=<?php echo $p['name'];?>&amp;block=<?php echo $block_name;?>"><?php echo $block_name;?></a></li>
								<?php
								}
								endforeach;
							}
						endforeach;
					}
					?>
				</ul>
			</div>
		</div>
	</div>
	</form>
</div>