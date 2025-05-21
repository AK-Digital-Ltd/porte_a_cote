<?php
/**
 * Customer invoice email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-invoice.php.
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

use Automattic\WooCommerce\Enums\OrderStatus;
use Automattic\WooCommerce\Utilities\FeaturesUtil;

if (!defined('ABSPATH')) {
	exit;
}

require_once get_stylesheet_directory() . '/woocommerce/emails/email-functions.php';

$email_improvements_enabled = FeaturesUtil::feature_is_enabled('email_improvements');

/**
 * Executes the e-mail header.
 *
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email_heading, $email); ?>

<p id="title_invoice">Votre facture</p>
<p>Bonjour
    <span class="orange_color" style="font-weight: 600;">
        <?php

		if (!empty($order->get_billing_first_name())) {
			/* translators: %s: Customer first name */
			printf(esc_html__('%s,', 'woocommerce'), esc_html($order->get_billing_first_name()));
		} else {
			printf(esc_html__('Hi,', 'woocommerce'));
		}
		?>
    </span>
</p>
<p>Veuillez trouver ci-joint la facture de votre commande.</p>
<a href="<?php echo esc_attr(wc_get_page_permalink('myaccount')); ?>" class="btn_show_account">
    Accéder à mon espace client
</a>

<?php
$billing_first_name = $order->get_billing_first_name();
$billing_last_name = $order->get_billing_last_name();
$billing_address_1 = $order->get_billing_address_1();
$billing_postcode = $order->get_billing_postcode();
$billing_city = $order->get_billing_city();
$billing_country_code = $order->get_billing_country();
$billing_country = isset(WC()->countries->countries[$billing_country_code])
	? WC()->countries->countries[$billing_country_code]
	: 'Pays non défini';
$billing_phone = $order->get_billing_phone();

$shipping_first_name = $order->get_shipping_first_name();
$shipping_last_name = $order->get_shipping_last_name();
$shipping_address_1 = $order->get_shipping_address_1();
$shipping_postcode = $order->get_shipping_postcode();
$shipping_city = $order->get_shipping_city();
$shipping_country_code = $order->get_shipping_country();
$shipping_country = isset(WC()->countries->countries[$shipping_country_code])
	? WC()->countries->countries[$shipping_country_code]
	: 'Pays non défini';

setlocale(LC_TIME, 'fr_FR.utf8', 'fra'); // S'assurer que la locale FR est utilisée
$date = $order->get_date_completed() ?: $order->get_date_created(); // Date de livraison (ou création si livraison non complétée)
$formatted_date = strftime('%A %d %B', $date->getTimestamp());
$formatted_date = ucfirst($formatted_date); // Met la première lettre en majuscule
?>

<div id="delivery_container">
    <h2>VOS INFORMATIONS DE LIVRAISON</h2>
    <div id="delivery_infos">
		<img src="http://la-porte-cot.local/wp-content/uploads/2025/05/solar_delivery-broken.png" alt="icon livraison">
        <p><span class="label_infos">Livrée le :</span> <?php echo esc_html($formatted_date); ?></p>
        <p><span class="label_infos">Mode de livraison :</span> Livraison à votre domicile</p>
        <div id="container_address">
            <div>
                <p class="label_infos">Adr. de livraison :</p>
                <p><?php echo esc_html($shipping_first_name . ' ' . $shipping_last_name); ?></p>
                <p><?php echo esc_html($shipping_address_1); ?>,
                    <?php echo esc_html($shipping_postcode . ' ' . $shipping_city); ?>
                </p>
                <p><?php echo esc_html($shipping_country); ?></p>
            </div>

            <div>
                <p class="label_infos">Adr. de facturation :</p>
                <p><?php echo esc_html($billing_first_name . ' ' . $billing_last_name); ?></p>
                <p><?php echo esc_html($billing_address_1); ?>,
                    <?php echo esc_html($billing_postcode . ' ' . $billing_city); ?>
                </p>
                <p><?php echo esc_html($billing_country); ?></p>
                <p><?php echo esc_html($billing_phone); ?></p>
            </div>
        </div>
    </div>
</div>

<?php if ($order->needs_payment()) { ?>
<p>
    <?php
		if ($order->has_status(OrderStatus::FAILED)) {
			printf(
				wp_kses(
					/* translators: %1$s Site title, %2$s Order pay link */
					__(''),
					array(
						'a' => array(
							'href' => array(),
						),
					)
				),
				esc_html(get_bloginfo('name', 'display')),
				'<a href="' . esc_url($order->get_checkout_payment_url()) . '">' . esc_html__('Pay for this order', 'woocommerce') . '</a>'
			);
		} else {
			printf(
				wp_kses(
					/* translators: %1$s Site title, %2$s Order pay link */
					__('', 'woocommerce'),
					array(
						'a' => array(
							'href' => array(),
						),
					)
				),
				esc_html(get_bloginfo('name', 'display')),
				'<a href="' . esc_url($order->get_checkout_payment_url()) . '">' . esc_html__('Pay for this order', 'woocommerce') . '</a>'
			);
		}
		?>
</p>

<?php } else { ?>
<p>
    <?php
		/* translators: %s Order date */
		printf(esc_html__('', 'woocommerce'), esc_html(wc_format_datetime($order->get_date_created())));
		?>
</p>
<?php
}
?>


<?php display_order_recap($order, $sent_to_admin, $plain_text, $email); ?>

<?php
/**
 * Executes the email footer.
 *
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);