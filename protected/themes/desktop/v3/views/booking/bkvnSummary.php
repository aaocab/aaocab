<div class="row title-widget m0">
    <div class="col-12">
        <div class="container merriw heading-line text-center">
			<?php echo $this->pageTitle; ?>
        </div>
    </div>
</div>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
/* @var $bModel Booking  */
/* @var $model Booking */
$spclInstruction = $model->getFullInstructions();

foreach ($bModels as $bModel)
{
	$fcity	 = Cities::getName($bModel->bkg_from_city_id);
	$tcity	 = Cities::getName($bModel->bkg_to_city_id);

	$routeCityList	 = $bModel->getTripCitiesListbyId();
	$ct				 = implode(' &#10147; ', $routeCityList);
	foreach ($bModel->bookingRoutes as $key => $bookingRoute)
	{
		$pickupCity[]	 = $bookingRoute->brt_from_city_id;
		$dropCity[]		 = $bookingRoute->brt_to_city_id;
		$pickup_date[]	 = $bookingRoute->brt_pickup_datetime;
		$temp_last_date	 = strtotime($bookingRoute->brt_pickup_datetime) + $bookingRoute->brt_trip_duration;
		$drop_date_time	 = date('Y-m-d H:i:s', $temp_last_date);
	}
	$pickup_date_time	 = $pickup_date[0];
	$locationArr		 = array_unique(array_merge($pickupCity, $dropCity));
	$dateArr			 = array($pickup_date_time, $drop_date_time);
	#print_r($dateArr);exit;
	#print_r($locationArr);exit;
	$note				 = DestinationNote::model()->showBookingNotes($locationArr, $dateArr, $showNoteTo			 = 2);
	$showCustPhone		 = (BookingTrackLog::model()->getdetailByEvent($bModel->bkg_id, 201)) ? true : false;
}
$luggageCapacity = Stub\common\LuggageCapacity::init($bModel->bkgSvcClassVhcCat->scv_vct_id, $bModel->bkgSvcClassVhcCat->scv_scc_id, $bModel->bkgAddInfo->bkg_no_person);
?>

<div class="container">
    <div class="row justify-center">
        <div class="col-12 col-xl-10 mt30">
                <div class="row">
				<div class="col-12 col-xl-6"><span class="font-16"><b>Booking ID:<?= Filter::formatBookingId($model->bkg_booking_id); ?></b></span> <span class="badge badge-pill badge-primary mr-1 mb-1">Trip ID: <?= $cabModel->bcb_id ?></span></div>
				<div class="col-12 col-xl-6 weight500 mb-1"><span class="color-green">*Driver App must be used.  <a href="#section2" class="color-blue"><u>See Trip Rules</u></a></span></div>
				<div class="col-12 col-xl-4 mb-1"><span class="text-muted font-12"><img src="/images/bx-calendar.svg" alt="img" width="13" height="13"> Trip start time</span><br><?= date('jS M Y (D) h:i A', strtotime($model->bkg_pickup_date)); ?></div>
				<div class="col-6 col-md-6 col-xl-4"><span class="text-muted font-12"><img src="/images/bx-user2.svg" alt="img" width="13" height="13"> Number of passengers</span><br><?= ($bModel->bkgAddInfo->bkg_no_person == '') ? '0' : $bModel->bkgAddInfo->bkg_no_person; ?></div>
				<div class="col-6 col-md-6 col-xl-4"><span class="text-muted font-12"><img src="/images/bxs-shopping-bag.svg" alt="img" width="13" height="13"> Luggage</span><br><?= $bModel->bkgAddInfo->bkg_num_large_bag; ?> big bags; <?= $bModel->bkgAddInfo->bkg_num_small_bag; ?> small bags</div>
				
				<div class="col-12">
					<div class="row">

						<div class="col-12">
							<ul class="timeline ps ps--active-y mb0">
								<li class="timeline-item timeline-icon-success active pb0">
									<h6 class="timeline-title weight500">Pickup address</h6>
									<div class="timeline-content">
										<?= $bModel->bkg_pickup_address; ?>
									</div>
								</li>
								<li class="timeline-item timeline-icon-primary active">
									<h6 class="timeline-title weight500">Drop address</h6>
									<div class="timeline-content">
										<?= $bModel->bkg_drop_address; ?>
									</div>
								</li>

							</ul>
						</div>
					</div>
				</div>
				<div class="col-12 col-xl-6 mt-1"><span class="text-muted font-12">Amount To Collect</span><br><span class="font-22">&#x20B9;<b><?= round($model->bkgInvoice->bkg_due_amount) ?></b></span></div>
                </div>
        </div>
    </div>
