<?php
/**
 * Email Order Items - MODIFIÉ POUR AFFICHAGE PERSONNALISÉ
 *
 * Ce template a été adapté pour afficher les produits avec image, nom, taille et prix.
 *
 * @package WooCommerce\Templates\Emails
 * @version 9.8.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

defined('ABSPATH') || exit;

$email_improvements_enabled = FeaturesUtil::feature_is_enabled('email_improvements');
?>

<!-- Titre personnalisé -->
<h2 style="margin-top: 30px; margin-bottom: 20px; color: #333; font-size: 18px;">Vos
    produits :</h2>

<?php foreach ($items as $item_id => $item): 
    $product = $item->get_product();
    if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
        continue;
    }

    $image = '';
    if (is_object($product)) {
        $image = $product->get_image($image_size);
    }
?>

<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <!-- Image produit -->
        <td align="left" valign="center">
            <?php
            if ($image) {
                echo wp_kses_post($image);
            }
            ?>
            <!-- Nom produit -->
            <span><?php echo wp_kses_post($item->get_name()); ?></span>
        </td>
        <!-- Quantité et Prix -->
        <td align="right" valign="middle">
            <span>
                x<?php echo esc_html($item->get_quantity()); ?>
            </span>
            <span class="price">
                <?php echo wp_kses_post($order->get_formatted_line_subtotal($item)); ?>
            </span>
        </td>
    </tr>
</table>

<?php endforeach; ?>