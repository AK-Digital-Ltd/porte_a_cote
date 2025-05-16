<?php
/**
 * Fonctions du thème enfant
 */

// Charge les styles du thème parent et enfant
function hello_elementor_child_enqueue_styles() {
	// Style du thème parent
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

	// Style du thème enfant
	wp_enqueue_style(
		'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'parent-style' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_styles' );

// Votre code personnalisé pourra être ajouté ici

/**
 * Personnalisation des dimensions dans l'onglet "Informations additionnelles"
 * Affiche Hauteur, Longueur, Largeur séparément au lieu d'une ligne unique.
 * Compatible avec WooCommerce + Elementor (widget Informations additionnelles).
 */
add_filter( 'woocommerce_display_product_attributes', 'custom_dimensions_display', 10, 2 );

function custom_dimensions_display( $product_attributes, $product ) {

	if ( $product->has_dimensions() ) {

		$height = wc_format_localized_decimal( $product->get_height() );
		$width  = wc_format_localized_decimal( $product->get_width() );
		$length = wc_format_localized_decimal( $product->get_length() );
		$unit   = get_option( 'woocommerce_dimension_unit' );
		unset( $product_attributes['dimensions'] );
		$product_attributes['hauteur'] = array(
			'label' => __( 'Hauteur', 'woocommerce' ) . " ($unit)",
			'value' => $height,
		);

		$product_attributes['longueur'] = array(
			'label' => __( 'Longueur', 'woocommerce' ) . " ($unit)",
			'value' => $length,
		);

		$product_attributes['largeur'] = array(
			'label' => __( 'Largeur', 'woocommerce' ) . " ($unit)",
			'value' => $width,
		);
	}

	return $product_attributes;
}

/**
 * Code pour gérer les produits upsell dans Elementor
 * Compatible avec les différents formats de paramètres
 */
function register_elementor_product_suggestions() {
	// Vérifie si Elementor est chargé
	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}

	/**
	 * Fonction qui filtre les arguments de requête pour la requête product_suggestions
	 *
	 * @param mixed $query_args Peut être un tableau d'arguments ou un objet WP_Query
	 * @param mixed $widget Widget Elementor (optionnel)
	 * @return mixed Les arguments modifiés (même type que l'entrée)
	 */
	function custom_product_suggestions_query( $query_args, $widget = null ) {
		// Vérifie si les arguments sont un objet WP_Query et ajuste en conséquence
		$is_query_object = is_object( $query_args ) && $query_args instanceof WP_Query;

		// Obtient l'ID du produit actuel
		$product_id = 0;

		// Différentes façons d'obtenir l'ID du produit actuel
		if ( function_exists( 'is_product' ) && is_product() ) {
			$product_id = get_the_ID();
		} elseif ( is_singular( 'product' ) ) {
			$product_id = get_the_ID();
		} elseif ( isset( $GLOBALS['post'] ) && $GLOBALS['post'] instanceof WP_Post && $GLOBALS['post']->post_type === 'product' ) {
			$product_id = $GLOBALS['post']->ID;
		}

		// Si aucun produit n'est trouvé, on retourne les arguments inchangés
		if ( ! $product_id || ! function_exists( 'wc_get_product' ) ) {
			return $query_args;
		}

		// Obtient le produit WooCommerce
		$product = wc_get_product( $product_id );
		if ( ! $product || is_wp_error( $product ) ) {
			return $query_args;
		}

		// Récupère les produits upsell
		$upsell_ids = $product->get_upsell_ids();

		// Si c'est un objet WP_Query, on modifie ses propriétés query_vars
		if ( $is_query_object ) {
			// Si des upsells existent
			if ( ! empty( $upsell_ids ) ) {
				$query_args->set( 'post_type', 'product' );
				$query_args->set( 'post__in', $upsell_ids );
				$query_args->set( 'orderby', 'post__in' );
			}
			// Sinon, on cherche des produits de la même catégorie
			else {
				$product_cats = wc_get_product_term_ids( $product_id, 'product_cat' );
				if ( ! empty( $product_cats ) ) {
					$query_args->set( 'post_type', 'product' );
					$query_args->set( 'post__not_in', array( $product_id ) );

					// Crée une requête de taxonomie
					$tax_query = array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $product_cats,
							'operator' => 'IN',
						),
					);
					$query_args->set( 'tax_query', $tax_query );
				}
			}
		}
		// Sinon, c'est un tableau standard d'arguments
		else {
			// Si des upsells existent
			if ( ! empty( $upsell_ids ) ) {
				$query_args['post_type'] = 'product';
				$query_args['post__in']  = $upsell_ids;
				$query_args['orderby']   = 'post__in';
			}
			// Sinon, on cherche des produits de la même catégorie
			else {
				$product_cats = wc_get_product_term_ids( $product_id, 'product_cat' );
				if ( ! empty( $product_cats ) ) {
					$query_args['post_type']    = 'product';
					$query_args['post__not_in'] = array( $product_id );
					$query_args['tax_query']    = array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $product_cats,
							'operator' => 'IN',
						),
					);
				}
			}
		}

		// Ajoute un log de débogage si demandé
		if ( isset( $_GET['debug_query'] ) && current_user_can( 'administrator' ) ) {
			error_log( 'Product Suggestions Query - Product ID: ' . $product_id );
			error_log( 'Is Query Object: ' . ( $is_query_object ? 'Yes' : 'No' ) );
			error_log( 'Upsell IDs: ' . print_r( $upsell_ids, true ) );
		}

		return $query_args;
	}

	// Enregistre les filtres pour les différents types de requêtes
	add_filter( 'elementor/query/product_suggestions', 'custom_product_suggestions_query', 10, 2 );
	add_filter( 'elementor_pro/query_control/get_query_args/product_suggestions', 'custom_product_suggestions_query', 10, 2 );

	// Pour le cas où Elementor passe un objet WP_Query directement (comme indiqué dans l'erreur)
	add_action(
		'pre_get_posts',
		function ( $query ) {
			// Vérifie si c'est notre requête personnalisée
			if ( isset( $query->query_vars['product_suggestions'] ) && $query->query_vars['product_suggestions'] === true ) {
				custom_product_suggestions_query( $query );
			}
		}
	);
}

