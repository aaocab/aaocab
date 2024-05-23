<?php
$version = Yii::app()->params['siteJSVersion'];
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

if (Yii::app()->request->cookies->contains('travellerCookie'))
{

	$var		 = Yii::app()->request->cookies['travellerCookie']->value;
	//   print_r($var);
	$cookieEmail = $var->email[0]->address;
	$cookiePhone = "+" . $var->phone[0]->fullNumber;
}
?>
<div class="signInBox">

	<!--	<ul class="nav nav-tabs d-flex justify-content-between" id="nav-tabs" role="tablist">
			<li class="nav-item">
				<a class="nav-link active text-center" data-toggle="tab" href="#tabLoginForm" role="tab" aria-controls="home" aria-selected="true">Login</a>
			</li>
			<li class="nav-item">
				<a class="nav-link text-center" data-toggle="tab" href="#tabSignupForm" role="tab" aria-controls="profile" aria-selected="false">New user</a>
			</li>
		</ul>-->
	<div class="text-center" style="display: table; margin: auto" id="googleBlock">
		<div id="g_id_onload"
			 data-client_id="794396816054-eiut1guo55kfnjp4et57d5fq3akppnb3"
			 data-context="signin"
			 data-callback="googleSignin"
			 data-moment_callback="oneTapListener"
			 data-auto_select="false"
			 data-auto_prompt="true"
			 >
		</div>
		<div class="g_id_signin"
			 data-type="standard"
			 data-shape="rectangular"
			 data-theme="outline"
			 data-text="signin_with"
			 data-size="large"
			 data-logo_alignment="left">
		</div>

		<div class="divider">
			<div class="divider-text text-uppercase text-muted"><small>OR</small>
			</div>
		</div>

	</div>

	<div class="tab-content" id="loginForm" style="display:none;">

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
			<div class="form-group mb-50">
				<label class="text-bold-500" for="exampleInputEmail1">Email address / Phone number.<br /><p class="font-11 weight400 color-gray mb5">(Please put phone number with country code e.g +18181818181)</p></label>
<?= $form->textField($userModel, "username", ["class" => "form-control", "required" => true, "placeholder" => "Enter email address / phone number."]) ?>
				<?= $form->error($userModel, "username", ["class" => "text-danger"]) ?>
			</div>

			<div id="newUser"></div>

			<div class="d-flex justify-content-center">
				<button type="submit" class="btn btn-primary glow w-200">Proceed<img src="/images/bx-right-arrow-alt.svg" alt="img" width="18" height="18"></button>
			</div>
			<div class="text-center font-16 weight500 color-green mt15" ><span>login to save upto 20%</span></div>	
<?php if ($hideSkipLogin != 1)
{ ?>
				<div class="col-12 text-right skipLoginBtn hide float-right mt15">
					<input type="hidden" name="YII_CSRF_TOKEN" value= "<?= Yii::app()->request->csrfToken; ?>">  
					<button onclick="skipLogin(1);" class=" mt10 btn-default border-0 pl20 pr20"><u>SKIP<img src="/images/bx-chevrons-right.svg" alt="img" width="18" height="18"></u></button>
				</div>
			<? } ?>
			<?php $this->endWidget(); ?>
		</div>
		<div id="signInerror" class="error"></div>

	</div>
	<!-- Password  -->
	<div class="tab-content" id="PasswordForm" style="display:none;">
		<?php $this->renderPartial("checkPassword", ['userModel' => $userModel]); ?>
	</div>
	<div class="tab-content" id="ResetPasswordOTPForm" style="display:none;">
		<?php
		$this->renderPartial("resetOTP",
					   ['contactModel'	 => $contactModel,
					'emailModel'	 => $emailModel,
					'phoneModel'	 => $phoneModel]
		);
		?>
	</div>
    <div class="tab-content" id="ResetPasswordForm" style="display:none;">
		<?php
		$this->renderPartial("resetpassword",
					   ['contactModel'	 => $contactModel,
					'emailModel'	 => $emailModel,
					'phoneModel'	 => $phoneModel]
		);
		?>
	</div>
	<!-- password end -->
	<div class="signInStep2" style="display: none">


	</div>

	<!-- SignUpOtpWithPersonalDetails  -->
	<div class="SignUpOtpWithPersonalDetails" id="SignUpOtpWithPersonalDetails"  style="display:none;">
		<?php
