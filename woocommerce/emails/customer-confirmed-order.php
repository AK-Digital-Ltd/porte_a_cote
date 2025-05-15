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
    printf(esc_html__('Merci pour votre commande %s,', 'child-theme'), esc_html($order->get_billing_first_name()));
    ?>
    <span style="color: #BA5C33;"><?php echo esc_html($customer_name); ?></span>
</p>
<div style="display:flex; grid-template-columns: repeat(4, 1fr); grid-template-rows: repart(1, 1fr); width: 100%;">
    <div
        style="display: flex; justify-content: center; align-items: center; flex-direction: column; text-align: center;">
        <div
            style="display: flex; justify-content: center; align-items: center; gap: 8px; width: 33px; height: 33px; background-color: #BA5C33; border-radius: 50%;">
            <img src="http://la-porte-cot.local/wp-content/uploads/2025/05/check-icon.svg" style="margin: 0;"
                alt="Check Icon">
        </div>
        <p style="font-weight: 600; color: #BA5C33;">Commande confirmée</p>
    </div>
    <div
        style="display: flex; justify-content: center; align-items: center; flex-direction: column; text-align: center;">
        <div
            style="display: flex; justify-content: center; align-items: center; flex-direction: column; gap: 8px; width: 33px; height: 33px; border-radius: 50%; border: 1px solid black;">
            <span>2</span>
        </div>
        <p>Commande en préparation</p>
    </div>
    <div
        style="display: flex; justify-content: center; align-items: center; flex-direction: column; text-align: center;">
        <div
            style="display: flex; justify-content: center; align-items: center; flex-direction: column; gap: 8px; width: 33px; height: 33px; border-radius: 50%; border: 1px solid black;">
            <span>3</span>
        </div>
        <p>Commande expédiée</p>
    </div>
    <div
        style="display: flex; justify-content: center; align-items: center; flex-direction: column; text-align: center;">
        <div
            style="display: flex; justify-content: center; align-items: center; flex-direction: column; gap: 8px; width: 33px; height: 33px; border-radius: 50%; border: 1px solid black;">
            <span>4</span>
        </div>
        <p>Commande livrée</p>
    </div>
</div>
<p><?php esc_html_e('Bonjour', 'child-theme'); ?> <span class="orange_color"
        style="font-weight: 700;"><?php echo esc_html($customer_name); ?></span> ,</p>
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
do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);

/**
 * Show order items
 */
do_action('woocommerce_email_order_items', $order, $sent_to_admin, $plain_text, $email);

/**
 * Show customer details
 */
do_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email);

/**
 * Additional content for confirmed orders
 */
?>
<p><?php esc_html_e('We will notify you when your order is being prepared for shipment.', 'child-theme'); ?></p>

<?php

/**
 * @hooked WC_Emails::email_footer() Outputs the email footer
 */
do_action('woocommerce_email_footer', $email);