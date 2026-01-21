<?php

/**
 * Custom WooCommerce Functions
 * Handles custom cart data, customization logic, and AJAX handlers
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle adding customized product to cart via AJAX
 * 
 * Security: All inputs are sanitized and validated
 */
function melt_add_customized_to_cart()
{
    // Check nonce with expiration handling
    $nonce_check = check_ajax_referer('melt_nonce', 'nonce', false);
    if (!$nonce_check) {
        wp_send_json_error([
            'message' => 'Your session has expired. Please refresh the page and try again.',
            'nonce_expired' => true
        ]);
    }

    if (!isset($_POST['product_id'])) {
        melt_log_error('WooCommerce', 'Product ID missing in customization request');
        wp_send_json_error(['message' => 'Product ID is required']);
    }

    $product_id = intval($_POST['product_id']);

    // Validate product exists
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error(['message' => 'Invalid product']);
    }

    // Sanitize customization data
    $raw_customization = isset($_POST['customization']) ? $_POST['customization'] : [];
    $customization = [];

    // Sanitize text fields
    if (isset($raw_customization['size'])) {
        $customization['size'] = sanitize_text_field($raw_customization['size']);
    }
    if (isset($raw_customization['flavor'])) {
        $customization['flavor'] = sanitize_text_field($raw_customization['flavor']);
    }
    if (isset($raw_customization['frosting'])) {
        $customization['frosting'] = sanitize_text_field($raw_customization['frosting']);
    }
    if (isset($raw_customization['filling'])) {
        $customization['filling'] = sanitize_text_field($raw_customization['filling']);
    }
    if (isset($raw_customization['decoration'])) {
        $customization['decoration'] = sanitize_text_field($raw_customization['decoration']);
    }
    if (isset($raw_customization['customMessage'])) {
        $customization['customMessage'] = sanitize_textarea_field($raw_customization['customMessage']);
    }
    if (isset($raw_customization['specialInstructions'])) {
        $customization['specialInstructions'] = sanitize_textarea_field($raw_customization['specialInstructions']);
    }

    // Sanitize numeric fields
    if (isset($raw_customization['layers'])) {
        $customization['layers'] = absint($raw_customization['layers']);
        // Validate range (2-10 layers)
        if ($customization['layers'] < 2 || $customization['layers'] > 10) {
            wp_send_json_error(['message' => 'Invalid number of layers']);
        }
    }

    // Sanitize date field
    if (isset($raw_customization['deliveryDate'])) {
        $customization['deliveryDate'] = sanitize_text_field($raw_customization['deliveryDate']);
        // Validate date format (YYYY-MM-DD)
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $customization['deliveryDate'])) {
            wp_send_json_error(['message' => 'Invalid delivery date format']);
        }
    }

    // Sanitize array fields (toppings)
    if (isset($raw_customization['toppings']) && is_array($raw_customization['toppings'])) {
        $customization['toppings'] = array_map('sanitize_text_field', $raw_customization['toppings']);
    }

    // Calculate custom price
    $base_price = (float) get_post_meta($product_id, '_price', true);
    $extra_cost = 0;

    // Prices matching JS (whitelist for security)
    $prices = [
        'sizes' => [
            "Small (6 inch)" => 0,
            "Medium (8 inch)" => 50,
            "Large (10 inch)" => 100,
            "Extra Large (12 inch)" => 180
        ],
        'layers' => 30, // for each layer > 2
        'toppings' => [
            "Fresh Berries" => 25,
            "Edible Gold Leaf" => 45,
            "Chocolate Shavings" => 20,
            "Fresh Flowers" => 35,
            "Macarons" => 40,
            "Nuts & Almonds" => 15,
            "Candy Pearls" => 30
        ],
        'decorations' => [
            "Simple" => 0,
            "Elegant Piping" => 50,
            "Custom Design" => 100,
            "Premium 3D Design" => 200
        ]
    ];

    // Calculate Extra Cost (only from whitelisted values)
    if (isset($customization['size']) && isset($prices['sizes'][$customization['size']])) {
        $extra_cost += $prices['sizes'][$customization['size']];
    }

    if (isset($customization['layers']) && intval($customization['layers']) > 2) {
        $extra_cost += (intval($customization['layers']) - 2) * $prices['layers'];
    }

    if (isset($customization['toppings']) && is_array($customization['toppings'])) {
        foreach ($customization['toppings'] as $topping) {
            if (isset($prices['toppings'][$topping])) {
                $extra_cost += $prices['toppings'][$topping];
            }
        }
    }

    if (isset($customization['decoration']) && isset($prices['decorations'][$customization['decoration']])) {
        $extra_cost += $prices['decorations'][$customization['decoration']];
    }

    // Add to cart with custom data
    $cart_item_data = [
        'melt_customization' => $customization,
        'melt_extra_cost' => $extra_cost
    ];

    $cart_item_key = WC()->cart->add_to_cart($product_id, 1, 0, [], $cart_item_data);

    if ($cart_item_key) {
        wp_send_json_success([
            'message' => 'Product added to cart',
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_total' => WC()->cart->get_cart_total()
        ]);
    } else {
        error_log('Melt Theme: Failed to add product to cart. Product ID: ' . $product_id);
        wp_send_json_error(['message' => 'Failed to add to cart']);
    }
}
add_action('wp_ajax_melt_add_customized_to_cart', 'melt_add_customized_to_cart');
add_action('wp_ajax_nopriv_melt_add_customized_to_cart', 'melt_add_customized_to_cart');

