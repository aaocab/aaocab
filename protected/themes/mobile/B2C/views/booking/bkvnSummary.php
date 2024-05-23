<style>
    .card{ box-shadow:0 0 0 0!important;}
</style>
<div class="content-boxed-widget2 mb5 list-view-panel p5 text-center font-14">
	<?php echo $this->pageTitle; ?>
</div>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
/* @var $bModel Booking  */
/* @var $model Booking */
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
?>

<div class="content-boxed-widget2 mb5 list-view-panel p5 text-center font-14">
    <span class="font-18"><b>Booking ID:<?= Filter::formatBookingId($model->bkg_booking_id); ?></b></span><br>
    <span class="bg-green3 radius-10 pl10 pr10 color-white">Trip ID: <b><?= $cabModel->bcb_id ?></b></span><br>
    <span class="color-gray2"><?= date('jS M Y (D) h:i A', strtotime($model->bkg_pickup_date)); ?></span><br>
    <span style="color:red">* Driver App must be used.  <a href="#section2">See Trip Rules</a></span>
</div>

<div class="content-boxed-widget2 mb5 list-view-panel">
    <div class="one-half font-12">Amount To Collect:</div>
    <div class="one-half last-column text-right"><span class="font-22">&#x20B9;<b><?= round($model->bkgInvoice->bkg_due_amount) ?></b></span></div>
    <div class="clear"></div>
</div>

<div class="content-boxed-widget2 mb5 list-view-panel font-12 line-height16">
    <div class="mb15"><span class="color-gray">Pickup address:</span><br><?= $bModel->bkg_pickup_address; ?></div>
    <div class="mb15"><span class="color-gray">Drop address:</span><br><?= $bModel->bkg_drop_address; ?></div>
</div>

