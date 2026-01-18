<?php
/**
 * The template for displaying search results pages
 *
 * @package Melt_Custom
 */

get_header();
?>

<div class="section">
	<div class="section-container">
		<header class="page-header" style="margin-bottom: 3rem; text-align: center;">
			<h1 class="page-title" style="font-family: var(--font-serif); font-size: clamp(2rem, 4vw, 3rem); margin-bottom: 1rem;">
				<?php
				printf(
					/* translators: %s: search query. */
					esc_html__( 'Search Results for: %s', 'melt-custom' ),
					'<span style="color: var(--primary);">' . get_search_query() . '</span>'
				);
				?>
			</h1>
		</header>

		<?php if ( have_posts() ) : ?>

			<div class="search-results" style="display: grid; gap: 2rem;">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="padding: 2rem; border: 1px solid var(--border); transition: all 0.3s ease;"
						onmouseover="this.style.boxShadow='0 10px 25px rgba(0, 0, 0, 0.1)'; this.style.borderColor='var(--primary)'"
						onmouseout="this.style.boxShadow='none'; this.style.borderColor='var(--border)'">
						
						<div style="display: flex; gap: 2rem; align-items: start;">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="post-thumbnail" style="flex-shrink: 0; width: 200px;">
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail( 'medium' ); ?>
									</a>
								</div>
							<?php endif; ?>
							
							<div style="flex: 1;">
								<h2 class="post-title" style="font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 0.5rem;">
									<a href="<?php the_permalink(); ?>" style="color: var(--foreground); transition: color 0.3s ease;"
										onmouseover="this.style.color='var(--primary)'"
										onmouseout="this.style.color='var(--foreground)'">
										<?php the_title(); ?>
									</a>
								</h2>
								
								<div class="post-meta" style="color: var(--muted-foreground); font-family: var(--font-body); font-size: 0.875rem; margin-bottom: 1rem;">
									<span><?php echo esc_html( get_the_date() ); ?></span>
									<span> • </span>
									<span><?php echo esc_html( get_the_author() ); ?></span>
									<?php if ( 'post' === get_post_type() && has_category() ) : ?>
										<span> • </span>
										<?php the_category( ', ' ); ?>
									<?php endif; ?>
								</div>
								
								<div class="post-excerpt" style="font-family: var(--font-body); color: var(--muted-foreground); line-height: 1.7; margin-bottom: 1rem;">
									<?php the_excerpt(); ?>
								</div>
								
								<a href="<?php the_permalink(); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--primary); font-family: var(--font-body); font-weight: 500; transition: gap 0.3s ease;"
									onmouseover="this.style.gap='1rem'"
									onmouseout="this.style.gap='0.5rem'">
									<?php esc_html_e( 'Read More', 'melt-custom' ); ?>
									<i data-lucide="arrow-right" style="width: 1rem; height: 1rem;"></i>
								</a>
							</div>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<?php
			// Pagination
			the_posts_pagination(
				array(
					'mid_size'  => 2,
					'prev_text' => sprintf(
						'<i data-lucide="chevron-left" style="width: 1rem; height: 1rem;"></i> %s',
						__( 'Previous', 'melt-custom' )
					),
					'next_text' => sprintf(
						'%s <i data-lucide="chevron-right" style="width: 1rem; height: 1rem;"></i>',
						__( 'Next', 'melt-custom' )
					),
				)
			);
			?>

		<?php else : ?>

			<div class="no-results" style="text-align: center; padding: 4rem 0;">
				<i data-lucide="search-x" style="width: 4rem; height: 4rem; color: var(--muted-foreground); margin: 0 auto 1.5rem; opacity: 0.3;"></i>
				<h2 style="font-family: var(--font-serif); font-size: 2rem; margin-bottom: 1rem;">
					<?php esc_html_e( 'Nothing Found', 'melt-custom' ); ?>
				</h2>
				<p style="font-family: var(--font-body); color: var(--muted-foreground); margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
					<?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'melt-custom' ); ?>
				</p>
				
				<div style="max-width: 500px; margin: 0 auto 3rem;">
					<?php get_search_form(); ?>
				</div>
				
				<div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
					<button onclick="location.href='<?php echo esc_url( home_url( '/' ) ); ?>'" style="padding: 0.75rem 1.5rem; background-color: var(--primary); color: white; font-family: var(--font-body); font-weight: 500; transition: background-color 0.3s ease;"
						onmouseover="this.style.backgroundColor='var(--accent)'"
						onmouseout="this.style.backgroundColor='var(--primary)'">
						<?php esc_html_e( 'Go to Homepage', 'melt-custom' ); ?>
					</button>
					
					<?php if ( function_exists( 'WC' ) ) : ?>
						<button onclick="location.href='<?php echo esc_url( home_url( '/shop' ) ); ?>'" style="padding: 0.75rem 1.5rem; border: 2px solid var(--primary); color: var(--primary); background-color: transparent; font-family: var(--font-body); font-weight: 500; transition: all 0.3s ease;"
							onmouseover="this.style.backgroundColor='var(--primary)'; this.style.color='white'"
							onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--primary)'">
							<?php esc_html_e( 'Browse Our Cakes', 'melt-custom' ); ?>
						</button>
					<?php endif; ?>
				</div>
			</div>

		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>
