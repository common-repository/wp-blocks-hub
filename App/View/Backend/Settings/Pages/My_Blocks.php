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

			<div class="wpbh-stick-in-parent meta-box-sortables ui-sortable">

				<!--
					Filter
				-->
				<div class="postbox">
					<button class="handlediv" type="button" aria-expanded="true">
						<span class="toggle-indicator"></span>
					</button>
					<h2><span><?php _e( 'Filter', 'wp-blocks-hub'); ?></span></h2>
					<div id="wpbh-myblocks-filters" class="inside">

						<div class="categorydiv">
							<div class="tabs-panel">
								<div class="categorychecklist form-no-clear">
									<ul>
										<li class="selectit">
											<label><input type="radio" name="filter" checked="checked" value=""> <?php _e( 'All my blocks', 'wp-blocks-hub'); ?></label>
										</li>
										<li class="selectit">
											<label><input type="radio" name="filter" value="active"> <?php _e( 'Active only', 'wp-blocks-hub'); ?></label>
										</li>
										<li class="selectit">
											<label><input type="radio" name="filter" value="inactive"> <?php _e( 'Inactive only', 'wp-blocks-hub'); ?></label>
										</li>
									</ul>
								</div>
							</div>					
						</div>

					</div>

					<div id="major-publishing-actions">
						<img id="loading-indicator" src="<?php echo WPBH()->Config['plugin_url']; ?>/assets/images/admin/loader.svg" alt="">
						<div id="publishing-action">
							<input data-txt-inprogress="<?php esc_attr_e( 'Filtering...', 'wp-blocks-hub'); ?>" data-txt-default="<?php esc_attr_e( 'Filter Blocks', 'wp-blocks-hub'); ?>" id="save" class="button button-primary button-large" type="submit" value="<?php esc_attr_e( 'Filter Blocks', 'wp-blocks-hub'); ?>">
						</div>
						<div class="clear"></div>
					</div>

				</div>

			</div>

		</form>

	</div>


	<div id="wpbh-dialog" class="hidden"></div>

</div>