<?php
$version = Yii::app()->params['siteJSVersion'] . rand();
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/userLogin.js?v=' . $version);

$isLoggedin		 = UserInfo::isLoggedIn();
$displayBack	 = ($isLoggedin) ? "none" : "inline";
$displayPhone	 = (!$isLoggedin) ? "none" : "block";

Yii::app()->clientScript->registerScriptFile("https://accounts.google.com/gsi/client", CClientScript::POS_HEAD);
if ($isLoggedin)
{
	$objPhone = Users::getPrimaryPhone(UserInfo::getUserId());
	if ($objPhone && $phoneModel)
	{
		/** @var ContactPhone $phoneModel */
		$phoneModel->phn_phone_country_code	 = $objPhone->getCountryCode();
		$phoneModel->phn_phone_no			 = $objPhone->getNationalNumber();
	}
	goto displayPhone;
}
?>
<div class="signInBox">

	 
	<div class="tab-content" id="loginForm" style="display:none;">
		<div class="divider hide">
			<div class="divider-text text-uppercase text-muted"><small>OR</small>
			</div>
		</div>
		<div class="tab-pane fade active show tabLoginForm" id="tabLoginForm" role="tabpanel" aria-labelledby="tabLoginForm-tab" >
			<?php
			/** @var CActiveForm $form */
			$form = $this->beginWidget('CActiveForm', array(
				'id'					 => 'login-form',
				'enableClientValidation' => false,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => false,
					'errorCssClass'		 => 'has-error',
				),
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class'		 => 'form-horizontal',
					'onsubmit'	 => 'return $jsUserLogin.signIn(this);'
				),
			));
			echo $form->errorSummary([$userModel], '', '', ["class" => 'alert alert-danger formMessages mb-1']);
			?>
			<input type="hidden" id="ref" name="ref" value="vendorAttach">
			<div class="form-group mb-50">
				<label class="text-bold-500" for="exampleInputEmail1">Email address / Phone number.<br /><p class="font-11 weight400 color-gray mb5">(Please put phone number with country code e.g +18181818181)</p></label>
				<?= $form->textField($userModel, "username", ["class" => "form-control", "required" => true, "placeholder" => "Enter email address / phone number."]) ?>
				<?= $form->error($userModel, "username", ["class" => "text-danger"]) ?>
			</div>
			<div id="newUser">
			</div>

			<div class="d-flex justify-content-center">
				<button type="submit" class="btn btn-primary glow w-200">Proceed<i id="icon-arrow" class="bx bx-right-arrow-alt align-middle"></i></button>
			</div>								

			<?php $this->endWidget(); ?>
		</div>

	</div>
	<!-- Password  -->
	<div class="tab-content" id="PasswordForm" style="display:none;">
		<?php $this->renderPartial("checkPassword", ['userModel' => $userModel]); ?>
	</div>
	<!-- password end -->

	<!-- SignUpOtpWithPersonalDetails  -->
	<div class="SignUpOtpWithPersonalDetails" id="SignUpOtpWithPersonalDetails"  style="display:none;">
		<?php
		$this->renderPartial("signUpByOtp",
				['contactModel'	 => $contactModel,
					'emailModel'	 => $emailModel,
					'phoneModel'	 => $phoneModel]
		);
		?>
	</div>
	<!-- SignUpOtpWithPersonalDetails end -->

	<!-- captchaVerifyForm  -->
	<div class="" id="captchaVerifyForm"  style="display:none;">
		<?php
		$this->renderPartial("captchaVerify", ['userModel' => $userModel, 'verifyURL' => "users/signupOTPNew?ref=vendorAttach"]);
		?>
	</div>
	<!-- captchaVerifyForm  -->

