<?php
/**
 * Admin Security Logs Page
 * Displays security logs in WordPress admin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add admin menu for security logs
 */
function melt_add_security_logs_menu() {
    add_menu_page(
        'Security Logs',
        'Security Logs',
        'manage_options',
        'melt-security-logs',
        'melt_security_logs_page',
        'dashicons-shield',
        30
    );
}
add_action('admin_menu', 'melt_add_security_logs_menu');

/**
 * Security logs page content
 */
function melt_security_logs_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Handle export
    if (isset($_GET['action']) && $_GET['action'] === 'export' && check_admin_referer('melt_export_logs')) {
        melt_export_security_logs_csv();
    }
    
    // Get filter parameters
    $event_type = isset($_GET['event_type']) ? sanitize_text_field($_GET['event_type']) : '';
    $days = isset($_GET['days']) ? absint($_GET['days']) : 7;
    $search_email = isset($_GET['search_email']) ? sanitize_email($_GET['search_email']) : '';
    
    // Build query args
    $args = [
        'limit' => 100,
        'offset' => 0
    ];
    
    if (!empty($event_type)) {
        $args['event_type'] = $event_type;
    }
    
    if (!empty($search_email)) {
        $args['email'] = $search_email;
    }
    
    if ($days > 0) {
        $args['date_from'] = date('Y-m-d H:i:s', strtotime("-$days days"));
    }
    
    // Get logs
    $logs = melt_get_security_logs($args);
    $stats = melt_get_security_stats($days);
    
    ?>
    <div class="wrap">
        <h1>🔒 Security Logs</h1>
        
        <!-- Statistics Dashboard -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin: 2rem 0;">
            <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="margin: 0 0 0.5rem; color: #666; font-size: 0.875rem;">Total Events</h3>
                <p style="margin: 0; font-size: 2rem; font-weight: bold; color: #2271b1;"><?php echo number_format($stats['total_events']); ?></p>
                <p style="margin: 0.5rem 0 0; font-size: 0.75rem; color: #999;">Last <?php echo $days; ?> days</p>
            </div>
            
            <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="margin: 0 0 0.5rem; color: #666; font-size: 0.875rem;">Successful Logins</h3>
                <p style="margin: 0; font-size: 2rem; font-weight: bold; color: #00a32a;"><?php echo number_format($stats['login_success']); ?></p>
                <p style="margin: 0.5rem 0 0; font-size: 0.75rem; color: #999;">Authenticated users</p>
            </div>
            
            <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="margin: 0 0 0.5rem; color: #666; font-size: 0.875rem;">Failed Logins</h3>
                <p style="margin: 0; font-size: 2rem; font-weight: bold; color: #d63638;"><?php echo number_format($stats['login_failed']); ?></p>
                <p style="margin: 0.5rem 0 0; font-size: 0.75rem; color: #999;">Failure rate: <?php echo $stats['failure_rate']; ?>%</p>
            </div>
            
            <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="margin: 0 0 0.5rem; color: #666; font-size: 0.875rem;">Blocked Attempts</h3>
                <p style="margin: 0; font-size: 2rem; font-weight: bold; color: #f0b849;"><?php echo number_format($stats['login_blocked']); ?></p>
                <p style="margin: 0.5rem 0 0; font-size: 0.75rem; color: #999;">Rate limited</p>
            </div>
            
            <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="margin: 0 0 0.5rem; color: #666; font-size: 0.875rem;">Password Resets</h3>
                <p style="margin: 0; font-size: 2rem; font-weight: bold; color: #2271b1;"><?php echo number_format($stats['password_reset_request']); ?></p>
                <p style="margin: 0.5rem 0 0; font-size: 0.75rem; color: #999;">Reset requests</p>
            </div>
            
            <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="margin: 0 0 0.5rem; color: #666; font-size: 0.875rem;">New Registrations</h3>
                <p style="margin: 0; font-size: 2rem; font-weight: bold; color: #00a32a;"><?php echo number_format($stats['registration_success']); ?></p>
                <p style="margin: 0.5rem 0 0; font-size: 0.75rem; color: #999;">New users</p>
            </div>
        </div>
        
        <!-- Filters -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
            <form method="get" action="">
                <input type="hidden" name="page" value="melt-security-logs">
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                    <div>
                        <label for="event_type" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Event Type</label>
                        <select name="event_type" id="event_type" style="width: 100%; padding: 0.5rem;">
                            <option value="">All Events</option>
                            <option value="login_success" <?php selected($event_type, 'login_success'); ?>>Login Success</option>
                            <option value="login_failed" <?php selected($event_type, 'login_failed'); ?>>Login Failed</option>
                            <option value="login_blocked" <?php selected($event_type, 'login_blocked'); ?>>Login Blocked</option>
                            <option value="password_reset_request" <?php selected($event_type, 'password_reset_request'); ?>>Password Reset Request</option>
                            <option value="password_reset_success" <?php selected($event_type, 'password_reset_success'); ?>>Password Reset Success</option>
                            <option value="registration_success" <?php selected($event_type, 'registration_success'); ?>>Registration Success</option>
                            <option value="registration_failed" <?php selected($event_type, 'registration_failed'); ?>>Registration Failed</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="days" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Time Period</label>
                        <select name="days" id="days" style="width: 100%; padding: 0.5rem;">
                            <option value="1" <?php selected($days, 1); ?>>Last 24 Hours</option>
                            <option value="7" <?php selected($days, 7); ?>>Last 7 Days</option>
                            <option value="30" <?php selected($days, 30); ?>>Last 30 Days</option>
                            <option value="90" <?php selected($days, 90); ?>>Last 90 Days</option>
                            <option value="0">All Time</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="search_email" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Search Email</label>
                        <input type="email" name="search_email" id="search_email" value="<?php echo esc_attr($search_email); ?>" placeholder="user@example.com" style="width: 100%; padding: 0.5rem;">
                    </div>
                    
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="submit" class="button button-primary">Filter</button>
                        <a href="?page=melt-security-logs" class="button">Reset</a>
                        <a href="<?php echo wp_nonce_url('?page=melt-security-logs&action=export', 'melt_export_logs'); ?>" class="button">Export CSV</a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Logs Table -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="padding: 1rem;">Date/Time</th>
                        <th style="padding: 1rem;">Event Type</th>
                        <th style="padding: 1rem;">Email</th>
                        <th style="padding: 1rem;">IP Address</th>
                        <th style="padding: 1rem;">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="5" style="padding: 2rem; text-align: center; color: #666;">
                                No security logs found for the selected filters.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <?php
                            // Color code by event type
                            $badge_color = '#666';
                            switch ($log->event_type) {
                                case 'login_success':
                                case 'registration_success':
                                case 'password_reset_success':
                                    $badge_color = '#00a32a';
                                    break;
                                case 'login_failed':
                                case 'registration_failed':
                                case 'password_reset_failed':
                                    $badge_color = '#d63638';
                                    break;
                                case 'login_blocked':
                                    $badge_color = '#f0b849';
                                    break;
                            }
                            ?>
                            <tr>
                                <td style="padding: 1rem;">
                                    <?php echo date('M j, Y g:i A', strtotime($log->created_at)); ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <span style="background: <?php echo $badge_color; ?>; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                                        <?php echo esc_html(str_replace('_', ' ', ucwords($log->event_type, '_'))); ?>
                                    </span>
                                </td>
                                <td style="padding: 1rem;">
                                    <?php echo esc_html($log->email ?: '-'); ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <code><?php echo esc_html($log->ip_address); ?></code>
                                </td>
                                <td style="padding: 1rem;">
                                    <?php
                                    if (!empty($log->details)) {
                                        $details = json_decode($log->details, true);
                                        if ($details) {
                                            echo '<small>';
                                            foreach ($details as $key => $value) {
                                                echo '<strong>' . esc_html($key) . ':</strong> ' . esc_html($value) . '<br>';
                                            }
                                            echo '</small>';
                                        }
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <p style="margin-top: 1.5rem; color: #666; font-size: 0.875rem;">
            <strong>Note:</strong> Security logs are automatically deleted after 90 days to maintain database performance.
        </p>
    </div>
    <?php
}
