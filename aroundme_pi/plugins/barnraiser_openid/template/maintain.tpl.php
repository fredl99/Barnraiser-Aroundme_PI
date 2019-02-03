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

<form action="index.php?p=barnraiser_openid&amp;t=maintain" method="POST">


<div class="am_administration">
	<div class="box">
		<div class="box_header">
			<h1>Displayed identity items</h1>
		</div>

		<div class="box_body">

			<table cellspacing="0" cellpadding="2" border="0" width="100%">
				<?php
				foreach ($config_identity_fields as $key => $i):
				?>
				<tr>
					<td valign="top">
						<label for="id_<?php echo $key;?>_level"><?php echo $lang['txt_identity_'.$key];?></label>
					</td>
					<td valign="top" align="right">
						<select id="id_<?php echo $key;?>_level" name="identity_level[<?php echo $key;?>]">
							<option value="0" selected="selected"><?php echo $lang['arr_permission_level_selector'][0];?></option>
							<option value="16"<?php if(isset($identity['level'][$key]) && $identity['level'][$key] == 16) { echo " selected=\"selected\"";}?>><?php echo $lang['arr_permission_level_selector'][16];?></option>
							<option value="32"<?php if(isset($identity['level'][$key]) && $identity['level'][$key] == 32) { echo " selected=\"selected\"";}?>><?php echo $lang['arr_permission_level_selector'][32];?></option>
							<option value="64"<?php if(isset($identity['level'][$key]) && $identity['level'][$key] == 64) { echo " selected=\"selected\"";}?>><?php echo $lang['arr_permission_level_selector'][64];?></option>
						</select>
					</td>
				</tr>
				<?php
				endforeach;
				?>
			</table>

			<p>
				<input type="submit" value="Create card" name="save_identity_levels" /><br />
			</p>
		</div>
	</div>
</div>
</form>