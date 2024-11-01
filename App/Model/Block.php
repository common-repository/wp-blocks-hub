<?php

namespace WPBlocksHub\Model;
use WPBlocksHub\Helper\Settings;

/**
 * Block model
 *
 */
class Block {

	/**
	 * Get all downloaded blocks
	 */
	function get_all_blocks( $as_array = false ) {

		$dirs = glob( WPBH()->Config['blocks_dir_path'] . '/*', GLOB_ONLYDIR );

		$blocks = [];

		foreach( $dirs as $block_dir ) {

			$manifest_file = $block_dir . '/manifest.json';

			if( file_exists( $manifest_file ) ) {
				$blocks[] = json_decode( WPBH()->Controller->FS->read( $manifest_file ), $as_array );
			}

		}

		return $blocks;
		
	}

	/**
	 * Get blocks versions
	 */
	function get_blocks_versions() {

		$data = [];
		$blocks = $this->get_all_blocks( true );

		if( !empty( $blocks ) ) {
			foreach( $blocks as $block ) {
				$data[ $block['block_id'] ] = $block['version'];
			}
		}

		return $data;

	}

	/**
	 * Get all active blocks
	 */
	function get_active_blocks() {
		
		$blocks = [];
		$my_blocks = $this->get_all_blocks();
		$active_blocks = (array)Settings::get_single_option( 'active_blocks', [] );

		if( !empty( $active_blocks ) ) {

			foreach( $my_blocks as $block ) {

				if( in_array( $block->slug, $active_blocks ) ) {
					$blocks[] = $block;
				} 

			}

		}

		return $blocks;

	}

	/**
	 * Get all inactive blocks
	 */
	function get_inactive_blocks() {
		
		$blocks = [];
		$my_blocks = $this->get_all_blocks();
		$active_blocks = (array)Settings::get_single_option( 'active_blocks', [] );

		if( !empty( $my_blocks ) ) {
			foreach( $my_blocks as $block ) {

				$slug = $block->slug;

				if( in_array( $slug, $active_blocks )) {
					continue;
				} else {
					$blocks[] = $block;
				}

			}
		}

		return $blocks;

	}

	/**
	 * Get inactive blocks ids
	 */
	function get_inactive_blocks_ids() {

		$ids = [];
		$my_blocks = $this->get_all_blocks();
		$active_blocks = (array)Settings::get_single_option( 'active_blocks', [] );

		if( !empty( $my_blocks ) ) {
			foreach( $my_blocks as $block ) {

				$slug = $block->slug;

				if( in_array( $slug, $active_blocks )) {
					continue;
				} else {
					$ids[ $block->block_id ] = $block->slug;
				}

			}
		}

		return $ids;

	}

	/**
	 * Activate block
	 */
	function activate_block( $slug ) {

		$active_blocks = (array)Settings::get_single_option( 'active_blocks', [] );

		if( ! in_array( $slug, $active_blocks ) ) {
			$active_blocks[] = $slug;
			Settings::update_single_option( 'active_blocks', array_filter( $active_blocks ) );
		}
		
	}

	/**
	 * Deactivate block
	 */
	function deactivate_block( $slug ) {

		$active_blocks = (array)Settings::get_single_option( 'active_blocks', [] );

		if( in_array( $slug, $active_blocks ) ) {
			$active_blocks = array_diff( $active_blocks, [$slug] );
			Settings::update_single_option( 'active_blocks', array_filter( $active_blocks ) );
		}

	}

	/**
	 * Remove from updates
	 */
	function remove_from_updates( $id ) {

		$updates = Settings::get_single_option( 'blocks_updates', [] );

		if( in_array( $id, array_keys( $updates ) ) ) {
			unset( $updates[$id] );
			Settings::update_single_option( 'blocks_updates', $updates );
		}

	}

}

?>