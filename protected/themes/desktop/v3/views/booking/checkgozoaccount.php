<?php
$model			 = new BookingTemp();
$model->scenario = "checkUser";
$form			 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'checkAccount',
	'enableClientValidation' => true,
	'clientOptions'			 => [
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => 'js:function(form,data,hasError){
				var isChecked = validateUserType();
				if(!isChecked)
				{
					$("form#checkAccount input:submit").prop("disabled", true);
				}
				hasError = hasError || !isChecked;
				if(!hasError){
					var value = $("input.checkUser:checked").val();
					if(value == 1)
					{
						showLogin(function(){
							processStep2();
						});
					}
					else
					{
						processStep2();
					}
				}

			}'
	],
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off',
	),
		));
/* @var $form CActiveForm */
?>
<input type="hidden" name="pageID" value="1" id="pageID">
<input type="hidden" name="rid" value="<?= $rid; ?>" id="rid">
<div class="menuTripType">
	<div class="row justify-center">
		<div class="col-12 text-center style-widget-1"><p class="merriw heading-line">Do you have a Gozo account?</p></div>

		<div class="col-12 col-md-4 col-lg-4 mt-2">
			<?= $form->error($model, "isNewUser") ?>
			<div class="row mb-3 radio-style2">
				<div class="col-12 radio">
					<?= $form->radioButton($model, "isNewUser", ["class" => "checkUser", "value" => 1, 'id' => 'isNewUser_Yes', "uncheckValue" => null]) ?>
					<?= $form->label($model, "isNewUser", ["label" => "Yes", 'for' => 'isNewUser_Yes']) ?>
				</div>
				<div class="col-12 radio">
					<?= $form->radioButton($model, "isNewUser", ["class" => "checkUser", "value" => 2, 'id' => 'isNewUser_NO', "uncheckValue" => null]) ?>
					<?= $form->label($model, "isNewUser", ["label" => "No", 'for' => 'isNewUser_NO']) ?>
				</div>
			</div>
		</div>
		<div class="col-12 text-center pb-3">
			<input type="submit" value="Next" name="yt0" id="btnUserTypeSubmit" class="btn btn-primary pl-5 pr-5" disabled>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function()
	{
		step = <?= $this->pageRequest->step ?>;
		tabURL = "<?= $this->getURL("booking/bookNow1") ?>";
		pageTitle = "aaocab: Book Now";
		tabHead = "";
		toggleStep(step, 1, tabURL, pageTitle, tabHead, false, <?= $this->pageRequest->step ?>);
		$("form#checkAccount input:radio.checkUser").on("change", function()
		{
			var isChecked = validateUserType();
			$("form#checkAccount input:submit").prop("disabled", !isChecked);
		});
		$('#isNewUser_Yes').click();

	});
	function validateUserType()
	{
		var elems = $("form#checkAccount input:radio.checkUser");
		var isChecked = false;
		$.each(elems, function()
		{
			if (this.checked)
			{
				isChecked = true;
			}
		});

		return isChecked;
	}
	function processStep2(){
		location.href = '<?=$this->getURL("booking/bkgType")?>';
	}
</script>
<?php $this->endWidget(); ?>

