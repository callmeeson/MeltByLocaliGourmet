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
				<img 
					src="<?php echo esc_url( $slide['image'] ); ?>" 
					alt="<?php echo esc_attr( $slide['title'] ); ?>" 
					loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>"
					onerror="this.onerror=null;this.src='<?php echo esc_url( get_template_directory_uri() . '/assets/images/hero/hero-' . (($index % 4) + 1) . '.jpeg' ); ?>';"
				>
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
			<button class="hero-button secondary" onclick="location.href='<?php echo esc_url( home_url( '/custom-cake' ) ); ?>'">
				Custom Orders
			</button>
		</div>
	</div>
</section>

<!-- Cake Collections Gallery Section -->
<section class="cake-gallery-section section fade-in-section">
	<div class="section-container">
		<div class="gallery-header text-center">
			<span class="gallery-eyebrow">Explore Our Collections</span>
			<h2 class="gallery-heading">Curated Cake Collections</h2>
			<div class="gallery-divider"></div>
			<p class="gallery-description">Each collection is thoughtfully crafted for different occasions and tastes</p>
		</div>

		<div class="gallery-wrapper">
			<?php
			// Fetch WooCommerce products
			if ( class_exists( 'WooCommerce' ) ) {
				$products = wc_get_products( array(
					'limit'  => 6,
					'orderby' => 'date',
					'order'  => 'DESC',
					'status' => 'publish',
				) );

				if ( $products ) : ?>
					<div class="gallery-grid">
						<?php 
						$index = 0;
						foreach ( $products as $product ) {
							$image_id = $product->get_image_id();
							$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : wc_placeholder_img_src();
							$class = 'item-' . $index;
							?>
							<div class="gallery-item <?php echo esc_attr($class); ?>">
								<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="gallery-link" aria-label="<?php echo esc_attr( $product->get_name() ); ?>">
									<div class="gallery-image-wrapper">
										<img 
											src="<?php echo esc_url( $image_url ); ?>" 
											alt="<?php echo esc_attr( $product->get_name() ); ?>"
											class="gallery-item-image"
											loading="lazy"
										>
									</div>
									<div class="gallery-overlay">
										<div class="gallery-content">
											<h3 class="gallery-title"><?php echo esc_html( $product->get_name() ); ?></h3>
											<span class="gallery-action">
												View Details
												<!-- Simple arrow icon using CSS or inline SVG if Lucide not available, but Lucide is used elsewhere -->
												<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="gallery-icon"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
											</span>
										</div>
									</div>
								</a>
							</div>
							<?php
							$index++;
						}
						?>
					</div>
				<?php else : ?>
					<p style="text-align: center; padding: 3rem; color: var(--muted-foreground);">No products available. Please add products to your store.</p>
				<?php endif;
			} else {
				echo '<p style="text-align: center; padding: 3rem; color: var(--muted-foreground);">WooCommerce is not active.</p>';
			}
			?>
		</div>
	</div>

	<style>
		/* Scoped Styles for Gallery Section */
		.cake-gallery-section {
			background-color: var(--background);
			padding-top: 4rem;
			padding-bottom: 4rem;
		}

		.gallery-header {
			margin-bottom: 3.5rem;
			position: relative;
			text-align: center;
		}

		.gallery-eyebrow {
			display: block;
			font-family: var(--font-body);
			font-size: 0.75rem;
			text-transform: uppercase;
			letter-spacing: 0.2em;
			color: var(--primary);
			margin-bottom: 1rem;
			font-weight: 600;
		}
		
		.gallery-heading {
			font-family: var(--font-serif);
			font-size: clamp(2rem, 5vw, 3rem);
			color: var(--foreground);
			margin-bottom: 1.5rem;
			line-height: 1.1;
		}

		.gallery-divider {
			width: 60px;
			height: 3px;
			background-color: var(--accent);
			margin: 0 auto 1.5rem;
			border-radius: 2px;
			opacity: 0.7;
		}

		.gallery-description {
			font-family: var(--font-body);
			color: var(--muted-foreground);
			font-size: 1.125rem;
			max-width: 600px;
			margin: 0 auto;
			line-height: 1.6;
		}

		/* Grid Layout */
		.gallery-grid {
			display: grid;
			grid-template-columns: repeat(12, 1fr);
			gap: 1.5rem;
		}

		.gallery-item {
			position: relative;
			border-radius: 0.75rem;
			overflow: hidden;
			background-color: var(--muted);
			box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
			transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
			/* Default aspect ratio safely handled by grid, but fallback: */
			aspect-ratio: 4/5;
		}

		.gallery-item:hover {
			transform: translateY(-5px);
			box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
		}

		.gallery-link {
			display: block;
			width: 100%;
			height: 100%;
			position: relative;
		}

		.gallery-image-wrapper {
			width: 100%;
			height: 100%;
		}

		.gallery-item-image {
			width: 100%;
			height: 100%;
			object-fit: cover;
			transition: transform 0.7s ease-out;
		}

		.gallery-item:hover .gallery-item-image {
			transform: scale(1.08);
		}

		.gallery-overlay {
			position: absolute;
			inset: 0;
			background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 40%, transparent 100%);
			display: flex;
			flex-direction: column;
			justify-content: flex-end;
			padding: 1.5rem;
			opacity: 1; /* Always visible but enhanced on hover */
		}

		.gallery-content {
			display: flex;
			flex-direction: column;
			gap: 0.5rem;
			transform: translateY(10px);
			transition: transform 0.3s ease;
		}

		.gallery-item:hover .gallery-content {
			transform: translateY(0);
		}

		.gallery-title {
			color: white;
			font-family: var(--font-serif);
			font-size: 1.35rem;
			font-weight: 500;
			margin: 0;
			line-height: 1.2;
			text-shadow: 0 2px 4px rgba(0,0,0,0.3);
		}

		.gallery-action {
			display: inline-flex;
			align-items: center;
			gap: 0.5rem;
			color: var(--accent);
			font-family: var(--font-body);
			font-weight: 600;
			font-size: 0.875rem;
			letter-spacing: 0.05em;
			text-transform: uppercase;
			opacity: 0;
			transform: translateY(10px);
			transition: all 0.3s ease;
		}

		.gallery-item:hover .gallery-action {
			opacity: 1;
			transform: translateY(0);
		}

		.gallery-icon {
			width: 16px;
			height: 16px;
			transition: transform 0.3s ease;
		}

		.gallery-item:hover .gallery-icon {
			transform: translateX(4px);
		}

		/* Mobile Scroll Snap */
		@media (max-width: 767px) {
			.gallery-grid {
				display: flex;
				overflow-x: auto;
				scroll-snap-type: x mandatory;
				padding-bottom: 2rem;
				gap: 1rem;
				/* Enable momentum scrolling */
				-webkit-overflow-scrolling: touch;
				/* Hide scrollbar */
				scrollbar-width: none; 
				margin-left: -1rem; /* Full bleed edge to edge */
				margin-right: -1rem;
				padding-left: 1rem;
				padding-right: 1rem;
			}
			
			.gallery-grid::-webkit-scrollbar {
				display: none;
			}
			
			.gallery-item {
				min-width: 280px;
				width: 80vw;
				scroll-snap-align: center;
				aspect-ratio: 3/4;
			}

			.gallery-action {
				opacity: 1;
				transform: translateY(0);
			}
		}

		/* Tablet & Desktop: Bento Grid */
		@media (min-width: 768px) {
			/* Item 0: Large feature */
			.gallery-item.item-0 {
				grid-column: span 8;
				grid-row: span 2;
				aspect-ratio: auto;
			}

			/* Item 1: Side feature top */
			.gallery-item.item-1 {
				grid-column: span 4;
				grid-row: span 1;
				aspect-ratio: 4/3;
			}

			/* Item 2: Side feature bottom */
			.gallery-item.item-2 {
				grid-column: span 4;
				grid-row: span 1;
				aspect-ratio: 4/3;
			}

			/* Item 3, 4, 5: Bottom row */
			.gallery-item.item-3,
			.gallery-item.item-4,
			.gallery-item.item-5 {
				grid-column: span 4;
				grid-row: span 1;
				aspect-ratio: 4/3;
			}
		}

		@media (min-width: 1024px) {
			/* Enhanced Layout for large screens */
			/* 
			Row 1: [0(4cols, 2rows)] [1(8cols)]
			Row 2:                   [2(4cols)] [3(4cols)]
			Row 3: [4(6cols)] [5(6cols)]
			*/
			
			/* Let's try the Asymmetrical Mosaic originally planned */
			.gallery-item.item-0 {
				grid-column: span 4;
				grid-row: span 2;
			}
			
			.gallery-item.item-1 {
				grid-column: span 8;
				grid-row: span 1;
				aspect-ratio: 2/1;
			}

			.gallery-item.item-2 {
				grid-column: span 4;
				grid-row: span 1;
				aspect-ratio: 1;
			}

			.gallery-item.item-3 {
				grid-column: span 4;
				grid-row: span 1;
				aspect-ratio: 1;
			}
			
			.gallery-item.item-4 {
				grid-column: span 6;
				grid-row: span 1;
				aspect-ratio: 16/9;
			}

			.gallery-item.item-5 {
				grid-column: span 6;
				grid-row: span 1;
				aspect-ratio: 16/9;
			}
		}
	</style>
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



<?php get_footer(); ?>
