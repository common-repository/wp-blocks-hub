<?php
namespace WPBlocksHub\Controller;
use WPBlocksHub\Helper\Utils;
use WPBlocksHub\Helper\Settings;

/**
 * Blocks Controller
 * Install, uninstall, activate, deactivate, update blocks
 **/
class Blocks {

	protected $_prefix;

	/**
	 * Constructor
	 **/
	function __construct() {

		$this->_prefix = WPBH()->Config['prefix'];

		// load installed and active blocks
		//$this->load_active_blocks();
		add_action( 'after_setup_theme', [ $this, 'load_active_blocks']);

		// block actions
		add_action( 'wp_ajax_' . $this->_prefix . '_block_download', [ $this, 'download']);
		add_action( 'wp_ajax_' . $this->_prefix . '_block_activate', [ $this, 'activate']);
		add_action( 'wp_ajax_' . $this->_prefix . '_block_deactivate', [ $this, 'deactivate']);
		add_action( 'wp_ajax_' . $this->_prefix . '_block_remove', [ $this, 'remove']);
		add_action( 'wp_ajax_' . $this->_prefix . '_block_update', [ $this, 'update']);

		// check for updates using cron job
		$this->schedule_update_cron();		

	}

	/**
	 * Load active blocks
	 */
	function load_active_blocks() {

		$blocksdir = WPBH()->Config['blocks_dir_path'];
		$blocksurl = WPBH()->Config['blocks_dir_url'];
		$active_blocks = (array)Settings::get_single_option( 'active_blocks' );

		$extensions = [
			'gutenberg' => has_action( 'enqueue_block_assets'),
			'elementor' => defined( 'ELEMENTOR_VERSION'),
			'wpbpb' => defined( 'WPB_VC_VERSION'),
			'beaver' => class_exists( 'FLBuilder' ),
			'shortcode' => ! defined( 'WPB_VC_VERSION'),
			'widget' => function_exists( 'register_sidebar')
		];

		if( is_array( $active_blocks ) && !empty( $active_blocks ) ) {

			foreach( $active_blocks as $active_block ) {

				if( $active_block == '' ) {
					continue;
				}

				foreach( $extensions as $extension => $to_load ) {

					$overridden_path = '/wp-blocks-hub/Blocks/' . $active_block . '/';

					$path = $blocksdir . '/' .  $active_block . '/';
					$init_file = $path . '/' . $extension . '/' . $extension . '.php';
					$overridden_init_file = locate_template( $overridden_path . $extension . '/' . $extension . '.php' );

					if( $overridden_init_file <> '' ) {
						$init_file = $overridden_init_file;
					}

					if( $extension == 'beaver' ) {
						$init_file = $path . '/beaver/wpbh-' . $active_block . '.php';
						$overridden_init_file = locate_template( $overridden_path . 'beaver/wpbh-' . $active_block . '.php' );
						if( $overridden_init_file <> '' ) {
							$init_file = $overridden_init_file;
						}
					}

					$assets = $path . '/assets.php';
					$overridden_assets = locate_template( $overridden_path . '/assets.php' );
					if( $overridden_assets <> '' ) {
						$assets = $overridden_assets;
					}

					$ajax = $path . '/ajax.php';
					$overridden_ajax = locate_template( $overridden_path . '/ajax.php' );
					if( $overridden_ajax <> '' ) {
						$ajax = $overridden_ajax;
					}

					$block_url = $blocksurl . '/' .  $active_block . '/';

					// register block assets
					if( file_exists( $assets ) ) {
						require_once $assets;
					}

					// register ajax functions
					if( file_exists( $ajax ) ) {
						require_once $ajax;
					}

					// load necessairy extension
					if( $to_load && file_exists( $init_file ) ) {
						switch( $extension ) {
							case 'widget':

								require_once $init_file;

							break;
							case 'gutenberg':
							
								if( Utils::bool( Settings::get_option( 'disable_gutenberg_blocks' ) ) == false ) {
									require_once $init_file;
								}

							break;
							case 'shortcode':

								if( Utils::bool( Settings::get_option( 'disable_shortcodes' ) ) == false ) {
									add_action( 'init', function() use( $init_file ) {
										require_once $init_file;
									}, 11 );
								}

							break;
							case 'beaver':

								add_action( 'init', function() use( $init_file ) {
									require_once $init_file;
								}, 11 );

							break;
							case 'elementor':

								add_action( 'elementor/widgets/widgets_registered', function() use( $init_file ) {
									require_once $init_file;
								});

								add_action( 'elementor/editor/before_enqueue_scripts', function() use( $block_url, $active_block ) {
									wp_enqueue_style( 'wpbh-elementor-' . $active_block, $block_url . '/elementor/elementor.css', [], WPBH()->Config['cache_time'] );
								});

								add_action( 'elementor/frontend/after_enqueue_scripts', function() use( $active_block ) {
									wp_enqueue_script( 'wpbh-' . $active_block );
								});
								
								add_action( 'elementor/frontend/after_enqueue_styles', function() use( $active_block ) {
									wp_enqueue_style( 'wpbh-' . $active_block );
								});

								add_action( 'elementor/preview/enqueue_styles', function() use( $active_block ) {
									wp_enqueue_script( 'wpbh-' . $active_block );
									wp_enqueue_style( 'wpbh-' . $active_block );
								});

							break;
							case 'wpbpb':

								add_action( 'vc_after_init', function() use( $init_file ) {
									require_once $init_file;
								});

							break;

						}
					}

				}

			}

		}

	}

