<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
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

$email_improvements_enabled = FeaturesUtil::feature_is_enabled('email_improvements');

do_action('woocommerce_email_header', $email_heading, $email); ?>

<?php echo $email_improvements_enabled ? '<div class="email-introduction">' : ''; ?>
<div style="display: flex; justify-content: center; align-items: center; flex-direction: column; width: 100%;">
	<img src="http://la-porte-cot.local/wp-content/uploads/2025/05/eIOE_1.svg" alt="Image création d'un compte">
	<?php /* translators: %s: Customer username */ ?>
	<p>
		<?php
		printf(
			esc_html__('Compte créé avec succès %s', 'woocommerce'),
			'<span class="orange_color" style="font-weight: 600;">' . esc_html($user_login) . '!</span>'
		);
		?>
	</p>
</div>

<?php if ($email_improvements_enabled): ?>
	<?php /* translators: %s: Site title */ ?>
	<p>Bonjour <span style="font-weight: 600;" class="orange_color"><?php echo esc_html($user_login) ?></span></p>
	<p><?php printf(esc_html__('Merci d\' avoir créé un compte chez %s.', 'woocommerce'), esc_html($blogname)); ?>
	</p>
	<div id="container_CTA">
		<a href="<?php echo esc_attr(wc_get_page_permalink('myaccount')); ?>" class="show_account">
			Accéder à mon espace client
		</a>
		<a href="<?php echo esc_url(home_url()); ?>" class="visit_website">
			Visiter notre site
		</a>
	</div>
	<?php
	// Ajout des produits suggérés via notre fonction
	echo wc_suggested_products_for_emails(4, 'date', 'DESC');
	?>
	
	<?php if ('yes' === get_option('woocommerce_registration_generate_password') && $password_generated && $set_password_url): ?>
		<?php // If the password has not been set by the user during the sign up process, send them a link to set a new password. ?>
		<p><a
				href="<?php echo esc_attr($set_password_url); ?>"><?php printf(esc_html__('Set your new password.', 'woocommerce')); ?></a>
		</p>
	<?php endif; ?>
	<div class="hr hr-bottom"></div>
<?php else: ?>
	<?php /* translators: %1$s: Site title, %2$s: Username, %3$s: My account link */ ?>
	<p><?php printf(esc_html__('Thanks for creating an account on %1$s. Your username is %2$s. You can access your account area to view orders, change your password, and more at: %3$s', 'woocommerce'), esc_html($blogname), '<strong>' . esc_html($user_login) . '</strong>', make_clickable(esc_url(wc_get_page_permalink('myaccount')))); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</p>
	<?php if ('yes' === get_option('woocommerce_registration_generate_password') && $password_generated && $set_password_url): ?>
		<?php // If the password has not been set by the user during the sign up process, send them a link to set a new password. ?>
		<p><a
				href="<?php echo esc_attr($set_password_url); ?>"><?php printf(esc_html__('Click here to set your new password.', 'woocommerce')); ?></a>
		</p>
	<?php endif; ?>
<?php endif; ?>
<?php echo $email_improvements_enabled ? '</div>' : ''; ?>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
// if ($additional_content) {
// 	echo $email_improvements_enabled ? '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td class="email-additional-content email-additional-content-aligned">' : '';
// 	echo wp_kses_post(wpautop(wptexturize($additional_content)));
// 	echo $email_improvements_enabled ? '</td></tr></table>' : '';
// }

do_action('woocommerce_email_footer', $email);
