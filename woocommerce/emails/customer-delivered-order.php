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

<?php 
$order_url = $order->get_view_order_url();
$customer_first_name = $order->get_billing_first_name();
$order_id = $order->get_id();
$order_date = $order->get_date_created();
$formatted_date = $order_date ? date_i18n('d/m/Y', $order_date->getTimestamp()) : '';
$shipping_method = $order->get_shipping_method();

?>

<div class="email-content">
    <h1>Votre commande est arrivée <span><?php echo esc_html($customer_first_name) ?> !</span></h1>

    <div class="email-introduction">
        <?php if(!empty($customer_first_name)): ?>
        <p>
            Bonjour <span><?php echo esc_html( $customer_first_name ); ?></span>,
        </p>
        <?php endif; ?>
        <p>
            Votre commande est arrivé, lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem
            ipsum lorem ipsum lorem ipsum lorem ipsum.
        </p>
    </div>

    <a class="follow-order" href="<?php echo esc_url( $order_url ); ?>">
        Suivre ma commande
    </a>

    <div class="tracking-info">
        <span>Informations de suivi</span>
        <div>
            <ul>
                <li>Numéro du colis : <span></span></li>
                <li>Numéro de la commande : <span><?php echo esc_html($order_id); ?></span></li>
                <li>Date de commande : <span><?php echo esc_html($formatted_date); ?></span></li>

            </ul>

            <ul>
                <li>Mode de livraison : <span><?php echo esc_html($shipping_method); ?></span></li>
                <li>Point relais : <span><?php echo esc_html($shipping_method); ?></span></li>
            </ul>
        </div>
    </div>
</div>
<?php

/**
 * @hooked WC_Emails::email_footer() Outputs the email footer
 */
do_action( 'woocommerce_email_footer', $email );