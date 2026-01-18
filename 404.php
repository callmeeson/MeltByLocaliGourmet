<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Melt_Custom
 */

get_header();
?>

<div class="section">
	<div class="section-container" style="text-align: center; padding: 6rem 2rem;">
		<div class="error-404-content">
			<h1 style="font-family: var(--font-serif); font-size: clamp(4rem, 10vw, 8rem); color: var(--primary); margin-bottom: 1rem;">
				404
			</h1>
			<h2 style="font-family: var(--font-serif); font-size: clamp(1.5rem, 3vw, 2.5rem); margin-bottom: 2rem;">
				<?php esc_html_e( 'Oops! Page Not Found', 'melt-custom' ); ?>
			</h2>
			<p style="font-family: var(--font-body); color: var(--muted-foreground); font-size: 1.125rem; max-width: 600px; margin: 0 auto 3rem;">
				<?php esc_html_e( 'The page you are looking for might have been moved, deleted, or possibly never existed.', 'melt-custom' ); ?>
			</p>
			
			<div style="display: flex; flex-direction: column; gap: 1rem; align-items: center; max-width: 400px; margin: 0 auto;">
				<button onclick="location.href='<?php echo esc_url( home_url( '/' ) ); ?>'" style="width: 100%; padding: 1rem 2rem; background-color: var(--primary); color: white; font-family: var(--font-body); font-weight: 500; transition: all 0.3s ease;"
					onmouseover="this.style.backgroundColor='var(--accent)'"
					onmouseout="this.style.backgroundColor='var(--primary)'">
					<?php esc_html_e( 'Go to Homepage', 'melt-custom' ); ?>
				</button>
				
				<button onclick="location.href='<?php echo esc_url( home_url( '/shop' ) ); ?>'" style="width: 100%; padding: 1rem 2rem; border: 2px solid var(--primary); color: var(--primary); background-color: transparent; font-family: var(--font-body); font-weight: 500; transition: all 0.3s ease;"
					onmouseover="this.style.backgroundColor='var(--primary)'; this.style.color='white'"
					onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--primary)'">
					<?php esc_html_e( 'Browse Our Cakes', 'melt-custom' ); ?>
				</button>
			</div>

			<!-- Search Form -->
			<div style="margin-top: 4rem; max-width: 500px; margin-left: auto; margin-right: auto;">
				<h3 style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 1rem;">
					<?php esc_html_e( 'Or try searching:', 'melt-custom' ); ?>
				</h3>
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