</div>
<?php
displayPhone:
?>
<div class="showPhone" style="display: <?= $displayPhone ?>">
	<div class="form-group m-1">
		<label class="text-bold-500" for="exampleInputPassword1">Phone</label>
		<?php
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
			'htmlOptions'			 => ['class' => 'form-control ipiShowPhone pl80', 'maxlength' => '15', 'onkeypress' => "return isNumber(event)", 'id' => "userLoginPhone"],
			'localisedCountryNames'	 => false, // other public properties
		));
		?>
		<div class="d-flex justify-content-center mt-1">
			<button type="button" style="display: <?= $displayBack ?>" class="btn btn-light-secondary pl-2 pr-2 mt-1 mr-2" onclick="return toggleSignin();">Go Back</button>
			<button type="button" onclick="savePhone();" class="btn btn-primary glow pl-2 pr-2 mt-1 mr-2 position-relative">Submit<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
		</div></div>
</div>
<div class="mt-2 mb-2 otpBox">

</div>

<script type="text/javascript">
	$skipShowGozonowPromt = true;
	$jsUserLogin = new userLogin();
	$(document).ready(function ()
	{ 
		$("#captchaVerifyForm").hide();
		$("#captchaBlock").hide();
		$("#PasswordForm").hide();
		$("#SignUpOtpWithPersonalDetails").hide();
		$("#skipPasswordForm").hide();
		$("#loginForm").show();
		$(".tabLoginForm").show();
		$(".skipDiscountMsg").hide();


		$('.backToRoot').click(function () {
			//toggleSignin();
			$("#loginForm").show();
//			$("#googleBlock").show();
			$("#PasswordForm").hide();
			$("#skipPasswordForm").hide();
			$(".skipDiscountMsg").hide();

		});
		$('.skiplogin').click(function () {
			closeLogin({phone: $("[name=fullContactNumberToskip").val()});
		});
	});


	function isNumber(evt)
	{
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57))
		{
			var message = "<div class='errorSummary'>Please enter only Numbers.</div>";
			toastr['error'](message, 'Failed to process!', {
				closeButton: true,
				tapToDismiss: false,
				timeout: 500000
			});
			return false;
		}
		return true;
	}

	function showPhone()
	{
		$(".showPhone").show();
		$(".signInBox").hide();
	}

	function savePhone()
	{
		var isValid = $itiuserLoginPhone.intlTelInput('isValidNumber');
		if (isValid)
		{
			closeLogin({phone: $itiuserLoginPhone.intlTelInput('getNumber')});
		} else
		{
			var message = "Please enter valid phone number..";
			toastr['error'](message, 'Invalid Number!', {
				closeButton: true,
				tapToDismiss: false,
				timeout: 500000
			});
			return;
		}
	}

	function toggleSignin()
	{
		$(".signInBox").show();
		$(".showPhone").hide();
		$(".otpBox").hide();
	}

</script>
<script>
//	var googleSignin = function (response)
//	{
//		let data = {
//			'<? //= Yii::app()->request->csrfTokenName      ?>': "<?//= Yii::app()->request->getCsrfToken() ?>",
//			"response": response
//		};
//
//		$.ajax({
//			"type": "POST",
//			"url": "<? //= $this->getURL("users/auth")      ?>",
//			data: data,
//			"dataType": "json",
//			success: function (data)
//			{
//				if (data.success)
//				{
//					closeLogin();
//				} else
//				{
//					displayError(null, data.errors);
//					initGoogleSignin();
//				}
//			}
//		});
//
//	};

	var oneTapListener = function (notification)
	{
		if (notification.isNotDisplayed() || notification.isSkippedMoment())
		{
			//	initGoogleSignin();
		}
	};

	var initGoogleSignin = function ()
	{
		google.accounts.id.initialize({
			client_id: "794396816054-eiut1guo55kfnjp4et57d5fq3akppnb3",
			auto_select: true,
			callback: googleSignin
		});
		google.accounts.id.renderButton(
				document.getElementById("g_id_signin"),
				{theme: "filled_blue", size: "large", logo_alignment: "center", width: '250px'}  // customization attributes
		);
	};


	function checkExt()
	{

		let countryCode = $("#ContactPhone_phn_phone_country_code").val();
		if (countryCode != '91')
		{
			$("#captchaBlock").show();

		} else
		{
			$("#captchaBlock").hide();
		}
	}

</script>

