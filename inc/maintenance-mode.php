<?php
/**
 * Custom Maintenance Mode Functionality
 * Allows Administrators and Developers to put the site into maintenance mode.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Maintenance Mode Setting in General Settings
 */
function melt_register_maintenance_setting() {
    register_setting('general', 'melt_maintenance_mode');
    
    add_settings_field(
        'melt_maintenance_mode',
        'System Maintenance Mode',
        'melt_maintenance_mode_callback',
        'general',
        'default',
        array('label_for' => 'melt_maintenance_mode')
    );
}
add_action('admin_init', 'melt_register_maintenance_setting');

/**
 * Render the Checkbox Field
 */
function melt_maintenance_mode_callback() {
    $value = get_option('melt_maintenance_mode');
    ?>
    <label>
        <input type="checkbox" name="melt_maintenance_mode" value="1" <?php checked(1, $value); ?> />
        Enable Maintenance Mode
    </label>
    <p class="description">
        When enabled, the public website will show a "Maintenance" message.<br>
        <strong>Administrators</strong> and <strong>Developers</strong> can still view the site.<br>
        <strong>Shop Owners</strong> and <strong>Customers</strong> will see the maintenance page.
    </p>
    <?php
}

/**
 * Handle the Redirect/Maintenance Page
 */
function melt_maintenance_mode_handler() {
    // 1. If maintenance mode is NOT enabled, do nothing.
    if (!get_option('melt_maintenance_mode')) {
        return;
    }

    // 2. Allow access to wp-login.php and admin-ajax (to prevent locking out login)
    if (in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {
        return;
    }

    // 3. Allow Administrators and Developers (manage_options capability) to view the site
    if (current_user_can('manage_options')) {
        return;
    }

    // 4. For everyone else (including logged-out users), show the 503 Maintenance page
    // Note: Since steps 1-3 didn't return, this catches EVERYONE else.
    
    // Prevent caching of the maintenance page
    nocache_headers();
    
    $protocol = wp_get_server_protocol();
    header("$protocol 503 Service Unavailable", true, 503);
    header('Retry-After: 600'); // Retry in 10 minutes
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Site Under Maintenance</title>
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: #1a1a1a; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; color: #fff; }
            .maintenance-container { background: #2d2d2d; padding: 3rem; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); text-align: center; max-width: 500px; width: 90%; border: 1px solid #404040; }
            
            .warning-icon {
                font-size: 4rem;
                margin-bottom: 1.5rem;
                display: inline-block;
                animation: glow 2s infinite ease-in-out;
            }
            
            @keyframes glow {
                0% { text-shadow: 0 0 10px rgba(255, 165, 0, 0.5); transform: scale(1); }
                50% { text-shadow: 0 0 30px rgba(255, 165, 0, 0.8), 0 0 50px rgba(255, 69, 0, 0.6); transform: scale(1.1); }
                100% { text-shadow: 0 0 10px rgba(255, 165, 0, 0.5); transform: scale(1); }
            }

            h1 { margin-top: 0; color: #fff; font-size: 2rem; margin-bottom: 1rem; }
            p { line-height: 1.6; color: #aaa; margin-bottom: 0; }
        </style>
    </head>
    <body>
        <div class="maintenance-container">
            <div class="warning-icon">⚠️</div>
            <h1>System Maintenance</h1>
            <p>We are currently performing scheduled maintenance to improve your experience.</p>
            <p>Please check back shortly.</p>
        </div>
    </body>
    </html>
    <?php
    exit();
}
add_action('template_redirect', 'melt_maintenance_mode_handler');

/**
 * Show Warning in Admin Bar when Active
 */
function melt_maintenance_admin_bar($wp_admin_bar) {
    if (get_option('melt_maintenance_mode') && current_user_can('manage_options') && !is_admin()) {
        $wp_admin_bar->add_node(array(
            'id'    => 'melt-maintenance-notice',
            'title' => '<span style="color: #ff4444; font-weight: bold;">⚠️ Maintenance Mode ACTIVE</span>',
            'href'  => admin_url('options-general.php'),
            'meta'  => array('title' => 'Click to disable maintenance mode')
        ));
    }
}
add_action('admin_bar_menu', 'melt_maintenance_admin_bar', 999);
