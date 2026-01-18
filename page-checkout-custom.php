<?php

/**
 * Template Name: Checkout Page (Custom)
 * 
 * Custom checkout page template with Stripe, Google Pay, and Apple Pay
 *
 * @package Melt_Custom
 */

// Redirect to cart if cart is empty
if (WC()->cart->is_empty()) {
    wp_redirect(wc_get_cart_url());
    exit;
}

// Check if user is logged in - if not, we'll show login prompt
$require_login = ! is_user_logged_in();

// Ensure user session is active
if (! WC()->session->has_session()) {
    WC()->session->set_customer_session_cookie(true);
}

get_header();


// Get Stripe settings
$stripe_settings = get_option('woocommerce_stripe_settings', array());
$stripe_publishable_key = isset($stripe_settings['publishable_key']) ? $stripe_settings['publishable_key'] : '';
$stripe_testmode = isset($stripe_settings['testmode']) && $stripe_settings['testmode'] === 'yes';

if ($stripe_testmode) {
    $stripe_publishable_key = isset($stripe_settings['test_publishable_key']) ? $stripe_settings['test_publishable_key'] : '';
}

// Calculate cart totals
WC()->cart->calculate_totals();
$cart_total = WC()->cart->get_total('edit');
$currency = get_woocommerce_currency();
?>

