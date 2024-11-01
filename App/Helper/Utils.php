<?php

namespace WPBlocksHub\Helper;

class Utils {

  /**
   * Sanitize boolean
   */
  public static function bool( $var ) {
    return filter_var( $var, FILTER_VALIDATE_BOOLEAN );
  }

  /**
   * Prepare boolean to JS
   */
  public static function js_bool( $var ) {
    return $var == 1 || $var == '1' || $var == 'true' ? 'true' : 'false';
  }

	/**
	 * Calculate bytes
	 **/
	public static function return_bytes( $val) {
		$val = absint( $val );
		$last = strtolower( $val[ strlen( $val)-1]);
		switch($last) {
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}
		return $val;
  }
  
  /**
   * Get system status
   */
  public static function get_system_status() {

    global $wp_version, $wpdb;

    $debug_active = defined('WP_DEBUG') && WP_DEBUG ? 'Enabled' : 'Disabled';
    $gzip_active = class_exists( 'ZipArchive' ) ? 'Enabled' : 'Disabled';
    $child_active = is_child_theme() ? 'Active' : 'Not used';
    $zip_active = extension_loaded('zip') ? 'Enabled' : 'Disabled';
    $curl_active = function_exists('curl_version') ? 'Enabled' : 'Disabled';
    $memory_limit = self::return_bytes( ini_get('memory_limit') );
    $is_multisite = is_multisite() ? 'Yes' : 'No';
    $plugin_data = get_plugin_data( WPBH_PLUGIN_FILE );

    $info = '';

    $inactive_blocks = $active_blocks = [];

    foreach( WPBH()->Model->Block->get_active_blocks() as $block ) {
      $active_blocks[] = $block->block_title . ' (v' . $block->version . ')';
    }

    foreach( WPBH()->Model->Block->get_inactive_blocks() as $block ) {
      $inactive_blocks[] = $block->block_title . ' (v' . $block->version . ')';
    }

    foreach( WPBH()->Config['writable_dirs'] as $dir ) {

      $info .= is_writable( $dir ) ? "WRITABLE $dir\n" : "NOT WRITABLE $dir\n";

    }

    $info .= '
Hub Plugin Version: ' . $plugin_data['Version'] . '
  
Active blocks: 
' . implode( $active_blocks, ', ' ) . ' 

Inactive blocks: 
' . implode( $inactive_blocks, ', ' ) . ' 

Website URL: ' . get_option('siteurl') . '
Home URL: ' . home_url() . '
Host: ' . $_SERVER['SERVER_NAME'] . '
Multisite: ' . $is_multisite . '
WordPress version: ' . $wp_version . '
WP Debug: ' . $debug_active . '

Theme name: ' . wp_get_theme()->get('Name') . '
Theme version: ' . wp_get_theme()->get('Version') . '
Child theme: ' . $child_active . '

Language: ' . get_locale() . '
 
Server software: ' . $_SERVER['SERVER_SOFTWARE'] . '
GZip: ' . $gzip_active . '
MySQL version: ' . $wpdb->get_var( 'SELECT VERSION();' ) . '
PHP version: ' . PHP_VERSION . '
PHP memory limit: ' . $memory_limit . '
PHP Zip: ' . $zip_active . '
CURL: ' . $curl_active . '
PHP post max size: ' . ini_get('post_max_size') . '
PHP max upload size: ' . size_format( wp_max_upload_size() ) . '
PHP max input vars: ' . ini_get('max_input_vars') . '
PHP max execution time: ' . ini_get('max_execution_time');

    return $info;

  }

  /**
   * Beautiful dump function
   */
  public static function dump( ...$params ) {
		echo '<pre style="text-align: left; font-family: \'Courier New\'; font-size: 12px;line-height: 20px;background: #efefef;border: 1px solid #777;border-radius: 5px;color: #333;padding: 10px;margin:0;overflow: auto;overflow-y: hidden;">';
		var_dump( $params );
		echo '</pre>';
  }

