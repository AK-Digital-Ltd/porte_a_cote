<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
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

defined('ABSPATH') || exit;

$text_align = is_rtl() ? 'right' : 'left';

$email_improvements_enabled = FeaturesUtil::feature_is_enabled('email_improvements');
$heading_class = $email_improvements_enabled ? 'email-order-detail-heading' : '';
$order_table_class = $email_improvements_enabled ? 'email-order-details' : '';
$order_total_text_align = $email_improvements_enabled ? 'right' : 'left';

if ($email_improvements_enabled) {
	add_filter('woocommerce_order_shipping_to_display_shipped_via', '__return_false');
}

/**
 * Action hook to add custom content before order details in email.
 *
 * @param WC_Order $order Order object.
 * @param bool     $sent_to_admin Whether it's sent to admin or customer.
 * @param bool     $plain_text Whether it's a plain text email.
 * @param WC_Email $email Email object.
 * @since 2.5.0
 */
do_action('woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email); ?>

<h2 class="recap">
    <?php
	echo wp_kses_post(__('RÉCAPITULATIF DE VOTRE COMMANDE', 'woocommerce'));
	if ($sent_to_admin) {
		$before = '<a class="link" href="' . esc_url($order->get_edit_order_url()) . '">';
		$after = '</a>';
	} else {
		$before = '';
		$after = '';
	}
	echo '<br><span>';
	?>
</h2>

<?php
/* translators: %s: Order ID. */
$email_improvements_enabled = \Automattic\WooCommerce\Utilities\FeaturesUtil::feature_is_enabled('email_improvements');
$order_number_string = $email_improvements_enabled ? __('N° de commande #%s', 'woocommerce') : __(
	'N° de commande #%s',
	'woocommerce'
);

$order_number = sprintf(
	esc_html($order_number_string),
	$order->get_order_number()
);
$order_date = esc_html(date_i18n('d/m/Y', $order->get_date_created()->getTimestamp()));
/* translators: %s: Order ID. */
$email_improvements_enabled = \Automattic\WooCommerce\Utilities\FeaturesUtil::feature_is_enabled('email_improvements');
$order_number_string = $email_improvements_enabled ? __('N° de commande #%s', 'woocommerce') : __(
	'N° de commande #%s',
	'woocommerce'
);

$order_number = sprintf(
	esc_html($order_number_string),
	$order->get_order_number()
);
$order_date = esc_html(date_i18n('d/m/Y', $order->get_date_created()->getTimestamp()));

?>
<div>
    <p style="margin: 0 0 8px 0;"><strong><?php echo $order_number; ?></strong></p>
    <p style="margin: 0;">Passée le : <span><?php echo $order_date; ?></span></p>
</div>

<div>
    <table class="order_details td font-family <?php echo esc_attr($order_table_class); ?> " cellspacing="0"
        cellpadding="6" style="width: 100%;" border="0">
        <tbody>
            <?php
			$image_size = 50;
			echo wc_get_email_order_items( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$order,
				array(
					'show_sku' => $sent_to_admin,
					'show_image' => $email_improvements_enabled,
					'image_size' => array($image_size, $image_size),
					'plain_text' => $plain_text,
					'sent_to_admin' => $sent_to_admin,
				)
			);
			?>
        </tbody>
        <tfoot>
            <?php
			$item_totals = $order->get_order_item_totals();
			$item_totals_count = count($item_totals);

			if ($item_totals) {
				$i = 0;
				foreach ($item_totals as $total) {
					$i++;
					$last_class = ($i === $item_totals_count) ? ' order-totals-last' : '';
					?>
            <tr
                class="order-totals order-totals-<?php echo esc_attr($total['type'] ?? 'unknown'); ?><?php echo esc_attr($last_class); ?>">
                <th class="td text-align-left" scope="row" colspan="2"
                    style="<?php echo (1 === $i) ? 'border-top-width: 4px;' : ''; ?>">
                    <?php
							echo wp_kses_post($total['label']) . ' ';
							if ($email_improvements_enabled) {
								echo isset($total['meta']) ? wp_kses_post($total['meta']) : '';
							}
							?>
                </th>
                <td class="td text-align-<?php echo esc_attr($order_total_text_align); ?>"
                    style="<?php echo (1 === $i) ? 'border-top-width: 4px;' : ''; ?>">
                    <?php echo wp_kses_post($total['value']); ?>
                </td>
            </tr>
            <?php
				}
			}
			if ($order->get_customer_note() && !$email_improvements_enabled) {
				?>
            <tr>
                <th class="td text-align-left" scope="row" colspan="2"><?php esc_html_e('Note:', 'woocommerce'); ?>
                </th>
                <td class="td text-align-left">
                    <?php echo wp_kses(nl2br(wptexturize($order->get_customer_note())), array()); ?>
                </td>
            </tr>
            <?php
			}
			if ($order->get_customer_note() && $email_improvements_enabled) {
				?>
            <tr class="order-customer-note">
                <td class="td text-align-left" colspan="3">
                    <b><?php esc_html_e('Customer note', 'woocommerce'); ?></b><br>
                    <?php echo wp_kses(nl2br(wptexturize($order->get_customer_note())), array('br' => array())); ?>
                </td>
            </tr>
            <?php
			}
			?>
        </tfoot>
    </table>
</div>

<?php
if ($email_improvements_enabled) {
	remove_filter('woocommerce_order_shipping_to_display_shipped_via', '__return_false');
}

/**
 * Action hook to add custom content after order details in email.
 *
 * @param WC_Order $order Order object.
 * @param bool     $sent_to_admin Whether it's sent to admin or customer.
 * @param bool     $plain_text Whether it's a plain text email.
 * @param WC_Email $email Email object.
 * @since 2.5.0
 */
do_action('woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email);
?>