<?php
namespace WPBlocksHub\Controller;
use WPBlocksHub\Helper\Utils;
/**
 * Backend Controller
 **/
class Backend {

	/**
	 * Constructor
	 **/
	function __construct() {
		
		// load admin assets
		add_action( 'admin_enqueue_scripts', [ $this, 'load_assets' ] );

		// add links to Plugins screen
		add_filter( 'plugin_action_links_' . plugin_basename( WPBH_PLUGIN_FILE ), [ $this, 'plugin_action_links' ] );
		add_filter( 'plugin_row_meta', [ $this, 'add_plugins_screen_links' ], 10, 2); 

		// display notices in admin panel
		add_action( 'admin_notices', [ $this, 'display_admin_notices' ] );

		// dismiss notices in admin panel
		add_action( 'wp_ajax_wpbh_admin_dismiss_notices', [ $this, 'dismiss_admin_notices' ] );

		// add external Go Premium menu item
		add_action( 'admin_menu', [ $this, 'add_menu_go_premium'], 30 );

	}

	/**
	 * Load admin assets
	 **/
	function load_assets() {
		$current_screen = get_current_screen();

		$style_dependencies = [ 'simptip', 'wp-pointer' ];
		$script_dependencies = [ 'jquery', 'jquery-ui-sortable', 'wp-pointer' ];

		if( $current_screen->id == 'portfolio_post' ) {
			wp_enqueue_media();
		}

		// register libs
		wp_register_script( 'wp-color-picker-alpha', WPBH()->Config['plugin_url'] . '/assets/libs/wp-color-picker-alpha/wp-color-picker-alpha.min.js', [ 'jquery', 'wp-color-picker' ], WPBH()->Config['cache_time'], true );

		wp_register_script( 'sticky-kit', WPBH()->Config['plugin_url'] . '/assets/libs/sticky-kit/jquery.sticky-kit.min.js', [ 'jquery' ], WPBH()->Config['cache_time'], true );

		wp_register_script( 'ace', WPBH()->Config['plugin_url'] . '/assets/libs/ace/ace.js', [], WPBH()->Config['cache_time'], true );

		wp_register_style( 'simptip', WPBH()->Config['plugin_url'] . '/assets/libs/simptip/simptip.min.css', [], WPBH()->Config['cache_time'] );

		wp_register_script( 'select2', WPBH()->Config['plugin_url'] . '/assets/libs/select2/select2.full.min.js', [ 'jquery' ], WPBH()->Config['cache_time'], true );
		wp_register_style( 'select2', WPBH()->Config['plugin_url'] . '/assets/libs/select2/select2.min.css', false, WPBH()->Config['cache_time'] );

		wp_register_script( 'ion-range-slider', WPBH()->Config['plugin_url'] . '/assets/libs/ion-range-slider/ion.rangeSlider.min.js', [ 'jquery' ], WPBH()->Config['cache_time'], true );
		wp_register_style( 'ion-range-slider', WPBH()->Config['plugin_url'] . '/assets/libs/ion-range-slider/ion.rangeSlider.min.css', false, WPBH()->Config['cache_time'] );

		wp_enqueue_style( 'wpbh-admin', WPBH()->Config['plugin_url'] . '/assets/css/admin/admin.css', $style_dependencies, WPBH()->Config['cache_time'] );
		wp_register_script( 'wpbh-admin-layout', WPBH()->Config['plugin_url'] . '/assets/js/admin/adminLayout.js', ['jquery', 'wpbh-admin'], WPBH()->Config['cache_time'], true );
		wp_register_script( 'wpbh-admin-helper', WPBH()->Config['plugin_url'] . '/assets/js/admin/helper.js', ['jquery', 'wpbh-admin'], WPBH()->Config['cache_time'], true );
		wp_register_script( 'wpbh-admin-blocks', WPBH()->Config['plugin_url'] . '/assets/js/admin/blocks.js', ['jquery', 'wpbh-admin', 'wpbh-admin-helper'], WPBH()->Config['cache_time'], true );
		wp_enqueue_script( 'wpbh-admin', WPBH()->Config['plugin_url'] . '/assets/js/admin/admin.js', $script_dependencies, WPBH()->Config['cache_time'], true );

		$js_vars = [
			'ajaxNonce' => wp_create_nonce( WPBH()->Config['prefix'] . '_ajax_nonce'),
			'prefix' => WPBH()->Config['prefix'],
			'displayWelcomePointer' => 'yes' === get_option( 'wpbh_display_welcome_pointer', 'yes' ),
			'welcomePointerStr' => WPBH()->View->Load( 'App/View/Backend/Pointers/WelcomePointer', [], true ),
			'displayTaxPointer' => 'yes' === get_option( 'wpbh_display_tax_pointer', 'yes' ),
			'displayTourPointers' => $current_screen->id == 'toplevel_page_wp-blocks-hub' && 'yes' === get_option( 'wpbh_display_tour_pointers', 'yes' ),
			'dragToOrderNotice' => WPBH()->View->Load( 'App/View/Backend/Pointers/DragToOrderPointer', [], true ),
			'selectImgsStr' => __( 'Choose images', 'wp-blocks-hub'),
			'selectImgStr' => __( 'Choose image', 'wp-blocks-hub'),
			'selectOk' => __( 'OK', 'wp-blocks-hub'),
			'strNext' => __( 'Next', 'wp-blocks-hub'),
			'strFinishTour' => __( 'Finish Tour', 'wp-blocks-hub'),
			'loadingError' => __( 'Loading error', 'wp-blocks-hub'),
			'hubLoadingErrorText' => __( 'Something went wrong due loading request.<br>Please try to reload this page, or contact support if the problem persists.', 'wp-blocks-hub'),
			'done' => __( 'Done', 'wp-blocks-hub'),
			'shortcodeCopied' => __( 'Shortcode copied to your clipboard. Use CTRL + V ( or Command + V on Mac) to paste shortcode.', 'wp-blocks-hub'),
			'licenseRequiredTitle' => __( 'Premium Block', 'wp-blocks-hub'),
			'licenseRequiredContent' => sprintf( __( 'Dear friend, please <a href="%s">purchase WP Blocks Hub Premium plugin</a> to download this block.<br>Unlocking premium feature gets you an access for all unlimited<br>blocks from the cloud.<br><br>By purchasing Premium version you support development and maintaning<br>of this project and get more premium blocks and priority support in return!<br><br>Thank you so much!', 'wp-blocks-hub'), WPBH()->Config['pro_plugin_url'] ),
			'isPremiumActive' => \WPBlocksHub\Helper\Utils::is_premium() ? 'yes' : 'no'
		];

		wp_localize_script( 'wpbh-admin', 'wpbhVars', $js_vars );

		if( $current_screen->id == 'benefit_post' ) {
			wp_enqueue_style( 'wp-color-picker' ); 
			wp_enqueue_script( 'wp-color-picker-alpha' );
		}

		if( in_array( $current_screen->id, ['widgets', 'benefit_post', 'portfolio_post'] )) {
			wp_enqueue_script( 'wpbh-admin-layout' );
		}

	}

