<?php
/**
 * Customize Modal Template Part - Premium Design with Enhanced UX
 * 
 * Include this before closing body tag
 */
?>

<style>
/* Modal Styles */
#customizeModal {
	animation: fadeInBackdrop 0.3s ease-out;
}

@keyframes fadeInBackdrop {
	from {
		opacity: 0;
	}
	to {
		opacity: 1;
	}
}

.customize-modal-content {
	animation: slideUpModal 0.4s cubic-bezier(0.16, 1, 0.3, 1);
	max-height: 90vh;
	overflow-y: auto;
	scroll-behavior: smooth;
}

@keyframes slideUpModal {
	from {
		transform: translateY(40px);
		opacity: 0;
	}
	to {
		transform: translateY(0);
		opacity: 1;
	}
}

/* Scrollbar styling */
.customize-modal-content::-webkit-scrollbar {
	width: 6px;
}

.customize-modal-content::-webkit-scrollbar-track {
	background: #f1f1f1;
	border-radius: 10px;
}

.customize-modal-content::-webkit-scrollbar-thumb {
	background: var(--primary);
	border-radius: 10px;
}

.customize-modal-content::-webkit-scrollbar-thumb:hover {
	background: #9a7d0a;
}

/* Two-column layout for desktop */
@media (min-width: 1024px) {
	.customize-modal-wrapper {
		display: grid;
		grid-template-columns: 1fr 350px;
		gap: 2rem;
		align-items: start;
	}
	
	.customize-modal-sidebar {
		position: sticky;
		top: 2rem;
	}
}

/* Section styling */
.customize-section {
	margin-bottom: 2rem;
	padding-bottom: 2rem;
	border-bottom: 1px solid var(--border);
}

.customize-section:last-child {
	margin-bottom: 0;
	padding-bottom: 0;
	border-bottom: none;
}

.customize-section-title {
	display: flex;
	align-items: center;
	gap: 0.5rem;
	color: var(--foreground);
	font-family: var(--font-body);
	font-weight: 600;
	margin-bottom: 1rem;
	font-size: 1rem;
	text-transform: uppercase;
	letter-spacing: 0.5px;
}

.customize-section-title::before {
	content: '';
	width: 4px;
	height: 4px;
	background: var(--primary);
	border-radius: 50%;
	flex-shrink: 0;
}

/* Buttons and options */
.option-group {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
	gap: 0.75rem;
}

.option-btn {
	padding: 1rem 0.75rem;
	border: 2px solid var(--border);
	border-radius: 10px;
	background: white;
	cursor: pointer;
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	font-family: var(--font-body);
	text-align: center;
	position: relative;
	overflow: hidden;
}

.option-btn:hover:not(.active) {
	transform: translateY(-3px);
	box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
	border-color: var(--primary);
}

.option-btn.active {
	background: linear-gradient(135deg, var(--primary), #daa520);
	color: white;
	border-color: var(--primary);
	box-shadow: 0 8px 20px rgba(184, 134, 11, 0.3);
}

.option-label {
	display: block;
	font-size: 0.75rem;
	font-weight: 600;
	margin-bottom: 0.25rem;
	color: var(--foreground);
}

.option-btn.active .option-label {
	color: white;
}

.option-sublabel {
	display: block;
	font-size: 0.7rem;
	color: var(--muted-foreground);
	margin-bottom: 0.25rem;
}

.option-btn.active .option-sublabel {
	color: rgba(255, 255, 255, 0.85);
}

.option-price {
	display: block;
	font-size: 0.8rem;
	font-weight: 600;
	color: var(--primary);
	margin-top: 0.25rem;
}

.option-btn.active .option-price {
	color: white;
}

/* Select dropdowns */
.form-select {
	width: 100%;
	padding: 0.85rem 1rem;
	border: 2px solid var(--border);
	border-radius: 10px;
	background: white;
	color: var(--foreground);
	font-family: var(--font-body);
	font-size: 0.95rem;
	cursor: pointer;
	transition: all 0.3s ease;
	appearance: none;
	background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
	background-repeat: no-repeat;
	background-position: right 0.75rem center;
	background-size: 1.25rem;
	padding-right: 2.5rem;
}

.form-select:hover:not(:focus) {
	border-color: var(--primary);
}

.form-select:focus {
	outline: none;
	border-color: var(--primary);
	box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.1);
}