/**
 * Restore custom data from session
 */
function melt_get_cart_item_from_session($cart_item, $values)
{
    if (isset($values['melt_customization'])) {
        $cart_item['melt_customization'] = $values['melt_customization'];
    }
    if (isset($values['melt_extra_cost'])) {
        $cart_item['melt_extra_cost'] = $values['melt_extra_cost'];
    }
    return $cart_item;
}
add_filter('woocommerce_get_cart_item_from_session', 'melt_get_cart_item_from_session', 20, 2);

/**
 * Add custom price to cart item
 */
function melt_apply_custom_price($cart)
{
    if (is_admin() && !defined('DOING_AJAX')) return;

    foreach ($cart->get_cart() as $cart_item) {
        if (isset($cart_item['melt_extra_cost'])) {
            $product = $cart_item['data'];
            // Price MUST be set on the product object in the cart
            $original_price = $product->get_price();
            $new_price = $original_price + $cart_item['melt_extra_cost'];
            $product->set_price($new_price);
        }
    }
}
add_action('woocommerce_before_calculate_totals', 'melt_apply_custom_price', 10, 1);

/**
 * Remove "View cart" link from add-to-cart notices.
 */
function melt_strip_view_cart_link($message, $products) {
    $message = preg_replace('/<a[^>]*>.*?<\/a>/i', '', $message);
    return trim($message);
}
add_filter('wc_add_to_cart_message_html', 'melt_strip_view_cart_link', 10, 2);

/**
 * Display custom data in cart and checkout
 * 
 * Security: All output is escaped to prevent XSS
 */
