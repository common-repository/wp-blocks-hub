<?php
	/**
	 * @accept $data
	 */
	use WPBlocksHub\Helper\Forms;
	use WPBlocksHub\Helper\Settings;
?>
<h3 id="settings-custom-code" class="title"><?php _e( 'Custom CSS & JavaScript', 'wp-blocks-hub'); ?></h3>

<table class="form-table">
	<tbody>
		<?php

			Forms::admin_input_textarea( [
				'name' => 'custom_css',
				'title' => __( 'Custom CSS', 'wp-blocks-hub'),
				'value' => Settings::get_single_option( 'custom_css' ),
				'desc' => __( 'Paste here any custom CSS code', 'wp-blocks-hub'),
				'ace' => true
			]);

			Forms::admin_input_textarea( [
				'name' => 'custom_js',
				'title' => __( 'Custom JavaScript', 'wp-blocks-hub'),
				'value' => Settings::get_single_option( 'custom_js' ),
				'desc' => __( 'Paste here any custom JS code. It will be added as inline JS on page footer between &lt;script type="javascript">//Your code&lt;/script> tag.', 'wp-blocks-hub'),
				'ace' => true
			]);

		?>
	</tbody>
</table>