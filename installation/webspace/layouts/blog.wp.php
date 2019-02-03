<style type="text/css">

#left { 
    position: absolute; 
    left: 2%; 
    width: 28%;
}

#right { 
    position: absolute; 
    left: 32%; 
    width: 66%;
}

</style>


<div id="left">
    <!-- START OF LEFT COLUMN -->
    <h1 class="header">My card</h1>
    <AM_BLOCK plugin="barnraiser_openid" name="card"  />
    <h1 class="header">Connect to me</h1>
    <AM_BLOCK plugin="barnraiser_openid" name="connect"  />
    <h1 class="header">My connections</h1>
    <AM_BLOCK plugin="barnraiser_connection" name="gallery" limit="16"  />
    <h1 class="header">Tags</h1>
    <AM_BLOCK plugin="barnraiser_blog" name="tagcloud"  />
    <!-- END OF LEFT COLUMN -->
</div>


<div id="right">
<!-- START OF RIGHT COLUMN -->
    <h1 class="header">My blog</h1>
    <AM_BLOCK plugin="barnraiser_blog" name="entry" limit="5"  />
<!-- END OF RIGHT COLUMN -->
</div>
