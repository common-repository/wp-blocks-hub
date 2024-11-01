<?php

namespace WPBlocksHub\Helper;

class Forms {

	/**
	 * Prepare array to select2 data structure
	 */
	public static function sanitize_fonts_array( $data, $selected = '', $include_standard = false, $can_be_custom = false ) {

		$result_array = [];

		$result_array['groups']['custom'] = [
			'title' => __( 'Default', 'wp-blocks-hub'),
			'values' =>	[
				[
					'title' => __('Inherit fonts', 'wp-blocks-hub'),
					'value' => '',
					'selected' => $selected == 'inherit'
				]
			]
		];

		if( $can_be_custom ) {
			$result_array['groups']['custom']['values'][] = [
				'title' => __('Type Custom Font Family', 'wp-blocks-hub'),
				'value' => 'custom',
				'selected' => $selected == 'custom'
			];
		}

		if( $include_standard ) {

			$result_array['groups']['standard'] = [
				'title' => __( 'Standard Fonts', 'wp-blocks-hub'),
			];

			foreach( WPBH()->Config['standard_fonts'] as $font ) {
				$result_array['groups']['standard']['values'][] = [
					'title' => $font,
					'value' => $font,
					'selected' => $selected == $font
				];
			}

		}

		if( !empty( $data['items'] ) ) {

			$google_fonts_items = [];

			foreach( $data['items'] as $font ) {

				$google_fonts_items[] = [
					'title' => $font['family'],
					'value' => $font['family'],
					'selected' => $selected == $font['family']
				];
			}

			$result_array['groups']['google_fonts'] = [
				'title' => __( 'Google Fonts', 'wp-blocks-hub'),
				'values' =>	$google_fonts_items
			];

		}

		return $result_array;

	}


	/**
	 * Display checkbox
	 */

	/*

		// usage example:

		Forms::admin_input_checkbox( [
			'name' => 'force_google_fonts_loading',
			'title' => __( 'Force Google Fonts loading', 'wp-blocks-hub'),
			'value' => 'yes',
			'checked' => true
		]);

	*/
	public static function admin_input_checkbox( $options ) {

		$defaults = [
			'id' => isset( $options['name'] ) ? sanitize_title( $options['name'] ) : uniqid('wpbh-input-id'),
			'title' => '',
			'subtitle' => '',
			'value' => '',
			'desc' => '',
			'checked' => false
		];

		$options = array_merge( $defaults, $options );

?>
	<tr <?php if( !empty( $options['conditional_logic'] )): ?>data-wpbh-cond-elem="<?php esc_attr_e( $options['conditional_logic']['elem'] ); ?>" data-wpbh-cond-val="<?php esc_attr_e( $options['conditional_logic']['value'] ); ?>"<?php endif; ?>>
		<th scope="row">
			<?php echo wp_kses_post( $options['subtitle'] ); ?>
		</th>
		<td>
			<label><input <?php if( $options['checked'] ): ?>checked="checked"<?php endif; ?> value="<?php esc_attr_e( $options['value'] ); ?>" name="<?php esc_attr_e( $options['name'] ); ?>" id="<?php echo esc_attr( $options['id'] ); ?>" type="checkbox"> <?php echo $options['title']; ?> </label>
			<?php if( $options['desc'] <> '' ): ?>
			<p class="description"><?php echo $options['desc']; ?></p>
			<?php endif; ?>
		</td>
	</tr>
<?php
	}

	/**
	 * Display text input
	 */

