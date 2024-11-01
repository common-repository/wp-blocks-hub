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
				'name' => '_' . $prefix . '_link',
				'title' => __( 'Benefit link', 'wp-blocks-hub'),
				'desc' => __( 'type here any link where visitor will be redirected by clicking on this post or leave it blank to disable this feature', 'wp-blocks-hub'),
				'value' => Settings::get_post_option( $data['post']->ID, 'link' )
			]);

			Forms::admin_input_color_picker( [
				'name' => '_' . $prefix . '_icon_color',
				'title' => __( 'SVG Icon Color', 'wp-blocks-hub'),
				'desc' => __( 'you can change icon color per post if you use SVG icon insted of photo as a featured image', 'wp-blocks-hub'),
				'value' => Settings::get_post_option( $data['post']->ID, 'icon_color' )
			]);

		?>
	</tbody>
</table>