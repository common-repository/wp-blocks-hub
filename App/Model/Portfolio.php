<?php

namespace WPBlocksHub\Model;
use WPBlocksHub\Helper\Utils;
use WPBlocksHub\Helper\Settings;

/**
 * Portfolio model
 *
 */
class Portfolio extends Database {
	

	/**
	 * Register custom post type
	 */
	function register_post_type() {

		$caption = Settings::get_option( 'portfolio_caption' );
		$slug = Settings::get_option( 'portfolio_slug' );

		register_post_type( 'portfolio_post',
			[
				'label'             => $caption,
				'description'       => '',
				'show_in_rest' 			=> true,
				'public'            => true,
				'show_ui'           => true,
				'show_in_menu'      => true,
				'show_in_nav_menus' => true,
				'menu_icon'					=> 'dashicons-portfolio',
				'capability_type'   => 'post',
				'hierarchical'      => false,
				'supports'          => [ 'title', 'editor',  'excerpt', 'custom-fields', 'thumbnail' ],
				'rewrite'           => [
					'slug' => $slug
				],
				'has_archive'       => true,
				'query_var'         => true,
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
					'name'               => $caption,
					'singular_name'      => __( 'Post', 'wp-blocks-hub' ),
					'menu_name'          => $caption,
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
	 * Register post meta
	 */
	function register_post_meta() {

    register_post_meta( 'portfolio_post', WPBH()->Config[ 'prefix'] . '_client_name', [
			'show_in_rest' => true,
			'single' => true,
			'type' => 'string',
		]);

    register_post_meta( 'portfolio_post', WPBH()->Config[ 'prefix'] . '_project_url', [
			'show_in_rest' => true,
			'single' => true,
			'type' => 'string',
		]);

	}

	/**
	 * Register taxonomies
	 */
	function register_taxonomies() {

		$disable_cat = Utils::bool( Settings::get_option( 'disable_portfolio_cat' ) );

		register_taxonomy( 'portfolio_cat',
			'portfolio_post',
			[
				'hierarchical'      => true,
				'show_in_rest' 			=> true,
				'rest_base' 				=> 'wpbh_portfolio_cats',
				'show_ui'           => true,
				'query_var'         => true,
				'show_in_nav_menus' => true,
				'rewrite'           => $disable_cat ? false : [
					'slug' => Settings::get_option( 'portfolio_cat_slug' )
				],
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

		$disable_tag = Utils::bool( Settings::get_option( 'disable_portfolio_tag' ) );

		register_taxonomy( 'portfolio_tag',
			'portfolio_post',
			[
				'hierarchical'      => false,
				'show_ui'           => true,
				'query_var'         => true,
				'show_in_nav_menus' => true,
				'rewrite'           => $disable_tag ? false : [
					'slug' => Settings::get_option( 'portfolio_tag_slug' )
				],
				'show_admin_column' => true,
				'labels'            => [
					'name'          => _x( 'Tags', 'taxonomy general name','wp-blocks-hub' ),
					'singular_name' => _x( 'Tag', 'taxonomy singular name','wp-blocks-hub' ),
					'search_items'  => __( 'Search in tags','wp-blocks-hub' ),
					'all_items'     => __( 'All tags','wp-blocks-hub' ),
					'edit_item'     => __( 'Edit tag','wp-blocks-hub' ),
					'update_item'   => __( 'Update tag','wp-blocks-hub' ),
					'add_new_item'  => __( 'Add new tag','wp-blocks-hub' ),
					'new_item_name' => __( 'New tag','wp-blocks-hub' ),
					'menu_name'     => __( 'Tags','wp-blocks-hub' )
				]
			]
		);

	}

	/**
	 * Install demo posts
	 */
	function insert_demo_posts() {

		if( ! function_exists( 'wp_generate_attachment_metadata') ) {
			require_once( ABSPATH . 'wp-admin/includes/image.php' );			
		}

		$taxonomy = Settings::get_option( 'portfolio_cat_slug' );

		$terms = [
			'branding' => __( 'Branding', 'wp-blocks-hub'),
			'presentation' => __( 'Presentation', 'wp-blocks-hub'),
			'pring' => __( 'Print', 'wp-blocks-hub'),
			'web-design' => __( 'Web design', 'wp-blocks-hub'),
			'interface' => __( 'Interface', 'wp-blocks-hub'),
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

		$thumbs = $imgs = [];

		for( $i=1; $i<8; $i++ ) {
			$thumbs[] = 'portfolio' . $i . '.jpg';
		}

		$thumbs_ids = Utils::add_images_to_media( $thumbs, WPBH()->Config['plugin_path'] . '/assets/images/demo/portfolio/' );

		foreach( $thumbs_ids as $t_id ) {
			$imgs[] = (object)[
				'id' => $t_id,
				'url' => wp_get_attachment_image_url( $t_id, 'thumbnail')
			];
		}

		$demo_content = [
			'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent vitae suscipit quam. Etiam ut libero lectus. Nam ac ante sit amet neque semper sollicitudin.',
			'Curabitur sit amet risus tellus. Vestibulum condimentum felis id arcu blandit, ultrices congue diam volutpat.',
			'Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;',
		];

		$demo_clients = [
			__( 'Jonh Smith', 'wp-blocks-hub'),
			__( 'Jane Doe', 'wp-blocks-hub'),
		];

		for( $i=1; $i<16; $i++ ) {

			$post_id = wp_insert_post([
				'post_type' => 'portfolio_post',
				'post_status' => 'publish',
				'post_title' => __( 'Sample portfolio', 'wp-blocks-hub') . ' ' . $i,
				'post_content' => $demo_content[ array_rand( $demo_content )]
			]);

			wp_set_object_terms( $post_id, $terms_ids[ array_rand( $terms_ids ) ], $taxonomy, true );

			set_post_thumbnail( $post_id, $thumbs_ids[ array_rand( $thumbs_ids ) ] );

			Settings::update_post_option( $post_id, 'client_name', $demo_clients[ array_rand( $demo_clients ) ]);
			Settings::update_post_option( $post_id, 'project_url', 'https://wpblockshub.com');

			shuffle( $imgs );
			$imgs = array_slice( $imgs, 0, 3);
			
			Settings::update_post_option( $post_id, 'gallery', json_encode( $imgs ) );

		}

	}

	/**
	 * Get posts
	 */
	function get_posts( $args ) {

		$defaults = [
			'post_type' => 'portfolio_post',
			'post_status' => 'publish'
		];

		$query_args = array_merge( $defaults, $args );

		return new \WP_Query( $query_args );

	}

}

?>