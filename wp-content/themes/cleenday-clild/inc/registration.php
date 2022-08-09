<?php 
$whitelist = array('117.211.65.38');
if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
    define('ACTIVATION_PAGE_ID', 12575);
}else{
    
    define('ACTIVATION_PAGE_ID', 12582);
}
//



function wpmlm_registration_page_shortcode_new() {
    
    wp_enqueue_style('wp-mlm-datepicker', plugin_dir_url('').'wp-mlm/css/datepicker.css' );
    wp_enqueue_style('wp-mlm-custom-style', plugin_dir_url('').'wp-mlm/css/custom-style.css' );

    wp_enqueue_script('wp-mlm-jquery-js', plugin_dir_url('').'wp-mlm/js/jquery.min.js'  , array( 'jquery' ));
    wp_enqueue_script('wp-mlm-jquery-validate', plugin_dir_url('').'wp-mlm/js/jquery.validate.js' , array( 'jquery' ));
    wp_enqueue_script('wp-mlm-bootstrap-datepicker1',plugin_dir_url('').'wp-mlm/js/bootstrap-datepicker.js' , array( 'jquery' ));

    wp_enqueue_script('wp-mlm-my-script1', plugin_dir_url('').'wp-mlm/js/script.js', array( 'jquery' ));

    wp_localize_script('wp-mlm-my-script1', 'path', array('pluginsUrl' => plugins_url(WP_MLM_PLUGIN_NAME),));
    
    if(isset($_GET['payment_method'])){
        if($_GET['payment_method']=='paypal'){
             $payment_obj = new WPMLM_Payment_Method();
            echo $payment_obj->wpmlm_paypal_method();
        }
       
    }

    session_start();
    global $wpdb;
    global $current_user;
    $table_prefix = $wpdb->prefix;

    $result = wpmlm_get_general_information();
    $reg_pack_type = $result->registration_type;
    $current_user_name = $current_user->user_login;
    $reg_amt = $result->registration_amt;
    $reg_amt_currency = $result->company_currency;

    $country_query = "SELECT * FROM ".$wpdb->prefix ."wpmlm_country WHERE 1 ORDER BY name ASC";
    $countries = $wpdb->get_results($country_query);

    $wp_nonce_code = wp_nonce_field("wpmlm_registration", "wpmlm_registration_nonce");

    $sponsor = (isset($_SESSION["sponsor"]) ? $_SESSION["sponsor"]:"");
    $backgroundImages = get_stylesheet_directory_uri().'/images/how-it-banner.jpg';
    $arr = '<style>
    .mlm-main-div{
    }
    #mlm-main-div{
        background-image: url(\''.$backgroundImages.'\') !important;
        background-size: cover !important;
        background-repeat: no-repeat !important;
        background-attachment: scroll !important;
        background-position: center center !important;
        background-color: #202020;
        min-height: 635px !important;
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content: center;
        width: 100%;
        min-height: 100%;
        padding: 20px;
    }
    .container-1{
        -webkit-border-radius: 10px 10px 10px 10px;
        border-radius: 10px 10px 10px 10px;
        background: #fff;
        padding: 30px;
        width: 90%;
        max-width: 700px;
        position: relative;
        padding: 0px;
        -webkit-box-shadow: 0 30px 60px 0 rgb(0 0 0 / 30%);
        box-shadow: 0 30px 60px 0 rgb(0 0 0 / 30%);
        text-align: center;
    }
    .wpmlm-registration-form {
        line-height: 0 !important;
        padding: 0 !important;
        border-radius: 10px;
    }
    input[type=text],.registerClass {
        background-color: #f6f6f6 !important;
        border: none;
        color: #0d0d0d;
        padding: 15px 20px !important;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px !important;
        margin: 5px;
        width: 85%;
        border: 2px solid #f6f6f6 !important;
        -webkit-transition: all 0.5s ease-in-out;
        -moz-transition: all 0.5s ease-in-out;
        -ms-transition: all 0.5s ease-in-out;
        -o-transition: all 0.5s ease-in-out;
        transition: all 0.5s ease-in-out;
        -webkit-border-radius: 5px 5px 5px 5px;
        border-radius: 5px 5px 5px 5px !important;
        line-height: normal !important;
    }
    input[type=\'password\'] {
        display: inline;
        margin: 5px;
    }
    .col-md-6.pl-0{
        min-height: 70px;
    }
    .resqueSubmit{
        background-color: #56baed;
        border: none;
        color: white;
        padding: 15px 80px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        text-transform: uppercase;
        font-size: 13px;
        -webkit-box-shadow: 0 10px 30px 0 rgb(95 186 233 / 40%);
        box-shadow: 0 10px 30px 0 rgb(95 186 233 / 40%);
        -webkit-border-radius: 5px 5px 5px 5px;
        border-radius: 5px 5px 5px 5px;
        margin: 5px 20px 40px 20px;
        -webkit-transition: all 0.3s ease-in-out;
        -moz-transition: all 0.3s ease-in-out;
        -ms-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }
    #formFooter{
        background-color: #f6f6f6;
        border-top: 1px solid #dce8f1;
        padding: 25px;
        text-align: center;
        -webkit-border-radius: 0 0 10px 10px;
        border-radius: 0 0 10px 10px;
    }
    #formFooter a{
        color: #92badd;
        display: inline-block;
        text-decoration: none;
        font-weight: 400;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
    }
    .wpmlm-registration-form label {
        margin-bottom: 12px;
        margin-top: 2px;
        font-size: 13px !important;
        top: 44px;
        position: absolute;
        left: 0px;
        width: 100%;
    }
    .divPadding{
        position: relative;
    }
    </style>';

   

    $arr.= '<div class="col-md-12" id="mlm-main-div">
    
    <div class="container-1">
                    <div class="fadeIn first">
                        <img src="'.get_stylesheet_directory_uri().'/images/rescue-logo.png" id="icon" alt="User Icon" style="width:100px; padding-top:20px; padding-bottom:20px;">
                    </div>
    <div class="alert info submit_message1"></div>';
    if(!empty($_GET['reg_status'])){
        $arr.=  '<div class="info-card-body row notification">
            <div class="col-sm-12 pl-0 divPaddingMsg">
                <div class="alert alert-success" role="alert">
                '.base64_decode($_GET['reg_status']).'
                </div>
            </div>
        </div>';
    }
    $arr.= '<div class="wpmlm-registration-form">
            <div class="info-card">
                    
                <div class="info-card-head" style="display:none;">
                    <h2>'. __("WP MLM User Registration","wpmlm-unilevel").'</h2>
                    <small class="form-text text-muted">'. __("CUSTOMER INFORMATION","wpmlm-unilevel") .'</small>
                </div>';
                
                $arr.= '<div class="info-card-body row">
                    <form class="info-card-form" id="wpmlm-registration-form" method="post">';
                    // $arr.= '<div class="col-md-6 pl-0 divPadding">
                    //         <input type="text" name="sname" class="registerClass main_input" id="sname" value="'.$sponsor.'" placeholder="'. __('Enter Sponsor name','wpmlm-unilevel').'" required>
                    //     </div>';
                        // <label for="sname">'. __("SPONSOR NAME","wpmlm-unilevel").':</label>
                    if ($reg_pack_type != "with_out_package") {
                            $packages = wpmlm_select_all_packages();
                        if (count($packages) > 0) {
                                $result2 = wpmlm_get_general_information();
                            
                $arr.=    '<div class="col-md-6 pl-0 divPadding">
                                <div class="form-group width-col-12">
                                    <select class="registerClass" name="package_select" id="package_select" required>
                                        <option value="" tabindex="1">'.__("Select Package","wpmlm-unilevel").'</option>';
                                    
                                $results = wpmlm_select_all_packages();
                                        foreach ($results as $res) {
                                        
                                $arr.= '<option value="'. $res->id.'">'. $res->package_name . ' - ' . $result2->company_currency . $res->package_price.'</option>' ;
                                } 
                            $arr.= '</select>
                                </div>
                            </div>';
                            } 
                        }
                        
                        if ($reg_pack_type == "with_out_package") {
                        //<label for="reg_amount">'. __("REGISTRATION AMOUNT","wpmlm-unilevel").':</label>
                $arr.='<div class="form-group">
                            
                            <input type="text" name="reg_amount" class="registerClass" id="reg_amount" value="'.(isset($_SESSION["package_price"])) ? $_SESSION["package_price"] : $reg_amt . $reg_amt_currency.'" readonly required>
                        </div>
                        ';
                        }
                    //<label for="fname">'. __("FIRST NAME","wpmlm-unilevel").':</label>
                    //<label for="lname">'. __("LAST NAME","wpmlm-unilevel").':</label>
                    //<label for="dob">'. __("DOB","wpmlm-unilevel").':</label>
                    //<label for="username">'. __("USERNAME","wpmlm-unilevel").':</label>
                    // <label for="email">'. __("EMAIL","wpmlm-unilevel").':</label>
                    // <label for="password">'. __("PASSWORD","wpmlm-unilevel").':</label>
                    // <label for="contact_no">'. __("MOBILE NUMBER","wpmlm-unilevel").':</label>
                    // <label for="address1">'. __("ADDRESS","wpmlm-unilevel").':</label>
                    // <label for="country">'. __("COUNTRY","wpmlm-unilevel") .':</label>
                    // <label for="state">'. __("STATE","wpmlm-unilevel").':</label>
                    // <label for="city">'. __("CITY","wpmlm-unilevel").':</label>
                    // <label for="zip">'. __("ZIP","wpmlm-unilevel").':</label>


                // $arr.='<div class="col-md-6 pl-0 divPadding">
                //             <input type="date" class="registerClass date_of_birth" name="date_of_birth" placeholder="'. __("Enter DOB","wpmlm-unilevel").'" required/>
                //         </div>';
                        $arr.='<div class="col-md-6 pl-0 divPadding">                                
                                <input type="text" class="registerClass " name="fname" id="fname" placeholder="'. __("Enter First Name","wpmlm-unilevel").'" required>           
                        </div>
                        <div class="col-md-6 pl-0 divPadding">
                                <input type="text" class="registerClass" name="lname" id="lname" placeholder="'. __("Enter Last Name","wpmlm-unilevel").'">                            
                        </div>

                        <div class="col-md-6 pl-0 divPadding">
                            <input type="text" class="registerClass main_input" name="username" id="username" placeholder="'. __("Enter Username","wpmlm-unilevel").'" required>
                        </div>


                        <div class="col-md-6 pl-0 divPadding">
                            <input type="password" class="registerClass" name="password" id="password" placeholder="'. __("Enter Password","wpmlm-unilevel").'" required>
                        </div>



                        <div class="col-md-6 pl-0 divPadding">
                           
                                <input type="text" class="registerClass main_input" name="email" id="email_id" placeholder="'. __("Enter Email","wpmlm-unilevel").'" required>
                          
                        </div>

                        <div class="col-md-6 pl-0 divPadding">
                             <input type="text" class="registerClass" name="contact_no" id="contact_no" placeholder="'. __("Mobile Number","wpmlm-unilevel").'" required>
                           
                        </div>';
                        // $arr.='<div class="col-md-6 pl-0 divPadding">
                        //         <input type="text" class="registerClass" name="address1" id="address1" placeholder="'. __("Enter Address","wpmlm-unilevel").'" required>                            
                        // </div>';
                        $arr.= '<div class="col-md-6 pl-0 divPadding">
                            
                                
                                <select class="registerClass" name="country" id="country" required>
                                    <option value="">'. __("Select Country","wpmlm-unilevel") .'</option>';
                                    foreach ($countries as $country) {
                                        if($country){
                                    $arr.='<option value="'.$country->id.'">'.$country->name.'</option>'; 
                                    }else{
                                    $arr.='<option value="">'. __("Country not available","wpmlm-unilevel") .'</option>';
                                        }
                                    }
                                    
                        $arr.= '</select>
                            
                                
                                
                            
                        </div>';
                        // $arr.= '<div class="col-md-6 pl-0 divPadding">
                        //         <input type="text" class="registerClass" name="state" id="state" placeholder="'. __("State","wpmlm-unilevel").'" required>
                        // </div>';

                        $arr.= '<div class="col-md-6 pl-0 divPadding">
                                <input type="text" class="registerClass" name="city" id="city" placeholder="'. __("City","wpmlm-unilevel").'" required>
                        </div>
                        <div class="col-md-6 pl-0 divPadding">
                                <input type="text" class="registerClass" name="zip" id="zip" placeholder="'. __("ZIP Code","wpmlm-unilevel").'" required>
                        </div>';
                        $arr.= '<div class="col-md-6 pl-0" style="display:none;">
                                
                                <br><h4>'. __("Payment Mode","wpmlm-unilevel").'</h4>';
                            
                            $results = wpmlm_select_reg_type();
                            $ckd = 0;
                            $reg_type = "paypal";
                            foreach($results as $res) {
                                $ckd++;
                                if ($ckd == 1) {
                                    $ckd = "checked";
                                } else {
                                    $ckd = "";
                                }
                            
                    
                                if ($res->reg_type == "free_join") {
                    $arr.= '  <div class="form-check form-check-inline width-col-12">
                                    <input class="form-check-input free_join" type="radio" name="user_registration_type" id="user_registration_type" value="'. $res->reg_type .'" required '. $ckd .'>
                                    <label class="form-check-label" for="user_registration_type">'.  ucwords(str_replace("_", " ", $res->reg_type)).'</label>
                                </div>';
                                
                                $reg_type = "";
                                } else {
                                

                        $arr.= '<div class="form-check form-check-inline paypal-radio width-col-12">
                                    <input class="form-check-input paid_join" type="radio" name="user_registration_type" id="user_registration_type" value="paypal"  required '. $ckd. '>
                                    <label class="form-check-label" for="user_registration_type"><img src='. plugins_url() . "/" . WP_MLM_PLUGIN_NAME . "/gateway/paypal-sdk-v2/paypal.svg".'></label>
                                </div>';
                                } 
                            }
                        
            $arr.= '</div>
                    <div class="row">
                        <div class="col-md-12 pl-0">
                        <div class="form-group">
                            <button type="submit" class="resqueSubmit" id="reg_submit" name="reg_submit" value="reg_submit" >'. __("Submit","wpmlm-unilevel").'</button></div>
                        </div>
                    </div>'.$wp_nonce_code.'
                    
                </form>
                </div>

                <div id="formFooter">
                    <a class="underlineHover" href="'.get_site_url().'/login">Log In</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                    <a class="underlineHover" href="'.get_site_url().'/wp-login.php?action=lostpassword">Forgot Password</a>
                </div>


            </div>
        </div>
    </div></div>';

    $arr .= <<<EOT
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#wpmlm-registration-form').on('submit', function(e){
            
            var formData = new FormData(this);
            formData.append('action','wpmlm_registration_page_new');
            formData.append('reg_submit','1');


            isValid = true;
            $(".registerClass").each(function () {
                        var element = $(this);
                        if (element.val() == '') {
                            isValid = false;
                        }
                    });



            if (isValid) {
                console.log({formData:formData,ajaxurl:ajaxurl});
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        console.log(data);
                        
                        if(data.payment_type != "free_join") {
                            window.location.href = data.payment_link;  
                        }else{
                            window.location.href = data.redirect_link;
                        }
                            
                    }
                });
    
            }
            return false;
    
        });
    });
    </script>
    EOT;
    // $(".mlm-main-div").html(data);window.location.href = "../mlm-user";

    return $arr;

} 
add_shortcode('wp_affiliate_registration_form_new', 'wpmlm_registration_page_shortcode_new');

