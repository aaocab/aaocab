<?php
$version	 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/editAccounts.js?v=' . $version);
?>
<style type="text/css">
    .form-group {
        margin-bottom: 0;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
    .modal-backdrop{
        height: 650px !important;
    }   

    .error{
        color:#ff0000;
    }

    .rounded {
        border:1px solid #ddd;
        border-radius: 10px;
    }

    .bg-warning{
        color: #333333;
    }


    .bordered {
        border:1px solid #ddd;
        min-height: 45px;
        line-height: 1.2;
        text-align: center;
    }

    .form-control{
        border: 1px solid #a5a5a5;
        text-align: center;

    }

    .modal-title{
        text-align: center;
        font-size: 1.5em;
        font-weight: 400;
    }
</style>
<?php
$colclass	 = 'col-xs-4 col-sm-3 col-md-2 col-lg-1 text-right p5';
$colclass1	 = 'col-xs-4 col-sm-3 col-md-2 col-lg-1 bordered p5';
$colclass2	 = 'col-xs-6 col-sm-4 col-md-3 col-lg-1 bordered p5';
$cabModel	 = $model->bkgBcb;
$readonly	 = [];
$cnt		 = count($cabModel->bookings);
if ($cnt > 1)
{
	$readonly = ['readonly' => 'readonly'];
}
?>

<div class="row">
    <div class="col-xs-12 text-center h3 mt0">
        <label for="type" class="control-label">
            <span style="font-weight: normal; font-size: 30px;">Booking Id: <b><?= $model->bkg_booking_id ?></b></span> </label>

    </div>
</div>


<div class="hide">
    <span class="fa-stack fa-lg">
        <i class="fa fa-user fa-stack-2x"></i>
        <i class="fa fa-remove fa-stack-1x fa-spin fa-rotate-90 text-warning mt5 "></i>
        <i class="fa fa-circle-o-notch fa-stack-1x text-warning mt5"></i>
    </span>
</div>




<div class="row">
    <div class="col-xs-12">
        <div style="width: 100%; padding: 3px; border-radius: 5px; -moz-border-radius: 5px; border: 1px #aaa solid;color: #444;">
            <div class="row ">
				<?php
				if ($model->bkg_status != '1' && $model->bkg_status != '15')
				{
					?> 
					<div class = "col-xs-offset-3 col-sm-offset-6 col-sm-6 col-xs-8  text-right" >
						<?php
						if ($model->bkg_agent_id > 0 && $model->bkg_agent_id != 1249)
						{
							?>
							<a class="btn btn-info  btn-sm text-center mr50" id="bkg_acct" onclick="changeCPComm()" title="Add Transaction" style="">Edit Partner Commision</a>
						<? } ?>
						<a class = "btn btn-primary btn-sm text-center mr50" id = "setFlag" onclick = "addRefund()" title = "Add Refund" style = "">Add Refund</a>
						<a class = "btn btn-primary btn-sm text-center mr50" id = "setFlag" onclick = "addCompensation()" title = "Add Compensation" style = "">Add Compensation</a>
						<a class = "btn btn-info btn-sm text-center pr5 " id = "bkg_acct" onclick = "addTransaction()" title = "Add Transaction" style = "">Add Transaction</a>

					</div>
					<?
				}
				?>
			</div>
			<div id="acctpnl">
				<?php
				$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'accounts-form',
					'enableClientValidation' => true,
					//'enableAjaxValidation' => false,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
						'afterValidate'		 => 'js:function(form,data,hasError){
                if(!hasError){				
					if(!checkValidation())
					{
					return false;
					}
					$.ajax({
					"type":"POST",
					"dataType":"json",
					"url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
						"data":form.serialize(),
						"success":function(data1){
							if(data1.success){						 
								acctbox.modal("hide");
								refreshAccountDetails();
							}
							else{
							
								settings=form.data(\'settings\');
								data2 = data1.error;
								alert(data2);
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
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class' => 'form-horizontal'
					),
				));
				?>
				<div class="panel mb0 pb20" >               
					<div class="panel-body panel-body panel-no-padding pb0">
						<?=
						$form->errorSummary($model);
						echo CHtml::errorSummary($model)
						?>
						<div class="row font-bold">
							<div class=" <?= $colclass ?>">
								Current Value
							</div>
							<div class="pt5 <?= $colclass2 ?> bg bg-danger">
								Booking Amount
							</div>
							<div class="pt15 <?= $colclass1 ?> bg bg-danger ">
								Kms Driven
							</div>
							<div class="pt5 <?= $colclass1 ?> bg bg-danger">
								GOZO Amount
							</div>
							<div class="<?= $colclass1 ?> bg bg-danger">
								Vendor Amount
							</div>
							<div class="<?= $colclass1 ?> bg bg-danger">
								Advance Received
							</div>
							<div class="<?= $colclass1 ?> bg bg-danger">
								Vendor Received
							</div>
							<div class="<?= $colclass1 ?> bg bg-danger">
								<?
								if ($model->bkg_agent_id > 0)
								{
									?>
									<?= 'Corporate Coins Used' ?>
									<?
								}
								else
								{
									?>
									<?= 'Gozo Coins Used' ?>
								<? } ?>
							</div>
							<div class="pt15 <?= $colclass1 ?> bg bg-danger">
								Customer Due
							</div>
							<div class="pt15 <?= $colclass1 ?> bg bg-danger">
								GOZO Due
							</div>
							<div class="pt15 <?= $colclass1 ?> bg bg-danger">
								Vendor Due
							</div>
						</div>

						<div class="row">
							<div class="col-xs-offset-1 pt15 <?= $colclass2 ?> text-danger">
								<?= $model->bkgInvoice->bkg_total_amount ?>
							</div>
							<div class="pt15  <?= $colclass1 ?> text-danger">
								<?= $model->bkg_trip_distance ?>
							</div>
							<div class="pt15  <?= $colclass1 ?> text-danger">
								<?= $model->bkgInvoice->bkg_gozo_amount ?>
							</div>
							<div class="pt15  <?= $colclass1 ?> text-danger">
								<?= $model->bkgInvoice->bkg_vendor_amount ?>
							</div>
							<div class="pt15  <?= $colclass1 ?> text-danger">
								<?= $model->bkgInvoice->bkg_advance_amount ?>
							</div>
							<div class="pt15  <?= $colclass1 ?> text-danger">
								<?= $model->bkgInvoice->bkg_vendor_collected ?>
							</div>
							<div class="pt15  <?= $colclass1 ?> text-danger">
								<?
								if ($model->bkg_agent_id > 0)
								{
									?>
									<?= $model->bkgInvoice->bkg_corporate_credit ?>
									<?
								}
								else
								{
									?>
									<?= $model->bkgInvoice->bkg_credits_used ?>
								<? } ?>

							</div>
							<div class="pt15  <?= $colclass1 ?> bg bg-warning">
								<?= ( $model->bkgInvoice->bkg_due_amount != 0) ? -1 * $model->bkgInvoice->bkg_due_amount : 0 ?>
							</div>
							<div class="pt15  <?= $colclass1 ?> bg bg-warning">
								<span id="final_gozo_due_txt"><?= $model->bkgInvoice->bkg_gozo_due ?></span>
							</div>
							<div class="pt15  <?= $colclass1 ?> bg bg-warning">
								<span id="final_vendor_due_txt_old"><?= $model->bkgInvoice->bkg_vendor_due ?></span>
							</div>
						</div>

						<div class="row mt20">
							<div class="p15 <?= $colclass2 ?> bg bg-danger font-bold">
								Base Amount
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_base_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Base Amount', 'readonly' => 'readonly')))) ?>
							</div>
						</div>

						<div class="row mt20">
							<div class="p15 <?= $colclass2 ?> bg bg-success font-bold">
								Discount
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_discount_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Discount',)))) ?>
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model, 'bkg_discount_amount_remark', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Discount Remark',)))) ?>
							</div>
							<div class="<?= $colclass2 ?> text-danger" style="display: none" id="discount_remark_err">
								Please enter remarks
							</div>
						</div>

						<div class="row mt20">
							<div class="p15 <?= $colclass2 ?> bg bg-danger  font-bold">
								Client Refund
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_refund_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Refund', 'readonly' => 'readonly')))) ?>
							</div>
							<div class="<?= $colclass2 ?> hide">
								<?= $form->textFieldGroup($model, 'bkg_refund_amount_remark', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Refund Remark',)))) ?>
							</div>
							<div class="<?= $colclass2 ?> text-danger" style="display: none" id="refund_remark_err">
								Please enter remarks
							</div>
						</div>
						<div class="row mt20">
							<div class="p15 <?= $colclass2 ?> bg bg-success  font-bold">
								Driver Allowance
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_driver_allowance_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Driver Allowance')))) ?>
							</div>
						</div>

						<div class="row mt20">
							<div class="p15 <?= $colclass2 ?> bg bg-success  font-bold">
								Addon Charge
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_addon_charges', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Addon Charge')))) ?>
							</div>
						</div>

						<div class="row">
							<div class="p15 <?= $colclass2 ?> bg bg-success  font-bold">
								Additional Charges
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_additional_charge', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Additional Charge',)))) ?>
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_additional_charge_remark', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Additional Charge Remark',)))) ?>
							</div>
							<div class="<?= $colclass2 ?> text-danger" style="display: none" id="additional_charge_remark_err">
								Please enter remarks
							</div>
						</div>

						<div class="row">
							<div class="p15 <?= $colclass2 ?> bg bg-success  font-bold">
								Collect on delivery
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_convenience_charge', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'COD Charge',)))) ?>
							</div>
						</div>



						<div class="row">
							<div class="p15 <?= $colclass2 ?> bg bg-success  font-bold">
								Extra KM Charge
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_extra_km_charge', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Extra KM Charge',)))) ?>
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_extra_km', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Extra KM',)))) ?>
							</div>
						</div>

						<div class="row">
							<div class="p15 <?= $colclass2 ?> bg bg-success  font-bold">
								Rate Per Extra KM
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_rate_per_km_extra', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Rate Per Extra KM',)))) ?>
							</div>
							<div class="<?= $colclass2 ?> text-danger" style="display: none" id="rate_Per_extra_km_err">
								Please enter extra km charge.
							</div>
						</div>
						
						<div class="row">
							<div class="p15 <?= $colclass2 ?> bg bg-success  font-bold">
								Extra Minutes Charge
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_extra_total_min_charge', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Extra Minutes Charge')))) ?>
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_extra_min', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Extra Minutes')))) ?>
							</div>
						</div>

						<div class="row mt20">
							<div class="p15 <?= $colclass2 ?> bg bg-success font-bold">
								GST
							</div>
							<div class="<?= $colclass2 ?> p15 bg bg-warning" id="service_tax">
								<?= $model->bkgInvoice->bkg_service_tax ?>
							</div>
							<? //= $form->hiddenField($model, 'bkg_service_tax', ['value' => $model->bkg_service_tax])        ?>
						</div>


						<div class="row">
							<div class="p15 <?= $colclass2 ?> bg bg-success  font-bold">
								Toll Tax
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_toll_tax', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Toll Tax',)))) ?>
							</div>
						</div>

						<div class="row">
							<div class="p15 <?= $colclass2 ?> bg bg-success  font-bold">
								State Tax
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_state_tax', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'State Tax',)))) ?>
							</div>
						</div>

						<div class="row">
							<div class="p15 <?= $colclass2 ?> bg bg-success font-bold">
								Airport Charge
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_airport_entry_fee', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Airport Charge',)))) ?>
							</div>
						</div>

						<div class="row">
							<div class="p15 <?= $colclass2 ?> bg bg-success  font-bold">
								Extra State Tax
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_extra_state_tax', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Extra State Tax',)))) ?>
							</div>
						</div>

						<div class="row">
							<div class="p15 <?= $colclass2 ?> bg bg-success  font-bold">
								Extra Toll Tax
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_extra_toll_tax', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Extra Toll Tax',)))) ?>
							</div>
						</div>

						<div class="row">
							<div class="p15 <?= $colclass2 ?> bg bg-success  font-bold">
								Parking Charge
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_parking_charge', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Extra Parking Tax',)))) ?>
							</div>
						</div>


						<div class="row mt20 font-bold">
							<div class="pt15 <?= $colclass ?>">
								Final Value
							</div>
							<div id='totalAmount' class="pt5 <?= $colclass2 ?> bg bg-danger">
								Booking Amount
							</div>
							<div class="pt15 <?= $colclass1 ?> bg bg-success">
								Kms Driven
							</div>
							<div class="pt5 <?= $colclass1 ?> bg bg-success">
								GOZO Amount
							</div>
							<? $vndAmtClass = ($cnt > 1 ) ? 'bg-danger' : 'bg-success'; ?>
							<div class="p5 <?= $colclass1 ?> bg <?= $vndAmtClass ?>">
								Vendor Amount
							</div>
							<div class="p5 <?= $colclass1 ?> bg bg-danger">
								Advance Received
							</div>
							<div class="p5 <?= $colclass1 ?> bg bg-success">
								Vendor Received
							</div>
							<div class="<?= $colclass1 ?> bg bg-danger">
								<?php
								if ($model->bkg_agent_id > 0)
								{
									?>
									<?= 'Corporate Coins Used' ?>
									<?
								}
								else
								{
									?>
									<?= 'Gozo Coins Used' ?>
								<? } ?>
							</div>
							<div class="pt15 <?= $colclass1 ?> bg bg-danger">
								Customer Due
							</div>
							<div class="pt15 <?= $colclass1 ?> bg bg-danger">
								GOZO Due
							</div>
							<div class="pt15 <?= $colclass1 ?> bg bg-danger">
								Vendor Due
							</div>
						</div>
						<div class="row">
							<div class="col-xs-offset-1 <?= $colclass2 ?> ">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_total_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '', 'readonly' => 'readonly')))) ?>
							</div>
							<div class="<?= $colclass1 ?>">
								<?= $form->textFieldGroup($model, 'bkg_trip_distance', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '',)))) ?>
							</div>
							<div class="<?= $colclass1 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_gozo_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '', 'readonly' => 'readonly')))) ?>
							</div>
							<div class="<?= $colclass1 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_vendor_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '',) + $readonly))) ?>
							</div>
							<div class="<?= $colclass1 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_advance_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '', 'readonly' => 'readonly')))) ?>
							</div>
							<div class="<?= $colclass1 ?>">
								<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_vendor_collected', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '',)))) ?>
							</div>
							<div class="<?= $colclass1 ?>">
								<?
								if ($model->bkg_agent_id > 0)
								{
									?>
									<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_corporate_credit', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '', 'readonly' => 'readonly')))) ?>
									<?
								}
								else
								{
									?>
									<?= $form->textFieldGroup($model->bkgInvoice, 'bkg_credits_used', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '', 'readonly' => 'readonly')))) ?>
								<? } ?>
							</div>
							<div class="pt15 <?= $colclass1 ?>  bg bg-warning" id="final_amount_due">
								<span id="final_amount_due_txt"><?= ( $model->bkgInvoice->bkg_due_amount != 0) ? -1 * $model->bkgInvoice->bkg_due_amount : 0 ?></span>
							</div>
							<div class="pt15 <?= $colclass1 ?>  bg bg-warning" id='final_gozo_due'>
								<span id="final_gozo_due_txt"><?= $model->bkgInvoice->bkg_gozo_due ?></span>
							</div>
							<div class="pt15 <?= $colclass1 ?> bg bg-warning " id='final_vendor_due'>
								<span id="final_vendor_due_txt"><?= $model->bkgInvoice->bkg_vendor_due ?></span>
							</div>
						</div>

						<div class="row mt20 font-bold">
							<div class="<?= $colclass ?>">
								Change
							</div>
							<div class="pt15 <?= $colclass2 ?> bg bg-warning" id="bkg_amount_diff">
								0
							</div>
							<div class="pt15 <?= $colclass1 ?> bg bg-warning" id="bkg_trip_distance_diff">
								0
							</div>
							<div class="pt15 <?= $colclass1 ?> bg bg-warning" id="bkg_gozo_amount_diff">
								0
							</div>
							<div class="pt15 <?= $colclass1 ?> bg bg-warning" id="bkg_vendor_amount_diff">
								0
							</div>
							<div class="pt15 <?= $colclass1 ?> bg bg-warning" id="bkg_advance_amount_diff">
								0
							</div>
							<div class="pt15 <?= $colclass1 ?> bg bg-warning" id="bkg_vendor_collected_diff">
								0
							</div>
