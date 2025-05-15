<?php
/**
 * Email template for "Shipped" status
 *
 * This file should be placed in: your-child-theme/woocommerce/emails/customer-shipped-order.php
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

<p><?php esc_html_e( 'We are pleased to inform you that your order has been shipped!', 'child-theme' ); ?></p>

<p><?php 
    printf( 
        esc_html__( 'Your tracking number is: %s', 'child-theme' ), 
        '<strong>' . esc_html( apply_filters( 'child_theme_tracking_number', '', $order ) ) . '</strong>'
    ); 
?></p>

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
 * @hooked WC_Emails::email_footer() Outputs the email footer
 */
do_action( 'woocommerce_email_footer', $email );