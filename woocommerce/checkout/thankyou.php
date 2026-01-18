<?php

/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 */

defined('ABSPATH') || exit;
?>
<!-- MELT CUSTOM THANKYOU TEMPLATE LOADED -->
<div class="woocommerce-order">

	<?php if ($order) : ?>

		<?php if ($order->has_status('failed')) : ?>

			<div class="thankyou-failed-container">
				<div class="thankyou-icon failed">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
					</svg>
				</div>

				<h1 class="thankyou-title"><?php esc_html_e('Order Failed', 'woocommerce'); ?></h1>

				<p class="thankyou-message">
					<?php esc_html_e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce'); ?>
				</p>

				<div class="thankyou-actions">
					<a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button pay"><?php esc_html_e('Pay', 'woocommerce'); ?></a>
					<?php if (is_user_logged_in()) : ?>
						<a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button pay"><?php esc_html_e('My Account', 'woocommerce'); ?></a>
					<?php endif; ?>
				</div>
			</div>

		<?php else : ?>

			<div class="thankyou-success-container">
				<div class="thankyou-header">
					<div class="thankyou-icon success">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
						</svg>
					</div>

					<h1 class="thankyou-title"><?php echo apply_filters('woocommerce_thankyou_order_received_text', esc_html__('Thank you. Your order has been received.', 'woocommerce'), $order); ?></h1>

					<p class="thankyou-subtitle">We have sent a confirmation email to <?php echo esc_html($order->get_billing_email()); ?></p>
				</div>

				<div class="thankyou-order-details-grid">
					<div class="order-overview-card">
						<ul class="order-overview-list">
							<li class="overview-item">
								<span class="label"><?php esc_html_e('Order number:', 'woocommerce'); ?></span>
								<span class="value">#<?php echo $order->get_order_number(); ?></span>
							</li>
							<li class="overview-item">
								<span class="label"><?php esc_html_e('Date:', 'woocommerce'); ?></span>
								<span class="value"><?php echo wc_format_datetime($order->get_date_created()); ?></span>
							</li>
							<li class="overview-item">
								<span class="label"><?php esc_html_e('Total:', 'woocommerce'); ?></span>
								<span class="value"><?php echo $order->get_formatted_order_total(); ?></span>
							</li>
							<?php if ($order->get_payment_method_title()) : ?>
								<li class="overview-item">
									<span class="label"><?php esc_html_e('Payment method:', 'woocommerce'); ?></span>
									<span class="value"><?php echo wp_kses_post($order->get_payment_method_title()); ?></span>
								</li>
							<?php endif; ?>
						</ul>
					</div>

					<div class="order-actions-card">
						<h3>What's Next?</h3>
						<p>Your order is being processed by our artisans. You will receive another email when it is ready for shipping or pickup.</p>
						<a href="<?php echo esc_url(home_url()); ?>" class="continue-shopping-btn">Continue Shopping</a>
					</div>
				</div>

				<div class="thankyou-order-complete-details">
					<?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
				</div>

			</div>

		<?php endif; ?>

	<?php else : ?>

		<div class="thankyou-success-container">
			<h1 class="thankyou-title"><?php echo apply_filters('woocommerce_thankyou_order_received_text', esc_html__('Thank you. Your order has been received.', 'woocommerce'), null); ?></h1>
			<a href="<?php echo esc_url(home_url()); ?>" class="continue-shopping-btn">Continue Shopping</a>
		</div>

	<?php endif; ?>

</div>