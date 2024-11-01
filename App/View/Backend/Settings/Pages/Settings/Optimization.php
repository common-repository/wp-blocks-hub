<?php
	/**
	 * @accept $data
	 */
	use WPBlocksHub\Helper\Forms;
	use WPBlocksHub\Helper\Settings;
	use WPBlocksHub\Helper\Utils;
?>
<h3 id="settings-optimization" class="title"><?php _e( 'Optimization', 'wp-blocks-hub'); ?></h3>

<p><?php _e( 'Page speed optimization tools.', 'wp-blocks-hub'); ?></p>

<table class="form-table">
	<tbody>
		<?php

			Forms::admin_input_checkbox( [
				'name' => 'disable_shortcodes',
				'title' => __( 'Disable shortcodes', 'wp-blocks-hub'),
				'subtitle' => __( 'Standard shortcodes', 'wp-blocks-hub'),
				'desc' => __( 'By defaults, plugin already loads active blocks assets, since WordPress does not allow to detect us, is shortcode on page or not (if shortcode outside post content). If you use professional page builders like WPBakery, disable standard shortcodes to increase page loading speed.', 'wp-blocks-hub'),
				'value' => 'yes',
				'checked' => Utils::bool( Settings::get_option( 'disable_shortcodes' ) ),
			]);

			Forms::admin_input_checkbox( [
				'name' => 'disable_gutenberg_blocks',
				'title' => __( 'Disable Gutenberg blocks', 'wp-blocks-hub'),
				'subtitle' => __( 'Gutenberg blocks', 'wp-blocks-hub'),
				'desc' => __( 'It is sad, but Gutenberg loads all assets for all active blocks. If you use any Pro Page Builder, like Elementor, disable Gutenberg Blocks for optimization reasons.', 'wp-blocks-hub'),
				'value' => 'yes',
				'checked' => Utils::bool( Settings::get_option( 'disable_gutenberg_blocks' ) ),
			]);

		?>
	</tbody>
</table>