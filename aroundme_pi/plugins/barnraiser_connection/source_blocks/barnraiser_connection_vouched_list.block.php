<div class="barnraiser_connection_vouched_list">
    <div class="block">	
        <div class="block_body">
            <?php
            if (isset($barnraiser_connection_vouched_connections)) {
	        ?>
            <table border="0" cellpadding="2" cellspacing="0">
                <?php
                foreach ($barnraiser_connection_vouched_connections as $key => $i):
                ?>
                <tr>
                    <td valign="top" width="40">
                    <?php
                    if (!empty($i['avatar'])) {
                    ?>
                        <a href="<?php echo $i['openid'];?>"><img src="<?php echo $i['openid'] . "/" . $i['avatar'];?>" style="border: solid 1px #000;margin-bottom:3px;" width="40" height="40" alt="" border="" /></a><br />
                    <?php
                    }
                    else {
                    ?>
                        <a href="<?php echo $i['openid'];?>"><img src="<?php echo AM_TEMPLATE_PATH;?>img/no_avatar.png" width="40" height="40" style="border: solid 1px #000;margin-bottom:3px;" alt="" border="" /></a><br />
                    <?php }?>
                    </td>
                    <td valign="top">
                       <a href="<?php echo $i['openid'];?>"><?php echo $i['nickname'];?></a><br />
                       
                       <?php
                       if (isset($i['reference'])) {
	                   ?>
	                   <?php echo strftime("%d %b %G", $i['reference_datetime']);?>: <?php echo $i['reference'];?><br />
                       <?php }?>
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
                No one vouched for.
            </p>
            <?php }?>
        </div>
    </div>
</div>