
<?php
function wpmlm_user_dashboard_nnu($user_id) {
    $user_row = wpmlm_getUserDetailsByParent($user_id);    
    $j_count =  wpmlm_getJoiningByTodayCountByUser($user_id);
    $current_user = wp_get_current_user();
    
    $ewallet_credit = wpmlm_getEwalletAmountByUser('credit',$user_id);    
    $ewallet_debit = wpmlm_getEwalletAmountByUser('debit',$user_id);    
    $debit_amt = ($ewallet_debit->sum !=''? $ewallet_debit->sum:0);
    $credit_amt = ($ewallet_credit->sum !=''? $ewallet_credit->sum:0);    
    $bonus_amount = wpmlm_get_total_leg_amount_by_user_id($user_id);
    $bonus_amount_today = wpmlm_get_total_leg_amount_by_user_id_today($user_id);
    
    $bonus_total_amt = ($bonus_amount->total_amount !=''? $bonus_amount->total_amount:0);
    $bonus_total_amt_today = ($bonus_amount_today->total_amount !=''? $bonus_amount_today->total_amount:0);   
    $general = wpmlm_get_general_information();
    $year = date('Y');

    
    $joining_details = wpmlm_getJoiningDetailsUsersByMonth($user_id,$year);
    
    
    if ($joining_details) {
        $i = 0;
        foreach ($joining_details as $jdt) {
            $i++;
            if ($i == $jdt->month) {
                $joining_count[] = $jdt->count;
            } else {

                for ($j = $i; $j < $jdt->month; $j++) {
                    $joining_count[] = 0;
                }
                $joining_count[] = $jdt->count;
                $i++;
            }
        }
        $joining_count = implode(',', $joining_count);
    } else {
        $joining_count = '0,0,0,0,0,0,0,0,0,0,0,0';
    }
    ?>
    
    <div id="general-settings">
           <div class="panel-border col-md-12" style="margin-top:0;">   
                    

                         
                    <?php /*     
                        <div class="panel-border col-md-4 col-sm-4 panel-ioss-mlm">
                                <div class="col-md-7 col-xs-6 col-md-7">
                                    <h4><?php _e('Downlines','wpmlm-unilevel'); ?></h4>
                                    <p><?php _e('Total','wpmlm-unilevel'); ?>: <span><?php echo count($user_row);?> </span></p>
                                    <p><?php _e('Today','wpmlm-unilevel'); ?>: <span><?php echo $j_count->count;?></span></p>
                            </div>
                            <div class="col-sm-5 col-xs-6 col-md-5">
                                <img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/bar-chart.png'; ?>">
                            </div>
                        </div>
                        <div class="panel-border col-md-4 col-sm-4 panel-ioss-mlm">
                            <div class="col-md-7 col-xs-6 col-md-7">
                                <h4><?php _e('Bonus','wpmlm-unilevel'); ?></h4>
                                <p><?php _e('Total','wpmlm-unilevel'); ?>: <span><?php echo $general->company_currency;?><?php echo $bonus_total_amt;?></span></p>
                                <p><?php _e('Today','wpmlm-unilevel'); ?>: <span><?php echo $general->company_currency;?><?php echo $bonus_total_amt_today;?></span></p>
                            </div>
                            <div class="col-sm-5 col-xs-6 col-md-5">
                                <img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/money-bag.png'; ?>">
                            </div>
                        </div>
                        <div class="panel-border col-md-4 col-sm-4 panel-ioss-mlm">
                            <div class="col-md-7 col-xs-6 col-md-7">
                                <h4><?php _e('E-Wallet','wpmlm-unilevel'); ?></h4>
                                <p><?php _e('Credit','wpmlm-unilevel'); ?>: <span><?php echo $general->company_currency;?><?php echo $credit_amt;?></span></p>
                                <p><?php _e('Debit','wpmlm-unilevel'); ?>: <span><?php echo $general->company_currency;?><?php echo $debit_amt;?></span></p>
                            </div>
                            <div class="col-sm-5 col-xs-6 col-md-5">
                                <img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/wallet.png'; ?>">
                            </div>
                        </div> */
                    ?>
   

      <div class="panel-border col-md-6" style="padding-left: 0px;padding-top: 11px;">
         
            <script>
            window.onload = function () {
                var ctx = document.getElementById("myChart");
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                        datasets: [{
                                label: 'Joinings',
                                lineTension: 0,
                                data: [<?php echo $joining_count; ?>],
                                backgroundColor: ['rgba(54, 162, 235, 0.2)'],
                                borderColor: ['rgba(54, 162, 235, 1)'],
                                borderWidth: 1
                            }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                        }
                    }
                });
            }
         </script>
         <canvas id="myChart" width="400" height="400">
         </canvas>
      </div>
               
               
    <div class="panel-border  col-md-6" style="padding-right: 0px;padding-top: 0px !important;">
                    <div class="panel-border col-md-12" style="margin-top:0">
                        
                                <?php echo getSponsorDetails(); ?>
                                
                    </div>    
                <table class="table table-striped table-bordered table-responsive-lg" cellspacing="0" width="100%">
                <thead>
                <caption class="user-table-profile"><?php _e('Recently joined users','wpmlm-unilevel'); ?></caption>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col"><?php _e('Username','wpmlm-unilevel'); ?></th>
                    <th scope="col"><?php _e('Fullname','wpmlm-unilevel'); ?></th>
                    <th scope="col"><?php _e('Email ID','wpmlm-unilevel'); ?></th>
                    </tr>
                </thead>
                
                <tbody class="panel-body content-class-mode">
                    
                        
                    <?php
                    $last_joined = wpmlm_get_recently_joined_users_by_parent($user_id,'4');
                    $jcount = 0;
                    foreach($last_joined as $lj){
                        $jcount++;
                        ?>
                    <tr>
                    <th scope="row"><?php echo $jcount;?></th>
                    <td><?php echo $lj->user_login;?></td>
                    <td><?php echo $lj->user_first_name.' '.$lj->user_second_name;?> </td>
                    <td><?php echo $lj->user_email;?> </td>
                    </tr>
                    <?php }?>
                    
                    
                    
                </tbody>
                </table>
        <?php /*
        <div class="mlm-users">
            <h4 class="usr"><?php _e("Top Bonus Earned Users","wpmlm-unilevel"); ?></h4>
            
            <?php 
            $top_earners = wpmlm_get_total_leg_amount_all_users_under_parent($user_id);
            if(count($top_earners)==0){
                echo '<div class="top_earners_div"><p><?php _e("No bonus earned users yet","wpmlm-unilevel"); ?></p><div>';
            }?>
            
            <?php 
            foreach($top_earners as $te){?>
                <div class="col-md-4">
                    <div class="user-list">
                        <img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/avatar.png'; ?>">
                        <li><h4><?php echo $te->user_first_name;?></h4></li>
                        <li><?php echo $general->company_currency;?><?php echo $te->total_amount;?></li>
                    </div>            
                </div>
            <?php
                }
            ?>
        </div>
        */?>
      </div>           

        </div>
    </div>
    <?php
}
function wpmlm_get_user_details_by_parent_id_join_new($parent_id) {
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    if(!is_array($parent_id)){
        return false;
    }
    $parent_id_string = implode(',',$parent_id);
   // var_dump($parent_id_string);
    $sql = "SELECT a.ID FROM {$table_prefix}users a INNER JOIN {$table_prefix}wpmlm_users b ON a.ID=b.user_ref_id WHERE b.user_parent_id in (" . $parent_id_string . ") order by b.join_date";
    //echo $sql;
    $results = $wpdb->get_results($sql);
    if(!empty($results)){
        foreach($results as $result){
            $user_id[] = $result->ID;
        }
        $userCheck = wpmlm_get_user_details_by_parent_id_join_new($user_id);
        if(!empty($userCheck)){
            $user_id = array_merge($userCheck,$user_id);
        }
        return $user_id;
    }else{
        return false;
    }
    
}

function getLableLoop($results,$role,$count,$label){
    // echo '<pre>';
    // var_dump($results);
    // echo '</pre>';
    global $wpdb;
    $table_prefix = $wpdb->prefix;

    
    $html = '';
   // ob_start();
            foreach ($results as $res) {
                if($role=='administrator'){
                $action = '<td>
                                <button type="button" class="btn btn-default btn-sm user_view_nnu" edit-id="'.$res->ID.'">'. __("View details","wpmlm-unilevel") .'</button>
                            </td>';
                }else{
                    $action ='';
                }
                $count++;
                $html .= '<tr>
                        <th scope="row">' . $count . '</th>
                        <td>' . $res->user_login . '</td>
                            <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>
                            <td>' . date("Y/m/d", strtotime($res->join_date)) . '</td>
                            <td>' . $res->user_email . '</td>
                            <td>' . $label . '</td>';
                $html .=  $action.'</tr>';
                $parent_id = $res->user_id;
                
                $sql = "SELECT a.*,b.* FROM {$table_prefix}users a INNER JOIN {$table_prefix}wpmlm_users b ON a.ID=b.user_ref_id WHERE b.user_parent_id = '" . $parent_id . "' order by b.join_date";
                
                $results = $wpdb->get_results($sql);
                
                if(!empty($results) && $res->user_id != $res->user_parent_id ){
                    $label++;
                    $html .= getLableLoop($results,$role,$count,$label);
                }
                  
            }
            
     return $html;                           
}