<style>
    /* ==========================================================================
   CHECKOUT PAGE STYLES
   ========================================================================== */

    .checkout-page-wrapper {
        padding-top: 6rem;
        background-color: white;
        min-height: 100vh;
        padding-bottom: 5rem;
    }

    .checkout-header {
        padding: 2rem 1.5rem;
        text-align: center;
        max-width: 1280px;
        margin: 0 auto;
    }

    .checkout-title {
        font-family: var(--font-serif);
        font-size: clamp(2rem, 5vw, 2.5rem);
        margin-bottom: 0.5rem;
        font-weight: 500;
        letter-spacing: -0.025em;
        color: var(--foreground);
    }

    .checkout-subtitle {
        font-family: var(--font-body);
        font-weight: 300;
        font-size: 0.95rem;
        color: var(--muted-foreground);
    }

    .checkout-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1.5rem;
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 2.5rem;
        align-items: start;
    }

    @media (max-width: 1024px) {
        .checkout-container {
            grid-template-columns: 1fr;
        }
    }

    /* Express Checkout Section */
    .express-checkout-section {
        background: linear-gradient(135deg, rgba(184, 134, 11, 0.03) 0%, rgba(218, 165, 32, 0.03) 100%);
        border: 1px solid rgba(184, 134, 11, 0.15);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .express-checkout-title {
        font-family: var(--font-body);
        font-size: 0.85rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--muted-foreground);
        margin-bottom: 1rem;
        text-align: center;
    }

    .express-checkout-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    #payment-request-button {
        min-height: 48px;
        width: 100%;
        max-width: 400px;
    }

    .express-checkout-divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 1.5rem;
        font-family: var(--font-body);
        font-size: 0.85rem;
        color: var(--muted-foreground);
    }

    .express-checkout-divider::before,
    .express-checkout-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    /* Main Checkout Form */
    .checkout-main {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .checkout-section {
        background: white;
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 16px;
        padding: 2rem;
        transition: all 0.3s ease;
    }

    .checkout-section:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.04);
    }

    .checkout-section-title {
        font-family: var(--font-serif);
        font-size: 1.35rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
        color: var(--foreground);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .checkout-section-number {
        width: 2rem;
        height: 2rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--font-body);
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* Form Styles */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .form-row.full-width {
        grid-template-columns: 1fr;
    }

    @media (max-width: 600px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group label {
        font-family: var(--font-body);
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--foreground);
    }

    .form-group label .required {
        color: #dc2626;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 0.875rem 1rem;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        font-family: var(--font-body);
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background: white;
        color: var(--foreground);
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.1);
    }

    .form-group input.error,
    .form-group select.error {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .form-group .field-error {
        font-size: 0.8rem;
        color: #dc2626;
        display: none;
    }

    .form-group.has-error .field-error {
        display: block;
    }

    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }

    /* Shipping Toggle */
    .shipping-toggle {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: var(--secondary);
        border-radius: 10px;
        margin-bottom: 1rem;
        cursor: pointer;
    }

    .shipping-toggle input[type="checkbox"] {
        width: 1.25rem;
        height: 1.25rem;
        accent-color: var(--primary);
    }

    .shipping-toggle label {
        font-family: var(--font-body);
        font-size: 0.95rem;
        cursor: pointer;
    }

    .shipping-fields {
        display: none;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border);
    }

    .shipping-fields.visible {
        display: block;
    }

    /* Payment Section */
    .payment-methods {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .payment-method-option {
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        padding: 1.25rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .payment-method-option:hover {
        border-color: var(--primary);
    }

    .payment-method-option.selected {
        border-color: var(--primary);
        background: rgba(184, 134, 11, 0.03);
    }

    .payment-method-option input[type="radio"] {
        width: 1.25rem;
        height: 1.25rem;
        accent-color: var(--primary);
    }

    .payment-method-label {
        flex: 1;
    }

    .payment-method-name {
        font-family: var(--font-body);
        font-weight: 500;
        font-size: 1rem;
        color: var(--foreground);
        margin-bottom: 0.25rem;
    }

    .payment-method-desc {
        font-family: var(--font-body);
        font-size: 0.85rem;
        color: var(--muted-foreground);
    }

    .payment-method-icons {
        display: flex;
        gap: 0.5rem;
    }

    .payment-method-icons img {
        height: 24px;
        width: auto;
    }

    /* Stripe Card Element */
    .stripe-card-element {
        padding: 1rem;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        background: white;
        margin-top: 1rem;
        transition: all 0.2s ease;
    }

    .stripe-card-element.StripeElement--focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.1);
    }

    .stripe-card-element.StripeElement--invalid {
        border-color: #dc2626;
    }

    #card-errors {
        color: #dc2626;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        min-height: 1.25rem;
    }

    /* Terms & Place Order */
    .terms-checkbox {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .terms-checkbox input[type="checkbox"] {
        width: 1.25rem;
        height: 1.25rem;
        accent-color: var(--primary);
        margin-top: 0.125rem;
    }

    .terms-checkbox label {
        font-family: var(--font-body);
        font-size: 0.9rem;
        color: var(--muted-foreground);
        line-height: 1.5;
    }

    .terms-checkbox label a {
        color: var(--primary);
        text-decoration: underline;
    }

    .place-order-btn {
        width: 100%;
        padding: 1.25rem 2rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-family: var(--font-body);
        font-weight: 600;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 25px -5px rgba(184, 134, 11, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .place-order-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 20px 35px -5px rgba(184, 134, 11, 0.5);
    }

    .place-order-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .place-order-btn .spinner {
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        display: none;
    }

    .place-order-btn.loading .spinner {
        display: block;
    }

    .place-order-btn.loading .btn-text {
        display: none;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Checkout Sidebar */
    .checkout-sidebar {
        position: sticky;
        top: 6rem;
        background: white;
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 16px;
        overflow: hidden;
    }

    .checkout-sidebar-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, rgba(184, 134, 11, 0.05) 0%, rgba(218, 165, 32, 0.05) 100%);
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    }

    .checkout-sidebar-title {
        font-family: var(--font-serif);
        font-size: 1.25rem;
        font-weight: 500;
        color: var(--foreground);
        margin: 0;
    }

    .checkout-sidebar-content {
        padding: 1.5rem;
    }

    /* Order Items */
    .order-items {
        margin-bottom: 1.5rem;
        max-height: 300px;
        overflow-y: auto;
    }

    .order-item {
        display: grid;
        grid-template-columns: 60px 1fr auto;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        align-items: center;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .order-item-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        background: var(--secondary);
    }

    .order-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .order-item-details {
        min-width: 0;
    }

    .order-item-name {
        font-family: var(--font-body);
        font-weight: 500;
        font-size: 0.9rem;
        color: var(--foreground);
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .order-item-meta {
        font-size: 0.8rem;
        color: var(--muted-foreground);
    }

    .order-item-price {
        font-family: var(--font-body);
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--primary);
        text-align: right;
    }

    /* Coupon Section */
    .coupon-section {
        padding: 1rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        margin-bottom: 1rem;
    }

    .coupon-input-row {
        display: flex;
        gap: 0.5rem;
    }

    .coupon-section input {
        flex: 1;
        padding: 0.75rem 1rem;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        font-family: var(--font-body);
        font-size: 0.9rem;
    }

    .coupon-section input:focus {
        outline: none;
        border-color: var(--primary);
    }

    .apply-coupon-btn {
        padding: 0.75rem 1.25rem;
        background: transparent;
        border: 1px solid var(--primary);
        color: var(--primary);
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        font-family: var(--font-body);
        white-space: nowrap;
    }

    .apply-coupon-btn:hover {
        background: var(--primary);
        color: white;
    }

    .applied-coupons {
        margin-top: 0.75rem;
    }

    .applied-coupon {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 0.75rem;
        background: rgba(34, 197, 94, 0.1);
        border-radius: 6px;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    .applied-coupon .coupon-name {
        color: #16a34a;
        font-weight: 500;
    }

    .applied-coupon .remove-coupon {
        color: #dc2626;
        cursor: pointer;
        font-size: 0.8rem;
    }

    /* Order Totals */
    .order-totals {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .order-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-family: var(--font-body);
    }

    .order-total-label {
        font-size: 0.9rem;
        color: var(--muted-foreground);
    }

    .order-total-value {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--foreground);
    }

    .order-total-row.discount .order-total-value {
        color: #16a34a;
    }

    .order-total-row.grand-total {
        padding-top: 1rem;
        border-top: 2px solid var(--primary);
        margin-top: 0.5rem;
    }

    .order-total-row.grand-total .order-total-label {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--foreground);
    }

    .order-total-row.grand-total .order-total-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
    }

    /* Secure Badge */
    .secure-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem;
        background: var(--secondary);
        font-size: 0.85rem;
        color: var(--muted-foreground);
    }

    .secure-badge svg {
        width: 1rem;
        height: 1rem;
        color: #16a34a;
    }

    /* Back to Cart Link */
    .back-to-cart {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-family: var(--font-body);
        font-size: 0.9rem;
        color: var(--muted-foreground);
        margin-bottom: 1.5rem;
        transition: color 0.2s ease;
    }

    .back-to-cart:hover {
        color: var(--primary);
    }

    /* Checkout Message/Error Display */
    #checkout-message {
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        display: none;
        font-family: var(--font-body);
        font-size: 0.9rem;
    }

    #checkout-message.error {
        background: rgba(220, 38, 38, 0.1);
        border: 1px solid rgba(220, 38, 38, 0.2);
        color: #dc2626;
        display: block;
    }

    #checkout-message.success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.2);
        color: #16a34a;
        display: block;
    }

    /* Mobile Adjustments */
    @media (max-width: 1024px) {
        .checkout-sidebar {
            position: relative;
            top: 0;
            order: -1;
        }
    }

    @media (max-width: 600px) {
        .checkout-section {
            padding: 1.25rem;
        }

        .checkout-sidebar-content {
            padding: 1rem;
        }

        .order-item {
            grid-template-columns: 50px 1fr auto;
            gap: 0.75rem;
        }

        .order-item-image {
            width: 50px;
            height: 50px;
        }
    }
