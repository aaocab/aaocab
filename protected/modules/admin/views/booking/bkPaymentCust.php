
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
						$("#serviceClassDiv").hide();
						$("#addOnDiv").hide();
						$("#addOnCabDiv").hide();
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
 <input type="hidden" id="allowNegativeAddon" value="1">
<?= CHtml::hiddenField("jsonData_payment", $data, ['id' => 'jsonData_payment']) ?>

<?
$readOnly	 = ($flag == 1) ? 'readonly' : false;
$isGozoNow	 = 0;
$staxrate						 = BookingInvoice::getGstTaxRate($model->bkg_agent_id, $model->bkg_booking_type);
if ($data != "")
{
	$jsonDataPayment = json_decode($data);
	$bookingType	 = $jsonDataPayment->bkg_booking_type;
	$isGozoNow		 = $jsonDataPayment->isGozonow;
	$staxrate						 = BookingInvoice::getGstTaxRate($jsonDataPayment->bkg_agent_id, $bookingType);
}
?>
<div class="row">


	<div class="col-xs-12">
		<div class="panel panel-default panel-border">
			<div class="alert alert-block">  
				<div class="mt20">
					<?php
					// print_r($note);exit;
					if (!empty($note))
					{
						?>
						<div class="row">
							<div class="col-xs-12" id="linkedusers"><div class="panel panel-primary panel-border compact">
									<div class="panel-heading heading_box" style="min-height:0">Special instructions & advisories that may affect your planned travel</div>
									<div aria-describedby="caption" class="table" role="grid">
										<div class="tr" role="row">
											<div class="th smallCol" role="columnheader">
												Place
											</div>
											<div class="th bigCol" role="columnheader">
												Note
											</div>
											<div class="th smallCol" role="columnheader">
												Valid From
											</div>
											<div class="th smallCol" role="columnheader">
												Valid To
											</div>
											<div class="th smallCol" role="columnheader">
												Applicable For
											</div>
										</div>
										<?php
										for ($i = 0; $i < count($note); $i++)
										{
											?>  
											<div class="tr" role="row">
												<div class="th smallCol" role="rowheader">
													<?php
													if ($note[$i]['dnt_area_type'] == 1)
													{
														?>
														<?= ($note[$i]['dnt_zone_name']) ?>
													<?php } ?>
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


												</div>
												<div class="td bigCol" role="gridcell">
													<?= ($note[$i]['dnt_note']) ?>
												</div>
												<div class="td smallCol" role="gridcell">
													<?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_from'])) ?>
												</div>
												<div class="td smallCol" role="gridcell">
													<?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_to'])) ?>
												</div>
												<div class="td smallCol" role="gridcell">
													<?php
													$dataArr = explode(",", ($note[$i]['dnt_show_note_to']));
													foreach ($dataArr as $showNoteTo)
													{

														if ($showNoteTo == 1)
														{
															echo "Consumer" . ", ";
														}
														else if ($showNoteTo == 2)
														{
															echo "Vendor" . ", ";
														}
														else if ($showNoteTo == 3)
														{
															echo "Driver" . ", ";
														}
														//destination notes by Rituparana
														//else if ($showNoteTo == 5)
														//{
														//echo "Agent" . ", ";
														//}
														else
														{
															echo "";
														}
													}
													?>

												</div>
											</div>
											<?php
										}
										?>
									</div>
								</div></div>
						</div>
						<?php
					}
					?>
				</div>
			</div>   

			<div class="form-group panel-body pt0" id="serviceClassDiv"></div>
			<?php // echo $form->hiddenField($model, 'bkg_service_class');  ?>
			<?php echo $form->hiddenField($invModel, 'bkg_addon_ids'); ?>


			<input type="hidden" id="customsccclass" value="">
			<div class="form-group panel-body pt0" id="serviceClassDivCustom">
				<?php
				$serviceClasses = ServiceClass::getAll();
				foreach ($serviceClasses as $value)
				{
					if ($value['scc_id'] != 5 && $value['scc_id'] != 4)
					{
						$scvId = SvcClassVhcCat::getSvcClassIdByVehicleCat($vhcId, $value['scc_id']);
						if ($quotes[$scvId] == false)
						{
							continue;
						}
						if ($quotes[$scvId]->routeRates->baseAmount > 0 || $bookingType == 8)
						{
							?>
							<div class="btn customserclass  serviceClass<?= $value['scc_id'] ?> col-xs-2 p16 mb10 btn-widget-1"  onclick="customServiceClass(<?= $value['scc_id'] ?>);">
								<?php ?>
								<input type="hidden" class="scvId<?= $value['scc_id'] ?>" value="<?= $scvId ?>" >
								<div class="">
									<label class="sccLabel"><?php echo $value['scc_label'] ?></label>	
									<?
									if ($bookingType != 8)
									{
										?><label class="sccLabel"><i class="fa fa-inr serviceclassinr"></i><?php echo $quotes[$scvId]->routeRates->baseAmount+49 ?></label><? } ?>
								</div>
							</div>

							<?php
						}
					}
				}
				?>
			</div>	
			<?php
			if (!$jsonDataPayment->bkg_addon_ids && $jsonDataPayment->isGozonow == 0)
			{
				?>
				<div class="form-group panel-body pt0" id="addOnDiv">
					<div class="col-sm-6 pt20 mt5">
						<label class="control-label" for="exampleInputCompany6">Choose a cancellation policy</label>
						<?php
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $invModel,
							'attribute'		 => 'bkg_addon_ids',
							'val'			 => $invModel->bkg_addon_ids,
							'data'			 => [],
							'htmlOptions'	 => array('style' => 'width:100%', 'id' => 'Booking_bkg_addon_ids', 'placeholder' => 'Select Cancellation policy Addons', 'class' => 'route-focus')
						));
						?>
						<span class="has-error"><? echo $form->error($model, 'bkg_addon_ids'); ?></span>
					</div>
					<div class="col-sm-6 pt20 mt5" id="addonDetailsDiv"></div>
				</div>
				<?php
			}
			if (!$jsonDataPayment->bkg_addon_cab && $jsonDataPayment->bkg_service_class != 4 && $jsonDataPayment->isGozonow == 0)
			{
				?>
				<div class="form-group panel-body pt0" id="addOnCabDiv">
					<div class="col-sm-6 pt20 mt5">
						<label class="control-label" for="exampleInputCompany6">Choose your preferred cab model</label>
						<?php
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $invModel,
							'attribute'		 => 'bkg_addon_cab',
							'val'			 => $invModel->bkg_addon_cab,
							'data'			 => [],
							'htmlOptions'	 => array('style' => 'width:100%', 'id' => 'Booking_bkg_addon_cab', 'placeholder' => 'Select Cab Model Addons', 'class' => 'route-focus')
						));
						?>
						<span class="has-error"><? echo $form->error($model, 'bkg_addon_cab'); ?></span>
					</div>
				</div>
			<?php } ?>
			<div class="form-group panel-body carModel hide">
				<div class="col-sm-6 pt20 mt5 selectmodel">
					<label class="control-label" for="exampleInputCompany6">Select Car Model</label>
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
					<span class="has-error"><? echo $form->error($model, 'bkg_vht_id'); ?></span>
				</div>
			</div>
			<?php
			if ($jsonDataPayment->isGozonow == 1)
			{
				?>
				<div class="col-xs-12 panel-body text-primary">Gozo NOW is enabled for this booking. All prices shown here by Gozo sales team are only representative. The actual price of the booking may be much higher or lower as it will be based on the inventory situation & real-time offers chosen by the customer on Gozo NOW screen.</div>
				<?php
			}
			?>		
			<div class="paymentDetailsDiv" >	
				<h3 class="pl15">Payment information and terms</h3>

				<?= $form->hiddenField($invModel, 'bkg_chargeable_distance'); ?>
				<?= $form->hiddenField($trcModel, 'bkg_garage_time'); ?>
				<? //= $form->hiddenField($invModel, 'bkg_is_toll_tax_included');   ?>
				<? //= $form->hiddenField($invModel, 'bkg_is_state_tax_included');   ?>
				<?= $form->hiddenField($invModel, 'bkg_gozo_base_amount'); ?>
				<?
				$toll_checked			 = ($invModel->bkg_is_toll_tax_included == 1) ? 'checked="checked"  disabled="disabled"' : "";
				$state_checked			 = ($invModel->bkg_is_state_tax_included == 1) ? 'checked="checked" disabled="disabled"' : "";
				$parking_checked		 = ($invModel->bkg_is_parking_included == 1) ? 'checked="checked" disabled="disabled"' : "";
				$airportEntryFeeChecked	 = ($invModel->bkg_is_airport_fee_included == 1) ? 'checked="checked" disabled="disabled"' : "";
				?>
				<input type="hidden" name="paymentChangesData" id="paymentChangesData">

				<div class="form-group panel-body pt0 ">
					<div class ="row">
						<div class="col-sm-6"></div>
						<div class="col-sm-6 standard-paybox ">
							<div class="from-group">
								<label class="control-label" for="bkg_trip_distance_standard">Trip Distance</label>
								<?= $form->textField($model, 'bkg_trip_distance', ['class' => 'form-control pay-focus', 'placeholder' => 'In km.']) ?>
								<input type="hidden" id="bkg_trip_distance_standard" value="">
							</div>
						</div>
					</div>
					<div class ="row">
						<div class="col-sm-6"></div>
						<div class="col-sm-6 standard-paybox ">
							<div class="from-group">
								<label class="control-label" for="bkg_trip_duration_standard">Trip Duration</label>
								<?= $form->textField($model, 'bkg_trip_duration', ['class' => 'form-control pay-focus', 'placeholder' => 'In Min']) ?>
								<input type="hidden" id="bkg_trip_duration_standard" value="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6"></div>
						<div class="col-sm-6 standard-paybox ">
							<div class="from-group">
								<label class="control-label" for="bkg_rate_per_km_extra_standard">Rate per Extra Km.</label>
								<input type="hidden" id="bkg_rate_per_km_extra_standard" value="">
								<?= $form->textField($invModel, 'bkg_rate_per_km_extra', ['class' => 'form-control pay-focus']) ?>
							</div>
							<div id="errordivrate" class="mt5 " style="color:#da4455"></div>
						</div>
					</div>
					<?php
					if ($bookingType == 9 || $bookingType == 10 || $bookingType == 11)
					{
						?>
						<div class="row">
							<div class="col-sm-6"></div>
							<div class="col-sm-6 standard-paybox ">
								<div class="from-group">
									<label class="control-label" for="bkg_rate_per_min_extra_standard">Rate per Extra Min.</label>
									<input type="hidden" id="bkg_rate_per_min_extra_standard" value="">
									<?= $form->textField($invModel, 'bkg_extra_per_min_charge', ['class' => 'form-control pay-focus']) ?>
								</div>
								<div id="errordivrate" class="mt5 " style="color:#da4455"></div>
							</div>
						</div>
						<?php
					}
					?>
					<div class="row hide bkg-cust-pay">
						<div class="col-sm-6"></div>
						<div class="col-sm-6 standard-paybox ">
							<div class="from-group">
								<label class="control-label" for="bkg_rate_per_km_standard">Rate per Km.</label>
								<input type="hidden" id="bkg_rate_per_km_standard" value="">
								<?= $form->textField($invModel, 'bkg_rate_per_km', ['class' => 'form-control pay-focus']) ?>
							</div>
							<div id="errordivrate" class="mt5 " style="color:#da4455"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6"></div>
						<div class="col-sm-6 standard-paybox ">
							<?
							$readonly = "";
							if (!Yii::app()->user->checkAccess('accountEdit'))
							{
								$readonly = 'readonly';
							}
							?>
							<div class="from-group">
								<label class="control-label" for="bkg_base_amount_standard">Amount</label>
								<input type="hidden" id="bkg_base_amount_standard" value="">
								<?= $form->textField($invModel, 'bkg_base_amount', ['class' => 'form-control pay-focus', 'placeholder' => 'Net Charge', 'readonly' => $readonly]) ?>
							</div>
							<div id="trip_rate"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6"></div>
						<div class="col-sm-6">
							<div class="from-group">
								<label class="control-label" for="bkg_addon_charges">Addon Charge</label>
								<input type="hidden" id="bkg_addon_charges" value="">
								<input type="hidden" id="bkg_addon_details" value="">
								<?= $form->textField($invModel, 'bkg_addon_charges', ['class' => 'form-control pay-focus', 'placeholder' => 'Addon Charge', 'readonly' => 'readonly']) ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<?= $form->textFieldGroup($invModel, 'bkg_additional_charge_remark', array('widgetOptions' => array('htmlOptions' => ['class' => 'pay-focus', 'readonly' => $readOnly]))) ?>
						</div>
						<div class="col-sm-6 standard-paybox ">
							<div class="from-group">
								<label class="control-label" for="bkg_additional_charge_standard">Additional Charge</label>
								<input type="hidden" id="bkg_additional_charge_standard" value="">
								<?= $form->textField($invModel, 'bkg_additional_charge', ['class' => 'form-control pay-focus', 'readonly' => $readOnly]) ?>
							</div>
						</div>
					</div>
					<input type="hidden" id="bkg_discount_amount_standard" value="">
					<?
					if ($isGozoNow != 1)
					{
						?>
						<div class="row">
							<div class="col-sm-6">
								<?php
//								if (!$flag)
//								{
								echo $form->textFieldGroup($invModel, 'bkg_promo1_code', array('label' => 'Promo Code', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Promo Code', 'class' => 'pay-focus adm-promo-apply'])))
								?>
								<?php // }       ?> 	
								<span class="text-danger" id="promocreditsucc"></span>
							</div>
							<!-- new feature--->
							<div class="col-sm-6 standard-paybox ">
								<div class="from-group">
									<label class="control-label" for="bkg_discount_amount_standard">Discount</label>
									<?= $form->textField($invModel, 'bkg_discount_amount', ['class' => 'form-control pay-focus', 'placeholder' => 'Discount', 'readonly' => 'readonly']) ?>
								</div>
							</div>
						</div>
						<?php
					}
					else
					{
						echo $form->hiddenField($invModel, 'bkg_discount_amount',['value'=>'0']);
					}
					?> 	
					<div class="row"> 
						<div class="col-sm-6">

						</div>
						<input type="hidden" name="rtevndamt" id="rtevndamt">
						<?= $form->hiddenField($invModel, 'bkg_quoted_vendor_amount'); ?>

						<div class="col-sm-6 standard-paybox ">
							<div class="from-group">
								<label class="control-label" for="bkg_vendor_amount_standard">Vendor Amount</label>
								<input type="hidden" id="bkg_vendor_amount_standard" value="">
								<?= $form->textField($invModel, 'bkg_vendor_amount', ['class' => 'form-control pay-focus', 'readonly' => 'readonly']) ?>
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
						<div class="col-sm-6 standard-paybox ">
							<div class="from-group">
								<label class="control-label" for="bkg_driver_allowance_amount_standard">Driver Allowance</label>
								<input type="hidden" id="bkg_driver_allowance_amount_standard" value="">
								<?= $form->textField($invModel, 'bkg_driver_allowance_amount', ['class' => 'form-control pay-focus', 'placeholder' => 'Driver allowance', 'oldamount' => 0, 'readonly' => true]); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 pt20">Parking Included <span class="checkerparkingtax"><input type="checkbox" name="BookingInvoice[bkg_is_parking_included]" class="pay-focus" id="BookingInvoice_bkg_is_parking_included" <?= $parking_checked ?>></span></div>
						<? //= $form->hiddenField($invModel, 'bkg_is_parking_included');             ?>	
						<div class="col-sm-6 standard-paybox ">
							<div class="from-group">
								<label class="control-label" for="bkg_parking_charge_standard">Extra Parking charges upto</label>
								<input type="hidden" id="bkg_parking_charge_standard" value="">
								<?= $form->textField($invModel, 'bkg_parking_charge', ['class' => 'form-control pay-focus', 'placeholder' => 'Parking', 'readonly' => 'readonly']) ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 pt20">Toll tax Included <span class="checkertolltax"><input type="checkbox" name="BookingInvoice[bkg_is_toll_tax_included]" class="pay-focus" id="BookingInvoice_bkg_is_toll_tax_included" <?= $toll_checked ?>></span></div>
						<div class="col-sm-6 standard-paybox ">
							<div class="from-group">
								<label class="control-label" for="bkg_toll_tax_standard">Toll Tax</label>
								<input type="hidden" id="bkg_toll_tax_standard" value="">
								<?= $form->textField($invModel, 'bkg_toll_tax', ['class' => 'form-control pay-focus', 'placeholder' => 'Toll Tax', 'readonly' => 'readonly']) ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 pt20">
							State tax Included <span class="checkerstatetax"><input type="checkbox" name="BookingInvoice[bkg_is_state_tax_included]" class="pay-focus" id="BookingInvoice_bkg_is_state_tax_included" <?= $state_checked ?>></span>

						</div>
						<div class="col-sm-6 standard-paybox ">
							<div class="from-group">
								<label class="control-label" for="bkg_state_tax_standard">State Tax</label>
								<input type="hidden" id="bkg_state_tax_standard" value="">
								<?= $form->textField($invModel, 'bkg_state_tax', ['class' => 'form-control pay-focus', 'placeholder' => 'State Tax', 'readonly' => 'readonly']) ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 pt20">
							Airport Entry Fee Included <span class="checkentryfee"><input type="checkbox" name="BookingInvoice[bkg_is_airport_fee_included]" class="pay-focus" id="BookingInvoice_bkg_is_airport_fee_included" <?= $airportEntryFeeChecked ?>></span>

						</div>
						<div class="col-sm-6 standard-paybox ">
							<div class="from-group">
								<label class="control-label" >Airport Entry Charge</label>
								<?= $form->textField($invModel, 'bkg_airport_entry_fee', ['class' => 'form-control pay-focus', 'placeholder' => 'Airport entry Charge', 'readonly' => 'readonly']) ?>
							</div>
						</div>
					</div>
					<div class="row hide">
						<div class="col-sm-6">
						</div>
						<div class="col-sm-6 standard-paybox ">
							<div class="from-group">
								<label class="control-label" for="bkg_convenience_charge_standard">Collect on delivery(COD) fee</label>
								<input type="hidden" id="bkg_convenience_charge_standard" value="">
								<?= $form->textField($invModel, 'bkg_convenience_charge', ['class' => 'form-control pay-focus', 'readonly' => 'readonly']) ?> 
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
						<div class="col-sm-6 standard-paybox ">
							<? $invModel->bkg_service_tax_rate	 = $staxrate; ?>
							<?= $form->hiddenField($invModel, 'bkg_service_tax_rate'); ?>
							<div class="from-group">
								<label class="control-label" for="bkg_service_tax_standard"><?= $taxLabel . " (rate: " . $staxrate . "%)" ?></label>
								<input type="hidden" id="bkg_service_tax_standard" value="">
								<?= $form->textField($invModel, 'bkg_service_tax', ['class' => 'form-control pay-focus', 'readonly' => 'readonly']) ?>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label" for="amountwithoutcodstandard">Total Amount(Without COD)</label>
								<input readonly="readonly" class="form-control pay-focus" name="amountwithoutcod" id="amountwithoutcod" type="text" value="0">
							</div> 
						</div>
						<div class="col-sm-6 ">
							<div class="from-group">
								<label class="control-label" for="bkg_total_amount_standard">Total Chargeable <?= $invModel->getAttributeLabel('bkg_total_amount') ?></label>
								<input type="hidden" id="bkg_total_amount_standard" value="">
								<?= $form->textField($invModel, 'bkg_total_amount', ['class' => 'form-control pay-focus', 'readonly' => 'readonly']) ?>
							</div>
						</div>

					</div>
					<div class="row hide" id="div_due_amount">
						<div class="col-xs-6 pull-right">
							<label class="text-info">Total Due Amount(Paid By Traveller)</label> 
							<div class="form-control" id="id_due_amount"><?= $invModel->bkg_due_amount ?></div>
						</div>
					</div>

					<?
					$tripdistance					 = ($model->bkg_trip_distance != '' && $model->bkg_trip_distance > 0) ? $model->bkg_trip_distance : 0;
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
				</div></div>
		</div>
	</div>
</div>  
<?php $this->endWidget(); ?>
<script>
	var jsonData = JSON.parse($('#jsonData_payment').val());
	$(document).ready(function () {
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
		trip_distance = 0;
		if ($('#jsonData_travellerInfo').val() != "" && $('#jsonData_travellerInfo').val() != undefined && $('#jsonData_travellerInfo').val() != 'undefined' && $('#jsonData_travellerInfo').val() != null) {
			$("#serviceClassDivCustom").hide();
			if (jsonData.tot_est_dist != jsonData.bkg_trip_distance)
			{
				$('#Booking_bkg_trip_distance').val(jsonData.bkg_trip_distance);
				var changedDistance = jsonData.bkg_trip_distance;
				$('#tot_est_dist').val(jsonData.bkg_trip_distance);
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
		}
		// $('.adm-promo-apply').attr('readonly', false);
		if (jsonData.bkg_booking_type == 8)
		{
			$('.standard-paybox').find('input').attr('readonly', false);
			$('.bkg-cust-pay').removeClass('hide');
			$('.customserclass').removeClass('hide');
			$('.serviceclassinr').addClass('hide');
			$('BookingInvoice_bkg_driver_allowance_amount').attr('readonly', false);
		}

//        if ($('#paymentChangesData').val() == '')
//        {
//            admBooking.changeStandardPaymentData();
//        }
		if (jsonData.bkg_booking_type == 8)
		{
			$('#Booking_bkg_trip_distance').val($('#bkg_trip_distance_standard').val());
			$('#Booking_bkg_trip_duration').val($('#bkg_trip_duration_standard').val());
			$('#addOnDiv').hide();
			$('#addOnCabDiv').hide();
		}
	});

	$('#BookingInvoice_bkg_rate_per_km').change(function () {
		if (jsonData.bkg_booking_type == 8)
		{
			admBooking.checkChangedPaymentInfo();
		}
	});

	$('#BookingInvoice_bkg_vendor_amount').change(function () {
		if (jsonData.bkg_booking_type == 8)
		{
			$('#BookingInvoice_bkg_quoted_vendor_amount').val($('#BookingInvoice_bkg_vendor_amount').val());
		}
		$('#rtevndamt').val($('#BookingInvoice_bkg_vendor_amount').val());
		admBooking.checkChangedPaymentInfo();
	});

	$('#BookingInvoice_bkg_rate_per_km_extra').change(function () {
		$('#vehicle_dist_ext').html("Note: Ext. Chrg. After " + $("#Booking_bkg_trip_distance").val() + " Kms. = " + $('#BookingInvoice_bkg_rate_per_km_extra').val() + "/Km.");
		admBooking.checkChangedPaymentInfo();
	});

	$('#Booking_bkg_trip_distance').change(function () {
		var changedDistance = $('#Booking_bkg_trip_distance').val();
		$('#tot_est_dist').val($('#Booking_bkg_trip_distance').val());

		var data = JSON.parse($('#jsonData_payment').val());
		if (data.bkg_service_class > 0) {
			var sccData = {"bkg_service_class": data.bkg_service_class};
			$.extend(true, jsonData, sccData);
		}

		admBooking.getAmountbyCitiesnVehicle(booking, jsonData, 'payment', 1);
		admBooking.calculateAmount(jsonData);
		$('#vehicle_dist_ext').html("Note: Ext. Chrg. After " + $("#Booking_bkg_trip_distance").val() + " Kms. = " + $('#BookingInvoice_bkg_rate_per_km_extra').val() + "/Km.");
		admBooking.checkChangedPaymentInfo();
		$('#Booking_bkg_trip_distance').val(changedDistance);
		$('#tot_est_dist').val(changedDistance);
		if (data.hasOwnProperty('bkg_addon_details'))
		{
			addonCpId = (typeof (data.bkg_addon_details.type1) != 'undefined') ? data.bkg_addon_details.type1.adn_id : 0;
			addonCmId = (typeof (data.bkg_addon_details.type2) != 'undefined') ? data.bkg_addon_details.type2.adn_id : 0;
			if (addonCpId)
			{
				$("#Booking_bkg_addon_ids").select2("val", addonCpId).trigger('change');
			}
			if (addonCmId)
			{
				$("#Booking_bkg_addon_cab").select2("val", addonCmId).trigger('change');
			}
		}
	});

	$('#Booking_bkg_trip_duration').change(function () {
		admBooking.checkChangedPaymentInfo();
	});

	$("#BookingInvoice_bkg_promo1_code").change(function ()
	{
		if (jsonData.isGozonow == 1 && $("#BookingInvoice_bkg_promo1_code").val() != '') {
			$("#BookingInvoice_bkg_promo1_code").val("");
			$("#BookingInvoice_bkg_promo1_code_em_").removeAttr('style');
			$("#BookingInvoice_bkg_promo1_code_em_").addClass('text-danger');
			$("#BookingInvoice_bkg_promo1_code_em_").text('Promo is not applicable for Gozo Now booking.');
			return;
		}
		$("#BookingInvoice_bkg_discount_amount").val('');
		admBooking.getDiscount(promo, jsonData);
		admBooking.changeCustomPaymentData();
	});

	function calculateAmount() {
		admBooking.calculateAmount(jsonData);
	}

	$("#BookingInvoice_bkg_base_amount,#BookingInvoice_bkg_discount_amount,#BookingInvoice_bkg_additional_charge,#BookingInvoice_bkg_parking_charge,#BookingInvoice_bkg_state_tax,#BookingInvoice_bkg_toll_tax,#BookingInvoice_bkg_driver_allowance_amount,#BookingInvoice_bkg_service_tax").change(function ()
	{
		var addCharge = $('#BookingInvoice_bkg_additional_charge').val();
		if (addCharge != 0)
		{
			$('#BookingInvoice_bkg_additional_charge').val(Math.abs(addCharge));
		}
		admBooking.getDiscount(promo, jsonData);
		admBooking.calculateAmount(jsonData);
		admBooking.checkChangedPaymentInfo();
	});

	$(".btn-payment").click(function () {
		var vhtId = '';
		var sccData = '';
		var data = JSON.parse($('#jsonData_payment').val());
		var sccData = (data.bkg_service_class) ? data.bkg_service_class : sccData;
		var tollTax = $('#BookingInvoice_bkg_toll_tax').val();
		var stateTax = $('#BookingInvoice_bkg_state_tax').val();
		var airportFee = $('#BookingInvoice_bkg_airport_entry_fee').val();
		var tollTaxInclude = $('#BookingInvoice_bkg_is_toll_tax_included').val();
		var stateTaxInclude = $('#BookingInvoice_bkg_is_state_tax_included').val();
		var airportFeeInclude = $('#BookingInvoice_bkg_is_airport_fee_included').val();
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
		if (tollTax > 0 && tollTaxInclude == 0) {
			admBooking.showErrors('Please Check Toll Tax Included', admBooking.elmTollTax);
			return false;
		}
		if (stateTax > 0 && stateTaxInclude == 0) {
			admBooking.showErrors('Please Check State Tax Included', admBooking.elmStateTax);
			return false;
		}
		if (airportFee > 0 && airportFeeInclude == 0) {
			admBooking.showErrors('Please Check Airport Entry Fee Included', admBooking.elmAirportFee);
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
		} else
		{
			$("#BookingInvoice_bkg_is_parking_included").val(0);
		}
		if (jsonData.trip_user == 1)
		{
			if ($("#BookingInvoice_bkg_is_parking_included").is(":checked") == true)
			{
				$("#BookingInvoice_bkg_parking_charge").attr("readonly", false);
			} else
			{
				$("#BookingInvoice_bkg_parking_charge").attr("readonly", true);
			}
		}
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
	});

	$('.bktype').click(function (event) {
		var bkType = $(event.currentTarget).data('type');
		$('.bktype').removeClass('btn-success');
		$(event.currentTarget).toggleClass('btn-success')
		$('.selectaddons').removeClass('hide');
	});

	function customServiceClass(sccId)
	{	
		//admBooking.getCustomServiceClass(sccId);
		if (sccId == 4 || sccId == 5)
		{
			admBooking.generateCarModels(jsonData);
		}
		else
		{
		$("#Booking_bkg_vht_id").select2('val','0');
        $("#Booking_bkg_vht_id").val('');	
		var modelData = {"modelId": $('#Booking_bkg_vht_id').val()};
		$.extend(true, jsonData, modelData);
		$('#jsonData_payment').val(JSON.stringify(jsonData));
		}
		admBooking.showAddOn(sccId, 1);
	}

	$('#Booking_bkg_addon_ids').change(function () { debugger;
		var element = $(this).find('option:selected');
		var cost = parseInt(element.attr("cost"));
		var details = element.attr("details");
		
		let allowNegativeAddon = $('#allowNegativeAddon').val();
		let promocode = $('#oldPromoCode').val();
		if(allowNegativeAddon!=1 && notEmpty(promocode) && cost < 0)
		{
			alert("Negative addon is not allowed with the given promo");
			$("#Booking_bkg_addon_ids").select2("val", 0).trigger('change');
			return false;
		}
		
		admBooking.showPaymentDetails($('#Booking_bkg_addon_ids').val(), cost, 1);
		$('#addonDetailsDiv').html("<b>Addon Details: </b><br>" + details);
	});
	function notEmpty(value) {
		 return value !== null && value !== undefined && value.trim().length > 0;
	}
	$('#Booking_bkg_addon_cab').change(function () { //debugger;
		var element = $(this).find('option:selected');
		var cost = element.attr("cost");
		admBooking.showPaymentDetails($('#Booking_bkg_addon_cab').val(), cost, 2);
	});


</script>
<style>
	.tr {
		display: flex;
	}
	.th, .td {
		border-top: 1px solid #ccc;
		border-right: 1px solid #ccc;
		padding: 4px 8px;
		flex: 1;
		font-size:14px;
		overflow-wrap: break-word;
		word-wrap: break-word;
		overflow: auto;
	}
	.bigCol
	{
		max-width:40%;
	}
	.smallCol
	{
		max-width:15%;		
	}
	.th {
		font-weight: bold;
	}
	.th[role="rowheader"] {
		background-color: #fff;
	}
	.th[role="columnheader"] {
		background-color: #fff;
	}
</style>