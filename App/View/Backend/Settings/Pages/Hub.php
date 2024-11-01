<?php
	/**
	 * @accept $data
	 */
?>

<div id="wpbh-hub">

	<div id="poststuff">

		<div id="post-body" class="metabox-holder blocks-holder">
		
			<div id="wpbh-hub-blocks">
				<div class="hub-loading-indicator"><img src="<?php echo WPBH()->Config['plugin_url']; ?>/assets/images/admin/loader.svg" alt=""></div>
			</div>

		</div>

		<form id="wpbh-search-form" method="post" action="" class="postbox-container">

			<div id="wpbh-filters-panel" class="wpbh-stick-in-parent meta-box-sortables ui-sortable">

				<!--
					Hub stats
				-->
				<div class="postbox">
					<button class="handlediv" type="button" aria-expanded="true">
						<span class="toggle-indicator"></span>
					</button>
					<h2><span><?php _e( 'Hub Stats', 'wp-blocks-hub'); ?></span></h2>
					<div id="wpbh-hub-status" class="inside">

						<div class="hub-loading-indicator"><img src="<?php echo WPBH()->Config['plugin_url']; ?>/assets/images/admin/loader.svg" alt=""></div>

					</div>
				</div>

				<!--
					Page builder
				-->
				<div class="postbox">
					<button class="handlediv" type="button" aria-expanded="true">
						<span class="toggle-indicator"></span>
					</button>
					<h2><span><?php _e( 'Compatibility', 'wp-blocks-hub'); ?></span></h2>
					<div id="wpbh-hub-compatibility" class="inside">

						<div class="hub-loading-indicator"><img src="<?php echo WPBH()->Config['plugin_url']; ?>/assets/images/admin/loader.svg" alt=""></div>

					</div>
				</div>

				<!--
					Categories
				-->
				<div class="postbox">
					<button class="handlediv" type="button" aria-expanded="true">
						<span class="toggle-indicator"></span>
					</button>
					<h2><span><?php _e( 'Categories', 'wp-blocks-hub'); ?></span></h2>
					<div id="wpbh-hub-categories" class="inside">

						<div class="hub-loading-indicator"><img src="<?php echo WPBH()->Config['plugin_url']; ?>/assets/images/admin/loader.svg" alt=""></div>

					</div>
				</div>

				<!--
					Tags
				-->
				<div class="postbox">
					<button class="handlediv" type="button" aria-expanded="true">
						<span class="toggle-indicator"></span>
					</button>
					<h2><span><?php _e( 'Tags', 'wp-blocks-hub'); ?></span></h2>
					<div id="wpbh-hub-tags" class="inside">

						<div class="hub-loading-indicator"><img src="<?php echo WPBH()->Config['plugin_url']; ?>/assets/images/admin/loader.svg" alt=""></div>

					</div>
				</div>

				<!--
					Filter button
				-->
				<div id="wpbh-hub-action-btn">
					<div id="submitdiv" class="postbox">
						<div class="inside">

							<div id="major-publishing-actions" class="single">
								<img id="loading-indicator" src="<?php echo WPBH()->Config['plugin_url']; ?>/assets/images/admin/loader.svg" alt="">
								<div id="publishing-action">
									<input data-txt-inprogress="<?php esc_attr_e( 'Searching...', 'wp-blocks-hub'); ?>" data-txt-default="<?php esc_attr_e( 'Find Blocks', 'wp-blocks-hub'); ?>" id="save" class="button button-primary button-large" type="submit" value="<?php esc_attr_e( 'Find Blocks', 'wp-blocks-hub'); ?>">
								</div>
								<div class="clear"></div>
							</div>

						</div>
					</div>
				</div>

			</div>

		</form>

	</div>


	<div id="wpbh-dialog" class="hidden"></div>

</div>