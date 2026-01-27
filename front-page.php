<?php
/**
 * Template Name: Homepage
 * 
 * The front page template file
 *
 * @package Melt_Custom
 */

get_header();
?>

<!-- Hero Section -->
<section class="hero-section" id="home">
	<div class="hero-slideshow">
		<?php
		$slides = melt_get_hero_slides();
		foreach ( $slides as $index => $slide ) :
			?>
			<div class="hero-slide <?php echo 0 === $index ? 'active' : ''; ?>" data-slide="<?php echo esc_attr( $index ); ?>">
				<img src="<?php echo esc_url( $slide['image'] ); ?>" alt="<?php echo esc_attr( $slide['title'] ); ?>" loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>">
			</div>
		<?php endforeach; ?>
	</div>

	<div class="hero-overlay"></div>

	<!-- Navigation Buttons -->
	<button class="hero-nav-button prev" onclick="prevSlide()" aria-label="Previous slide">
		<i data-lucide="chevron-left"></i>
	</button>
	<button class="hero-nav-button next" onclick="nextSlide()" aria-label="Next slide">
		<i data-lucide="chevron-right"></i>
	</button>

	<!-- Slide Indicators -->
	<div class="hero-indicators">
		<?php foreach ( $slides as $index => $slide ) : ?>
			<button class="hero-indicator <?php echo 0 === $index ? 'active' : ''; ?>" onclick="goToSlide(<?php echo esc_attr( $index ); ?>)" aria-label="Go to slide <?php echo esc_attr( $index + 1 ); ?>"></button>
		<?php endforeach; ?>
	</div>

	<!-- Hero Content -->
	<div class="hero-content">
		<h1 class="hero-title" id="heroTitle"><?php echo esc_html( $slides[0]['title'] ); ?></h1>
		<p class="hero-subtitle" id="heroSubtitle"><?php echo esc_html( $slides[0]['subtitle'] ); ?></p>
		<div class="hero-buttons">
			<button class="hero-button primary" onclick="location.href='<?php echo esc_url( home_url( '/shop' ) ); ?>'">
				View Collection
			</button>
			<button class="hero-button secondary" onclick="location.href='<?php echo esc_url( home_url( '/custom-orders' ) ); ?>'">
				Custom Orders
			</button>
		</div>
	</div>
</section>

