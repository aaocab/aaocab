<?php
$totalAdvance	 = PaymentGateway::model()->getTotalAdvance($model->bkg_id);
$vehicalModel	 = new VehicleTypes();
$vctId			 = $model->bkgSvcClassVhcCat->scv_vct_id;
$car_type		 = SvcClassVhcCat::model()->getVctSvcList('string', '', $vctId);
$cabModel		 = VehicleCategory::model()->findByPk($vctId);

$model->bkgInvoice->calculateConvenienceFee(0);
$model->bkgInvoice->calculateTotal();
//$carType= VehicleTypes::model()->getVehicleTypeById($model->bkg_vehicle_type_id);
$priceRule					 = AreaPriceRule::model()->getValues($model->bkg_from_city_id, $model->bkg_vehicle_type_id, $model->bkg_booking_type);
$prr_day_driver_allowance	 = $priceRule['prr_day_driver_allowance'];
$prr_Night_driver_allowance	 = $priceRule['prr_night_driver_allowance'];
$cancelTimes				 = CancellationPolicyRule::getCancelationTimeRange($model->bkg_id, 1);
$freeAction					 = 0;
$paidAction					 = 0;
if (date('Y-m-d h:i:s') > date('Y-m-d h:i:s', strtotime('-24 hour', strtotime($model->bkg_pickup_date))))
{
	$freeAction = 0;
}
if (date('Y-m-d h:i:s') > date('Y-m-d h:i:s', strtotime('-6 hour', strtotime($model->bkg_pickup_date))))
{
	$paidAction = 1;
}
//getting the Price rule array for fare inclusion and exclusion
$newpriceRule	= PriceRule::getByCity($model->bkg_from_city_id, $model->bkg_booking_type, $model->bkg_vehicle_type_id);
if(!empty($newpriceRule)){$prarr = $newpriceRule->attributes; }	
?>

