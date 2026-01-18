/**
 * Customize Modal JavaScript
 * Handles the cake customization interface
 */

(function () {
    'use strict';

    // Customization prices
    const CUSTOMIZATION_PRICES = {
        sizes: {
            "Small (6 inch)": 0,
            "Medium (8 inch)": 50,
            "Large (10 inch)": 100,
            "Extra Large (12 inch)": 180
        },
        layers: 30, // per additional layer
        toppings: {
            "Fresh Berries": 25,
            "Edible Gold Leaf": 45,
            "Chocolate Shavings": 20,
            "Fresh Flowers": 35,
            "Macarons": 40,
            "Nuts & Almonds": 15,
            "Candy Pearls": 30
        },
        decorations: {
            "Simple": 0,
            "Elegant Piping": 50,
            "Custom Design": 100,
            "Premium 3D Design": 200
        }
    };

    // Current product being customized
    let currentProduct = null;

    // Customization state
    let customization = {
        size: "Medium (8 inch)",
        layers: 2,
        flavor: "Vanilla",
        frosting: "Buttercream",
        filling: "Vanilla Cream",
        toppings: [],
        decoration: "Simple",
        customMessage: "",
        deliveryDate: "",
        specialInstructions: ""
    };

    // Open customize modal
    window.openCustomizeModal = function (productId, productName, productPrice, productImage) {
        currentProduct = {
            id: productId,
            name: productName,
            price: productPrice,
            image: productImage
        };

        // Reset customization
        customization = {
            size: "Medium (8 inch)",
            layers: 2,
            flavor: "Vanilla",
            frosting: "Buttercream",
            filling: "Vanilla Cream",
            toppings: [],
            decoration: "Simple",
            customMessage: "",
            deliveryDate: "",
            specialInstructions: ""
        };

        // Show modal
        document.getElementById('customizeModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';

        // Update product info
        document.getElementById('modalProductName').textContent = productName;
        document.getElementById('modalProductImage').src = productImage;

        // Set minimum date for delivery
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('deliveryDate').setAttribute('min', today);

        // Update price
        updateTotalPrice();

        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    };

    // Close modal
    window.closeCustomizeModal = function () {
        document.getElementById('customizeModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    };

    // Calculate additional price
    function calculateAdditionalPrice() {
        let additional = 0;

        // Size
        additional += CUSTOMIZATION_PRICES.sizes[customization.size] || 0;

        // Layers (beyond 2)
        if (customization.layers > 2) {
            additional += (customization.layers - 2) * CUSTOMIZATION_PRICES.layers;
        }

        // Toppings
        customization.toppings.forEach(topping => {
            additional += CUSTOMIZATION_PRICES.toppings[topping] || 0;
        });

        // Decoration
        additional += CUSTOMIZATION_PRICES.decorations[customization.decoration] || 0;

        return additional;
    }

    // Update total price display
    function updateTotalPrice() {
        const additionalPrice = calculateAdditionalPrice();
        const totalPrice = currentProduct.price + additionalPrice;

        document.getElementById('totalPrice').textContent = totalPrice.toFixed(2);
        document.getElementById('basePrice').textContent = currentProduct.price.toFixed(2);
        document.getElementById('additionalPrice').textContent = additionalPrice.toFixed(2);

        // Show/hide breakdown
        if (additionalPrice > 0) {
            document.getElementById('priceBreakdown').style.display = 'block';
        } else {
            document.getElementById('priceBreakdown').style.display = 'none';
        }
    }

    // Select size
    window.selectSize = function (size, button) {
        customization.size = size;

        // Update UI
        document.querySelectorAll('.size-option').forEach(btn => {
            btn.classList.remove('active');
        });
        if (button) { button.classList.add('active'); }

        updateTotalPrice();
    };

    // Change layers
    window.changeLayers = function (delta) {
        const newLayers = customization.layers + delta;
        if (newLayers >= 2 && newLayers <= 10) {
            customization.layers = newLayers;
            document.getElementById('layersCount').textContent = newLayers;

            // Update layer price display
            const layerPrice = document.getElementById('layerPrice');
            if (newLayers > 2) {
                layerPrice.textContent = '+' + ((newLayers - 2) * CUSTOMIZATION_PRICES.layers) + ' AED';
                layerPrice.style.display = 'inline';
            } else {
                layerPrice.style.display = 'none';
            }

            updateTotalPrice();
        }
    };

    // Toggle topping
    window.toggleTopping = function (topping, button) {
        const index = customization.toppings.indexOf(topping);
        if (index > -1) {
            customization.toppings.splice(index, 1);
            if (button) { button.classList.remove('active'); }
        } else {
            customization.toppings.push(topping);
            if (button) { button.classList.add('active'); }
        }
        updateTotalPrice();
    };

    // Select decoration
    window.selectDecoration = function (decoration, button) {
        customization.decoration = decoration;

        // Update UI
        document.querySelectorAll('.decoration-option').forEach(btn => {
            btn.classList.remove('active');
        });
        if (button) { button.classList.add('active'); }

        updateTotalPrice();
    };

    // Update select fields
    window.updateCustomization = function (field, value) {
        customization[field] = value;
    };

    // Update character count
    window.updateCharCount = function (field, maxLength, inputEl) {
        const value = inputEl ? inputEl.value : '';
        document.getElementById(field + 'Count').textContent = value.length;
    };

    // Add customized product to cart
    window.addCustomizedToCart = function (evt) {
        const totalPrice = currentProduct.price + calculateAdditionalPrice();

        // Get the button that was clicked
        const button = evt ? evt.target.closest('button') : null;

        // Show loading state if button exists
        let originalHTML = '';
        if (button) {
            originalHTML = button.innerHTML;
            button.innerHTML = '<span style="position: relative; z-index: 1;">Adding to Cart...</span>';
            button.disabled = true;
        }

        // Prepare customization data
        const params = new URLSearchParams();
        params.append('action', 'melt_add_customized_to_cart');
        params.append('product_id', currentProduct.id);
        params.append('nonce', meltAjax.nonce);

        Object.keys(customization).forEach((key) => {
            const value = customization[key];
            if (Array.isArray(value)) {
                value.forEach((item) => {
                    params.append('customization[' + key + '][]', item);
                });
                return;
            }
            params.append('customization[' + key + ']', value ?? '');
        });

        // Send AJAX request
        fetch(meltAjax.ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: params
        })
            .then(response => response.json())
            .then(data => {
                if (button) {
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                }

                if (data.success) {
                    // Close modal
                    closeCustomizeModal();

                    // Trigger WooCommerce added_to_cart event to show toast
                    jQuery(document.body).trigger('added_to_cart', [data.data.fragments, data.data.cart_hash, jQuery(button || document.body)]);

                    // Update cart fragments if available
                    if (data.data && data.data.fragments) {
                        jQuery.each(data.data.fragments, function (key, value) {
                            jQuery(key).replaceWith(value);
                        });
                    }
                } else {
                    const errorMsg = data.data && data.data.message ? data.data.message : 'Unknown error';
                    console.error('Cart error:', data);
                    alert('Error adding to cart: ' + errorMsg);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (button) {
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                }
                alert('Error adding product to cart. Please try again.');
            });
    };

    // Close modal when clicking outside
    window.addEventListener('click', function (event) {
        const modal = document.getElementById('customizeModal');
        if (event.target === modal) {
            closeCustomizeModal();
        }
    });

})(); // End IIFE



