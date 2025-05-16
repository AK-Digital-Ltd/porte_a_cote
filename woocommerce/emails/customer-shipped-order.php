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


<div class="email-content">
    <div class="email-introduction">
        <p>
            Bonjour, <span><?php echo esc_html( $order->get_billing_first_name() ); ?></span>,
        </p>

        <p>
            Nous préparons votre commande, lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem
            ipsum
            lorem ipsum lorem ipsum lorem ipsum.
        </p>
    </div>


    <a class="follow-order" href="<?php echo esc_url( $order->get_view_order_url() ); ?>"
        style="text-decoration: none; color: #fff;">
        Suivre ma commande
    </a>
</div>




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