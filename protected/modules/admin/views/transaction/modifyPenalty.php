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
						alert(data1.message);
						
						}
						else{
						refndbox.modal("hide");
						alert(data1.error);	
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
                    <div class="col-xs-12 col-sm-6 ">  
						<?= $form->numberFieldGroup($model, 'adt_amount', array('label' => 'Penalty Amount', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Penalty Amount", 'min' => 0]))) ?>
                    </div>
                     <div class="col-xs-12 col-sm-6 "> 
						<?= $form->textAreaGroup($model, 'adt_remarks', array('label' => 'Modify Reason', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Enter Modify Reason"]))) ?>
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



