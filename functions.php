<?php

/**
 * Melt Custom Theme Functions
 *
 * @package Melt_Custom
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

// Disable WordPress.com script concatenation (causes duplicate declarations)
if (! defined('CONCATENATE_SCRIPTS')) {
	define('CONCATENATE_SCRIPTS', false);
}

// Theme version
define('MELT_VERSION', '1.0.4');

// Include email verification system
require_once get_template_directory() . '/inc/email-verification.php';

// Include PHPMailer configuration
require_once get_template_directory() . '/inc/phpmailer-config.php';

// Include email testing tool
require_once get_template_directory() . '/inc/email-test.php';

// Include security logger
require_once get_template_directory() . '/inc/security-logger.php';

// Include password reset functionality
require_once get_template_directory() . '/inc/password-reset.php';

// Include admin security logs page
require_once get_template_directory() . '/inc/admin-security-logs.php';

// Include registration diagnostics
require_once get_template_directory() . '/inc/registration-diagnostics.php';

// Include custom WooCommerce logic
require_once get_template_directory() . '/inc/woocommerce-custom.php';

// Ensure WooCommerce shop is visible to all users (not just logged-in)
add_filter('woocommerce_prevent_automatic_wizard_redirect', '__return_true');
add_filter('option_woocommerce_catalog_visibility', function ($value) {
	return ''; // Empty = visible to all
});
add_filter('woocommerce_product_is_visible', '__return_true', 99);

// WordPress.com specific fixes
if (defined('IS_WPCOM') && constant('IS_WPCOM')) {
	// Disable asset minification that causes chunk errors
	add_filter('jetpack_enable_minify', '__return_false');
	add_filter('jetpack_photon_skip_for_url', '__return_true');

	// Prevent WordPress.com from optimizing theme assets
	add_filter('wpcom_asset_minification_enabled', '__return_false');

	// Force original asset URLs
	add_filter('style_loader_src', function ($src) {
		return remove_query_arg('minify', $src);
	}, 10, 1);

	add_filter('script_loader_src', function ($src) {
		return remove_query_arg('minify', $src);
	}, 10, 1);
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function melt_setup()
{
	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	// Let WordPress manage the document title.
	add_theme_support('title-tag');

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus(
		array(
			'primary' => __('Primary Menu', 'melt-custom'),
			'footer'  => __('Footer Menu', 'melt-custom'),
		)
	);

	// Switch default core markup to output valid HTML5.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	// Add support for editor styles.
	add_theme_support('editor-styles');

	// Add support for WooCommerce
	add_theme_support('woocommerce');
	add_theme_support('wc-product-gallery-zoom');
	add_theme_support('wc-product-gallery-lightbox');
	add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'melt_setup');

/**
 * Enqueue scripts and styles.
 */
