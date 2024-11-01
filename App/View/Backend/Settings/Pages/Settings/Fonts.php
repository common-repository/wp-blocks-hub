<?php
	/**
	 * @accept $data
	 */
	use WPBlocksHub\Helper\Forms;
	use WPBlocksHub\Helper\Settings;
	use WPBlocksHub\Helper\Utils;

	$primary_font = Settings::get_option( 'primary_font' );
	$primary_font_size = Settings::get_option( 'primary_font_size_desktop' );
	$primary_font_line_height = Settings::get_option( 'primary_font_line_height_desktop' );

	$secondary_font = Settings::get_option( 'secondary_font' );

	$heading_font_size = Settings::get_option( 'heading_font_size_desktop' );
	$heading_font_line_height = Settings::get_option( 'heading_font_line_height_desktop' );

	$bigger_font_size = Settings::get_option( 'bigger_font_size_desktop' );
	$bigger_font_line_height = Settings::get_option( 'bigger_font_line_height_desktop' );

	$smaller_font_size = Settings::get_option( 'smaller_font_size_desktop' );
	$smaller_font_line_height = Settings::get_option( 'smaller_font_line_height_desktop' );
?>
<h3 id="settings-global-styles" class="title"><?php _e( 'Global styles', 'wp-blocks-hub'); ?></h3>

<p><?php _e( 'All of blocks use these settings to render stylesheet. Set up fonts and colors to match your website style.', 'wp-blocks-hub'); ?></p>

