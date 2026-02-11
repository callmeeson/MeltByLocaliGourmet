<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<header class="site-header" id="masthead">
		<div class="header-container">
			<!-- Mobile Menu Toggle Button -->
			<button class="mobile-menu-toggle" id="mobile-menu-toggle" aria-label="Menu">
				<span class="hamburger-line"></span>
				<span class="hamburger-line"></span>
				<span class="hamburger-line"></span>
				<span class="menu-text">Menu</span>
			</button>

			<!-- Logo -->
			<a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero/logo.png" alt="Melt by Locali Gourmet" class="logo-image">
			</a>

			<!-- Desktop Navigation -->
			<nav class="main-navigation">
				<?php
				if (has_nav_menu('primary')) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_class'     => 'nav-menu',
							'container'      => false,
							'fallback_cb'    => false,
						)
					);
				} else {
					// Default menu if no menu is set
				?>
					<ul class="nav-menu">
						<li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
						<li><a href="<?php echo esc_url(home_url('/shop')); ?>">Shop</a></li>
						<li><a href="<?php echo esc_url(home_url('/custom-cake')); ?>">Custom Cake</a></li>
						<li><a href="<?php echo esc_url(home_url('/about')); ?>">About Melt</a></li>
						<li><a href="<?php echo esc_url(home_url('/contact')); ?>">Contact Us</a></li>
					</ul>
				<?php
				}
				?>
			</nav>

			<!-- Header Icons -->
			<div class="header-icons">
				<!-- Search Icon -->
				<a href="<?php echo esc_url(home_url('/search')); ?>" class="header-icon-btn" aria-label="Search">
					<i data-lucide="search" class="header-icon"></i>
				</a>

				<!-- Location Icon -->
				<button class="header-icon-btn" onclick="openLocations()" aria-label="Locations">
					<i data-lucide="map-pin" class="header-icon"></i>
				</button>

				<!-- User Account Icon with Dropdown -->
				<?php if (is_user_logged_in()) : ?>
					<div class="user-dropdown-wrapper">
						<button class="header-icon-btn user-dropdown-trigger" aria-label="My Account">
							<i data-lucide="user" class="header-icon"></i>
						</button>

						<!-- User Dropdown Menu -->
						<div class="user-dropdown-menu">
							<div class="user-dropdown-header">
								<?php
								$current_user = wp_get_current_user();
								$full_name = trim($current_user->first_name . ' ' . $current_user->last_name);
								if ('' === $full_name) {
									$full_name = $current_user->display_name;
								}
								?>
								<div class="user-welcome">
									<span class="welcome-label">Welcome</span>
									<span class="welcome-name"><?php echo esc_html($full_name); ?></span>
								</div>
							</div>

							<?php
							// Get recent orders with transient caching (5 minutes)
							if (function_exists('wc_get_orders')) {
								$customer_id = get_current_user_id();
								$cache_key = 'melt_user_orders_' . $customer_id;
								$orders = get_transient($cache_key);

								if (false === $orders) {
									$orders = wc_get_orders(array(
										'customer_id' => $customer_id,
										'limit'       => 3,
										'orderby'     => 'date',
										'order'       => 'DESC',
										'status'      => array('wc-pending', 'wc-processing', 'wc-on-hold', 'wc-completed', 'wc-refunded'),
									));
									// Cache for 5 minutes
									set_transient($cache_key, $orders, 5 * MINUTE_IN_SECONDS);
								}

								if (! empty($orders)) :
							?>
									<div class="user-dropdown-divider"></div>
									<div class="recent-orders-section">
										<p class="recent-orders-title">RECENT ORDERS</p>
										<div class="recent-orders-list">
											<?php foreach ($orders as $order) : ?>
												<a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="order-item">
													<div class="order-item-header">
														<div>
															<p class="order-number">#<?php echo esc_html($order->get_order_number()); ?></p>
															<p class="order-date">
																<?php
																$date_created = $order->get_date_created();
																if ($date_created && is_object($date_created) && method_exists($date_created, 'format')) {
																	echo esc_html($date_created->format('M d, Y'));
																} else {
																	echo esc_html('N/A');
																}
																?>
															</p>
														</div>
														<p class="order-total">AED <?php echo esc_html(number_format($order->get_total(), 2)); ?></p>
													</div>
													<div class="order-status status-<?php echo esc_attr($order->get_status()); ?>">
														<?php
														$status = $order->get_status();
														$icons = array(
															'pending'    => 'clock',
															'processing' => 'package',
															'on-hold'    => 'pause-circle',
															'completed'  => 'check-circle',
															'cancelled'  => 'x-circle',
															'refunded'   => 'rotate-ccw',
															'failed'     => 'alert-circle',
														);
														$icon = isset($icons[$status]) ? $icons[$status] : 'package';
														?>
														<i data-lucide="<?php echo esc_attr($icon); ?>" style="width: 14px; height: 14px;"></i>
														<span><?php echo esc_html(wc_get_order_status_name($order->get_status())); ?></span>
													</div>
												</a>
											<?php endforeach; ?>
										</div>
										<?php
										if (function_exists('wc_get_customer_order_count') && function_exists('wc_get_account_endpoint_url')) {
											$total_orders = wc_get_customer_order_count($customer_id);
											if ($total_orders > 3) :
										?>
												<a href="<?php echo esc_url(add_query_arg('tab', 'orders', home_url('/dashboard'))); ?>" class="view-all-orders">
													View All Orders (<?php echo esc_html($total_orders); ?>)
												</a>
										<?php endif;
										}
										?>
									</div>
									<div class="user-dropdown-divider"></div>
							<?php
								endif;
							}
							?>

							<div class="user-dropdown-links">
								<a href="<?php echo esc_url(home_url('/dashboard')); ?>" class="dropdown-link">
									<i data-lucide="layout-dashboard" style="width: 16px; height: 16px;"></i>
									<span>Dashboard</span>
								</a>
								<a href="<?php echo esc_url(home_url('/shop')); ?>" class="dropdown-link">
									<i data-lucide="shopping-bag" style="width: 16px; height: 16px;"></i>
									<span>Shop</span>
								</a>
								<a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="dropdown-link">
									<i data-lucide="log-out" style="width: 16px; height: 16px;"></i>
									<span>Logout</span>
								</a>
							</div>
						</div>
					</div>
				<?php else : ?>
					<div class="user-dropdown-wrapper">
						<button class="header-icon-btn user-dropdown-trigger" aria-label="Account">
							<i data-lucide="user" class="header-icon"></i>
						</button>

						<!-- Guest User Dropdown Menu -->
						<div class="user-dropdown-menu guest-dropdown">
							<div class="guest-dropdown-content">
								<div class="guest-welcome">
									<i data-lucide="user-circle" style="width: 48px; height: 48px; color: var(--primary); margin-bottom: 0.75rem;"></i>
									<h3 style="font-family: var(--font-serif); font-size: 1.25rem; margin: 0 0 0.5rem; color: var(--foreground);">Welcome to Melt</h3>
									<p style="font-family: var(--font-body); font-size: 0.875rem; color: var(--muted-foreground); margin: 0;">Sign in to access your orders and account</p>
								</div>

								<div class="guest-dropdown-buttons">
									<button onclick="openAuthModal('login')" class="guest-btn primary">
										<i data-lucide="user" style="width: 16px; height: 16px;"></i>
										<span>Login / Register</span>
									</button>
								</div>

								<?php /* Divider - simplified for debugging */ ?>
								<div class="user-dropdown-divider"></div>

								<div class="guest-links">
									<a href="<?php echo esc_url(home_url('/shop')); ?>" class="dropdown-link">
										<i data-lucide="shopping-bag" style="width: 16px; height: 16px;"></i>
										<span>Browse Shop</span>
									</a>
									<a href="<?php echo esc_url(home_url('/about')); ?>" class="dropdown-link">
										<i data-lucide="info" style="width: 16px; height: 16px;"></i>
										<span>About Melt</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<!-- Cart Icon -->
				<?php if (function_exists('WC') && function_exists('wc_get_cart_url')) : ?>
					<a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="header-icon-btn relative cart-button" aria-label="Shopping Cart">
						<i data-lucide="shopping-cart" class="header-icon"></i>
						<?php
						$cart_count = melt_get_cart_count();
						if ($cart_count > 0) :
						?>
							<span class="cart-count"><?php echo esc_html($cart_count); ?></span>
						<?php endif; ?>
					</a>
				<?php endif; ?>
			</div>

			<!-- Mobile Header Icons -->
			<div class="mobile-header-icons">
				<!-- Location Icon -->
				<button class="header-icon-btn" onclick="openLocations()" aria-label="Locations">
					<i data-lucide="map-pin" class="header-icon"></i>
				</button>

				<!-- Cart Icon -->
				<?php if (function_exists('WC') && function_exists('wc_get_cart_url')) : ?>
					<a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="header-icon-btn relative cart-button" aria-label="Shopping Cart">
						<i data-lucide="shopping-cart" class="header-icon"></i>
						<?php
						$cart_count = melt_get_cart_count();
						if ($cart_count > 0) :
						?>
							<span class="cart-count"><?php echo esc_html($cart_count); ?></span>
						<?php endif; ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<!-- Mobile Menu Overlay -->
		<div class="mobile-menu-overlay" onclick="closeMobileMenu()"></div>

		<!-- Modern Mobile Sidebar Menu -->
		<div class="mobile-menu">
			<div class="mobile-menu-content">
				<!-- Premium Header -->
				<div class="mobile-menu-header">
					<div class="mobile-menu-header-content">
						<h2>Menu</h2>
						<p>Explore our artisan collection</p>
					</div>
					<button type="button" class="mobile-menu-close" aria-label="Close menu" onclick="closeMobileMenu()">
						<i data-lucide="x"></i>
					</button>
				</div>

				<!-- Search Bar -->
				<div class="mobile-menu-search">
					<i data-lucide="search"></i>
					<input type="text" placeholder="Search products..." aria-label="Search for products">
				</div>

				<!-- Navigation Links -->
				<nav class="mobile-nav">
					<ul class="mobile-nav-menu">
						<li><a href="<?php echo esc_url(home_url('/')); ?>">🏠 Home</a></li>
						<li><a href="<?php echo esc_url(home_url('/shop')); ?>">🛍️ Shop</a></li>
						<li><a href="<?php echo esc_url(home_url('/custom-cake')); ?>">🎂 Custom Cake</a></li>
						<li><a href="<?php echo esc_url(home_url('/about')); ?>">ℹ️ About Melt</a></li>
						<li><a href="<?php echo esc_url(home_url('/contact')); ?>">📧 Contact Us</a></li>
					</ul>
				</nav>

				<div class="mobile-menu-divider"></div>

				<!-- Quick Actions -->
				<div class="mobile-menu-actions">
					<p class="mobile-menu-actions-title">Quick Actions</p>

					<?php if (is_user_logged_in()) : ?>
						<a href="<?php echo esc_url(home_url('/dashboard')); ?>" class="mobile-menu-link">
							<i data-lucide="layout-dashboard"></i>
							<span>My Dashboard</span>
						</a>
						<a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="mobile-menu-link">
							<i data-lucide="log-out"></i>
							<span>Logout</span>
						</a>
					<?php else : ?>
						<button onclick="openAuthModal('login'); closeMobileMenu();" class="mobile-menu-link">
							<i data-lucide="user"></i>
							<span>Login / Register</span>
						</button>
					<?php endif; ?>

					<?php if (function_exists('WC') && function_exists('wc_get_cart_url')) : ?>
						<a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="mobile-menu-link">
							<i data-lucide="shopping-cart"></i>
							<span>View Cart (<?php echo melt_get_cart_count(); ?>)</span>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</header>