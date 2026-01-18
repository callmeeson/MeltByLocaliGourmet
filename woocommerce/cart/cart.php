<?php
/**
 * Cart Page
 *
 * Custom cart template matching Melt brand design
 *
 * @package Melt_Custom
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<style>
/* Cart Page Styles */
.cart-page-wrapper {
	padding-top: 5rem;
	background-color: white;
	min-height: 100vh;
	padding-bottom: 5rem;
}

.cart-header {
	padding: 2rem 1.5rem;
	text-align: center;
	max-width: 1280px;
	margin: 0 auto;
}

.cart-title {
	font-family: var(--font-serif);
	font-size: clamp(2rem, 5vw, 2.5rem);
	margin-bottom: 0.5rem;
	font-weight: 500;
	letter-spacing: -0.025em;
	color: var(--foreground);
}

.cart-subtitle {
	font-family: var(--font-body);
	font-weight: 300;
	font-size: 0.95rem;
	color: var(--muted-foreground);
}

.cart-container {
	max-width: 1280px;
	margin: 0 auto;
	padding: 0 1.5rem;
	display: grid;
	grid-template-columns: 1fr 380px;
	gap: 2rem;
	align-items: start;
}

@media (max-width: 968px) {
	.cart-container {
		grid-template-columns: 1fr;
	}
}

.cart-items-section {
	display: flex;
	flex-direction: column;
	gap: 1.5rem;
}

.cart-item {
	background: white;
	border: 1px solid rgba(0,0,0,0.06);
	border-radius: 16px;
	padding: 1.5rem;
	display: grid;
	grid-template-columns: 120px 1fr auto;
	gap: 1.5rem;
	align-items: center;
	transition: all 0.3s ease;
}

.cart-item:hover {
	box-shadow: 0 8px 16px rgba(0,0,0,0.06);
	border-color: rgba(184, 134, 11, 0.2);
}

.cart-item-image {
	width: 120px;
	height: 120px;
	border-radius: 12px;
	overflow: hidden;
	background: var(--secondary);
}

