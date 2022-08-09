<?php 
//ini_set('display_errors',1);
require_once get_stylesheet_directory().'/inc/registration.php';

require_once get_stylesheet_directory().'/inc/admin/adminMlm.php';

function cleenday_child_enqueue_parent_style() {
   
    $theme   = wp_get_theme( 'cleenday' );
    $version = $theme->get( 'Version' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', null, $version );
    wp_enqueue_style( 'child-custom-style', get_stylesheet_directory_uri() . '/css/custom.css', null, $version );
    wp_enqueue_script('wp-themeCustomjs',get_stylesheet_directory_uri() . '/js/custom.js' , array( 'jquery' ));

    wp_enqueue_script('jquery-ui.min', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js' , array( 'jquery' ));
    
    // wp_deregister_script( 'fep-script' );
    
    // wp_register_script( 'fep-script-new', get_stylesheet_directory_uri() . '/js/script.js', array( 'jquery' ), 12, true );
	
    // wp_localize_script( 'fep-script-new', 'fep_script',
	// 	array(
	// 		'root'    => esc_url_raw( rest_url( 'front-end-pm/v1' ) ),
	// 		'nonce'   => wp_create_nonce( 'wp_rest' ),
	// 		'no_match'   => __( 'No matches found', 'front-end-pm' ),
	// 	)
	// );
   
}
add_action( 'admin_enqueue_scripts', 'load_custom_admin_style' );
function load_custom_admin_style() {
   // wp_register_style( 'admin_custom_css', get_stylesheet_directory_uri() . '/customAdmin.css', false, '1.0.0' );
    //OR
    wp_enqueue_style( 'admin_custom_css', get_stylesheet_directory_uri() . '/css/admin/customAdmin.css', false, '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'cleenday_child_enqueue_parent_style' );
/**check local mail**/


/**check local mail**/
function mailtrap($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp.mailtrap.io';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 2525;
    $phpmailer->Username = '584b8fa618659b';
    $phpmailer->Password = '89a65193d6526b';
}

$whitelist = array('117.211.65.38');
if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
   add_action('phpmailer_init', 'mailtrap');
}
  

function userRole_update_custom_roles() {
    if ( get_option( 'custom_roles_version' ) < 1 ) {
        add_role( 'custom_user', 'User', array( 'read' => true, 'level_0' => true ) );
        update_option( 'custom_roles_version', 1 );
    }
    remove_role( 'contributor' );
    // 'author'
    // 'Editor'
    // 'contributor'
    if ( ! is_admin() ) {
        add_filter( 'show_admin_bar' , '__return_false' );
    }
}
add_action( 'init', 'userRole_update_custom_roles' );

//

remove_action('init', 'wpmlm_register_menu');

add_action('init', 'wpmlm_register_menu_nnu');


if ( !function_exists('wp_authenticate') ) :
    function wp_authenticate($username, $password) {
        $username = sanitize_user($username);
        $password = trim($password);
    
        $user = apply_filters('authenticate', null, $username, $password);
    
        if ( $user == null ) {
            // TODO what should the error message be? (Or would these even happen?)
            // Only needed if all authentication handlers fail to return anything.
            $user = new WP_Error('authentication_failed', __('<strong>ERROR</strong>: Invalid username or incorrect password.'));
        } elseif ( get_user_meta( $user->ID, 'has_to_be_activated', true ) != false ) {
            $user = new WP_Error('activation_failed', __('<strong style="color:gray;">ERROR</strong>: User is not activated.'));
        }
    
        $ignore_codes = array('empty_username', 'empty_password');
    
        if (is_wp_error($user) && !in_array($user->get_error_code(), $ignore_codes) ) {
            do_action('wp_login_failed', $username);
        }
    
        return $user;
    }
    endif;

    add_filter('authenticate','checkActivationFromMail',20,3);

    function checkActivationFromMail( $user, $username, $password ) {
            if ( $user instanceof WP_User ) {
                return $user;
            }
            
            $user  = get_user_by( 'login', $username );
           // var_dump($username);
           // var_dump(filter_var($username, FILTER_VALIDATE_EMAIL));
           // echo (filter_var($username, FILTER_VALIDATE_EMAIL)) ? "[+] Email '$username' is valid\n" :         "[-] Email '$username' is NOT valid\n";
          
          
          
            if(empty($user)){
                $user  = get_user_by( 'email', $username );
            }
            
            if (  $user == false ) {
                
                return $user;
            }

            if( filter_var($username, FILTER_VALIDATE_EMAIL) ) {
                $user = new WP_Error('email_failed', __('<strong>ERROR</strong>: Invalid username'));
            }elseif ( $user == null ) {
                $user = new WP_Error('authentication_failed', __('<strong>ERROR</strong>: Invalid username or incorrect password.'));
            } elseif ( get_user_meta( $user->ID, 'has_to_be_activated', true ) != false ) {
                $user = new WP_Error('activation_failed', __('<strong style="color:gray;">ERROR</strong>: User is not activated.'));
            }

            $ignore_codes = array('empty_username', 'empty_password');
        
            if (is_wp_error($user) && !in_array($user->get_error_code(), $ignore_codes) ) {
                do_action('wp_login_failed', $username);
            }
        
            return $user;
            
            // if ( ! $user ) {
            //     return new WP_Error(
            //         'invalid_username',
            //         sprintf(
            //             /* translators: %s: User name. */
            //             __( '<strong>Error</strong>: The username <strong>%s</strong> is not registered on this site. If you are unsure of your username, try your email address instead.' ),
            //             $username
            //         )
            //     );
            // }

        }



function wpsd_add_login_message() {
    return '<p class="message">Your account is now activated. You can now able to login with your account credential.</p>';
}
if ( filter_input( INPUT_GET, 'account_active' ) === 'success' ){
    add_filter('login_message', 'wpsd_add_login_message');
}
/**************user directory query upgrade****************/

add_filter('fep_directory_arguments','directoryQueryUpgrade',10,1);
function directoryQueryUpgrade($arg){
    $parent_id = get_current_user_id();
    $parent_id_arr = array($parent_id);
    $u = wpmlm_get_user_details_by_parent_id_join_new($parent_id_arr);
//var_dump($u);
    if(!empty($u)){
        $arr = array( 'include' => $u );
        $arg = array_merge($arg,$arr);
    }else{
        $u = array(0);
        $arr = array( 'include' => $u );
        $arg = array_merge($arg,$arr);
    }
    
    return $arg;
}


add_filter('fep_menu_buttons','messageBoxMenu',10,1);
function messageBoxMenu($menu){
	
    // if(isset($menu['message_box'])){
    //     unset($menu['message_box']);
    // }
    // if(isset($menu['newmessage'])){
    //     unset($menu['newmessage']);
    // }
    // if(isset($menu['announcements'])){
    //     unset($menu['announcements']);
    // }
    
    // echo '<pre>';
	// //print_r($menu['announcements']);

	// echo '</pre>';
	return $menu;
}
//add_action('fep_message_table_column_content','msgBoxCol');
function msgBoxCol($col){
    
    if($col == 'to_user'){
    }
}
/******************Msg Col Added**********************/
add_filter('fep_message_table_columns','addColOnMsgBox',10,1);
function addColOnMsgBox($arg){
    
    $new['subject']= 'User Email';
    $new['to_user']= 'To User';
	$offset = 2;
	$arg = array_slice($arg, 0, $offset, true) +
			$new +
            array_slice($arg, $offset, NULL, true);
    return $arg;
}

add_action('fep_message_table_column_content','tableContentColumnData',10,2);
function tableContentColumnData($col){
	
    
	if($col == 'to_user'){
        $msg_id = fep_get_the_id();
        
        $id = fep_get_message_field( 'mgs_id', $msg_id );
        if(!empty($id)){
            $data = fep_get_participants($id);
            $meta = fep_get_meta( $id , '_fep_email_sent', true );
           
            if(!empty($data[0])){
                $uid = $data[0];
                $author_obj = get_user_by('id', $uid);
                echo '<div class="toUserName">'.$author_obj->data->user_login.'</div>';
            }
            
            
        }
        
		//echo '<span class="userName">'.$user->data->user_login.'</span>';
	}elseif($col == 'subject'){
		//echo '<span class="userEmail">'.$user->data->user_email.'</span>';
	}

}
/******************Dir Col Added**********************/
add_filter('fep_directory_table_columns','directoryColumnList',1,10);

function directoryColumnList($arr){
	$new['user_info']= 'User Name';
	$new['user_email']= 'User Email';
    $new['send_message_new']= '	Send Messag';
    
	$offset = 3;
	$arr = array_slice($arr, 0, $offset, true) +
			$new +
            array_slice($arr, $offset, NULL, true);

	unset($arr['block_unblock']);
    unset($arr['send_message']);
    unset($arr['fep-cb']);
	return $arr;
}
add_action('fep_directory_table_column_content','tableContentColumn',10,2);
function tableContentColumn($col,$user){
	
	if($col == 'user_info'){
		echo '<span class="userName">'.$user->data->user_login.'</span>';
	}elseif($col == 'user_email'){
		echo '<span class="userEmail">'.$user->data->user_email.'</span>';
	}elseif($col == 'send_message_new'){
        if ( get_current_user_id() != $user->ID ) {
            //user_nicename
            ?><a href="<?php echo fep_query_url( 'newmessage', array( 'fep_to' => $user->user_login ) ); ?>"><?php _e( 'Send Message', 'front-end-pm' ); ?></a><?php
        }
    }

}

/*******msg support**********/
add_filter('fep_message_to_support','newMessageSupport');
function newMessageSupport($support){

    return $support;

}

add_action('fep_form_field_init_output_message_to','messageToFieldOutput',10,2);

function messageToFieldOutput( $field, $errors ){
    if ( $errors->get_error_message( $field['id'] ) ){
        printf( '<div class="fep-error">%s</div>', $errors->get_error_message( $field['id'] ) );
        $errors->remove( $field['id'] );
    }
    $attrib = ''; 
    if ( ! empty( $field['required'] ) ) $attrib .= ' required = "required"';
    if ( ! empty( $field['readonly'] ) ) $attrib .= ' readonly = "readonly"';
    if ( ! empty( $field['disabled'] ) ) $attrib .= ' disabled = "disabled"';
    if ( ! empty( $field['minlength'] ) ) $attrib .= ' minlength = "' . absint( $field['minlength'] ) . '"';
    if ( ! empty( $field['maxlength'] ) ) $attrib .= ' maxlength = "' . absint( $field['maxlength'] ) . '"';
    if ( ! empty( $field['multiple'] ) && 'select' == $field['type'] ) $attrib .= ' multiple = "multiple"';

    $attrib = apply_filters( 'fep_filter_form_field_attrib', $attrib, $field, $errors );
     
    if ( ! empty( $field['class'] ) ){
        $field['class'] = fep_sanitize_html_class( $field['class'] );
    }
    
    ?>
    <div class="fep-form-field fep-form-field-<?php echo esc_attr( $field['id'] ); ?>"><?php if ( ! empty( $field['label'] ) ) { ?>
        <div class="fep-label"><label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['label'] ) ; ?>: <?php if ( ! empty( $field['required'] ) ) : ?><span class="required">*</span><?php endif; ?></label></div>
        <?php } ?>
        <div class="fep-field"><?php
        
        switch( $field['type'] ) {
        
            case has_action( 'fep_form_field_output_' . $field['type'] ):
                do_action( 'fep_form_field_output_' . $field['type'], $field, $errors );
                break;
            case 'text' :
            case 'email' :
            case 'url' :
            case 'number' :
            case 'hidden' :
            case 'submit' :
                printf( '<input type="%1$s" id="%2$s" class="%3$s" name="%4$s" placeholder="%5$s" value="%6$s" %7$s />',
                    esc_attr( $field['type'] ),
                    esc_attr( $field['id'] ),
                    $field['class'],
                    esc_attr( $field['name'] ),
                    esc_attr( $field['placeholder'] ),
                    esc_attr( $field['posted-value' ] ),
                    $attrib
                );
                break;
            case 'message_to' :
                if( isset( $_REQUEST['fep_to'] ) ) {
                    $to = $_REQUEST['fep_to'];
                } elseif( isset( $_REQUEST['to'] ) ) {
                    $to = $_REQUEST['to'];
                } else {
                    $to = '';
                }
                if( ! empty( $field['posted-value' ] ) ) {
                    $message_to = fep_get_userdata( $field['posted-value' ], 'user_nicename' );
                    $message_top = fep_user_name( fep_get_userdata( $message_to, 'ID' ) );
                } elseif( $to ){
                    $support = array(
                        'nicename' 	=> true,
                        'id' 		=> true,
                        'email' 	=> true,
                        'login' 	=> true,
                    );
                    $support = apply_filters( 'fep_message_to_support', $support );
                    //$user = fep_get_userdata( $to, 'user_login', 'login' );
                    
                    if ( ! empty( $support['nicename'] ) && $user = fep_get_userdata( $to, 'user_nicename' ) ) {
                        $message_to = $user;
                        $message_top = fep_user_name( fep_get_userdata( $user, 'ID' ) );
                    } elseif( is_numeric( $to ) && ! empty( $support['id'] ) && $user = fep_get_userdata( $to, 'user_nicename', 'id' ) ) {
                        $message_to = $user;
                        $message_top = fep_user_name( fep_get_userdata( $user, 'ID' ) );
                    } elseif ( is_email( $to ) && ! empty( $support['email'] ) && $user = fep_get_userdata( $to, 'user_nicename', 'email' ) ) {
                        $message_to = $user;
                        $message_top = fep_user_name( fep_get_userdata( $user, 'ID' ) );
                    } elseif ( ! empty( $support['login'] ) && $user = fep_get_userdata( $to, 'user_login', 'login' ) ) {
                        $message_to = $user;
                        
                        $message_top = fep_user_name( fep_get_userdata( $user, 'ID','login' ) );
                    } else {
                        $message_to = '';
                        $message_top = '';
                    }
                    
                } else {
                    $message_to = '';
                    $message_top = '';
                }

                    
               
                if( ! empty( $field['suggestion'] ) ) : ?>
                    <?php wp_enqueue_script( 'fep-script' ); ?>
                    <input type="hidden" name="message_to" id="fep-message-to" autocomplete="off" value="<?php echo esc_attr( $message_to ); ?>" />
                    <input type="text" class="<?php echo $field['class']; ?>" name="message_top" id="fep-message-top" autocomplete="off" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" value="<?php echo esc_attr( $message_top ); ?>" />
                    <div id="fep-result"></div>
                <?php else : ?>
                    <input type="text" class="<?php echo $field['class']; ?>" name="message_to" id="fep-message-top" placeholder="<?php echo esc_attr( $field['noscript-placeholder'] ); ?>" autocomplete="off" value="<?php echo esc_attr( $message_to ); ?>" />
                <?php endif;
                break;
            case "textarea":
                printf( '<textarea id="%1$s" class="%2$s" name="%3$s" placeholder="%4$s" %5$s >%6$s</textarea>',
                    esc_attr( $field['id'] ),
                    $field['class'],
                    esc_attr( $field['name'] ),
                    esc_attr( $field['placeholder'] ),
                    $attrib,
                    esc_textarea( $field['posted-value' ] )
                );
                break;
            case "wp_editor" :
                wp_editor( wp_kses_post( $field['posted-value' ] ), $field['id'], array( 'textarea_name' => $field['name'], 'editor_class' => $field['class'], 'media_buttons' => false ) );
                break;
            case "teeny" :
                wp_editor( wp_kses_post( $field['posted-value' ] ), $field['id'], array( 'textarea_name' => $field['name'], 'editor_class' => $field['class'], 'teeny' => true, 'media_buttons' => false) );
                break;
                    
            case "checkbox" :
                if( ! empty( $field['multiple' ] ) ) {
                    foreach( $field['options' ] as $key => $name ) {
                        printf( '<label><input type="%1$s" id="%2$s" class="%3$s" name="%4$s" value="%5$s" %6$s /> %7$s</label>',
                            'checkbox',
                            esc_attr( $field['id'] ),
                            $field['class'],
                            esc_attr( $field['name'] . '[]' ),
                            esc_attr( $key ),
                            checked( in_array( $key, (array) $field['posted-value' ] ), true, false ),
                            esc_attr( $name )
                        );
                    }
                } else {
                    printf( '<label><input type="%1$s" id="%2$s" class="%3$s" name="%4$s" value="%5$s" %6$s /> %7$s</label>',
                        'checkbox',
                        esc_attr( $field['id'] ),
                        $field['class'],
                        esc_attr( $field['name'] ),
                        '1',
                        checked( $field['posted-value' ], '1', false ),
                        esc_attr( $field['cb_label'] )
                    );
                }
                break;
            case "select" :
                if( ! empty( $field['multiple' ] ) ) {
                    ?>
                    <select id="<?php echo esc_attr( $field['id'] ); ?>" class="<?php echo $field['class']; ?>" name="<?php echo esc_attr( $field['name'] ); ?>[]"<?php echo $attrib; ?>>
                        <?php foreach( $field['options'] as $key => $name ) { ?>
                            <option value="<?php echo esc_attr( $key ); ?>" <?php selected( in_array( $key, (array) $field['posted-value' ] ), true ); ?>><?php echo esc_attr( $name ); ?></option>
                        <?php } ?>
                    </select>
                    <?php
                } else {
                    ?>
                    <select id="<?php echo esc_attr( $field['id'] ); ?>" class="<?php echo $field['class']; ?>" name="<?php echo esc_attr( $field['name'] ); ?>"<?php echo $attrib; ?>>
                        <?php foreach( $field['options'] as $key => $name ) { ?>
                            <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $field['posted-value' ], $key ); ?>><?php echo esc_attr( $name ); ?></option>
                        <?php } ?>
                    </select>
                    <?php
                }
                break;
            case "radio" :
                foreach( $field['options'] as $key => $name ) { ?>
                    <label><input type="radio" class="<?php echo $field['class']; ?>" name="<?php echo esc_attr( $field['name'] ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php checked( $field['posted-value' ], $key ); ?> /> <?php echo esc_attr( $name ); ?></label><br />
                <?php }
                break;
            case 'token' :
            case 'wp_token' :
            case 'shortcode-message-to' :
                printf( '<input type="%1$s" id="%2$s" class="%3$s" name="%4$s" value="%5$s" %6$s />',
                    'hidden',
                    esc_attr( $field['id'] ),
                    $field['class'],
                    esc_attr( $field['name'] ),
                    esc_attr( $field['value' ] ),
                    $attrib
                );
                break;
            case 'fep_parent_id' :
                printf( '<input type="%1$s" id="%2$s" class="%3$s" name="%4$s" value="%5$s" %6$s />',
                    'hidden',
                    esc_attr( $field['id'] ),
                    $field['class'],
                    esc_attr( $field['name'] ),
                    esc_attr( $field['posted-value' ] ),
                    $attrib
                );
                break;
            case 'file' :
                wp_enqueue_script( 'fep-attachment-script' );
                ?>
                    <div id="fep_upload">
                        <div class="fep-attachment-field-div">
                            <input class="fep-attachment-field-input" type="file" name="<?php echo esc_attr( $field['name'] ); ?>[]" /><a href="#" class="fep-attachment-field-a"><?php echo __( 'Remove', 'front-end-pm' ); ?></a>
                        </div>
                    </div>
                    <a id="fep-attachment-field-add" href="#"><?php echo __( 'Add more files', 'front-end-pm' ) ; ?></a>
                    <div id="fep-attachment-note"></div>
                <?php
                break;
            case "action_hook" :
                $field['hook'] = empty( $field['hook'] ) ? $field['key'] : $field['hook'] ;
                do_action( $field['hook'], $field );
                break;
            case "function" :
                $field['function'] = empty( $field['function'] ) ? $field['key'] : $field['function'];
                if( is_callable( $field['function'] ) ){
                    call_user_func( $field['function'], $field );
                }
                break;
            default :
                    printf(__( 'No Function or Hook defined for %s field type', 'front-end-pm' ), $field['type'] );
                break;
        }
        if ( ! empty( $field['description'] ) ) {
            ?><div class="description"><?php echo  wp_kses_post( $field['description'] ); ?></div><?php
        }

    ?>
        </div>
    </div><?php 
}

add_filter('fep_filter_show_which_name','displayNameSetForMessage');

function displayNameSetForMessage($arr){
    
    return 'user_login';
}
/*********************ajax autosuggestion *******************************/
add_filter('fep_filter_rest_users_response_autosuggestion','autoFillAjaxContent',10,4);

function autoFillAjaxContent($response, $args, $q, $x){
    $parent_id = get_current_user_id();
    $parent_id_arr = array($parent_id);
    $u = wpmlm_get_user_details_by_parent_id_join_new($parent_id_arr);
//var_dump($u);
    if(!empty($u)){
        $arr = array( 'include' => $u );
        $args = array_merge($args,$arr);
    }else{
        $u = array(0);
        $arr = array( 'include' => $u );
        $args = array_merge($args,$arr);
    }

    $users = get_users( $args );
				foreach ( $users as $user ) {
					$response[] = array(
						'id'       => $user->ID,
						'nicename' => $user->user_login,
						'name'     => fep_user_name( $user->ID ),
					);
				}
    return  $response ;
             
}

function validate_field_errors( $field, $errors ){
    if( ! empty( $field['required'] ) && empty( $field['posted-value'] ) ){
        $errors->add( $field['id'], ! empty( $field['error-message'] ) ? $field['error-message'] : sprintf( __( '%s required.', 'front-end-pm' ), esc_html( $field['label'] ) ) );
    } elseif( (! empty( $field['readonly'] ) || ! empty( $field['disabled'] ) /* || $field['type'] == 'hidden' */ ) && $field['value'] != $field['posted-value'] ){
        $errors->add( $field['id'], ! empty( $field['error-message'] ) ? $field['error-message'] : sprintf( __( '%s can not be changed.', 'front-end-pm' ), esc_html( $field['label'] ) ) );
    } elseif( ! empty( $field['minlength'] ) && strlen( $field['posted-value'] ) < absint( $field['minlength'] ) ){
        $errors->add( $field['id'], ! empty( $field['error-message'] ) ? $field['error-message'] : sprintf( __( '%s minlength %d.', 'front-end-pm' ), esc_html( $field['label'] ), absint( $field['minlength'] ) ) );
    } elseif( ! empty( $field['maxlength'] ) && strlen( $field['posted-value'] ) > absint( $field['maxlength'] ) ){
        $errors->add( $field['id'], ! empty( $field['error-message'] ) ? $field['error-message'] : sprintf( __( '%s maxlength %d.', 'front-end-pm' ), esc_html( $field['label'] ), absint( $field['maxlength'] ) ) );
    }
}
add_action('fep_form_field_init_validate_message_to','validateAjaxSubmit',10,2);
function validateAjaxSubmit($field, $errors){
    
		validate_field_errors( $field, $errors );
			
		switch( $field['type'] ) {
			case has_action( 'fep_form_field_validate_' . $field['type'] ):
				do_action( 'fep_form_field_validate_' . $field['type'], $field, $errors );
				break;
			case 'email' :
				if( $field['posted-value'] && ! is_email( $field['posted-value'] ) ){
					$errors->add( $field['id'], ! empty( $field['error-message'] ) ? $field['error-message'] : sprintf(__("Please provide valid email address for %s.", 'front-end-pm' ), esc_html( $field['label'] ) ) );
				}
				break;
			case 'number' :
				if( $field['posted-value'] && ! is_numeric( $field['posted-value'] ) ){
					$errors->add( $field['id'], ! empty( $field['error-message'] ) ? $field['error-message'] : sprintf(__("%s is not a valid number.", 'front-end-pm' ), esc_html( $field['label'] ) ) );
				}
				break;
			case 'token':
				if ( ! fep_verify_nonce( $field['posted-value'], $field['token-action'] ) ) {
					$errors->add( $field['id'], __("Invalid Token. Please try again!", 'front-end-pm' ) );
				}
			break;
			case 'wp_token':
				if ( ! wp_verify_nonce( $field['posted-value'], $field['token-action'] ) ) {
					$errors->add( $field['id'], __("Invalid Token. Please try again!", 'front-end-pm' ) );
				}
			break;
			case 'message_to' :
			case 'shortcode-message-to' :
				if ( ! empty( $_POST['message_to'] ) ) {
					$preTo = $_POST['message_to'];
				} else {
					$preTo = ( isset( $_POST['message_top'] ) ) ? $_POST['message_top']: ''; 
				}
				$preTo = apply_filters( 'fep_preto_filter', $preTo ); //return user_nicename
				
				if( ! is_array( $preTo ) ){
					$preTo = array( $preTo );
				}
				$_POST['message_to_id'] = array();
				
				foreach ( $preTo as $pre ) {
					$to = fep_get_userdata( $pre,"ID",'login' );
					
					if( $to && get_current_user_id() != $to) {
						$_POST['message_to_id'][] = $to;
						if ( ! fep_current_user_can( 'send_new_message_to', $to ) ) {
							$errors->add( $field['id'] .'-permission' , sprintf(__("%s does not want to receive messages!", 'front-end-pm' ), fep_user_name( $to ) ) );
						}
					} else {
						$errors->add( $field['id'] , sprintf(__( 'Invalid receiver "%s".', 'front-end-pm' ), $pre ) );
					}
				}
				if ( empty( $_POST['message_to_id'] ) ) {
					$errors->add( $field['id'] , __( 'You must enter a valid recipient!', 'front-end-pm' ) );
				}
				break;
			case 'fep_parent_id' :
				 if ( empty( $field['posted-value'] ) || ! is_numeric( $field['posted-value'] ) || fep_get_parent_id( $field['posted-value'] ) != $field['posted-value'] ) {
					$errors->add( $field['id'] , __("Invalid parent ID!", 'front-end-pm' ) );
				 } elseif ( ! fep_current_user_can( 'send_reply', $field['posted-value'] ) ) {
					$errors->add( $field['id'] , __("You do not have permission to send this message!", 'front-end-pm' ) );
				}
				break;
			case "checkbox" :
				if( ! empty( $field['multiple' ] ) ) {
					$value = $_POST[ $field['name'] ] = is_array( $field['posted-value'] ) ? $field['posted-value'] : array();
					foreach( $value as $p_value ) {
						if( ! array_key_exists( $p_value, $field['options'] ) ) {
							$errors->add( $field['id'], ! empty( $field['error-message'] ) ? $field['error-message'] : sprintf(__("Invalid value for %s.", 'front-end-pm' ), esc_html( $field['label'] ) ) );
						}
					}
				} else {
					$_POST[ $field['name'] ] = !empty( $_POST[ $field['name'] ] ) ? 1 : 0;
				}
				break;
			case "radio" :
				if( $field['posted-value'] && ! array_key_exists( $field['posted-value'], $field['options'] ) ) {
					$errors->add( $field['id'], ! empty( $field['error-message'] ) ? $field['error-message'] : sprintf(__("Invalid value for %s.", 'front-end-pm' ), esc_html( $field['label'] ) ) );
				}
				break;
			case "select" :
				if( ! empty( $field['multiple' ] ) ) {
					$value = $_POST[ $field['name'] ] = is_array( $field['posted-value'] ) ? $field['posted-value'] : array();
					foreach( $value as $p_value ) {
						if( ! array_key_exists( $p_value, $field['options'] ) ) {
							$errors->add( $field['id'], ! empty( $field['error-message'] ) ? $field['error-message'] : sprintf(__("Invalid value for %s.", 'front-end-pm' ), esc_html( $field['label'] ) ) );
						}
					}
				} else {
					if( $field['posted-value'] && ! array_key_exists( $field['posted-value'], $field['options'] ) ) {
						$errors->add( $field['id'], ! empty( $field['error-message'] ) ? $field['error-message'] : sprintf(__("Invalid value for %s.", 'front-end-pm' ), esc_html( $field['label'] ) ) );
					}
				}
				break;
			case "file" :
				$mime = get_allowed_mime_types();
				$size_limit = (int) wp_convert_hr_to_bytes(fep_get_option( 'attachment_size','4MB' ) );
				$fields = (int) fep_get_option( 'attachment_no', 4);
				
				if( ! isset( $_FILES[ $field['name'] ] ) || empty( $_FILES[ $field['name'] ]['tmp_name'] ) )
					break;
				
				if( ! is_array( $_FILES[ $field['name'] ] ) || ! is_array( $_FILES[ $field['name'] ]['tmp_name'] ) ){
					$errors->add( 'AttachmentNotArray', __( 'Invalid Attachment', 'front-end-pm' ) );
					break;
				}
					
				if( $fields < count( $_FILES[ $field['name'] ]['tmp_name'] ) ){
					$errors->add( 'AttachmentCount', sprintf( __( 'Maximum %s allowed', 'front-end-pm' ), sprintf(_n( '%s file', '%s files', $fields, 'front-end-pm' ), number_format_i18n( $fields ) ) ) );
					break;
				}
				foreach( $_FILES[ $field['name'] ]['tmp_name'] as $key => $tmp_name ) {
					$file_name = isset( $_FILES[$field['name']]['name'][ $key ] ) ? basename( $_FILES[$field['name']]['name'][ $key ] ) : '' ;
			
					//if file is uploaded
					if ( $tmp_name ) {
						$attach_type = wp_check_filetype( $file_name, $mime );
						$attach_size = $_FILES[ $field['name'] ]['size'][ $key ];
						//check file size
						if ( $attach_size > $size_limit ) {
							$errors->add( 'AttachmentSize', sprintf(__( 'Attachment (%1$s) file is too big. Maximum file size allowed %2$s', 'front-end-pm' ), esc_html( $file_name), fep_get_option( 'attachment_size','4MB' ) ) );
						}
						//check file type
						if ( empty( $attach_type['type'] ) ) {
							$errors->add( 'AttachmentType', sprintf(__( "Invalid attachment file type. Allowed Types are (%s)", 'front-end-pm' ),implode( ', ',array_keys( $mime) ) ) );
						}
					} // if $tmp_name
				}// endforeach
				break;
			default :
				do_action( 'fep_form_field_validate', $field, $errors );
				break;
		}
}

remove_filter('authenticate', 'wp_authenticate_email_password', 20);


add_filter('authenticate', function($user, $email, $password){

    //Check for empty fields
    
    if(empty($user) || empty ($password)){        
        //create new error object and add errors to it.
        $error = new WP_Error();
        if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error->add('wrong_username', __('<strong>ERROR</strong>: Enter a valid Username .'));
        }elseif(empty($user)){ //No email
            $error->add('empty_username', __('<strong>ERROR</strong>: Username field is empty.'));
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ //Invalid Email
            $error->add('invalid_username', __('<strong>ERROR</strong>: Email is invalid.'));
        }

        if(empty($password)){ //No password
            $error->add('empty_password', __('<strong>ERROR</strong>: Password field is empty.'));
        }

        return $error;
    }

    //Check if user exists in WordPress database
    $user = get_user_by('login', $email);

    //bad email
    if(!$user){
        $error = new WP_Error();
        $error->add('invalid', __('<strong>ERROR</strong>: Either the username or password you entered is invalid.'));
        return $error;
    } elseif ( get_user_meta( $user->ID, 'has_to_be_activated', true ) != false ) {
        $error = new WP_Error();
        $error->add('activation_failed', __('<strong>ERROR</strong>: User is not activated.'));
        return $error;
    }
    else{ //check password
        if(!wp_check_password($password, $user->user_pass, $user->ID)){ //bad password
            $error = new WP_Error();
            $error->add('invalid', __('<strong>ERROR</strong>: Either the username or password you entered is invalid.'));
            return $error;
        }else{
            return $user; //passed
        }
    }
}, 20, 3);

