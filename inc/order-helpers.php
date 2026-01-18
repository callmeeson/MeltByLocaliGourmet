<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Helper function to get order status icon
 */
function melt_get_order_status_icon( $status ) {
	$icons = array(
		'pending'    => 'clock',
		'processing' => 'package',
		'on-hold'    => 'pause-circle',
		'completed'  => 'check-circle',
		'cancelled'  => 'x-circle',
		'refunded'   => 'rotate-ccw',
		'failed'     => 'alert-circle',
	);
	
	return isset( $icons[ $status ] ) ? $icons[ $status ] : 'package';
}

