<?php

namespace WPBlocksHub\Model;
use WPBlocksHub\Helper\Settings;
use WPBlocksHub\Helper\Utils;

/**
 * Testimonial model
 *
 */
class Testimonial extends Database {
	
	/**
	 * Register custom post type
	 */
	function register_post_type() {

		register_post_type( 'testimonial_post',
			[
				'label'             => __( 'Testimonials', 'wp-blocks-hub' ),
				'description'       => '',
				'show_in_rest' 			=> true,
				'public'            => false,
				'show_ui'           => true,
				'show_in_menu'      => true,
				'show_in_nav_menus' => false,
				'menu_icon'					=> 'dashicons-format-chat',
				'capability_type'   => 'post',
				'hierarchical'      => false,
				'supports'          => [ 'title', 'editor', 'custom-fields', 'thumbnail' ],
				'rewrite'           => false,
				'has_archive'       => false,
				'query_var'         => false,
				'menu_position'     => 5,
				'capabilities'      => [
					'publish_posts'       => 'edit_pages',
					'edit_posts'          => 'edit_pages',
					'edit_others_posts'   => 'edit_pages',
					'delete_posts'        => 'edit_pages',
					'delete_others_posts' => 'edit_pages',
					'read_private_posts'  => 'edit_pages',
					'edit_post'           => 'edit_pages',
					'delete_post'         => 'edit_pages',
					'read_post'           => 'edit_pages',
				],
				'labels'            => [
					'name'               => __( 'Testimonials', 'wp-blocks-hub' ),
					'singular_name'      => __( 'Post', 'wp-blocks-hub' ),
					'menu_name'          => __( 'Testimonials', 'wp-blocks-hub' ),
					'add_new'            => __( 'Add Post', 'wp-blocks-hub' ),
					'add_new_item'       => __( 'Add New Post', 'wp-blocks-hub' ),
					'all_items'          => __( 'All Posts', 'wp-blocks-hub' ),
					'edit_item'          => __( 'Edit Post', 'wp-blocks-hub' ),
					'new_item'           => __( 'New Post', 'wp-blocks-hub' ),
					'view_item'          => __( 'View Post', 'wp-blocks-hub' ),
					'search_items'       => __( 'Search Posts', 'wp-blocks-hub' ),
					'not_found'          => __( 'No Posts Found', 'wp-blocks-hub' ),
					'not_found_in_trash' => __( 'No Posts Found in Trash', 'wp-blocks-hub' ),
					'parent_item_colon'  => __( 'Parent Post:', 'wp-blocks-hub' )
				]
			]
		);

	}

	/**
	 * Register taxonomies
	 */
	function register_taxonomies() {

		register_taxonomy( 'testimonial_cat',
			'testimonial_post',
			[
				'hierarchical'      => true,
				'show_in_rest' 			=> true,
				'rest_base' 				=> 'wpbh_testimonials_cats',
				'show_ui'           => true,
				'query_var'         => false,
				'show_in_nav_menus' => false,
				'rewrite'           => false,
				'show_admin_column' => true,
				'labels'            => [
					'name'          => _x( 'Categories', 'taxonomy general name','wp-blocks-hub' ),
					'singular_name' => _x( 'Category', 'taxonomy singular name','wp-blocks-hub' ),
					'search_items'  => __( 'Search in categories','wp-blocks-hub' ),
					'all_items'     => __( 'All categories','wp-blocks-hub' ),
					'edit_item'     => __( 'Edit category','wp-blocks-hub' ),
					'update_item'   => __( 'Update category','wp-blocks-hub' ),
					'add_new_item'  => __( 'Add new category','wp-blocks-hub' ),
					'new_item_name' => __( 'New category','wp-blocks-hub' ),
					'menu_name'     => __( 'Categories','wp-blocks-hub' )
				]
			]
		);

	}

	/**
	 * Register post meta
	 */
	function register_post_meta() {

    register_post_meta( 'testimonial_post', WPBH()->Config[ 'prefix'] . '_author_name', [
			'show_in_rest' => true,
			'single' => true,
			'type' => 'string',
		]);

    register_post_meta( 'testimonial_post', WPBH()->Config[ 'prefix'] . '_author_position', [
			'show_in_rest' => true,
			'single' => true,
			'type' => 'string',
		]);

	}

	/**
	 * Get posts
	 */
	function get_posts( $args ) {

		$defaults = [
			'post_type' => 'testimonial_post',
			'post_status' => 'publish'
		];

		$query_args = array_merge( $defaults, $args );

		return new \WP_Query( $query_args );

	}

	/**
	 * Install demo posts
	 */
	function insert_demo_posts() {
		
		if( ! function_exists( 'wp_generate_attachment_metadata') ) {
			require_once( ABSPATH . 'wp-admin/includes/image.php' );			
		}

		$taxonomy = 'testimonial_cat';

		$terms = [
			'client-testimonials' => __( 'Client testimonials', 'wp-blocks-hub'),
			'reviews' => __( 'Reviews', 'wp-blocks-hub'),
		];

		$terms_ids = $thumbs_ids = [];

		foreach( $terms as $slug => $title ) {
			if( ! term_exists( $slug, $taxonomy ) ) {
				$terms_ids[] = wp_insert_term(
					$title,
					$taxonomy, 
					[
						'description'=> '',
						'slug' => $slug,
					]
				);
			} else {
				$term =  get_term_by( 'slug', $slug, $taxonomy );
				$terms_ids[] = $term->term_id;
			}
		}

		$thumbs = [
			'testimonial1.jpg',
			'testimonial2.jpg',
			'testimonial3.jpg',
			'testimonial4.jpg'
		];

		$thumbs_ids = Utils::add_images_to_media( $thumbs, WPBH()->Config['plugin_path'] . '/assets/images/demo/testimonials/' );

		$demo_content = [
			'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent vitae suscipit quam. Etiam ut libero lectus. Nam ac ante sit amet neque semper sollicitudin.',
			'Curabitur sit amet risus tellus. Vestibulum condimentum felis id arcu blandit, ultrices congue diam volutpat.',
			'Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;',
		];

		$demo_authors = [
			__( 'Jonh Doe', 'wp-blocks-hub'),
			__( 'Jane Doe', 'wp-blocks-hub'),
		];

		$demo_positions = [
			__( 'CEO at Company.com', 'wp-blocks-hub'),
			__( 'Web Designer', 'wp-blocks-hub'),
		];

		for( $i=0; $i<15; $i++ ) {

			$post_id = wp_insert_post([
				'post_type' => 'testimonial_post',
				'post_status' => 'publish',
				'post_title' => __( 'Sample testimonial', 'wp-blocks-hub') . ' ' . $i,
				'post_content' => $demo_content[ array_rand( $demo_content )]
			]);

			$cat = $i<=7 ? $terms_ids[0] : $terms_ids[1];
			wp_set_object_terms( $post_id, $cat, $taxonomy, true );

			set_post_thumbnail( $post_id, $thumbs_ids[ array_rand( $thumbs_ids ) ] );

			Settings::update_post_option( $post_id, 'author_name', $demo_authors[ array_rand( $demo_authors ) ]);
			Settings::update_post_option( $post_id, 'author_position', $demo_positions[ array_rand( $demo_positions ) ]);

		}

	}

}

?>