/* Quantity controls */
.quantity-control {
	display: flex;
	align-items: center;
	gap: 1rem;
	background: var(--secondary);
	padding: 1rem;
	border-radius: 10px;
}

.qty-btn {
	width: 2.5rem;
	height: 2.5rem;
	padding: 0;
	border: 2px solid var(--border);
	border-radius: 8px;
	background: white;
	cursor: pointer;
	transition: all 0.3s ease;
	display: flex;
	align-items: center;
	justify-content: center;
	font-weight: 600;
	color: var(--primary);
	font-size: 1.2rem;
}

.qty-btn:hover {
	background: var(--primary);
	color: white;
	border-color: var(--primary);
	transform: scale(1.05);
}

.qty-display {
	flex: 1;
	text-align: center;
}

.qty-display .qty-number {
	display: block;
	font-size: 1.75rem;
	font-family: var(--font-serif);
	color: var(--foreground);
	font-weight: 600;
}

.qty-display .qty-label {
	display: block;
	font-size: 0.7rem;
	color: var(--muted-foreground);
	margin-top: 0.25rem;
}

/* Text inputs */
.form-textarea {
	width: 100%;
	padding: 0.85rem 1rem;
	border: 2px solid var(--border);
	border-radius: 10px;
	font-family: var(--font-body);
	font-size: 0.95rem;
	resize: vertical;
	min-height: 80px;
	transition: all 0.3s ease;
}

.form-textarea:focus {
	outline: none;
	border-color: var(--primary);
	box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.1);
}

/* Price summary */
.price-summary {
	background: linear-gradient(135deg, rgba(184, 134, 11, 0.05), rgba(218, 165, 32, 0.05));
	border: 2px solid var(--primary);
	border-radius: 12px;
	padding: 1.25rem;
	margin-bottom: 1.5rem;
}

.price-row {
	display: flex;
	justify-content: space-between;
	align-items: center;
	font-family: var(--font-body);
	margin-bottom: 0.75rem;
	font-size: 0.95rem;
}

.price-row:last-child {
	margin-bottom: 0;
	padding-top: 0.75rem;
	border-top: 1px solid var(--primary);
	font-weight: 600;
	font-size: 1.1rem;
	color: var(--primary);
}

.price-label {
	color: var(--muted-foreground);
}

.price-value {
	color: var(--foreground);
	font-weight: 500;
}

