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
				'name' => '_' . $prefix . '_position',
				'title' => __( 'Position', 'wp-blocks-hub'),
				'desc' => __( 'postiion text displays right after person name', 'wp-blocks-hub'),
				'value' => Settings::get_post_option( $data['post']->ID, 'position' )
			]);

		?>
	</tbody>
</table>