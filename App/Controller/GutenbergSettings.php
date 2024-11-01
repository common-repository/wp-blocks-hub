<?php
namespace WPBlocksHub\Controller;
use WPBlocksHub\Helper\Utils;

/**
 * Gutenberg Settings Controller
 **/
class GutenbergSettings {

	/**
	 * Constructor
	 **/
	function __construct() {

		// force to localize all gutenberg blocks
		add_filter( 'load_script_translation_file', function( $file, $handle, $domain) {
			if( $domain === 'wp-blocks-hub' ) {
				return WPBH()->Config['plugin_path'] . 'languages/wp-blocks-hub-' . get_locale() . '.json';
			}
			return $file;
		}, 10, 3);

		// add own "WP Blocks Hub" category
		add_filter( 'block_categories', [ $this, 'add_block_category']);

	}

	/**
	 * Add own "WP Blocks Hub" category
	 */
	function add_block_category( $categories ) {
		$category_slugs = wp_list_pluck( $categories, 'slug' );
    return in_array( 'wpbh', $category_slugs, true ) ? $categories : array_merge(
			$categories,
			[
				[
					'slug'  => 'wpbh',
					'title' => __( 'WP Blocks Hub', 'wp-blocks-hub' ),
					'icon'  => 'screenoptions',
				]
			]
		);
	}

}

?>