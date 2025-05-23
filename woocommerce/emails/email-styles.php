<?php
/**
 * Email Styles
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-styles.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 9.8.0
 */

use Automattic\WooCommerce\Internal\Email\EmailFont;
use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );

// Load colors.
$bg               = get_option( 'woocommerce_email_background_color' );
$body             = get_option( 'woocommerce_email_body_background_color' );
$base             = get_option( 'woocommerce_email_base_color' );
$text             = get_option( 'woocommerce_email_text_color' );
$footer_text      = get_option( 'woocommerce_email_footer_text_color' );
$header_alignment = get_option( 'woocommerce_email_header_alignment', $email_improvements_enabled ? 'left' : false );
$logo_image_width = get_option( 'woocommerce_email_header_image_width', '120' );
$default_font     = 'Helvetica';
$font_family      = $email_improvements_enabled ? get_option( 'woocommerce_email_font_family', $default_font ) : $default_font;

/**
 * Check if we are in preview mode (WooCommerce > Settings > Emails).
 *
 * @since 9.6.0
 * @param bool $is_email_preview Whether the email is being previewed.
 */
$is_email_preview = apply_filters( 'woocommerce_is_email_preview', false );

if ( $is_email_preview ) {
	$bg_transient               = get_transient( 'woocommerce_email_background_color' );
	$body_transient             = get_transient( 'woocommerce_email_body_background_color' );
	$base_transient             = get_transient( 'woocommerce_email_base_color' );
	$text_transient             = get_transient( 'woocommerce_email_text_color' );
	$footer_text_transient      = get_transient( 'woocommerce_email_footer_text_color' );
	$header_alignment_transient = get_transient( 'woocommerce_email_header_alignment' );
	$logo_image_width_transient = get_transient( 'woocommerce_email_header_image_width' );
	$font_family_transient      = get_transient( 'woocommerce_email_font_family' );

	$bg               = $bg_transient ? $bg_transient : $bg;
	$body             = $body_transient ? $body_transient : $body;
	$base             = $base_transient ? $base_transient : $base;
	$text             = $text_transient ? $text_transient : $text;
	$footer_text      = $footer_text_transient ? $footer_text_transient : $footer_text;
	$header_alignment = $header_alignment_transient ? $header_alignment_transient : $header_alignment;
	$logo_image_width = $logo_image_width_transient ? $logo_image_width_transient : $logo_image_width;
	$font_family      = $font_family_transient ? $font_family_transient : $font_family;
}

// Only use safe fonts. They won't be escaped to preserve single quotes.
$safe_font_family = EmailFont::$font[ $font_family ] ?? EmailFont::$font[ $default_font ];

$base_text = wc_light_or_dark( $base, '#202020', '#ffffff' );

// Pick a contrasting color for links.
$link_color = wc_hex_is_light( $base ) ? $base : $base_text;

if ( wc_hex_is_light( $body ) ) {
	$link_color = wc_hex_is_light( $base ) ? $base_text : $base;
}

// If email improvements are enabled, always use the base color for links.
if ( $email_improvements_enabled ) {
	$link_color = $base;
}

$border_color    = wc_light_or_dark( $body, 'rgba(0, 0, 0, .2)', 'rgba(255, 255, 255, .2)' );
$bg_darker_10    = wc_hex_darker( $bg, 10 );
$body_darker_10  = wc_hex_darker( $body, 10 );
$base_lighter_20 = wc_hex_lighter( $base, 20 );
$base_lighter_40 = wc_hex_lighter( $base, 40 );
$text_lighter_20 = wc_hex_lighter( $text, 20 );
$text_lighter_40 = wc_hex_lighter( $text, 40 );

// !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
// body{padding: 0;} ensures proper scale/positioning of the email in the iOS native email app.
?>
body {
background-color:#f5eee6;
padding: 0;
text-align: center;
}

