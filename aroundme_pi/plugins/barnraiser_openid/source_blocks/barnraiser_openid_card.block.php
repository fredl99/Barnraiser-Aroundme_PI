<?php
if (isset($_SESSION['permission'])) {
  $display_level = $_SESSION['permission'];
}
else {
  $display_level = 0;
}
?>

<div class="barnraiser_openid_card">
    <div class="block">	
 		<div class="block_body">
            <?php
            if (isset($identity['level']['avatar']) && $display_level >= $identity['level']['avatar'] && isset($identity['avatar'])) {
            ?>
                <img src="core/get_file.php?avatar=<?php echo $identity['avatar'];?>" width="100" height="100" alt="avatar" />
            <?php }?>
 
            <?php
            if (isset($identity['level']['description']) && $display_level >= $identity['level']['description'] && isset($identity['description'])) {
            ?>
            <p>
                <?php echo $identity['description'];?>
            </p>
            <?php }?>
 
            <table cellspacing="0" cellpadding="2" border="0">
            <?php
            $card_identity_fields = $config_identity_fields;
            unset($card_identity_fields['description'], $card_identity_fields['avatar']);
 
            foreach($card_identity_fields as $key => $i):
            if (!empty($identity[$key])) {

            if (isset($identity['level'][$key]) && $display_level >= $identity['level'][$key] && isset($identity[$key])) {
            ?>
                <tr>
                    <td valign="top" class="profile_field">
                         <?php echo $lang['txt_identity_'.$key];?>:<br />
                    </td>
                    <td valign="top" class="profile_value">
	                    <?php
	                    if (isset($lang['arr_identity_field'][$key][$identity[$key]])) {
		                   echo $lang['arr_identity_field'][$key][$identity[$key]];
		                }
		                else {
		              	echo $identity[$key];
		                }
                        ?><br />
                    </td>
                </tr>
                <?php
                }
                }
                endforeach;
                ?>
            </table>
 
            <ul>
                <li><a href="plugins/barnraiser_openid/vcard.php">vCard</a></li>
            </ul>
        </div>

        <?php
        if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {
        ?>
        <div class="block_footer">
            <a href="index.php?p=barnraiser_openid&amp;t=maintain&amp;wp=<?php echo AM_WEBPAGE_NAME;?>"><?php echo $lang['href_maintain'];?></a>
        </div>
        <?php }?>
    </div>
</div>