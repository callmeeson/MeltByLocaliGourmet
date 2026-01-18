<?php
/**
 * Checkout Sidebar Template Part
 * 
 * Contains order summary with items, coupons, and totals
 *
 * @package Melt_Custom
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="checkout-sidebar fade-in-section">
    <div class="checkout-sidebar-header">
        <h2 class="checkout-sidebar-title">Order Summary</h2>
    </div>
    
    <div class="checkout-sidebar-content">
        <!-- Order Items -->
        <div class="order-items">
            <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) :
            ?>
                <div class="order-item">
                    <div class="order-item-image">
                        <?php echo $_product->get_image(); ?>
                    </div>
                    <div class="order-item-details">
                        <div class="order-item-name"><?php echo esc_html( $_product->get_name() ); ?></div>
                        <div class="order-item-meta">
                            <?php 
                            // Show quantity
                            echo 'Qty: ' . esc_html( $cart_item['quantity'] );
                            
                            // Show customization summary if exists
                            if ( isset( $cart_item['melt_customization'] ) ) {
                                $customs = $cart_item['melt_customization'];
                                $meta_parts = [];
                                
                                if ( ! empty( $customs['size'] ) ) {
                                    $meta_parts[] = esc_html( $customs['size'] );
                                }
                                if ( ! empty( $customs['flavor'] ) ) {
                                    $meta_parts[] = esc_html( $customs['flavor'] );
                                }
                                
                                if ( ! empty( $meta_parts ) ) {
                                    echo ' · ' . implode( ', ', $meta_parts );
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="order-item-price">
                        <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                    </div>
                </div>
            <?php 
                endif;
            endforeach; 
            ?>
        </div>

        <!-- Coupon Section -->
        <div class="coupon-section">
            <div class="coupon-input-row">
                <input type="text" id="checkout_coupon_code" placeholder="Coupon code" />
                <button type="button" class="apply-coupon-btn" onclick="applyCheckoutCoupon()">Apply</button>
            </div>
            
            <?php if ( WC()->cart->get_coupons() ) : ?>
            <div class="applied-coupons">
                <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                    <div class="applied-coupon">
                        <span class="coupon-name"><?php echo esc_html( strtoupper( $code ) ); ?></span>
                        <span class="remove-coupon" onclick="removeCheckoutCoupon('<?php echo esc_js( $code ); ?>')">Remove</span>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Order Totals -->
        <div class="order-totals">
            <div class="order-total-row">
                <span class="order-total-label">Subtotal</span>
                <span class="order-total-value"><?php wc_cart_totals_subtotal_html(); ?></span>
            </div>

            <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                <div class="order-total-row discount">
                    <span class="order-total-label">Coupon: <?php echo esc_html( strtoupper( $code ) ); ?></span>
                    <span class="order-total-value">-<?php wc_cart_totals_coupon_html( $coupon ); ?></span>
                </div>
            <?php endforeach; ?>

            <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                <div class="order-total-row">
                    <span class="order-total-label">Shipping</span>
                    <span class="order-total-value"><?php wc_cart_totals_shipping_html(); ?></span>
                </div>
            <?php endif; ?>

            <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                <div class="order-total-row">
                    <span class="order-total-label"><?php echo esc_html( $fee->name ); ?></span>
                    <span class="order-total-value"><?php wc_cart_totals_fee_html( $fee ); ?></span>
                </div>
            <?php endforeach; ?>

            <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
                <div class="order-total-row">
                    <span class="order-total-label">Tax</span>
                    <span class="order-total-value"><?php wc_cart_totals_taxes_total_html(); ?></span>
                </div>
            <?php endif; ?>

            <div class="order-total-row grand-total">
                <span class="order-total-label">Total</span>
                <span class="order-total-value"><?php wc_cart_totals_order_total_html(); ?></span>
            </div>
        </div>
    </div>

    <!-- Secure Badge -->
    <div class="secure-badge">
        <i data-lucide="lock" style="width: 1rem; height: 1rem; color: #16a34a;"></i>
        <span>Secured by Stripe</span>
    </div>
</div>