#outer_wrapper {
background-color:#f5eee6;
padding: 20px 0;
font-family: 'Segoe UI', Helvetica, Arial, sans-serif !important;
}

<?php if ( $email_improvements_enabled ) : ?>
#inner_wrapper {
background-color: <?php echo esc_attr( $body ); ?>;
border-radius: 8px;
}
<?php endif; ?>

#wrapper {
margin: 0 auto;
padding: <?php echo $email_improvements_enabled ? '24px 0' : '70px 0'; ?>;
-webkit-text-size-adjust: none !important;
width: 100%;
}

#template_header_image {
text-align: center;
margin: 24px 0 64px 0;
}
#template_header_image img{
max-width: 200px;
height: auto;
}

#template_container {
border-radius: 5px !important;
background-color: #ffffff;
margin: 0 auto !important;
padding: 24px;
}

#template_body {
max-width: 642px;
}

#template_header {
background-color: <?php echo esc_attr( $email_improvements_enabled ? $body : $base ); ?>;
border-radius: 3px 3px 0 0 !important;
color: <?php echo esc_attr( $email_improvements_enabled ? $text : $base_text ); ?>;
border-bottom: 0;
font-weight: bold;
line-height: 100%;
vertical-align: middle;
font-family: <?php echo $safe_font_family; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
}

#template_header h1,
#template_header h1 a {
color: <?php echo esc_attr( $email_improvements_enabled ? $text : $base_text ); ?>;
background-color: inherit;
}

<?php if ( $email_improvements_enabled ) : ?>
.hr {
border-bottom: 1px solid #1e1e1e;
opacity: 0.2;
margin: 16px 0;
}

.hr-top {
margin-top: 32px;
}

.hr-bottom {
margin-bottom: 32px;
}

#template_header_image {
padding: 32px 32px 0;
}

#template_header_image p {
margin-bottom: 0;
text-align: <?php echo esc_attr( $header_alignment ); ?>;
}

#template_header_image img {
width: <?php echo esc_attr( $logo_image_width ); ?>px;
}

.email-logo-text {
color: <?php echo esc_attr( $link_color ); ?>;
font-family: <?php echo $safe_font_family; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
font-size: 18px;
}

.email-introduction {
padding-bottom: 24px;
}

.email-order-item-meta {
color: <?php echo esc_attr( $footer_text ); ?>;
font-size: 14px;
line-height: 140%;
}

#body_content table td td.email-additional-content {
color: <?php echo esc_attr( $text ); ?>;
font-family: <?php echo $safe_font_family; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
padding: 32px 0 0;
}

.email-additional-content p {
text-align: center;
}

.email-additional-content-aligned p {
text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

<?php else : ?>

#template_header_image img {
margin-left: 0;
margin-right: 0;
}
<?php endif; ?>

#template_footer td {
padding: 0;
border-radius: <?php echo $email_improvements_enabled ? '0' : '6px'; ?>;
}

#template_footer #credit {
border: 0;
<?php if ( $email_improvements_enabled ) : ?>
border-top: 1px solid <?php echo esc_attr( $border_color ); ?>;
<?php endif; ?>
color: <?php echo esc_attr( $footer_text ); ?>;
font-family: <?php echo $safe_font_family; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
font-size: 12px;
line-height: <?php echo $email_improvements_enabled ? '140%' : '150%'; ?>;
text-align: center;
padding: <?php echo $email_improvements_enabled ? '32px' : '24px 0'; ?>;
}

#template_footer #credit p {
margin: <?php echo $email_improvements_enabled ? '0' : '0 0 16px'; ?>;
}

#body_content {
background-color: <?php echo esc_attr( $body ); ?>;
}

#body_content table td {
padding: <?php echo $email_improvements_enabled ? '20px 32px 32px' : '48px 48px 32px'; ?>;
}

#body_content table td td {
padding: 12px;
}

#body_content table td th {
padding: 12px;
}

#body_content table .email-order-details td,
#body_content table .email-order-details th {
padding: 8px 12px;
}