function melt_display_custom_data_in_cart($item_data, $cart_item)
{
    if (isset($cart_item['melt_customization'])) {
        $customs = $cart_item['melt_customization'];

        if (!empty($customs['size'])) {
            $item_data[] = ['key' => 'Size', 'value' => esc_html($customs['size'])];
        }
        if (!empty($customs['layers'])) {
            $item_data[] = ['key' => 'Layers', 'value' => esc_html($customs['layers'])];
        }
        if (!empty($customs['flavor'])) {
            $item_data[] = ['key' => 'Flavor', 'value' => esc_html($customs['flavor'])];
        }
        if (!empty($customs['frosting'])) {
            $item_data[] = ['key' => 'Frosting', 'value' => esc_html($customs['frosting'])];
        }
        if (!empty($customs['filling'])) {
            $item_data[] = ['key' => 'Filling', 'value' => esc_html($customs['filling'])];
        }
        if (!empty($customs['toppings']) && is_array($customs['toppings'])) {
            // Escape each topping individually
            $escaped_toppings = array_map('esc_html', $customs['toppings']);
            $item_data[] = ['key' => 'Toppings', 'value' => implode(', ', $escaped_toppings)];
        }
        if (!empty($customs['decoration'])) {
            $item_data[] = ['key' => 'Decoration', 'value' => esc_html($customs['decoration'])];
        }
        if (!empty($customs['customMessage'])) {
            $item_data[] = ['key' => 'Message', 'value' => esc_html($customs['customMessage'])];
        }
        if (!empty($customs['deliveryDate'])) {
            $item_data[] = ['key' => 'Delivery Date', 'value' => esc_html($customs['deliveryDate'])];
        }
        if (!empty($customs['specialInstructions'])) {
            $item_data[] = ['key' => 'Instructions', 'value' => esc_html($customs['specialInstructions'])];
        }
    }
    return $item_data;
}
add_filter('woocommerce_get_item_data', 'melt_display_custom_data_in_cart', 10, 2);

/**
 * Add custom data to order line items
 */
function melt_add_custom_data_to_order($item, $cart_item_key, $values, $order)
{
    if (isset($values['melt_customization'])) {
        $customs = $values['melt_customization'];

        foreach ($customs as $key => $value) {
            if (!empty($value)) {
                if (is_array($value)) {
                    $value = implode(', ', $value);
                }
                // Format key label
                $label = ucfirst(preg_replace('/(?<!^)[A-Z]/', ' $0', $key)); // camelCase to Normal Case
                $item->add_meta_data($label, $value);
            }
        }
    }
}

/**
 * AJAX Handler for Cart Actions (Update, Remove, Coupon)
 */
function melt_ajax_cart_action()
{
    check_ajax_referer('melt_nonce', 'nonce');

    if (! function_exists('WC')) {
        wp_send_json_error(array('message' => 'WooCommerce not active'));
    }

    $action_type = isset($_POST['action_type']) ? sanitize_text_field($_POST['action_type']) : '';
    $message = '';
    $success = true;

    try {
        switch ($action_type) {
            case 'update_quantity':
                $cart_item_key = isset($_POST['cart_item_key']) ? sanitize_text_field($_POST['cart_item_key']) : '';
                $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 0;

                if ($cart_item_key && $quantity >= 0) {
                    if ($quantity === 0) {
                        WC()->cart->remove_cart_item($cart_item_key);
                        $message = 'Item removed from cart.';
                    } else {
                        WC()->cart->set_quantity($cart_item_key, $quantity, true);
                        $message = 'Cart updated.';
                    }
                }
                break;

            case 'remove_item':
                $cart_item_key = isset($_POST['cart_item_key']) ? sanitize_text_field($_POST['cart_item_key']) : '';
                if ($cart_item_key) {
                    WC()->cart->remove_cart_item($cart_item_key);
                    $message = 'Item removed.';
                }
                break;

            case 'apply_coupon':
                $coupon_code = isset($_POST['coupon_code']) ? sanitize_text_field($_POST['coupon_code']) : '';
                if ($coupon_code) {
                    if (! WC()->cart->has_discount($coupon_code)) {
                        $result = WC()->cart->add_discount($coupon_code);
                        if ($result) {
                            $message = 'Coupon applied successfully.';
                        } else {
                            // Get the first error message if any
                            $errors = wc_get_notices('error');
                            $message = !empty($errors) ? strip_tags($errors[0]['notice']) : 'Invalid coupon code.';
                            wc_clear_notices(); // Clear them so they don't stack
                            $success = false;
                        }
                    } else {
                        $message = 'Coupon already applied.';
                        $success = false;
                    }
                }
                break;

            case 'remove_coupon':
                $coupon_code = isset($_POST['coupon_code']) ? sanitize_text_field($_POST['coupon_code']) : '';
                if ($coupon_code) {
                    WC()->cart->remove_coupon($coupon_code);
                    $message = 'Coupon removed.';
                }
                break;

            default:
                wp_send_json_error(array('message' => 'Invalid action'));
                break;
        }

        // Recalculate totals
        WC()->cart->calculate_totals();
    } catch (Exception $e) {
        wp_send_json_error(array('message' => $e->getMessage()));
    }

    // Render Fragments
    ob_start();
    get_template_part('template-parts/cart/cart-items');
    $cart_items_html = ob_get_clean();

    ob_start();
    get_template_part('template-parts/cart/cart-sidebar');
    $cart_sidebar_html = ob_get_clean();

    $response = array(
        'success'   => $success,
        'message'   => $message,
        'fragments' => array(
            '.cart-items-section' => $cart_items_html,
            '.cart-sidebar'       => $cart_sidebar_html,
            '.cart-subtitle'      => WC()->cart->get_cart_contents_count() . ' item(s) in your cart'
        ),
        'cart_count' => WC()->cart->get_cart_contents_count()
    );

    wp_send_json_success($response);
}
add_action('wp_ajax_melt_cart_action', 'melt_ajax_cart_action');
add_action('wp_ajax_nopriv_melt_cart_action', 'melt_ajax_cart_action');

