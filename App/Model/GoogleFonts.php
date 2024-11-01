<?php

namespace WPBlocksHub\Model;

/**
 * Google Fonts model
 *
 */
class GoogleFonts {
	
	/**
	 * Get google fonts and cache
	 */
	public function get_google_fonts() {

		$transient_name = WPBH()->Config['prefix'] . '_google_fonts_list';

		// delete_transient( $transient_name );
		$fonts_cache = get_transient( $transient_name );

		if( defined( 'WPBH_DEBUG') && WPBH_DEBUG ) {
			$fonts_cache = false;
		}

		$week = 7 * DAY_IN_SECONDS;

		if( false === $fonts_cache || ( $fonts_cache['updated'] + $week < time() ) ) {

			$response = wp_remote_get( WPBH()->Config['google_fonts_list_url'] );
			$body = wp_remote_retrieve_body( $response );

			if( 200 === wp_remote_retrieve_response_code( $response ) && !is_wp_error( $body ) && !empty( $body ) ) {

				$new_transient = [
					'updated' => time(),
					'fonts' => $body
				];

				set_transient( $transient_name, $new_transient, $week );
				return $new_transient;

			} else {

				if ( empty( $fonts_cache['fonts'] ) ) {
					$fonts_cache['fonts'] = json_encode( [ 'items' => [] ] );
					set_transient( $transient_name, $fonts_cache, time() - $week + MINUTE_IN_SECONDS );
				}

			}

		} 

		return (array)$fonts_cache;

	}

	/**
	 * Get google fonts array with subsets and variants
	 */
	function get_fonts_array() {

		$fonts = [];
		$all_google_fonts = $this->get_google_fonts();
		$google_fonts = json_decode( $all_google_fonts['fonts'] );

		foreach( $google_fonts->items as $font ) {
			$fonts[ $font->family ] = [
				'category' => $font->category,
				'variants' => $font->variants,
				'subsets' => $font->subsets,
			];
		}

		return $fonts;

	}
	
}

?>