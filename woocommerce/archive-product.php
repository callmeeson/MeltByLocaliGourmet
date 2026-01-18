<?php
/**
 * The Template for displaying product archives, including the main shop page
 *
 * This template matches the React ShopPage design exactly
 *
 * @package Melt_Custom
 */

get_header();
?>

<style>
/* Shop Page Styles - Copied from page-shop.php for consistency */
.shop-page-wrapper {
	padding-top: 4.5rem; /* Just enough clearance for fixed navbar */
	background-color: white;
	min-height: 100vh;
}

.shop-header {
	padding: 0 1.5rem 2rem; /* No top padding */
	text-align: center;
}

.shop-title {
	font-family: var(--font-serif);
	font-size: clamp(2rem, 5vw, 3rem);
	margin-bottom: 1rem;
	font-weight: 500;
	letter-spacing: -0.025em;
	color: var(--foreground);
}

.shop-subtitle {
	font-family: var(--font-body);
	font-weight: 300;
	font-size: 1rem;
	color: var(--muted-foreground);
}

.category-filters {
	display: flex;
	flex-wrap: wrap;
	justify-content: center;
	gap: 0.75rem;
	padding: 0 1.5rem 2rem;
	border-bottom: 1px solid var(--border);
	margin-bottom: 4rem;
	max-width: 1280px;
	margin-left: auto;
	margin-right: auto;
}

.category-filter-btn {
	padding: 0.5rem 1.5rem;
	font-family: var(--font-body);
	font-weight: 400;
	font-size: 0.9rem;
	transition: all 0.3s ease;
	text-decoration: none;
	color: var(--muted-foreground);
	border-bottom: 2px solid transparent;
	margin-bottom: -2px;
	background: none;
	border: none;
	border-bottom: 2px solid transparent;
	cursor: pointer;
}

.category-filter-btn:hover {
	color: var(--foreground);
	transform: scale(1.05);
}

.category-filter-btn.active {
	color: var(--foreground);
	border-bottom-color: var(--primary);
}

.products-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
	gap: 3rem 1.5rem;
	padding: 0 1.5rem 5rem;
	max-width: 1280px;
	margin: 0 auto;
}

.product-card {
	transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
	cursor: pointer;
    background: white;
    border: 1px solid rgba(0,0,0,0.06);
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
	transform: translateY(-8px);
    box-shadow: 0 20px 40px -5px rgba(0,0,0,0.08);
    border-color: rgba(184, 134, 11, 0.2);
}

.product-image-wrapper {
	position: relative;
	overflow: hidden;
	aspect-ratio: 4/5; /* Slightly taller for elegance */
	background-color: var(--secondary);
	border-bottom: 1px solid rgba(0,0,0,0.04);
}

.product-card:hover .product-image-wrapper {
	box-shadow: none; /* Handled by card now */
}

