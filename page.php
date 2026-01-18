<?php
/**
 * The template for displaying all pages
 *
 * @package Melt_Custom
 */

get_header();
?>

<div class="section">
	<div class="section-container" style="max-width: 1000px;">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="page-header" style="margin-bottom: 3rem; text-align: center;">
					<h1 class="page-title" style="font-family: var(--font-serif); font-size: clamp(2.5rem, 5vw, 4rem);">
						<?php the_title(); ?>
					</h1>
				</header>
				
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="page-thumbnail" style="margin-bottom: 3rem;">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
				<?php endif; ?>
				
				<div class="page-content" style="font-family: var(--font-body); line-height: 1.8; color: var(--foreground);">
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
			</article>

			<?php
			// Comments
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile;
		?>
	</div>
</div>

<?php get_footer(); ?>
