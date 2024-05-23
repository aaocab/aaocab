<div class="row" id="bankdetailbody">
    <div class="  pb10" style="float: none; margin: auto">


		<div class="panel panel-default mb0">
			<div class="h4 text-center"> Bank Details for customer</div>

			<?php
			$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'transaction-form', 'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error',
					'afterValidate'		 => 'js:function(form,data,hasError){
					if(!hasError){
					$.ajax({
					"type":"POST",
					"dataType":"json",
				   "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/savecustbankdetails', ['bkg_id' => $bkgId])) . '",
                         "data":form.serialize(),
						"success":function(data1){
						if(data1.success){	 
						bankbox.modal("hide");						
						}
						else{
						settings=form.data(\'settings\');
						data2 = data1.error;
						$.each (settings.attributes, function (i) {
						$.fn.yiiactiveform.updateInput (settings.attributes[i], data2, form);
						});
						$.fn.yiiactiveform.updateSummary(form, data2);
						}
						},
						});
						}
                    }'
				),
				// Please note: When you enable ajax validation, make sure the corresponding
				// controller action is handling ajax validation correctly.
				// See class documentation of CActiveForm for details on this,
				// you need to use the performAjaxValidation()-method described there.
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
//				'action'				 => Yii::app()->createUrl('admin/booking/savecustbankdetails', ['bkg_id' => $bkgId]),
				'htmlOptions'			 => array(
					'class' => 'form-horizontal',
				),
			));
			?>

			<div class="panel-body">
				<input type="hidden" name="bkg_id" value="<?php echo $bkgId ?>">
				<input type="hidden" name="YII_CSRF_TOKEN"  >  
				<input type="hidden" name="showbankDetails" value="1" >  

				<div class="row mb10">
					<div class="col-xs-6  "><label class="form-l " for="bankname">Bank Name</label></div>
					<div class="col-xs-6  ">
						<input type="text" id="bankname" name="Bank[name]" value="<?php echo $model->name ?>"  required="required" placeholder="Bank Name" class="form-control border-radius">

					</div>
				</div>
				<div class="row mb10">
					<div class="col-xs-6  "><label class="form-l " for="branchName">Branch Name</label></div>
					<div class="col-xs-6  ">
						<input type="text" id="branchName" name="Bank[branchName]" value="<?php echo $model->branchName ?>"  required="required" placeholder="Branch Name" class="form-control border-radius">

					</div>
				</div>

				<div class="row mb10">
					<div class="col-xs-6  "><label class="form-l " for="accountNumber">Account Number</label></div>
					<div class="col-xs-6  ">
						<input type="text" id="accountNumber" name="Bank[accountNumber]" value="<?php echo $model->accountNumber ?>"  required="required" placeholder="Account Number" class="form-control border-radius">

					</div>
				</div>
				<div class="row mb10">
					<div class="col-xs-6  "><label class="form-l " for="accountType">Account Type</label></div>
					<div class="col-xs-6  ">
						<select id="accountType" name="Bank[accountType]" class="   form-control border-radius js-example-basic-single" required="required">
							<option style="border:1px" class=" form-control border-radius " value="">&lt; SELECT ONE &gt; </option>
							<option style="border:1px" class=" form-control border-radius " value="0"  <?php echo ($model->accountType == '0') ? 'selected' : ''; ?>>Savings </option>
							<option style="border:1px" class=" form-control border-radius " value="1"  <?php echo ($model->accountType == '1') ? 'selected' : ''; ?>>Current </option> 
						</select>
					</div>
				</div>
				<div class="row mb10">
					<div class="col-xs-6  "><label class="form-l " for="ifsc">IFSC</label></div>
					<div class="col-xs-6  ">
						<input type="text" id="ifsc" name="Bank[ifsc]" value="<?php echo $model->ifsc ?>"  required="required" placeholder="IFSC" class="form-control border-radius">

					</div>
				</div>
				<div class="row mb10">
					<div class="col-xs-6  "><label class="form-l " for="Pay_beneficiaryName">Beneficiary Name</label></div>
					<div class="col-xs-6  ">
						<input type="text" id="beneficiaryName" name="Bank[beneficiaryName]" value="<?php echo $model->beneficiaryName ?>"  required="required" placeholder="Beneficiary Name" class="form-control border-radius">

					</div>
				</div>



			</div>


			<div class="panel-footer" style="text-align: center">
				<div class="col-xs-12 text-center pb10">
					<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>
                </div>	
			</div>




			<?php $this->endWidget(); ?>
		</div>

    </div>
	<?php
	$script	 = "$(document).ready(function(){
	$('input[name=YII_CSRF_TOKEN]').val('" . $this->renderDynamicDelay('Filter::getToken') . "');
});
$('#modal_title').text('$pagetitle');
";
	Yii::app()->clientScript->registerScript('updateYiiCSRF', $script, CClientScript::POS_END);
	?>
	<script>


		$('#modal_title').text('<?php echo $pagetitle ?>');

	</script>

</div>