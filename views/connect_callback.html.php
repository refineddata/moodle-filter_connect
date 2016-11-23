<div style="text-align: <?php echo $iconalign ?>; width: 100%; margin-top:30px;">
	<div id="<?php echo $icondiv ?>" class="connect-course-icon-left">
		<a href="<?php echo $link ?>"
	    <?php if ( $mouseovers || is_siteadmin( $USER ) ) { ?>class="connect_tooltip"<?php } ?>
	        style="display: inline-block;" target="<?php echo $linktarget; ?>">
		<img src="<?php echo $iconurl ?>" border="0"/>
		<?php echo $clock ?>
		</a>
	</div>
	<div class="connect-course-aftertext-<?php echo $iconalign; ?>">
		<?php echo $aftertext; ?>
	</div>
	<div class="connect_popup" style="display: block;">
		<?php echo $overtext; ?>
	</div>
</div>
<div style="clear:both; margin-bottom: 20px;"><div>
