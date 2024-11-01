<?php

namespace WPBlocksHub\Helper;

class Settings {

	/**
	 * Get option
	 */
	public static function get_option( $option_name, $default_value = null ) {

		$options = get_option( WPBH()->Config['prefix'] . '_settings' );

		if( is_null( $default_value ) ) {
			$default_value = isset( WPBH()->Config['default_options'][ $option_name ] ) ? WPBH()->Config['default_options'][ $option_name ] : $default_value;
		}

		return isset( $options[ $option_name ] ) ? $options[ $option_name ] : $default_value;

	}

	/**
	 * Get single option
	 */
	public static function get_single_option( $option_name, $default = null ) {
		return get_option( WPBH()->Config['prefix'] . '_' . $option_name, $default );
	}

	/**
	 * Update single option
	 */
	public static function update_single_option( $option_name, $value ) {
		return update_option( WPBH()->Config['prefix'] . '_' . $option_name, $value, 'no' );
	}

	/**
	 * Update option
	 */
	public static function update_option( $option_name, $new_value ) {

		$options = (array)get_option( WPBH()->Config['prefix'] . '_settings' );
		$options[ $option_name ] = $new_value;

		return true;

	}

	/**
	 * Get post option
	 */
	public static function get_post_option( $post_ID, $option_name, $single = true ) {
		return get_post_meta( $post_ID, WPBH()->Config['prefix'] . '_' . $option_name, $single );
	}

	/**
	 * Update post option
	 */
	public static function update_post_option( $post_ID, $option_name, $new_value ) {
		return update_post_meta( $post_ID, WPBH()->Config['prefix'] . '_' . $option_name, $new_value );
	}

}
?>