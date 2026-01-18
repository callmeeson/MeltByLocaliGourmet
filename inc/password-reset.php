<?php
/**
 * Password Reset Functionality
 * Handles password reset requests and verification
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle password reset request
 */
function melt_request_password_reset() {
    check_ajax_referer('melt_nonce', 'nonce');
    
    $email = sanitize_email($_POST['email']);
    
    if (empty($email)) {
        wp_send_json_error(['message' => 'Email is required.']);
    }
    
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Invalid email address.']);
    }
    
    // Check if user exists
    $user = get_user_by('email', $email);
    
    if (!$user) {
        // Don't reveal if email exists or not (security)
        wp_send_json_success([
            'message' => 'If an account exists with this email, you will receive password reset instructions.'
        ]);
    }
    
    // Rate limiting - max 3 reset requests per hour per email
    $transient_name = 'melt_reset_attempts_' . md5($email);
    $attempts = get_transient($transient_name) ?: 0;
    
    if ($attempts >= 3) {
        melt_log_security_event('password_reset_blocked', $user->ID, $email, [
            'reason' => 'rate_limit_exceeded'
        ]);
        wp_send_json_error(['message' => 'Too many reset requests. Please try again in 1 hour.']);
    }
    
    // Increment attempts
    set_transient($transient_name, $attempts + 1, HOUR_IN_SECONDS);
    
    // Generate reset token
    $reset_token = wp_generate_password(32, false);
    $token_hash = hash('sha256', $reset_token);
    
    // Store token
    update_user_meta($user->ID, 'password_reset_token', $token_hash);
    update_user_meta($user->ID, 'password_reset_expires', time() + HOUR_IN_SECONDS); // 1 hour
    
    // Send reset email
    $sent = melt_send_password_reset_email($user->ID, $email, $user->display_name, $reset_token);
    
    // Log event
    melt_log_security_event('password_reset_request', $user->ID, $email, [
        'email_sent' => $sent
    ]);
    
    if ($sent) {
        wp_send_json_success([
            'message' => 'If an account exists with this email, you will receive password reset instructions.'
        ]);
    } else {
        melt_log_error('Email', 'Failed to send password reset email', ['email' => $email, 'user_id' => $user->ID]);
        wp_send_json_error([
            'message' => 'Unable to send reset email. Please try again later.'
        ]);
    }
}
add_action('wp_ajax_nopriv_melt_request_password_reset', 'melt_request_password_reset');

/**
 * Send password reset email
 */
function melt_send_password_reset_email($user_id, $email, $name, $token) {
    $reset_url = add_query_arg([
        'action' => 'reset_password',
        'user_id' => $user_id,
        'token' => $token
    ], home_url());
    
    $subject = 'Password Reset Request - Melt by Locali Gourmet';
    
    $message = melt_get_password_reset_email_template($name, $reset_url);
    
    // Set email headers
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: Melt by Locali Gourmet <noreply@meltbylg.com>',
    ];
    
    // Send email
    return wp_mail($email, $subject, $message, $headers);
}

/**
 * Get password reset email template
 */
