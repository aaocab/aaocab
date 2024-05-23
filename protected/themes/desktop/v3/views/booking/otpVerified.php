<div class="otpverified">
	<div class="col-12 mt-3 mb-3 text-center">
		<img src="/images/img-2022/check.svg" width="150" alt="">
		<h2 class="merriw weight600 mt-1">OTP verified successfully</h2>
	</div>
</div>
<?php
/** @var CActiveForm $form */
$form = $this->beginWidget('CActiveForm', array(
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
		'class' => 'form-horizontal',
	),
		));
?>
<input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
<?php $this->endWidget(); ?>
<script type="text/javascript">
	$(document).ready(function()
	{
		$('.otpverified').removeClass('hide');
		$('.verifyotp').addClass('hide');

		setTimeout(function()
		{
			proceedSignin();
		}, 500);
	});

	function proceedSignin()
	{
		var form = $("form#otpverified");
		let url = window.sessionStorage.getItem('returnURL');
		let callback = window.sessionStorage.getItem('callback');
		if(url == null && callback != '')
		{
			let data1 = '';
			eval(callback);
			return;
		}
		if (url == undefined || url == '')
		{
			url = "/";
		}
		let rData = window.sessionStorage.getItem('rdata');
		if(rData == undefined || rData == '')
		{
			location.href = url;
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
</script>