	/*

		// usage example:

		Forms::admin_input_text( [
			'name' => 'testimonials_post_type_slug',
			'title' => __( 'Custom Post Slug', 'wp-blocks-hub'),
			'conditional_logic' => [
				'elem' => 'testimonials_post_type',
				'value' => 'default'
			]
		]);

	*/
	public static function admin_input_text( $options ) {

		$defaults = [
			'id' => isset( $options['name'] ) ? sanitize_title( $options['name'] ) : uniqid('wpbh-input-id'),
			'title' => '',
			'value' => '',
			'desc' => ''
		];

		$options = array_merge( $defaults, $options );

?>
	<tr <?php if( !empty( $options['conditional_logic'] )): ?>data-wpbh-cond-elem="<?php esc_attr_e( $options['conditional_logic']['elem'] ); ?>" data-wpbh-cond-val="<?php esc_attr_e( $options['conditional_logic']['value'] ); ?>"<?php endif; ?>>
		<th scope="row">
			<label for="<?php esc_attr_e( $options['id'] ); ?>"><?php echo $options['title']; ?></label>
		</th>
		<td>
			<input value="<?php esc_attr_e( $options['value'] ); ?>" name="<?php esc_attr_e( $options['name'] ); ?>" id="<?php echo esc_attr( $options['id'] ); ?>" class="regular-text" type="text">
			<?php if( $options['desc'] <> '' ): ?>
			<p class="description"><?php echo $options['desc']; ?></p>
			<?php endif; ?>
		</td>
	</tr>
<?php
	}

	/**
	 * Display textarea
	 */

	/*

		// usage example:

		Forms::admin_input_textarea( [
			'name' => 'td_system_status',
			'title' => __( 'System status', 'wp-blocks-hub'),
			'value' => '',
			'desc' => __( 'Please copy and provide following information within your support tickets.', 'wp-blocks-hub')
		]);

	*/
	public static function admin_input_textarea( $options ) {

		$defaults = [
			'id' => isset( $options['name'] ) ? sanitize_title( $options['name'] ) : uniqid('wpbh-input-id'),
			'title' => '',
			'value' => '',
			'desc' => '',
			'readonly' => false
		];

		$options = array_merge( $defaults, $options );

?>
	<tr <?php if( !empty( $options['conditional_logic'] )): ?>data-wpbh-cond-elem="<?php esc_attr_e( $options['conditional_logic']['elem'] ); ?>" data-wpbh-cond-val="<?php esc_attr_e( $options['conditional_logic']['value'] ); ?>"<?php endif; ?>>
		<th scope="row">
			<label for="<?php esc_attr_e( $options['id'] ); ?>"><?php echo $options['title']; ?></label>
		</th>
		<td>
			<textarea name="<?php esc_attr_e( $options['name'] ); ?>" id="<?php echo esc_attr( $options['id'] ); ?>" <?php if( $options['readonly']): ?>readonly="readonly"<?php endif; ?> rows="15" class="widefat"><?php esc_attr_e( $options['value'] ); ?></textarea>
			<?php if( isset( $options['ace'] ) ): ?>
			<div id="<?php esc_attr_e( $options['id'] ); ?>_ace"><?php esc_attr_e( $options['value'] ); ?></div>
			<?php endif; ?>
			<?php if( $options['desc'] <> '' ): ?>
			<p class="description"><?php echo $options['desc']; ?></p>
			<?php endif; ?>
		</td>
	</tr>
<?php
	}

	/**
	 * Display color picker input
	 */

	/*

		// usage example:

		Forms::admin_input_color_picker( [
			'name' => 'accent_color',
			'title' => __( 'Accent Color', 'wp-blocks-hub'),
			'value' => '#000000',
			'conditional_logic' => [
				'elem' => 'testimonials_post_type',
				'value' => 'default'
			]
		]);

	*/
	public static function admin_input_color_picker( $options ) {

		$defaults = [
			'id' => isset( $options['name'] ) ? sanitize_title( $options['name'] ) : uniqid('wpbh-input-id'),
			'title' => '',
			'value' => '',
			'default_value' => '',
			'desc' => ''
		];

		$options = array_merge( $defaults, $options );

?>
	<tr <?php if( isset( $options['conditional_logic'] )): ?>data-wpbh-cond-elem="<?php esc_attr_e( $options['conditional_logic']['elem'] ); ?>" data-wpbh-cond-val="<?php esc_attr_e( $options['conditional_logic']['value'] ); ?>"<?php endif; ?>>
		<th scope="row">
			<label for="<?php esc_attr_e( $options['id'] ); ?>"><?php echo $options['title']; ?></label>
		</th>
		<td>
			<input data-alpha="true" data-default-color="<?php esc_attr_e( $options['default_value'] ); ?>" value="<?php esc_attr_e( $options['value'] ); ?>" name="<?php esc_attr_e( $options['name'] ); ?>" id="<?php echo esc_attr( $options['id'] ); ?>" class="wpbh-color-picker" type="text">
			<?php if( isset( $options['desc'] ) && $options['desc'] <> '' ): ?>
			<p class="description"><?php echo $options['desc']; ?></p>
			<?php endif; ?>
		</td>
	</tr>
<?php
	}

