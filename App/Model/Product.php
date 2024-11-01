<?php

namespace WPBlocksHub\Model;
use WPBlocksHub\Helper\Utils;
use WPBlocksHub\Helper\Settings;

/**
 * Product model
 *
 */
class Product extends Database {
	
	/**
	 * Get posts
	 */
	function get_posts( $args ) {

		$defaults = [
			'post_type' => 'product',
			'post_status' => 'publish'
		];

		$query_args = array_merge( $defaults, $args );

		return new \WP_Query( $query_args );

	}

	/**
	 * Get minimum and maximum prices for WooCommerce products
	 **/
	function get_min_max_prices() {
		global $wpdb, $wp_the_query;

		$args       = $wp_the_query->query_vars;
		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : [];
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] :[];

		if( ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = [
				'taxonomy' => $args['taxonomy'],
				'terms'    => [ $args['term'] ],
				'field'    => 'slug',
			];
		}

		foreach( $meta_query + $tax_query as $key => $query ) {
			if( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
				unset( $meta_query[ $key ] );
			}
		}

		$meta_query = new \WP_Meta_Query( $meta_query );
		$tax_query  = new \WP_Tax_Query( $tax_query );

		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		$sql  = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
		$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
		$sql .= " 	WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', [ 'product'] ) ) ) . "')
					AND {$wpdb->posts}.post_status = 'publish'
					AND price_meta.meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', [ '_price' ] ) ) ) . "')
					AND price_meta.meta_value > '' ";
		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

		if( $search = @\WC_Query::get_main_search_query_sql() ) {
			$sql .= ' AND ' . $search;
		}

		return $wpdb->get_row( $sql );
	}

}

?>