<div class="updated error">
	<h4><?php _e( 'Important Warning!', 'wp-blocks-hub'); ?></h4>
	<p><?php _e( 'WP Blocks Hub plugin does not have an access to write in it\'s temporary directory and this will case problems of using the plugin (you can not download blocks from the central hub and style settings will not work!). Please, check server write permissions for following directories:', 'wp-blocks-hub'); ?></p>
	<?php foreach( $data as $dir ): ?>
		<p><?php echo $dir; ?></p>
	<?php endforeach; ?>
	<p><a href="#" class="wpbh-dismiss" data-dismiss="not_writable_notice"><?php _e( 'Dismiss', 'wp-blocks-hub'); ?></a></p>
</div>