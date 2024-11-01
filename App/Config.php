<?php

  $plugin_dir_url = plugin_dir_url( WPBH_PLUGIN_FILE );
  $plugin_dir_path = wp_normalize_path( plugin_dir_path( WPBH_PLUGIN_FILE ) );

  $tmp_dir_path = wp_normalize_path( $plugin_dir_path . '/tmp' );
  $tmp_dir_url = $plugin_dir_url . '/tmp';
  $tmp_style_cache_dir_path = wp_normalize_path( $tmp_dir_path . '/style_cache' );
  $tmp_style_cache_dir_url = $tmp_dir_url . '/style_cache';
  $blocks_dir_path = wp_normalize_path( $plugin_dir_path . '/Blocks' );
  $blocks_dir_url = wp_normalize_path( $plugin_dir_url . '/Blocks' );

  return [
    'cache_time'                          => '07112019170737',
    'plugin_url'                          => $plugin_dir_url,
    'plugin_path'                         => $plugin_dir_path,
    'tmp_dir_path'                        => $tmp_dir_path,
    'tmp_dir_url'                         => $tmp_dir_url,
    'tmp_style_cache_dir_url'             => $tmp_style_cache_dir_url,
    'tmp_style_cache_dir_path'            => $tmp_style_cache_dir_path,
    'blocks_dir_path'                     => $blocks_dir_path,
    'blocks_dir_url'                      => $blocks_dir_url,
    'writable_dirs' => [
			$tmp_dir_path,
			$tmp_style_cache_dir_path,
			$blocks_dir_path
    ],
    'prefix' => 'wpbh',
    'google_fonts_list_url' => 'https://wpblockshub.com/static/google-fonts.json',
    'load_hub_data_url'     => 'https://wpblockshub.com/?hub_action=get_hub_data',
    'load_hub_blocks_url'   => 'https://wpblockshub.com/?hub_action=get_blocks',
    'check_updates_url'     => 'https://wpblockshub.com/?hub_action=check_updates',
    'download_block_url'    => 'https://wpblockshub.com/?hub_action=download_block',
    'pro_plugin_url'        => 'https://codecanyon.net/user/wpblockshub/portfolio',
    'taxonomies_list' => [
      'testimonial_cat',
      'portfolio_cat',
      'portfolio_tag',
      'benefit_cat',
      'person_cat',
    ],
    'default_options' => [

      /** API Settings */
      'google_maps_api_key'         => '',
      'twitter_consumer_key'        => '',
      'twitter_consumer_secret'     => '',
      'twitter_access_token'        => '',
      'twitter_access_token_secret' => '',

      /** CPT Settings */
      'disable_portfolio'     => 'no',
      'portfolio_caption'     => __( 'Portfolio', 'wp-blocks-hub' ),
      'portfolio_slug'        => 'portfolio',
      'portfolio_cat_slug'    => 'portfolio_cat',
      'portfolio_tag_slug'    => 'portfolio_tag',
      'portfolio_single_tpl'  => '01-full-width-slider.php',
      'portfolio_archive_tpl' => '01-masonry.php',
      'disable_portfolio_cat' => 'no',
      'disable_portfolio_tag' => 'yes',
      'disable_testimonials'  => 'no',
      'disable_people'        => 'no',
      'disable_benefits'      => 'no',

      /** Global fonts Settings */
      'mobile_breakpoint'     => 1024,

      'primary_font'          => '',
      'primary_font_custom'   => '',
      'secondary_font'        => '',
      'secondary_font_custom' => '',
      'load_google_fonts'     => 'no',
      'google_fonts_subsets'  => [ 'latin' ],

      /** Font sizes */
      'primary_font_size_desktop'         => 14,
      'primary_font_size_mobile'          => 14,
      'primary_font_line_height_desktop'  => 24,
      'primary_font_line_height_mobile'   => 18,
      'heading_font_size_desktop'         => 20,
      'heading_font_size_mobile'          => 18,
      'heading_font_line_height_desktop'  => 28,
      'heading_font_line_height_mobile'   => 24,
      'bigger_font_size_desktop'          => 24,
      'bigger_font_size_mobile'           => 20,
      'bigger_font_line_height_desktop'   => 34,
      'bigger_font_line_height_mobile'    => 28,
      'smaller_font_size_desktop'         => 13,
      'smaller_font_size_mobile'          => 13,
      'smaller_font_line_height_desktop'  => 20,
      'smaller_font_line_height_mobile'   => 18,

      /** Global colors Settings */
      'alpha_accent_color'    => '#0081ff',
      'beta_accent_color'     => '#39b54a',
      'gamma_accent_color'    => '#252525',
      'delta_accent_color'    => '#e83656',
      'primary_accent_inner'  => '#ffffff',
      'accent_inner'          => '#ffffff',

      /** Light theme Settings */
      'tl_border_color'         => '#eeeeee',
      'tl_icon_color'           => '#8799a3',
      'tl_shadow_color'         => 'rgba(0, 0, 0, 0.1)',
      'tl_shadow_offset_x'      => -4,
      'tl_shadow_offset_y'      => 8,
      'tl_shadow_blur'          => 16,
      'tl_shadow_spread'        => -4,
      'tl_header_color'         => '#252525',
      'tl_text_color'           => '#636363',
      'tl_text_alt_color'       => '#8799a3',
      'tl_primary_bg_color'     => '#ffffff',
      'tl_secondary_bg_color'   => '#f3f4f6',

      /** Dark theme Settings */
      'td_border_color'         => '#636363',
      'td_icon_color'           => '#636363',
      'td_shadow_color'         => '#000000',
      'td_shadow_offset_x'      => -4,
      'td_shadow_offset_y'      => 8,
      'td_shadow_blur'          => 16,
      'td_shadow_spread'        => -4,
      'td_header_color'         => '#ffffff',
      'td_text_color'           => '#ffffff',
      'td_text_alt_color'       => '#636363',
      'td_primary_bg_color'     => '#3a3a3a',
      'td_secondary_bg_color'   => '#121212',

      /** optimization */
      'disable_shortcodes'        => 'no',
      'disable_gutenberg_blocks'  => 'no',

    ],
    'standard_fonts' => [
      'Arial',
      'Verdana',
      'Trebuchet',
      'Georgia',
      'Times New Roman',
      'Tahoma',
      'Palatino',
      'Helvetica',
      'Calibri',
      'Myriad Pro',
      'Lucida',
      'Arial Black',
      'Gill Sans',
      'Geneva',
      'Impact',
      'Serif'
    ],
    'fonts_subsets' => [
      [
        'title' => 'latin',
        'value' => 'latin',
      ],
      [
        'title' => 'latin-ext',
        'value' => 'latin-ext',
      ],
      [
        'title' => 'menu',
        'value' => 'menu',
      ],
      [
        'title' => 'greek',
        'value' => 'greek',
      ],
      [
        'title' => 'greek-ext',
        'value' => 'greek-ext',
      ],
      [
        'title' => 'cyrillic',
        'value' => 'cyrillic',
      ],
      [
        'title' => 'cyrillic-ext',
        'value' => 'cyrillic-ext',
      ],
      [
        'title' => 'vietnamese',
        'value' => 'vietnamese',
      ],
      [
        'title' => 'arabic',
        'value' => 'arabic',
      ],
      [
        'title' => 'khmer',
        'value' => 'khmer',
      ],
      [
        'title' => 'lao',
        'value' => 'lao',
      ],
      [
        'title' => 'tamil',
        'value' => 'tamil',
      ],
      [
        'title' => 'bengali',
        'value' => 'bengali',
      ],
      [
        'title' => 'hindi',
        'value' => 'hindi',
      ],
      [
        'title' => 'korean',
        'value' => 'korean',
      ],
    ],
    'social_profiles' => [
      'facebook_url' => __( 'Facebook URL', 'wp-blocks-hub'),
      'twitter_url' => __( 'Twitter URL', 'wp-blocks-hub'),
      'instagram_url' => __( 'Instagram URL', 'wp-blocks-hub'),
      'google_plus_url' => __( 'Google Plus URL', 'wp-blocks-hub'),
      'pinterest_url' => __( 'Pinterest URL', 'wp-blocks-hub'),
      'linkedin_url' => __( 'LinkedIn URL', 'wp-blocks-hub'),
      'youtube_url' => __( 'YouTube URL', 'wp-blocks-hub'),
      'vimeo_url' => __( 'Vimeo URL', 'wp-blocks-hub'),
      'dribbble_url' => __( 'Dribbble URL', 'wp-blocks-hub'),
      'behance_url' => __( 'Behance URL', 'wp-blocks-hub'),
      'tumblr_url' => __( 'Tumblr URL', 'wp-blocks-hub'),
      'flickr_url' => __( 'Flickr URL', 'wp-blocks-hub'),
      'medium_url' => __( 'Medium URL', 'wp-blocks-hub'),
    ]
  ];
?>