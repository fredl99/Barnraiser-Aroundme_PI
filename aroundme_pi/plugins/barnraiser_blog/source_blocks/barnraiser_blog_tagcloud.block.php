<?php
if (isset($tags)) {
?>
<div class="barnraiser_blog_tagcloud">
    <div class="block">
	    <div class="block_body">
            <p>
                <?php
                foreach($tags as $key => $i):
                ?>
                <a href="index.php?wp=<?php echo $barnraiser_blog_list_wp;?>&amp;blog_tag=<?php echo urlencode($i['name']);?>">
                <?php echo $i['name'];?></a>
                <?php
                if (count($i['tagged']) > 0) {
                ?>
                <sup><?php echo count($i['tagged']);?></sup>
                <?php }?>
                <?php
                if (count($i['tagged']) > $key) {
                    echo ", ";
                }
                endforeach;
                ?>
                <br />
            </p>
        </div>
        
		<?php
        if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {
        ?>
		<div class="block_footer">
		    <a href="index.php?p=barnraiser_blog&amp;t=maintain&amp;wp=<?php echo AM_WEBPAGE_NAME;?>"><?php echo $lang['href_maintain'];?></a>
	    </div>	
	    <?php }?>
    </div>
</div>
<?php }?>