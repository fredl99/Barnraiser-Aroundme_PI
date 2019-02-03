<a name="connect"></a>
<div class="barnraiser_openid_connect">
    <div class="block">
        <?php
        if (isset($_SESSION['permission']) && $_SESSION['permission'] >= 16) {
        ?>
        <div class="block_body">
           <table cellspacing="0" cellpadding="2" border="0">
           <?php if (isset($_SESSION['openid_fullname'])) { ?>
                <tr>
                    <td valign="top" class="profile_field">
                      Name:<br />
                    </td>
                    <td valign="top" class="profile_value">
                      <?php echo $_SESSION['openid_fullname']; ?><br />
                    </td>
               </tr>
           <?php } ?>
      
           <?php if (isset($_SESSION['openid_nickname'])) { ?>
               <tr>
                   <td valign="top" class="profile_field">
                      Nickname:<br />
                  </td>
                   <td valign="top" class="profile_value">
                       <?php echo $_SESSION['openid_nickname']; ?><br />
                   </td>
               </tr>
           <?php } ?>
      
           <?php if (isset($_SESSION['openid_country'])) { ?>
               <tr>
                    <td valign="top" class="profile_field">
                        Country:<br />
                    </td>
                    <td valign="top" class="profile_value">
                        <?php echo $lang['arr_identity_field']['country'][$_SESSION['openid_country']]; ?><br />
                    </td>
                </tr>
            <?php } ?>
      
            <?php if (isset($_SESSION['openid_language'])) { ?>
                <tr>
                    <td valign="top" class="profile_field">
                        Prefered language:<br />
                    </td>
                    <td valign="top" class="profile_value">
                        <?php echo $lang['arr_identity_field']['language'][$_SESSION['openid_language']]; ?><br />
                    </td>
                </tr>
            <?php } ?>
      
            <?php if (isset($_SESSION['connections'])) { ?>
                <tr>
                    <td valign="top" class="profile_field">
                        No of times connected:<br />
                    </td>
                    <td valign="top" class="profile_value">
                        <?php echo $_SESSION['connections']; ?><br />
                    </td>
                </tr>
            <?php } ?>
            </table>
  
            <h2>Relationship</h2>
    
            <?php if (isset($is_me)) { ?>
            	<p>You have a good relationship with yourself! :)</p>
            <?php } elseif (isset($relation)) { ?>
            	<p><?php echo $relation; ?></p>
            <?php } ?>

            <ul>
              <li><a href="index.php?disconnect=1">Disconnect</a></li>
            </ul>
        </div>
        <?php
        }
        else {
        ?>
	    <div class="block_body">
		    <p>
  		        Connect to me, join my network and comment on my stuff! To connect enter your OpenID.
  		    </p>
  
 		    <p>
  		        <form method="post">
                <label for="openid_login">OpenID</label>
                <input type="text" id="openid_login" name="openid_login" value="http://example.domain.org" onFocus="this.value=''; return false;"/>
                <input type="submit" name="connect"  value="GO" />
                </form>
            </p>
        </div>
        <?php }?>
     </div>
</div>