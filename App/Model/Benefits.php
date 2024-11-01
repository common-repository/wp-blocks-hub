<?php

namespace WPBlocksHub\Model;
use WPBlocksHub\Helper\Settings;
use WPBlocksHub\Helper\Utils;

/**
 * Benefits model
 *
 */
class Benefits extends Database {
	
	/**
	 * Register custom post type
	 */
	function register_post_type() {

		register_post_type( 'benefit_post',
			[
				'label'             => __( 'Benefits', 'wp-blocks-hub' ),
				'description'       => '',
				'show_in_rest' 			=> true,
				'public'            => false,
				'show_ui'           => true,
				'show_in_menu'      => true,
				'show_in_nav_menus' => false,
				'menu_icon'					=> 'dashicons-awards',
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
					'name'               => __( 'Benefits', 'wp-blocks-hub' ),
					'singular_name'      => __( 'Benefit', 'wp-blocks-hub' ),
					'menu_name'          => __( 'Benefits', 'wp-blocks-hub' ),
					'add_new'            => __( 'Add Benefit', 'wp-blocks-hub' ),
					'add_new_item'       => __( 'Add New Benefit', 'wp-blocks-hub' ),
					'all_items'          => __( 'All Benefits', 'wp-blocks-hub' ),
					'edit_item'          => __( 'Edit Benefit', 'wp-blocks-hub' ),
					'new_item'           => __( 'New Benefit', 'wp-blocks-hub' ),
					'view_item'          => __( 'View Benefit', 'wp-blocks-hub' ),
					'search_items'       => __( 'Search Benefits', 'wp-blocks-hub' ),
					'not_found'          => __( 'No Benefits Found', 'wp-blocks-hub' ),
					'not_found_in_trash' => __( 'No Benefits Found in Trash', 'wp-blocks-hub' ),
					'parent_item_colon'  => __( 'Parent Benefit:', 'wp-blocks-hub' )
				]
			]
		);

	}

	/**
	 * Register post meta
	 */
	function register_post_meta() {

    register_post_meta( 'benefit_post', WPBH()->Config[ 'prefix'] . '_link', [
			'show_in_rest' => true,
			'single' => true,
			'type' => 'string',
		]);

    register_post_meta( 'benefit_post', WPBH()->Config[ 'prefix'] . '_icon_color', [
			'show_in_rest' => true,
			'single' => true,
			'type' => 'string',
		]);

	}

	/**
	 * Register taxonomies
	 */
	function register_taxonomies() {

		register_taxonomy( 'benefit_cat',
			'benefit_post',
			[
				'hierarchical'      => true,
				'show_in_rest' 			=> true,
				'rest_base' 				=> 'wpbh_benefits_cats',
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
			'post_type' => 'benefit_post',
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

		$taxonomy = 'benefit_cat';

		$terms = [
			'why-choose-us' => __( 'Why Choose Us', 'wp-blocks-hub'),
			'company-certifications' => __( 'Company Certifications', 'wp-blocks-hub'),
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

		$benefit_data = [
			[
				'icon' => 'shipping-and-delivery.svg',
				'color' => '#00bff3',
				'title' => 'Experienced',
				'content' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod.'
			],
			[
				'icon' => 'space-invaders.svg',
				'color' => '#3ab54b',
				'title' => 'Professional',
				'content' => 'Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius notare quam.'
			],
			[
				'icon' => 'space-robot.svg',
				'color' => '#ff9e11',
				'title' => 'Vibrant',
				'content' => 'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.'
			],
			[
				'icon' => 'startup.svg',
				'color' => '#1cbbb4',
				'title' => 'Reliably',
				'content' => 'Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius notare quam.'
			],
			[
				'icon' => 'undertake.svg',
				'color' => '#f04e4e',
				'title' => 'Easy to use',
				'content' => 'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.'
			],
			[
				'icon' => 'miscellaneous.svg',
				'color' => '#6739b6',
				'title' => 'Variations',
				'content' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod.'
			],
		];

		$thumbs = [];

		foreach( $benefit_data as $benefit ) {
			$thumbs[] = $benefit['icon'];
		}

		$thumbs_ids = Utils::add_images_to_media( $thumbs, WPBH()->Config['plugin_path'] . '/assets/images/demo/benefits/' );

		$i = 0; foreach( $benefit_data as $benefit ) {

			$post_id = wp_insert_post([
				'post_type' => 'benefit_post',
				'post_status' => 'publish',
				'post_title' => $benefit['title'],
				'post_content' => $benefit['content'],
			]);

			$cat = $i<=7 ? $terms_ids[0] : $terms_ids[1];
			wp_set_object_terms( $post_id, $cat, $taxonomy, true );

			set_post_thumbnail( $post_id, $thumbs_ids[ $i ] );

			Settings::update_post_option( $post_id, 'link', 'https://wpblockshub.com/');
			Settings::update_post_option( $post_id, 'icon_color', $benefit['color']);

			$i++;
				
		}

	}

}

?>