</div>

<?php
$correctimg		 = '<img src="/images/bxs-check-circle.svg" alt="img" width="18" height="18">';
$crossimg		 = '<img src="/images/bxs-x-circle.svg" alt="img" width="18" height="18">';
$vehicleModel = $bModel->bkgBcb->bcbCab->vhcType->vht_model;
if($bModel->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
{
	$vehicleModel = OperatorVehicle::getCabModelName($bModel->bkgBcb->bcb_vendor_id, $bModel->bkgBcb->bcb_cab_id);
}
?>
<div class="container mt-1">
	<div class="row justify-center">
		<div class="col-12 col-xl-10 mb0">
			<div class="row" style="display: flex; flex-wrap: wrap; justify-content:center;">
				<div class="col-6 col-md-4 col-xl-3 mb20">
					<div class="card flex4 mb0">
						<div class="card-body p15">
							<div class="d-flex justify-content-between">
								<div><span class="text-muted font-11">Included kms</span><br><span class="font-18 weight500"><?= $bModel->bkg_trip_distance; ?></span></div>
								<div><img src="/images/img-2022/icons8-speed-50.png" width="30"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-6 col-md-4 col-xl-3 mb20">
					<div class="card flex4 mb0">
						<div class="card-body p15">
							<div class="d-flex justify-content-between">
								<div><span class="text-muted font-11">Extra charge per km</span><br>&#x20B9;<span class="weight500"><?= $bModel->bkgInvoice->bkg_rate_per_km_extra; ?></span></div>
								<div><img src="/images/img-2022/rupees.png" width="30"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-6 col-md-4 col-xl-3 mb20">
					<div class="card flex4 mb0">
						<div class="card-body p15">
							<div class="d-flex justify-content-between">
								<div><span class="text-muted font-11">Service tier</span><br><span class="font-14 weight500"><?= SvcClassVhcCat::getCatrgoryLabel($bModel->bkg_vehicle_type_id, true).' '. $vhcModel; ?></span></div>
								<div><img src="/images/img-2022/taxi-driver.png" width="30"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-6 col-md-4 col-xl-3 mb20">
					<div class="card flex4 mb0">
						<div class="card-body p15">
							<div class="d-flex justify-content-between">
								<div><span class="text-muted font-11">Cab type booked</span><br><span class="font-14 weight500"><?= $bModel->bkgBcb->bcbCab->vhcType->vht_make; ?> - <?= $vehicleModel; ?></span></div>
								<div><img src="/images/img-2022/taxi-64.png" width="30"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-6 col-md-4 col-xl-3 mb-2"><span class="color-gray2 font-13" style="display: contents;"><?= ($bModel->bkgInvoice->bkg_night_pickup_included > 0) ? $correctimg : $crossimg; ?> Night pickup allowance:</span>  <br> <?= ($bmodel->bkgInvoice->bkg_night_pickup_included == 1) ? "Not payable by customer" : "Payable on actuals" ?></div>
								<div class="col-6 col-md-4 col-xl-3 mb-2"><span class="color-gray2 font-13" style="display: contents;"><?= ($bModel->bkgInvoice->bkg_night_drop_included > 0) ? $correctimg : $crossimg; ?>  Night drop allowance:</span> <br> <?= ($model->bkgInvoice->bkg_night_drop_included == 1) ? "Not payable by customer" : "Payable on actuals" ?></div>
								<div class="col-6 col-md-4 col-xl-3 mb-2">
									<span class="color-gray2 font-13" style="display: contents;" onclick="showIncExc('toll')">
										<?= ($bModel->bkgInvoice->bkg_is_toll_tax_included == 1) ? $correctimg : $crossimg; ?> Toll tax: <i class="far fa-plus-square ml5"></i></span> <br>
									<span><?= ($bModel->bkgInvoice->bkg_is_toll_tax_included == 1) ? 'Not payable by customer' : 'Payable on actuals'; ?></span>
									<span id="tollDesc" class="hide font-10 lineheight14"><BR>
										<?php
										if ($bModel->bkgInvoice->bkg_is_toll_tax_included == 1)
										{
											?>   
											Our estimate of toll charges for travel on this route are ₹<?= ($bModel->bkgInvoice->bkg_toll_tax != '') ? $bModel->bkgInvoice->bkg_toll_tax : 0; ?>. 
											Toll taxes (even if amount is different) is already included in the trip cost<?php
										}
										else
										{
											?>
											Our estimate of toll charges  on this route are ₹<b><?= $bModel->bkgInvoice->bkg_toll_tax ?></b>. Any charges incurred is payable by customer.
											<?php
										}
										?>
									</span>
								</div>
								<div class="col-6 col-md-4 col-xl-3 mb-2"><span class="color-gray2 font-13" style="display: contents;"><?= $crossimg ?> MCD:</span> <br>not included</div>
								
								<div class="col-6 col-md-4 col-xl-3 mb-2">
									<span class="color-gray2 font-13" style="display: contents;" onclick="showIncExc('parking')">
										<?=($bModel->bkg_agent_id == Config::get('transferz.partner.id') || $bModel->bkgInvoice->bkg_parking_charge > 0) ? $correctimg : $crossimg; ?> Parking charges: <i class="far fa-plus-square ml5"></i>
									</span>
									<br>
									<span>
										<?=($bModel->bkg_agent_id == Config::get('transferz.partner.id') || $bModel->bkgInvoice->bkg_parking_charge > 0) ? "Included" : "Not Included"; ?>
									</span>
									<br>
									<span id="parkingDesc" class="hide font-10">
										<?php
											$strParking = "";
											if($bModel->bkg_agent_id == Config::get('transferz.partner.id') || $bModel->bkgInvoice->bkg_parking_charge > 0)
											{
												$strParking = "Parking charges are prepaid, ";
											}
											if($bModel->bkgInvoice->bkg_parking_charge > 0)
											{
												$strParking .= " upto ₹{$bModel->bkgInvoice->bkg_parking_charge}. ";
											}
											echo $strParking;
										?>
										Customer will directly pay for parking charges after the total parking cost for the trip exceeds <?= ($bModel->bkgInvoice->bkg_parking_charge > 0 ? "₹" . round($bModel->bkgInvoice->bkg_parking_charge) : "") ?>. Driver must upload all parking receipts for payments made by drive.
									</span>
								</div>
								<!--<div class="col-6 col-md-4 col-xl-3 mb-2">
									<span class="color-gray2 font-13" style="display: contents;" onclick="showIncExc('parking')">
										<?#= ($bModel->bkgInvoice->bkg_parking_charge > 0) ? "&#x20B9;" . $bModel->bkgInvoice->bkg_parking_charge : ''; ?><?#= ($bModel->bkgInvoice->bkg_parking_charge > 0) ? $correctimg : $crossimg; ?> Parking charges: <i class="far fa-plus-square ml5"></i></span> <br>
									<span><?#= ($bModel->bkgInvoice->bkg_parking_charge > 0) ? "&#x20B9;" . $bModel->bkgInvoice->bkg_parking_charge : ''; ?><?#= ($bModel->bkgInvoice->bkg_parking_charge > 0) ? 'included' : 'not included'; ?></span>
									<span id="parkingDesc" class="hide font-10"><BR>
										<?php
										if ($bModel->bkgInvoice->bkg_parking_charge > 0)
										{
											?> Parking charges are prepaid upto ₹<?#= round($bModel->bkgInvoice->bkg_parking_charge) ?>.<?php } ?> 
										Customer will directly pay for parking charges after the total parking cost for the trip exceeds ₹<?#= round($bModel->bkgInvoice->bkg_parking_charge) ?>. Driver must upload all parking receipts for payments made by drive.
									</span>
								</div>-->
								<div class="col-6 col-md-4 col-xl-3 mb-2">
									<span class="color-gray2 font-13" style="display: contents;" onclick="showIncExc('state')"><?= ($bModel->bkgInvoice->bkg_is_state_tax_included == 1) ? $correctimg : $crossimg; ?> State taxes: <i class="far fa-plus-square ml5"></i></span> <br>
									<span><?= ($bModel->bkgInvoice->bkg_is_state_tax_included == 1) ? 'Not payable by customer' : 'Payable on actuals'; ?></span>
									<span id="stateDesc" class="hide font-10"><BR>
										<?php
										if ($bModel->bkgInvoice->bkg_is_state_tax_included == 1)
										{
											?>   
											Our estimate of State Tax for travel on this route are ₹<?= ($bModel->bkgInvoice->bkg_state_tax != '') ? $bModel->bkgInvoice->bkg_state_tax : 0; ?>. 
											State Taxes (even if amount is different) is already included in the trip cost<?php
										}
										else
										{
											?>
											Our estimate of State Tax on this route are ₹<b><?= $bModel->bkgInvoice->bkg_state_tax ?></b>. Any charges incurred is payable by customer.
											<?php
										}
										?>
									</span>
								</div>
								<div class="col-6 col-md-4 col-xl-3 mb-2">
									<span class="color-gray2 font-13" style="display: contents;" onclick="showIncExc('airport')"><?= ($bModel->bkgInvoice->bkg_is_airport_fee_included == 1 ) ? $correctimg : $crossimg; ?> Airport entry charges: <i class="far fa-plus-square ml5"></i></span> <br>
									<span><?= ($bModel->bkgInvoice->bkg_is_airport_fee_included == 1 ) ? 'Not payable by customer' : 'Payable on actuals'; ?> </span>
									<span id="airportDesc" class="hide font-10"><BR>
										<?php
										if ($bModel->bkgInvoice->bkg_is_airport_fee_included != 1)
										{
											?>   
											Our estimate of airport entry charges on this route are ₹ <?= $bModel->bkgInvoice->bkg_airport_entry_fee ?> . Any charges incurred is payable by customer. <?php
										}
										else
										{
											?>

											Our estimate of airport entry charges on this route are ₹<?= ($bModel->bkgInvoice->bkg_airport_entry_fee != '') ? $bModel->bkgInvoice->bkg_airport_entry_fee : 0; ?>. 
											airport entry charges (even if amount is different) is already included in the trip cost 
											<?php
										}
										?>
									</span>
								</div>
								<div class="col-6 col-md-4 col-xl-3 mb-2"><span class="color-gray2 font-13" style="display: contents;">Journey break:</span></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container">
<div class="row justify-center accordion-widget mb0">
	<div class="col-12 col-xl-10" id="accordion-icon-wrapper">
        <div class="accordion collapse-icon accordion-icon-rotate" id="accordionWrapa2" data-toggle-hover="true">
		<div class="card collapse-header" id="heading19">
			<div id="heading19" class="card-header collapsed" data-toggle="collapse" data-target="#accordion19" aria-expanded="false" aria-controls="accordion19" role="tablist">
				<span class="collapse-title">
					<span class="collapse-title">Additional booking requirements</span>
				</span>
			</div>
			<div id="accordion19" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading19" class="collapse" style="">
				<div class="card">
<div class="card-body">
				<ol class="pl15"><?= ($spclInstruction != "") ? $spclInstruction : "No additional requested." ?></ol>
</div>
</div>
			</div>
        </div>

		<div class="card collapse-header" id="heading18">
			<div id="heading18" class="card-header collapsed" data-toggle="collapse" data-target="#accordion18" aria-expanded="false" aria-controls="accordion18" role="tablist">
				<span class="collapse-title">
					<span class="collapse-title">Requirements for taxi operator</span>
				</span>
			</div>
			<div id="accordion18" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading18" class="collapse" style="">
<div class="card list-6styled">
<div class="card-body">			
<ol class="pl15">
					<?php
					$results		 = TncPoints::getByType(2);
					foreach ($results as $r)
					{
						?>
						<li><?= $r['tnp_text']; ?> </li>
					<?php }
					?>
                </ol>
</div>
			</div>
</div>
        </div>

		<div class="card collapse-header" id="heading14">
			<div id="heading14" class="card-header collapsed" data-toggle="collapse" data-target="#accordion14" aria-expanded="false" aria-controls="accordion14" role="tablist">
					<span class="collapse-title">Trip requirements for driver</span>
			</div>
			<div id="accordion14" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading14" class="collapse" style="">
<div class="card list-6styled">
<div class="card-body">			 
<ol class="pl15">
					<?php
                    $results = TncPoints::getByType(3);
					foreach ($results as $r)
					{
						?>
						<li><?= $r['tnp_text']; ?> </li>
					<?php }
					?>
                </ol>
			</div>
</div>
</div>
        </div>

        <div class="card collapse-header" id="heading5">
			<div id="heading5" class="card-header collapsed" data-toggle="collapse" data-target="#accordion5" aria-expanded="false" aria-controls="accordion5" role="tablist">
					<span class="collapse-title">Travel advisories &amp; restrictions</span>
			</div>
			<div id="accordion5" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading5" class="collapse" style="">
			<?php
				$this->renderPartial("bkTravelAdvisories", ["model" => $model, "note" => $note], false);
			?>
			</div>
        </div>
</div>
</div>
</div>
</div>


  


<div class="container">
	<div class="row justify-center">
		<div class="col-12 col-xl-10 mb30">
			<div class="bg-white-box">
				<div class="row">
					<div class="col-12 list-5styled mt0" id="section2">
						<h3 class="font-20"><b>IMPORTANT POINTS:</b></h3>
						<ol type="1">
							<li><b>Must use Driver app throughout the journey.</b></li>
							<ol type="i">
								<li>
									Start, Stop and resume trip with the driver app.
								</li>
								<li>
									You may not close the driver app during the trip.
								</li>
							</ol>
							<?php
							if (in_array($bModel->bkg_booking_type, [2, 3, 9, 10, 11]))
							{
								?>
								<li>Customer has paid for the kms included in this trip. Customer is authorized to use the vehicle for sightseeing in the neighboring areas/cities of all destination(s) in the itinerary.</li>
								<?php
							}
							else
							{
								?>
								<li>All additional pickups and drops are chargeable seperately. Sight-seeing is not included on one-way or airport transfer bookings.</li>
							<?php } ?>
							<li>Driver must arrive at pickup location at least 15min before pickup time.</li>
							<ol type="i">
								<li>
									Trip will attract late arrival penalty if Driver arrives at location after pickup time.
								</li>
							</ol>
							<li>Customer may request driver for ID verification. If ID not matching or not provided then cutsomer can cancel the booking for zero cancellation charges. Penalties will apply for non matching driver or non matching car.</li>
							<?php
							if (in_array($bModel->bkg_status, [5, 6, 7]))
							{
								?>
								<li>Ensure that Drivers License, Car RC  and Insurance are on file with Gozo. Ensure only a commercial permit vehicle is assigned.</li>
								<?php
							}
							else
							{
								?>
								<li style="color:red">aaocab REQUIRES YOU TO ASSIGN DRIVER & CAR 12 hours before pickup. Ensure that Drivers License, Car RC and Insurance are on file with Gozo. Ensure only a commercial permit vehicle is assigned.</li>
								<?php } ?>
							<li>Driver must not entertain a change of address greater than 5km from actual destination by customer unless "change of address" is sent by system.</li>
						</ol>



					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
    function viewCustomerContact(booking_id)
    {
        $href = "<?= Yii::app()->createUrl('booking/viewCustomerDetails') ?>";
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"booking_id": booking_id, "type": 2},
            success: function (data)
            {
                var obj = $.parseJSON(data);
                if (obj.success == true)
                {
                    $("#customerDetails").show();
                    $("#viewCustDetails").hide();
                    window.location.reload();
                }
            }
        });
    }
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
