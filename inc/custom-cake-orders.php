<?php
/**
 * Custom Cake Orders Management
 * 
 * Registers a custom post type for Custom Cake Orders and handles
 * the admin display logic.
 *
 * @package Melt_Custom
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Custom Cake Post Type
 */
function melt_register_custom_cake_cpt() {
    $labels = array(
        'name'                  => 'Cake Orders',
        'singular_name'         => 'Cake Order',
        'menu_name'             => 'Cake Orders',
        'name_admin_bar'        => 'Cake Order',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Cake Order',
        'new_item'              => 'New Cake Order',
        'edit_item'             => 'View Cake Order',
        'view_item'             => 'View Cake Order',
        'all_items'             => 'All Cake Orders',
        'search_items'          => 'Search Cake Orders',
        'not_found'             => 'No cake orders found.',
        'not_found_in_trash'    => 'No cake orders found in Trash.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false, // Not publicly queryable
        'publicly_queryable' => false,
        'show_ui'            => true,  // Show in admin
        'show_in_menu'       => true,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 56, // Below WooCommerce
        'menu_icon'          => 'dashicons-carrot',
        'supports'           => array( 'title' ), // Only title, we'll use meta boxes for the rest
    );

    register_post_type( 'custom_cake', $args );
}
add_action( 'init', 'melt_register_custom_cake_cpt' );

/**
 * Add Custom Columns to Admin List
 */
function melt_set_custom_cake_columns( $columns ) {
    $new_columns = array(
        'cb' => $columns['cb'],
        'title' => 'Order ID / Name',
        'contact_info' => 'Contact Info',
        'delivery_date' => 'Delivery/Pickup Date',
        'order_type' => 'Type',
        'date' => 'Date Submitted',
        'status' => 'Status',
    );
    return $new_columns;
}
add_filter( 'manage_custom_cake_posts_columns', 'melt_set_custom_cake_columns' );

/**
 * Populate Custom Columns
 */
function melt_custom_cake_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'status':
            $status = get_post_meta( $post_id, 'melt_cc_status', true );
            if ( ! $status ) $status = 'submitted';
            
            $status_labels = array(
                'submitted' => 'Submitted',
                'viewed'    => 'Viewed',
                'contacted' => 'Contacted',
                'confirmed' => 'Confirmed',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            );
            
            $label = isset( $status_labels[$status] ) ? $status_labels[$status] : ucfirst( $status );
            
            $colors = array(
                'submitted' => '#999',
                'viewed'    => '#3399cc',
                'contacted' => '#e6b800',
                'confirmed' => '#28a745',
                'completed' => '#007cba',
                'cancelled' => '#dc3545',
            );
            
            $color = isset( $colors[$status] ) ? $colors[$status] : '#999';
            
            echo '<span style="background-color: ' . $color . '; color: #fff; padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: bold;">' . esc_html( $label ) . '</span>';
            break;

        case 'contact_info':
            $email = get_post_meta( $post_id, 'melt_cc_email', true );
            $phone = get_post_meta( $post_id, 'melt_cc_contact', true );
            echo esc_html( $email ) . '<br>' . esc_html( $phone );
            break;

        case 'delivery_date':
            $date = get_post_meta( $post_id, 'melt_cc_pref_date', true );
            $time = get_post_meta( $post_id, 'melt_cc_pref_time', true );
            // Format time to 12-hour format with AM/PM
            if ( ! empty( $time ) ) {
                $time = date( 'g:i A', strtotime( $time ) );
            }
            echo esc_html( $date ) . ' at ' . esc_html( $time );
            break;

        case 'order_type':
            $type = get_post_meta( $post_id, 'melt_cc_order_type', true );
            echo '<span style="text-transform: capitalize; font-weight: bold;">' . esc_html( $type ) . '</span>';
            break;
    }
}
add_action( 'manage_custom_cake_posts_custom_column', 'melt_custom_cake_column_content', 10, 2 );

/**
 * Add Meta Box for Order Details
 */
