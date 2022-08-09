<?php
function wpmlm_user_dashboard($user_id) {
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
           <div class="panel-border col-md-12">         
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
   </div>
   

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
               
               
               <div class="panel-border  col-md-6" style="padding-right: 0px;padding-top: 42px !important;">
               
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
      </div>           

        </div>
    </div>
    <?php
}