/**********user activation************/
add_filter( 'manage_users_columns', 'user_add_column'  );
add_filter( 'manage_users_custom_column', 'user_status_column' , 10, 3 );
function user_add_column( $columns ) {
    $the_columns['new_user_status'] = __( 'Status', 'new-user-approve' );
    //$the_columns['verification_status'] = __( 'Email Status', 'new-user-approve' );
    $newcol = array_slice( $columns, 0, -1 );
    $newcol = array_merge( $newcol, $the_columns );
    $columns = array_merge( $newcol, array_slice( $columns, 1 ) );
    return $columns;
}
function user_status_column( $val, $column_name, $user_id ) {
    switch ( $column_name ) {
        case 'new_user_status' :
                $user_info = get_user_by('id',$user_id);

                $code = get_user_meta( $user_id, 'has_to_be_activated', true );
                
                $status = ($code!= false)?'disabled':'approved';
                /*user_status = 2 (approved), user_status = 0,3 (disabled)  */
                //$status = $user_info->data->user_status == 2?'disabled':'approved' ;
                
                if ( $status == 'approved' ) {
                    
                        $status_i18n = __( 'approved', 'new-user-approve' );
                        $status = '<div class="decision '.$user_info->data->user_status.'">
                                '.__(ucfirst($status_i18n),'divichild').' |
                                <a href="#" data-user_id="' . $user_id . '" data-status="approved" class="dapprove ">'.__(ucfirst(__( 'Disapproved', 'new-user-approve' )),'divichild').'</a>
                                </div>';
                } else if ( $user_info->data->user_status == 2 ) {
                    $status_i18n = __( 'approved', 'new-user-approve' );
                    $status = '<div class="decision">
                        <a href="#" data-user_id="' . $user_id . '" data-status="'.($status_i18n=='approved'?'disabled':'approved').'" class="dapprove ">'.__(ucfirst($status_i18n),'divichild').'</a> |
                        <a href="#" data-user_id="' . $user_id . '" data-status="approved" class="dapprove ">'.__(ucfirst(__( 'Disapproved', 'new-user-approve' )),'divichild').'</a>
                        </div>';
                } else if ( $status == 'disabled' ) {
                    $status_i18n = __( 'approved', 'new-user-approve' );
                    $status = '<div class="decision '.$user_info->data->user_status.'">
                        <a href="#" data-user_id="' . $user_id . '" data-status="'.($status_i18n=='approved'?'disabled':'approved').'" class="dapprove ">'.__(ucfirst($status_i18n),'divichild').'</a> |
                        '.__(ucfirst(__( 'Disapproved', 'new-user-approve' )),'divichild').'
                        </div>';
                }else if(in_array("administrator", $user_info->roles)){
                    $status_i18n = __( 'approved', 'new-user-approve' );
                    $status = '<div class="decision 3">
                        '.__(ucfirst($status_i18n),'divichild').'
                        </div>';
                }
        return $status;
        break;
            case 'verification_status' :  
                $verified_email =  get_user_meta($user_id, 'ihc_verification_status', TRUE);
                if($verified_email==1){
                    $emailstatus = __('Verified','divichild');
                }else if($verified_email==-1){
                    $emailstatus = __('Unapproved','divichild');
                }else{
                    $emailstatus = '-';
                }
                return $emailstatus;
        break;
        default:
    }
    return $val;
}



