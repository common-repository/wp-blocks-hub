<?php
	/**
	 * @accept $data
	 */
	use WPBlocksHub\Helper\Forms;
	use WPBlocksHub\Helper\Settings;

	$tl_border_color = Settings::get_option( 'tl_border_color' );
	$tl_icon_color = Settings::get_option( 'tl_icon_color' );
	$tl_shadow_color = Settings::get_option( 'tl_shadow_color' );
	$tl_header_color = Settings::get_option( 'tl_header_color' );
	$tl_text_color = Settings::get_option( 'tl_text_color' );
	$tl_text_alt_color = Settings::get_option( 'tl_text_alt_color' );
	$tl_primary_bg_color = Settings::get_option( 'tl_primary_bg_color' );
	$tl_secondary_bg_color = Settings::get_option( 'tl_secondary_bg_color' );
	$tl_shadow_offset_x = Settings::get_option( 'tl_shadow_offset_x' );
	$tl_shadow_offset_y = Settings::get_option( 'tl_shadow_offset_y' );
	$tl_shadow_blur = Settings::get_option( 'tl_shadow_blur' );
	$tl_shadow_spread = Settings::get_option( 'tl_shadow_spread' );
?>
<h3 id="settings-light-theme-styles" class="title"><?php _e( 'Light theme styles', 'wp-blocks-hub'); ?></h3>

