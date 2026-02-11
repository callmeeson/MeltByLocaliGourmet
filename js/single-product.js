jQuery(document).ready(function ($) {
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

    $(document.body).on('added_to_cart', function (event, fragments, cart_hash, $button) {
        let message = 'Added to cart';
        if ($button && $button.data && $button.data('product_name')) {
            message = $button.data('product_name') + ' added to cart';
        }
        showToast(message);
    });

    console.log('Melt Product Script Loaded');

    // Sticky Mobile Bar Functionality
    const stickyBar = document.getElementById('meltStickyBar');
    const productInfo = document.querySelector('.melt-product-info');
    const stickyAddBtn = document.getElementById('meltStickyAddBtn');
    const mainAddBtn = document.querySelector('.melt-add-to-cart-btn');
    const stickyPrice = document.getElementById('meltStickyPrice');
    const mainPrice = document.getElementById('meltProductPrice');

    if (stickyBar && productInfo) {
        let lastScrollY = window.scrollY;
        let ticking = false;

        function updateStickyBar() {
            const productInfoRect = productInfo.getBoundingClientRect();
            const isProductInfoAboveViewport = productInfoRect.bottom < 0;

            if (isProductInfoAboveViewport) {
                stickyBar.classList.add('is-visible');
            } else {
                stickyBar.classList.remove('is-visible');
            }

            ticking = false;
        }

        function requestTick() {
            if (!ticking) {
                window.requestAnimationFrame(updateStickyBar);
                ticking = true;
            }
        }

        window.addEventListener('scroll', requestTick, { passive: true });
        window.addEventListener('resize', requestTick, { passive: true });

        // Sticky button click handler
        if (stickyAddBtn && mainAddBtn) {
            stickyAddBtn.addEventListener('click', function () {
                // Scroll to main button and click it
                window.scrollTo({
                    top: productInfo.offsetTop - 100,
                    behavior: 'smooth'
                });
                setTimeout(() => {
                    mainAddBtn.click();
                }, 300);
            });
        }

        // Sync sticky price with main price
        if (stickyPrice && mainPrice) {
            const priceObserver = new MutationObserver(function (mutations) {
                stickyPrice.innerHTML = mainPrice.innerHTML;
            });
            priceObserver.observe(mainPrice, { childList: true, subtree: true });
        }
    }

    // Loading States for Add to Cart Buttons
    const addToCartForms = document.querySelectorAll('.melt-cart-form');
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const submitBtn = form.querySelector('.melt-add-to-cart-btn');
            const spinner = submitBtn.querySelector('.melt-btn-spinner');
            const btnText = submitBtn.querySelector('.melt-btn-text');

            if (submitBtn && !submitBtn.disabled) {
                submitBtn.classList.add('is-loading');
                if (spinner) spinner.style.display = 'inline-flex';
                if (btnText) btnText.textContent = 'Adding...';

                // Reset after 3 seconds as fallback
                setTimeout(() => {
                    submitBtn.classList.remove('is-loading');
                    if (spinner) spinner.style.display = 'none';
                    if (btnText) btnText.textContent = 'Add to Cart';
                }, 3000);
            }
        });
    });

    // Reset loading state on successful add to cart
    $(document.body).on('added_to_cart', function () {
        addToCartForms.forEach(form => {
            const submitBtn = form.querySelector('.melt-add-to-cart-btn');
            const spinner = submitBtn ? submitBtn.querySelector('.melt-btn-spinner') : null;
            const btnText = submitBtn ? submitBtn.querySelector('.melt-btn-text') : null;

            if (submitBtn) {
                submitBtn.classList.remove('is-loading');
                if (spinner) spinner.style.display = 'none';
                if (btnText) btnText.textContent = 'Add to Cart';
            }
        });
    });

    // Gallery functionality
    const mainImage = document.getElementById('meltMainImage');
    const mainImageWrapper = document.getElementById('meltMainImageWrapper');
    const thumbnails = document.querySelectorAll('.melt-thumbnail');
    const prevBtn = document.getElementById('meltGalleryPrev');
    const nextBtn = document.getElementById('meltGalleryNext');
    let currentImageIndex = 0;

    // Function to change image
    function changeImage(thumb, index) {
        const fullUrl = thumb.dataset.full;
        if (fullUrl && mainImage) {
            mainImage.style.opacity = '0.5';
            setTimeout(() => {
                mainImage.src = fullUrl;
                mainImage.style.opacity = '1';
            }, 150);
        }

        thumbnails.forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');
        currentImageIndex = index;
    }

    // Thumbnail click and touch events
    thumbnails.forEach((thumb, index) => {
        // Click event
        thumb.addEventListener('click', function (e) {
            e.preventDefault();
            changeImage(this, index);
        });

        // Touch event for mobile
        thumb.addEventListener('touchend', function (e) {
            e.preventDefault();
            changeImage(this, index);
        }, { passive: false });
    });

    // Navigation arrows
    if (prevBtn && nextBtn && thumbnails.length > 1) {
        prevBtn.addEventListener('click', () => {
            currentImageIndex = (currentImageIndex - 1 + thumbnails.length) % thumbnails.length;
            thumbnails[currentImageIndex].click();
            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        nextBtn.addEventListener('click', () => {
            currentImageIndex = (currentImageIndex + 1) % thumbnails.length;
            thumbnails[currentImageIndex].click();
            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    }

    // Image zoom
    if (mainImageWrapper) {
        let isZoomed = false;
        mainImageWrapper.addEventListener('click', function (e) {
            if (e.target.tagName === 'BUTTON') return;
            isZoomed = !isZoomed;
            this.classList.toggle('zoomed', isZoomed);
        });
    }

    // Quantity controls - jQuery Event Delegation
    $(document).on('click', '.melt-qty-minus, .melt-qty-plus', function (e) {
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
                url: meltData.ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_product_variations',
                    product_id: productId
                },
                success: function (response) {
                    if (response.success && response.data) {
                        variationsData = response.data;
                        console.log('Variations loaded:', variationsData.length);
                    }
                }
            });
        }

        selects.on('change', function () {
            checkVariationSelection();
            updateVariationPrice();
        });

        function checkVariationSelection() {
            let allSelected = true;
            selects.each(function () {
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

            selects.each(function () {
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
                    url: meltData.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'get_variation_price',
                        product_id: productId,
                        attributes: selectedAttributes
                    },
                    success: function (response) {
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

        resetLink.on('click', function (e) {
            e.preventDefault();
            selects.val('').trigger('change');
            priceDisplay.html(originalPriceHtml);
            checkVariationSelection();
        });
    }

    // Tabs
    $('.melt-tab-btn').on('click', function () {
        const targetTab = $(this).data('tab');

        $('.melt-tab-btn').removeClass('active');
        $(this).addClass('active');

        $('.melt-tab-content').removeClass('active');

        // Construct ID safely
        const tabId = 'meltTab' + targetTab.split('-').map(function (word) {
            return word.charAt(0).toUpperCase() + word.slice(1);
        }).join('');

        $('#' + tabId).addClass('active');
    });

    // Delivery date persistence
    const deliveryInput = document.getElementById('meltDeliveryDate');
    if (deliveryInput) {
        const saved = localStorage.getItem('melt_delivery_date');
        if (saved) deliveryInput.value = saved;
        deliveryInput.addEventListener('change', function () {
            localStorage.setItem('melt_delivery_date', this.value);
        });
    }

    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
