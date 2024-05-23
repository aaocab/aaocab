<?php

/** @var CActiveForm $form */
$form		 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'otpverify-form',
	'action'				 => '/users/otpVerify',
	'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => 'form-horizontal',
		'onsubmit'	 => 'return $jsUserLogin.validateForm(this);'
	),
		));
$showResend	 = "display: none";
$spamMsg	 = "";

foreach ($this->pageRequest->contactVerifications as $contactVerification)
{
	if ($contactVerification->type == Stub\common\ContactVerification::TYPE_EMAIL)
	{
		$spamMsg = " Check your spam folder.";
	}
}
$smsSend;
$verificationObj		 = $this->pageRequest->contactVerifications[0];
$phoneverificationObj	 = $this->pageRequest->contactVerifications[1];

if ($verificationObj != null && $verificationObj->type == 2)
{
	$smsSend = $verificationObj->isSendSMS;
}
if ($phoneverificationObj != null && $phoneverificationObj->type == 2)
{
	$smsSend = $phoneverificationObj->isSendSMS;
}
?>
<div class="form-group back-btn-2"><a href="#" class="p10 pl0 color-black"><img src="/images/bx-arrow-back.svg" alt="img" width="14" height="14" onclick="return toggleSignin();"></a></div>
<div class="row text-center verifyotp">
	<input type="hidden" id="verifyData" name="verifyData" value="<?php echo $verifyData; ?>">
        <input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
	<input type="hidden" id="pid" name="signup" value="<?= $signup ?>">
	<input type="hidden" id="oneTimePassword" name="otp" value="">
	<input type="hidden" id="step" class="step" value="3">
	<input type="hidden" id="ref" name="ref" value="<?= $ref ?>">
	<?php
   
	if ($smsSend == 0 && ($verificationObj->type == 2 || $phoneverificationObj->type == 2))
	{
		$style = "display:none;";
	}
	?>
	<div class="col-12 mt-1" style="<?= $style; ?>">
		<h4 class="merriw">We have sent a one-time password (OTP) to  <?php echo $this->pageRequest->getOTPMessage() ?>. Enter it here to proceed</h4>
	</div>
	<input type="hidden" inputmode="numeric" autocomplete="one-time-code" pattern="\d{6}" required />

	<div id="otp" class="col-12" style="max-width: 300px; margin: auto ;<?= $style; ?>">

		<div class="row">
			<div class="col-3 p5"><?php echo CHtml::numberField('number1', '', array('onkeyup' => 'onKeyUpEvent(1, event)', 'onfocus' => 'onFocusEvent(1)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 ootpNumber1 otpNum')) ?></div>
			<div class="col-3 p5"><?php echo CHtml::numberField('number2', '', array('onkeyup' => 'onKeyUpEvent(2, event)', 'onfocus' => 'onFocusEvent(2)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 ootpNumber2 otpNum')) ?></div>
			<div class="col-3 p5"><?php echo CHtml::numberField('number3', '', array('onkeyup' => 'onKeyUpEvent(3, event)', 'onfocus' => 'onFocusEvent(3)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 ootpNumber3 otpNum')) ?></div>
			<div class="col-3 p5"><?php echo CHtml::numberField('number4', '', array('onkeyup' => 'onKeyUpEvent(4, event)', 'onfocus' => 'onFocusEvent(4)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 ootpNumber4 otpNum')) ?></div>
			<div class="correctotp danger col-12 mt5"></div>
		</div>
		<?php
		if (YII_DEBUG && APPLICATION_ENV != 'production' && Yii::app()->params['sendSMS'] == false && ($smsSend == 1))
		{
			echo 'OTP: ' . $verifyotp . '<br>';
		}
		?>
		<span id="counter"></span>
	</div>

	<div class="col-12 text-center mb-2">
		<?php
		if ($smsSend == 0 && ($verificationObj->type == 2 || $phoneverificationObj->type == 2))
		{
			$showResend = "display: none";
			?>
			<b>Sorry can't send OTP to this number,Please try again after some time.</b>
		<?php }
		?>
	</div>
