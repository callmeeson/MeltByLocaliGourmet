<?php
/**
 * Template Name: Custom Cake Order
 * Description: Multi-step form for custom cake orders.
 *
 * @package Melt_Custom
 */

// Handle Form Submission
$submission_status = '';
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['custom_cake_nonce'] ) && wp_verify_nonce( $_POST['custom_cake_nonce'], 'submit_custom_cake' ) ) {
    
    // Collect Data
    $fullname = isset($_POST['fullname']) ? sanitize_text_field( $_POST['fullname'] ) : '';
    $contact = isset($_POST['contact-number']) ? sanitize_text_field( $_POST['contact-number'] ) : '';
    $email = isset($_POST['email']) ? sanitize_email( $_POST['email'] ) : '';
    
    $order_type = isset($_POST['order-type']) ? sanitize_text_field( $_POST['order-type'] ) : '';
    $delivery_address = isset($_POST['delivery-address']) ? sanitize_textarea_field( $_POST['delivery-address'] ) : '';
    $pref_date = isset($_POST['preferred-date']) ? sanitize_text_field( $_POST['preferred-date'] ) : '';
    $pref_time = isset($_POST['preferred-time']) ? sanitize_text_field( $_POST['preferred-time'] ) : '';
    
    $flavor = isset($_POST['cake-flavor']) ? sanitize_text_field( $_POST['cake-flavor'] ) : '';
    $servings = isset($_POST['servings']) ? sanitize_text_field( $_POST['servings'] ) : '';
    $instructions = isset($_POST['instructions']) ? sanitize_textarea_field( $_POST['instructions'] ) : '';
    
    // Insert Post into Database
    $post_args = array(
        'post_title'    => 'Order from ' . $fullname . ' - ' . date('M d, Y'),
        'post_status'   => 'publish',
        'post_type'     => 'custom_cake',
    );

    if ( is_user_logged_in() ) {
        $post_args['post_author'] = get_current_user_id();
    }

    $post_id = wp_insert_post( $post_args );

    if ( $post_id ) {
        // Save Meta Data
        update_post_meta( $post_id, 'melt_cc_fullname', $fullname );
        update_post_meta( $post_id, 'melt_cc_contact', $contact );
        update_post_meta( $post_id, 'melt_cc_email', $email );
        
        update_post_meta( $post_id, 'melt_cc_order_type', $order_type );
        update_post_meta( $post_id, 'melt_cc_delivery_address', $delivery_address );
        update_post_meta( $post_id, 'melt_cc_pref_date', $pref_date );
        update_post_meta( $post_id, 'melt_cc_pref_time', $pref_time );
        
        update_post_meta( $post_id, 'melt_cc_flavor', $flavor );
        update_post_meta( $post_id, 'melt_cc_servings', $servings );
        update_post_meta( $post_id, 'melt_cc_instructions', $instructions );

        // Handle File Upload
        if ( ! empty( $_FILES['cake-sample']['name'] ) ) {
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );

            $attachment_id = media_handle_upload( 'cake-sample', $post_id );

            if ( ! is_wp_error( $attachment_id ) ) {
                update_post_meta( $post_id, 'melt_cc_sample_image_id', $attachment_id );
            }
        }

        // Email to Admin
        $to = get_option( 'admin_email' );
        $subject = 'New Custom Cake Inquiry #' . $post_id . ' from ' . $fullname;
        $message = "New Custom Cake Order Details:\n\n";
        $message .= "View in Admin Panel: " . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . "\n\n";
        $message .= "Client Info:\n";
        $message .= "Name: $fullname\n";
        $message .= "Contact: $contact\n";
        $message .= "Email: $email\n\n";
        
        $message .= "Delivery Details:\n";
        $message .= "Type: $order_type\n";
        if ( $order_type === 'delivery' ) {
            $message .= "Address: $delivery_address\n";
        }
        $message .= "Date: $pref_date\n";
        $message .= "Time: $pref_time\n\n";
        
        $message .= "Cake Details:\n";
        $message .= "Flavor: $flavor\n";
        $message .= "Servings: $servings\n";
        $message .= "Instructions: $instructions\n";
        
        $headers = array( 'Content-Type: text/plain; charset=UTF-8', "Reply-To: $email" );
        
        wp_mail( $to, $subject, $message, $headers );

        // Email to Client
        $client_subject = 'We received your Custom Cake Inquiry! #' . $post_id;
        $client_message = "Hi $fullname,\n\n";
        $client_message .= "Thank you for reaching out to Melt by Locali Gourmet! We have received your custom cake inquiry.\n\n";
        $client_message .= "Our team will review your request and get back to you shortly to confirm details and pricing.\n\n";
        
        $client_message .= "Here is a summary of your request:\n";
        $client_message .= "----------------------------------------\n";
        $client_message .= "Reference ID: #$post_id\n";
        $client_message .= "Preferred Date: $pref_date at $pref_time\n";
        $client_message .= "Flavor: $flavor\n";
        $client_message .= "Servings: $servings\n";
        $client_message .= "----------------------------------------\n\n";
        
        $client_message .= "If you have any questions in the meantime, please reply to this email or contact us via WhatsApp.\n\n";
        $client_message .= "Best regards,\n";
        $client_message .= "Melt Team";
        
        $client_headers = array( 'Content-Type: text/plain; charset=UTF-8', "From: Melt <" . get_option( 'admin_email' ) . ">" );
        
        wp_mail( $email, $client_subject, $client_message, $client_headers );
        
        $submission_status = 'success';
    } else {
        $submission_status = 'error';
    }
}

