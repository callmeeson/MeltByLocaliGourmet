<?php

/**
 * Template Name: Custom Dashboard
 *
 * @package Melt_Custom
 */

// Redirect if not logged in
if (! is_user_logged_in()) {
    wp_redirect(home_url('/my-account'));
    exit;
}

get_header();

$current_user = wp_get_current_user();
$customer_id  = $current_user->ID;

// Get Orders
$orders = wc_get_orders(array(
    'customer_id' => $customer_id,
    'limit'       => -1,
    'orderby'     => 'date',
    'order'       => 'DESC',
    'status'      => array('pending', 'processing', 'on-hold', 'completed', 'cancelled', 'refunded', 'failed'),
));

// Get Addresses
$billing_address = array(
    'first_name' => get_user_meta($customer_id, 'billing_first_name', true),
    'last_name'  => get_user_meta($customer_id, 'billing_last_name', true),
    'company'    => get_user_meta($customer_id, 'billing_company', true),
    'address_1'  => get_user_meta($customer_id, 'billing_address_1', true),
    'address_2'  => get_user_meta($customer_id, 'billing_address_2', true),
    'city'       => get_user_meta($customer_id, 'billing_city', true),
    'state'      => get_user_meta($customer_id, 'billing_state', true),
    'postcode'   => get_user_meta($customer_id, 'billing_postcode', true),
    'country'    => get_user_meta($customer_id, 'billing_country', true),
    'email'      => get_user_meta($customer_id, 'billing_email', true),
    'phone'      => get_user_meta($customer_id, 'billing_phone', true),
);

$shipping_address = array(
    'first_name' => get_user_meta($customer_id, 'shipping_first_name', true),
    'last_name'  => get_user_meta($customer_id, 'shipping_last_name', true),
    'company'    => get_user_meta($customer_id, 'shipping_company', true),
    'address_1'  => get_user_meta($customer_id, 'shipping_address_1', true),
    'address_2'  => get_user_meta($customer_id, 'shipping_address_2', true),
    'city'       => get_user_meta($customer_id, 'shipping_city', true),
    'state'      => get_user_meta($customer_id, 'shipping_state', true),
    'postcode'   => get_user_meta($customer_id, 'shipping_postcode', true),
    'country'    => get_user_meta($customer_id, 'shipping_country', true),
);

// Helper for status colors
function get_status_class($status)
{
    switch ($status) {
        case 'pending':
            return 'status-pending';
        case 'processing':
            return 'status-processing'; // Maps to "preparing"
        case 'on-hold':
            return 'status-on-hold';
        case 'completed':
            return 'status-completed'; // Maps to "delivered"
        case 'cancelled':
            return 'status-cancelled';
        case 'refunded':
            return 'status-refunded';
        case 'failed':
            return 'status-failed';
        default:
            return 'status-default';
    }
}

function get_status_icon($status)
{
    switch ($status) {
        case 'pending':
            return 'clock';
        case 'processing':
            return 'package';
        case 'on-hold':
            return 'pause-circle';
        case 'completed':
            return 'check-circle';
        case 'cancelled':
            return 'x-circle';
        default:
            return 'package';
    }
}
?>