/* Buttons */
.btn-primary {
	width: 100%;
	padding: 1rem 1.5rem;
	background: linear-gradient(135deg, var(--primary), #daa520);
	color: white;
	border: none;
	border-radius: 10px;
	font-family: var(--font-body);
	font-weight: 600;
	font-size: 1rem;
	cursor: pointer;
	transition: all 0.3s ease;
	box-shadow: 0 8px 16px rgba(184, 134, 11, 0.2);
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 0.5rem;
	margin-bottom: 0.75rem;
}

.btn-primary:hover {
	transform: translateY(-2px);
	box-shadow: 0 12px 24px rgba(184, 134, 11, 0.3);
}

.btn-primary:active {
	transform: translateY(0);
}

.btn-secondary {
	width: 100%;
	padding: 0.85rem 1.5rem;
	background: white;
	color: var(--primary);
	border: 2px solid var(--primary);
	border-radius: 10px;
	font-family: var(--font-body);
	font-weight: 600;
	font-size: 0.95rem;
	cursor: pointer;
	transition: all 0.3s ease;
}

.btn-secondary:hover {
	background: var(--secondary);
	transform: translateY(-2px);
}

/* Modal preview section */
.product-preview {
	background: white;
	border: 2px solid var(--border);
	border-radius: 12px;
	padding: 1.5rem;
	text-align: center;
}

.product-preview-image {
	width: 100%;
	height: auto;
	border-radius: 10px;
	margin-bottom: 1rem;
	object-fit: cover;
}

.product-preview-name {
	font-family: var(--font-serif);
	font-size: 1.25rem;
	color: var(--foreground);
	margin-bottom: 0.5rem;
	font-weight: 600;
}

.product-preview-desc {
	font-family: var(--font-body);
	font-size: 0.85rem;
	color: var(--muted-foreground);
	margin-bottom: 1.5rem;
}

/* Close button */
.modal-close-btn {
	color: white;
	background: rgba(255, 255, 255, 0.2);
	backdrop-filter: blur(10px);
	border: 1px solid rgba(255, 255, 255, 0.3);
	border-radius: 8px;
	padding: 0.5rem;
	cursor: pointer;
	transition: all 0.3s ease;
	width: 2.5rem;
	height: 2.5rem;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-shrink: 0;
}

.modal-close-btn:hover {
	background: rgba(255, 255, 255, 0.3);
	transform: rotate(90deg);
}

/* Responsive */
@media (max-width: 768px) {
	.customize-modal-wrapper {
		display: block;
	}
	
	.customize-modal-sidebar {
		position: static;
		margin-top: 2rem;
	}
	
	.option-group {
		grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
	}
}
</style>

<!-- Customize Modal -->
<div id="customizeModal" style="display: none; position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.7); backdrop-filter: blur(12px); z-index: 9999; padding: 1rem; overflow-y: auto; align-items: center; justify-content: center;">
	<div class="customize-modal-content" style="background: white; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 1100px; width: 100%; margin: auto;">
		
		<!-- Header -->
		<div style="background: linear-gradient(135deg, #B8860B 0%, #DAA520 100%); padding: 1.5rem 2rem; border-radius: 16px 16px 0 0; position: relative; overflow: hidden;">
			<div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
			<div style="position: absolute; bottom: -30px; left: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
			
			<div style="display: flex; align-items: center; justify-content: space-between; position: relative; gap: 1.5rem;">
				<div>
					<h2 style="color: white; font-family: var(--font-serif); font-size: 1.75rem; display: flex; align-items: center; gap: 0.5rem; margin: 0; font-weight: 600; letter-spacing: -0.5px;">
						<i data-lucide="cake" style="width: 1.5rem; height: 1.5rem;"></i>
						Customize Your Cake
					</h2>
					<p id="modalProductName" style="color: rgba(255, 255, 255, 0.95); font-family: var(--font-body); margin: 0.5rem 0 0; font-size: 0.95rem; font-weight: 300;"></p>
				</div>
				<button onclick="closeCustomizeModal()" class="modal-close-btn" aria-label="Close dialog">
					<i data-lucide="x" style="width: 1.25rem; height: 1.25rem;"></i>
				</button>
			</div>
		</div>

		<!-- Product Image (Hidden) -->
		<img id="modalProductImage" src="" alt="" style="display: none;">

		<!-- Modal Content Grid -->
		<div class="customize-modal-wrapper" style="padding: 2rem;">

			<!-- Main Content -->
			<div>

				<!-- Size Selection -->
				<div class="customize-section">
					<div class="customize-section-title">Cake Size</div>
					<div class="option-group">
						<button class="option-btn size-option active" onclick="selectSize('Small (6 inch)', this)">
							<span class="option-label">Small</span>
							<span class="option-sublabel">6 inch</span>
							<span class="option-price">Base</span>
						</button>
						<button class="option-btn size-option" onclick="selectSize('Medium (8 inch)', this)">
							<span class="option-label">Medium</span>
							<span class="option-sublabel">8 inch</span>
							<span class="option-price">+50 AED</span>
						</button>
						<button class="option-btn size-option" onclick="selectSize('Large (10 inch)', this)">
							<span class="option-label">Large</span>
							<span class="option-sublabel">10 inch</span>
							<span class="option-price">+100 AED</span>
						</button>
						<button class="option-btn size-option" onclick="selectSize('Extra Large (12 inch)', this)">
							<span class="option-label">XL</span>
							<span class="option-sublabel">12 inch</span>
							<span class="option-price">+180 AED</span>
						</button>
					</div>
				</div>

				<!-- Layers -->
				<div class="customize-section">
					<div class="customize-section-title">Layers</div>
					<div class="quantity-control">
						<button onclick="changeLayers(-1)" class="qty-btn">−</button>
						<div class="qty-display">
							<span class="qty-number" id="layersCount">2</span>
							<span class="qty-label">layers</span>
						</div>
						<button onclick="changeLayers(1)" class="qty-btn">+</button>
						<span id="layerPrice" style="color: var(--primary); font-family: var(--font-body); font-weight: 600; min-width: 60px; text-align: right; display: none;"></span>
					</div>
				</div>

				<!-- Flavor, Frosting & Filling -->
				<div class="customize-section">
					<div class="customize-section-title">Flavors & Fillings</div>
					<div style="display: grid; gap: 1rem;">
						<div>
							<label style="display: block; color: var(--muted-foreground); font-family: var(--font-body); font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">Cake Flavor</label>
							<select onchange="updateCustomization('flavor', this.value)" class="form-select">
								<option>Vanilla</option>
								<option>Chocolate</option>
								<option>Red Velvet</option>
								<option>Strawberry</option>
								<option>Carrot</option>
								<option>Lemon</option>
								<option>Saffron</option>
								<option>Pistachio</option>
							</select>
						</div>
						<div>
							<label style="display: block; color: var(--muted-foreground); font-family: var(--font-body); font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">Frosting Type</label>
							<select onchange="updateCustomization('frosting', this.value)" class="form-select">
								<option>Buttercream</option>
								<option>Cream Cheese</option>
								<option>Whipped Cream</option>
								<option>Fondant</option>
								<option>Ganache</option>
								<option>Royal Icing</option>
							</select>
						</div>
						<div>
							<label style="display: block; color: var(--muted-foreground); font-family: var(--font-body); font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">Filling</label>
							<select onchange="updateCustomization('filling', this.value)" class="form-select">
								<option>Vanilla Cream</option>
								<option>Chocolate Mousse</option>
								<option>Fruit Jam</option>
								<option>Caramel</option>
								<option>Nutella</option>
								<option>Salted Caramel</option>
								<option>Passion Fruit</option>
								<option>Tiramisu Cream</option>
							</select>
						</div>
					</div>
				</div>

				<!-- Toppings -->
				<div class="customize-section">
					<div class="customize-section-title">Premium Toppings</div>
					<div class="option-group">
						<button class="option-btn" onclick="toggleTopping('Fresh Berries', this)">
							<span class="option-label">Berries</span>
							<span class="option-price">+25 AED</span>
						</button>
						<button class="option-btn" onclick="toggleTopping('Edible Gold Leaf', this)">
							<span class="option-label">Gold Leaf</span>
							<span class="option-price">+45 AED</span>
						</button>
						<button class="option-btn" onclick="toggleTopping('Chocolate Shavings', this)">
							<span class="option-label">Chocolate</span>
							<span class="option-price">+20 AED</span>
						</button>
						<button class="option-btn" onclick="toggleTopping('Fresh Flowers', this)">
							<span class="option-label">Flowers</span>
							<span class="option-price">+35 AED</span>
						</button>
						<button class="option-btn" onclick="toggleTopping('Macarons', this)">
							<span class="option-label">Macarons</span>
							<span class="option-price">+40 AED</span>
						</button>
						<button class="option-btn" onclick="toggleTopping('Nuts & Almonds', this)">
							<span class="option-label">Nuts</span>
							<span class="option-price">+15 AED</span>
						</button>
						<button class="option-btn" onclick="toggleTopping('Candy Pearls', this)">
							<span class="option-label">Candy</span>
							<span class="option-price">+30 AED</span>
						</button>
					</div>
				</div>

				<!-- Decoration -->
				<div class="customize-section">
					<div class="customize-section-title">Decoration Style</div>
					<div class="option-group">
						<button class="option-btn decoration-option active" onclick="selectDecoration('Simple', this)">
							<span class="option-label">Simple</span>
							<span class="option-price">Base</span>
						</button>
						<button class="option-btn decoration-option" onclick="selectDecoration('Elegant Piping', this)">
							<span class="option-label">Piping</span>
							<span class="option-price">+50 AED</span>
						</button>
						<button class="option-btn decoration-option" onclick="selectDecoration('Custom Design', this)">
							<span class="option-label">Custom</span>
							<span class="option-price">+100 AED</span>
						</button>
						<button class="option-btn decoration-option" onclick="selectDecoration('Premium 3D Design', this)">
							<span class="option-label">Premium</span>
							<span class="option-price">+200 AED</span>
						</button>
					</div>
				</div>

				<!-- Message & Instructions -->
				<div class="customize-section">
					<div class="customize-section-title">Special Requests</div>
					<div style="display: grid; gap: 1rem;">
						<div>
							<label style="display: block; color: var(--muted-foreground); font-family: var(--font-body); font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">Custom Message</label>
							<input type="text" id="customMessage" onchange="updateCustomization('customMessage', this.value)" placeholder="Add a custom message..." class="form-select" style="appearance: none; background: none;">
						</div>
						<div>
							<label style="display: block; color: var(--muted-foreground); font-family: var(--font-body); font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">Delivery Date</label>
							<input type="date" id="deliveryDate" onchange="updateCustomization('deliveryDate', this.value)" class="form-select" style="appearance: none; background: none;">
						</div>
						<div>
							<label style="display: block; color: var(--muted-foreground); font-family: var(--font-body); font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">Special Instructions</label>
							<textarea id="specialInstructions" onchange="updateCustomization('specialInstructions', this.value)" placeholder="Any allergies or special requests?" class="form-textarea"></textarea>
						</div>
					</div>
				</div>

			</div>

			<!-- Sidebar - Price Summary & CTA -->
			<div class="customize-modal-sidebar">
				<!-- Product Preview -->
				<div class="product-preview">
					<img id="modalProductImagePreview" src="" alt="" class="product-preview-image">
					<h3 class="product-preview-name" id="modalProductNamePreview"></h3>
					<p class="product-preview-desc" id="modalProductDescPreview"></p>
				</div>

				<!-- Price Summary -->
				<div class="price-summary" style="margin-top: 1.5rem;">
					<div class="price-row">
						<span class="price-label">Base Price</span>
						<span class="price-value" id="basePrice">0.00</span>
					</div>
					<div class="price-row" id="additionalPriceRow" style="display: none;">
						<span class="price-label">Customizations</span>
						<span class="price-value" id="additionalPrice">0.00</span>
					</div>
					<div class="price-row">
						<span style="color: var(--primary); font-weight: 600;">Total</span>
						<span style="color: var(--primary); font-size: 1.25rem; font-weight: 700;" id="totalPrice">0.00</span>
					</div>
				</div>

				<!-- Action Buttons -->
				<button class="btn-primary" id="addToCartBtn" onclick="addCustomizedToCart(event)">
					<i data-lucide="shopping-cart" style="width: 1.1rem; height: 1.1rem;"></i>
					Add to Cart
				</button>
				<button class="btn-secondary" onclick="closeCustomizeModal()">Cancel</button>
			</div>

		</div>
	</div>
</div>

<script src="<?php echo get_template_directory_uri(); ?>/js/customize-modal.js"></script>

