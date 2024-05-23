<style>
    #main {
		margin: 0;
	}

	#main #faq .card {
		margin-bottom: 30px;
		border: 0;
	}

	#main #faq .card .card-header {
		border: 0;
		-webkit-box-shadow: 0 0 20px 0 rgba(213, 213, 213, 0.5);
		box-shadow: 0 0 20px 0 rgba(213, 213, 213, 0.5);
		border-radius: 2px;
		padding: 0;
	}

	#main #faq .card .card-header .btn-header-link {
		color: #fff;
		display: block;
		text-align: left;
		background: #fedfa7;
		color: #222;
		padding: 15px;
		font-weight: 500;
	}

	#main #faq .card .card-header .btn-header-link:after {
		content: "\f107";
		font-family: 'Font Awesome 5 Free';
		font-weight: 900;
		float: right;
	}

	#main #faq .card .card-header .btn-header-link.collapsed {
		background: #1c4fa2;
		color: #fff;
	}

	#main #faq .card .card-header .btn-header-link.collapsed:after {
		content: "\f106";
	}

	#main #faq .card .collapsing {
		background: #fedfa7;
		line-height: 30px;
	}

	#main #faq .card .collapse {
		border: 0;
	}

	#main #faq .card .collapse.show {
		background: #fedfa7;
		line-height: 30px;
		color: #222;
	}
</style>

<div class="row title-widget">
    <div class="col-12">
        <div class="container">
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
    <div class="row">
        <div class="col-12 col-lg-10 offset-lg-1 mt30">
            <div class="bg-white-box">
                <div class="row">
                    <div class="col-12 text-center">
                        <span class="font-18"><b>Booking ID:<?= $model->bkg_booking_id; ?></b></span><br>
                        <span class="bg-green3 radius-10 pl10 pr10 color-white">Trip ID: <b><?= $cabModel->bcb_id ?></b></span><br>
                        <span class="color-gray2"><?= date('jS M Y (D) h:i A', strtotime($model->bkg_pickup_date)); ?></span><br>
                        <span style="color:red">* Driver App must be used.  <a href="#section2">See Trip Rules</a></span>
                    </div>
                    <div class="col-12 mt15">
                        <div class="row border-top pt10">
                            <div class="col-12 col-lg-4"><span class="color-gray2">Pickup address:</span>&nbsp;<?= $bModel->bkg_pickup_address; ?></div>
                            <div class="col-12 col-lg-4 text-center"><span class="font-16">Amount To Collect:</span> <span class="font-22">&#x20B9;<b><?= round($model->bkgInvoice->bkg_due_amount) ?></b></span></div>
                            <div class="col-12 col-lg-4"><span class="color-gray2">Drop address:</span>&nbsp;<?= $bModel->bkg_drop_address; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="col-12 text-center mt20 mb20">&nbsp;</div>
