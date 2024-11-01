<?php

namespace WPBlocksHub\Model;

/**
 * Taxonomy model
 *
 * Works with custom taxonomies
 */
class Taxonomy extends Database {

  /**
   * Install function, adds custom "order" column to wp_terms table
   */
  function install() {

		$result = $this->wpdb->query( 'DESCRIBE ' . $this->tables['terms'] . ' `term_order`' );
		if ( false == $result ) {
			$query = 'ALTER TABLE ' . $this->tables['terms'] . ' ADD `term_order` INT( 4 ) NULL DEFAULT "0"';
			$result = $this->wpdb->query( $query );
		}

  }

  /**
   * Get term order by term id
   */
  function get_term_order_by_term_id( $id ) {
    return $this->wpdb->get_results( 'SELECT term_order FROM ' . $this->tables['terms'] . ' WHERE term_id = ' . absint( $id ) );
  }

  /**
   * Set term order by term id
   */
  function set_term_order_by_term_id( $order, $term_id ) {
    $this->wpdb->update( $this->wpdb->terms, [ 'term_order' => absint( $order ) ], [ 'term_id' => absint( $term_id ) ] );
  }

}
?>