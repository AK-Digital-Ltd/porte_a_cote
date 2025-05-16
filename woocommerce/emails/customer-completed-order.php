<?php
/**
 * Template for "Confirmed" order status email
 *
 * This file should be placed in: your-child-theme/woocommerce/emails/customer-confirmed-order.php
 *
 * @package ChildTheme
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * @hooked WC_Emails::email_header() Outputs the email header
 */
do_action('woocommerce_email_header', $email_heading, $email); ?>

<p style="font-size: 24px; font-weight: 600;">
	<?php
	// Récupérer le client actuel
	$current_user = wp_get_current_user();
	$customer_name = $current_user->display_name;

	// Afficher le message
	printf(esc_html__('Merci pour votre commande %s', 'child-theme'), esc_html($order->get_billing_first_name()));
	?><span class="orange_color"><?php echo esc_html($customer_name); ?></span> !
</p>
<div id="product_route" style="display:flex; justify-content: space-between; align-items: start; width: 100%;">
	<div class="container">
		<div class="finish">
			<img src="http://la-porte-cot.local/wp-content/uploads/2025/05/check-icon.svg" style="margin: 0;"
				alt="Check Icon">
		</div>
		<p>Commande confirmée</p>
	</div>
	<div class="container">
		<div class="in_progress">
			<span>2</span>
		</div>
		<p>Commande en préparation</p>
	</div>
	<div class="container">
		<div class="in_progress">
			<span>3</span>
		</div>
		<p>Commande expédiée</p>
	</div>
	<div class="container">
		<div class="in_progress">
			<span>4</span>
		</div>
		<p>Commande livrée</p>
	</div>
</div>
<p><?php esc_html_e('Bonjour', 'child-theme'); ?> <span class="orange_color"
		style="font-weight: 700;"><?php echo esc_html($customer_name); ?></span>,</p>
<p><?php esc_html_e('Merci d\'avoir passé commande chez Carole la porte à côté et de nous faire confiance pour votre décoration !', 'child-theme'); ?>
</p>

<?php

/**
 * Show order details
 */
do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);

/**
 * Show order meta data
 */
// do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);

/**
 * Show order items
 */
do_action('woocommerce_email_order_items', $order, $sent_to_admin, $plain_text, $email);

/**
 * Show customer details
 */
// do_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email);

/**
 * Additional content for confirmed orders
 */
?>
<?php

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
				<?php echo wc_price($total); ?></td>
		</tr>
		<tr>
			<td colspan="2" style="border-bottom: 1px solid #eee; padding-top: 5px;"></td>
		</tr>
	</table>
</div>

<p>À bientôt,</p>
<p>- Carole</p>
<?php

/**
 * @hooked WC_Emails::email_footer() Outputs the email footer
 */
// do_action('woocommerce_email_footer', $email);