	/**
	 * Download and unzip block
	 */
	function download() {
		Utils::verify_ajax_request();

		$block_id = absint( $_REQUEST[ 'blockId'] );
		$http = new \WP_Http();

		$response = $http->request( add_query_arg(
			[
				'block_id' => $block_id,
				'locale' => get_locale(),
				'purchase_code' => get_option( 'wpbh_purchase_code', ''),
				'domain' => \WPBlocksHub\Helper\Utils::get_current_domain()
			],
			WPBH()->Config['download_block_url']
		) );

		$tpm_folder = WPBH()->Config['tmp_dir_path'];
		$tmp_file_path = $tpm_folder . '/' . uniqid() . '.zip';

		$blocks_folder = WPBH()->Config['blocks_dir_path'];

		if( intval( wp_remote_retrieve_response_code( $response ) ) === 200 ) {

			global $wp_filesystem;
			include_once ABSPATH . 'wp-admin/includes/file.php';

			$blocks_folder_writable = WP_Filesystem( false, $blocks_folder );

			if( $blocks_folder_writable && $wp_filesystem->method === 'direct' ) {

				// create tmp folder if it does not exist
				if( ! $wp_filesystem->is_dir( $tpm_folder ) ) {

					if( ! $wp_filesystem->mkdir( $tpm_folder ) ) {
						wp_send_json_error( [
							'errorTitle' => __( 'Error', 'wp-blocks-hub'),
							'errorText' => __( 'Can not create temporary folder. Please check hosting files permissions.', 'wp-blocks-hub'),
						] );
					} 

				} 

				if( $wp_filesystem->is_dir( $tpm_folder ) ) {

					$maybe_json = json_decode( $response['body'] );

					if( json_last_error() === JSON_ERROR_NONE ) {

						wp_send_json_error( [
							'errorTitle' => __( 'License validation error', 'wp-blocks-hub'),
							'errorText' => $maybe_json->data->error_text,
						] );

					} else {

						if( $wp_filesystem->put_contents( $tmp_file_path, $response['body'] ) ) {

							$unzipfile = unzip_file( $tmp_file_path, $blocks_folder );
	
							$wp_filesystem->delete( $tmp_file_path, false, 'f' );
	
							if( is_wp_error( $unzipfile ) ) {
			
								wp_send_json_error( [
									'errorTitle' => __( 'Error', 'wp-blocks-hub'),
									'errorText' => __( 'Can not unzip block files to blocks directory.', 'wp-blocks-hub'),
								] );
			
							} else {
			
								wp_send_json_success( [
									'btnActionNow' => 'activate',
									'btnTextNow' => __( 'Activate', 'wp-blocks-hub'),
								] );
			
							}
	
						} else {
	
							wp_send_json_error( [
								'errorTitle' => __( 'Error', 'wp-blocks-hub'),
								'errorText' => __( 'Can not save ZIP file to a temporary folder.', 'wp-blocks-hub'),
							] );
			
						}

					}

				}

			} else {

				wp_send_json_error( [
					'errorTitle' => __( 'Error', 'wp-blocks-hub'),
					'errorText' => __( 'Can not save downloaded file into temporary folder. Please check hosting files permissions.', 'wp-blocks-hub'),
				] );

			}

		} else {

			wp_send_json_error( [
				'errorTitle' => __( 'Error', 'wp-blocks-hub'),
				'errorText' => __( 'Can not retreive ZIP archive from Hub.', 'wp-blocks-hub'),
			] );

		}

		exit;

	}

