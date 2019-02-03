<div class="barnraiser_connection_summary">
    <div class="block">	
        <div class="block_body">
			<?php
			if (isset($barnraiser_connection_statistics)) {
			?>
			<table cellspacing="0" cellpading="2" border="0" width="100%"
			    <?php
			    foreach ($barnraiser_connection_statistics as $key => $i):
			    ?>
			    <tr>
			        <td valign="top">
			           <?php echo $lang['plugin_barnraiser_connection']['statistic'][$key];?>
		            </td>
		            <td valign="top" align="right">
		               <?php echo $i;?>
		            </td>
		        </tr>
		        <?php
		        endforeach;
		        ?>
		    </table>
		    <?php
	        }
	        else {
		    ?>
		    <p>
		        No statistics to display
		    </p>
			<?php }?>
		</div>
	</div>
</div>