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

			foreach( WPBH()->Config['social_profiles'] as $name => $title ) {

				Forms::admin_input_text( [
					'name' => '_' . $prefix . '_' . $name,
					'title' => $title,
					'value' => Settings::get_post_option( $data['post']->ID, $name )
				]);

			}

		?>
	</tbody>
</table>