/**
 * Create Stripe PaymentIntent for Custom Checkout
 * 
 * Creates a WooCommerce order in pending status and a Stripe PaymentIntent
 */
function melt_build_checkout_order($billing_data, $shipping_data, $email, $name, $phone, $order_notes, $delivery_date = '')
{
    // Create WooCommerce order
    $order = wc_create_order();
    $user_id = get_current_user_id();
    if ($user_id) {
        $order->set_customer_id($user_id);
    }

    // Add cart items to order
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $product = $cart_item['data'];
        $quantity = $cart_item['quantity'];

        $item_id = $order->add_product($product, $quantity);

        // Add customization meta if exists
        if (isset($cart_item['melt_customization'])) {
            $customs = $cart_item['melt_customization'];
            foreach ($customs as $key => $value) {
                if (! empty($value)) {
                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }
                    $label = ucfirst(preg_replace('/(?<!^)[A-Z]/', ' $0', $key));
                    wc_add_order_item_meta($item_id, $label, $value);
                }
            }
        }
    }

    // Set billing address
    if (! empty($billing_data)) {
        $order->set_billing_first_name(sanitize_text_field($billing_data['first_name'] ?? ''));
        $order->set_billing_last_name(sanitize_text_field($billing_data['last_name'] ?? ''));
        $order->set_billing_email(sanitize_email($billing_data['email'] ?? $email));
        $order->set_billing_phone(sanitize_text_field($billing_data['phone'] ?? $phone));
        $order->set_billing_address_1(sanitize_text_field($billing_data['address_1'] ?? ''));
        $order->set_billing_address_2(sanitize_text_field($billing_data['address_2'] ?? ''));
        $order->set_billing_city(sanitize_text_field($billing_data['city'] ?? ''));
        $order->set_billing_state(sanitize_text_field($billing_data['state'] ?? ''));
        $order->set_billing_postcode(sanitize_text_field($billing_data['postcode'] ?? ''));
        $order->set_billing_country(sanitize_text_field($billing_data['country'] ?? ''));
    } else {
        // For express checkout, set basic info
        $name_parts = explode(' ', $name, 2);
        $order->set_billing_first_name($name_parts[0] ?? '');
        $order->set_billing_last_name($name_parts[1] ?? '');
        $order->set_billing_email($email);
        $order->set_billing_phone($phone);
    }

    if (empty($shipping_data)) {
        $customer = WC()->customer;
        $shipping_data = array(
            'first_name' => $customer ? $customer->get_shipping_first_name() : '',
            'last_name'  => $customer ? $customer->get_shipping_last_name() : '',
            'address_1'  => $customer ? $customer->get_shipping_address_1() : '',
            'address_2'  => $customer ? $customer->get_shipping_address_2() : '',
            'city'       => $customer ? $customer->get_shipping_city() : '',
            'state'      => $customer ? $customer->get_shipping_state() : '',
            'postcode'   => $customer ? $customer->get_shipping_postcode() : '',
            'country'    => $customer ? $customer->get_shipping_country() : '',
        );
        $has_saved_shipping = implode('', $shipping_data) !== '';
        if (! $has_saved_shipping && ! empty($billing_data)) {
            $shipping_data = array(
                'first_name' => $billing_data['first_name'] ?? '',
                'last_name'  => $billing_data['last_name'] ?? '',
                'address_1'  => $billing_data['address_1'] ?? '',
                'address_2'  => $billing_data['address_2'] ?? '',
                'city'       => $billing_data['city'] ?? '',
                'state'      => $billing_data['state'] ?? '',
                'postcode'   => $billing_data['postcode'] ?? '',
                'country'    => $billing_data['country'] ?? '',
            );
        }
    }

    if (! empty($shipping_data)) {
        $order->set_shipping_first_name(sanitize_text_field($shipping_data['first_name'] ?? ''));
        $order->set_shipping_last_name(sanitize_text_field($shipping_data['last_name'] ?? ''));
        $order->set_shipping_address_1(sanitize_text_field($shipping_data['address_1'] ?? ''));
        $order->set_shipping_address_2(sanitize_text_field($shipping_data['address_2'] ?? ''));
        $order->set_shipping_city(sanitize_text_field($shipping_data['city'] ?? ''));
        $order->set_shipping_state(sanitize_text_field($shipping_data['state'] ?? ''));
        $order->set_shipping_postcode(sanitize_text_field($shipping_data['postcode'] ?? ''));
        $order->set_shipping_country(sanitize_text_field($shipping_data['country'] ?? ''));
    }

    // Add customer note
    if (! empty($order_notes)) {
        $order->set_customer_note($order_notes);
    }
    if (! empty($delivery_date)) {
        $order->update_meta_data('_delivery_date', sanitize_text_field($delivery_date));
    }

    // Apply coupons
    foreach (WC()->cart->get_applied_coupons() as $coupon_code) {
        $order->apply_coupon($coupon_code);
    }

    return $order;
}

