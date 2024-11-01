<?php
namespace WPBlocksHub\Controller;
use WPBlocksHub\Helper\Utils;
use WPBlocksHub\Helper\Forms;
use WPBlocksHub\Helper\Settings;

/**
 * Settings controller
 **/
class BackendSettings {

	/**
	 * Constructor
	 **/
	function __construct() {

		// add admin menu
		add_action( 'admin_menu', [ $this, 'add_settings_page'] );

		// load assets
		add_action( 'admin_enqueue_scripts', [ $this, 'load_assets' ] );

		// save settings
		add_action( 'wp_ajax_' . WPBH()->Config['prefix'] . '_save_settings', [ $this, 'save_settings' ] );

		// install demo posts
		add_action( 'wp_ajax_' . WPBH()->Config['prefix'] . '_install_demo_posts', [ $this, 'install_demo_posts' ] );

		// clear error log
		add_action( 'wp_ajax_' . WPBH()->Config['prefix'] . '_clear_error_log', [ $this, 'clear_error_log' ] );

		// flush cache
		add_action( 'wp_ajax_' . WPBH()->Config['prefix'] . '_flush_cache', [ $this, 'flush_cache' ] );

		// reset all settings
		add_action( 'wp_ajax_' . WPBH()->Config['prefix'] . '_reset_settings', [ $this, 'reset_settings' ] );

		// load tour pointers
		add_action( 'wp_ajax_' . WPBH()->Config['prefix'] . '_load_tour_pointers', [ $this, 'ajax_load_tour_pointers']);

	}

	/**
	 * Add options page under "Plugins" menu item
	 */
	function add_settings_page() {

		add_submenu_page(
			'wp-blocks-hub',
			__( 'Settings', 'wp-blocks-hub'),
			__( 'Settings', 'wp-blocks-hub'),
			'manage_options',
			'wp-blocks-hub-settings',
			[  $this, 'show_settings_page' ]
		);

	}

	/**
	 * Load admin assets
	 **/
	function load_assets() {

		$current_screen = get_current_screen();

		if( strpos( $current_screen->id, 'wp-blocks-hub-settings' ) !== false ) {

			wp_enqueue_style( 'wp-color-picker' ); 
			wp_enqueue_style( 'select2' ); 
			wp_enqueue_style( 'wp-pointer' ); 
			wp_enqueue_style( 'ion-range-slider' ); 
			wp_enqueue_script( 'toc', WPBH()->Config['plugin_url'] . '/assets/libs/toc/toc.js', [ 'jquery' ], WPBH()->Config['cache_time'], true );
	
			wp_enqueue_script( 'wpbh-settings', WPBH()->Config['plugin_url'] . '/assets/js/admin/settings.js',
				[
					'jquery',
					'wp-color-picker-alpha',
					'sticky-kit',
					'toc',
					'select2',
					'wp-pointer',
					'ace',
					'ion-range-slider',
					'wpbh-admin-layout'
				],
				WPBH()->Config['cache_time'],
				true
			);

		}

	}

	/**
	 * Show settings page
	 */
	function show_settings_page() {

		$fonts = WPBH()->Model->GoogleFonts->get_google_fonts();

		$google_fonts_data = json_decode( $fonts['fonts'], true );
		$fonts_array = Forms::sanitize_fonts_array( $google_fonts_data, '', true, true );

		$view_data = [
			'current_page' => 'settings',
			'current_page_title' => __( 'Blocks Hub: Settings', 'wp-blocks-hub'),
			'available_post_types' => get_post_types( '', 'objects' ),
			'fonts' => $fonts_array
		];

		WPBH()->View->Load( 'App/View/Backend/Settings/Header', $view_data );

		WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Settings', $view_data );

		WPBH()->View->Load( 'App/View/Backend/Settings/Footer', $view_data );

	}

