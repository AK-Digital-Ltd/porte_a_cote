<?php

/**
 * Ajouter un statut de commande personnalisé "En préparation" à WooCommerce
 * À ajouter dans le fichier functions.php de votre thème ou dans un plugin personnalisé
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
    exit;
}

// Enregistrer le nouveau statut de commande
function ajouter_statut_en_preparation() {
    register_post_status('wc-en-preparation', array(
        'label'                     => _x('En préparation', 'Statut de commande', 'woocommerce'),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('En préparation <span class="count">(%s)</span>', 'En préparation <span class="count">(%s)</span>', 'woocommerce')
    ));
}
add_action('init', 'ajouter_statut_en_preparation');

// Ajouter le nouveau statut à la liste des statuts de WooCommerce
// Ajouter le statut à la liste de WooCommerce, après "processing"
function ajouter_statut_en_preparation_a_wc($order_statuses) {
    $nouveaux_statuts = array();

    foreach ($order_statuses as $key => $label) {
        $nouveaux_statuts[$key] = $label;

        if ('wc-processing' === $key) {
            $nouveaux_statuts['wc-en-preparation'] = _x('En préparation', 'Statut de commande', 'woocommerce');
        }
    }

    return $nouveaux_statuts;
}
add_filter('wc_order_statuses', 'ajouter_statut_en_preparation_a_wc');

// Personnaliser la couleur du statut dans l'administration (optionnel)
function ajouter_style_en_preparation() {
    echo '<style>
        mark.order-status.status-en-preparation {
            background-color: #f8dda7;
            color: #94660c;
        }
    </style>';
}
add_action('admin_head', 'ajouter_style_en_preparation');

// Ajouter des actions de commande pour le nouveau statut (optionnel)
function ajouter_emails_statut_en_preparation($email_actions) {
    $email_actions[] = 'woocommerce_order_status_processing_to_en-preparation';
    $email_actions[] = 'woocommerce_order_status_on-hold_to_en-preparation';
    return $email_actions;
}
add_filter('woocommerce_email_actions', 'ajouter_emails_statut_en_preparation');

// Créer une notification par email pour ce statut (optionnel)
function envoyer_email_en_preparation($order_id, $order = false) {
    // Si vous souhaitez envoyer un email au client lorsque la commande passe à "En préparation"
    // Ajoutez votre code ici ou utilisez le système d'emails de WooCommerce
}
add_action('woocommerce_order_status_processing_to_en-preparation', 'envoyer_email_en_preparation', 10, 2);
add_action('woocommerce_order_status_on-hold_to_en-preparation', 'envoyer_email_en_preparation', 10, 2);

add_action('init', function () {
    $order = wc_get_order(13); // Remplace 123 par l'ID d'une commande
    if ($order && $order->get_status() !== 'en-preparation') {
        $order->update_status('en-preparation');
    }
});
class WC_Emails_Transactionnels {

    /**
     * Constructeur
     */
    public function __construct() {
        // Initialiser les hooks
        $this->init_hooks();
    }

    /**
     * Initialiser les hooks WordPress
     */
    public function init_hooks() {
        // Hook pour envoyer l'email lorsqu'une commande passe au statut "completed"
        add_action('woocommerce_order_status_changed', array($this, 'check_order_status'), 10, 4);
        
        // Ajouter une option dans les réglages WooCommerce
        add_filter('woocommerce_email_settings', array($this, 'add_email_settings'));
    }

    /**
     * Vérifier le changement de statut de la commande
     *
     * @param int $order_id ID de la commande
     * @param string $old_status Ancien statut
     * @param string $new_status Nouveau statut
     * @param object $order Objet de la commande
     */
    public function check_order_status($order_id, $old_status, $new_status, $order) {
        // Vérifier si le nouveau statut est "completed" (terminé)
        if ($new_status === 'completed') {
            // Vérifier la méthode de paiement
            $payment_method = $order->get_payment_method();
            
            // Continuer seulement si la méthode de paiement est CB ou PayPal
            if ($payment_method === 'stripe' || $payment_method === 'paypal') {
                $this->send_transaction_email($order);
            }
        }
    }

    /**
     * Envoyer l'email transactionnel
     *
     * @param WC_Order $order Objet de la commande
     */
    public function send_transaction_email($order) {
        // Récupérer les données de la commande
        $order_id = $order->get_id();
        $customer_email = $order->get_billing_email();
        $customer_name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
        $payment_method = $order->get_payment_method_title();
        $order_total = $order->get_total();
        $currency = $order->get_currency();
        
        // Construire le sujet de l'email
        $subject = sprintf(
            __('Confirmation de votre commande #%s - %s', 'emails-transactionnels-woo'),
            $order_id,
            get_bloginfo('name')
        );
        
        // Obtenir le template d'email
        $email_content = $this->get_email_template($order);
        
        // En-têtes de l'email
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>'
        );
        
        // Envoyer l'email
        wp_mail($customer_email, $subject, $email_content, $headers);
        
        // Enregistrer une note dans la commande
        $order->add_order_note(
            __('Email de confirmation transactionnel envoyé au client.', 'emails-transactionnels-woo')
        );
    }
    
    /**
     * Récupérer le template de l'email
     *
     * @param WC_Order $order Objet de la commande
     * @return string Le contenu HTML de l'email
     */
    public function get_email_template($order) {
        // Récupérer les données de la commande
        $order_id = $order->get_id();
        $customer_name = $order->get_billing_first_name();
        $payment_method = $order->get_payment_method_title();
        $order_total = wc_price($order->get_total(), array('currency' => $order->get_currency()));
        $order_items = $order->get_items();
        $order_date = $order->get_date_created()->date_i18n(get_option('date_format'));
        $shipping_method = $order->get_shipping_method();
        $order_url = $order->get_view_order_url();
        
        // Initialiser le contenu HTML
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>' . __('Confirmation de commande', 'emails-transactionnels-woo') . '</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333333;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                .header {
                    text-align: center;
                    padding: 20px 0;
                    border-bottom: 1px solid #eeeeee;
                }
                .content {
                    padding: 20px 0;
                }
                .order-details {
                    background-color: #f9f9f9;
                    padding: 15px;
                    margin: 20px 0;
                    border-radius: 4px;
                }
                .products {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                .products th, .products td {
                    padding: 10px;
                    border-bottom: 1px solid #eeeeee;
                    text-align: left;
                }
                .footer {
                    text-align: center;
                    padding: 20px 0;
                    font-size: 12px;
                    color: #777777;
                    border-top: 1px solid #eeeeee;
                }
                .button {
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #4CAF50;
                    color: white !important;
                    text-decoration: none;
                    border-radius: 4px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>' . __('Commande confirmée', 'emails-transactionnels-woo') . '</h1>
                </div>
                
                <div class="content">
                    <p>' . sprintf(__('Bonjour %s,', 'emails-transactionnels-woo'), $customer_name) . '</p>
                    
                    <p>' . sprintf(__('Nous vous confirmons que votre commande #%s a bien été reçue et payée par %s. Votre commande est maintenant terminée.', 'emails-transactionnels-woo'), $order_id, $payment_method) . '</p>
                    
                    <div class="order-details">
                        <h3>' . __('Détails de la commande', 'emails-transactionnels-woo') . '</h3>
                        <p><strong>' . __('Numéro de commande:', 'emails-transactionnels-woo') . '</strong> #' . $order_id . '</p>
                        <p><strong>' . __('Date:', 'emails-transactionnels-woo') . '</strong> ' . $order_date . '</p>
                        <p><strong>' . __('Méthode de paiement:', 'emails-transactionnels-woo') . '</strong> ' . $payment_method . '</p>
                        <p><strong>' . __('Méthode d\'expédition:', 'emails-transactionnels-woo') . '</strong> ' . $shipping_method . '</p>
                        <p><strong>' . __('Total:', 'emails-transactionnels-woo') . '</strong> ' . $order_total . '</p>
                    </div>
                    
                    <h3>' . __('Produits commandés', 'emails-transactionnels-woo') . '</h3>
                    <table class="products">
                        <thead>
                            <tr>
                                <th>' . __('Produit', 'emails-transactionnels-woo') . '</th>
                                <th>' . __('Quantité', 'emails-transactionnels-woo') . '</th>
                                <th>' . __('Prix', 'emails-transactionnels-woo') . '</th>
                            </tr>
                        </thead>
                        <tbody>';

        // Ajouter chaque produit
        foreach ($order_items as $item_id => $item) {
            $product = $item->get_product();
            $item_name = $item->get_name();
            $item_qty = $item->get_quantity();
            $item_total = wc_price($item->get_total(), array('currency' => $order->get_currency()));
            
            $html .= '<tr>
                <td>' . $item_name . '</td>
                <td>' . $item_qty . '</td>
                <td>' . $item_total . '</td>
            </tr>';
        }

        $html .= '</tbody>
                    </table>
                    
                    <p style="text-align: center;">
                        <a href="' . $order_url . '" class="button">' . __('Voir les détails de ma commande', 'emails-transactionnels-woo') . '</a>
                    </p>
                    
                    <p>' . __('Merci pour votre achat!', 'emails-transactionnels-woo') . '</p>
                    <p>' . __('N\'hésitez pas à nous contacter si vous avez des questions.', 'emails-transactionnels-woo') . '</p>
                </div>
                
                <div class="footer">
                    <p>' . get_bloginfo('name') . ' - ' . get_bloginfo('url') . '</p>
                    <p>' . sprintf(__('© %s Tous droits réservés.', 'emails-transactionnels-woo'), date('Y')) . '</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Ajouter des paramètres aux réglages emails de WooCommerce
     *
     * @param array $settings Réglages actuels
     * @return array Réglages modifiés
     */
    public function add_email_settings($settings) {
        // Ajouter une section pour nos réglages
        $custom_settings = array(
            array(
                'title' => __('Emails Transactionnels Personnalisés', 'emails-transactionnels-woo'),
                'type'  => 'title',
                'desc'  => __('Configurez les emails transactionnels pour les commandes terminées payées par CB ou PayPal.', 'emails-transactionnels-woo'),
                'id'    => 'custom_transactional_emails',
            ),
            array(
                'title'   => __('Activer/Désactiver', 'emails-transactionnels-woo'),
                'desc'    => __('Activer les emails transactionnels personnalisés', 'emails-transactionnels-woo'),
                'id'      => 'custom_transactional_emails_enabled',
                'default' => 'yes',
                'type'    => 'checkbox',
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'custom_transactional_emails',
            ),
        );
        
        // Insérer nos réglages après le premier élément (titre de section)
        array_splice($settings, 1, 0, $custom_settings);
        
        return $settings;
    }
}

// Initialiser la classe
$wc_emails_transactionnels = new WC_Emails_Transactionnels();