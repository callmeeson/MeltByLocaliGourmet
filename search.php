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

			<div class="search-results-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem;">
				<?php
				while ( have_posts() ) :
					the_post();
					$has_thumb = has_post_thumbnail();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); border: 1px solid var(--border); transition: all 0.3s ease; display: flex; flex-direction: column;"
						onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)'"
						onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)'">
						
						<?php if ( $has_thumb ) : ?>
							<div class="post-thumbnail" style="position: relative; padding-top: 66.67%; overflow: hidden;">
								<a href="<?php the_permalink(); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
									<?php the_post_thumbnail( 'medium_large', array( 'style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;' ) ); ?>
								</a>
							</div>
						<?php endif; ?>
						
						<div class="post-content" style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
							<div class="post-meta" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted-foreground); margin-bottom: 0.75rem;">
								<?php echo get_post_type() === 'product' ? 'Product' : get_the_date(); ?>
							</div>

							<h2 class="post-title" style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 0.75rem; line-height: 1.4;">
								<a href="<?php the_permalink(); ?>" style="color: var(--foreground); text-decoration: none;">
									<?php the_title(); ?>
								</a>
							</h2>
							
							<div class="post-excerpt" style="font-family: var(--font-body); font-size: 0.9rem; color: var(--muted-foreground); line-height: 1.6; margin-bottom: 1.5rem; flex: 1;">
								<?php echo wp_trim_words( get_the_excerpt(), 15 ); ?>
							</div>
							
							<a href="<?php the_permalink(); ?>" style="align-self: flex-start; display: inline-flex; align-items: center; gap: 0.5rem; color: var(--primary); font-family: var(--font-body); font-weight: 500; font-size: 0.9rem; transition: gap 0.2s ease;"
								onmouseover="this.style.gap='0.75rem'"
								onmouseout="this.style.gap='0.5rem'">
								<?php echo get_post_type() === 'product' ? 'View Product' : 'Read More'; ?>
								<i data-lucide="arrow-right" style="width: 1rem; height: 1rem;"></i>
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
