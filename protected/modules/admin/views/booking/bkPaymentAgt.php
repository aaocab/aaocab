<style>
input {vertical-align:text-bottom;}
</style>
<?php
/* @var $form TbActiveForm */
$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
						if($("#rePaymentOpt").val() == "true")
						{
							$("#rePayment").find("input").attr("disabled",true);
							$("#additionalInfo").html(data1);
							$("#additionalInfo").removeClass("hide");
							$(document).scrollTop($("#additionalInfo").offset().top);
						}
						else
						{
							$("#payment").find("input").attr("disabled",true);
							$("#travellerInfo").html(data1);
							$("#travellerInfo").removeClass("hide");
							$(document).scrollTop($("#travellerInfo").offset().top);
						}
						$(".btn-editPayment").removeClass("hide");
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
		'onkeydown'	 => "return event.key != 'Enter';",
		'class'		 => '',
	),
		));
?>
<input type="hidden" id="oldPromoCode" name="oldPromoCode" value="">
<input type="hidden" id="bkg_surge_differentiate_amount" name="bkg_surge_differentiate_amount" value="">
<input type="hidden" id="bkgPricefactor" name="bkgPricefactor">
<input type="hidden" id="rePaymentOpt" name="rePaymentOpt" value="<?= $rePaymentOpt ?>">
<input type="hidden" name="multicityjsondata" class='box-multicityjson' value="">
<?= CHtml::hiddenField("jsonData_payment", $data, ['id' => 'jsonData_payment']) ?>
<?php
$staxrate	 = BookingInvoice::getGstTaxRate($model->bkg_agent_id, $model->bkg_booking_type);
if ($data != "")
{
	$jsonDataPayment = json_decode($data);
	$bookingType	 = $jsonDataPayment->bkg_booking_type;
	$staxrate		 = BookingInvoice::getGstTaxRate($jsonDataPayment->bkg_agent_id, $bookingType);
}
$clsHideCustomPayBox = "";
if (!Yii::app()->user->checkAccess('customPayment'))
{
	$clsHideCustomPayBox = "hide";
}
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default panel-border">
			<span class="edit-block btn-editPayment hide"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
			<div class="alert alert-block">
				<?php
				if (!empty($note))
				{
					?>
					<div class="row">
						<div class="col-xs-12" id="linkedusers"><div class="panel panel-primary panel-border compact">
								<div class="panel-heading" style="min-height:0;background-color: red;">Special instructions & advisories that may affect your planned travel</div>

								<div class="panel-body">
									<div class="row" style="padding: 0px 4px;" >
										<div class="col-sm-2" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
											<div class="p5" style="font-size: 1.2em">City</div></div>
										<div class="col-sm-6" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
											<div class="p5" style="font-size: 1.2em"><span class="m5">Note</span></div></div>
										<div class="col-sm-2" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
											<div class="p5" style="font-size: 1.2em"><span class="m5">Valid From</span></div></div>
										<div class="col-sm-2" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
											<div class="p5" style="font-size: 1.2em"><span class="m5"> Valid To</span></div></div>
									</div>
									<?php
									for ($i = 0; $i < count($note); $i++)
									{
										?>   
										<div class="row" style="padding: 0px 4px;">
											<div class="col-sm-2" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
												<div class="p5" style="font-size: 1.1em">
													<?php
													if ($note[$i]['dnt_area_type'] == 3)
													{
														?>
														<?= ($note[$i]['cty_name']) ?>
														<?php
													}
													else if ($note[$i]['dnt_area_type'] == 2)
													{
														?>
														<?= ($note[$i]['dnt_state_name']) ?>
														<?php
													}
													else if ($note[$i]['dnt_area_type'] == 0)
													{
														?>
														<?= "Applicable to all" ?>
														<?php
													}
													else if ($note[$i]['dnt_area_type'] == 4)
													{
														?>
														<?= Promos::$region[$note[$i]["dnt_area_id"]] ?>
														<?php
													}
													?>
												</div></div>
											<div class="col-sm-6" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
												<div class="p5 nowrap" style="font-size: 1.1em"><span class="m5 "><?= ($note[$i]['dnt_note']) ?></span></div></div>
											<div class="col-sm-2" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
												<div class="p5" style="font-size: 1.1em"><span class="m5">  <?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_from'])) ?></span></div></div>
											<div class="col-sm-2" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
												<div class="p5" style="font-size: 1.1em"><span class="m5"><?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_to'])) ?></span></div></div>
										</div><?php
									}
									?>
								</div>

							</div></div>
					</div>
					<?php
				}
				?>
			</div>

			<div class="form-group panel-body pt0" id="serviceClassDiv"></div>
			<?php // echo $form->hiddenField($model, 'bkg_service_class'); ?>
			<?php echo $form->hiddenField($invModel, 'bkg_addon_ids'); ?>


			<input type="hidden" id="customsccclass" value="">
			<div class="form-group panel-body pt0" id="serviceClassDivCustom">
				<?php
				$serviceClasses = ServiceClass::getAll();
				foreach ($serviceClasses as $value)
				{
					$scvId = SvcClassVhcCat::getSvcClassIdByVehicleCat($vhcId, $value['scc_id']);
					if ($quotes[$scvId] == false)
					{
						continue;
					}
					if ($quotes[$scvId]->routeRates->baseAmount > 0 || $bookingType == 8)
					{
						?>
						<div class="btn customserclass serviceClass<?= $value['scc_id'] ?> col-xs-2 p16 mb10 btn-widget-1"  onclick="customServiceClass(<?= $value['scc_id'] ?>);">
							<input type="hidden" class="scvId<?= $value['scc_id'] ?>" value="<?= $scvId ?>" >
							<div class="">
								<label class="sccLabel"><?php echo $value['scc_label'] ?></label>
								<?
								if ($bookingType != 8)
								{
									?>	<label class="sccLabel"><i class="fa fa-inr serviceclassinr"></i><?php echo $quotes[$scvId]->routeRates->baseAmount ?></label><? } ?>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>
			<div class="form-group panel-body pt0 hide" id="addOnDiv">
                <div class="col-sm-6 pt20 mt5">
					<label class="control-label" for="exampleInputCompany6">Select Addons</label>
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $invModel,
						'attribute'		 => 'bkg_addon_ids',
						'val'			 => $invModel->bkg_addon_ids,
						'data'			 => [],
						'htmlOptions'	 => array('style' => 'width:100%', 'id' => 'Booking_bkg_addon_ids', 'placeholder' => 'Select Addons', 'class' => 'route-focus')
					));
					?>
					<span class="has-error"><? echo $form->error($model, 'bkg_addon_ids'); ?></span>
				</div>
			</div>
			<div class="form-group panel-body carModel hide">
				<div class="col-sm-6 pt20 mt5 selectmodel">
					<label class="control-label" for="exampleInputCompany6">Select Car Type </label>
					<?php
