<?php
/**
 * Checkout Billing Form Template Part
 * 
 * Contains billing address fields for the checkout page
 *
 * @package Melt_Custom
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$countries = WC()->countries->get_countries();
$default_country = WC()->customer->get_billing_country() ?: WC()->countries->get_base_country();
$states = WC()->countries->get_states( $default_country );

// Get any saved customer data
$customer = WC()->customer;
?>

<div class="billing-fields">
    <div class="form-row">
        <div class="form-group">
            <label for="billing_first_name">First Name <span class="required">*</span></label>
            <input type="text" id="billing_first_name" name="billing_first_name" 
                   value="<?php echo esc_attr( $customer->get_billing_first_name() ); ?>" 
                   placeholder="Enter your first name" required>
            <span class="field-error">First name is required</span>
        </div>
        <div class="form-group">
            <label for="billing_last_name">Last Name <span class="required">*</span></label>
            <input type="text" id="billing_last_name" name="billing_last_name" 
                   value="<?php echo esc_attr( $customer->get_billing_last_name() ); ?>" 
                   placeholder="Enter your last name" required>
            <span class="field-error">Last name is required</span>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="billing_email">Email Address <span class="required">*</span></label>
            <input type="email" id="billing_email" name="billing_email" 
                   value="<?php echo esc_attr( $customer->get_billing_email() ); ?>" 
                   placeholder="your@email.com" required>
            <span class="field-error">Valid email is required</span>
        </div>
        <div class="form-group">
            <label for="billing_phone">Phone Number <span class="required">*</span></label>
            <input type="tel" id="billing_phone" name="billing_phone" 
                   value="<?php echo esc_attr( $customer->get_billing_phone() ); ?>" 
                   placeholder="+1 (555) 123-4567" required>
            <span class="field-error">Phone number is required</span>
        </div>
    </div>

    <div class="form-row full-width">
        <div class="form-group">
            <label for="billing_address_1">Street Address <span class="required">*</span></label>
            <input type="text" id="billing_address_1" name="billing_address_1" 
                   value="<?php echo esc_attr( $customer->get_billing_address_1() ); ?>" 
                   placeholder="House number and street name" required>
            <span class="field-error">Address is required</span>
        </div>
    </div>

    <div class="form-row full-width">
        <div class="form-group">
            <label for="billing_address_2">Apartment, suite, unit, etc. (optional)</label>
            <input type="text" id="billing_address_2" name="billing_address_2" 
                   value="<?php echo esc_attr( $customer->get_billing_address_2() ); ?>" 
                   placeholder="Apartment, suite, unit, etc.">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="billing_city">City <span class="required">*</span></label>
            <input type="text" id="billing_city" name="billing_city" 
                   value="<?php echo esc_attr( $customer->get_billing_city() ); ?>" 
                   placeholder="City" required>
            <span class="field-error">City is required</span>
        </div>
        <div class="form-group">
            <label for="billing_state">State / Province <span class="required">*</span></label>
            <?php if ( ! empty( $states ) ) : ?>
                <select id="billing_state" name="billing_state" required>
                    <option value="">Select state...</option>
                    <?php foreach ( $states as $code => $name ) : ?>
                        <option value="<?php echo esc_attr( $code ); ?>" 
                                <?php selected( $customer->get_billing_state(), $code ); ?>>
                            <?php echo esc_html( $name ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php else : ?>
                <input type="text" id="billing_state" name="billing_state" 
                       value="<?php echo esc_attr( $customer->get_billing_state() ); ?>" 
                       placeholder="State / Province" required>
            <?php endif; ?>
            <span class="field-error">State is required</span>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="billing_postcode">Postal / ZIP Code <span class="required">*</span></label>
            <input type="text" id="billing_postcode" name="billing_postcode" 
                   value="<?php echo esc_attr( $customer->get_billing_postcode() ); ?>" 
                   placeholder="Postal code" required>
            <span class="field-error">Postal code is required</span>
        </div>
        <div class="form-group">
            <label for="billing_country">Country <span class="required">*</span></label>
            <select id="billing_country" name="billing_country" required>
                <?php foreach ( $countries as $code => $name ) : ?>
                    <option value="<?php echo esc_attr( $code ); ?>" 
                            <?php selected( $default_country, $code ); ?>>
                        <?php echo esc_html( $name ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="field-error">Country is required</span>
        </div>
    </div>

    <!-- Ship to Different Address -->
    <div class="shipping-toggle">
        <input type="checkbox" id="ship_to_different_address" name="ship_to_different_address">
        <label for="ship_to_different_address">Ship to a different address?</label>
    </div>

    <div id="shipping-fields" class="shipping-fields">
        <div class="form-row">
            <div class="form-group">
                <label for="shipping_first_name">First Name <span class="required">*</span></label>
                <input type="text" id="shipping_first_name" name="shipping_first_name" 
                       placeholder="Enter first name">
            </div>
            <div class="form-group">
                <label for="shipping_last_name">Last Name <span class="required">*</span></label>
                <input type="text" id="shipping_last_name" name="shipping_last_name" 
                       placeholder="Enter last name">
            </div>
        </div>

        <div class="form-row full-width">
            <div class="form-group">
                <label for="shipping_address_1">Street Address <span class="required">*</span></label>
                <input type="text" id="shipping_address_1" name="shipping_address_1" 
                       placeholder="House number and street name">
            </div>
        </div>

        <div class="form-row full-width">
            <div class="form-group">
                <label for="shipping_address_2">Apartment, suite, unit, etc. (optional)</label>
                <input type="text" id="shipping_address_2" name="shipping_address_2" 
                       placeholder="Apartment, suite, unit, etc.">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="shipping_city">City <span class="required">*</span></label>
                <input type="text" id="shipping_city" name="shipping_city" placeholder="City">
            </div>
            <div class="form-group">
                <label for="shipping_state">State / Province <span class="required">*</span></label>
                <input type="text" id="shipping_state" name="shipping_state" placeholder="State / Province">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="shipping_postcode">Postal / ZIP Code <span class="required">*</span></label>
                <input type="text" id="shipping_postcode" name="shipping_postcode" placeholder="Postal code">
            </div>
            <div class="form-group">
                <label for="shipping_country">Country <span class="required">*</span></label>
                <select id="shipping_country" name="shipping_country">
                    <?php foreach ( $countries as $code => $name ) : ?>
                        <option value="<?php echo esc_attr( $code ); ?>" 
                                <?php selected( $default_country, $code ); ?>>
                            <?php echo esc_html( $name ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="form-row full-width" style="margin-top: 1.5rem;">
        <div class="form-group">
            <label for="delivery_date">Preferred Delivery Date <span class="required">*</span></label>
            <input type="date" id="delivery_date" name="delivery_date" required>
            <span class="field-error">Delivery date is required</span>
        </div>
    </div>

    <!-- Order Notes -->
    <div class="form-row full-width" style="margin-top: 1.5rem;">
        <div class="form-group">
            <label for="order_notes">Order Notes (optional)</label>
            <textarea id="order_notes" name="order_notes" 
                      placeholder="Notes about your order, e.g. delivery instructions or special requests for your cake..."></textarea>
        </div>
    </div>
</div>