add_action( 'wp_ajax_set_user_status', 'set_user_status_ajax_handler' );
add_action( 'admin_footer', 'set_user_status_js' );
function set_user_status_js()
{
    $nonce = wp_create_nonce('set_user_status');
    $ajax_url = admin_url('admin-ajax.php'); ?>
    <script type="text/javascript">
    (function($){
        $(document).ready(function(){
            $('body').on('click','.decision a',function(event){
                event.preventDefault();
                var that = $(this);
                var user_id = $(this).data('user_id');
                var status = $(this).data('status');
                var newstatus = '<div class="decision dapprove ajax_load"></div>';
                var selected_item = that.parent().parent();
                selected_item.html(newstatus);
                $.post( "<?= $ajax_url; ?>", {
                    nonce: "<?= $nonce; ?>",
                    action: 'set_user_status',
                    user_id: $(this).data('user_id'),
                    status: $(this).data('status'),
                }, function(data){
                    if (data.ok) {
                        console.log({'dat':data});
                        var nnewstatus = '<div class="decision"><a href="#" data-user_id="' +user_id+ '" data-status="'+(status == 'approved'?'disabled':'approved')+'" class="dapprove">'+data.bstatus+'</a></div>';
                        if(data.status_html != ''){
                        selected_item.html(data.status_html);

                        jQuery( "#menu-users .wp-menu-name" ).append( data.notification_ball );
                        }
                    }
                });
            });
        });
    })(jQuery)
    </script>
    <?php
}

