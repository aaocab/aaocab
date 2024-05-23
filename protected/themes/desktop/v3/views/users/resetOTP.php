

<!--		<div class="text-center mt40 n"><img src="/images/img-2022/check.svg" alt="Reset" width="100"></div>-->
	

<?php


$ref = "resetPassword";
/** @var CActiveForm $form */
$form		 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'resetOTP-form',
	'action'				 => '/users/verifyOTP',
	'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => 'form-horizontal',
		'onsubmit'	 => 'return $jsUserLogin.validateResetPasswordOTP(this);'
	),
		));
$showResend	 = "display: none";
$spamMsg	 = "";
//foreach ($this->pageRequest->contactVerifications as $contactVerification)
//{
//	if ($contactVerification->type == Stub\common\ContactVerification::TYPE_EMAIL)
//	{
//		$spamMsg = " Check your spam folder.";
//	}
//}
$smsSend;


//$verificationObj		 = $this->pageRequest->contactVerifications[0];
//$phoneverificationObj	 = $this->pageRequest->contactVerifications[1];
//
//if ($verificationObj != null && $verificationObj->type == 2)
//{
//	$smsSend = $verificationObj->isSendSMS;
//}
//if ($phoneverificationObj != null && $phoneverificationObj->type == 2)
//{
//	$smsSend = $phoneverificationObj->isSendSMS;
//}
?>
<div class="form-group back-btn-2"><a href="#" class="p10 pl0 color-black"><img src="/images/bx-arrow-back.svg" alt="img" width="14" height="14" onclick="return toggleOTP();"></a></div>
<div class="row text-center verifyotp">
    <input type="hidden" id="verifyData" name="verifyData" value="<?php echo $verifyData; ?>">
    <input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
	<input type="hidden" id="pid" name="signup" value="<?= $signup ?>">
	<input type="hidden" id="oneTimePasswordReset" name="otp" value="">
	<input type="hidden" id="step" class="step" value="3">
	<input type="hidden" id="ref" name="ref" value="<?= $ref ?>">
    <input type="hidden" id="otpValidTill" name="otpValidTill" value="">
     <input type="hidden" id="otpLastSent" name="otpLastSent" value="">
     <input type="hidden" name="YII_CSRF_TOKEN" value= "<?= Yii::app()->request->csrfToken ?>">  
	<?php
	if ($smsSend == 0 && ($verificationObj->type == 2 || $phoneverificationObj->type == 2))
	{
		$style = "display:none;";
	}
	?>
    	<div id="resetPasswordText" class=" col-12 mt-1 mb5 text-center mt20 font-16"></div>
        <div id="resetPasswordTextOR" style="display:none;" class=" col-12 mt-1 mb5 text-center mt20 font-16">OR</div>
    
    
	<div class="col-12 mt-1" style="<?= $style; ?>">
		<h4 class="merriw">Put one-time password (OTP) send to <span class='typeID'></span>. Enter it here to proceed</h4>
	</div>
	<input type="hidden" inputmode="numeric" autocomplete="one-time-code" pattern="\d{6}" required />

	<div id="otp" class="col-12" style="max-width: 300px; margin: auto ;<?= $style; ?>">

		<div class="row">
			<div class="col-3 p5"><?php echo CHtml::numberField('number11', '', array('onkeyup' => 'onKeyUpEvent(1, event)', 'onfocus' => 'onFocusEvent(1)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 ootpNumberReset1 otpNum1')) ?></div>
			<div class="col-3 p5"><?php echo CHtml::numberField('number21', '', array('onkeyup' => 'onKeyUpEvent(2, event)', 'onfocus' => 'onFocusEvent(2)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 ootpNumberReset2 otpNum1')) ?></div>
			<div class="col-3 p5"><?php echo CHtml::numberField('number31', '', array('onkeyup' => 'onKeyUpEvent(3, event)', 'onfocus' => 'onFocusEvent(3)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 ootpNumberReset3 otpNum1')) ?></div>
			<div class="col-3 p5"><?php echo CHtml::numberField('number41', '', array('onkeyup' => 'onKeyUpEvent(4, event)', 'onfocus' => 'onFocusEvent(4)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 ootpNumberReset4 otpNum1')) ?></div>
			<div class="correctotp danger col-12 mt5"></div>
		</div>
	