<?php
$correctimg		 = '<img src="/images/email/correct.png" height="15" width="15">';
$crossimg		 = '<img src="/images/email/cross.png" height="15" width="15">';
$vehicleModel = $bModel->bkgBcb->bcbCab->vhcType->vht_model;
if($bModel->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
{
	$vehicleModel = OperatorVehicle::getCabModelName($bModel->bkgBcb->bcb_vendor_id, $bModel->bkgBcb->bcb_cab_id);
}
?>
<div class="container">
	<div class="row">
		<div class="col-12 col-lg-10 offset-lg-1 mb30">
			<div class="bg-white-box">
				<div class="row flex">
					<div class="col-lg-3 mb20" style="display: flex; line-height: 18px;">
						<span class="color-gray2 font-13" style="display: contents;">Included kms:</span> <br><?= $bModel->bkg_trip_distance; ?>
					</div>
					<div class="col-lg-3 mb20" style="display: flex; line-height: 18px;">
						<span class="color-gray2 font-13" style="display: contents;">Extra charge per km:</span> <br>&#x20B9;<?= $bModel->bkgInvoice->bkg_rate_per_km_extra; ?>
					</div>
					<div class="col-lg-3 mb20" style="display: flex; line-height: 18px;">
						<span class="color-gray2 font-13" style="display: contents;">Service tier:</span> <br> 
						<?= $bModel->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . '(' . $bModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . $vhcModel . ' )'; ?>

					</div>
					<div class="col-lg-3 mb20" style="display: flex; line-height: 18px;">
						<span class="color-gray2 font-13" style="display: contents;">Cab type booked:</span> <br><?= $bModel->bkgBcb->bcbCab->vhcType->vht_make; ?> - <?= $vehicleModel; ?>
					</div>
					<div class="col-lg-3 mb20" style=" line-height: 18px;">
						<span class="color-gray2 font-13" style="display: contents;"><?= ($bModel->bkgInvoice->bkg_night_pickup_included > 0) ? $correctimg : $crossimg; ?> Night pickup allowance:</span>  <br> <?= ($bmodel->bkgInvoice->bkg_night_pickup_included == 1) ? "Not payable by customer" : "Payable on actuals" ?>
					</div>
					<div class="col-lg-3 mb20" style="line-height: 18px;">
						<span class="color-gray2 font-13" style="display: contents;"><?= ($bModel->bkgInvoice->bkg_night_drop_included > 0) ? $correctimg : $crossimg; ?>  Night drop allowance:</span> <br> <?= ($model->bkgInvoice->bkg_night_drop_included == 1) ? "Not payable by customer" : "Payable on actuals" ?>
					</div>
					<div class="col-lg-3 mb20" style="line-height: 18px;">
						<span class="color-gray2 font-13" style="display: contents;" onclick="showIncExc('toll')">
<?= ($bModel->bkgInvoice->bkg_is_toll_tax_included == 1) ? $correctimg : $crossimg; ?> Toll tax: <i class="far fa-plus-square ml5"></i></span> <br>
						<span><?= ($bModel->bkgInvoice->bkg_is_toll_tax_included == 1) ? 'Not payable by customer' : 'Payable on actuals'; ?></span>
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
					 
					<div class="col-lg-3 mb20" style=" line-height: 18px;">
						<span class="color-gray2 font-13" style="display: contents;"><?= $crossimg ?> MCD:</span> <br>not included
					</div>
					<div class="col-lg-3 mb20" style=" line-height: 18px;">
						<span class="color-gray2 font-13" style="display: contents;" onclick="showIncExc('parking')">
							<?= ($bModel->bkgInvoice->bkg_parking_charge > 0) ? "&#x20B9;" . $bModel->bkgInvoice->bkg_parking_charge : ''; ?><?= ($bModel->bkgInvoice->bkg_parking_charge > 0) ? $correctimg : $crossimg; ?> Parking charges: <i class="far fa-plus-square ml5"></i></span> <br>
						<span><?= ($bModel->bkgInvoice->bkg_parking_charge > 0) ? "&#x20B9;" . $bModel->bkgInvoice->bkg_parking_charge : ''; ?><?= ($bModel->bkgInvoice->bkg_parking_charge > 0) ? 'included' : 'not included'; ?></span>
						<span id="parkingDesc" class="hide font-10"><BR>
						<?php
						if ($bModel->bkgInvoice->bkg_parking_charge > 0)
						{
							?> Parking charges are prepaid upto ₹<?= round($bModel->bkgInvoice->bkg_parking_charge) ?>.<?php } ?> 
							Customer will directly pay for parking charges after the total parking cost for the trip exceeds ₹<?= round($bModel->bkgInvoice->bkg_parking_charge) ?>. Driver must upload all parking receipts for payments made by drive.
						</span>
					</div>
					<div class="col-lg-3 mb20" style=" line-height: 18px;">
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
					<div class="col-lg-3 mb20" style=" line-height: 18px;">
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
					<div class="col-lg-3 mb20" style="display: flex; line-height: 18px;">
						<span class="color-gray2 font-13" style="display: contents;">Journey break:</span> <br>
					</div>
					<div class="col-lg-3 mb20" style="display: flex; line-height: 18px;">
						<span class="color-gray2 font-13" style="display: contents;">Number of passengers:</span> <br><?= ($bModel->bkgAddInfo->bkg_no_person == '') ? '0' : $bModel->bkgAddInfo->bkg_no_person; ?>
					</div>
					<div class="col-lg-3 mb20" style="display: flex; line-height: 18px;">
						<span class="color-gray2 font-13" style="display: contents;">Luggage:</span> <br><?= $bModel->bkgAddInfo->bkg_num_large_bag; ?> big bags; <?= $bModel->bkgAddInfo->bkg_num_small_bag; ?> small bags
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="main">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 offset-lg-1"> 
                <div class="accordion" id="faq">
                    <div class="card">
                        <div class="card-header" id="faqheadC">
                            <a href="#" class="btn btn-header-link text-uppercase" data-toggle="collapse" data-target="#faqC"
                               aria-expanded="true" aria-controls="faqC">Additional booking requirements</a>
                        </div>

                        <div id="faqC" class="collapse" aria-labelledby="faqheadC" data-parent="#faq">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
										<ol class="pl10"><?= ($spclInstruction != "") ? $spclInstruction : "No additional requested." ?></ol>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>        


<div id="main">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 offset-lg-1"> 
                <div class="accordion" id="faq">
                    <div class="card">
                        <div class="card-header" id="faqhead1">
                            <a href="#" class="btn btn-header-link" data-toggle="collapse" data-target="#faq1"
                               aria-expanded="true" aria-controls="faq1">REQUIREMENTS FOR TAXI OPERATOR</a>
                        </div>
						<?php $results		 = TncPoints::getByType(2); ?>
                        <div id="faq1" class="collapse" aria-labelledby="faqhead1" data-parent="#faq">
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
        </div>
    </div>
</div>


<div id="main">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 offset-lg-1"> 
                <div class="accordion" id="faq">
                    <div class="card">
                        <div class="card-header" id="faqheadF">
                            <a href="#" class="btn btn-header-link text-uppercase" data-toggle="collapse" data-target="#faqF"
                               aria-expanded="true" aria-controls="faqF">Trip Cancellation Policy & Operator Compensation policy</a>
                        </div>

                        <div id="faqF" class="collapse" aria-labelledby="faqheadF" data-parent="#faq">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p class="font-18 mb0"><b>Customer Cancellation policy</b></p>
                                        <ul class="pl15"><!-- &#x20B9;<?//= array_values($cancelTimes_new->slabs)[1]; ?> -->
                                            <li>Free cancellation period  (<?= date('d M Y H:i a'); ?> to <?= date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[0])) ?>)</li>
                                            <li>Cancellation Charge  (<?= date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[0])) ?> to <?= date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[1])) ?>)</li>
                                            <li>No Refund  (after <?= date('d M Y H:i a', strtotime($model->bkg_pickup_date)); ?>)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>         


