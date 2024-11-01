<?php
	/**
	 * @accept $data
	 */
?>
<div id="poststuff">
	<form method="POST" id="wpbh-settings-form" action="">

		<div class="wpbh-grid side">
			<div class="wpbh-grid__col">
			
				<?php
				
					WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Settings/Demo_Data', $data );

					WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Settings/API_Settings', $data );

					WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Settings/CPT_Settings', $data );

					WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Settings/Portfolio_Settings', $data );

					WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Settings/Responsive_Settings', $data );

					WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Settings/Optimization', $data );

					WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Settings/Fonts', $data );

					WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Settings/Colors', $data );

					WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Settings/LightThemeSettings', $data );

					WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Settings/DarkThemeSettings', $data );

					WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Settings/Custom_Code', $data );

					WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Settings/SystemStatus', $data );

					WPBH()->View->Load( 'App/View/Backend/Settings/Pages/Settings/Tools', $data );
					
				?>

			</div>
			<div class="wpbh-grid__col">
			
				<div id="wpbh-save-settings-box" class="stuffbox wpbh-stick-in-parent">
					<h2><?php _e( 'Blocks Hub Settings', 'wp-blocks-hub'); ?></h2>
					<div class="inside">
						<div id="minor-publishing">
							<div id="misc-publishing-actions">

								<div id="wpbh-settings-toc" class="misc-pub-section">
								</div>

							</div>
						</div>
						<div id="major-publishing-actions">
							<img id="loading-indicator" src="<?php echo WPBH()->Config['plugin_url']; ?>/assets/images/admin/loader.svg" alt="">
							<div id="publishing-action">
								<input data-txt-saved="<?php esc_attr_e( 'Successfully!', 'wp-blocks-hub'); ?>" data-txt-default="<?php esc_attr_e( 'Save settings', 'wp-blocks-hub'); ?>" id="save" class="button button-primary button-large" type="submit" value="<?php esc_attr_e( 'Save settings', 'wp-blocks-hub'); ?>">
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>

			</div>
		</div>

	</form>
</div>