function wpmlm_get_user_details_by_parent_id_join_only_user_id($parent_id) {
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $sql = "SELECT a.*,b.* FROM {$table_prefix}users a INNER JOIN {$table_prefix}wpmlm_users b ON a.ID=b.user_ref_id WHERE b.user_parent_id = '" . $parent_id . "' order by b.join_date";
    $results = $wpdb->get_results($sql);

    return $results;
}

function wpmlm_user_referrals_nnu($user_id = '') {
    
    $results = wpmlm_get_user_details_by_parent_id_join($user_id);
    $current_user_id = get_current_user_id();
    $user_info=get_userdata($current_user_id);
    $role = implode(', ', $user_info->roles);
     
    ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4><i class="fa fa-bars" aria-hidden="true"></i> 
                    <span><?php _e('Referral Details','wpmlm-unilevel'); ?></span>
                </h4>
                    
                </div>
                <div  id="profile_print_area" style="overflow: auto; padding: 10px;" class="report-data" >
                    <?php
                    if (count($results) > 0) {
                        ?>
                        <table id="user-referrals-table" class="table table-striped table-bordered table-responsive-lg" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php _e('Username','wpmlm-unilevel'); ?></th>
                                    <th><?php _e('Full Name','wpmlm-unilevel'); ?></th>                                    
                                    <th><?php _e('Joining Date','wpmlm-unilevel'); ?></th>
                                    <th><?php _e('Email','wpmlm-unilevel'); ?></th>
                                    <th><?php _e('Label','wpmlm-unilevel'); ?></th>
                                    <?php if($role=='administrator'){?>
                                    <th><?php _e('Action','wpmlm-unilevel'); ?></th>
                                    <?php }?>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 0;
                                $label = 0;
                                echo getLableLoop($results,$role, $count,$label);
                                ?>

                            </tbody>
                        </table>
                    <?php
                    } else {
                        echo '<div class="no-data">'. _e("No Data","wpmlm-unilevel").'</div>';
                    }
                    ?>
                </div>

            </div>
        </div>
    </div> 
    <script>
        jQuery(document).ready(function ($) {
            $('#user-referrals-table').DataTable({
                "pageLength": 10,
                "bFilter": false
            });
        });

    </script>
    <?php
}

function wpmlm_user_messaging_nnu($user_id = '') {

    $results = wpmlm_get_user_details_by_parent_id_join($user_id);
    $current_user_id = get_current_user_id();
    $user_info=get_userdata($current_user_id);
    $role = implode(', ', $user_info->roles);
            
    ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4><i class="fa fa-bars" aria-hidden="true"></i> <span><?php _e('Messaging Details','wpmlm-unilevel'); ?></span></h4>
                    
                </div>
                <div  id="profile_print_area" style="overflow: auto; padding: 10px;" class="report-data" >
                    <?php
                    echo do_shortcode('[front-end-pm]');
                    /*
                    if (count($results) > 0) {
                        ?>
                        <table id="user-referrals-table" class="table table-striped table-bordered table-responsive-lg" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php _e('Username','wpmlm-unilevel'); ?></th>
                                    <th><?php _e('Full Name','wpmlm-unilevel'); ?></th>                                    
                                    <th><?php _e('Joining Date','wpmlm-unilevel'); ?></th>
                                    <th><?php _e('Email','wpmlm-unilevel'); ?></th>
                                    <?php if($role=='administrator'){?>
                                    <th><?php _e('Action','wpmlm-unilevel'); ?></th>
                                    <?php }?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 0;
                                foreach ($results as $res) {
                                    
                                    if($role=='administrator'){
                                    $action = '<td>
                                                    <button type="button" class="btn btn-default btn-sm user_view_nnu" edit-id="'.$res->ID.'">'. __("View details","wpmlm-unilevel") .'</button>
                                                </td>';
                                    }else{
                                        $action ='';
                                    }

                                    $count++;
                                    echo '<tr>
                                        <th scope="row">' . $count . '</th>
                                        <td>' . $res->user_login . '</td>
                                            <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>
                                            <td>' . date("Y/m/d", strtotime($res->join_date)) . '</td>
                                            <td>' . $res->user_email . '</td>'.$action;                                    
                                        '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php
                    } else {
                        echo '<div class="no-data">'. _e("No Data","wpmlm-unilevel").'</div>';
                    }*/
                    ?>
                </div>

            </div>
        </div>
    </div> 
    <script>
        jQuery(document).ready(function ($) {
            $('#user-referrals-table').DataTable({
                "pageLength": 10,
                "bFilter": false
            });
        });

    </script>
    <?php
}

add_action( 'wp_ajax_wpmlm_ajax_user_profile_nnu', 'wpmlm_ajax_user_profile_nnu' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_user_profile_nnu', 'wpmlm_ajax_user_profile_nnu' );
function wpmlm_ajax_user_profile_nnu() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $table_name1 = $wpdb->prefix . "users";
    $user_id = intval($_POST['user_id']);
    if (isset($_POST['user_form3_nonce']) && wp_verify_nonce($_POST['user_form3_nonce'], 'user_form3')) {

        $user_address = sanitize_text_field($_POST['address1']);
        $user_email = sanitize_email($_POST['user_email']);
        $user_dob = sanitize_text_field($_POST['dob']);
        $joining_date = sanitize_text_field($_POST['joining_date']);
        $user_city = sanitize_text_field($_POST['city']);
        $user_state = sanitize_text_field($_POST['state']);
        $user_country = sanitize_text_field($_POST['country']);
        $user_zip = sanitize_text_field($_POST['zip']);
        $user_first_name = sanitize_text_field($_POST['fname']);
        $user_second_name = sanitize_text_field($_POST['lname']);
        
        $sponsor_id = sanitize_text_field($_POST['sponsor_id']);
        
        $user_mobile = sanitize_text_field($_POST['contact_no']);

        if(!empty($sponsor_id)){
            $user_level = wpmlm_get_user_level_by_parent_id($sponsor_id);
        }else{
            $user_level = 0;
            $sponsor_id = 0;
        }
        $joining_date = date('Y-m-d H:i:s', strtotime($joining_date));  
        $user_details = array(
            'user_address' => $user_address,
            'user_email' => $user_email,
            'user_dob' => $user_dob,
            'user_city' => $user_city,
            'user_state' => $user_state,
            'user_first_name' => $user_first_name,
            'user_second_name' => $user_second_name,
            'user_country' => $user_country,
            'user_zip' => $user_zip,
            'user_mobile' => $user_mobile,
            'join_date' => $joining_date,
            'user_level' => $user_level,
            'user_parent_id' => $sponsor_id,
            'user_status' => 1,
        );
        $condition = array('user_ref_id' => $user_id);
        $wpdb->update($table_name, $user_details, $condition);

        $user_details1 = array(
            'user_email' => $user_email
        );
        $condition1 = array('ID' => $user_id);
        $result = $wpdb->update($table_name1, $user_details1, $condition1);

        _e("Updated Successfully","wpmlm-unilevel");
        exit();
    }


    if (isset($_POST['user_form4_admin_nonce']) && wp_verify_nonce($_POST['user_form4_admin_nonce'], 'user_form4_admin')) {

        $newpassword = $_POST['password_admin'];
        wp_set_password($newpassword, $user_id);
        //echo 'Password Updated Successfully';
        _e("Password Updated Successfully","wpmlm-unilevel");
        exit();
    }
}

