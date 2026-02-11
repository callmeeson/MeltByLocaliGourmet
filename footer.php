<footer class="site-footer">
	<div class="footer-container">
		<div class="footer-grid">
			<!-- Brand -->
			<div class="footer-brand">
				<h3>Melt</h3>
				<p class="brand-subtitle">by Locali Gourmet</p>
				<p class="brand-description">Crafting exquisite artisanal cakes since 2015</p>
				<div class="social-links">
					<a href="https://www.instagram.com/meltbylocaligourmet?igsh=MWFidGlraDEwemZvdw%3D%3D&utm_source=qr" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
						<i data-lucide="instagram"></i>
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
					<li><a href="<?php echo esc_url(home_url('/custom-cake')); ?>">Custom Cake</a></li>
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
						<span>+971 55 550 7868</span>
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
<!-- Modern Premium WhatsApp Widget -->
<style>
	/* WhatsApp Widget - Modern Premium Design */
	.whatsapp-widget {
		position: fixed;
		bottom: 24px;
		right: 24px;
		z-index: 9999;
		font-family: var(--font-body);
	}

	@media (max-width: 480px) {
		.whatsapp-widget {
			bottom: 16px;
			right: 16px;
		}
	}

	/* Panel */
	.whatsapp-panel {
		position: absolute;
		bottom: 72px;
		right: 0;
		width: 340px;
		max-width: calc(100vw - 32px);
		background: #ffffff;
		border-radius: 20px;
		box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(0, 0, 0, 0.05);
		overflow: hidden;
		opacity: 0;
		visibility: hidden;
		transform: translateY(10px) scale(0.95);
		transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	}

	.whatsapp-panel.is-open {
		opacity: 1;
		visibility: visible;
		transform: translateY(0) scale(1);
	}

	/* Panel Header */
	.whatsapp-panel-header {
		background: linear-gradient(135deg, #25d366 0%, #128C7E 100%);
		padding: 18px 20px;
		display: flex;
		justify-content: space-between;
		align-items: center;
		position: relative;
		overflow: hidden;
	}

	.whatsapp-panel-header::before {
		content: '';
		position: absolute;
		top: -50%;
		right: -10%;
		width: 150px;
		height: 150px;
		background: rgba(255, 255, 255, 0.1);
		border-radius: 50%;
	}

	.whatsapp-panel-title {
		color: #ffffff;
		font-weight: 600;
		font-size: 17px;
		margin: 0;
		z-index: 1;
	}

	.whatsapp-panel-close {
		background: rgba(255, 255, 255, 0.2);
		border: none;
		width: 32px;
		height: 32px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		cursor: pointer;
		transition: all 0.2s ease;
		z-index: 1;
		color: #ffffff;
		padding: 0;
	}

	.whatsapp-panel-close:hover {
		background: rgba(255, 255, 255, 0.3);
		transform: rotate(90deg);
	}

	/* Panel Body */
	.whatsapp-panel-body {
		padding: 20px;
	}

	.whatsapp-message {
		color: #666;
		font-size: 14px;
		line-height: 1.5;
		margin: 0 0 10px;
	}

	.whatsapp-response {
		color: #999;
		font-size: 13px;
		margin: 0 0 18px;
		display: flex;
		align-items: center;
		gap: 6px;
	}

	.whatsapp-response::before {
		content: '';
		width: 8px;
		height: 8px;
		background: #25d366;
		border-radius: 50%;
		display: inline-block;
		animation: pulse-dot 2s infinite;
	}

	@keyframes pulse-dot {

		0%,
		100% {
			opacity: 1;
		}

		50% {
			opacity: 0.5;
		}
	}

	/* Agent Card */
	.whatsapp-agent {
		background: linear-gradient(135deg, rgba(37, 211, 102, 0.08) 0%, rgba(18, 140, 126, 0.08) 100%);
		padding: 14px;
		border-radius: 12px;
		margin-bottom: 18px;
		border: 1px solid rgba(37, 211, 102, 0.1);
	}

	.whatsapp-agent-label {
		font-size: 11px;
		color: #888;
		text-transform: uppercase;
		letter-spacing: 1.2px;
		margin: 0 0 4px;
		font-weight: 600;
	}

	.whatsapp-agent-name {
		font-weight: 600;
		color: #333;
		font-size: 16px;
		margin: 0;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.whatsapp-agent-name::before {
		content: '👋';
		font-size: 18px;
	}

	/* CTA Button */
	.whatsapp-cta {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 10px;
		width: 100%;
		background: linear-gradient(135deg, #25d366 0%, #128C7E 100%);
		color: #ffffff;
		text-align: center;
		padding: 14px 24px;
		border-radius: 50px;
		font-weight: 600;
		font-size: 15px;
		text-decoration: none;
		transition: all 0.3s ease;
		box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
		border: none;
		cursor: pointer;
	}

	.whatsapp-cta:hover {
		transform: translateY(-2px);
		box-shadow: 0 8px 20px rgba(37, 211, 102, 0.4);
	}

	.whatsapp-cta:active {
		transform: translateY(0);
	}

	/* Float Button */
	.whatsapp-button {
		width: 60px;
		height: 60px;
		border-radius: 50%;
		background: linear-gradient(135deg, #25d366 0%, #128C7E 100%);
		border: none;
		cursor: pointer;
		display: flex;
		align-items: center;
		justify-content: center;
		transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
		box-shadow: 0 8px 24px rgba(37, 211, 102, 0.35);
		animation: whatsapp-pulse 3s infinite;
		position: relative;
		overflow: hidden;
	}

	.whatsapp-button::before {
		content: '';
		position: absolute;
		inset: 0;
		background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
		opacity: 0;
		transition: opacity 0.3s ease;
	}

	.whatsapp-button:hover {
		transform: scale(1.1);
		animation: none;
		box-shadow: 0 12px 32px rgba(37, 211, 102, 0.45);
	}

	.whatsapp-button:hover::before {
		opacity: 1;
	}

	.whatsapp-button:active {
		transform: scale(1.05);
	}

	.whatsapp-button svg {
		position: relative;
		z-index: 2;
		display: block;
		pointer-events: none;
		width: 32px;
		height: 32px;
		flex-shrink: 0;
	}

	@keyframes whatsapp-pulse {

		0%,
		100% {
			transform: scale(1);
			box-shadow: 0 8px 24px rgba(37, 211, 102, 0.35);
		}

		50% {
			transform: scale(1.05);
			box-shadow: 0 8px 24px rgba(37, 211, 102, 0.5), 0 0 0 12px rgba(37, 211, 102, 0);
		}
	}

	/* Badge (optional notification badge) */
	.whatsapp-badge {
		position: absolute;
		top: -4px;
		right: -4px;
		width: 20px;
		height: 20px;
		background: #FF3B30;
		color: #fff;
		border-radius: 50%;
		font-size: 11px;
		font-weight: 700;
		display: flex;
		align-items: center;
		justify-content: center;
		box-shadow: 0 2px 8px rgba(255, 59, 48, 0.4);
		border: 2px solid #ffffff;
		animation: badge-pop 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
	}

	@keyframes badge-pop {
		0% {
			transform: scale(0);
		}

		100% {
			transform: scale(1);
		}
	}
</style>

<div class="whatsapp-widget">
	<!-- Chat Panel -->
	<div class="whatsapp-panel" id="whatsapp-panel">
		<div class="whatsapp-panel-header">
			<h3 class="whatsapp-panel-title">Let's Chat!</h3>
			<button class="whatsapp-panel-close" onclick="toggleWhatsAppPanel()" aria-label="Close">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</button>
		</div>
		<div class="whatsapp-panel-body">
			<p class="whatsapp-message">👋 Hi! Have questions about our artisan cakes?</p>
			<p class="whatsapp-response">We typically reply within minutes</p>

			<div class="whatsapp-agent">
				<p class="whatsapp-agent-label">Chat with</p>
				<p class="whatsapp-agent-name">Melt Team</p>
			</div>

			<a href="https://wa.me/971555507868?text=Hi%21%20I%27d%20like%20to%20chat%20about%20Melt%20by%20Locali%20Gourmet."
				target="_blank"
				rel="noopener noreferrer"
				class="whatsapp-cta">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
				</svg>
				Start Conversation
			</a>
		</div>
	</div>

	<!-- Float Button -->
	<button class="whatsapp-button" id="whatsapp-btn" onclick="toggleWhatsAppPanel()" aria-label="Chat on WhatsApp">
		<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="white">
			<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
		</svg>
	</button>
</div>

<script>
	function toggleWhatsAppPanel() {
		const panel = document.getElementById('whatsapp-panel');
		panel.classList.toggle('is-open');
	}

	// Close panel when clicking outside
	document.addEventListener('click', function(event) {
		const widget = document.querySelector('.whatsapp-widget');
		const panel = document.getElementById('whatsapp-panel');
		const button = document.getElementById('whatsapp-btn');

		if (widget && panel && !widget.contains(event.target)) {
			panel.classList.remove('is-open');
		}
	});

	// Close on ESC key
	document.addEventListener('keydown', function(event) {
		if (event.key === 'Escape') {
			document.getElementById('whatsapp-panel').classList.remove('is-open');
		}
	});
</script>

<?php
// Include auth modal
get_template_part('template-parts/auth-modal');
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