<!--<span id="counter"></span>-->
	<div class="col-12 text-center mb-2">Didn't receive the OTP? <?= $spamMsg ?> 
		<a id="resendotp" style="<?= $showResend; ?>" href="#" onclick="$jsUserLogin.resendOtp('<?php echo $verifyData; ?>');">Resend OTP</a><br><div class="resendotp"></div>
		<!--		<button type="button" class="btn btn-light-secondary pl-2 pr-2 mt-1 mr-2" onclick="return toggleSignin();">Go Back</button>-->
		<button type="submit" class="btn btn-primary pl-3 pr-3 mt-1" disabled>NEXT</button> </div>
<?php 
				$hideSkipLogin = 0;
				$sessSkipLoginCnt = Yii::app()->session['_gz_skip_login_count'];
				$skipLoginContactLimit = json_decode(Config::get('quote.guest'))->contactLimit;
				if($sessSkipLoginCnt > 0 && $sessSkipLoginCnt > $skipLoginContactLimit)
				{
					$hideSkipLogin = 1;
				}
				if($hideSkipLogin!=1){
			?>
			<div class="col-12 text-right skipLoginBtn hide float-right mt15">
				<button onclick="skipLogin();" class="mt10 btn-default border-0 pl20 pr20"><u>SKIP<img src="/images/bx-chevrons-right.svg" alt="img" width="18" height="18"></u></button>
				</div><?}?>
</div>
<div class="otpverified hide">
	<div class="col-12 mt-3 mb-3 text-center">
		<img src="/images/img-2022/check.svg" width="150" alt="">
		<h2 class="merriw weight600 mt-1">OTP verified successfully</h2>
	</div>
</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
	$(document).ready(function ()
	{

		$('#counter').show();
		var validTill = <?= $verificationObj->otpValidTill ?>;
		var lastSend = <?= $verificationObj->otpLastSent ?>;
		var duration = validTill - lastSend;
		$jsUserLogin.countdown(30);

		trackPage("<?= Yii::app()->createUrl("booking/verifyOTP") ?>");

<?php
if ($verifyURL != "")
{
	?>
			setUrl();
<?php
}
?>
if (typeof $skipLogin !== 'undefined') 
{
			if($skipLogin == 0)
			{
				$('.skipLoginBtn').removeClass('hide');
			}
}
	});
	function setUrl()
	{
		var model = {};
		model.url = '<?= $verifyURL ?>';
		$jsUserLogin.model = model;
		$jsUserLogin.initializeUrl();
	}

	function getCodeBoxElement(index)
	{
		return $('form#otpverify-form .ootpNumber' + index)[0];
	}

	function onKeyUpEvent(index, event)
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
		var OTP = $jsUserLogin.getOTP();
		$("#oneTimePassword").val(OTP);

		$("form#otpverify-form BUTTON:submit").prop("disabled", !(OTP.length === 4));
//		if(OTP.length === 4)
//		{
//			$jsUserLogin.validateForm();
//		}

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



	function verifyData(data)
	{
		if (data.success)
		{
			$('.otpverified').removeClass('hide');
			$('.verifyotp').addClass('hide');
			closeLogin(1);
			return;
		}
		let errorMessage = '';
		if (data.errors !== undefined && data.errors !== null && Array.isArray(data.errors))
		{
			errorMessage = data.errors.join("<br>");
		}

		if (errorMessage !== '')
		{
			$('.correctotp').text(errorMessage);
			$('.correctotp').removeClass('success');
			$('.correctotp').addClass('danger');
		}
	}

	function addZeros(n)
	{
		return (n < 10) ? '0' + n : '' + n;
	}

//	function countdown(duration)
//	{
//        
//		var display2 = $('#counter');
//		var timer2 = duration, minutes, seconds;
//		$countDown = setInterval(function ()
//		{
//			minutes = parseInt(timer2 / 60, 10)
//			seconds = parseInt(timer2 % 60, 10);
//			minutes = minutes < 10 ? "0" + minutes : minutes;
//			seconds = seconds < 10 ? "0" + seconds : seconds;
//			display2.text(minutes + ":" + seconds);
//			if (--timer2 < 0)
//			{
//				clearInterval($countDown);
//				$('#resendotp').show();
//				$('#counter').hide();
//			}
//		}, 1000);
//	}


</script>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