  /**
   * Write error log
   */
  public static function write_error_log( $var, $desc = ' >> ', $clear_log = false, $just_clear = false ) {
		$log_file_destination = WPBH()->Config['tmp_dir_path'] . '/wpbh.log';
		if ( $clear_log ) {
      file_put_contents( $log_file_destination, '' );
      if( $just_clear ) {
        return;
      }
		}
		error_log( '[' . date( "H:i:s" ) . ']' . '-------------------------' . PHP_EOL, 3, $log_file_destination );
		error_log( '[' . date( "H:i:s" ) . ']' . $desc . ' : ' . print_r( $var, true ) . PHP_EOL, 3, $log_file_destination );
  }

  /**
   * Get errors log
   */
  public static function get_error_log() {
    return @file_get_contents( WPBH()->Config['tmp_dir_path'] . '/wpbh.log' );
  }

	/**
	 * Verify AJAX nonce
	 */
	public static function verify_ajax_request() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], WPBH()->Config['prefix'] . '_ajax_nonce' ) ) {
			die( __( 'AJAX request was not validated', 'wp-blocks-hub'));
		}

  }
  
  /**
   * Check updates data
   */
  public static function check_updates_data() {

    $updates = \WPBlocksHub\Helper\Settings::get_single_option( 'blocks_updates');
    $my_blocks = WPBH()->Model->Block->get_blocks_versions();
    $my_blocks_ids = is_array( $my_blocks ) ? array_keys( $my_blocks ) : [];

    $data = [];

    if( !empty( $updates ) ) {
      foreach( $updates as $id=>$version ) {

        if( !in_array( $id, $my_blocks_ids ) ) {
          continue;
        } else {
          $data[ $id ] = $version;
        }
  
      }
    }

    \WPBlocksHub\Helper\Settings::update_single_option( 'blocks_updates', $data );

    return $data;

  }

  /**
   * Check for updates count and display info in the menu
   */
  public static function format_menu_title_with_updates( $text ) {

    $updates = self::check_updates_data();
    $updates_count = is_array( $updates ) && !empty( $updates ) ? count( $updates ) : 0;

    if( $updates_count > 0 ) {
      return $text . ' <span class="update-plugins count-' . $updates_count . '"><span class="update-count">' . $updates_count . '</span></span>';
    } else {
      return $text;
    }

  }

  /**
   * Insert images to media gallery
   */
  public static function add_images_to_media( $thumbs, $dir ) {

    $thumbs_ids = [];

		$attachment_data = [
			'post_mime_type' => 'image/jpg',
			'post_title' => '',
			'post_content' => '',
			'post_status' => 'inherit'
		];

		foreach( $thumbs as $filename ) {

			$file = $dir . $filename;
			$upload_file = wp_upload_bits( $filename, null, file_get_contents( $file));

			if( ! $upload_file['error']) {

				$wp_filetype = wp_check_filetype( $filename, null );

				$attachment = [
					'post_mime_type' => $wp_filetype['type'],
					'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
					'post_content' => '',
					'post_status' => 'inherit'
				];

				$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'] );

				if( ! is_wp_error( $attachment_id)) {
					$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
					wp_update_attachment_metadata( $attachment_id, $attachment_data );

					$thumbs_ids[] = $attachment_id;

				}

			}
    }
    
    return $thumbs_ids;

  }

  /**
   * Get available templates for custom post types
   */
  public static function get_single_templates( $post_type ) {

    $templates = [];
    $tpls_dir = WPBH()->Config['plugin_path'] . '/templates/' . $post_type;

    if( count( glob( "$tpls_dir/*" )) === 0 ) {
      return [];
    }

    $handle = opendir( $tpls_dir );

    while ( false !== ( $file = readdir( $handle ) ) ) {
      if( is_file( $tpls_dir . '/' . $file) ) {
        $templates[] = [
          'title' => $file,
          'value' => $file,
        ];
      }
    }

    return $templates;

  }

  /**
   * Check is premium version used
   */
  public static function is_premium() {
    return get_option( 'wpbh_license', '') == 'activated';
  }

	/**
	 * Get domain name
	 */
	public static function get_current_domain() {

		if( substr( $_SERVER['HTTP_HOST'], 0, 4) == 'www.') {
			$domain = substr( $_SERVER['HTTP_HOST'], 4);
		} else {
			$domain = $_SERVER['HTTP_HOST'];
		}

		return $domain;

	}

}
?>