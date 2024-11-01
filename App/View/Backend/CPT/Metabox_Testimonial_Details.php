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

			Forms::admin_input_text( [
				'name' => '_' . $prefix . '_author_name',
				'title' => __( 'Author name', 'wp-blocks-hub'),
				'desc' => __( 'e.g.: John Doe', 'wp-blocks-hub'),
				'value' => Settings::get_post_option( $data['post']->ID, 'author_name' )
			]);

			Forms::admin_input_text( [
				'name' => '_' . $prefix . '_author_position',
				'title' => __( 'Position', 'wp-blocks-hub'),
				'desc' => __( 'e.g.: CEO of SomeCompany Inc.', 'wp-blocks-hub'),
				'value' => Settings::get_post_option( $data['post']->ID, 'author_position' )
			]);

		?>
	</tbody>
</table>