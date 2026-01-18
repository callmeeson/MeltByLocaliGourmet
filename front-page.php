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

<!-- Cake Collections Section -->
<section class="section fade-in-section" style="background-color: white;">
	<div class="section-container">
		<div class="section-header">
			<p style="color: var(--primary); margin-bottom: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; font-size: 0.875rem; font-family: var(--font-body);">
				Explore Our Collections
			</p>
			<h2 class="section-title">Curated Cake Collections</h2>
			<p class="section-description">Each collection is thoughtfully crafted for different occasions and tastes</p>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
			<?php
			$collections = array(
				array(
					'title'       => 'Wedding Elegance',
					'description' => 'Multi-tier masterpieces for your special day',
					'image'       => 'https://images.unsplash.com/photo-1535254973040-607b474cb50d?w=1200',
					'count'       => '25+ Designs',
					'price'       => 'Starting from AED 1,200',
				),
				array(
					'title'       => 'Birthday Celebrations',
					'description' => 'Custom cakes that make moments unforgettable',
					'image'       => 'https://images.unsplash.com/photo-1602351447937-745cb720612f?w=1200',
					'count'       => '40+ Designs',
					'price'       => 'Starting from AED 280',
				),
				array(
					'title'       => 'Chocolate Paradise',
					'description' => 'Rich Belgian chocolate creations',
					'image'       => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=1200',
					'count'       => '18+ Varieties',
					'price'       => 'Starting from AED 320',
				),
				array(
					'title'       => 'Fruit Symphony',
					'description' => 'Fresh fruits and delicate flavors',
					'image'       => 'https://images.unsplash.com/photo-1464349095431-e9a21285b2df?w=1200',
					'count'       => '15+ Varieties',
					'price'       => 'Starting from AED 290',
				),
				array(
					'title'       => 'Luxury Collection',
					'description' => 'Gold leaf and premium ingredients',
					'image'       => 'https://images.unsplash.com/photo-1588195538326-c5b1e5b80e27?w=1200',
					'count'       => '12+ Exclusives',
					'price'       => 'Starting from AED 850',
				),
				array(
					'title'       => 'Custom Creations',
					'description' => 'Bring your vision to life',
					'image'       => 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=1200',
					'count'       => 'Unlimited Options',
					'price'       => 'Custom Pricing',
				),
			);

			foreach ( $collections as $index => $collection ) :
				?>
				<div class="collection-card fade-in-item group" style="position: relative; cursor: pointer;">
					<div style="position: relative; overflow: hidden; aspect-ratio: 4/3;">
						<img 
							src="<?php echo esc_url( $collection['image'] ); ?>" 
							alt="<?php echo esc_attr( $collection['title'] ); ?>"
							style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.7s ease;"
							onmouseover="this.style.transform='scale(1.1)'"
							onmouseout="this.style.transform='scale(1)'"
						>
						<div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.4) 50%, transparent 100%);"></div>
						<div class="collection-card-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; padding: 1.5rem; color: white;">
							<span style="color: var(--primary); background-color: rgba(255, 255, 255, 0.9); padding: 0.25rem 0.75rem; font-size: 0.75rem; letter-spacing: 0.05em; text-transform: uppercase; display: inline-block; margin-bottom: 0.75rem; font-family: var(--font-body); font-weight: 600;">
								<?php echo esc_html( $collection['count'] ); ?>
							</span>
							<h3 style="font-size: 1.5rem; margin-bottom: 0.5rem; font-family: var(--font-serif);">
								<?php echo esc_html( $collection['title'] ); ?>
							</h3>
							<p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 0.5rem; font-family: var(--font-elegant);">
								<?php echo esc_html( $collection['description'] ); ?>
							</p>
							<p style="color: var(--primary); font-family: var(--font-body); font-weight: 500;">
								<?php echo esc_html( $collection['price'] ); ?>
							</p>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

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
