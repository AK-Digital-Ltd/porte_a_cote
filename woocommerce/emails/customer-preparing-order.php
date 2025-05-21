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

<?php 
$order_url = $order->get_view_order_url();
$customer_first_name = $order->get_billing_first_name();
?>

<div class="email-content">
    <img src="http://carole-la-porte-a-cote.localwp/wp-content/uploads/2025/05/box.png" width="105" height="135" />
    <h1>Nous préparons votre commande <span><?php echo esc_html($customer_first_name) ?> !</span></h1>

    <div class="product_route">
        <img src="http://carole-la-porte-a-cote.localwp/wp-content/uploads/2025/05/tracker-step-2.png"
            alt="step tracker" width="642" height="60" />
    </div>

    <div class="email-introduction">
        <?php if(!empty($customer_first_name)): ?>
        <p>
            Bonjour <span><?php echo esc_html( $customer_first_name ); ?></span>,
        </p>
        <?php endif; ?>
        <p>
            Nous préparons votre commande, lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem
            ipsum lorem ipsum lorem ipsum lorem ipsum.
        </p>
    </div>

    <a class="follow-order" href="<?php echo esc_url( $order_url ); ?>">
        Voir ma commande
    </a>
</div>
<?php

/**
 * @hooked WC_Emails::email_footer() Outputs the email footer
 */
do_action( 'woocommerce_email_footer', $email );