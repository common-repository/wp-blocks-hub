<?php
	/**
	 * @accept $data
	 */
	use WPBlocksHub\Helper\Forms;
	use WPBlocksHub\Helper\Settings;

	$prefix = WPBH()->Config['prefix'];
?>

<?php wp_nonce_field( $prefix . '_metabox_nonce', $prefix . '_metabox_nonce' ); ?>
<table class="form-table">
	<tbody>
		<?php

			Forms::admin_input_images_picker( [
				'name' => '_' . $prefix . '_gallery',
				'title' => __( 'Project Gallery', 'wp-blocks-hub'),
				'value' => Settings::get_post_option( $data['post']->ID, 'gallery' )
			]);

			Forms::admin_input_text( [
				'name' => '_' . $prefix . '_client_name',
				'title' => __( 'Client name', 'wp-blocks-hub'),
				'value' => Settings::get_post_option( $data['post']->ID, 'client_name' )
			]);

			Forms::admin_input_text( [
				'name' => '_' . $prefix . '_project_url',
				'title' => __( 'Project URL', 'wp-blocks-hub'),
				'value' => Settings::get_post_option( $data['post']->ID, 'project_url' )
			]);

		?>
	</tbody>
</table>