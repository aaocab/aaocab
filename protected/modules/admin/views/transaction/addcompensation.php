<style>
    .selectize-input {
        min-width: 0px !important; 
        width: 30% !important; 
    }
    .form-group {
        margin-bottom: 7px;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }

</style>
<?
if (!Yii::app()->request->isAjaxRequest)
{
	$panelCss	 = "col-sm-9 col-md-7 col-lg-6 ";
	$panelClass	 = " panel-grape";
}
else
{
	$panelHeading = "display: none";
}
?>
<div class="row">
    <div class="<?= $panelCss ?>" style="float: none; margin: auto">

		<?php
		$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'transaction-form', 'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
					if(!hasError){
					$.ajax({
					"type":"POST",
					"dataType":"json",
					"url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
						"data":form.serialize(),
						"success":function(data1){
						if(data1.success){						
						refndbox.modal("hide");						
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
			'htmlOptions'			 => array(
				'class' => 'form-horizontal',
			),
		));
		/* @var $form TbActiveForm */
		?>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
					<?= $form->errorSummary($model); ?>
					<?php echo CHtml::errorSummary($model); ?>
                    <div class="form-group">
                        <label  class="col-xs-12 col-sm-4 control-label ">Booking Id : </label>
                        <div class="col-xs-12 col-sm-7 "> 
							<?= $form->textFieldGroup($model, 'bkg_booking_id', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['readonly' => 'readonly']))) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-xs-12 col-sm-4 control-label">Payment Type : </label>
                        <div class="form-group col-xs-12 col-sm-7 "> 
                            <div class="input-group col-xs-12">
								<?php
								$paymenttypearr	 = AccountLedger::getCompensationLedgerIds();
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'adt_ledger_id',
									'val'			 => $model->adt_ledger_id,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($paymenttypearr)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Payment Type', 'id' => 'AccountTransDetails_adt_ledger_id')
								));
								?><?= $form->error($model, 'adt_ledger_id'); ?>
                            </div>
                        </div>

                    </div>
					<div  class="col-xs-12 col-sm-6 hide creditmaxuse">  
						<label  class="col-xs-12 col-sm-6 control-label">Credit max use : </label>
						<div class="form-group col-xs-12">
							<div class="input-group col-xs-12">
								<?php
								$maxStr			 = UserCredits::model()->getMaxUseTypes();
								$maxUseVal		 = Filter::getJSON($maxStr);
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'ucrMaxuseType',
									'val'			 => $model->ucrMaxuseType,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($maxUseVal)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Credit Max Use', 'id' => 'UserCredits_ucr_maxuse_type')
								));
								?><?= $form->error($model, 'ucrMaxuseType'); ?>
							</div>
						</div>
					</div>

					<div  class="col-xs-12 col-sm-6 hide credittype">
						<label  class="col-xs-12 col-sm-5 control-label">Credit type : </label>
						<div class="form-group col-xs-12">
							<div class="input-group col-xs-12">
								<?php
								$creditTypes	 = UserCredits::model()->getCreditTypes();
								$types			 = Filter::getJSON($creditTypes);
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'ucrCreditType',
									'val'			 => $model->ucrCreditType,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($types)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Credit Type', 'id' => 'UserCredits_ucr_credit_type')
								));
								?><?= $form->error($model, 'ucrCreditType'); ?>
							</div>
						</div>
					</div>

                    <div class="col-xs-12 col-sm-6 ">  
						<?= $form->numberFieldGroup($model, 'adt_amount', array('label' => 'Compensation Amount', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Compensation Amount", 'min' => 0]))) ?>
                    </div>
                    <div class="col-xs-12 col-sm-6 "> 
						<?= $form->textAreaGroup($model, 'adt_remarks', array('label' => 'Description', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Enter Description"]))) ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-center pb10">
					<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>
                </div>
            </div>
        </div>
		<?php $this->endWidget(); ?>
    </div>
</div>

<script type="text/javascript">
	
	
	$('#AccountTransDetails_adt_ledger_id').change(function(){ //debugger;
		var value = $('#AccountTransDetails_adt_ledger_id').val();
		if(value == 36)
		{
			$('.creditmaxuse').removeClass('hide');
			$('.credittype').removeClass('hide');
		}
		else{
			$('.creditmaxuse').addClass('hide');
			$('.credittype').addClass('hide');
		}
	});
</script>