<!--   <div class="pt15 <?= $colclass1 ?> bg bg-warning" id="bkg_credits_used_diff">  0 </div>-->
						</div>
						<div class="row">
							<div class="<?= $colclass ?> font-bold">
								Remarks
							</div>
							<div class="<?= $colclass2 ?>">
								<?= $form->textFieldGroup($model, 'bkg_total_amount_remark', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '',)))) ?>
							</div>
							<div class="<?= $colclass1 ?>">
								<?= $form->textFieldGroup($model, 'bkg_trip_distance_remark', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '',)))) ?>
							</div>
							<div class="<?= $colclass1 ?>">
								<?= $form->textFieldGroup($model, 'bkg_gozo_amount_remark', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '',)))) ?>
							</div>
							<div class="<?= $colclass1 ?>">
								<?= $form->textFieldGroup($model, 'bkg_vendor_amount_remark', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '',)))) ?>
							</div>
							<div class="<?= $colclass1 ?> ">
								<?= $form->textFieldGroup($model, 'bkg_advance_amount_remark', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '',)))) ?>
							</div>
							<div class="<?= $colclass1 ?>">
								<?= $form->textFieldGroup($model, 'bkg_vendor_collected_remark', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '',)))) ?>
							</div>
<!--                                                        <div class="<?= $colclass1 ?>">
							<?= $form->textFieldGroup($model, 'bkg_credits_used_remark', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '',)))) ?>
							</div>-->

							<?php
							if ($model->bkg_status == 2)
							{
								$userArr = array('User');
							}
							else if ($model->bkg_status == 3)
							{
								$userArr = array('User', 'Vendor');
							}
							else if (in_array($model->bkg_status, [5, 6, 7]))
							{
								$userArr = array('User', 'Vendor', 'Driver');
							}
							?>    
							<div class="col-xs-12">
								<?=
								$form->checkboxListGroup($model, 'chk_user_msg', array(
									'widgetOptions'	 => array(
										'data' => $userArr,
									),
									'inline'		 => true,
										)
								);
								?>
							</div>
						</div>
					</div>				
					<div class="row">
						<div class="col-xs-12 text-center mt20">
							<input type="submit" value="Save" class="btn btn-primary font-bold">
						</div>
					</div>
				</div>
				<?php $this->endWidget(); ?>
			</div>

		</div>
	</div>
