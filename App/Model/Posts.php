<?php

namespace WPBlocksHub\Model;
use WPBlocksHub\Helper\Settings;

/**
 * Blog posts model
 *
 */
class Posts extends Database {
	
	/**
	 * Get posts
	 */
	function get_posts( $args ) {

		$defaults = [
			'post_type' => 'post',
			'post_status' => 'publish'
		];

		$query_args = array_merge( $defaults, $args );

		return new \WP_Query( $query_args );

	}

}

?>