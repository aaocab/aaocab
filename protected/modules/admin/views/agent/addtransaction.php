<style type="text/css">
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
$paymentType	 = PaymentType::model()->getList(false);
$bankTransType	 = VehicleTypes::model()->getJSON(AccountTransDetails::model()->getbankTransTypeList());
$status			 = ['0' => 'Open', '1' => 'Success', '2' => 'Failure'];
$ptpJson		 = VehicleTypes::model()->getJSON($paymentType);
$statusJson		 = VehicleTypes::model()->getJSON($status);
$modeJson		 = VehicleTypes::model()->getJSON(PaymentGateway::model()->getModeList());


//json_encode($paymentType);

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
			'id'					 => 'agent-transaction-form', 'enableClientValidation' => true,
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
                    "beforeSend": function () {
                        ajaxindicatorstart("");
                    },
                    "complete": function () {  
                        ajaxindicatorstop();
                    },
                    "success":function(data1){
                        if(data1.success){                       
                        location.href=data1.url;
                            return false;
                        } 
                        else{                      
                        var errors = data1.errors;                                           
                        settings=form.data(\'settings\');
                         $.each (settings.attributes, function (i) {                            
                            $.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
                          });
                          $.fn.yiiactiveform.updateSummary(form, errors);
                        } 
                    },
                    error: function(xhr, status, error){                     
                       var x= confirm("Network Error Occurred. Do you want to retry?");
                       if(x){
                                $("#edit-booking-form").submit();
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

					<?php echo CHtml::errorSummary($model); ?>
					<?= $form->hiddenField($model, 'agt_agent_id') ?>

                    <div class="form-group">
                        <label  class="col-xs-12 col-sm-4 control-label">Payment Type : </label>
                        <div class="form-group col-xs-12 col-sm-7 "> 
                            <div class="input-group col-xs-12">
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'trans_ptp',
									'val'			 => $model->trans_ptp,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($ptpJson)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Payment Type')
								));
								?><span class="has-error"><? echo $form->error($model, 'trans_ptp'); ?></span>
                            </div>
                        </div>
                    </div>	

                    <div class="form-group bank oth">
                        <label  class="col-xs-12 col-sm-4 control-label ">Bank Transaction Type : </label>
                        <div class="form-group col-xs-12 col-sm-7 "> 
                            <div class="input-group col-xs-12">
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'bank_trans_type',
									'val'			 => $model->bank_trans_type,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($bankTransType)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Bank Transaction Type')
								));
								?>
                                <span class="has-error"><? echo $form->error($model, 'bank_trans_type'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 chq oth">
						<?= $form->numberFieldGroup($model, 'bank_chq_no', array('label' => 'Cheque Number', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Cheque Number"]))) ?>                      
                    </div>
                    <div class="col-xs-12 col-sm-6 neft chq bcash neft bank oth"> 
						<?= $form->textFieldGroup($model, 'bank_name', array()) ?>
                    </div>
                    <div class="col-xs-12 col-sm-6 neft bank oth"> 
						<?= $form->textFieldGroup($model, 'bank_ifsc', array()) ?>
                    </div>

                    <div class="col-xs-12 col-sm-6 neft chq bcash oth"> 
						<?= $form->textFieldGroup($model, 'bank_branch', array()) ?>
                    </div>
                    <div class="col-xs-12 col-sm-6 chq oth">
						<?=
						$form->datePickerGroup($model, 'bank_chq_dated', array('label'			 => 'Cheque Dated',
							'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => date(),
									'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('placeholder'	 => 'Cheque Dated',
									'class'			 => 'input-group border-gray full-width')),
							'prepend'		 => '<i class="fa fa-calendar"></i>'));
						?>
                    </div>


                    <div class="col-xs-12 col-sm-6 ">
                        <div class="form-group">
                            <label for="Trnsactions_trans_mode">Mode</label>
                            <div class="input-group col-xs-12">
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'agt_trans_mode',
									'val'			 => $model->agt_trans_mode,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($modeJson)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Mode')
								));
								?>
                                <span class="has-error"><? echo $form->error($model, 'agt_trans_mode'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 " id='transid'> 
						<?= $form->textFieldGroup($model, 'bank_trans_id', array()) ?>
                    </div>


                    <div class="col-xs-12 col-sm-6 ">  
						<?= $form->numberFieldGroup($model, 'trans_amount', array('label' => 'Payment Amount', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Payment Amount", 'min' => 0]))) ?>
                    </div>
                    <div class="col-xs-12 col-sm-6 "> 
						<?= $form->textAreaGroup($model, 'trans_desc', array('label' => 'Description', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Enter Description"]))) ?>
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
    $(document).ready(function () {
        $('.oth').hide();
    });


    $('#AgentTransactions_trans_ptp').change(function () {
        var transptp = $('#AgentTransactions_trans_ptp').val();
        if (transptp == 1) {
            $('.oth').hide();
            $('.cash').show();
        }
        if (transptp == 2) {
            $('.oth').hide();
            $('.bank').show();
        }
    });
    $('#AgentTransactions_bank_trans_type').change(function () {
        var btranstype = $('#AgentTransactions_bank_trans_type').val();

        if (btranstype == 1) {
            $('.neft').hide();
            $('.chq').hide();
            $('.bcash').show();
        }
        if (btranstype == 2) {
            $('.neft').hide();
            $('.bcash').hide();
            $('.chq').show();
        }
        if (btranstype == 3) {
            $('.chq').hide();
            $('.bcash').hide();
            $('.neft').show();
        }
    });
    $('#AgentTransactions_cash_received_by').change(function () {
        var rec = $('#AgentTransactions_cash_received_by').val();
        if (rec == 1) {
            $('.bank').show();
        }
    });
</script>
