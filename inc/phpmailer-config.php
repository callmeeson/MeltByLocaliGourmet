<?php
/**
 * PHPMailer SMTP Configuration
 * Configure your email sending settings here
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Configure PHPMailer to use SMTP
 * 
 * IMPORTANT: Add these constants to wp-config.php:
 * define('MELT_SMTP_HOST', 'smtp.gmail.com');
 * define('MELT_SMTP_USER', 'your-email@gmail.com');
 * define('MELT_SMTP_PASS', 'your-app-password');
 * define('MELT_SMTP_PORT', 587);
 * define('MELT_SMTP_SECURE', 'tls');
 * define('MELT_SMTP_FROM_NAME', 'Melt by Locali Gourmet');
 */
function melt_configure_phpmailer($phpmailer) {
    // Check if SMTP constants are defined
    if (!defined('MELT_SMTP_HOST') || !defined('MELT_SMTP_USER') || !defined('MELT_SMTP_PASS')) {
        error_log('Melt Theme: SMTP constants not defined in wp-config.php. Email sending will fail.');
        return; // Don't configure SMTP, fall back to wp_mail() default
    }
    
    // Enable SMTP
    $phpmailer->isSMTP();
    
    // Enable debugging only in development (based on WP_DEBUG)
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $phpmailer->SMTPDebug = 2; // 0 = off, 1 = client, 2 = client and server
        $phpmailer->Debugoutput = function($str, $level) {
            error_log("PHPMailer Debug: " . $str);
        };
    } else {
        $phpmailer->SMTPDebug = 0; // Disable debug in production
    }
    
    // SMTP Settings from constants
    $phpmailer->Host = defined('MELT_SMTP_HOST') ? MELT_SMTP_HOST : 'smtp.gmail.com';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Username = MELT_SMTP_USER;
    $phpmailer->Password = MELT_SMTP_PASS;
    $phpmailer->SMTPSecure = defined('MELT_SMTP_SECURE') ? MELT_SMTP_SECURE : 'tls';
    $phpmailer->Port = defined('MELT_SMTP_PORT') ? MELT_SMTP_PORT : 587;
    
    // From settings
    $phpmailer->From = MELT_SMTP_USER;
    $phpmailer->FromName = defined('MELT_SMTP_FROM_NAME') ? MELT_SMTP_FROM_NAME : 'Melt by Locali Gourmet';
    $phpmailer->CharSet = 'UTF-8';
    
    // Additional settings for Gmail
    $phpmailer->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
}
add_action('phpmailer_init', 'melt_configure_phpmailer');

/**
 * Admin notice if SMTP constants are not configured
 */
function melt_smtp_config_notice() {
    if (!defined('MELT_SMTP_HOST') || !defined('MELT_SMTP_USER') || !defined('MELT_SMTP_PASS')) {
        ?>
        <div class="notice notice-error">
            <p><strong>Melt Theme:</strong> SMTP email configuration is missing. Email sending will not work.</p>
            <p>Add these constants to your <code>wp-config.php</code> file:</p>
            <pre style="background: #f5f5f5; padding: 10px; overflow-x: auto;">
define('MELT_SMTP_HOST', 'smtp.gmail.com');
define('MELT_SMTP_USER', 'your-email@gmail.com');
define('MELT_SMTP_PASS', 'your-app-password');
define('MELT_SMTP_PORT', 587);
define('MELT_SMTP_SECURE', 'tls');
define('MELT_SMTP_FROM_NAME', 'Melt by Locali Gourmet');
            </pre>
            <p><strong>Security Note:</strong> Never commit <code>wp-config.php</code> to version control!</p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'melt_smtp_config_notice');

/**
 * Log email errors
 */
function melt_log_email_errors($wp_error) {
    error_log('Mail Error: ' . $wp_error->get_error_message());
}
add_action('wp_mail_failed', 'melt_log_email_errors');

/**
 * Alternative: Use SendGrid, Mailgun, or other services
 * Uncomment and configure one of these if not using Gmail
 */

// SENDGRID CONFIGURATION
/*
function melt_configure_sendgrid($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp.sendgrid.net';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Username = 'apikey';
    $phpmailer->Password = 'YOUR_SENDGRID_API_KEY';
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->Port = 587;
    $phpmailer->From = 'noreply@meltbylg.com';
    $phpmailer->FromName = 'Melt by Locali Gourmet';
}
add_action('phpmailer_init', 'melt_configure_sendgrid');
*/

// MAILGUN CONFIGURATION
/*
function melt_configure_mailgun($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp.mailgun.org';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Username = 'postmaster@your-domain.mailgun.org';
    $phpmailer->Password = 'YOUR_MAILGUN_SMTP_PASSWORD';
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->Port = 587;
    $phpmailer->From = 'noreply@meltbylg.com';
    $phpmailer->FromName = 'Melt by Locali Gourmet';
}
add_action('phpmailer_init', 'melt_configure_mailgun');
*/