add_shortcode('wp_affiliate_user_dashboard_new', 'wpmlm_user_shortcode_new');


function wpmlm_user_shortcode_new() {
    echo '<div class="col-md-12" id="mlm-main-div2">' . wpmlm_user_area_nnu() . '</div>';
}

add_action('login_header','addContent_loginForm');
function addContent_loginForm(){
    echo '<div class="wrapper">';
}

add_action('login_footer','footerContent_loginForm');
function footerContent_loginForm(){
    echo '</div>';
    ?>
    <script>
        window.onload = function() {
            document.getElementById("user_login").setAttribute("placeholder", "Username");
            document.getElementById("user_pass").setAttribute("placeholder", "Password");
        };
        // jQuery(document).ready(function($) {
        //     jQuery('#user_login').attr('placeholder','Username');
        //     jQuery('#password').attr('placeholder','Password');
        // });
    </script>
    <?php
}
function the_dramatist_custom_login_css() {
   ?>
        <style type="text/css">
        .login #login_error{
                color: #842029;
                background-color: #f8d7da;
                border-color: #f5c2c7;
                position: relative;
                padding: 8px;
                margin-bottom: 1rem;
                border: 1px solid transparent;
                border-radius: 0.25rem;
                font-size: 16px;
            }
            #loginform>p:first-child>label{
                display: none;
            }
            button, input, select, textarea,.login input.password-input{
                font-family: "Poppins", sans-serif;
            }
            #login #nav a:hover{
                background: none !important;
                border: 0 !important;
            }
            #login #nav a{
                background: none !important;
                padding: 0 !important;
                color: unset !important;
                color: #92badd !important;
                display: inline-block;
                text-decoration: none;
                font-weight: 400;
                font-size: 1rem;
            }
            body{
                font-family: "Poppins", sans-serif;
            } 
            .login #nav{
                background-color: #f6f6f6;
                border-top: 1px solid #dce8f1;
                padding: 25px;
                text-align: center;
                -webkit-border-radius: 0 0 10px 10px;
                border-radius: 0 0 10px 10px;
                margin: 0;
            }
            #nav a:after{
                left: 0;
                bottom: -10px;
                width: 0;
                height: 2px;
                background-color: #56baed;
                content: "";
                transition: width 0.2s;
            }
            .privacy-policy-page-link{
                display: none;
            }
            #backtoblog{
                display: none;
            }
            .wp-pwd button[type=button]{
                display: none !important;
            }
            p.forgetmenot{
                width: 100%;
            padding: 10px;
            }
            .login .button-primary{
                float: none;
            }
            
            p.submit{
                text-align: center;
            }
            #wp-submit{
                background-color: #56baed;
                border: none;
                color: white;
                padding: 15px 80px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                text-transform: uppercase;
                font-size: 13px;
                -webkit-box-shadow: 0 10px 30px 0 rgb(95 186 233 / 40%);
                box-shadow: 0 10px 30px 0 rgb(95 186 233 / 40%);
                -webkit-border-radius: 5px 5px 5px 5px;
                border-radius: 5px 5px 5px 5px;
                margin: 5px 20px 40px 20px;
                -webkit-transition: all 0.3s ease-in-out;
                -moz-transition: all 0.3s ease-in-out;
                -ms-transition: all 0.3s ease-in-out;
                -o-transition: all 0.3s ease-in-out;
                transition: all 0.3s ease-in-out;
            }
            .user-pass-wrap label{
                display: none;
            }
            .login input[type=text],.login input[type=password]{
                background-color: #f6f6f6;
                color: #0d0d0d;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 5px;
                width: 85%;
                border: 2px solid #f6f6f6;
                -webkit-transition: all 0.5s ease-in-out;
                -moz-transition: all 0.5s ease-in-out;
                -ms-transition: all 0.5s ease-in-out;
                -o-transition: all 0.5s ease-in-out;
                transition: all 0.5s ease-in-out;
                -webkit-border-radius: 5px 5px 5px 5px;
                border-radius: 5px 5px 5px 5px;
            }
            .login form{
                padding:0;
                border: 0;
            }
            #login h1{
                padding-top: 20px;
            }
            #login h1 a{
                background-image: url(<?php echo get_stylesheet_directory_uri();?>/images/rescue-logo.png);
                background-size: 100px;
                height: 100px;
                width: 100px;
            }
            .wrapper {
                display: flex;
                align-items: center;
                flex-direction: column;
                justify-content: center;
                width: 100%;
                /* min-height: 100%; */
                /* padding: 20px; */
            }
            .wrapper{
                background-image: url(<?php echo get_stylesheet_directory_uri();?>/images/how-it-banner.jpg);
                background-size: cover;
                background-repeat: no-repeat;
                background-attachment: scroll;
                background-position: center center;
                background-color: #202020;
                min-height: 635px;
            }
            #login{
                -webkit-border-radius: 10px 10px 10px 10px;
                border-radius: 10px 10px 10px 10px;
                background: #fff;
                padding: 30px;
                width: 90%;
                max-width: 450px;
                position: relative;
                padding: 0px;
                -webkit-box-shadow: 0 30px 60px 0 rgb(0 0 0 / 30%);
                box-shadow: 0 30px 60px 0 rgb(0 0 0 / 30%);
                text-align: center;
            }
        </style>  
    <?php
}
add_action('login_head', 'the_dramatist_custom_login_css');

