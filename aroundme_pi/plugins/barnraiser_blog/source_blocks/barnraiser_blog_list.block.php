<div class="barnraiser_blog_list">
    <div class="block">
        <div class="block_body">
            <?php
            if (isset($blog_entries)) {
            ?>
  	        <table cellspacing="0" cellpadding="2" border="0" width="100%">
            <?php
            foreach ($blog_entries as $key => $i):
            ?>
                <tr>
                    <td valign="top">
                        <b><a href="index.php?wp=<?php echo $barnraiser_blog_list_wp;?>&amp;blog_entry_id=<?php echo $i['blog_entry_id'];?>"><?php echo $i['title'];?></a></b>
                    </td>
                    <td valign="top" align="right">
      	                <a href="index.php?wp=<?php echo $barnraiser_blog_list_wp;?>&amp;blog_entry_id=<?php echo $i['blog_entry_id'];?>#comments">comments</a> (<?php echo $i['comment_total'];?>)
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span class="blog_date"><?php echo strftime("%d %b %G %H:%M", $i['datetime']);?></span>: 
                        <span class="blog_entry"><?php echo $i['body'];?></span>
                    </td>
                </tr>
  
                <?php
                endforeach;
                ?>
            </table>
            <?php
            }
            else {?>
            <p>
                Sorry, no blog entries yet:(
            </p>
            <?php }?>
        </div>

	    <div class="block_footer">
	    	<?php
            if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {
            ?>
				<a href="index.php?p=barnraiser_blog&amp;t=maintain&amp;wp=<?php echo AM_WEBPAGE_NAME;?>&amp;add_blog_entry=1"><?php echo $lang['href_add'];?></a>
				<a href="index.php?p=barnraiser_blog&amp;t=maintain&amp;wp=<?php echo AM_WEBPAGE_NAME;?>"><?php echo $lang['href_maintain'];?></a>
            <?php }?>

			<a href="plugins/barnraiser_blog/feed/rss.php?wp=<?php echo $barnraiser_blog_list_wp;?>">RSS</a>
        </div>
    </div>
</div>