<?php

namespace WPBlocksHub\Model;
use WPBlocksHub\Helper\Settings;
use WPBlocksHub\Helper\Utils;

/**
 * People model
 *
 */
class People extends Database {
	
	/**
	 * Register custom post type
	 */
	function register_post_type() {

		register_post_type( 'person_post',
			[
				'label'             => __( 'People', 'wp-blocks-hub' ),
				'description'       => '',
				'show_in_rest' 			=> true,
				'public'            => false,
				'show_ui'           => true,
				'show_in_menu'      => true,
				'show_in_nav_menus' => false,
				'menu_icon'					=> 'dashicons-businessman',
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
					'name'               => __( 'People', 'wp-blocks-hub' ),
					'singular_name'      => __( 'Person', 'wp-blocks-hub' ),
					'menu_name'          => __( 'People', 'wp-blocks-hub' ),
					'add_new'            => __( 'Add Person', 'wp-blocks-hub' ),
					'add_new_item'       => __( 'Add New Person', 'wp-blocks-hub' ),
					'all_items'          => __( 'All People', 'wp-blocks-hub' ),
					'edit_item'          => __( 'Edit Person', 'wp-blocks-hub' ),
					'new_item'           => __( 'New Person', 'wp-blocks-hub' ),
					'view_item'          => __( 'View Person', 'wp-blocks-hub' ),
					'search_items'       => __( 'Search People', 'wp-blocks-hub' ),
					'not_found'          => __( 'No People Found', 'wp-blocks-hub' ),
					'not_found_in_trash' => __( 'No People Found in Trash', 'wp-blocks-hub' ),
					'parent_item_colon'  => __( 'Parent Person:', 'wp-blocks-hub' )
				]
			]
		);

	}

	/**
	 * Register post meta
	 */
	function register_post_meta() {

    register_post_meta( 'person_post', WPBH()->Config[ 'prefix'] . '_position', [
			'show_in_rest' => true,
			'single' => true,
			'type' => 'string',
		]);

		foreach( WPBH()->Config['social_profiles'] as $name => $title ) {

			register_post_meta( 'person_post', WPBH()->Config[ 'prefix'] . '_' . $name, [
				'show_in_rest' => true,
				'single' => true,
				'type' => 'string',
			]);

		}

	}

	/**
	 * Register taxonomies
	 */
	function register_taxonomies() {

		register_taxonomy( 'person_cat',
			'person_post',
			[
				'hierarchical'      => true,
				'show_in_rest' 			=> true,
				'rest_base' 				=> 'wpbh_people_cats',
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
	 * Get posts
	 */
	function get_posts( $args ) {

		$defaults = [
			'post_type' => 'person_post',
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

		$taxonomy = 'person_cat';

		$terms = [
			'headquarters' => __( 'Headquarters', 'wp-blocks-hub'),
			'team' => __( 'Team', 'wp-blocks-hub'),
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
			'team1.jpg',
			'team2.jpg',
			'team3.jpg',
			'team4.jpg',
			'team_square1.jpg',
			'team_square2.jpg',
			'team_square3.jpg',
		];

		$thumbs_ids = Utils::add_images_to_media( $thumbs, WPBH()->Config['plugin_path'] . '/assets/images/demo/people/' );

		$demo_content = [
			'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
			'Curabitur sit amet risus tellus vestibulum condimentum felis.',
			'Class aptent taciti sociosqu ad litora torquent per conubia.',
		];

		$demo_authors = [
			__( 'Jonh Doe', 'wp-blocks-hub'),
			__( 'Adam Smith', 'wp-blocks-hub'),
			__( 'Jane Doe', 'wp-blocks-hub'),
		];

		$demo_positions = [
			__( 'Senior Web Developer', 'wp-blocks-hub'),
			__( 'Designer', 'wp-blocks-hub'),
			__( 'Finance Officer ', 'wp-blocks-hub'),
		];

		$demo_positions_head = [
			__( 'Chief Executive Officer', 'wp-blocks-hub'),
			__( 'Chief Design Officer', 'wp-blocks-hub'),
			__( 'Chief Commercial Officer', 'wp-blocks-hub'),
		];

		for( $i=0; $i<15; $i++ ) {

			$first_part = $i<=7;

			$post_id = wp_insert_post([
				'post_type' => 'person_post',
				'post_status' => 'publish',
				'post_title' => $demo_authors[ array_rand( $demo_authors )],
				'post_content' => $demo_content[ array_rand( $demo_content )]
			]);

			$cat = $first_part ? $terms_ids[0] : $terms_ids[1];
			wp_set_object_terms( $post_id, $cat, $taxonomy, true );

			if( $first_part ) {
				$head_thumbs = array_slice( $thumbs_ids, 0, 4 );
				set_post_thumbnail( $post_id, $head_thumbs[ array_rand( $head_thumbs ) ] );
				Settings::update_post_option( $post_id, 'position', $demo_positions_head[ array_rand( $demo_positions_head ) ]);
			} else {
				$team_thumbs = array_slice( $thumbs_ids, 4 );
				set_post_thumbnail( $post_id, $team_thumbs[ array_rand( $team_thumbs ) ] );
				Settings::update_post_option( $post_id, 'position', $demo_positions[ array_rand( $demo_positions ) ]);
			}

			Settings::update_post_option( $post_id, 'facebook_url', 'https://facebook.com');
			Settings::update_post_option( $post_id, 'twitter_url', 'https://twitter.com');
			Settings::update_post_option( $post_id, 'linkedin_url', 'https://linkedin.com');

		}

	}

}

?>