add_action( 'wp_ajax_wpmlm_ajax_add_user_profile_nnu', 'wpmlm_ajax_add_user_profile_nnu' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_add_user_profile_nnu', 'wpmlm_ajax_add_user_profile_nnu' );
function wpmlm_ajax_add_user_profile_nnu() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $table_name1 = $wpdb->prefix . "users";
    //$user_id = intval($_POST['user_id']);
    $user_email = sanitize_email($_POST['user_email']);
    $user_name = sanitize_text_field($_POST['user_name']);
    
    $user_name_check = custom_wpmlm_ajax_user_check('username',$user_name);

    $user_email_check = custom_wpmlm_ajax_user_check('email',$user_email);
    
//&& $user_email_check == 1

    if (isset($_POST['user_form3_nonce']) && wp_verify_nonce($_POST['user_form3_nonce'], 'user_form3') && $user_name_check == 1 ) {

        $user_address = sanitize_text_field($_POST['address1']);
        
        $user_dob = sanitize_text_field($_POST['dob']);
        $joining_date = sanitize_text_field($_POST['joining_date']);
        $user_city = sanitize_text_field($_POST['city']);
        $user_state = sanitize_text_field($_POST['state']);
        $user_country = sanitize_text_field($_POST['country']);
        $user_zip = sanitize_text_field($_POST['zip']);
        $user_first_name = sanitize_text_field($_POST['fname']);
        $user_second_name = sanitize_text_field($_POST['lname']);
        
        $sponsor_id = sanitize_text_field($_POST['sponsor_id']);
        
        $user_mobile = sanitize_text_field($_POST['contact_no']);

        if(!empty($sponsor_id)){
            $user_level = wpmlm_get_user_level_by_parent_id($sponsor_id);
        }else{
            $user_level = 0;
            $sponsor_id = 0;
        }
       // $joining_date = date('Y-m-d H:i:s', $joining_date); 
        $user_ref = get_current_user_id(); 
        //user_parent_id > sponsor id
        

        $user_details1 = array(
            'user_email' => $user_email,
            'user_login' => $user_name,
            'user_registered'=>date('Y-m-d H:i:s')
        );
        //$condition1 = array('ID' => $user_id);
        $result = $wpdb->insert($table_name1, $user_details1);
        $last_inser_user_id = $wpdb->insert_id;
        if(!empty($last_inser_user_id)){

            $user_details = array(
                'user_address' => $user_address,
                'user_email' => $user_email,
                'user_dob' => $user_dob,
                'user_city' => $user_city,
                'user_ref_id'=> $last_inser_user_id,
                
                'user_state' => $user_state,
                'user_first_name' => $user_first_name,
                'user_second_name' => $user_second_name,
                'user_country' => $user_country,
                'user_zip' => $user_zip,
                'user_mobile' => $user_mobile,
                'join_date' => $joining_date,
                'user_level' => $user_level,
                'user_parent_id' => $sponsor_id,
                'user_status' => 1,

            );
            //$condition = array('user_ref_id' => $user_id);
            $wpdb->insert($table_name, $user_details);

            update_user_meta( $last_inser_user_id, 'first_name', trim( $user_first_name ) );
            update_user_meta( $last_inser_user_id, 'last_name', trim( $user_second_name ) );
            $u = new WP_User( $last_inser_user_id );
            $u->add_role( 'custom_user' );

        }
       // _e("User Created Successfully","wpmlm-unilevel");
        $msg = "User Created Successfully";
        $arr['status'] = 1;
        $arr['msg'] = $msg; 
        echo json_encode($arr);
        exit();
    }else{
        //_e("User name or Email already exists.","wpmlm-unilevel");
        $msg = "User name or Email already exists.";
        $arr['status'] = 0;
        $arr['msg'] = $msg; 
        echo json_encode($arr);
        exit();
    }
   
}

