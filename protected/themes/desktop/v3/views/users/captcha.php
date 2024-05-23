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
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'captchaVerify-form',
	'enableClientValidation' => false,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => 'form-horizontal',
		'onsubmit'	 => 'return validateCaptcha(this);'
	),
		));
?>
<div  class="backBtnCaptcha color-black"><img src="/images/bx-arrow-back.svg" alt="img" width="14" height="14" ></div>
<div class="row text-center verifyotp">

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
								'buttonLabel'	 => '<img src="/images/bx-refresh.svg" alt="img" width="24" height="24">',
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
	</div>
	<div class="col-12 text-center mb-2">
		<input type="hidden" name="YII_CSRF_TOKEN" value= "<?= Yii::app()->request->csrfToken; ?>">  
		<button type="submit" class="btn btn-primary pl-3 pr-3 mt-1" >NEXT</button> </div>
</div>


<?php $this->endWidget(); ?>

<script>
    function validateCaptcha(form)
    {
        var form = $("form#captchaVerify-form");
        // alert(form);
        $.ajax({
            "type": "POST",
//			"dataType": "html",
//            "async":true,
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('users/captchaskiplogin')) ?>",
            "data": $(form).serialize(),
            "beforeSend": function ()
            {
                blockForm(form);
            },
            "complete": function ()
            {
                unBlockForm(form);
            },
            "success": function (data2)
            {
				    debugger;  
					var data = "";
					data = JSON.parse(data2);
                    isJSON = true;
                    if (!data.success)
                    {
                        $(".errorCaptcha").show();
                        $(".errorCaptcha").text(data.error);
                    }
					else
					{
								$skipLogin = 1;
								bootbox.hideAll();
								checkCatQuotes();
					}
              
              
            },
            error: function (xhr, ajaxOptions, thrownError)
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
	
	$(".backBtnCaptcha").click(function(e)
	{
		    e.preventDefault();
			$(".signInBox").show();
			$("#googleBlock").show();
			$("#loginForm").show();
			$(".showPhone").hide();
			$(".otpBox").hide();
			$("#PasswordForm").hide();
			$('#captchaSkipLogin').hide();
			
	});
</script>