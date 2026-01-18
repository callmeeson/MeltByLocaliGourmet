<?php
/**
 * The template for displaying single posts
 *
 * @package Melt_Custom
 */

get_header();
?>

<div class="section">
	<div class="section-container" style="max-width: 900px;">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="post-thumbnail" style="margin-bottom: 2rem;">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
				<?php endif; ?>
				
				<header class="post-header" style="margin-bottom: 2rem;">
					<h1 class="post-title" style="font-family: var(--font-serif); font-size: clamp(2rem, 4vw, 3rem); margin-bottom: 1rem;">
						<?php the_title(); ?>
					</h1>
					
					<div class="post-meta" style="color: var(--muted-foreground); font-family: var(--font-body); font-size: 0.875rem;">
						<span><?php echo esc_html( get_the_date() ); ?></span>
						<span> • </span>
						<span><?php echo esc_html( get_the_author() ); ?></span>
						<?php if ( has_category() ) : ?>
							<span> • </span>
							<?php the_category( ', ' ); ?>
						<?php endif; ?>
					</div>
				</header>
				
				<div class="post-content" style="font-family: var(--font-body); line-height: 1.8; color: var(--foreground);">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'melt-custom' ),
							'after'  => '</div>',
						)
					);
					?>
				</div>

				<?php if ( has_tag() ) : ?>
					<footer class="post-footer" style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border);">
						<div class="post-tags">
							<?php the_tags( '<span style="font-family: var(--font-body); color: var(--muted-foreground);">Tags: </span>', ', ' ); ?>
						</div>
					</footer>
				<?php endif; ?>
			</article>

			<?php
			// Post navigation
			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'melt-custom' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'melt-custom' ) . '</span> <span class="nav-title">%title</span>',
				)
			);

			// Comments
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile;
		?>
	</div>
</div>

<?php get_footer(); ?>