function melt_scripts()
{
	// Theme stylesheet - Force loading with high priority
	wp_enqueue_style('melt-style', get_stylesheet_uri(), array(), MELT_VERSION, 'all');

	// Responsive styles
	wp_enqueue_style('melt-responsive', get_template_directory_uri() . '/css/responsive.css', array('melt-style'), MELT_VERSION, 'all');

	// User dropdown styles
	wp_enqueue_style('melt-user-dropdown', get_template_directory_uri() . '/css/user-dropdown.css', array('melt-style'), MELT_VERSION, 'all');

	// Custom JavaScript - Critical, loads normally
	wp_enqueue_script('melt-main', get_template_directory_uri() . '/js/main.js', array(), MELT_VERSION, true);

	// Toast notifications - Can be deferred
	wp_enqueue_script('melt-toast', get_template_directory_uri() . '/js/toast.js', array(), MELT_VERSION, true);
	wp_script_add_data('melt-toast', 'strategy', 'defer');

	// Auth modal scripts - Only load if user is NOT logged in
	if (! is_user_logged_in()) {
		// Auth modal script (CRITICAL: Must load before enhancements)
		wp_enqueue_script('melt-auth-modal', get_template_directory_uri() . '/js/auth-modal.js', array('jquery', 'melt-toast'), MELT_VERSION, true);

		// Auth modal enhancements (password reset & remaining attempts)
		wp_enqueue_script('melt-auth-enhancements', get_template_directory_uri() . '/js/auth-modal-enhancements.js', array('melt-auth-modal'), MELT_VERSION, true);
		wp_script_add_data('melt-auth-enhancements', 'strategy', 'defer');

		// Auth loading spinner
		wp_enqueue_script('melt-auth-spinner', get_template_directory_uri() . '/js/auth-loading-spinner.js', array('melt-auth-modal'), MELT_VERSION, true);
		wp_script_add_data('melt-auth-spinner', 'strategy', 'defer');
	}

	// Lucide Icons (SVG icon library) - Can be deferred
	wp_enqueue_script('lucide-icons', 'https://unpkg.com/lucide@latest', array(), null, true);
	wp_script_add_data('lucide-icons', 'strategy', 'defer');

	// Customize Modal Script - Only on shop pages
	if (is_page_template('page-shop.php') || is_shop() || is_product_taxonomy()) {
		wp_enqueue_script('melt-customize-modal', get_template_directory_uri() . '/js/customize-modal.js', array('melt-main'), MELT_VERSION, true);
	}

	// WooCommerce Add to Cart AJAX Script
	if (class_exists('WooCommerce')) {
		wp_enqueue_script('wc-add-to-cart');

		// Add to Cart Toast Notification
		wp_enqueue_script('melt-add-to-cart-toast', get_template_directory_uri() . '/js/add-to-cart-toast.js', array('jquery', 'wc-add-to-cart'), MELT_VERSION, true);
		wp_script_add_data('melt-add-to-cart-toast', 'strategy', 'defer');
	}

	// Mobile Menu
	wp_enqueue_style('melt-mobile-menu', get_template_directory_uri() . '/css/mobile-menu.css', array(), MELT_VERSION);
	wp_enqueue_script('melt-mobile-menu', get_template_directory_uri() . '/js/mobile-menu.js', array(), MELT_VERSION, true);

	// Thank You Page Styles - Only load on order-received page
	if (is_wc_endpoint_url('order-received')) {
		wp_enqueue_style('melt-thankyou', get_template_directory_uri() . '/css/thankyou.css', array('melt-style'), MELT_VERSION, 'all');
	}

	// Localize script for AJAX
	wp_localize_script(
		'melt-main',
		'meltAjax',
		array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce'   => wp_create_nonce('melt_nonce'),
		)
	);
}
add_action('wp_enqueue_scripts', 'melt_scripts', 5); // Priority 5 to load early

/**
 * Centralized error logging function
 * 
 * @param string $context Context of the error (e.g., 'AJAX', 'Email', 'WooCommerce')
 * @param string $message Error message
 * @param array  $data Additional data to log
 * @return void
 */
function melt_log_error($context, $message, $data = array())
{
	if (defined('WP_DEBUG') && WP_DEBUG) {
		$log_message = sprintf(
			'[Melt Theme - %s] %s',
			$context,
			$message
		);

		if (! empty($data)) {
			$log_message .= ' | Data: ' . wp_json_encode($data);
		}

		error_log($log_message);
	}
}

/**
 * Add critical CSS via inline style system
 */