<?php
$correctimg	 = '<img src="/images/email/correct.png" style="display:inline;">';
$crossimg	 = '<img src="/images/email/cross.png" style="display:inline;">';
$vehicleModel = $bModel->bkgBcb->bcbCab->vhcType->vht_model;
if($bModel->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
{
	$vehicleModel = OperatorVehicle::getCabModelName($bModel->bkgBcb->bcb_vendor_id, $bModel->bkgBcb->bcb_cab_id);
}
?>
<div class="content-boxed-widget2 mb5 list-view-panel line-height16 font-14 flex">
    <div class="one-half mr10 mb20" style="">
        <span class="color-orange font-12">Included kms:</span> <br><b><?= $bModel->bkg_trip_distance; ?></b>
    </div>
    <div class="one-half last-column mb20">
        <span class="color-orange font-12">Extra charge per km:</span> <br>&#x20B9;<b><?= $bModel->bkgInvoice->bkg_rate_per_km_extra; ?></b>
    </div>
    <div class="one-half mr10 mb20">
        <span class="color-orange font-12">Service tier:</span> <br><b><?= $bModel->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . '(' . $bModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . $vhcModel . ' )'; ?></b>
    </div>
    <div class="one-half last-column mb20">
        <span class="color-orange font-12">Cab type booked:</span> <br><b><?= $bModel->bkgBcb->bcbCab->vhcType->vht_make; ?> - <?= $vehicleModel; ?></b>
    </div>
    <div class="one-half mr10 mb20">
        <span class="color-orange font-12">Night pickup allowance:</span> <br><span><b><?= ($bModel->bkgInvoice->bkg_night_pickup_included > 0) ? $correctimg . ' included' : $crossimg . ' not included'; ?></b></span>
    </div>
    <div class="one-half last-column mb20">
        <span class="color-orange font-12">Night drop allowance:</span> <br><b><?= ($bModel->bkgInvoice->bkg_night_drop_included > 0) ? $correctimg . ' included' : $crossimg . ' not included'; ?></b>
    </div>
    <div class="one-half mr10 mb20">
        <span class="color-orange font-12" onclick="showIncExc('toll')"> Toll tax: <i class="far fa-plus-square ml5"></i></span> <br>
		<span><b><?= ($bModel->bkgInvoice->bkg_toll_tax > 0) ? $correctimg . ' included' : $crossimg . ' not included'; ?></b></span>
		<span id="tollDesc" class="hide font-10"><BR>
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
    <div class="one-half last-column mb20">
        <span class="color-orange font-12">MCD:</span> <br><b><?= $crossimg ?> not included</b>
    </div>
    <div class="one-half mr10 mb20">
        <span class="color-orange font-12" onclick="showIncExc('parking')"> Parking charges: <i class="far fa-plus-square ml5"></i></span> <br>
		<span><b><?= ($bModel->bkgInvoice->bkg_parking_charge > 0) ? "&#x20B9;" . $bModel->bkgInvoice->bkg_parking_charge : ''; ?>&nbsp;<?= ($bModel->bkgInvoice->bkg_parking_charge > 0) ? $correctimg . ' included' : $crossimg . ' not included'; ?>.</b></span>
		<span id="parkingDesc" class="hide font-10"><BR>
			<?php
			if ($bModel->bkgInvoice->bkg_parking_charge > 0)
			{
				?> Parking charges are prepaid upto ₹<?= round($bModel->bkgInvoice->bkg_parking_charge) ?>.<?php } ?> 
			Customer will directly pay for parking charges after the total parking cost for the trip exceeds ₹<?= round($bModel->bkgInvoice->bkg_parking_charge) ?>. Driver must upload all parking receipts for payments made by drive.
		</span>
    </div>
    <div class="one-half last-column mb20">
        <span class="color-orange font-12" onclick="showIncExc('state')"> State taxes: <i class="far fa-plus-square ml5"></i></span> <br>
		<span><b><?= ($bModel->bkgInvoice->bkg_state_tax > 0) ? $correctimg . ' included' : $crossimg . ' not included'; ?></b></span>
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
    <div class="one-half mr10 mb25">
        <span class="color-orange font-12" onclick="showIncExc('airport')"> Airport entry charges: <i class="far fa-plus-square ml5"></i></span> <br>
		<span><b><?= ($bModel->bkgInvoice->bkg_airport_entry_fee > 0) ? $correctimg . ' included' : $crossimg . ' not included'; ?>. </b></span>
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
    <div class="one-half last-column mb20">
        <span class="color-orange font-12">Journey break:</span> <br><b>&nbsp;</b>
    </div>
    <div class="one-half mr10 mb20">
        <span class="color-orange font-12">Number of passengers:</span> <br><b><?= ($bModel->bkgAddInfo->bkg_no_person == '') ? '0' : $bModel->bkgAddInfo->bkg_no_person; ?></b>
    </div>
    <div class="one-half last-column mb20">
        <span class="color-orange font-12">Luggage:</span> <br><b><?= $bModel->bkgAddInfo->bkg_num_large_bag; ?></b> big bags; <b><?= $bModel->bkgAddInfo->bkg_num_small_bag; ?></b> small bags
    </div>
    <div class="clear"></div>
</div>

<div class="content-boxed-widget2 mb5 list-view-panel font-12 line-height16">
    <div id="accordion">
        <div class="card m0 p0">
            <div class="card-header p0">
                <a class="card-link text-center p10" style="color:#0f0f0f; font-weight: 500;" data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                    Additional booking requirements
                </a>
            </div>
            <div id="collapse2" class="collapse">
                <div class="card-body">
                    <ol class="pl10"><?= ($spclInstruction != "") ? $spclInstruction : "No additional requested." ?></ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-boxed-widget2 mb5 list-view-panel font-12 line-height16">
    <div id="accordion">
        <div class="card m0 p0">
            <div class="card-header p0">
                <a class="card-link text-center p10" style="color:#0f0f0f; font-weight: 500;" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    Requirements for taxi operator
                </a>
            </div>
			<?php $results = TncPoints::getByType(2); ?>
            <div id="collapseOne" class="collapse">
                <div class="card-body">
                    <ol>
						<?php
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
</div>


<div class="content-boxed-widget2 mb5 list-view-panel font-12 line-height16">
    <div id="accordion">
        <div class="card m0 p0">
            <div class="card-header p0">
                <a class="card-link text-center p10" style="color:#0f0f0f; font-weight: 500;" data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                    Trip Cancellation Policy
                </a>
            </div>
            <div id="collapse3" class="collapse">
                <div class="card-body">
					<div class="row">
						<div class="col-lg-12">
							<p class="font-12 mb0"><b>Customer Cancellation policy</b></p>
                                                        <ul><!-- &#x20B9;<?//= array_values($cancelTimes_new->slabs)[1]; ?> -->
								<li>Free cancellation period  (<?= date('d M Y H:i a'); ?> to <?= date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[0])) ?>)</li>
								<li>Cancellation Charge (<?= date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[0])) ?> to <?= date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[1])) ?>)</li>
								<li>No Refund  (after <?= date('d M Y H:i a', strtotime($model->bkg_pickup_date)); ?>)</li>
							</ul>
						</div>
					</div>
                </div>
            </div>



        </div>
    </div>
