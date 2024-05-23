
<?php
$isNewUser			 = UserLog::getloggedInCount(UserInfo::getUserId());
$isPasswordEmailSend = Users::model()->findByPk(UserInfo::getUserId())->usr_changepassword;
/** @var CActiveForm $form */
$form				 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'otpverified',
	'action'				 => '/booking/otpVerify',
	'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class' => 'form-horizontal'
	),
		));
?>
<div class="otpverified">
	<div class="col-12 mt-3 mb-3 text-center">
		<input type="hidden" id="ref" name="ref" value="<?= $ref ?>">
		<input type="hidden" id="returnUrl" name="returnUrl" value="<?= $returnUrl ?>">
		<?php
		if ($isPasswordEmailSend == 1)
		{
			?>
			<p class="text-center">Email has been sent to registered account with your user credentials.</p>
			<?php
		}
		?>


		<?php
		if ($isNewUser == 1)
		{
			?>
			<p class="text-center"><span class="coin-text">Great! You have got<img src="/images/img-2022/gozo_coin.svg?v=0.2" alt="Gozo Coin" width="14"> <?= UserCredits::getUserCoin(UserInfo::getUserId()) ?> gozocoins as signup bonus </span></p>

		<? } ?>
		<img src="/images/img-2022/check.svg" width="150" alt="">
		<h2 class="merriw weight600 mt-1 font-20">OTP verified successfully</h2>
		<p class="mb10">Redirecting in <span id ="timer">5</span> second</p>
		<div class="btn btn-success text-center" onclick="closeVerifyBox()">OK</div>



	</div>

</div>

<input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
<?php $this->endWidget(); ?>
<script type="text/javascript">
	$(document).ready(function ()
	{
		$('.otpverified').removeClass('hide');
		$('.verifyotp').addClass('hide');

	 
//		if (($('#returnUrl').val()) == '' && returnUrl == '') {
//			closeLogin(1);
//		}
		if ($('#ref').val() != "vendorAttach") {
			closeLogin(1);
		}
		clearInterval(counter);

		setTimeout(closeVerifyBox, 5000);
		initialMillis = Date.now();
		counter = setInterval(timer, 1000);
	});

	function proceedSignin()
	{

		var form = $("form#otpverified");
		let url = window.sessionStorage.returnURL;
		if (url == undefined || url == '')
		{
			location.href = '/book-cab';
			return;
		}

		form.prop("action", url);
		form.find("INPUT[name=rdata]").val(window.sessionStorage.getItem('rdata'));
		block_ele = $('.otpverified');

		$(block_ele).block({
			message: '<div class="loader"></div>',
			overlayCSS: {
				backgroundColor: "#FFF",
				opacity: 0.8,
				cursor: 'wait'
			},
			css: {
				border: 0,
				padding: 0,
				backgroundColor: 'transparent'
			}
		});
		form.submit();
	}

	function closeVerifyBox()
	{
		if ($('#ref').val() == "vendorAttach") {

			//var returnUrl = '<?//= $returnUrl ?>';
			location.href = "/vendorAttach";

		}

 
		closeLoginFinal();
	}

	var initial = 5000;
	var count = initial;
	var counter; //10 will  run it every 100th of a second
	var initialMillis;
	function timer()
	{
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
		var res = count / 1000;
		if (document.getElementById("timer") !== null)
		{
			document.getElementById("timer").innerHTML = res.toPrecision(1);
		}
	}


	displayCount(initial);




</script>