	/**
	 * Display radio input
	 */

	/*

		// usage example:

		Forms::admin_input_radio( [
			'name' => 'testimonials_post_type',
			'title' => __( 'Testimonials Post Type', 'wp-blocks-hub'),
			'desc' => sprintf( __( 'Read more information about how to get Google Maps API key <a href="%s">here</a>. Google Maps JavaScript API v3 is REQUIRED to display blocks that use Google Maps.', 'wp-blocks-hub'), 'https://wpblockshub.com/documentation/faq/how-to-get-google-maps-api-key'),
			'values' => [
				[
					'title' => __( 'Use Blocks Hub Testimonials Post Type', 'wp-blocks-hub'),
					'value' => 'default',
					'checked' => true
				],
				[
					'title' => __( 'Use your own Post Type', 'wp-blocks-hub'),
					'value' => 'custom'
				]
			]
		]);

	*/

	public static function admin_input_radio( $options ) {

		$defaults = [
			'title' => '',
			'value' => '',
			'default_value' => '',
			'desc' => ''
		];

		$options = array_merge( $defaults, $options );

?>
	<tr <?php if( isset( $options['conditional_logic'] )): ?>data-wpbh-cond-elem="<?php esc_attr_e( $options['conditional_logic']['elem'] ); ?>" data-wpbh-cond-val="<?php esc_attr_e( $options['conditional_logic']['value'] ); ?>"<?php endif; ?>>
		<th scope="row">
			<label><?php echo $options['title']; ?></label>
		</th>
		<td>
			
			<?php if( ! empty( $options['values'] ) ): ?>

				<?php foreach( $options['values'] as $input ): ?>

				<?php
					$checked = isset( $options['value'] ) && $options['value'] == $input['value'] ? true : isset( $input['checked'] );
				?>

				<p>
					<label><input <?php if( $checked ): ?>checked="checked"<?php endif; ?> type="radio" name="<?php esc_attr_e( $options['name'] ); ?>" value="<?php esc_attr_e( $input['value'] ); ?>"> <?php esc_html_e( $input['title'] ); ?></label>
				</p>
				<?php endforeach; ?>

			<?php endif; ?>

			<?php if( $options['desc'] <> '' ): ?>
			<p class="description"><?php echo $options['desc']; ?></p>
			<?php endif; ?>
		</td>
	</tr>
<?php
	}

	/**
	 * Display dropdown
	 */

	/*

		// usage example:
		$dropdown_values = [];

		foreach( $data['available_post_types'] as $post_type ) {

			$dropdown_values[] = [
				'title' => $post_type->label,
				'value' => $post_type->name,
				'selected' => isset( $data['settings']['testimonials_custom_post_type'] ) && $post_type->name == $data['settings']['testimonials_custom_post_type']
			];

		}

		Forms::admin_input_dropdown( [
			'name' => 'testimonials_custom_post_type',
			'title' => __( 'Choose Post Type', 'wp-blocks-hub'),
			'values' => $dropdown_values,
			'conditional_logic' => [
				'elem' => 'testimonials_post_type',
				'value' => 'custom'
			]
		]);

	*/

