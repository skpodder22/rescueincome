<?php
function wpmlm_payment_options() {
$result = wpmlm_get_paypal_details();    
$result1 = wpmlm_select_reg_type_name();
$arr = explode(',', $result1->reg_type);
$result2 = wpmlm_get_general_information();
?>
<div id="registration-type-settings">
    <div class="panel panel-default">

        <div class="panel-heading">
            <h4><i class="fa fa-external-link-square"></i> <span> <?php _e('Payment Settings','wpmlm-unilevel'); ?></span></h4>
        </div>

        <div class="panel-border">
            <h5><?php _e('Registration Type','wpmlm-unilevel'); ?></h5>
            <div class="submit-message1"></div>
            <form id="registration-type-settings-form" class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-2">
                        <input class="form-control reg_type reg_type_checkbox" name="reg_type[]" type="checkbox" <?php
                        if (in_array('free_join', $arr)) {
                            echo 'checked';
                        }
                        ?> value="free_join">
                        <label class="control-label" for="free_join"><?php _e('Free Join','wpmlm-unilevel'); ?></label>

                    </div>

                </div>

                
                <div class="form-group"> 
                    <div class="col-sm-2">
                        <button  name="reg-type-submit" class="btn btn-danger" id="reg-type-submit"><?php _e('Save','wpmlm-unilevel'); ?></button>
                    </div>
                </div>
                <?php wp_nonce_field('register_action', 'reg_submit'); ?>
            </form>
        </div>


        <?php 
        if (in_array('paid_join', $arr)) {
            $style="display:block;";       
        }else{
            $style="display:none;";
        } 
        ?>
       
    </div>
</div>

<script>
    jQuery(document).ready(function ($) {

       
        var plugin_url = path.pluginsUrl;
  
        $("#registration-type-settings-form").submit(function () {
            $(".submit-message1").show(); 
            var formData = new FormData(this);
            formData.append('action', 'wpmlm_ajax_payment_option');
            
            isValid = true;
            
            if ($('.reg_type:checkbox:checked').length == 0) {
                $(".submit-message1").html('<div class="alert alert-danger">Please select atleast one registration type</div>');
                setTimeout(function () {
                         $(".submit-message1").hide('slow');   

                        }, 3000);
                
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
                        $(".submit-message1").show();
                        $(".submit-message1").html('<div class="alert alert-info">' + data + '</div>');
                        setTimeout(function () {
                            $(".submit-message1").hide();
                        }, 2000);
                    }
                });
            }
            return false;
        });
        
        $("#payment-type-settings-form").submit(function () {
            isValid = true;
            var formData = new FormData(this);
            formData.append('action', 'wpmlm_ajax_payment_option');
            $(".paypal_input").each(function () {
                var element = $(this);
                if (element.val() == '') {
                    $(this).addClass("invalid");
                    isValid = false;
                }
            });

            if (isValid) {
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $(".submit-message").show();
                        $(".submit-message").html('<div class="alert alert-info">' + data + '</div>');
                        setTimeout(function () {
                            $(".submit-message").hide();
                        }, 2000);
                    }
                });
            }
            return false;
        });

    });

</script>
<?php
}