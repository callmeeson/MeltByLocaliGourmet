<?php
/**
 * Cart Sidebar Template Part
 * 
 * Contains Order Summary and Coupon Section
 */
?>
<div class="cart-sidebar">
    <h2 class="cart-sidebar-title">Order Summary</h2>

    <!-- Coupon Code Section -->
    <div class="cart-coupon-section">
        <div class="coupon-input-group">
            <input type="text" name="coupon_code" class="coupon-input" id="coupon_code" value="" placeholder="Coupon code" />
            <button type="button" class="apply-coupon-btn" onclick="applyCoupon()">Apply</button>
        </div>
        <!-- Coupon Error Message (Hidden by default) -->
        <div id="coupon-message" style="display:none; font-size: 0.85rem; margin-top: 0.5rem;"></div>
    </div>
    
    <div class="cart-totals">
        <div class="cart-total-row">
            <span class="cart-total-label">Subtotal</span>
            <span class="cart-total-value"><?php wc_cart_totals_subtotal_html(); ?></span>
        </div>

        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
            <div class="cart-total-row coupon-row">
                <span class="cart-total-label">
                    Coupon: <?php echo esc_html( $code ); ?>
                    <a href="javascript:void(0)" onclick="removeCoupon('<?php echo esc_js( $code ); ?>')" style="color:red; font-size:0.8rem; margin-left:0.5rem;">[Remove]</a>
                </span>
                <span class="cart-total-value">-<?php wc_cart_totals_coupon_html( $coupon ); ?></span>
            </div>
        <?php endforeach; ?>

        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
            <div class="cart-total-row">
                <span class="cart-total-label">Shipping</span>
                <span class="cart-total-value"><?php wc_cart_totals_shipping_html(); ?></span>
            </div>
        <?php endif; ?>

        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <div class="cart-total-row">
                <span class="cart-total-label"><?php echo esc_html( $fee->name ); ?></span>
                <span class="cart-total-value"><?php wc_cart_totals_fee_html( $fee ); ?></span>
            </div>
        <?php endforeach; ?>

        <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
            <div class="cart-total-row">
                <span class="cart-total-label">Tax</span>
                <span class="cart-total-value"><?php wc_cart_totals_taxes_total_html(); ?></span>
            </div>
        <?php endif; ?>

        <div class="cart-total-row grand-total">
            <span class="cart-total-label">Total</span>
            <span class="cart-total-value"><?php wc_cart_totals_order_total_html(); ?></span>
        </div>
    </div>

    <?php
    // Get custom checkout page URL
    $checkout_page = get_page_by_path( 'checkout-custom' );
    $checkout_url = $checkout_page ? get_permalink( $checkout_page ) : wc_get_checkout_url();
    ?>
    <a href="<?php echo esc_url( $checkout_url ); ?>" class="checkout-button">
        Proceed to Checkout
    </a>


    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="continue-shopping">
        ← Continue Shopping
    </a>
</div>