//		$this->renderPartial("signUpByOtp",
//					   ['contactModel'	 => $contactModel,
//					'emailModel'	 => $emailModel,
//					'phoneModel'	 => $phoneModel,'verifyData'=>$verifyData]
//		);
		?>
	</div>
	<div class="alertExist" id="alertExist"  style="display:none;">
		<?php
		$this->renderPartial("signUpByOtpExistContact",
					   ['contactModel'	 => $contactModel,
					'emailModel'	 => $emailModel,
					'phoneModel'	 => $phoneModel, 'verifyData'	 => $verifyData]
		);
		?>
	</div>
	<div id="otpverified"></div>
	<!-- SignUpOtpWithPersonalDetails end -->

	<!-- captchaVerifyForm  -->
	<!--	<div class="" id="captchaVerifyForm"  style="display:none;">-->
	<?php
	//$this->renderPartial("captchaVerify",
	//   ['userModel' => $userModel, 'verifyURL' => "users/signupOTPNew"]);
	?>
	<!--	</div>-->
	<!-- captchaVerifyForm  -->


	<?php
	if ($showPhone == 1)
	{
		?>
		<!--		<div class=" divider divider_skipPasswordForm"style="display:none">
								<div class="divider-text text-uppercase text-muted "><small>OR</small></div>
					<div class="text-center font-16 weight500 color-green skipDiscountMsg" ><span>login to save upto 20%</span></div>
				</div>-->
		<!--		<div class="text-center">				
										<a href="javascript:void(0)" class="btn btn-outline btn-outline-primary" onclick="showPhone();">Continue with phone number</a>
					<span class="float-right mt5 hide" id="skipPasswordForm">
						<button onclick="skipLogin();" class="skiplogin mt10 btn-default border-0 pl20 pr20"><u>SKIP<img src="/images/bx-chevrons-right.svg" alt="img" width="18" height="18"></u></button>
						<input type="hidden" name="fullContactNumberToskip" value="">
						<input type="hidden" name="ContactEmailToskip" value="">
					</span>
				</div>-->
	<?php }
	?>
</div>
<?php
displayPhone:
?>
<div class="showPhone" style="display: <?= $displayPhone ?>">
	<div class="form-group m-1">
		<label class="text-bold-500" for="exampleInputPassword1">Phone</label>
		<?php
//        if($var->phone[0]->fullNumber)
//        {
//        $phoneModel->phn_phone_no = $var->phone[0]->fullNumber;
//        }
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
			<button type="button" onclick="savePhone();" class="btn btn-primary glow pl-2 pr-2 mt-1 mr-2 position-relative">Submit<img src="/images/bx-right-arrow-alt.svg" alt="img" width="18" height="18"></button>
		</div></div>
</div>
<div class="mt-2 mb-2 otpBox">

</div>
<div id="captchaSkipLogin" style="display: none">

</div>


<script type="text/javascript">
//debugger;