function melt_inline_critical_css()
{
	$css = "
		:root, html, body {
			--primary: #B8860B !important;
			--accent: #DAA520 !important;
			--background: #FFFFFF !important;
			--foreground: #1A1A1A !important;
		}
		.hero-button.primary { background-color: #B8860B; }
	";
	wp_add_inline_style('melt-style', $css);
}
add_action('wp_enqueue_scripts', 'melt_inline_critical_css', 20);

/**
 * Register widget area.
 */
function melt_widgets_init()
{
	register_sidebar(
		array(
			'name'          => __('Sidebar', 'melt-custom'),
			'id'            => 'sidebar-1',
			'description'   => __('Add widgets here.', 'melt-custom'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'melt_widgets_init');

/**
 * Add body classes based on page template
 */
function melt_body_classes($classes)
{
	// Add class if it's the home page
	if (is_front_page()) {
		$classes[] = 'home-page';
	}

	return $classes;
}
add_filter('body_class', 'melt_body_classes');

/**
 * Custom excerpt length
 */
function melt_excerpt_length($length)
{
	return 20;
}
add_filter('excerpt_length', 'melt_excerpt_length');

/**
 * Custom excerpt more
 */
function melt_excerpt_more($more)
{
	return '...';
}
add_filter('excerpt_more', 'melt_excerpt_more');

/**
 * Register Custom Post Type for Hero Slides
 */
function melt_register_hero_slides()
{
	$labels = array(
		'name'               => 'Hero Slides',
		'singular_name'      => 'Hero Slide',
		'menu_name'          => 'Hero Slides',
		'add_new'            => 'Add New Slide',
		'add_new_item'       => 'Add New Hero Slide',
		'edit_item'          => 'Edit Hero Slide',
		'new_item'           => 'New Hero Slide',
		'view_item'          => 'View Hero Slide',
		'search_items'       => 'Search Hero Slides',
		'not_found'          => 'No hero slides found',
		'not_found_in_trash' => 'No hero slides found in trash',
	);

	$args = array(
		'labels'              => $labels,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-images-alt2',
		'supports'            => array('title', 'thumbnail'),
		'menu_position'       => 5,
		'publicly_queryable'  => false,
		'exclude_from_search' => true,
	);

	register_post_type('hero_slide', $args);
}
add_action('init', 'melt_register_hero_slides');

/**
 * Add meta boxes for hero slides
 */
function melt_add_hero_slide_meta_boxes()
{
	add_meta_box(
		'hero_slide_details',
		'Slide Details',
		'melt_hero_slide_meta_box_callback',
		'hero_slide',
		'normal',
		'high'
	);
}
add_action('add_meta_boxes', 'melt_add_hero_slide_meta_boxes');

/**
 * Hero slide meta box callback
 */
function melt_hero_slide_meta_box_callback($post)
{
	wp_nonce_field('melt_save_hero_slide_meta', 'melt_hero_slide_nonce');

	$subtitle = get_post_meta($post->ID, '_hero_slide_subtitle', true);
?>
	<p>
		<label for="hero_slide_subtitle"><strong>Subtitle:</strong></label><br>
		<textarea id="hero_slide_subtitle" name="hero_slide_subtitle" rows="3" style="width: 100%;"><?php echo esc_textarea($subtitle); ?></textarea>
	</p>
	<p>
		<em>Note: Set the featured image as the slide background image.</em>
	</p>
<?php
}

/**
 * Save hero slide meta data
 */
function melt_save_hero_slide_meta($post_id)
{
	// Check nonce
	if (! isset($_POST['melt_hero_slide_nonce']) || ! wp_verify_nonce($_POST['melt_hero_slide_nonce'], 'melt_save_hero_slide_meta')) {
		return;
	}

	// Check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	// Check permissions
	if (! current_user_can('edit_post', $post_id)) {
		return;
	}

	// Save subtitle
	if (isset($_POST['hero_slide_subtitle'])) {
		update_post_meta($post_id, '_hero_slide_subtitle', sanitize_textarea_field($_POST['hero_slide_subtitle']));
	}
}
add_action('save_post_hero_slide', 'melt_save_hero_slide_meta');

/**
 * Get hero slides
 */
function melt_get_hero_slides()
{
	$args = array(
		'post_type'      => 'hero_slide',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	);

	$slides = get_posts($args);

	// Default slides if none exist
	if (empty($slides)) {
		return array(
			array(
				'image'    => 'https://images.unsplash.com/photo-1640794334523-b299f14d28db?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxsdXh1cnklMjBjaG9jb2xhdGUlMjBjYWtlfGVufDF8fHx8MTc2NzU5OTE0MXww&ixlib=rb-4.1.0&q=80&w=1080',
				'title'    => 'Artisan Patisserie',
				'subtitle' => 'Handcrafted confections made with premium ingredients and timeless techniques',
			),
			array(
				'image'    => 'https://images.unsplash.com/photo-1740594967618-23cd757b9291?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxlbGVnYW50JTIwdmFuaWxsYSUyMGNha2V8ZW58MXx8fHwxNzY3NTk5NTYxfDA&ixlib=rb-4.1.0&q=80&w=1080',
				'title'    => 'Elegant Creations',
				'subtitle' => 'Exquisite cakes designed to elevate your special moments',
			),
			array(
				'image'    => 'https://images.unsplash.com/photo-1763161693167-d5c117505cde?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxnb3VybWV0JTIwc3RyYXdiZXJyeSUyMGNha2V8ZW58MXx8fHwxNzY3NTk5NTYyfDA&ixlib=rb-4.1.0&q=80&w=1080',
				'title'    => 'Fresh & Delicious',
				'subtitle' => 'Premium ingredients sourced daily for the finest flavors',
			),
			array(
				'image'    => 'https://images.unsplash.com/photo-1666453579043-fef25e9ce099?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxhcnRpc2FuJTIwbGF5ZXJlZCUyMGNha2V8ZW58MXx8fHwxNzY3NTk5NTYzfDA&ixlib=rb-4.1.0&q=80&w=1080',
				'title'    => 'Layered Perfection',
				'subtitle' => 'Each layer crafted with precision and passion',
			),
		);
	}

	$result = array();
	foreach ($slides as $slide) {
		$image = get_the_post_thumbnail_url($slide->ID, 'full');
		if (! $image) {
			$image = 'https://images.unsplash.com/photo-1640794334523-b299f14d28db?w=1080';
		}

		$result[] = array(
			'image'    => $image,
			'title'    => get_the_title($slide->ID),
			'subtitle' => get_post_meta($slide->ID, '_hero_slide_subtitle', true),
		);
	}

	return $result;
}

/**
 * AJAX handler for cart updates
 */
function melt_ajax_update_cart()
{
	// Check nonce with expiration handling
	$nonce_check = check_ajax_referer('melt_nonce', 'nonce', false);
	if (! $nonce_check) {
		wp_send_json_error(array(
			'message' => 'Your session has expired. Please refresh the page and try again.',
			'nonce_expired' => true
		));
	}

	if (! function_exists('WC')) {
		wp_send_json_error(array('message' => 'WooCommerce is not active'));
	}

	$product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
	$quantity   = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;

	if ($product_id > 0) {
		WC()->cart->add_to_cart($product_id, $quantity);
		wp_send_json_success(
			array(
				'message'    => 'Product added to cart',
				'cart_count' => WC()->cart->get_cart_contents_count(),
			)
		);
	}

	wp_send_json_error(array('message' => 'Invalid product'));
}
add_action('wp_ajax_melt_update_cart', 'melt_ajax_update_cart');
add_action('wp_ajax_nopriv_melt_update_cart', 'melt_ajax_update_cart');

/**
 * Get cart count for header
 */
function melt_get_cart_count()
{
	if (function_exists('WC')) {
		return WC()->cart->get_cart_contents_count();
	}
	return 0;
}

/**
 * Clear user orders cache when a new order is created
 */
function melt_clear_user_orders_cache($order_id)
{
	$order = wc_get_order($order_id);
	if ($order) {
		$customer_id = $order->get_customer_id();
		if ($customer_id) {
			delete_transient('melt_user_orders_' . $customer_id);
		}
	}
}
add_action('woocommerce_new_order', 'melt_clear_user_orders_cache');
add_action('woocommerce_update_order', 'melt_clear_user_orders_cache');

/**
 * Add lazy loading to images
 */
function melt_add_lazy_loading($content)
{
	if (is_admin()) {
		return $content;
	}
	// Add loading="lazy" to all img tags
	$content = preg_replace('/<img((?![^>]*loading=)[^>]*)>/i', '<img loading="lazy"$1>', $content);
	return $content;
}
add_filter('the_content', 'melt_add_lazy_loading', 20);
add_filter('post_thumbnail_html', 'melt_add_lazy_loading', 20);
add_filter('widget_text', 'melt_add_lazy_loading', 20);

/**
 * Clean up expired transients daily
 */
function melt_cleanup_transients()
{
	global $wpdb;
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_%' AND option_value < UNIX_TIMESTAMP()");
	melt_log_error('Maintenance', 'Cleaned up expired transients');
}
add_action('wp_scheduled_delete', 'melt_cleanup_transients');

/**
 * Custom WooCommerce support
 */
if (class_exists('WooCommerce')) {
	// Remove default WooCommerce styles
	add_filter('woocommerce_enqueue_styles', '__return_empty_array');

	// Remove WooCommerce breadcrumbs
	remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

	// Remove default WooCommerce wrappers
	remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

	// Add custom wrappers
	add_action('woocommerce_before_main_content', 'melt_woocommerce_wrapper_start', 10);
	add_action('woocommerce_after_main_content', 'melt_woocommerce_wrapper_end', 10);

	function melt_woocommerce_wrapper_start()
	{
		echo '<div class="section"><div class="section-container">';
	}

	function melt_woocommerce_wrapper_end()
	{
		echo '</div></div>';
	}
}

/**
 * AJAX handler for password change
 */
function melt_ajax_change_password()
{
	// Verify nonce
	if (! isset($_POST['password_nonce']) || ! wp_verify_nonce($_POST['password_nonce'], 'melt_change_password')) {
		wp_send_json_error(array('message' => 'Security check failed. Please refresh the page and try again.'));
	}

	// Check if user is logged in
	if (! is_user_logged_in()) {
		wp_send_json_error(array('message' => 'You must be logged in to change your password.'));
	}

	$current_user = wp_get_current_user();
	$current_password = isset($_POST['current_password']) ? $_POST['current_password'] : '';
	$new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
	$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

	// Validate inputs
	if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
		wp_send_json_error(array('message' => 'All password fields are required.'));
	}

	// Verify current password
	if (! wp_check_password($current_password, $current_user->user_pass, $current_user->ID)) {
		wp_send_json_error(array('message' => 'Current password is incorrect.'));
	}

	// Check if new passwords match
	if ($new_password !== $confirm_password) {
		wp_send_json_error(array('message' => 'New passwords do not match.'));
	}

	// Validate password strength (minimum 8 characters)
	if (strlen($new_password) < 8) {
		wp_send_json_error(array('message' => 'New password must be at least 8 characters long.'));
	}

	// Check if new password is same as current
	if (wp_check_password($new_password, $current_user->user_pass, $current_user->ID)) {
		wp_send_json_error(array('message' => 'New password must be different from current password.'));
	}

	// Update password
	wp_set_password($new_password, $current_user->ID);

	// Log the password change
	melt_log_error('Security', 'Password changed for user', array('user_id' => $current_user->ID));

	// Re-authenticate the user so they don't get logged out
	wp_set_auth_cookie($current_user->ID, true);

	wp_send_json_success(array('message' => 'Password updated successfully!'));
}
add_action('wp_ajax_melt_change_password', 'melt_ajax_change_password');

/**
 * Fix Login URL Loop
 * Ensures login URL points to wp-login.php if it seems to be pointing to the homepage
 */
function melt_fix_login_url($login_url, $redirect, $force_reauth)
{
	// If login URL does not contain 'wp-login.php', forces it back.
	// This catches cases where it was renamed to homepage or something else.
	// Also ignore if it is checking for admin (sometimes valid) but here we focus on frontend loop.
	if (strpos($login_url, 'wp-login.php') === false && strpos($login_url, 'wp-admin') === false) {
		$login_url = site_url('wp-login.php', 'login');
		if (!empty($redirect)) {
			$login_url = add_query_arg('redirect_to', urlencode($redirect), $login_url);
		}
	}
	return $login_url;
}
add_filter('login_url', 'melt_fix_login_url', 999, 3);

/**
 * Break Redirect Loop
 * Detects redirect_to parameters on the homepage and sanitizes them
 */
function melt_break_redirect_loop()
{
	// Only run on front page/home
	if (is_front_page() || is_home()) {
		// Check if redirect_to is present in the request
		if (isset($_GET['redirect_to'])) {
			// Unconditionally break ANY redirect_to on the homepage
			// Homepage should not have a redirect_to parameter normally
			// This is the safest way to stop the loop
			wp_redirect(remove_query_arg('redirect_to', home_url()));
			exit;
		}
	}
}
add_action('template_redirect', 'melt_break_redirect_loop', 1);
