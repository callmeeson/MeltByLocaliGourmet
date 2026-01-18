<?php
/**
 * Security Logger
 * Tracks authentication events and security-related activities
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create security logs table
 */
function melt_create_security_log_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'melt_security_logs';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        event_type VARCHAR(50) NOT NULL,
        user_id BIGINT(20) UNSIGNED DEFAULT 0,
        email VARCHAR(100) DEFAULT '',
        ip_address VARCHAR(45) NOT NULL,
        user_agent VARCHAR(255) DEFAULT '',
        details TEXT,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY event_type (event_type),
        KEY user_id (user_id),
        KEY email (email),
        KEY ip_address (ip_address),
        KEY created_at (created_at)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Create table on theme activation
add_action('after_switch_theme', 'melt_create_security_log_table');

/**
 * Log security event
 * 
 * @param string $event_type Event type (login_success, login_failed, etc.)
 * @param int $user_id WordPress user ID (0 for failed attempts)
 * @param string $email Email address used
 * @param array $details Additional details to log
 * @return bool Success status
 */
function melt_log_security_event($event_type, $user_id = 0, $email = '', $details = []) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'melt_security_logs';
    
    // Get IP address
    $ip_address = melt_get_user_ip();
    
    // Get user agent
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';
    
    // Prepare data
    $data = [
        'event_type' => sanitize_text_field($event_type),
        'user_id' => absint($user_id),
        'email' => sanitize_email($email),
        'ip_address' => sanitize_text_field($ip_address),
        'user_agent' => $user_agent,
        'details' => !empty($details) ? wp_json_encode($details) : '',
        'created_at' => current_time('mysql')
    ];
    
    // Insert log
    $result = $wpdb->insert($table_name, $data);
    
    return $result !== false;
}

/**
 * Get user IP address
 * 
 * @return string IP address
 */
function melt_get_user_ip() {
    $ip = '';
    
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    // Handle multiple IPs (proxy)
    if (strpos($ip, ',') !== false) {
        $ip = explode(',', $ip)[0];
    }
    
    return sanitize_text_field(trim($ip));
}

/**
 * Get security logs
 * 
 * @param array $args Query arguments
 * @return array Security logs
 * 
 * Security: All inputs are validated and sanitized before use in SQL queries
 */
function melt_get_security_logs($args = []) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'melt_security_logs';
    
    // Default arguments
    $defaults = [
        'event_type' => '',
        'user_id' => 0,
        'email' => '',
        'ip_address' => '',
        'date_from' => '',
        'date_to' => '',
        'limit' => 100,
        'offset' => 0,
        'orderby' => 'created_at',
        'order' => 'DESC'
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    // Validate and sanitize all inputs
    $args['event_type'] = sanitize_text_field($args['event_type']);
    $args['user_id'] = absint($args['user_id']);
    $args['email'] = sanitize_email($args['email']);
    $args['ip_address'] = sanitize_text_field($args['ip_address']);
    $args['limit'] = absint($args['limit']);
    $args['offset'] = absint($args['offset']);
    
    // Validate date formats (YYYY-MM-DD HH:MM:SS)
    if (!empty($args['date_from'])) {
        $args['date_from'] = sanitize_text_field($args['date_from']);
        if (!preg_match('/^\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2})?$/', $args['date_from'])) {
            $args['date_from'] = ''; // Invalid format, ignore
        }
    }
    if (!empty($args['date_to'])) {
        $args['date_to'] = sanitize_text_field($args['date_to']);
        if (!preg_match('/^\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2})?$/', $args['date_to'])) {
            $args['date_to'] = ''; // Invalid format, ignore
        }
    }
    
    // Enforce maximum limit
    if ($args['limit'] > 1000) {
        $args['limit'] = 1000;
    }
    if ($args['limit'] < 1) {
        $args['limit'] = 100;
    }
    
    // Build query
    $where = ['1=1'];
    $where_values = [];
    
    if (!empty($args['event_type'])) {
        $where[] = 'event_type = %s';
        $where_values[] = $args['event_type'];
    }
    
    if (!empty($args['user_id'])) {
        $where[] = 'user_id = %d';
        $where_values[] = $args['user_id'];
    }
    
    if (!empty($args['email'])) {
        $where[] = 'email LIKE %s';
        $where_values[] = '%' . $wpdb->esc_like($args['email']) . '%';
    }
    
    if (!empty($args['ip_address'])) {
        $where[] = 'ip_address = %s';
        $where_values[] = $args['ip_address'];
    }
    
    if (!empty($args['date_from'])) {
        $where[] = 'created_at >= %s';
        $where_values[] = $args['date_from'];
    }
    
    if (!empty($args['date_to'])) {
        $where[] = 'created_at <= %s';
        $where_values[] = $args['date_to'];
    }
    
    $where_clause = implode(' AND ', $where);
    
    // Sanitize orderby and order (whitelist only)
    $allowed_orderby = ['id', 'event_type', 'user_id', 'email', 'ip_address', 'created_at'];
    $orderby = in_array($args['orderby'], $allowed_orderby, true) ? $args['orderby'] : 'created_at';
    $order = strtoupper($args['order']) === 'ASC' ? 'ASC' : 'DESC';
    
    // Build final query
    $query = "SELECT * FROM $table_name WHERE $where_clause ORDER BY $orderby $order LIMIT %d OFFSET %d";
    $where_values[] = $args['limit'];
    $where_values[] = $args['offset'];
    
    if (!empty($where_values)) {
        $query = $wpdb->prepare($query, $where_values);
    }
    
    return $wpdb->get_results($query);
}