add_filter('register_url','updateRegisterLink');
function updateRegisterLink($link){
    $link = get_site_url().'/affiliate-user-registration/';
    return $link;
}
add_action( 'wp_ajax_wpmlm_registration_page_new', 'wpmlm_registration_page_new' );
add_action( 'wp_ajax_nopriv_wpmlm_registration_page_new', 'wpmlm_registration_page_new' );
function wpmlm_registration_page_new(){
    
    if (isset($_POST['reg_submit']) && wp_verify_nonce($_POST['wpmlm_registration_nonce'], 'wpmlm_registration')) {


        $paypal_result = wpmlm_get_paypal_details();
        // define('PAYPAL_BASEURL', 'https://api.sandbox.paypal.com');
        define('PAYPAL_CNT_ID', $paypal_result->paypal_client_id);
        define('PAYPAL_CNT_SEC', $paypal_result->paypal_client_secret);


        session_start();
        $result = wpmlm_get_general_information();
        $reg_pack_type = $result->registration_type;
        $current_user_name = $current_user->user_login;
        $reg_amt = $result->registration_amt;
        $reg_amt_currency = $result->company_currency;

        global $post;
        $post_slug = $post->post_name;
        $redirect_url = home_url($post_slug);
        $paypal_congig_mode = $paypal_result->paypal_mode;

        $sponsor = sanitize_text_field($_POST['sname']);
        $user_first_name = sanitize_text_field($_POST['fname']);
        $user_second_name = sanitize_text_field($_POST['lname']);
        $user_dob = sanitize_text_field($_POST['date_of_birth']);
        $username = sanitize_text_field($_POST['username']);
        $password = sanitize_text_field($_POST['password']);
        $email = sanitize_text_field($_POST['email']);
        $user_mobile = sanitize_text_field($_POST['contact_no']);
        $user_address = sanitize_text_field($_POST['address1']);
        $user_city = sanitize_text_field($_POST['city']);
        $user_state = sanitize_text_field($_POST['state']);
        $user_country = sanitize_text_field($_POST['country']);
        $user_zip = sanitize_text_field($_POST['zip']);
        $user_registration_type = sanitize_text_field($_POST['user_registration_type']);
        $package_select = sanitize_text_field($_POST['package_select']);

        $pkg = wpmlm_select_package_by_id($package_select);

        $_SESSION['package_name'] = $pkg->package_name;
        if($reg_pack_type != 'with_out_package'){
            $_SESSION['package_price'] = $pkg->package_price;
        } else {
            $_SESSION['package_price'] = $reg_amt;
        }

        $_SESSION['session_pkg_id'] = $package_select;

        /**Block for now*/
        
        /*$the_user = get_user_by('login', $sponsor);
        $user_parent_id = $the_user->ID;*/

        $invalid_usernames = array('admin');
        $username = sanitize_user($username);

        //$user_level = wpmlm_get_user_level_by_parent_id($user_parent_id);
        // $user_ref = get_current_user_id();
        //|| email_exists( $email )
        if ( username_exists( $username )  ) {
            return false;
            wp_redirect($redirect_url);
            exit();
        }
        $user_ref = wp_create_user($username, $password, $email );
        if ( $user_ref && !is_wp_error( $user_ref ) ) {
            $code = sha1( $user_ref . time() );
            $activation_link = add_query_arg( array( 'key' => $code, 'user' => $user_ref ), get_permalink( ACTIVATION_PAGE_ID ));
            add_user_meta( $user_ref, 'has_to_be_activated', $code, true );
            //ACTIVATION_PAGE_ID

            update_user_meta( $user_ref, 'first_name', trim( $user_first_name ) );
            update_user_meta( $user_ref, 'last_name', trim( $user_second_name ) );

        }
        $_SESSION['user_ref'] = $user_ref;

        $user_info = get_userdata($user_ref);
        $user_email = $user_info->user_email;
        $_SESSION['user_email'] = $user_email;

        $user_level = 0;
        $user_parent_id = 0;
        $user_details = array(
            'user_ref_id' => $user_ref,
            'user_parent_id' => $user_parent_id,
            'user_first_name' => $user_first_name,
            'user_second_name' => $user_second_name,
            'user_address' => $user_address,
            'user_city' => $user_city,
            'user_state' => $user_state,
            'user_country' => $user_country,
            'user_zip' => $user_zip,
            'user_mobile' => $user_mobile,
            'user_email' => $user_email,
            'user_dob' => $user_dob,
            'user_level' => $user_level,
            'user_registration_type' => $user_registration_type,
            'join_date' => date("Y-m-d H:i:s"),
            'user_status' => 1,
            'package_id' => $_SESSION['session_pkg_id'],
            'user_status' => 1,
        );
       
        $_SESSION['user_details'] = $user_details;
        $current_url = site_url('/');
        if ($user_registration_type == 'free_join') {
            wp_update_user(array('ID' => $user_ref, 'role' => 'custom_user'));
            $success_msg = wpmlm_insert_user_registration_details($user_details);
            
            if ($success_msg) {
                if ( ($reg_amt != 0) || ($reg_pack_type == 'with_package')) {
                    
                    wpmlm_insert_leg_amount($user_ref, $_SESSION['session_pkg_id']);
                   
                }
                $tran_pass = wpmlm_getRandTransPasscode(8);
                $hash_tran_pass = wp_hash_password($tran_pass);
                $tran_pass_details = array(
                    'user_id' => $user_ref,
                    'tran_password' => $hash_tran_pass
                );
                wpmlm_insert_tran_password($tran_pass_details);
                wpmlm_insertBalanceAmount($user_ref);
                //sendMailRegistration($user_email, $username, $password, $user_first_name, $user_second_name);
                //sendMailTransactionPass($user_email, $tran_pass);
                unset($_SESSION['user_ref']);
                unset($_SESSION['user_details']);
                unset($_SESSION['session_pkg_id']);
                unset($_SESSION['user_email']);
                unset($_SESSION['package_price']);
                unset($_SESSION['package_name']);
                unset($_SESSION['sponsor']);
                unset($_SESSION['.']);

                // First get the user details
                $user = get_user_by('login', $username );
                // If no error received, set the WP Cookie
                if ( !is_wp_error( $user ) ) {

                    // wp_clear_auth_cookie();
                    // wp_set_current_user ( $user->ID ); // Set the current user detail
                    // wp_set_auth_cookie  ( $user->ID ); // Set auth details in cookie
                    //*********  Registration Success Mail *********** */
                    do_action(
						'success_mail_become_a_user_mail_sent',
						array(
							'bat_email'   => $user_email,
							'bat_phone'   => '',
							'bat_message' => 'I need become a user',
                            'activation_link' =>$activation_link
						)
					);
                } else {

                    $message = "Failed to log in";
                    
                }

                $dash_result = wpmlm_get_general_information();
                $dash_slug_id = get_post($dash_result->user_dash); 
                // $dash_slug = $dash_slug_id->post_name;
                $dash_slug = "affiliate-user-dashboard";
                $dash_slug = "affiliate-user-registration";
                
                $reg_slug_id = get_post($dash_result->user_registration); 
                $reg_slug = $reg_slug_id->post_name;

                $reg_msg = base64_encode('Registration Completed Successfully!');
                header('Content-Type: application/json');
                echo json_encode([
                            // 'redirect_link' => "{$current_url}{$dash_slug}&?reg_status={$reg_msg}",
                            'redirect_link' => "{$current_url}{$dash_slug}/?reg_status={$reg_msg}",
                            'payment_type' => 'free_join',
                        ]);
                exit();
                //wp_redirect();

            } else {

                $reg_msg = base64_encode('Sorry! Registration Failed, Please try again');
                wp_redirect($current_url .''. $reg_slug.'&?reg_failed=' . $reg_msg);
                exit();

            }

        } 
    }
   
}