	public static function admin_input_dropdown( $options ) {

		$defaults = [
			'id' => isset( $options['name'] ) ? sanitize_title( $options['name'] ) : uniqid('wpbh-input-id'),
			'title' => '',
			'value' => '',
			'desc' => ''
		];

		$options = array_merge( $defaults, $options );
?>
	<tr <?php if( isset( $options['conditional_logic'] )): ?>data-wpbh-cond-elem="<?php esc_attr_e( $options['conditional_logic']['elem'] ); ?>" data-wpbh-cond-val="<?php esc_attr_e( $options['conditional_logic']['value'] ); ?>"<?php endif; ?>>
		<th scope="row">
			<label for="<?php esc_attr_e( $options['id'] ); ?>"><?php echo $options['title']; ?></label>
		</th>
		<td>
			
			<select class="regular-text" name="<?php esc_attr_e( $options['name'] ); ?>" id="<?php echo esc_attr( $options['id'] ); ?>">
				<?php if( ! empty( $options['values'] ) ): ?>

					<?php foreach( $options['values'] as $input ): ?>

					<?php
						$selected = isset( $options['value'] ) && $options['value'] == $input['value'] ? true : isset( $input['selected'] ) && filter_var( $input['selected'], FILTER_VALIDATE_BOOLEAN );
					?>

					<option <?php if( $selected ): ?>selected="selected"<?php endif; ?> value="<?php esc_attr_e( $input['value'] ); ?>"> <?php esc_html_e( $input['title'] ); ?></option>
					<?php endforeach; ?>

				<?php endif; ?>
			</select>

			<?php if( $options['desc'] <> '' ): ?>
			<p class="description"><?php echo $options['desc']; ?></p>
			<?php endif; ?>
		</td>
	</tr>
<?php
	}

	/**
	 * Display Select2
	 */

	/*

		// usage example:
		$dropdown_values = [];

		foreach( $data['available_post_types'] as $post_type ) {

			$dropdown_values[] = [
				'title' => $post_type->label,
				'value' => $post_type->name,
				'selected' => isset( $data['settings']['testimonials_custom_post_type'] ) && $post_type->name == $data['settings']['testimonials_custom_post_type']
			];

		}

		Forms::admin_input_select2( [
			'name' => 'testimonials_custom_post_type',
			'title' => __( 'Choose Post Type', 'wp-blocks-hub'),
			'values' => $dropdown_values,
			'multiple' => true,
			'conditional_logic' => [
				'elem' => 'testimonials_post_type',
				'value' => 'custom'
			]
		]);

	*/

	public static function admin_input_select2( $options ) {

		$defaults = [
			'id' => isset( $options['name'] ) ? sanitize_title( $options['name'] ) : uniqid('wpbh-input-id'),
			'title' => '',
			'values' => '',
			'desc' => '',
			'multiple' => false
		];

		$options = array_merge( $defaults, $options );

?>
	<tr <?php if( isset( $options['conditional_logic'] )): ?>data-wpbh-cond-elem="<?php esc_attr_e( $options['conditional_logic']['elem'] ); ?>" data-wpbh-cond-val="<?php esc_attr_e( $options['conditional_logic']['value'] ); ?>"<?php endif; ?>>
		<th scope="row">
			<label for="<?php esc_attr_e( $options['id'] ); ?>"><?php echo $options['title']; ?></label>
		</th>
		<td>
			
			<select <?php if( $options['multiple'] ): ?>multiple="multiple"<?php endif; ?> class="wpbh-select2 regular-text" name="<?php esc_attr_e( $options['name'] ); ?>" id="<?php echo esc_attr( $options['id'] ); ?>">

				<?php if( !empty( $options['values']['groups'] ) ): ?>

					<?php foreach( $options['values']['groups'] as $k=>$group ): ?>

					<optgroup label="<?php esc_attr_e( $group['title'] ); ?>">
						<?php foreach( $group['values'] as $input ): ?>
						<?php
							$selected = isset( $options['value'] ) && $options['value'] == $input['value'] ? true : isset( $input['selected'] ) && filter_var( $input['selected'], FILTER_VALIDATE_BOOLEAN );
						?>
						<option <?php if( $selected ): ?>selected="selected"<?php endif; ?> value="<?php esc_attr_e( $input['value'] ); ?>"> <?php esc_html_e( $input['title'] ); ?></option>
						<?php endforeach; ?>
					</optgroup>

					<?php endforeach; ?>

				<?php else: ?>

					<?php foreach( $options['values'] as $input ): ?>
					<?php
						if( $options['multiple'] ) {
							$selected = is_array( $options['value'] ) && in_array( $input['value'], $options['value'] );
						} else {
							$selected = isset( $options['value'] ) && $options['value'] == $input['value'] ? true : isset( $input['selected'] ) && filter_var( $input['selected'], FILTER_VALIDATE_BOOLEAN );
						}
					?>
					<option <?php if( $selected ): ?>selected="selected"<?php endif; ?> value="<?php esc_attr_e( $input['value'] ); ?>"> <?php esc_html_e( $input['title'] ); ?></option>
					<?php endforeach; ?>

				<?php endif; ?>
			</select>

			<?php if( $options['desc'] <> '' ): ?>
			<p class="description"><?php echo $options['desc']; ?></p>
			<?php endif; ?>
		</td>
	</tr>
<?php
	}

