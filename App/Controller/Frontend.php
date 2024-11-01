<?php
namespace WPBlocksHub\Controller;
use WPBlocksHub\Helper\Settings;

/**
 * Front-end Controller
 **/
class Frontend {

	function __construct() {

		// register assets for front-end part of custom templates
		add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );

		// Custom archive templates
		add_action( 'template_include', [ $this, 'set_cpt_templates'] );

	}

	/**
	 * Register assets for front-end part of custom templates
	 */
	function register_assets() {

		/**
		 * Archive templates assets
		 */
		wp_register_script( 'wpbh-portfolio-archive-01-masonry', WPBH()->Config['plugin_url'] . '/assets/js/front/portfolio-archive-01-masonry.min.js', [ 'jquery', 'swipebox', 'wpbh-helper', 'masonry', 'imagesloaded', 'is-in-viewport'], WPBH()->Config['cache_time'], true );
		wp_register_style( 'wpbh-portfolio-archive-01-masonry', WPBH()->Config['plugin_url'] . '/assets/css/front/portfolio-archive-01-masonry.scss', [ 'wpbh-grid', 'swipebox', 'wpbh-grid-animations'], WPBH()->Config['cache_time'] );

		/**
		 * Single templates assets
		 */
		wp_register_script( 'wpbh-portfolio-single-01-full-width-slider', WPBH()->Config['plugin_url'] . '/assets/js/front/portfolio-single-01-full-width-slider.js', [ 'jquery', 'slick'], WPBH()->Config['cache_time'], true );
		wp_register_style( 'wpbh-portfolio-single-01-full-width-slider', WPBH()->Config['plugin_url'] . '/assets/css/front/portfolio-single-01-full-width-slider.scss', [ 'wpbh-grid', 'slick'], WPBH()->Config['cache_time'] );

		wp_register_script( 'wpbh-portfolio-single-02-two-columns', WPBH()->Config['plugin_url'] . '/assets/js/front/portfolio-single-02-two-columns.js', [ 'jquery'], WPBH()->Config['cache_time'], true );
		wp_register_style( 'wpbh-portfolio-single-02-two-columns', WPBH()->Config['plugin_url'] . '/assets/css/front/portfolio-single-02-two-columns.scss', [ 'wpbh-grid'], WPBH()->Config['cache_time'] );

		wp_register_script( 'wpbh-portfolio-single-03-masonry', WPBH()->Config['plugin_url'] . '/assets/js/front/portfolio-single-03-masonry.js', [ 'jquery'], WPBH()->Config['cache_time'], true );
		wp_register_style( 'wpbh-portfolio-single-03-masonry', WPBH()->Config['plugin_url'] . '/assets/css/front/portfolio-single-03-masonry.scss', [ 'wpbh-grid'], WPBH()->Config['cache_time'] );

		/**
		 * Enqueue archive assets
		 */
		if( is_post_type_archive( 'portfolio_post') || is_tax( 'portfolio_cat') || is_tax( 'portfolio_tag') ) {

			$tpl_name = str_replace( '.php', '', Settings::get_option( 'portfolio_archive_tpl' ) );
			
			wp_enqueue_script( 'wpbh-portfolio-archive-' . $tpl_name );
			wp_enqueue_style( 'wpbh-portfolio-archive-' . $tpl_name );
		}

		/**
		 * Enqueue single assets
		 */
		if( is_singular( 'portfolio_post') ) {

			$tpl_name = str_replace( '.php', '', Settings::get_option( 'portfolio_single_tpl' ) );

			wp_enqueue_script( 'wpbh-portfolio-single-' . $tpl_name );
			wp_enqueue_style( 'wpbh-portfolio-single-' . $tpl_name );
		}

	}

	/**
	 * Set templates for custom post types
	 */
	function set_cpt_templates( $template ) {

		if( is_post_type_archive( 'portfolio_post') || is_tax( 'portfolio_cat') || is_tax( 'portfolio_tag') ) {

			$tpl_name = Settings::get_option( 'portfolio_archive_tpl' );
			$file_override = locate_template( '/wp-blocks-hub/templates/portfolio_archive/' . $tpl_name );

			if( $file_override <> '' ) {
				return $file_override;
			} else {
				return  WPBH()->Config['plugin_path'] . '/templates/portfolio_archive/' . $tpl_name;
			}

		}

		if( is_singular( 'portfolio_post') ) {

			$tpl_name = Settings::get_option( 'portfolio_single_tpl' );
			$file_override = locate_template( 'wp-blocks-hub/templates/portfolio_single/' . $tpl_name );

			if( $file_override <> '' ) {
				return $file_override;
			} else {
				return  WPBH()->Config['plugin_path'] . '/templates/portfolio_single/' . $tpl_name;
			}

		}

		return $template;

	}

}

?>