.product-image {
	width: 100%;
	height: 100%;
	object-fit: cover;
	transition: transform 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.product-card:hover .product-image {
	transform: scale(1.08);
}

.product-overlay {
	position: absolute;
	inset: 0;
	background-color: rgba(0, 0, 0, 0);
	transition: background-color 0.3s ease;
	pointer-events: none;
    z-index: 1;
}

.product-card:hover .product-overlay {
	background-color: rgba(0, 0, 0, 0.02);
}

.product-info {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    justify-content: space-between;
}

.product-name {
	font-family: var(--font-serif);
	font-size: 1.1rem;
	letter-spacing: -0.01em;
	margin-bottom: 0.5rem;
	transition: color 0.3s ease;
	color: var(--foreground);
    line-height: 1.3;
}

.product-card:hover .product-name {
	color: var(--primary);
}

.product-description {
	font-family: var(--font-body);
	font-size: 0.875rem;
	font-weight: 300;
	color: var(--muted-foreground);
	margin-bottom: 1.25rem;
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	overflow: hidden;
	line-height: 1.6;
}

.product-footer {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding-top: 0.75rem;
    border-top: 1px solid rgba(0,0,0,0.06);
    margin-top: auto;
}

.product-price {
	font-family: var(--font-body);
	font-size: 1.1rem;
    font-weight: 500;
	color: var(--foreground);
	transition: transform 0.3s ease;
	display: inline-block;
}

.product-card:hover .product-price {
	color: var(--primary);
}

.product-buttons {
	display: flex;
	gap: 0.75rem;
    align-items: center;
}

.btn-customize {
	padding: 0.5rem 1rem;
	border: 1px solid var(--border);
	background-color: white;
	color: var(--foreground);
	font-family: var(--font-body);
	font-size: 0.85rem;
    font-weight: 500;
	cursor: pointer;
	transition: all 0.2s ease;
	display: inline-flex;
	align-items: center;
	gap: 0.35rem;
    border-radius: 8px;
    position: relative;
    z-index: 10;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}

.btn-customize:hover {
	background-color: var(--secondary);
	border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(184, 134, 11, 0.15);
}

.btn-add {
	padding: 0.5rem 0.5rem;
    width: 2.25rem;
    height: 2.25rem;
    justify-content: center;
	background-color: var(--primary);
	color: white;
	border: none;
	font-family: var(--font-body);
	cursor: pointer;
	transition: all 0.2s ease;
	display: inline-flex;
	align-items: center;
    border-radius: 50%;
    position: relative;
    z-index: 10;
	text-decoration: none;
    box-shadow: 0 2px 4px rgba(184, 134, 11, 0.2);
}

.btn-add:hover {
	background-color: var(--accent);
    transform: scale(1.1) rotate(90deg);
}

/* Pagination Styles */
.pagination-wrapper {
	display: flex;
	justify-content: center;
	align-items: center;
	gap: 0.5rem;
	padding: 3rem 1.5rem 5rem;
	max-width: 1280px;
	margin: 0 auto;
}

.pagination-btn {
	padding: 0.5rem 1rem;
	border: 1px solid var(--border);
	background-color: white;
	color: var(--foreground);
	font-family: var(--font-body);
	font-size: 0.9rem;
	cursor: pointer;
	transition: all 0.3s ease;
	min-width: 2.5rem;
	text-align: center;
	text-decoration: none;
	display: inline-flex;
	align-items: center;
	justify-content: center;
}

.pagination-btn:hover:not(.active):not(.disabled) {
	background-color: var(--primary);
	color: white;
	border-color: var(--primary);
	transform: scale(1.05);
}

.pagination-btn.active {
	background-color: var(--primary);
	color: white;
	border-color: var(--primary);
}

.pagination-btn.disabled {
	opacity: 0.5;
	cursor: not-allowed;
	pointer-events: none;
}

.pagination-info {
	font-family: var(--font-body);
	font-size: 0.875rem;
	color: var(--muted-foreground);
	padding: 0 1rem;
}
</style>

<div class="shop-page-wrapper">
	<!-- Shop Header -->
	<div class="shop-header fade-in-section">
		<h1 class="shop-title">Our Collection</h1>
		<p class="shop-subtitle">Handcrafted with passion and precision</p>
	</div>

	<!-- Category Filters -->
	<div class="category-filters fade-in-section" id="categoryFilters">
		<?php
		// Get current category from URL
		$current_cat_slug = isset($_GET['product_cat']) ? sanitize_text_field($_GET['product_cat']) : '';
        if(empty($current_cat_slug) && isset($_GET['category'])) {
            $current_cat_slug = sanitize_text_field($_GET['category']);
        }
		
		// All Cakes Button
		$all_active_class = (empty($current_cat_slug) || $current_cat_slug === 'all') ? 'active' : '';
        $shop_url = wc_get_page_permalink( 'shop' );
		echo '<a href="' . $shop_url . '" class="category-filter-btn ' . $all_active_class . '">All Cakes</a>';
		
		// Get product categories
		$categories = get_terms(array(
			'taxonomy' => 'product_cat',
			'hide_empty' => true,
		));
		
		if (!empty($categories) && !is_wp_error($categories)) {
			foreach ($categories as $category) {
				if ($category->slug === 'uncategorized') continue;
				
				$active_class = $current_cat_slug === $category->slug ? 'active' : '';
				echo '<a href="' . get_term_link($category) . '" class="category-filter-btn ' . $active_class . '">' . esc_html($category->name) . '</a>';
			}
		}
		?>
	</div>

	<!-- Products Grid -->
	<div class="products-grid" id="productsGrid">
		<?php
        // Use standard WooCommerce loop if available, else custom query
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				global $product;
				// Ensure visibility
				if (empty($product) || !$product->is_visible()) continue;
				
				$product_image = wp_get_attachment_image_url($product->get_image_id(), 'large');
				if (!$product_image) {
					$product_image = wc_placeholder_img_src();
				}

                // Get categories for filtering reference
				$product_cats = wc_get_product_term_ids($product->get_id(), 'product_cat');
                $cat_slugs = [];
                foreach($product_cats as $cat_id) {
                    $term = get_term($cat_id, 'product_cat');
                    if($term && !is_wp_error($term)) $cat_slugs[] = $term->slug;
                }
                $categories_json = json_encode($cat_slugs);
				?>
				<div class="product-card fade-in-item" data-categories='<?php echo esc_attr($categories_json); ?>'>
					<!-- Product Image -->
					<a href="<?php echo get_permalink(); ?>" class="product-image-wrapper">
						<img src="<?php echo esc_url($product_image); ?>" 
							alt="<?php echo esc_attr($product->get_name()); ?>" 
							class="product-image"
							loading="lazy">
						<div class="product-overlay"></div>
					</a>

					<!-- Product Info -->
					<div class="product-info">
						<h3 class="product-name">
                            <a href="<?php echo get_permalink(); ?>"><?php echo $product->get_name(); ?></a>
                        </h3>
						<div class="product-description"><?php echo $product->get_short_description(); ?></div>

						<!-- Price and Buttons -->
						<div class="product-footer">
							<span class="product-price">
								<?php 
								$price_html = $product->get_price_html();
								if (empty($price_html) && $product->get_price()) {
									// For variable products or products with empty price_html
									echo 'AED ' . number_format((float)$product->get_price(), 2);
								} else {
									echo $price_html;
								}
								?>
							</span>
							
								<div class="product-buttons">
									<button class="btn-customize" onclick="event.preventDefault(); event.stopPropagation(); openCustomizeModal(<?php echo esc_js($product->get_id()); ?>, '<?php echo esc_js($product->get_name()); ?>', <?php echo esc_js((float)$product->get_price()); ?>, '<?php echo esc_url($product_image); ?>')">
										<i data-lucide="sparkles" style="width: 0.75rem; height: 0.75rem;"></i>
										Customize
									</button>
									
									<?php if ( $product->is_type( 'variable' ) ) : ?>
										<button class="btn-add" 
												onclick="event.preventDefault(); event.stopPropagation(); quickAddToCart(<?php echo $product->get_id(); ?>, this)">
											<i data-lucide="plus" style="width: 0.75rem; height: 0.75rem;"></i>
										</button>
									<?php else : ?>
										<a href="<?php echo $product->add_to_cart_url(); ?>" 
										   data-quantity="1" 
										   class="btn-add product_type_<?php echo $product->get_type(); ?> add_to_cart_button ajax_add_to_cart" 
										   data-product_id="<?php echo $product->get_id(); ?>" 
										   data-product_sku="<?php echo $product->get_sku(); ?>" 
										   aria-label="Add &ldquo;<?php echo esc_attr($product->get_name()); ?>&rdquo; to your cart" 
										   rel="nofollow">
											<i data-lucide="plus" style="width: 0.75rem; height: 0.75rem;"></i>
											<!-- Add -->
										</a>
									<?php endif; ?>
								</div>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
		<?php else : ?>
			<div class="no-products" style="grid-column: 1 / -1; text-align: center; padding: 4rem;">
				<h3 style="font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 1rem;">No products found</h3>
				<p style="color: var(--muted-foreground);">Try adjusting your category filter or check back later.</p>
                <a href="<?php echo $shop_url; ?>" class="btn-primary" style="display: inline-block; margin-top: 1rem; color: var(--primary); text-decoration: underline;">View All Products</a>
			</div>
		<?php endif; ?>
	</div>

	<!-- Pagination -->
	<div class="pagination-wrapper">
        <?php
        global $wp_query;
        $total_pages = $wp_query->max_num_pages;
        $current_page = max(1, get_query_var('paged'));
        $base_url = get_pagenum_link(1); // Simplification, WC handles links well usually
        // Using WooCommerce standard pagination function for links would be better but keeping consistency with custom design
        
        echo paginate_links( array(
            'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'format'    => '?paged=%#%',
            'current'   => max( 1, get_query_var( 'paged' ) ),
            'total'     => $total_pages,
            'prev_text' => '<i data-lucide="chevron-left" style="width: 1rem; height: 1rem;"></i>',
            'next_text' => '<i data-lucide="chevron-right" style="width: 1rem; height: 1rem;"></i>',
            'type'      => 'list',
            'end_size'  => 3,
            'mid_size'  => 3,
        ) );
        ?>
        <!-- Custom styled pagination links logic would go here if not using paginate_links, but paginate_links is safer for archives. 
             Ideally we hide default WC pagination and use this if we want to match style perfectly.
             For now, let's use the standard WC loop pagination output or leave it to user to style the default.
        -->
	</div>
    <style>
        /* Quick override for WP pagination to match design */
        .page-numbers {
            display: flex;
            gap: 0.5rem;
            list-style: none;
            padding: 0;
            justify-content: center;
        }
        .page-numbers li a, .page-numbers li span {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border);
            background-color: white;
            color: var(--foreground);
            font-family: var(--font-body);
            text-decoration: none;
            transition: all 0.3s ease;
            display: block;
        }
        .page-numbers li span.current, .page-numbers li a:hover {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
    </style>
</div>

<?php 
// Include customize modal
get_template_part('template-parts/customize-modal'); 
?>

<script>
// Quick add to cart function for all product types
function quickAddToCart(productId, button) {
	// Show loading state
	const originalHTML = button.innerHTML;
	button.innerHTML = '<i data-lucide="loader" style="width: 0.75rem; height: 0.75rem; animation: spin 1s linear infinite;"></i>';
	button.disabled = true;
	
	// Add to cart via AJAX
	const formData = new FormData();
	formData.append('action', 'woocommerce_add_to_cart');
	formData.append('product_id', productId);
	formData.append('quantity', 1);
	
	fetch(wc_add_to_cart_params.ajax_url, {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		// Reset button
		button.innerHTML = originalHTML;
		button.disabled = false;
		
		if (data.error) {
			alert('Error adding to cart: ' + data.error);
		} else {
			// Update cart count if element exists
			const cartCount = document.querySelector('.cart-count');
			if (cartCount && data.fragments) {
				// Trigger WooCommerce fragments refresh
				jQuery(document.body).trigger('added_to_cart', [data.fragments, data.cart_hash, button]);
			}
			
			// Show success feedback
			button.style.backgroundColor = '#22c55e';
			setTimeout(() => {
				button.style.backgroundColor = '';
			}, 1000);
		}
		
		// Reinitialize icons
		if (typeof lucide !== 'undefined') {
			lucide.createIcons();
		}
	})
	.catch(error => {
		console.error('Error:', error);
		button.innerHTML = originalHTML;
		button.disabled = false;
		alert('Error adding product to cart');
		
		if (typeof lucide !== 'undefined') {
			lucide.createIcons();
		}
	});
}

// Add spin animation for loader
const style = document.createElement('style');
style.textContent = '@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }';
document.head.appendChild(style);
</script>

<?php get_footer(); ?>
