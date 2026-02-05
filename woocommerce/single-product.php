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
                                    <button class="melt-gallery-prev" id="meltGalleryPrev" aria-label="Previous image">‹</button>
                                    <button class="melt-gallery-next" id="meltGalleryNext" aria-label="Next image">›</button>
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

                        <div class="melt-product-price">
                            <?php echo $product->get_price_html(); ?>
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
    </div>

    <script>
        jQuery(document).ready(function($) {
            'use strict';

            const toast = document.getElementById('meltToast');
            let toastTimer = null;

            function showToast(message) {
                if (!toast || !message) return;
                toast.textContent = message;
                toast.classList.add('is-visible');
                if (toastTimer) clearTimeout(toastTimer);
                toastTimer = setTimeout(() => {
                    toast.classList.remove('is-visible');
                }, 2800);
            }

            document.querySelectorAll('.woocommerce-message, .woocommerce-notice--success').forEach((notice) => {
                notice.remove();
            });

            if (toast) {
                const hasSuccess = toast.dataset.hasSuccess === '1';
                const noticeMessage = toast.dataset.message || 'Added to cart';
                if (hasSuccess) {
                    showToast(noticeMessage);
                }
            }

            $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
                let message = 'Added to cart';
                if ($button && $button.data && $button.data('product_name')) {
                    message = $button.data('product_name') + ' added to cart';
                }
                showToast(message);
            });

            console.log('Melt Product Script Loaded');

            // Gallery functionality
            const mainImage = document.getElementById('meltMainImage');
            const mainImageWrapper = document.getElementById('meltMainImageWrapper');
            const thumbnails = document.querySelectorAll('.melt-thumbnail');
            const prevBtn = document.getElementById('meltGalleryPrev');
            const nextBtn = document.getElementById('meltGalleryNext');
            let currentImageIndex = 0;

            // Thumbnail click
            thumbnails.forEach((thumb, index) => {
                thumb.addEventListener('click', function() {
                    const fullUrl = this.dataset.full;
                    if (fullUrl && mainImage) {
                        mainImage.style.opacity = '0.5';
                        setTimeout(() => {
                            mainImage.src = fullUrl;
                            mainImage.style.opacity = '1';
                        }, 150);
                    }

                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentImageIndex = index;
                });
            });

            // Navigation arrows
            if (prevBtn && nextBtn && thumbnails.length > 1) {
                prevBtn.addEventListener('click', () => {
                    currentImageIndex = (currentImageIndex - 1 + thumbnails.length) % thumbnails.length;
                    thumbnails[currentImageIndex].click();
                });

                nextBtn.addEventListener('click', () => {
                    currentImageIndex = (currentImageIndex + 1) % thumbnails.length;
                    thumbnails[currentImageIndex].click();
                });
            }

            // Image zoom
            if (mainImageWrapper) {
                let isZoomed = false;
                mainImageWrapper.addEventListener('click', function(e) {
                    if (e.target.tagName === 'BUTTON') return;
                    isZoomed = !isZoomed;
                    this.classList.toggle('zoomed', isZoomed);
                });
            }

            // Quantity controls - jQuery Event Delegation
            $(document).on('click', '.melt-qty-minus, .melt-qty-plus', function(e) {
                e.preventDefault();

                const $btn = $(this);
                const $wrapper = $btn.closest('.melt-quantity-controls');
                const $input = $wrapper.find('.melt-quantity-input');

                if (!$input.length) return;

                let current = parseFloat($input.val());
                if (isNaN(current)) current = 1;

                const min = parseFloat($input.attr('min')) || 1;
                const max = parseFloat($input.attr('max'));
                const step = parseFloat($input.attr('step')) || 1;

                if ($btn.hasClass('melt-qty-minus')) {
                    if (current > min) {
                        $input.val(current - step).trigger('change');
                    }
                } else {
                    // -1 or NaN means unlimited in WooCommerce
                    if (isNaN(max) || max === -1 || current < max) {
                        $input.val(current + step).trigger('change');
                    }
                }
            });

            // Variable product handling with WooCommerce integration
            const variationForm = $('.variations_form');
            if (variationForm.length) {
                const selects = variationForm.find('.melt-variation-select');
                const addToCartBtn = variationForm.find('.melt-add-to-cart-btn');
                const resetLink = variationForm.find('.melt-reset-variations');
                const priceDisplay = $('.melt-product-price');
                const productId = variationForm.find('input[name="product_id"]').val();

                // Store original price HTML
                const originalPriceHtml = priceDisplay.html();

                // Get variation data from WooCommerce
                let variationsData = [];

                // Fetch variations data via AJAX
                if (productId) {
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {
                            action: 'get_product_variations',
                            product_id: productId
                        },
                        success: function(response) {
                            if (response.success && response.data) {
                                variationsData = response.data;
                                console.log('Variations loaded:', variationsData.length);
                            }
                        }
                    });
                }

                selects.on('change', function() {
                    checkVariationSelection();
                    updateVariationPrice();
                });

                function checkVariationSelection() {
                    let allSelected = true;
                    selects.each(function() {
                        if (!$(this).val()) allSelected = false;
                    });

                    if (allSelected) {
                        addToCartBtn.prop('disabled', false);
                        addToCartBtn.find('.melt-btn-text').text('Add to cart');
                        if (resetLink.parent()) {
                             resetLink.parent().show();
                        }
                    } else {
                        addToCartBtn.prop('disabled', true);
                        addToCartBtn.find('.melt-btn-text').text('Select options to add to cart');
                        if (resetLink.parent()) {
                            resetLink.parent().hide();
                        }
                    }
                }

                function updateVariationPrice() {
                    if (!productId || !priceDisplay.length) return;

                    // Collect selected attributes
                    const selectedAttributes = {};
                    let allSelected = true;

                    selects.each(function() {
                        const $select = $(this);
                        const value = $select.val();
                        if (value) {
                            selectedAttributes[$select.attr('name')] = value;
                        } else {
                            allSelected = false;
                        }
                    });

                    if (!allSelected) {
                        // Reset to original price if not all selected
                        priceDisplay.html(originalPriceHtml);
                        // Reset variation_id
                        variationForm.find('.variation_id').val('0');
                        return;
                    }

                    // Find matching variation
                    const matchingVariation = findMatchingVariation(selectedAttributes);

                    if (matchingVariation && matchingVariation.price_html) {
                        priceDisplay.html(matchingVariation.price_html);
                        // Set the variation_id for add to cart
                        variationForm.find('.variation_id').val(matchingVariation.variation_id);
                        console.log('Price updated:', matchingVariation.display_price);
                        console.log('Variation ID set:', matchingVariation.variation_id);
                    } else {
                        // Fallback: Use AJAX to get variation data
                        $.ajax({
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            type: 'POST',
                            data: {
                                action: 'get_variation_price',
                                product_id: productId,
                                attributes: selectedAttributes
                            },
                            success: function(response) {
                                if (response.success && response.data && response.data.price_html) {
                                    priceDisplay.html(response.data.price_html);
                                    // Set the variation_id for add to cart
                                    if (response.data.variation_id) {
                                        variationForm.find('.variation_id').val(response.data.variation_id);
                                        console.log('Variation ID set via AJAX:', response.data.variation_id);
                                    }
                                    console.log('Price updated via AJAX');
                                }
                            }
                        });
                    }
                }

                function findMatchingVariation(selectedAttributes) {
                    if (!variationsData || variationsData.length === 0) return null;

                    for (let i = 0; i < variationsData.length; i++) {
                        const variation = variationsData[i];
                        let matches = true;

                        for (let attrName in selectedAttributes) {
                            const selectedValue = selectedAttributes[attrName].toLowerCase();
                            const variationValue = (variation.attributes[attrName] || '').toLowerCase();

                            // Empty variation attribute means "any"
                            if (variationValue !== '' && variationValue !== selectedValue) {
                                matches = false;
                                break;
                            }
                        }

                        if (matches) {
                            return variation;
                        }
                    }

                    return null;
                }

                resetLink.on('click', function(e) {
                    e.preventDefault();
                    selects.val('').trigger('change');
                    priceDisplay.html(originalPriceHtml);
                    checkVariationSelection();
                });
            }

            // Tabs
            $('.melt-tab-btn').on('click', function() {
                const targetTab = $(this).data('tab');

                $('.melt-tab-btn').removeClass('active');
                $(this).addClass('active');

                $('.melt-tab-content').removeClass('active');

                // Construct ID safely
                const tabId = 'meltTab' + targetTab.split('-').map(function(word) {
                    return word.charAt(0).toUpperCase() + word.slice(1);
                }).join('');

                $('#' + tabId).addClass('active');
            });

            // Delivery date persistence
            const deliveryInput = document.getElementById('meltDeliveryDate');
            if (deliveryInput) {
                const saved = localStorage.getItem('melt_delivery_date');
                if (saved) deliveryInput.value = saved;
                deliveryInput.addEventListener('change', function() {
                    localStorage.setItem('melt_delivery_date', this.value);
                });
            }

            // Initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

<?php
endwhile;
get_footer();
