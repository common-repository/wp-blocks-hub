<?php

namespace WPBlocksHub\Model;

class Database {

	protected $wpdb;
	protected $tables = [];
	
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		
		$this->tables = [
			'posts' 							=> $this->wpdb->prefix . 'posts',
			'terms' 							=> $this->wpdb->prefix . 'terms',
			'termmeta' 						=> $this->wpdb->prefix . 'termmeta',
			'term_taxonomy' 			=> $this->wpdb->prefix . 'term_taxonomy',
			'term_relationships' 	=> $this->wpdb->prefix . 'term_relationships',
		];
		
	}
}

?>