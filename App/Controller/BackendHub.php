<?php
namespace WPBlocksHub\Controller;
use WPBlocksHub\Helper\Utils;
use WPBlocksHub\Helper\Settings;

/**
 * Hub settings controller
 **/
class BackendHub {

	/**
	 * Constructor
	 **/
	function __construct() {

		// add "Hub" settings
		add_action( 'admin_menu', [ $this, 'add_settings_page'] );

		// load assets
		add_action( 'admin_enqueue_scripts', [ $this, 'load_assets' ] );

		// load hub
		add_action( 'wp_ajax_' . WPBH()->Config['prefix'] . '_load_hub', [ $this, 'ajax_load_hub']);

		// load blocks
		add_action( 'wp_ajax_' . WPBH()->Config['prefix'] . '_load_blocks', [ $this, 'ajax_load_blocks']);

	}

	/**
	 * Add options page under "Plugins" menu item
	 */
	function add_settings_page() {

		add_menu_page(
			__( 'WP Blocks Hub Plugin Settings', 'wp-blocks-hub'),
			Utils::format_menu_title_with_updates( __( 'Blocks Hub', 'wp-blocks-hub') ),
			'manage_options',
			'wp-blocks-hub',
			[  $this, 'show_settings_page' ],
			'dashicons-screenoptions'
		);

		add_submenu_page(
			'wp-blocks-hub',
			__( 'Hub', 'wp-blocks-hub'),
			__( 'Hub', 'wp-blocks-hub'),
			'manage_options',
			'wp-blocks-hub',
			[  $this, 'show_settings_page' ]
		);

	}

	/**
	 * Show settings page
	 */
	function show_settings_page() {

		$view_data = [
			'current_page' => 'hub',
			'current_page_title' => __( 'Blocks Hub', 'wp-blocks-hub')
		];

		WPBH()->View->Load( 'App/View/Backend/Settings/Header', $view_data );

		WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Hub', $view_data );

		WPBH()->View->Load( 'App/View/Backend/Settings/Footer', $view_data );

	}

	/**
	 * Load admin assets
	 **/
	function load_assets() {
		$current_screen = get_current_screen();

		if( $current_screen->id == 'toplevel_page_wp-blocks-hub' ) {

			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_style( 'wp-pointer' ); 
			wp_enqueue_script( 'wpbh-hub', WPBH()->Config['plugin_url'] . '/assets/js/admin/hub.js',
				[
					'jquery',
					'sticky-kit',
					'wp-pointer',
					'jquery-ui-dialog',
					'wpbh-admin-helper'
				],
				WPBH()->Config['cache_time'],
				true
			);

			wp_enqueue_script( 'wpbh-admin-blocks');

		}

	}

	/**
	 * Load Hub data through AJAX
	 */
	function ajax_load_hub() {

		Utils::verify_ajax_request();

		$view_data = WPBH()->Model->Cache->get(
			WPBH()->Config['load_hub_data_url'],
			WPBH()->Config['prefix'] . '_hub_data'
		);

		$view_data->screen = 'hub';
		$view_data->locale = get_locale();
		$view_data->active_blocks = (array)Settings::get_single_option( 'active_blocks' );
		$view_data->inactive_blocks = WPBH()->Model->Block->get_inactive_blocks_ids();

		$blocksHTML = '';

		if( isset( $view_data->blocks ) && !empty( $view_data->blocks ) ) {
			$blocksHTML = WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Hub/Blocks', $view_data, true );
		} else {
			$blocksHTML = WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Hub/NoBlocks', $view_data, true );
		}

		$result = [
			'blocksHTML' => $blocksHTML,
			'hubStatusHTML' => WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Hub/Status', $view_data, true ),
			'hubCompatibilityHTML' => WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Hub/Compatibility', $view_data, true ),
			'hubCategoriesHTML' => WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Hub/Categories', $view_data, true ),
			'hubTagsHTML' => WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Hub/Tags', $view_data, true ),
		];

		wp_send_json_success( $result );

	}

	/**
	 * Load Blocks data through AJAX
	 */
	function ajax_load_blocks() {
		
		Utils::verify_ajax_request();

		parse_str( $_REQUEST['data'], $_filters );
		$hash = md5( $_REQUEST['data'] );

		$view_data = WPBH()->Model->Cache->get( 
			WPBH()->Config['load_hub_blocks_url'],
			WPBH()->Config['prefix'] . '_blocks_data_' . $hash,
			$_filters
		);

		$view_data->screen = 'hub';
		$view_data->active_blocks = (array)Settings::get_single_option( 'active_blocks' );
		$view_data->inactive_blocks = WPBH()->Model->Block->get_inactive_blocks_ids();

		$blocksHTML = '';

		if( isset( $view_data->blocks ) && !empty( $view_data->blocks ) ) {
			$blocksHTML = WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Hub/Blocks', $view_data, true );
		} else if( !isset( $_filters['paged'] ) ) {
			$blocksHTML = WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Hub/NoBlocks', $view_data, true );
		}

		$result = [
			'blocksHTML' => $blocksHTML,
		];

		wp_send_json_success( $result );

	}

}

?>