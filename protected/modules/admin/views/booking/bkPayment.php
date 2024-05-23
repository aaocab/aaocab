
<?php
	/* @var $form TbActiveForm */
	$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'paymentForm', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
                if(!hasError){
                if(!admBooking.validatePayment())
				{
					return false;                         
				}
                    $.ajax({
                    "type":"POST",
                    "dataType":"HTML",
                    async: false,
                    "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/payment')) . '",
                    "data":form.serialize(),
                    "beforeSend": function () {
                        ajaxindicatorstart("");
                    },
                    "complete": function () {                     
                        ajaxindicatorstop();
                    },
                    "success":function(data1){
						$("#bkErrors").addClass("hide");
						$(".btn-payment").removeClass("btn-info");
						$(".btn-payment").addClass("disabled");
						$("#payment").find("input").attr("disabled",true);
                        $("#travellerInfo").html(data1);
						$("#travellerInfo").removeClass("hide");
						$(".btn-editPayment").removeClass("hide");
						$(document).scrollTop($("#travellerInfo").offset().top);
                        },
                     error: function(xhr, status, error){
                      
                         }
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
			'onkeydown' => "return event.key != 'Enter';",
			'class' => '',
		),
	));
?>
<input type="hidden" id="oldPromoCode" name="oldPromoCode" value="">
<input type="hidden" id="bkg_surge_differentiate_amount" name="bkg_surge_differentiate_amount" value="">
<input type="hidden" id="bkgPricefactor" name="bkgPricefactor">
<?= CHtml::hiddenField("jsonData_payment", $data, ['id'=>'jsonData_payment'])?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default panel-border">
			<span class="edit-block btn-editPayment hide"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                <h3 class="pl15">Payment information and terms</h3>
              <?= $form->hiddenField($invModel, 'bkg_chargeable_distance'); ?>
			  <?= $form->hiddenField($trcModel, 'bkg_garage_time'); ?>
			  <?= $form->hiddenField($invModel, 'bkg_is_toll_tax_included'); ?>
			  <?= $form->hiddenField($invModel, 'bkg_is_state_tax_included'); ?>
			  <?= $form->hiddenField($invModel, 'bkg_gozo_base_amount'); ?>
             <?
				$toll_checked	 = ($invModel->bkg_is_toll_tax_included == 1) ? 'checked="checked"  disabled="disabled"' : "";
				$state_checked	 = ($invModel->bkg_is_state_tax_included == 1) ? 'checked="checked" disabled="disabled"' : "";
				$parking_checked = ($invModel->bkg_is_parking_included == 1) ? 'checked="checked" disabled="disabled"' : "";
			?>
				<input type="hidden" name="paymentChangesData" id="paymentChangesData">
           <div class="form-group panel-body pt0">
			   <div class ="row hide" id="priceType">
				   <div class="col-sm-6"></div>
				   <div class="col-sm-3">
					   <label class="checkbox-inline pt0">
							<input type="radio" name="pricerad" value="standard" checked="checked" class="pay-focus"> Standard
					   </label>
				   </div>
				   <div class="col-sm-3">
					   <label class="checkbox-inline pt0">
							<input type="radio" name="pricerad" value="custom" class="pay-focus"> Custom
					   </label>
				   </div>
			   </div><br>
			   <div class ="row">
				   <div class="col-sm-6"></div>
				   <div class="col-sm-6 standard-paybox b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_trip_distance_standard">Standard Trip Distance</label>
							<input type="text" id="bkg_trip_distance_standard" value="" class="form-control pay-focus" placeholder="In km.">
						</div>
						<?= $form->hiddenField($model, 'bkg_trip_distance') ?>
				   </div>
				   <div class="col-sm-6 b2b-div2 custom-paybox hide">
						<div class="from-group">
							<label class="control-label" for="bkg_trip_distance_custom">Custom Trip Distance</label>
							<input type="text" id="bkg_trip_distance_custom" value="" class="form-control pay-focus" placeholder="In km." readonly="readonly">
						</div>
				   </div>
			   </div>
			   <div class ="row">
				   <div class="col-sm-6"></div>
				   <div class="col-sm-6 standard-paybox b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_trip_duration_standard">Standard Trip Duration</label>
							<input type="text" id="bkg_trip_duration_standard" value="" class="form-control pay-focus" placeholder="In Min">
						</div>
						<?= $form->hiddenField($model, 'bkg_trip_duration') ?>
				   </div>
				   <div class="col-sm-6 b2b-div2 custom-paybox hide">
						<div class="from-group">
							<label class="control-label" for="bkg_trip_duration_custom">Custom Trip Duration</label>
							<input type="text" id="bkg_trip_duration_custom" value="" class="form-control pay-focus" placeholder="In Min" readonly="readonly">
						</div>
				   </div>
			   </div>
			   <div class="row">
					<div class="col-sm-6"></div>
					<div class="col-sm-6 standard-paybox b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_rate_per_km_extra_standard">Standard Rate per Extra Km.</label>
							<input type="text" id="bkg_rate_per_km_extra_standard" value="" class="form-control pay-focus">
						</div>
						<div id="errordivrate" class="mt5 " style="color:#da4455"></div>
						<?= $form->hiddenField($invModel, 'bkg_rate_per_km_extra') ?>
						<?= $form->hiddenField($invModel, 'bkg_rate_per_km') ?>
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox hide">
						<div class="from-group">
							<label class="control-label" for="bkg_rate_per_km_extra_custom">Custom Rate per Extra Km.</label>
							<input type="text" id="bkg_rate_per_km_extra_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
                </div>
                <div class="row">
                    <div class="col-sm-6"></div>
					<div class="col-sm-6 standard-paybox b2b-div1">
					  <?
					  $readonly		 = [];
					  if (!Yii::app()->user->checkAccess('accountEdit'))
					  {
						  $readonly = ['readonly' => 'readonly'];
					  }
					  ?>
						<div class="from-group">
							<label class="control-label" for="bkg_base_amount_standard">Standard Amount</label>
							<input type="text" id="bkg_base_amount_standard" value="" class="form-control pay-focus" placeholder="Net Charge">
						</div>
					  <?= $form->hiddenField($invModel, 'bkg_base_amount') ?>
						<div id="trip_rate"></div>
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox hide">
						<div class="from-group">
							<label class="control-label" for="bkg_base_amount_custom">Custom Amount</label>
							<input type="text" id="bkg_base_amount_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
				</div>
                <div class="row">
					<div class="col-sm-6">
						<?= $form->textFieldGroup($invModel, 'bkg_additional_charge_remark', array('widgetOptions' => array('htmlOptions' => ['class' => 'pay-focus']))) ?>
					</div>
					<div class="col-sm-6 standard-paybox b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_additional_charge_standard">Standard Additional Charge</label>
							<input type="text" id="bkg_additional_charge_standard" value="" class="form-control pay-focus">
						</div>
						<?= $form->hiddenField($invModel, 'bkg_additional_charge') ?>
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox hide">
						<div class="from-group">
							<label class="control-label" for="bkg_additional_charge_custom">Custom Additional Charge</label>
							<input type="text" id="bkg_additional_charge_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
				</div>
			    <div class="row">
					<div class="col-sm-6">
						<?= $form->textFieldGroup($invModel, 'bkg_promo1_code', array('label' => 'Promo Code', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Promo Code','class' => 'pay-focus adm-promo-apply', 'readonly' => 'readonly']))) ?>
						<span class="text-danger" id="promocreditsucc"></span>
					</div>
                     <!-- new feature--->
                    <div class="col-sm-6 b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_discount_amount_standard">Standard Discount</label>
							<input type="text" id="bkg_discount_amount_standard" value="" class="form-control pay-focus" placeholder="Discount" readonly='readonly'>
						</div>
						<?= $form->hiddenField($invModel, 'bkg_discount_amount') ?>
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox hide">
						<div class="from-group">
							<label class="control-label" for="bkg_discount_amount_custom">Custom Discount</label>
							<input type="text" id="bkg_discount_amount_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
                </div>
                <div class="row"> 
					<div class="col-sm-6">

					</div>
					<input type="hidden" name="rtevndamt" id="rtevndamt">
					<?= $form->hiddenField($invModel, 'bkg_quoted_vendor_amount'); ?>

					<div class="col-sm-6 standard-paybox b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_vendor_amount_standard">Standard Vendor Amount</label>
							<input type="text" id="bkg_vendor_amount_standard" value="" class="form-control pay-focus" readonly = 'readonly'>
						</div>
						<?= $form->hiddenField($invModel, 'bkg_vendor_amount') ?>
					</div>
					<div class="col-sm-6 custom-paybox b2b-div2 hide">
						<div class="from-group">
							<label class="control-label" for="bkg_vendor_amount_custom">Custom Vendor Amount</label>
							<input type="text" id="bkg_vendor_amount_custom" value="" class="form-control pay-focus" readonly = 'readonly'>
						</div>
					</div>
				</div>
                <div class="row"> 
					<div class="col-sm-6  pt20">
						Night Pickup <span class="checkeNightPickupAllowance"><input type="checkbox" name="bkg_night_pickup_included" class="pay-focus" id="Booking_bkg_night_pickup_included1" <?= $isNightPickupAllowence ?>></span>
						Night Drop Off <span class="checkeNightDropOffAllowance"><input type="checkbox" name="bkg_night_drop_included" class="pay-focus" id="Booking_bkg_night_drop_included1" <?= $isNightDropoffAllowenceAllowence ?>></span>	

						<?= $form->hiddenField($invModel, 'bkg_night_pickup_included'); ?>
						<?= $form->hiddenField($invModel, 'bkg_night_drop_included'); ?>



					</div>
					<div class="col-sm-6 standard-paybox b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_driver_allowance_amount_standard">Standard Driver Allowance</label>
							<input type="text" id="bkg_driver_allowance_amount_standard" value="" class="form-control pay-focus" placeholder='Driver allowance' oldamount = 0>
						</div>
						<?= $form->hiddenField($invModel, 'bkg_driver_allowance_amount'); ?>
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox hide">
						<div class="from-group">
							<label class="control-label" for="bkg_driver_allowance_amount_custom">Custom Driver Allowance</label>
							<input type="text" id="bkg_driver_allowance_amount_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
				</div>
                <div class="row">
					<div class="col-xs-6 pt20">Parking Included <span class="checkerparkingtax"><input type="checkbox" name="bkg_is_parking_included" class="pay-focus" id="Booking_bkg_is_parking_included" <?= $parking_checked ?>></span></div>
					<div class="col-sm-6 b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_parking_charge_standard">Standard Parking Charge</label>
							<input type="text" id="bkg_parking_charge_standard" value="" class="form-control pay-focus" placeholder='Parking' readonly = 'readonly'>
						</div>
						<?= $form->hiddenField($invModel, 'bkg_parking_charge') ?>
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox hide">
						<div class="from-group">
							<label class="control-label" for="bkg_parking_charge_custom">Custom Parking Charge</label>
							<input type="text" id="bkg_parking_charge_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6 pt20">Toll tax Included <span class="checkertolltax"><input type="checkbox" name="bkg_is_toll_tax_included1" class="pay-focus" id="Booking_bkg_is_toll_tax_included1" <?= $toll_checked ?>></span></div>
					<div class="col-sm-6 b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_toll_tax_standard">Standard Toll Tax</label>
							<input type="text" id="bkg_toll_tax_standard" value="" class="form-control pay-focus" placeholder='Toll Tax' readonly = 'readonly'>
						</div>
						<?= $form->hiddenField($invModel, 'bkg_toll_tax') ?>
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox hide">
						<div class="from-group">
							<label class="control-label" for="bkg_toll_tax_custom">Custom Toll Tax</label>
							<input type="text" id="bkg_toll_tax_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 pt20">
						State tax Included <span class="checkerstatetax"><input type="checkbox" name="bkg_is_state_tax_included1" class="pay-focus" id="Booking_bkg_is_state_tax_included1" <?= $state_checked ?>></span>

					</div>
					<div class="col-sm-6 b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_state_tax_standard">Standard State Tax</label>
							<input type="text" id="bkg_state_tax_standard" value="" class="form-control pay-focus" placeholder='State Tax' readonly = 'readonly'>
						</div>
						<?= $form->hiddenField($invModel, 'bkg_state_tax') ?>
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox hide">
						<div class="from-group">
							<label class="control-label" for="bkg_state_tax_custom">Custom State Tax</label>
							<input type="text" id="bkg_state_tax_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
				</div>
                 <div class="row hide">
					<div class="col-sm-6">
					</div>
					<div class="col-sm-6 b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_convenience_charge_standard">Standard Collect on delivery(COD) fee</label>
							<input type="text" id="bkg_convenience_charge_standard" value="" class="form-control pay-focus" readonly = 'readonly'>
						</div>
						<?= $form->hiddenField($invModel, 'bkg_convenience_charge') ?>                                 
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox hide">
						<div class="from-group">
							<label class="control-label" for="bkg_convenience_charge_custom">Custom Collect on delivery(COD) fee</label>
							<input type="text" id="bkg_convenience_charge_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
				</div>
                <?
							//$staxrate						 = Filter::getServiceTaxRate();
							$staxrate						 = BookingInvoice::getGstTaxRate($model->bkg_agent_id, $model->bkg_booking_type);
							$taxLabel						 = ($staxrate == 5) ? 'GST' : 'Service Tax ';
							?>

							<div class="row">
								<div class="col-sm-6">
								</div>
								<div class="col-sm-6 b2b-div1">
									<? $invModel->bkg_service_tax_rate	 = $staxrate; ?>
									<?= $form->hiddenField($invModel, 'bkg_service_tax_rate'); ?>
									<div class="from-group">
										<label class="control-label" for="bkg_service_tax_standard">Standard <?= $taxLabel." (rate: " . $staxrate . "%)" ?></label>
										<input type="text" id="bkg_service_tax_standard" value="" class="form-control pay-focus" readonly = 'readonly'>
									</div>
									<?= $form->hiddenField($invModel, 'bkg_service_tax') ?>
								</div>
								<div class="col-sm-6 b2b-div2 custom-paybox hide">
									<div class="from-group">
										<label class="control-label" for="bkg_service_tax_custom">Custom <?= $taxLabel." (rate: " . $staxrate . "%)" ?></label>
										<input type="text" id="bkg_service_tax_custom" value="" class="form-control pay-focus" readonly = 'readonly'>
									</div>
								</div>
							</div>


							<div class="row">
								<div class="col-sm-6 b2b-div1">
									<div class="form-group">
										<label class="control-label" for="amountwithoutcodstandard">Standard Total Amount(Without COD)</label>
										<input readonly="readonly" class="form-control pay-focus" name="amountwithoutcodstandard" id="amountwithoutcodstandard" type="text" value="0">
										<input readonly="readonly" class="form-control pay-focus" name="amountwithoutcod" id="amountwithoutcod" type="hidden" value="0">
									</div> 
								</div>
								<div class="col-sm-6 b2b-div2 hide">
									<div class="form-group">
										<label class="control-label" for="amountwithoutcodcustom">Custom Total Amount(Without COD)</label>
										<input readonly="readonly" class="form-control pay-focus" name="amountwithoutcodcustom" id="amountwithoutcodcustom" type="text" value="0">
									</div> 
								</div>
								<div class="col-sm-6 b2b-div1">
									<div class="from-group">
										<label class="control-label" for="bkg_total_amount_standard">Standard Total Chargeable <?= $invModel->getAttributeLabel('bkg_total_amount') ?></label>
										<input type="text" id="bkg_total_amount_standard" value="" class="form-control pay-focus" readonly = 'readonly'>
									</div>
									<?= $form->hiddenField($invModel, 'bkg_total_amount') ?>
								</div>
								<div class="col-sm-6 b2b-div2 hide">
									<div class="from-group">
										<label class="control-label" for="bkg_total_amount_custom">Custom Total Chargeable <?= $invModel->getAttributeLabel('bkg_total_amount') ?></label>
										<input type="text" id="bkg_total_amount_custom" value="" class="form-control pay-focus" readonly = 'readonly'>
									</div>
								</div>
							</div>
							<!--- start-->
                            <div class="row hide" id="divAgentCredit">
								<div class="col-xs-2" style="padding: 20px;">
									Collect from: <span id="pay_agt_name"></span>
								</div>
								<div class="col-xs-2" style="padding: 20px;">
									<label class="checkbox-inline pt0" id="agt_paid_standard">
										<span class="agt_paid_txt">100%</span> <input type="radio" name="agentpaidrad" value="100" checked="checked" class="pay-focus">
									</label>
								</div>
								<div class="col-xs-2" style="padding: 20px;">
									<label class="checkbox-inline pt0" id="agt_paid_custom">
										<span class="agt_paid_txt">Other</span> <input type="radio" name="agentpaidrad" value="0" class="pay-focus">
									</label>
								</div>
								<div class="col-xs-6 pull-right">
									<?
									if ($model->agentCreditAmount == '')
									{
										$model->agentCreditAmount = 0;
									}
									?>
									<?=$form->numberFieldGroup($model, 'agentCreditAmount', array('label' => 'Amount paid by Agent', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control pay-focus', 'placeholder' => "Agent Advance Credit", 'min' => 0]))) ?>
								</div> 
							</div>
							<div class="row hide" id="div_due_amount">
								<div class="col-xs-6 pull-right">
									<label class="text-info">Total Due Amount(Paid By Traveller)</label> 
									<div class="form-control" id="id_due_amount"><?= $invModel->bkg_due_amount ?></div>
								</div>
							</div>
							<div class="row hide" id="autoAssignment">
								<div class="col-xs-6">
									<label class="text-info">Auto-assigned(default)</label> 
								</div>
								<div class="col-xs-6">
									<input type="checkbox" name="BookingPref[bkg_block_autoassignment]" id="BookingPref_bkg_block_autoassignment" value="1" class="pay-focus">Block-auto assignment
								</div>
							</div>
							<?
							$tripdistance = ($model->bkg_trip_distance != '' && $model->bkg_trip_distance > 0) ? $model->bkg_trip_distance : 0;
							if ($tripdistance > 0)
							{
								if ($invModel->bkg_rate_per_km > 0)
								{
									$tripextrarate = "Note: Ext. Chrg. After " . $tripdistance . " Kms. = " . $invModel->bkg_rate_per_km . "/Km.";
								}
							}
							?>
                            <!-- end -->
							<div class="row">
								<div class="col-xs-12" id="vehicle_dist_ext"><?= $tripextrarate ?>  </div></div>
							<div class="row">
								<div class="col-sm-12 text-center">
									<button type='button' class='btn btn-info btn-payment pl20 pr20'>Next</button>
								</div>
							</div>
						</div>
					</div>
			</div>
		</div>  
<?php $this->endWidget(); ?>
<script>
	var jsonData = JSON.parse($('#jsonData_payment').val());
	$(document).ready(function(){
		admBooking.getAmountbyCitiesnVehicle(booking,jsonData,'payment');
		admBooking.getAgentBaseDiscFare(jsonData);
        admBooking.calculateAmount(jsonData);
		admBooking.getAgentDetails(jsonData.bkg_agent_id,'payment');
		if(jsonData.trip_user == 2)
        {
			$('#priceType').removeClass('hide');
			$('.b2b-div1').removeClass('col-sm-6');
			$('.b2b-div1').addClass('col-sm-3');
			$('.b2b-div2').removeClass('col-sm-6 hide');
			$('.b2b-div2').addClass('col-sm-3');
			admBooking.changeCustomPaymentData();
			<?php if(Yii::app()->user->checkAccess('customPayment')){?>
				$('.standard-paybox').find('input').attr('readonly',true);
			<?php } else {?>
				$('input[name="pricerad"]').attr('disabled',true);
			<?php } ?>
        }
        else
        {
			$('#priceType').addClass('hide');
            $('.b2b-div1').addClass('col-sm-6');
            $('.b2b-div1').removeClass('col-sm-3');
            $('.b2b-div2').addClass('col-sm-6 hide');
            $('.b2b-div2').removeClass('col-sm-3');
			$('.adm-promo-apply').attr('readonly',false);
        }
        if($('#paymentChangesData').val() == '')
        {
            admBooking.changeStandardPaymentData();
        }
	});
	
	$('input[name="pricerad"]').click(function(event){
		if(jsonData.trip_user == 2)
		{
			var radVal = $(event.currentTarget).val();
			if(radVal == 'custom')
			{	
				$('.custom-paybox').find('input').attr('readonly',false);
				admBooking.getDiscount(promo,jsonData);
				admBooking.changeCustomPaymentData();
			}
			else
			{
				$('.custom-paybox').find('input').attr('readonly',true);
				admBooking.changeInvoicePaymentData();
				admBooking.getDiscount(promo,jsonData);
				admBooking.calculateAmount(jsonData);
				$('#bkg_discount_amount_standard').val($('#BookingInvoice_bkg_discount_amount').val());
				$('#vehicle_dist_ext').html("Note: Ext. Chrg. After " + $("#bkg_trip_distance_standard").val() + " Kms. = " + $('#bkg_rate_per_km_extra_standard').val() + "/Km.");
			}
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
		}
	});
	
	var agtPaidPerVal;
	
	$('#bkg_base_amount_custom').change(function(){
		if(jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_base_amount').val($('#bkg_base_amount_custom').val());
			admBooking.calculateCustomAmount(promo,jsonData);
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
		}
		
	});
	
	$('#bkg_additional_charge_custom').change(function(){
		if(jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_additional_charge').val($('#bkg_additional_charge_custom').val());
			admBooking.calculateCustomAmount(promo,jsonData);
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
			
		}
	});
	
	$('#bkg_discount_amount_custom').change(function(){
		if(jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_discount_amount').val($('#bkg_discount_amount_custom').val());
			admBooking.calculateCustomAmount(promo,jsonData);
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
		}
	});
	
	$('#bkg_vendor_amount_custom').change(function(){
		if(jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_quoted_vendor_amount').val($('#bkg_vendor_amount_custom').val());
			admBooking.checkChangedPaymentInfo();
		}
	});
	
	$('#bkg_driver_allowance_amount_custom').change(function(){
		if(jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_driver_allowance_amount').val($('#bkg_driver_allowance_amount_custom').val());
			admBooking.calculateCustomAmount(promo,jsonData);
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
		}
	});
	
	$('#bkg_parking_charge_custom').change(function(){
		if(jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_parking_charge').val($('#bkg_parking_charge_custom').val());
			admBooking.calculateCustomAmount(promo,jsonData);
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
		}
	});
	
	$('#bkg_toll_tax_custom').change(function(){
		if(jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_toll_tax').val($('#bkg_toll_tax_custom').val());
			admBooking.calculateCustomAmount(promo,jsonData);
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
		}
	});
	
	$('#bkg_state_tax_custom').change(function(){
		if(jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_state_tax').val($('#bkg_state_tax_custom').val());
			admBooking.calculateCustomAmount(promo,jsonData);
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
		}
	});
	
	$('#bkg_convenience_charge_custom').change(function(){
		if(jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_convenience_charge').val($('#bkg_convenience_charge_custom').val());
			admBooking.calculateCustomAmount(promo,jsonData);
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
		}
	});
	
	$('#bkg_rate_per_km_extra_custom').change(function(){
		if(jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_rate_per_km_extra').val($('#bkg_rate_per_km_extra_custom').val());
			$('#vehicle_dist_ext').html("Note: Ext. Chrg. After " + $("#bkg_trip_distance_custom").val() + " Kms. = " + $('#bkg_rate_per_km_extra_custom').val() + "/Km.");
			admBooking.checkChangedPaymentInfo();
		}
	});
	
	$('#bkg_trip_distance_standard').change(function(){
		$('#Booking_bkg_trip_distance').val($('#bkg_trip_distance_standard').val());
		$('#vehicle_dist_ext').html("Note: Ext. Chrg. After " + $("#bkg_trip_distance_standard").val() + " Kms. = " + $('#bkg_rate_per_km_extra_standard').val() + "/Km.");
		admBooking.checkChangedPaymentInfo();
	});
	
	$('#bkg_trip_distance_custom').change(function(){
		$('#Booking_bkg_trip_distance').val($('#bkg_trip_distance_custom').val());
		$('#vehicle_dist_ext').html("Note: Ext. Chrg. After " + $("#bkg_trip_distance_custom").val() + " Kms. = " + $('#bkg_rate_per_km_extra_custom').val() + "/Km.");
		admBooking.checkChangedPaymentInfo();
	});
	
	$('#bkg_trip_duration_standard').change(function(){
		$('#Booking_bkg_trip_duration').val($('#bkg_trip_duration_standard').val());
		admBooking.checkChangedPaymentInfo();
	});
	
	$('#bkg_trip_duration_custom').change(function(){
		$('#Booking_bkg_trip_duration').val($('#bkg_trip_duration_custom').val());
		admBooking.checkChangedPaymentInfo();
	});
	
	$('#bkg_total_amount_custom').change(function(){
		$('#Booking_agentCreditAmount').val($('#BookingInvoice_bkg_total_amount').val()).change();
	});
	
	$("#BookingInvoice_bkg_promo1_code").change(function ()
    {
        $("#BookingInvoice_bkg_discount_amount").val('');
		if($('input[name="pricerad"]:checked').val() == 'custom')
		{	
			admBooking.getDiscount(promo,jsonData);
			admBooking.changeCustomPaymentData();
		}
		else
		{
			admBooking.getDiscount(promo,jsonData);
			admBooking.changeStandardPaymentData();
			admBooking.changeCustomPaymentData();
		}
		$('#bkg_discount_amount_standard').val($('#BookingInvoice_bkg_discount_amount').val());
    });
	
	$('#bkg_driver_allowance_amount_standard').change(function ()
    {
		$("#BookingInvoice_bkg_driver_allowance_amount").val($("#bkg_driver_allowance_amount_standard").val());
		admBooking.getDiscount(promo,jsonData);
        admBooking.calculateAmount(jsonData);
		admBooking.changeStandardPaymentData();
    });
	
	$('#bkg_toll_tax_standard').change(function ()
    {
		$("#BookingInvoice_bkg_toll_tax").val($("#bkg_toll_tax_standard").val());
		admBooking.getDiscount(promo,jsonData);
        admBooking.calculateAmount(jsonData);
		admBooking.changeStandardPaymentData();
    });
	
	$('#bkg_state_tax_standard').change(function ()
    {
		$("#BookingInvoice_bkg_state_tax").val($("#bkg_state_tax_standard").val());
		admBooking.getDiscount(promo,jsonData);
        admBooking.calculateAmount(jsonData);
		admBooking.changeStandardPaymentData();
    });
	
	$('#Booking_agentCreditAmount').change(function ()
    {
		admBooking.getDiscount(promo,jsonData);
        admBooking.calculateAmount(jsonData);
    });
	
	$('#bkg_parking_charge_standard').change(function ()
    {
		$("#BookingInvoice_bkg_parking_charge").val($("#bkg_parking_charge_standard").val());
		admBooking.getDiscount(promo,jsonData);
        admBooking.calculateAmount(jsonData);
		admBooking.changeStandardPaymentData();
    });
	
	function calculateAmount(){
		admBooking.calculateAmount(jsonData);
	}
	
	$('input[name="agentpaidrad"]').click(function(event){
		var paidVal = $(event.currentTarget).val();
		admBooking.calculateAgentPaidAmount(paidVal);
	});
	
	$("#BookingInvoice_bkg_base_amount,#BookingInvoice_bkg_discount_amount,#BookingInvoice_bkg_additional_charge").change(function ()
    {
        admBooking.getDiscount(promo,jsonData);
        admBooking.calculateAmount(jsonData);
		if(jsonData.trip_user == 1)
		{
			admBooking.changeStandardPaymentData();
		}
		else
		{
			admBooking.changeCustomPaymentData();
		}
    });
	
	$("#bkg_base_amount_standard").change(function(){
		$("#BookingInvoice_bkg_base_amount").val($("#bkg_base_amount_standard").val()).change();
	});
    
	$("#bkg_additional_charge_standard").change(function(){
		$("#BookingInvoice_bkg_additional_charge").val($("#bkg_additional_charge_standard").val()).change();
	});
	
	$(".btn-payment").click(function(){
		$("#paymentForm").submit();
	});
	
	$(".btn-editPayment").click(function(){
		$('#travellerInfo,#vendorIns').html('');
        $('#travellerInfo,#vendorIns').addClass('hide');
		$(".btn-payment").addClass("btn-info");
		$(".btn-payment").removeClass("disabled");
		$("#payment").find("input").attr("disabled",false);
		$(".btn-editPayment").addClass("hide");
	});
	
	$("#Booking_bkg_is_parking_included").click(function(){
		if(jsonData.trip_user == 1)
		{
			if($("#Booking_bkg_is_parking_included").is(":checked") == true)
			{
				$("#bkg_parking_charge_standard").attr("readonly",false);
			}
			else
			{
				$("#bkg_parking_charge_standard").attr("readonly",true);
			}
		}
	});
	
</script>