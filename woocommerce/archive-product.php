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
		padding-top: 4.5rem;
		/* Just enough clearance for fixed navbar */
		background-color: white;
		min-height: 100vh;
	}

	.shop-header {
		padding: 0 1.5rem 2rem;
		/* No top padding */
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
		cursor: default;
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
		aspect-ratio: 1 / 1;
		/* Smaller, more compact image area */
		background: linear-gradient(135deg, rgba(15, 23, 42, 0.03), rgba(184, 134, 11, 0.05));
		border-bottom: 1px solid rgba(15, 23, 42, 0.04);
		border: none;
		padding: 0;
		width: 100%;
		text-align: left;
		cursor: default;
	}

	.product-card:hover .product-image-wrapper {
		box-shadow: none;
		/* Handled by card now */
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
		-webkit-line-clamp: 3;
		-webkit-box-orient: vertical;
		overflow: hidden;
		text-overflow: ellipsis;
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
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
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

	/* Woo variation form inside quick view */
	.product-quick-view__actions #productQuickViewVariations {
		width: 100%;
	}

	.product-quick-view__actions form.variations_form {
		width: 100%;
	}

	.product-quick-view__actions .variations {
		width: 100%;
		border: 0;
		border-collapse: collapse;
		margin: 0 0 0.75rem;
	}

	.product-quick-view__actions .variations td,
	.product-quick-view__actions .variations th {
		padding: 0.35rem 0;
		border: 0;
		vertical-align: middle;
	}

	.product-quick-view__actions .variations label {
		font-family: var(--font-body);
		font-weight: 500;
		font-size: 0.9rem;
		color: var(--foreground);
	}

	.product-quick-view__actions .variations select {
		width: 100%;
		padding: 0.6rem 0.75rem;
		border: 1px solid rgba(15, 23, 42, 0.12);
		border-radius: 10px;
		background: #fff;
		font-family: var(--font-body);
		font-size: 0.9rem;
	}

	.product-quick-view__actions .single_variation_wrap {
		display: grid;
		gap: 0.75rem;
	}

	.product-quick-view__actions .woocommerce-variation-price,
	.product-quick-view__actions .woocommerce-variation-availability {
		font-family: var(--font-body);
	}

	.product-quick-view__actions .woocommerce-variation-price .price {
		font-weight: 700;
		font-size: 1.05rem;
		color: var(--foreground);
	}

	.product-quick-view__actions .woocommerce-variation-add-to-cart {
		display: flex;
		gap: 0.75rem;
		align-items: center;
		flex-wrap: wrap;
	}

	.product-quick-view__actions .quantity .qty {
		width: 90px;
		padding: 0.55rem 0.6rem;
		border: 1px solid rgba(15, 23, 42, 0.12);
		border-radius: 10px;
	}

	.product-quick-view__actions .single_add_to_cart_button.button {
		background: var(--primary);
		color: #fff;
		border-radius: 10px;
		padding: 0.65rem 1rem;
		border: 1px solid rgba(0, 0, 0, 0.08);
		font-family: var(--font-body);
		font-weight: 600;
	}

	.product-quick-view__actions .single_add_to_cart_button.button:disabled,
	.product-quick-view__actions .single_add_to_cart_button.button.disabled {
		opacity: 0.55;
		cursor: not-allowed;
	}

	.product-quick-view__actions .reset_variations {
		font-size: 0.85rem;
		color: var(--muted-foreground);
		text-decoration: underline;
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

	.product-quick-view__field select {
		padding: 0.55rem 0.75rem;
		border: 1px solid rgba(15, 23, 42, 0.12);
		border-radius: 10px;
		font-family: var(--font-body);
		background: #fff;
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
		$current_cat_slug = isset($_GET['product_cat']) ? sanitize_text_field($_GET['product_cat']) : '';
		if (empty($current_cat_slug) && isset($_GET['category'])) {
			$current_cat_slug = sanitize_text_field($_GET['category']);
		}

		// All Cakes Button
		$all_active_class = (empty($current_cat_slug) || $current_cat_slug === 'all') ? 'active' : '';
		$shop_url = wc_get_page_permalink('shop');
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
		if (have_posts()) :
			while (have_posts()) : the_post();
				global $product;
				// Ensure visibility
				if (empty($product) || !$product->is_visible()) continue;

				$product_image = wp_get_attachment_image_url($product->get_image_id(), 'large');
				if (!$product_image) {
					$product_image = wc_placeholder_img_src();
				}

				// Get categories for filtering reference
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
				foreach ($product_cats as $cat_id) {
					$term = get_term($cat_id, 'product_cat');
					if ($term && !is_wp_error($term)) $cat_slugs[] = $term->slug;
				}
				$categories_json = json_encode($cat_slugs);
				$size_terms = [];
				$size_attribute_name = '';
				$attribute_taxonomies = wc_get_attribute_taxonomies();
				if (!empty($attribute_taxonomies)) {
					foreach ($attribute_taxonomies as $attribute_taxonomy) {
						if (isset($attribute_taxonomy->attribute_label) && strtolower($attribute_taxonomy->attribute_label) === 'cake size') {
							$size_attribute_name = wc_attribute_taxonomy_name($attribute_taxonomy->attribute_name);
							break;
						}
					}
				}
				if (!empty($size_attribute_name)) {
					$size_terms = wc_get_product_terms($product->get_id(), $size_attribute_name, ['fields' => 'names']);
				}
				if (empty($size_terms)) {
					$product_attributes = $product->get_attributes();
					foreach ($product_attributes as $attribute) {
						if (!$attribute instanceof WC_Product_Attribute) continue;
						if ($attribute->is_taxonomy()) {
							$tax = $attribute->get_name();
							$tax_obj = get_taxonomy($tax);
							$tax_label = $tax_obj ? $tax_obj->labels->singular_name : '';
							if (strtolower($tax_label) === 'cake size') {
								$size_terms = wc_get_product_terms($product->get_id(), $tax, ['fields' => 'names']);
								break;
							}
						} else {
							$attr_label = $attribute->get_name();
							if (strtolower($attr_label) === 'cake size') {
								$size_terms = $attribute->get_options();
								break;
							}
						}
					}
				}
				$size_terms_json = wp_json_encode($size_terms);

				$price_display = $product->get_price_html();
				$card_price_html = $price_display;

				// Variable products: show "Starts at" using default variation price (fallback to min variation price).
				if ($product->is_type('variable')) {
					$default_attrs = $product->get_default_attributes();
					$default_variation_id = 0;
					if (!empty($default_attrs)) {
						$default_variation_id = $product->get_matching_variation($default_attrs);
					}

					$start_price = null;
					if ($default_variation_id) {
						$default_variation = wc_get_product($default_variation_id);
						if ($default_variation) {
							$start_price = (float) $default_variation->get_price();
						}
					}
					if ($start_price === null) {
						$start_price = (float) $product->get_variation_price('min', true);
					}
					if ($start_price > 0) {
						$card_price_html = 'Starts at ' . wc_price($start_price);
					}
				} elseif (empty($price_display) && $product->get_price()) {
					$card_price_html = wc_price((float) $product->get_price());
				}

				$card_price_text = wp_strip_all_tags($card_price_html);
				$description = wp_strip_all_tags($product->get_description());
		?>
				<div class="product-card fade-in-item"
					data-categories='<?php echo esc_attr($categories_json); ?>'
					data-size-label="Cake Size"
					data-size-options='<?php echo esc_attr($size_terms_json); ?>'
					data-product-id="<?php echo esc_attr($product->get_id()); ?>"
					data-product-sku="<?php echo esc_attr($product->get_sku()); ?>"
					data-product-type="<?php echo esc_attr($product->get_type()); ?>"
					data-product-url="<?php echo esc_url($product->get_permalink()); ?>"
					data-add-to-cart-url="<?php echo esc_url($product->add_to_cart_url()); ?>"
					data-name="<?php echo esc_attr($product->get_name()); ?>"
					data-price="<?php echo esc_attr($card_price_text); ?>"
					data-description="<?php echo esc_attr($description); ?>"
					data-images='<?php echo esc_attr(wp_json_encode($images)); ?>'>
					<!-- Product Image -->
					<a href="<?php echo esc_url($product->get_permalink()); ?>" class="product-image-link" style="display: block; text-decoration: none;">
						<div class="product-image-wrapper" aria-label="<?php echo esc_attr($product->get_name()); ?>">
							<img src="<?php echo esc_url($product_image); ?>"
								alt="<?php echo esc_attr($product->get_name()); ?>"
								class="product-image"
								loading="lazy">
							<div class="product-overlay"></div>
						</div>
					</a>

					<!-- Product Info -->
					<div class="product-info">
						<h3 class="product-name">
							<span><?php echo $product->get_name(); ?></span>
						</h3>
						<div class="product-description"><?php echo $description; ?></div>

						<!-- Price and Buttons -->
						<div class="product-footer">
							<span class="product-price">
								<?php
								echo $card_price_html;
								?>
							</span>

							<div class="product-buttons">
								<a href="<?php echo esc_url($product->get_permalink()); ?>" class="btn-add">View Product</a>
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

		echo paginate_links(array(
			'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
			'format'    => '?paged=%#%',
			'current'   => max(1, get_query_var('paged')),
			'total'     => $total_pages,
			'prev_text' => '<i data-lucide="chevron-left" style="width: 1rem; height: 1rem;"></i>',
			'next_text' => '<i data-lucide="chevron-right" style="width: 1rem; height: 1rem;"></i>',
			'type'      => 'list',
			'end_size'  => 3,
			'mid_size'  => 3,
		));
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

		.page-numbers li a,
		.page-numbers li span {
			padding: 0.5rem 1rem;
			border: 1px solid var(--border);
			background-color: white;
			color: var(--foreground);
			font-family: var(--font-body);
			text-decoration: none;
			transition: all 0.3s ease;
			display: block;
		}

		.page-numbers li span.current,
		.page-numbers li a:hover {
			background-color: var(--primary);
			color: white;
			border-color: var(--primary);
		}
	</style>
</div>


<?php get_footer(); ?>

<?php get_footer(); ?>