<?php 
require_once 'wp_mlm_user_function.php';
function wpmlm_user_settings_nnu() {
    include('wp_mlm_user.php');
}
function wpmlm_admin_settings_nnu() {
    include('wp_mlm_admin.php');
}
function wpmlm_admin_actions_nnu() {
    $icon_url = get_stylesheet_directory_uri() . "/images/icon-01.png";
    add_menu_page('WP MLM ADMIN', 'WP MLM', 'manage_options', 'mlm-admin-settings', 'wpmlm_admin_settings_nnu', $icon_url);
    
}

function wpmlm_admin_actions_user_nnu() {
    $icon_url = get_stylesheet_directory_uri() . "/images/icon-01.png";

    add_menu_page('WP MLM ADMIN', 'WP MLM', 'contributor', 'mlm-user-settings', 'wpmlm_user_settings_nnu', $icon_url);
}

function wpmlm_register_menu_nnu() {
    if (current_user_can('subscriber') || (current_user_can('contributor'))) {
        add_action('admin_menu', 'wpmlm_admin_actions_user_nnu');
    } else {
        add_action('admin_menu', 'wpmlm_admin_actions_nnu');
    }
}



function wpmlm_admin_scripts_nnu() {
    wp_enqueue_style('wp-mlm-bootstrap-css', get_stylesheet_directory_uri().'/admin/css/bootstrap.min.css');
    wp_enqueue_style('wp-mlm-font-awesome-css', get_stylesheet_directory_uri().'/admin/css/font-awesome.min.css');
    wp_enqueue_style('wp-mlm-orgchart-style-css', get_stylesheet_directory_uri().'/admin/css/orgchart-style.css');
    wp_enqueue_style('orgchart-css', get_stylesheet_directory_uri().'/admin/css/jquery.orgchart.css');
    wp_enqueue_style('wp-mlm-datepicker', get_stylesheet_directory_uri().'/admin/css/datepicker.css');
    wp_enqueue_style('wp-mlm-dataTables-css', get_stylesheet_directory_uri().'/admin/css/dataTables.bootstrap.min.css');    
    wp_enqueue_script('wp-mlm-jquery-js', get_stylesheet_directory_uri().'/admin/js/jquery.min.js', array( 'jquery' ));
    wp_enqueue_script('wp-mlm-bootstrap-js', get_stylesheet_directory_uri().'/admin/js/bootstrap.min.js', array( 'jquery' ));
    wp_enqueue_script('wp-mlm-bootstrap-datepicker', get_stylesheet_directory_uri().'/admin/js/bootstrap-datepicker.js', array( 'jquery' ));
    wp_enqueue_script('wp-mlm-session-js', get_stylesheet_directory_uri().'/admin/js/jquery.session.min.js', array( 'jquery' ));
    wp_enqueue_script('wp-mlm-orgchart-js', get_stylesheet_directory_uri().'/admin/js/jquery.orgchart.js', array( 'jquery' ));
    wp_enqueue_script('wp-mlm-dataTables', get_stylesheet_directory_uri().'/admin/js/jquery.dataTables.min.js', array( 'jquery' ));
    wp_enqueue_script('wp-mlm-bootstrap-dataTables', get_stylesheet_directory_uri().'/admin/js/dataTables.bootstrap.min.js', array( 'jquery' ));
    wp_enqueue_script('wp-mlm-chart-js', get_stylesheet_directory_uri().'/admin/js/Chart.min.js', array( 'jquery' ));
    wp_enqueue_style('admin-wp-mlm-style', get_stylesheet_directory_uri().'/admin/css/style.css');
    wp_register_script('wp-mlm-my-script', get_stylesheet_directory_uri().'/admin/js/custom.js', array( 'jquery' ));
    wp_enqueue_script('wp-mlm-my-script');
    wp_localize_script('wp-mlm-my-script', 'path', array('pluginsUrl' => get_stylesheet_directory_uri(),));
    wp_localize_script("wp-mlm-my-script", "site", array("siteUrl" => site_url()));
}



if (isset($_GET['page']) && (($_GET['page'] == 'mlm-admin-settings') || ($_GET['page'] == 'mlm-user-settings'))){   
    remove_action('admin_enqueue_scripts', 'wpmlm_admin_scripts');   
    add_action('admin_enqueue_scripts', 'wpmlm_admin_scripts_nnu');   
}
