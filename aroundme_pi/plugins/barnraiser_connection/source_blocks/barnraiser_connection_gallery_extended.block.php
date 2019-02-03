<script type="text/javascript">

	<?php if (isset($inbound_connections)) { ?>
	var _identities = new Array();
	
	var _datetime_last_visit = new Array();
	var _avatars = new Array();
	var _vouched = new Array();
	var _references = new Array();
	var _nicknames = new Array();
	
	<?php if (isset($_SESSION['openid_identity']) && !empty($_SESSION['openid_identity'])) { ?>
	var _visitor = "<?php echo $_SESSION['openid_identity']; ?>";
	<?php } else { ?>
	var _visitor = false;
	<?php } ?>
	
		<?php foreach($inbound_connections as $key => $val) { ?>
			<?php if (!isset($val['empty']) && isset($val['identity'], $val['datetime_last_visit'], $val['nickname'])) { ?>
			_identities[<?php echo $key; ?>] = "<?php echo $val['identity']; ?>";
			_nicknames[<?php echo $key; ?>] = "<?php echo $val['nickname']; ?>";
			
			_datetime_last_visit[<?php echo $key; ?>] = "<?php echo $val['datetime_last_visit']; ?>";
			
			<?php if (isset($val['avatar'])) { ?>
				_avatars[<?php echo $key; ?>] = "<?php echo $val['identity'] . "/" . $val['avatar']; ?>";
			<?php } ?>
			
			<?php if (isset($val['is_vouched'])) { ?>
				_vouched[<?php echo $key; ?>] = true;
			<?php } else { ?>
				_vouched[<?php echo $key; ?>] = false;
			<?php } ?>
			
			<?php if (isset($val['reference'])) { ?>
				_references[<?php echo $key; ?>] = "<?php echo $val['reference']; ?>";
			<?php } else { ?>
				_references[<?php echo $key; ?>] = false;
			<?php } ?>
			
			
			<?php } ?>
		<?php } ?>
	<?php } ?>
	
	// AJAX _GET request to script
	var http_request = false;
	var _display = false;
	var _identity = false;
	var _global_i = false;

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

	function fetchPopLog(identity) {
		document.getElementById('barnraiser_network_gallery_extended_connection').innerHTML = "<b>Loading network...</b>";
		str = 'plugins/barnraiser_connection/ajax.php';
		p = 'identity=' + identity;
		
		if (!_identity) {
			_identity = identity;
			_display = true;
		}
		else if (_identity == identity) {
			_display = false;
			_identity = false;
		}
		else {
			_identity = identity;
			_display = true;
		}
		
		// compute _global_i (we need this to point to different friends)
		for(j = 0; j < _identities.length; j++) {
			if (_identities[j] == _identity) {
				_global_i = j;
			}
		}

		makeRequest(str, p, displayPopLog);
	}
	
	function displayPopLog() {
		if (http_request.readyState == 4) {
			if (http_request.status == 200) {
				xmlDoc = http_request.responseXML;

				failure = xmlDoc.getElementsByTagName('failure');
				
				if (failure.length == 0 && typeof xmlDoc.getElementsByTagName('me_nickname')[0].firstChild.nodeValue != 'undefined') {
					friends = xmlDoc.getElementsByTagName('inbound');
					number_of_friends = friends.length;
				
					//we can also get each friend (your friends friends) and perhaps display their name/id in a list
					//alert(friends[0].getElementsByTagName('identity')[0].firstChild.nodeValue);
				
					log_entries = xmlDoc.getElementsByTagName('logentry');
					number_of_log_entries = log_entries.length;
					
					
					nickname = xmlDoc.getElementsByTagName('me_nickname')[0].firstChild.nodeValue;
					
					if (_visitor == _identities[_global_i]) {
						visitor_is_me = true;
					}
					else {
						visitor_is_me = false;
					}
				
					<?php if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) { ?>
						owner_is_me = true;
					<?php } else { ?>
						local_nickname = "<?php echo $owner['nickname']; ?>";
						owner_is_me = false;
					<?php } ?>
				
					if (visitor_is_me) {
						output = "<h3>You</h3>";
					}
					else {
						output = "<h3>" + nickname + "</h3>";
					}

					if (_avatars[_global_i]) {
						output += "<p style=\"float: left;\"><img style=\"border: 1px solid black;\" src=" + _avatars[_global_i] + " /></p>";
					}
					
					if (_datetime_last_visit[_global_i] == '0') {
						//output += "<p><b>" + local_nickname + "</b> have not connected to <b>" + nickname + "</b> yet.</p>";
						if (owner_is_me) {
							output += "<p><b>You</b> have not connected to <b>" + nickname + "</b> yet.<br /><a href=\"" + _identities[_global_i] + "\">Go and say hello! :)</a></p>";
						}
						else if (visitor_is_me) {
							output += "<p><b>" + local_nickname + "</b> have not connected to <b>you</b> yet. Write something in their guestbook to make them connect! :)</p>";
						}
						else {
							output += "<p><b>" + local_nickname + "</b> have not connected to <b>" + nickname + "</b> yet.</p>";
						}
					}
					else {
						/*tmpDate = new Date(_datetime_last_visit[_global_i] * 1000);*/
						if (owner_is_me) {
							output += "<p><b>You</b> connected to <a href=\"" + _identities[_global_i] + "\">" + nickname + "'s</a> page last on the " + _datetime_last_visit[_global_i] + "</p>";
						}
						else if (visitor_is_me) {
							output += "<p><b>" + local_nickname + "</b> did connect to <b>your</b> place last on the " + _datetime_last_visit[_global_i] + "</p>";
						}
						else {
							output += "<p><b>" + local_nickname + "</b> have connected to <a href=\"" + _identities[_global_i] + "\">" + nickname + "'s</a> page last on the " + _datetime_last_visit[_global_i] + "</p>";
						}
					}
					
					if (_vouched[_global_i]) {
						if (owner_is_me) {
							output += "<p><b>You</b> have vouched for <b>" + nickname + "</b><br />";
							if (_references[_global_i] == false) {
								output += "<b>You</b> have not given <b>" + nickname + "</b> any reference yet.</p>";
							}
							else {
								output += "<b>Your reference to " + nickname + ":</b> <i>" + _references[_global_i] + "</i></p>";
							}
						}
						else if (visitor_is_me) {
							output += "<p><b>" + local_nickname + "</b> has vouched for you!<br />";
							if (_references[_global_i] == false) {
								output += "<b>" + local_nickname + "</b> have not given <b>you</b> any reference yet.</p>";
							}
							else {
								output += "<b>" + local_nickname + "'s reference to you:</b> <i>" + _references[_global_i] + "</i></p>";
							}
						}
						else {
							output += "<p><b>" + local_nickname + "</b> has vouched for <b>" + nickname + "</b>!<br />";
							if (_references[_global_i] == false) {
								output += "<b>" + local_nickname + "</b> have not given <b>" + nickname + "</b> any reference yet.</p>";
							}
							else {
								output += "<b>" + local_nickname + "'s reference to " + nickname + ":</b> <i>" + _references[_global_i] + "</i></p>";
							}
						}
					}
				
					if (visitor_is_me) {
						output += "<p><b>You</b> have " + number_of_friends + " nerds in your network, and " + number_of_log_entries + " pops i your poplog!</p>";
					}
					else {
						output += "<p><b>" + nickname + "</b> has " + number_of_friends + " nerds in their network, and " + number_of_log_entries + " pops i their poplog!</p>";
					}
				
					if (visitor_is_me) {
						output += "<p><i>Stuff happening at <b>your</b> place...</i></p>";
					}
					else {
						output += "<p><i>Stuff happening at <b>" + nickname + "'s</b> place...</i></p>";
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
				
					output += "<ul>";
					if (visitor_is_me) {
						output += "<li><b><a href=\"" + _identity + "\">back to your place</a></b></li>";
					}
					else {
						output += "<li><b><a href=\"" + _identity + "\">visit " + nickname + "</a></b></li>";
					}
					output += "</ul>";
				}
				else { // there was a failure
					output = "<p><b>" + _nicknames[_global_i] + "</b> has no network</p>";
					output += "<p><a href=\"" + _identity + "\">visit " + _nicknames[_global_i] + "</a></p>";
				}
				document.getElementById('barnraiser_network_gallery_extended_connection').innerHTML = output;
				
				if (!_display) {
					document.getElementById('barnraiser_network_gallery_extended_connection').style.display = 'none';	
				}
				else {
					document.getElementById('barnraiser_network_gallery_extended_connection').style.display = 'block';	
				}
			} 
			else {
				alert('There was a problem with the request.');
			}
		}
	}

