<?php
/*
Plugin Name: WP MLM
Plugin URI: http://wpmlmsoftware.com
Description: MLM Unilevel plugin for Wordpress.
Version: 3.0
Author: IOSS
Author URI: http://wpmlmsoftware.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
 
WP MLM is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
WP MLM is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with WP MLM. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 
 */

// namespace My_Plugin;

if (!defined('ABSPATH'))
    exit;

if (!defined('WP_MLM_PLUGIN_NAME'))
    define('WP_MLM_PLUGIN_NAME', plugin_basename( dirname( __FILE__ , 1 )));

// Path and URL
if (!defined('WP_MLM_PLUGIN_DIR'))
    define('WP_MLM_PLUGIN_DIR', WP_PLUGIN_DIR . '/'.WP_MLM_PLUGIN_NAME);
require_once(WP_MLM_PLUGIN_DIR . '/wp-mlm-constant.php');
require_once(WP_MLM_PLUGIN_DIR . '/wp-mlm-custom-functions.php');

require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-registration-page.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-user-income-details.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-user-referrals.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-user-ewallet-details.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-user-ewallet-management.php');
require_once(WP_MLM_PLUGIN_DIR . '/core_functions/wp-mlm-db-functions.php');
require_once(WP_MLM_PLUGIN_DIR . '/functions/wp-mlm-db-functions.php');
require_once(WP_MLM_PLUGIN_DIR . '/functions/wp-mlm-ajax-functions.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-admin-area.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-admin-dashboard.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-user-dashboard.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-user-area.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-registration-package-settings.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-level-commission-settings.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-payment-options.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-genealogy-tree.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-user-details-admin.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-user-profile-admin.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-settings.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-password-settings.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-general-settings.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-reports.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wp-mlm-ewallet-management.php');


register_activation_hook(__FILE__, 'wpmlm_install');
register_uninstall_hook(__FILE__, 'wpmlm_uninstall');
register_deactivation_hook(__FILE__, 'wpmlm_deactivate');


function wpmlm_install() {
    create_wpmlm_users_table();
    create_wpmlm_registration_packages_table();
    create_wpmlm_configuration_table();
    create_wpmlm_level_table();
    create_wpmlm_leg_amount_table();
    create_wpmlm_reg_type_table();
    create_wpmlm_paypal_table();
    create_wpmlm_general_information_table();
    create_wpmlm_fund_transfer_table();
    create_wpmlm_ewallet_history_table();
    create_wpmlm_transaction_id_table();
    create_wpmlm_tran_password_table();
    create_wpmlm_country_table();
    create_wpmlm_user_balance_amount_table();
    insert_wpmlm_first_user();
    insert_wpmlm_country_data();
    insert_wpmlm_general_information();
    insert_wpmlm_configuration_information();
    install_mlm_user_dashboard();
    install_mlm_registration_form();
    insert_wpmlm_reg_type();
    insert_wpmlm_paypal_data();

}

function wpmlm_uninstall() {
    wpmlm_delete_user_data();
    wpmlm_drop_tables();
}

function install_mlm_user_dashboard(){
        
        $new_page_title =  'Affiliate User Dashboard';
        $new_page_slug = 'affiliate-user-dashboard';
        $new_page_content = '[wp_affiliate_user_dashboard]';
        $new_page_template = ''; //ex. template-custom.php. Leave blank if you don’t want a custom page template.
        //don’t change the code below, unless you know what you’re doing
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                'post_type' => 'page',
                'post_name' => $new_page_slug,
                'post_title' => $new_page_title,
                'post_content' => $new_page_content,
                'post_status' => 'publish',
                'post_author' => 1,
        );
        if(!isset($page_check->ID)){
                $new_page_id = wp_insert_post($new_page);
                wpmlm_update_user_dash($new_page_id);
                if(!empty($new_page_template)){
                        update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
                }
        }
}

function install_mlm_registration_form(){
        
        $new_page_title =  'Registration';
        $new_page_slug = 'affiliate-user-registration';
        $new_page_content = '[wp_affiliate_registration_form]';
        $new_page_template = ''; //ex. template-custom.php. Leave blank if you don’t want a custom page template.
        //don’t change the code below, unless you know what you’re doing
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                'post_type' => 'page',
                'post_name' => $new_page_slug,
                'post_title' => $new_page_title,
                'post_content' => $new_page_content,
                'post_status' => 'publish',
                'post_author' => 1,
        );
        if(!isset($page_check->ID)){
                $new_page_id = wp_insert_post($new_page);
                wpmlm_update_reg_form($new_page_id);
                if(!empty($new_page_template)){
                        update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
                }
        }
}


add_action('init', 'wpmlm_register_menu');
add_action('login_head', 'wpmlm_login_style');
add_action('login_head', 'wpmlm_custom_loginlogo');

// load the scripts on only the plugin admin page 
if (isset($_GET['page']) && (($_GET['page'] == 'mlm-admin-settings') || ($_GET['page'] == 'mlm-user-settings'))){   
    add_action('admin_enqueue_scripts', 'wpmlm_admin_scripts');   
}