function wpmlm_getSponsor($user_id) {
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $sql = "SELECT a.ID,a.user_login FROM {$table_prefix}users a INNER JOIN {$table_prefix}wpmlm_users b ON a.ID=b.user_ref_id AND b.user_ref_id != '" . $user_id . "'";
    $results = $wpdb->get_results($sql);
    return $results;
    
}
function wpmlm_add_user_profile_admin_nnu() {
    // $results = wpmlm_get_user_details_by_id_join($user_id);
    // $results1 = wpmlm_get_user_details_by_id_join($results[0]->user_parent_id);
    // $date = strtotime($results1[0]->join_date);
    // $joining_date = date('Y-m-d', $date);    
    // $package_details = wpmlm_select_package_by_id($results[0]->package_id);
    ?>
    <div id="user-profile">
    <div class="panel panel-default">
        <div class="panel-heading">
         <h4><i class="fa fa-info-circle"></i> <span class="report-caption"> <?php _e('User Profile','wpmlm-unilevel'); ?></span></h4>         
      </div>
                <div class="panel-border">
               
 
                    <h4><?php _e('Personal Information','wpmlm-unilevel'); ?></h4>
                    <div id="user-form3-div">
                    <div id="user-form3-message"></div>
                        <form id="add-user-form3-nnu"  class="form-horizontal" method="post">                        
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label class="control-label col-md-3 user-dt" for="user_name"><?php _e('User Name','wpmlm-unilevel'); ?>:</label>
                                    <div class="col-md-7">
                                        <input type="text" class="user-input" name="user_name" id="user_name" value=""  style="border: none;" >
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label class="control-label col-md-3 user-dt" for="package_price"><?php _e('Sponsor Name','wpmlm-unilevel'); ?>:</label>
                                    <div class="col-md-7">
                                            <select   name="sponsor_id" id="sponsor_id" style=" width:100%; background-color: #fff" >
                                                <?php
                                                $country_results = wpmlm_getSponsor($user_id);
                                                foreach ($country_results as $res) {
                                                    if (0 == $res->ID) {
                                                        $selected = 'selected';
                                                        $sponsor_id_temp = $res->user_login;
                                                    } else {
                                                        $selected = '';
                                                    }
                                                    echo '<option ' . $selected . ' value="' . $res->ID . '">' . $res->user_login . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <!-- <input type="text" id="sponsor_id_temp" value="<?php echo $sponsor_id_temp; ?>"  style="border: none;"> -->
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label class="control-label col-md-3 user-dt" for="joining_date"><?php _e('Joining Date','wpmlm-unilevel'); ?>:</label>
                                    <div class="col-md-7">
                                        <input type="text" class="date-picker" data-date-format="yyyy-mm-dd" data-date-viewmode="years" name="joining_date" id="joining_date" value=""  style="border: none;">
                                    </div>
                                </div>
                                <?php 
                                $package_details =0;
                                if($package_details){?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 user-dt" for="registration_package"><?php _e('Registration Package','wpmlm-unilevel'); ?> :</label>
                                        <div class="col-md-7">
                                            <input type="text" class="" name="registration_package" id="registration_package" value=""  style="border: none;">
                                        </div>
                                    </div>
                                <?php }?>
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label class="control-label col-md-3 user-dt" for="fname"><?php _e('First Name','wpmlm-unilevel'); ?>:</label>
                                    <div class="col-md-7">
                                        <input type="text" class="user-input" name="fname" id="fname" value=""  style="border: none;" >
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label class="control-label col-md-3 user-dt" for="lname"><?php _e('Last Name','wpmlm-unilevel'); ?>:</label>
                                    <div class="col-md-7">
                                        <input type="text" class="user-input" name="lname" id="lname" value=""  style="border: none;">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label class="control-label col-md-3 user-dt" for="address1"><?php _e('Address 1','wpmlm-unilevel'); ?>:</label>
                                    <div class="col-md-7">
                                        <input type="text" class="" name="address1" id="address1" value=""  style="border: none;" >
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label class="control-label col-md-3 user-dt" for="user_email"><?php _e('Email','wpmlm-unilevel'); ?>:</label>
                                    <div class="col-md-7">
                                        <input type="email" class="user-input" name="user_email" id="user_email" value=""  style="border: none;">
                                    </div>
                                </div>
                            
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label class="control-label col-md-3 user-dt" for="city"><?php _e('Date of birth','wpmlm-unilevel'); ?>:</label>
                                    <div class="col-md-7">
                                        <input data-date-format="yyyy-mm-dd" data-date-viewmode="years" type="text" class="date-picker" name="dob" id="dob" value=""  style="border: none;">
                                    </div>
                                </div>
                            
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label class="control-label col-md-3 user-dt" for="contact_no"><?php _e('Telephone','wpmlm-unilevel'); ?>:</label>
                                    <div class="col-md-7">
                                        <input type="text" class="" name="contact_no" id="contact_no" value=""  style="border: none;" onkeypress="return isNumberKey(event)">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label class="control-label col-md-3 user-dt" for="city"><?php _e('City','wpmlm-unilevel'); ?>:</label>
                                    <div class="col-md-7">
                                        <input type="text" class="" name="city" id="city" value=""  style="border: none;">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label class="control-label col-md-3 user-dt" for="state"><?php _e('State','wpmlm-unilevel'); ?>:</label>
                                    <div class="col-md-7">
                                        <input type="text" class="" name="state" id="state" value=""  style="border: none;">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label class="control-label col-md-3 user-dt" for="country"><?php _e('Country','wpmlm-unilevel'); ?>:</label>
                                    <div class="col-md-7">
                                        <select   name="country" id="country" style=" width:100%; background-color: #fff" >
                                            <?php
                                            $country_results = wpmlm_getAllCountry();
                                            foreach ($country_results as $res) {
                                                if (0 == $res->id) {
                                                    $selected = 'selected';
                                                    $country_name = $res->name;
                                                } else {
                                                    $selected = '';
                                                }
                                                echo '<option ' . $selected . ' value="' . $res->id . '">' . $res->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <!-- <input type="text" id="country_temp" value="<?php echo $country_name; ?>"  style="border: none;"> -->
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label class="control-label col-md-3 user-dt" for="zip"><?php _e('Zip Code','wpmlm-unilevel'); ?>:</label>
                                    <div class="col-md-7">
                                        <input type="text" class="" name="zip" id="zip" value=""  style="border: none;" onkeypress="return isNumberKey(event)">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label class="control-label col-md-3 user-dt" for=""></label>
                                    <div class="col-sm-7" class="up-can-btn">
                                        <?php wp_nonce_field('user_form3', 'user_form3_nonce'); ?>
                                        <div class="form-group">
                                            <div class="sharedaddy">
                                                    <ul>
                                                        <li class="share-facebook">
                                                            <button class="btn btn-success  col-sm-offset-2 add_user_form3_save" type="submit" name="add_user_form3_save" id="add_user_form3_save">
                                                                <?php _e('Add User','wpmlm-unilevel'); ?> <i class="fa fa-arrow-circle-right"></i>
                                                            </button>
                                                        </li>
                                                        <li class="share-twitter">
                                                            <a style="width:87px;" data-cancel="user-form3" class="btn btn-primary edit-cancel">
                                                            <?php _e('Cancel','wpmlm-unilevel'); ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                            </div>            
                                        </div>   
                                    </div>
                                </div>
                        </form>
                    </div>
        <?php if (!current_user_can('administrator')) {
        ?>

                    <div class="" id="change-password">
                    <div id="user-form4-message"></div>
                    <h4><?php _e('Change Password','wpmlm-unilevel'); ?></h4>
                        <form id="user-form4" class="form-horizontal " method="post">
                            <div class="form-group">
                     <label class="control-label col-md-3 user-dt" for="password_admin"><?php _e('New Password','wpmlm-unilevel'); ?>:</label>
                     <div class="col-md-7">
                                    <input type="password" class="user-password-input form-control" name="password_admin" id="password_admin">
                                </div>
                            </div>
                            <div class="form-group">
                     <label class="control-label col-md-3 user-dt" for="confirm_password"><?php _e('Confirm Password','wpmlm-unilevel'); ?>:</label>
                     <div class="col-md-7">
                                    <input type="password" class="user-password-input form-control confirm_password" name="confirm_password_admin" id="confirm_password_admin">
                                </div>
                            </div>
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <?php wp_nonce_field('user_form4_admin', 'user_form4_admin_nonce'); ?>
                            <div class="form-group">
                   <label class="control-label col-md-3 user-dt" for=""></label>

                     <div class=" col-md-7">
                        <button class="btn btn-danger  user_form4_save" type="submit" name="user_form4_save" id="user_form4_save">
                                         <?php _e('Save','wpmlm-unilevel'); ?>
                                    </button>
                                </div>
                            </div>                        
                        </form> 
    </div>
    <?php 
    }
    ?>
            
                </div>      
                    </div>   
                </div> 
    <script type="text/javascript">
        
                
        jQuery(document).ready(function ($) {

               
                $("#user_name").change(function () {
                    $(".err_msg_username").remove();
                    
                    var username = $(this).val();
                    console.log('user_name'+username);
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: {action:'wpmlm_ajax_user_check',username: username},
                        beforeSend: function () {
                            $("#user_name").parent().append('<div class="err_msg_username"><img src=' + plugin_url + '/images/loader.gif></div>');
                        },
                        success: function (data) {
                            console.log({data:data});
                            $(".err_msg_username").remove();
                            if ($.trim(data) === "1") {
                                $("#user_name").removeClass('invalid');
                                $("#user_name").removeClass('error');
                                $("#user_name").addClass('valid');
                                $("#user_name").attr('aria-invalid', 'false');
                                $("#reg_submit").attr('disabled', false);
                                $("#reg_submit").css('opacity','1');
                                $("#reg_submit").css('cursor','pointer');

                            } else {

                                $("#user_name").parent().append('<div class="err_msg_username">' + data + '</div>');
                                $("#user_name").addClass('invalid');
                                $("#user_name").removeClass('valid');
                                $("#user_name").addClass('error');
                                $("#user_name").attr('aria-invalid', 'true');
                                $("#reg_submit").attr('disabled', true);
                                $("#reg_submit").css('opacity','0.5');
                                $("#reg_submit").css('cursor','not-allowed');
                            }

                        }

                    });
                });
                
                // $("#user_email").change(function () {
                //     $(".err_msg_email").remove();

                //     var email = $(this).val();
                //     console.log('user_name'+email);
                //     $.ajax({
                //         type: "POST",
                //         url: ajaxurl,
                //         data: {action:'wpmlm_ajax_user_check',email: email},
                //         beforeSend: function () {
                //             $("#user_email").parent().append('<div class="err_msg_email"><img src=' + plugin_url + '/images/loader.gif></div>');
                //         },
                //         success: function (data) {
                //             console.log({data:data});
                //             $(".err_msg_email").remove();
                //             if ($.trim(data) === "1") {
                //                 $("#user_email").removeClass('invalid');
                //                 $("#user_email").removeClass('error');
                //                 $("#user_email").addClass('valid');
                //                 $("#user_email").attr('aria-invalid', 'false');
                //                 $("#reg_submit").attr('disabled', false);
                //                 $("#reg_submit").css('opacity','1');
                //                 $("#reg_submit").css('cursor','pointer');

                //             } else {
                //                 $("#user_email").parent().append('<div class="err_msg_email">' + data + '</div>');
                //                 $("#user_email").addClass('invalid');
                //                 $("#user_email").removeClass('valid');
                //                 $("#user_email").addClass('error');
                //                 $("#user_email").attr('aria-invalid', 'true');
                //                 $("#reg_submit").attr('disabled', true);
                //                 $("#reg_submit").css('opacity','0.5');
                //                 $("#reg_submit").css('cursor','not-allowed');
                //             }

                //         }

                //     });
                // });


                $("#dob").datepicker({
                    autoclose: true
                });
                jQuery("#joining_date").datepicker({
                    autoclose: true
                });

            $(".user-form3-edit").click(function () {
                $("#dob").datepicker({
                    autoclose: true
                });
                jQuery("#joining_date").datepicker({
                    autoclose: true
                });
               // $("#country").show();
                //$("#country_temp").hide();
                //$("#sponsor_id").show();
                //$("#sponsor_id_temp").hide();
                
                $("#user-form3-nnu [type=text],[type=email]").addClass("form-control");
                $("#user-form3-nnu [type=text],[type=email]").attr("readonly", false);
                $("#user-form3-nnu [type=text],[type=email]").css("border", "1px solid #bbb");
                $("#user-form3-nnu .form-group").css("margin-bottom", "10px");
                $("#user-form3-update").show();
            });


            $(document).on('click', '.edit-cancel', function () {
                var cancel_id = $(this).attr('data-cancel');
                $("#" + cancel_id + " [type=text], #" + cancel_id + " [type=email]").removeClass("form-control");
                $("#" + cancel_id + " [type=text], #" + cancel_id + " [type=email]").attr("readonly", true);
                $("#" + cancel_id + " [type=text], #" + cancel_id + " [type=email]").css("border", "none");
                //$("#" + cancel_id + " .form-group").css("margin-bottom", "10px");
                $("#" + cancel_id + "-update").hide();
               // $("#country").hide();
                //$("#country_temp").show();
                //$("#sponsor_id").hide();
                //$("#sponsor_id_temp").show();
                
            });

            // form 2 update start //
            $(document).on('submit', '#add-user-form3-nnu', function () {

                console.log('addUser');

                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_add_user_profile_nnu');
                isValid = true;
                $(".user-input").each(function () {
                    var element = $(this);
                    if (element.val() == "") {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });

                if($( "#user_email" ).hasClass( "invalid" )){
                    isValid = false; 
                }
                
                if (isValid) {
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            //alert(data);
                            data = JSON.parse(data);
                            console.log({data:data});
                            if(data.status == 1){
                                $("#user-form3-message").show();
                                $("#user-form3-message").html('<div class="alert alert-info">' + data.msg + '</div>');
                                jQuery('#add-user-form3-nnu')[0].reset();
                            }else{
                                $("#user-form3-message").show();
                                $("#user-form3-message").html('<div class="alert alert-info">' + data.msg + '</div>');
                                
                            }
                            

                        }

                    });
                }
                return false;
            });

            $(document).on('submit', '#user-form3-nnu', function () {

                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_user_profile_nnu');
                isValid = true;
                $(".user-input").each(function () {
                    var element = $(this);
                    if (element.val() == "") {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });
                
                if($( "#user_email" ).hasClass( "invalid" )){
                    isValid = false; 
                }

                if (isValid) {
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            //alert(data);
                            console.log({data:data});
                            $("#user-form3-message").show();
                            $("#user-form3-message").html('<div class="alert alert-info">' + data + '</div>');
                            setTimeout(function () {
                                //$("#user-form3-message").hide('slow');
                                $(".edit-cancel").trigger('click');
                            }, 1000);

                        }

                    });
                }
                return false;
            });

            $(document).on('submit', '#user-form4', function () {
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_user_profile_nnu');
                isValid = true;
                $(".user-password-input").each(function () {
                    var element = $(this);
                    if (element.val() == "") {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });
                
                var pass = $("#password_admin").val();
                var confirm_password = $("#confirm_password_admin").val();
                if (pass.length < 6) {
                  isValid = false;
                }
                
                
                if (confirm_password != pass && confirm_password != '') {
                   isValid = false; 
                }
                
                if (isValid) {
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            
                            
                            $("#user-form4")[0].reset();
                            $("#user-form4-message").show();
                            $("#user-form4-message").html('<div class="alert alert-info">' + data + '</div>');
                            setTimeout(function () {
                                $("#user-form4-message").hide();
                            }, 1000);

                        }

                    });
                }
                return false;
            })

            $(".user-input").focus(function () {
                $(this).removeClass("invalid");
            })
            $(".user-password-input").focus(function () {
                $(this).removeClass("invalid");
            })


        });

    </script>
    <?php
}
function wpmlm_user_profile_admin_nnu($user_id = '') {
    $results = wpmlm_get_user_details_by_id_join($user_id);
    $results1 = wpmlm_get_user_details_by_id_join($results[0]->user_parent_id);
    $date = strtotime($results1[0]->join_date);
    $joining_date = date('Y-m-d', $date);    
    $package_details = wpmlm_select_package_by_id($results[0]->package_id);
    ?>
    <div id="user-profile">
    <div class="panel panel-default">
        <div class="panel-heading">
         <h4><i class="fa fa-info-circle"></i> <span class="report-caption"> <?php _e('User Profile','wpmlm-unilevel'); ?></span></h4>         
      </div>
                <div class="panel-border">
               <h4><?php _e('User Information','wpmlm-unilevel'); ?></h4>
                    <form id="user-form1" class="form-horizontal " method="post">
                        <div class="form-group">
                            <label class="control-label col-md-3 user-dt" for="user_name"><?php _e('User Name','wpmlm-unilevel'); ?>:</label>
                            <div class="col-md-7">
                                <input type="text" class="" name="user_name" id="user_name" value="<?php echo $results[0]->user_login; ?>" readonly style="border: none;" >
                            </div>
                        </div>
                       
                        
                    </form>
               
                    <div id="user-form2-message"></div>
                    
                    
                    <form id="user-form2" class="form-horizontal " method="post" style="margin-top: 20px;">
                        <!-- <div class="form-group">
                            <label class="control-label col-md-3 user-dt" for="fname"><?php _e('First Name','wpmlm-unilevel'); ?>:</label>
                            <div class="col-md-7">
                                <input type="text" class="user-input" name="fname" id="fname" value="<?php echo $results[0]->user_first_name; ?>" readonly style="border: none;" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 user-dt" for="lname"><?php _e('Last Name','wpmlm-unilevel'); ?>:</label>
                            <div class="col-md-7">
                                <input type="text" class="user-input" name="lname" id="lname" value="<?php echo $results[0]->user_second_name; ?>" readonly style="border: none;">
                            </div>
                        </div> -->
                        <div class="form-group"> 
                            <div class="col-sm-offset-4 col-sm-6">
                            </div>
                        </div>
                    </form>

               <div id="user-form3-message"></div>
    <!-- <h4><?php _e('Contact Information','wpmlm-unilevel'); ?></h4> -->
    <h4><?php _e('Personal Information','wpmlm-unilevel'); ?></h4>

               <div id="user-form3-div">
                  <form id="user-form3-nnu" class="form-horizontal" method="post">
                      <div class="form-group">
                   <label class="control-label col-md-3 user-dt" for=""></label>

                     <div class=" col-md-7">
                        <a class="btn btn-danger user-form3-edit">
                            <i class="fa fa-edit"></i>&nbsp;<?php _e('Edit','wpmlm-unilevel'); ?>
                        </a>

                    </div>
                  </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 user-dt" for="package_price"><?php _e('Sponsor Name','wpmlm-unilevel'); ?>:</label>
                            <div class="col-md-7">
                                <?php 
                                if ( is_admin() ) {
                                 ?>
                                 <?php
                                    $sponsor_id_temp = '';
                                    $country_results = wpmlm_getSponsor($user_id);
                                        ?>
                                 <select   name="sponsor_id" id="sponsor_id" style="display:none; width:100%; background-color: #fff" >
                                        <?php
                                        foreach ($country_results as $res) {
                                            if ($results[0]->user_parent_id == $res->ID) {
                                                $selected = 'selected';
                                                $sponsor_id_temp = $res->user_login;
                                            } else {
                                                $selected = '';
                                            }
                                            echo '<option ' . $selected . ' value="' . $res->ID . '">' . $res->user_login . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <input type="text" id="sponsor_id_temp" value="<?php echo $sponsor_id_temp; ?>" disabled readonly style="border: none;">
                                 <?php
                                } else {
                                    ?>
                                    <?php
                                        $sponsor_id_temp = '';
                                        $country_results = wpmlm_getSponsor($user_id);
                                        foreach ($country_results as $res) {
                                            if ($results[0]->user_parent_id == $res->ID) {
                                                $selected = 'selected';
                                                $sponsor_id_temp = $res->user_login;
                                            } else {
                                                $selected = '';
                                            }
                                        }
                                        ?>
                                    <input type="text"  value="<?php echo $sponsor_id_temp; ?>" readonly disabled style="border: none;">
                                    <?php
                                }
                                ?>
                                <!-- <input type="text" class="" name="sponsor_name" id="sponsor_name" value="<?php echo $results1[0]->user_login; ?>" readonly style="border: none;"> -->
                                <?php /*
                                
                                    */?>
                                    

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 user-dt" for="joining_date"><?php _e('Joining Date','wpmlm-unilevel'); ?>:</label>
                            <div class="col-md-7">
                                <input type="text" class="date-picker" data-date-format="yyyy-mm-dd" data-date-viewmode="years" name="joining_date" id="joining_date" value="<?php echo $joining_date; ?>" readonly style="border: none;">
                            </div>
                        </div>
                        <?php 
                        if($package_details){?>
                            <div class="form-group">
                                <label class="control-label col-md-3 user-dt" for="registration_package"><?php _e('Registration Package','wpmlm-unilevel'); ?> :</label>
                                <div class="col-md-7">
                                    <input type="text" class="" name="registration_package" id="registration_package" value="<?php echo $package_details->package_name; ?>" readonly style="border: none;">
                                </div>
                            </div>
                        <?php }?>
                        <div class="form-group">
                            <label class="control-label col-md-3 user-dt" for="fname"><?php _e('First Name','wpmlm-unilevel'); ?>:</label>
                            <div class="col-md-7">
                                <input type="text" class="user-input" name="fname" id="fname" value="<?php echo $results[0]->user_first_name; ?>" readonly style="border: none;" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 user-dt" for="lname"><?php _e('Last Name','wpmlm-unilevel'); ?>:</label>
                            <div class="col-md-7">
                                <input type="text" class="user-input" name="lname" id="lname" value="<?php echo $results[0]->user_second_name; ?>" readonly style="border: none;">
                            </div>
                        </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 user-dt" for="address1"><?php _e('Address 1','wpmlm-unilevel'); ?>:</label>
                                <div class="col-md-7">
                                    <input type="text" class="" name="address1" id="address1" value="<?php echo $results[0]->user_address; ?>" readonly style="border: none;" >
                                </div>
                            </div>
                            <div class="form-group">
                        <label class="control-label col-md-3 user-dt" for="user_email"><?php _e('Email','wpmlm-unilevel'); ?>:</label>
                        <div class="col-md-7">
                                    <input type="email" class="user-input" name="user_email" id="user_email" value="<?php echo $results[0]->user_email; ?>" readonly style="border: none;">
                                </div>
                            </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 user-dt" for="city"><?php _e('Date of birth','wpmlm-unilevel'); ?>:</label>
                        <div class="col-md-7">
                                    <input data-date-format="yyyy-mm-dd" data-date-viewmode="years" type="text" class="date-picker" name="dob" id="dob" value="<?php echo $results[0]->user_dob; ?>" readonly style="border: none;">
                                </div>
                            </div>
                      
                            <div class="form-group">
                        <label class="control-label col-md-3 user-dt" for="contact_no"><?php _e('Telephone','wpmlm-unilevel'); ?>:</label>
                        <div class="col-md-7">
                                    <input type="text" class="" name="contact_no" id="contact_no" value="<?php echo $results[0]->user_mobile; ?>" readonly style="border: none;" onkeypress="return isNumberKey(event)">
                                </div>
                            </div>
                            <div class="form-group">
                        <label class="control-label col-md-3 user-dt" for="city"><?php _e('City','wpmlm-unilevel'); ?>:</label>
                        <div class="col-md-7">
                                    <input type="text" class="" name="city" id="city" value="<?php echo $results[0]->user_city; ?>" readonly style="border: none;">
                                </div>
                            </div>
                            <div class="form-group">
                        <label class="control-label col-md-3 user-dt" for="state"><?php _e('State','wpmlm-unilevel'); ?>:</label>
                        <div class="col-md-7">
                                    <input type="text" class="" name="state" id="state" value="<?php echo $results[0]->user_state; ?>" readonly style="border: none;">
                                </div>
                            </div>
                            <div class="form-group">
                        <label class="control-label col-md-3 user-dt" for="country"><?php _e('Country','wpmlm-unilevel'); ?>:</label>
                        <div class="col-md-7">
                                    <select   name="country" id="country" style="display:none; width:100%; background-color: #fff" >
                                        <?php
                                        $country_results = wpmlm_getAllCountry();
                                        foreach ($country_results as $res) {
                                            if ($results[0]->user_country == $res->id) {
                                                $selected = 'selected';
                                                $country_name = $res->name;
                                            } else {
                                                $selected = '';
                                            }
                                            echo '<option ' . $selected . ' value="' . $res->id . '">' . $res->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <input type="text" id="country_temp" value="<?php echo $country_name; ?>" readonly style="border: none;">
                                </div>
                            </div>
                            <div class="form-group">
                        <label class="control-label col-md-3 user-dt" for="zip"><?php _e('Zip Code','wpmlm-unilevel'); ?>:</label>
                        <div class="col-md-7">
                                    <input type="text" class="" name="zip" id="zip" value="<?php echo $results[0]->user_zip; ?>" readonly style="border: none;" onkeypress="return isNumberKey(event)">
                                </div>
                            </div>
                     <div class="form-group">
                  <label class="control-label col-md-3 user-dt" for=""></label>
                        <div class="col-sm-7" class="up-can-btn">
                                    <input id="user_id" type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                    <?php wp_nonce_field('user_form3', 'user_form3_nonce'); ?>
                           <div class="form-group" id="user-form3-update" style="display: none">
                             
                                        
                                                         <div class="sharedaddy">
                  
        <ul>
            <li class="share-facebook">
                <button class="btn btn-success  col-sm-offset-2 user_form3_save" type="submit" name="user_form3_save" id="user_form3_save">
                    <?php _e('Update','wpmlm-unilevel'); ?> <i class="fa fa-arrow-circle-right"></i>
                </button></li>
            <li class="share-twitter"><a style="width:87px;" data-cancel="user-form3" class="btn btn-primary edit-cancel">
                                        <?php _e('Cancel','wpmlm-unilevel'); ?>
                                        </a></li>
            
        </ul>
            </div>            
                           </div>
                                        
                                    </div>
                                </div>
                        </form>
                    </div>
        <?php if (!current_user_can('administrator')) {
        ?>

                    <div class="" id="change-password">
                    <div id="user-form4-message"></div>
                    <h4><?php _e('Change Password','wpmlm-unilevel'); ?></h4>
                        <form id="user-form4" class="form-horizontal " method="post">
                            <div class="form-group">
                     <label class="control-label col-md-3 user-dt" for="password_admin"><?php _e('New Password','wpmlm-unilevel'); ?>:</label>
                     <div class="col-md-7">
                                    <input type="password" class="user-password-input form-control" name="password_admin" id="password_admin">
                                </div>
                            </div>
                            <div class="form-group">
                     <label class="control-label col-md-3 user-dt" for="confirm_password"><?php _e('Confirm Password','wpmlm-unilevel'); ?>:</label>
                     <div class="col-md-7">
                                    <input type="password" class="user-password-input form-control confirm_password" name="confirm_password_admin" id="confirm_password_admin">
                                </div>
                            </div>
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <?php wp_nonce_field('user_form4_admin', 'user_form4_admin_nonce'); ?>
                            <div class="form-group">
                   <label class="control-label col-md-3 user-dt" for=""></label>

                     <div class=" col-md-7">
                        <button class="btn btn-danger  user_form4_save" type="submit" name="user_form4_save" id="user_form4_save">
                                         <?php _e('Save','wpmlm-unilevel'); ?>
                                    </button>
                                </div>
                            </div>                        
                        </form> 
    </div>
    <?php 
    }
    ?>
            
                </div>      
                    </div>   
                </div> 
    <script type="text/javascript">
        
                
        jQuery(document).ready(function ($) {

            

            $(".user-form3-edit").click(function () {
                $("#dob").datepicker({
                    autoclose: true
                });
                jQuery("#joining_date").datepicker({
                    autoclose: true
                });
                $("#country").show();
                $("#country_temp").hide();
                $("#sponsor_id").show();
                $("#sponsor_id_temp").hide();
                
                $("#user-form3-nnu [type=text],[type=email]").addClass("form-control");
                $("#user-form3-nnu [type=text],[type=email]").attr("readonly", false);
                $("#user-form3-nnu [type=text],[type=email]").css("border", "1px solid #bbb");
                $("#user-form3-nnu .form-group").css("margin-bottom", "10px");
                $("#user-form3-update").show();
            });


            $(document).on('click', '.edit-cancel', function () {
                var cancel_id = $(this).attr('data-cancel');
                $("#" + cancel_id + " [type=text], #" + cancel_id + " [type=email]").removeClass("form-control");
                $("#" + cancel_id + " [type=text], #" + cancel_id + " [type=email]").attr("readonly", true);
                $("#" + cancel_id + " [type=text], #" + cancel_id + " [type=email]").css("border", "none");
                //$("#" + cancel_id + " .form-group").css("margin-bottom", "10px");
                $("#" + cancel_id + "-update").hide();
                $("#country").hide();
                $("#country_temp").show();
                $("#sponsor_id").hide();
                $("#sponsor_id_temp").show();
                
            });

            // form 2 update start //
            

            $(document).on('submit', '#user-form3-nnu', function () {

                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_user_profile_nnu');
                isValid = true;
                $(".user-input").each(function () {
                    var element = $(this);
                    if (element.val() == "") {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });
                
                if($( "#user_email" ).hasClass( "invalid" )){
                    isValid = false; 
                }

                if (isValid) {
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            //alert(data);
                            console.log({data:data});
                            $("#user-form3-message").show();
                            $("#user-form3-message").html('<div class="alert alert-info">' + data + '</div>');
                            setTimeout(function () {
                                //$("#user-form3-message").hide('slow');
                                $(".edit-cancel").trigger('click');
                            }, 1000);

                        }

                    });
                }
                return false;
            });

            $(document).on('submit', '#user-form4', function () {
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_user_profile_nnu');
                isValid = true;
                $(".user-password-input").each(function () {
                    var element = $(this);
                    if (element.val() == "") {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });
                
                var pass = $("#password_admin").val();
                var confirm_password = $("#confirm_password_admin").val();
                if (pass.length < 6) {
                  isValid = false;
                }
                
                
                if (confirm_password != pass && confirm_password != '') {
                   isValid = false; 
                }
                
                if (isValid) {
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            
                            
                            $("#user-form4")[0].reset();
                            $("#user-form4-message").show();
                            $("#user-form4-message").html('<div class="alert alert-info">' + data + '</div>');
                            setTimeout(function () {
                                $("#user-form4-message").hide();
                            }, 1000);

                        }

                    });
                }
                return false;
            })

            $(".user-input").focus(function () {
                $(this).removeClass("invalid");
            })
            $(".user-password-input").focus(function () {
                $(this).removeClass("invalid");
            })


        });

    </script>
    <?php
}

