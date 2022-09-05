<?php
#

/*
 * Enqueue parent css
 */
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}


/*
 * Enqueue wpts files to admin
 */
add_action('admin_enqueue_scripts', 'wp_theme_settings_add_stylesheet');
function wp_theme_settings_add_stylesheet(){
    $cpath = get_stylesheet_directory_uri();
	wp_enqueue_style('wp_theme_settings', $cpath.'/wpts/wp_theme_settings.css');
	wp_register_script('wp_theme_settings',$cpath . '/wpts/wp_theme_settings.js', array('jquery'));
	wp_enqueue_script('wp_theme_settings');
}

/*
 * WPTS
 */
$page = getPageWithCustomQueryAndTranslator();
	 $id_names = array_column($page, 'post_title','ID');
$wpts_general_fields = [
    [
        'type'=>'select',
        'label' => 'Dashboard Menu',
	    'name' => 'select_input[]' ,
		'description' => '',
        'options'=>$id_names,
        'add_more_button'=>'<div id="newRow"></div><div class="button_wraper"><input type="button" name="add_more" id="add_more_button" value="Add Menu Item"></div>',
        'content_more_wraper'=> '<div id="inputFormRow"><div class="button_wraper" id="content_more_wraper">',
        'content_more_wraper_end'=>'</div></div>'
    ]
];

/*[
    'type' => 'fa', 
    'label' => 'Example Icons', 
    'name' => 'example_icons' ,
    'description' => 'Example description',
],*/

$wpts = new wp_theme_settings(
  array(
    'general' => array('description' => ''),
    'settingsID' => 'wp_theme_settings',
    'settingFields' => array('wp_theme_settings_title'), 
    'tabs' => array(
      'general' => array('text' => 'General', 'dashicon' => 'dashicons-admin-generic' , 'tabFields' => $wpts_general_fields),
      
     ),
  )
);