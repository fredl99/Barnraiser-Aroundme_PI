<div class="barnraiser_blog_entry">
    <div class="block">
        <?php
        if (isset($blog_entry)) {
        ?>

	    <div class="block_body">
	        <h2><?php echo $blog_entry['title'];?></h2>
			
			<p>
				<i><?php echo strftime("%d %b %G %H:%M", $blog_entry['datetime']);?></i>
			</p>
			
            <p>
                <?php echo $blog_entry['body'];?>
            </p>
  
            <div class="menu">
                <a href="index.php?wp=<?php echo AM_WEBPAGE_NAME;?>">list blog entries</a>
  
                <?php
                if (isset($blog_entry['previous_entry_id'])) {
                ?>
                &nbsp;&#124;&nbsp;
  
                <a href="index.php?wp=<?php echo AM_WEBPAGE_NAME;?>&amp;blog_entry_id=<?php echo $blog_entry['previous_entry_id'];?>">previous entry</a>
                <?php }?>
  
                <?php
                if (isset($blog_entry['next_entry_id'])) {
                ?>
                &nbsp;&#124;&nbsp;
  
                <a href="index.php?wp=<?php echo AM_WEBPAGE_NAME;?>&amp;blog_entry_id=<?php echo $blog_entry['next_entry_id'];?>">next entry</a>
                <?php }?>

                <?php
                if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {
                ?>
                	<a href="index.php?p=barnraiser_blog&amp;t=maintain&amp;wp=<?php echo AM_WEBPAGE_NAME;?>&amp;blog_entry_id=<?php echo $blog_entry['blog_entry_id'];?>"><?php echo $lang['href_edit'];?></a>
            	<?php }?>
            </div>
  
            <div class="share">
                <a name="share"></a>
                <h2>Share this</h2>
				<?php
					$url = phpself(1);
					$url .= "?wp=" . AM_WEBPAGE_NAME . "&blog_entry_id=" . $blog_entry['blog_entry_id'];
				?>
                <p>
                    <a href="http://del.icio.us/post?url=<?php echo $url;?>&amp;title=<?php echo urlencode($blog_entry['title']);?>"><img src="plugins/barnraiser_blog/template/img/delicious.png" alt="del.icio.us logo" border="0" /></a>
                    <a href="http://del.icio.us/post?url=<?php echo $url;?>&amp;title=<?php echo urlencode($blog_entry['title']);?>">del.icio.us</a>
  
                    <a href="http://digg.com/submit?phase=2&amp;url=<?php echo $url;?>&amp;title=<?php echo urlencode($blog_entry['title']);?>"><img src="plugins/barnraiser_blog/template/img/digg.png" alt="Digg logo" border="0" /></a>
                    <a href="http://digg.com/submit?phase=2&amp;url=<?php echo $url;?>&amp;title=<?php echo urlencode($blog_entry['title']);?>">Digg</a>
  
                    <a href="http://www.stumbleupon.com/submit?url=<?php echo $url;?>&amp;title=<?php echo urlencode($blog_entry['title']);?>"><img src="plugins/barnraiser_blog/template/img/stumbleupon.png" alt="StumbleUpon logo" border="0" /></a>
                    <a href="http://www.stumbleupon.com/submit?url=<?php echo $url;?>&amp;title=<?php echo urlencode($blog_entry['title']);?>">StumbleUpon</a>
  
                    <a href="http://www.technorati.com/faves?add=<?php echo $url;?>&amp;title=<?php echo urlencode($blog_entry['title']);?>"><img src="plugins/barnraiser_blog/template/img/technorati.png" alt="Technorati logo" border="0" /></a>
                    <a href="http://www.technorati.com/faves?add=<?php echo $url;?>&amp;title=<?php echo urlencode($blog_entry['title']);?>">Technorati</a>
                </p>
            </div>

            <?php
            if (isset($blog_entry['tags'])) {
            ?>
            <div class="tags">
                <h2>Tagged with</h2>

                <?php
                foreach($blog_entry['tags'] as $key => $i):
                ?>
                <a href="index.php?wp=<?php echo AM_WEBPAGE_NAME;?>&amp;blog_tag=<?php echo urlencode($i);?>">
                <?php echo $i;?></a>

                <?php
                if (count($blog_entry['tags']) > $key+1) {
                    echo ", ";
                }
                endforeach;
                ?>
               <br />
            </div>
            <?php }?>
  
  
            <div class="comments">
                <a name="comments"></a>
               <h2>Comments</h2>
  
               <?php
               if (isset($blog_entry['comments'])) {
               foreach($blog_entry['comments'] as $key => $i):
               ?>
               <p class="comment_header">
                   <a name="blog_comment<?php echo $i['datetime']?>"></a>
                   <b><a href="<?php echo $i['openid'];?>"><?php echo $i['connection']['nickname']?></a></b>

                   &nbsp;&#124;&nbsp;

                   <?php echo strftime("%d %b %G %H:%M", $i['datetime']);?>

                   <?php
                   if (isset($_SESSION['permission']) && $_SESSION['permission'] >= 64) {
                   ?>
                   &nbsp;&#124;&nbsp;

                   <a href="index.php?p=barnraiser_blog&amp;t=maintain&amp;wp=<?php echo AM_WEBPAGE_NAME;?>&amp;del_blog_entry_id=<?php echo $blog_entry['blog_entry_id'];?>&amp;del_comment_id=<?php echo $i['datetime'];?>" class="owner_link">delete this comment</a>
                   <?php }?>
                   <br />
               </p>

               <p>
                   <?php echo $i['comment']?><br />
               </p>
               <?php
               endforeach;
               }?>
  
               <?php
               if (isset($_SESSION['permission']) && $_SESSION['permission'] >= 16) {
               ?>
               <form action="plugins/barnraiser_blog/add_comment.php?wp=<?php echo AM_WEBPAGE_NAME;?>" method="post">
               
               <div class="plugin_blog_add_comment">
                   <input type="hidden" name="blog_entry_id" value="<?php echo $blog_entry['blog_entry_id'];?>" />
                   <textarea rows="4" cols="80" name="comment_body"></textarea><br />
                   <input type="submit" name="insert_blog_comment" value="<?php echo $lang['plugin_barnraiser_blog']['sub_add'];?>" />
               </div>
               </form>
               <?php
               }
               else {
               ?>
               <p>
                   <a href="#connect">Connect</a> to add a comment.
               </p>
               <?php }?>
           </div>
        </div>
        <?php
        }
        elseif (isset($blog_entries)) {
        ?>
  
        <div class="block_body">
            <?php
            foreach ($blog_entries as $key => $i):
            ?>
  
            <p>
                <h1><a href="index.php?wp=<?php echo AM_WEBPAGE_NAME;?>&amp;blog_entry_id=<?php echo $i['blog_entry_id'];?>"><?php echo $i['title'];?></a></h1>
                <span class="blog_date"><?php echo strftime("%d %b %G %H:%M", $i['datetime']);?></span><br />
                <span class="blog_entry"><?php echo $i['body'];?></span>
            </p>
  
            <p>
                <?php
                if (isset($i['tags'])) {
                ?>
                tags:
                <?php
                foreach($i['tags'] as $keyt => $t):
                ?>
                <a href="index.php?wp=<?php echo AM_WEBPAGE_NAME;?>&amp;blog_tag=<?php echo urlencode($t);?>">
                <?php echo $t;?></a>

                <?php
                if (count($i['tags'])-1 > $keyt) {
                  echo ", ";
                }
                endforeach;
                ?>
                &nbsp;&#124;&nbsp;
                <?php }?>
  
  

                <a href="index.php?wp=<?php echo AM_WEBPAGE_NAME;?>&amp;blog_entry_id=<?php echo $i['blog_entry_id'];?>#comments">comments</a> (<?php echo $i['comment_total'];?>)

                &nbsp;&#124;&nbsp;

                <a href="index.php?wp=<?php echo AM_WEBPAGE_NAME;?>&amp;blog_entry_id=<?php echo $i['blog_entry_id'];?>#share">share this</a>
  
                <?php
                if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {
                ?>
                &nbsp;&#124;&nbsp;
  
                <a href="index.php?p=barnraiser_blog&amp;t=maintain&amp;blog_entry_id=<?php echo $i['blog_entry_id'];?>" class="owner_link">edit this</a>
                <?php }?>
            </p>
  
            <?php
            endforeach;
            ?>
        </div>
  
        <div class="blog_footer">
            <a href="plugins/barnraiser_blog/feed/rss.php?wp=<?php echo AM_WEBPAGE_NAME;?>">RSS</a>

            <?php
            if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {
            ?>
            	<a href="index.php?p=barnraiser_blog&amp;t=maintain&amp;wp=<?php echo AM_WEBPAGE_NAME;?>&amp;add_blog_entry=1"><?php echo $lang['href_add'];?></a>
				<a href="index.php?p=barnraiser_blog&amp;t=maintain&amp;wp=<?php echo AM_WEBPAGE_NAME;?>"><?php echo $lang['href_maintain'];?></a>
            <?php }?>
        </div>
        <?php
        }
        else {
        ?>
        <div class="block_body">
		    <p>
			     No blog entries available.
		    </p>
        </div>

        <?php
        if (isset($_SESSION['permission']) && $_SESSION['permission'] == 64) {
        ?>
        <div class="blog_footer">
            <a href="index.php?p=barnraiser_blog&amp;t=maintain&amp;add_blog_entry=1" class="owner_link">add a blog entry</a>
        </div>
        <?php }?>
        <?php }?>
    </div>
</div>