<!-- Cake Collections Gallery Section -->
<section class="cake-gallery-section">
	<div class="cake-gallery-container">
		<div class="gallery-header">
			<p>Explore Our Collections</p>
			<h2>Curated Cake Collections</h2>
			<p class="gallery-description">Each collection is thoughtfully crafted for different occasions and tastes</p>
		</div>

		<div class="gallery-grid">
			<?php
			// Custom styles for the bento grid
			?>
			<style>
				.gallery-grid {
					display: grid;
					grid-template-columns: 1fr;
					gap: 1rem;
				}

				.gallery-item {
					position: relative;
					overflow: hidden;
					border-radius: 0.5rem;
					isolation: isolate;
					height: 300px; /* Default height for mobile */
				}

				@media (min-width: 768px) {
					.gallery-grid {
						grid-template-columns: repeat(3, 1fr);
						grid-auto-rows: 300px;
						gap: 1.5rem;
					}
					
					/* Reset height for grid items to fill their cells */
					.gallery-item {
						height: 100%;
					}

					/* Bento Grid Layout Configuration for 6 items */
					/* Row 1: [Tall] [Wide.....] */
					/* Row 2: [Tall] [Std] [Std] */
					/* Row 3: [Wide.....] [Std] */
					
					.gallery-item.item-0 { grid-row: span 2; } /* Tall */
					.gallery-item.item-1 { grid-column: span 2; } /* Wide */
					/* item-2 and item-3 are standard 1x1 */
					.gallery-item.item-4 { grid-column: span 2; } /* Wide */
					/* item-5 is standard 1x1 */
				}

				.gallery-item-image {
					width: 100%;
					height: 100%;
					object-fit: cover;
					transition: transform 0.7s ease;
				}

				.gallery-item:hover .gallery-item-image {
					transform: scale(1.05);
				}

				.gallery-overlay {
					position: absolute;
					inset: 0;
					background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 50%, transparent 100%);
					display: flex;
					flex-direction: column;
					justify-content: flex-end;
					padding: 1.5rem;
					z-index: 10;
				}

				.gallery-title {
					color: white;
					font-family: var(--font-serif);
					font-size: 1.5rem;
					margin-bottom: 0.25rem;
					transform: translateY(0);
					transition: transform 0.3s ease;
				}
				
				.gallery-subtitle {
					color: rgba(255, 255, 255, 0.9);
					font-family: var(--font-body);
					font-size: 0.875rem;
					opacity: 0;
					transform: translateY(10px);
					transition: all 0.3s ease;
					height: 0;
					overflow: hidden;
				}

				.gallery-item:hover .gallery-title {
					transform: translateY(-5px);
				}

				.gallery-item:hover .gallery-subtitle {
					opacity: 1;
					transform: translateY(0);
					height: auto;
				}
			</style>

			<?php
			// Fetch WooCommerce products to display their images
			if ( class_exists( 'WooCommerce' ) ) {
				$products = wc_get_products( array(
					'limit'  => 6,
					'orderby' => 'date',
					'order'  => 'DESC',
					'status' => 'publish',
				) );

				if ( $products ) {
					$index = 0;
					foreach ( $products as $product ) {
						$image_id = $product->get_image_id();
						if ( $image_id ) {
							$image_url = wp_get_attachment_image_url( $image_id, 'large' );
							// Determine class based on index
							$class = 'item-' . $index;
							?>
							<div class="gallery-item <?php echo esc_attr($class); ?>">
								<a href="<?php echo esc_url( $product->get_permalink() ); ?>" style="display: block; width: 100%; height: 100%;">
									<img 
										src="<?php echo esc_url( $image_url ); ?>" 
										alt="<?php echo esc_attr( $product->get_name() ); ?>"
										class="gallery-item-image"
										loading="lazy"
									>
									<div class="gallery-overlay">
										<h3 class="gallery-title"><?php echo esc_html( $product->get_name() ); ?></h3>
										<div class="gallery-subtitle">View Collection</div>
									</div>
								</a>
							</div>
							<?php
							$index++;
						}
					}
				} else {
					// Fallback if no products found
					echo '<p style="grid-column: 1 / -1; text-align: center; padding: 2rem;">No products available. Please add products to your store.</p>';
				}
			} else {
				// WooCommerce not active fallback
				echo '<p style="grid-column: 1 / -1; text-align: center; padding: 2rem;">WooCommerce is not active.</p>';
			}
			?>
		</div>
	</div>
</section>

<?php
/*
<!-- Artisans at Work Section -->
<section class="section fade-in-section" style="background-color: rgba(248, 248, 248, 0.3);">
	<div class="section-container">
		<div class="section-header">
			<p style="color: var(--primary); margin-bottom: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; font-size: 0.875rem; font-family: var(--font-body);">
				Behind The Scenes
			</p>
			<h2 class="section-title">Watch Our Artisans at Work</h2>
			<p class="section-description">Every cake is handcrafted with precision, passion, and years of expertise</p>
		</div>

		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem;">
			<?php
			$videos = array(
				array(
					'title'       => 'Crafting Wedding Elegance',
					'description' => 'Watch our master baker create a 3-tier wedding masterpiece',
					'thumbnail'   => 'https://images.unsplash.com/photo-1607478900766-efe13248b125?w=800',
					'duration'    => '8:45',
				),
				array(
					'title'       => 'Chocolate Truffle Process',
					'description' => 'From melting Belgian chocolate to the final decoration',
					'thumbnail'   => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=800',
					'duration'    => '6:20',
				),
				array(
					'title'       => 'Fresh Fruit Assembly',
					'description' => 'See how we layer fresh strawberries and cream',
					'thumbnail'   => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=800',
					'duration'    => '5:15',
				),
				array(
					'title'       => 'Gold Leaf Application',
					'description' => 'The delicate art of luxury cake decoration',
					'thumbnail'   => 'https://images.unsplash.com/photo-1558636508-e0db3814bd1d?w=800',
					'duration'    => '4:30',
				),
			);

			foreach ( $videos as $index => $video ) :
				?>
				<div class="video-card fade-in-item" style="position: relative; cursor: pointer;">
					<div style="position: relative; overflow: hidden; aspect-ratio: 16/9; background-color: black;">
						<img 
							src="<?php echo esc_url( $video['thumbnail'] ); ?>" 
							alt="<?php echo esc_attr( $video['title'] ); ?>"
							style="width: 100%; height: 100%; object-fit: cover; transition: all 0.7s ease;"
							onmouseover="this.style.transform='scale(1.1)'; this.style.opacity='0.75';"
							onmouseout="this.style.transform='scale(1)'; this.style.opacity='1';"
						>
						
						<!-- Play Button -->
						<div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;">
							<div class="play-button" style="width: 5rem; height: 5rem; border-radius: 50%; background-color: rgba(255, 255, 255, 0.9); display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);"
								onmouseover="this.style.backgroundColor='var(--primary)'; this.style.transform='scale(1.1)';"
								onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.9)'; this.style.transform='scale(1)';">
								<i data-lucide="play" style="width: 2rem; height: 2rem; color: var(--primary); margin-left: 0.25rem; transition: color 0.3s ease;"></i>
							</div>
						</div>

						<!-- Duration Badge -->
						<div class="video-duration-badge" style="position: absolute; bottom: 1.5rem; right: 1.5rem; background-color: rgba(0, 0, 0, 0.8); color: white; padding: 0.5rem 1rem; font-family: var(--font-body); font-weight: 600;">
							<?php echo esc_html( $video['duration'] ); ?>
						</div>

						<!-- Text Overlay -->
						<div class="video-card-overlay" style="position: absolute; bottom: 1.5rem; left: 1.5rem; color: white; max-width: 28rem;">
							<h3 style="font-size: 1.5rem; margin-bottom: 0.5rem; font-family: var(--font-serif); transition: color 0.3s ease;">
								<?php echo esc_html( $video['title'] ); ?>
							</h3>
							<p style="color: rgba(255, 255, 255, 0.9); font-family: var(--font-elegant);">
								<?php echo esc_html( $video['description'] ); ?>
							</p>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div style="text-align: center; margin-top: 3rem;" class="fade-in-section">
			<button style="padding: 1rem 2rem; background-color: var(--primary); color: white; font-family: var(--font-body); font-weight: 600; transition: all 0.3s ease; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);"
				onmouseover="this.style.backgroundColor='var(--accent)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';"
				onmouseout="this.style.backgroundColor='var(--primary)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';">
				View All Videos
			</button>
		</div>
	</div>
</section>
*/
?>