add_action( 'wp_ajax_wpmlm_ajax_user_details_nnu', 'wpmlm_ajax_user_details_nnu' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_user_details_nnu', 'wpmlm_ajax_user_details_nnu' );
function wpmlm_ajax_user_details_nnu() {

    $user_id = $_GET['user_id'];
    if(!empty($user_id)){
        session_start();
        $_SESSION['selected_user_id']= $user_id;
        $user_details = wpmlm_get_user_details($user_id);
        $user = get_user_by('id', $user_id);
        $parent_id = $user_details->user_parent_id;
    }
    ?>
    <div class="panel-border col-md-12">
        <div id="exTab5" >
            <div class="col-md-3 ">
                <ul  class="nav nav-tabs tabs-right mlm-user-tabs">            
                    <li class="active"><a  href="#1c" data-toggle="tab"><?php _e('User Profile','wpmlm-unilevel'); ?></a></li>
                    <li><a href="#2c" data-toggle="tab"><?php _e('E-wallet Details','wpmlm-unilevel'); ?></a></li>
                    <li><a href="#3c" data-toggle="tab"><?php _e('Bonus Details','wpmlm-unilevel'); ?></a></li>
                    <li><a href="#4c" data-toggle="tab"><?php _e('Referral Details','wpmlm-unilevel'); ?></a></li>
                    
                </ul>
            </div>

            <div class="tab-content clearfix col-md-9">

                <div class="tab-pane active" id="1c">
                    <div><?php echo wpmlm_user_profile_admin_nnu($user_id); ?></div>
                </div>
                <div class="tab-pane" id="2c">
                    <div><?php echo wpmlm_user_ewallet_details($user_id); ?></div>
                </div>
                <div class="tab-pane" id="3c">
                    <div><?php echo wpmlm_user_income_details($user_id); ?></div>
                </div>
                <div class="tab-pane" id="4c">
                    <div><?php echo wpmlm_user_referrals_nnu($user_id); ?></div>
                </div>
                


            </div>
        </div>
    </div>
    <script>

        jQuery(".user-details-tab").click(function ($) {
            jQuery("#user-div").show();
            jQuery("#exTab5").hide();
            jQuery(".user-details").hide();


            


        });
    </script>
    <?php
    exit();
}

add_action( 'wp_ajax_wpmlm_ajax_add_user_details_nnu', 'wpmlm_ajax_add_user_details_nnu' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_add_user_details_nnu', 'wpmlm_ajax_add_user_details_nnu' );
function wpmlm_ajax_add_user_details_nnu() {

    // $user_id = $_GET['user_id'];
    // if(!empty($user_id)){
    //     session_start();
    //     $_SESSION['selected_user_id']= $user_id;
    //     $user_details = wpmlm_get_user_details($user_id);
    //     $user = get_user_by('id', $user_id);
    //     $parent_id = $user_details->user_parent_id;
    // }
    ?>
    <div class="panel-border col-md-12">
        <div id="exTab5" >
            <div class="col-md-3 ">
                <ul  class="nav nav-tabs tabs-right mlm-user-tabs">            
                    <li class="active"><a  href="#1c" data-toggle="tab"><?php _e('Add User Details','wpmlm-unilevel'); ?></a></li>
                   <?php /* <li><a href="#2c" data-toggle="tab"><?php _e('E-wallet Details','wpmlm-unilevel'); ?></a></li>
                    <li><a href="#3c" data-toggle="tab"><?php _e('Bonus Details','wpmlm-unilevel'); ?></a></li>
                    <li><a href="#4c" data-toggle="tab"><?php _e('Referral Details','wpmlm-unilevel'); ?></a></li> */?>
                    
                </ul>
            </div>

            <div class="tab-content clearfix col-md-9">

                <div class="tab-pane active" id="1c">
                    <div><?php echo wpmlm_add_user_profile_admin_nnu(); ?></div>
                </div>
                <div class="tab-pane" id="2c">
                    <div><?php //echo wpmlm_user_ewallet_details($user_id); ?></div>
                </div>
                <div class="tab-pane" id="3c">
                    <div><?php //echo wpmlm_user_income_details($user_id); ?></div>
                </div>
                <div class="tab-pane" id="4c">
                    <div><?php //echo wpmlm_user_referrals_nnu($user_id); ?></div>
                </div>
                


            </div>
        </div>
    </div>
    <script>

        jQuery(".user-details-tab").click(function ($) {
            jQuery("#user-div").show();
            jQuery("#exTab5").hide();
            jQuery(".user-details").hide();

        });
    </script>
    <?php
    exit();
}
function wpmlm_user_details_admin_nnu() {
    ?>
    <div class="panel-border-heading">
        <h4><i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('User Details','wpmlm-unilevel'); ?></h4>
    </div>


    <div class="panel-border checkUserDiv" id="user-div">
        <div class="addUserButton">
            <button type="button" class="btn btn-default btn-sm add_user_view_nnu" >
                Add User
            </button>
        </div>
        <div class="addUserButton2" id="user-div-nnu">
            <table id="user-table" class="table table-striped table-bordered table-responsive-lg" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?php _e('Username','wpmlm-unilevel'); ?></th>
                        <th><?php _e('Full Name','wpmlm-unilevel'); ?></th>
                        <th><?php _e('Joining Date','wpmlm-unilevel'); ?></th>
                        <th><?php _e('Sponsor','wpmlm-unilevel'); ?></th>
                        <th><?php _e('Action','wpmlm-unilevel'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $results = wpmlm_get_all_user_details_join();
                    $p_count = 0;
                    foreach ($results as $res) {

                        $p_count++;
                        echo '<tr>
                                <th scope="row">' . $p_count . '</th>
                                <td>' . $res->user_login . '</td>
                                <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>
                                <td>' . date("Y/m/d", strtotime($res->join_date)).'</td>
                                <td class="sponsor">' .($res->user_parent_id !=0 ?getUserNameWpmlTable($res->user_parent_id):''). '</td>
                                <td>
                                    <button type="button" class="btn btn-default btn-sm user_view_nnu" edit-id="' . $res->ID . '">';?>
                                    <?php _e("View details","wpmlm-unilevel"). '</button>
                                </td>
                            </tr>';
                    }
                        ?>

                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12 please-wait" style="text-align: center; display: none"><img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/please-wait.gif'; ?>"></div>
    <div class="user-details">

    </div>


    <script>

        jQuery(document).ready(function ($) {
            $('#user-table').DataTable({
                "pageLength": 10
            });
            $(document).on("click", ".user_view_nnu", function () {
                $(".please-wait").show();
                $(".user-details").show();

                var user_id = $(this).attr('edit-id');
                $.get(ajaxurl + '?user_id=' + user_id+'&action=wpmlm_ajax_user_details_nnu', function (data) {
                    $('.user-details').html(data);
                    $(".please-wait").hide("slow");

                });
                $("#user-div").hide();
                return false;

            });
            $(document).on("click", ".add_user_view_nnu", function () {
                $(".please-wait").show();
                $(".user-details").show();

                //var user_id = $(this).attr('edit-id');
                //user_id=' + user_id+'
                $.get(ajaxurl + '?&action=wpmlm_ajax_add_user_details_nnu', function (data) {
                    $('.user-details').html(data);
                    $(".please-wait").hide("slow");

                });
                $("#user-div").hide();
                return false;

            });
        });

    </script>
    <?php
}
function wpmlm_admin_area_nnu() {  
    //install_events_pg();  
    $user_id = get_current_user_id();
    $user_details = wpmlm_get_user_details($user_id);
    $parent_id = $user_details->user_parent_id;
    $packages = wpmlm_select_all_packages();
    $depth = wpmlm_get_level_depth();    
    ?>
    <div class="panel-border-heading">
    <h3 class="mlm-title"><?php _e('WP MLM Admin','wpmlm-unilevel'); ?></h3>
    </div>
      <div class="ioss-mlm-menu panel-border">
        <input id="ioss-mlm-tab1" class="tab_class" type="radio" name="tabs" checked>
        <label class="tab_class" for="ioss-mlm-tab1"><?php _e('Dashboard','wpmlm-unilevel'); ?></label>      
        <input id="ioss-mlm-tab2" class="tab_class user-details-tab" type="radio" name="tabs">
        <label class="tab_class" for="ioss-mlm-tab2"><?php _e('Users','wpmlm-unilevel'); ?></label>      
        <input id="ioss-mlm-tab3" class="tab_class tree-tab" type="radio" name="tabs">
        <label class="tab_class" for="ioss-mlm-tab3"><?php _e('Genealogy Tree','wpmlm-unilevel'); ?></label>      
        <input id="ioss-mlm-tab4" class="tab_class ewallet-tab" type="radio" name="tabs">
        <label class="tab_class" for="ioss-mlm-tab4"><?php _e('E-wallet Management','wpmlm-unilevel'); ?></label>
        <input id="ioss-mlm-tab5" class="tab_class report-tab" type="radio" name="tabs">
        <label class="tab_class" for="ioss-mlm-tab5"><?php _e('Reports','wpmlm-unilevel'); ?></label>
        <input id="ioss-mlm-tab6" class="tab_class" type="radio" name="tabs">
        <label class="tab_class" for="ioss-mlm-tab6"><?php _e('Change Password','wpmlm-unilevel'); ?></label>
        <input id="ioss-mlm-tab7" class="tab_class" type="radio" name="tabs">
        <label class="tab_class" for="ioss-mlm-tab7"><?php _e('Settings','wpmlm-unilevel'); ?></label> 
          
        <section id="content1"><p><?php echo wpmlm_admin_dashboard($user_id); ?></p></section>      
        <section id="content2"><p><?php echo wpmlm_user_details_admin_nnu(); ?></p></section>      
        <section id="content3" ><p><?php echo wpmlm_unilevel_tree($user_id); ?></p></section>      
        <section id="content4"><p><?php echo wpmlm_ewallet_management(); ?></p></section>    
        <section id="content5"><p><?php echo wpmlm_all_reports(); ?></p></section>
        <section id="content6"><p><?php echo wpmlm_password_settings(); ?></p></section>
        <section id="content7"><p><?php echo wpmlm_settings(); ?></p></section>
          
      </div>
<?php }

function wpmlm_user_area_nnu() {
    
    $user_id = get_current_user_id();
    $user_details = wpmlm_get_user_details($user_id);
    $user = get_user_by('id', $user_id);
    $parent_id = $user_details->user_parent_id;
    $package_id = $user_details->package_id;
    $user_status = $user_details->user_status;
    
    if ($user_id && ($user_status == 1)) {
        echo '<div class="col-md-12 mlm-main-div" id="mlm-main-div"> ';

        if (isset($_GET['reg_status'])) {
            
            echo '<div class="panel-border"><div class="col-md-8 status-msg alert alert-success alert-dismissible text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' . base64_decode($_GET['reg_status']) . '</b></div></div>';
            ?>

            <h3 class="mlm-title"><?php _e('WP MLM User','wpmlm-unilevel'); ?></h3>
            <div class="ioss-mlm-menu">
                <input id="ioss-mlm-tab6" class="tab_class" type="radio" name="tabs" checked>
                <label class="tab_class" for="ioss-mlm-tab6"><?php _e('Dashboard','wpmlm-unilevel'); ?></label>

                <input id="ioss-mlm-tab1" class="tab_class" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab1"><?php _e('My Profile','wpmlm-unilevel'); ?></label>      
                <input id="ioss-mlm-tab2" class="tab_class tree-tab-user" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab2"><?php _e('Genealogy Tree','wpmlm-unilevel'); ?></label>      
                <input id="ioss-mlm-tab3" class="tab_class ewallet-tab-user" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab3"><?php _e('E-wallet Management','wpmlm-unilevel'); ?></label>      
                <input id="ioss-mlm-tab4" class="tab_class" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab4"><?php _e('Bonus Details','wpmlm-unilevel'); ?></label>
                <input id="ioss-mlm-tab5" class="tab_class" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab5"><?php _e('Referrall Details','wpmlm-unilevel'); ?></label>
                

                <section id="content1"><p><?php echo wpmlm_user_profile_admin_nnu($user_id); ?></p></section>    
                <section id="content2" ><p><?php echo wpmlm_unilevel_tree($user_id); ?></p></section> 
                <section id="content3"><p><?php echo wpmlm_user_ewallet_management(); ?></p></section>
                <section id="content4"><p><?php echo wpmlm_user_income_details($user_id); ?></p></section>
                <section id="content5"><p><?php echo wpmlm_user_referrals_nnu($user_id); ?></p></section>
                <section id="content6"><p><?php echo wpmlm_user_dashboard_nnu($user_id); ?></p></section>
                 

            </div>
            <?php
        } else if (isset($_GET['reg_failed'])) {
            ?>
                    <h3 class="mlm-title"><?php _e('WP MLM User Registration','wpmlm-unilevel'); ?></h3>
                    <?php
                    echo '<div class="panel-border"><div class="col-md-8 status-msg alert alert-danger text-center"><b>' . base64_decode($_GET['reg_failed']) . '</b>
            </div></div>';
        } else {

            ?>
            
            <h3><?php _e('WP MLM User','wpmlm-unilevel'); ?></h3>
            <div class="ioss-mlm-menu">
                <input id="ioss-mlm-tab6" class="tab_class" type="radio" name="tabs" checked>
                <label class="tab_class" for="ioss-mlm-tab6"><?php _e('Dashboard','wpmlm-unilevel'); ?></label>
                <input id="ioss-mlm-tab1" class="tab_class" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab1"><?php _e('My Profile','wpmlm-unilevel'); ?></label>      
                <input id="ioss-mlm-tab2" class="tab_class tree-tab-user" type="radio" name="tabs">
                <!-- <label class="tab_class" for="ioss-mlm-tab2"><?php _e('Genealogy Tree','wpmlm-unilevel'); ?></label>    
                <input id="ioss-mlm-tab3" class="tab_class ewallet-tab-user" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab3"><?php _e('E-wallet Management','wpmlm-unilevel'); ?></label>      
                <input id="ioss-mlm-tab4" class="tab_class" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab4"><?php _e('Bonus Details','wpmlm-unilevel'); ?></label> -->
                <input id="ioss-mlm-tab5" class="tab_class" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab5"><?php _e('Referral Details','wpmlm-unilevel'); ?></label>

                <input id="ioss-mlm-tab7" class="tab_class" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab7"><?php _e('Inbox','wpmlm-unilevel'); ?></label>
                


                <section id="content1"><p><?php echo wpmlm_user_profile_admin_nnu($user_id); ?></p></section>  
                
                <section id="content2" ><p><?php echo wpmlm_unilevel_tree($user_id); ?></p></section> 
                <section id="content3"><p><?php echo wpmlm_user_ewallet_management(); ?></p></section>
                <section id="content4"><p><?php echo wpmlm_user_income_details($user_id); ?></p></section>
                <section id="content5"><p><?php echo wpmlm_user_referrals_nnu($user_id); ?></p></section>
                <section id="content6"><p><?php echo wpmlm_user_dashboard_nnu($user_id); ?></p></section> 
                <section id="content7"><p><?php echo wpmlm_user_messaging_nnu($user_id); ?></p></section> 
                 
            </div>

            <?php
        }
        echo "</div>";
       
    } else {
        
        echo '<div class="alert alert-warning alert-dismissible show" role="alert"> Please Register first! :)
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span></button></div>';
        echo do_shortcode('[wp_affiliate_registration_form_new]');
    }
    
}

function custom_wpmlm_ajax_user_check($field = 'username',$field_value) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'users';
    $table_prefix = $wpdb->prefix;
    if (isset($field) && $field == 'username') {
        $username = sanitize_text_field($field_value);
        $username = $wpdb->get_var("SELECT user_login FROM {$table_name} WHERE user_login = '$username'");
        if ($username) {
            //_e('Sorry! Username already taken','wpmlm-unilevel');
            return 0;
        } else {
            return 1;
            //echo '1';
        }
        exit();
    }

    if (isset($field) && $field == 'sponsor' ) {
        $sponsor = sanitize_text_field($field_value);        
        $sponsor = $wpdb->get_var("SELECT a.*,b.* FROM {$table_prefix}users a INNER JOIN {$table_prefix}wpmlm_users b ON a.ID=b.user_ref_id AND a.user_login = '" . $sponsor . "'");
        if (!$sponsor) {
            //_e('Sorry! The specified sponsor is not available for registration.','wpmlm-unilevel');
            return 0;
        } else {
            return 1;
           //echo '1';
        }
        exit();
    }

    if (isset($field) && $field == 'email' ) {
        $user_email = sanitize_email($field_value);
        if (email_exists($user_email)) {
            //_e('Sorry! The Email Address is Already Registered.','wpmlm-unilevel');
            return 0;
        } else {
           // echo '1';
            return 1;
        }
        exit();
    }

    if (isset($field) && $field == 'user_id' ) {

        $user_id = intval($field_value);
        $the_user = get_user_by('ID', $user_id);
        $email = $the_user->user_email;

        $user_email = sanitize_email($_POST['user_email']);


        if ($email == $user_email) {
            echo '1';
            exit();
        } else {
            if (email_exists($user_email)) {
                echo '0';
                exit();
            } else {
                echo '1';
                exit();
            }
        }
    }
}

