<?php

function wpmlm_package_settings() {
    $result = wpmlm_get_general_information();

 
        $checked = 'checked';
        $div_style = 'display:none';
        $reg_form_style = 'display:block';
    
    ?>
    <div class="panel panel-default">

        <div class="panel-heading">
            <h4><i class="fa fa-external-link-square"></i> <span> <?php _e('Registration Settings','wpmlm-unilevel'); ?></span></h4>

        </div>
        <div class="panel-border">
            <div>
                <input type="checkbox" <?php echo $checked; ?> class="form-control" name="reg_with_out_package" id="reg_with_out_package" disabled><label class="control-label reg_with_out_pkg_label" for="reg_with_out_package"><?php _e('Registration without using packages','wpmlm-unilevel'); ?></label>


                <div class="amt_submit_message"></div>
              <form id="reg-amt-form" class="form-horizontal " method="post" style="<?php echo $reg_form_style; ?>">
                    <div class="form-group">
                        <label class="control-label col-md-3 user-dt" for="reg_amt"><?php _e('Registration Amount','wpmlm-unilevel'); ?>:</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control reg_amt" name="reg_amt" id="reg_amt" placeholder="<?php _e('Enter registration amount','wpmlm-unilevel'); ?>" value="<?php echo $result->registration_amt; ?>">
                        </div>
                    </div>

                    <div class="form-group"> 
                        <div class="col-sm-offset-3 col-sm-6">
                            <?php wp_nonce_field('reg_amt_add', 'reg_amt_add_nonce'); ?>
                            <button id="reg-amt-save" type="submit" class="btn btn-danger"> <?php _e('Save','wpmlm-unilevel'); ?></button>
                        </div>
                    </div>
                </form>

            </div>

          
        </div>
    </div>
     <script>

        jQuery(document).ready(function ($) {

            $(document).on('submit', '#reg-amt-form', function () {
                $(".amt_submit_message").show();
                $(".amt_submit_message").html('');

                isValid = true;
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_package_settings');

                if (($("#reg_amt").val() == '')|| ($("#reg_amt").val()<0)) {
                    $("#reg_amt").addClass("invalid");
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
                            if ($.trim(data) == '1') {
                                $(".amt_submit_message").html('<div class="alert alert-info"><?php _e("Amount Updated Successfully","wpmlm-unilevel");?></div>');
                                setTimeout(function () {
                                    $(".amt_submit_message").hide();
                                }, 1000);

                            }


                            if ($.trim(data) == '2') {
                                $(".amt_submit_message").html('<div class="alert alert-info"><?php _e("Already Updated","wpmlm-unilevel");?></div>');
                                setTimeout(function () {
                                    $(".amt_submit_message").hide();
                                }, 1000);

                            }

                        }
                    });
                }
                return false;
            });

            $(document).on('click', '#reg_with_out_package', function () {
                var reg_type;
                if ($("#reg_with_out_package").is(':checked')) {
                    $("#package-div").hide();
                    $(".package-settings").hide();
                    $("#package-settings").hide();

                    $("#reg-amt-form").show();
                    reg_type = 'with_out_package';

                } else {
                    $("#reg-amt-form").hide();
                    $("#package-div").show();
                    $(".package-settings").show();
                    reg_type = 'with_package';
                }
                jQuery.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {reg_type: reg_type, action: 'wpmlm_ajax_general_settings'},
                    success: function (data) {
                        if (data == 1) {

                        }

                    }
                });
            });

        });

    </script>
    <?php
}