//					echo $form->dropDownListGroup($model, 'bkg_vht_id', array(
//						'label'			 => '', 'widgetOptions'	 => array(
//							'data'				 => '',
//							'model'				 => $model,
//							'attribute'			 => 'bkg_vht_id',
//							'useWithBootstrap'	 => true,
//							'val'				 => $model->bkg_vht_id,
//							'fullWidth'			 => false,
//							"placeholder"		 => "Select Source City",
//							'htmlOptions'		 => array('width' => '100%',
//							),
//					)));
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'bkg_vht_id',
						'val'			 => $model->bkg_vht_id,
						'data'			 => [],
						'htmlOptions'	 => array('style' => 'width:100%', 'id' => 'Booking_bkg_vht_id', 'placeholder' => 'Select Car Model', 'class' => 'route-focus')
					));
					?>
				</div>
			</div>

			<h3 class="pl15">Payment information and terms</h3>
			<?= $form->hiddenField($invModel, 'bkg_chargeable_distance'); ?>
			<?= $form->hiddenField($trcModel, 'bkg_garage_time'); ?>
			<? //= $form->hiddenField($invModel, 'bkg_is_toll_tax_included');   ?>
			<? //= $form->hiddenField($invModel, 'bkg_is_state_tax_included');   ?>
			<?= $form->hiddenField($invModel, 'bkg_gozo_base_amount'); ?>
			<?
			$toll_checked	 = ($invModel->bkg_is_toll_tax_included == 1) ? 'checked="checked"  disabled="disabled"' : "";
			$state_checked	 = ($invModel->bkg_is_state_tax_included == 1) ? 'checked="checked" disabled="disabled"' : "";
			$parking_checked = ($invModel->bkg_is_parking_included == 1) ? 'checked="checked" disabled="disabled"' : "";
			?>
			<input type="hidden" name="paymentChangesData" id="paymentChangesData">
			<div class="form-group panel-body pt0">
				<div class ="row  <?= $clsHideCustomPayBox ?>" id="priceType">
					<div class="col-sm-6"></div>
					<div class="col-sm-3">
						<label class="checkbox-inline pt0 pl0">
							<input type="radio" name="pricerad" value="standard" checked="checked" class="pay-focus"> Standard
						</label>
					</div>
					<div class="col-sm-3">
						<label class="checkbox-inline pt0 pl0">
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
					<div class="col-sm-6 b2b-div2 custom-paybox <?= $clsHideCustomPayBox ?>">
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
					<div class="col-sm-6 b2b-div2 custom-paybox <?= $clsHideCustomPayBox ?>">
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
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox <?= $clsHideCustomPayBox ?>">
						<div class="from-group">
							<label class="control-label" for="bkg_rate_per_km_extra_custom">Custom Rate per Extra Km.</label>
							<input type="text" id="bkg_rate_per_km_extra_custom" value="" class="form-control pay-focus">
						</div>
					</div>
                </div>

				<?php
				if ($bookingType == 9 || $bookingType == 10 || $bookingType == 11)
				{
					?>
					<div class="row">
						<div class="col-sm-6"></div>
						<div class="col-sm-6 standard-paybox b2b-div1">
							<div class="from-group">
								<label class="control-label" for="bkg_rate_per_min_extra_standard">Standard Rate per Extra Time.</label>
								<input type="text" id="bkg_rate_per_min_extra_standard" value="" class="form-control pay-focus">
							</div>
							<div id="errordivrate" class="mt5 " style="color:#da4455"></div>
							<?= $form->hiddenField($invModel, 'bkg_extra_per_min_charge') ?>
						</div>
						<div class="col-sm-6 b2b-div2 custom-paybox <?= $clsHideCustomPayBox ?>">
							<div class="from-group">
								<label class="control-label" for="bkg_rate_per_min_extra_custom">Custom Rate per Extra Time.</label>
								<input type="text" id="bkg_rate_per_min_extra_custom" value="" class="form-control pay-focus" readonly="readonly">
							</div>
							<div id="errordivrate" class="mt5 " style="color:#da4455"></div>

						</div>
					</div>		


					<?php
				}
				?>
				<div class="row hide bkg-cust-pay">
					<div class="col-sm-6"></div>
					<div class="col-sm-6 standard-paybox b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_rate_per_km_standard">Standard Rate per Km.</label>
							<input type="text" id="bkg_rate_per_km_standard" value="" class="form-control pay-focus">
						</div>
						<div id="errordivrate" class="mt5 " style="color:#da4455"></div>
						<?= $form->hiddenField($invModel, 'bkg_rate_per_km') ?>
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox <?= $clsHideCustomPayBox ?>">
						<div class="from-group">
							<label class="control-label" for="bkg_rate_per_km_custom">Custom Rate per Km.</label>
							<input type="text" id="bkg_rate_per_km_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
                </div>
                <div class="row">
                    <div class="col-sm-6"></div>
					<div class="col-sm-6 standard-paybox b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_base_amount_standard">Standard Amount</label>
							<input type="text" id="bkg_base_amount_standard" value="" class="form-control pay-focus" placeholder="Net Charge">
						</div>
						<?= $form->hiddenField($invModel, 'bkg_base_amount') ?>
						<div id="trip_rate"></div>
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox <?= $clsHideCustomPayBox ?>">
						<div class="from-group">
							<label class="control-label" for="bkg_base_amount_custom">Custom Amount</label>
							<input type="text" id="bkg_base_amount_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
				</div>
				<div class="row">
                    <div class="col-sm-6"></div>
					<div class="col-sm-6 standard-paybox b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_addon_charges_standard">Standard Addon Charge</label>
							<input type="text" id="bkg_addon_charges_standard" value="" class="form-control pay-focus" placeholder="Addon Charge">

							<input type="hidden" id="bkg_addon_details" value="">
							<?= $form->hiddenField($invModel, 'bkg_addon_charges') ?>
						</div>

						<? //= $form->hiddenField($invModel, 'bkg_addon_charges')    ?>
						<div id="trip_rate"></div>
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox <?= $clsHideCustomPayBox ?>">
						<div class="from-group">
							<label class="control-label" for="bkg_addon_charges_custom">Custom Addon Charge</label>
							<input type="text" id="bkg_addon_charges_custom" value="" class="form-control pay-focus" readonly="readonly">
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
					<div class="col-sm-6 b2b-div2 custom-paybox <?= $clsHideCustomPayBox ?>">
						<div class="from-group">
							<label class="control-label" for="bkg_additional_charge_custom">Custom Additional Charge</label>
							<input type="text" id="bkg_additional_charge_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<?= $form->textFieldGroup($invModel, 'bkg_promo1_code', array('label' => 'Promo Code', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Promo Code', 'class' => 'pay-focus adm-promo-apply', 'readonly' => 'readonly']))) ?>
						<span class="text-danger" id="promocreditsucc"></span>
					</div>
					<!-- new feature--->
                    <div class="col-sm-6 standard-paybox b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_discount_amount_standard">Standard Discount</label>
							<input type="text" id="bkg_discount_amount_standard" value="" class="form-control pay-focus" placeholder="Discount" readonly='readonly'>
						</div>
						<?= $form->hiddenField($invModel, 'bkg_discount_amount') ?>
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox <?= $clsHideCustomPayBox ?>">
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
					<div class="col-sm-6 custom-paybox b2b-div2 <?= $clsHideCustomPayBox ?>">
						<div class="from-group">
							<label class="control-label" for="bkg_vendor_amount_custom">Custom Vendor Amount</label>
							<input type="text" id="bkg_vendor_amount_custom" value="" class="form-control pay-focus" readonly = 'readonly'>
						</div>
					</div>
				</div>
                <div class="row"> 
					<div class="col-sm-6  pt20">
						<span class="checkeNightPickupAllowance"><input type="checkbox" name="bkg_night_pickup_included" class="pay-focus" id="Booking_bkg_night_pickup_included1" <?= $isNightPickupAllowence ?>></span> Night Pickup &nbsp;&nbsp;
						<span class="checkeNightDropOffAllowance"><input type="checkbox" name="bkg_night_drop_included" class="pay-focus" id="Booking_bkg_night_drop_included1" <?= $isNightDropoffAllowenceAllowence ?>></span> Night Drop Off 	

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
					<div class="col-sm-6 b2b-div2 custom-paybox <?= $clsHideCustomPayBox ?>">
						<div class="from-group">
							<label class="control-label" for="bkg_driver_allowance_amount_custom">Custom Driver Allowance</label>
							<input type="text" id="bkg_driver_allowance_amount_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
				</div>
                <div class="row">
					<div class="col-xs-6 pt20"><span class="checkerparkingtax"><input type="checkbox" name="BookingInvoice[bkg_is_parking_included]" class="pay-focus" id="BookingInvoice_bkg_is_parking_included" <?= $parking_checked ?>></span> Parking Included</div>
					<div class="col-sm-6 standard-paybox b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_parking_charge_standard">Standard Parking Charge</label>
							<input type="text" id="bkg_parking_charge_standard" value="" class="form-control pay-focus" placeholder='Parking' readonly = 'readonly'>
						</div>
						<?= $form->hiddenField($invModel, 'bkg_parking_charge') ?>
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox <?= $clsHideCustomPayBox ?>">
						<div class="from-group">
							<label class="control-label" for="bkg_parking_charge_custom">Custom Parking Charge</label>
							<input type="text" id="bkg_parking_charge_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6 pt20"><span class="checkertolltax"><input type="checkbox" name="BookingInvoice[bkg_is_toll_tax_included]" class="pay-focus" id="BookingInvoice_bkg_is_toll_tax_included" <?= $toll_checked ?>></span> Toll tax Included</div>
					<div class="col-sm-6 standard-paybox b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_toll_tax_standard">Standard Toll Tax</label>
							<input type="text" id="bkg_toll_tax_standard" value="" class="form-control pay-focus" placeholder='Toll Tax' readonly = 'readonly'>
						</div>
						<?= $form->hiddenField($invModel, 'bkg_toll_tax') ?>
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox <?= $clsHideCustomPayBox ?>">
						<div class="from-group">
							<label class="control-label" for="bkg_toll_tax_custom">Custom Toll Tax</label>
							<input type="text" id="bkg_toll_tax_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 pt20">
						<span class="checkerstatetax"><input type="checkbox" name="BookingInvoice[bkg_is_state_tax_included]" class="pay-focus" id="BookingInvoice_bkg_is_state_tax_included" <?= $state_checked ?>></span> State tax Included

					</div>
					<div class="col-sm-6 standard-paybox b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_state_tax_standard">Standard State Tax</label>
							<input type="text" id="bkg_state_tax_standard" value="" class="form-control pay-focus" placeholder='State Tax' readonly = 'readonly'>
						</div>
						<?= $form->hiddenField($invModel, 'bkg_state_tax') ?>
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox <?= $clsHideCustomPayBox ?>">
						<div class="from-group">
							<label class="control-label" for="bkg_state_tax_custom">Custom State Tax</label>
							<input type="text" id="bkg_state_tax_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
				</div>
                <div class="row">
					<div class="col-sm-6 pt20">
						<span class="checkairportfee"><input type="checkbox" name="BookingInvoice[bkg_is_airport_fee_included]" class="pay-focus" id="BookingInvoice_bkg_is_airport_fee_included"></span> Airport Fee Included

					</div>
					<div class="col-sm-6 standard-paybox b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_airport_fee_standard">Standard Airport Entry Fee</label>
							<input type="text" id="bkg_airport_fee_standard" value="" class="form-control pay-focus" placeholder='Airport Fee' readonly = 'readonly'>
						</div>
						<?php echo $form->hiddenField($invModel, 'bkg_airport_entry_fee') ?>
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox <?= $clsHideCustomPayBox ?>">
						<div class="from-group">
							<label class="control-label" for="bkg_airport_fee_custom">Custom Airport Entry Fee</label>
							<input type="text" id="bkg_airport_fee_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
				</div>
				<div class="row hide">
					<div class="col-sm-6">
					</div>
					<div class="col-sm-6 standard-paybox b2b-div1">
						<div class="from-group">
							<label class="control-label" for="bkg_convenience_charge_standard">Standard Collect on delivery(COD) fee</label>
							<input type="text" id="bkg_convenience_charge_standard" value="" class="form-control pay-focus" readonly = 'readonly'>
						</div>
						<?= $form->hiddenField($invModel, 'bkg_convenience_charge') ?>                                 
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox <?= $clsHideCustomPayBox ?>">
						<div class="from-group">
							<label class="control-label" for="bkg_convenience_charge_custom">Custom Collect on delivery(COD) fee</label>
							<input type="text" id="bkg_convenience_charge_custom" value="" class="form-control pay-focus" readonly="readonly">
						</div>
					</div>
				</div>
				<?
				//$staxrate						 = Filter::getServiceTaxRate();
				$taxLabel						 = ($staxrate == 5) ? 'GST' : 'Service Tax ';
				?>

				<div class="row">
					<div class="col-sm-6">
					</div>
					<div class="col-sm-6 standard-paybox b2b-div1">
						<? $invModel->bkg_service_tax_rate	 = $staxrate; ?>
						<?= $form->hiddenField($invModel, 'bkg_service_tax_rate'); ?>
						<div class="from-group">
							<label class="control-label" for="bkg_service_tax_standard">Standard <?= $taxLabel . " (rate: " . $staxrate . "%)" ?></label>
							<input type="text" id="bkg_service_tax_standard" value="" class="form-control pay-focus" readonly = 'readonly'>
						</div>
						<?= $form->hiddenField($invModel, 'bkg_service_tax') ?>
					</div>
					<div class="col-sm-6 b2b-div2 custom-paybox <?= $clsHideCustomPayBox ?>">
						<? $invModel->bkg_service_tax_rate	 = $staxrate; ?>
						<?= $form->hiddenField($invModel, 'bkg_service_tax_rate'); ?>
						<div class="from-group">
							<label class="control-label" for="bkg_service_tax_custom">Custom <?= $taxLabel . " (rate: " . $staxrate . "%)" ?></label>
							<input type="text" id="bkg_service_tax_custom" value="" class="form-control pay-focus" readonly = 'readonly'>
						</div>
						<?= $form->hiddenField($invModel, 'bkg_service_tax') ?>
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
					<div class="col-sm-6 b2b-div2">
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
					<div class="col-sm-6 b2b-div2 <?= $clsHideCustomPayBox ?>">
						<div class="from-group">
							<label class="control-label" for="bkg_total_amount_custom">Custom Total Chargeable <?= $invModel->getAttributeLabel('bkg_total_amount') ?></label>
							<input type="text" id="bkg_total_amount_custom" value="" class="form-control pay-focus" readonly = 'readonly'>
						</div>
					</div>
				</div>
				<div class="row m0">
					<div class="col-xs-6 col-lg-2" style="padding: 20px 0 0;">
						<label class="checkbox-inline p10 btn btn-default font-14" id="agt_booking_quote">
						<input type="radio" name="agtBkgCategory" value="2" class="pay-focus"> Quote Booking
						</label>
					</div>
					<div class="col-xs-6 col-lg-2" style="padding: 20px 0 0;">
						<label class="checkbox-inline p10 btn btn-default font-14" id="agt_booking_confirm">
							<input type="radio" name="agtBkgCategory" value="1" checked="checked" class="pay-focus"> Confirm Booking
						</label>
					</div>
				</div>
				
				<!--- start-->
				
					<div class="row hide divAgentBooking" id="divAgentCredit">
						<div class="col-xs-6 col-lg-3" style="padding: 20px;">
							Collect from: <span id="pay_agt_name"></span>
						</div>
						<div class="col-xs-2 col-lg-1" style="padding: 20px 0;">
							<label class="checkbox-inline pt0" id="agt_paid_standard">
								<input type="radio" name="agentpaidrad" value="100" checked="checked" class="pay-focus"> 100%
							</label>
						</div>
						<div class="col-xs-2 col-lg-1" style="padding: 20px 0;">
							<label class="checkbox-inline pt0" id="agt_paid_custom">
								<input type="radio" name="agentpaidrad" value="0" class="pay-focus"> Other
							</label>
						</div>
						<div class="col-xs-6 pull-right">
							<?
							if ($model->agentCreditAmount == '')
							{
								$model->agentCreditAmount = 0;
							}
							?>
							<?= $form->numberFieldGroup($model, 'agentCreditAmount', array('label' => 'Amount paid by Agent', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control pay-focus', 'placeholder' => "Agent Advance Credit", 'min' => 0]))) ?>
						</div> 
					</div>
					<div class="row hide divAgentBooking" id="div_due_amount">
						<div class="col-xs-6 pull-right">
							<label class="text-info">Total Due Amount(Paid By Traveller)</label> 
							<div class="form-control" id="id_due_amount"><?= $invModel->bkg_due_amount ?></div>
						</div>
					</div>
					<div class="row mt15" id="autoAssignment">
						<div class="col-xs-6">
							<label class="text-info">Auto-assigned(default)</label> 
						</div>
						<div class="col-xs-6">
							<input type="checkbox" name="BookingPref[bkg_block_autoassignment]" id="BookingPref_bkg_block_autoassignment" value="1" class="pay-focus"> Block-auto assignment
						</div>
					</div>
					<div class="row" id="isFbgType">
						<div class="col-xs-6">
							<label class="text-info" for="bkg_is_fbg_type">Check FBG Booking or not</label> 
						</div>
						<div class="col-xs-6">
							<span class="isFbgBooking"><input type="checkbox" name="BookingPref[bkg_is_fbg_type]" class="box-multicityjson" id="bkg_is_fbg_type" value="1" disabled="true" > Is FBG Booking</span>	

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
	var agtPaidPerVal;
	$(document).ready(function () {

		initialize();
	});

	function initialize()
	{

		StandardPaymentBoxDefaults();

		trip_distance = 0;
		if ($('#jsonData_travellerInfo').val() != "" && $('#jsonData_travellerInfo').val() != undefined && $('#jsonData_travellerInfo').val() != 'undefined' && $('#jsonData_travellerInfo').val() != null)
		{
			$("#serviceClassDivCustom").hide();
			if (jsonData.tot_est_dist != jsonData.bkg_trip_distance)
			{
				$('#Booking_bkg_trip_distance').val(jsonData.bkg_trip_distance);
				var changedDistance = jsonData.bkg_trip_distance;
				$('#tot_est_dist').val(jsonData.bkg_trip_distance);
				$('#bkg_trip_distance_standard').val(changedDistance);
				trip_distance = 1;
			}
		}
		if (jsonData.bkg_booking_type != 8)
		{
			admBooking.getAmountbyCitiesnVehicle(booking, jsonData, 'payment', trip_distance);
			admBooking.getAgentBaseDiscFare(jsonData);
		}
		admBooking.calculateAmount(jsonData);
		admBooking.getAgentDetails(jsonData.bkg_agent_id, 'payment');
		if (trip_distance == 1)
		{
			$('#Booking_bkg_trip_distance').val(changedDistance);
			$('#tot_est_dist').val(changedDistance);
			$('#bkg_trip_distance_standard').val(changedDistance);
		}
		if (jsonData.trip_user == 2)
		{
			$('.b2b-div1').removeClass('col-sm-6');
			$('.b2b-div1').addClass('col-sm-3');
			//$('.b2b-div2').removeClass('col-sm-6 hide');
			$('.b2b-div2').addClass('col-sm-3');
			if (jsonData.bkg_booking_type == 8)
			{
				$('.bkg-cust-pay').removeClass('hide');
				$('.customserclass').removeClass('hide');
			}
			admBooking.changeCustomPaymentData();
<?php
if (Yii::app()->user->checkAccess('customPayment'))
{
	?>
				//				$('.standard-paybox').find('input').attr('readonly',true);
				//				$("#bkg_trip_distance_standard").attr('readonly',false);
				//				$("#Booking_bkg_trip_distance").attr('readonly',false);
				//				$("#bkg_trip_duration_standard").attr('readonly',false);
				//				$("#Booking_bkg_trip_duration").attr('readonly',false);
				//				$("#bkg_rate_per_km_extra_standard").attr('readonly',false);
				//				$("#Booking_bkg_rate_per_km_extra").attr('readonly',false);

	<?php
}
else
{
	?>
				$('input[name="pricerad"]').attr('disabled', true);
				if (jsonData.bkg_booking_type == 8)
				{
					$('.standard-paybox').find('input').attr('readonly', false);
				}
<?php } ?>
		}
		if ($('#paymentChangesData').val() == '')
		{
			admBooking.changeStandardPaymentData();
		}
		if (jsonData.bkg_booking_type == 8)
		{
			$('#Booking_bkg_trip_distance').val($('#bkg_trip_distance_standard').val());
			$('#Booking_bkg_trip_duration').val($('#bkg_trip_duration_standard').val());
			$('#addOnDiv').hide();
		}
		if(jsonData.agtBkgCategory == 2)
		{ 
			$("input[name=agtBkgCategory][value='2']").prop("checked",true);
			$("input[name=agtBkgCategory][value='1']").removeAttr('checked');
			$("input[name=agtBkgCategory][value='2']").attr("checked", "checked");
			if(!$('.divAgentBooking').hasClass('hide'))
			{
				$('.divAgentBooking').addClass('hide');
			}

		}
	}

	function StandardPaymentBoxDefaults() {
		$('.standard-paybox').find('input').attr('readonly', true);
		$("#bkg_trip_distance_standard").attr('readonly', false);
		$("#Booking_bkg_trip_distance").attr('readonly', false);
		$("#bkg_trip_duration_standard").attr('readonly', false);
		$("#Booking_bkg_trip_duration").attr('readonly', false);
		$("#bkg_rate_per_km_extra_standard").attr('readonly', false);
		$("#Booking_bkg_rate_per_km_extra").attr('readonly', false);

	}

	$('#Booking_bkg_vht_id').change(function () {
		var sccData = {"bkg_service_class": 4};
		var addonIds = $('#BookingInvoice_bkg_addon_ids').val();
		$.extend(true, jsonData, sccData);
		var modelData = {"modelId": $('#Booking_bkg_vht_id').val()};
		$.extend(true, jsonData, modelData);
		$('#jsonData_payment').val(JSON.stringify(jsonData));
		admBooking.getAmountbyCitiesnVehicle(booking, jsonData, 'payment');
		admBooking.showPaymentDetails(addonIds);
	});

	$('input[name="pricerad"]').click(function (event) {
		if (jsonData.trip_user == 2)
		{
			var radVal = $(event.currentTarget).val();
			if (radVal == 'custom')
			{
				$('.custom-paybox').find('input').attr('readonly', false);
//				if(jsonData.bkg_booking_type != 8)
//				{
				$('#bkg_addon_charges_custom').attr('readonly', true);
//				}
				$("#bkg_is_fbg_type").prop('disabled', false);
				admBooking.getDiscount(promo, jsonData);
				admBooking.changeCustomPaymentData();
			} else
			{
				$('.custom-paybox').find('input').attr('readonly', true);
				$("#bkg_is_fbg_type").prop('disabled', true);
				admBooking.changeInvoicePaymentData();
				admBooking.getDiscount(promo, jsonData);
				admBooking.calculateAmount(jsonData);
				$('#bkg_discount_amount_standard').val($('#BookingInvoice_bkg_discount_amount').val());
				$('#vehicle_dist_ext').html("Note: Ext. Chrg. After " + $("#bkg_trip_distance_standard").val() + " Kms. = " + $('#bkg_rate_per_km_extra_standard').val() + "/Km.");
			}
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
			quoteBookingSelected();
		}
	});

	$('#bkg_rate_per_km_standard').change(function () {
		if (jsonData.bkg_booking_type == 8)
		{
			$('#BookingInvoice_bkg_rate_per_km').val($('#bkg_rate_per_km_standard').val());
			admBooking.checkChangedPaymentInfo();
		}
		quoteBookingSelected();
	});

	$('#bkg_rate_per_km_custom').change(function () {
		if (jsonData.trip_user == 2 && jsonData.bkg_booking_type == 8)
		{
			$('#BookingInvoice_bkg_rate_per_km').val($('#bkg_rate_per_km_custom').val());
			admBooking.checkChangedPaymentInfo();
		}
		quoteBookingSelected();
	});

	$('#bkg_base_amount_custom').change(function () {
		if (jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_base_amount').val($('#bkg_base_amount_custom').val());
			admBooking.calculateCustomAmount(promo, jsonData);
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
			quoteBookingSelected();
		}

	});

	$('#bkg_additional_charge_custom').change(function () {
		if (jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_additional_charge').val(Math.abs($('#bkg_additional_charge_custom').val()));
			admBooking.calculateCustomAmount(promo, jsonData);
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
			quoteBookingSelected();
		}
	});

	$('#bkg_discount_amount_custom').change(function () {
		if (jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_discount_amount').val($('#bkg_discount_amount_custom').val());
			admBooking.calculateCustomAmount(promo, jsonData);
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
			quoteBookingSelected();
		}
	});

	$('#bkg_vendor_amount_custom').change(function () {
		if (jsonData.trip_user == 2)
		{
			if (jsonData.bkg_booking_type == 8)
			{
				$('#BookingInvoice_bkg_quoted_vendor_amount').val($('#bkg_vendor_amount_custom').val());
			}
			$('#rtevndamt').val($('#bkg_vendor_amount_custom').val());
			$('#BookingInvoice_bkg_vendor_amount').val($('#bkg_vendor_amount_custom').val());
			admBooking.checkChangedPaymentInfo();
			quoteBookingSelected();
		}
	});

	$('#bkg_driver_allowance_amount_custom').change(function () {
		if (jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_driver_allowance_amount').val($('#bkg_driver_allowance_amount_custom').val());
			admBooking.calculateCustomAmount(promo, jsonData);
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
			quoteBookingSelected();
		}
	});

	$('#bkg_parking_charge_custom').change(function () {
		if (jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_parking_charge').val($('#bkg_parking_charge_custom').val());
			admBooking.calculateCustomAmount(promo, jsonData);
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
			quoteBookingSelected();
		}
	});
	$('#bkg_airport_fee_custom').change(function () {
		if (jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_airport_entry_fee').val($('#bkg_airport_fee_custom').val());
			admBooking.calculateCustomAmount(promo, jsonData);
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
			quoteBookingSelected();
		}
	});

	$('#bkg_airport_fee_standard').change(function ()
	{
		$("#BookingInvoice_bkg_airport_entry_fee").val($("#bkg_airport_fee_standard").val());
		admBooking.getDiscount(promo, jsonData);
		admBooking.calculateAmount(jsonData);
		admBooking.changeStandardPaymentData();
		admBooking.checkChangedPaymentInfo();
		quoteBookingSelected();
		
	});

	$('#bkg_toll_tax_custom').change(function () {
		if (jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_toll_tax').val($('#bkg_toll_tax_custom').val());
			admBooking.calculateCustomAmount(promo, jsonData);
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
			quoteBookingSelected();
		}
	});

	$('#bkg_state_tax_custom').change(function () {
		if (jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_state_tax').val($('#bkg_state_tax_custom').val());
			admBooking.calculateCustomAmount(promo, jsonData);
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
			quoteBookingSelected();
		}
	});

	$('#bkg_convenience_charge_custom').change(function () {
		if (jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_convenience_charge').val($('#bkg_convenience_charge_custom').val());
			admBooking.calculateCustomAmount(promo, jsonData);
			agtPaidPerVal = $('input[name="agentpaidrad"]:checked').val();
			admBooking.calculateAgentPaidAmount(agtPaidPerVal);
		}
		quoteBookingSelected();
	});

	$('#bkg_rate_per_km_extra_custom').change(function () {
		if (jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_rate_per_km_extra').val($('#bkg_rate_per_km_extra_custom').val());
			$('#vehicle_dist_ext').html("Note: Ext. Chrg. After " + $("#bkg_trip_distance_custom").val() + " Kms. = " + $('#bkg_rate_per_km_extra_custom').val() + "/Km.");
			admBooking.checkChangedPaymentInfo();
			quoteBookingSelected();
		}
	});

	$('#bkg_rate_per_min_extra_custom').change(function () {
		if (jsonData.trip_user == 2)
		{
			$('#BookingInvoice_bkg_extra_per_min_charge').val($('#bkg_rate_per_min_extra_custom').val());
			$('#vehicle_dist_ext').html("Note: Ext. Chrg. After " + $("#bkg_trip_distance_custom").val() + " Min. = " + $('#bkg_rate_per_min_extra_custom').val() + "/Min.");
			admBooking.checkChangedPaymentInfo();
		}
		quoteBookingSelected();
	});


	$('#bkg_rate_per_km_extra_standard').change(function () {
		$('#BookingInvoice_bkg_rate_per_km_extra').val($('#bkg_rate_per_km_extra_standard').val());
		$('#vehicle_dist_ext').html("Note: Ext. Chrg. After " + $("#bkg_trip_distance_standard").val() + " Kms. = " + $('#bkg_rate_per_km_extra_standard').val() + "/Km.");
		admBooking.checkChangedPaymentInfo();
		quoteBookingSelected();
	});

	$('#bkg_trip_distance_standard').change(function () {
		var changedDistance = $('#bkg_trip_distance_standard').val();
		$('#Booking_bkg_trip_distance').val($('#bkg_trip_distance_standard').val());
		$('#tot_est_dist').val($('#bkg_trip_distance_standard').val());
		admBooking.getAmountbyCitiesnVehicle(booking, jsonData, 'payment', 1);
		admBooking.calculateAmount(jsonData);
		$('#vehicle_dist_ext').html("Note: Ext. Chrg. After " + $("#bkg_trip_distance_custom").val() + " Kms. = " + $('#bkg_rate_per_km_extra_custom').val() + "/Km.");
		admBooking.changeCustomPaymentData();
		$('#vehicle_dist_ext').html("Note: Ext. Chrg. After " + $("#bkg_trip_distance_standard").val() + " Kms. = " + $('#bkg_rate_per_km_extra_standard').val() + "/Km.");
		admBooking.checkChangedPaymentInfo();
		$('#Booking_bkg_trip_distance').val(changedDistance);
		$('#tot_est_dist').val(changedDistance);
		$('#bkg_trip_distance_standard').val(changedDistance);
		quoteBookingSelected();
	});

	$('#bkg_trip_distance_custom').change(function () {
		var changedDistance = $('#bkg_trip_distance_custom').val();
		$('#Booking_bkg_trip_distance').val($('#bkg_trip_distance_custom').val());
		$('#tot_est_dist').val($('#bkg_trip_distance_custom').val());

		var data = JSON.parse($('#jsonData_payment').val());
		if (data.bkg_service_class > 0) {
			var sccData = {"bkg_service_class": data.bkg_service_class};
			$.extend(true, jsonData, sccData);
		}
		admBooking.getAmountbyCitiesnVehicle(booking, jsonData, 'payment', 1);
		admBooking.calculateAmount(jsonData);
		$('#vehicle_dist_ext').html("Note: Ext. Chrg. After " + $("#bkg_trip_distance_custom").val() + " Kms. = " + $('#bkg_rate_per_km_extra_custom').val() + "/Km.");
		admBooking.changeCustomPaymentData();
		admBooking.checkChangedPaymentInfo();
		$('#Booking_bkg_trip_distance').val(changedDistance);
		$('#tot_est_dist').val(changedDistance);
		$('#bkg_trip_distance_custom').val(changedDistance);
		quoteBookingSelected();
	});

	$('#bkg_trip_duration_standard').change(function () {
		$('#Booking_bkg_trip_duration').val($('#bkg_trip_duration_standard').val());
		admBooking.checkChangedPaymentInfo();
		quoteBookingSelected();
	});

	$('#bkg_trip_duration_custom').change(function () {
		$('#Booking_bkg_trip_duration').val($('#bkg_trip_duration_custom').val());
		admBooking.checkChangedPaymentInfo();
		quoteBookingSelected();
	});

	$('#bkg_total_amount_custom').change(function () {
		$('#Booking_agentCreditAmount').val($('#BookingInvoice_bkg_total_amount').val()).change();
		admBooking.checkChangedPaymentInfo();
		quoteBookingSelected();
	});

	$("#BookingInvoice_bkg_promo1_code").change(function ()
	{
		$("#BookingInvoice_bkg_discount_amount").val('');
		if ($('input[name="pricerad"]:checked').val() == 'custom')
		{
			admBooking.getDiscount(promo, jsonData);
			admBooking.changeCustomPaymentData();
		} else
		{
			admBooking.getDiscount(promo, jsonData);
			admBooking.changeStandardPaymentData();
			admBooking.changeCustomPaymentData();
		}
		$('#bkg_discount_amount_standard').val($('#BookingInvoice_bkg_discount_amount').val());
		admBooking.checkChangedPaymentInfo();
		quoteBookingSelected();
	});

	$('#bkg_driver_allowance_amount_standard').change(function ()
	{
		$("#BookingInvoice_bkg_driver_allowance_amount").val($("#bkg_driver_allowance_amount_standard").val());
		admBooking.getDiscount(promo, jsonData);
		admBooking.calculateAmount(jsonData);
		admBooking.changeStandardPaymentData();
		admBooking.checkChangedPaymentInfo();
		quoteBookingSelected();
	});

	$('#bkg_toll_tax_standard').change(function ()
	{
		$("#BookingInvoice_bkg_toll_tax").val($("#bkg_toll_tax_standard").val());
		admBooking.getDiscount(promo, jsonData);
		admBooking.calculateAmount(jsonData);
		admBooking.changeStandardPaymentData();
		admBooking.checkChangedPaymentInfo();
		quoteBookingSelected();
	});

	$('#bkg_state_tax_standard').change(function ()
	{
		$("#BookingInvoice_bkg_state_tax").val($("#bkg_state_tax_standard").val());
		admBooking.getDiscount(promo, jsonData);
		admBooking.calculateAmount(jsonData);
		admBooking.changeStandardPaymentData();
		admBooking.checkChangedPaymentInfo();
		quoteBookingSelected();
	});

	$('#Booking_agentCreditAmount').change(function ()
	{
		admBooking.getDiscount(promo, jsonData);
		admBooking.calculateAmount(jsonData);
		quoteBookingSelected();
	});

	$('#bkg_parking_charge_standard').change(function ()
	{
		$("#BookingInvoice_bkg_parking_charge").val($("#bkg_parking_charge_standard").val());
		admBooking.getDiscount(promo, jsonData);
		admBooking.calculateAmount(jsonData);
		admBooking.changeStandardPaymentData();
		admBooking.checkChangedPaymentInfo();
		quoteBookingSelected();
	});

	function calculateAmount() {
		admBooking.calculateAmount(jsonData);
	}

	$('input[name="agentpaidrad"]').click(function (event) {
		var paidVal = $(event.currentTarget).val();
		admBooking.calculateAgentPaidAmount(paidVal);
	});
 
	$('input[name="agtBkgCategory"]').click(function (event) {
		var agtBkgCategory = $(event.currentTarget).val();
		if (agtBkgCategory == 2)
		{
			if(!$('.divAgentBooking').hasClass('hide'))
			{
				$('.divAgentBooking').addClass('hide');
			}
		}
		if (agtBkgCategory == 1)
		{
			if($('.divAgentBooking').hasClass('hide'))
			{
				$('.divAgentBooking').removeClass('hide');
			}
		}
	});
	$("#BookingInvoice_bkg_base_amount,#BookingInvoice_bkg_discount_amount,#BookingInvoice_bkg_additional_charge").change(function ()
	{
		admBooking.getDiscount(promo, jsonData);
		admBooking.calculateAmount(jsonData);
		admBooking.changeCustomPaymentData();
		admBooking.checkChangedPaymentInfo();
		quoteBookingSelected();
	});

	$("#bkg_base_amount_standard").change(function () {
		$("#BookingInvoice_bkg_base_amount").val($("#bkg_base_amount_standard").val()).change();
		admBooking.checkChangedPaymentInfo();
		quoteBookingSelected();
	});

	$("#bkg_additional_charge_standard").change(function () {
		$("#BookingInvoice_bkg_additional_charge").val($("#bkg_additional_charge_standard").val()).change();
		admBooking.checkChangedPaymentInfo();
		quoteBookingSelected();
	});

	$(".btn-payment").click(function () {
		var vhtId = '';
		var sccData = '';
		var data = JSON.parse($('#jsonData_payment').val());
		var sccData = (data.bkg_service_class) ? data.bkg_service_class : sccData;
		vhtId = (data.modelId) ? data.modelId : vhtId;

		if (sccData == '')
		{
			admBooking.showErrors('Please Select Service Class', admBooking.elmTripDistance);
			return false;
		}
		if (sccData == 4 && vhtId == "")
		{
			admBooking.showErrors('Please Select Cab Model', admBooking.elmModelId);
			return false;
		}
		$("#paymentForm").submit();
	});

	$(".btn-editPayment").click(function () {
		if ($("#rePaymentOpt").val() == "true")
		{
			$('#additionalInfo,#vendorIns').html('');
			$('#additionalInfo,#vendorIns').addClass('hide');
			$("#rePayment").find("input").attr("disabled", false);
		} else
		{
			$('#travellerInfo,#rePayment,#additionalInfo,#vendorIns').html('');
			$('#travellerInfo,#rePayment,#additionalInfo,#vendorIns').addClass('hide');
			$("#payment").find("input").attr("disabled", false);
		}
		$(".btn-payment").addClass("btn-info");
		$(".btn-payment").removeClass("disabled");
		$(".btn-editPayment").addClass("hide");
	});

	$("#BookingInvoice_bkg_is_parking_included").click(function () {
		if ($("#BookingInvoice_bkg_is_parking_included").is(":checked") == true)
		{
			$("#BookingInvoice_bkg_is_parking_included").val(1);
			$("#bkg_parking_charge_standard").attr("readonly", false);
		} else
		{
			$("#BookingInvoice_bkg_is_parking_included").val(0);
			$("#bkg_parking_charge_standard").attr("readonly", true);
		}
		if (jsonData.trip_user == 1)
		{
			if ($("#BookingInvoice_bkg_is_parking_included").is(":checked") == true)
			{
				$("#bkg_parking_charge_standard").attr("readonly", false);
			} else
			{
				$("#bkg_parking_charge_standard").attr("readonly", true);
			}
		}
		quoteBookingSelected();
	});

	$("#BookingInvoice_bkg_is_toll_tax_included,#BookingInvoice_bkg_is_state_tax_included").click(function () {
		if ($("#BookingInvoice_bkg_is_toll_tax_included").is(":checked") == true) {
			$("#BookingInvoice_bkg_is_toll_tax_included").val(1);
		} else {
			$("#BookingInvoice_bkg_is_toll_tax_included").val(0);
		}
		if ($("#BookingInvoice_bkg_is_state_tax_included").is(":checked") == true) {
			$("#BookingInvoice_bkg_is_state_tax_included").val(1);
		} else {
			$("#BookingInvoice_bkg_is_state_tax_included").val(0);
		}
		quoteBookingSelected();
	});
	$("#BookingInvoice_bkg_is_airport_fee_included").click(function () {

		if ($("#BookingInvoice_bkg_is_airport_fee_included").is(":checked") == true)
		{
			$("#BookingInvoice_bkg_is_airport_fee_included").val(1);
			$("#bkg_airport_fee_standard").attr("readonly", true);
			$("#bkg_airport_fee_custom").attr("readonly", true);
		} else
		{
			$("#BookingInvoice_bkg_is_airport_fee_included").val(0);
			$("#bkg_airport_fee_standard").attr("readonly", false);
			$("#bkg_airport_fee_custom").attr("readonly", false);
		}
		if (jsonData.trip_user == 1)
		{
			if ($("#BookingInvoice_bkg_is_airport_fee_included").is(":checked") == true)
			{
				$("#bkg_parking_charge_standard").attr("readonly", true);
				$("#bkg_airport_fee_custom").attr("readonly", true);
			} else
			{
				$("#bkg_parking_charge_standard").attr("readonly", false);
				$("#bkg_airport_fee_custom").attr("readonly", false);
			}
		}
		quoteBookingSelected();
	});
	$('#bkg_is_fbg_type').change(function () {
		if ($("#bkg_is_fbg_type").is(":checked") == true) {
			$("#bkg_is_fbg_type").val(1);
		} else {
			$("#bkg_is_fbg_type").val(0);
		}
		admBooking.calculateCustomAmount(promo, jsonData);
	});

	function customServiceClass(sccId)
	{
		//admBooking.getCustomServiceClass(sccId);
		if (sccId == 4 || sccId == 5)
		{
			admBooking.generateCarModels(jsonData);
		}
		admBooking.showAddOn(sccId, 1);
		quoteBookingSelected();
		if(jsonData.agtBkgCategory == 2)
		{   
			$("input[name=agtBkgCategory][value='2']").prop("checked",true);
			$("input[name=agtBkgCategory][value='1']").removeAttr('checked');
			$("input[name=agtBkgCategory][value='2']").attr("checked", "checked");
			if(!$('.divAgentBooking').hasClass('hide'))
			{
				$('.divAgentBooking').addClass('hide');
			}

		}
	}

	$('#Booking_bkg_addon_ids').change(function () {
		var element = $(this).find('option:selected');
		var cost = element.attr("cost");
		admBooking.showPaymentDetails($('#Booking_bkg_addon_ids').val(), cost);
	});
	
	function quoteBookingSelected()
	{
		var agtBkgCategory = $('input[name="agtBkgCategory"]:checked').val();
		if (agtBkgCategory == 2)
		{
			if(!$('.divAgentBooking').hasClass('hide'))
			{
				$('.divAgentBooking').addClass('hide');
			}
		}
		if (agtBkgCategory == 1)
		{
			if($('.divAgentBooking').hasClass('hide'))
			{
				$('.divAgentBooking').removeClass('hide');
			}
		}
	}
</script>