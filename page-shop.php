<?php
/**
 * Template Name: Shop Page (Custom)
 * 
 * Custom shop page template matching the React design exactly
 *
 * @package Melt_Custom
 */

get_header();
?>

<style>
/* Shop Page Styles */
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
	transition: all 0.35s ease;
	cursor: pointer;
    background: #fff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    display: flex;
    flex-direction: column;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
}

.product-card:hover {
	transform: translateY(-6px);
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
    border-color: rgba(184, 134, 11, 0.25);
}

.product-image-wrapper {
	position: relative;
	overflow: hidden;
	aspect-ratio: 1 / 1; /* Smaller, more compact image area */
	background: linear-gradient(135deg, rgba(15, 23, 42, 0.03), rgba(184, 134, 11, 0.05));
	border-bottom: 1px solid rgba(15, 23, 42, 0.04);
	border: none;
	padding: 0;
	width: 100%;
	text-align: left;
	cursor: pointer;
}

.product-card:hover .product-image-wrapper {
	box-shadow: none; /* Handled by card now */
}

.product-image {
	width: 100%;
	height: 100%;
	object-fit: cover;
	transition: transform 0.6s ease;
}

.product-card:hover .product-image {
	transform: scale(1.06);
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
    padding: 1.25rem 1.5rem 1.5rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    justify-content: space-between;
}

.product-name {
	font-family: var(--font-serif);
	font-size: 1.05rem;
	letter-spacing: -0.012em;
	margin-bottom: 0.35rem;
	transition: color 0.3s ease;
	color: var(--foreground);
    line-height: 1.35;
}

.product-card:hover .product-name {
	color: var(--primary);
}

.product-description {
	font-family: var(--font-body);
	font-size: 0.85rem;
	font-weight: 300;
	color: var(--muted-foreground);
	margin-bottom: 1rem;
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
	padding-top: 0.9rem;
    border-top: 1px solid rgba(15, 23, 42, 0.08);
    margin-top: auto;
}

.product-price {
	font-family: var(--font-body);
	font-size: 1rem;
    font-weight: 600;
	color: var(--foreground);
	transition: transform 0.3s ease;
	display: inline-block;
}

.product-card:hover .product-price {
	color: var(--primary);
}

.product-buttons {
	display: flex;
	gap: 0.5rem;
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
	padding: 0.5rem 0.9rem;
    width: auto;
    height: auto;
    justify-content: center;
	background-color: var(--primary);
	color: white;
	border: none;
	font-family: var(--font-body);
	font-size: 0.8rem;
    font-weight: 600;
	cursor: pointer;
	transition: all 0.2s ease;
	display: inline-flex;
	align-items: center;
    gap: 0.4rem;
    border-radius: 999px;
    position: relative;
    z-index: 10;
	text-decoration: none;
    box-shadow: 0 6px 14px rgba(184, 134, 11, 0.25);
}

.btn-add:hover {
	background-color: var(--accent);
    transform: translateY(-1px);
}

.added_to_cart {
	display: none !important;
}

/* Product Quick View Modal */
.product-quick-view {
	position: fixed;
	inset: 0;
	background: rgba(15, 23, 42, 0.5);
	display: none;
	align-items: center;
	justify-content: center;
	padding: 1.5rem;
	z-index: 9999;
	backdrop-filter: blur(6px);
}

.product-quick-view__dialog {
	background: #fff;
	border-radius: 20px;
	max-width: 960px;
	width: 100%;
	display: grid;
	grid-template-columns: minmax(0, 1.1fr) minmax(0, 0.9fr);
	gap: 1.5rem;
	padding: 1.5rem;
	box-shadow: 0 24px 60px rgba(15, 23, 42, 0.2);
	position: relative;
}

.product-quick-view__media {
	background: linear-gradient(135deg, rgba(15, 23, 42, 0.03), rgba(184, 134, 11, 0.08));
	border-radius: 16px;
	padding: 1rem;
	display: flex;
	flex-direction: column;
	gap: 0.75rem;
}

