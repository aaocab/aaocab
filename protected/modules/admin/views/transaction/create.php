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
$recipientList	 = [2 => 'Driver', 3 => 'Vendor'];
$paymentType	 = PaymentType::model()->getList(false);
$bankTransType	 = VehicleTypes::model()->getJSON(AccountTransDetails::model()->getbankTransTypeList());
$status			 = ['0' => 'Open', '1' => 'Success', '2' => 'Failure'];
$ptpJson		 = VehicleTypes::model()->getJSON($paymentType);
$statusJson		 = VehicleTypes::model()->getJSON($status);
$modeJson		 = VehicleTypes::model()->getJSON(AccountTransDetails::model()->getModeList1());
$recipientJson	 = VehicleTypes::model()->getJSON($recipientList);

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
		$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'transaction-form', 'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
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
					<div class="form-group">

						<label  class="col-xs-12 col-sm-4 control-label ">Booking Id : </label>
						<div class="col-xs-12 col-sm-7 "> 
							<?= $form->textFieldGroup($model, 'booking_id', array('label' => '', 'widgetOptions' => array('htmlOptions' => $ronly))) ?>
						</div>
					</div>        

					<div class="form-group">
						<label  class="col-xs-12 col-sm-4 control-label">Payment Type : </label>
						<div class="form-group col-xs-12 col-sm-7 "> 
							<div class="input-group col-xs-12">
								<?php
								//$this->widget('booster.widgets.TbSelect2', array(
//									'model' => $model,
//									'attribute' => 'apg_ptp_id',
//									'val' => $model->apg_ptp_id,
//									'asDropDownList' => FALSE,
//									'options' => array('data' => new CJavaScriptExpression($ptpJson)),
//									'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Payment Type')
//								));
								?>
								<?php
								$paymenttypearr	 = AccountLedger::getPaymentLedgers();
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'apg_ledger_id',
									'val'			 => $model->apg_ledger_id,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($paymenttypearr)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Payment Type')
								));
								?>


								<span class="has-error"><?= $form->error($model, 'apg_ledger_id'); ?></span>
							</div>
						</div>
					</div>	



					<div class="col-xs-12 col-sm-6 ">
						<div class="form-group">
							<label for="Trnsactions_trans_mode">Mode</label>
							<div class="input-group col-xs-12">
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'apg_mode',
									'val'			 => $model->apg_mode,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($modeJson)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Mode')
								));
								?>
								<span class="has-error"><? echo $form->error($model, 'apg_mode'); ?></span>
							</div>
						</div>
					</div>

					<!--	<div class="col-xs-12 col-sm-6 " id='transid'> 
					<? //=$form->textFieldGroup($model, 'bank_trans_id', array()) ?>
						</div>-->
					<div class="col-xs-12 col-sm-6 "> 
						<?= $form->textFieldGroup($model, 'apg_code', array()) ?>
					</div>

					<div class="col-xs-12 col-sm-6 ">  
						<?= $form->numberFieldGroup($model, 'apg_amount', array('label' => 'Payment Amount', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Payment Amount", 'min' => 0]))) ?>
					</div>
					<div class="col-xs-12 col-sm-6 "> 
						<?= $form->textAreaGroup($model, 'apg_remarks', array('label' => 'Description', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Enter Description"]))) ?>
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

<div>    
</div>     
<script type="text/javascript">
    $(document).ready(function () {
        $('.oth').hide();

    });

    $('#Transactions_trans_booking_code_id').change(function () {
        var $bcode = $('#Transactions_trans_booking_code_id').val();
        if ($bcode.length < 10) {
            alert('Booking Id is must');
            $('#Transactions_trans_booking_code_id').focus();
        } else
        {
            getAmountNCheckBCode();
        }
    });
    $('#Transactions_trans_mode1').change(function () {
        getAmountNCheckBCode();
    });
    function getAmountNCheckBCode()
    {
        var $bcode = $('#Transactions_trans_booking_code_id').val();
        var href2 = '<?= Yii::app()->createUrl("booking/checkcode"); ?>';
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "json",
            "data": {"bcode": $bcode},
            "success": function (data) {
                if (data.success) {
                    $('#Transactions_trans_booking_id').val(data.bkgid);
                    $("#Transactions_trans_amount").attr('min', 0);
                    if ($('#Transactions_trans_mode1').val() == 2) {
                        $("#Transactions_trans_amount").attr('max', data.amount_due);
                        $("#Transactions_trans_amount").val(data.amount_due).change();
                    } else if ($('#Transactions_trans_mode1').val() == 1) {
                        $("#Transactions_trans_amount").attr('max', data.amount_advance);
                        $("#Transactions_trans_amount").val(data.amount_paid).change();
                    } else {
                        $("#Transactions_trans_amount").attr('max', data.amount_net);
                        $("#Transactions_trans_amount").val(data.amount_net).change();
                    }
                } else {
                    alert('Booking code is not valid');
                }
            }


        });
    }


    $('#Transactions_trans_ptp1').change(function () {
        var transptp = $('#Transactions_trans_ptp1').val();
        if (transptp == 1) {
            $('.oth').hide();
            $('.cash').show();
        }
        if (transptp == 2) {
            $('.oth').hide();
            $('.bank').show();
        }
    });
    $('#Transactions_bank_trans_type').change(function () {
        var btranstype = $('#Transactions_bank_trans_type').val();

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
    $('#Transactions_cash_received_by').change(function () {
        var rec = $('#Transactions_cash_received_by').val();
        if (rec == 1) {
            $('.bank').show();
        }
    });
</script>
