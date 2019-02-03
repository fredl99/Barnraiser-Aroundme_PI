<div class="barnraiser_blog_blogroll">
    <div class="block">
	    <div class="block_body">
            <ul>
                <li><a href="http://www.fsf.org/">Free software foundation</a></li>
                <li><a href="http://www.gnu.org/">The GNU Project</a></li>
                <li><a href="http://www.rsf.org/">Reporters without borders</a></li>
            </ul>
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