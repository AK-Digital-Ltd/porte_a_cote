<?php
/**
 * Admin cancelled order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/admin-cancelled-order.php.
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

if (!defined('ABSPATH')) {
	exit;
}

$email_improvements_enabled = FeaturesUtil::feature_is_enabled('email_improvements');

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email_heading, $email); ?>

<?php
echo $email_improvements_enabled ? '<div class="email-introduction">' : '';
/* translators: %1$s: Order number. %2$s: Customer full name */
?>
<p id="title_order_cancelled">Votre commande a été annulée</p>
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
<p>Veuillez nous excuser pour la gêne occasionnée, votre commande a été annulée.</p>
<a href="<?php echo esc_attr(wc_get_page_permalink('myaccount')); ?>" class="btn_show_account">
	Accéder à mon espace client
</a>


<?php echo $email_improvements_enabled ? '</div>' : ''; ?>

<?php
/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
// do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
// do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */

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
$billing_country_code = $order->get_billing_country();
$billing_country = isset(WC()->countries->countries[$billing_country_code])
	? WC()->countries->countries[$billing_country_code]
	: 'Pays non défini';
$billing_phone = $order->get_billing_phone();

// Données statiques ou à ajuster dynamiquement si besoin
$delivery_date = date_i18n('l d F', strtotime('+2 days')); // Ex : mardi 14 mai
$delivery_mode = 'Livraison à votre domicile';
?>

<div id="billing_address" style="margin-top: 40px; margin-bottom: 20px; font-family: Arial, sans-serif;">
	<h2 style="color: #333; font-size: 18px; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
		Adresse de facturation</h2>
	<div style="padding: 10px 0;">
		<p style="margin: 5px 0; font-size: 14px;"><?php echo $billing_first_name . ' ' . $billing_last_name; ?></p>
		<p style="margin: 5px 0; font-size: 14px;"><?php echo esc_html($billing_address_1); ?>,
			<?php echo esc_html($billing_postcode . ' ' . $billing_city); ?>
		</p>
		<p style="margin: 5px 0; font-size: 14px;"><?php echo $billing_country ?></p>
		<p style="margin: 5px 0; font-size: 14px;"><?php echo $billing_phone ?></p>
	</div>
</div>

<!-- Récapitulatif de commande stylisé -->
<div id="order_summary" style="margin-top: 20px; margin-bottom: 30px; font-family: Arial, sans-serif;">
	<table style="width: 100%; border-collapse: collapse; font-size: 14px;">
		<tr>
			<td colspan="2" style="border-bottom: 1px solid #eee; padding-top: 5px;"></td>
		</tr>
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
<?php
// if ( $additional_content ) {
// 	echo $email_improvements_enabled ? '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td class="email-additional-content">' : '';
// 	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
// 	echo $email_improvements_enabled ? '</td></tr></table>' : '';
// }

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);
