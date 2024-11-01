<?php
namespace WPBlocksHub\Controller;
use WPBlocksHub\Helper\Settings;
use WPBlocksHub\Helper\Media;
use WPBlocksHub\Helper\Utils;

/**
 * REST Controller
 **/
class REST {

	/**
	 * Constructor
	 **/
	function __construct() {

		// register additional REST fields for CPT
		add_action( 'rest_api_init', [ $this, 'register_rest_fields' ]);

	}

	/**
	 * Additional REST fields
	 */
	function register_rest_fields() {

		/**
		 * People posts
		 */
    register_rest_field( 'person_post', 'wpbh_featured_image_src', [
			'get_callback' => [ $this, 'get_featured_image_src' ],
			'update_callback' => null,
			'schema' => null,
		]);

    register_rest_field( 'person_post', 'wpbh_featured_image_src_medium', [
			'get_callback' => [ $this, 'get_featured_image_src_square' ],
			'update_callback' => null,
			'schema' => null,
		]);

		/**
		 * Testimonials posts
		 */
    register_rest_field( 'testimonial_post', 'wpbh_featured_image_src', [
			'get_callback' => [ $this, 'get_featured_image_src' ],
			'update_callback' => null,
			'schema' => null,
		]);

    register_rest_field( 'testimonial_post', 'wpbh_featured_image_src_medium', [
			'get_callback' => [ $this, 'get_featured_image_src_square' ],
			'update_callback' => null,
			'schema' => null,
		]);

		/**
		 * Benefits posts
		 */
    register_rest_field( 'benefit_post', 'wpbh_featured_image_src', [
			'get_callback' => [ $this, 'get_featured_image_svg' ],
			'update_callback' => null,
			'schema' => null,
		]);

		/**
		 * Blog posts
		 */
    register_rest_field( 'post', 'wpbh_category_list', [
			'get_callback' => [ $this, 'get_post_categories_list' ],
			'update_callback' => null,
			'schema' => null,
		]);

    register_rest_field( 'post', 'wpbh_featured_image_src', [
			'get_callback' => [ $this, 'get_featured_image_src_square' ],
			'update_callback' => null,
			'schema' => null,
		]);

    register_rest_field( 'post', 'wpbh_date_formatted', [
			'get_callback' => [ $this, 'get_date_formatted' ],
			'update_callback' => null,
			'schema' => null,
		]);

    register_rest_field( 'post', 'wpbh_date_formatted_short', [
			'get_callback' => [ $this, 'get_date_formatted_short' ],
			'update_callback' => null,
			'schema' => null,
		]);

    register_rest_field( 'post', 'wpbh_post_author_formatted', [
			'get_callback' => [ $this, 'get_post_author_formatted' ],
			'update_callback' => null,
			'schema' => null,
		]);

    register_rest_field( 'post', 'wpbh_post_author_with_photo', [
			'get_callback' => [ $this, 'get_post_author_with_photo' ],
			'update_callback' => null,
			'schema' => null,
		]);

    register_rest_field( 'post', 'wpbh_post_comments_count', [
			'get_callback' => [ $this, 'get_post_comments_count' ],
			'update_callback' => null,
			'schema' => null,
		]);

		/**
		 * Portfolio posts
		 */
    register_rest_field( 'portfolio_post', 'wpbh_featured_image_src', [
			'get_callback' => [ $this, 'get_featured_image_src_square' ],
			'update_callback' => null,
			'schema' => null,
		]);

    register_rest_field( 'portfolio_post', 'wpbh_category_list', [
			'get_callback' => [ $this, 'get_portfolio_categories_list' ],
			'update_callback' => null,
			'schema' => null,
		]);

		/**
		 * Woocommerce fields
		 */

    register_rest_field( 'product', 'wpbh_featured_image_src', [
			'get_callback' => [ $this, 'get_featured_image_src_medium' ],
			'update_callback' => null,
			'schema' => null,
		]);

    register_rest_field( 'product', 'wpbh_product_gallery', [
			'get_callback' => [ $this, 'get_product_gallery' ],
			'update_callback' => null,
			'schema' => null,
		]);

    register_rest_field( 'product', 'wpbh_product_price', [
			'get_callback' => [ $this, 'get_product_price' ],
			'update_callback' => null,
			'schema' => null,
		]);

    register_rest_field( 'product', 'wpbh_product_rating', [
			'get_callback' => [ $this, 'get_product_rating' ],
			'update_callback' => null,
			'schema' => null,
		]);

    register_rest_field( 'product', 'wpbh_is_product_on_sale', [
			'get_callback' => [ $this, 'get_product_sale_status' ],
			'update_callback' => null,
			'schema' => null,
		]);

    register_rest_field( 'product', 'wpbh_product_add_to_cart_btn', [
			'get_callback' => [ $this, 'get_product_add_to_cart_btn' ],
			'update_callback' => null,
			'schema' => null,
		]);

	}


