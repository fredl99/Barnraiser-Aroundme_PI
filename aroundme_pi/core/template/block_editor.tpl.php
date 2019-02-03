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
	<form action="index.php?t=block_editor&amp;src=<?php echo $_REQUEST['src'];?>&amp;block=<?php echo $_REQUEST['block'];?>" method="POST">

	<div class="box">
		<div class="box_header">
		    <h1>Edit your plugin block</h1>
		</div>
		
		<div class="box_body">
			<p>
				<label for="id_block_body"><?php echo $lang['txt_label_block_body'];?></label><br />
				<textarea id="id_block_body" rows="20" cols="120" name="block_body" wrap="off"><?php echo $block;?></textarea>
				<br />
			</p>

			<p align="right">
				<input type="submit" value="Reset block" name="reset_block" />
				<input type="submit" value="Save block" name="save_block" /><br />
			</p>
			
			<p>
				<a href="#" onclick="javascript:objShowHide('core_webpage_linker');">Add links to webpages</a>

				&nbsp;&#124;&nbsp;
	
				<a href="#" onclick="javascript:objShowHide('core_picture_selector');">Add a picture</a>
				<br />
			</p>
		</div>
	</div>
	</form>
	
	<?php
	include ('core/template/inc/picture_selector.inc.tpl.php');
	?>
	
	<?php
	include ('core/template/inc/webpage_linker.inc.tpl.php');
	?>
</div>