.product-quick-view__image {
	width: 100%;
	aspect-ratio: 1 / 1;
	border-radius: 12px;
	object-fit: cover;
	background: #f8fafc;
}

.product-quick-view__thumbs {
	display: flex;
	gap: 0.5rem;
	overflow-x: auto;
	padding-bottom: 0.25rem;
}

.product-quick-view__thumb {
	width: 56px;
	height: 56px;
	border-radius: 10px;
	border: 1px solid rgba(15, 23, 42, 0.12);
	background: #fff;
	cursor: pointer;
	flex: 0 0 auto;
	padding: 0;
	overflow: hidden;
}

.product-quick-view__thumb img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	display: block;
}

.product-quick-view__thumb.is-active {
	border-color: rgba(184, 134, 11, 0.6);
	box-shadow: 0 0 0 2px rgba(184, 134, 11, 0.2);
}

.product-quick-view__content {
	display: flex;
	flex-direction: column;
	gap: 0.75rem;
}

.product-quick-view__title {
	font-family: var(--font-serif);
	font-size: 1.6rem;
	letter-spacing: -0.02em;
	color: var(--foreground);
}

.product-quick-view__price {
	font-family: var(--font-body);
	font-size: 1.1rem;
	font-weight: 600;
	color: var(--primary);
}

.product-quick-view__desc {
	font-family: var(--font-body);
	font-size: 0.95rem;
	color: var(--muted-foreground);
	line-height: 1.6;
}

.product-quick-view__actions {
	display: flex;
	gap: 0.75rem;
	align-items: center;
	margin-top: auto;
}

.product-quick-view__field {
	display: flex;
	flex-direction: column;
	gap: 0.35rem;
}

.product-quick-view__field label {
	font-family: var(--font-body);
	font-size: 0.8rem;
	color: var(--muted-foreground);
}

.product-quick-view__field input[type="date"] {
	padding: 0.55rem 0.75rem;
	border: 1px solid rgba(15, 23, 42, 0.12);
	border-radius: 10px;
	font-family: var(--font-body);
}

.product-quick-view__close {
	position: absolute;
	top: 12px;
	right: 12px;
	width: 36px;
	height: 36px;
	border-radius: 50%;
	border: none;
	background: rgba(15, 23, 42, 0.08);
	cursor: pointer;
	font-size: 20px;
	line-height: 1;
}