function melt_add_custom_cake_meta_boxes() {
    add_meta_box(
        'melt_cake_order_status',
        'Update Status',
        'melt_render_cake_status_meta_box',
        'custom_cake',
        'side',
        'high'
    );
    
    add_meta_box(
        'melt_cake_order_details',
        'Order Details',
        'melt_render_cake_order_meta_box',
        'custom_cake',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'melt_add_custom_cake_meta_boxes' );

/**
 * Render Meta Box Content
 */
function melt_render_cake_order_meta_box( $post ) {
    // Get all meta data
    $meta = get_post_meta( $post->ID );
    
    // Helper to get value
    $get_val = function($key) use ($meta) {
        return isset($meta[$key][0]) ? $meta[$key][0] : '';
    };

    ?>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <!-- Client Info -->
        <div class="cake-order-section">
            <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 10px; margin-top: 0;">Client Information</h3>
            <p><strong>Full Name:</strong> <?php echo esc_html( $get_val('melt_cc_fullname') ); ?></p>
            <p><strong>Email:</strong> <a href="mailto:<?php echo esc_attr( $get_val('melt_cc_email') ); ?>"><?php echo esc_html( $get_val('melt_cc_email') ); ?></a></p>
            <p><strong>Phone:</strong> <a href="tel:<?php echo esc_attr( $get_val('melt_cc_contact') ); ?>"><?php echo esc_html( $get_val('melt_cc_contact') ); ?></a></p>
        </div>

        <!-- Delivery Info -->
        <div class="cake-order-section">
            <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 10px; margin-top: 0;">Delivery / Pickup Details</h3>
            <p><strong>Type:</strong> <span style="text-transform: uppercase; font-weight: bold;"><?php echo esc_html( $get_val('melt_cc_order_type') ); ?></span></p>
            <p><strong>Preferred Date:</strong> <?php echo esc_html( $get_val('melt_cc_pref_date') ); ?></p>
            <p><strong>Preferred Time:</strong> <?php 
                $time_val = $get_val('melt_cc_pref_time');
                echo esc_html( !empty($time_val) ? date('g:i A', strtotime($time_val)) : '' ); 
            ?></p>
            <?php if ( $get_val('melt_cc_order_type') === 'delivery' ) : ?>
                <p><strong>Delivery Address:</strong><br><?php echo nl2br( esc_html( $get_val('melt_cc_delivery_address') ) ); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div style="margin-top: 30px;">
        <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 10px;">Cake Specifications</h3>
        <table class="widefat striped" style="border: 1px solid #ddd;">
            <tbody>
                <tr>
                    <td style="width: 200px; font-weight: bold;">Flavor Preferences</td>
                    <td><?php echo esc_html( $get_val('melt_cc_flavor') ); ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Servings / Guests</td>
                    <td><?php echo esc_html( $get_val('melt_cc_servings') ); ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Special Instructions</td>
                    <td><?php echo nl2br( esc_html( $get_val('melt_cc_instructions') ) ); ?></td>
                </tr>
                <?php 
                $attachment_id = $get_val('melt_cc_sample_image_id');
                if ( $attachment_id ) : 
                    $image_url = wp_get_attachment_url( $attachment_id );
                ?>
                <tr>
                    <td style="font-weight: bold;">Sample/Inspiration Photo</td>
                    <td>
                        <a href="<?php echo esc_url($image_url); ?>" target="_blank">
                            <?php echo wp_get_attachment_image( $attachment_id, 'medium', false, array( 'style' => 'max-width: 300px; height: auto; border-radius: 4px; border: 1px solid #ddd;' ) ); ?>
                        </a>
                        <br>
                        <a href="<?php echo esc_url($image_url); ?>" target="_blank">View Full Size</a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * Render Status Meta Box
 */
function melt_render_cake_status_meta_box( $post ) {
    $current_status = get_post_meta( $post->ID, 'melt_cc_status', true );
    if ( ! $current_status ) $current_status = 'submitted';
    
    $statuses = array(
        'submitted' => 'Submitted',
        'viewed'    => 'Viewed',
        'contacted' => 'Contacted',
        'confirmed' => 'Confirmed',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    );
    
    wp_nonce_field( 'melt_save_cake_status', 'melt_cake_status_nonce' );
    ?>
    <p>
        <label for="melt_cc_status" style="font-weight: bold; display: block; margin-bottom: 5px;">Current Status:</label>
        <select name="melt_cc_status" id="melt_cc_status" style="width: 100%;">
            <?php foreach ( $statuses as $value => $label ) : ?>
                <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current_status, $value ); ?>>
                    <?php echo esc_html( $label ); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p class="description">Update the status of this order to notify the customer.</p>
    <?php
}

/**
 * Save Custom Cake Meta Data
 */
function melt_save_custom_cake_meta( $post_id ) {
    // Check nonce
    if ( ! isset( $_POST['melt_cake_status_nonce'] ) || ! wp_verify_nonce( $_POST['melt_cake_status_nonce'], 'melt_save_cake_status' ) ) {
        return;
    }
    
    // Check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    // Save Status
    if ( isset( $_POST['melt_cc_status'] ) ) {
        update_post_meta( $post_id, 'melt_cc_status', sanitize_text_field( $_POST['melt_cc_status'] ) );
    }
}
add_action( 'save_post', 'melt_save_custom_cake_meta' );
