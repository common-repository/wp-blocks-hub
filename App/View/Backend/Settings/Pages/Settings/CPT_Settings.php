<?php
	/**
	 * @accept $data
	 */
	use WPBlocksHub\Helper\Forms;
	use WPBlocksHub\Helper\Settings;
	use WPBlocksHub\Helper\Utils;
?>
<h3 id="settings-cpt" class="title"><?php _e( 'Custom Post Types', 'wp-blocks-hub'); ?></h3>

<p><?php _e( 'Here you can disable unused custom post types.', 'wp-blocks-hub'); ?></p>

<table class="form-table">
	<tbody>
		<?php

			Forms::admin_input_checkbox( [
				'name' => 'disable_testimonials',
				'title' => __( 'Disable Testimonials', 'wp-blocks-hub'),
				'subtitle' => __( 'Testimonials CPT', 'wp-blocks-hub'),
				'desc' => __( 'Check this option if you wish to disable Testimonials Custom Post Type', 'wp-blocks-hub'),
				'value' => 'yes',
				'checked' => Utils::bool( Settings::get_option( 'disable_testimonials' ) ),
				'value' => 'yes',
			]);

			Forms::admin_input_checkbox( [
				'name' => 'disable_people',
				'title' => __( 'Disable People Posts', 'wp-blocks-hub'),
				'subtitle' => __( 'People CPT', 'wp-blocks-hub'),
				'desc' => __( 'Check this option if you wish to disable People Custom Post Type', 'wp-blocks-hub'),
				'value' => 'yes',
				'checked' => Utils::bool( Settings::get_option( 'disable_people' ) ),
				'value' => 'yes',
			]);

			Forms::admin_input_checkbox( [
				'name' => 'disable_benefits',
				'title' => __( 'Disable Benefits Posts', 'wp-blocks-hub'),
				'subtitle' => __( 'Benefits CPT', 'wp-blocks-hub'),
				'desc' => __( 'Check this option if you wish to disable Benefits Custom Post Type', 'wp-blocks-hub'),
				'value' => 'yes',
				'checked' => Utils::bool( Settings::get_option( 'disable_benefits' ) ),
				'value' => 'yes',
			]);

		?>
	</tbody>
</table>