</style>

<div class="checkout-page-wrapper">
    <!-- Checkout Header -->
    <div class="checkout-header fade-in-section">
        <h1 class="checkout-title">Checkout</h1>
        <p class="checkout-subtitle">Complete your order securely</p>
    </div>

    <?php if ($require_login) : ?>
        <!-- Login Required Section -->
        <style>
            .login-required-section {
                max-width: 600px;
                margin: 0 auto 2rem;
                padding: 0 1.5rem;
            }

            .login-required-box {
                background: linear-gradient(135deg, rgba(184, 134, 11, 0.05) 0%, rgba(218, 165, 32, 0.05) 100%);
                border: 2px solid var(--primary);
                border-radius: 16px;
                padding: 2.5rem;
                text-align: center;
            }

            .login-required-icon {
                width: 4rem;
                height: 4rem;
                background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem;
            }

            .login-required-icon svg {
                width: 2rem;
                height: 2rem;
                color: white;
            }

            .login-required-title {
                font-family: var(--font-serif);
                font-size: 1.5rem;
                font-weight: 500;
                color: var(--foreground);
                margin-bottom: 0.75rem;
            }

            .login-required-text {
                font-family: var(--font-body);
                font-size: 0.95rem;
                color: var(--muted-foreground);
                margin-bottom: 1.5rem;
                line-height: 1.6;
            }

            .login-required-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 1rem 2rem;
                background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
                color: white;
                border: none;
                border-radius: 12px;
                font-family: var(--font-body);
                font-weight: 600;
                font-size: 1rem;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 10px 25px -5px rgba(184, 134, 11, 0.4);
            }

            .login-required-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 15px 30px -5px rgba(184, 134, 11, 0.5);
            }

            .login-required-note {
                margin-top: 1rem;
                font-size: 0.85rem;
                color: var(--muted-foreground);
            }

            .login-required-note a {
                color: var(--primary);
                text-decoration: underline;
            }
        </style>

        <div class="login-required-section fade-in-section">
            <div class="login-required-box">
                <div class="login-required-icon">
                    <i data-lucide="user-circle" style="width: 2rem; height: 2rem; color: white;"></i>
                </div>
                <h2 class="login-required-title">Login Required</h2>
                <p class="login-required-text">
                    Please login or create an account to complete your purchase.
                    This helps us track your order and provide you with updates.
                </p>
                <button type="button" class="login-required-btn" onclick="openAuthModal()">
                    <i data-lucide="log-in" style="width: 1.1rem; height: 1.1rem;"></i>
                    Login / Register
                </button>
                <p class="login-required-note">
                    Already have an account? <a href="javascript:void(0)" onclick="openAuthModal()">Click here to login</a>
                </p>
            </div>
        </div>

        <script>
            // Auto-open auth modal if not logged in
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Lucide icons for login box
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        </script>
    <?php else : ?>
        <div class="checkout-container">
            <!-- Main Checkout Form -->
            <div class="checkout-main">
                <!-- Back to Cart -->
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="back-to-cart">
                    <i data-lucide="arrow-left" style="width: 1rem; height: 1rem;"></i>
                    Back to Cart
                </a>

                <!-- Express Checkout -->
                <div class="express-checkout-section fade-in-section" id="express-checkout-container" style="display: none;">
                    <div class="express-checkout-title">Express Checkout</div>
                    <div class="express-checkout-buttons">
                        <div id="payment-request-button">
                            <!-- Stripe Payment Request Button will be mounted here -->
                        </div>
                    </div>
                    <div class="express-checkout-divider">
                        <span>or continue with details below</span>
                    </div>
                </div>

                <!-- Message Display -->
                <div id="checkout-message"></div>

                <form id="checkout-form" class="checkout-form">
                    <?php wp_nonce_field('melt_checkout_nonce', 'checkout_nonce'); ?>

                    <!-- Billing Details -->
                    <div class="checkout-section fade-in-section">
                        <h2 class="checkout-section-title">
                            <span class="checkout-section-number">1</span>
                            Billing Details
                        </h2>

                        <?php get_template_part('template-parts/checkout/checkout-billing'); ?>
                    </div>

                    <!-- Payment -->
                    <div class="checkout-section fade-in-section">
                        <h2 class="checkout-section-title">
                            <span class="checkout-section-number">2</span>
                            Payment Method
                        </h2>

                        <?php get_template_part('template-parts/checkout/checkout-payment'); ?>
                    </div>
                </form>
            </div>

            <!-- Order Summary Sidebar -->
            <?php get_template_part('template-parts/checkout/checkout-sidebar'); ?>
        </div>
    <?php endif; // End login check 
    ?>
