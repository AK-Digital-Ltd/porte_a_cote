<?php
/**
 * Email Header
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-header.php.
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

use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
</head>

<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0"
    offset="0">
    <table width="100%" id="outer_wrapper">
        <tr>
            <td>
                <!-- Deliberately empty to support consistent sizing and layout across multiple email clients. -->
            </td>
            <td align="center">
                <div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
                    <table border=" 0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="inner_wrapper">
                        <tr>
                            <td align="center" valign="top">
                                <div id="template_header_image">
                                    <img src="http://la-porte-cot.local/wp-content/uploads/2025/05/logo-carolelaporteacote.png"
                                        alt="Logo Carole la porte à côté" />
                                </div>
                                <table border="0" cellpadding="0" cellspacing="0" id="template_container"
                                    align="center">
                                    <!-- <tr>
                                        <td align="center" valign="top">
                                            <img src="http://la-porte-cot.local/wp-content/uploads/2025/05/left-tree-1.png"
                                                width="200" height="506" class="left-tree" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">
                                            <img src="http://la-porte-cot.local/wp-content/uploads/2025/05/right-tree-1.png"
                                                width="200" height="506" class="right-tree" />
                                        </td>
                                    </tr> -->
                                    <tr>
                                        <td align="center" valign="top">
                                            <!-- Body -->
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                id="template_body">
                                                <tr>
                                                    <td valign="top" id="body_content">
                                                        <!-- Content -->
                                                        <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                            <tr>
                                                                <td valign="top" id="body_content_inner_cell"
                                                                    style="padding: 48px 0 !important;">
                                                                    <div id="body_content_inner">