function melt_create_payment_intent()
{
    if (! check_ajax_referer('melt_checkout_nonce', 'nonce', false)) {
        wp_send_json(array('success' => false, 'message' => 'Security check failed. Please refresh and try again.'));
    }

    if (WC()->cart->is_empty()) {
        wp_send_json(array('success' => false, 'message' => 'Your cart is empty.'));
    }

    // Get Stripe settings
    $stripe_settings = get_option('woocommerce_stripe_settings', array());
    $testmode = isset($stripe_settings['testmode']) && $stripe_settings['testmode'] === 'yes';
    $secret_key = $testmode
        ? (isset($stripe_settings['test_secret_key']) ? $stripe_settings['test_secret_key'] : '')
        : (isset($stripe_settings['secret_key']) ? $stripe_settings['secret_key'] : '');

    if (empty($secret_key)) {
        wp_send_json(array('success' => false, 'message' => 'Payment gateway not configured.'));
    }

    try {
        // Get billing data
        $billing_data = isset($_POST['billing_data']) ? json_decode(stripslashes($_POST['billing_data']), true) : array();
        $shipping_data = isset($_POST['shipping_data']) ? json_decode(stripslashes($_POST['shipping_data']), true) : array();
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $order_notes = isset($_POST['order_notes']) ? sanitize_textarea_field($_POST['order_notes']) : '';

        // Calculate totals
        WC()->cart->calculate_totals();
        $total = WC()->cart->get_total('edit');
        $currency = strtolower(get_woocommerce_currency());

        $order = melt_build_checkout_order($billing_data, $shipping_data, $email, $name, $phone, $order_notes, $delivery_date);

        // Set payment method
        $order->set_payment_method('stripe');
        $order->set_payment_method_title('Credit Card (Stripe)');

        // Calculate totals
        $order->calculate_totals();

        // Set status to pending
        $order->set_status('pending');
        $order->save();

        $order_total = $order->get_total();
        $amount_cents = round($order_total * 100);

        // Create Stripe PaymentIntent
        $response = wp_remote_post('https://api.stripe.com/v1/payment_intents', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $secret_key,
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ),
            'body' => array(
                'amount'               => $amount_cents,
                'currency'             => $currency,
                'automatic_payment_methods[enabled]' => 'true',
                'metadata[order_id]'   => $order->get_id(),
                'metadata[site]'       => get_bloginfo('name'),
                'description'          => 'Order #' . $order->get_id() . ' from ' . get_bloginfo('name'),
            ),
        ));

        if (is_wp_error($response)) {
            $order->update_status('failed', 'Stripe API error: ' . $response->get_error_message());
            wp_send_json(array('success' => false, 'message' => 'Payment service unavailable. Please try again.'));
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($body['error'])) {
            $order->update_status('failed', 'Stripe error: ' . $body['error']['message']);
            wp_send_json(array('success' => false, 'message' => $body['error']['message']));
        }

        // Store PaymentIntent ID in order meta
        $order->update_meta_data('_stripe_payment_intent', $body['id']);
        $order->save();

        wp_send_json(array(
            'success'       => true,
            'client_secret' => $body['client_secret'],
            'order_id'      => $order->get_id(),
        ));
    } catch (Exception $e) {
        melt_log_error('Checkout', 'PaymentIntent creation failed', array('error' => $e->getMessage()));
        wp_send_json(array('success' => false, 'message' => 'An error occurred. Please try again.'));
    }
}
add_action('wp_ajax_melt_create_payment_intent', 'melt_create_payment_intent');
add_action('wp_ajax_nopriv_melt_create_payment_intent', 'melt_create_payment_intent');

