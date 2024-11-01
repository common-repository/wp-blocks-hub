<?php
	/**
	 * @accept $data
	 */
	use WPBlocksHub\Helper\Forms;
	use WPBlocksHub\Helper\Settings;
?>
<h3 id="settings-responsive" class="title"><?php _e( 'Responsive Settings', 'wp-blocks-hub'); ?></h3>

<table class="form-table">
	<tbody>
		<?php

			Forms::admin_input_text( [
				'name' => 'mobile_breakpoint',
				'title' => __( 'Mobile breakpoint', 'wp-blocks-hub'),
				'desc' => __( 'Mobile styles will be applied when a screen size becomes less than this value', 'wp-blocks-hub'),
				'value' => Settings::get_option( 'mobile_breakpoint' )
			]);

		?>
	</tbody>
</table>