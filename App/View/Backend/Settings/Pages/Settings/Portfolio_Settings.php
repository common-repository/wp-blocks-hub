<?php
	/**
	 * @accept $data
	 */
	use WPBlocksHub\Helper\Forms;
	use WPBlocksHub\Helper\Settings;
	use WPBlocksHub\Helper\Utils;
?>
<h3 id="settings-portfolio" class="title"><?php _e( 'Portfolio', 'wp-blocks-hub'); ?></h3>

<p><?php _e( 'Settings for Portfolio custom post type', 'wp-blocks-hub'); ?></p>

<table class="form-table">
	<tbody>
		<?php

			Forms::admin_input_checkbox( [
				'name' => 'disable_portfolio',
				'title' => __( 'Disable Portfolio', 'wp-blocks-hub'),
				'subtitle' => __( 'Portfolio CPT', 'wp-blocks-hub'),
				'desc' => __( 'Check this option if you wish to disable Portfolio Custom Post Type', 'wp-blocks-hub'),
				'value' => 'yes',
				'checked' => Utils::bool( Settings::get_option( 'disable_portfolio' ) ),
			]);

			Forms::admin_input_text( [
				'name' => 'portfolio_caption',
				'title' => __( 'Portfolio menu title', 'wp-blocks-hub'),
				'desc' => __( 'Here you can rename portfolio in your admin menu', 'wp-blocks-hub'),
				'value' => Settings::get_option( 'portfolio_caption' )
			]);

			Forms::admin_input_text( [
				'name' => 'portfolio_slug',
				'title' => __( 'Portfolio slug', 'wp-blocks-hub'),
				'desc' => __( 'Slug is used in URL for portfolio custom post type, e.g. <code>https://your-website.com/portfolio/single-post-name</code>', 'wp-blocks-hub'),
				'value' => Settings::get_option( 'portfolio_slug' )
			]);

			$portfolio_tpls = Utils::get_single_templates( 'portfolio_single');

			Forms::admin_input_select2( [
				'name' => 'portfolio_single_tpl',
				'title' => __( 'Single portfolio template', 'wp-blocks-hub'),
				'desc' => __( 'Single portfolio templates located in <code>/wp-content/plugins/wp-blocks-hub/templates/portfolio/</code> directory. You can override these templates in your theme by copying them into <code>/wp-content/themes/your-theme/wp-blocks-hub/templates/portfolio/</code> folder.', 'wp-blocks-hub'),
				'values' => $portfolio_tpls,
				'multiple' => false,
				'value' => Settings::get_option( 'portfolio_single_tpl' )
			]);

			Forms::admin_input_checkbox( [
				'name' => 'disable_portfolio_cat',
				'title' => __( 'Disable portfolio category', 'wp-blocks-hub'),
				'subtitle' => __( 'Portfolio category', 'wp-blocks-hub'),
				'desc' => __( 'Check this option to remove Portfolio Category taxonomy from public visibility', 'wp-blocks-hub'),
				'value' => 'yes',
				'checked' => Utils::bool( Settings::get_option( 'disable_portfolio_cat' ) ),
			]);

			Forms::admin_input_text( [
				'name' => 'portfolio_cat_slug',
				'title' => __( 'Portfolio category slug', 'wp-blocks-hub'),
				'value' => Settings::get_option( 'portfolio_cat_slug' )
			]);

			Forms::admin_input_checkbox( [
				'name' => 'disable_portfolio_tag',
				'title' => __( 'Disable portfolio tags', 'wp-blocks-hub'),
				'subtitle' => __( 'Portfolio tags', 'wp-blocks-hub'),
				'desc' => __( 'Check this option to remove Portfolio Tag taxonomy from public visibility', 'wp-blocks-hub'),
				'value' => 'yes',
				'checked' => Utils::bool( Settings::get_option( 'disable_portfolio_tag' ) ),
			]);

			Forms::admin_input_text( [
				'name' => 'portfolio_tag_slug',
				'title' => __( 'Portfolio tag slug', 'wp-blocks-hub'),
				'value' => Settings::get_option( 'portfolio_tag_slug' )
			]);

			$portfolio_archive_tpls = Utils::get_single_templates( 'portfolio_archive');

			Forms::admin_input_select2( [
				'name' => 'portfolio_archive_tpl',
				'title' => __( 'Portfolio archive template', 'wp-blocks-hub'),
				'desc' => __( 'Portfolio archive templates located in <code>/wp-content/plugins/wp-blocks-hub/templates/portfolio_archive/</code> directory. You can override these templates in your theme by copying them into <code>/wp-content/themes/your-theme/wp-blocks-hub/templates/portfolio_archive/</code> folder.', 'wp-blocks-hub'),
				'values' => $portfolio_archive_tpls,
				'multiple' => false,
				'value' => Settings::get_option( 'portfolio_archive_tpl' )
			]);

		?>
	</tbody>
</table>