</script>

<div class="barnraiser_network_gallery_extended">
	<div class="block">
		<div class="block_body">
			<?php
			if (isset($inbound_connections)) {
			foreach ($inbound_connections as $key => $i):
			?>
			<div class="gallery_item">
			    <?php
				if (!empty($i['avatar'])) {
				?>
				    <a href="#" title="<?php echo $i['nickname']; ?> - <?php echo $i['identity']; ?>" onClick="fetchPopLog('<?php echo $i['identity']; ?>');"><img src="<?php echo $i['avatar'];?>" class="avatar" style="width:40px; height:40px;" width="40" height="40" alt="" border="" /></a><br />
				<?php
				}
				elseif (isset($i['openid'])) {
				?>
				<div class="no_avatar" style="width:40px; height:40px;" title="<?php echo $i['nickname']; ?> - <?php echo $i['identity']; ?>" onClick="fetchPopLog('<?php echo $i['identity']; ?>');"></div>
                <?php
				}
				else {
			    ?>
				<div class="avatar_placeholder" style="width:40px; height:40px;"></div>
                <?php } ?>
             </div>
	         <?php
	         endforeach;
	         }
	         else {
             ?>
	         <p>
	             No connections to display.
	         </p>  
	         <?php }?>
			 <div style="clear:both;"></div>
			
			 <div class="presentation" id="barnraiser_network_gallery_extended_connection"></div>
	    </div>
	
	
	    <div class="block_footer">
	       <?php
           if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {
           ?>
           <a href="index.php?t=network">manage my network</a>
           <?php }?>
	    </div>
	</div>
</div>