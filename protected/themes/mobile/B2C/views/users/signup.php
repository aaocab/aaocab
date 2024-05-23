<?
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.js?v=' . $version);
?>
<? $imgVer	 = Yii::app()->params['imageVersion']; ?>
<!--page-login header-clear-large page-login-full-->
<div class="content-boxed-widget page-login page-login-full widget-style-2 widget-content-bg">
	<h3 class="top-10 text-style-2 text-left bottom-0">Welcome aboard!</h3>
        <p class="color-gray bottom-10">Enter your details</p>

	<?php
	$form	 = $this->beginWidget('CActiveForm', array(
		'id'					 => 'signup-form', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error'
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
	<div style="text-align:left; width:350px ;">
	 <?php  if (!empty($errors)){ ?>
				<div  style="font-size: 15px;color: #721c24;background-color: #f8d7da;border-color: #f5c6cb;">
					<ul style="list-style-type:none;">
						<?php foreach($errors as $err){	 ?>
								<li><?php echo $err; ?></li>
						<?php  }?>
					</ul>
				</div>
	
	    <?php } ?>
	
	</div>
	<div class="page-login-field input-simple-1 has-icon input-green bottom-10">
		<?= $form->textField($contactModel, 'ctt_first_name', array('label' => '', 'class' => 'nameFilterMask pl10', 'placeholder' =>'First name', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter your name"]))) ?>

	</div>
	<div class="page-login-field input-simple-1 has-icon input-green bottom-10">
		<?= $form->textField($contactModel, 'ctt_last_name', array('label' => '', 'class' => 'nameFilterMask pl10', 'placeholder' =>'Last name','widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter your last name"]))) ?>

	</div>
	<div class="page-login-field input-simple-1 has-icon input-green bottom-10">
		<?= $form->emailField($emailModel, 'eml_email_address', array('label' => '', 'id' => 'email_2', 'class' =>'pl10', 'placeholder' =>'Email', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email (will be used for login) "]))) ?>

	</div>
	<div class="bottom-0">
		<div class="page-login-field input-simple-1 has-icon input-blue bottom-10">
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
					'htmlOptions'			 => ['class' => 'form-control', 'id' => 'fullContactNumber', 'value' => '', 'maxlength' => '15'],
					'localisedCountryNames'	 => false, // other public properties
				));
				?> 
				<?php echo $form->error($phoneModel, 'phn_phone_country_code'); ?>
				<?php echo $form->error($phoneModel, 'phn_phone_no'); ?>
			</div>

			<div class="clear"></div>
		</div>
	</div>
	<div class="page-login-field input-simple-1 has-icon input-green bottom-10">
		<?= $form->passwordField($model, 'new_password', array('label' => '', 'class' =>'pl10', 'placeholder' =>'Password', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Password"]))) ?>

	</div>


	<div class="page-login-field input-simple-1 has-icon input-green bottom-10">
		<?= $form->passwordField($model, 'repeat_password', array('label' => '', 'class' =>'pl10', 'placeholder' =>'Repeat Password', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Repeat Password"]))) ?>

	</div>

	<div class="page-login-field input-simple-1 has-icon input-green bottom-10">
		<?
		$cookieReferredCode = Yii::app()->request->cookies['gozo_refferal_id']->value;
		if ($model->usr_referred_code != '')
		{
			$model->usr_referred_code = $model->usr_referred_code;
		}
		else if ($cookieReferredCode != '')
		{
			$model->usr_referred_code = $cookieReferredCode;
		}
		?>
		<?= $form->textField($model, 'usr_referred_code', array('label' => '', 'class' =>'pl10', 'placeholder' =>'Referral Code', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Refferal Code"]))) ?>

	</div>
	<?php
	if (CCaptcha::checkRequirements())
	{
		?>  
        <div class="bottom-20" style="display: inline-block;">
			<?php
			echo '<b>ARE YOU HUMAN?</b><br />' . $form->labelEx($model, 'verifyCode');
			?> 
            <div class="page-login-field input-simple-1 has-icon input-green mb10">
				<?php
				$this->widget('CCaptcha', array('clickableImage' => true, 'showRefreshButton' => false, 'captchaAction' => 'site/captcha'));
				echo $form->error($model, 'verifyCode');
				echo '' . $form->textField($model, 'verifyCode');
				?>
				<div class="">
                                    <p class="mt10 bottom-0">Click on the image to change it or refresh it.</p>
                                    <p class="bottom-0">Please enter the letters as they are shown in the image above.</p>
                                    <p>Letters are not case-sensitive.</p>
				</div>
			</div>
		</div>
		<?php
	}
	?>
	<div class="mb20 mt20">

		<? //= CHtml::submitButton("REGISTER", ['class' => "uppercase btn-orange shadow-medium", 'tabindex' => "4"]);   ?>
		<input type="button" value="Register" class="btn-submit" tabindex= "4" id="register_2"><img src="/images/right.svg" width="45" alt="" class="pull-right">

	</div>
	<?php $this->endWidget(); ?>
</div>

<script type="text/javascript">
    $jsBookNow = new BookNow();
    $(document).ready(function () {
//		var errmsg = "";
//		var errors = <?php //echo json_encode($errors); ?>;
//		$.each(errors, function (key, val) {
//			alert(key + val);
//			errmsg += val;
//		});
//		$jsBookNow.showErrorMsg(errmsg);
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