<div class="dashboard-overlay">
    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-content-wrapper">
                <div>
                    <h2 class="dashboard-title">My Account</h2>
                    <p class="dashboard-subtitle">Welcome back, <?php echo esc_html($current_user->display_name); ?></p>
                </div>
                <a href="<?php echo esc_url(home_url()); ?>" class="close-dashboard" aria-label="Close">
                    <i data-lucide="x"></i>
                </a>
            </div>
        </div>

        <div class="dashboard-content-wrapper">
            <!-- Sidebar Navigation -->
            <div class="dashboard-sidebar">
                <nav class="sidebar-nav">
                    <button class="nav-item active" data-tab="overview">
                        <i data-lucide="settings"></i>
                        <span>Overview</span>
                        <i data-lucide="chevron-right" class="chevron"></i>
                    </button>
                    <button class="nav-item" data-tab="orders">
                        <i data-lucide="package"></i>
                        <span>Order History</span>
                        <i data-lucide="chevron-right" class="chevron"></i>
                    </button>
                    <button class="nav-item" data-tab="custom-cakes">
                        <i data-lucide="cake-slice"></i>
                        <span>Custom Cakes</span>
                        <i data-lucide="chevron-right" class="chevron"></i>
                    </button>
                    <button class="nav-item" data-tab="profile">
                        <i data-lucide="user"></i>
                        <span>Profile</span>
                        <i data-lucide="chevron-right" class="chevron"></i>
                    </button>
                    <button class="nav-item" data-tab="addresses">
                        <i data-lucide="map-pin"></i>
                        <span>Addresses</span>
                        <i data-lucide="chevron-right" class="chevron"></i>
                    </button>
                    <button class="nav-item" data-tab="security">
                        <i data-lucide="shield"></i>
                        <span>Security</span>
                        <i data-lucide="chevron-right" class="chevron"></i>
                    </button>
                    <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="nav-item logout-link">
                        <i data-lucide="log-out"></i>
                        <span>Logout</span>
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="dashboard-main">

                <!-- Overview Tab -->
                <div id="tab-overview" class="tab-content active">
                    <h3 class="section-title">Account Overview</h3>

                    <div class="stats-grid">
                        <div class="stat-card orders-stat">
                            <i data-lucide="package" class="stat-icon"></i>
                            <div class="stat-value"><?php echo count($orders); ?></div>
                            <div class="stat-label">Total Orders</div>
                        </div>

                        <div class="stat-card delivered-stat">
                            <i data-lucide="check-circle" class="stat-icon"></i>
                            <div class="stat-value">
                                <?php
                                $delivered = 0;
                                foreach ($orders as $o) {
                                    if ($o->get_status() == 'completed') $delivered++;
                                }
                                echo $delivered;
                                ?>
                            </div>
                            <div class="stat-label">Delivered</div>
                        </div>

                        <div class="stat-card addresses-stat">
                            <i data-lucide="map-pin" class="stat-icon"></i>
                            <div class="stat-value"><?php echo (!empty($billing_address['address_1']) ? 1 : 0) + (!empty($shipping_address['address_1']) ? 1 : 0); ?></div>
                            <div class="stat-label">Saved Addresses</div>
                        </div>
                    </div>

                    <div class="recent-orders-preview">
                        <h4 class="subsection-title">Recent Orders</h4>
                        <?php if (empty($orders)) : ?>
                            <div class="no-data">No orders yet. Start shopping!</div>
                            <?php else :
                            $recent_orders = array_slice($orders, 0, 3);
                            foreach ($recent_orders as $order) : ?>
                                <div class="preview-order-card">
                                    <div class="card-header">
                                        <div>
                                            <div class="order-ref">Order #<?php echo $order->get_order_number(); ?></div>
                                            <div class="order-date"><?php echo $order->get_date_created()->format('M d, Y'); ?></div>
                                        </div>
                                        <div class="status-badge <?php echo get_status_class($order->get_status()); ?>">
                                            <i data-lucide="<?php echo get_status_icon($order->get_status()); ?>"></i>
                                            <span><?php echo wc_get_order_status_name($order->get_status()); ?></span>
                                        </div>
                                    </div>
                                    <div class="order-total-preview"><?php echo $order->get_formatted_order_total(); ?></div>
                                </div>
                        <?php endforeach;
                        endif; ?>
                    </div>
                </div>

                <!-- Orders Tab -->
                <div id="tab-orders" class="tab-content">
                    <h3 class="section-title">Order History</h3>

                    <?php if (empty($orders)) : ?>
                        <div class="empty-state">
                            <i data-lucide="package" class="empty-icon"></i>
                            <p>No orders yet</p>
                        </div>
                    <?php else : ?>
                        <div class="orders-list">
                            <?php foreach ($orders as $order) : ?>
                                <div class="order-card-full">
                                    <div class="order-header-full">
                                        <div class="order-info-group">
                                            <div>
                                                <div class="order-ref">Order <span class="highlight">#<?php echo $order->get_order_number(); ?></span></div>
                                                <div class="order-date">Placed on <?php echo $order->get_date_created()->format('M d, Y'); ?></div>
                                            </div>
                                            <div class="status-badge <?php echo get_status_class($order->get_status()); ?>">
                                                <i data-lucide="<?php echo get_status_icon($order->get_status()); ?>"></i>
                                                <span><?php echo wc_get_order_status_name($order->get_status()); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="order-body">
                                        <div class="order-items">
                                            <?php foreach ($order->get_items() as $item_id => $item) :
                                                $product = $item->get_product();
                                            ?>
                                                <div class="order-item-row">
                                                    <span class="item-name">
                                                        <?php echo $item->get_name(); ?> x<?php echo $item->get_quantity(); ?>
                                                    </span>
                                                    <span class="item-price"><?php echo wc_price($order->get_item_total($item)); ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>

                                        <div class="order-footer-details">
                                            <?php if ($order->get_shipping_address_1()) : ?>
                                                <div class="detail-row">
                                                    <i data-lucide="map-pin"></i>
                                                    <span><?php echo $order->get_shipping_address_1() . ', ' . $order->get_shipping_city(); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <div class="order-total-row">
                                                <span>Total</span>
                                                <span class="total-amount"><?php echo $order->get_formatted_order_total(); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Custom Cakes Tab -->
                <div id="tab-custom-cakes" class="tab-content">
                    <h3 class="section-title">My Custom Cake Orders</h3>
                    <?php
                    $cake_orders = get_posts(array(
                        'post_type' => 'custom_cake',
                        'post_status' => 'publish',
                        'author' => $current_user->ID,
                        'posts_per_page' => -1,
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ));
                    
                    if (empty($cake_orders)) : ?>
                        <div class="empty-state">
                            <i data-lucide="cake-slice" class="empty-icon"></i>
                            <p>You haven't placed any custom cake orders yet.</p>
                            <a href="<?php echo home_url('/custom-cake'); ?>" class="btn-primary-small" style="margin-top: 15px;">Design a Cake</a>
                        </div>
                    <?php else : ?>
                        <div class="orders-list">
                            <?php foreach ($cake_orders as $cake) : 
                                $status_key = get_post_meta($cake->ID, 'melt_cc_status', true);
                                if (!$status_key) $status_key = 'submitted';

                                $status_config = array(
                                    'submitted' => array('label' => 'Submitted', 'icon' => 'clock', 'class' => 'status-processing'),
                                    'viewed'    => array('label' => 'Viewed', 'icon' => 'eye', 'class' => 'status-processing'),
                                    'contacted' => array('label' => 'Contacted', 'icon' => 'message-circle', 'class' => 'status-on-hold'),
                                    'confirmed' => array('label' => 'Confirmed', 'icon' => 'check-circle', 'class' => 'status-completed'),
                                    'completed' => array('label' => 'Completed', 'icon' => 'package-check', 'class' => 'status-completed'),
                                    'cancelled' => array('label' => 'Cancelled', 'icon' => 'x-circle', 'class' => 'status-cancelled'),
                                );

                                $config = isset($status_config[$status_key]) ? $status_config[$status_key] : $status_config['submitted'];
                                
                                $date = get_the_date('M d, Y', $cake->ID);
                                $pref_date = get_post_meta($cake->ID, 'melt_cc_pref_date', true);
                                $flavor = get_post_meta($cake->ID, 'melt_cc_flavor', true);
                            ?>
                            <div class="order-card-full">
                                <div class="order-header-full">
                                    <div class="order-info-group">
                                        <div>
                                            <div class="order-ref">Request <span class="highlight">#<?php echo $cake->ID; ?></span></div>
                                            <div class="order-date">Submitted on <?php echo $date; ?></div>
                                        </div>
                                        <div class="status-badge <?php echo esc_attr($config['class']); ?>">
                                            <i data-lucide="<?php echo esc_attr($config['icon']); ?>"></i>
                                            <span><?php echo esc_html($config['label']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="order-body">
                                    <div class="order-items">
                                        <div class="order-item-row">
                                            <span class="item-name">
                                                <strong>Custom Cake Request</strong><br>
                                                <small>Flavor: <?php echo esc_html($flavor ?: 'Not specified'); ?></small><br>
                                                <small>Preferred Date: <?php echo esc_html($pref_date); ?></small>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Profile Tab -->
                <div id="tab-profile" class="tab-content">
                    <div class="tab-header-flex">
                        <h3 class="section-title">Profile Information</h3>
                        <a href="<?php echo esc_url(wc_customer_edit_account_url()); ?>" class="btn-primary-small">Edit Profile</a>
                    </div>

                    <div class="profile-card">
                        <div class="form-group-static">
                            <label>Display Name</label>
                            <div class="static-value"><?php echo esc_html($current_user->display_name); ?></div>
                        </div>

                        <div class="form-group-static">
                            <label>Email Address</label>
                            <div class="static-value"><?php echo esc_html($current_user->user_email); ?></div>
                        </div>
                        <!-- Note: Standard WP doesn't always store phone in user meta unless Woo added it, checking billing phone -->
                        <?php if (! empty($billing_address['phone'])) : ?>
                            <div class="form-group-static">
                                <label>Phone Number</label>
                                <div class="static-value"><?php echo esc_html($billing_address['phone']); ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Addresses Tab -->
                <div id="tab-addresses" class="tab-content">
                    <div class="tab-header-flex">
                        <h3 class="section-title">Addresses</h3>
                        <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', 'billing')); ?>" class="btn-primary-small">Manage Addresses</a>
                    </div>

                    <div class="addresses-grid">
                        <div class="address-card">
                            <div class="address-header">
                                <div class="address-type">
                                    <i data-lucide="map-pin"></i>
                                    <span>Billing Address</span>
                                </div>
                            </div>
                            <?php if (! empty($billing_address['address_1'])) : ?>
                                <div class="address-details">
                                    <div><?php echo $billing_address['first_name'] . ' ' . $billing_address['last_name']; ?></div>
                                    <div><?php echo $billing_address['address_1']; ?></div>
                                    <?php if ($billing_address['address_2']) echo '<div>' . $billing_address['address_2'] . '</div>'; ?>
                                    <div><?php echo $billing_address['city'] . ', ' . $billing_address['state']; ?></div>
                                    <div><?php echo $billing_address['country']; ?></div>
                                    <div><?php echo $billing_address['phone']; ?></div>
                                </div>
                                <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', 'billing')); ?>" class="edit-address-link">Edit</a>
                            <?php else : ?>
                                <p class="no-address">No billing address set.</p>
                                <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', 'billing')); ?>" class="add-address-link">Add Billing Address</a>
                            <?php endif; ?>
                        </div>

                        <div class="address-card">
                            <div class="address-header">
                                <div class="address-type">
                                    <i data-lucide="map-pin"></i>
                                    <span>Shipping Address</span>
                                </div>
                            </div>
                            <?php if (! empty($shipping_address['address_1'])) : ?>
                                <div class="address-details">
                                    <div><?php echo $shipping_address['first_name'] . ' ' . $shipping_address['last_name']; ?></div>
                                    <div><?php echo $shipping_address['address_1']; ?></div>
                                    <?php if ($shipping_address['address_2']) echo '<div>' . $shipping_address['address_2'] . '</div>'; ?>
                                    <div><?php echo $shipping_address['city'] . ', ' . $shipping_address['state']; ?></div>
                                    <div><?php echo $shipping_address['country']; ?></div>
                                </div>
                                <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', 'shipping')); ?>" class="edit-address-link">Edit</a>
                            <?php else : ?>
                                <p class="no-address">No shipping address set.</p>
                                <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', 'shipping')); ?>" class="add-address-link">Add Shipping Address</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Security Tab -->
                <div id="tab-security" class="tab-content">
                    <h3 class="section-title">Security Settings</h3>

                    <div class="security-card">
                        <div class="security-section">
                            <div class="security-header">
                                <i data-lucide="lock"></i>
                                <h4>Change Password</h4>
                            </div>
                            <p class="security-desc">Update your password to keep your account secure.</p>

                            <form id="change-password-form" class="password-form">
                                <?php wp_nonce_field('melt_change_password', 'password_nonce'); ?>

                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <div class="password-input-wrapper">
                                        <input type="password" id="current_password" name="current_password" required>
                                        <button type="button" class="toggle-password" data-target="current_password">
                                            <i data-lucide="eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <div class="password-input-wrapper">
                                        <input type="password" id="new_password" name="new_password" required minlength="8">
                                        <button type="button" class="toggle-password" data-target="new_password">
                                            <i data-lucide="eye"></i>
                                        </button>
                                    </div>
                                    <div class="password-strength" id="password-strength">
                                        <div class="strength-bar">
                                            <div class="strength-fill"></div>
                                        </div>
                                        <span class="strength-text">Password strength</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="confirm_password">Confirm New Password</label>
                                    <div class="password-input-wrapper">
                                        <input type="password" id="confirm_password" name="confirm_password" required>
                                        <button type="button" class="toggle-password" data-target="confirm_password">
                                            <i data-lucide="eye"></i>
                                        </button>
                                    </div>
                                    <span class="password-match-status" id="password-match-status"></span>
                                </div>

                                <div class="form-message" id="password-message"></div>

                                <button type="submit" class="btn-primary-full" id="change-password-btn">
                                    <span class="btn-text">Update Password</span>
                                    <span class="btn-loading" style="display: none;">
                                        <i data-lucide="loader-2" class="spin"></i>
                                        Updating...
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navItems = document.querySelectorAll('.nav-item[data-tab]');
        const tabContents = document.querySelectorAll('.tab-content');

        function setActiveTab(tabId) {
            // Update Nav
            navItems.forEach(item => {
                if (item.dataset.tab === tabId) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });

            // Update Content
            tabContents.forEach(content => {
                if (content.id === 'tab-' + tabId) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });
        }

        function getInitialTab() {
            const params = new URLSearchParams(window.location.search);
            const tabFromQuery = params.get('tab');
            if (tabFromQuery) {
                return tabFromQuery;
            }

            if (window.location.hash) {
                return window.location.hash.replace('#', '');
            }

            return null;
        }

        navItems.forEach(item => {
            item.addEventListener('click', () => {
                const tabId = item.dataset.tab;
                setActiveTab(tabId);
            });
        });

        const initialTab = getInitialTab();
        if (initialTab) {
            setActiveTab(initialTab);
        }

        // Initialize Lucide icons
        lucide.createIcons();

        // Password Change Functionality
        const passwordForm = document.getElementById('change-password-form');
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const passwordMessage = document.getElementById('password-message');
        const passwordMatchStatus = document.getElementById('password-match-status');
        const strengthFill = document.querySelector('.strength-fill');
        const strengthText = document.querySelector('.strength-text');

        // Password visibility toggle
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.setAttribute('data-lucide', 'eye-off');
                } else {
                    input.type = 'password';
                    icon.setAttribute('data-lucide', 'eye');
                }
                lucide.createIcons();
            });
        });

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            return strength;
        }

        if (newPasswordInput) {
            newPasswordInput.addEventListener('input', function() {
                const password = this.value;
                const strength = checkPasswordStrength(password);

                let width = '0%';
                let color = '#e74c3c';
                let text = 'Very weak';

                switch (strength) {
                    case 1:
                        width = '20%';
                        color = '#e74c3c';
                        text = 'Weak';
                        break;
                    case 2:
                        width = '40%';
                        color = '#f39c12';
                        text = 'Fair';
                        break;
                    case 3:
                        width = '60%';
                        color = '#f1c40f';
                        text = 'Good';
                        break;
                    case 4:
                        width = '80%';
                        color = '#27ae60';
                        text = 'Strong';
                        break;
                    case 5:
                        width = '100%';
                        color = '#27ae60';
                        text = 'Very strong';
                        break;
                }

                if (password.length === 0) {
                    text = 'Password strength';
                    width = '0%';
                }

                strengthFill.style.width = width;
                strengthFill.style.backgroundColor = color;
                strengthText.textContent = text;

                // Check match if confirm has value
                if (confirmPasswordInput.value) {
                    checkPasswordMatch();
                }
            });
        }

        // Password match checker
        function checkPasswordMatch() {
            if (!confirmPasswordInput.value) {
                passwordMatchStatus.textContent = '';
                return;
            }

            if (newPasswordInput.value === confirmPasswordInput.value) {
                passwordMatchStatus.textContent = '✓ Passwords match';
                passwordMatchStatus.style.color = '#27ae60';
            } else {
                passwordMatchStatus.textContent = '✗ Passwords do not match';
                passwordMatchStatus.style.color = '#e74c3c';
            }
        }

        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        }

        // Form submission
        if (passwordForm) {
            passwordForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const btn = document.getElementById('change-password-btn');
                const btnText = btn.querySelector('.btn-text');
                const btnLoading = btn.querySelector('.btn-loading');

                // Validate passwords match
                if (newPasswordInput.value !== confirmPasswordInput.value) {
                    showMessage('Passwords do not match.', 'error');
                    return;
                }

                // Show loading state
                btnText.style.display = 'none';
                btnLoading.style.display = 'inline-flex';
                btn.disabled = true;

                const formData = new FormData(this);
                formData.append('action', 'melt_change_password');

                fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showMessage(data.data.message, 'success');
                            passwordForm.reset();
                            strengthFill.style.width = '0%';
                            strengthText.textContent = 'Password strength';
                            passwordMatchStatus.textContent = '';
                        } else {
                            showMessage(data.data.message, 'error');
                        }
                    })
                    .catch(error => {
                        showMessage('An error occurred. Please try again.', 'error');
                    })
                    .finally(() => {
                        btnText.style.display = 'inline';
                        btnLoading.style.display = 'none';
                        btn.disabled = false;
                        lucide.createIcons();
                    });
            });
        }

        function showMessage(message, type) {
            passwordMessage.textContent = message;
            passwordMessage.className = 'form-message ' + type;

            // Auto-hide after 5 seconds
            setTimeout(() => {
                passwordMessage.textContent = '';
                passwordMessage.className = 'form-message';
            }, 5000);
        }
    });
</script>

<?php get_footer(); ?>