#body_content table .email-order-details td:first-child,
#body_content table .email-order-details th:first-child {
padding-<?php echo is_rtl() ? 'right' : 'left'; ?>: 0;
}

#body_content table .email-order-details td:last-child,
#body_content table .email-order-details th:last-child {
padding-<?php echo is_rtl() ? 'left' : 'right'; ?>: 0;
}

#body_content .email-order-details tbody tr:last-child td {
border-bottom: 1px solid <?php echo esc_attr( $border_color ); ?>;
padding-bottom: 24px;
}

#body_content .email-order-details tfoot tr:first-child td,
#body_content .email-order-details tfoot tr:first-child th {
padding-top: 24px;
}

#body_content .order-item-data td {
border: 0 !important;
padding: 0 !important;
vertical-align: middle;
}

#body_content .email-order-details .order-totals td,
#body_content .email-order-details .order-totals th {
font-weight: normal;
padding-bottom: 5px;
padding-top: 5px;
}

#body_content .email-order-details .order-totals-total th {
font-weight: bold;
}

#body_content .email-order-details .order-totals-total td {
font-weight: bold;
font-size: 20px;
}

#body_content .email-order-details .order-totals-last td,
#body_content .email-order-details .order-totals-last th {
border-bottom: 1px solid <?php echo esc_attr( $border_color ); ?>;
padding-bottom: 24px;
}

#body_content .email-order-details .order-customer-note td {
border-bottom: 1px solid <?php echo esc_attr( $border_color ); ?>;
padding-bottom: 24px;
padding-top: 24px;
}

#body_content td ul.wc-item-meta {
font-size: small;
margin: 1em 0 0>;
padding: 0;
list-style: none;
}

#body_content td ul.wc-item-meta li {
margin: 0.5em 0 0;
padding: 0;
}

#body_content td ul.wc-item-meta li p {
margin: 0;
}

#body_content .email-order-details .wc-item-meta-label {
clear: both;
float: <?php echo is_rtl() ? 'right' : 'left'; ?>;
font-weight: normal;
margin-<?php echo is_rtl() ? 'left' : 'right'; ?>: .25em;
}

#body_content p {
margin: 0 0 16px;
}

#body_content_inner {
color: <?php echo esc_attr( $text_lighter_20 ); ?>;
font-family: <?php echo $safe_font_family; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
font-size: <?php echo $email_improvements_enabled ? '16px' : '14px'; ?>;
line-height: 150%;
text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

.td {
color: <?php echo esc_attr( $text_lighter_20 ); ?>;
border: <?php echo $email_improvements_enabled ? '0' : '1px solid ' . esc_attr( $body_darker_10 ); ?>;
vertical-align: middle;
}

.address {
<?php if ( $email_improvements_enabled ) { ?>
color: <?php echo esc_attr( $text ); ?>;
font-style: normal;
padding: 8px 0;
<?php } else { ?>
padding: 12px;
color: <?php echo esc_attr( $text_lighter_20 ); ?>;
border: 1px solid <?php echo esc_attr( $body_darker_10 ); ?>;
<?php } ?>
}

.additional-fields {
padding: 12px 12px 0;
color: <?php echo esc_attr( $text_lighter_20 ); ?>;
border: 1px solid <?php echo esc_attr( $body_darker_10 ); ?>;
list-style: none outside;
}

.additional-fields li {
margin: 0 0 12px 0;
}

.text,
.address-title,
.order-item-data {
color: <?php echo esc_attr( $text ); ?>;
font-family: <?php echo $safe_font_family; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
}

.link {
color: <?php echo esc_attr( $link_color ); ?>;
}

#header_wrapper {
padding: <?php echo $email_improvements_enabled ? '20px 32px 0' : '36px 48px'; ?>;
display: block;
}

<?php if ( $header_alignment ) : ?>
#header_wrapper h1 {
text-align: <?php echo esc_attr( $header_alignment ); ?>;
}
<?php endif; ?>