</div>

<?php if ($stripe_publishable_key) : ?>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        /**
         * Melt Custom Checkout - Stripe Integration
         */
        (function() {
            'use strict';

            // Stripe Configuration
            const stripeKey = '<?php echo esc_js($stripe_publishable_key); ?>';
            const ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
            const nonce = '<?php echo wp_create_nonce('melt_checkout_nonce'); ?>';
            const cartTotal = <?php echo (float) $cart_total * 100; ?>; // Amount in cents
            const currency = '<?php echo strtolower($currency); ?>';
            const siteName = '<?php echo esc_js(get_bloginfo('name')); ?>';

            // Initialize Stripe
            const stripe = Stripe(stripeKey);
            const elements = stripe.elements();

            // Stripe Elements styling
            const elementStyle = {
                base: {
                    color: '#1A1A1A',
                    fontFamily: 'Inter, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#666666'
                    }
                },
                invalid: {
                    color: '#dc2626',
                    iconColor: '#dc2626'
                }
            };

            // Create card element
            const cardElement = elements.create('card', {
                style: elementStyle
            });
            cardElement.mount('#card-element');

            // Handle card errors
            cardElement.on('change', function(event) {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            // Payment Request Button (Google Pay / Apple Pay)
            const paymentRequest = stripe.paymentRequest({
                country: '<?php echo esc_js(WC()->countries->get_base_country()); ?>',
                currency: currency,
                total: {
                    label: siteName,
                    amount: cartTotal,
                },
                requestPayerName: true,
                requestPayerEmail: true,
                requestPayerPhone: true,
                requestShipping: <?php echo WC()->cart->needs_shipping() ? 'true' : 'false'; ?>,
            });

            const prButton = elements.create('paymentRequestButton', {
                paymentRequest: paymentRequest,
                style: {
                    paymentRequestButton: {
                        type: 'default',
                        theme: 'dark',
                        height: '48px'
                    }
                }
            });

            // Check if Payment Request is available
            paymentRequest.canMakePayment().then(function(result) {
                if (result) {
                    prButton.mount('#payment-request-button');
                    document.getElementById('express-checkout-container').style.display = 'block';
                }
            });

            // Handle Payment Request payment
            paymentRequest.on('paymentmethod', async function(event) {
                try {
                    // Create order and get client secret
                    const response = await createPaymentIntent(event.payerEmail, event.payerName, event.payerPhone);

                    if (!response.success) {
                        event.complete('fail');
                        showMessage(response.message || 'Payment failed', 'error');
                        return;
                    }

                    // Confirm the payment
                    const {
                        error,
                        paymentIntent
                    } = await stripe.confirmCardPayment(
                        response.client_secret, {
                            payment_method: event.paymentMethod.id
                        }, {
                            handleActions: false
                        }
                    );

                    if (error) {
                        event.complete('fail');
                        showMessage(error.message, 'error');
                    } else if (paymentIntent.status === 'succeeded' || paymentIntent.status === 'requires_capture') {
                        event.complete('success');
                        // Finalize order
                        await finalizeOrder(response.order_id, paymentIntent.id);
                    } else {
                        event.complete('fail');
                        showMessage('Payment was not successful', 'error');
                    }
                } catch (err) {
                    event.complete('fail');
                    showMessage('An error occurred. Please try again.', 'error');
                    console.error(err);
                }
            });

            // Payment method toggle
            const paymentMethodOptions = document.querySelectorAll('input[name="payment_method"]');
            const cardElementWrapper = document.getElementById('card-element');
            const cardErrors = document.getElementById('card-errors');

            function updatePaymentMethodUI() {
                const selected = document.querySelector('input[name="payment_method"]:checked');
                const isStripe = selected && selected.value === 'stripe';

                if (cardElementWrapper) {
                    cardElementWrapper.style.display = isStripe ? 'block' : 'none';
                }
                if (cardErrors) {
                    cardErrors.style.display = isStripe ? 'block' : 'none';
                    if (!isStripe) {
                        cardErrors.textContent = '';
                    }
                }

                paymentMethodOptions.forEach(option => {
                    const container = option.closest('.payment-method-option');
                    if (container) {
                        container.classList.toggle('selected', option.checked);
                    }
                });
            }

            paymentMethodOptions.forEach(option => {
                option.addEventListener('change', updatePaymentMethodUI);
            });

            updatePaymentMethodUI();

            // Form Submission
            const form = document.getElementById('checkout-form');
            const submitBtn = document.getElementById('place-order-btn');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Validate form
                if (!validateForm()) {
                    return;
                }

                // Check terms
                const termsCheckbox = document.getElementById('terms-checkbox');
                if (termsCheckbox && !termsCheckbox.checked) {
                    showMessage('Please accept the terms and conditions to proceed.', 'error');
                    return;
                }

                // Disable button and show loading
                submitBtn.disabled = true;
                submitBtn.classList.add('loading');

                try {
                    // Get form data
                    const formData = new FormData(form);
                    const billingData = {
                        first_name: formData.get('billing_first_name'),
                        last_name: formData.get('billing_last_name'),
                        email: formData.get('billing_email'),
                        phone: formData.get('billing_phone'),
                        address_1: formData.get('billing_address_1'),
                        address_2: formData.get('billing_address_2') || '',
                        city: formData.get('billing_city'),
                        state: formData.get('billing_state'),
                        postcode: formData.get('billing_postcode'),
                        country: formData.get('billing_country')
                    };

                    const selectedPayment = formData.get('payment_method') || 'stripe';
                    const shipToDifferent = formData.get('ship_to_different_address') === 'on';
                    const shippingData = shipToDifferent ? {
                        first_name: formData.get('shipping_first_name'),
                        last_name: formData.get('shipping_last_name'),
                        address_1: formData.get('shipping_address_1'),
                        address_2: formData.get('shipping_address_2') || '',
                        city: formData.get('shipping_city'),
                        state: formData.get('shipping_state'),
                        postcode: formData.get('shipping_postcode'),
                        country: formData.get('shipping_country')
                    } : null;

                    if (selectedPayment === 'cod') {
                        const response = await createCodOrder(
                            billingData.email,
                            billingData.first_name + ' ' + billingData.last_name,
                            billingData.phone,
                            billingData,
                            shippingData
                        );
                        if (!response.success) {
                            showMessage(response.message || 'Failed to place order', 'error');
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('loading');
                            return;
                        }

                        if (response.redirect_url) {
                            window.location.href = response.redirect_url;
                        } else {
                            showMessage('Order placed but redirect failed', 'error');
                        }
                        return;
                    }

                    // Create payment intent
                    const response = await createPaymentIntent(
                        billingData.email,
                        billingData.first_name + ' ' + billingData.last_name,
                        billingData.phone,
                        billingData,
                        shippingData
                    );

                    if (!response.success) {
                        showMessage(response.message || 'Failed to process order', 'error');
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('loading');
                        return;
                    }

                    // Confirm card payment
                    const {
                        error,
                        paymentIntent
                    } = await stripe.confirmCardPayment(response.client_secret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: billingData.first_name + ' ' + billingData.last_name,
                                email: billingData.email,
                                phone: billingData.phone,
                                address: {
                                    line1: billingData.address_1,
                                    line2: billingData.address_2,
                                    city: billingData.city,
                                    state: billingData.state,
                                    postal_code: billingData.postcode,
                                    country: billingData.country
                                }
                            }
                        }
                    });

                    if (error) {
                        showMessage(error.message, 'error');
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('loading');
                    } else if (paymentIntent.status === 'succeeded' || paymentIntent.status === 'requires_capture') {
                        // Finalize order
                        await finalizeOrder(response.order_id, paymentIntent.id);
                    } else {
                        showMessage('Payment requires additional action', 'error');
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('loading');
                    }

                } catch (err) {
                    console.error('Checkout error:', err);
                    showMessage('An error occurred. Please try again.', 'error');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('loading');
                }
            });

            // Create Payment Intent via AJAX
            async function createPaymentIntent(email, name, phone, billingData = null, shippingData = null) {
                const formData = new FormData();
                formData.append('action', 'melt_create_payment_intent');
                formData.append('nonce', nonce);
                formData.append('email', email || '');
                formData.append('name', name || '');
                formData.append('phone', phone || '');

                if (billingData) {
                    formData.append('billing_data', JSON.stringify(billingData));
                }
                if (shippingData) {
                    formData.append('shipping_data', JSON.stringify(shippingData));
                }

                // Get order notes if present
                const orderNotes = document.getElementById('order_notes');
                if (orderNotes && orderNotes.value) {
                    formData.append('order_notes', orderNotes.value);
                }

                const response = await fetch(ajaxUrl, {
                    method: 'POST',
                    body: formData
                });

                return await response.json();
            }

            // Create COD Order via AJAX
            async function createCodOrder(email, name, phone, billingData = null, shippingData = null) {
                const formData = new FormData();
                formData.append('action', 'melt_create_cod_order');
                formData.append('nonce', nonce);
                formData.append('email', email || '');
                formData.append('name', name || '');
                formData.append('phone', phone || '');

                if (billingData) {
                    formData.append('billing_data', JSON.stringify(billingData));
                }
                if (shippingData) {
                    formData.append('shipping_data', JSON.stringify(shippingData));
                }

                const orderNotes = document.getElementById('order_notes');
                if (orderNotes && orderNotes.value) {
                    formData.append('order_notes', orderNotes.value);
                }

                const response = await fetch(ajaxUrl, {
                    method: 'POST',
                    body: formData
                });

                return await response.json();
            }

            // Finalize Order after successful payment
            async function finalizeOrder(orderId, paymentIntentId) {
                const formData = new FormData();
                formData.append('action', 'melt_finalize_order');
                formData.append('nonce', nonce);
                formData.append('order_id', orderId);
                formData.append('payment_intent_id', paymentIntentId);

                const response = await fetch(ajaxUrl, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success && result.redirect_url) {
                    window.location.href = result.redirect_url;
                } else {
                    showMessage(result.message || 'Order completed but redirect failed', 'error');
                }
            }

            // Form Validation
            function validateForm() {
                let isValid = true;
                const requiredFields = form.querySelectorAll('[required]');

                requiredFields.forEach(field => {
                    const formGroup = field.closest('.form-group');

                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('error');
                        if (formGroup) formGroup.classList.add('has-error');
                    } else {
                        field.classList.remove('error');
                        if (formGroup) formGroup.classList.remove('has-error');
                    }

                    // Email validation
                    if (field.type === 'email' && field.value) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(field.value)) {
                            isValid = false;
                            field.classList.add('error');
                            if (formGroup) formGroup.classList.add('has-error');
                        }
                    }
                });

                if (!isValid) {
                    showMessage('Please fill in all required fields correctly.', 'error');
                    // Scroll to first error
                    const firstError = form.querySelector('.error');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }

                return isValid;
            }

            // Show Message
            function showMessage(message, type) {
                const messageEl = document.getElementById('checkout-message');
                messageEl.textContent = message;
                messageEl.className = type;
                messageEl.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            // Shipping toggle
            const shipDifferentCheckbox = document.getElementById('ship_to_different_address');
            const shippingFields = document.getElementById('shipping-fields');

            if (shipDifferentCheckbox && shippingFields) {
                shipDifferentCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        shippingFields.classList.add('visible');
                    } else {
                        shippingFields.classList.remove('visible');
                    }
                });
            }

            // Coupon functionality
            window.applyCheckoutCoupon = async function() {
                const couponInput = document.getElementById('checkout_coupon_code');
                if (!couponInput || !couponInput.value.trim()) return;

                const formData = new FormData();
                formData.append('action', 'melt_cart_action');
                formData.append('nonce', nonce);
                formData.append('action_type', 'apply_coupon');
                formData.append('coupon_code', couponInput.value.trim());

                const response = await fetch(ajaxUrl, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Reload page to update totals
                    window.location.reload();
                } else {
                    showMessage(result.data.message || 'Failed to apply coupon', 'error');
                }
            };

            window.removeCheckoutCoupon = async function(code) {
                const formData = new FormData();
                formData.append('action', 'melt_cart_action');
                formData.append('nonce', nonce);
                formData.append('action_type', 'remove_coupon');
                formData.append('coupon_code', code);

                await fetch(ajaxUrl, {
                    method: 'POST',
                    body: formData
                });

                window.location.reload();
            };

            // Initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Fade in animations
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.fade-in-section').forEach(el => observer.observe(el));

        })();
    </script>
<?php else : ?>
    <div style="padding: 2rem; text-align: center; color: #dc2626;">
        <p><strong>Error:</strong> Stripe is not configured. Please configure Stripe settings in WooCommerce → Settings → Payments → Stripe.</p>
    </div>
<?php endif; ?>

<?php get_footer(); ?>