<div class="row">
	<div class="col-12">
		<div class="bg-white-box">
			<div class="font-20 mb10 text-uppercase"><b>Terms and Conditions</b></div>
			<div class="row">
				<div class="col-sm-6">
					<div class="row">
						<div class="col-12 font-16 mb10"><b>Cancellation information</b></div>
                        <div class="col-12">
                            <div class="row font-11">
                                <div class="col-12 mb5">
                                    <div class="bg-green3 p5 mb-1 color-white">
                                        <p class="text-center mb10 font-12">
                                            <b>Free cancellation period</b>
                                        </p>
                                        <p class="mb0 font-10"><?= date('d M Y H:i a', strtotime($model->bkg_create_date)); ?> <span class="float-right"><?= date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[0])) ?></span></p>
                                    </div>
									<?php //if(array_values($cancelTimes_new->slabs)[1] > 0) { ?>
                                    <div class="bg-orange p5 mb-1 color-white">
                                        <p class="text-center mb10 font-12">
                                            <b>Cancellation Charge: &#x20B9;<?= array_values($cancelTimes_new->slabs)[1]; ?></b>
                                        </p>
                                        <p class="mb0 font-10"><?= date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[0])) ?> <span class="float-right"><?= date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[1])) ?></span></p>
                                    </div>
									<?php //} ?>
                                    <div class="bg-red p5 mb-1 color-white">
                                        <p class="text-center mb10 font-12"><b>No Refund</b></p>
                                        <p class="mb0 font-10"><?= date('d M Y H:i a', strtotime($model->bkg_pickup_date)); ?> <span class="float-right">After this</span></p>
                                    </div>
                                </div>
                            </div>
							<div style="margin-top: 10px; line-height: 20px;">
								<?php
								$cancellationPoints = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_CANCELLATION); //print_r($cancellationPoints);
								//echo TncPoints::TNC_TYPE_CUSTOMER."====".$model->bkg_booking_type."======". $model->bkgSvcClassVhcCat->scv_scc_id."=====". TncPoints::TNC_CANCELLATION;exit;
								if (count($cancellationPoints) > 0)
								{
									echo "<ol style='font-size:10px; line-height:15px;padding-left:25px;'>";
									foreach ($cancellationPoints as $c)
									{
										echo "<li style='list-style-type:  circle'>" . $c['tnp_text'] . "</li>";
									}
									echo "</ol>";
								}
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="col-12 font-15 mb10"><b>FARE INCLUSIONS AND EXCLUSIONS: </b></div>
					<div class="row font-11"><div class="col-11">
							<?php
							$correctimg	 = '<img src="/images/email/correct.png" height="15" width="15">';
							$crossimg	 = '<img src="/images/email/cross.png" height="15" width="15">';
							?>
                                                <p><span onclick="showIncExc('toll')"><?= ($model->bkgInvoice->bkg_is_toll_tax_included == 1) ? $correctimg : $crossimg ?>  TOLL TAXES <i class="far fa-plus-square ml5"></i></span>
										<span id="tollDesc" class="hide font-10"><BR>
												<?php
												if ($model->bkgInvoice->bkg_is_toll_tax_included == 1)
												{
													?>   
													Our estimate of toll charges for travel on this route are ₹<?= ($model->bkgInvoice->bkg_toll_tax != '') ? $model->bkgInvoice->bkg_toll_tax : 0; ?>. 
													Toll taxes (even if amount is different) is already included in the trip cost<?php
												}
												else
												{
													?>
													Our estimate of toll charges  on this route are ₹<b><?= $model->bkgInvoice->bkg_toll_tax ?></b>. Any charges incurred is payable by customer.
													<?php
												}
												?>
											</span>
									</p>
									<p><span onclick="showIncExc('state')"><?= ($model->bkgInvoice->bkg_is_state_tax_included == 1) ? $correctimg : $crossimg ?>  STATE TAXES <i class="far fa-plus-square ml5"></i></span>
										<span id="stateDesc" class="hide font-10"><BR>
											<?php
											if ($model->bkgInvoice->bkg_is_state_tax_included == 1)
											{
												?>   
												Our estimate of State Tax for travel on this route are ₹<?= ($model->bkgInvoice->bkg_state_tax != '') ? $model->bkgInvoice->bkg_state_tax : 0; ?>. 
												State Taxes (even if amount is different) is already included in the trip cost<?php
											}
											else
											{
												?>
												Our estimate of State Tax on this route are ₹<b><?= $model->bkgInvoice->bkg_state_tax ?></b>. Any charges incurred is payable by customer.
												<?php
											}
											?>
											</span>
									</p>
									<p><?= $crossimg ?> MCD</p> 
									<p>
										<span onclick="showIncExc('airport')"><?= ($model->bkgInvoice->bkg_is_airport_fee_included == 1) ? $correctimg : $crossimg ?>  AIRPORT ENTRY CHARGES <?= (($model->bkgInvoice->bkg_is_airport_fee_included == 1) ?'(Rs.'.$model->bkgInvoice->bkg_airport_entry_fee.')':'');?> <i class="far fa-plus-square ml5"></i></span>
										<span id="airportDesc" class="hide font-10"><BR>
											<?php
											if ($model->bkgInvoice->bkg_is_airport_fee_included != 1)
											{
												?>   
												Our estimate of airport entry charges on this route are ₹ <?= $model->bkgInvoice->bkg_airport_entry_fee ?> . Any charges incurred is payable by customer. <?php
											}
											else
											{
												?>

												Our estimate of airport entry charges on this route are ₹<?= ($model->bkgInvoice->bkg_airport_entry_fee != '') ? $model->bkgInvoice->bkg_airport_entry_fee : 0; ?>. 
												airport entry charges (even if amount is different) is already included in the trip cost 
												<?php
											}
											?>
										</span>
									</p>
									<!--<p><?= ($model->bkgInvoice->bkg_night_pickup_included == 1 || $model->bkgInvoice->bkg_night_drop_included == 1) ? $correctimg : $crossimg ?> NIGHT CHARGES 
									<?php echo  (!empty($prarr['prr_night_start_time'] && !empty($prarr['prr_night_end_time']))?"(". date("g A",strtotime($prarr['prr_night_start_time'])). " - ". date("g A",strtotime($prarr['prr_night_end_time'])).")":'').(!empty($prarr['prr_night_driver_allowance'])?" - Rs.".$prarr['prr_night_driver_allowance']:'');?> </p>-->
									<p><?= ($model->bkgInvoice->bkg_night_pickup_included > 0) ? $correctimg : $crossimg; ?> NIGHT PICKUP CHARGES 
										<?php echo  (!empty($prarr['prr_night_start_time'] && !empty($prarr['prr_night_end_time']))?"(". date("g A",strtotime($prarr['prr_night_start_time'])). " - ". date("g A",strtotime($prarr['prr_night_end_time'])).")":'').(!empty($prarr['prr_night_driver_allowance'])?" - Rs.".$prarr['prr_night_driver_allowance']:'');?>
									</p>
									<p><?= ($model->bkgInvoice->bkg_night_drop_included > 0) ? $correctimg : $crossimg; ?>  NIGHT DROP CHARGES  
									<?php echo  (!empty($prarr['prr_night_start_time'] && !empty($prarr['prr_night_end_time']))?"(". date("g A",strtotime($prarr['prr_night_start_time'])). " - ". date("g A",strtotime($prarr['prr_night_end_time'])).")":'').(!empty($prarr['prr_night_driver_allowance'])?" - Rs.".$prarr['prr_night_driver_allowance']:'');?>
									</p>					
									<p><?= ($model->bkgInvoice->bkg_trip_waiting_charge > 0) ? $correctimg : $crossimg ?> WAITING CHARGES <!--(Rs.120 / HOUR rounded to nearest 30 MINS).--></p>
									<p><?= ($model->bkgInvoice->bkg_extra_km > 0) ? $correctimg : $crossimg ?> EXTRA CHARGES <?= '(&nbsp;<i style="font-size:11px" class="fa">&#xf156;</i>.'.round($model->bkgInvoice->bkg_rate_per_km_extra, 2).' / KM beyond '.$model->bkg_trip_distance.' KMS).'?></p>
									<p><?= $crossimg ?> GREEN TAX </p>
									<p><?= $crossimg ?> ENTRY TAXES / CHARGES</p>
									<p><span onclick="showIncExc('parking')"><?= ($model->bkgInvoice->bkg_parking_charge == 1) ? $correctimg : $crossimg; ?>  PARKING CHARGES <i class="far fa-plus-square ml5"></i></span>
										<span id="parkingDesc" class="hide font-10"><BR>
										<?php
										if ($model->bkgInvoice->bkg_parking_charge > 0)
										{
											?> Parking charges are prepaid upto ₹<?= round($model->bkgInvoice->bkg_parking_charge) ?>.<?php } ?> 
											Customer will directly pay for parking charges after the total parking cost for the trip exceeds ₹<?= round($model->bkgInvoice->bkg_parking_charge) ?>. Driver must upload all parking receipts for payments made by drive.
										</span>
									</p>
								<Br>
							<p>FINAL OUTSTANDING SHALL BE COMPUTED AFTER TRIP COMPLETION. ADDITIONAL AMOUNT, IF ANY, MAY BE PAID IN CASH TO THE DRIVER DIRECTLY.</p>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<!--
