
<div class="row">
    <div class="col-xs-12  mt0" id="loginsection">

		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'plogin-form1', 'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){             
                            $.ajax({
                                "type":"POST",
                                "dataType":"json",
                                "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('users/partialsignin')) . '",
                                "data":form.serialize(),
                                "success":function(data1){
									if(!$.isEmptyObject(data1) && data1.success==true){
									var userinfo = JSON.parse(data1.userdata);
                                    $userid = data1.id;				   									
									fillUserform2(userinfo);
									fillUserform13(userinfo);                                
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
			// Please note: When you enable ajax validatio n, make sure the corresponding
			// controller action is handling ajax validation correctly.
			// See class documentation of CActiveForm for details on this,
			// you need to use the performAjaxValidation()-method described there.
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'action'				 => Yii::app()->createUrl('users/partialsignin'),
			'htmlOptions'			 => array(
				'class' => 'form-horizontal',
			),
		));
		/* @var $form TbActiveForm */
		?>

        <div class="row">
            <div class="col-xs-12">     
				<?php echo CHtml::errorSummary($model); ?>
            </div>
        </div> 
		<?php if ($isFlexxi)
		{ ?>
			<div class="row">
				<div class="col-xs-12 col-md-10 col-md-offset-2 mb20">
					<a href="#"><span class="btn fb-btn text-uppercase" onclick="$jsLogin.openFbDialog(<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook', 'isFlexxi' => true)); ?>);"><i class="fa fa-facebook pr5"></i> Connect with Facebook</span></a>
				</div>
			</div>
<?php }
else
{ ?>
			<div class="row">
				<div class="col-xs-12">Email Address<span class="red-color">*</span></div>
				<div class="col-xs-12 mb20">                        
					<?= $form->emailFieldGroup($emailModel, 'eml_email_address', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['required' => TRUE, 'placeholder' => "Email"]), 'groupOptions' => ['class' => 'm0'])) ?>   
				</div>
				<div class="col-xs-12">Password<span class="red-color">*</span></div>
				<div class="col-xs-12">                        
	<?= $form->passwordFieldGroup($model, 'usr_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['required' => TRUE, 'placeholder' => "Password"]), 'groupOptions' => ['class' => 'm0'])) ?>   
				</div> 
				<div class="col-xs-12">&nbsp;</div>
				<div class="col-xs-12 pt10">
					<div class="row">
						<div class="col-xs-6">
							<input class="btn next2-btn text-uppercase col-xs-12" type="submit" name="signin" value="Sign In"/>
						</div>
						<div class="col-xs-6">
							<a class="btn next3-btn text-uppercase col-xs-12" onclick="callSignupbox()" role="button"><b>Sign Up</b></a>
						</div>
					</div>
				</div>
			</div>
<?php } ?>
<?php $this->endWidget(); ?>         
    </div>

</div>
<script>

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
                if ($("#hideDetails").hasClass("col-xs-12 col-sm-6 col-md-6 marginauto book-panel pb0"))
                {
                    $("#hideDetails").removeClass("col-xs-12 col-sm-6 col-md-6 marginauto book-panel pb0");
                    $("#hideDetails").addClass("col-xs-12 col-sm-9 col-md-7 book-panel pb0");
                }
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
        if (data.usr_mobile != '') {
            if ($('input[name="BookingTemp[fullContactNumber]"]').val() == '')
            {
                $('input[name="BookingTemp[fullContactNumber]"]').val(data.usr_mobile);

            }
            if ($('input[name="BookingTemp[bkg_contact_no]"]').val() == '') {
                $('input[name="BookingTemp[bkg_contact_no]').val(data.usr_mobile);
            } else if ($('input[name="BookingTemp[bkg_contact_no]').val() != '' && $('input[name="BookingTemp[bkg_contact_no]').val() != data.usr_mobile) {
                $('#BookingTemp_bkg_alternate_contact').val(data.usr_mobile);
            }
        }
        if (data.usr_email != '') {
            if ($('input[name="BookingTemp[bkg_user_email]"]').val() == '') {
                $('input[name="BookingTemp[bkg_user_email]"]').val(data.usr_email);
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

    function callSignupbox()
    {
        $jsLogin.userSignup("<?= Yii::app()->createUrl('users/partialsignup', ['callback' => 'refreshNavbar(data1)']) ?>");
    }
</script>