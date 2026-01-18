<?php
/**
 * Cart Items Template Part
 * 
 * Used for initial render and AJAX updates
 */
?>
<div class="cart-items-section">
    <?php
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
            ?>
            <div class="cart-item fade-in-item" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                <!-- Product Image -->
                <div class="cart-item-image">
                    <?php
                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                    if ( ! $product_permalink ) {
                        echo $thumbnail;
                    } else {
                        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
                    }
                    ?>
                </div>

                <!-- Product Details -->
                <div class="cart-item-details">
                    <h3 class="cart-item-name">
                        <?php
                        if ( ! $product_permalink ) {
                            echo wp_kses_post( $_product->get_name() );
                        } else {
                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                        }
                        ?>
                    </h3>

                    <?php
                    // Display customization data if exists
                    if ( isset( $cart_item['melt_customization'] ) && !empty( $cart_item['melt_customization'] ) ) {
                        echo '<dl class="cart-item-meta">';
                        $customs = $cart_item['melt_customization'];
                        
                        if ( !empty( $customs['size'] ) ) {
                            echo '<dt>Size:</dt><dd>' . esc_html( $customs['size'] ) . '</dd><br>';
                        }
                        if ( !empty( $customs['layers'] ) ) {
                            echo '<dt>Layers:</dt><dd>' . esc_html( $customs['layers'] ) . '</dd><br>';
                        }
                        if ( !empty( $customs['flavor'] ) ) {
                            echo '<dt>Flavor:</dt><dd>' . esc_html( $customs['flavor'] ) . '</dd><br>';
                        }
                        if ( !empty( $customs['frosting'] ) ) {
                            echo '<dt>Frosting:</dt><dd>' . esc_html( $customs['frosting'] ) . '</dd><br>';
                        }
                        if ( !empty( $customs['filling'] ) ) {
                            echo '<dt>Filling:</dt><dd>' . esc_html( $customs['filling'] ) . '</dd><br>';
                        }
                        if ( !empty( $customs['toppings'] ) && is_array( $customs['toppings'] ) ) {
                            echo '<dt>Toppings:</dt><dd>' . esc_html( implode( ', ', $customs['toppings'] ) ) . '</dd><br>';
                        }
                        if ( !empty( $customs['decoration'] ) ) {
                            echo '<dt>Decoration:</dt><dd>' . esc_html( $customs['decoration'] ) . '</dd><br>';
                        }
                        if ( !empty( $customs['customMessage'] ) ) {
                            echo '<dt>Message:</dt><dd>"' . esc_html( $customs['customMessage'] ) . '"</dd><br>';
                        }
                        if ( !empty( $customs['deliveryDate'] ) ) {
                            echo '<dt>Delivery:</dt><dd>' . esc_html( $customs['deliveryDate'] ) . '</dd>';
                        }
                        if ( !empty( $customs['specialInstructions'] ) ) {
                            echo '<dt>Instructions:</dt><dd>' . esc_html( $customs['specialInstructions'] ) . '</dd>';
                        }
                        
                        echo '</dl>';
                    }
                    ?>
                </div>

                <!-- Actions & Price -->
                <div class="cart-item-actions">
                    <div class="cart-item-price">
                        <?php
                        echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                        ?>
                    </div>

                    <!-- Quantity Controls -->
                    <div class="quantity-controls">
                        <button type="button" class="quantity-btn" onclick="updateQuantity('<?php echo esc_attr( $cart_item_key ); ?>', -1)">−</button>
                        <input type="number" 
                            class="quantity-input" 
                            name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]" 
                            value="<?php echo esc_attr( $cart_item['quantity'] ); ?>" 
                            min="0" 
                            max="<?php echo esc_attr( $_product->get_max_purchase_quantity() ); ?>"
                            readonly
                            id="qty-<?php echo esc_attr( $cart_item_key ); ?>">
                        <button type="button" class="quantity-btn" onclick="updateQuantity('<?php echo esc_attr( $cart_item_key ); ?>', 1)">+</button>
                    </div>

                    <!-- Remove Button -->
                    <?php $remove_url = wc_get_cart_remove_url( $cart_item_key ); ?>
                    <button type="button" class="remove-item" onclick="removeItem('<?php echo esc_js( $cart_item_key ); ?>')">
                        <i data-lucide="trash-2" style="width: 1rem; height: 1rem;"></i>
                        Remove
                    </button>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>
