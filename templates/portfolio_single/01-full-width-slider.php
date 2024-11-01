<?php
	use WPBlocksHub\Helper\Media;
	use WPBlocksHub\Helper\Settings;
	get_header();
	the_post();
?>

<div class="wpbh-container">
	<div class="wpbh-row">
		<div class="wpbh-col-12">

			<div class="wpbh-portfolio-categories">
				<?php echo get_the_term_list( get_the_ID(), 'portfolio_cat', '', '', '' ); ?>
			</div>

			<h2 class="wpbh-single-post-title"><?php the_title(); ?></h2>

			<div class="wpbh-client-name">
				<strong><?php _e( 'Client', 'wp-blocks-hub'); ?>: <?php echo Settings::get_post_option( get_the_ID(), 'client_name' ); ?></strong>
			</div>

		</div>
	</div>
</div>

<?php
	$gallery = json_decode( Settings::get_post_option( get_the_ID(), 'gallery' ) );
	if( is_array( $gallery ) && ! empty( $gallery ) ):
?>
<div id="wpbh-portfolio-single-gallery" class="wpbh-gallery">
	<?php foreach( $gallery as $item ): ?>
		<div class="gallery-slide">
			<img src="<?php echo wp_get_attachment_image_url( $image = $item->id, 'full'); ?>" alt="">
		</div>
	<?php endforeach; ?>
</div>
<?php endif; ?>

<div class="wpbh-container">
	<div class="wpbh-row">
		<div class="wpbh-offset-3 wpbh-col-6">

			<?php the_content(); ?>

			<?php
				$project_url = Settings::get_post_option( get_the_ID(), 'project_url' );
				if( $project_url <> ''):
			?>
			<a href="<?php echo esc_attr( $project_url ); ?>" target="_blank" class="wpbh-visit-project"><?php _e( 'Visit project', 'wp-blocks-hub'); ?></a>
			<?php endif; ?>

		</div>
	</div>
</div>

<?php get_footer(); ?>