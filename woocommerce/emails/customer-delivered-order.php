<?php
/**
 * Template for "Delivered" order status email
 *
 * This file should be placed in: your-child-theme/woocommerce/emails/customer-delivered-order.php
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

<p><?php esc_html_e( 'Your order has been delivered successfully! We hope you enjoy your purchase.', 'child-theme' ); ?></p>

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
 * Additional content for delivered orders
 */
?>
<p><?php esc_html_e( 'If you have any questions or feedback about your order, please don\'t hesitate to contact us.', 'child-theme' ); ?></p>

<?php
if ($order->get_id()) {
    $review_link = add_query_arg(array(
        'order_id' => $order->get_id(),
        'review_request' => true
    ), site_url());
    ?>
    <p><?php printf(esc_html__('We value your opinion! %sLeave a review%s about your purchase experience.', 'child-theme'), 
        '<a href="' . esc_url($review_link) . '">', '</a>'); ?></p>
    <?php
}
?>

<?php

/**
 * @hooked WC_Emails::email_footer() Outputs the email footer
 */
do_action( 'woocommerce_email_footer', $email );