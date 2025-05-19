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

$email_improvements_enabled = FeaturesUtil::feature_is_enabled('email_improvements');

/**
 * Executes the e-mail header.
 *
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email_heading, $email); ?>

<?php echo $email_improvements_enabled ? '<div class="email-introduction">' : ''; ?>
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
		<p><span class="label_infos">Livrée le :</span> <?php echo esc_html($formatted_date); ?></p>
		<p><span class="label_infos">Mode de livraison :</span> Livraison à votre domicile</p>
		<div id="container_address">
			<div>
				<p class="label_infos">Adr. de livraison</p>
				<p><?php echo esc_html($shipping_first_name . ' ' . $shipping_last_name); ?></p>
				<p><?php echo esc_html($shipping_address_1); ?>,
					<?php echo esc_html($shipping_postcode . ' ' . $shipping_city); ?>
				</p>
				<p><?php echo esc_html($shipping_country); ?></p>
			</div>

			<div>
				<p class="label_infos">Adr. de facturation</p>
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
<?php echo $email_improvements_enabled ? '</div>' : ''; ?>

<?php

/**
 * Hook for the woocommerce_email_order_details.
 *
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);

/**
 * Hook for the woocommerce_email_order_meta.
 *
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
// do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/**
 * Hook for woocommerce_email_customer_details.
 *
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
// do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
// if ( $additional_content ) {
// 	echo $email_improvements_enabled ? '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td class="email-additional-content">' : '';
// 	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
// 	echo $email_improvements_enabled ? '</td></tr></table>' : '';
// }

// Sécuriser l'accès aux totaux de la commande
$subtotal = $order->get_subtotal() ?: 0;
$shipping_total = $order->get_shipping_total() ?: 0;
$discount_total = $order->get_discount_total() ?: 0;
$total = $order->get_total() ?: 0;

// Vérifie si les frais d'expédition sont inclus
$shipping_included = ($shipping_total == 0);
// Récupération des infos de facturation de la commande WooCommerce
?>

<!-- Récapitulatif de commande stylisé -->
<div id="order_summary" style="margin-top: 20px; margin-bottom: 30px; font-family: Arial, sans-serif;">
	<table style="width: 100%; border-collapse: collapse; font-size: 14px;">
		<!-- Sous-total -->
		<tr>
			<td style="padding: 8px 0; text-align: left; color: #666;">Sous total :</td>
			<td style="padding: 8px 0; text-align: right; font-weight: normal;"><?php echo wc_price($subtotal); ?></td>
		</tr>

		<!-- Frais d'expédition -->
		<tr>
			<td style="padding: 8px 0; text-align: left; color: #666;">Frais d'expédition :</td>
			<td style="padding: 8px 0; text-align: right; font-weight: normal;">
				<?php if ($shipping_included): ?>
					<span style="color: #666;">(inclus) <?php echo wc_price($shipping_total); ?></span>
				<?php else: ?>
					<?php echo wc_price($shipping_total); ?>
				<?php endif; ?>
			</td>
		</tr>

		<!-- Remise -->
		<tr>
			<td style="padding: 8px 0; text-align: left; color: #666;">Total remise :</td>
			<td style="padding: 8px 0; text-align: right; font-weight: normal;"><?php echo wc_price($discount_total); ?>
			</td>
		</tr>

		<!-- Ligne séparatrice avant le total -->


		<!-- Total TTC -->
		<tr>
			<td style="padding: 12px 0; text-align: left; font-weight: bold; font-size: 16px;">Total TTC</td>
			<td style="padding: 12px 0; text-align: right; font-weight: bold; font-size: 16px;">
				<?php echo wc_price($total); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="border-bottom: 1px solid #eee; padding-top: 5px;"></td>
		</tr>
	</table>
</div>

<p>À bientôt,</p>

<?php
/**
 * Executes the email footer.
 *
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);
