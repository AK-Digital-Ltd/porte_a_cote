<?php
/**
 * Custom email functions for WooCommerce
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Display order recap with billing address and order summary
 * 
 * @param WC_Order $order The order object
 * @param bool $sent_to_admin Whether the email is being sent to admin
 * @param bool $plain_text Whether the email is plain text
 * @param WC_Email $email The email object
 */
function display_order_recap($order, $sent_to_admin, $plain_text, $email) {
    // Récupération des infos de facturation
    $billing_first_name = $order->get_billing_first_name();
    $billing_last_name = $order->get_billing_last_name();
    $billing_address_1 = $order->get_billing_address_1();
    $billing_postcode = $order->get_billing_postcode();
    $billing_city = $order->get_billing_city();
    $billing_country = $order->get_billing_country();
    $billing_phone = $order->get_billing_phone();
    
    // Récupération des totaux
    $subtotal = $order->get_subtotal() ?: 0;
    $shipping_total = $order->get_shipping_total() ?: 0;
    $discount_total = $order->get_discount_total() ?: 0;
    $total = $order->get_total() ?: 0;
    
    // Vérifie si les frais d'expédition sont inclus
    $shipping_included = ($shipping_total == 0);
    ?>

<div class="order_recap">
    <?php
        /**
         * Show order details
         */
        do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);
        
        /**
         * Show order items
         */
        do_action('woocommerce_email_order_items', $order, $sent_to_admin, $plain_text, $email);
        ?>
</div>
<div class="order_recap">
    <div class="billing_address">
        <h2>Adresse de facturation :</h2>
        <div>
            <p><?php echo esc_html($billing_first_name . ' ' . $billing_last_name); ?></p>
            <p><?php echo esc_html($billing_address_1); ?>,
                <?php echo esc_html($billing_postcode . ' ' . $billing_city); ?>
            </p>
            <p><?php echo esc_html($billing_country); ?></p>
            <p><?php echo esc_html($billing_phone); ?></p>
        </div>
    </div>

    <!-- Récapitulatif de commande stylisé -->
    <div class="order_summary">
        <table>
            <tr>
                <td colspan="2" class="border"></td>
            </tr>
            <!-- Sous-total -->
            <tr>
                <td id="first">Sous total :</td>
                <td id="first"><?php echo wc_price($subtotal); ?></td>
            </tr>

            <!-- Frais d'expédition -->
            <tr>
                <td>Frais d'expédition :</td>
                <td>
                    <?php if ($shipping_included): ?>
                    <span>(inclus) <?php echo wc_price($shipping_total); ?></span>
                    <?php else: ?>
                    <?php echo wc_price($shipping_total); ?>
                    <?php endif; ?>
                </td>
            </tr>

            <!-- Remise -->
            <tr>
                <td>Total remise :</td>
                <td>
                    <?php echo wc_price($discount_total); ?>
                </td>
            </tr>

            <!-- Total TTC -->
            <tr>
                <td class="total">Total TTC</td>
                <td class="total">
                    <?php echo wc_price($total); ?></td>
            </tr>
            <tr>
                <td colspan="2" class="border"></td>
            </tr>
        </table>
    </div>
</div>
<?php
}