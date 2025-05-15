<?php
/**
 * Template for "Confirmed" order status email
 *
 * This file should be placed in: your-child-theme/woocommerce/emails/customer-confirmed-order.php
 *
 * @package ChildTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * @hooked WC_Emails::email_header() Outputs the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php printf( esc_html__( 'Hello %s,', 'child-theme' ), esc_html( $order->get_billing_first_name() ) ); ?></p>

<p><?php esc_html_e( 'Thank you! Your order has been confirmed and is now being processed.', 'child-theme' ); ?></p>

<?php

/**
 * Show order details
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show order meta data
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show order items
 */
do_action( 'woocommerce_email_order_items', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show customer details
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Additional content for confirmed orders
 */
?>
<p><?php esc_html_e( 'We will notify you when your order is being prepared for shipment.', 'child-theme' ); ?></p>

<?php

/**
 * @hooked WC_Emails::email_footer() Outputs the email footer
 */
do_action( 'woocommerce_email_footer', $email );