get_header();

// Enqueue Styles and Scripts for this page
// Note: In a production environment, these should be enqueued via functions.php using wp_enqueue_scripts hook
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/custom-cake-form.css">

<div class="custom-cake-page-wrapper">
    <div class="custom-cake-container">
        
        <?php if ( $submission_status === 'success' ) : ?>
            <div class="success-message" style="text-align: center; padding: 40px;">
                <h2 style="color: #d4a574;">Thank You!</h2>
                <p>Your custom cake inquiry has been sent. We will contact you shortly.</p>
                <a href="<?php echo home_url('/shop'); ?>" class="btn-submit" style="display: inline-block; margin-top: 20px; text-decoration: none;">Back to Shop</a>
            </div>
        <?php else : ?>

            <div class="custom-cake-header">
                <h1>Design Your Cake</h1>
                <p>Let us create something special for you.</p>
            </div>

            <div class="form-progress">
                <div class="progress-step active">1</div>
                <div class="progress-step">2</div>
                <div class="progress-step">3</div>
                <div class="progress-step">4</div>
            </div>

            <form id="custom-cake-form" action="" method="post" enctype="multipart/form-data">
                <?php wp_nonce_field( 'submit_custom_cake', 'custom_cake_nonce' ); ?>

                <!-- Step 1: Client Information -->
                <div class="form-step active" id="step1">
                    <h2 class="step-title">Client Information</h2>
                    <div class="form-group">
                        <label for="fullname">Full Name *</label>
                        <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Enter your full name" required>
                    </div>
                    <div class="form-group">
                        <label for="contact-number">Contact Number *</label>
                        <input type="tel" name="contact-number" id="contact-number" class="form-control" placeholder="Enter your mobile number" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email address" required>
                    </div>
                    <div class="form-navigation" style="justify-content: flex-end;">
                        <button type="button" class="btn-next">Next Step</button>
                    </div>
                </div>

                <!-- Step 2: Delivery Details -->
                <div class="form-step" id="step2">
                    <h2 class="step-title">Delivery Details</h2>
                    <div class="form-group">
                        <label for="order-type">Order Type *</label>
                        <select name="order-type" id="order-type" class="form-control" required>
                            <option value="">Select Option</option>
                            <option value="pickup">Pickup</option>
                            <option value="delivery">Delivery</option>
                        </select>
                    </div>
                    <div class="form-group hidden" id="delivery-address-group">
                        <label for="delivery-address">Delivery Address *</label>
                        <input type="text" name="delivery-address" id="delivery-address" class="form-control" placeholder="Enter full delivery address">
                    </div>
                    <div class="form-group">
                        <label for="preferred-date">Preferred Date *</label>
                        <input type="date" name="preferred-date" id="preferred-date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="preferred-time">Preferred Time *</label>
                        <input type="time" name="preferred-time" id="preferred-time" class="form-control" required>
                    </div>
                    <div class="form-navigation">
                        <button type="button" class="btn-prev">Previous</button>
                        <button type="button" class="btn-next">Next Step</button>
                    </div>
                </div>

                <!-- Step 3: Cake Details -->
                <div class="form-step" id="step3">
                    <h2 class="step-title">Cake Details</h2>
                    <div class="form-group">
                        <label for="cake-flavor">Cake Flavors</label>
                        <input type="text" name="cake-flavor" id="cake-flavor" class="form-control" placeholder="e.g. Vanilla, Chocolate, Red Velvet">
                    </div>
                    <div class="form-group">
                        <label for="servings">Servings</label>
                        <input type="number" name="servings" id="servings" class="form-control" placeholder="Number of guests">
                    </div>
                    <div class="form-group">
                        <label for="cake-sample">Upload Sample/Inspire Photo</label>
                        <input type="file" name="cake-sample" id="cake-sample" class="form-control" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="instructions">Other Instructions</label>
                        <textarea name="instructions" id="instructions" class="form-control" placeholder="Describe your design, colors, or special requirements..."></textarea>
                    </div>
                    <div class="form-navigation">
                        <button type="button" class="btn-prev">Previous</button>
                        <button type="button" class="btn-next">Review Order</button>
                    </div>
                </div>

                <!-- Step 4: Order Confirmation -->
                <div class="form-step" id="step4">
                    <h2 class="step-title">Review & Confirm</h2>
                    
                    <div id="review-container">
                        <!-- JS will populate this -->
                    </div>

                    <div class="form-group" style="margin-top: 30px;">
                        <label style="font-weight: 600;">Terms and Conditions</label>
                        <p style="font-size: 0.9rem; color: #666; margin-bottom: 10px;">
                            By submitting this form, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>. 
                            Please note that this is an inquiry form and your order is not confirmed until we contact you and payment is processed.
                        </p>
                        <div class="checkbox-group">
                            <input type="checkbox" name="consent" id="consent" required>
                            <label for="consent" style="margin-bottom: 0;">I agree to the terms and conditions</label>
                        </div>
                    </div>

                    <div class="form-navigation">
                        <button type="button" class="btn-prev">Previous</button>
                        <button type="submit" class="btn-submit">Submit Inquiry</button>
                    </div>
                </div>

            </form>
        <?php endif; ?>
    </div>
</div>

<script src="<?php echo get_template_directory_uri(); ?>/js/custom-cake-form.js"></script>

<?php get_footer(); ?>
