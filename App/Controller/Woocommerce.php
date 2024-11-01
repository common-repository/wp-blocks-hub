<?php
namespace WPBlocksHub\Controller;
use WPBlocksHub\Helper\Utils;

/**
 * Woocommerce Controller
 **/
class Woocommerce {

	/**
	 * Constructor
	 **/
	function __construct() {

		// show products cat in REST
		add_action( 'init', [ $this, 'add_product_cat_to_rest'], 11 );

	}

	/**
	 * Add product categories to REST
	 */
	function add_product_cat_to_rest() {

		if( class_exists( 'WooCommerce' ) ) {

			$products_cat = get_taxonomy( 'product_cat' );

			$products_cat->show_in_rest = true;
			$products_cat->rest_base = 'wpbh_products_cats';

			// update taxonomy
			register_taxonomy( 'product_cat', apply_filters( 'woocommerce_taxonomy_objects_product_cat', ['product'] ), (array) $products_cat );

		}

	}

}

?>