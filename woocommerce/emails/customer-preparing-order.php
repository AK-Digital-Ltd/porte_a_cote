<?php
/**
 * Template for "Preparing" order status email
 *
 * This file should be placed in: your-child-theme/woocommerce/emails/customer-preparing-order.php
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

<p><?php esc_html_e( 'Great news! Your order is now being prepared for shipment.', 'child-theme' ); ?></p>

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
 * Additional content for preparing orders
 */
?>
<p><?php esc_html_e( 'We are carefully packaging your items and will notify you once your order has been shipped.', 'child-theme' ); ?></p>

<?php

/**
 * @hooked WC_Emails::email_footer() Outputs the email footer
 */
do_action( 'woocommerce_email_footer', $email );