	/**
	 * Display font size picker
	 */

	/*

		// usage example:

		Forms::admin_input_font_size( [
			'name' => 'primary_font_size',
			'title' => __( 'Primary font size', 'wp-blocks-hub'),
			'value_desktop' => 14,
			'value_mobile' => 12,
		]);

	*/
	public static function admin_input_font_size( $options ) {

		$defaults = [
			'id' => isset( $options['name'] ) ? sanitize_title( $options['name'] ) : uniqid('wpbh-input-id'),
			'title' => '',
			'value_desktop' => '',
			'value_mobile' => '',
			'desc' => ''
		];

		$options = array_merge( $defaults, $options );

?>
	<tr <?php if( !empty( $options['conditional_logic'] )): ?>data-wpbh-cond-elem="<?php esc_attr_e( $options['conditional_logic']['elem'] ); ?>" data-wpbh-cond-val="<?php esc_attr_e( $options['conditional_logic']['value'] ); ?>"<?php endif; ?>>
		<th scope="row">
			<label for="<?php esc_attr_e( $options['id'] ); ?>"><?php echo $options['title']; ?></label>
		</th>
		<td>
			<div class="wpbh-range-slider-holder">
				<div>
					<div>
						<input data-min="7" data-max="90" value="<?php esc_attr_e( $options['value_desktop'] ); ?>" name="<?php esc_attr_e( $options['name'] ); ?>_desktop" id="<?php echo esc_attr( $options['id'] ); ?>-desktop" class="wpbh-range-slider" type="text">
						<span class="desc"><?php _e( 'Desktop screen', 'wp-blocks-hub'); ?></span>
					</div>
				</div>
				<div>
					<div>
						<input data-min="7" data-max="90" value="<?php esc_attr_e( $options['value_mobile'] ); ?>" name="<?php esc_attr_e( $options['name'] ); ?>_mobile" id="<?php echo esc_attr( $options['id'] ); ?>-mobile" class="wpbh-range-slider" type="text">
						<span class="desc"><?php _e( 'Mobile devices', 'wp-blocks-hub'); ?></span>
					</div>
				</div>
			</div>
			<?php if( $options['desc'] <> '' ): ?>
			<p class="description"><?php echo $options['desc']; ?></p>
			<?php endif; ?>
		</td>
	</tr>
<?php
	}

	/**
	 * Shadow picker
	 */

