<?php
namespace WPBlocksHub\Controller;
use WPBlocksHub\Helper\Settings;
use WPBlocksHub\Helper\Media;
use WPBlocksHub\Helper\Utils;

/**
 * Custom Post Types Controller
 **/
class CustomPostTypes {

	private $_prefix;

	/**
	 * Constructor
	 **/
	function __construct() {

		$this->_prefix = WPBH()->Config['prefix'];

		// register custom post types
		add_action( 'init', [ $this, 'register_custom_post_types'], 5 );

		// disable gutenberg for custom post types
		add_filter( 'use_block_editor_for_post_type', [ $this, 'disable_gutenberg_for_cpt'], 10, 2);

		// add custom metaboxes
		add_action( 'add_meta_boxes', [ $this, 'add_metaboxes' ] );

		// save custom data when you edit a post
		add_action( 'save_post', [ $this, 'save_metadata' ] );

		// add custom columns to posts
		add_filter( 'manage_portfolio_post_posts_columns', [ $this, 'add_list_columns' ], 10);
		add_action( 'manage_portfolio_post_posts_custom_column', [ $this, 'print_list_columns' ], 10, 2);
		add_filter( 'manage_testimonial_post_posts_columns', [ $this, 'add_list_columns' ], 10);
		add_action( 'manage_testimonial_post_posts_custom_column', [ $this, 'print_list_columns' ], 10, 2);
		add_filter( 'manage_benefit_post_posts_columns', [ $this, 'add_list_columns' ], 10);
		add_action( 'manage_benefit_post_posts_custom_column', [ $this, 'print_list_columns' ], 10, 2);
		add_filter( 'manage_person_post_posts_columns', [ $this, 'add_list_columns' ], 10);
		add_action( 'manage_person_post_posts_custom_column', [ $this, 'print_list_columns' ], 10, 2);

		// custom filters
		add_action( 'restrict_manage_posts', [ $this, 'add_custom_filters'] );
		add_filter( 'parse_query', [ $this, 'filter_posts_query'] );

		// custom taxonomies order
		add_action( 'wp_ajax_' . WPBH()->Config['prefix'] . '_order_taxonomy', [ $this, 'ajax_taxonomy_order' ] );
		add_filter( 'get_terms_orderby', [ $this, 'get_terms_orderby' ], 10, 3 );
		add_filter( 'wp_get_object_terms', [ $this, 'get_object_terms' ], 10, 3 );
		add_filter( 'get_terms', [ $this, 'get_object_terms' ], 10, 3 );

		// toggle featured post status
		add_action( 'wp_ajax_' . WPBH()->Config['prefix'] . '_toggle_featured_post', [ $this, 'toggle_featured_post_status' ] );

	}

	/**
	 * Register custom post types
	 */
	function register_custom_post_types() {

		// portfolio CPT
		if( false == Utils::bool( Settings::get_option( 'disable_portfolio') ) ) {

			WPBH()->Model->Portfolio->register_post_type();
			WPBH()->Model->Portfolio->register_post_meta();
			WPBH()->Model->Portfolio->register_taxonomies();

		}

		// testimonials CPT
		if( false == Utils::bool( Settings::get_option( 'disable_testimonials') ) ) {

			WPBH()->Model->Testimonial->register_post_type();
			WPBH()->Model->Testimonial->register_post_meta();
			WPBH()->Model->Testimonial->register_taxonomies();

		}

		// people CPT
		if( false == Utils::bool( Settings::get_option( 'disable_people') ) ) {

			WPBH()->Model->People->register_post_type();
			WPBH()->Model->People->register_post_meta();
			WPBH()->Model->People->register_taxonomies();

		}

		// benefits CPT
		if( false == Utils::bool( Settings::get_option( 'disable_benefits') ) ) {

			WPBH()->Model->Benefits->register_post_type();
			WPBH()->Model->Benefits->register_post_meta();
			WPBH()->Model->Benefits->register_taxonomies();

		}

	}

	/**
	 * Disable gutenberg for custom post types
	 */
	function disable_gutenberg_for_cpt( $current_status, $post_type ) {

		if( in_array( $post_type, ['testimonial_post', 'person_post', 'benefit_post', 'portfolio_post'] ) ) {
			return false;
		} 

		return $current_status;

	}

	/**
	 * Add custom metaboxes
	 */
	function add_metaboxes() {

		add_meta_box(
			'portfolio_details',
			__( 'Details', 'wp-blocks-hub' ),
			[ $this, 'metabox_portfolio_details' ],
			'portfolio_post',
			'normal',
			'high'
		);

		add_meta_box(
			'testimonial_details',
			__( 'Details', 'wp-blocks-hub' ),
			[ $this, 'metabox_testimonial_details' ],
			'testimonial_post',
			'normal',
			'high'
		);

		add_meta_box(
			'person_details',
			__( 'Details', 'wp-blocks-hub' ),
			[ $this, 'metabox_person_details' ],
			'person_post',
			'normal',
			'high'
		);

		add_meta_box(
			'person_social_profiles',
			__( 'Social profiles', 'wp-blocks-hub' ),
			[ $this, 'metabox_person_social_profiles' ],
			'person_post',
			'normal',
			'high'
		);

		add_meta_box(
			'benefit_details',
			__( 'Details', 'wp-blocks-hub' ),
			[ $this, 'metabox_benefit_details' ],
			'benefit_post',
			'normal',
			'high'
		);

	}

