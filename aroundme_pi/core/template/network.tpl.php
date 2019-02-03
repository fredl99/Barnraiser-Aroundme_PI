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

	
<form action="index.php?t=network" method="POST">

<div id="am_administration">
	<?php
	if (isset($inbound_connection)) {
	?>
	
	
	<script type="text/javascript">
	
		var nr_of_connectons_inbound = "<?php echo $inbound_connection['connections']; ?>";
		
		<?php if (isset($inbound_connection['is_vouched']) && !empty($inbound_connection['is_vouched'])) { ?>
			var is_vouched = true;
		<?php } else { ?>
			var is_vouched = false;
		<?php } ?>
		
		<?php if (isset($inbound_connection['reference']) && !empty($inbound_connection['reference'])) { ?>
			var reference = "<?php echo $inbound_connection['reference']; ?>";
		<?php } else { ?>
			var reference = false;
		<?php } ?>
	
		function fetchPopLog(identity) {
			str = 'plugins/barnraiser_connection/ajax.php';
			p = 'identity=' + identity;

			makeRequest(str, p, displayPopLog);
		}
		
		
		function makeRequest(url, parameters, destination) {

			http_request = false;

			if (window.XMLHttpRequest) { // Mozilla, Safari,...
				http_request = new XMLHttpRequest();
				if (http_request.overrideMimeType) {
					// set type accordingly to anticipated content type
					http_request.overrideMimeType('text/xml');
					//http_request.overrideMimeType('text/html');
				}
			}
			else if (window.ActiveXObject) { // IE
				try {
					http_request = new ActiveXObject("Msxml2.XMLHTTP");
				} 
				catch (e) {
					try {
						http_request = new ActiveXObject("Microsoft.XMLHTTP");
					} 
					catch (e) {
					}
				}
			}

			if (!http_request) {
				alert('Cannot create XMLHTTP instance');
				return false;
			}
			http_request.onreadystatechange = destination;
			http_request.open('POST', url, true); 
			http_request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			http_request.send(parameters);
		}
		
		
		function displayPopLog() {
			if (http_request.readyState == 4) {
				if (http_request.status == 200) {
					xmlDoc = http_request.responseXML;

					failure = xmlDoc.getElementsByTagName('failure');

					if (failure.length == 0) {
						friends = xmlDoc.getElementsByTagName('inbound');
						number_of_friends = friends.length;

						//we can also get each friend (your friends friends) and perhaps display their name/id in a list
						//alert(friends[0].getElementsByTagName('identity')[0].firstChild.nodeValue);

						log_entries = xmlDoc.getElementsByTagName('logentry');
						number_of_log_entries = log_entries.length;

						nr_of_connectons_outbound = 0;
						is_vouched_outbound = false;
						reference_outbound = false;
						
						for(k=0; k < number_of_friends; k++) {
							if (friends[k].getElementsByTagName('identity')[0].firstChild.nodeValue == "<?php echo $_SESSION['openid_identity']; ?>") {
								nr_of_connectons_outbound = friends[k].getElementsByTagName('connections')[0].firstChild.nodeValue;
								is_vouched_outbound = friends[k].getElementsByTagName('is_vouched')[0].firstChild.nodeValue;
								reference_outbound = friends[k].getElementsByTagName('reference')[0].firstChild.nodeValue;
							}
						}
						
						

						nickname = xmlDoc.getElementsByTagName('me_nickname')[0].firstChild.nodeValue;

						output = "<p>" + nickname + " has connected to you " + nr_of_connectons_inbound + " time(s).</p>";
						output += "<p>You have connected to " + nickname + " " + nr_of_connectons_outbound + " time(s).</p>";
						
						if (is_vouched) {
							output += "<p>";
							output += "You have vouched for " + nickname;
							if (reference != false) {
								output += "<br />Reference: " + reference;
							}
							output += "</p>";
						}
						else {
							output += "<p>You have not vouched for " + nickname + "</p>";
						}
						
						if (is_vouched_outbound != false && is_vouched_outbound != '0') {
							output += "<p>";
							output += nickname + " has vouched for you";
							if (reference_outbound != false) {
								output += "<br />Reference: " + reference_outbound;
							}
							output += "</p>";
						}
						else {
							
						}

						if (number_of_log_entries > 0) {
							output += "<ul>";
							for(i = number_of_log_entries-1; i >= Math.max(0, number_of_log_entries-11); i--) {
								datetime = log_entries[i].getElementsByTagName('datetime')[0].firstChild.nodeValue;

								/*dateObj = new Date(datetime * 1000);
								dateformat = dateObj.getDate() + '/' + dateObj.getMonth() + ' ' + dateObj.getHours() + ':' + dateObj.getMinutes();*/

								entry = log_entries[i].getElementsByTagName('entry')[0].firstChild.nodeValue;
								output += "<li>" + datetime + ": " + entry + "</li>";
							}
							output += "</ul>";
						}
						else {
							output += "<p>no entries</p>";
						}

					}
					else { // there was a failure
						output = "<p>this person has no network</p>"
					}
					document.getElementById('barnraiser_network_gallery_extended_connection').innerHTML = output;
				} 
				else {
					alert('There was a problem with the request.');
				}
			}
		}
	</script>
	
	
	<div id="am_administration_left">
	
		<div class="box">
			<div class="box_header">
				<h1>card</h1>
			</div>

			<div class="box_body">
				<?php
				if (!empty($inbound_connection['avatar'])) {
				?>
				<a href="<?php echo $inbound_connection['identity'];?>"><img src="<?php echo $inbound_connection['avatar'];?>" style="border: solid 1px #000;margin-bottom:3px;" width="100" height="100" alt="" border="" /></a><br />
				<?php
				}
				else {
				?>
				<a href="<?php echo $inbound_connection['identity'];?>"><img src="<?php echo AM_TEMPLATE_PATH;?>img/no_avatar.png" width="100" height="100" style="border: solid 1px #000;margin-bottom:3px;" alt="" border="" /></a><br />
				<?php }?>
			
				<table cellspacing="0" cellpadding="2" border="0">
	            <?php
	            $card_identity_fields = $config_identity_fields;
	            unset($card_identity_fields['description'], $card_identity_fields['avatar']);

	            foreach($card_identity_fields as $key => $i):
	            if (!empty($inbound_connection[$key])) {
				?>
	                <tr>
	                    <td valign="top" class="profile_field">
	                         <?php echo $lang['txt_identity_'.$key];?>:<br />
	                    </td>
	                    <td valign="top" class="profile_value">
		                    <?php
		                    if (isset($lang['arr_identity_field'][$key][$inbound_connection[$key]])) {
			                   echo $lang['arr_identity_field'][$key][$inbound_connection[$key]];
			                }
			                else {
			              	echo $inbound_connection[$key];
			                }
	                        ?><br />
	                    </td>
	                </tr>
	                <?php
	                }
	                endforeach;
	                ?>
	            </table>
	
				<ul>
					<li><a href="<?php echo $inbound_connection['identity'];?>">Visit</a></li>
					<li><a href="index.php?t=network">Return to network</a></li>
				</ul>
			</div>
		</div>
		
		<div class="box">
			<div class="box_header">
				<h1>Connection summary</h1>
			</div>

			<div class="box_body">
				<div class="presentation" id="barnraiser_network_gallery_extended_connection">loading...</div>
			</div>
		</div>
	</div>
	
	<div id="am_administration_right">
		<div class="box">
			<div class="box_header">
				<h1>Nerd stature</h1>
			</div>
			
			<div class="box_body">	
				<p>
					Set the nerds access rights (status). If you know and respect a nerd then vouch for their soul. You cannot vouch for a critter. Smurfs have extra powers.
				</p>
				
				<input type="hidden" name="connection_id" value="<?php echo $inbound_connection['filename'];?>" />
				
				<p>
					<label for="id_permission">Permissions</label><br />
					<input type="radio" id="id_permission_32" name="connection_permission" value="32"<?php if ($inbound_connection['permission'] == 32) { echo "checked=\"checked\"";}?> /><label style="float: none; font-weight: normal;" for="id_permission_32"><?php echo $lang['arr_permission_level'][32];?></label><br />
					<input type="radio" id="id_permission_16" name="connection_permission" value="16"<?php if ($inbound_connection['permission'] == 16) { echo "checked=\"checked\"";}?> /><label style="float: none; font-weight: normal;" for="id_permission_16"><?php echo $lang['arr_permission_level'][16];?></label><br />
					<input type="radio" id="id_permission_4" name="connection_permission" value="4"<?php if ($inbound_connection['permission'] == 4) { echo "checked=\"checked\"";}?> /><label style="float: none; font-weight: normal;" for="id_permission_4"><?php echo $lang['arr_permission_level'][4];?></label><br />
				<p>
				
				<p>
					<label for="id_vouched">Vouch</label><br />
					<label style="float: none; font-weight: normal;" for="id_connection_is_vouched">I know, trust and vouch for the soul of this person</label>
					<input type="checkbox" id="id_connection_is_vouched" name="connection_is_vouched"<?php if (isset($inbound_connection['is_vouched'])) { echo " checked=\"checked\"";}?> />
				</p>
				
				<p>
					<label for="id_reference">Reference</label><br />
					<textarea id="id_reference" name="connection_reference"><?php if (isset($inbound_connection['reference'])) { echo $inbound_connection['reference'];}?></textarea>
				<p>
				
				<p align="right">
					<input type="submit" name="save_connection" value="save" />
				</p>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		fetchPopLog('<?php echo $inbound_connection['identity']; ?>');
	</script>
	
	<?php
	}
	elseif (isset($_REQUEST['v']) && $_REQUEST['v'] == "inbound") {
	?>
	<div class="box">
		<div class="box_header">
			<h1>Incoming nerds</h1>
		</div>

		<div class="box_body">
		
			<?php
			if (isset($inbound_connections['connections'])) {
			?>
			<table cellspacing="0" cellpadding="2" border="0" width="100%">
			<?php
			foreach ($inbound_connections['connections'] as $key => $i):
			?>
			<tr>
				<td valign="top" width="40">
					<?php
					if (!empty($i['avatar'])) {
					?>
					<a href="index.php?t=network&amp;inbound_connection_id=<?php echo $i['filename']; ?>" title="<?php echo $i['nickname']; ?> - <?php echo $i['identity']; ?>"><img src="<?php echo $i['avatar'];?>" style="border: solid 1px #000;margin-bottom:3px;" width="40" height="40" alt="" border="" /></a><br />
					<?php
					}
					else {
					?>
					<a href="index.php?t=network&amp;inbound_connection_id=<?php echo $i['filename']; ?>" title="<?php echo $i['nickname']; ?> - <?php echo $i['identity']; ?>"><img src="<?php echo AM_TEMPLATE_PATH;?>img/no_avatar.png" width="40" height="40" style="border: solid 1px #000;margin-bottom:3px;" alt="" border="" /></a><br />
					<?php }?>
				</td>
				<td valign="top">
					<a href="<?php echo $i['openid'];?>"><b><?php echo $i['nickname'];?></b></a><br />
					
					<p>
						<b>Identity information:</b>
					
						<?php
						$card_identity_fields = $config_identity_fields;
						unset($card_identity_fields['nickname'], $card_identity_fields['avatar']);

						foreach($card_identity_fields as $cif_key => $cif):
						if (!empty($i[$cif_key])) {
						?>
						<?php echo $lang['txt_identity_'.$cif_key];?>: 
						<?php
					
						if (isset($lang['arr_identity_field'][$cif_key][$i[$cif_key]])) {
							echo $lang['arr_identity_field'][$cif_key][$i[$cif_key]];
						}
						else {
							echo $i[$cif_key].", ";
						}
						}
						endforeach;
						?>
					</p>
					
					<p>
						<b>Stature</b>: 
						<?php echo $lang['arr_permission_level'][$i['permission']];?>
					
						<?php
						if (!empty($i['is_vouched'])) {
							echo ", " . $lang['txt_vouched'];
						}
						?>
					</p>
					
					<?php
					if (!empty($i['reference'])) {
						echo "<p><b>Reference:</b> <i> " . $i['reference'] . "</i></p>";
					}
					?>
				</td>
			</tr>
			<?php		
			endforeach;
			?>
			</table>
			<?php
			}
			else {
			?>
			<p>
				No one has connected to you yet.
			</p>
			<?php }?>
		</div>
	
		<div class="box_footer">
			<a href="#">What is this?</a>
		</div>
	</div>
			
	<?php
	}
	elseif (isset($_REQUEST['v']) && $_REQUEST['v'] == "outbound_human") {
	?>
		<div class="box">
			<div class="box_header">
				<h1>Nerds visited</h1>
			</div>

			<div class="box_body">
				<?php
				if (isset($outbound_connections['humans'])) {
				?>
				<table cellspacing="0" cellpadding="2" border="0" width="100%">
					<?php
					foreach ($outbound_connections['humans'] as $key => $i):
					?>
					<tr>
						<td valign="top" colspan="4">
							<a href="<?php echo $i['realm'];?>"><b><?php echo $i['title'];?></b></a><br />
						</td>
					</tr>
					<tr>
						<td>
							Data sent: 

							<?php
							if (!empty($i['sent_data'])) {
							foreach ($i['sent_data'] as $ds_key => $ds):
								echo $ds . ", ";
							endforeach;
							}
							?>
						</td>
						<td valign="top">
							First visit: <?php echo $i['datetime_first_visit'];?><br />
						</td>
						<td valign="top">
							Last visit: <?php echo $i['datetime_last_visit'];?><br />
						</td>
						<td align="right">
							<?php
							$checked ="";
							if (!empty($i['trusted'])) {
								$checked = " checked=\"checked\"";
							}
							?>
							<input type="checkbox" value="<?php echo $i['filename'];?>" name="trusted_humans[]"<?php echo $checked;?> />
						</td>
					</tr>
					<?php
					endforeach;
					?>
				</table>
				
				<p align="right">
					<input type="submit" name="set_trust_human" value="set auto-login" />
				<p>
				<?php
				}
				else {
				?>
				You've not visited any fellow nerds'. Click on some of your inbound nerds to go to their webspaces, then connect to join their network.
				<?php }?>
			</div>
		</div>
		
	<?php
	}
	elseif (isset($_REQUEST['v']) && $_REQUEST['v'] == "outbound_sites") {
	?>
	
		<div class="box">
			<div class="box_header">
				<h1>Websites visited</h1>
			</div>

			<div class="box_body">
				<?php
				if (isset($outbound_connections['sites'])) {
				?>
				<table cellspacing="0" cellpadding="2" border="0" width="100%">
					<?php
					foreach ($outbound_connections['sites'] as $key => $i):
					?>
					<tr>
						<td valign="top" colspan="4">
							<a href="<?php echo $i['realm'];?>"><b><?php echo $i['title'];?></b></a><br />
						</td>
					</tr>
					<tr>
						<td>
							Data sent: 

							<?php
							if (!empty($i['sent_data'])) {
							foreach ($i['sent_data'] as $ds_key => $ds):
								echo $ds . ", ";
							endforeach;
							}
							?>
						</td>
						<td valign="top">
							First visit: <?php echo $i['datetime_first_visit'];?><br />
						</td>
						<td valign="top">
							Last visit: <?php echo $i['datetime_last_visit'];?><br />
						</td>
						<td align="right">
							<?php
							$checked ="";
							if (!empty($i['trusted'])) {
								$checked = " checked=\"checked\"";
							}
							?>
							<input type="checkbox" value="<?php echo $i['filename'];?>" name="trusted_sites[]"<?php echo $checked;?> />
						</td>
					</tr>
					<?php
					endforeach;
					?>
				</table>
				
				<p align="right">
					<input type="submit" name="set_trust_sites" value="set auto-login" />
				<p>
				<?php
				}
				else {
				?>
				You've not visited any sites. Any site that you connect to using your OpenID that is not a person will appear here.
				<?php }?>
			</div>
		</div>
		
	<?php
	}
	else {
	?>
		<div id="am_administration_left">
			<div class="box">
				<div class="box_header">
					<h1>Latest incoming nerds</h1>
				</div>

				<div class="box_body">
				
					<?php
					if (isset($inbound_connections['connections'])) {
					foreach ($inbound_connections['connections'] as $key => $i):
					?>
					<div class="gallery_item">
						<?php
						if (!empty($i['avatar'])) {
						?>
						<a href="index.php?t=network&amp;inbound_connection_id=<?php echo $i['filename']; ?>" title="<?php echo $i['nickname']; ?> - <?php echo $i['identity']; ?>"><img src="<?php echo $i['identity'] . "/" . $i['avatar'];?>" style="border: solid 1px #000;margin-bottom:3px;" width="40" height="40" alt="" border="" /></a><br />
						<?php
						}
						else {
						?>
						<a href="index.php?t=network&amp;inbound_connection_id=<?php echo $i['filename']; ?>" title="<?php echo $i['nickname']; ?> - <?php echo $i['identity']; ?>"><img src="<?php echo AM_TEMPLATE_PATH;?>img/no_avatar.png" width="40" height="40" style="border: solid 1px #000;margin-bottom:3px;" alt="" border="" /></a><br />
						<?php }?>
					</div>
					<?php
					endforeach;
					}
					?>
					
					<div style="clear:both;"></div>
				</div>
			
				<div class="box_footer">	
					<a href="index.php?t=network&amp;v=inbound">view more</a>	
				</div>
			</div>
		
		
			<div class="box">
				<div class="box_header">
					<h1>Nerds visited</h1>
				</div>

				<div class="box_body">
					<?php
					if (isset($outbound_connections['humans'])) {
					?>
					<table cellspacing="0" cellpadding="2" border="0" width="100%">
					<?php
					foreach ($outbound_connections['humans'] as $key => $i):
					?>
					<tr>
						<td valign="top">
							<a href="<?php echo $i['realm']; ?>"><?php echo $i['title']; ?></a><br />
						</td>
					</tr>
					<?php
					endforeach;
					?>
					</table>
					<?php
					}
					else {
					?>
					You've not visited any fellow nerds'. Click on some of your inbound nerds to go to their webspaces, then connect to join their network.
					<?php }?>
				</div>
			
				<div class="box_footer">
					<a href="index.php?t=network&amp;v=outbound_human">view more and manage auto-logins</a>
				</div>
			</div>
		
			<div class="box">
				<div class="box_header">
					<h1>Websites visited</h1>
				</div>

				<div class="box_body">
					<?php
					if (isset($outbound_connections['sites'])) {
					?>
					<table cellspacing="0" cellpadding="2" border="0" width="100%">
					<?php
					foreach ($outbound_connections['sites'] as $key => $i):
					?>
					<tr>
						<td valign="top">
							<a href="<?php echo $i['realm']; ?>"><?php echo $i['title']; ?></a><br />
						</td>
					</tr>
					<?php
					endforeach;
					?>
					</table>
					<?php
					}
					else {
					?>
					You've not visited any fellow nerds'. Click on some of your inbound nerds to go to their webspaces, then connect to join their network.
					<?php }?>
				</div>
			
				<div class="box_footer">
					<a href="index.php?t=network&amp;v=outbound_sites">view more and manage auto-logins</a>
				</div>
			</div>
		
		
		</div>
	
	
		<div id="am_administration_right">
			<div class="box">
				<div class="box_header">
					<h1>Vouched</h1>
				</div>

				<div class="box_body">
					<?php
					if (isset($inbound_connections['vouched_connections'])) {
					?>
					<table cellspacing="0" celllpadding="0" border="0">
					<?php
					foreach ($inbound_connections['vouched_connections'] as $key => $i):
					?>
					<tr>
						<td valign="top" width="40">
							<?php
							if (!empty($i['avatar'])) {
							?>
							<a href="index.php?t=network&amp;inbound_connection_id=<?php echo $i['filename']; ?>" title="<?php echo $i['nickname']; ?> - <?php echo $i['identity']; ?>"><img src="<?php echo $i['identity'] . "/" . $i['avatar'];?>" style="border: solid 1px #000;margin-bottom:3px;" width="40" height="40" alt="" border="" /></a><br />
							<?php
							}
							else {
							?>
							<a href="index.php?t=network&amp;inbound_connection_id=<?php echo $i['filename']; ?>" title="<?php echo $i['nickname']; ?> - <?php echo $i['identity']; ?>"><img src="<?php echo AM_TEMPLATE_PATH;?>img/no_avatar.png" width="40" height="40" style="border: solid 1px #000;margin-bottom:3px;" alt="" border="" /></a><br />
							<?php }?>
						</td>
						<td valign="top">
							<b><?php echo $i['nickname']; ?></b><br />
						</td>
					</tr>
					<?php
					endforeach;
					?>
					</table>
					<?php
					}
					else {
					?>
					<p>
						You have not vouched for any nerds yet. Vouching for a nerd shows your respect for their most gratious entities. To vouch for a nerd select them and check "vouched".
					</p>
					<?php }?>
				</div>
			</div>

			<div class="box">
				<div class="box_header">
					<h1>Smurfs</h1>
				</div>

				<div class="box_body">
					<?php
					if (isset($inbound_connections['trusted_connections'])) {
					?>
					<table cellspacing="0" cellpadding="2" border="0">
					<?php
					foreach ($inbound_connections['trusted_connections'] as $key => $i):
					?>
					<tr>
						<td valign="top" width="40">
							<?php
							if (!empty($i['avatar'])) {
							?>
							<a href="index.php?t=network&amp;inbound_connection_id=<?php echo $i['filename']; ?>" title="<?php echo $i['nickname']; ?> - <?php echo $i['identity']; ?>"><img src="<?php echo $i['identity'] . "/" . $i['avatar'];?>" style="border: solid 1px #000;margin-bottom:3px;" width="40" height="40" alt="" border="" /></a><br />
							<?php
							}
							else {
							?>
							<a href="index.php?t=network&amp;inbound_connection_id=<?php echo $i['filename']; ?>" title="<?php echo $i['nickname']; ?> - <?php echo $i['identity']; ?>"><img src="<?php echo AM_TEMPLATE_PATH;?>img/no_avatar.png" width="40" height="40" style="border: solid 1px #000;margin-bottom:3px;" alt="" border="" /></a><br />
							<?php }?>
						</td>
						<td valign="top">
							<b><?php echo $i['nickname']; ?></b><br />
						</td>
					</tr>
					<?php
					endforeach;
					?>
					</table>
					<?php
					}
					else {
					?>
					Giving a nerd smurf status gives them permissions to see extra stuff in your webspace.
					<?php }?>
				</div>
			</div>
		
			<div class="box">
				<div class="box_header">
					<h1>Critters</h1>
				</div>

				<div class="box_body">
					<?php
					if (isset($inbound_connections['foes'])) {
					foreach ($inbound_connections['foes'] as $key => $i):
					?>
					<a href="index.php?t=network&amp;inbound_connection_id=<?php echo $i['filename']; ?>"><?php echo $i['nickname']; ?></a>
					<?php
					if (count($inbound_connections['foes']) > $key+1) {
						echo ", ";
					}
					endforeach;
					}
					else {
					?>
					Bugs are little nerds that polute your webspace and vex you. Punish them and deny their sillyness by barring them. Select them and select "bug".
					<?php }?>
				</div>
			</div>
		</div>
	<?php }?>
</div>
</form>