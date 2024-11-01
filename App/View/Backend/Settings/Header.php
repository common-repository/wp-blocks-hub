<div class="wrap wpbh-wrap">

	<h1 class="wp-heading-inline"><?php esc_html_e( $data['current_page_title'] ); ?></h1>

	<?php if( ! \WPBlocksHub\Helper\Utils::is_premium() ): ?>
	<a href="<?php echo WPBH()->Config['pro_plugin_url']; ?>" target="_blank" id="gopremium-btn" class="page-title-action button-primary"><?php _e( 'Go Premium', 'wp-blocks-hub'); ?><i class="dashicons dashicons-star-filled"></i></a>
	<?php endif; ?>

	<div id="wpbh-go-premium-tooltip">
		<h3><?php _e( 'You are Awesome! (: Hugs :)', 'wp-blocks-hub'); ?></h3>
		<p><?php _e( 'Please, support development and maintaning of this project and get more premium blocks and priority support in return!', 'wp-blocks-hub'); ?></p>
		<p><?php _e( 'Unlocking premium feature gets you an access for all unlimited blocks from the cloud.', 'wp-blocks-hub'); ?></p>
		<p><strong><?php _e( 'Go premium just right now!', 'wp-blocks-hub'); ?></strong></p>
	</div>

	<hr class="wp-header-end">

