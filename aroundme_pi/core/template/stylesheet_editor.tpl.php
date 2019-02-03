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

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	
	<title><?php echo $lang['hdr_page_title'];?></title>
	<style type="text/css">
	<!--
	@import url(<?php echo AM_TEMPLATE_PATH;?>css/aroundme.css);
	-->
	</style>

	<style type="text/css">
	<!--
	<?php
	if (isset($webspace['css'])) {
		echo $webspace['css'];
	}
	?>
	-->
	</style>
	
	<?php
	if (isset($update_mother)) {
	?>
	<script type="text/javascript">
		window.opener.location.reload(true);
	</script>
	<?php }?>
</head>

<body>

<div id="am_container">
	
	<div class="menu">
		<?php
		$link_css = "";
		if (!isset($style)) {
			$link_css = " class=\"highlight\"";
		}
		?>
		<a href="stylesheet_editor.php"<?php echo $link_css;?>>styles</a>
		
		&nbsp;&#124;&nbsp;
		
		<a href="#" onclick="javascript:self.close();"><?php echo $lang['sub_close'];?></a>
		<br />
	</div>
</div>	

<?php
if (!empty($GLOBALS['am_error_log'])) {
?>
<div id="error_container">
	ERRORS -> <?php print_r($GLOBALS['am_error_log']);?>
</div>
<?php }?>

			
<div id="body_container">
	<div id="am_administration">
		
		<form action="stylesheet_editor.php" method="POST">
		
		<?php
		if (isset($style) || isset($_REQUEST['add_style'])) {
		?>
		<div class="box">
			<div class="box_header">
				<h1>Stylesheet editor</h1>
			</div>

			<div class="box_body">

				<?php
				if (isset($style['filename'])) {
				?>
				<input type="hidden" name="style_filename" value="<?php echo $style['filename'];?>" />
				<?php
				}
				else {
				?>
				<p>
					<label for="id_style_filename">Filename</label>
					<input type="text" id="id_style_filename" name="style_filename" value="<?php if (isset($style['tmp_filename'])) { echo $style['tmp_filename'];}?>" /><br />
				</p>
				<?php }?>

				<p>
					<label for="id_style_name">Name</label>
					<input type="text" id="id_style_name" name="style_name" value="<?php if (isset($style['name'])) { echo $style['name'];}?>" /><br />
				</p>

				<p>
					<label for="id_css">Stylesheet</label>
					<textarea id="id_css" name="style_css" rows="20" cols="80" style="width:20em;"><?php if (isset($style['css'])) { echo $style['css'];}?></textarea><br />
				</p>

				<p align="right">
					<input type="submit" name="save_stylesheet" value="save" />
					<input type="button" name="preview" value="preview" onclick="previewStylesheet();"/>
				</p>
				<script type="text/javascript">
				function previewStylesheet() {
					window.opener.document.getElementById('css').innerHTML = document.getElementById('id_css').value;
				}
				</script>
			</div>
		</div>
		<?php
		}
		else {
		?>
		 <div class="box">
	  		<div class="box_header">
  				<h1>Styles</h1>
  			</div>
        
  			<div class="box_body">
				<p>
					We have a list of style blocks and we can load up the one we want to use now.
				</p>

				<?php
				if (isset($styles)) {
				?>
				<table cellspacing="0" cellpadding="2" border="0" width="100%">
				<tr>
					<td valign="top">
						<b>Name</b><br />
					</td>
					<td align="center" valign="top">
						<b>Current</b><br />
					</td>
					<td align="right" valign="top">
						<b>Del</b><br />
					</td>
				</tr>
				<?php
				foreach($styles as $key => $i):
				?>
				<tr>
					<td valign="top">
						<a href="stylesheet_editor.php?style=<?php echo urlencode($i['filename']);?>"><?php echo $i['name'];?></a><br />
					</td>
					<td align="center" valign="top">
							<?php
							$checked = "";
							if (isset($webspace['webspace_css']) && $webspace['webspace_css'] == $i['filename']) {
								$checked = " checked=\"checked\"";
							}
							?>
							<input type="radio" name="default_style_name" value="<?php echo $i['filename'];?>"<?php echo $checked;?> /><br />
						</td>
						<td align="right" valign="top">
							<?php
							if (isset($webspace['webspace_css']) && $webspace['webspace_css'] != $i['filename']) {
							?>
							<input type="checkbox" name="delete_style_names[]" value="<?php echo $i['filename'];?>" />
							<?php }?>
							<br />
						</td>
				</tr>
				<?php
				endforeach;
				?>
				</table>

				<p align="right">
					<input type="submit" name="delete_webspace_styles" value="delete checked styles" />
					<input type="submit" name="set_current_webspace_style" value="set current style" />
				</p>
				<?php }?>
	
				<ul>
					<li><a href="stylesheet_editor.php?add_style=1">Add a stylesheet</a></li>
				</ul>
			</div>
		</div>
		<?php }?>
	</form>
	</div
</div>
</body>
</html>