function melt_get_password_reset_email_template($name, $reset_url) {
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
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
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
                color: rgba(255,255,255,0.9);
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
            .reset-button {
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
            .reset-button:hover {
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
            .warning-box {
                background: rgba(239, 68, 68, 0.1);
                border-left: 4px solid #ef4444;
                padding: 15px 20px;
                margin: 20px 0;
                border-radius: 4px;
            }
            .warning-box p {
                margin: 0;
                color: #666;
                font-size: 14px;
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
                <h2>Password Reset Request</h2>
                
                <p>Hi <?php echo esc_html($name); ?>,</p>
                
                <p>We received a request to reset your password. If you made this request, click the button below to create a new password:</p>
                
                <div style="text-align: center;">
                    <a href="<?php echo esc_url($reset_url); ?>" class="reset-button">Reset My Password</a>
                </div>
                
                <div class="info-box">
                    <p><strong>⏰ Important:</strong> This password reset link will expire in 1 hour for security reasons.</p>
                </div>
                
                <div class="warning-box">
                    <p><strong>🔒 Security Notice:</strong> If you didn't request a password reset, please ignore this email. Your password will remain unchanged.</p>
                </div>
                
                <div class="divider"></div>
                
                <p style="font-size: 13px; color: #999;">If the button doesn't work, copy and paste this link into your browser:</p>
                <p style="font-size: 13px; color: #B8860B; word-break: break-all;"><?php echo esc_url($reset_url); ?></p>
                
                <div class="divider"></div>
                
                <p style="font-size: 13px; color: #999;">For security reasons, we recommend:</p>
                <ul style="font-size: 13px; color: #999; margin: 10px 0;">
                    <li>Use a strong, unique password</li>
                    <li>Never share your password with anyone</li>
                    <li>Enable two-factor authentication if available</li>
                </ul>
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
 * Handle password reset (from email link)
 */
function melt_handle_password_reset_page() {
    if (!isset($_GET['action']) || $_GET['action'] !== 'reset_password') {
        return;
    }
    
    $user_id = isset($_GET['user_id']) ? absint($_GET['user_id']) : 0;
    $token = isset($_GET['token']) ? sanitize_text_field($_GET['token']) : '';
    
    if (!$user_id || !$token) {
        wp_die('Invalid password reset link.');
    }
    
    // Verify token
    $stored_token_hash = get_user_meta($user_id, 'password_reset_token', true);
    $token_expires = get_user_meta($user_id, 'password_reset_expires', true);
    $token_hash = hash('sha256', $token);
    
    if ($token_hash !== $stored_token_hash) {
        melt_log_security_event('password_reset_failed', $user_id, '', [
            'reason' => 'invalid_token'
        ]);
        wp_die('Invalid password reset token.');
    }
    
    if (time() > $token_expires) {
        melt_log_security_event('password_reset_failed', $user_id, '', [
            'reason' => 'token_expired'
        ]);
        wp_die('Password reset link has expired. Please request a new one.');
    }
    
    // Show password reset form
    melt_show_password_reset_form($user_id, $token);
    exit;
}
add_action('template_redirect', 'melt_handle_password_reset_page');

/**
 * Show password reset form
 */
function melt_show_password_reset_form($user_id, $token) {
    $user = get_user_by('ID', $user_id);
    
    get_header();
    ?>
    <div class="section" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 2rem;">
        <div style="max-width: 500px; width: 100%; background: white; padding: 3rem; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <h1 style="text-align: center; color: var(--primary); margin-bottom: 1rem;">Reset Password</h1>
            <p style="text-align: center; color: var(--muted-foreground); margin-bottom: 2rem;">
                Enter your new password for <strong><?php echo esc_html($user->user_email); ?></strong>
            </p>
            
            <form id="resetPasswordForm" method="post">
                <input type="hidden" name="user_id" value="<?php echo esc_attr($user_id); ?>">
                <input type="hidden" name="token" value="<?php echo esc_attr($token); ?>">
                
                <div style="margin-bottom: 1.5rem;">
                    <label for="newPassword" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">New Password</label>
                    <input type="password" id="newPassword" name="new_password" required 
                           style="width: 100%; padding: 0.75rem; border: 2px solid rgba(184, 134, 11, 0.3); border-radius: 8px; font-size: 1rem;">
                    <div id="passwordStrengthMeter" style="display: none; margin-top: 0.5rem;"></div>
                </div>
                
                <div style="margin-bottom: 1.5rem;">
                    <label for="confirmPassword" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirm_password" required 
                           style="width: 100%; padding: 0.75rem; border: 2px solid rgba(184, 134, 11, 0.3); border-radius: 8px; font-size: 1rem;">
                    <span id="passwordMatchError" style="display: none; color: var(--destructive); font-size: 0.875rem; margin-top: 0.25rem;"></span>
                </div>
                
                <button type="submit" id="resetPasswordBtn" 
                        style="width: 100%; padding: 1rem; background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: transform 0.2s;">
                    Reset Password
                </button>
            </form>
            
            <div id="resetMessage" style="display: none; margin-top: 1rem; padding: 1rem; border-radius: 8px;"></div>
        </div>
    </div>
    
    <script>
    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const btn = document.getElementById('resetPasswordBtn');
        const message = document.getElementById('resetMessage');
        
        // Validate passwords match
        if (newPassword !== confirmPassword) {
            document.getElementById('passwordMatchError').textContent = 'Passwords do not match';
            document.getElementById('passwordMatchError').style.display = 'block';
            return;
        }
        
        // Validate password strength
        if (newPassword.length < 8) {
            message.textContent = 'Password must be at least 8 characters';
            message.style.display = 'block';
            message.style.background = 'rgba(239, 68, 68, 0.1)';
            message.style.color = '#ef4444';
            return;
        }
        
        // Show loading
        btn.textContent = 'Resetting...';
        btn.disabled = true;
        
        // Submit via AJAX
        const formData = new FormData(this);
        formData.append('action', 'melt_reset_password');
        formData.append('nonce', '<?php echo wp_create_nonce('melt_nonce'); ?>');
        
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                message.textContent = data.data.message;
                message.style.display = 'block';
                message.style.background = 'rgba(34, 197, 94, 0.1)';
                message.style.color = '#22c55e';
                
                setTimeout(() => {
                    window.location.href = '<?php echo home_url(); ?>';
                }, 2000);
            } else {
                message.textContent = data.data.message;
                message.style.display = 'block';
                message.style.background = 'rgba(239, 68, 68, 0.1)';
                message.style.color = '#ef4444';
                btn.textContent = 'Reset Password';
                btn.disabled = false;
            }
        })
        .catch(error => {
            message.textContent = 'An error occurred. Please try again.';
            message.style.display = 'block';
            message.style.background = 'rgba(239, 68, 68, 0.1)';
            message.style.color = '#ef4444';
            btn.textContent = 'Reset Password';
            btn.disabled = false;
        });
    });
    
    // Clear match error on input
    document.getElementById('confirmPassword').addEventListener('input', function() {
        document.getElementById('passwordMatchError').style.display = 'none';
    });
    </script>
    <?php
    get_footer();
}