#template_footer #credit,
#template_footer #credit a {
color: <?php echo esc_attr( $footer_text ); ?>;
}

h1 {
color: <?php echo esc_attr( $email_improvements_enabled ? $text : $base ); ?>;
font-family: <?php echo $safe_font_family; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
font-size: <?php echo $email_improvements_enabled ? '32px' : '30px'; ?>;
font-weight: <?php echo $email_improvements_enabled ? 700 : 300; ?>;
<?php if ( $email_improvements_enabled ) : ?>
letter-spacing: -1px;
<?php endif; ?>
line-height: <?php echo $email_improvements_enabled ? '120%' : '150%'; ?>;
margin: 0;
text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
<?php if ( ! $email_improvements_enabled ) : ?>
text-shadow: 0 1px 0 <?php echo esc_attr( $base_lighter_20 ); ?>;
<?php endif; ?>
}

h2 {
color: <?php echo esc_attr( $email_improvements_enabled ? $text : $base ); ?>;
display: block;
font-family: <?php echo $safe_font_family; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
font-size: <?php echo $email_improvements_enabled ? '20px' : '18px'; ?>;
font-weight: bold;
line-height: <?php echo $email_improvements_enabled ? '160%' : '130%'; ?>;
margin: 0 0 18px;
text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

h3 {
color: <?php echo esc_attr( $email_improvements_enabled ? $text : $base ); ?>;
display: block;
font-family: <?php echo $safe_font_family; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
font-size: 16px;
font-weight: bold;
line-height: <?php echo $email_improvements_enabled ? '160%' : '130%'; ?>;
margin: 16px 0 8px;
text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

a {
color: <?php echo esc_attr( $link_color ); ?>;
font-weight: normal;
text-decoration: underline;
}

img {
border: none;
display: inline-block;
font-size: 14px;
font-weight: bold;
height: auto;
outline: none;
text-decoration: none;
text-transform: capitalize;
vertical-align: middle;
margin-<?php echo is_rtl() ? 'left' : 'right'; ?>: <?php echo $email_improvements_enabled ? '24px' : '10px'; ?>;
max-width: 100%;
}

h2.email-order-detail-heading span {
color: <?php echo esc_attr( $footer_text ); ?>;
display: block;
font-size: 14px;
font-weight: normal;
}

.font-family {
font-family: <?php echo $safe_font_family; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
}

.text-align-left {
text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

.text-align-right {
text-align: <?php echo is_rtl() ? 'left' : 'right'; ?>;
}

/**
* Media queries are not supported by all email clients, however they do work on modern mobile
* Gmail clients and can help us achieve better consistency there.
*/
@media screen and (max-width: 600px) {
<?php if ( $email_improvements_enabled ) : ?>
#template_header_image {
padding: 16px 10px 0 !important;
}

#header_wrapper {
padding: 16px 10px 0 !important;
}

#header_wrapper h1 {
font-size: 24px !important;
}

#body_content_inner_cell {
padding: 10px !important;
}

#body_content_inner {
font-size: 12px !important;
}

.email-order-item-meta {
font-size: 12px !important;
}

#body_content .email-order-details .order-totals-total td {
font-size: 14px !important;
}

.email-order-detail-heading {
font-size: 16px !important;
line-height: 130% !important;
}

.email-additional-content {
padding-top: 16px !important;
}
<?php else : ?>
#header_wrapper {
padding: 27px 36px !important;
font-size: 24px;
}

#body_content table > tbody > tr > td {
padding: 10px !important;
}

#body_content_inner {
font-size: 10px !important;
}
<?php endif; ?>
}

/**
* Custom Footer Styles
*/
/* Signature section */
.email-signature {
text-align: left;
margin-bottom: 124px;
}

/* Footer info section */
.footer-info-table {
width: 100%;
margin: 24px 0;
border-collapse: collapse;
}

