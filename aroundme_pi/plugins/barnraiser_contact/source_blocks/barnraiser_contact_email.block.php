<div class="barnraiser_contact_email">
    <div class="block">	
        <div class="block_body">
  
            <?php
            if (isset($_SESSION['permission']) && $_SESSION['permission'] >= 16) {
            if (isset($_REQUEST['contact_msg']) && $_REQUEST['contact_msg'] == 1) {
            ?>
            <p>
                <span class="interface_message">Thank you. Your email was sent.</span>
            </p>
  
            <ul>
                <li><a href="index.php">Return to the start page</a></li>
            </ul>
            <?php
            }
            else {
            ?>
            <form action="plugins/barnraiser_contact/send_email.php" method="post">
  
            <p>
                Please fill in the email address you would like me to reply to.<br />
            </p>
  
            <p>
                <label for="id_email">Reply email</label>
                <input type="text" id="id_email" name="email" value="<?php if (isset($_SESSION['openid_email'])) { echo $_SESSION['openid_email'];}?>" /><br />
            </p>
  
            <p>
                <label for="id_subject">Subject</label>
                <input type="text" id="id_subject" name="subject" value="" /><br />
            </p>
  
            <p>
                <label for="id_message">Message</label>
                <textarea name="message" id="id_message"></textarea>
            </p>
  
            <p>
                <input type="submit" name="send_email" value="send" />
            </p>
  
            </form>
            <?php
            }
            }
            else {
            ?>
            <p>
                <a href="index.php#connect">Connect</a> to email me.
            </p>
            <?php }?>
        </div>
    </div>
</div>