	/**
	 * Activate block
	 */
	function activate() {
		Utils::verify_ajax_request();

		$slug = sanitize_title( $_REQUEST['blockSlug'] );
		$block_dir = WPBH()->Config['blocks_dir_path'];

		if( file_exists( $block_dir . '/' . $slug ) ) {

			WPBH()->Model->Block->activate_block( $slug );

			wp_send_json_success( [
				'btnActionNow' => 'deactivate',
				'btnTextNow' => __( 'Deactivate', 'wp-blocks-hub'),
			] );

		} else {

			wp_send_json_error( [
				'errorTitle' => __( 'Error', 'wp-blocks-hub'),
				'errorText' => __( 'Can not activate block, wrong block slug identifier was provided.', 'wp-blocks-hub'),
			] );

		}

		exit;
	}

	/**
	 * Deactivate block
	 */
	function deactivate() {
		Utils::verify_ajax_request();

		$slug = sanitize_title( $_REQUEST['blockSlug'] );

		WPBH()->Model->Block->deactivate_block( $slug );

		wp_send_json_success( [
			'btnActionNow' => 'activate',
			'btnTextNow' => __( 'Activate', 'wp-blocks-hub'),
		] );

		exit;
	}

	/**
	 * Remove block
	 */
	function remove() {
    global $wp_filesystem;
		
		Utils::verify_ajax_request();

		$slug = sanitize_title( $_REQUEST['blockSlug'] );
		$id = absint( $_REQUEST['blockId'] );

		$path = WPBH()->Config['blocks_dir_path'] . '/' . $slug;

		if( file_exists( $path ) && $id > 0 ) {

			// firstly deactivate
			WPBH()->Model->Block->deactivate_block( $slug );

			// remove from updates
			WPBH()->Model->Block->remove_from_updates( $id );

			// is writable
			if( WP_Filesystem( false, $path ) ) {

				if( $wp_filesystem->rmdir( $path, true) ) {

					wp_send_json_success();

				} else {

					wp_send_json_error( [
						'errorTitle' => __( 'Error', 'wp-blocks-hub'),
						'errorText' => __( 'Can not remove block, directory is not writable and can not be removed.', 'wp-blocks-hub'),
					] );

				}

			} else {

				wp_send_json_error( [
					'errorTitle' => __( 'Error', 'wp-blocks-hub'),
					'errorText' => __( 'Can not remove block, please check hosting files permissions.', 'wp-blocks-hub'),
				] );

			}

		} else {

			wp_send_json_error( [
				'errorTitle' => __( 'Error', 'wp-blocks-hub'),
				'errorText' => __( 'Can not remove block, a directory with slug identifier does not exist or wrong block ID was provided.', 'wp-blocks-hub'),
			] );

		}	

		exit;
	}

