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
<?php
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
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
                        getWalletDetails();					
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
					<?= $form->hiddenField($model, 'adt_trans_ref_id') ?>
                    <div class="form-group">
                        <label  class="col-xs-12 col-sm-4 control-label ">Reference Id : </label>
                        <div class="col-xs-12 col-sm-7 "> 
							<?= $form->textFieldGroup($model, 'refrence_id', array('label' => '', 'widgetOptions' => array())) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-xs-12 col-sm-4 control-label">Payment Type : </label>
                        <div class="form-group col-xs-12 col-sm-7 "> 
                            <div class="input-group col-xs-12">
								<?php
								
								$paymenttypearr = AccountLedger::getRefundLedgerIds();
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
                    <div class="col-xs-12 col-sm-6 ">  
						<?= $form->numberFieldGroup($model, 'adt_amount', array('label' => 'Add Amount', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Add Amount", 'min' => 0]))) ?>
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