// Hook d'initialisation d'Elementor
add_action( 'elementor/init', 'register_elementor_product_suggestions' );

/**
 * Remplace le texte "Rupture de stock" par "Oups, victime de son succès"
 * sur les pages produits WooCommerce.
 */
add_filter( 'woocommerce_get_availability_text', 'custom_out_of_stock_message', 10, 2 );

function custom_out_of_stock_message( $availability_text, $product ) {
    if ( ! $product->is_in_stock() ) {
        return __( 'Oups, victime de son succès. Produit disponible sur commande', 'your-textdomain' );
    }

    return $availability_text;
}

// Forcer WooCommerce à recalculer les frais de port à chaque affichage du panier
add_action( 'woocommerce_before_calculate_totals', function( $cart ) {
    
    // Ne rien faire si on est dans l'admin et pas en AJAX (évite des recalculs inutiles en back-office)
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

    // Force le recalcul des méthodes de livraison
    $cart->calculate_shipping();
});

// Empêcher le dédoublement de cart_totals et cart_table à chaque mise à jour du panier
add_action( 'wp_enqueue_scripts', 'ccs_cart_fix_all', 20 );
function ccs_cart_fix_all() {
    if ( is_cart() ) {
        wp_add_inline_script( 'jquery', <<<JS
(function(){
    function cleanCartStuff() {
        // Nettoyer les totaux en double
        const totals = document.querySelectorAll('.cart_totals');
        if (totals.length > 1) {
            for (let i = 1; i < totals.length; i++) {
                totals[i].remove();
            }
        }

        // Nettoyer les tables de produits en double
        const tables = document.querySelectorAll('.woocommerce-cart-form');
        if (tables.length > 1) {
            for (let i = 1; i < tables.length; i++) {
                tables[i].remove();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        cleanCartStuff();

        const observer = new MutationObserver(() => {
            cleanCartStuff();
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
})();
JS
        );
    }
}

// Affiche la quantité d'articles + prix à côté du titre "Votre panier"
add_action( 'wp_enqueue_scripts', 'ccs_dynamic_cart_summary_final', 20 );
function ccs_dynamic_cart_summary_final() {
    if ( is_cart() || is_checkout() ) {
        wp_add_inline_script( 'jquery', <<<JS
jQuery(document).ready(function($) {
    function updateCartSummary() {
        let totalQty = 0;
        const seen = new Set();

        $('.woocommerce-cart-form .cart_item').each(function() {
            const productName = $(this).find('.product-name').text().trim();
            const price = $(this).find('.product-price .woocommerce-Price-amount').text().trim();
            const rowKey = productName + '|' + price;

            if (seen.has(rowKey)) return;
            seen.add(rowKey);

            const qty = parseInt($(this).find('input.qty').val(), 10) || 0;
            totalQty += qty;
        });

        const totalText = $('.order-total .woocommerce-Price-amount').first().text();

        $('.ccs-cart-summary').text(
            totalQty + ' article' + (totalQty > 1 ? 's' : '') + ' | ' + totalText
        );
    }

    updateCartSummary();
    $('body').on('updated_cart_totals', updateCartSummary);
});
JS
        );
    }
}

/**
 * Code to add to your child theme's functions.php
 */

// Include the custom order status class
require_once get_stylesheet_directory() . '/includes/class-custom-order-status.php';

/**
 * Initialize custom order statuses
 */
function child_theme_init_custom_order_statuses() {
    // Define custom order statuses
    $custom_statuses = array(
        'wc-preparing' => array(
            'label' => 'Preparing',
            'public_name' => _x( 'Preparing', 'Order status', 'child-theme' ),
            'color' => '#f8a306'
        ),
        'wc-shipped' => array(
            'label' => 'Shipped',
            'public_name' => _x( 'Shipped', 'Order status', 'child-theme' ),
            'color' => '#7b35ea'
        ),
        'wc-delivered' => array(
            'label' => 'Delivered',
            'public_name' => _x( 'Delivered', 'Order status', 'child-theme' ),
            'color' => '#2ca02c'
        ),
    );
    
    // Instantiate the class with our custom statuses
    global $custom_order_status;
    $custom_order_status = new Custom_Order_Status($custom_statuses);
    
    // Add filters for email templates
    add_filter('woocommerce_email_actions', 'child_theme_add_email_notifications');
}
add_action('init', 'child_theme_init_custom_order_statuses', 5);

/**
 * Add email notifications for status transitions
 *
 * @param array $email_actions Existing email actions
 * @return array Updated email actions
 */
function child_theme_add_email_notifications($email_actions) {
    // Add actions for specific status transitions
    foreach (array('confirmed', 'preparing', 'shipped', 'delivered') as $status) {
        $email_actions[] = 'woocommerce_order_status_' . $status;
    }
    
    return $email_actions;
}

/**
 * Add custom email templates
 */
function child_theme_add_email_templates() {
    // Define template paths for emails
    $template_path = get_stylesheet_directory() . '/woocommerce/emails/';
    
    // Check and create template directory if needed
    if (!file_exists($template_path)) {
        wp_mkdir_p($template_path);
    }
}
add_action('init', 'child_theme_add_email_templates');

/**
 * Override WooCommerce email templates in the child theme
 * 
 * @param string $template Template path
 * @param string $template_name Template name
 * @param string $template_path Parent template path
 * @return string New template path
 */
function child_theme_woocommerce_locate_template($template, $template_name, $template_path) {
    // Check if the template is for an email and a custom status
    if (strpos($template_name, 'emails/') === 0) {
        global $custom_order_status;
        
        if (isset($custom_order_status)) {
            $statuses = $custom_order_status->get_custom_statuses();
            
            foreach ($statuses as $status_slug => $status_data) {
                $status_key = str_replace('wc-', '', $status_slug);
                
                // Check if template matches a custom status
                if (strpos($template_name, 'email-' . $status_key) !== false) {
                    $child_template = get_stylesheet_directory() . '/woocommerce/' . $template_name;
                    
                    // Use child theme template if it exists
                    if (file_exists($child_template)) {
                        return $child_template;
                    }
                }
            }
        }
    }
    
    return $template;
}
add_filter('woocommerce_locate_template', 'child_theme_woocommerce_locate_template', 10, 3);

/**
 * Add custom status email notifications for status transitions
 */
function child_theme_add_custom_status_email_notifications() {
    global $custom_order_status;
    
    if (isset($custom_order_status)) {
        $statuses = $custom_order_status->get_custom_statuses();
        
        foreach ($statuses as $status_slug => $status_data) {
            $status_key = str_replace('wc-', '', $status_slug);
            
            // Add action to send email when status changes
            add_action('woocommerce_order_status_' . $status_key, function($order_id) use ($status_key, $status_data) {
                $order = wc_get_order($order_id);
                
                if ($order) {
                    // Trigger the appropriate email template
                    do_action('woocommerce_customer_' . $status_key . '_order_email', $order);
                }
            }, 10, 1);
        }
    }
}
add_action('init', 'child_theme_add_custom_status_email_notifications');

add_filter('woocommerce_email_order_details', function($order, $sent_to_admin, $plain_text, $email) {
    // Suppression du hook qui affiche les totaux
    remove_action('woocommerce_email_after_order_table', array(WC()->mailer()->emails['WC_Email_Customer_Completed_Order'], 'order_details_table'), 10);
    
    return $order;
}, 5, 4);

// Désactiver les totaux de commande dans les emails
add_filter('woocommerce_get_order_item_totals', function($total_rows, $order, $tax_display) {
    // Si nous sommes dans un email
    if (did_action('woocommerce_email_header')) {
        // Vider complètement le tableau des totaux
        return array();
    }
    return $total_rows;
}, 10, 3);