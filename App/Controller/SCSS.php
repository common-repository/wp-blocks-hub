<?php
namespace WPBlocksHub\Controller;

use WPBlocksHub\Helper\Utils;
use WPBlocksHub\Helper\Settings;
use ScssPhp\ScssPhp\Compiler;

/**
 * SCSS Compiler Controller
 **/
class SCSS {

	protected $compiler;
	protected $scss_vars;
	protected $tmp_dir;
	protected $cache_dir;
	protected $tmp_dir_url;
	protected $cache_dir_url;

	/**
	 * Constructor
	 **/
	function __construct() {

		// get style vars from settings, register custom functions
		add_action( 'init', [ $this, 'init_compiler']);

		// parse .scss files
		add_filter( 'style_loader_src', [ $this, 'parse_stylesheet'], 100, 2 );

	}

	/**
	 * Get style vars from settings, register custom functions
	 */
	function init_compiler() {

		$this->tmp_dir = WPBH()->Config['tmp_dir_path'];
		$this->cache_dir = WPBH()->Config['tmp_style_cache_dir_path'];
		$this->tmp_dir_url = WPBH()->Config['tmp_dir_url'];
		$this->cache_dir_url = WPBH()->Config['tmp_style_cache_dir_url'];

		$this->scss_vars = [
			'alpha_accent_color' 								=> Settings::get_option( 'alpha_accent_color' ),
			'beta_accent_color' 								=> Settings::get_option( 'beta_accent_color' ),
			'gamma_accent_color' 								=> Settings::get_option( 'gamma_accent_color' ),
			'delta_accent_color' 								=> Settings::get_option( 'delta_accent_color' ),
			'primary_accent_inner' 							=> Settings::get_option( 'primary_accent_inner' ),
			'accent_inner' 											=> Settings::get_option( 'accent_inner' ),
			'tl_border_color' 									=> Settings::get_option( 'tl_border_color' ),
			'tl_icon_color' 										=> Settings::get_option( 'tl_icon_color' ),
			'tl_shadow_color' 									=> Settings::get_option( 'tl_shadow_color' ),
			'tl_shadow_offset_x'								=> Settings::get_option( 'tl_shadow_offset_x' ),
			'tl_shadow_offset_y'								=> Settings::get_option( 'tl_shadow_offset_y' ),
			'tl_shadow_blur'										=> Settings::get_option( 'tl_shadow_blur' ),
			'tl_shadow_spread'									=> Settings::get_option( 'tl_shadow_spread' ),
			'tl_header_color' 									=> Settings::get_option( 'tl_header_color' ),
			'tl_text_color' 										=> Settings::get_option( 'tl_text_color' ),
			'tl_text_alt_color' 								=> Settings::get_option( 'tl_text_alt_color' ),
			'tl_primary_bg_color' 							=> Settings::get_option( 'tl_primary_bg_color' ),
			'tl_secondary_bg_color' 						=> Settings::get_option( 'tl_secondary_bg_color' ),
			'td_border_color' 									=> Settings::get_option( 'td_border_color' ),
			'td_icon_color' 										=> Settings::get_option( 'td_icon_color' ),
			'td_shadow_color' 									=> Settings::get_option( 'td_shadow_color' ),
			'td_shadow_offset_x'								=> Settings::get_option( 'td_shadow_offset_x' ),
			'td_shadow_offset_y'								=> Settings::get_option( 'td_shadow_offset_y' ),
			'td_shadow_blur'										=> Settings::get_option( 'td_shadow_blur' ),
			'td_shadow_spread'									=> Settings::get_option( 'td_shadow_spread' ),
			'td_header_color' 									=> Settings::get_option( 'td_header_color' ),
			'td_text_color' 										=> Settings::get_option( 'td_text_color' ),
			'td_text_alt_color' 								=> Settings::get_option( 'td_text_alt_color' ),
			'td_primary_bg_color' 							=> Settings::get_option( 'td_primary_bg_color' ),
			'td_secondary_bg_color' 						=> Settings::get_option( 'td_secondary_bg_color' ),
			'primary_font' 											=> Settings::get_option( 'primary_font' ),
			'primary_font_size_desktop'					=> Settings::get_option( 'primary_font_size_desktop' ),
			'primary_font_size_mobile'					=> Settings::get_option( 'primary_font_size_mobile' ),
			'primary_font_line_height_desktop'	=> Settings::get_option( 'primary_font_line_height_desktop' ),
			'primary_font_line_height_mobile'		=> Settings::get_option( 'primary_font_line_height_mobile' ),
			'primary_font_custom'								=> Settings::get_option( 'primary_font_custom' ),
			'secondary_font'										=> Settings::get_option( 'secondary_font' ),
			'secondary_font_custom'							=> Settings::get_option( 'secondary_font_custom' ),
			'heading_font_size_desktop'					=> Settings::get_option( 'heading_font_size_desktop' ),
			'heading_font_size_mobile'					=> Settings::get_option( 'heading_font_size_mobile' ),
			'heading_font_line_height_desktop'	=> Settings::get_option( 'heading_font_line_height_desktop' ),
			'heading_font_line_height_mobile'		=> Settings::get_option( 'heading_font_line_height_mobile' ),
			'bigger_font_size_desktop'					=> Settings::get_option( 'bigger_font_size_desktop' ),
			'bigger_font_size_mobile'						=> Settings::get_option( 'bigger_font_size_mobile' ),
			'bigger_font_line_height_desktop'		=> Settings::get_option( 'bigger_font_line_height_desktop' ),
			'bigger_font_line_height_mobile'		=> Settings::get_option( 'bigger_font_line_height_mobile' ),
			'smaller_font_size_desktop'					=> Settings::get_option( 'smaller_font_size_desktop' ),
			'smaller_font_size_mobile'					=> Settings::get_option( 'smaller_font_size_mobile' ),
			'smaller_font_line_height_desktop'	=> Settings::get_option( 'smaller_font_line_height_desktop' ),
			'smaller_font_line_height_mobile'		=> Settings::get_option( 'smaller_font_line_height_mobile' ),
			'mobile_breakpoint'									=> Settings::get_option( 'mobile_breakpoint' ),
		];

		$this->compiler = new Compiler();
		$this->compiler->setVariables( $this->scss_vars );
		$this->compiler->setFormatter( 'ScssPhp\ScssPhp\Formatter\Compressed' );

		$this->compiler->registerFunction(
			'fontFamilyPrimary', 
			function() {

				if( $this->scss_vars['primary_font'] == 'custom' ) {
					return $this->scss_vars['primary_font_custom'];
				} else {
					return $this->scss_vars['primary_font'] == '' ? '' : $this->scss_vars['primary_font'];
				}

			}
		);

		$this->compiler->registerFunction(
			'fontFamilySecondary', 
			function() {

				if( $this->scss_vars['secondary_font'] == 'custom' ) {
					return $this->scss_vars['secondary_font_custom'];
				} else {
					return $this->scss_vars['secondary_font'] == '' ? '' : $this->scss_vars['secondary_font'];
				}

			}
		);

		$this->compiler->registerFunction(
			'boxShadowLight', 
			function() {
				return $this->scss_vars['tl_shadow_offset_x'] . 'px ' . $this->scss_vars['tl_shadow_offset_y'] . 'px ' . $this->scss_vars['tl_shadow_blur'] . 'px ' . $this->scss_vars['tl_shadow_spread'] . 'px ' . $this->scss_vars['tl_shadow_color'] . ';';
			}
		);

		$this->compiler->registerFunction(
			'boxShadowDark', 
			function() {
				return $this->scss_vars['td_shadow_offset_x'] . 'px ' . $this->scss_vars['td_shadow_offset_y'] . 'px ' . $this->scss_vars['td_shadow_blur'] . 'px ' . $this->scss_vars['td_shadow_spread'] . 'px ' . $this->scss_vars['td_shadow_color'] . ';';
			}
		);

	}

