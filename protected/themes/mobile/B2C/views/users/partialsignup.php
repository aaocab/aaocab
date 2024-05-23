<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/login.js?v=' . $version);
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
$imgVer	 = Yii::app()->params['imageVersion']; 
?>

<div class="content-boxed-widget page-login page-login-full">
	<?php
	$form	 = $this->beginWidget('CActiveForm', array(
		'id'					 => 'signup-form', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){      
                            $.ajax({
                                "type":"POST",
                                "dataType":"json",
                                "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                                "data":form.serialize(),
                                "success":function(data1){
								var book_Now = new BookNow();
                                if(!$.isEmptyObject(data1) && data1.success==true){									
                                   	$("#navbar_sign").html(data1.rNav);									
									let objlogin = new Login();
									let usrData = JSON.parse(data1.userdata);
									objlogin.fillUserFormMobile(usrData);									
							    }
                                else{
									settings=form.data(\'settings\');
//                                  var data = data1.data;
//                                    $.each (settings.attributes, function (i) {
//                                      $.fn.yiiactiveform.updateInput (settings.attributes[i], data, form);
//                                  });
                                    $.fn.yiiactiveform.updateSummary(form, data1);
									var msg = "";
									elements = data1.errors;
									
									elements.forEach(function(item, index) {
										 msg += item + "<br/>";
									});
									if(msg) {
										book_Now.showErrorMsg(msg);
									}
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
		
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class' => 'form-horizontal',
		),
	));
	?>   
	<div style="text-align:center; padding:0 20px ;">
	</div>
	<div class="col-12"><div><span style='color:#B80606;' class="showErrorN"></span></div>
		<div class="input-simple-1 has-icon input-green bottom-10"><strong>Required Field</strong><em>First name</em><i class="fas fa-user-alt"></i>
		<?= $form->textField($contactModel, 'ctt_first_name', array('class' => 'nameFilterMask', 'label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter your name"]))) ?>

	</div>
	<div class="input-simple-1 has-icon input-green bottom-10"><strong>Required Field</strong><em>Last name</em><i class="fas fa-user-alt"></i>
		<?= $form->textField($contactModel, 'ctt_last_name', array('class' => 'nameFilterMask', 'label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter your last name"]))) ?>

	</div>
	<div class="input-simple-1 has-icon input-green bottom-10"><strong>Required Field</strong><em>Email*</em><i class="fas fa-envelope"></i>
		<?= $form->emailField($emailModel, 'eml_email_address', array('label' => '', 'id' => 'email_2', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email (will be used for login) "]))) ?>

	</div>
	<div class="bottom-0">
		<div class="input-simple-1 has-icon input-blue bottom-15"><strong>Required Field</strong><em>Phone Number (incl. country code)</em><i class="fa fa-phone"></i>
			<div class="">

				<?php
				$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
					'model'					 => $phoneModel,
					'attribute'				 => 'phn_phone_no',
					'codeAttribute'			 => 'phn_phone_country_code',
					'numberAttribute'		 => 'phn_phone_no',
					'options'				 => array(// optional
						'separateDialCode'	 => true,
						'autoHideDialCode'	 => true,
						'initialCountry'	 => 'in'
					),
					'htmlOptions'			 => ['class' => 'form-control', 'id' => 'fullContactNumber','value' => '','maxlength' => '15'],
					'localisedCountryNames'	 => false, // other public properties
				));
				?> 
				<?php echo $form->error($phoneModel, 'phn_phone_country_code'); ?>
				<?php echo $form->error($phoneModel, 'phn_phone_no'); ?>
			</div>

			<div class="clear"></div>
		</div>
	</div>
	<div class="input-simple-1 has-icon input-green bottom-20"><strong>Required Field</strong><em>Password</em><i class="fas fa-lock"></i>
		<?= $form->passwordField($model, 'new_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Password"]))) ?>

	</div>


	<div class="input-simple-1 has-icon input-green bottom-20"><strong>Required Field</strong><em>Repeat Password</em><i class="fas fa-lock"></i>
		<?= $form->passwordField($model, 'repeat_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Repeat Password"]))) ?>

	</div>

	<div class="input-simple-1 has-icon input-green bottom-20"><em>Referral Code</em>		
		<?= $form->textField($model, 'usr_referred_code', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Refferal Code"]))) ?>

	</div>
    <?php
	if (CCaptcha::checkRequirements())
	{
		?>  
		<div class="input-simple-1 has-icon input-green bottom-20">
			<?php
			echo '<b>ARE YOU HUMAN?</b><br />' . $form->labelEx($model, 'verifyCode');
			?> 
			<div>
				<?php
				$this->widget('CCaptcha', array('clickableImage' => true, 'showRefreshButton' => false, 'captchaAction' => 'site/captcha'));
				echo $form->error($model, 'verifyCode');
				echo '<br />' . $form->textField($model, 'verifyCode');
				?>
				<div class="">
					Click on the image to change it or refresh it.<br/>
					Please enter the letters as they are shown in the image above.<br/>
					Letters are not case-sensitive.
				</div>
			</div>
		</div>
		<?php
	}
	?>
	<div class="text-center mb20">		
		<input type="button" value="Register" class="uppercase btn-orange shadow-medium" tabindex= "4" id="register_2">
	</div>
	<?php $this->endWidget(); ?>
</div>

<script type="text/javascript">
    $jsBookNow = new BookNow();
    $(document).ready(function () {
    });
    function validateCheckHandlerss() {
        if ($("#formId").validation({errorClass: 'validationErr'})) {
            return true;
        } else {
            return false;
        }
    }
    $("#register_2").click(function (event) {
        var is_error = 0;
        var msg = "";
        var uemail = $("#email_2").val();
        var usercontact = $.trim($('#fullContactNumber').val());
        var cont = usercontact.replace(/\s/g, '');

        if ($.trim($("#Contact_ctt_first_name").val()) == "")
        {
            msg += "First name cannot be blank<br/>";
            is_error++;
        }
        if ($.trim($("#Contact_ctt_last_name").val()) == "")
        {
            msg += "Last name cannot be blank<br/>";
            is_error++;
        }

        if ($.trim(uemail) == "")
        {
            msg += "Email cannot be blank<br/>";
            is_error++;
        } else if (!$jsBookNow.validateEmail(uemail))
        {
            msg += "Email is not valid<br/>";
            is_error++;
        }

        if (usercontact == "")
        {
            msg += 'Mobile no cannot be blank<br/>';
            is_error++;
        } 
		/*else if (cont.length < 10 || cont.length > 12)
        {
            msg += 'Invalid mobile no<br/>';
            is_error++;
        } 
		else if (isInteger(usercontact) == false) {
            msg += 'Invalid mobile no<br/>';
            is_error++;
        }*/

        if ($.trim($("#Users_new_password").val()) == "")
        {
            msg += "Password cannot be blank<br/>";
            is_error++;
        }
        if ($.trim($("#Users_repeat_password").val()) != $.trim($("#Users_new_password").val()))
        {
            msg += "Repeat Password does not match<br/>";
            is_error++;
        }

        if (is_error > 0) {
            $jsBookNow.showErrorMsg(msg);
            event.preventDefault();
        } else {
            $("#signup-form").submit();
        }
    });
    function isInteger(s) {
        var i;
        s = s.toString();
        for (i = 0; i < s.length; i++) {
            var c = s.charAt(i);
            if (isNaN(c)) {
                return false;
            }
        }
        return true;
    }	
</script>