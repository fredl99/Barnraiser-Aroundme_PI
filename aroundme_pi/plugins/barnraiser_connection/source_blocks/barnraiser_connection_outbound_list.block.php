<div class="barnraiser_connection_sites">
    <div class="block">
        <div class="block_body">
            <?php
			if (isset($barnraiser_connection_outbound_connections)) {
			?>
			<ul>
                <?php
                foreach ($barnraiser_connection_outbound_connections as $key => $i):
                ?>
                <li><a href="<?php echo $i['realm'];?>"><?php echo $i['title'];?></a></li>
                <?php
                endforeach;
                ?>
            </ul>
            <?php
            }
            else {
	        ?>
	        <p>
	            No connections to display.
	        </p>
		    <?php }?>
        </div>
    
        <?php
        if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {
        ?>
        <div class="block_footer">
            <a href="index.php?t=network">manage sites</a>
        </div>
        <?php }?>
    </div>
</div>