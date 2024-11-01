<?php
namespace WPBlocksHub\Controller;

/**
 * Filesystem Controller
 **/
class FS {

	/**
	 * Read file content
	 **/
	function read( $path ) {
		global $wp_filesystem;

		if( empty( $wp_filesystem)) {
			require_once( ABSPATH . '/wp-admin/includes/file.php');
			WP_Filesystem();
		}

		add_filter( 'filesystem_method', [ $this, 'set_fs_method'] );

		$file_content = $wp_filesystem->get_contents( $path );

		remove_filter( 'filesystem_method', [ $this, 'set_fs_method'] );

		return $file_content;

	}

	/**
	 * Write file content
	 **/
	function write( $path, $content ) {
		global $wp_filesystem;

		if( empty( $wp_filesystem)) {
			require_once( ABSPATH . '/wp-admin/includes/file.php');
			WP_Filesystem();
		}

		add_filter( 'filesystem_method', [ $this, 'set_fs_method'] );

		$wp_filesystem->put_contents( $path, $content, FS_CHMOD_FILE );

		remove_filter( 'filesystem_method', [ $this, 'set_fs_method'] );

	}

	/**
	 * Create directory
	 */
	function mkdir( $path ) {
		global $wp_filesystem;

		if( empty( $wp_filesystem)) {
			require_once( ABSPATH . '/wp-admin/includes/file.php');
			WP_Filesystem();
		}

		add_filter( 'filesystem_method', [ $this, 'set_fs_method'] );

		$wp_filesystem->mkdir( $path );

		remove_filter( 'filesystem_method', [ $this, 'set_fs_method'] );

	}

	function set_fs_method() {
		return 'direct';
	}

}

?>