<div id="settings-light-theme-cp" class="wpbh-settings-theme-cp wpbh-cols cols2">
	<div class="wpbh-col">

		<table class="form-table">
			<tbody>
				<?php

					Forms::admin_input_color_picker( [
						'name' => 'tl_border_color',
						'title' => __( 'Border Color', 'wp-blocks-hub'),
						'value' => $tl_border_color
					]);

					Forms::admin_input_color_picker( [
						'name' => 'tl_icon_color',
						'title' => __( 'Icon Color', 'wp-blocks-hub'),
						'value' => $tl_icon_color
					]);

					Forms::admin_input_color_picker( [
						'name' => 'tl_header_color',
						'title' => __( 'Headers Color', 'wp-blocks-hub'),
						'value' => $tl_header_color
					]);

					Forms::admin_input_color_picker( [
						'name' => 'tl_text_color',
						'title' => __( 'Text Color', 'wp-blocks-hub'),
						'value' => $tl_text_color
					]);

					Forms::admin_input_color_picker( [
						'name' => 'tl_text_alt_color',
						'title' => __( 'Text Alt Color', 'wp-blocks-hub'),
						'value' => $tl_text_alt_color
					]);

					Forms::admin_input_color_picker( [
						'name' => 'tl_primary_bg_color',
						'title' => __( 'Primary Background Color', 'wp-blocks-hub'),
						'value' => $tl_primary_bg_color
					]);

					Forms::admin_input_color_picker( [
						'name' => 'tl_secondary_bg_color',
						'title' => __( 'Secondary Background Color', 'wp-blocks-hub'),
						'value' => $tl_secondary_bg_color
					]);

					Forms::admin_input_color_picker( [
						'name' => 'tl_shadow_color',
						'title' => __( 'Shadow Color', 'wp-blocks-hub'),
						'value' => $tl_shadow_color
					]);

					Forms::admin_input_shadow_picker([
						'name' => 'tl_shadow',
						'title' => __( 'Box shadow', 'wp-blocks-hub'),
						'offset_x' => $tl_shadow_offset_x,
						'offset_y' => $tl_shadow_offset_y,
						'blur' => $tl_shadow_blur,
						'spread' => $tl_shadow_spread,
					]);

				?>
			</tbody>
		</table>

	</div>
	<div class="wpbh-col">
		
		<div class="postbox postbox-darken wpbh-stick-in-parent">
			<h2><?php _e( 'Light theme styles preview', 'wp-blocks-hub'); ?></h2>
			<div class="inside">
				<div style="
						<?php if( !empty( $tl_border_color ) ): ?>border-color: <?php echo $tl_border_color; ?>;<?php endif; ?> 
						box-shadow: <?php echo $tl_shadow_offset_x; ?>px <?php echo $tl_shadow_offset_y; ?>px <?php echo $tl_shadow_blur; ?>px <?php echo $tl_shadow_spread; ?>px <?php echo $tl_shadow_color; ?>; 
						<?php if( !empty( $tl_primary_bg_color ) ): ?>background-color: <?php echo $tl_primary_bg_color; ?>;<?php endif; ?> 
					" id="wpbh-preview-light" class="wpbh-preview-box light">
					<div class="wpbh-preview-box_head">
					
						<div class="wpbh-box_head_img" style="<?php if( !empty( $tl_secondary_bg_color ) ): ?>background-color: <?php echo $tl_secondary_bg_color; ?>;<?php endif; ?> ">
							<svg height="60" viewBox="-11 0 512 512" width="60" xmlns="http://www.w3.org/2000/svg">
								<path style="<?php if( !empty( $tl_icon_color ) ): ?>fill: <?php echo $tl_icon_color; ?>;<?php endif; ?>" d="m19.914062 393.808594 96.097657 50.480468 97.085937-51.882812-91.683594-48.992188zm0 0"/>
								<path style="<?php if( !empty( $tl_icon_color ) ): ?>fill: <?php echo $tl_icon_color; ?>;<?php endif; ?>" d="m469.902344 393.808594-101.5-50.394532-91.683594 48.992188 97.085938 51.882812zm0 0"/>
								<path style="<?php if( !empty( $tl_icon_color ) ): ?>fill: <?php echo $tl_icon_color; ?>;<?php endif; ?>" d="m154.394531 327.039062 90.515625 48.371094 90.511719-48.371094-90.511719-44.941406zm0 0"/>
								<path style="<?php if( !empty( $tl_icon_color ) ): ?>fill: <?php echo $tl_icon_color; ?>;<?php endif; ?>" d="m259.902344 256.066406 229.914062 114.15625v-249.640625l-229.914062-120.582031zm0 0"/>
								<path style="<?php if( !empty( $tl_icon_color ) ): ?>fill: <?php echo $tl_icon_color; ?>;<?php endif; ?>" d="m229.914062 0-229.914062 120.582031v249.640625l229.914062-114.15625zm0 0"/>
								<path style="<?php if( !empty( $tl_icon_color ) ): ?>fill: <?php echo $tl_icon_color; ?>;<?php endif; ?>" d="m341.722656 461.144531-96.8125-51.738281-96.816406 51.738281 96.816406 50.855469zm0 0"/>
							</svg>
						</div>

						<div class="wpbh-box_head_text">
							<div class="wpbh-box_head shine" style="<?php if( !empty( $tl_header_color ) ): ?>background-color: <?php echo $tl_header_color; ?>;<?php endif; ?>"></div>
							<div class="wpbh-box_head_data shine" style="<?php if( !empty( $tl_text_alt_color ) ): ?>background-color: <?php echo $tl_text_alt_color; ?>;<?php endif; ?>"></div>
						</div>					

					</div>
					<div class="wpbh-preview-box_body">
					
						<div class="wpbh-box_body_text">
							<div class="shine" style="<?php if( !empty( $tl_text_color ) ): ?>background-color: <?php echo $tl_text_color; ?>;<?php endif; ?>"></div>
							<div class="shine" style="<?php if( !empty( $tl_text_color ) ): ?>background-color: <?php echo $tl_text_color; ?>;<?php endif; ?>"></div>
							<div class="shine" style="<?php if( !empty( $tl_text_color ) ): ?>background-color: <?php echo $tl_text_color; ?>;<?php endif; ?>"></div>
							<div class="shine" style="<?php if( !empty( $tl_text_color ) ): ?>background-color: <?php echo $tl_text_color; ?>;<?php endif; ?>"></div>
							<div class="shine" style="<?php if( !empty( $tl_text_color ) ): ?>background-color: <?php echo $tl_text_color; ?>;<?php endif; ?>"></div>
						</div>

					</div>
				</div>
			</div>
		</div>

	</div>
</div>