/**
 * Show delivery date on admin order screen.
 */
function melt_admin_order_delivery_date($order)
{
    $delivery_date = $order->get_meta('_delivery_date');
    if (! empty($delivery_date)) {
        echo '<p><strong>' . esc_html__('Delivery date:', 'melt-custom') . '</strong> ' . esc_html($delivery_date) . '</p>';
    }
}
add_action('woocommerce_admin_order_data_after_billing_address', 'melt_admin_order_delivery_date');

/**
 * Ensure product images show in WooCommerce order emails.
 */
function melt_enable_email_product_images($args)
{
    $args['show_image'] = true;
    $args['image_size'] = array(100, 100);
    return $args;
}
add_filter('woocommerce_email_order_items_args', 'melt_enable_email_product_images', 10, 1);

/**
 * Create COD Order for Custom Checkout
 */
function melt_create_cod_order()
{
    if (! check_ajax_referer('melt_checkout_nonce', 'nonce', false)) {
        wp_send_json(array('success' => false, 'message' => 'Security check failed. Please refresh and try again.'));
    }

    if (WC()->cart->is_empty()) {
        wp_send_json(array('success' => false, 'message' => 'Your cart is empty.'));
    }

    $available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
    if (! isset($available_gateways['cod'])) {
        wp_send_json(array('success' => false, 'message' => 'Cash on delivery is not available.'));
    }

    try {
        $billing_data = isset($_POST['billing_data']) ? json_decode(stripslashes($_POST['billing_data']), true) : array();
        $shipping_data = isset($_POST['shipping_data']) ? json_decode(stripslashes($_POST['shipping_data']), true) : array();
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $order_notes = isset($_POST['order_notes']) ? sanitize_textarea_field($_POST['order_notes']) : '';
        $delivery_date = isset($_POST['delivery_date']) ? sanitize_text_field($_POST['delivery_date']) : '';

        if (empty($delivery_date)) {
            wp_send_json(array('success' => false, 'message' => 'Please select a delivery date.'));
        }
        $delivery_date = isset($_POST['delivery_date']) ? sanitize_text_field($_POST['delivery_date']) : '';

        if (empty($delivery_date)) {
            wp_send_json(array('success' => false, 'message' => 'Please select a delivery date.'));
        }
        $delivery_date = isset($_POST['delivery_date']) ? sanitize_text_field($_POST['delivery_date']) : '';

        if (empty($delivery_date)) {
            wp_send_json(array('success' => false, 'message' => 'Please select a delivery date.'));
        }

        $order = melt_build_checkout_order($billing_data, $shipping_data, $email, $name, $phone, $order_notes, $delivery_date);

        $order->set_payment_method('cod');
        $order->set_payment_method_title($available_gateways['cod']->get_title());

        $order->calculate_totals();
        $order->set_status('on-hold', 'Awaiting cash on delivery.');
        $order->save();

        WC()->cart->empty_cart();

        wp_send_json(array(
            'success'      => true,
            'redirect_url' => $order->get_checkout_order_received_url(),
        ));
    } catch (Exception $e) {
        melt_log_error('Checkout', 'COD order creation failed', array('error' => $e->getMessage()));
        wp_send_json(array('success' => false, 'message' => 'An error occurred. Please try again.'));
    }
}
add_action('wp_ajax_melt_create_cod_order', 'melt_create_cod_order');
add_action('wp_ajax_nopriv_melt_create_cod_order', 'melt_create_cod_order');

