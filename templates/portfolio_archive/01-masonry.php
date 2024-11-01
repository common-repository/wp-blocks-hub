<?php
	use WPBlocksHub\Helper\Media;
	use WPBlocksHub\Helper\Utils;
	use WPBlocksHub\Helper\Settings;
	get_header();
?>

	<div id="wpbh-portfolio-archive-01-masonry" class="wpbh-container">
		<div class="wpbh-row">
			<div class="wpbh-col-12">

				<?php if( is_tax( 'portfolio_cat') ): $term = get_term_by( 'slug', get_query_var( 'portfolio_cat'), 'portfolio_cat' ); ?>
					<h1><?php printf( __( '%s posts in "%s" category', 'wp-blocks-hub'), Settings::get_option( 'portfolio_caption' ), $term->name ); ?></h1>
				<?php elseif( is_tax( 'portfolio_tag') ): $term = get_term_by( 'slug', get_query_var( 'portfolio_tag'), 'portfolio_tag' ); ?>
					<h1><?php printf( __( '%s posts, tagged by "%s"', 'wp-blocks-hub'), Settings::get_option( 'portfolio_caption' ), $term->name ); ?></h1>
				<?php else: ?>
					<h1><?php echo Settings::get_option( 'portfolio_caption' ); ?></h1>
				<?php endif; ?>

				<?php if( have_posts() ): ?>

					<div id="wpbh-portfolio-archive-01-masonry-grid" class="wpbh-masonry-grid wpbh-grid-effect-fade">
						<?php while( have_posts() ): the_post(); ?>
						<div class="wpbh-item">
							<div class="wpbh-inside <?php echo has_post_thumbnail() ? 'wpbh-with-photo' : 'wpbh-no-photo'; ?>">

								<div class="wpbh-image-container">
									<?php if( has_post_thumbnail() ): ?>
									<div class="wpbh-thumb">
										<a href="<?php the_permalink(); ?>">
											<?php
												$thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'full');
												echo Media::img( $thumb_url, 370, 230 );
											?>
										</a>
									</div>
									<a href="<?php echo esc_attr( $thumb_url ); ?>" class="wpbh-icon wpbh-icon-lightbox"></a>
									<a href="<?php the_permalink(); ?>" class="wpbh-icon wpbh-icon-link"></a>
									<?php endif; ?>
								</div>

								<a href="<?php the_permalink(); ?>" class="wpbh-post-title-link">
									<h2 class="wpbh-post-title"><?php the_title(); ?></h2>
								</a>

								<div class="wpbh-excerpt"><?php the_excerpt(); ?></div>

								<div class="wpbh-categories"><?php echo get_the_term_list( get_the_ID(), 'portfolio_cat', '', '', '' ); ?></div>

							</div>
						</div>
						<?php endwhile; ?>
					</div>

					<div class="wpbh-archive-pagination">
						<?php echo paginate_links(); ?>
					</div>

				<?php else: ?>

					<p class="wpbh-no-posts"><?php _e( 'No portfolio posts were found in this category', 'wp-blocks-hub'); ?></p>

				<?php endif; ?>

			</div>
		</div>
	</div>

<?php get_footer(); ?>