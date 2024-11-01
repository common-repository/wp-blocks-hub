<?php
namespace WPBlocksHub\Controller;
use WPBlocksHub\Helper\Utils;

/**
 * Init Controller
 **/
class Init {

	/**
	 * Constructor
	 **/
	function __construct() {

		// plugin activation hook
		register_activation_hook( WPBH_PLUGIN_FILE, [ $this, 'plugin_install'] );

		// plugin deactivation hook
		register_deactivation_hook( WPBH_PLUGIN_FILE, [ $this, 'plugin_deactivation_hook' ] );

	}

	/**
	 * Activation hook
	 */
	function plugin_install() {

		// Add custom "order" column to wp_terms table
		WPBH()->Model->Taxonomy->install();

		// create necessary directories if they do not exist
		$dirs = WPBH()->Config['writable_dirs'];

		foreach( $dirs as $dir ) {
			if( ! wp_is_writable( $dir ) ) {
				wp_mkdir_p( $dir );
			}
		}

	}

	/**
	 * Deactivation hook
	 */
	function plugin_deactivation_hook() {

		delete_option( 'wpbh_display_welcome_pointer' );
		delete_option( 'wpbh_display_not_writable_msg' );
		delete_option( 'wpbh_display_tax_pointer');
		delete_option( 'wpbh_display_tour_pointers');
		delete_option( 'wpbh_blocks_updates');
		delete_option( 'wpbh_active_blocks');

	}

}

?>