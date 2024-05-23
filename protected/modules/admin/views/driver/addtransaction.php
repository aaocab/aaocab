

<style>
	span.stars, span.stars span {
		display: block;
		background: url(http://localhost:92/images/stars.png) 0 -16px repeat-x;
		width: 80px;
		height: 16px;
	}

	span.stars span {
		background-position: 0 0;
	}
</style>
<?php
$ptpJson			 = VehicleTypes::model()->getJSON(PaymentType::model()->getList(false, false));
$modeJson			 = VehicleTypes::model()->getJSON(PaymentGateway::model()->getModeList());
$bankTransType		 = VehicleTypes::model()->getJSON(PaymentGateway::model()->getbankTransTypeList());
$operatorJson		 = VehicleTypes::model()->getJSON(AccountTransDetails::model()->getOperatorList());
$gozoPaid			 = AccountLedger::getGozoPiadLedgerIds();
$gozoReceiver		 = AccountLedger::getGozoReceiverLedgerIds();
?>

<div id="vendorContent">
				<?php
				$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'addamount-form', 'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
					),
					// Please note: When you enable ajax validation, make sure the corresponding
					// controller action is handling ajax validation correctly.
					// See class documentation of CActiveForm for details on this,
					// you need to use the performAjaxValidation()-method described there.
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class' => 'form-horizontal'
					),
				));
				/* @var $form TbActiveForm */
				?>


                <div class="row mt30">
<!--                    <div class="col-sm-3 table-responsive">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <td><b>Accounts Payable</b></td>
                                <td><i class="fa fa-inr"></i>
								
								
								</td>
                            </tr>
                            <tr>
                                <td><b>Accounts Receivable</b></td>
                                <td><i class="fa fa-inr"></i>
								
								
								
								</td>
                            </tr>
                            <tr>
                                <td><b>Security Deposit</b></td>
                                <td><i class="fa fa-inr"></i>
									
									
								</td>
                            </tr>
                        </table>
                    </div>-->
                    <div class="col-sm-9">
                        <div class="panel panel-default">
                            <div class="panel-body">

								<?= $form->radioButtonListGroup($model, 'apg_type', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Gozo Paid', 2 => 'Gozo receiver')), 'inline' => true)) ?>
								
								<?php
								if ($model->apg_type == 1)
								{
									$gozoPaidTextStyle = 'display:block;';
								}
								else if ($model->apg_type == 2)
								{
									$gozoReceiverTextStyle = 'display:block;';
								}
								else
								{
									$gozoPaidTextStyle		 = 'display:none;';
									$gozoReceiverTextStyle	 = 'display:none;';
								}
								?>
								
                                <div class="row" >
                                    <div class="col-sm-4  " id="gozoPaid" style="<?= $gozoPaidTextStyle; ?>;">
										<?php
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'apg_ledger_id_1',
											'val'			 => $model->apg_ledger_id_1,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($gozoPaid)),
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Payment Type')
										));
										?>
										</br></br>
                                    </div>

                                   <div class="col-sm-4 " id="gozoReceiver" style="<?= $gozoReceiverTextStyle; ?>;">
										<?php
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'apg_ledger_id_2',
											'val'			 => $model->apg_ledger_id_2,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($gozoReceiver)),
											'htmlOptions'	 => array('onchange' => "showgozotrip(this)", 'style' => 'width:100%', 'placeholder' => 'Payment Type')
										));
										?>
										<?php
										$gozoTripTextStyle	 = 'display:none;';
										?>     
                                        </br></br>
								
<!--                                        <div id="row" >
											<div class="col-sm-12">
												<div id="gozoTripid" style="display:none;">
													<?= $form->textFieldGroup($model, 'trip_id', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Trip ID', 'class' => 'form-control', 'title' => '', 'style' => 'min-height:23px')))) ?>
												</div>
											</div>


										</div> -->
                                    </div>
                                 <div class="col-sm-4 pl50">
										<?= $form->textFieldGroup($model, 'apg_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('style' => 'width:220px', 'placeholder' => 'Enter Amount', 'class' => 'form-control','required'=>'required')))) ?>

                                    </div>
                                </div>


                                <div id="row">
                                    <div class="col-sm-8">
										<?= $form->textAreaGroup($model, 'apg_remarks', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'add notes here on payment received, payment sent or any communication with vendor (promise of payment, phone call or complaints). A manual entry will not appear on invoice unless an amount is entered in the manual entry.(+=credit to gozo, -=debit to gozo)', 'class' => 'form-control', 'title' => 'add notes here on payment received, payment sent or any communication with vendor (promise of payment, phone call or complaints). A manual entry will not appear on invoice unless an amount is entered in the manual entry.(+=credit to gozo, -=debit to gozo)', 'style' => 'min-height:83px', 'required'=>'required')))) ?>
                                    </div>
                                 

                                </div>
                                <div id="row">
                                    <div class="row col-sm-12"> 
                                        <div class="col-sm-4">
											<?=
											$form->datePickerGroup($model, 'apg_date', array('label'			 => '',
												'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => date(),
														'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('placeholder'	 => 'Transaction Date','required'=>'required',
														'class'			 => 'input-group border-gray full-width')),
												'prepend'		 => '<i class="fa fa-calendar"></i>'));
											?>
                                        </div>


                                    </div>
                                </div>
                              

                                <div id="row">
                                    <div class="row col-sm-12"> 
                                     
                                        <div class="col-sm-4">
											<?= CHtml::submitButton('Save Manual Entry', array('id' => 'saveEntry', 'class' => 'btn btn-success mt5')); ?>
                                        </div>
                                        <div class="col-sm-4">&nbsp;</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
				<?php $this->endWidget(); ?>
              
            </div>
			
<script>
    $("#saveEntry").click(function () {
        var subVal = $("#saveEntry").val();
        var accVal = $("#PaymentGateway_apg_type_0").val();
		if(accVal==''){
			alert("fgdgdg");
		}
    });

    $("#PaymentGateway_apg_type_0").click(function () {
        var accVal = $("#PaymentGateway_apg_type_0").val();
        checkGroupType(1);
    });

    $("#PaymentGateway_apg_type_1").click(function () {
        var accVal = $("#PaymentGateway_apg_type_1").val();
        checkGroupType(2);
    });

    $("#PaymentGateway_apg_trans_type").click(function () {
        var transValue = $("#PaymentGateway_apg_trans_type").val();
        checkTransactionType(transValue);
    }); 

    

    function checkGroupType(type) {
        if (type == 1) {
            $("#gozoPaid").show();
            $("#gozoReceiver").hide();
        } else if (type == 2) {
            $("#gozoReceiver").show();
            $("#gozoPaid").hide();
        }

    }

   

    $(document).ready(function () {
    });

</script>
			
