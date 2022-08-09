<?php
/**
 * The header for Cleenday theme
 *
 * This is the template that displays all of the <head> section and everything up until <main>
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cleenday
 * @since 1.0.0
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <?php ?>
    
    <?php
    wp_head();
    ?>

<script>
    jQuery( document ).ready(function() {
        <?php 
        if (is_user_logged_in()) {
            $logout = (string)wp_logout_url('login');
            ?>
            var logoutLink = jQuery("#logoutLink").attr('data-url');
            let delogoutLink = decodeURI(logoutLink);
            jQuery( 'a[data-logout="logoutLink"]' ).attr('href',delogoutLink);
            
            <?php
         } else {
            ?>
            var loginLink = '<?php esc_html_e(wp_login_url(get_permalink()));?>';
            <?php
         }
        ?>
    });
    </script>

<?php

if(!empty($_GET['fepaction'])){
    ?>
    <script>
        jQuery( document ).ready(function() {
           //ioss-mlm-tab7
           jQuery("#ioss-mlm-tab7").trigger("click");
        });
    </script>
    <?php
} ?>

</head>

<body <?php body_class(); ?>>
<div style="display: none;" id="logoutLink" data-url="<?php echo $logout;?>"></div>
    <?php
    wp_body_open();

    /**
    * Get Preloader
    *
    * @since 1.0.0
    */
    do_action('cleenday/preloader');

    /**
    * Elementor Pro Header Render
    *
    * @since 1.0.0
    */
    do_action('cleenday/elementor_pro/header');

    /**
    * Check WGL header active option
    *
    * @since 1.0.0
    */
    if (apply_filters('cleenday/header/enable', true)) {
        get_template_part('templates/header/section', 'header');
    }

    /**
    * Check WGL page title active option
    *
    * @since 1.0.0
    */
    $page_title = apply_filters('cleenday/page_title/enable', true);
    if (isset($page_title['page_title_switch']) && $page_title['page_title_switch'] !== 'off') {
        get_template_part('templates/header/section', 'page_title');
    }
    ?>
    <main id="main" class="site-main">