	/**
	 * Portfolio details metabox
	 */
	function metabox_portfolio_details( $post ) {

		WPBH()->View->load( 'App/View/Backend/CPT/Metabox_Portfolio_Details', [ 'post' => $post ] );

	}

	/**
	 * Testionial details metabox
	 */
	function metabox_testimonial_details( $post ) {

		WPBH()->View->load( 'App/View/Backend/CPT/Metabox_Testimonial_Details', [ 'post' => $post ] );

	}

	/**
	 * People details metabox
	 */
	function metabox_person_details( $post ) {

		WPBH()->View->load( 'App/View/Backend/CPT/Metabox_Person_Details', [ 'post' => $post ] );

	}

	/**
	 * Person social profiles
	 */
	function metabox_person_social_profiles( $post ) {

		WPBH()->View->load( 'App/View/Backend/CPT/Metabox_Person_Social_Profiles', [ 'post' => $post ] );

	}

	/**
	 * Benefits details metabox
	 */
	function metabox_benefit_details( $post ) {

		WPBH()->View->load( 'App/View/Backend/CPT/Metabox_Benefit_Details', [ 'post' => $post ] );

	}

	/**
	 * Save custom metadata
	 */
	function save_metadata( $post_id ) {

		$nonce_name = $this->_prefix . '_metabox_nonce';

		// Check if our nonce is set.
		if ( ! isset( $_POST[ $nonce_name ] ) ) {
			return $post_id;
		}

		$nonce = $_POST[ $nonce_name ];

		if ( ! wp_verify_nonce( $nonce, $nonce_name ) ) {
			return $post_id;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		$can_edit_post = current_user_can( 'edit_post', $post_id );

		if ( $_POST['post_type'] == 'portfolio_post' && $can_edit_post ) {

			Settings::update_post_option( $post_id, 'gallery', sanitize_text_field( $_POST['_' . $this->_prefix . '_gallery'] ) );
			Settings::update_post_option( $post_id, 'client_name', sanitize_text_field( $_POST['_' . $this->_prefix . '_client_name'] ) );
			Settings::update_post_option( $post_id, 'project_url', sanitize_text_field( $_POST['_' . $this->_prefix . '_project_url'] ) );			

		} else if( $_POST['post_type'] == 'testimonial_post' && $can_edit_post ) {

			Settings::update_post_option( $post_id, 'author_name', sanitize_text_field( $_POST['_' . $this->_prefix . '_author_name'] ) );
			Settings::update_post_option( $post_id, 'author_position', sanitize_text_field( $_POST['_' . $this->_prefix . '_author_position'] ) );

		} else if( $_POST['post_type'] == 'benefit_post' && $can_edit_post ) {

			Settings::update_post_option( $post_id, 'link', sanitize_text_field( $_POST['_' . $this->_prefix . '_link'] ) );
			Settings::update_post_option( $post_id, 'icon_color', sanitize_text_field( $_POST['_' . $this->_prefix . '_icon_color'] ) );

		} else if( $_POST['post_type'] == 'person_post' && $can_edit_post ) {

			Settings::update_post_option( $post_id, 'position', sanitize_text_field( $_POST['_' . $this->_prefix . '_position'] ) );

			foreach( WPBH()->Config['social_profiles'] as $name => $title ) {
				Settings::update_post_option( $post_id, $name, sanitize_text_field( $_POST['_' . $this->_prefix . '_' . $name ] ) );
			}

		} else {

			return $post_id;

		}

	}

	/**
	 * Add custom columns to admin UI
	 */
	function add_list_columns( $columns ) {

		// post thumbnail
		$img_col = [ 'thumbnail' => __( 'Cover Image', 'wp-blocks-hub' ) ];
		$columns = array_slice( $columns, 0, 1, true ) + $img_col + array_slice( $columns, 1, NULL, true );

		// ID
		$id_col = [ 'public_id' => __( 'ID', 'wp-blocks-hub' ) ];
		$columns = array_slice( $columns, 0, 3, true ) + $id_col + array_slice( $columns, 3, NULL, true );

		// featured post
		$featured_col = [ 'featured' => __( 'Featured', 'wp-blocks-hub' ) ];
		$columns = array_slice( $columns, 0, 4, true ) + $featured_col + array_slice( $columns, 4, NULL, true );

		return $columns;

	}

	/**
	 * Print custom columns
	 */
	function print_list_columns( $column_name, $post_ID ) {

		$view_data = [ 'post_id' => $post_ID ];

		switch( $column_name ) {

			case 'thumbnail':
				WPBH()->View->load( 'App/View/Backend/CPT/Col_Thumb', $view_data );
			break;

			case 'public_id':
				WPBH()->View->load( 'App/View/Backend/CPT/Col_Id', $view_data );
			break;

			case 'featured':
				WPBH()->View->load( 'App/View/Backend/CPT/Col_Featured', $view_data );
			break;

		}


	}

	/**
	 * Add custom filters
	 */
	function add_custom_filters() {
		global $typenow, $wp_query;

		if( in_array( $typenow, ['portfolio_post', 'testimonial_post', 'benefit_post', 'person_post'] ) ) {

			$view_data = [];

			if( isset( $_GET['public_id'] ) ) {
				$view_data['public_id'] = absint( $_GET['public_id'] );
			}

			if( isset( $_GET['featured'] ) && in_array( $_GET['featured'], ['yes', 'no'] ) ) {
				$view_data['featured'] = $_GET['featured'];
			}

			WPBH()->View->load( 'App/View/Backend/CPT/Filters', $view_data );

		}

	}

	/**
	 * Filter posts query
	 */
	function filter_posts_query( $query ) {
		global $pagenow;

		if( is_admin() && ( isset( $_GET['post_type'] ) && in_array( $_GET['post_type'], ['portfolio_post', 'testimonial_post', 'benefit_post', 'person_post'] ) ) && $pagenow == 'edit.php' ) {

			$prefix = '_' . $this->_prefix . '_';

			// filter by public ID
			if( isset( $_GET['public_id'] ) && $_GET['public_id'] <> '' ) {
				$query->query_vars['p'] = absint( $_GET['public_id'] );
			}

			// filter by Featured
			if( isset( $_GET['featured'] ) && in_array( $_GET['featured'], ['yes', 'no'] ) ) {

				if( $_GET['featured'] == 'yes' ) {

					$query->query_vars['meta_key'] = $prefix . 'featured';
					$query->query_vars['meta_value'] = 'yes';
					$query->query_vars['meta_compare'] = '=';

				} else if( $_GET['featured'] == 'no' ) {

					$meta_query = [
						'relation' => 'OR',
						[
							'meta_key' => $prefix . 'featured',
							'meta_value' => 'no',
							'meta_compare' => '='
						],
						[
							'meta_key' => $prefix . 'featured',
							'meta_compare' => 'NOT EXISTS'
						],
					];
	
					$query->query_vars['meta_query'][] = $meta_query;

				}

			}

		}

	} 

  /**
   * Custom taxonomy order using AJAX & drag-n-drop
   */
  function ajax_taxonomy_order() {

    if( current_user_can( 'edit_posts') ) {

      parse_str( $_POST['order'], $data );

      if( ! is_array( $data ) ) {

        wp_die( __( 'Incorrect post data', 'wp-blocks-hub' ) );

      } else {

        foreach( $data as $key => $values ) {

          foreach( $values as $position => $id ) {

            WPBH()->Model->Taxonomy->set_term_order_by_term_id( absint( $position ) + 1, absint( $id ) );

          }

        }

      }

    } else {

			_e( 'You do not have permissions to do that', 'wp-blocks-hub');

		}

		exit;

  }

  /**
   * Automatically re-order terms
   */
	function get_terms_orderby( $orderby, $args ) {

		if ( is_admin() ) {
      return $orderby;
    }
		
		$tags = WPBH()->Config['taxonomies_list'];

		if( ! isset( $args['taxonomy'] ) ) {
      return $orderby;
    }
		
    $taxonomy = $args['taxonomy'];
    
		if ( false == in_array( $taxonomy, $tags ) ) {
      return $orderby;
    }
		
		$orderby = 't.term_order';
    return $orderby;
    
	}

  /**
   * Automatically re-order terms object
   */
	function get_object_terms( $terms ) {

		$tags = WPBH()->Config['taxonomies_list'];
		
		if ( is_admin() && isset( $_GET['orderby'] ) ) {
      return $terms;
    }
		
		foreach( $terms as $key => $term ) {

			if ( is_object( $term ) && isset( $term->taxonomy ) ) {

        $taxonomy = $term->taxonomy;
        if ( !in_array( $taxonomy, $tags ) ) {
          return $terms;
        } 

			} else {
				return $terms;
			}
		}
		
		usort( $terms, function( $a, $b ) {
      if ( $a->term_order ==  $b->term_order ) {
        return 0;
      } 
      return ( $a->term_order < $b->term_order ) ? -1 : 1;
    });

		return $terms;
	}

	/**
	 * Toggle featured post status
	 */
	function toggle_featured_post_status() {

		if( current_user_can( 'edit_posts') ) {

			$post_id = absint( $_POST['post_id'] );

			$new_status = Utils::bool( Settings::get_post_option( $post_id, 'featured' ) ) ? 'no' : 'yes';

			Settings::update_post_option( $post_id, 'featured', $new_status );

			echo $new_status;

		} else {

			_e( 'You do not have permissions to do that', 'wp-blocks-hub');

		}

		exit;

	}

}

?>