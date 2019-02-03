<div class="barnraiser_connection_gallery">
    <div class="block">
        <div class="block_body">
            <?php
            if(isset($barnraiser_connection_inbound_connections)) {
            foreach ($barnraiser_connection_inbound_connections as $key => $i):
            ?>
            <div class="gallery_item">
                 <?php
                 if (!empty($i['avatar'])) {
                 ?>
                     <a href="<?php echo $i['openid'];?>"><img src="<?php echo $i['avatar'];?>" class="avatar" style="width:40px; height:40px;" width="40" height="40" alt="" border="" /></a><br />
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
                 <?php }?>
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
        </div>
    </div>
</div>