@media (max-width: 900px) {
	.product-quick-view__dialog {
		grid-template-columns: 1fr;
	}
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
		$current_cat_slug = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : 'all';
		
		// All Cakes Button
		$all_active_class = $current_cat_slug === 'all' ? 'active' : '';
		echo '<a href="' . remove_query_arg('category') . '" class="category-filter-btn ' . $all_active_class . '">All Cakes</a>';
		
		// Get product categories
		$categories = get_terms(array(
			'taxonomy' => 'product_cat',
			'hide_empty' => true,
		));
		
		if (!empty($categories) && !is_wp_error($categories)) {
			foreach ($categories as $category) {
				if ($category->slug === 'uncategorized') continue;
				
				$active_class = $current_cat_slug === $category->slug ? 'active' : '';
				echo '<a href="' . add_query_arg('category', $category->slug) . '" class="category-filter-btn ' . $active_class . '">' . esc_html($category->name) . '</a>';
			}
		}
		?>
	</div>

	<!-- Products Grid -->
	<div class="products-grid" id="productsGrid">
		<?php
		// Pagination settings
		$products_per_page = 9;
		$current_page = max(1, get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1));
		
		// Query arguments
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => $products_per_page,
			'paged' => $current_page,
			'status' => 'publish',
		);
		
		// Add category filter if selected
		if ($current_cat_slug !== 'all') {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $current_cat_slug,
				),
			);
		}
		
		$loop = new WP_Query($args);
		
		if ($loop->have_posts()) :
			while ($loop->have_posts()) : $loop->the_post();
				global $product;
				// Ensure visibility
				if (empty($product) || !$product->is_visible()) continue;
				
				$product_image = wp_get_attachment_image_url($product->get_image_id(), 'large');
				if (!$product_image) {
                    // Placeholder if no image
					$product_image = wc_placeholder_img_src();
				}
				
				$gallery_ids = $product->get_gallery_image_ids();
				$images = [];
				$images[] = $product_image;
				if (!empty($gallery_ids)) {
					foreach ($gallery_ids as $image_id) {
						$image_url = wp_get_attachment_image_url($image_id, 'large');
						if ($image_url) {
							$images[] = $image_url;
						}
					}
				}

				$product_cats = wc_get_product_term_ids($product->get_id(), 'product_cat');
                $cat_slugs = [];
                foreach($product_cats as $cat_id) {
                    $term = get_term($cat_id, 'product_cat');
                    if($term && !is_wp_error($term)) $cat_slugs[] = $term->slug;
                }
                $categories_json = json_encode($cat_slugs);
				$price_display = $product->get_price_html();
				if (empty($price_display) && $product->get_price()) {
					$price_display = 'AED ' . number_format((float)$product->get_price(), 2);
				}
				$short_description = wp_strip_all_tags($product->get_short_description());
				?>
				<div class="product-card fade-in-item"
					data-categories='<?php echo esc_attr($categories_json); ?>'
					data-name="<?php echo esc_attr($product->get_name()); ?>"
					data-price="<?php echo esc_attr(wp_strip_all_tags($price_display)); ?>"
					data-description="<?php echo esc_attr($short_description); ?>"
					data-images='<?php echo esc_attr(wp_json_encode($images)); ?>'>
					<!-- Product Image -->
					<button type="button" class="product-image-wrapper" aria-label="<?php echo esc_attr($product->get_name()); ?>">
						<img src="<?php echo esc_url($product_image); ?>" 
							alt="<?php echo esc_attr($product->get_name()); ?>" 
							class="product-image"
							loading="lazy">
						<div class="product-overlay"></div>
					</button>

					<!-- Product Info -->
					<div class="product-info">
						<h3 class="product-name">
                            <span><?php echo $product->get_name(); ?></span>
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
									<!-- Arrow button adds base product directly to cart -->
									<a href="<?php echo $product->add_to_cart_url(); ?>" 
									   data-quantity="1" 
									   class="btn-add product_type_<?php echo $product->get_type(); ?> add_to_cart_button ajax_add_to_cart" 
									   data-product_id="<?php echo $product->get_id(); ?>" 
									   data-product_sku="<?php echo $product->get_sku(); ?>" 
									   aria-label="Add &ldquo;<?php echo esc_attr($product->get_name()); ?>&rdquo; to your cart" 
									   rel="nofollow">
										<i data-lucide="shopping-cart" style="width: 0.75rem; height: 0.75rem;"></i>
										Add to cart
									</a>
								</div>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
		<?php else : ?>
			<div class="no-products" style="grid-column: 1 / -1; text-align: center; padding: 4rem;">
				<h3 style="font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 1rem;">No products found</h3>
				<p style="color: var(--muted-foreground);">Try adjusting your category filter or check back later.</p>
                <a href="<?php echo remove_query_arg('category'); ?>" class="btn-primary" style="display: inline-block; margin-top: 1rem; color: var(--primary); text-decoration: underline;">View All Products</a>
			</div>
		<?php endif; ?>
	</div>

	<!-- Pagination -->
	<?php 
	$total_pages = $loop->max_num_pages;
	if ($total_pages > 1) : 
        $base_url = remove_query_arg('paged');
        // Preserve category filter
        if ($current_cat_slug !== 'all') {
            $base_url = add_query_arg('category', $current_cat_slug, $base_url);
        }
    ?>
		<div class="pagination-wrapper">
			<!-- Previous Button -->
            <?php if ($current_page > 1) : ?>
			<a href="<?php echo add_query_arg('paged', $current_page - 1, $base_url); ?>" class="pagination-btn">
				<i data-lucide="chevron-left" style="width: 1rem; height: 1rem;"></i>
			</a>
            <?php else : ?>
            <button class="pagination-btn disabled" disabled><i data-lucide="chevron-left" style="width: 1rem; height: 1rem;"></i></button>
            <?php endif; ?>

			<!-- Page Numbers -->
			<?php
			$range = 2; // Show 2 pages on each side of current page
			$start = max(1, $current_page - $range);
			$end = min($total_pages, $current_page + $range);

			// First page
			if ($start > 1) :
				?>
				<a href="<?php echo remove_query_arg('paged', $base_url); ?>" class="pagination-btn <?php echo $current_page == 1 ? 'active' : ''; ?>">1</a>
				<?php if ($start > 2) : ?>
					<span class="pagination-info">...</span>
				<?php endif; ?>
			<?php endif; ?>

			<!-- Page range -->
			<?php for ($i = $start; $i <= $end; $i++) : ?>
				<a href="<?php echo $i == 1 ? remove_query_arg('paged', $base_url) : add_query_arg('paged', $i, $base_url); ?>" class="pagination-btn <?php echo $current_page == $i ? 'active' : ''; ?>">
					<?php echo $i; ?>
				</a>
			<?php endfor; ?>

			<!-- Last page -->
			<?php if ($end < $total_pages) : ?>
				<?php if ($end < $total_pages - 1) : ?>
					<span class="pagination-info">...</span>
				<?php endif; ?>
				<a href="<?php echo add_query_arg('paged', $total_pages, $base_url); ?>" class="pagination-btn <?php echo $current_page == $total_pages ? 'active' : ''; ?>">
					<?php echo $total_pages; ?>
				</a>
			<?php endif; ?>

			<!-- Next Button -->
            <?php if ($current_page < $total_pages) : ?>
			<a href="<?php echo add_query_arg('paged', $current_page + 1, $base_url); ?>" class="pagination-btn">
				<i data-lucide="chevron-right" style="width: 1rem; height: 1rem;"></i>
			</a>
            <?php else : ?>
            <button class="pagination-btn disabled" disabled><i data-lucide="chevron-right" style="width: 1rem; height: 1rem;"></i></button>
            <?php endif; ?>

			<!-- Page Info -->
			<span class="pagination-info">
				Page <?php echo $current_page; ?> of <?php echo $total_pages; ?>
			</span>
		</div>
	<?php endif; 
	wp_reset_postdata();
	?>
