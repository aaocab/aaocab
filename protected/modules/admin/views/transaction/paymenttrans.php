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
    .checkbox-inline{
        margin-left: 0;
    }
</style>
<?
$paymentType = PaymentType::model()->getList(false, false);
if ($creditVal > 0)
{
	$paymentType = PaymentType::model()->getList(false, true);
}
unset($paymentType[PaymentType::TYPE_PAYNIMO]);
if ($agentBooking)
{
	$plist			 = PaymentType::model()->getList();
	$agtCorpCredit	 = PaymentType::TYPE_AGENT_CORP_CREDIT;
	$paymentType	 += [$agtCorpCredit => $plist[$agtCorpCredit]];
}
//$ptpJson = VehicleTypes::model()->getJSON($paymentType);
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
if ($model->apg_booking_id > 0)
{
	$bModel = Booking::model()->findByPk($model->apg_booking_id);
	if ($bModel->bkg_agent_id > 0)
	{
		unset($paymentType[PaymentType::TYPE_JOURNAL]);
	}
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
						tranbox.modal("hide");						
						}
						else{
							$(".paymenttransaction").css("pointer-events","auto");
                                                if(data1.msg!=null && data1.msg!=""){
                                                  alert(data1.msg);
                                                }else{
//						        settings=form.data(\'settings\');
//                                                        data2 = data1.error;
//                                                        $.each (settings.attributes, function (i) {
//                                                        $.fn.yiiactiveform.updateInput (settings.attributes[i], data2, form);
//                                                        });
//                                                        $.fn.yiiactiveform.updateSummary(form, data2);
                                                   alert("Error Occurred");
                                                }
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
					<?= CHtml::errorSummary($model); ?>
					<?= $form->hiddenField($model, 'apg_trans_ref_id') ?>
                    <input type="hidden" name="creditValTrans" id="creditValTrans" value="<?= $creditVal ?>">
                    <div class="form-group">
                        <label  class="col-xs-12 col-sm-4 control-label ">Booking Id : </label>
                        <div class="col-xs-12 col-sm-7 "> 

							<?= $form->textFieldGroup($model, 'booking_id', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['readonly' => 'readonly']))) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-xs-12 col-sm-4 control-label">Payment Type : </label>
                        <div class="form-group col-xs-12 col-sm-7 ">
                            <div class="input-group col-xs-12">
								<?php
								if ($agentBooking)
								{
									$paymenttypearr = AccountLedger::getPaymentLedgers(false, true);
								}
								else
								{
									$paymenttypearr = AccountLedger::getPaymentLedgers(false, false, true,true);
								}
								$paymenttypearr = str_replace('{"id":"46","text":"PayNimo"},', '', $paymenttypearr);
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'apg_ledger_id',
									'val'			 => $model->apg_ledger_id,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($paymenttypearr)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Payment Type', 'id' => 'PaymentGateway_apg_ledger_id')
								));
								?><?= $form->error($model, 'apg_ledger_id'); ?>
                            </div>
                        </div>
                    </div>		
					<div class="col-xs-12  text-center " id="walletBalance" style="display: none"> 
						<?php echo 'Wallet Balance : ' . $walletBalance ?>
                    </div>	

                    <div class="col-xs-12 col-sm-6 hide"> 
						<?= $form->textFieldGroup($model, 'apg_code', array()) ?>
                    </div>
                    <div class="col-xs-12 col-sm-6 oth" id='trnid'> 
						<?= $form->textFieldGroup($model, 'apg_txn_id', array()) ?>
                    </div>
                    <div class="col-xs-12 col-sm-6 ">  
						<?= $form->numberFieldGroup($model, 'apg_amount', array('label' => 'Transaction Amount', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Transaction Amount", 'min' => 0]))) ?>
                    </div>
                    <div class="col-xs-12 col-sm-6 "> 
						<?= $form->textAreaGroup($model, 'apg_remarks', array('label' => 'Description', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Enter Description"]))) ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-center pb10">
					<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30 paymenttransaction')); ?>
                </div>
            </div>

        </div>
		<?php $this->endWidget(); ?>

    </div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$('#trnid').hide();
		$('#PaymentGateway_apg_ptp_id_0').prop('checked', true);
	});
	$('input[type="submit"]').click(function () {
		$('.paymenttransaction').css('pointer-events', 'none');
	});

	$('#PaymentGateway_booking_id').change(function () {
		var $bcode = $('#PaymentGateway_booking_id').val();
		if ($bcode.length < 10) {
			alert('Booking Id is must');
			$('#PaymentGateway_booking_id').focus();
		} else
		{
			getAmountNCheckBCode();
		}
	});
	$('#PaymentGateway_apg_mode').change(function () {
		getAmountNCheckBCode();
	});
	function getAmountNCheckBCode()
	{
		var $bcode = $('#PaymentGateway_booking_id').val();
		var href2 = '<?= Yii::app()->createUrl("booking/checkcode"); ?>';
		$.ajax({
			"url": href2,
			"type": "GET",
			"dataType": "json",
			"data": {"bcode": $bcode},
			"success": function (data) {
				if (data.success) {
					$('#PaymentGateway_booking_id').val(data.bkgid);
					$("#PaymentGateway_apg_amount").attr('min', 0);
					if ($('#PaymentGateway_apg_mode').val() == 2) {
						$("#PaymentGateway_apg_amount").attr('max', data.amount_due);
						$("#PaymentGateway_apg_amount").val(data.amount_due).change();
					} else if ($('#PaymentGateway_apg_mode').val() == 1) {
						$("#PaymentGateway_apg_amount").attr('max', data.amount_advance);
						$("#PaymentGateway_apg_amount").val(data.amount_paid).change();
					} else {
						$("#PaymentGateway_apg_amount").attr('max', data.amount_net);
						$("#PaymentGateway_apg_amount").val(data.amount_net).change();
					}
				} else {
					alert('Booking code is not valid');
				}
			}


		});
	}


	$('#PaymentGateway_apg_ptp1').change(function () {

		var transptp = $('#PaymentGateway_apg_ptp_id').val();
		transptp = $('input:radio[name="PaymentGateway[apg_ptp_id]"]:checked').val();
		$('#PaymentGateway_apg_amount').removeAttr('max');
		$('.oth').hide();
		if (transptp == 1) {
			$('.cash').show();
		}
		if (transptp == 2) {
			$('.bank').show();
		}
		if (transptp == 6) {
			$('#trnid').show();
		}
		if (transptp == 5) {
			var creditVal = $('#creditValTrans').val();
			$('#PaymentGateway_apg_amount').val(creditVal);
			$('#PaymentGateway_apg_amount').attr('max', creditVal);
		}
	});


	$('#PaymentGateway_apg_ledger_id').change(function () {
		var btranstype = $('#PaymentGateway_apg_ledger_id').val();
		$('#walletBalance').hide();
		if (btranstype == 47) {
			$('#walletBalance').show();
		}

	});

	$('#PaymentGateway_bank_apg_type').change(function () {
		var btranstype = $('#PaymentGateway_bank_apg_type').val();
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
	$('#PaymentGateway_cash_received_by').change(function () {
		var rec = $('#PaymentGateway_cash_received_by').val();
		if (rec == 1) {
			$('.bank').show();
		}

	});


</script>


