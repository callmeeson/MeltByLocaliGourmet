<?php
/**
 * Template Name: Cart Page (Custom)
 * 
 * Custom cart page template with premium design
 *
 * @package Melt_Custom
 */

get_header();
?>

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

/* Coupon Styles */
.cart-coupon-section {
	margin-bottom: 1.5rem;
	padding-bottom: 1.5rem;
	border-bottom: 1px solid rgba(0,0,0,0.06);
}

.coupon-input-group {
	display: flex;
	gap: 0.5rem;
}

.coupon-input {
	flex: 1;
	padding: 0.75rem;
	border: 1px solid rgba(0,0,0,0.1);
	border-radius: 8px;
	font-family: var(--font-body);
	font-size: 0.95rem;
	transition: all 0.2s ease;
}

.coupon-input:focus {
	outline: none;
	border-color: var(--primary);
	box-shadow: 0 0 0 2px rgba(184, 134, 11, 0.1);
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
}

.apply-coupon-btn:hover {
	background: var(--primary);
	color: white;
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

		<div class="cart-container-wrapper">
			<div class="cart-container">
				<!-- Cart Items -->
				<?php get_template_part( 'template-parts/cart/cart-items' ); ?>

				<!-- Cart Sidebar -->
				<?php get_template_part( 'template-parts/cart/cart-sidebar' ); ?>
			</div>
		</div>

	<?php endif; ?>
</div>

<script>
/**
 * AJAX Cart Actions
 */
function cartAction(actionType, data = {}) {
	// Show loading state
	document.body.style.cursor = 'wait';
	const container = document.querySelector('.cart-container');
	if(container) container.style.opacity = '0.6';

	const formData = new FormData();
	formData.append('action', 'melt_cart_action');
	formData.append('nonce', '<?php echo wp_create_nonce( "melt_nonce" ); ?>');
	formData.append('action_type', actionType);

	for (const key in data) {
		formData.append(key, data[key]);
	}

	fetch('<?php echo admin_url( "admin-ajax.php" ); ?>', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(response => {
		if (response.success) {
			const data = response.data;
			
			// Update Fragments
			if (data.fragments) {
				for (const selector in data.fragments) {
					const el = document.querySelector(selector);
					if (el) {
						el.outerHTML = data.fragments[selector];
					}
				}
			}
			
			// Show message if any
			if (data.message) {
				// Use toast if available, otherwise console
				if (typeof showToast === 'function') {
					showToast(data.message, data.success ? 'success' : 'error');
				}
				
				// Also handle specific elements like coupon error
				if (actionType === 'apply_coupon' && !data.success) {
					const msgEl = document.getElementById('coupon-message');
					if(msgEl) {
						msgEl.textContent = data.message;
						msgEl.style.display = 'block';
						msgEl.style.color = 'red';
					}
				}
			}

			// Re-init icons
			if (typeof lucide !== 'undefined') {
				lucide.createIcons();
			}
			
			// Trigger cart updated event
			jQuery(document.body).trigger('updated_wc_div');
			
		} else {
			alert(response.data.message || 'Error updating cart');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		alert('Connection error. Please try again.');
	})
	.finally(() => {
		document.body.style.cursor = 'default';
		const container = document.querySelector('.cart-container');
		if(container) container.style.opacity = '1';
		
		// Refocus input if needed
		if (actionType === 'apply_coupon') {
			// maybe clear input?
		}
	});
}

function updateQuantity(cartItemKey, change) {
	const input = document.getElementById('qty-' + cartItemKey);
	if (!input) return;
	
	const currentQty = parseInt(input.value);
	const newQty = Math.max(0, currentQty + change);
	
	// Create optimistic UI update
	input.value = newQty;
	
	cartAction('update_quantity', {
		cart_item_key: cartItemKey,
		quantity: newQty
	});
}

function removeItem(cartItemKey) {
	if (confirm('Remove this item?')) {
		cartAction('remove_item', {
			cart_item_key: cartItemKey
		});
	}
}

function applyCoupon() {
	const input = document.getElementById('coupon_code');
	if (!input || !input.value) return;
	
	cartAction('apply_coupon', {
		coupon_code: input.value
	});
}

function removeCoupon(code) {
	if (confirm('Remove coupon ' + code + '?')) {
		cartAction('remove_coupon', {
			coupon_code: code
		});
	}
}

// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
	lucide.createIcons();
}
</script>
</div>
</div>

<?php get_footer(); ?>
