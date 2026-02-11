<?php

/**
 * Custom Single Product Template
 * Optimized UX/UI Design
 *
 * @package Melt_Custom
 */

get_header();

while (have_posts()) : the_post();
    global $product;

    if (!$product || !$product->is_visible()) {
        return;
    }

    $melt_toast_message = '';
    $melt_has_success_notice = false;
    if (function_exists('wc_get_notices')) {
        $success_notices = wc_get_notices('success');
        if (!empty($success_notices)) {
            $first_notice = $success_notices[0];
            if (is_array($first_notice) && isset($first_notice['notice'])) {
                $melt_toast_message = wp_strip_all_tags($first_notice['notice']);
            } else {
                $melt_toast_message = wp_strip_all_tags($first_notice);
            }
            $melt_has_success_notice = true;
        }
    }
?>

    <div class="melt-product-page">
        <div
            class="melt-toast"
            id="meltToast"
            role="status"
            aria-live="polite"
            aria-atomic="true"
            data-has-success="<?php echo $melt_has_success_notice ? '1' : '0'; ?>"
            data-message="<?php echo esc_attr($melt_toast_message); ?>">
        </div>
        <!-- Breadcrumbs -->
        <div class="melt-product-breadcrumbs">
            <div class="melt-container">
                <nav class="breadcrumb-nav">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span class="breadcrumb-separator">/</span>
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Shop</a>
                    <span class="breadcrumb-separator">/</span>
                    <span><?php echo esc_html(get_the_title()); ?></span>
                </nav>
            </div>
        </div>

        <!-- Main Product Content -->
        <div class="melt-container">
            <div class="melt-product-layout">

                <!-- Left: Product Gallery -->
                <div class="melt-product-gallery">
                    <div class="melt-gallery-main">
                        <?php
                        $main_image_id = get_post_thumbnail_id();
                        $main_image_url = $main_image_id
                            ? wp_get_attachment_image_url($main_image_id, 'full')
                            : wc_placeholder_img_src('full');
                        $gallery_ids = $product->get_gallery_image_ids();
                        ?>
                        <div class="melt-main-image-wrapper" id="meltMainImageWrapper">
                            <img
                                src="<?php echo esc_url($main_image_url); ?>"
                                alt="<?php echo esc_attr(get_the_title()); ?>"
                                class="melt-main-image"
                                id="meltMainImage" />
                            <div class="melt-zoom-hint">Click to zoom</div>

                            <?php if (!empty($gallery_ids)) : ?>
                                <div class="melt-gallery-nav">
                                    <button class="melt-gallery-prev" id="meltGalleryPrev" aria-label="Previous image">
                                        <i data-lucide="chevron-left"></i>
                                    </button>
                                    <button class="melt-gallery-next" id="meltGalleryNext" aria-label="Next image">
                                        <i data-lucide="chevron-right"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($gallery_ids)) : ?>
                        <div class="melt-gallery-thumbnails" id="meltGalleryThumbs">
                            <?php
                            $main_thumb = $main_image_id
                                ? wp_get_attachment_image_url($main_image_id, 'thumbnail')
                                : wc_placeholder_img_src('thumbnail');
                            $main_full = $main_image_id
                                ? wp_get_attachment_image_url($main_image_id, 'full')
                                : wc_placeholder_img_src('full');
                            ?>
                            <div class="melt-thumbnail active" data-full="<?php echo esc_url($main_full); ?>">
                                <img src="<?php echo esc_url($main_thumb); ?>" alt="Product thumbnail" />
                            </div>

                            <?php foreach ($gallery_ids as $gallery_id) :
                                $thumb_url = wp_get_attachment_image_url($gallery_id, 'thumbnail');
                                $full_url = wp_get_attachment_image_url($gallery_id, 'full');
                            ?>
                                <div class="melt-thumbnail" data-full="<?php echo esc_url($full_url); ?>">
                                    <img src="<?php echo esc_url($thumb_url); ?>" alt="Product thumbnail" />
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Right: Product Info & Form -->
                <div class="melt-product-info">
                    <!-- Product Header -->
                    <div class="melt-product-header">
                        <h1 class="melt-product-title"><?php the_title(); ?></h1>

                        <?php if ($product->get_average_rating() > 0) : ?>
                            <div class="melt-product-rating">
                                <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                                <span class="melt-review-count">(<?php echo $product->get_review_count(); ?> reviews)</span>
                            </div>
                        <?php endif; ?>

                        <div class="melt-product-price" id="meltProductPrice">
                            <?php echo $product->get_price_html(); ?>
                        </div>

                        <!-- Stock Availability -->
                        <?php if ($product->is_in_stock()) : ?>
                            <div class="melt-stock-status in-stock">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="8" cy="8" r="8" fill="#10B981" />
                                    <path d="M5 8L7 10L11 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span>In Stock</span>
                                <?php
                                $stock_qty = $product->get_stock_quantity();
                                if ($stock_qty && $stock_qty <= 10) :
                                ?>
                                    <span class="melt-stock-urgency"> - Only <?php echo $stock_qty; ?> left!</span>
                                <?php endif; ?>
                            </div>
                        <?php else : ?>
                            <div class="melt-stock-status out-of-stock">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="8" cy="8" r="8" fill="#EF4444" />
                                    <path d="M5 5L11 11M5 11L11 5" stroke="white" stroke-width="2" stroke-linecap="round" />
                                </svg>
                                <span>Out of Stock</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Trust Signals -->
                    <div class="melt-trust-signals">
                        <div class="melt-trust-item">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 8.5L10 3L3 8.5V17H17V8.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M7 17V11H13V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Free shipping on orders over $50</span>
                        </div>
                        <div class="melt-trust-item">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 1L12.5 6.5L18.5 7.5L14.25 11.5L15.5 17.5L10 14.5L4.5 17.5L5.75 11.5L1.5 7.5L7.5 6.5L10 1Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>100% Secure Checkout</span>
                        </div>
                        <div class="melt-trust-item">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 10L8 15L17 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Easy 30-day returns</span>
                        </div>
                    </div>

                    <!-- Product Form Card -->
                    <div class="melt-product-form-card">
                        <?php if ($product->is_type('variable')) : ?>
                            <!-- Variable Product Form -->
                            <form class="melt-cart-form variations_form cart" method="post" enctype="multipart/form-data">
                                <div class="melt-variations-container">
                                    <?php
                                    $available_variations = $product->is_type('variable') ? $product->get_available_variations() : [];
                                    $attributes = $product->get_variation_attributes();
                                    foreach ($attributes as $attribute_name => $options) :
                                        $attribute_label = wc_attribute_label($attribute_name);
                                    ?>
                                        <div class="melt-variation-group">
                                            <label class="melt-variation-label" for="<?php echo esc_attr(sanitize_title($attribute_name)); ?>">
                                                <?php echo esc_html($attribute_label); ?>
                                            </label>
                                            <select
                                                id="<?php echo esc_attr(sanitize_title($attribute_name)); ?>"
                                                name="attribute_<?php echo esc_attr(sanitize_title($attribute_name)); ?>"
                                                class="melt-variation-select"
                                                data-attribute_name="attribute_<?php echo esc_attr(sanitize_title($attribute_name)); ?>">
                                                <option value="">Choose an option...</option>
                                                <?php foreach ($options as $option) :
                                                    $price_suffix = '';
                                                    foreach ($available_variations as $variation) {
                                                        $attr_key = 'attribute_' . sanitize_title($attribute_name);
                                                        if (isset($variation['attributes'][$attr_key])) {
                                                            if ($variation['attributes'][$attr_key] === $option || $variation['attributes'][$attr_key] === '') {
                                                                if (isset($variation['price_html']) && $variation['price_html']) {
                                                                    $price_suffix = ' - ' . strip_tags($variation['price_html']);
                                                                    $price_suffix = str_replace('&nbsp;', ' ', $price_suffix);
                                                                }
                                                                break;
                                                            }
                                                        }
                                                    }
                                                ?>
                                                    <option value="<?php echo esc_attr($option); ?>">
                                                        <?php echo esc_html(ucfirst($option) . $price_suffix); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    <?php endforeach; ?>

                                    <div class="melt-variation-reset" style="display: none;">
                                        <a href="#" class="melt-reset-variations">Clear selection</a>
                                    </div>
                                </div>

                                <?php /* Variation details removed - not needed for current implementation */ ?>

                                <div class="melt-quantity-group">
                                    <label class="melt-quantity-label" for="meltQuantity">Quantity</label>
                                    <div class="melt-quantity-controls">
                                        <button type="button" class="melt-qty-btn melt-qty-minus" aria-label="Decrease quantity">−</button>
                                        <?php
                                        $max_qty = $product->get_max_purchase_quantity();
                                        $max_attr = ($max_qty > 0) ? 'max="' . esc_attr($max_qty) . '"' : '';
                                        ?>
                                        <input
                                            type="number"
                                            id="meltQuantity"
                                            class="melt-quantity-input"
                                            name="quantity"
                                            value="1"
                                            min="1"
                                            <?php echo $max_attr; ?> />
                                        <button type="button" class="melt-qty-btn melt-qty-plus" aria-label="Increase quantity">+</button>
                                    </div>
                                </div>

                                <div class="melt-delivery-date-group">
                                    <label class="melt-delivery-label" for="meltDeliveryDate">Preferred Delivery Date</label>
                                    <input
                                        type="date"
                                        id="meltDeliveryDate"
                                        class="melt-delivery-input"
                                        name="delivery_date"
                                        min="<?php echo date('Y-m-d'); ?>" />
                                </div>

                                <button
                                    type="submit"
                                    class="melt-add-to-cart-btn"
                                    disabled>
                                    <span class="melt-btn-spinner" style="display: none;">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="2" stroke-opacity="0.25" />
                                            <path d="M10 2C10 2 10 2 10 2C14.4183 2 18 5.58172 18 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                        </svg>
                                    </span>
                                    <span class="melt-btn-text">Select options to add to cart</span>
                                </button>


                                <?php wp_nonce_field('woocommerce-add-to-cart', 'woocommerce-add-to-cart-nonce'); ?>
                                <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" />
                                <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>" />
                                <input type="hidden" name="variation_id" class="variation_id" value="0" />
                            </form>
                        <?php else : ?>
                            <!-- Simple Product Form -->
                            <form class="melt-cart-form cart" method="post" enctype="multipart/form-data">
                                <div class="melt-quantity-group">
                                    <label class="melt-quantity-label" for="meltQuantity">Quantity</label>
                                    <div class="melt-quantity-controls">
                                        <button type="button" class="melt-qty-btn melt-qty-minus" aria-label="Decrease quantity">−</button>
                                        <?php
                                        $max_qty = $product->get_max_purchase_quantity();
                                        $max_attr = ($max_qty > 0) ? 'max="' . esc_attr($max_qty) . '"' : '';
                                        ?>
                                        <input
                                            type="number"
                                            id="meltQuantity"
                                            class="melt-quantity-input"
                                            name="quantity"
                                            value="1"
                                            min="1"
                                            <?php echo $max_attr; ?> />
                                        <button type="button" class="melt-qty-btn melt-qty-plus" aria-label="Increase quantity">+</button>
                                    </div>
                                </div>

                                <div class="melt-delivery-date-group">
                                    <label class="melt-delivery-label" for="meltDeliveryDate">Preferred Delivery Date</label>
                                    <input
                                        type="date"
                                        id="meltDeliveryDate"
                                        class="melt-delivery-input"
                                        name="delivery_date"
                                        min="<?php echo date('Y-m-d'); ?>" />
                                </div>

                                <button
                                    type="submit"
                                    class="melt-add-to-cart-btn"
                                    name="add-to-cart"
                                    value="<?php echo esc_attr($product->get_id()); ?>">
                                    <span class="melt-btn-spinner" style="display: none;">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="2" stroke-opacity="0.25" />
                                            <path d="M10 2C10 2 10 2 10 2C14.4183 2 18 5.58172 18 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                        </svg>
                                    </span>
                                    <span class="melt-btn-text"><?php echo esc_html($product->single_add_to_cart_text()); ?></span>
                                </button>

                                <?php wp_nonce_field('woocommerce-add-to-cart', 'woocommerce-add-to-cart-nonce'); ?>
                            </form>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <!-- Product Tabs (Description & Reviews) -->
        <?php if ($product->get_description() || $product->get_reviews_allowed()) : ?>
            <div class="melt-product-tabs-section">
                <div class="melt-container">
                    <div class="melt-tabs-wrapper">
                        <div class="melt-tabs-nav">
                            <?php if ($product->get_description()) : ?>
                                <button class="melt-tab-btn active" data-tab="description">Description</button>
                            <?php endif; ?>
                            <?php if ($product->get_short_description()) : ?>
                                <button class="melt-tab-btn" data-tab="short-description">Quick Info</button>
                            <?php endif; ?>
                            <?php if ($product->get_reviews_allowed()) : ?>
                                <button class="melt-tab-btn" data-tab="reviews">Reviews (<?php echo $product->get_review_count(); ?>)</button>
                            <?php endif; ?>
                        </div>

                        <?php if ($product->get_description()) : ?>
                            <div class="melt-tab-content active" id="meltTabDescription">
                                <div class="melt-tab-inner">
                                    <?php echo wpautop($product->get_description()); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($product->get_short_description()) : ?>
                            <div class="melt-tab-content" id="meltTabShortDescription">
                                <div class="melt-tab-inner">
                                    <?php echo wpautop($product->get_short_description()); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($product->get_reviews_allowed()) : ?>
                            <div class="melt-tab-content" id="meltTabReviews">
                                <div class="melt-tab-inner">
                                    <div class="melt-reviews-header">
                                        <h3 class="melt-reviews-title">Customer Reviews</h3>
                                        <?php if (get_option('woocommerce_review_rating_verification_required') === 'no' || wc_customer_bought_product('', get_current_user_id(), $product->get_id())) : ?>
                                            <button class="melt-add-review-btn">Write a Review</button>
                                        <?php endif; ?>
                                    </div>

                                    <div class="melt-reviews-list">
                                        <?php
                                        $reviews = get_comments(array(
                                            'post_id' => $product->get_id(),
                                            'status' => 'approve',
                                            'type' => 'review'
                                        ));

                                        if ($reviews) :
                                            foreach ($reviews as $review) :
                                        ?>
                                                <div class="melt-review-item">
                                                    <div class="melt-review-header">
                                                        <span class="melt-review-author"><?php echo esc_html($review->comment_author); ?></span>
                                                        <span class="melt-review-date"><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($review->comment_date))); ?></span>
                                                    </div>
                                                    <div class="melt-review-rating">
                                                        <?php echo wc_get_rating_html(intval(get_comment_meta($review->comment_ID, 'rating', true))); ?>
                                                    </div>
                                                    <div class="melt-review-content">
                                                        <?php echo wpautop(esc_html($review->comment_content)); ?>
                                                    </div>
                                                </div>
                                            <?php
                                            endforeach;
                                        else :
                                            ?>
                                            <p class="melt-no-reviews">No reviews yet. Be the first to review this product!</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Related Products -->
        <?php
        $related_ids = wc_get_related_products($product->get_id(), 4);
        if (!empty($related_ids)) :
        ?>
            <div class="melt-related-products">
                <div class="melt-container">
                    <div class="melt-related-header">
                        <h2 class="melt-related-title">You Might Also Like</h2>
                        <p class="melt-related-subtitle">Discover more delicious treats from our collection</p>
                    </div>
                    <div class="melt-related-grid">
                        <?php foreach ($related_ids as $related_id) :
                            $related_product = wc_get_product($related_id);
                            if (!$related_product) continue;
                        ?>
                            <div class="melt-related-card">
                                <a href="<?php echo esc_url(get_permalink($related_id)); ?>" class="melt-related-link">
                                    <div class="melt-related-image">
                                        <?php echo $related_product->get_image('woocommerce_thumbnail'); ?>
                                    </div>
                                    <div class="melt-related-info">
                                        <h3 class="melt-related-name"><?php echo esc_html($related_product->get_name()); ?></h3>
                                        <div class="melt-related-price">
                                            <?php echo $related_product->get_price_html(); ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Sticky Mobile Add-to-Cart Bar -->
        <div class="melt-sticky-mobile-bar" id="meltStickyBar">
            <div class="melt-sticky-bar-content">
                <div class="melt-sticky-bar-info">
                    <div class="melt-sticky-bar-price" id="meltStickyPrice">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    <?php if ($product->is_in_stock()) : ?>
                        <div class="melt-sticky-bar-stock">
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="6" cy="6" r="6" fill="#10B981" />
                                <path d="M4 6L5.5 7.5L8 5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>In Stock</span>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="button" class="melt-sticky-bar-btn" id="meltStickyAddBtn">
                    <span>Add to Cart</span>
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 1L3 4M3 4L6 7M3 4H11C13.2091 4 15 5.79086 15 8V8C15 10.2091 13.2091 12 11 12H4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

<?php
endwhile;
get_footer();