.footer-info-left {
width: 60%;
border-right: 1px solid rgba(198, 198, 198, 1);
}

.foooter-info-left, .footer-info-right {
font-size: 14px;
text-align: center;
}

.footer-info-left p, .footer-info-right p {
margin-bottom: 4px !important;
}

.footer-info-right {
width: 40%;
}

.footer-info-icon {
width: 37px;
height: 37px;
}

.footer-info-title {
margin: 0 0 8px 0;
font-size: 14px;
}

.footer-info-value {
margin: 0;
color: rgba(186, 92, 51, 1);
font-size: 12px;
}

/* Social media section */
.social-media-container {
margin: 16px 0 64px 0;
background-color: rgba(186, 92, 51, 0.1);
padding: 24px;
}

.social-media-title {
font-weight: bold;
font-size: 14px;
margin: 0 0 16px;
}

.social-media-link {
text-decoration: none;
}

.social-media-link.facebook {
margin: 0 12px 0 12px;
}

.social-media-link.instagram {
margin-right: 12px;
}

.facebook-icon {
width: 15px;
height: 32px;
}

.instagram-icon {
width: 32px;
height: 32px;
}

.youtube-icon {
width: 32px;
height: 21px;
}

/* Footer contact info */
.footer-contact-info {
font-size: 12px;
margin-top: 64px;
}

.footer-contact-link {
text-decoration: underline;
}

.footer-contact-link,
.footer-contact-phone, .footer-contact-email {
color: rgba(186, 92, 51, 1);
}

/* Main footer container */
.footer-container {
text-align: center;
padding: 0 !important;
}

/* EMAIL CONTENT */

/* EMAIL PRODUCT ROUTE */

.email-content {
text-align: center;
width: 100%;
}

.email-content > img {
margin-bottom: 32px;
}

.email-content h1 {
color: rgba(0, 0, 0, 1) !important;
font-weight: 600;
font-size: 24px;
text-align: center;
margin-bottom: 43px;
text-shadow: none !important;
}

.email-content h1 span {
color: rgba(186, 92, 51, 1) !important;
text-shadow: none !important;
}

.product_route {
margin-bottom: 43px;
display:flex;
justify-content: space-between;
align-items: start;
width: 100%;
}
.product_route p {
font-size: 12px;
font-weight: 400;
}

.row {
display: flex;
justify-content: center;
align-items: center;
flex-direction: column;
text-align: center;
position: relative;
flex: 1;
}

.row:not(:last-child)::after {
content: '';
position: absolute;
top: 17px;
transform: translateX(50%);
width: 100%;
height: 1px;
border-top: 2px dotted #aaa;
z-index: 0;
}

.row:has(.finish)::after {
border-top: 2px solid #BA5C33;
}

.dotted::after {
border-top: 2px dotted #BA5C33 !important;
}

.orange_color {
color: #BA5C33;
}
.finish,
.in_progress {
display: flex;
justify-content: center;
align-items: center;
width: 33px;
height: 33px;
border-radius: 50%;
position: relative;
z-index: 1;
gap: 8px;
}
.finish {
background-color: #BA5C33;
}
.in_progress {
flex-direction: column;
border: 1px solid black;
background-color: #FFFFFF;
}

.highlight {
font-weight: 600 !important;
color: #BA5C33 !important;
}

.email-introduction {
font-weight: 700;
color: rgba(0, 0, 0, 1);
text-align: left;
}

.email-introduction span {
color: rgba(186, 92, 51, 1);
}

.follow-order {
display: block;
width: fit-content;
margin: auto;
text-align: center;
background-color: rgba(186, 92, 51, 1);
padding: 16px 16px;
border-radius: 5px;
border: none;
font-size: 18px;
outline: none;
margin-top: 77px;
text-decoration: none;
color: #fff;
}

