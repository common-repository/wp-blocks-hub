<?php

namespace WPBlocksHub\View;

/**
 * Anything to do with templates
 * and outputting client code
 **/
class View {
	
	/**
	 * Load view. Used on back-end side
	 *
	 * @throws \Exception
	 **/
	function Load( $path = '', $data = [], $return = false, $base = null ) { 

		if ( is_null( $base ) ) {
			$base = WPBH()->Config['plugin_path'];
		}
		
		$full_path = $base . $path . '.php';
		
		if ( $return ) {
			ob_start();
		}
		
		if ( file_exists( $full_path ) ) {
			require $full_path;
		} else {
			throw new \Exception( 'The view path ' . $full_path . ' can not be found.' );
		}
		
		if ( $return ) {
			return ob_get_clean();
		}
		
	}

	/**
	 * Load block view
	 *
	 * @throws \Exception
	 **/
	function LoadBlockView( $base, $path = '', $data = [], $return = false ) { 
		
		$base = wp_normalize_path( plugin_dir_path( $base ) );

		$full_path = $base . $path . '.php';
		
		if ( $return ) {
			ob_start();
		}
		
		if ( file_exists( $full_path ) ) {
			require $full_path;
		} else {
			throw new \Exception( 'The view path ' . $full_path . ' can not be found.' );
		}
		
		if ( $return ) {
			return ob_get_clean();
		}
		
	}

}
?>