<!--		<span id="counter"></span>-->
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
<div class="col-12 text-center weight600 mt-1 color-red otpError "></div>
	<div class="col-12 text-center mb-2">
        <span id="counterFP"></span>
        	<a id="resendotpFP" style="<?= $showResend; ?>" href="#" onclick="$jsUserLogin.resendOtpForForgotPassword();">Resend OTP</a><br><div class="resendotpFP"></div>
		<button type="submit" class="btn btn-primary pl-3 pr-3 mt-1" disabled>NEXT</button> </div>
	<div class="col-12">
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
<div class="float-right mt5 skipLoginBtn hide">
		<button onclick="skipLogin();" class="mt10 btn-default border-0 pl20 pr20"><u>SKIP<img src="/images/bx-chevrons-right.svg" alt="img" width="18" height="18"></u></button>
	</div>
				<?}?>
</div>
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

	//	$('#counterFP').show();
//		var validTill = $("#otpValidTill").val();
//		var lastSend = $("#otpLastSent").val();
//		var duration = validTill - lastSend;
//		$jsUserLogin.countdownFP(duration);

		trackPage("<?= Yii::app()->createUrl("booking/verifyOTP") ?>");

<?php
if ($verifyURL != "")
{
	?>
			setUrl();
<?php }
?>

	});
	function setUrl()
	{
		var model = {};
		model.url = '<?= $verifyURL ?>';
		$jsUserLogin.model = model;
		$jsUserLogin.initializeUrl();
	}

	function getCodeBoxElementResetOTP(index)
	{
     
		return $('form#resetOTP-form .ootpNumberReset' + index)[0];
	}

	function onKeyUpEvent(index, event)
	{
       
		const eventCode = event.which || event.keyCode;
		if (getCodeBoxElementResetOTP(index).value.length > 1)
		{
			getCodeBoxElementResetOTP(index).value = getCodeBoxElementResetOTP(index).value.slice(0, 1);
		}
		if (getCodeBoxElementResetOTP(index).value.length == 1)
		{
			if (index !== 4)
			{
				getCodeBoxElementResetOTP(index + 1).focus();
			} else
			{
				getCodeBoxElementResetOTP(index).blur();
			}
		}
		if (eventCode === 8 && index !== 1)
		{
			getCodeBoxElementResetOTP(index - 1).focus();
		}
		var OTP = $jsUserLogin.getResetOTP();
		$("#oneTimePasswordReset").val(OTP);
		$("form#resetOTP-form BUTTON:submit").prop("disabled", !(OTP.length === 4));


	}
	function onFocusEvent(index)
	{
		for (item = 1; item < index; item++)
		{
			const currentElement = getCodeBoxElementResetOTP(item);
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

	function countdownFP(duration)
	{
		var display2 = $('#counterFP');
		var timer2 = duration, minutes, seconds;
		$countDownFP = setInterval(function ()
		{
			minutes = parseInt(timer2 / 60, 10);
			seconds = parseInt(timer2 % 60, 10);
			minutes = minutes < 10 ? "0" + minutes : minutes;
			seconds = seconds < 10 ? "0" + seconds : seconds;
			display2.text(minutes + ":" + seconds);
			if (--timer2 < 0)
			{
				clearInterval($countDownFP);
				$('#resendotpFP').show();
				$('#counterFP').hide();
			}
		}, 1000);
	}

    function toggleOTP()
    {
$("#PasswordForm").show();
$("#ResetPasswordOTPForm").hide();
    }
</script>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