	/**
	 * REST featured image src (full)
	 */
	function get_featured_image_src( $object, $field_name, $request ) {
		$feat_img_array = wp_get_attachment_image_src(
			$object['featured_media'], 'full', false
		);
		return $feat_img_array[0];
	}

	/**
	 * REST featured image src (large)
	 */
	function get_featured_image_src_square( $object, $field_name, $request ) {
		$feat_img_array = wp_get_attachment_image_src(
			$object['featured_media'], 'large', false
		);
		return $feat_img_array[0];
	}

	/**
	 * REST featured image src (medium)
	 */
	function get_featured_image_src_medium( $object, $field_name, $request ) {
		$feat_img_array = wp_get_attachment_image_src(
			$object['featured_media'], 'medium', false
		);
		return $feat_img_array[0];
	}

	/**
	 * REST featured image src (SVG)
	 */
	function get_featured_image_svg( $object, $field_name, $request ) {
		$feat_img_array = wp_get_attachment_image_src(
			$object['featured_media'], 'full', false
		);

		$img = $feat_img_array[0];

		if( Media::is_attachment_svg( $img ) ) {
			$path = get_attached_file( $object['featured_media'] );
			$img = WPBH()->Controller->FS->read( $path );
		}

		return $img;
	}

	/**
	 * Get post categories list
	 */
	function get_post_categories_list( $object ) {
		return get_the_category_list( ' ', '', $object['id'] );
	}

	/**
	 * Get portfolio categories list
	 */
	function get_portfolio_categories_list( $object ) {
		return get_the_term_list( $object['id'], 'portfolio_cat', '', '', '' );
	}

	/**
	 * Get formatted date
	 */
	function get_date_formatted( $object ) {
		return get_the_date( get_option( 'date_format', $object['id'] ) );
	}

	/**
	 * Get short date formatted
	 */
	function get_date_formatted_short( $object ) {
		return '<span class="wpbh-day">' . get_the_date( 'd', $object['id'] ) . '</span><span class="wpbh-month">' . get_the_date( 'F', $object['id'] ) . '</span>';
	}

	/**
	 * Get post author
	 */
	function get_post_author_formatted( $object ) {
		$post_author_id = get_post_field( 'post_author', $object['id'] );
		$author_name = get_the_author_meta( 'display_name', $post_author_id );
		return sprintf( __( 'By <a href="%s">%s</a>', 'wp-blocks-hub'), get_author_posts_url( $post_author_id ), $author_name );
	}

	/**
	 * Get post author with photo
	 */
	function get_post_author_with_photo( $object ) {
		$post_author_id = get_post_field( 'post_author', $object['id'] );
		$author_name = get_the_author_meta( 'display_name', $post_author_id );
		$avatar = get_avatar( $post_author_id, 100 );
		$author_posts_url = get_author_posts_url( $post_author_id );
		$name = sprintf( __( 'By <a href="%s">%s</a>', 'wp-blocks-hub'), $author_posts_url, $author_name );
		$return_html = '<div class="wpbh-author-data"><div class="wpbh-avatar"><a href="' . $author_posts_url . '">' . $avatar . '</a></div><div class="wpbh-author-name">' . $name . '</div></div>';
		return $return_html;
	}

	/**
	 * Get post comments count
	 */
	function get_post_comments_count( $object ) {
		return get_comments_number( $object['id'] );
	}

	/**
	 * Get product gallery
	 */
	function get_product_gallery( $object ) {
		$product = new \WC_Product( $object['id'] );
		$attachment_ids = $product->get_gallery_image_ids();

		if( !empty( $attachment_ids ) ) {

			$html = '<div class="wpbh-product-gallery">';

			foreach( $attachment_ids as $attachment_id ) {
				$html .= '<div class="wpbh-product-slide"><a href="' . get_permalink( $object['id'] ) . '"><img src="' . wp_get_attachment_image_url( $attachment_id, 'medium' ) . '" alt=""></a></div>';
			}

			return $html . '</div>';
		}

		return '';

	}

	/**
	 * Get product price
	 */
	function get_product_price( $object ) {
		$product = new \WC_Product( $object['id'] );
		return $product->get_price_html();
	}

	/**
	 * Get product rating
	 */
	function get_product_rating( $object ) {
		$product = new \WC_Product( $object['id'] );
		$average = $product->get_average_rating();
		return $average ? wc_get_rating_html( $average ) : ''; 
	}

	/**
	 * Check if product on sale
	 */
	function get_product_sale_status( $object ) {
		$product = new \WC_Product( $object['id'] );
		return $product->is_on_sale();
	}

	/**
	 * Get add to cart button
	 */
	function get_product_add_to_cart_btn( $object ) {
		$product = new \WC_Product( $object['id'] );
		return sprintf( '<a href="%s" data-quantity="%d" class="%s" data-product_id="%s">%s</a>',
			esc_url( $product->add_to_cart_url() ),
			1,
			esc_attr( 'add_to_cart_button ajax_add_to_cart' ),
			esc_attr( $object['id'] ),
			esc_html( $product->single_add_to_cart_text() )
		);

	}

}

?>