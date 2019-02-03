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

<!-- Made with AROUNDMe Personal identity - http://www.barnraiser.org/ - Enjoy free software -->

<head>
	<link rel="openid.server" href="<?php echo $openid['server']; ?>" />
	<link rel="openid.network" href="<?php echo $openid['network']; ?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<?php
	if (isset($webspace['language_code'])) {
	?>
	<meta http-equiv="Content-Language" content="<?php echo $webspace['language_code'];?>" />
	<?php }?>

	<?php
	if (isset($webspace['webspace_title'])) {
	?>
	<title><?php echo $webspace['webspace_title'];?></title>
	<?php
	}
	else {
	?>
	<title><?php echo $lang['txt_page_title'];?></title>
	<?php }?>
	
	
	<style type="text/css">
	<!--
	@import url(<?php echo AM_TEMPLATE_PATH;?>css/aroundme.css);
	-->
	</style>
	
	<!--[if IE]>
	<style type="text/css">
	@import url(<?php echo AM_TEMPLATE_PATH;?>css/aroundme-IE.css);
	</style>
	<![endif]-->

	<style type="text/css" id="css">
	<!--
	<?php
	if (isset($webspace['webspace_css'])) {
		echo $webspace['webspace_css'];
	}
	?>
	-->
	</style>

	<script type="text/javascript" src="<?php echo AM_TEMPLATE_PATH;?>js/functions.js"></script>

	<?php
	if (!empty($this->header_link_tag_arr)) {
	foreach ($this->header_link_tag_arr as $key => $i):
	?>
	<link rel="<?php echo $i[0];?>" type="<?php echo $i[1];?>" title="<?php echo $i[2];?>" href="<?php echo $i[3];?>" />
	<?php
	endforeach;
	}
	?>

	<link rel="icon" href="core/template/img/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="core/template/img/favicon.ico" type="image/x-icon">
</head>

<?php
if (!defined('AM_SCRIPT_NAME') && defined('AM_WEBPAGE_NAME')) {
?>	
<body id="am_webpage" onload="checkImages();">
<?php
}
else {
?>
<body id="am_admin" onload="checkImages();">
<?php }?>

	<?php
	if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {
	?>
	<div id="am_menu_container">
		<ul>
			<?php
			$link_css = "";
			if (!defined('AM_SCRIPT_NAME') && defined('AM_WEBPAGE_NAME') && defined('AM_WEBPAGE_NAME') == $webspace['default_webpage_name']) {
				$link_css = " class=\"highlight\"";
			}
			?>
			<li class="am_menu_home"><a href="index.php"<?php echo $link_css;?>>Home</a></li>

			
			<?php
			$link_css = "";
			if (defined('AM_SCRIPT_NAME') && AM_SCRIPT_NAME == "identity") {
				$link_css = " class=\"highlight\"";
			}
			?>
			<li class="am_menu_identity"><a href="index.php?t=identity"<?php echo $link_css;?>>Identity</a></li>

			
			<?php
			$link_css = "";
			if (defined('AM_SCRIPT_NAME') && AM_SCRIPT_NAME == "network") {
				$link_css = " class=\"highlight\"";
			}
			?>
			<li class="am_menu_network"><a href="index.php?t=network"<?php echo $link_css;?>>Network</a></li>

			
			<?php
			$link_css = "";
			if ((defined('AM_SCRIPT_NAME') && AM_SCRIPT_NAME == "setup") || (isset($_REQUEST['p']) && isset($_REQUEST['t']) && $_REQUEST['t'] == "maintain")) {
				$link_css = " class=\"highlight\"";
			}
			?>
			<li class="am_menu_setup"><a href="index.php?t=setup"<?php echo $link_css;?>>Webspace</a></li>
			
			
			<?php
			if(isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {
			$link_css = "";
			if (defined('AM_SCRIPT_NAME') && AM_SCRIPT_NAME == "file") {
				$link_css = " class=\"highlight\"";
			}
			?>
			<li class="am_menu_file"><a href="index.php?t=file"<?php echo $link_css;?>><?php echo $lang['href_menu_file'];?></a></li>
			<?php }?>

		
			<?php
			if(defined('AM_WEBPAGE_NAME')){
			$link_css = "";
			if (defined('AM_SCRIPT_NAME') && AM_SCRIPT_NAME == "webpage") {
				$link_css = " class=\"highlight\"";
			}
			?>
			<li class="am_menu_edit"><a href="index.php?t=webpage&amp;wp=<?php echo AM_WEBPAGE_NAME;?>"<?php echo $link_css;?>>Edit</a></li>

			
			<li class="am_menu_style"><a href="#" onclick="javascript:launchPopupWindow('core/stylesheet_editor.php', '350', '550');">Style</a></li>
			<?php }?>

			<li class="am_menu_disconnect"><a href="index.php?disconnect=1"><?php echo $lang['am_menu_disconnect'];?></a></li>
			
		</ul>
	</div>
	<?php }?>

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
		<?php echo $content;?>
	</div>

	<!-- AROUNDMe Personal identity server version <?php echo $core_config['release']['version'];?> - Installed <?php echo $core_config['release']['install_date'];?> -->

</body>
</html>