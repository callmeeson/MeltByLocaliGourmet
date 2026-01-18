<?php
/**
 * Email Testing Tool
 * Use this to test if emails are working
 */

// Test email sending
function melt_test_email() {
    if (!isset($_GET['test_email']) || !current_user_can('administrator')) {
        return;
    }
    
    $to = get_option('admin_email');
    $subject = 'Melt Email Test - ' . date('Y-m-d H:i:s');
    $message = '
        <h2>Email Test Successful!</h2>
        <p>If you\'re reading this, your email configuration is working correctly.</p>
        <p>Time: ' . date('Y-m-d H:i:s') . '</p>
        <p>PHPMailer is properly configured and sending emails.</p>
    ';
    
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: Melt by Locali Gourmet <meltbylocaligourmet@gmail.com>',
    );
    
    $result = wp_mail($to, $subject, $message, $headers);
    
    if ($result) {
        wp_die('✅ Test email sent successfully! Check your inbox at: ' . $to . '<br><br><a href="' . admin_url() . '">Back to Dashboard</a>');
    } else {
        wp_die('❌ Email failed to send. Check your error logs at: ' . WP_CONTENT_DIR . '/debug.log<br><br><a href="' . admin_url() . '">Back to Dashboard</a>');
    }
}
add_action('init', 'melt_test_email');

/**
 * Instructions displayed in admin
 */
function melt_email_test_admin_notice() {
    if (!current_user_can('administrator')) {
        return;
    }
    
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $test_url = add_query_arg('test_email', '1', home_url());
    
    echo '<div class="notice notice-info is-dismissible">';
    echo '<p><strong>📧 Email Testing:</strong> <a href="' . esc_url($test_url) . '" target="_blank">Click here to test email sending</a></p>';
    echo '</div>';
}
// Uncomment to show admin notice
// add_action('admin_notices', 'melt_email_test_admin_notice');