	/**
	 * Add links to WP admin > Plugins screen
	**/
	function plugin_action_links( $links ) {

		$links['wpbh_pro'] = sprintf( '<a href="%1$s" class="wpbh-pro">%2$s</a>', WPBH()->Config['pro_plugin_url'], __( 'Unlock Premium', 'wp-blocks-hub') );
		return $links;
	}

	/**
	 * Add links to plugin links box
	 */
	function add_plugins_screen_links( $links, $file ) {

		if ( $file == plugin_basename( WPBH_PLUGIN_FILE ) ) {
			$links[] = sprintf( '<a href="%1$s" class="wpbh-pro">%2$s</a>', admin_url('plugins.php?page=wp-blocks-hub'), __( 'Settings', 'wp-blocks-hub') );
		}

		return $links;

	}

	/**
	 * Display notices in admin panel
	 */
	function display_admin_notices() {
		$dirs = WPBH()->Config['writable_dirs'];

		$checked_dirs = [];

		foreach( $dirs as $dir ) {
			if( ! wp_is_writable( $dir ) ) {
				Utils::write_error_log( '', ' >> ' . $dir . ' ' . __('directory is not writable', 'wp-blocks-hub') );
				$checked_dirs[] = $dir;
			}
		}

		if( ! empty( $checked_dirs ) && false == Utils::bool( get_option( 'wpbh_display_not_writable_msg') ) ) {
			WPBH()->View->Load( '/App/View/Backend/NotWritableNotice', $checked_dirs );
		}

	}

	/**
	 * Dismiss admin notices
	 */
	function dismiss_admin_notices() {

		switch( $_POST['notice'] ) {

			case 'hide_welcome_pointer':
				update_option( 'wpbh_display_welcome_pointer', 'no' );
			break;

			case 'not_writable_notice':
				update_option( 'wpbh_display_not_writable_msg', 'yes' );
			break;

			case 'hide_tax_pointer':
				update_option( 'wpbh_display_tax_pointer', 'no' );
			break;

			case 'hide_tour_pointers':
				update_option( 'wpbh_display_tour_pointers', 'no' );
			break;

		}

		exit;

	}

	/**
	 * Add an external link to menu
	 */
	function add_menu_go_premium() {

		add_submenu_page(
			'wp-blocks-hub',
			'<span class="wpbh-activate-pro">' . esc_html__( 'Purchase Premium', 'wp-blocks-hub') . ' ➤ </span>',
			'<span class="wpbh-activate-pro">' . esc_html__( 'Purchase Premium', 'wp-blocks-hub') . ' ➤ </span>',
			'manage_options',
			WPBH()->Config['pro_plugin_url']
		);

	}

}

?>