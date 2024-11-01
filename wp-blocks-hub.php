<?php
/*
	Plugin Name: WP Blocks Hub
	Plugin URI: https://wordpress.org/plugins/wp-blocks-hub/
	Description: Plugin provides a huge number of blocks (extensions) for a popular Page Builders (Gutenberg, Elementor, WPBakery Page Builder (ex. Visual Composer) etc) in the cloud. All of provided blocks are easily customizable, have professional design and work with any theme. Also there are no only blocks for page builders, we also have widgets and shortcodes, and all in the same place! Nice! You don't need to keep all unused extensions, simply search and download only blocks thant you really need from a central hub!
	Version: 1.0.2
	Author: WP Blocks Hub
	Author URI: https://wpblockshub.com
	License: GPL2
*/
define( 'WPBH_PLUGIN_FILE', __FILE__ );

// autoloader
spl_autoload_register( function ( $class ) {
	
	$prefix = 'WPBlocksHub\\';
	
	$base_dir = __DIR__ . '/App/';
	
	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}
	
	$relative_class = substr( $class, $len );
	$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

	if ( file_exists( $file ) ) {
		require $file;
	}
} );

// Global point of enter
if ( ! function_exists( 'WPBH' ) ) {
	
	function WPBH() {
		return \WPBlocksHub\App::getInstance();
	}
	
}

// vendor
require_once plugin_dir_path( WPBH_PLUGIN_FILE ) . '/vendor/autoload.php';

// Run the plugin
WPBH()->run();

?>