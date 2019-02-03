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

<form action="index.php?p=barnraiser_guestbook&amp;t=maintain" method="POST">

<div class="am_administration">
	<div class="box">
		<div class="box_header">
			<h1>Wall entries</h1>
		</div>

		<div class="box_body">
			<?php
			if (isset($guestbook_entries)){
			?>
			<table cellspacing="0" cellpadding="2" border="0" width="100%">
				<?php
				foreach ($guestbook_entries as $key => $i):
				?>
				<tr>
					<td valign="top">
						<b><a href="<?php echo $i['openid'];?>" target="_top"><?php echo $i['connection']['nickname']?></a></b><br />
					</td>
					<td valign="top">
						<?php echo strftime("%d %b %G %H:%M", $i['datetime']);?><br />
					</td>
					<td valign="top" align="right" rowspan="2">
						<input type="checkbox" name="delete_guestbook_entry_id[]" value="<?php echo $i['filename'];?>" /><br />
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<?php echo $i['entry']?><br />
					</td>
				</tr>
				<?php
				endforeach;
				?>
			</table>

			<p align="right">
				<input type="submit" name="delete_guestbook_entries" value="delete selected wall entries" /><br />
			</p>

			<?php
			}
			else {
			?>
			<p>
				<?php echo $lang['txt_no_guestbooks'];?><br />
			</p>
			<?php }?>
		</div>
	</div>
</form>