</div>

<script type="text/javascript">
	bkgbase = parseInt(<?= $model->bkgInvoice->bkg_base_amount ?>);
	var $oldrfnamt = parseInt(<?= $model->bkgInvoice->bkg_refund_amount ?>);
	var acctbox;
	var $totBkgAmt = 0;
	var $taxRate = parseInt(<?= ($model->bkgInvoice->bkg_service_tax_rate == 0) ? 1 :  $model->bkgInvoice->bkg_service_tax_rate?>);
	var extraRatePerKM = parseFloat(<?= $model->bkgInvoice->bkg_rate_per_km_extra ?>);
	var extraRatePerMin = parseFloat(<?= $model->bkgInvoice->bkg_extra_per_min_charge ?>);
	var bkAccounts = new bookingAccounts();
	var bkAccountsSaved = new bookingAccounts();
	$(document).ready(function ()
	{
		bkAccounts.init($taxRate, extraRatePerKM, extraRatePerMin);
		bkAccountsSaved.init($taxRate, extraRatePerKM, extraRatePerMin);
		bkAccountsSaved.calculateDue();
		calculateDueAmounts();
	});
	$('#BookingInvoice_bkg_base_amount').change(function () {
		calculateDueAmounts();
	});

	$('#BookingInvoice_bkg_trip_distance').change(function () {
		// calculateSTax();
		olddist = bkAccountsSaved.kmDriven;
		newdist = bkAccounts.getKMDriven();
		var res = newdist - olddist;
		var resSign = '';
		if (res < 0) {
			resSign = res;
		} else {
			resSign = '+' + res;
		}
		$('#bkg_trip_distance_diff').text(resSign).change;
		if ($('#bkg_trip_distance_diff').text().trim() != 0 && $('#Booking_bkg_trip_distance_remark').val() == '') {
			$('#Booking_bkg_trip_distance_remark_em_').text('');
			$('#Booking_bkg_trip_distance_remark_em_').show();
			$('#Booking_bkg_trip_distance_remark_em_').text('Please enter remarks');
		}
	});

	$('#BookingInvoice_bkg_total_amount').change(function () {
		calculateDueAmounts();
		var oldTotal = bkAccountsSaved.totalAmount;
		var newTotal = bkAccounts.totalAmount;
		var diff = newTotal - oldTotal;
		$('#bkg_amount_diff').text(diff).change;

		if ($('#bkg_amount_diff').text() != '' && $('#bkg_amount_diff').text() != '0' && $('#Booking_bkg_total_amount_remark').val() == '') {
			$('#Booking_bkg_total_amount_remark_em_').text('');
			$('#Booking_bkg_total_amount_remark_em_').show();
			$('#Booking_bkg_total_amount_remark_em_').text('Please enter remarks');
		}

	});
	$('#BookingInvoice_bkg_gozo_amount').change(function () {
		oldamt = bkAccountsSaved.gozoAmount;
		newamt = parseInt(bkAccounts.getGozoAmount());
		res = newamt - oldamt;
		resSign = '';
		if (res < 0) {
			resSign = res;
		} else {
			resSign = '+' + res;
		}
		$('#bkg_gozo_amount_diff').html(resSign).change;
		if ($('#bkg_gozo_amount_diff').text() != '' && $('#Booking_bkg_gozo_amount_remark').val() == '') {
			$('#Booking_bkg_gozo_amount_remark_em_').text('');
			$('#Booking_bkg_gozo_amount_remark_em_').show();
			$('#Booking_bkg_gozo_amount_remark_em_').text('Please enter remarks');
		}
		calculateDueAmounts();
	});

	$('#BookingInvoice_bkg_vendor_amount').change(function () {
		oldamt = bkAccountsSaved.vendorAmount;
		newamt = parseInt(bkAccounts.getVendorAmount());
		res = newamt - oldamt;
		resSign = '';
		if (res < 0) {
			resSign = res;
		} else {
			resSign = '+' + res;
		}
		$('#bkg_vendor_amount_diff').html(resSign).change;
		if ($('#bkg_vendor_amount_diff').text() != '' && $('#Booking_bkg_vendor_amount_remark').val() == '') {
			$('#Booking_bkg_vendor_amount_remark_em_').text('');
			$('#Booking_bkg_vendor_amount_remark_em_').show();
			$('#Booking_bkg_vendor_amount_remark_em_').text('Please enter remarks');
		}
		calculateDueAmounts();
	});

	$('#BookingInvoice_bkg_advance_amount').change(function () {
		oldamt = bkAccountsSaved.advance;
		newamt = bkAccounts.getAdvance();
		res = newamt - oldamt;
		resSign = '';
		if (res < 0) {
			resSign = res;
		} else {
			resSign = '+' + res;
		}
		$('#bkg_advance_amount_diff').text(resSign).change;
		if ($('#bkg_advance_amount_diff').text() != '' && $('#Booking_bkg_advance_amount_remark').val() == '') {
			$('#Booking_bkg_advance_amount_remark_em_').text('');
			$('#Booking_bkg_advance_amount_remark_em_').show();
			$('#Booking_bkg_advance_amount_remark_em_').text('Please enter remarks');
		} else {
			$('#Booking_bkg_advance_amount_remark_em_').hide();
		}
		calculateDueAmounts();
	});
	$('#BookingInvoice_bkg_vendor_collected').change(function () {
		oldamt = bkAccountsSaved.vendorCollected;
		newamt = bkAccounts.getVendorCollected();
		res = newamt - oldamt;
		resSign = '';
		if (res < 0) {
			resSign = res;
		} else {
			resSign = '+' + res;
		}
		$('#bkg_vendor_collected_diff').text(resSign).change;
		if ($('#bkg_vendor_collected_diff').text() != '' && $('#Booking_bkg_vendor_collected_remark').val() == '') {
			$('#Booking_bkg_vendor_collected_remark_em_').text('');
			$('#Booking_bkg_vendor_collected_remark_em_').show();
			$('#Booking_bkg_vendor_collected_remark_em_').text('Please enter remarks');
		}
		calculateDueAmounts();
	});

	$('#BookingInvoice_bkg_discount_amount').change(function () {
		calculateDueAmounts();
	});
	$('#BookingInvoice_bkg_additional_charge').change(function () {
		calculateDueAmounts();
	});

	$('#BookingInvoice_bkg_extra_km_charge').change(function () {
		calculateDueAmounts();
	});

	$('#BookingInvoice_bkg_extra_km').change(function () {
		bkAccounts.calculateExtraKMCharge();
		bkAccounts.setVal("BookingInvoice_bkg_extra_km_charge", bkAccounts.extraKMCharge);
		calculateDueAmounts();
	});
	
	$('#BookingInvoice_bkg_extra_total_min_charge').change(function () {
		calculateDueAmounts();
	});
	$('#BookingInvoice_bkg_extra_min').change(function () {
		bkAccounts.calculateExtraMinCharge();
		bkAccounts.setVal("BookingInvoice_bkg_extra_total_min_charge", bkAccounts.extraMinCharge);
		calculateDueAmounts();
	});
	
	$('#BookingInvoice_bkg_convenience_charge').change(function () {
		calculateDueAmounts();
	});
	$('#BookingInvoice_bkg_driver_allowance_amount').change(function () {
		calculateDueAmounts();
	});
	$('#BookingInvoice_bkg_toll_tax').change(function () {
		bkAccounts.populateTotalAmount();
	});
	$('#BookingInvoice_bkg_state_tax').change(function () {
		calculateDueAmounts();
	});
	$('#BookingInvoice_bkg_extra_state_tax').change(function () {
		calculateDueAmounts();
	});
	$('#BookingInvoice_bkg_extra_toll_tax').change(function () {
		calculateDueAmounts();
	});
	$('#BookingInvoice_bkg_parking_charge').change(function () {
		calculateDueAmounts();
	});
	$('#BookingInvoice_bkg_additional_charge_remark').change(function () {
		var $addamt = parseInt($('#BookingInvoice_bkg_additional_charge').val());

		if ($addamt != '' && $('#BookingInvoice_bkg_additional_charge_remark').val() == '') {
			$('#additional_charge_remark_err').show();
			return false;
		} else {
			$('#additional_charge_remark_err').hide();
		}

		//    calculateAmount('bkg_base_amount');
	});
	$('#Booking_bkg_discount_amount_remark').change(function () {
		var $addamt = parseInt($('#BookingInvoice_bkg_discount_amount').val());

		if ($addamt != '' && $('#Booking_bkg_discount_amount_remark').val() == '') {
			$('#discount_remark_err').show();
			return false;
		} else {
			$('#discount_remark_err').hide();
		}

		//    calculateAmount('bkg_base_amount');
	});
	function checkValidation() {

		if (parseInt($('#bkg_amount_diff').text().trim()) != '0' && $('#Booking_bkg_total_amount_remark').val() == '') {
			$('#Booking_bkg_total_amount_remark_em_').text('');
			$('#Booking_bkg_total_amount_remark_em_').show();
			$('#Booking_bkg_total_amount_remark_em_').text('Please enter remarks');
			return false;
		}

		var $rfndamt = parseInt($('#BookingInvoice_bkg_refund_amount').val());
		var $oldrfnamt = parseInt(<?= $model->bkgInvoice->bkg_refund_amount ?>);
		if ($rfndamt != $oldrfnamt && $('#Booking_bkg_refund_amount_remark').val() == '') {
			$('#refund_remark_err').show();
			return false;
		}


		var $addamt = parseInt($('#BookingInvoice_bkg_additional_charge').val());
		var $oldaddamt = parseInt(<?= $model->bkgInvoice->bkg_additional_charge ?>);
		if ($addamt != $oldaddamt && $('#BookingInvoice_bkg_additional_charge_remark').val() == '') {
			$('#additional_charge_remark_err').show();
			return false;
		}


//		if (bkAccounts.getTotalAmount() != (bkAccounts.getGozoAmount() + bkAccounts.getVendorAmount()))
//		{
//			alert('Please check Booking amount, GOZO amount, Vendor amount and Refund');
//			return false;
//		}

//		if (bkAccounts.vendorDue + bkAccounts.gozoDue + bkAccounts.customerDue != 0)
//		{
//			alert('Please check Customer due, GOZO due and Vendor due');
//			return false;
//		}

		if ($('#bkg_trip_distance_diff').text().trim() != 0 && $('#Booking_bkg_trip_distance_remark').val() == '') {
			$('#Booking_bkg_trip_distance_remark_em_').text('');
			$('#Booking_bkg_trip_distance_remark_em_').show();
			$('#Booking_bkg_trip_distance_remark_em_').text('Please enter remarks');
			return false;
		}
			
		if($('#BookingInvoice_bkg_rate_per_km_extra').val() == '' || $('#BookingInvoice_bkg_rate_per_km_extra').val() <= 0)
		{
			if(extraRatePerKM > 0 || $('#BookingInvoice_bkg_rate_per_km_extra').val() < 0)
			{
				$('#rate_Per_extra_km_err').show();
				return false;	
			}
		}
		
		return true;
	}
	function calculateDueAmounts() {
		bkAccounts.populateTotalAmount();
		$('#final_amount_due').text(bkAccounts.customerDue);
		$('#final_vendor_due').text(bkAccounts.vendorDue);
		$('#final_gozo_due').text(bkAccounts.gozoDue);
		$('#final_vendor_due_txt').text(bkAccounts.vendorDue);
		$('#final_gozo_due_txt').text(bkAccounts.gozoDue);
	}

	function calculateSTax(amt) {

		$stax = Math.round(amt * $taxRate / 100);
		$('#BookingInvoice_bkg_service_tax').val($stax);
		$('#service_tax').text($stax);
		return $stax;
	}
	function calculateRevSTax(amt) {
		taxVal = 1 + ($taxRate / 100);
		$stax = amt - Math.round(amt / taxVal);
		return $stax;
	}



	function changeCPComm() {
		booking_id = '<?= $model->bkg_id ?>';
		$href = "<?= Yii::app()->createUrl('admin/booking/changeCPComm') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": booking_id},
			success: function (data) {
				tranbox = bootbox.dialog({
					message: data,
					title: 'Edit Partner Commission',
					onEscape: function () {

					}
				});
				tranbox.on('hidden.bs.modal', function (e) {
					$('body').addClass('modal-open');
				});
			}
		});
	}
	

	function addTransaction() {

		booking_id = '<?= $model->bkg_id ?>';
		$href = "<?= Yii::app()->createUrl('admin/transaction/paymenttran') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": booking_id},
			success: function (data) {
				tranbox = bootbox.dialog({
					message: data,
					title: 'Add Transaction',
					onEscape: function () {

					}
				});
				tranbox.on('hidden.bs.modal', function (e) {
					$('body').addClass('modal-open');
				});
			}
		});
	}
	function addRefund() {

		booking_id = '<?= $model->bkg_id ?>';

		$href = "<?= Yii::app()->createUrl('admin/transaction/refundtran') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": booking_id},
			success: function (data) {
				refndbox = bootbox.dialog({
					message: data,
					title: 'Add Refund',
					onEscape: function () {

					}
				});
				refndbox.on('hidden.bs.modal', function (e) {
					$('body').addClass('modal-open');
				});
			}
		});
	}
	
	function addCompensation()
	{
		bookingId = '<?= $model->bkg_id ?>';

		$href = "<?= Yii::app()->createUrl('admin/transaction/addCompensation') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkgId": bookingId},
			success: function (data) {
				refndbox = bootbox.dialog({
					message: data,
					title: 'Add Compensation',
					onEscape: function () {

					}
				});
				refndbox.on('hidden.bs.modal', function (e) {
					$('body').addClass('modal-open');
				});
			}
		});
	}
</script>