	/**
	 * Update block
	 */
	function update() {
		global $wp_filesystem;
		Utils::verify_ajax_request();

		$slug = sanitize_title( $_REQUEST['blockSlug'] );
		$id = absint( $_REQUEST['blockId'] );

		$blocks_dir = WPBH()->Config['blocks_dir_path'];
		$block_dir = $blocks_dir . '/' . $slug;
		$tpm_folder = WPBH()->Config['tmp_dir_path'];
		$tmp_file_path = $tpm_folder . '/' . uniqid() . '.zip';

		if( file_exists( $block_dir ) && $id > 0 ) {

			$http = new \WP_Http();
			
			// get new block zipped files
			$request = $http->request( add_query_arg(
				[
					'block_id' => $id,
					'locale' => get_locale(),
					'purchase_code' => get_option( 'wpbh_purchase_code', ''),
					'domain' => \WPBlocksHub\Helper\Utils::get_current_domain()
				],
				WPBH()->Config['download_block_url']
			) );

			if( intval( wp_remote_retrieve_response_code( $request ) ) === 200 ) {

				include_once ABSPATH . 'wp-admin/includes/file.php';
				$is_blocks_dir_writable = WP_Filesystem( false, $block_dir );

				if( $is_blocks_dir_writable && $wp_filesystem->method === 'direct' ) {

					// create tmp folder if it does not exist
					if( ! $wp_filesystem->is_dir( $tpm_folder ) ) {

						if( ! $wp_filesystem->mkdir( $tpm_folder ) ) {
							wp_send_json_error( [
								'errorTitle' => __( 'Error', 'wp-blocks-hub'),
								'errorText' => __( 'Can not create temporary folder. Please check hosting files permissions.', 'wp-blocks-hub'),
							] );
						} 

					} 

					// remove current files
					if( $wp_filesystem->rmdir( $block_dir, true) ) {

						if( $wp_filesystem->is_dir( $tpm_folder ) ) {
							if( $wp_filesystem->put_contents( $tmp_file_path, $request['body'] ) ) {
		
								$unzipfile = unzip_file( $tmp_file_path, $blocks_dir );
		
								$wp_filesystem->delete( $tmp_file_path, false, 'f' );
		
								if( is_wp_error( $unzipfile ) ) {
				
									wp_send_json_error( [
										'errorTitle' => __( 'Error', 'wp-blocks-hub'),
										'errorText' => __( 'Can not unzip block files to blocks directory.', 'wp-blocks-hub'),
									] );
				
								} else {
				
									// remove from updates
									WPBH()->Model->Block->remove_from_updates( $id );
									wp_send_json_success();
				
								}
		
							} else {
		
								wp_send_json_error( [
									'errorTitle' => __( 'Error', 'wp-blocks-hub'),
									'errorText' => __( 'Can not save ZIP file to a temporary folder.', 'wp-blocks-hub'),
								] );
				
							}
						}
	
					} else {
	
						wp_send_json_error( [
							'errorTitle' => __( 'Error', 'wp-blocks-hub'),
							'errorText' => __( 'Can not remove block, directory is not writable and can not be removed.', 'wp-blocks-hub'),
						] );
	
					}

				} else {

					wp_send_json_error( [
						'errorTitle' => __( 'Error', 'wp-blocks-hub'),
						'errorText' => __( 'Can not save downloaded file into temporary folder. Please check hosting files permissions.', 'wp-blocks-hub'),
					] );

				}

			} else {

				wp_send_json_error( [
					'errorTitle' => __( 'Error', 'wp-blocks-hub'),
					'errorText' => __( 'Can not retreive ZIP archive from Hub.', 'wp-blocks-hub'),
				] );

			}

		} else {

			wp_send_json_error( [
				'errorTitle' => __( 'Error', 'wp-blocks-hub'),
				'errorText' => __( 'Can not update block, a directory with slug identifier does not exist or wrong block ID was provided.', 'wp-blocks-hub'),
			] );

		}	

		exit;
	}

	/**
	 * Register updates cron job
	 */
	function schedule_update_cron() {

		$hook = 'wpbh_check_updates';

		if( false === wp_next_scheduled( $hook ) ) {
			wp_schedule_event( time(), 'daily', $hook );
		}

		add_action( $hook, [ $this, 'check_updates'] );

	}

	/**
	 * Check for updates
	 */
	function check_updates() {

		$my_blocks = WPBH()->Model->Block->get_blocks_versions();

		// if we have downloaded blocks
		if( !empty( $my_blocks ) ) {

			$params = [ 'blocks' => array_keys( $my_blocks ) ];

			$response = wp_remote_post(
				WPBH()->Config['check_updates_url'],
				[
					'body' => $params
				]
			);
	
			$body = wp_remote_retrieve_body( $response );
	
			if( 200 === wp_remote_retrieve_response_code( $response ) && !is_wp_error( $body ) && !empty( $body ) ) {
	
				$data_array = json_decode( $body, true );
	
				if( ! is_null( $data_array ) && !empty( $data_array['blocks'] ) ) {

					// set updates info

					$updates_info = [];

					foreach( $data_array['blocks'] as $id=>$actual_version ) {
						if( version_compare( $actual_version, $my_blocks[$id], '>' ) ) {
							$updates_info[ $id ] = $actual_version;
						}
					}

					Settings::update_single_option( 'blocks_updates', $updates_info );

				}
	
			}

		}

	}

}

?>