function set_user_status_ajax_handler()
{

    global $wpdb;
    $nonce = $_POST['nonce'];

    if ( ! wp_verify_nonce( $nonce, 'set_user_status' ) )
    die ( 'Not permitted');

    // Extract the vars from the Ajax request
    $user_id = $_POST['user_id'];
    

    // Now update the relevant post
    $nnew_status = esc_attr( $_POST['status'] );
        
    if ( $nnew_status == 'approved' ){
      
        update_user_meta( $user_id, 'has_to_be_activated',3 );
    }else if ( $nnew_status == 'disabled' ){
        
        
        delete_user_meta( $user_id, 'has_to_be_activated' );
        activeWpmlTable($user_id);
    }
    /** 0 for active 2 for default  In-active, 3 for inactive */
   
        
    
    //$update = $wpdb->query('UPDATE '.$wpdb->prefix.'users SET user_status = '.$user_status.' WHERE ID = '.$user_id);


    if ( $nnew_status == 'disabled' ) {

        $status_i18n = __( 'approved', 'new-user-approve' );
        $status = '<div class="decision 2">
            '.__(ucfirst($status_i18n),'divichild').' |
            <a href="#" data-user_id="' . $user_id . '" data-status="approved" class="dapprove ">'.__(ucfirst(__( 'Disapproved', 'new-user-approve' )),'divichild').'</a>
            </div>';


        

    } else if ( $nnew_status == 2 ) {
        $status_i18n = __( 'approved', 'new-user-approve' );
        $status = '<div class="decision">
                <a href="#" data-user_id="' . $user_id . '" data-status="'.($status_i18n=='approved'?'disabled':'approved').'" class="dapprove ">'.__(ucfirst($status_i18n),'divichild').'</a> |
                <a href="#" data-user_id="' . $user_id . '" data-status="approved" class="dapprove ">'.__(ucfirst(__( 'Disapproved', 'new-user-approve' )),'divichild').'</a>
                </div>';
    
    } else if ( $nnew_status == 'approved' ) {
        $status_i18n = __( 'approved', 'new-user-approve' );
        $status = '<div class="decision 3">
            <a href="#" data-user_id="' . $user_id . '" data-status="'.($status_i18n=='approved'?'disabled':'approved').'" class="dapprove ">'.__(ucfirst($status_i18n),'divichild').'</a> |
            '.__(ucfirst(__( 'Disapproved', 'new-user-approve' )),'divichild').'
            </div>';
        
    }

    // make sure it all went OK
    if (is_wp_error($user_id))
    {
    $response = [
    'ok' => false,
    ];
    } else 
    {
        /*file_email to approve the account*/
        //mail_for_disabled_enabled_account($uuser_id,$new_status);
        /*file_email to approve the account*/

        $user = "SELECT count(*) as total_registered_user FROM `".$wpdb->prefix."users` where user_status = 0 ";
        $res = $wpdb->get_results($user,ARRAY_A);
        $notification_ball = '';
        if(!empty($res) && !empty($res[0]['total_registered_user'])){
            $notification_ball = '<span class="ihc-top-bar-count additional_style_for_user">'.$res[0]['total_registered_user'].'</span>';
        }

            $response = [
            'ok'      => true,
            'id'      => $user_id,
            'status'  =>    get_user_meta( $user_id, 'has_to_be_activated',true ),
            'bstatus' => ucfirst(__($nnew_status,'divichild')),
            'status_html' => $status,
            'notification_ball'=>$notification_ball
            ];
    }

    // Return the response
    wp_send_json( $response );
}