<!-- Seasonal Cakes Section -->
<?php
// You can create a custom query here to fetch WooCommerce products or custom posts for seasonal cakes
if ( function_exists( 'wc_get_products' ) ) :
	?>
	<section class="section fade-in-section" style="background-color: white;">
		<div class="section-container">
			<div class="section-header">
				<p style="color: var(--primary); margin-bottom: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; font-size: 0.875rem; font-family: var(--font-body);">
					Limited Edition
				</p>
				<h2 class="section-title">Seasonal Delights</h2>
				<p class="section-description">Exclusive flavors available for a limited time</p>
			</div>

			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
				<?php
				$seasonal_products = wc_get_products(
					array(
						'limit'    => 4,
						'orderby'  => 'date',
						'order'    => 'DESC',
						'category' => array( 'seasonal' ), // Filter by seasonal category
						'status'   => 'publish',
					)
				);

				if ( $seasonal_products ) :
					foreach ( $seasonal_products as $product ) :
						?>
						<div class="product-card fade-in-item" style="position: relative; cursor: pointer; background-color: white; border: 1px solid var(--border); transition: all 0.3s ease;"
							onmouseover="this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)'; this.style.transform='translateY(-4px)';"
							onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)';">
							<div style="overflow: hidden;">
								<?php echo wp_kses_post( $product->get_image( 'medium' ) ); ?>
							</div>
							<div style="padding: 1.5rem;">
								<h3 style="font-size: 1.25rem; margin-bottom: 0.5rem; font-family: var(--font-serif);">
									<?php echo esc_html( $product->get_name() ); ?>
								</h3>
								<p style="color: var(--muted-foreground); margin-bottom: 1rem; font-family: var(--font-body);">
									<?php echo wp_kses_post( wp_trim_words( $product->get_short_description(), 15 ) ); ?>
								</p>
								<div style="display: flex; justify-content: space-between; align-items: center;">
									<span style="color: var(--primary); font-size: 1.25rem; font-weight: 600; font-family: var(--font-body);">
										<?php echo wp_kses_post( $product->get_price_html() ); ?>
									</span>
									<button onclick="location.href='<?php echo esc_url( $product->get_permalink() ); ?>'" style="padding: 0.5rem 1rem; background-color: var(--primary); color: white; font-family: var(--font-body); font-weight: 500; transition: all 0.3s ease;"
										onmouseover="this.style.backgroundColor='var(--accent)';"
										onmouseout="this.style.backgroundColor='var(--primary)';">
										View Details
									</button>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php get_footer(); ?>
