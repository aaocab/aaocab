<style>
	#yw0{
		width: 150px;
		height: 60px;
		border: #f7f7f6 1px solid;
		padding: 10px;
		text-align: center;
		margin: auto;
		margin-bottom: 10px;
	}
</style>
<?php
/** @var CActiveForm $form */
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'captchaVerify-form',
	'action'				 => '/users/captchaVerify',
	'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => 'js:function(form,data,hasError){
				if(!hasError){
					validateCaptcha(this);
				}
			}'
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => 'form-horizontal',
	),
		));

//$verificationObj = $this->pageRequest->contactVerifications[0];
?>
	<div class="form-group mt30 n"><a href="#" class="p10 pl0 color-black"><i class='bx bx-arrow-back' onclick="return toggleSignin();"></i></a></div>
<div class="row text-center verifyotp">
	<input type="hidden" id="verifyURL" name="verifyURL" value="">
	<input type="hidden" id="verifyData" name="verifyData" value="<?php echo $verifyData; ?>"> 
	<input type="hidden" id="pid" name="signup" value="<?= $signup ?>">
 

	<div id="otp" class="col-12">
		<div id ='captchaBlock'>
			<div class="row form-group">
				<?php
				if (CCaptcha::checkRequirements())
				{
					?>  
					<div class="col-12 col-sm-6 col-md-12">
						<?php
						echo '<br />' . $form->labelEx($userModel, 'verifyCode');
						?> 
						<div>
							<?php
							$this->widget('CCaptcha', array(
								'clickableImage' => true,
								'buttonLabel'	 => '<i class="bx bx-refresh ml5 font-24 weight600"></i>',
								'captchaAction'	 => "site/captcha"));
							echo $form->error($userModel, 'verifyCode');
							echo '<br />' . $form->textField($userModel, 'verifyCode');
							?>
                               <div class="font-12 mt5 errorCaptcha color-red" style="display:none"></div>
							<div class="font-12 mt5">Please enter the letters as they are shown in the image above.<br/>
								Letters are not case-sensitive.
							</div>
						</div>
					</div>
				<?php } ?>
			</div>

		</div><div class="correctotp danger col-12 mt5"></div>
<!--		<span id="counter"></span>-->
	</div>
	<div class="col-12 text-center mb-2">
<!--		<button type="button" class="btn btn-light-secondary pl-2 pr-2 mt-1 mr-2" onclick="return toggleSignin();">Go Back</button>-->
		<button type="submit" class="btn btn-primary pl-3 pr-3 mt-1" >NEXT</button> </div>
</div>


<?php $this->endWidget(); ?>

<script>
	function validateCaptcha(form)
	{
		var form = $("form#captchaVerify-form");
        <? // $verifyURL ?>
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('users/captchaVerifyNew')) ?>",
			"data": $(form).serialize(),
			"beforeSend": function()
			{
				blockForm(form);
			},
			"complete": function()
			{
				unBlockForm(form);
			},
			"success": function(data2)
			{ 
				$("html,body").animate({scrollTop: 180}, "slow");
				var data = "";
				var isJSON = false;
				try
				{
					data = JSON.parse(data2);
					isJSON = true;
					if(!data.success)
					{
						$(".errorCaptcha").show();
						$(".errorCaptcha").text(data.errors[0]);
					}
				}
				catch (e)
				{

				}
				if (!isJSON)
				{
					//$("#SignUpOtpWithPersonalDetails").hide();
					$(".SignUpOtpWithPersonalDetails").html(data2);
					$(".SignUpOtpWithPersonalDetails").show();
									
					//$(".otpBox").html(data2);
					//$(".otpBox").show();
					$("#captchaVerifyForm").hide();
					
					trackPage("<?= CHtml::normalizeUrl($this->getURL('users/signupOTPNew')) ?>");
				}
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
</script>