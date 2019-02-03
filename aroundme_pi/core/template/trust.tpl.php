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
	function viewSaveCheckbox() {
		document.getElementById('save_identity_box').style.visibility = "visible";
		document.getElementById('save_identity').checked = "checked";
	}
</script>

<form method="post" name="frm">

<div class="am_administration">
	<div id="am_administration_left">
		<div class="box">
			<div class="box_header">
				<h1>Identity information request</h1>
			</div>

			<div class="box_body">
				<?php
				if (empty($trusted_root_title)) {
					$trusttrusted_root_titleed_root = 'no name given';
				}
				?>
				
				<p>
					The site called '<a href="<?php echo $trusted_root; ?>"><?php echo $trusted_root_title; ?></a>' (<?php echo $trusted_root; ?>) has requested the following identity information from you:<br />
				</p>
				
				<?php
				if (!empty($_GET['openid_sreg_required'])) {
				foreach(explode(',', $_GET['openid_sreg_required']) as $i):
				?>
				
				<table cellspacing="0" cellpadding="4" border="0">
				<?php
				if (!isset($config_identity_fields[$i]) || (isset($config_identity_fields[$i]) && $config_identity_fields[$i] == 'text')) {
				?>
				<tr>
					<td valign="top">
						<label for="<?php echo $i; ?>"><?php echo $i; ?></label>
					</td>
					<td valign="top">
						<input onKeyPress="viewSaveCheckbox();" type="text" name="<?php echo $i; ?>" id="<?php echo $i; ?>" value="<?php if (!empty($identity[$i])) { echo $identity[$i]; } ?>" <?php if (empty($identity[$i])) echo "disabled"; ?>/>
					</td>
					<td valign="top">
						<input type="checkbox" name="checkbox_<?php echo $i; ?>" id="checkbox_<?php echo $i; ?>" value="1" <?php if (!empty($identity[$i])) echo "checked=\"checked\""; ?> onchange="disable_field('<?php echo $i; ?>');"/>
						<label for="checkbox_<?php echo $i; ?>" style="float: none;font-weight:normal;">&#149;</label>
					</td>
				</tr>
				<?php
				}
				elseif ($config_identity_fields[$i] == 'select') {
				?>
				<tr>
					<td valign="top">
						<label for="<?php echo $i; ?>" <?php if (empty($identity[$i])) echo "disabled"; ?>><?php echo $i; ?></label>
					</td>
					<td valign="top">
						<select name="<?php echo $i; ?>" onchange="viewSaveCheckbox();">
							<option value="0" selected="selected"><?php echo $lang['txt_select_none'];?></option>
							<?php foreach($lang['arr_identity_field'][$i] as $k => $v) { ?>
								<option value="<?php echo $k; ?>" <?php if (isset($identity[$i]) && $k == $identity[$i]) echo "selected=\"selected\""; ?>><?php echo $v; ?></option>
							<?php } ?>
						</select>
					</td>
					<td valign="top">
						<input type="checkbox" name="checkbox_<?php echo $i; ?>" id="checkbox_<?php echo $i; ?>" value="1" <?php if (!empty($identity[$i])) echo "checked=\"checked\""; ?> onchange="disable_field('<?php echo $i; ?>');"/>
						<label for="checkbox_<?php echo $i; ?>" style="float: none;font-weight:normal;">&#149;</label>
					</td>
				</tr>
				<?php
				}
				endforeach;
				}
				?>

				<?php
				if (!empty($_GET['openid_sreg_optional'])) {
				foreach(explode(',', $_GET['openid_sreg_optional']) as $i):
				?>

				<?php
				if (!isset($config_identity_fields[$i]) || (isset($config_identity_fields[$i]) && $config_identity_fields[$i] == 'text')) {
				?>
				<tr>
					<td valign="top">
						<label for="<?php echo $i; ?>"><?php echo $i; ?></label>
					</td>
					<td valign="top">
						<input type="text" onKeyPress="viewSaveCheckbox();" name="<?php echo $i; ?>" id="<?php echo $i; ?>" value="<?php if (!empty($identity[$i])) { echo $identity[$i]; } ?>" <?php if (empty($identity[$i])) echo "disabled"; ?>/>
					</td>
					<td valign="top">
						<input type="checkbox" name="checkbox_<?php echo $i; ?>" id="checkbox_<?php echo $i; ?>" value="1" <?php if (!empty($identity[$i])) echo "checked=\"checked\""; ?> onchange="disable_field('<?php echo $i; ?>');"/>
						<label for="checkbox_<?php echo $i; ?>"></label>
					</td>
				</tr>
				<?php
				}
				elseif ($config_identity_fields[$i] == 'select') {
				?>
				<tr>
					<td valign="top">
						<label for="<?php echo $i; ?>"><?php echo $i; ?></label>
					</td>
					<td valign="top">
						<select onchange="viewSaveCheckbox();" name="<?php echo $i; ?>" <?php if (empty($identity[$i])) echo "disabled"; ?>>
							<option value="0" selected="selected"><?php echo $lang['txt_select_none'];?></option>
						<?php 
						foreach($lang['arr_identity_field'][$i] as $k => $v) {
							?>
							<option value="<?php echo $k; ?>" <?php if (isset($identity[$i]) && $k == $identity[$i]) echo "selected=\"selected\""; ?>><?php echo $v; ?></option>
						<?php } ?>
						</select>
					</td>
					<td valign="top">
						<input type="checkbox" name="checkbox_<?php echo $i; ?>" id="checkbox_<?php echo $i; ?>" value="1" <?php if (!empty($identity[$i])) echo "checked=\"checked\""; ?> onchange="disable_field('<?php echo $i; ?>');"/>
						<label for="checkbox_<?php echo $i; ?>"></label>
					</td>
				</tr>
				<?php
				}
				elseif ($config_identity_fields[$i] == 'radio') {
				?>
				<tr>
					<td valign="top">
						<label for="<?php echo $i; ?>"><?php echo $i; ?></label>
					</td>
					<td valign="top">
						<input onchange="viewSaveCheckbox();" type="radio" id="id_<?php echo $i;?>" name="<?php echo $i; ?>" <?php if (empty($identity[$i])) echo "checked=\"checked\""; ?> value="0" />None &nbsp;

						<?php foreach($lang['arr_identity_field'][$i] as $k => $v) { ?>
							<input id="id_radio_<?php echo $k; ?>" type="radio" name="<?php echo $i; ?>" value="<?php echo $k; ?>" <?php if (!empty($identity[$i]) && $identity[$i] == $k) echo "checked=\"checked\""; ?> />
							<label for="id_radio_<?php echo $k; ?>" style="float: none;font-weight:normal;"><?php echo $v; ?></label>
						<?php } ?>
					</td>
					<td valign="top">
						<input type="checkbox" name="checkbox_<?php echo $i; ?>" id="checkbox_<?php echo $i; ?>" value="1" <?php if (!empty($identity[$i])) echo "checked=\"checked\""; ?> onchange="disable_field('<?php echo $i; ?>');"/>
						<label for="checkbox_<?php echo $i; ?>"></label>
					</td>
				</tr>
				<?php
				}
				elseif ($config_identity_fields[$i] == 'avatar') {
				?>
				<?php
				if (isset($identity['avatar'])) {
				?>
				<tr>
					<td valign="top">
						<label for="<?php echo $i; ?>"><?php echo $i; ?></label>
					</td>
					<td valign="top">
						<input id="id_avatar" type="hidden" name="<?php echo $i; ?>" value="core/get_file.php?avatar=<?php echo $identity['avatar']; ?>" checked="checked" />
						<label for="id_avatar" style="float: none;font-weight:normal; border:1px solid #333;"><img src="core/get_file.php?avatar=<?php echo $identity['avatar']; ?>" alt="avatar" /></label>
					</td>
					<td valign="top">
						<input type="checkbox" name="checkbox_<?php echo $i; ?>" id="checkbox_<?php echo $i; ?>" value="1" <?php if (!empty($identity['avatar'])) echo "checked=\"checked\""; ?> onchange="disable_field('<?php echo $i; ?>');"/>
						<label for="checkbox_<?php echo $i; ?>"></label>
					</td>
				</tr>
				<?php }?>
				<?php
				}
				endforeach;
				}
				?>
				
				<tr style="visibility: hidden;" id="save_identity_box">
					<td></td>
					<td valign="top" align="right">
						<label for="save_identity" style="float: none; width: auto;">Save changes</label>
					</td>
					<td valign="top">
						<input type="checkbox" name="save_identity" id="save_identity" value="1" />
					</td>
				</tr>
				</table>
			</div>
		</div>
	</div>

	<div id="am_administration_right">
		<div class="box">
			<div class="box_header">
				<h1>Authorize</h1>
			</div>

			<div class="box_body">
				<p>
					<b>I authorize this site to use the identity information marked.</b>
				</p>
				
				<table cellspacing="0" cellpadding="4" border="0" width="100%">
					<tr>
						<td valign="top">
							<label for="trust_always" style="float: none;font-weight:normal; width:none;">Save this information and auto-connect to this site in the future</label><br />
						</td>
						<td valign="top" align="right">
							<input type="checkbox" name="trust_always" id="trust_always" value="1" checked="checked" />
						</td>
					</tr>
					<tr>
						<td valign="top">
							<label for="trust_always2" style="float: none;font-weight:normal; width:none;">Add that i have connected to my log</label><br />
						</td>
						<td valign="top" align="right">
							<input type="checkbox" name="trust_always2" id="trust_always2" value="1" checked="checked"/>
						</td>
					</tr>
				</table>
				
				<p align="right">
					<input type="submit" name="cancel" value="deny" />
					<input type="submit" name="trust" class="trust_allow" value="ALLOW" />
				</p>
			</div>
		</div>
	</div>
</div>	


<script type="text/javascript">

function disable_field(id) { 
	var elem = document.getElementsByName(id);
	for(i = 0; i < elem.length; i++) {
		elem[i].disabled = !document.getElementById('checkbox_' + id).checked;
	}
}

</script>