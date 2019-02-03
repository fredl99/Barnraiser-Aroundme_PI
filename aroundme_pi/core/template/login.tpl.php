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


<?php
if (isset($display) && $display == 'append_connection') {
?>
<form method="post">
<input type="hidden" name="return_to" value="<?php if (isset($_REQUEST['return_to'])) { echo $_REQUEST['return_to']; } elseif (isset($_SERVER['HTTP_REFERER'])) { echo $_SERVER['HTTP_REFERER'];}?>" />
	<div class="am_administration">
		<div class="box">
			<div class="box_header">
				<h1>Extra information request</h1>
			</div>
			
			<div class="box_body">
				<p>
					Please give us your nickname.
				</p>
	
				<p>
					<label for="openid_nickname"><?php echo $lang['txt_label_nickname'];?></label>
					<input type="text" id="openid_nickname" name="connection_nickname" value="" />
				</p>
	
				<p align="right">
					<input type="submit" name="update_connection" value="<?php echo $lang['sub_continue'];?>" />
				</p>
			</div>
		</div>
	</div>
</form>
<?php
}
else {
?>

<form method="post">
<input type="hidden" name="data" value="<?php echo urlencode(serialize($_GET)); ?>" />

<div class="am_administration">
	<div id="am_administration_left">
	<div class="box">
		<div class="box_header">
			<h1>SECURITY</h1>
		</div>

		<div class="box_body">
			<p>
				1. Check your OpenID account matches the first part of the URL given in your browser! If it does not then NEVER enter your password.
			</p>
			
			<p>
				<img src="<?php echo AM_TEMPLATE_PATH;?>img/browser_warning.png" alt="picture of a browsers url field" style="border: 1px solid #eee;" />
			</p>
			
			<p>
				2. Only ever type your password into this screen. NEVER type it into any other screen! If you are asked for your OpenID password anywhere else then you risk compromising your OpenID account.
			</p>
			
			<p>
				<img src="<?php echo AM_TEMPLATE_PATH;?>img/password_warning.png" alt="picture of a browsers url field" style="border: 1px solid #eee;" />
			</p>
			
			<p>
				3. Never give your password to anyone. Even the makers of this software do not need when giving you technical support. NEVER write down or give your password away.
			</p>
			
		</div>
	</div>
	</div>
	
	<div id="am_administration_right">
		<div class="login">
			<div class="box">
				<div class="box_header">
					<h1>OpenID login</h1>
				</div>
		
				<div class="box_body"">
					<p>
						<label for="id_password" style="font-weight: bold;">password</label>
						<input type="password" id="id_password" name="passwd" value="" style="font-weight: bold; border: 1px solid black;" title="enter your password here"/>
						<input type="submit" name="login" value="login" style="font-weight: bold; border: 1px solid black; cursor: pointer;" title="click to login"/>
					</p>
					
					<?php 
					if (!empty($_REQUEST['openid_mode'])) {
					?>
					<p>	
						<label for="id_reset_trust">reset trust</label>
						<input id="id_reset_trust" name="reset_trust" type="checkbox" value="1" />
					</p>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>
</form>
<?php }?>