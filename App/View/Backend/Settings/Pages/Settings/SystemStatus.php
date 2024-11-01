<?php
	/**
	 * @accept $data
	 */
	use WPBlocksHub\Helper\Forms;
	use WPBlocksHub\Helper\Utils;
?>
<h3 id="settings-system-status" class="title"><?php _e( 'System status', 'wp-blocks-hub'); ?></h3>

<table class="form-table">
	<tbody>
		<?php

			Forms::admin_input_textarea( [
				'name' => 'system_status',
				'title' => __( 'Report', 'wp-blocks-hub'),
				'value' => Utils::get_system_status(),
				'desc' => __( 'Please copy and provide following information within your support tickets. All information will be automatically copied into your clipboard once you click on textarea above.', 'wp-blocks-hub'),
				'readonly' => true
			]);

			Forms::admin_input_textarea( [
				'name' => 'error_log',
				'title' => __( 'Errors log', 'wp-blocks-hub'),
				'value' => Utils::get_error_log(),
				'desc' => __( 'Please copy and provide following information within your support tickets. All information will be automatically copied into your clipboard once you click on textarea above.', 'wp-blocks-hub'),
				'readonly' => true
			]);

		?>
	</tbody>
</table>