<?php

/**
 * Email Verification System
 * Handles user registration with email verification
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle user registration with email verification
 */
function melt_handle_registration()
{
    check_ajax_referer('melt_nonce', 'nonce');

    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $password = $_POST['password']; // Don't sanitize passwords, just validate

    // Validation
    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        wp_send_json_error(['message' => 'All fields are required.']);
    }

    // Validate name (letters and spaces only, min 2 characters)
    if (!preg_match('/^[a-zA-Z\s]{2,}$/', $name)) {
        wp_send_json_error(['message' => 'Please enter a valid name (letters only, minimum 2 characters).']);
    }

    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Invalid email address.']);
    }

    // Validate phone number (10-15 digits, optional + prefix)
    $phone_clean = preg_replace('/[\s\-\(\)]/', '', $phone);
    if (!preg_match('/^[+]?[0-9]{10,15}$/', $phone_clean)) {
        wp_send_json_error(['message' => 'Please enter a valid phone number (10-15 digits).']);
    }

    // Improved password validation (minimum 8 characters for security)
    if (strlen($password) < 8) {
        wp_send_json_error(['message' => 'Password must be at least 8 characters.']);
    }

    if (email_exists($email)) {
        wp_send_json_error(['message' => 'This email is already registered.']);
    }

    // Create user
    $user_id = wp_create_user($email, $password, $email);

    if (is_wp_error($user_id)) {
        // Log failed registration
        melt_log_security_event('registration_failed', 0, $email, [
            'reason' => $user_id->get_error_message()
        ]);
        wp_send_json_error(['message' => $user_id->get_error_message()]);
    }

    // Update user meta
    wp_update_user([
        'ID' => $user_id,
        'display_name' => $name,
        'first_name' => $name,
        'role' => 'customer' // Set user role to customer for WooCommerce
    ]);

    // Add phone number
    update_user_meta($user_id, 'billing_phone', $phone);

    // Generate verification token
    $verification_token = wp_generate_password(32, false);
    update_user_meta($user_id, 'email_verification_token', $verification_token);
    update_user_meta($user_id, 'email_verified', '0');
    update_user_meta($user_id, 'verification_token_expires', time() + (24 * 60 * 60)); // 24 hours

    // Send verification email
    $sent = melt_send_verification_email($user_id, $email, $name, $verification_token);

    if ($sent) {
        // Log successful registration
        melt_log_security_event('registration_success', $user_id, $email, [
            'email_sent' => true
        ]);
        wp_send_json_success([
            'message' => 'Registration successful! Please check your email to verify your account.'
        ]);
    } else {
        // Log registration with email failure
        melt_log_security_event('registration_success', $user_id, $email, [
            'email_sent' => false
        ]);
        wp_send_json_error([
            'message' => 'Registration successful but we could not send the verification email. Please contact support.'
        ]);
    }
}
add_action('wp_ajax_nopriv_melt_register', 'melt_handle_registration');

/**
 * Handle user login with verification check
 */