<div id="main">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 offset-lg-1"> 
                <div class="accordion" id="faq">
                    <div class="card">
                        <div class="card-header" id="faqheadB">
                            <a href="#" class="btn btn-header-link" data-toggle="collapse" data-target="#faqB"
                               aria-expanded="true" aria-controls="faqB">TRIP REQUIREMENTS FOR DRIVER</a>
                        </div>
						<?php $results = TncPoints::getByType(3); ?>
                        <div id="faqB" class="collapse" aria-labelledby="faqheadB" data-parent="#faq">
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
        </div>
    </div>
</div>       



<div id="main">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 offset-lg-1"> 
                <div class="accordion" id="faq">
                    <div class="card">
                        <div class="card-header" id="faqheadD">
                            <a href="#" class="btn btn-header-link" data-toggle="collapse" data-target="#faqD"
                               aria-expanded="true" aria-controls="faqD">SPECIAL INSTRUCTIONS & ADVISORIES THAT MAY AFFECT YOUR PLANNED TRAVEL</a>
                        </div>
                        <div id="faqD" class="collapse" aria-labelledby="faqheadD" data-parent="#faq">


                            <div class="row m0">
                                <div class="col-12">
                                    <table class="table table-dark table-striped" style="line-height: 20px;">
                                        <thead>
                                            <tr>
                                                <th scope="col">Place</th>
                                                <th scope="col">Note</th>
                                                <th scope="col">Valid From</th>
                                                <th scope="col">Valid To</th>
                                                <th scope="col">Applicable For</th>
                                            </tr>
                                        </thead>
                                        <tbody>
											<?php
											for ($i = 0; $i < count($note); $i++)
											{
												?>
												<tr>

													<td>
														<?php
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
														?>
													</td>
													<td>
														<?= ($note[$i]['dnt_note']) ?>
													</td>
													<td>
														<?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_from'])) ?>
													</td>
													<td>
														<?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_to'])) ?>
													</td>
													<th scope="row">
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
															else
															{
																echo "";
															}
														}
														?>
													</th>
												</tr>
												<?php
											}
											?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>        


<div class="container">
	<div class="row">
		<div class="col-12 col-lg-10 offset-lg-1 mb30">
			<div class="bg-white-box">
				<div class="row">
					<div class="col-12 list-type-3 font-12 mt0" id="section2">
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
