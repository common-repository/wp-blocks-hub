<?php
	$has_video_preview = $block->video_url <> '';
	$locale = get_locale();
	$title = isset( $locale, $block->translations->$locale->block_title ) ? $block->translations->$locale->block_title : $block->block_title;
	$desc = isset( $locale, $block->translations->$locale->description ) ? $block->translations->$locale->description : $block->description;
?>
<div class="wpbh-block-item <?php if( $block->is_premium ): ?>premium<?php endif; ?>">
	<div class="inside">

		<?php if( $block->is_premium ): ?>
		<div class="badge">
			<span><?php _e( 'Premium', 'wp-blocks-hub'); ?></span>
		</div>
		<?php endif; ?>
	
		<div class="wpbh-preview <?php if( $has_video_preview ): ?> video-preview<?php endif; ?>">
			<?php if( $has_video_preview ): ?>
				<video width="100%" muted loop>
					<source src="<?php esc_attr_e( $block->video_url ); ?>" type="video/mp4">
				</video>
			<?php else: ?>
				<img src="<?php esc_attr_e( $block->thumbnail_url ); ?>" alt="">
			<?php endif; ?>
		</div>

		<div class="wpbh-text">

			<header>
				<h3><?php esc_html_e( $title ); ?></h3>
			</header>

			<div class="desc">
				<p><?php esc_html_e( $desc ); ?></p>
			</div>

			<footer>
				<p><em><strong><?php _e( 'Compatibility', 'wp-blocks-hub'); ?>:</strong> <?php esc_html_e( $block->compatibility_text ); ?></em></p>

				<?php if( $data->screen == 'my-blocks' ): ?>
				<p><em><strong><?php _e( 'Installed version', 'wp-blocks-hub'); ?>:</strong> <?php esc_html_e( $block->version ); ?></em></p>				
				<?php endif; ?>

			</footer>

			<hr/>

			<div class="wpbh-actions">
				<img class="wpbh-loader hidden" src="<?php echo WPBH()->Config['plugin_url']; ?>/assets/images/admin/loader.svg" alt=""> 

				<?php
					$base_url = admin_url( 'admin-ajax.php?&nonce=' . wp_create_nonce( WPBH()->Config['prefix'] . '_ajax_nonce') );

					$btn_title = __( 'Download', 'wp-blocks-hub');
					$action = 'download';

					// is it active block?
					if( in_array( $block->slug, $data->active_blocks ) ) {
						$btn_title = __( 'Deactivate', 'wp-blocks-hub');
						$action = 'deactivate';
						// is it inactive block?
					} else if( in_array( $block->slug, $data->inactive_blocks ) ) {
						$btn_title = __( 'Activate', 'wp-blocks-hub');
						$action = 'activate';
						// else, this is a new block, can be downloaded
					} 

					$block_download_url = add_query_arg( ['action' => WPBH()->Config['prefix'] . '_block_download'], $base_url );
					$block_remove_url = add_query_arg( ['action' => WPBH()->Config['prefix'] . '_block_remove'], $base_url );
					$block_update_url = add_query_arg( ['action' => WPBH()->Config['prefix'] . '_block_update'], $base_url );
				?>
				<a
					href="#"
					data-download-url="<?php echo esc_attr( $block_download_url ); ?>"
					data-block-id="<?php echo esc_attr( $block->block_id ); ?>"
					data-block-slug="<?php echo esc_attr( $block->slug ); ?>"
					data-block-action="<?php echo esc_attr( $action ); ?>"
					class="button wpbh-block-do-action">
					<?php echo wp_kses_post( $btn_title ); ?>
				</a>
				
				<?php if( $data->screen == 'my-blocks' ): ?>
				
					<?php if( in_array( $block->block_id, array_keys( $data->updates_data ) ) ): ?>
					<a href="#" data-block-id="<?php echo esc_attr( $block->block_id ); ?>" data-block-slug="<?php echo esc_attr( $block->slug ); ?>" data-remove-url="<?php echo esc_attr( $block_remove_url ); ?>" class="button wpbh-block-update simptip-position-bottom simptip-movable" data-tooltip="<?php esc_attr_e( 'Update available', 'wp-blocks-hub'); ?>"><i class="dashicons dashicons-update-alt"></i></a>
					<?php endif; ?>

					<?php if( $block->shortcode_example <> '' ): ?>
					<a href="#" class="button wpbh-block-copy-shortcode simptip-position-bottom simptip-movable <?php if( ! in_array( $block->slug, $data->active_blocks ) ): ?>hidden<?php endif; ?>" data-shortcode="<?php echo esc_attr( $block->shortcode_example ); ?>" data-tooltip="<?php esc_attr_e( 'Copy shortcode', 'wp-blocks-hub'); ?>"><i class="dashicons dashicons-clipboard"></i></a>
					<?php endif; ?>

					<a href="#" data-block-id="<?php echo esc_attr( $block->block_id ); ?>" data-block-slug="<?php echo esc_attr( $block->slug ); ?>" data-update-url="<?php echo esc_attr( $block_update_url ); ?>" class="button wpbh-block-remove simptip-position-bottom simptip-movable" data-tooltip="<?php esc_attr_e( 'Remove block', 'wp-blocks-hub'); ?>"><i class="dashicons dashicons-trash"></i></a>

				<?php endif; ?>
				
			</div>

		</div>

	</div>
</div>