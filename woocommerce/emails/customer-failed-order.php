<?php
/**
 * Customer failed order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-failed-order.php.
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hook for the woocommerce_email_header.
 *
 * @hooked WC_Emails::email_header() Output the email header
 * @since 3.7.0
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php 
$customer_first_name = $order->get_billing_first_name();
$order_date = $order->get_date_created();
$formatted_date = $order_date ? date_i18n('d/m/Y', $order_date->getTimestamp()) : '';
$total = $order->get_total();
$order_number = $order->get_order_number();
$billing_address_1 = $order->get_billing_address_1();
$billing_postcode = $order->get_billing_postcode();
$billing_city = $order->get_billing_city();
$billing_country = $order->get_billing_country();

$order_url = $order->get_view_order_url();
?>

<div class="email-content">
    <h1>Paiement échoué</h1>
    <div class="email-introduction">
        <?php if(!empty($customer_first_name)): ?>
        <p>
            Bonjour <span><?php echo esc_html( $customer_first_name ); ?></span>,
        </p>
        <?php endif; ?>
        <p>
            Paiement echoué, lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem
            ipsum lorem ipsum lorem ipsum lorem ipsum.
        </p>
    </div>

    <div class="payment-failed">
        <p>Votre paiement à échoué</p>
        <br>
        <p>Le <?php echo esc_html($formatted_date) ?></p>
        <br>
        <p>CAROLE LA PORTE A COTE</p>
        <br>
        <p><?php echo esc_html($billing_address_1) ?></p>
        <p><?php echo esc_html(text: $billing_city) ?></p>
        <p><?php echo esc_html(text: $billing_postcode) ?></p>
        <p><?php echo esc_html(text: $billing_country) ?></p>
        <br>
        <p>MONTANT : <?php echo esc_html($total) ?> EUR</p>
        <p>Numéro de commande : <?php echo esc_html($order_number) ?></p>
        <br>
        <p>Ticket client</p>
        <p>à conserver</p>
    </div>

    <a class="follow-order" href="<?php echo esc_url($order_url) ?>">Voir ma commande</a>
</div>

<?php
/**
* Hook for the woocommerce_email_footer.
*
* @hooked WC_Emails::email_footer() Output the email footer
* @since 3.7.0
*/
do_action( 'woocommerce_email_footer', $email );

?>