.cart-item-image img {
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.cart-item-details {
	display: flex;
	flex-direction: column;
	gap: 0.75rem;
}

.cart-item-name {
	font-family: var(--font-serif);
	font-size: 1.25rem;
	font-weight: 500;
	color: var(--foreground);
	margin: 0;
}

.cart-item-name a {
	color: inherit;
	text-decoration: none;
	transition: color 0.3s ease;
}

.cart-item-name a:hover {
	color: var(--primary);
}

.cart-item-meta {
	font-family: var(--font-body);
	font-size: 0.875rem;
	color: var(--muted-foreground);
	line-height: 1.6;
}

.cart-item-meta dt {
	display: inline;
	font-weight: 500;
	color: var(--foreground);
}

.cart-item-meta dd {
	display: inline;
	margin: 0 0 0.25rem 0.5rem;
}

.cart-item-actions {
	display: flex;
	flex-direction: column;
	align-items: flex-end;
	gap: 1rem;
}

.cart-item-price {
	font-family: var(--font-body);
	font-size: 1.5rem;
	font-weight: 600;
	color: var(--primary);
}

.quantity-controls {
	display: flex;
	align-items: center;
	gap: 0.75rem;
	background: var(--secondary);
	padding: 0.5rem;
	border-radius: 12px;
}

.quantity-btn {
	width: 2rem;
	height: 2rem;
	border-radius: 8px;
	border: 1px solid var(--border);
	background: white;
	color: var(--foreground);
	display: flex;
	align-items: center;
	justify-content: center;
	cursor: pointer;
	transition: all 0.2s ease;
	font-size: 1.25rem;
	font-weight: 600;
}

.quantity-btn:hover:not(:disabled) {
	background: var(--primary);
	color: white;
	border-color: var(--primary);
	transform: scale(1.1);
}

.quantity-btn:disabled {
	opacity: 0.3;
	cursor: not-allowed;
}

.quantity-input {
	width: 3rem;
	text-align: center;
	font-family: var(--font-body);
	font-size: 1rem;
	font-weight: 500;
	border: none;
	background: transparent;
	color: var(--foreground);
}

.remove-item {
	font-family: var(--font-body);
	font-size: 0.875rem;
	color: #dc2626;
	background: none;
	border: none;
	cursor: pointer;
	padding: 0.5rem;
	transition: all 0.2s ease;
	display: flex;
	align-items: center;
	gap: 0.35rem;
}

.remove-item:hover {
	color: #991b1b;
	transform: scale(1.05);
}

.cart-sidebar {
	position: sticky;
	top: 6rem;
	background: white;
	border: 1px solid rgba(0,0,0,0.06);
	border-radius: 16px;
	padding: 2rem;
	box-shadow: 0 4px 12px rgba(0,0,0,0.04);
}

.cart-sidebar-title {
	font-family: var(--font-serif);
	font-size: 1.5rem;
	font-weight: 500;
	margin-bottom: 1.5rem;
	color: var(--foreground);
}

.cart-totals {
	display: flex;
	flex-direction: column;
	gap: 1rem;
	margin-bottom: 1.5rem;
	padding-bottom: 1.5rem;
	border-bottom: 1px solid rgba(0,0,0,0.06);
}

.cart-total-row {
	display: flex;
	justify-content: space-between;
	align-items: center;
	font-family: var(--font-body);
}

.cart-total-label {
	font-size: 0.95rem;
	color: var(--muted-foreground);
}

.cart-total-value {
	font-size: 1rem;
	font-weight: 500;
	color: var(--foreground);
}

.cart-total-row.grand-total {
	padding-top: 1rem;
	border-top: 2px solid var(--primary);
	margin-top: 0.5rem;
}

.cart-total-row.grand-total .cart-total-label {
	font-size: 1.1rem;
	font-weight: 600;
	color: var(--foreground);
}

.cart-total-row.grand-total .cart-total-value {
	font-size: 1.75rem;
	font-weight: 700;
	color: var(--primary);
}

.checkout-button {
	width: 100%;
	padding: 1rem 2rem;
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
	text-decoration: none;
	display: block;
	text-align: center;
}

.checkout-button:hover {
	transform: translateY(-2px);
	box-shadow: 0 20px 35px -5px rgba(184, 134, 11, 0.5);
}

.continue-shopping {
	display: block;
	text-align: center;
	margin-top: 1rem;
	font-family: var(--font-body);
	font-size: 0.95rem;
	color: var(--muted-foreground);
	text-decoration: none;
	transition: color 0.3s ease;
}

.continue-shopping:hover {
	color: var(--primary);
}

.empty-cart {
	text-align: center;
	padding: 5rem 2rem;
	max-width: 600px;
	margin: 0 auto;
}

.empty-cart-icon {
	font-size: 5rem;
	margin-bottom: 1.5rem;
	opacity: 0.3;
}

.empty-cart-title {
	font-family: var(--font-serif);
	font-size: 2rem;
	margin-bottom: 1rem;
	color: var(--foreground);
}

.empty-cart-text {
	font-family: var(--font-body);
	font-size: 1rem;
	color: var(--muted-foreground);
	margin-bottom: 2rem;
}

.shop-button {
	display: inline-block;
	padding: 1rem 2.5rem;
	background: var(--primary);
	color: white;
	border-radius: 12px;
	font-family: var(--font-body);
	font-weight: 600;
	text-decoration: none;
	transition: all 0.3s ease;
}

.shop-button:hover {
	background: var(--accent);
	transform: translateY(-2px);
	box-shadow: 0 10px 20px rgba(184, 134, 11, 0.3);
}

@media (max-width: 768px) {
	.cart-item {
		grid-template-columns: 80px 1fr;
		gap: 1rem;
	}
	
	.cart-item-image {
		width: 80px;
		height: 80px;
	}
	
	.cart-item-actions {
		grid-column: 1 / -1;
		flex-direction: row;
		justify-content: space-between;
		align-items: center;
	}
}
</style>

<div class="cart-page-wrapper">
	<!-- Cart Header -->
	<div class="cart-header fade-in-section">
		<h1 class="cart-title">Shopping Cart</h1>
		<p class="cart-subtitle"><?php echo WC()->cart->get_cart_contents_count(); ?> item(s) in your cart</p>
	</div>

	<?php if ( WC()->cart->is_empty() ) : ?>
		
		<!-- Empty Cart State -->
		<div class="empty-cart fade-in-section">
			<div class="empty-cart-icon">🛒</div>
			<h2 class="empty-cart-title">Your cart is empty</h2>
			<p class="empty-cart-text">Looks like you haven't added any items to your cart yet. Explore our delicious collection!</p>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="shop-button">
				Browse Our Collection
			</a>
		</div>

	<?php else : ?>

		<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>

			<div class="cart-container">
				<!-- Cart Items -->
				<div class="cart-items-section">
					<?php
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
							?>
							<div class="cart-item fade-in-item">
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
										if ( !empty( $customs['toppings'] ) && is_array( $customs['toppings'] ) ) {
											echo '<dt>Toppings:</dt><dd>' . esc_html( implode( ', ', $customs['toppings'] ) ) . '</dd><br>';
										}
										if ( !empty( $customs['customMessage'] ) ) {
											echo '<dt>Message:</dt><dd>"' . esc_html( $customs['customMessage'] ) . '"</dd><br>';
										}
										if ( !empty( $customs['deliveryDate'] ) ) {
											echo '<dt>Delivery:</dt><dd>' . esc_html( $customs['deliveryDate'] ) . '</dd>';
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
									<button type="button" class="remove-item" onclick="removeItem('<?php echo esc_attr( $cart_item_key ); ?>')">
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

				<!-- Cart Sidebar -->
				<div class="cart-sidebar">
					<h2 class="cart-sidebar-title">Order Summary</h2>
					
					<div class="cart-totals">
						<div class="cart-total-row">
							<span class="cart-total-label">Subtotal</span>
							<span class="cart-total-value"><?php wc_cart_totals_subtotal_html(); ?></span>
						</div>

						<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
							<div class="cart-total-row">
								<span class="cart-total-label">Coupon: <?php echo esc_html( $code ); ?></span>
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

					<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-button">
						Proceed to Checkout
					</a>

					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="continue-shopping">
						← Continue Shopping
					</a>
				</div>
			</div>

			<?php do_action( 'woocommerce_cart_actions' ); ?>
			<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
		</form>

	<?php endif; ?>
</div>

<script>
function updateQuantity(cartItemKey, change) {
	const input = document.getElementById('qty-' + cartItemKey);
	const currentQty = parseInt(input.value);
	const newQty = Math.max(0, currentQty + change);
	
	input.value = newQty;
	
	// Submit form to update cart
	input.closest('form').submit();
}

function removeItem(cartItemKey) {
	if (confirm('Remove this item from your cart?')) {
		const input = document.getElementById('qty-' + cartItemKey);
		input.value = 0;
		input.closest('form').submit();
	}
}

// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
	lucide.createIcons();
}
</script>

<?php do_action( 'woocommerce_after_cart' ); ?>
