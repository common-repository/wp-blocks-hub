<?php
	/**
	 * @accept $data
	 */
	use WPBlocksHub\Helper\Forms;
?>
<h3 id="settings-demo-data" class="title"><?php _e( 'Demo data', 'wp-blocks-hub'); ?></h3>

<p><?php _e( 'Demo posts for Custom Post Types.', 'wp-blocks-hub'); ?></p>

<table class="form-table">
	<tbody>
		<?php

			Forms::admin_input_button( [
				'name' => 'install_portfolio_posts',
				'title' => __( 'Portfolio Posts', 'wp-blocks-hub'),
				'text' => __( 'Install posts', 'wp-blocks-hub'),
				'data-attr' => 'portfolio',
				'desc' => __( 'This button adds some demo posts for "Portfolio" Custom Post Type.', 'wp-blocks-hub')
			]);

			Forms::admin_input_button( [
				'name' => 'install_testimonial_posts',
				'title' => __( 'Testimonial Posts', 'wp-blocks-hub'),
				'text' => __( 'Install posts', 'wp-blocks-hub'),
				'data-attr' => 'testimonial',
				'desc' => __( 'This button adds some demo posts for "Testimonials" Custom Post Type.', 'wp-blocks-hub')
			]);

			Forms::admin_input_button( [
				'name' => 'install_people_posts',
				'title' => __( 'People Posts', 'wp-blocks-hub'),
				'text' => __( 'Install posts', 'wp-blocks-hub'),
				'data-attr' => 'person',
				'desc' => __( 'This button adds some demo posts for "People" Custom Post Type.', 'wp-blocks-hub')
			]);

			Forms::admin_input_button( [
				'name' => 'install_benefits_posts',
				'title' => __( 'Benefits Posts', 'wp-blocks-hub'),
				'text' => __( 'Install posts', 'wp-blocks-hub'),
				'data-attr' => 'benefit',
				'desc' => __( 'This button adds some demo posts for "Benefits" Custom Post Type.', 'wp-blocks-hub')
			]);

		?>
	</tbody>
</table>