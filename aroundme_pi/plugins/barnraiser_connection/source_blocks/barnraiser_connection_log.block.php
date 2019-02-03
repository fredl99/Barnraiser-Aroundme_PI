<div class="barnraiser_connection_log">
    <div class="block">
        <div class="block_body">
            <?php
			if (isset($connection_log)) {
			?>
			<ul>
                <?php
                foreach($connection_log as $key => $i):
                ?>
                    <li><?php echo strftime("%d %b %H:%M", $i['datetime']);?>: <?php echo $i['entry'];?></li>
                <?php
                endforeach;
                ?>
            </ul>
            <?php }?>
			
			<?php
            if (isset($_SESSION['permission']) && $_SESSION['permission'] >= 64) {
            ?>
			<div class="add">
	            <form action="plugins/barnraiser_connection/add_log.php" method="post">
	            <textarea name="log_entry" onFocus="this.value=''; return false;">Type an announcement here:)</textarea><br />
	            <input type="submit" name="insert_log_entry" value="<?php echo $lang['href_add'];?>" />
               </form>
	        </div>
		    <?php }?>
        </div>
	
		<div class="block_footer">
	    	<?php
            if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {
            ?>
            	<a href="plugins/barnraiser_connection/feed/rss.php"><?php echo $lang['href_rss'];?></a>
			<?php }?>
        </div>
    </div>
</div>