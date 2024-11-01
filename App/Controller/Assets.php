<?php
namespace WPBlocksHub\Controller;
use WPBlocksHub\Helper\Utils;
use WPBlocksHub\Helper\Settings;

/**
 * Assets Controller
 **/
class Assets {

	/**
	 * Constructor
	 **/
	function __construct() {

		// register shared assets
		add_action( 'wp_enqueue_scripts', [ $this, 'register_shared_assets' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_shared_assets' ] );

		// loade google fonts
		add_action( 'wp_enqueue_scripts', [ $this, 'load_google_fonts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'load_google_fonts' ] );

		// load custom CSS
		add_action( 'wp_print_styles', [ $this, 'print_custom_css' ], 10, 99 );

		// load custom JS from settings
		add_action( 'wp_print_footer_scripts', [ $this, 'print_custom_js' ], 10, 99 );

	}

	/**
	 * Register shared assets
	 */
	function register_shared_assets() {

		wp_register_script( 'wpbh-helper', WPBH()->Config['plugin_url'] . '/assets/libs/wpbh-helper/wpbh-helper.js', [ 'jquery'], WPBH()->Config['cache_time'], true );

		wp_register_script( 'circle-progress', WPBH()->Config['plugin_url'] . '/assets/libs/circle-progress/circle-progress.min.js', [ 'jquery'], WPBH()->Config['cache_time'], true );
		wp_register_script( 'typewriter', WPBH()->Config['plugin_url'] . '/assets/libs/typewriter/typewriter.min.js', false, WPBH()->Config['cache_time'], true );
		wp_register_script( 'odometer', WPBH()->Config['plugin_url'] . '/assets/libs/odometer/odometer.min.js', false, WPBH()->Config['cache_time'], true );
		wp_register_script( 'vivus', WPBH()->Config['plugin_url'] . '/assets/libs/vivus/vivus.min.js', false, WPBH()->Config['cache_time'], true );

		wp_register_script( 'swipebox', WPBH()->Config['plugin_url'] . '/assets/libs/swipebox/jquery.swipebox.min.js', false, WPBH()->Config['cache_time'], true );
		wp_register_style( 'swipebox', WPBH()->Config['plugin_url'] . '/assets/libs/swipebox/swipebox.min.css', [], WPBH()->Config['cache_time'] );

		wp_register_script( 'slick', WPBH()->Config['plugin_url'] . '/assets/libs/slick/slick.min.js', false, WPBH()->Config['cache_time'], true );
		wp_register_style( 'slick', WPBH()->Config['plugin_url'] . '/assets/libs/slick/slick.css', [], WPBH()->Config['cache_time'] );

		wp_register_script( 'swiper', WPBH()->Config['plugin_url'] . '/assets/libs/swiper/swiper.min.js', false, WPBH()->Config['cache_time'], true );
		wp_register_style( 'swiper', WPBH()->Config['plugin_url'] . '/assets/libs/swiper/swiper.min.css', [], WPBH()->Config['cache_time'] );

		wp_register_script( 'selectric', WPBH()->Config['plugin_url'] . '/assets/libs/selectric/jquery.selectric.min.js', false, WPBH()->Config['cache_time'], true );
		wp_register_style( 'selectric', WPBH()->Config['plugin_url'] . '/assets/libs/selectric/selectric.min.css', [], WPBH()->Config['cache_time'] );

		wp_register_script( 'minibar', WPBH()->Config['plugin_url'] . '/assets/libs/minibar/minibar.min.js', false, WPBH()->Config['cache_time'], true );
		wp_register_style( 'minibar', WPBH()->Config['plugin_url'] . '/assets/libs/minibar/minibar.min.css', [], WPBH()->Config['cache_time'] );

		$google_maps_key = Settings::get_option( 'google_maps_api_key' );
		if( $google_maps_key <> '' ) {
			wp_register_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $google_maps_key, false, WPBH()->Config['cache_time'], true );
			wp_enqueue_script( 'google-maps');
		}

		wp_register_script( 'leaflet', WPBH()->Config['plugin_url'] . '/assets/libs/leaflet/leaflet.min.js', [], WPBH()->Config['cache_time'] );
		wp_register_script( 'leaflet-color-filter', WPBH()->Config['plugin_url'] . '/assets/libs/leaflet/leaflet-tilelayer-colorfilter.min.js', [], WPBH()->Config['cache_time'] );
		wp_register_style( 'leaflet', WPBH()->Config['plugin_url'] . '/assets/libs/leaflet/leaflet.min.css', [], WPBH()->Config['cache_time'] );

		wp_register_script( 'wow', WPBH()->Config['plugin_url'] . '/assets/libs/wow/wow.min.js', false, WPBH()->Config['cache_time'], true );
		wp_register_script( 'is-in-viewport', WPBH()->Config['plugin_url'] . '/assets/libs/is-in-viewport/isInViewport.min.js', false, WPBH()->Config['cache_time'], true );

		wp_register_style( 'wpbh-grid-animations', WPBH()->Config['plugin_url'] . '/assets/libs/grid-animations/animations.min.css', [], WPBH()->Config['cache_time'] );
		wp_register_style( 'wpbh-social-icons-font', WPBH()->Config['plugin_url'] . '/assets/libs/social-icons-font/css/fontello.css', [], WPBH()->Config['cache_time'] );
		wp_register_style( 'wpbh-grid', WPBH()->Config['plugin_url'] . '/assets/libs/grid/bootstrap-grid.min.css', [], WPBH()->Config['cache_time'] );
		wp_register_style( 'wpbh-hamburger', WPBH()->Config['plugin_url'] . '/assets/libs/hamburger/hamburger.min.css', [], WPBH()->Config['cache_time'] );

	}

	/**
	 * Load assets
	 */
	function load_google_fonts() {

		wp_register_script( 'webfont', WPBH()->Config['plugin_url'] . '/assets/libs/webfont/webfont.js', false, WPBH()->Config['cache_time'], true );

		// load google fonts from settings
		if( Utils::bool( Settings::get_option( 'load_google_fonts' ) ) ) {

			$webfonts = [];

			$standard_fonts = WPBH()->Config['standard_fonts'];
			$google_fonts = WPBH()->Model->GoogleFonts->get_fonts_array();

			$primary_font = Settings::get_option( 'primary_font' );
			$secondary_font = Settings::get_option( 'secondary_font' );
			$additional_subsets = Settings::get_option( 'google_fonts_subsets' );

			foreach( [ $primary_font, $secondary_font ] as $webfont ) {

				if( ! in_array( $webfont, ['', 'custom'] ) && ! in_array( $webfont, $standard_fonts ) ) {

					$font_query = urlencode( $webfont );
	
					if( in_array( '700', $google_fonts[ $webfont ]['variants'] ) ) {
						$font_query .= ':regular,700';
					}
	
					if( !empty( $additional_subsets ) ) {
						$_subsets = [];
						foreach( $additional_subsets as $subset ) {
							if( $subset == 'latin' ) {
								continue;
							}
							if( in_array( $subset, $google_fonts[ $webfont ]['subsets'] ) ) {
								$_subsets[] = $subset;
							}
						}
						if( !empty( $_subsets ) ) {
							$font_query .= ':' . implode( ',', $_subsets );
						}
					}
	
					$webfonts['google']['families'][] = $font_query;
				}

			}

			$fonts_json = json_encode( $webfonts );

			wp_enqueue_script( 'webfont' );
			wp_add_inline_script( 'webfont', "WebFont.load( $fonts_json );" );

		}

	}

	/**
	 * Print custom CSS
	 */
	function print_custom_css() {
		?>
		<style><?php echo Settings::get_single_option( 'custom_css' ); ?></style>
		<?php
	}

	/**
	 * Print custom JS
	 */
	function print_custom_js() {
		?>
		<script><?php echo stripslashes( Settings::get_single_option( 'custom_js' ) ); ?></script>
		<?php
	}

}

?>