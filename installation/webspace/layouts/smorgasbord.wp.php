<style type="text/css">

#left { 
    position: absolute; 
    left: 2%; 
    width: 28%;
}

#middle { 
    position: absolute; 
    left: 32%; 
    width: 36%;
}

#right { 
    position: absolute; 
    left: 70%; 
    width: 28%;
}

</style>


<div id="left">
    <!-- START OF LEFT COLUMN -->
    <h1 class="header">My card</h1>
    <AM_BLOCK plugin="barnraiser_openid" name="card"  />
    <h1 class="header">My activity log</h1>
    <AM_BLOCK plugin="barnraiser_connection" name="log" limit="6"  />
    <!-- END OF LEFT COLUMN -->
</div>


<div id="middle">
    <!-- START OF MIDDLE COLUMN -->
    <h1 class="header">My wall</h1>
    <AM_BLOCK plugin="barnraiser_guestbook" name="list" limit="8"  />
    <!-- END OF MIDDLE COLUMN -->
</div>


<div id="right">
<!-- START OF RIGHT COLUMN -->
     <h1 class="header">My connections</h1>
     <AM_BLOCK plugin="barnraiser_connection" name="gallery_extended" limit="16"  />
     <h1 class="header">connect to me</h1>
     <AM_BLOCK plugin="barnraiser_openid" name="connect"  />
     <h1 class="header">People I vouch for</h1>
     <AM_BLOCK plugin="barnraiser_connection" name="vouched_list"  />
     <h1 class="header">People i've visited</h1>
     <AM_BLOCK plugin="barnraiser_connection" name="outbound_list" limit="8" ofilter="humans"  />
<!-- END OF RIGHT COLUMN -->
</div>