	/*

		// usage example:

		Forms::admin_input_shadow_picker( [
			'name' => 'primary_font_size',
			'title' => __( 'Primary font size', 'wp-blocks-hub'),
		]);

	*/
	public static function admin_input_shadow_picker( $options ) {

		$defaults = [
			'id' => isset( $options['name'] ) ? sanitize_title( $options['name'] ) : uniqid('wpbh-input-id'),
			'title' => '',
			'offset_x' => '',
			'offset_y' => '',
			'blur' => '',
			'spread' => '',
			'desc' => ''
		];

		$options = array_merge( $defaults, $options );

?>
	<tr <?php if( !empty( $options['conditional_logic'] )): ?>data-wpbh-cond-elem="<?php esc_attr_e( $options['conditional_logic']['elem'] ); ?>" data-wpbh-cond-val="<?php esc_attr_e( $options['conditional_logic']['value'] ); ?>"<?php endif; ?>>
		<th scope="row">
			<label for="<?php esc_attr_e( $options['id'] ); ?>"><?php echo $options['title']; ?></label>
		</th>
		<td>
			<div class="wpbh-range-slider-holder">
				<div>
					<div>
						<input data-min="-150" data-max="150" value="<?php esc_attr_e( $options['offset_x'] ); ?>" name="<?php esc_attr_e( $options['name'] ); ?>_offset_x" id="<?php echo esc_attr( $options['id'] ); ?>-offset-x" class="wpbh-range-slider" type="text">
						<span class="desc"><?php _e( 'Offset X', 'wp-blocks-hub'); ?></span>
					</div>
				</div>
				<div>
					<div>
						<input data-min="-150" data-max="150" value="<?php esc_attr_e( $options['offset_y'] ); ?>" name="<?php esc_attr_e( $options['name'] ); ?>_offset_y" id="<?php echo esc_attr( $options['id'] ); ?>-offset-y" class="wpbh-range-slider" type="text">
						<span class="desc"><?php _e( 'Offset Y', 'wp-blocks-hub'); ?></span>
					</div>
				</div>
			</div>
			<div class="wpbh-range-slider-holder">
				<div>
					<div>
						<input data-min="0" data-max="150" value="<?php esc_attr_e( $options['blur'] ); ?>" name="<?php esc_attr_e( $options['name'] ); ?>_blur" id="<?php echo esc_attr( $options['id'] ); ?>-blur" class="wpbh-range-slider" type="text">
						<span class="desc"><?php _e( 'Blur', 'wp-blocks-hub'); ?></span>
					</div>
				</div>
				<div>
					<div>
						<input data-min="-150" data-max="150" value="<?php esc_attr_e( $options['spread'] ); ?>" name="<?php esc_attr_e( $options['name'] ); ?>_spread" id="<?php echo esc_attr( $options['id'] ); ?>-spread" class="wpbh-range-slider" type="text">
						<span class="desc"><?php _e( 'Spread', 'wp-blocks-hub'); ?></span>
					</div>
				</div>
			</div>
			<?php if( $options['desc'] <> '' ): ?>
			<p class="description"><?php echo $options['desc']; ?></p>
			<?php endif; ?>
		</td>
	</tr>
<?php
	}

	/**
	 * Display button
	 */

	/*

		// usage example:

		Forms::admin_input_button( [
			'name' => 'install_testimonial_posts',
			'title' => __( 'Testimonial Posts', 'wp-blocks-hub'),
			'text' => __( 'Install', 'wp-blocks-hub'),
			'desc' => __( 'This button adds some demo posts for "Testimonials" Custom Post Type.', 'wp-blocks-hub'),
			'primary' => true
		]);

	*/
	public static function admin_input_button( $options ) {

		$defaults = [
			'id' => isset( $options['name'] ) ? sanitize_title( $options['name'] ) : uniqid('wpbh-input-id'),
			'title' => '',
			'value' => '',
			'desc' => '',
			'data-attr' => ''
		];

		$options = array_merge( $defaults, $options );

?>
	<tr <?php if( isset( $options['conditional_logic'] )): ?>data-wpbh-cond-elem="<?php esc_attr_e( $options['conditional_logic']['elem'] ); ?>" data-wpbh-cond-val="<?php esc_attr_e( $options['conditional_logic']['value'] ); ?>"<?php endif; ?>>
		<th scope="row">
			<label><?php echo $options['title']; ?></label>
		</th>
		<td>
			<p>
				<button data-data="<?php echo esc_attr( $options['data-attr'] ); ?>" type="button" id="<?php echo esc_attr( $options['id'] ); ?>" name="<?php esc_attr_e( $options['name'] ); ?>" class="button <?php if( isset( $options['primary'] ) ): ?>button-primary<?php endif; ?>"><?php esc_html_e( $options['text'] ); ?></button>
				<img class="wpbh-loader hidden" src="<?php echo WPBH()->Config['plugin_url']; ?>/assets/images/admin/loader.svg" alt="">
			</p>

			<?php if( isset( $options['desc'] ) && $options['desc'] <> '' ): ?>
			<p class="description"><?php echo $options['desc']; ?></p>
			<?php endif; ?>
		</td>
	</tr>
<?php
	}