function activeWpmlTable($user_id){
global $wpdb;
    $table_prefix = $wpdb->prefix;
    $sql = "update {$table_prefix}wpmlm_users set user_status=1 WHERE user_ref_id = '" . $user_id . "'";
    $wpdb->query($sql);
}



function getUserNameWpmlTable($user_id){
    global $wpdb;
        $table_prefix = $wpdb->prefix;
        $sql = "select user_login from {$table_prefix}users WHERE ID = '" . $user_id . "'";
        $user_login = $wpdb->get_row($sql);
         return $user_login->user_login;
    }

    function getSponsorDetails(){
        ob_start();
        $login_id = get_current_user_id();
        
        $t = wpmlm_get_user_details_by_id_join($login_id);
        if(!empty($t[0]->user_parent_id)){
            $sponsor_id = $t[0]->user_parent_id;
            //$user_info=get_userdata($sponsor_id);
            $user_info = wpmlm_get_user_details_by_id_join($sponsor_id);
        }
       
        ?>
        <div class="panel-border col-md-12 col-sm-8 panel-ioss-mlm" style="float: right;margin-top:0">
            <div class="col-md-12 col-xs-12 col-md-12">
                    <h4><?php _e('Sponsor Details','wpmlm-unilevel'); ?></h4>
                    <p>
                        <?php  //$user_info->user_login;?>
                        <?php echo $user_info[0]->user_first_name;?> <?php echo $user_info[0]->user_second_name;?><br>
                        <span><?php echo $user_info[0]->user_login;?> (<?php echo $user_info[0]->user_email;?>)</span>
                    </p>
                    <p></p>
            </div>
            <div class="col-sm-5 col-xs-6 col-md-5">
                <!-- <img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/bar-chart.png'; ?>"> -->
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

   


    add_action('login_head', 'wpmlm_login_styleTheme');

    function wpmlm_login_styleTheme() {
        echo '<style type="text/css">
            .login .reset-pass-submit .button{
                margin:5px 0px 40px 8px
            }
            .reset-pass-submit #wp-submit{
                margin:5px 10px 40px 10px
            }
            .login #pass-strength-result{
                margin-top: 5px !important;
                margin: 0 auto;
                width:90% !important;
            }
        </style>';
    }
add_filter('fep_message_table_bulk_actions','removeArchiveFromMsgDropDown');
 function removeArchiveFromMsgDropDown($param){
    
    unset($param['archive']);
    unset($param['mark-as-unread']);
    unset($param['mark-as-read']);
    return $param;
 }   

 add_filter('fep_message_table_filters','removeArchiveFromSandboxDropDown');
 function removeArchiveFromSandboxDropDown($param){
    unset($param['archive']);
    unset($param['unread']);
    //unset($param['Unread']);
    return $param;
 }