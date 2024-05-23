<?php
/** @var BookingTemp $model */
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'otpverified-form',
	'enableClientValidation' => true,
	'clientOptions'			 => [
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	],
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'action'				 => $this->getURL(['booking/canbooking']),
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off',
	),
		));

?>
<div class="otpverified">
	<div class="col-12 mt-3 mb-3 text-center">
<!--		<input type="hidden" id="returnUrl" name="returnUrl" value="1">-->
		<input type="hidden" id="bk_id" name="bk_id" value="<?= $bkgId ?>">
		<input type="hidden" id="bkreason" name="bkreason" value="<?= $bkreasonId ?>">
		<input type="hidden" id="bkreasontext" name="bkreasontext" value="<?= $reasonText ?>">
		<input type="hidden" id="bkpnlogin" name="bkpnlogin" value="<?= $isBkpn ?>">
		<input type="hidden" id="partnerVerified" name="partnerOtpVerified" value="<?= $success ?>">
		<img src="/images/img-2022/check.svg" width="150" alt="">
		<h2 class="merriw weight600 mt-1 font-20">OTP verified successfully</h2>
		<p class="mb10">Redirecting in <span id ="timer">5</span> second</p>
		<div class="btn btn-success text-center" onclick="closeVerifyBox()">OK</div>
	</div>

</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
	$(document).ready(function ()
	{
		//debugger;
		$('.otpverified').removeClass('hide');
		$('.verifyotp').addClass('hide');
		clearInterval(counter);

		setTimeout(closeVerifyBox, 5000);
		initialMillis = Date.now();
		counter = setInterval(timer, 1000);
	});


	var cnt = 0;
	function closeVerifyBox()
	{
		//debugger;
		//closeLoginFinal();
		$('#cancelBookingModal').addClass('fade');
		$('#cancelBookingModal').css('display', 'none');
		$('#cancelBookingModal').modal('hide');
		if(cnt == 0)
		{
			canBookingOtpVerified();
			return false;
		}
		 
	}

	var initial = 5000;
	var count = initial;
	var counter; //10 will  run it every 100th of a second
	var initialMillis;
	function timer()
	{
		//debugger;
		if (count <= 0)
		{
			clearInterval(counter);
			return;
		}
		var current = Date.now();
		count = count - (current - initialMillis);
		initialMillis = current;
		displayCount(count);
	}

	function displayCount(count)
	{
		//debugger;
		var res = count / 1000;
		if (document.getElementById("timer") !== null)
		{
			document.getElementById("timer").innerHTML = res.toPrecision(1);
		}
	}

	displayCount(initial);

	function canBookingOtpVerified()
	{
		//debugger;
		var form = $("form#otpverified-form");
		cnt = cnt + 1;
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/canbooking')) ?>",
			"data": form.serialize(),
			"success": function (data2)
			{
				//debugger;
				$("html,body").animate({scrollTop: 180}, "slow");
				var data = "";
				var isJSON = false;
				try
				{
					data = JSON.parse(data2);
					isJSON = true;
					if (data.success)
					{	//debugger;
						window.location.reload();
						//$('.partnercanbooking').addClass('hide');
						//$('.actionmenu').addClass('hide');
						//	toastr['info']('OTP verified successfully', {
						//	closeButton: true,
						//	tapToDismiss: false,
						//	timeout: 500000
						//	});
						return false;
					}
					else
					{
						toastr['error']('Error in booking cancellation!', {
							closeButton: true,
							tapToDismiss: false,
							timeout: 500000
						});
						return false;
					}
				} catch (e)
				{

				}
				if (!isJSON)
				{
					//$("form#otpverify-form").parent().html(data2);
					$('#cancelBookingModal').removeClass('fade');
					$('#cancelBookingModal').css('display', 'block');
					$('#cancelBookingModelContent').html(data2);
					$('#cancelBookingModal').modal('show');
				} else
				{
					$jsUserLogin.verifyData(data);
				}
			},
			error: function (xhr, ajaxOptions, thrownError)
			{
				//debugger;
				//alert('555555555555555');

			}
		});
		return false;
	}

</script>