function melt_handle_login()
{
    check_ajax_referer('melt_nonce', 'nonce');

    // Rate Limiting
    $ip = $_SERVER['REMOTE_ADDR'];
    $transient_name = 'melt_login_attempts_' . md5($ip);
    $attempts = get_transient($transient_name) ?: 0;

    // Initialize email for use in logging (will be sanitized later)
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';

    if ($attempts >= 5) {
        // Log blocked attempt
        try {
            melt_log_security_event('login_blocked', 0, $email, [
                'attempts' => $attempts,
                'reason' => 'rate_limit_exceeded'
            ]);
        } catch (Exception $e) {
            // Silently fail if logging doesn't work
        }
        wp_send_json_error([
            'message' => 'Too many failed attempts. Please try again in 15 minutes.',
            'remaining_attempts' => 0
        ]);
    }


    // Get password from POST
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($email) || empty($password)) {
        wp_send_json_error(['message' => 'Email and password are required.']);
    }

    // Get user by email
    $user = get_user_by('email', $email);

    if (!$user) {
        // Increment attempts on failure
        set_transient($transient_name, $attempts + 1, 15 * MINUTE_IN_SECONDS);

        // Log failed attempt
        try {
            melt_log_security_event('login_failed', 0, $email, [
                'reason' => 'user_not_found',
                'attempts' => $attempts + 1
            ]);
        } catch (Exception $e) {
            // Silently fail if logging doesn't work
        }

        // Calculate remaining attempts
        $remaining = max(0, 5 - ($attempts + 1));

        wp_send_json_error([
            'message' => 'Invalid email or password.',
            'remaining_attempts' => $remaining
        ]);
    }

    // Check if email is verified
    $is_verified = get_user_meta($user->ID, 'email_verified', true);

    if ($is_verified !== '1') {
        wp_send_json_error([
            'message' => 'Please verify your email address before logging in. Check your inbox for the verification link.'
        ]);
    }

    // Attempt login
    $credentials = [
        'user_login' => $email,
        'user_password' => $password,
        'remember' => true,
    ];

    $user_login = wp_signon($credentials, true);

    if (is_wp_error($user_login)) {
        // Increment attempts on failure
        set_transient($transient_name, $attempts + 1, 15 * MINUTE_IN_SECONDS);

        // Log failed attempt
        try {
            melt_log_security_event('login_failed', $user->ID, $email, [
                'reason' => 'invalid_password',
                'attempts' => $attempts + 1
            ]);
        } catch (Exception $e) {
            // Silently fail if logging doesn't work
        }

        // Calculate remaining attempts
        $remaining = max(0, 5 - ($attempts + 1));

        wp_send_json_error([
            'message' => 'Invalid email or password.',
            'remaining_attempts' => $remaining
        ]);
    }

    // Clear attempts on success
    delete_transient($transient_name);

    // Log successful login
    try {
        melt_log_security_event('login_success', $user->ID, $email);
    } catch (Exception $e) {
        // Silently fail if logging doesn't work
    }

    wp_send_json_success([
        'message' => 'Login successful!',
        'redirect' => home_url()
    ]);
}
add_action('wp_ajax_nopriv_melt_login', 'melt_handle_login');

/**
 * Send verification email using PHPMailer
 */
function melt_send_verification_email($user_id, $email, $name, $token)
{
    $verification_url = add_query_arg([
        'action' => 'verify_email',
        'user_id' => $user_id,
        'token' => $token
    ], home_url());

    $subject = 'Verify Your Email - Melt by Locali Gourmet';

    $message = melt_get_verification_email_template($name, $verification_url);

    // Set email headers
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: Melt by Locali Gourmet <noreply@meltbylg.com>',
    ];

    // Send email
    return wp_mail($email, $subject, $message, $headers);
}

/**
 * Get verification email template
 */
