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

<div class="am_administration">

	<form action="index.php?t=identity" method="POST" enctype="multipart/form-data">

	<div id="am_administration_left">
		<div class="box">
			<div class="box_header">
				<h1>Your identity</h1>
			</div>
		
			<div class="box_body">
				<p>
					<?php echo $lang['txt_identity_intro'];?><br />
				</p>
			</div>
		</div>
		
		
		 <div class="box">
		 	<div class="box_header">
		 		<h1>Create your Identity</h1>
		 	</div>
         
		 	<div class="box_body">
				<?php
				foreach ($config_identity_fields as $key => $i):
				?>
		
				<?php
				if ($i == "text") {
				?>
				
				<p>
					<label for="id_<?php echo $key;?>"><?php echo $lang['txt_identity_' . $key];?></label>
					<input type="text" name="identity[<?php echo $key;?>]" id="id_<?php echo $key;?>" value="<?php if (isset($identity[$key])) { echo $identity[$key];}?>" />
				</p>
				
				<?php
				}
				elseif ($i == "textarea") {
				?>
				
				<p>
					<label for="id_<?php echo $key;?>"><?php echo $lang['txt_identity_' . $key];?></label>
					<textarea cols="30" rows="6" name="identity[<?php echo $key;?>]" id="id_<?php echo $key;?>"><?php if (isset($identity[$key])) { echo $identity[$key];}?></textarea>
				</p>
			
				<?php
				}
				elseif ($i == "select") {
				?>
				
				<p>
					<label for="id_<?php echo $key;?>"><?php echo $lang['txt_identity_' . $key];?></label> 
					
					<select id="id_<?php echo $key;?>" name="identity[<?php echo $key;?>]">
						<option value="0" selected="selected"><?php echo $lang['txt_select_none'];?></option>
						<?php
						foreach ($lang['arr_identity_field'][$key] as $selectkey => $s):
						?>
						<option value="<?php echo $selectkey;?>"<?php if(isset($identity[$key]) && $identity[$key] == $selectkey) { echo " selected=\"selected\"";}?>><?php echo $s;?></option>
						<?php
						endforeach;
						?>
					</select>
				</p>
				
				<?php
				}
				elseif ($i == "radio") {
				?>
				
				<p>
					<label for="id_<?php echo $key;?>"><?php echo $lang['txt_identity_' . $key];?></label> 
					<input type="radio" id="id_<?php echo $key;?>" name="identity[<?php echo $key;?>]" value="0" checked="checked" />None &nbsp;
					
					<?php
					foreach ($lang['arr_identity_field'][$key] as $radiokey => $r):
					?>
					<input type="radio" id="id_<?php echo $r;?>" name="identity[<?php echo $key;?>]" value="<?php echo $radiokey;?>"<?php if(isset($identity[$key]) && $identity[$key] == $radiokey) { echo " checked=\"checked\"";}?> /><label style="float: none; font-weight: normal;" for="id_<?php echo $r;?>"><?php echo $r;?></label> &nbsp;
					<?php
					endforeach;
					?>
				</p>
				<?php }?>
				
				<?php
				endforeach;
				?>
			
				<p align="right">
					<input type="submit" value="Save Identity" name="save_identity" />
				</p>
			</div>
		</div>
	</div>

	<div id="am_administration_right">
		<?php
		if (isset($avatars)) {
		?>
		<div class="box">
			<div class="box_header">
				<h1><?php echo $lang['hdr_avatar'];?></h1>
			</div>

			<div class="box_body">
				<p>
					These are the avatars you have available. You can check the checkboxes to delete unwanted avatars and you can click the radio button to select the avatar that you want to associate with your identity card.
				</p>

				<p>
					<?php
					foreach($avatars as $key => $i):
					?>
					<div class="avatar_gallery">
						<label for="<?php echo $i; ?>" style="float: none; cursor: pointer;"><img src="core/get_file.php?avatar=<?php echo $i;?>" alt="avatar" /></label><br />


						<p>
							<?php
							
							$checked = "";
							if (isset($identity['avatar']) && $i == $identity['avatar']) {
								$checked = " checked=\"checked\"";
							}
							?>
							<input id="<?php echo $i; ?>" type="radio" name="current_avatar_name" value="<?php echo $i;?>"<?php echo $checked;?> />

							<?php
							if (!isset($identity['avatar']) || $i != $identity['avatar']) {
							?>
							<input type="checkbox" name="delete_avatar_name[]" value="<?php echo $i;?>" /><br />
							<?php }?>
						</p>
					</div>
					<?php
					endforeach;
					?>
				</p>
				<div style="clear: both;"></div>

				<p align="right">
					<input type="submit" name="submit_delete_avatar" value="<?php echo $lang['sub_delete_avatar'];?>" />&nbsp;
					<input type="submit" name="submit_set_avatar" value="<?php echo $lang['sub_set_avatar'];?>" />
				</p>
			</div>
		</div>
		<?php }?>
		
		
		<div class="box">
			<div class="box_header">
				<h1>Upload an avatar</h1>
			</div>

			<div class="box_body">
				<p>
					<?php echo $lang['txt_avatar_intro'];?>
				</p>

				<p>
					<label for="frm_file"><?php echo $lang['txt_upload_file']; ?></label>
					<input type="file" name="frm_file" id="frm_file" />
				</p>

				<p align="right">
					<input type="submit" name="submit_upload_avatar" value="<?php echo $lang['sub_upload_avatar'];?>" />
				</p>
			</div>
		</div>
	</div>
	</form>
	</div>