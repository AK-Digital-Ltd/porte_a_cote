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

<?php 
$order_url = $order->get_view_order_url();
$customer_first_name = $order->get_billing_first_name();
$delivery_address = $order->get_shipping_address_1();
?>

<div class="email-content">
    <h1>Merci pour votre commande <span><?php echo esc_html($customer_first_name) ?> !</span></h1>

    <div class="product_route">
        <div class="row dotted">
            <div class="finish">
                <img src="http://carole-la-porte-a-cote.localwp/wp-content/uploads/2025/05/check.png" width="16"
                    height="13" style="margin: 0;" alt="Check Icon">
            </div>
            <p class="highlight">Commande confirmée</p>
        </div>
        <div class="row">
            <div class="in_progress">
                <span>2</span>
            </div>
            <p>Commande en préparation</p>
        </div>
        <div class="row">
            <div class="in_progress">
                <span>3</span>
            </div>
            <p>Commande expédiée</p>
        </div>
        <div class="row">
            <div class="in_progress">
                <span>4</span>
            </div>
            <p>Commande livrée</p>
        </div>
    </div>

    <div class="email-introduction">
        <?php if(!empty($customer_first_name)): ?>
        <p>
            Bonjour <span><?php echo esc_html( $customer_first_name ); ?></span>,
        </p>
        <?php endif; ?>
        <p>
            Merci d’avoir passé commande chez Carole la porte à côté et de nous faire confiance pour votre décoration !
        </p>
    </div>

    <a class="follow-order" href="<?php echo esc_url( $order_url ); ?>">
        Voir ma commande
    </a>

    <div class="delivery-info">
        <p>Vos informations de livraison</p>
        <div class="delivery-info-content">
            <img src="http://carole-la-porte-a-cote.localwp/wp-content/uploads/2025/05/solar_delivery-broken.png"
                width="71" height="69" alt="Delivery Icon">

            <ul>
                <li>Livraison : <span></span></li>
                <li>Mode de livraison : <span>Point relais</span></li>
                <li>Adr. de livraison : <span><?php echo esc_html($delivery_address); ?></span></li>
            </ul>
        </div>
    </div>
</div>
<?php

/**
 * @hooked WC_Emails::email_footer() Outputs the email footer
 */
do_action( 'woocommerce_email_footer', $email );