<script type="text/javascript">
	function clean() {
		if (document.getElementById('id_guestbook_body').value == 'Type something here:)') {
			document.getElementById('id_guestbook_body').value = '';
			return false;
		}
	}
</script>

<div class="barnraiser_guestbook_list">
    <div class="block">
        <div class="block_body">
            <?php
            if (isset($guestbook_entries)){
            ?>
            <table cellspacing="0" cellpadding="2" border="0">
                <?php
                foreach ($guestbook_entries as $key => $i):
                ?>
               <tr>
                    <td>
                        <b><a href="<?php echo $i['openid'];?>" target="_top"><?php echo $i['connection']['nickname']?></a></b><br />
                    </td>
                    <td align="right">
                        <?php echo strftime("%d %b %G %H:%M", $i['datetime']);?><br />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php echo $i['entry']?>
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
                <?php echo $lang['txt_no_guestbooks'];?><br />
            </p>
            <?php }?>
  
           <div class="add">
                <?php
                if (isset($_SESSION['permission']) && $_SESSION['permission'] >= 16) {
                ?>
                <form action="plugins/barnraiser_guestbook/add_guestbook.php" method="post">
                <textarea rows="2" cols="34" id="id_guestbook_body" name="guestbook_body" onFocus="clean();">Type something here:)</textarea><br />
                <span id="guestbook_input_indicator" class="input_indicator"></span>
                <input type="submit" name="insert_guestbook" value="<?php echo $lang['href_add'];?>" />
                </form>
                <?php
                }
                else {
                ?>
                <p>
                    <a href="#connect">Connect</a> to add a guest book entry.
                </p>
                <?php }?>
            </div>
        </div>

        <?php
        if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {
        ?>
        <div class="block_footer">
            <a href="index.php?p=barnraiser_guestbook&amp;t=maintain&amp;wp=<?php echo AM_WEBPAGE_NAME;?>"><?php echo $lang['href_maintain'];?></a>
        </div>
        <?php }?>
    </div>
</div>