<?php
	/**
	 * @accept $data
	 */
	use WPBlocksHub\Helper\Forms;
	use WPBlocksHub\Helper\Settings;

	$alpha_accent_color = Settings::get_option( 'alpha_accent_color' );
	$beta_accent_color = Settings::get_option( 'beta_accent_color' );
	$gamma_accent_color = Settings::get_option( 'gamma_accent_color' );
	$delta_accent_color = Settings::get_option( 'delta_accent_color' );
	$primary_accent_inner = Settings::get_option( 'primary_accent_inner' );
	$accent_inner = Settings::get_option( 'accent_inner' );
?>
<div class="wpbh-cols cols2">
	<div class="wpbh-col">

		<h3 id="settings-global-colors" class="title"><?php _e( 'Colors', 'wp-blocks-hub'); ?></h3>

		<table id="wpbh-accent-colors" class="form-table">
			<tbody>
				<?php

					Forms::admin_input_color_picker( [
						'name' => 'alpha_accent_color',
						'title' => __( 'Alpha Accent Color', 'wp-blocks-hub'),
						'value' => $alpha_accent_color,
					]);

					Forms::admin_input_color_picker( [
						'name' => 'beta_accent_color',
						'title' => __( 'Beta Accent Color', 'wp-blocks-hub'),
						'value' => $beta_accent_color
					]);

					Forms::admin_input_color_picker( [
						'name' => 'gamma_accent_color',
						'title' => __( 'Gamma Accent Color', 'wp-blocks-hub'),
						'value' => $gamma_accent_color
					]);

					Forms::admin_input_color_picker( [
						'name' => 'delta_accent_color',
						'title' => __( 'Delta Accent Color', 'wp-blocks-hub'),
						'value' => $delta_accent_color
					]);

					Forms::admin_input_color_picker( [
						'name' => 'primary_accent_inner',
						'title' => __( 'Primary Accent Inner Color', 'wp-blocks-hub'),
						'value' => $primary_accent_inner
					]);

					Forms::admin_input_color_picker( [
						'name' => 'accent_inner',
						'title' => __( 'Accent Inner Color', 'wp-blocks-hub'),
						'value' => $accent_inner
					]);

				?>
			</tbody>
		</table>

	</div>
	<div class="wpbh-col">
		<div class="postbox postbox-darken wpbh-stick-in-parent">
			<h2><?php _e( 'Accent colors preview', 'wp-blocks-hub'); ?></h2>
			<div class="inside">
				<div id="wpbh-colors-preview" class="wpbh-preview-colors-box">
					
					<div <?php if( !empty( $alpha_accent_color ) ): ?> style="background-color: <?php esc_attr_e( $alpha_accent_color ); ?>"<?php endif; ?> id="wpbh-alpha_accent_color-preview" class="wpbh-colors-preview-box"><span <?php if( !empty( $primary_accent_inner ) ): ?> style="color: <?php esc_attr_e( $primary_accent_inner ); ?>"<?php endif; ?>>Alpha</span></div>
					<div <?php if( !empty( $beta_accent_color ) ): ?> style="background-color: <?php esc_attr_e( $beta_accent_color ); ?>"<?php endif; ?> id="wpbh-beta_accent_color-preview" class="wpbh-colors-preview-box"><span <?php if( !empty( $accent_inner ) ): ?> style="color: <?php esc_attr_e( $accent_inner ); ?>"<?php endif; ?>>Beta</span></div>
					<div <?php if( !empty( $gamma_accent_color ) ): ?> style="background-color: <?php esc_attr_e( $gamma_accent_color ); ?>"<?php endif; ?> id="wpbh-gamma_accent_color-preview" class="wpbh-colors-preview-box"><span <?php if( !empty( $accent_inner ) ): ?> style="color: <?php esc_attr_e( $accent_inner ); ?>"<?php endif; ?>>Gamma</span></div>
					<div <?php if( !empty( $delta_accent_color ) ): ?> style="background-color: <?php esc_attr_e( $delta_accent_color ); ?>"<?php endif; ?> id="wpbh-delta_accent_color-preview" class="wpbh-colors-preview-box"><span <?php if( !empty( $accent_inner ) ): ?> style="color: <?php esc_attr_e( $accent_inner ); ?>"<?php endif; ?>>Delta</span></div>

				</div>
			</div>
		</div>
	</div>

</div>