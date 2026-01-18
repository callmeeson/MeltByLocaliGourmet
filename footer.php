<footer class="site-footer">
	<div class="footer-container">
		<div class="footer-grid">
			<!-- Brand -->
			<div class="footer-brand">
				<h3>Melt</h3>
				<p class="brand-subtitle">by Locali Gourmet</p>
				<p class="brand-description">Crafting exquisite artisanal cakes since 2015</p>
				<div class="social-links">
					<a href="#" aria-label="Facebook">
						<i data-lucide="facebook"></i>
					</a>
					<a href="#" aria-label="Instagram">
						<i data-lucide="instagram"></i>
					</a>
					<a href="#" aria-label="Twitter">
						<i data-lucide="twitter"></i>
					</a>
				</div>
			</div>

			<!-- Quick Links -->
			<div class="footer-section">
				<h4>Quick Links</h4>
				<ul>
					<li><a href="<?php echo esc_url(home_url('/about')); ?>">About Us</a></li>
					<li><a href="<?php echo esc_url(home_url('/our-story')); ?>">Our Story</a></li>
					<li><a href="<?php echo esc_url(home_url('/shop')); ?>">Shop</a></li>
					<li><a href="<?php echo esc_url(home_url('/custom-orders')); ?>">Custom Orders</a></li>
					<li><a href="<?php echo esc_url(home_url('/catering')); ?>">Catering</a></li>
				</ul>
			</div>

			<!-- Customer Service -->
			<div class="footer-section">
				<h4>Support</h4>
				<ul>
					<li><a href="<?php echo esc_url(home_url('/contact')); ?>">Contact</a></li>
					<li><a href="<?php echo esc_url(home_url('/shipping')); ?>">Shipping</a></li>
					<li><a href="<?php echo esc_url(home_url('/returns')); ?>">Returns</a></li>
					<li><a href="<?php echo esc_url(home_url('/faq')); ?>">FAQ</a></li>
					<li><a href="<?php echo esc_url(home_url('/terms')); ?>">Terms</a></li>
				</ul>
			</div>

			<!-- Contact Info -->
			<div class="footer-section">
				<h4>Contact</h4>
				<ul>
					<li style="display: flex; align-items: flex-start; gap: 0.75rem;">
						<i data-lucide="map-pin" style="width: 1rem; height: 1rem; flex-shrink: 0; margin-top: 0.125rem;"></i>
						<span>123 Baker Street, Dubai, UAE</span>
					</li>
					<li style="display: flex; align-items: center; gap: 0.75rem;">
						<i data-lucide="phone" style="width: 1rem; height: 1rem; flex-shrink: 0;"></i>
						<span>(555) 123-4567</span>
					</li>
					<li style="display: flex; align-items: center; gap: 0.75rem;">
						<i data-lucide="mail" style="width: 1rem; height: 1rem; flex-shrink: 0;"></i>
						<span>hello@meltbylg.com</span>
					</li>
				</ul>
			</div>
		</div>

		<div class="footer-bottom">
			<p>&copy; <?php echo esc_html(date('Y')); ?> Melt by Locali Gourmet. All rights reserved.</p>
		</div>
	</div>
</footer>
<!-- WhatsApp Floating Widget -->
<style>
	@keyframes whatsapp-pulse {
		0% {
			transform: scale(1);
			box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
		}

		70% {
			transform: scale(1.05);
			box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
		}

		100% {
			transform: scale(1);
			box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
		}
	}

	#whatsapp-btn {
		animation: whatsapp-pulse 2s infinite;
	}

	#whatsapp-btn:hover {
		animation: none;
		transform: scale(1.1);
		box-shadow: 0 6px 25px rgba(37, 211, 102, 0.5);
	}