	/**
	 * Save settings
	 */
	function save_settings() {

		Utils::verify_ajax_request();

		if( current_user_can( 'manage_options') ) {

			$new_settings = $_settings = [];

			parse_str( $_POST['data'], $new_settings );

			foreach( WPBH()->Config['default_options'] as $key=>$val ) {

				if( isset( $new_settings[ $key ] ) ) {

					if( is_array( $new_settings[ $key ] ) ) {
						$_settings[ $key ] = $new_settings[ $key ];

					} else {
						$_settings[ $key ] = isset( $new_settings[ $key ] ) ? sanitize_text_field( $new_settings[ $key ] ) : null;
					}
				}

			}

			update_option( WPBH()->Config['prefix'] . '_settings', $_settings, 'no' );

			if( isset( $new_settings['custom_css'] ) ) {
				Settings::update_single_option( 'custom_css', $new_settings['custom_css'] );
			}

			if( isset( $new_settings['custom_js'] ) ) {
				Settings::update_single_option( 'custom_js', $new_settings['custom_js'] );
			}

			_e( 'Settings saved successfully', 'wp-blocks-hub');

		} else {

			_e( 'You are not allowed to do that', 'wp-blocks-hub');

		}

		exit;
	}

	/**
	 * Install demo posts
	 */
	function install_demo_posts() {

		Utils::verify_ajax_request();

		if( current_user_can( 'manage_options') ) {

			switch( $_POST['type']) {
				case 'portfolio':

					WPBH()->Model->Portfolio->insert_demo_posts();

				break;
				case 'testimonial':

					WPBH()->Model->Testimonial->insert_demo_posts();

				break;
				case 'person':

					WPBH()->Model->People->insert_demo_posts();

				break;
				case 'benefit':

					WPBH()->Model->Benefits->insert_demo_posts();

				break;
			}

			WPBH()->View->Load( 'App/View/Backend/Pointers/DemoDataPointer' );

		}

		exit;
	}

	/**
	 * Clear error log
	 */
	function clear_error_log() {

		Utils::verify_ajax_request();

		if( current_user_can( 'manage_options') ) {

			Utils::write_error_log( '','', true, true );

			WPBH()->View->Load( 'App/View/Backend/Pointers/ClearErrorLogPointer' );

		}

		exit;

	}

	/**
	 * Flush cache
	 */
	function flush_cache() {

		Utils::verify_ajax_request();

		if( current_user_can( 'manage_options') ) {

			$prefix = WPBH()->Config['prefix'];

			WPBH()->Model->Cache->flush_cache();

			WPBH()->View->Load( 'App/View/Backend/Pointers/FlushCachePointer' );

		}

		exit;
		
	}

	/**
	 * Reset all settings
	 */
	function reset_settings() {
	
		Utils::verify_ajax_request();

		if( current_user_can( 'manage_options') ) {

			$prefix = WPBH()->Config['prefix'];

			Settings::update_single_option( 'settings', '' );
			Settings::update_single_option( 'custom_css', '' );
			Settings::update_single_option( 'custom_js', '' );

			WPBH()->View->Load( 'App/View/Backend/Pointers/ResetSettingsPointer' );

		}

		exit;

	}


	/**
	 * Load tour pointers
	 */
	function ajax_load_tour_pointers() {
		Utils::verify_ajax_request();

		$pointers = [
			[
				'content' => WPBH()->View->Load( 'App/View/Backend/Pointers/Tour/Step1', [], true ),
				'element' => '#wpbh-filters-panel',
				'edge' => 'right',
				'align' => 'right',
			],
			[
				'content' => WPBH()->View->Load( 'App/View/Backend/Pointers/Tour/Step2', [], true ),
				'element' => '#wpbh-hub-blocks .wpbh-block-item:eq(1) .wpbh-block-do-action',
				'edge' => 'top',
				'align' => 'left',
			],
			[
				'content' => WPBH()->View->Load( 'App/View/Backend/Pointers/Tour/Step3', [], true ),
				'element' => '#toplevel_page_wp-blocks-hub ul li:eq(2)',
				'edge' => 'left',
				'align' => 'right',
			],
			[
				'content' => WPBH()->View->Load( 'App/View/Backend/Pointers/Tour/Step4', [], true ),
				'element' => '#toplevel_page_wp-blocks-hub ul li:eq(3)',
				'edge' => 'left',
				'align' => 'right',
			],
			[
				'content' => WPBH()->View->Load( 'App/View/Backend/Pointers/Tour/Step5', [], true ),
				'element' => '#menu-pages',
				'edge' => 'left',
				'align' => 'right',
			],
		];

		wp_send_json_success( $pointers );

	}

}

?>