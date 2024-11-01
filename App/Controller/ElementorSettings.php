<?php
namespace WPBlocksHub\Controller;

/**
 * Elementor Controller
 **/
class ElementorSettings {

	/**
	 * Constructor
	 **/
	function __construct() {

		// add own "WP Blocks Hub" category
		add_filter( 'elementor/init', [ $this, 'add_widget_category']);

	}

	/**
	 * Add own "WP Blocks Hub" category
	 */
	function add_widget_category() {

    \Elementor\Plugin::instance()->elements_manager->add_category(
			'wpbh',
			[ 'title' => __( 'WP Blocks Hub', 'wp-blocks-hub') ]
		);

	}

}

?>