.delivery-info-content, .tracking-info {
background-color: rgba(179, 142, 107, 0.1);
border: 1px solid rgba(179, 142, 107, 1);
border-radius: 5px;
padding: 32px 50px;
}

.delivery-info,
.tracking-info {
margin-top: 77px !important;
}

.delivery-info > p,
.tracking-info span {
font-weight: 600;
font-size: 16px;
text-transform: uppercase;
color: rgba(0, 0, 0, 1);
}

.delivery-info-content {
text-align: center;
}

.tracking-info div {
text-align: left;
margin-top: 22px;
}

.tracking-info ul, .delivery-info-content ul{
list-style-type: none;
padding: 0;
margin: 0;
}

.tracking-info ul li, .delivery-info-content ul li {
color: rgba(0, 0, 0, 1) !important;
font-weight: 600;
padding: 4px 0;
}

.tracking-info ul li span, .delivery-info-content ul li span {
color: rgba(65, 66, 65, 1);
font-weight: 400;
}

/* ORDER RECAP */

.order_recap {
max-width: 548px !important;
margin: 0 auto;
}

.order_details {
border :none !important;
}

.recap {
display: block;
line-height: 130%;
text-align: center !important;
font-size: 16px;
font-weight: 600;
color: rgba(0,0,0,1);
margin-top: 64px;
}

.price {
font-weight: 600;
color: rgba(38, 38, 38, 1);
margin-left: 8px;
}

/* BILLING ADRESS */

.billing_address {
margin-top: 0px;
margin-bottom: 0px;
}

.billing_address h2 {
color: #333;
font-size: 18px;
margin-bottom: 15px;
}

.billing_address div {
padding: 10px 0;
text-align: left;
}

.billing_address p {
margin: 2px 0 !important;
}

/* ORDER SUMMARY */

.order_summary {
margin-bottom: 82px;
}

.order_summary table {
width: 100%;
border-collapse: collapse;
}

.order_summary .border {
border-bottom: 2px solid #ddd;
}

.order_summary tr td {
color: rgba(0, 0, 0, 1);
padding: 4px 0 !important;
}

#first {
padding-top: 12px !important;
}

.order_summary tr td:first-child {
text-align: left;
}

.order_summary tr td:last-child {
text-align: right;
color: rgba(0, 0, 0, 1) !important;
}

.order_summary span {
color: #666;
}

.total {
font-weight: bold;
font-size: 16px;
padding: 8px 0 !important;
color: rgba(0, 0, 0, 1) !important;
}

/* PAYMENT FAILED */

.payment-failed {
margin-top: 64px !important;
padding: 32px;
border: 1px solid rgba(202, 202, 202, 1);
max-width: 424px;
margin: 0 auto;
text-align: left;
text-transform: uppercase;
}

.payment-failed p {
margin: 0 !important;
color: rgba(0, 0, 0, 1) !important;
}


/* INVOICES */

#title_invoice {
width: 100%;
font-size: 24px;
font-weight: 600;
text-align: center;
}

.btn_show_account {
border: 2px solid transparent;
background-color: #BA5C33;
color: #FFFFFF;
display: flex;
justify-content: center;
align-items: center;
flex: 1;
border-radius: 5px;
padding: 8px 16px;
cursor: pointer;
text-decoration: none;
width: fit-content;
margin: auto;
margin-top: 64px;
}


#delivery_container {
display: flex;
justify-content: start;
align-items: center;
flex-direction: column;
gap: 16px;
margin-top: 64px;
}

#delivery_infos {
display: flex;
justify-content: start;
align-items: center;
flex-direction: column
background-color: rgba(179, 142, 107, 0.1);;
border: 1px solid #B38E6B;
border-radius: 5px;
width: 100%;
padding: 32px 50px;
gap: 16px;
box-sizing: border-box;
margin-bottom: 32px;
}

#delivery_infos div#container_address {
display: flex;
justify-content: center;
align-items: start;
gap: 24px;
}

.label_infos {
font-weight: 600;
color: black !important;
}