add_action('success_mail_become_a_user_mail_sent','userRegistrationMailNotification',10,1);
function userRegistrationMailNotification($arg){
    
    if(!empty($arg['bat_email']) && is_string($arg['bat_email'])){
        $to = $arg['bat_email'];
        
        $activation_link = $arg['activation_link'];
        $subject = esc_html('Request to become a user.','cleenday-child');
        $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
        <html><body>
        <p style="margin: 0 0 20px 0;">
            </p>
        <title>aiep.org.uk</title>
        <div id="wrapper" dir="ltr" style="color: #777;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="center" valign="top">
                                            <div id="template_header_image" style="text-align: center;">
                                <p style="margin-top: 0; margin: 0 0 20px 0;">
                                    <img src="'.get_stylesheet_directory_uri().'/images/rescue-logo.png" alt="">
                                </p>
                            </div>
                            <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="background: #f2fbff;">
                            <thead id="email-header">
                            <tr>
                                <td align="center" valign="top">
                                    <h2 class="order-heading" style="background: #00adff; padding: 20px; color: #FFF; margin: 0 0 20px 0; font-weight: lighter; font-size: 24px; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;">
                                        '.esc_html('Become a user','cleenday-child').'</h2>
                                </td>
                            </tr>
                            </thead>
                            <tbody id="email-body">
                            <tr>
                                <td align="center" valign="top" style="padding: 0 20px 20px 20px;">
                                    <p style="margin: 0 0 20px 0;">
                                    '. sprintf(esc_html( 'User %s has requested to become a user at '.get_site_url(), 'Rescueincome' ),$to).'
                                    </p>
                                    <p style="margin: 0 0 20px 0;">'.esc_html('Please click on the activation link to activate your account.','cleenday-child').'</p>
                                    <p style="margin: 0 0 20px 0;">'.esc_html('Activation Link : ','cleenday-child').' <a href="'.$activation_link.'">Click Here</a></p>';
                                   // $message = ' <p style="margin: 0 0 20px 0;">'.esc_html('Your account is waiting for Admin approval.','cleenday-child').'</p>';
                                    $message .= '<p style="margin: 0 0 20px 0;">						</p>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot id="email-footer">
                            <tr>
                                <td style="text-align: center; padding: 20px; border-top: 1px solid #DDD;">
                                '.esc_html('Rescueincome','cleenday-child').'    
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        </body></html>';

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
        $headers .= 'From: '.get_option( 'admin_email' ) . "\r\n";
        $headers = $headers;
        $return = wp_mail( $to, $subject, $message, $headers);
    }
}