</style>
<div id="whatsapp-widget" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
	<!-- Chat Panel (appears above button) -->
	<div id="whatsapp-panel" style="display: none; position: absolute; bottom: 70px; right: 0; width: 300px; background: #fff; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); overflow: hidden;">
		<div style="background: linear-gradient(135deg, #25d366, #128C7E); padding: 16px; display: flex; justify-content: space-between; align-items: center;">
			<p style="color: #fff; font-weight: 600; margin: 0; font-size: 16px;">Start a Conversation</p>
			<button onclick="toggleWhatsAppPanel()" style="background: none; border: none; color: #fff; cursor: pointer; padding: 4px;">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</button>
		</div>
		<div style="padding: 16px;">
			<p style="color: #666; font-size: 14px; margin: 0 0 8px;">Hi! Click below to chat on WhatsApp</p>
			<p style="color: #999; font-size: 13px; margin: 0 0 16px;">The team typically replies in a few minutes.</p>
			<div style="background: rgba(37, 211, 102, 0.1); padding: 12px; border-radius: 8px; margin-bottom: 16px;">
				<span style="font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 1px;">MELT BY</span><br>
				<span style="font-weight: 600; color: #333;">L'OCALI GOURMET</span>
			</div>
			<a href="https://wa.me/15551234567?text=Hi%21%20I%27d%20like%20to%20chat%20about%20Melt%20by%20Locali%20Gourmet." target="_blank" rel="noopener noreferrer" style="display: block; background: #25d366; color: #fff; text-align: center; padding: 12px 20px; border-radius: 50px; font-weight: 500; text-decoration: none; transition: transform 0.2s, box-shadow 0.2s;">
				Contact Now
			</a>
		</div>
	</div>
	<!-- Float Button with Pulse Animation -->
	<button id="whatsapp-btn" onclick="toggleWhatsAppPanel()" style="width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, #25d366, #128C7E); border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: transform 0.2s, box-shadow 0.2s;">
		<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
			<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
		</svg>
	</button>
</div>

<script>
	function toggleWhatsAppPanel() {
		var panel = document.getElementById('whatsapp-panel');
		if (panel.style.display === 'none' || panel.style.display === '') {
			panel.style.display = 'block';
		} else {
			panel.style.display = 'none';
		}
	}

	// Close panel when clicking outside
	document.addEventListener('click', function(event) {
		var widget = document.getElementById('whatsapp-widget');
		var panel = document.getElementById('whatsapp-panel');
		if (widget && panel && !widget.contains(event.target)) {
			panel.style.display = 'none';
		}
	});
</script>

<?php
// Include auth modal
get_template_part('template-parts/auth-modal');

// Include customize modal (for shop pages)
if (is_page_template('page-shop.php') || is_shop() || is_product_taxonomy()) {
	get_template_part('template-parts/customize-modal');
}
?>

<?php wp_footer(); ?>

<script>
	// Initialize Lucide icons when DOM is ready
	document.addEventListener('DOMContentLoaded', function() {
		if (typeof lucide !== 'undefined') {
			lucide.createIcons();
		}

		const whatsappWidget = document.querySelector('[data-whatsapp]');
		if (whatsappWidget) {
			const toggleButton = whatsappWidget.querySelector('.whatsapp-float__button');
			const closeButton = whatsappWidget.querySelector('.whatsapp-float__close');
			const panel = whatsappWidget.querySelector('.whatsapp-float__panel');

			const setOpen = (isOpen) => {
				whatsappWidget.classList.toggle('is-open', isOpen);
				if (toggleButton) {
					toggleButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
				}
				if (panel && isOpen) {
					panel.focus();
				}
			};

			if (toggleButton) {
				toggleButton.addEventListener('click', () => {
					setOpen(!whatsappWidget.classList.contains('is-open'));
				});
			}

			if (closeButton) {
				closeButton.addEventListener('click', () => {
					setOpen(false);
				});
			}

			document.addEventListener('click', (event) => {
				if (!whatsappWidget.contains(event.target)) {
					setOpen(false);
				}
			});
		}

		// Fallback: Define openAuthModal if not already defined
		if (typeof openAuthModal === 'undefined') {
			console.warn('Auth modal script not loaded, using fallback');
			window.openAuthModal = function(mode) {
				const modal = document.getElementById('authModal');
				if (modal) {
					modal.style.display = 'flex';
					document.body.style.overflow = 'hidden';

					// Update title based on mode
					const title = document.getElementById('authModalTitle');
					const subtitle = document.getElementById('authModalSubtitle');
					if (mode === 'register' || mode === 'signup') {
						if (title) title.textContent = 'Create Account';
						if (subtitle) subtitle.textContent = 'Sign up to discover artisan excellence';
					} else {
						if (title) title.textContent = 'Welcome Back';
						if (subtitle) subtitle.textContent = 'Sign in to continue your order';
					}

					// Reinitialize icons
					if (typeof lucide !== 'undefined') {
						lucide.createIcons();
					}
				} else {
					console.error('Auth modal element not found');
				}
			};

			window.closeAuthModal = function() {
				const modal = document.getElementById('authModal');
				if (modal) {
					modal.style.display = 'none';
					document.body.style.overflow = 'auto';
				}
			};
		}
	});
</script>

</body>

</html>