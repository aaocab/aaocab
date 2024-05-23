<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/userLogin.js?v=' . $version);

$showResend	 = "display: none";
?>

<?php
/** @var BookingTemp $model */
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'otpverify-form',
	'enableClientValidation' => true,
	'clientOptions'			 => [
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	],
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'action'				 => $this->getURL(['booking/VerifyOTPPartnerCancel']),
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off',
	),
		));
?>

<div class="row text-center verifyotp">
	<input type="hidden" id="verifyData" name="verifyData" value="<?php echo $verifyData; ?>">
	<input type="hidden" name="rdata" value="<?= $pageRequest->getEncrptedData() ?>">
	<input type="hidden" id="oneTimePassword" name="otp" value="">
	<input type="hidden" id="bk_id" name="bk_id" value="<?= $bkgId ?>">
	<input type="hidden" id="bkreason" name="bkreason" value="<?= $bkreasonId ?>">
	<input type="hidden" id="bkreasontext" name="bkreasontext" value="<?= $reasonText ?>">
	<input type="hidden" id="bkpnlogin" name="bkpnlogin" value="<?= $isBkpn ?>">
	
	<div class="col-12 mt-1" style="<?//= $style; ?>">
		<h4 class="merriw">We have sent a one-time password (OTP) to Phone: <?php echo $phoneno ?>. Enter it here to proceed</h4>
	</div>
	<input type="hidden" inputmode="numeric" autocomplete="one-time-code" pattern="\d{6}" required />

	<div id="otp" class="col-12" style="max-width: 300px; margin: auto ;<?//= $style; ?>">

		<div class="row">
			<div class="col-3 p5"><?php echo CHtml::numberField('number1', '', array('onkeyup' => 'onKeyUpEvent(1, event)', 'onfocus' => 'onFocusEvent(1)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 ootpNumber1 otpNum')) ?></div>
			<div class="col-3 p5"><?php echo CHtml::numberField('number2', '', array('onkeyup' => 'onKeyUpEvent(2, event)', 'onfocus' => 'onFocusEvent(2)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 ootpNumber2 otpNum')) ?></div>
			<div class="col-3 p5"><?php echo CHtml::numberField('number3', '', array('onkeyup' => 'onKeyUpEvent(3, event)', 'onfocus' => 'onFocusEvent(3)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 ootpNumber3 otpNum')) ?></div>
			<div class="col-3 p5"><?php echo CHtml::numberField('number4', '', array('onkeyup' => 'onKeyUpEvent(4, event)', 'onfocus' => 'onFocusEvent(4)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 ootpNumber4 otpNum')) ?></div>
			<div class="correctotp danger col-12 mt5"></div>
		</div>
		<?php
		//if (YII_DEBUG && APPLICATION_ENV != 'production' && Yii::app()->params['sendSMS'] == false && ($smsSend == 1))
		//{
			//echo 'OTP: ' . $verifyotp . '<br>';
		//}
		?>
		<span id="counter"></span>
	</div>


	<div class="col-12 text-center mb-2">Didn't receive the OTP? <?//= $spamMsg ?> 
		<a id="resendotp" style="<?= $showResend; ?>" href="#" onclick="resendOtp('<?php echo $verifyData; ?>');">Resend OTP</a><br><div class="resendotp"></div>
		<!--		<button type="button" class="btn btn-light-secondary pl-2 pr-2 mt-1 mr-2" onclick="return toggleSignin();">Go Back</button>-->
		<button type="submit" class="btn btn-primary pl-3 pr-3 mt-1" disabled>PROCEED</button> </div>

</div>
<div class="otpverified hide">
	<div class="col-12 mt-3 mb-3 text-center">
		<img src="/images/img-2022/check.svg" width="150" alt="">
		<h2 class="merriw weight600 mt-1">OTP verified successfully</h2>
	</div>
</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
	$jsUserLogin = new userLogin();
	
	$(document).ready(function ()
	{

		$('#counter').show();
		$jsUserLogin.countdown(30);

		$("form#otpverify-form").unbind("submit").on("submit", function()
		{
			verifyOtp();
			return false;
		}); 

	});
	

	function getCodeBoxElement(index)
	{
		return $('form#otpverify-form .ootpNumber' + index)[0];
	}

	function onKeyUpEvent(index, event)
	{	//debugger;
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

	function countdown(duration)
	{
		var display2 = $('#counter');
		var timer2 = duration, minutes, seconds;
		$countDown = setInterval(function ()
		{
			minutes = parseInt(timer2 / 60, 10)
			seconds = parseInt(timer2 % 60, 10);
			minutes = minutes < 10 ? "0" + minutes : minutes;
			seconds = seconds < 10 ? "0" + seconds : seconds;
			display2.text(minutes + ":" + seconds);
			if (--timer2 < 0)
			{
				clearInterval($countDown);
				$('#resendotp').show();
				$('#counter').hide();
			}
		}, 1000);
	}

	
	function verifyOtp()
	{	
		//debugger;
		var form = $("form#otpverify-form");
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/VerifyOTPPartnerCancel')) ?>",
			"data": form.serialize(),
			"success": function(data2)
			{	//debugger;

				$("html,body").animate({scrollTop: 180}, "slow");
				var data = "";
				var isJSON = false;
				try
				{
					data = JSON.parse(data2);
					isJSON = true;
				} catch (e)
				{

				}
				if (!isJSON)
				{
					$('#cancelBookingModal').removeClass('fade');
					$('#cancelBookingModal').css('display', 'block');
					$('#cancelBookingModelContent').html(data2);
					$('#cancelBookingModal').modal('show');
				} else
				{
					$jsUserLogin.verifyData(data);
				}
			},
			error: function(xhr, ajaxOptions, thrownError)
			{
				
			}
		});
		return false;
	}
	
	
	function resendOtp(verifyData)
	{
		//debugger;
		var objuserLogin = this;
		var bkgid = $('#bk_id').val();
		$.ajax({
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/ReSendOTPPartnerCancel')) ?>",
			"type": "GET",
			"dataType": "json",
			"data": {'verifyData': verifyData, 'bkgid': bkgid},
			"success": function (data)
			{	//debugger
				let errorMessage = '';
				if (data.errors !== undefined && data.errors !== null && Array.isArray(data.errors))
				{
					errorMessage = data.errors.join("<br>");
				}

				if (errorMessage !== '')
				{
					$('.correctotp').text(errorMessage);
				}
				if (data.success)
				{

					$('.correctotp').text('OTP sent successfully');
					$('.correctotp').removeClass('danger');
					$('.correctotp').addClass('success');
					$("INPUT[name=rdata]").val(data.data.rdata);
					$('#counter').show();
					countdown(30);
					$('#resendotp').hide();
				}
			}
		});
	};
	
</script>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