function melt_get_verification_email_template($name, $verification_url)
{
    ob_start();
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                line-height: 1.6;
                color: #1A1A1A;
                background-color: #F8F8F8;
                margin: 0;
                padding: 0;
            }

            .email-container {
                max-width: 600px;
                margin: 40px auto;
                background: white;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }

            .email-header {
                background: linear-gradient(135deg, #B8860B 0%, #DAA520 100%);
                padding: 40px 30px;
                text-align: center;
            }

            .email-header h1 {
                color: white;
                margin: 0;
                font-size: 28px;
                font-weight: 600;
            }

            .email-header p {
                color: rgba(255, 255, 255, 0.9);
                margin: 10px 0 0;
                font-size: 14px;
            }

            .email-body {
                padding: 40px 30px;
            }

            .email-body h2 {
                color: #1A1A1A;
                font-size: 22px;
                margin: 0 0 20px;
            }

            .email-body p {
                color: #666;
                margin: 0 0 20px;
                font-size: 15px;
            }

            .verify-button {
                display: inline-block;
                background: linear-gradient(135deg, #B8860B 0%, #DAA520 100%);
                color: white !important;
                text-decoration: none;
                padding: 16px 40px;
                border-radius: 8px;
                font-weight: 600;
                font-size: 16px;
                margin: 20px 0;
                box-shadow: 0 4px 12px rgba(184, 134, 11, 0.3);
            }

            .verify-button:hover {
                box-shadow: 0 6px 20px rgba(184, 134, 11, 0.4);
            }

            .email-footer {
                background: #F8F8F8;
                padding: 30px;
                text-align: center;
                border-top: 1px solid #E0E0E0;
            }

            .email-footer p {
                color: #999;
                font-size: 13px;
                margin: 5px 0;
            }

            .divider {
                height: 1px;
                background: #E0E0E0;
                margin: 30px 0;
            }

            .info-box {
                background: rgba(184, 134, 11, 0.1);
                border-left: 4px solid #B8860B;
                padding: 15px 20px;
                margin: 20px 0;
                border-radius: 4px;
            }

            .info-box p {
                margin: 0;
                color: #666;
                font-size: 14px;
            }
        </style>
    </head>

    <body>
        <div class="email-container">
            <div class="email-header">
                <h1>🎂 Melt by Locali Gourmet</h1>
                <p>Artisan Patisserie</p>
            </div>

            <div class="email-body">
                <h2>Welcome, <?php echo esc_html($name); ?>! 👋</h2>

                <p>Thank you for creating an account with Melt. We're excited to have you join our community of artisan cake lovers!</p>

                <p>To complete your registration and start exploring our exquisite collection, please verify your email address by clicking the button below:</p>

                <div style="text-align: center;">
                    <a href="<?php echo esc_url($verification_url); ?>" class="verify-button">Verify My Email</a>
                </div>

                <div class="info-box">
                    <p><strong>⏰ Important:</strong> This verification link will expire in 24 hours for security reasons.</p>
                </div>

                <div class="divider"></div>

                <p style="font-size: 13px; color: #999;">If the button doesn't work, copy and paste this link into your browser:</p>
                <p style="font-size: 13px; color: #B8860B; word-break: break-all;"><?php echo esc_url($verification_url); ?></p>

                <div class="divider"></div>

                <p style="font-size: 13px; color: #999;">If you didn't create this account, please ignore this email or contact us if you have concerns.</p>
            </div>

            <div class="email-footer">
                <p><strong>Melt by Locali Gourmet</strong></p>
                <p>123 Baker Street, Dubai, UAE</p>
                <p>📞 (555) 123-4567 | 📧 hello@meltbylg.com</p>
                <p style="margin-top: 15px;">© <?php echo date('Y'); ?> Melt by Locali Gourmet. All rights reserved.</p>
            </div>
        </div>
    </body>

    </html>
<?php
    return ob_get_clean();
}

/**
 * Handle email verification
 */
function melt_verify_email()
{
    if (!isset($_GET['action']) || $_GET['action'] !== 'verify_email') {
        return;
    }

    $user_id = isset($_GET['user_id']) ? absint($_GET['user_id']) : 0;
    $token = isset($_GET['token']) ? sanitize_text_field($_GET['token']) : '';

    if (!$user_id || !$token) {
        wp_die('Invalid verification link.');
    }

    $stored_token = get_user_meta($user_id, 'email_verification_token', true);
    $token_expires = get_user_meta($user_id, 'verification_token_expires', true);

    // Check if token matches
    if ($token !== $stored_token) {
        wp_die('Invalid verification token.');
    }

    // Check if token expired
    if (time() > $token_expires) {
        wp_die('Verification link has expired. Please request a new one.');
    }

    // Verify the email
    update_user_meta($user_id, 'email_verified', '1');
    delete_user_meta($user_id, 'email_verification_token');
    delete_user_meta($user_id, 'verification_token_expires');

    // Log email verification
    $user = get_user_by('ID', $user_id);
    if ($user) {
        melt_log_security_event('email_verification', $user_id, $user->user_email);
    }

    // Auto-login the user
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id, true);

    // Redirect to clean homepage (no query params to avoid loops)
    wp_redirect(home_url('/'));
    exit;
}
add_action('template_redirect', 'melt_verify_email');

/**
 * Resend verification email
 */
function melt_resend_verification_email()
{
    check_ajax_referer('melt_nonce', 'nonce');

    $email = sanitize_email($_POST['email']);

    $user = get_user_by('email', $email);

    if (!$user) {
        wp_send_json_error(['message' => 'User not found.']);
    }

    $is_verified = get_user_meta($user->ID, 'email_verified', true);

    if ($is_verified === '1') {
        wp_send_json_error(['message' => 'Email is already verified.']);
    }

    // Generate new token
    $verification_token = wp_generate_password(32, false);
    update_user_meta($user->ID, 'email_verification_token', $verification_token);
    update_user_meta($user->ID, 'verification_token_expires', time() + (24 * 60 * 60));

    // Resend email
    $sent = melt_send_verification_email($user->ID, $email, $user->display_name, $verification_token);

    if ($sent) {
        wp_send_json_success(['message' => 'Verification email sent! Please check your inbox.']);
    } else {
        wp_send_json_error(['message' => 'Failed to send verification email.']);
    }
}
add_action('wp_ajax_nopriv_melt_resend_verification', 'melt_resend_verification_email');
