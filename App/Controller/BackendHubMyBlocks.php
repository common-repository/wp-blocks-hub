<?php
namespace WPBlocksHub\Controller;
use WPBlocksHub\Helper\Utils;
use WPBlocksHub\Helper\Settings;

/**
 * My blocks controller
 **/
class BackendHubMyBlocks {

	/**
	 * Constructor
	 **/
	function __construct() {

		add_action( 'admin_menu', [ $this, 'add_settings_page'] );

		// load assets
		add_action( 'admin_enqueue_scripts', [ $this, 'load_assets' ] );

		// load my blocks
		add_action( 'wp_ajax_' . WPBH()->Config['prefix'] . '_load_my_blocks', [ $this, 'ajax_load_my_blocks']);

	}

	/**
	 * Add options page under "Plugins" menu item
	 */
	function add_settings_page() {

		add_submenu_page(
			'wp-blocks-hub',
			__( 'My Blocks', 'wp-blocks-hub'),
			Utils::format_menu_title_with_updates( __( 'My Blocks', 'wp-blocks-hub') ),
			'manage_options',
			'wp-blocks-hub-my-blocks',
			[  $this, 'show_settings_page' ]
		);

	}

	/**
	 * Show settings page
	 */
	function show_settings_page() {

		$view_data = [
			'current_page' => 'my_blocks',
			'current_page_title' => __( 'Blocks Hub: My blocks', 'wp-blocks-hub')
		];

		WPBH()->View->Load( 'App/View/Backend/Settings/Header', $view_data );

		WPBH()->View->Load( 'App/View/Backend/Settings/Pages/My_Blocks', $view_data );

		WPBH()->View->Load( 'App/View/Backend/Settings/Footer', $view_data );

	}

	/**
	 * Load admin assets
	 **/
	function load_assets() {
		$current_screen = get_current_screen();

		if( strpos( $current_screen->id, 'blocks-hub-my-blocks' ) !== false ) {

			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_script( 'wpbh-my-blocks', WPBH()->Config['plugin_url'] . '/assets/js/admin/myBlocks.js',
				[
					'jquery',
					'sticky-kit',
					'jquery-ui-dialog',
					'wpbh-admin-helper',
				],
				WPBH()->Config['cache_time'],
				true
			);

			wp_enqueue_script( 'wpbh-admin-blocks');

		}

	}

	/**
	 * Load my blocks
	 */
	function ajax_load_my_blocks() {

		Utils::verify_ajax_request();

		$_filters = [];

		if( isset( $_REQUEST['data'] ) ) {
			parse_str( $_REQUEST['data'], $_filters );
		}

		if( isset( $_filters['filter'] ) && !empty( $_filters['filter'] ) ) {

			if( $_filters['filter'] == 'active' ) {
				$blocks = WPBH()->Model->Block->get_active_blocks();
			} else if( $_filters['filter'] == 'inactive' ) {
				$blocks = WPBH()->Model->Block->get_inactive_blocks();
			}

		} else {
			$blocks = WPBH()->Model->Block->get_all_blocks();
		}

		$view_data = [
			'screen' => 'my-blocks',
			'updates_data' => Settings::get_single_option( 'blocks_updates'),
			'active_blocks' => (array)Settings::get_single_option( 'active_blocks' ),
			'inactive_blocks' => WPBH()->Model->Block->get_inactive_blocks_ids(),
			'blocks' => $blocks,
		];

		$blocksHTML = '';

		if( !empty( $blocks ) ) {
			$blocksHTML = WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Hub/Blocks', (object)$view_data, true );
		} else {
			$blocksHTML = WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Hub/NoBlocks', (object)$view_data, true );
		}

		$result = [
			'blocksHTML' => $blocksHTML,
		];

		wp_send_json_success( $result );

		exit;

	}

}

?>