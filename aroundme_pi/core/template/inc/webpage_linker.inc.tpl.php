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


<a name="webpage_linker"></a>
<div class="box" id="core_webpage_linker" style="display:none;">
	<div class="box_header">
		<h1>Webpage linker</h1>
	</div>
	
	<div class="box_body">
		<?php
		if (isset($webpages)) {
		?>
		<p>
			You can copy the HTML tag into any web page to create a link to your chosen web page.
		</p>

		<table cellspacing="0" cellpadding="2" border="0" width="100%">
			<?php
			foreach ($webpages as $key => $i):
			?>
			<tr>
				<td valign="top">
					<?php echo $i;?><br />
				</td>
				<td>
					<input type="text" style="width:30em;" name="show_tag" value='<a href="index.php?wp=<?php echo $i;?>">link description</a>' onclick="javascript:this.focus();this.select();" readonly="true" /><br />
				</td>
			</tr>
			<?php
			endforeach;
			?>
		</table>
		<?php }?>
	</div>
</div>