//    function putOtp(otp)
//    {
//        var arr = otp.split("");
//        $('.ootpNumber1').val(arr[0]);
//        $('.ootpNumber2').val(arr[1]);
//        $('.ootpNumber3').val(arr[2]);
//        $('.ootpNumber4').val(arr[3]);
//        getOTP();
//    }
	var returnUrl = '<?=Yii::app()->user->getReturnUrl();?>';
	$jsUserLogin = new userLogin();
	$(document).ready(function()
	{
		if (document.cookie != '')
		{
			let cookie = getCookie('travellerCookie');
			if (cookie)
			{
				$("input[name='ContactPhone[fullContactNumber]']").val(cookie);
			}
		}


		let cookieEmail = '<?php echo $cookieEmail; ?>';
		let cookiePhone = '<?php echo $cookiePhone; ?>';
		let cookieUser = (cookieEmail != '') ? (cookieEmail) : (cookiePhone);
		// alert(cookieUser);
		$("#Users_username").val(cookieUser);

		trackPage("/signin");

		$("#captchaVerifyForm").hide();
		$("#captchaBlock").hide();
		$("#PasswordForm").hide();
		$("#SignUpOtpWithPersonalDetails").hide();
		if (!$("#skipPasswordForm").hasClass("hide"))
		{
			$("#skipPasswordForm").addClass('hide');
		}
		$("#loginForm").show();
		$(".tabLoginForm").show();
		$(".skipDiscountMsg").hide();


		$('.backToRoot').click(function(e)
		{
			e.preventDefault();
			//toggleSignin();
			$("#loginForm").show();
			$("#googleBlock").show();
			$("#PasswordForm").hide();
			if (!$("#skipPasswordForm").hasClass("hide"))
			{
				$("#skipPasswordForm").addClass('hide');
			}
			$(".skipDiscountMsg").hide();

		});
		$('.skiplogin').click(function()
		{
			closeLogin({phone: $("[name=fullContactNumberToskip").val()});
		});
		if (typeof $skipLogin !== 'undefined')
		{
			if ($skipLogin == 0)
			{
				$('.skipLoginBtn').removeClass('hide');
			}
			if ($skipLogin == 2 && !$('.skiplogin').hasClass('hide'))
			{
				$('.skipLoginBtn').addClass('hide');
			}
		}
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
		//debugger;
		var isValid = $itiuserLoginPhone.intlTelInput('isValidNumber');
		if (isValid)
		{
			//debugger;
			document.cookie = 'travellerCookie' + "=" + $itiuserLoginPhone.intlTelInput('getNumber');
			closeLogin({phone: $itiuserLoginPhone.intlTelInput('getNumber')});
		}
		else
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
	var googleSignin = function(response)
	{
		let data = {
			'<?= Yii::app()->request->csrfTokenName ?>': "<?= Yii::app()->request->getCsrfToken() ?>",
			"response": response
		};

		$.ajax({
			"type": "POST",
			"url": "<?= $this->getURL("users/auth") ?>",
			data: data,
			"dataType": "json",
			success: function(response)
			{
				if (response.success)
				{
					if (response.hasOwnProperty("data") && response.data.hasOwnProperty("isNew") && response.data.isNew)
					{
						trackSignUp("Google");
					}

					trackLogin("Google");
					closeLogin();
				}
				else
				{
					displayError(null, response.errors);
					initGoogleSignin();
				}
			}
		});

	};

	var oneTapListener = function(notification)
	{
		if (notification.isNotDisplayed() || notification.isSkippedMoment())
		{
			//		initGoogleSignin();
		}
	};

	var initGoogleSignin = function()
	{
		google.accounts.id.renderButton(document.getElementById("g_id_onload"), {
			theme: 'outline'
		});
	};


	function checkExt()
	{

		let countryCode = $("#ContactPhone_phn_phone_country_code").val();
		if (countryCode != '91')
		{
			$("#captchaBlock").show();

		}
		else
		{
			$("#captchaBlock").hide();
		}
	}
	function getCookie(name)
	{
		//debugger;
		const value = `; ${document.cookie}`;
		const parts = value.split(`; ${name}=`);
		var pt = parts[1];
		if (pt == undefined)
		{
			return false;
		}
		var pp = pt.split('; ');

		return pp[0];
		//if (parts.length === 2) return parts.pop().split(';').shift();
	}

	function skipLogin(triggeredFrom = 0)
	{
		if (triggeredFrom == 1)
		{
			$.ajax({
				"type": "POST",
				"dataType": "html",
				"async": true,
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('users/captchaskiplogin')) ?>",
				"data": {'showcaptcha': 1, 'YII_CSRF_TOKEN': "<?= Yii::app()->request->csrfToken ?>"},
				"success": function(data2)
				{
					var data = "";
					var isJSON = false;
					try
					{
						data = JSON.parse(data2);
						isJSON = true;
						if (!data.success)
						{
							$(".errorCaptcha").show();
							$(".errorCaptcha").text(data.errors[0]);
						}
						else
						{
							if (data.allowQuote)
							{
								$skipLogin = 1;
								bootbox.hideAll();
								checkCatQuotes();
							}
						}
					}
					catch (e)
					{

					}
					if (!isJSON)
					{

						$(".signInBox").hide();
						$(".showPhone").hide();
						$(".otpBox").hide();
						$('#captchaSkipLogin').html(data2);
						$('#captchaSkipLogin').show();
					}
					return false;
				},
				error: function(xhr, ajaxOptions, thrownError)
				{
					var message = xhr.status + ": " + thrownError;
					toastr['error'](message, 'Failed to process!', {
						closeButton: true,
						tapToDismiss: false,
						timeout: 500000
					});
				}
			});

			return false;
		}
		else
		{
			$skipLogin = 1;
			bootbox.hideAll();
			checkCatQuotes();
	}
	}

</script>

