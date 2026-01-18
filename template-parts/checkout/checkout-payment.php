<?php
/**
 * Checkout Payment Template Part
 * 
 * Contains payment method selection and Stripe card element
 *
 * @package Melt_Custom
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="payment-fields">
    <!-- Card Payment Option -->
    <div class="payment-methods">
        <div class="payment-method-option selected">
            <input type="radio" name="payment_method" value="stripe" id="payment_method_stripe" checked>
            <div class="payment-method-label">
                <div class="payment-method-name">Credit / Debit Card</div>
                <div class="payment-method-desc">Pay securely with your card via Stripe</div>
            </div>
            <div class="payment-method-icons">
                <svg width="40" height="24" viewBox="0 0 40 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="40" height="24" rx="4" fill="#1A1F71"/>
                    <path d="M18.5 15.5L16 8.5H17.5L19 13L20.5 8.5H22L19.5 15.5H18.5Z" fill="white"/>
                    <path d="M14.5 8.5H16V15.5H14.5V8.5Z" fill="white"/>
                    <path d="M13 8.5L11 13L10.5 11H9L10.5 15.5H12L15 8.5H13Z" fill="white"/>
                    <path d="M24 11.5C24 10.5 24.5 10 25.5 10C26 10 26.5 10.2 26.8 10.5L27.3 9.3C26.8 9 26.2 8.8 25.5 8.8C23.8 8.8 22.5 9.8 22.5 11.5C22.5 14 26 13.5 26 14.8C26 15.3 25.5 15.7 24.8 15.7C24.2 15.7 23.5 15.4 23 15L22.5 16.2C23.2 16.7 24 16.9 24.8 16.9C26.5 16.9 27.8 15.9 27.8 14.3C27.8 11.8 24 12.2 24 11.5Z" fill="white"/>
                    <path d="M31.5 15.5L29 8.5H30.5L32 13L33.5 8.5H35L32.5 15.5H31.5Z" fill="white"/>
                </svg>
                <svg width="40" height="24" viewBox="0 0 40 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="40" height="24" rx="4" fill="#EB001B" fill-opacity="0.1"/>
                    <circle cx="15" cy="12" r="7" fill="#EB001B"/>
                    <circle cx="25" cy="12" r="7" fill="#F79E1B"/>
                    <path d="M20 6.8C21.7 8 22.8 9.9 22.8 12C22.8 14.1 21.7 16 20 17.2C18.3 16 17.2 14.1 17.2 12C17.2 9.9 18.3 8 20 6.8Z" fill="#FF5F00"/>
                </svg>
                <svg width="40" height="24" viewBox="0 0 40 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="40" height="24" rx="4" fill="#016FD0"/>
                    <path d="M20 7L17 17H19.5L20 15.5H23L23.5 17H26L23 7H20ZM20.5 13.5L21.5 10L22.5 13.5H20.5Z" fill="white"/>
                    <path d="M12 7V17H14.5V13.5L17 17H20L16.5 12L20 7H17L14.5 10.5V7H12Z" fill="white"/>
                    <path d="M27 7V17H31C33 17 34 15.5 34 14V10C34 8.5 33 7 31 7H27ZM29.5 9H30.5C31.3 9 31.8 9.5 31.8 10.3V13.7C31.8 14.5 31.3 15 30.5 15H29.5V9Z" fill="white"/>
                </svg>
            </div>
        </div>
        <?php
        $available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
        if (isset($available_gateways['cod'])) :
        ?>
            <div class="payment-method-option">
                <input type="radio" name="payment_method" value="cod" id="payment_method_cod">
                <div class="payment-method-label">
                    <div class="payment-method-name">Cash on Delivery</div>
                    <div class="payment-method-desc">Pay with cash when your order arrives</div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Stripe Card Element -->
    <div class="stripe-card-element" id="card-element">
        <!-- Stripe Element will be mounted here -->
    </div>
    <div id="card-errors" role="alert"></div>

    <!-- Terms and Conditions -->
    <div class="terms-checkbox" style="margin-top: 1.5rem;">
        <input type="checkbox" id="terms-checkbox" name="terms" value="1">
        <label for="terms-checkbox">
            I have read and agree to the website 
            <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>" target="_blank">privacy policy</a> 
            and 
            <a href="<?php echo esc_url( wc_get_page_permalink( 'terms' ) ); ?>" target="_blank">terms and conditions</a>.
            <span class="required">*</span>
        </label>
    </div>

    <!-- Place Order Button -->
    <button type="submit" id="place-order-btn" class="place-order-btn">
        <span class="btn-text">
            <i data-lucide="lock" style="width: 1.1rem; height: 1.1rem;"></i>
            Place Order — <?php echo WC()->cart->get_total(); ?>
        </span>
        <span class="spinner"></span>
    </button>

    <!-- Security Note -->
    <p style="text-align: center; font-size: 0.8rem; color: var(--muted-foreground); margin-top: 1rem;">
        <i data-lucide="shield-check" style="width: 0.9rem; height: 0.9rem; display: inline-block; vertical-align: middle; margin-right: 0.25rem;"></i>
        Your payment information is encrypted and secure
    </p>
</div>