</div>


<div class="content-boxed-widget2 mb5 list-view-panel font-12 line-height16">
    <div id="accordion">
        <div class="card m0 p0">
            <div class="card-header p0">
                <a class="card-link text-center p10" style="color:#0f0f0f; font-weight: 500;" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                    Trip requirements for driver
                </a>
            </div>
			<?php $results = TncPoints::getByType(3); ?>
            <div id="collapseTwo" class="collapse">
                <div class="card-body">
                    <ol>
						<?php
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
</div>

<div class="content-boxed-widget2 mb5 list-view-panel font-12 line-height16">
    <div id="accordion">
        <div class="card m0 p0">
            <div class="card-header p0">
                <a class="card-link text-center p10" style="color:#0f0f0f; font-weight: 500;" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                    Special instructions & advisories that may affect your planned travel 
                </a>
            </div>
            <div id="collapseThree" class="collapse">
				<?php
				for ($i = 0; $i < count($note); $i++)
				{
					?>
					<div class="card-body">
						<div>
							<p class="mb0 line-height16"><span class="color-orange font-12">Place</span></p>
							<p><?php
								if ($note[$i]['dnt_area_type'] == 1)
								{
									echo ($note[$i]['dnt_zone_name']);
								}
								if ($note[$i]['dnt_area_type'] == 3)
								{
									echo($note[$i]['cty_name']);
								}
								else if ($note[$i]['dnt_area_type'] == 2)
								{
									echo ($note[$i]['dnt_state_name']);
								}
								else if ($note[$i]['dnt_area_type'] == 0)
								{
									echo "Applicable to all";
								}
								else if ($note[$i]['dnt_area_type'] == 4)
								{
									echo Promos::$region[$note[$i]["dnt_area_id"]];
								}
								?></p>

							<p class="mb0 line-height16"><span class="color-orange font-12">Note</span></p>
							<p><?= ($note[$i]['dnt_note']) ?></p>

							<p class="mb0 line-height16"><span class="color-orange font-12">Valid From</span></p>
							<p><?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_from'])) ?></p>

							<p class="mb0 line-height16"><span class="color-orange font-12">Valid To</span></p>
							<p><?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_to'])) ?></p>

							<p class="mb0 line-height16"><span class="color-orange font-12">Applicable For</span></p>
							<p><?php
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
									else
									{
										echo "";
									}
								}
								?></p>
						</div>
						<?php
					}
					?>

				</div>
            </div>
        </div>
    </div>
</div>

<div class="content-boxed-widget2 mb5 list-view-panel font-12 line-height16" id="section2">
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
			<li style="color:red">GozoCabs REQUIRES YOU TO ASSIGN DRIVER & CAR 12 hours before pickup. Ensure that Drivers License, Car RC and Insurance are on file with Gozo. Ensure only a commercial permit vehicle is assigned.</li>
			<?php } ?>
		<li>Driver must not entertain a change of address greater than 5km from actual destination by customer unless "change of address" is sent by system.</li>
	</ol>

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
