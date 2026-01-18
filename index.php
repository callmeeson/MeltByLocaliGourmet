<?php
/**
 * The main template file
 *
 * @package Melt_Custom
 */

get_header();
?>

<div class="section">
	<div class="section-container">
		<?php
		if ( have_posts() ) :
			?>
			<div class="posts-grid" style="display: grid; gap: 2rem;">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="post-thumbnail">
								<?php the_post_thumbnail( 'large' ); ?>
							</div>
						<?php endif; ?>
						
						<div class="post-content" style="padding: 2rem 0;">
							<h2 class="post-title" style="font-family: var(--font-serif); font-size: 2rem; margin-bottom: 1rem;">
								<a href="<?php the_permalink(); ?>" style="color: var(--foreground);">
									<?php the_title(); ?>
								</a>
							</h2>
							
							<div class="post-meta" style="color: var(--muted-foreground); font-family: var(--font-body); font-size: 0.875rem; margin-bottom: 1rem;">
								<span><?php echo esc_html( get_the_date() ); ?></span>
								<span> • </span>
								<span><?php echo esc_html( get_the_author() ); ?></span>
							</div>
							
							<div class="post-excerpt" style="font-family: var(--font-body); color: var(--muted-foreground); line-height: 1.7;">
								<?php the_excerpt(); ?>
							</div>
							
							<a href="<?php the_permalink(); ?>" style="display: inline-block; margin-top: 1rem; padding: 0.75rem 1.5rem; background-color: var(--primary); color: white; font-family: var(--font-body); font-weight: 500; transition: background-color 0.3s ease;"
								onmouseover="this.style.backgroundColor='var(--accent)';"
								onmouseout="this.style.backgroundColor='var(--primary)';">
								Read More
							</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<?php
			// Pagination
			the_posts_pagination(
				array(
					'mid_size'  => 2,
					'prev_text' => __( '&larr; Previous', 'melt-custom' ),
					'next_text' => __( 'Next &rarr;', 'melt-custom' ),
				)
			);
			?>

		<?php else : ?>
			<div class="no-posts" style="text-align: center; padding: 4rem 0;">
				<h2 style="font-family: var(--font-serif); font-size: 2rem; margin-bottom: 1rem;">
					<?php esc_html_e( 'Nothing Found', 'melt-custom' ); ?>
				</h2>
				<p style="font-family: var(--font-body); color: var(--muted-foreground);">
					<?php esc_html_e( 'Sorry, but nothing matched your search criteria. Please try again with different keywords.', 'melt-custom' ); ?>
				</p>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>
