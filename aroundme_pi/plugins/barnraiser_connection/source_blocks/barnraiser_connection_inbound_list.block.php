<div class="barnraiser_network_list">
    <div class="block">	
        <div class="block_body">
            <table border="0" cellpadding="2" cellspacing="0">
                <?php
                foreach ($network as $key => $i):
                ?>
                <tr>
                    <td valign="top" width="40">
                    <?php
                    if (!empty($i['avatar'])) {
                    ?>
                        <a href="<?php echo $i['openid'];?>"><img src="<?php echo $i['avatar'];?>" style="border: solid 1px #000;margin-bottom:3px;" width="40" height="40" alt="" border="" /></a><br />
                    <?php
                    }
                    else {
                    ?>
                        <a href="<?php echo $i['openid'];?>"><img src="<?php echo AM_TEMPLATE_PATH;?>img/no_avatar.png" width="40" height="40" style="border: solid 1px #000;margin-bottom:3px;" alt="" border="" /></a><br />
                    <?php }?>
                    </td>
                    <td valign="top">
                       <?php echo $i['name'];?>
                    </td>
                </tr>
                <?php
                endforeach;
                ?>
            </table>
        </div>
    </div>
</div>