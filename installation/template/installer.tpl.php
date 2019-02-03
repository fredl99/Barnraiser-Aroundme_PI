<?php
// -----------------------------------------------------------------------
// This file is part of AROUNDMe
// 
// Copyright (C) 2003-2007 Barnraiser
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

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title><?php echo $lang['txt_page_title'];?></title>
	
	<style type="text/css">
	<?php include AM_TEMPLATE_PATH . 'css/installer.css'; ?>
	</style>
	
	<!--[if IE]>
	<style type="text/css">
	<?php @include AM_TEMPLATE_PATH . 'css/installer-IE.css'; ?>
	</style>
	<![endif]-->
</head>

<body>

	<?php
	if (!empty($GLOBALS['am_error_log'])) {
	?>
	<div id="error_container">
		<?php
		foreach($GLOBALS['am_error_log'] as $key => $i):
		?>
			<?php
			if (isset($lang['error'][$i[0]])) {
				echo $lang['error'][$i[0]];
			}
			else {
				echo $i[0];
			}
	
			if (!empty($i[1])) {
				echo ": " . $i[1];
			}?>
			<br />
		<?php
		endforeach;
		?>
	</div>
	<?php }?>
	
	<div id="body_container">
		
		<form method="POST" enctype="multipart/form-data"/>
		<?php 
		if (!isset($display)) {

		$lang['txt_release_version'] = str_replace ('AM_KEYWORD_RELEASE_VERSION', $config['release']['version'], $lang['txt_release_version']);
		?>
			
			<div id="installer_column_left">
				<h1><?php echo $lang['hdr_welcome'];?></h1>
				
				<p>
					<?php echo $lang['txt_release_version'];?>
				</p>
				
				<p>
					<?php echo $lang['txt_release_intro'];?>
				</p>
			</div>
			
			<div id="installer_column_right">
				<h2><?php echo $lang['hdr_install_select_openid'];?></h2>

				<p>
					<?php echo $lang['txt_install_select_openid_intro'];?>
				</p>

				<img src="index.php?image=browser_url" />
				
				<p>
					<?php echo $lang['txt_install_openid_url'];?>
				</p>
				
				<?php if (isset($openid_url)) { ?>
				<p>
					<label for="id_openid_url"><?php echo $lang['label_openid_url'];?></label><br />
					<input type="radio" name="openid_url" id="id_openid_url_1" checked="checked" onchange="javascript:document.getElementById('id_openid_url_4').style.display='none';" value="<?php echo $openid_url['openid_url_1']; ?>"/> <label for="id_openid_url_1" style="font-weight: normal; float: none;"><?php echo $openid_url['openid_url_1']; ?></label><br />
					<input type="radio" name="openid_url" id="id_openid_url_3" onchange="javascript:document.getElementById('id_openid_url_4').style.display='block';" value="0"/> <label for="id_openid_url_3" style="font-weight: normal; float: none;"><?php echo $lang['label_alt_openid_url'];?></label>
				</p>

				<input type="text" id="id_openid_url_4" name="openid_url_4" value="http://" style="display:none;" />
				<?php } ?>

				<?php
				if (empty($GLOBALS['am_error_log'])) {
				?>
				<p align="right">
					<input type="submit" name="start_install" value="<?php echo $lang['sub_start_install'];?>" />
				</p>
				<?php }?>
			</div>
		<?php
		}
		elseif (isset($display) && $display == "step1") {
		?>
			<script type="text/javascript">
				var path = '../installation/webspace/img/';
				var layout = 'smorgasbord';
				var css = 'light';
			
				function setWebspaceLayout(id) {
					layout = id;
					
					if (layout == 'expert') {
						var img = layout+'.png';
					}
					else {
						var img = css+'_'+layout+'.png';
					}
					document.getElementById('id_layout').value = layout;
					swapImg(img);
					swapText(id);
				}
			
				function setWebspaceCss(id) {
					css = id;
					
					if (layout == 'expert') {
						var img = layout+'.png';
					}
					else {
						img = css+'_'+layout+'.png';
					}
					document.getElementById('id_css').value = css;
					swapImg(img);
				}
			
				function swapImg(img) {
					document.getElementById('webspace_preview').src = 'index.php?image='+img+'&layout=1';
				}
			
				var inner_html_blog = "<h3>Blog</h3><p>A blog is a 2 column grid with the right column containing selected (or latest) blog entry. On the left is your identity card, a list of your latest blog entries and a tagcloud.</p>";
				var inner_html_smorgasbord = "<h3>Smorgasbord</h3><p>A smorgasbord is a variety of things spread over a 3 column grid. In the left colum is your identity card and your activity log. In the middle column is guestbook. In the right hand column is your network.</p>";
				var inner_html_basic = "<h3>Minimalist</h3><p>A simple profile page containing only your identity card and a connect box.</p>";
				var inner_html_expert = "<h3>Expert mode</h3><p>A blank home page is set and no CSS added. You should be competent with both HTML and CSS to choose this option!</p>";
			
			
			
				function swapText(id) {
					text = 'inner_html_'+id;
					document.getElementById('layout_intro').innerHTML = eval(text);
				
				}
			</script>
			
			<h1>Step 1 of 3 - Create your webspace</h1>
			
			<div id="installer_column_left">
				<h2>Choose look and feel</h2>

				<div class="box">
					<div class="box_body">
						<p>
							Your webspace is your profile page, or presentation. You can customise it to include all sorts of stuff including a blog and a wall. To start with pick the webspace that most closely matches what you want.
						</p>
				
						<p>
							layout 
							<a href="javascript:setWebspaceLayout('blog');">blog</a>, 
							<a href="javascript:setWebspaceLayout('smorgasbord');">smorgasbord</a>, 
							<a href="javascript:setWebspaceLayout('basic');">minimalist</a>, 
							<a href="javascript:setWebspaceLayout('expert');">expert</a>.
							<input type="hidden" value="smorgasbord" id="id_layout" name="layout"/>
							<input type="hidden" value="light" id="id_css" name="css"/>
						</p>
			
						<p>
							style: 
							<a href="javascript:setWebspaceCss('light');">light</a>, 
							<a href="javascript:setWebspaceCss('dark');">dark</a>.
						</p>
						<img id="webspace_preview" src="index.php?image=light_smorgasbord.png&layout=1" style="border: 1px solid black;" />
						<p id="layout_intro">choose</p>
					</div>
				</div>
			</div>
			
			<div id="installer_column_right">
					
				<div class="box">
					<div class="box_body">
						<h2>Title</h2>

						<p>
							<label for="id_webspace_title"><?php echo $lang['txt_label_webspace_title'];?></label>
							<input type="text" id="id_webspace_title" name="webspace_title" value="<?php if(isset($_POST['webspace_title'])) { echo stripslashes($_POST['webspace_title']);}?>" style="width:380px;" /><br />
						</p>

						<p align="right">
							<input type="submit" name="step1" value="next step" />
						</p>
					</div>
				</div>
			
				<script type="text/javascript">
					swapText('smorgasbord');
				</script>
			</div>
		<?php
		}
		elseif (isset($display) && $display == "step2") {
		?>
	
			<h1>Step 2 of 3 - Create your identity</h1>
			
			<div id="installer_column_left">
				<div class="box">
					<div class="box_body">
						<h2>Choose identity information</h2>
						
						<p>
							All options are optional, but we advise you to fill them in. No one will see them unless you specifically choose to let them see it.
						</p>
					
						<?php
						foreach ($config['identity_field'] as $key => $i):
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
					</div>
				</div>
			</div>
			
			<div id="installer_column_right">
				<div class="box">
					<div class="box_body">
						<h2>Add an avatar</h2>
						
						<p>
							<?php echo $lang['txt_avatar_intro'];?><br />
						</p>

						<p>
							<label for="frm_file"><?php echo $lang['txt_upload_file']; ?></label>
							<input type="file" name="frm_file" id="frm_file" /><br />
						</p>
					</div>
				</div>
				
				<div class="box">
					<div class="box_body">
						<h2>Password</h2>
						 
						<p>
							This is your OpenID password. You will use it when connecting to both your own webspace and other OpenID services. A secure password is very important. Choose a password which is not easy to guess. Guidelines: At least 5 characters long. Use numbers and letters. Use only a-z, A-Z and 0-9
						<p>
							<label for="id_password1">password</label>
							<input type="password" id="id_password1" name="password1" value="" />
						</p>

						<p>
							<label for="id_password2">confirm password</label>
							<input type="password" id="id_password2" name="password2" value="" />
						</p>
                      
						<p align="right">
							<input type="submit" name="step2" value="next step" />
						</p>
					</div>
				</div>
			</div>
		
 		<?php
		}
		elseif (isset($display) && $display == "step3") {
		?>
			
			<h1>Step 3 of 3 - Additional information</h1>
				
			<div id="installer_column_left">
				<div class="box">
					<div class="box_body">
						<h2>Email configuration</h2>
				
						<p>
							Some plugins need to send you email. Add the information below if you want to add email contact forms to your webspace.
						</p>
						
						<p>
							<label for="id_email_address">email address</label>
							<input type="text" name="email[email_address]" id="id_email_address" value="<?php if (isset($email['port'])) { echo $email['port'];} else { echo "you@your_mail.org";}?>" />
						</p>
					
						<p>
							<label for="id_email_host">email host</label>
							<input type="text" name="email[email_host]" id="id_email_host" value="<?php if (isset($email['host'])) { echo $email['host'];} else { echo "smtp@your_mail_host.org";}?>" /><br />
							<i>This is your SMTP server. Look in your email preferences and see what the address of the server used to send your emails is.</i>
						</p>
						
						<p>
							If you need a username and password to access SMTP type them below otherwise leave them empty
						</p>
					
						<p>
							<label for="id_email_smtp_user">username</label>
							<input type="text" name="email[smtp_user]" id="id_email_smtp_user" value="<?php if (isset($email['smtp_user'])) { echo $email['smtp_user'];}?>" />
						</p>
					
						<p>
							<label for="id_email_smtp_password">password</label>
							<input type="text" name="email[smtp_password]" id="id_email_smtp_password" value="<?php if (isset($email['smtp_password'])) { echo $email['smtp_password'];}?>" />
						</p>
					</div>
				</div>
			</div>
			
			
			
			<div id="installer_column_right">
				<div class="box">
					<div class="box_body">
						<h2>Email hints</h2>
						
						<p>
							Open your email software and look in the settings to find these fields.
						</p>

						<p align="right">
							<input type="submit" name="step3" value="finish" />
						</p>
					</div>
				</div>
			</div>
		
 		<?php
		}
		elseif (isset($display) && $display == "complete") {
		?>
			<h1>You're done</h1>

			<div id="installer_column_left">
				<div class="box">
					<div class="box_body">
						<h2>Congratulations!</h2>
						
						<p>
							Congratulations! Your installation is complete.
						</p>
					</div>
				</div>
			</div>
			
			<div id="installer_column_right">
				<div class="box">
					<div class="box_body">
						<h2>Remember</h2>
						
						<p>
							Your OpenID is <b><?php echo $openid_url; ?></b>. Write this down and remember your password. To connect to any OpenID enabled website or to log in to your webspace you will see an OpenID connect form. Enter this OpenID. You will be taken to your webspace and asked for your password. Enter it and you will be connected.
						</p>
						
						<ul>
							<li><a href="<?php echo $openid_url; ?>">Go straight to your webspace and start playing</a></li>
						</ul>
					</div>
				</div>
			</div>
		<?php }?>
		
		</form>
	</div>
</body>
</html>




