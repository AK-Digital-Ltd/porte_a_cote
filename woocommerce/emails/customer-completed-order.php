<?php
/**
 * Customer completed order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-completed-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 9.8.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Inclure le fichier de fonctions personnalisées pour les emails
require_once get_stylesheet_directory() . '/woocommerce/emails/email-functions.php';

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );

/**
 * @hooked WC_Emails::email_header() Outputs the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php 
$order_url = $order->get_view_order_url();
$customer_first_name = $order->get_billing_first_name();

// Sécuriser l'accès aux totaux de la commande
$subtotal = $order->get_subtotal() ?: 0;
$shipping_total = $order->get_shipping_total() ?: 0;
$discount_total = $order->get_discount_total() ?: 0;
$total = $order->get_total() ?: 0;

// Vérifie si les frais d'expédition sont inclus
$shipping_included = ($shipping_total == 0);
// Récupération des infos de facturation de la commande WooCommerce
$billing_first_name = $order->get_billing_first_name();
$billing_last_name = $order->get_billing_last_name();
$billing_address_1 = $order->get_billing_address_1();
$billing_postcode = $order->get_billing_postcode();
$billing_city = $order->get_billing_city();
$billing_country = $order->get_billing_country();
$billing_phone = $order->get_billing_phone();

// Données statiques ou à ajuster dynamiquement si besoin
$delivery_date = date_i18n('l d F', strtotime('+2 days')); // Ex : mardi 14 mai
$delivery_mode = 'Livraison à votre domicile';
?>

<div class="email-content">
    <h1>Merci pour votre commande <span><?php echo esc_html($customer_first_name) ?> !</span></h1>

    <div class="product_route">
        <img src="http://carole-la-porte-a-cote.localwp/wp-content/uploads/2025/05/track-step-1.png" alt="step tracker"
            width="642" height="60" />
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
                <li>Adr. de livraison : <span><?php echo esc_html($billing_address_1); ?>,
                        <?php echo esc_html($billing_postcode . ' ' . $billing_city); ?></span></li>
            </ul>
        </div>
    </div>

    <?php display_order_recap($order, $sent_to_admin, $plain_text, $email); ?>

</div>
<?php

/**
 * @hooked WC_Emails::email_footer() Outputs the email footer
 */
do_action( 'woocommerce_email_footer', $email );