<div class="row">
	<div class="col-12">
		<div class="bg-white-box">
			<div class="font-20 mb10 text-uppercase"><b>IMPORTANT TERMS OF YOUR BOOKING</b></div>
			<div class="row">
				<div class="col-sm-6">
				<div class="row">
					<div class="col-12 font-16 mb10"><b>Cancellation information</b></div>
                        <div class="col-12">
                            <div class="row font-11">
                                <div class="col-12 mb5">
                                    <div class="bg-green3 p5 mb-1 color-white">
                                        <p class="text-center mb10 font-12">
                                            <b>Free cancellation period</b>
                                        </p>
                                        <p class="mb0 font-10"><?= date('d M Y H:i a'); ?> <span class="float-right"><?=date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[0]))?></span></p>
                                    </div>
                                    <?php //if(array_values($cancelTimes_new->slabs)[1] > 0) { ?>
                                    <div class="bg-orange p5 mb-1 color-white">
                                        <p class="text-center mb10 font-12">
                                            <b>Cancellation Charge : &#x20B9; <?=array_values($cancelTimes_new->slabs)[1];?></b>
                                        </p>
                                        <p class="mb0 font-10"><?=date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[0]))?> <span class="float-right"><?=date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[1]))?></span></p>
                                    </div>
                                    <?php //} ?>
                                    <div class="bg-red p5 mb-1 color-white">
                                        <p class="text-center mb10 font-12"><b>No Refund</b></p>
                                        <p class="mb0 font-10"><?= date('d M Y H:i a', strtotime($model->bkg_pickup_date)); ?> <span class="float-right">After this</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row font-11">
                                 
                                </div>
                            </div>
                        </div>
					</div>
				<div class="col-sm-6">
					<span class="heading-part mb10 text-uppercase"><b>Driver Details</b></span>
					<p class="mb20 color-light-blue">Driver details will be shared up to 2 hrs prior to departure.</p>
					 
					<span class="heading-part mb10 text-uppercase"><b>Stops</b></span>
					<p class="mb20 color-light-blue">This is a point to point booking and only one stop for meals is included.</p>
					 
					<span class="heading-part mb10 text-uppercase"><b>Delays</b></span>
					<p class="mb20 color-light-blue">Due to traffic or any other unavoidable reason, pickup may be delayed by 30 mins.</p>
					
					<span class="heading-part mb10 text-uppercase"><b>Hilly Regions</b></span>
					<p class="mb20 color-light-blue">AC will be switched off in hilly areas.</p>
					
					<!--<span class="heading-part mb10"><b>Receipts</b></span>
					<p class="mb20">You need to collect the receipts from driver for any toll tax, state tax, night charges or extra km paid directly to the driver during the trip. GOZO is not liable to provide invoices for such amount</p>
					--
				</div>

				<div class="col-sm-12">
					<span class="heading-part mb10 text-uppercase"><b>Included with Quotation</b></span>
					<p class="color-light-blue">
					NO route deviations allowed unless listed in itinerary 
					<?php 
					/*	if ($model->bkgInvoice->bkg_is_toll_tax_included == 1 && $model->bkgInvoice->bkg_is_state_tax_included == 1)
						{
							echo '; Toll & state taxes';
						}
                        if ($model->bkgInvoice->bkg_is_airport_fee_included == 1)
						{
							echo ', Airport Entry Charges';
						}
						if($model->bkg_booking_type == 1)
						{
						echo "<br />".$isAllowencePickupText.$br.$isAllowenceDropOffText;
						}
						?>
						<?php
						if( $prr_day_driver_allowance >0 &&( $model->bkg_booking_type == 2 || $model->bkg_booking_type == 3))
						{
						echo "<br />"."Drivers daytime allowance of Rs. ".$prr_day_driver_allowance." per day is included in quotation";
						}
						?>
						<div class="heading-journeybreak hide">
						<span class="heading-part mb10 text-uppercase"><b>Additional charges</b></span>
						 <p id="journeybreak">30 minutes break during journey (Rs. 150/-).</p>
						 </div>
						<span class="heading-part mb10 text-uppercase"><b>Customer to pay separately</b></span>
						<p>Any and all parking charges; Any parking charges
						<?php
						if ($model->bkgInvoice->bkg_is_toll_tax_included == 0 && $model->bkgInvoice->bkg_is_state_tax_included == 0)
						{
							echo '; Toll & state taxes';
						}
                        if ($model->bkgInvoice->bkg_is_airport_fee_included == 0)
						{
							echo '; Airport Entry Charges';
						}
						$night_driver_allowance_txt = ($prr_Night_driver_allowance > 0) ? "of Rs. " . $prr_Night_driver_allowance : '';
						if ($model->bkgInvoice->bkg_night_drop_included == 0 && $model->bkgInvoice->bkg_night_pickup_included == 1)
						{
							if ($model->bkg_booking_type == 1)
							{
								echo "<br />" . " Night drop allowance " . $night_driver_allowance_txt . " to be paid if drop off happens between (10pm and 6am).";
							}
							else
							{
								echo "<br />" . "Night drop allowance " . $night_driver_allowance_txt . " to be paid to driver for each night when driving between the hours of 10pm and 6am. ";
							}
						}
						if ($model->bkgInvoice->bkg_night_pickup_included == 0 && $model->bkgInvoice->bkg_night_drop_included==1)
						{
							if ($model->bkg_booking_type == 1)
							{
								echo "<br />" . " Night pickup allowance " . $night_driver_allowance_txt . " to be paid if drop off happens between (10pm and 6am).";
							}
							else
							{
								echo "<br />" . "Night pickup allowance " . $night_driver_allowance_txt . " to be paid to driver for each night when driving between the hours of 10pm and 6am. ";
							}
						}
						if ($model->bkgInvoice->bkg_night_pickup_included == 0 && $model->bkgInvoice->bkg_night_drop_included == 0)
						{
							if ($model->bkg_booking_type == 1)
							{
								echo "<br />" . " Night driving allowance " . $night_driver_allowance_txt . " to be paid if pickup or drop off happens between (10pm and 6am).";
							}
							else
							{
								echo "<br />" . "Night driving allowance " . $night_driver_allowance_txt . " to be paid to driver for each night when driving between the hours of 10pm and 6am. ";
							}
						}
						if ($model->bkgInvoice->bkg_night_pickup_included == 1 && $model->bkgInvoice->bkg_night_drop_included == 1)
						{
							echo" ";
						}*/
					?>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
-->
<script>
function showIncExc(text)
    {

        if (text == "toll")
        {
            $('#tollDesc').toggleClass("hide");
        }
        if (text == "state")
        {
            $('#stateDesc').toggleClass("hide");
        }
        if (text == "airport")
        {
            $('#airportDesc').toggleClass("hide");
        }
        if (text == "parking")
        {
            $('#parkingDesc').toggleClass("hide");
        }

    }
</script>