/**
 * Finalize Order After Successful Payment
 * 
 * Marks order as processing/completed and clears cart
 */
function melt_finalize_order()
{
    if (! check_ajax_referer('melt_checkout_nonce', 'nonce', false)) {
        wp_send_json(array('success' => false, 'message' => 'Security check failed.'));
    }

    $order_id = isset($_POST['order_id']) ? absint($_POST['order_id']) : 0;
    $payment_intent_id = isset($_POST['payment_intent_id']) ? sanitize_text_field($_POST['payment_intent_id']) : '';

    if (! $order_id) {
        wp_send_json(array('success' => false, 'message' => 'Invalid order.'));
    }

    $order = wc_get_order($order_id);

    if (! $order) {
        wp_send_json(array('success' => false, 'message' => 'Order not found.'));
    }

    // Verify payment intent matches (security check)
    $stored_intent = $order->get_meta('_stripe_payment_intent');
    if ($stored_intent !== $payment_intent_id) {
        wp_send_json(array('success' => false, 'message' => 'Payment verification failed.'));
    }

    // Update order status
    $order->update_status('processing', 'Payment completed via Stripe.');
    $order->update_meta_data('_stripe_charge_captured', 'yes');
    $order->payment_complete($payment_intent_id);
    $order->save();

    // Clear cart
    WC()->cart->empty_cart();

    // Get redirect URL
    $redirect_url = $order->get_checkout_order_received_url();

    wp_send_json(array(
        'success'      => true,
        'redirect_url' => $redirect_url,
    ));
}
add_action('wp_ajax_melt_finalize_order', 'melt_finalize_order');
add_action('wp_ajax_nopriv_melt_finalize_order', 'melt_finalize_order');

/**
 * Custom Thank You Page - Works with Block Checkout and Classic Shortcode
 * 
 * Hooks into woocommerce_thankyou to inject custom content and hides default via CSS
 */

// Add CSS to hide default WooCommerce thank you content on order-received page
add_action('wp_head', 'melt_thankyou_hide_default_css');

function melt_thankyou_hide_default_css()
{
    // Debug: Check if we're on order-received
    echo '<!-- MELT DEBUG: wp_head fired, is_wc_endpoint_url(order-received) = ' . (is_wc_endpoint_url('order-received') ? 'TRUE' : 'FALSE') . ' -->';

    if (is_wc_endpoint_url('order-received')) {
?>
        <style>
            /* Hide default WooCommerce thank you content */
            .woocommerce-order>.woocommerce-thankyou-order-received,
            .woocommerce-order>.woocommerce-order-overview,
            .woocommerce-order>.woocommerce-order-details,
            .woocommerce-order>section.woocommerce-customer-details {
                display: none !important;
            }
        </style>
    <?php
    }
}

