<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>
  
<div class="content-padding box-text-1 car-panel mb10 pb0 p0 pt0" id="loginsection">
        <div class="page-login top-0 bottom-0 bg-white">
            <h1 class="color-black ultrabold top-10 bottom-5 font-30">Login</h1>
       <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'plogin-form1', 'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'errorCssClass' => 'has-error',
                'afterValidate' => 'js:function(form,data,hasError){
                        if(!hasError){             
                            $.ajax({
                                "type":"POST",
                                "dataType":"json",
                                "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('users/partialsignin')) . '",
                                "data":form.serialize(),
                                "success":function(data1){
									if(!$.isEmptyObject(data1) && data1.success==true){
										$userid = data1.id;                                       
										window.refreshNavbar(data1);
									}
									else{
										settings=form.data(\'settings\');
										var data = data1.data;
										$.each (settings.attributes, function (i) {
										  $.fn.yiiactiveform.updateInput (settings.attributes[i], data, form);
										});
										$.fn.yiiactiveform.updateSummary(form, data1);
									}
								},
							});
						}
					}'
            ),
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // See class documentation of CActiveForm for details on this,
            // you need to use the performAjaxValidation()-method described there.
            'enableAjaxValidation' => false,
            'errorMessageCssClass' => 'help-block',
            'action' => Yii::app()->createUrl('users/partialsignin'),
            'htmlOptions' => array(
                'class' => 'form-horizontal',
            ),
        ));
        /* @var $form CActiveForm */
        ?>
			<?php echo CHtml::errorSummary($model); ?>
            <div class="page-login-field top-15">
                <i class="fas fa-envelope"></i>
               <?= $form->emailField($model, 'usr_email',['required' => TRUE,'class' => 'm0', 'placeholder' => "Email"]) ?>      
                <em>(required)</em>
            </div>
            <div class="page-login-field bottom-15">
                <i class="fa fa-lock"></i>
               <?= $form->passwordField($model, 'usr_password', ['required' => TRUE, 'placeholder' => "Password",'class' => 'm0']) ?>       
                <em>(required)</em>
            </div>
            <div class="page-login-links bottom-10">
                <a class="forgot float-right" href="page-signup.html"><i class="fa fa-user float-right"></i>Create Account</a>
                <a class="create float-left" href="page-forgot.html"><i class="fa fa-eye"></i>Login with Facebook</a>
                <div class="clear"></div>
            </div>
            
            <input class="button button-full button-round button-mint uppercase ultrabold" type="submit" name="signin" value="Login"/>
            
        <?php $this->endWidget(); ?>               
 </div>
    </div>

<script>
var bkCSRFToken = "<?=Yii::app()->request->csrfToken?>";
$(document).ready(function ()
    {		
        callbackLogin = 'fillUserdata';	
    });

  
    function updateLogin()
    {
        $href = '<?= Yii::app()->createUrl('users/refreshuserdata') ?>';
        jQuery.ajax({type: 'get', url: $href,
            "dataType": "json",
            success: function (data1)
            {
                $('#userdiv').hide();
                $('#navbar_sign').html(data1.rNav);
				$('#hideLogin').hide();
				$('#hideDetails').removeClass('col-xs-12 col-sm-7 col-md-7 float-right marginauto book-panel pb0');
				$('#hideDetails').addClass('col-xs-12 col-sm-12 col-md-9 float-none marginauto book-panel pb0');	
				fillUserform2(data1.userData);
                fillUserform13(data1.userData);
            }
        });
    }
    function fillUserform2(data) {

        if ($('#BookingTemp_bkg_user_name').val() == '' && $('#BookingTemp_bkg_user_lname').val() == '')
        {
            $('#BookingTemp_bkg_user_name').val(data.usr_name);
            $('#BookingTemp_bkg_user_lname').val(data.usr_lname);
        }
        if (data['usr_mobile'] != '') {
            if ($('#BookingTemp_bkg_contact_no').val() == '') {
                $('#BookingTemp_bkg_contact_no').val(data.usr_mobile);
            } else if ($('#BookingTemp_bkg_contact_no').val() != '' && $('#BookingTemp_bkg_contact_no').val() != data.usr_mobile) {
                $('#BookingTemp_bkg_alternate_contact').val(data.usr_mobile);
            }
        }
        if (data.usr_email != '') {
            if ($('#BookingTemp_bkg_user_email1').val() == '') {
                $('#BookingTemp_bkg_user_email1').val(data.usr_email);
            }
            if ($('#BookingTemp_bkg_user_email2').val() == '') {
                $('#BookingTemp_bkg_user_email2').val(data.usr_email);
            }
        }

    }
    function fillUserform13(data) {
        if ($('#Booking_bkg_user_name').val() == '' && $('#Booking_bkg_user_lname').val() == '')
        {
            $('#Booking_bkg_user_name').val(data.usr_name);
            $('#Booking_bkg_user_lname').val(data.usr_lname);
        }
        if (data.usr_mobile != '') {
            if ($('#Booking_bkg_contact_no').val() == '') {
                $('#Booking_bkg_contact_no').val(data.usr_mobile);
            } else if ($('#Booking_bkg_contact_no').val() != '' && $('#Booking_bkg_contact_no').val() != data.usr_mobile) {
                $('#Booking_bkg_alternate_contact').val(data.usr_mobile);
            }
        }
        if (data.usr_email != '') {
            if ($('#Booking_bkg_user_email1').val() == '') {
                $('#Booking_bkg_user_email1').val(data.usr_email);
            }
            if ($('#Booking_bkg_user_email2').val() == '') {
                $('#Booking_bkg_user_email2').val(data.usr_email);
            }
        }


    }
</script>