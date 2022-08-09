<?php

function wpmlm_settings() {
    
    ?>
    <div class="panel-border-heading">
        <h4><i class="fa fa-cogs" aria-hidden="true"></i> <?php _e('Settings','wpmlm-unilevel'); ?></h4>
    </div>
    <div id="general-settings">
        <div class="panel-border col-md-12">
    <div id="exTab6" >
        <div class="col-md-3 ">
            <ul  class="nav nav-tabs tabs-right">            
                <li class="active"><a  href="#1d" data-toggle="tab"><?php _e('General Settings','wpmlm-unilevel'); ?></a></li>
                <li><a href="#2d" data-toggle="tab"><?php _e('Registration Settings','wpmlm-unilevel'); ?></a></li>
                <li><a href="#3d" data-toggle="tab"><?php _e('Bonus Settings','wpmlm-unilevel'); ?></a></li>
                <li><a href="#4d" data-toggle="tab"><?php _e('Payment Settings','wpmlm-unilevel'); ?></a></li>
            </ul>
        </div>

        <div class="tab-content clearfix col-md-9">

            <div class="tab-pane active" id="1d">
                <div><?php echo wpmlm_general_settings(); ?></div>
            </div>
            <div class="tab-pane" id="2d">
                <div><?php echo wpmlm_package_settings(); ?></div>
            </div>
            <div class="tab-pane" id="3d">
                <div><?php echo wpmlm_commission_settings(); ?></div>
            </div>
            <div class="tab-pane" id="4d">
                <div><?php echo wpmlm_payment_options(); ?></div>
            </div>


        </div>
    </div>
</div>

    </div> 

    <?php
}