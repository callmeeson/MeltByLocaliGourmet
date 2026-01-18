<?php
/**
 * Registration Diagnostic and Fix Script
 * 
 * This script helps diagnose and fix registration issues:
 * 1. Check for duplicate accounts
 * 2. Verify customer role exists
 * 3. Fix existing users without customer role
 * 4. Test email verification status
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add admin menu for diagnostics
 */
function melt_add_registration_diagnostics_menu() {
    add_submenu_page(
        'users.php',
        'Registration Diagnostics',
        'Registration Diagnostics',
        'manage_options',
        'melt-registration-diagnostics',
        'melt_registration_diagnostics_page'
    );
}
add_action('admin_menu', 'melt_add_registration_diagnostics_menu');

/**
 * Diagnostics page
 */
function melt_registration_diagnostics_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Handle actions
    if (isset($_POST['action'])) {
        check_admin_referer('melt_diagnostics');
        
        if ($_POST['action'] === 'fix_roles') {
            melt_fix_user_roles();
            echo '<div class="notice notice-success"><p>User roles have been updated!</p></div>';
        } elseif ($_POST['action'] === 'delete_duplicates') {
            $deleted = melt_delete_duplicate_users();
            echo '<div class="notice notice-success"><p>Deleted ' . $deleted . ' duplicate accounts!</p></div>';
        }
    }
    
    // Get diagnostics data
    $users_without_role = melt_get_users_without_customer_role();
    $duplicate_emails = melt_find_duplicate_emails();
    $unverified_users = melt_get_unverified_users();
    
    ?>
    <div class="wrap">
        <h1>🔧 Registration Diagnostics</h1>
        
        <!-- Customer Role Check -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <h2>Customer Role Status</h2>
            <?php
            $customer_role = get_role('customer');
            if ($customer_role) {
                echo '<p style="color: #00a32a;">✅ Customer role exists</p>';
                echo '<p><strong>Capabilities:</strong> ' . count($customer_role->capabilities) . ' permissions</p>';
            } else {
                echo '<p style="color: #d63638;">❌ Customer role does NOT exist!</p>';
                echo '<p>WooCommerce may not be installed or activated.</p>';
            }
            ?>
        </div>
        
        <!-- Users Without Customer Role -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <h2>Users Without Customer Role</h2>
            <?php if (empty($users_without_role)): ?>
                <p style="color: #00a32a;">✅ All users have proper roles</p>
            <?php else: ?>
                <p style="color: #f0b849;">⚠️ Found <?php echo count($users_without_role); ?> users without customer role</p>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Current Role</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users_without_role as $user): ?>
                            <tr>
                                <td><?php echo $user->ID; ?></td>
                                <td><?php echo $user->user_email; ?></td>
                                <td><?php echo implode(', ', $user->roles); ?></td>
                                <td><?php echo date('M j, Y', strtotime($user->user_registered)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <form method="post" style="margin-top: 1rem;">
                    <?php wp_nonce_field('melt_diagnostics'); ?>
                    <input type="hidden" name="action" value="fix_roles">
                    <button type="submit" class="button button-primary">Fix User Roles</button>
                </form>
            <?php endif; ?>
        </div>
        
        <!-- Duplicate Emails -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <h2>Duplicate Email Addresses</h2>
            <?php if (empty($duplicate_emails)): ?>
                <p style="color: #00a32a;">✅ No duplicate emails found</p>
            <?php else: ?>
                <p style="color: #d63638;">❌ Found <?php echo count($duplicate_emails); ?> duplicate email addresses</p>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Count</th>
                            <th>User IDs</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($duplicate_emails as $email => $data): ?>
                            <tr>
                                <td><?php echo esc_html($email); ?></td>
                                <td><?php echo $data['count']; ?></td>
                                <td><?php echo implode(', ', $data['user_ids']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <form method="post" style="margin-top: 1rem;">
                    <?php wp_nonce_field('melt_diagnostics'); ?>
                    <input type="hidden" name="action" value="delete_duplicates">
                    <button type="submit" class="button button-secondary">Delete Duplicate Accounts (Keep Oldest)</button>
                    <p style="color: #666; font-size: 0.875rem; margin-top: 0.5rem;">This will keep the oldest account for each email and delete the rest.</p>
                </form>
            <?php endif; ?>
        </div>
        
        <!-- Unverified Users -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <h2>Unverified Email Addresses</h2>
            <?php if (empty($unverified_users)): ?>
                <p style="color: #00a32a;">✅ All users have verified emails</p>
            <?php else: ?>
                <p style="color: #f0b849;">⚠️ Found <?php echo count($unverified_users); ?> unverified users</p>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Registered</th>
                            <th>Token Expires</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($unverified_users as $user): ?>
                            <?php
                            $expires = get_user_meta($user->ID, 'verification_token_expires', true);
                            $expired = $expires && time() > $expires;
                            ?>
                            <tr>
                                <td><?php echo $user->ID; ?></td>
                                <td><?php echo $user->user_email; ?></td>
                                <td><?php echo date('M j, Y g:i A', strtotime($user->user_registered)); ?></td>
                                <td>
                                    <?php if ($expires): ?>
                                        <?php if ($expired): ?>
                                            <span style="color: #d63638;">Expired</span>
                                        <?php else: ?>
                                            <?php echo date('M j, Y g:i A', $expires); ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Get users without customer role
 */
function melt_get_users_without_customer_role() {
    $args = [
        'role__not_in' => ['customer', 'administrator', 'shop_manager'],
        'number' => 100
    ];
    
    return get_users($args);
}

/**
 * Find duplicate email addresses
 */
function melt_find_duplicate_emails() {
    global $wpdb;
    
    $query = "
        SELECT user_email, COUNT(*) as count, GROUP_CONCAT(ID ORDER BY ID) as user_ids
        FROM {$wpdb->users}
        GROUP BY user_email
        HAVING count > 1
    ";
    
    $results = $wpdb->get_results($query);
    $duplicates = [];
    
    foreach ($results as $row) {
        $duplicates[$row->user_email] = [
            'count' => $row->count,
            'user_ids' => explode(',', $row->user_ids)
        ];
    }
    
    return $duplicates;
}

/**
 * Get unverified users
 */
function melt_get_unverified_users() {
    $args = [
        'meta_query' => [
            [
                'key' => 'email_verified',
                'value' => '0',
                'compare' => '='
            ]
        ],
        'number' => 100
    ];
    
    return get_users($args);
}

/**
 * Fix user roles
 */
function melt_fix_user_roles() {
    $users = melt_get_users_without_customer_role();
    
    foreach ($users as $user) {
        // Skip administrators and shop managers
        if (in_array('administrator', $user->roles) || in_array('shop_manager', $user->roles)) {
            continue;
        }
        
        // Set to customer role
        $user->set_role('customer');
    }
}

/**
 * Delete duplicate users (keep oldest)
 */
function melt_delete_duplicate_users() {
    $duplicates = melt_find_duplicate_emails();
    $deleted = 0;
    
    foreach ($duplicates as $email => $data) {
        $user_ids = $data['user_ids'];
        
        // Keep the first (oldest) user, delete the rest
        array_shift($user_ids);
        
        foreach ($user_ids as $user_id) {
            require_once(ABSPATH . 'wp-admin/includes/user.php');
            if (wp_delete_user($user_id)) {
                $deleted++;
            }
        }
    }
    
    return $deleted;
}
