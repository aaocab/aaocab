<!--class="tab-pane fade" id="tabSignupForm" role="tabpanel" aria-labelledby="tabSignupForm-tab"-->
<div class="form-group mt5 n"><a href="#" class="p10 pl0 color-black"><i class='bx bx-arrow-back backToRoot1'></i></a></div>
<div class="row" >
	<div class="col-12  h4" >
		Welcome to aaocab! Please provide following details to create an account 
	</div>
</div>
<div >
	<?php
	$contactModel	 = new Contact();
	$phoneModel		 = new ContactPhone();
	if ($fullNumber != '')
	{
		Filter::parsePhoneNumber($fullNumber, $code, $number);

		$phoneModel->fullContactNumber		 = $fullNumber;
		$phoneModel->phn_phone_country_code	 = $code;
		$phoneModel->phn_phone_no			 = $number;
	}
	$emailModel	 = new ContactEmail();
	$form		 = $this->beginWidget('CActiveForm', array(
		'id'					 => 'signup-form',
		'enableClientValidation' => false,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => false,
			'errorCssClass'		 => 'has-error',
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class'		 => 'form-horizontal',
			'onsubmit'	 => 'return $jsUserLogin.register(this);'
		),
	));
	?>

	<div class="alert alert-danger mb-2 text-center hide alerterror" role="alert"></div>
	<input type="hidden" id="otpObject" name="otpObject" value="">
	<input type="hidden" id="ref" name="ref" value="vendorAttach">
	<input type="hidden" id="verifyData" name="verifyData" value="<?php echo $verifyData; ?>">

	<input type="hidden" id="pid" name="signup" value="<? //$signup                ?>">
	<input type="hidden" id="vOTP" name="otp" value="">
	<input type="hidden" id="step" class="step" value="3">








	<div class="form-group mb-50">
		<label class="text-bold-500" for="exampleInputEmail1">First name</label>
		<?= $form->textField($contactModel, "ctt_first_name", ["class" => "form-control", "placeholder" => "Enter first name", "required" => true]) ?>
		<?= $form->error($contactModel, "ctt_first_name", ["class" => "text-danger"]) ?>
	</div>
	<div class="form-group mb-50">
		<label class="text-bold-500" for="exampleInputEmail1">Last name</label>
		<?= $form->textField($contactModel, "ctt_last_name", ["class" => "form-control", "placeholder" => "Enter last name", "required" => true]) ?>
		<?= $form->error($contactModel, "ctt_last_name", ["class" => "text-danger"]) ?>
	</div>
	<div class="form-group mb-50 hide">
		<label class="text-bold-500" for="exampleInputEmail1">Email</label>
		<!--, "required" => true-->
		<?= $form->emailField($emailModel, "eml_email_address", ["class" => "form-control", "placeholder" => "Enter email address"]) ?>
		<?= $form->error($emailModel, "eml_email_address", ["class" => "text-danger"]) ?>
	</div>
	<div class="form-group">
		<label class="text-bold-500" for="exampleInputPassword1">Phone</label>
		<?php
//ContactPhone_phn_phone_no
		$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
			'model'					 => $phoneModel,
			'attribute'				 => 'fullContactNumber',
			'codeAttribute'			 => 'phn_phone_country_code',
			'numberAttribute'		 => 'phn_phone_no',
			'options'				 => array(
				'customContainer'	 => 'full-width',
				'separateDialCode'	 => true,
				'autoHideDialCode'	 => true,
				'initialCountry'	 => 'in'
			),
			'htmlOptions'			 => ['class' => 'form-control pl80', 'maxlength' => '15', 'onkeypress' => "return isNumber(event)", 'id' => 'fullContactNumber' . $id],
			'localisedCountryNames'	 => false, // other public properties
		));
		?>
	</div>
	<div class="form-group mb-50">
		<div class="col-12 mt-1 text-center font-13" >
			We have sent a one-time password (OTP) to <span class='userName'>   </span>. Enter it here to proceed.</div><!--  -->

		<div id="otp" class="col-12" style="max-width: 300px; margin: auto ;<?= $style; ?>">

			<div class="row">
				<div class="col-3 p5"><?php echo CHtml::numberField('number1', '', array('onkeyup' => 'onKeyUpEvent1(1, event)', 'onfocus' => 'onFocusEvent(1)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 otpNumber1')) ?></div>
				<div class="col-3 p5"><?php echo CHtml::numberField('number2', '', array('onkeyup' => 'onKeyUpEvent1(2, event)', 'onfocus' => 'onFocusEvent(2)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 otpNumber2')) ?></div>
				<div class="col-3 p5"><?php echo CHtml::numberField('number3', '', array('onkeyup' => 'onKeyUpEvent1(3, event)', 'onfocus' => 'onFocusEvent(3)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 otpNumber3')) ?></div>
				<div class="col-3 p5"><?php echo CHtml::numberField('number4', '', array('onkeyup' => 'onKeyUpEvent1(4, event)', 'onfocus' => 'onFocusEvent(4)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 otpNumber4')) ?></div>
				<div class="correctotp danger col-12 mt5 text-center"></div>
			</div>
		</div>
	</div>

	<div class="form-group mb-50  text-center">
		<span id="OTP"class="justify-content-center otpText"></span>
		<span id="counterSU"></span>

		<span class="justify-content-center">	Didn't receive the OTP? <a id="resendotpSU" style="display: none;" href="#" onclick="$jsUserLogin.resendOtpSU();"> Resend OTP</a></span><br>
		<div class="resendotpSU"></div>
	</div>


	<div class="d-flex justify-content-center ">
		<button type="submit" class="btn btn-primary glow w-200 position-relative ">Signup<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
	</div>
	<?php $this->endWidget(); ?>
</div>


<script>
	$jsUserLogin = new userLogin();
	$(document).ready(function ()
	{
		if ($('#ContactPhone_phn_phone_no').val())
		{
			$('#fullContactNumber').attr('readonly', true);
		}

		$('.backToRoot1').click(function () {
			//toggleSignin();
			$("#loginForm").show();
			$(".tabLoginForm").show();
			$("#googleBlock").show();
			$("#PasswordForm").hide();
			$("#SignUpOtpWithPersonalDetails").hide();
		});
	});

	function getCodeBoxElement(index)
	{
		return $('form#signup-form .otpNumber' + index)[0];
	}

	function onKeyUpEvent1(index, event)
	{

		const eventCode = event.which || event.keyCode;
		if (getCodeBoxElement(index).value.length > 1)
		{
			getCodeBoxElement(index).value = getCodeBoxElement(index).value.slice(0, 1);
		}
		if (getCodeBoxElement(index).value.length == 1)
		{
			if (index !== 4)
			{
				getCodeBoxElement(index + 1).focus();
			} else
			{
				getCodeBoxElement(index).blur();
			}
		}
		if (eventCode === 8 && index !== 1)
		{
			getCodeBoxElement(index - 1).focus();
		}
		var OTP = getOTP();
		$("form#signup-form BUTTON:submit").prop("disabled", !(OTP.length === 4));
	}
	function onFocusEvent(index)
	{
		for (item = 1; item < index; item++)
		{
			const currentElement = getCodeBoxElement(item);
			if (!currentElement.value)
			{
				currentElement.focus();
				break;
			}
		}
	}
	function getOTP()
	{ 
		var num1 = $('.otpNumber1').val();
		var num2 = $('.otpNumber2').val();
		var num3 = $('.otpNumber3').val();
		var num4 = $('.otpNumber4').val();
		var cusotp = num1 + num2 + num3 + num4;
		$("#vOTP").val(cusotp);
		return cusotp;
	}






</script>
