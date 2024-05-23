<?php
$totalAdvance	 = PaymentGateway::model()->getTotalAdvance($model->bkg_id);
$tripTimeDiff	 = 240;
$rule			 = 1;
$refundArr		 = BookingPref::model()->calculateRefund($tripTimeDiff, $model->bkgInvoice->bkg_total_amount, $totalAdvance, $rule, $model->bkg_id, true);
$vehicalModel	 = new VehicleTypes();
//$carType		 = $vehicalModel->getVehicleTypeById($model->bkg_vehicle_type_id);
//$car_type		 = $vehicalModel->getCarType($carType);
//$cabModel		 = $vehicalModel->getModelDetailsbyId($model->bkg_vehicle_type_id);
$vctId			 = $model->bkgSvcClassVhcCat->scv_vct_id;
$carType		 = SvcClassVhcCat::model()->getVctSvcList('string', '', $vctId);
$cabModel		 = VehicleCategory::model()->findByPk($vctId);
$model->bkgInvoice->calculateConvenienceFee(0);
$model->bkgInvoice->calculateTotal();
$priceRule = AreaPriceRule::model()->getValues($model->bkg_from_city_id,$model->bkg_vehicle_type_id,$model->bkg_booking_type);
$prr_day_driver_allowance =$priceRule['prr_day_driver_allowance'];
$prr_Night_driver_allowance =$priceRule['prr_night_driver_allowance'];
$freeAction = 0;
$paidAction = 0;
if(date('Y-m-d h:i:s') > date('Y-m-d h:i:s',strtotime('-24 hour', strtotime($model->bkg_pickup_date)))){
	$freeAction =1;
}
if(date('Y-m-d h:i:s') > date('Y-m-d h:i:s',strtotime('-6 hour', strtotime($model->bkg_pickup_date)))){
	$paidAction =1;
}
?>
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>
<div class="content-boxed-widget p0 accordion-path">
	<div class="accordion accordion-style-0">
		<div class="accordion-border">
			<!--<a href="javascript:void(0)" class="font18" data-accordion="accordion-58"><span class="uppercase">IMPORTANT TERMS OF YOUR BOOKING</span><i class="fa fa-plus"></i></a>-->
			<a href="javascript:void(0)" class="font18" data-accordion="accordion-58"><span class="uppercase">FARE INCLUSIONS AND EXCLUSIONS</span><i class="fa fa-plus"></i></a>
			<div class="accordion-content" id="accordion-58" style="display: none;">
			<!--	<div class="content p0">	
					<span class="bottom-10 font-16"><b>Cab Category</b></span>
					<p class="mb20">The booking will be for cab type <?//= $carType ?> and we do not commit on providing the preferred cab model (<?= $cabModel['vct_desc'] ?>).</p>
					<span class="bottom-10 font-16"><b>Hilly Regions</b></span>
					<!--<p class="bottom-20">AC will be switched off in hilly areas.</p>
					<span class="bottom-10 font-16"><b>Night Charges</b></span>--
					<p class="bottom-20">Post 9:00 PM to 6:00 AM, an Additional night charge (approx. Rs.250/night) will be applicable. These charges should be directly paid to the driver.</p>
					<span class="bottom-10 font-16"><b>Driver Details</b></span>
					<p class="bottom-20">Driver details will be shared up to 2 hrs prior to departure.</p>
					<span class="bottom-10 font-16"><b>Stops</b></span>
					<p class="bottom-20">This is a point to point booking and only one stop for meals is included.</p>
					<span class="bottom-10 font-16"><b>Delays</b></span>
					<p class="bottom-20">Due to traffic or any other unavoidable reason, pickup may be delayed by 30 mins.</p>
					<!--<span class="bottom-10 font-16"><b>Receipts</b></span>
					<p class="bottom-20">You need to collect the receipts from driver for any toll tax, state tax, night charges or extra km paid directly to the driver during the trip. GOZO is not liable to provide invoices for such amount</p>
					--
				</div>
				<div class="content p0">
				<span class="bottom-10 font-16"><b>Included with Quotation</b></span>
				<p>
					NO route deviations allowed unless listed in itinerary 
					<?php 
						/*if ($model->bkgInvoice->bkg_is_toll_tax_included == 1 && $model->bkgInvoice->bkg_is_state_tax_included == 1)
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
                    </p>
					<div class="heading-journeybreak hide">
                     <span class="heading-part mb10"><b>Additional charges paid</b></span>
						 <p id="journeybreak">30 minutes break during journey (Rs. 150/-).</p>
					</div>	 
                    <span class="heading-part mb10"><b>Customer to pay separately</b></span>
                      <p>
						Customer to pay separately
						Any or parking charges; 
						Any parking charges
						<?php
						if ($model->bkgInvoice->bkg_is_toll_tax_included == 0 && $model->bkgInvoice->bkg_is_state_tax_included == 0)
						{
							echo '; Toll & state taxes';
						}
                        if ($model->bkgInvoice->bkg_is_airport_fee_included == 0)
						{
							echo ', Airport Entry Charges';
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
				</div>-->
				
					<div class="row font-11">
							<?php
							$correctimg	 = '<img src="/images/email/correct.png" class="inline-block mr5">';
							$crossimg	 = '<img src="/images/email/cross.png" class="inline-block mr5">';
							//getting the Price rule array for fare inclusion and exclusion
							$newpriceRule	= PriceRule::getByCity($model->bkg_from_city_id, $model->bkg_booking_type, $model->bkg_vehicle_type_id);
							if(!empty($newpriceRule)){$prarr = $newpriceRule->attributes; }	
							?>
                                   <p class="mb10"><span onclick="showIncExc('toll')"><?= ($model->bkgInvoice->bkg_is_toll_tax_included == 1) ? $correctimg : $crossimg ?>  TOLL TAXES <i class="far fa-plus-square ml5"></i></span>
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
									<p class="mb10"><span onclick="showIncExc('state')"><?= ($model->bkgInvoice->bkg_is_state_tax_included == 1) ? $correctimg : $crossimg ?>  STATE TAXES <i class="far fa-plus-square ml5"></i></span>
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
									<p class="mb10"><?= $crossimg ?> MCD</p> 
									<p class="mb10">
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
									<!--<p class="mb10"><?= ($model->bkgInvoice->bkg_night_pickup_included == 1 || $model->bkgInvoice->bkg_night_drop_included == 1) ? $correctimg : $crossimg ?> NIGHT CHARGES 
									<?php echo  (!empty($prarr['prr_night_start_time'] && !empty($prarr['prr_night_end_time']))?"(". date("g A",strtotime($prarr['prr_night_start_time'])). " - ". date("g A",strtotime($prarr['prr_night_end_time'])).")":'').(!empty($prarr['prr_night_driver_allowance'])?" - Rs.".$prarr['prr_night_driver_allowance']:'');?> </p>-->
									<p class="mb10"><?= ($model->bkgInvoice->bkg_night_pickup_included > 0) ? $correctimg : $crossimg; ?> NIGHT PICKUP CHARGES 
										<?php echo  (!empty($prarr['prr_night_start_time'] && !empty($prarr['prr_night_end_time']))?"(". date("g A",strtotime($prarr['prr_night_start_time'])). " - ". date("g A",strtotime($prarr['prr_night_end_time'])).")":'').(!empty($prarr['prr_night_driver_allowance'])?" - Rs.".$prarr['prr_night_driver_allowance']:'');?>
									</p>
									<p class="mb10"><?= ($model->bkgInvoice->bkg_night_drop_included > 0) ? $correctimg : $crossimg; ?>  NIGHT DROP CHARGES  
									<?php echo  (!empty($prarr['prr_night_start_time'] && !empty($prarr['prr_night_end_time']))?"(". date("g A",strtotime($prarr['prr_night_start_time'])). " - ". date("g A",strtotime($prarr['prr_night_end_time'])).")":'').(!empty($prarr['prr_night_driver_allowance'])?" - Rs.".$prarr['prr_night_driver_allowance']:'');?>
									</p>
									<p class="mb10"><?= ($model->bkgInvoice->bkg_trip_waiting_charge > 0) ? $correctimg : $crossimg ?> WAITING CHARGES <!--(Rs.120 / HOUR rounded to nearest 30 MINS).--></p>
									<p class="mb10"><?= ($model->bkgInvoice->bkg_extra_km > 0) ? $correctimg : $crossimg ?> EXTRA CHARGES <?= '(Rs.'.round($model->bkgInvoice->bkg_rate_per_km_extra, 2).' / KM beyond '.$model->bkg_trip_distance.' KMS).'?></p>
									<p class="mb10"><?= $crossimg ?> GREEN TAX </p>
									<p class="mb10"><?= $crossimg ?> ENTRY TAXES / CHARGES</p>
									<p class="mb10"><span onclick="showIncExc('parking')"><?= ($model->bkgInvoice->bkg_parking_charge == 1) ? $correctimg : $crossimg; ?>  PARKING CHARGES <i class="far fa-plus-square ml5"></i></span>
										<span id="parkingDesc" class="hide font-10"><BR>
										<?php
										if ($model->bkgInvoice->bkg_parking_charge > 0)
										{
											?> Parking charges are prepaid upto ₹<?= round($model->bkgInvoice->bkg_parking_charge) ?>.<?php } ?> 
											Customer will directly pay for parking charges after the total parking cost for the trip exceeds ₹<?= round($model->bkgInvoice->bkg_parking_charge) ?>. Driver must upload all parking receipts for payments made by drive.
										</span></p>
								<Br>
							<p>FINAL OUTSTANDING SHALL BE COMPUTED AFTER TRIP COMPLETION. ADDITIONAL AMOUNT, IF ANY, MAY BE PAID IN CASH TO THE DRIVER DIRECTLY.</p>
						</div>
					

			</div>
		</div>
	</div>
</div>
<?php
$dosdontsPoints		 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_DOS_AND_DONTS); //print_r($dosdontsPoints);
$boardingcheckPoints = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_BORDING_CHECK); //print_r($boardingcheckPoints);
$othertermsPoints	 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_OTHER_TERMS); //print_r($othertermsPoints);
?>
<div class="content-boxed-widget p0 accordion-path">
	<div class="accordion accordion-style-0">
		<div class="accordion-border">
			<a href="javascript:void(0)" class="font18" data-accordion="accordion-59"><span class="uppercase">BOARDING CHECKS</span><i class="fa fa-plus"></i></a>
			<div class="accordion-content" id="accordion-59" style="display: none;">
				<div class="content p0">
				<?php
						if (count($boardingcheckPoints) > 0)
						{
							echo "<ol style='font-size:10px; line-height:15px;padding-left:25px;'>";
							foreach ($boardingcheckPoints as $c)
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
</div>

<div class="content-boxed-widget p0 accordion-path">
	<div class="accordion accordion-style-0">
		<div class="accordion-border">
			<a href="javascript:void(0)" class="font18" data-accordion="accordion-60s"><span class="uppercase">ON TRIP DOs & DONTs </span><i class="fa fa-plus"></i></a>
			<div class="accordion-content" id="accordion-60s" style="display: none;">
				<div class="content p0">
				<?php
						if (count($dosdontsPoints) > 0)
						{
							echo "<ol style='font-size:10px; line-height:15px;padding-left:25px;'>";
							foreach ($dosdontsPoints as $c)
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
</div>

<div class="content-boxed-widget p0 accordion-path">
	<div class="accordion accordion-style-0">
		<div class="accordion-border">
			<a href="javascript:void(0)" class="font18" data-accordion="accordion-61"><span class="uppercase">OTHER TERMS</span><i class="fa fa-plus"></i></a>
			<div class="accordion-content" id="accordion-61" style="display: none;">
				<div class="content p0">
					<?php
						if (count($othertermsPoints) > 0)
						{
							$str = '';
							$str = "<ol type='1' style='font-size:10px; line-height:15px;padding-left:25px;'>";
							foreach ($othertermsPoints as $c)
							{
								$str .= "<li style='list-style-type:  circle'>" . $c['tnp_text'] . "</li>";
							}
							$str .= "</ol>";
							echo $str;
						}
						?>
				</div>
			</div>
		</div>
	</div>
</div>
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