<?php

namespace WPBlocksHub\Model;

/**
 * Transient / cache model
 *
 */
class Cache extends Database {
	
	/**
	 * Get cached request
	 */
	function get( $url, $transient_name, $params = [] ) {

		//delete_transient( $transient_name );

		$transient_cache = get_transient( $transient_name );

		$view_data = [];

		if( defined( 'WPBH_DEBUG') && WPBH_DEBUG ) {
			$transient_cache = false;
		}

		if( $transient_cache === false || empty( $transient_cache ) ) {

			$response = wp_remote_post(
				$url,
				[
					'body' => $params
				]
			);

			$body = wp_remote_retrieve_body( $response );

			if( 200 === wp_remote_retrieve_response_code( $response ) && !is_wp_error( $body ) && !empty( $body ) ) {

				$data_array = json_decode( $body );

				if( ! is_null( $data_array ) ) {
					set_transient( $transient_name, $data_array, DAY_IN_SECONDS );
					$view_data = $data_array;
				} else {
					wp_send_json_error();
				}

			} else {
				wp_send_json_error();
			}

		} else {
			$view_data = $transient_cache;
		}

		return $view_data;

	}

	/**
	 * Delete all saved transients
	 */
	function flush_cache() {
		$prefix = WPBH()->Config['prefix'];
		$this->wpdb->query( 'DELETE FROM `wp_options` WHERE `option_name` LIKE ("%\_transient_' . $prefix . '\_%")' );
		$this->wpdb->query( 'DELETE FROM `wp_options` WHERE `option_name` LIKE ("%\_transient_timeout_' . $prefix . '\_%")' );
	}
	
}

?>