add_action( 'template_redirect', 'wpse8170_activate_user' );
function wpse8170_activate_user() {
    if ( is_page() && get_the_ID() == ACTIVATION_PAGE_ID ) {
        $user_id = filter_input( INPUT_GET, 'user', FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1 ) ) );
        if ( $user_id ) {
            // get user meta activation hash field
            $code = get_user_meta( $user_id, 'has_to_be_activated', true );
            
            if ( $code == filter_input( INPUT_GET, 'key' ) ) {
                delete_user_meta( $user_id, 'has_to_be_activated' );
                $redirect_path = get_site_url().'/wp-login.php';
                $redirect = add_query_arg( 'account_active', 'success', $redirect_path );
                wp_redirect( $redirect );
                exit;
            }
        }
    }
}



/************Add Admin User Field ******************/

function userStatus( $contactmethods ) {
    $contactmethods['status'] = 'User Status';
    return $contactmethods;
}
//add_filter( 'user_contactmethods', 'userStatus', 10, 1 );


function userStatusField( $column ) {
    $column['status'] = 'User Status';
    return $column;
}
//add_filter( 'manage_users_columns', 'userStatusField' );

function userStatusFieldValue( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'status' :
            $code = get_user_meta( $user_id, 'has_to_be_activated', true );
            if($code != false){
                $st = 'Not Activated';
            }else{
                $st = 'Activated';
            }
            return $st;
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'userStatusFieldValue', 10, 3 );