	/**
	 * Image picker
	 */
	public static function image_picker( $options ) {
		$defaults = [
			'id' => isset( $options['name'] ) ? sanitize_title( $options['name'] ) : uniqid('wpbh-input-id'),
			'title' => '',
			'value' => '',
			'desc' => ''
		];

		$options = array_merge( $defaults, $options );
		?>

		<div class="wpbh-image-picker">

			<div class="wpbh-image-picker-selector">
				<input type="hidden" id="<?php esc_attr_e( $options['id'] ); ?>" name="<?php esc_attr_e( $options['name'] ); ?>" value="<?php esc_attr_e( $options['value'] ); ?>">

				<a href="#" class="wpbh-image-picker-add"><?php _e( 'Add image', 'wp-blocks-hub'); ?></a>

				<div class="wpbh-image-picker-thumb">
					<?php if( is_numeric( $options['value'] ) && $options['value'] > 0 ): ?>
					<?php $img = wp_get_attachment_image_src( $options['value'], 'thumbnail' ); ?>
					<div class="wpbh-item"><img src="<?php echo $img[0]; ?>" class="wpbh-img" alt=""><a href="#" class="wpbh-remove dashicons dashicons-no-alt"></a></div>
					<?php endif; ?>
				</div>	

			</div>

			<?php if( isset( $options['desc'] ) && $options['desc'] <> '' ): ?>
			<p class="description"><?php echo $options['desc']; ?></p>
			<?php endif; ?>
		</div>

		<?php
	}

	/**
	 * Gallery picker
	 */
	public static function admin_input_images_picker( $options ) {

		$defaults = [
			'id' => isset( $options['name'] ) ? sanitize_title( $options['name'] ) : uniqid('wpbh-input-id'),
			'title' => '',
			'value' => '',
			'desc' => ''
		];

		$options = array_merge( $defaults, $options );
?>
	<tr <?php if( isset( $options['conditional_logic'] )): ?>data-wpbh-cond-elem="<?php esc_attr_e( $options['conditional_logic']['elem'] ); ?>" data-wpbh-cond-val="<?php esc_attr_e( $options['conditional_logic']['value'] ); ?>"<?php endif; ?>>
		<th scope="row">
			<label for="<?php esc_attr_e( $options['id'] ); ?>"><?php echo $options['title']; ?></label>
		</th>
		<td>
			<div>

				<input type="hidden" name="<?php esc_attr_e( $options['name'] ); ?>" value="<?php esc_attr_e( $options['value'] ); ?>">

				<a href="#" class="wpbh-images-picker-add"><?php _e( 'Add images', 'wp-blocks-hub'); ?></a>

				<div class="wpbh-images-picker-thumbs">
					<?php
						$images = json_decode( $options['value'] );
						if( !empty( $images ) ):
							foreach( $images as $img ):
					?>
					<div class="wpbh-item"><img src="<?php echo esc_attr( $img->url ); ?>" data-id="<?php echo esc_attr( $img->id ); ?>" class="wpbh-img" alt=""><a href="#" class="wpbh-remove dashicons dashicons-no-alt"></a></div>
					<?php
							endforeach;
						endif;
					?>
				</div>			

			</div>

			<?php if( isset( $options['desc'] ) && $options['desc'] <> '' ): ?>
			<p class="description"><?php echo $options['desc']; ?></p>
			<?php endif; ?>
		</td>
	</tr>
<?php
	}

}

?>