	/**
	 * Parse .scss files
	 */
	function parse_stylesheet( $src, $handle ) {

		// we only want to handle .scss files
		if ( ! preg_match( '/\.scss(\.php)?$/', preg_replace( '/\?.*$/', '', $src ) ) ) {
			return $src;
		}

		// get file path from $src
		if ( ! strstr( $src, '?' ) ) {
			$src .= '?';
		}

		$src_scheme = parse_url( $src, PHP_URL_SCHEME );
		$wp_content_url_scheme = parse_url( WP_CONTENT_URL, PHP_URL_SCHEME );
		 
		if ( $src_scheme != $wp_content_url_scheme ) {
			$src = set_url_scheme( $src, $wp_content_url_scheme );
		}

		list( $scss_path, $query_string ) = explode( '?', str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $src ) );

		$version = '';

		if( $this->needs_compile( $handle, $scss_path ) ) {

			try {

				// compile
				$compiled = $this->compiler->compile(
					WPBH()->Controller->FS->read( $scss_path),
				$scss_path );

				// save compiled data
				WPBH()->Controller->FS->write(
					$this->get_cache_dir() . "/{$handle}.css", 
					$compiled
				);

				// save cache data
				$version = $this->set_cached_file_data( $handle, $scss_path );

			} catch (\Exception $e) {

				Utils::write_error_log( '', ' >> ' . $e->getMessage() );

			}

		} else {
			$cache = $this->get_cached_file_data( $handle );
			$version = $cache['time'];
		}

		return add_query_arg( [
			'v' => $version
		], $this->get_cache_dir( false ) . "/{$handle}.css" );

	}

	/**
	 * Get cached stylesheet data
	 */
	function get_cached_file_data( $handle ) {

		$cache = get_option(  WPBH()->Config['prefix'] . '_scss_cache', [] );

		if( isset( $cache[ $handle ] ) ) {
			return $cache[ $handle ];
		}

		return null;
	}

	/**
	 * Set cached data
	 */
	function set_cached_file_data( $handle, $scss_path ) {

		$cache = (array)get_option(  WPBH()->Config['prefix'] . '_scss_cache', [] );
		$time = time();

		$cache[ $handle ] = [
			'version' => $this->get_cache_hash( $scss_path ),
			'time' => $time
		];

		update_option( WPBH()->Config['prefix'] . '_scss_cache', $cache );

		return $time;

	}

	/**
	 * Get cache dir
	 */
	function get_cache_dir( $path = true ) {

		if( ! file_exists( $this->tmp_dir ) ) {
			WPBH()->Controller->FS->mkdir( $this->tmp_dir );
		}

		if( ! file_exists( $this->cache_dir ) ) {
			WPBH()->Controller->FS->mkdir( $this->cache_dir );
		}
		
		return $path === true ? $this->cache_dir : $this->cache_dir_url;

	}

	/**
	 * Check if we need to re-compile stylesheet
	 */
	function needs_compile( $handle, $scss_path ) {

		if( ! file_exists( $this->get_cache_dir() . "/{$handle}.css" ) ) {
			return true;
		}

		$cache_version = $this->get_cache_hash( $scss_path );
		$cache = $this->get_cached_file_data( $handle );

		if( empty( $cache ) ) {
			return true;
		} else {
			return $cache['version'] !== $cache_version;
		}

	}

	/**
	 * Get cache hash
	 */
	function get_cache_hash( $scss_path ) {
		$stylesheet_modified_time = filemtime( $scss_path );
		return md5( serialize( [ $this->scss_vars, $stylesheet_modified_time ] ) );
	}

}

?>