// Hook into woocommerce_thankyou to add custom content
add_action('woocommerce_thankyou', 'melt_custom_thankyou_content', 5);

function melt_custom_thankyou_content($order_id)
{
    if (!$order_id) {
        return;
    }

    $order = wc_get_order($order_id);
    if (!$order) {
        return;
    }
    ?>
    <!-- MELT CUSTOM THANKYOU -->
    <div class="thankyou-success-container">
        <div class="thankyou-header">
            <div class="thankyou-icon success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <h2 class="thankyou-title"><?php esc_html_e('Thank you for your order!', 'melt-custom'); ?></h2>

            <p class="thankyou-subtitle">We have sent a confirmation email to <?php echo esc_html($order->get_billing_email()); ?></p>
        </div>

        <div class="thankyou-order-details-grid">
            <div class="order-overview-card">
                <ul class="order-overview-list">
                    <li class="overview-item">
                        <span class="label"><?php esc_html_e('Order number:', 'woocommerce'); ?></span>
                        <span class="value">#<?php echo esc_html($order->get_order_number()); ?></span>
                    </li>
                    <li class="overview-item">
                        <span class="label"><?php esc_html_e('Date:', 'woocommerce'); ?></span>
                        <span class="value"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></span>
                    </li>
                    <?php $delivery_date = $order->get_meta('_delivery_date'); ?>
                    <?php if (! empty($delivery_date)) : ?>
                        <li class="overview-item">
                            <span class="label"><?php esc_html_e('Delivery date:', 'melt-custom'); ?></span>
                            <span class="value"><?php echo esc_html($delivery_date); ?></span>
                        </li>
                    <?php endif; ?>
                    <li class="overview-item">
                        <span class="label"><?php esc_html_e('Total:', 'woocommerce'); ?></span>
                        <span class="value"><?php echo wp_kses_post($order->get_formatted_order_total()); ?></span>
                    </li>
                    <?php if ($order->get_payment_method_title()) : ?>
                        <li class="overview-item">
                            <span class="label"><?php esc_html_e('Payment method:', 'woocommerce'); ?></span>
                            <span class="value"><?php echo wp_kses_post($order->get_payment_method_title()); ?></span>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="order-actions-card">
                <h3>What's Next?</h3>
                <p>Your order is being processed by our artisans. You will receive another email when it is ready for shipping or pickup.</p>
                <a href="<?php echo esc_url(home_url()); ?>" class="continue-shopping-btn">Continue Shopping</a>
            </div>
        </div>

        <div class="thankyou-order-complete-details">
            <h2><?php esc_html_e('Order details', 'woocommerce'); ?></h2>

            <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
                <thead>
                    <tr>
                        <th class="woocommerce-table__product-name product-name"><?php esc_html_e('Product', 'woocommerce'); ?></th>
                        <th class="woocommerce-table__product-table product-total"><?php esc_html_e('Total', 'woocommerce'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order->get_items() as $item_id => $item) :
                        $product = $item->get_product();
                    ?>
                        <tr class="woocommerce-table__line-item order_item">
                            <td class="woocommerce-table__product-name product-name">
                                <?php
                                echo wp_kses_post($item->get_name());
                                echo ' <strong class="product-quantity">&times;&nbsp;' . esc_html($item->get_quantity()) . '</strong>';
                                ?>
                            </td>
                            <td class="woocommerce-table__product-total product-total">
                                <?php echo wp_kses_post($order->get_formatted_line_subtotal($item)); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="row"><?php esc_html_e('Subtotal:', 'woocommerce'); ?></th>
                        <td><?php echo wp_kses_post($order->get_subtotal_to_display()); ?></td>
                    </tr>
                    <?php if ($order->get_total_discount() > 0) : ?>
                        <tr>
                            <th scope="row"><?php esc_html_e('Discount:', 'woocommerce'); ?></th>
                            <td>-<?php echo wp_kses_post(wc_price($order->get_total_discount())); ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <th scope="row"><?php esc_html_e('Total:', 'woocommerce'); ?></th>
                        <td><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
<?php
}
