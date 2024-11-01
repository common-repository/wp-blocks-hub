<?php
	/**
	 * @accept $data
	 */
?>
<div class="wpbh-section">
	<div class="wpbh-section-launched"><?php printf( '<span>%s</span> <strong>%s</strong>', __( 'Hub launched', 'wp-blocks-hub'), human_time_diff( $data->status->hub_launched, current_time('timestamp') ) . ' ' . __( 'ago', 'wp-blocks-hub') ); ?></div>
</div>
<div class="wpbh-section">
	<div class="wpbh-section-total">
		<?php printf( _n( '<strong>%s</strong> <span>block in the hub</span>', '<strong>%s</strong> <span>total blocks in hub</span>', $data->status->total_blocks, 'wp-blocks-hub' ), $data->status->total_blocks ); ?>
	</div>
</div>