/**
 * Get security log statistics
 * 
 * @param int $days Number of days to look back
 * @return array Statistics
 */
function melt_get_security_stats($days = 30) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'melt_security_logs';
    $date_from = date('Y-m-d H:i:s', strtotime("-$days days"));
    
    $stats = [];
    
    // Total events
    $stats['total_events'] = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE created_at >= %s",
        $date_from
    ));
    
    // Events by type
    $events_by_type = $wpdb->get_results($wpdb->prepare(
        "SELECT event_type, COUNT(*) as count FROM $table_name WHERE created_at >= %s GROUP BY event_type",
        $date_from
    ), OBJECT_K);
    
    $stats['login_success'] = isset($events_by_type['login_success']) ? $events_by_type['login_success']->count : 0;
    $stats['login_failed'] = isset($events_by_type['login_failed']) ? $events_by_type['login_failed']->count : 0;
    $stats['login_blocked'] = isset($events_by_type['login_blocked']) ? $events_by_type['login_blocked']->count : 0;
    $stats['password_reset_request'] = isset($events_by_type['password_reset_request']) ? $events_by_type['password_reset_request']->count : 0;
    $stats['password_reset_success'] = isset($events_by_type['password_reset_success']) ? $events_by_type['password_reset_success']->count : 0;
    $stats['registration_success'] = isset($events_by_type['registration_success']) ? $events_by_type['registration_success']->count : 0;
    
    // Calculate failure rate
    $total_login_attempts = $stats['login_success'] + $stats['login_failed'];
    $stats['failure_rate'] = $total_login_attempts > 0 ? round(($stats['login_failed'] / $total_login_attempts) * 100, 2) : 0;
    
    // Top blocked IPs
    $stats['blocked_ips'] = $wpdb->get_results($wpdb->prepare(
        "SELECT ip_address, COUNT(*) as count FROM $table_name 
        WHERE event_type = 'login_blocked' AND created_at >= %s 
        GROUP BY ip_address 
        ORDER BY count DESC 
        LIMIT 10",
        $date_from
    ));
    
    return $stats;
}

/**
 * Cleanup old security logs
 * 
 * @param int $days Delete logs older than this many days (default 90)
 * @return int Number of rows deleted
 */
function melt_cleanup_old_logs($days = 90) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'melt_security_logs';
    $date_threshold = date('Y-m-d H:i:s', strtotime("-$days days"));
    
    $deleted = $wpdb->query($wpdb->prepare(
        "DELETE FROM $table_name WHERE created_at < %s",
        $date_threshold
    ));
    
    return $deleted;
}

/**
 * Schedule daily cleanup
 */
function melt_schedule_log_cleanup() {
    if (!wp_next_scheduled('melt_daily_log_cleanup')) {
        wp_schedule_event(time(), 'daily', 'melt_daily_log_cleanup');
    }
}
add_action('wp', 'melt_schedule_log_cleanup');

/**
 * Run daily cleanup
 */
function melt_run_daily_cleanup() {
    melt_cleanup_old_logs(90); // Keep logs for 90 days
}
add_action('melt_daily_log_cleanup', 'melt_run_daily_cleanup');

/**
 * Export security logs to CSV
 * 
 * @param array $args Query arguments
 */
function melt_export_security_logs_csv($args = []) {
    $logs = melt_get_security_logs($args);
    
    if (empty($logs)) {
        return;
    }
    
    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=security-logs-' . date('Y-m-d') . '.csv');
    
    // Create output stream
    $output = fopen('php://output', 'w');
    
    // Add BOM for Excel UTF-8 support
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Add headers
    fputcsv($output, ['ID', 'Date/Time', 'Event Type', 'User ID', 'Email', 'IP Address', 'User Agent', 'Details']);
    
    // Add data
    foreach ($logs as $log) {
        fputcsv($output, [
            $log->id,
            $log->created_at,
            $log->event_type,
            $log->user_id,
            $log->email,
            $log->ip_address,
            $log->user_agent,
            $log->details
        ]);
    }
    
    fclose($output);
    exit;
}