/**
 * Process password reset
 */
function melt_reset_password() {
    check_ajax_referer('melt_nonce', 'nonce');
    
    $user_id = isset($_POST['user_id']) ? absint($_POST['user_id']) : 0;
    $token = isset($_POST['token']) ? sanitize_text_field($_POST['token']) : '';
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    
    if (!$user_id || !$token || !$new_password) {
        wp_send_json_error(['message' => 'Missing required fields.']);
    }
    
    // Verify token again
    $stored_token_hash = get_user_meta($user_id, 'password_reset_token', true);
    $token_expires = get_user_meta($user_id, 'password_reset_expires', true);
    $token_hash = hash('sha256', $token);
    
    if ($token_hash !== $stored_token_hash) {
        melt_log_security_event('password_reset_failed', $user_id, '', [
            'reason' => 'invalid_token'
        ]);
        wp_send_json_error(['message' => 'Invalid password reset token.']);
    }
    
    if (time() > $token_expires) {
        melt_log_security_event('password_reset_failed', $user_id, '', [
            'reason' => 'token_expired'
        ]);
        wp_send_json_error(['message' => 'Password reset link has expired.']);
    }
    
    // Validate password
    if (strlen($new_password) < 8) {
        wp_send_json_error(['message' => 'Password must be at least 8 characters.']);
    }
    
    // Reset password
    wp_set_password($new_password, $user_id);
    
    // Delete reset token
    delete_user_meta($user_id, 'password_reset_token');
    delete_user_meta($user_id, 'password_reset_expires');
    
    // Clear rate limiting
    $user = get_user_by('ID', $user_id);
    delete_transient('melt_reset_attempts_' . md5($user->user_email));
    
    // Log success
    melt_log_security_event('password_reset_success', $user_id, $user->user_email);
    
    // Auto-login
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id, true);
    
    wp_send_json_success([
        'message' => 'Password reset successful! Redirecting...',
        'redirect' => home_url()
    ]);
}
add_action('wp_ajax_nopriv_melt_reset_password', 'melt_reset_password');
