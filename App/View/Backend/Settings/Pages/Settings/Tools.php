<?php
	/**
	 * @accept $data
	 */
	use WPBlocksHub\Helper\Forms;
?>
<h3 id="settings-tools" class="title"><?php _e( 'Tools', 'wp-blocks-hub'); ?></h3>

<table class="form-table">
	<tbody>
		<?php

			Forms::admin_input_button( [
				'name' => 'clear_error_log',
				'title' => __( 'Clear error log', 'wp-blocks-hub'),
				'desc' => __( 'Click on the button to clear error log file', 'wp-blocks-hub'),
				'text' => __( 'Clear', 'wp-blocks-hub'),
			]);

			Forms::admin_input_button( [
				'name' => 'flush_hub_cache',
				'title' => __( 'Flush hub cache', 'wp-blocks-hub'),
				'text' => __( 'Flush', 'wp-blocks-hub'),
				'desc' => __( 'Reset cached data if something goes wrong with accepting data from a central hub', 'wp-blocks-hub')
			]);

			Forms::admin_input_button( [
				'name' => 'reset_settings',
				'title' => __( 'Reset settings', 'wp-blocks-hub'),
				'desc' => __( 'All saved plugin settings will be switched to defaults', 'wp-blocks-hub'),
				'text' => __( 'Reset', 'wp-blocks-hub'),
			]);

		?>
	</tbody>
</table>