<div class="wpbh-cols cols2">
	<div class="wpbh-col">

	<h3 id="settings-global-fonts" class="title"><?php _e( 'Fonts', 'wp-blocks-hub'); ?></h3>

	<table class="form-table form-table-fonts">
		<tbody>
		<?php
				Forms::admin_input_select2( [
					'name' => 'primary_font',
					'title' => __( 'Primary font', 'wp-blocks-hub'),
					'values' => $data['fonts'],
					'multiple' => false,
					'value' => $primary_font,
				]);

				Forms::admin_input_text( [
					'name' => 'primary_font_custom',
					'title' => __( 'Custom primary font', 'wp-blocks-hub'),
					'desc' => __( 'Type a font family here: e.g. "san-serif", serif', 'wp-blocks-hub'),
					'value' => Settings::get_option( 'primary_font_custom' ),
					'conditional_logic' => [
						'elem' => 'primary_font',
						'value' => 'custom'
					]
				]);

				Forms::admin_input_font_size( [
					'name' => 'primary_font_size',
					'title' => __( 'Primary font size', 'wp-blocks-hub'),
					'value_desktop' => Settings::get_option( 'primary_font_size_desktop' ),
					'value_mobile' => Settings::get_option( 'primary_font_size_mobile' ),
				]);

				Forms::admin_input_font_size( [
					'name' => 'primary_font_line_height',
					'title' => __( 'Primary font line height', 'wp-blocks-hub'),
					'value_desktop' => Settings::get_option( 'primary_font_line_height_desktop' ),
					'value_mobile' => Settings::get_option( 'primary_font_line_height_mobile' ),
				]);
		?>
		</tbody>
	</table>

	<table class="form-table form-table-fonts">
		<tbody>
		<?php
				Forms::admin_input_select2( [
					'name' => 'secondary_font',
					'title' => __( 'Secondary font', 'wp-blocks-hub'),
					'values' => $data['fonts'],
					'multiple' => false,
					'value' => Settings::get_option( 'secondary_font' ),
				]);

				Forms::admin_input_text( [
					'name' => 'secondary_font_custom',
					'title' => __( 'Custom secondary font', 'wp-blocks-hub'),
					'desc' => __( 'Type a font family here: e.g. "san-serif", serif', 'wp-blocks-hub'),
					'value' => Settings::get_option( 'secondary_font_custom' ),
					'conditional_logic' => [
						'elem' => 'secondary_font',
						'value' => 'custom'
					]
				]);

				?>
				</tbody>
			</table>
		
			<table class="form-table form-table-fonts">
				<tbody>
				<?php

				Forms::admin_input_font_size( [
					'name' => 'heading_font_size',
					'title' => __( 'Heading font size', 'wp-blocks-hub'),
					'value_desktop' => Settings::get_option( 'heading_font_size_desktop' ),
					'value_mobile' => Settings::get_option( 'heading_font_size_mobile' ),
				]);

				Forms::admin_input_font_size( [
					'name' => 'heading_font_line_height',
					'title' => __( 'Heading font line height', 'wp-blocks-hub'),
					'value_desktop' => Settings::get_option( 'heading_font_line_height_desktop' ),
					'value_mobile' => Settings::get_option( 'heading_font_line_height_mobile' ),
				]);

				?>
				</tbody>
			</table>
		
			<table class="form-table form-table-fonts">
				<tbody>
				<?php

				Forms::admin_input_font_size( [
					'name' => 'bigger_font_size',
					'title' => __( 'Bigger font size', 'wp-blocks-hub'),
					'value_desktop' => Settings::get_option( 'bigger_font_size_desktop' ),
					'value_mobile' => Settings::get_option( 'bigger_font_size_mobile' ),
				]);

				Forms::admin_input_font_size( [
					'name' => 'bigger_font_line_height',
					'title' => __( 'Bigger font line height', 'wp-blocks-hub'),
					'value_desktop' => Settings::get_option( 'bigger_font_line_height_desktop' ),
					'value_mobile' => Settings::get_option( 'bigger_font_line_height_mobile' ),
				]);

				?>
				</tbody>
			</table>
		
			<table class="form-table form-table-fonts">
				<tbody>
				<?php

				Forms::admin_input_font_size( [
					'name' => 'smaller_font_size',
					'title' => __( 'Smaller font size', 'wp-blocks-hub'),
					'value_desktop' => Settings::get_option( 'smaller_font_size_desktop' ),
					'value_mobile' => Settings::get_option( 'smaller_font_size_mobile' ),
				]);

				Forms::admin_input_font_size( [
					'name' => 'smaller_font_line_height',
					'title' => __( 'Smaller font line height', 'wp-blocks-hub'),
					'value_desktop' => Settings::get_option( 'smaller_font_line_height_desktop' ),
					'value_mobile' => Settings::get_option( 'smaller_font_line_height_mobile' ),
				]);

				?>
				</tbody>
			</table>
		
			<table class="form-table form-table-fonts">
				<tbody>
				<?php

				Forms::admin_input_checkbox( [
					'name' => 'load_google_fonts',
					'title' => __( 'Load Google Fonts', 'wp-blocks-hub'),
					'desc' => __( 'Uncheck this box if you do not want to load Google Fonts (e.g. in case your theme always do that)', 'wp-blocks-hub'),
					'value' => 'yes',
					'checked' => Utils::bool( Settings::get_option( 'load_google_fonts' ) ),
					'value' => 'yes',
				]);

				Forms::admin_input_select2( [
					'name' => 'google_fonts_subsets[]',
					'title' => __( 'Load additional subsets', 'wp-blocks-hub'),
					'desc' => __( 'Additional subsets will be loaded only if current font supports them', 'wp-blocks-hub'),
					'values' => WPBH()->Config['fonts_subsets'],
					'multiple' => true,
					'value' => Settings::get_option( 'google_fonts_subsets' ),
				]);

			?>
			</tbody>
		</table>	
	</div>
	<div class="wpbh-col">
		<div class="postbox postbox-darken wpbh-stick-in-parent">
			<h2><?php _e( 'Fonts preview', 'wp-blocks-hub'); ?></h2>
			<div class="inside">
				<div id="wpbh-text-preview" class="wpbh-preview-text-box">
					
					<div
						id="wpbh-text-preview-font-secondary"
						style="
							<?php if( !empty( $secondary_font ) ): ?>font-family: <?php esc_attr_e( $secondary_font ); ?>;<?php endif; ?>
							<?php if( !empty( $heading_font_size ) ): ?>font-size: <?php esc_attr_e( $heading_font_size ); ?>px;<?php endif; ?>
							<?php if( !empty( $heading_font_line_height ) ): ?>line-height: <?php esc_attr_e( $heading_font_line_height ); ?>px;<?php endif; ?>
						"
					>Lorem ipsum dolor sit amet</div>

					<div
						id="wpbh-text-preview-font-bigger"
						style="
							<?php if( !empty( $primary_font ) ): ?>font-family: <?php esc_attr_e( $primary_font ); ?>;<?php endif; ?>
							<?php if( !empty( $bigger_font_size ) ): ?>font-size: <?php esc_attr_e( $bigger_font_size ); ?>px;<?php endif; ?>
							<?php if( !empty( $bigger_font_line_height ) ): ?>line-height: <?php esc_attr_e( $bigger_font_line_height ); ?>px;<?php endif; ?>
						"
					>Bigger text</div>

					<div
						id="wpbh-text-preview-font-primary"
						style="
						<?php if( !empty( $primary_font ) ): ?>font-family: <?php esc_attr_e( $primary_font ); ?>;<?php endif; ?>
						<?php if( !empty( $primary_font_size ) ): ?>font-size: <?php esc_attr_e( $primary_font_size ); ?>px;<?php endif; ?>
						<?php if( !empty( $primary_font_line_height ) ): ?>line-height: <?php esc_attr_e( $primary_font_line_height ); ?>px;<?php endif; ?>
						"
					>Fusce in lorem sed orci ultricies dictum id vel nisi. Maecenas mattis risus vel diam fermentum, ac elementum est pretium. Quisque ultrices porttitor dolor, et faucibus nisl lobortis at. Suspendisse tempus vestibulum tempus. Cras vehicula odio vel porta egestas. Nam auctor iaculis turpis eu malesuada.</div>

					<div
						id="wpbh-text-preview-font-smaller"
						style="
						<?php if( !empty( $primary_font ) ): ?>font-family: <?php esc_attr_e( $primary_font ); ?>;<?php endif; ?>
						<?php if( !empty( $smaller_font_size ) ): ?>font-size: <?php esc_attr_e( $smaller_font_size ); ?>px;<?php endif; ?>
						<?php if( !empty( $smaller_font_line_height ) ): ?>line-height: <?php esc_attr_e( $smaller_font_line_height ); ?>px;<?php endif; ?>
						"
					>Smaller text</div>

				</div>
			</div>
		</div>
	</div>
</div>