</div>

<?php 
// Include customize modal
get_template_part('template-parts/customize-modal'); 
?>

<!-- Product quick view modal -->
<div id="productQuickView" class="product-quick-view" aria-hidden="true">
	<div class="product-quick-view__dialog" role="dialog" aria-modal="true" aria-labelledby="productQuickViewTitle">
		<button type="button" class="product-quick-view__close" aria-label="Close">x</button>
		<div class="product-quick-view__media">
			<img class="product-quick-view__image" src="" alt="" id="productQuickViewImage">
			<div class="product-quick-view__thumbs" id="productQuickViewThumbs"></div>
		</div>
		<div class="product-quick-view__content">
			<h3 class="product-quick-view__title" id="productQuickViewTitle"></h3>
			<div class="product-quick-view__price" id="productQuickViewPrice"></div>
			<div class="product-quick-view__desc" id="productQuickViewDesc"></div>
			<div class="product-quick-view__field">
				<label for="productQuickViewDate">Preferred Delivery Date</label>
				<input type="date" id="productQuickViewDate">
			</div>
			<div class="product-quick-view__actions">
				<a class="btn-add" href="#" id="productQuickViewAdd">Add to cart</a>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>

<script>
(function () {
	'use strict';

	const modal = document.getElementById('productQuickView');
	if (!modal) return;

	const titleEl = document.getElementById('productQuickViewTitle');
	const priceEl = document.getElementById('productQuickViewPrice');
	const descEl = document.getElementById('productQuickViewDesc');
	const imageEl = document.getElementById('productQuickViewImage');
	const thumbsEl = document.getElementById('productQuickViewThumbs');
	const addBtn = document.getElementById('productQuickViewAdd');
	const dateInput = document.getElementById('productQuickViewDate');
	const closeBtn = modal.querySelector('.product-quick-view__close');

	function openModal() {
		modal.style.display = 'flex';
		modal.setAttribute('aria-hidden', 'false');
	}

	function closeModal() {
		modal.style.display = 'none';
		modal.setAttribute('aria-hidden', 'true');
	}

	function setActiveImage(url, alt) {
		imageEl.src = url;
		imageEl.alt = alt || '';
	}

	function renderThumbs(images, alt) {
		thumbsEl.innerHTML = '';
		if (!images || images.length <= 1) {
			thumbsEl.style.display = 'none';
			return;
		}
		thumbsEl.style.display = 'flex';
		images.forEach((url, index) => {
			const button = document.createElement('button');
			button.type = 'button';
			button.className = 'product-quick-view__thumb';
			button.setAttribute('aria-label', 'View image ' + (index + 1));
			const img = document.createElement('img');
			img.src = url;
			img.alt = alt || '';
			button.appendChild(img);
			if (index === 0) {
				button.classList.add('is-active');
			}
			button.addEventListener('click', (event) => {
				event.stopPropagation();
				Array.from(thumbsEl.children).forEach((thumb) => {
					thumb.classList.remove('is-active');
				});
				button.classList.add('is-active');
				setActiveImage(url, alt);
			});
			thumbsEl.appendChild(button);
		});
	}

	function openQuickView(card) {
		const name = card.dataset.name || '';
		const price = card.dataset.price || '';
		const description = card.dataset.description || '';
		let images = [];

		try {
			images = JSON.parse(card.dataset.images || '[]');
		} catch (e) {
			images = [];
		}

		titleEl.textContent = name;
		priceEl.textContent = price;
		descEl.textContent = description || 'No description available.';
		if (dateInput) {
			const today = new Date();
			const offset = today.getTimezoneOffset();
			const local = new Date(today.getTime() - offset * 60000);
			dateInput.min = local.toISOString().split('T')[0];
			dateInput.value = localStorage.getItem('melt_delivery_date') || '';
		}

		if (images.length > 0) {
			setActiveImage(images[0], name);
		}
		renderThumbs(images, name);

		const addButton = card.querySelector('.add_to_cart_button');
		if (addButton) {
			addBtn.href = addButton.getAttribute('href') || '#';
			addBtn.setAttribute('data-product_id', addButton.getAttribute('data-product_id') || '');
			addBtn.setAttribute('data-product_sku', addButton.getAttribute('data-product_sku') || '');
			addBtn.className = addButton.className;
			addBtn.innerHTML = addButton.innerHTML;
		}

		openModal();
	}

	if (dateInput) {
		dateInput.addEventListener('change', () => {
			if (dateInput.value) {
				localStorage.setItem('melt_delivery_date', dateInput.value);
			}
		});
	}

	addBtn.addEventListener('click', () => {
		if (dateInput && dateInput.value) {
			localStorage.setItem('melt_delivery_date', dateInput.value);
		}
	});

	document.addEventListener('click', (event) => {
		const link = event.target.closest('.product-card a');
		if (!link) return;
		const card = link.closest('.product-card');
		const ignore = event.target.closest('.btn-add, .add_to_cart_button');
		if (ignore || !card) return;
		event.preventDefault();
		event.stopPropagation();
		openQuickView(card);
	}, true);

	document.querySelectorAll('.product-card').forEach((card) => {
		card.querySelectorAll('.product-image-wrapper').forEach((button) => {
			button.addEventListener('click', (event) => {
				event.preventDefault();
				event.stopPropagation();
				openQuickView(card);
			});
		});
		card.addEventListener('click', (event) => {
			const ignore = event.target.closest('.btn-add, .add_to_cart_button');
			if (ignore) return;
			openQuickView(card);
		});
	});

	closeBtn.addEventListener('click', closeModal);
	modal.addEventListener('click', (event) => {
		if (event.target === modal) {
			closeModal();
		}
	});
	document.addEventListener('keydown', (event) => {
		if (event.key === 'Escape' && modal.style.display === 'flex') {
			closeModal();
		}
	});
})();
</script>
