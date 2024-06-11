<?php
if (!$model)
{
	if ((trim($params['arr']->bookingId)) != '')
	{
		$bookingId	 = trim($params['arr']->bookingId);
		$model		 = Booking::model()->findByBookingid($bookingId);
	}
}
if ($params['otp'] != '')
{
	$otp = $params['otp'];
}
if ($params['refCodeUrl'] != '')
{
	$refCodeUrl = $params['refCodeUrl'];
}
if ($params['payurl'] != '')
{
	$payurl = $params['payurl'];
}
if ($params['cancellationPoints'] != '')
{
	$cancellationPoints = json_decode(json_encode($params['cancellationPoints']),true);
}
if ($params['dosdontsPoints'] != '')
{
	$dosdontsPoints = json_decode(json_encode($params['dosdontsPoints']),true);
}
if ($params['boardingcheckPoints'] != '')
{
	$boardingcheckPoints = json_decode(json_encode($params['boardingcheckPoints']),true);
}
if ($params['othertermsPoints'] != '')
{
	$othertermsPoints = json_decode(json_encode($params['othertermsPoints']),true);
}

/* @var $model Booking */
$routeCityList	 = $model->getTripCitiesListbyId();
$model1			 = clone $model;

$model->bkgInvoice->calculateConvenienceFee(0);
$model->bkgInvoice->calculateTotal();
$fileLink	 = Yii::app()->createAbsoluteUrl('booking/invoice?bkg=' . $model->bkg_id . '&hsh=' . Yii::app()->shortHash->hash($model->bkg_id));
$file		 = "<a href='$fileLink' target='_blank'>Invoice Link</a>";

$ct		 = implode(' -> ', $routeCityList);
//$bookarr['stateTax'] = $model->bkg_is_state_tax_included;
//$bookarr['tollTax'] = $model->bkg_is_toll_tax_included;
$stax	 = 'Excluded';
if ($model->bkgInvoice->bkg_is_state_tax_included == 1)
{
	$stax = 'Included';
}
$ttax = 'Excluded';
if ($model->bkgInvoice->bkg_is_toll_tax_included == 1)
{
	$ttax = 'Included';
}



$splRequest	 = $model->bkgAddInfo->getSpecialRequests();
$grossAmount = $model->bkgInvoice->calculateGrossAmount();
$advance	 = ($model->bkgInvoice->bkg_advance_amount > 0) ? $model->bkgInvoice->bkg_advance_amount : 0;
$creditsused = ($model->bkgInvoice->bkg_credits_used > 0) ? $model->bkgInvoice->bkg_credits_used : 0;
$due		 = $model1->bkgInvoice->bkg_due_amount;

$msg = "";

$flexxiInfo = '
<ol type="1" style="font-size:10px; line-height:15px;padding-left:0px;">
    <li>Your reservation is subject to aaocab terms and conditions.  (http://www.aaocab.com/terms)</li>
    <li>FLEXXI trips are always ONE-WAY trips.  
        <ol type="i">
            <li>
                <strong>ONLY Flexxi Promoters are picked up from their specified source-address and dropped at their destination-address. All other Flexxi Riders must assemble at one of our pre-specified pickup points ahead of the trip start time,</strong> the car will pick them up from  that location and shall drop the Flexxi riders at their respective destination address.
            </li>
            <li>
                <strong> PLEASE BE READY ON TIME FOR PICKUP:</strong> In order to ensure that all our customers receive timely service, we request you to be ready on time. Your trip may be cancelled if you are not ready to travel at the scheduled time.
            </li>
            <li>
                <strong> DRIVER WILL WAIT FOR MAX 15MIN AT PICKUP TIME:</strong> Driver will wait for you for a max of 15mins at the scheduled pickup time and place. Your trip may be canceled or rescheduled at your cost if you are not ready to leave on time.
            </li>
            <li>
                <strong>ON-JOURNEY STOPS ARE NOT ALLOWED FOR FLEXXI TRIPS.</strong> A SINGLE 15-MINUTE COMPLIMENTARY ON JOURNEY BREAK IS INCLUDED FOR TRIPS LONGER THAN 4 HOURS. ALL TRAVELERS TAKE A COMMON 15 MINUTE STOP.
            </li>  
            <li>
                <strong>YOU ARE RESPONSIBLE FOR NIGHT DRIVING CHARGES (IF APPLICABLE): </strong>If your trip is scheduled to start between 10pm and 6am or if the trip is estimated to end post 10pm then night driving allowance charges of Rs. 250/- shall be applicable and shall be distributed evenly across all riders who purchased a seat for the Flexxi trip. Check your confirmation email on whether driver allowance is included. 
            </li>
            <li>
                <strong>REST FOR DRIVER & CHANGE OF VEHICLE:</strong> Our drivers are required to drive not longer than 4 hours continuously taking atleast a 30 minute break. Longer drives may involve a change of vehicle and driver for one-way transfers. 
            </li>     
        </ol>
    </li>  
    <li>Your quoted rate is applicable ONLY for the exact itinerary mentioned in this reservation. 
        <ol type="a">
            <li>
                YOUR QUOTATION is for a point to point drop only as specified by the pickup address, drop address and routing instructions (use of specified routes).
            </li>
            <li>
                Drivable (estimated) kms in your quote are estimates. You will be billed for the actual distance travelled by you. If you have not provided the exact address before your pickup or if the exact address could not be determined at time of providing you the booking amount estimate then your quote includes travel from city center to city center. Distance to be driven as quoted in the booking is only applicable for travel between the addresses, waypoints and locations as exactly specified in your itinerary. 
            </li>
            <li>
                Driver will not agree to any changes to route or itinerary unless the changes are made and the updated itinerary is documented in your reservation. Any changes, additions of waypoints, pick-up points, drop points, halts, destination cities or sightseeing spots are ABSOLUTELY NOT AUTHORIZED unless they are added to your itinerary and confirmed in writing through a booking confirmation email. Changes to itinerary will lead to pricing changes.  
            </li>
            <li>
                It is required that the entire itinerary be documented in your reservation. The quoted price will change based on multiple factors including but not limited to the itinerary, waypoints, driving terrain, local union fees, local restrictions and estimated distances to be driven.
            </li>
        </ol>   
    </li>
    <li>
        One day means one calendar day (12am midnight to 11.59pm next day).
    </li> 
    <li>AT PICKUP TIME. 
        <ol type="a">
            <li>
                You must CHECK IDENTIFICATION OF THE DRIVER AND CONFIRM THE LICENSE PLATE OF YOUR CAR AT THE START OF THE TRIP.
            </li>
            <li>
                DO NOT RIDE IF THE VEHICLE IS NOT COMMERCIALLY LICENSED. (YELLOW COLORED LICENSE PLATE WITH BLACK LETTERS). 
            </li>
            <li>
                DO NOT RIDE IF THE VEHICLE & DRIVER INFORMATION DO NOT MATCH THE INFORMATION PROVIDED BY GOZO. WE SHALL NOT BE RESPONSIBLE OR LIABLE IN ANY MANNER IF YOU CHOOSE TO RIDE IN A VEHICLE THAT IS NOT COMMERCIALLY LICENSED OR RIDE WITH A DRIVER OTHER THAN THE ONE THAT WE HAVE ASSIGNED TO YOU.
            </li>
            <li>
                You may ask the driver for identification to ensure you are riding with the correct driver. Drivers may ask for your identification too. Failure to provide identification will make your booking subject to cancellation at your cost.
            </li>
            <li>
                PROVIDE THE ONE-TIME PASSWORD (OTP) TO THE DRIVER SO HE MAY START THE TRIP. 
            </li>
        </ol>   
    </li> 
    <li>
        <stong>YOU ARE HIRING AN AC CAR.</strong> For drives in hilly regions, the air conditioning may be switched off to prevent engine overload.
    </li>  
    <li>
        <strong>YOU AGREE TO MAKE PAYMENTS FOR YOUR TRIP AS PER THE FOLLOWING PAYMENT SCHEDULE. </strong>
        <ol type="a">
            <li>
                <strong>Advance payment:</strong> You are required to pay in full or a minimum specified percent of total amount as advance to confirm your booking.
            </li>
            <li>
                <strong>100% of customers remaining payable amount </strong>shall be paid to the taxi operator while boarding the cab 
            </li>     
        </ol>    
    </li> 
    <li>
        CANCELLATION: You may cancel your reservation by login to our Mobile App or Website. All bookings cancelled less than 24 hours before the scheduled pickup time shall be subject to Cancellation & Refund Policy as laid down in Terms & Conditions page on our website.
    </li>
    <li>
        INCLUSIONS AND EXCLUSIONS: Your reservation indicates the total number of people and the amount of luggage that the vehicle will accommodate. Please ensure you have clearly communicated the number of passengers and amount of luggage you will carry atleast 24hours before pickup . The driver will not allow any additional passengers or luggage beyond what is allowed in the category of vehicle stated in the reservation. Parking charges, Airport Entry fees or any other form of Entry fees are NOT INCLUDED in the quotation. The customer is responsible for all such additional charges incurred during the trip.
    </li>
    <li>
        When you book our Value or Economy services, we can only promise to provide a vehicle in a certain category but we cannot guarantee a specific model of vehicle. WE CANNOT GUARANTEE THE AVAILABILITY OF A SPECIFIC MODEL OR CONFIGURATION OF VEHICLE UNLESS YOU HAVE MADE RESERVATIONS AND PAID FOR A SERVICE TYPE THAT GUARANTEES A SPECIFIC VEHICLE MODEL.
    </li>
    <li>
        Gozo is committed to punctuality of pickups and high quality of service for all our customers. We are able to offer one-way FLEXXI transfers at extremely low prices by pairing the requirements of multiple customers to travel together in the same taxi. Because multiple travelers are involved, you MUST BE READY ON TIME and travel per the scheduled time. Flexxi Promoters have confirmed travel plans and are looking to save money for their trip. Promoters book the car at full price and then offer unused seats in their car for Flexxi Riders to purchase. Flexxi Riders have flexible travel plans, they choose to buy unused seats in someone else taxi.
    </li>
    <li>
        If you are a FLEXXI promoter, you have booked a car at the normal price and have offered to share some unused seats in your hired taxi with other Flexxi Riders. Gozo is helping find other riders to share the ride with you. If we are able to share other riders, they will share the cost of your trip and you will save money. You may cancel your trip for a full refund upto 24 hours in advance of the trip. If you cancel or reschedule within 24hours of your scheduled trip start time, your booking shall be subject to cancellation charges.
    </li>
    <li>
       If you are a FLEXXI rider, you have flexible plans and are looking to ride for a cheap fare in someone else taxi. You are buying a seat in a taxi that someone else has hired. Gozo is merely acting as a facilitator and enabling you and the other party to save money by sharing the ride together. If the FLEXXI promoter cancels the trip, your trip may likely also be canceled. You may cancel upto 24hours in advance of the trip. If you cancel within 24hours of your scheduled trip start time, your booking shall be subject to cancellation charges.  
    </li>
</ol>
';

$payable = "";
$amount	 = "";
if ($arr['advance'] > 0 || $arr['creditsused'] > 0)
{
	$amount	 .= "<br/><b>Advance Received: </b>Rs. " . $arr['advance'];
	$a		 = $arr['amount'] > 0 ? $arr['amount'] - $arr['advance'] - $arr['creditsused'] : $arr['amount'];
	$payable = $arr['amount'] > 0 ? "<br/><b>Payable to driver: </b>Rs. " . $a : "";
}
if ($arr['trip_type'] == 1)
{
	$amount .= "<br/><b>Cost: </b>Rs. " . $arr['amount'] . $payable;
}
else
{
	$amount .= "<br/><b>Rate: </b>Rs. " . $arr['rate_per_km'] . "/km";
}

if ($arr['crpcreditused'] > 0)
{
	$amount .= "<br/><b>Due: </b>Rs. " . $arr['due'];
}
if ($arr['booking_type'] == 1 || $arr['booking_type'] == 3)
{
	$returnTime = "";
}
else
{
	$returnTime = '<br/><b>Return Time: </b>' . $arr['returnDateTimeFormat'];
}


if ($arr['booking_type'] == 1)
{
	$bookingType = "One Way Drop";
	$dropArea	 = '<br/><b>Drop Area: </b>' . $arr['dropArea'];
	$info		 = $oneWayInfo;
}
elseif ($arr['booking_type'] == 2)
{
	$bookingType = "Return Trip";
	$dropArea	 = "";
	if ($arr['rate_per_km_extra'] != null && $arr['rate_per_km_extra'] != "")
	{
		$info = $returnInfoPerKm;
	}
	else
	{
		$info = "";
	}
}
elseif ($arr['booking_type'] == 9 || $arr['booking_type'] == 10 || $arr['booking_type'] == 11)
{
	$bookingType = Booking::model()->getBookingType($arr['booking_type']);
	$dropArea	 = '<br/><b>Drop Area: </b>' . $arr['dropArea'];
	$info		 = $returnInfoPerKm;
}
elseif ($arr['booking_type'] == 3)
{
	$bookingType = "Multi way Trip";
	if ($arr['rate_per_km_extra'] != null && $arr['rate_per_km_extra'] != "")
	{
		$info = $returnInfoPerKm;
	}
	else
	{
		$info = "";
	}
}
else if ($arr['booking_type'] == 5)
{

	$bookingType = "Package Trip";
	if ($arr['rate_per_km_extra'] != null && $arr['rate_per_km_extra'] != "")
	{
		$info = $returnInfoPerKm;
	}
	else
	{
		$info = "";
	}
}
else if ($arr['booking_type'] == 6)
{

	$bookingType = "Flexxi Share Trip";
//    if ($arr['rate_per_km_extra'] != null && $arr['rate_per_km_extra'] != "")
//    {
	$info		 = $flexxiInfo;
	//  }
	//   else
	//   {
	//$info = "";
	//  } 
}
//change
if ($arr['cod'] > 0 && $arr['crpcreditused'] == 0)
{
	$strCOD = " Waive off your 'collect-on-delivery' fee (Rs." . $arr['cod'] . ") by paying at least Rs." . $arr['minpay'] . ". Revised total fare will be " . $arr['amountWithoutCOD'] . " advance payment before " . $arr['expTimeCashBack'] . " to save up to 50%. Pay now by clicking here: <a href='" . $arr['payurl'] . "'>" . $arr['payurl'] . "</a>";
}
else
{
	if ($arr['due'] > 0)
	{
		$strCOD = " Pay now by clicking here: <a href='" . $arr['payurl'] . "'>" . $arr['payurl'] . "</a>";
	}
}

$msg = 'Hi ' . $arr['userName'] .
		',<br/><br/>We have an update for you.<br/>' .
		'<br/>We have confirmed your reservation. A driver will be assigned soon and we will update you with further details.<br/>' .
		'<br/>The details of your reservation request are as follows:<br/>' .
		'<br/><b>Booking ID: </b>' . $arr['bookingId'] .
		'<br/><b>Type: </b> ' . $bookingType .
		'<br/><b>From: </b>' . $arr['fromCity'] .
		'<br/><b>To: </b>' . $arr['toCity'] .
		'<br/><b>Pickup Address: </b>' . $arr['pickupAddress'] .
		'<br/><b>Pickup Time: </b>' . $arr['pickupFormattedMonthDate'] . $arr['pickupTime'] .
		$dropArea .
		$returnTime .
		'<br/><b>Cab: </b>' . $arr['cabType'] .
		'<br/><b>Primary Phone: </b>' . $arr['primaryPhone'] .
		$amount .
		'<br/><br/>You will receive the cab details at least 3 hours before your scheduled pickup time.<br/>' .
		'<br/>' . $strCOD . '<br/>';
'<br/>You can contact us at +91 90518-77-000 or email us at info@aaocab.com for any queries.<br/>' .
		'<br/>Regards,' .
		'<br/>aaocab<br/><br/>' .
		'<br/>For updates and promotions, like us on <a href="http://www.facebook.com/aaocab">facebook</a> , follow us on <a href="http://www.twitter.com/aaocab">twitter</a> or <a href="https://plus.google.com/113163564383201478409">google+</a> . Who knows you might get a free ride sometime? ;)<br/><br/>';

$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode = $response->getData()->phone['ext'];
}
//$msg.=$info;
?>
<table style="width:98%;border: #d4d4d4 0px solid;border-collapse: collapse; font-family: 'Arial'; font-size: 12px; padding: 5px; color: #000;"  bgcolor="#fff" align="center" cellpadding="5" cellspacing="0">
    <tr>
        <td align="center"><p><strong style="font-size:16px;">FLEXXI SHARE RESERVATION CONFIRMED</strong><br />
<? if ($model->bkg_flexxi_type == 2)
{ ?>
	                Thank you for choosing aaocab. We have confirmed your FLEXXI SHARE reservation request.You will receive the cab details at least 3 hours before your scheduled pickup time.You will be provided a pickup point address atleast 8 hours before your scheduled pickup time. 
<? }
else
{ ?>
					Thank you for choosing aaocab. We have confirmed your FLEXXI SHARE reservation request.You will receive the cab details at least 3 hours before your scheduled pickup time.
				<? } ?>
			</p>
        </td>
    </tr>
				<? if ($otp != '')
				{ ?>
	    <tr>
	        <td class="text-center bg-danger p10 mb10">Please use OTP: <b><?= $otp ?></b> at the time of pickup. Please don't share OTP before boarding the cab.
			</td>
	    </tr>
<? } ?>
    <tr>
        <td>
            <table width="100%" border="0">
                <tr>
                    <td>
                        <table width="100%" border="1" style="border-collapse: collapse;" cellpadding="5" bordercolor="#CCCCCC">
                            <tr>
                                <td width="40%"><strong style="color:#7a7a7a">Booking id</strong></td>
								<td><a href="<?= $payurl ?>" target="_blank"><?= Filter::formatBookingId($model->bkg_booking_id); ?></a></td>
							</tr>
                            <tr>
                                <td><strong style="color:#7a7a7a">Name:</strong></td>
                                <td><?= $model->bkgUserInfo->getUsername() ?></td>
                            </tr>
                            <tr>
                                <td><strong style="color:#7a7a7a">Email:</strong></td>
                                <td><?= $model->bkgUserInfo->bkg_user_email; ?></td>
                            </tr>
                            <tr>
                                <td><strong style="color:#7a7a7a">Phone:</strong></td>
                                <td><?= ($contactNo != '') ? '+' . $countryCode . ' ' . $contactNo : '' ?></td>
                            </tr>
                            <tr>
                                <td><strong style="color:#7a7a7a">Pickup date/TIME:</strong></td>
                                <td><?= date('jS M Y (D) h:i A', strtotime($model->bkg_pickup_date)) ?></td>
                            </tr>
                            <tr>
                                <td><strong style="color:#7a7a7a">PICKUP ADDRESS:</strong></td>
<? if ($model->bkg_flexxi_type == 2)
{ ?>
									<td>PICKUP POINT ADDRESS WILL BE PROVIDED 8HOURS AHEAD OF SCHEDULED PICKUP TIME</td> 
								<? }
								else
								{ ?>
									<td><?= $model->bkg_pickup_address; ?></td>
								<? } ?>
                            </tr>
                            <tr>
                                <td><strong style="color:#7a7a7a">SPECIAL INSTRUCTIONS:</strong></td>
                                <td><?= $splRequest; ?></td>
                            </tr>
                        </table></td>
                    <td width="40%" align="center" valign="top">
                        <table width="100%" border="0">
                            <tr>
                                <td><strong style="color:#7a7a7a">BOOKING  STATUS : </strong><?php
								if ($model->bkg_reconfirm_flag == 0)
								{
									echo '<font style="color:red"><b>RECONFIRM PENDING</b></font>';
								}
								else if ($model->bkg_reconfirm_flag == 1)
								{
									echo '<font style="color:green"><b>RECONFIRMED</b></font>';
								}
								?></td>
                            </tr>
                            <tr>
                                <td><strong style="color:#7a7a7a">ADVANCE  RECEIVED:</strong> <?= number_format($advance) ?></td>
                            </tr>
                            <tr>
                                <td><img src="http://aaocab.com/images/price-guarantee-img.gif" alt="Price Guarantee"  /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table></td>
    </tr>
	<?php
	if ($model->bkgInvoice->bkg_advance_amount <= 0)
	{
		?>
		<tr>
			<td align="center">
				<div>
					<table width="100%" border="0">

						<tr>
							<td align="left" valign="middle"><a href="<?= $payurl ?>" target="_blank"><img src="http://aaocab.com/images/hotlink-ok/paynow_btn.png" alt="Pay Now" /></a></td>
							<td align="left"><span style="font-size:15px; font-weight:bold;">TO LOCK YOUR PRICE AND RECONFIRM YOUR BOOKING</span></td>
						</tr>

					</table>
				</div>
			</td>
		</tr>
		<?php
	}
	?>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><strong style="font-size:16px;">ITINERARY</strong></td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="1" style="border-collapse: collapse;" cellpadding="4" bordercolor="#CCCCCC">
                <tr>
                    <td align="center" bgcolor="#FF6600"><span class="style1">From</span></td>
                    <td align="center" bgcolor="#FF6600"><span class="style1">To</span></td>
                    <td align="center" bgcolor="#FF6600"><span class="style1">Departure  Date</span></td>
                    <td align="center" bgcolor="#FF6600"><span class="style1">Time</span></td>
                    <td align="center" bgcolor="#FF6600"><span class="style1">Estimated  Distance</span></td>
                    <td align="center" bgcolor="#FF6600"><span class="style1">Est.  Travel Duration</span></td>
                </tr>
				<?php
				$last	 = 0;
				$tdays	 = '';
				foreach ($model->bookingRoutes as $k => $brt)
				{
					?>
					<tr>
						<td align="center"><?= $brt->brtFromCity->cty_name; ?><br><?= $brt->brt_from_location; ?></td>
						<td align="center"><?= $brt->brtToCity->cty_name ?><br><?= $brt->brt_to_location ?></td>
						<td align="center"><?= DateTimeFormat::DateTimeToDatePicker($brt->brt_pickup_datetime); ?></td>
						<td align="center"><?= DateTimeFormat::DateTimeToTimePicker($brt->brt_pickup_datetime); ?></td>
						<td align="center"><?= $brt->brt_trip_distance ?> Km</td>
						<td align="center"><?= round($brt->brt_trip_duration / 60) . ' Hours'; ?></td>
					</tr>
					<?php
				}
				?>
            </table>
        </td>
    </tr>
    <tr>
        <td><strong style="font-size:16px;">RENTAL DETAILS</strong></td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="1" style="border-collapse: collapse;" cellpadding="4" bordercolor="#CCCCCC">
                <tr>
                    <td width="30%" align="left"><span style="color:#7a7a7a"><strong>Car  Package For</strong></span></td>
                    <td align="left"><?= $ct; ?></td>
                </tr>
                <tr>
                    <td align="left"><strong style="color:#7a7a7a">Cab  Type </strong></td>
                    <td align="left"><?= '(' . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . ') ' . $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . ' ' . $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc . '(' . $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_capacity . ' seater)' ?></td>
                </tr>
                <tr>
                    <td align="left"><strong style="color:#7a7a7a">Pickup  Address</strong></td>
					<? if ($model->bkg_flexxi_type == 2)
					{ ?>
	                    <td align="left">PICKUP POINT ADDRESS WILL BE PROVIDED 8HOURS AHEAD OF THE SCHEDULED PICKUP TIME </td>
					<? }
					else
					{ ?>
	                    <td align="left"><?= $model->bkg_pickup_address; ?></td>
<? } ?>
                </tr>
                <tr>
                    <td align="left"><strong style="color:#7a7a7a">Final  Drop Address</strong></td>
                    <td align="left"><?= $model->bkg_drop_address; ?></td>
                </tr>
                <tr>
                    <td align="left"><strong style="color:#7a7a7a">Journey  Type</strong></td>
                    <td align="left"><?= $model->getBookingType($model->bkg_booking_type); ?></td>
                </tr>
                <tr>
                    <td align="left"><strong style="color:#7a7a7a">No. of Seats</strong></td>
                    <td align="left"><?= $model->bkgAddInfo->bkg_no_person; ?></td>
                </tr>
                <tr>
                    <td align="left"><strong style="color:#7a7a7a">Pickup  Date Time</strong></td>
                    <td align="left"><?= date('jS M Y (D) h:i A', strtotime($model->bkg_pickup_date)); ?></td>
                </tr>
                <tr>
                    <td align="left"><strong style="color:#7a7a7a">Kms Included  in your quotation</strong></td>
                    <td align="left"><?= $model->bkg_trip_distance; ?> km</td>
                </tr>
                <tr>
                    <td align="left"><strong style="color:#7a7a7a">Charges  beyond <?= $model->bkg_trip_distance ?> Km</strong></td>
                    <td align="left"><?= $model->bkgInvoice->bkg_rate_per_km_extra; ?> / km</td>
                </tr>
                <tr>
                    <td align="left"><strong style="color:#7a7a7a">Included with quotation</strong></td>
                    <td align="left">Flexxi shared seats for transportation from pickup point to specified destination address. This is a shared trip and NO route deviations are allowed<?php
						if ($model->bkgInvoice->bkg_is_toll_tax_included == 1 && $model->bkgInvoice->bkg_is_state_tax_included == 1)
						{
							echo '. Toll & state taxes ' . 'are already included in quoted fare';
						}
						?>
                    </td>
                </tr>
                <tr>
                    <td align="left"><strong style="color:#7a7a7a">Customer to pay separately</strong></td>
                    <td align="left">Any and all airport entry or parking charges; Any parking charges <?php
						if ($model->bkgInvoice->bkg_is_toll_tax_included == 0 && $model->bkgInvoice->bkg_is_state_tax_included == 0)
						{
							echo '; Toll & state taxes';
						}
						?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td><strong style="font-size:16px;">FARE DETAILS</strong></td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="1" style="border-collapse: collapse;" cellpadding="4" bordercolor="#CCCCCC">
                <tr>
                    <td width="80%" align="left"><strong style="color:#7a7a7a">Base  Fare</strong></td>
                    <td align="left">Rs.<strong><em><?= number_format($model->bkgInvoice->bkg_base_amount); ?></em></strong></td>
                </tr>
				<?php
				if ($model->bkgInvoice->bkg_additional_charge > 0)
				{
					?>
					<tr>
						<td style="border: #d4d4d4 1px solid; padding: 5px;"><b>Additional Charge</b></td>
						<td style="border: #d4d4d4 1px solid;padding: 5px;">Rs.<i style="font-weight: bold;"><?= number_format($model->bkgInvoice->bkg_additional_charge) ?></i></td>
					</tr>
					<?php
				}
				if ($model->bkgInvoice->bkg_driver_allowance_amount > 0)
				{
					?>
					<tr>
						<td align="left"><strong style="color:#7a7a7a">Driver  Allowance</strong></td>
						<td align="left">Rs.<strong><em><?= number_format($model->bkgInvoice->bkg_driver_allowance_amount); ?></em></strong></td>
					</tr>
					<?php
				}
				if ($model->bkgInvoice->bkg_discount_amount != 0)
				{
					?>
					<tr>
						<td align="left"><strong style="color:#7a7a7a">Discount Amount (Code : <?= $model->bkgInvoice->bkg_promo1_code ?>)</strong></td>
						<td align="left">(-)Rs.<i style="font-weight: bold;"><?= number_format($model->bkgInvoice->bkg_discount_amount) ?></i></td>
					</tr>
<?php }
?>
                <tr>
                    <td align="left"><strong style="color:#7a7a7a">Amount  (Excl Tax)</strong></td>
                    <td align="left">Rs.<strong><em><?= ($grossAmount - $model->bkgInvoice->bkg_convenience_charge); ?></em></strong></td>
                </tr>

                <tr>
                    <td align="left"><strong style="color:#7a7a7a">Toll  Tax <?= ($model->bkgInvoice->bkg_is_toll_tax_included == 1) ? "(Included)" : "(Excluded)" ?></strong></td>
                    <td align="left">Rs.<strong><em><?= ($model->bkgInvoice->bkg_toll_tax != '') ? $model->bkgInvoice->bkg_toll_tax : 0; ?></em></strong></td>
                </tr>
                <tr>
                    <td align="left"><strong style="color:#7a7a7a">State  Tax <?= ($model->bkgInvoice->bkg_is_state_tax_included == 1) ? "(Included)" : "(Excluded)" ?></strong></td>
                    <td align="left">Rs.<strong><em><?= ($model->bkgInvoice->bkg_state_tax != '') ? $model->bkgInvoice->bkg_state_tax : 0; ?></em></strong></td>
                </tr>
				<?
				$staxrate	 = $model->bkgInvoice->getServiceTaxRate();
				$taxLabel	 = ($staxrate == 5) ? 'GST' : 'Service Tax ';
				?>
				<?
				if ($model->bkgInvoice->bkg_cgst > 0)
				{
					?>
					<tr>
						<td align="left"><strong style="color:#7a7a7a">CGST (@<?= Yii::app()->params['cgst'] ?>%)</strong></td>
						<td align="left">Rs.<strong><em><?= ((Yii::app()->params['cgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax) | 0; ?></em></strong></td>
					</tr>
				<? } ?>
				<?
				if ($model->bkgInvoice->bkg_sgst > 0)
				{
					?>
					<tr>
						<td align="left"><strong style="color:#7a7a7a">SGST (@<?= Yii::app()->params['sgst'] ?>%)</strong></td>
						<td align="left">Rs.<strong><em><?= ((Yii::app()->params['sgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax) | 0; ?></em></strong></td>
					</tr>
				<? } ?>
				<?
				if ($model->bkgInvoice->bkg_igst > 0)
				{
					?>
					<tr>
						<td align="left"><strong style="color:#7a7a7a">IGST (@<?= Yii::app()->params['igst'] ?>%)</strong></td>
						<td align="left">Rs.<strong><em><?= ((Yii::app()->params['igst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax) | 0; ?></em></strong></td>
					</tr>
				<? } ?>
				<?
				if ($staxrate != 5)
				{
					?>
					<tr>
						<td align="left"><strong style="color:#7a7a7a"><?= $taxLabel ?></strong></td>
						<td align="left">Rs.<strong><em><?= $model->bkgInvoice->bkg_service_tax; ?></em></strong></td>
					</tr>
<? } ?>
                <tr>
                    <td align="left" bgcolor="#FF6600"><span style="color: #FFFFFF"><strong style="font-size:20px;">Total  cost: </strong>(if paid in advance)</span></td>
                    <td align="left" bgcolor="#FF6600"><span style="color: #FFFFFF"><strong style="font-size:20px;">Rs.<?= number_format($model->bkgInvoice->bkg_total_amount) ?></strong></span></td>
                </tr>
				<?php
				if ($model1->bkgInvoice->bkg_convenience_charge > 0)
				{
					?>
					<tr>
						<td align="left"><strong style="color:#7a7a7a">Applicable  Collect on Delivery(COD) fee<br />
							</strong>(to be waived if advance payment is received  48hours before start of trip)</td>
						<td align="left">Rs.<strong><?= number_format($model1->bkgInvoice->bkg_total_amount - $model->bkgInvoice->bkg_total_amount) ?></strong></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#FF6600"><span class="style3"><strong style="font-size:20px;">Total  cost: </strong>(if advance payment  not received)</span></td>
						<td align="left" bgcolor="#FF6600"><span class="style3"><strong style="font-size:20px;">Rs. <?= number_format($model1->bkgInvoice->bkg_total_amount); ?></strong></span></td>
					</tr>
					<?php
				}
				if ($advance > 0)
				{
					?>
					<tr>
						<td align="left"><strong style="color:#7a7a7a">Advance  payment received:</strong></td>
						<td align="left">Rs. <?= number_format($advance) ?></td>
					</tr>
					<?php
				}
				?>  
                <tr>
                    <td align="left"><strong style="color:#7a7a7a">Payment  Due</strong></td>
                    <td align="left"><strong style="font-size:20px;">Rs. <?= number_format($due); ?></strong></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="font-size: 13px;"><br><strong><b>Your auto-generated invoice can be viewed :</b> <?= $file; ?></strong><br><br></td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>
   <!-- <tr>
        <td><strong style="font-size:16px;"><u>Important terms and conditions for your trip. </u></strong></td>
    </tr>
    <tr>
        <td>
            <div style="margin-top: 10px; line-height: 20px;">
	<?php
	//$info = $flexxiInfo; 
	//echo $info;
	?>
            </div>
        </td>
    </tr>-->
	<tr>
        <td><strong style="font-size:16px;">FARE INCLUSIONS AND EXCLUSIONS: </strong></td>
    </tr>
	<tr>
		<Td>
			<?php
			$url = 'http://www.aaocab.com'; //'http://gozotech1.ddns.net:6171';

			$correctimg	 = '<img src="http://aaocab.com/images/hotlink-ok/correct.png" height="15" width="15">';
			$crossimg	 = '<img src="http://aaocab.com/images/hotlink-ok/cross.png" height="15" width="15">';
			?>
			<p><span><?= ($model->bkgInvoice->bkg_is_toll_tax_included == 1) ? $correctimg : $crossimg ?> TOLL TAXES</span>
				<span id="tollDesc" class="font-10"><BR>
					[<?php
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
					?>]
				</span>
			</p>
			<p><span><?= ($model->bkgInvoice->bkg_is_state_tax_included == 1) ? $correctimg : $crossimg ?>   STATE TAXES</span>
				<span id="stateDesc" class="font-10"><BR>
					[<?php
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
					?>]
				</span>
			</p>
			<p><?= $crossimg ?> MCD</p> 
			<p>
				<span><?= ($model->bkgInvoice->bkg_is_airport_fee_included == 1) ? $correctimg : $crossimg ?>   AIRPORT ENTRY CHARGES <?= (($model->bkgInvoice->bkg_is_airport_fee_included == 1) ? '(Rs.' . $model->bkgInvoice->bkg_airport_entry_fee . ')' : ''); ?></span>
				<span id="airportDesc" class="font-10"><BR>
					[<?php
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
					?>]
				</span>
			</p>
			<!--<p><?= ($model->bkgInvoice->bkg_night_pickup_included == 1 || $model->bkgInvoice->bkg_night_drop_included == 1) ? $correctimg : $crossimg ?> NIGHT CHARGES 
				<?php echo (!empty($prarr['prr_night_start_time'] && !empty($prarr['prr_night_end_time'])) ? "(" . date("g A", strtotime($prarr['prr_night_start_time'])) . " - " . date("g A", strtotime($prarr['prr_night_end_time'])) . ")" : '') . (!empty($prarr['prr_night_driver_allowance']) ? " - Rs." . $prarr['prr_night_driver_allowance'] : ''); ?> </p>-->
			<p><?= ($model->bkgInvoice->bkg_night_pickup_included > 0) ? $correctimg : $crossimg; ?> NIGHT PICKUP CHARGES 
<?php echo (!empty($prarr['prr_night_start_time'] && !empty($prarr['prr_night_end_time'])) ? "(" . date("g A", strtotime($prarr['prr_night_start_time'])) . " - " . date("g A", strtotime($prarr['prr_night_end_time'])) . ")" : '') . (!empty($prarr['prr_night_driver_allowance']) ? " - Rs." . $prarr['prr_night_driver_allowance'] : ''); ?>
			</p>
			<p><?= ($model->bkgInvoice->bkg_night_drop_included > 0) ? $correctimg : $crossimg; ?>  NIGHT DROP CHARGES  
<?php echo (!empty($prarr['prr_night_start_time'] && !empty($prarr['prr_night_end_time'])) ? "(" . date("g A", strtotime($prarr['prr_night_start_time'])) . " - " . date("g A", strtotime($prarr['prr_night_end_time'])) . ")" : '') . (!empty($prarr['prr_night_driver_allowance']) ? " - Rs." . $prarr['prr_night_driver_allowance'] : ''); ?>
			</p>	
			<p><?= ($model->bkgInvoice->bkg_trip_waiting_charge > 0) ? $correctimg : $crossimg ?> WAITING CHARGES <!--(Rs.120 / HOUR rounded to nearest 30 MINS).--></p>
			<!--<p><? //= ($model->bkgInvoice->bkg_extra_km > 0) ? $correctimg : $crossimg  ?> EXTRA CHARGES <? //= '(Rs.' . $prarr['prr_rate_per_km_extra'] . ' / KM beyond ' . $prarr['prr_driver_allowance_km_limit'] . ' KMS).'  ?></p>-->
			<p><?= ($model->bkgInvoice->bkg_extra_km > 0) ? $correctimg : $crossimg ?> EXTRA CHARGES <?= '(Rs.' . $model->bkgInvoice->bkg_rate_per_km_extra . ' / KM beyond ' . $model->bkg_trip_distance . ' KMS).' ?></p>
			<p><?= $crossimg ?> GREEN TAX </p>
			<p><?= $crossimg ?> ENTRY TAXES / CHARGES</p>
			<p><span><?= ($model->bkgInvoice->bkg_parking_charge == 1) ? $correctimg : $crossimg; ?>  PARKING CHARGES</span>
				<span id="parkingDesc" class="font-10"><BR>
					[<?php
					if ($model->bkgInvoice->bkg_parking_charge > 0)
					{
						?> Parking charges are prepaid upto ₹<?= round($model->bkgInvoice->bkg_parking_charge) ?>.<?php } ?> 
					Customer will directly pay for parking charges after the total parking cost for the trip exceeds ₹<?= round($model->bkgInvoice->bkg_parking_charge) ?>. Driver must upload all parking receipts for payments made by drive.
					]</span>
			</p>

		</Td>
	</tr>
	<Tr>
		<td style="font-size:10px;"><p>FINAL OUTSTANDING SHALL BE COMPUTED AFTER TRIP COMPLETION. ADDITIONAL AMOUNT, IF ANY, MAY BE PAID IN CASH TO THE DRIVER DIRECTLY.</p></td>
	</Tr>

	<tr>
		<td>			
			<div>
				<div><strong style="font-size:16px;">Cancellation information</strong><small>(Booking created at <?= date('d M Y h:i A', strtotime($model->bkg_create_date)); ?>)</small></div>
				<div class="col-12">

					<div>
<?php $cancelTimes_new = CancellationPolicy::initiateRequest($model); ?>
                        <div style="background: #00a388; color:#fff!important; padding: 5px; margin-bottom: 5px;">
                            <p style="text-align: center; color: #fff;"><b>Free cancellation period</b></p>
                            <p style="color:#fff;"><?= date('d M Y H:i a'); ?> <span style="float: right;"><?= date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[0])) ?></span></p>
                        </div>

                        <div style="background: #f36c31; color:#fff!important; padding: 5px; margin-bottom: 5px;">
							<p style="text-align: center; color: #fff;"><b>Cancellation Charge : &#x20B9; <?= array_values($cancelTimes_new->slabs)[1]; ?></b></p>
							<p style="color:#fff;"><?= date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[0])) ?> <span style="float: right;"><?= date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[1])) ?></span></p>
						</div>

                        <div style="background: #ef2b2b; color:#fff!important; padding: 5px; margin-bottom: 5px;">
                            <p style="text-align: center; color: #fff;"><b>Booking is non-refundable</b> <small>(between & after the below mentioned time period)</small></p>
							<p style="color:#fff;"><?= date('d M Y H:i a', strtotime($model->bkg_pickup_date)); ?> <span style="float: right;">After this</span></p>
						</div>	
					</div>
				</div>
			</div>
		</td></tr>

	<tr>
		<td>
			<div style="margin-top: 10px; line-height: 20px;">
				<?php
				if (count($cancellationPoints) > 0)
				{
					echo "<ol style='font-size:10px; line-height:15px;padding-left:25px;'>";
					foreach ($cancellationPoints as $c)
					{
						echo "<li style='list-style-type:  circle'>" . $c->tnp_text . "</li>";
					}
					echo "</ol>";
				}
				?>
			</div>
		</td>
	</tr>
	<tr><td><strong style="font-size:16px;">BOARDING CHECKS</strong></td></tr>
	<TR><TD>
			<div style="margin-top: 10px; line-height: 20px;">
				<?php
				if (count($boardingcheckPoints) > 0)
				{
					echo "<ol style='font-size:10px; line-height:15px;padding-left:25px;'>";
					foreach ($boardingcheckPoints as $c)
					{
						echo "<li style='list-style-type:  circle'>" . $c->tnp_text . "</li>";
					}
					echo "</ol>";
				}
				?>
			</div>
		</TD></TR>
	<tr><td><strong style="font-size:16px;">ON TRIP DOs & DONTs</strong></td></tr>
	<TR><TD>
			<div style="margin-top: 10px; line-height: 20px;">
				<?php
				if (count($dosdontsPoints) > 0)
				{
					echo "<ol style='font-size:10px; line-height:15px;padding-left:25px;'>";
					foreach ($dosdontsPoints as $c)
					{
						echo "<li style='list-style-type:  circle'>" . $c->tnp_text . "</li>";
					}
					echo "</ol>";
				}
				?>
			</div>
		</TD></TR>

	<tr><td><strong style="font-size:16px;">OTHER TERMS</strong></td></tr>
	<TR><TD>
			<div style="margin-top: 10px; line-height: 20px;">
				<?php
				if (count($othertermsPoints) > 0)
				{
					$str = '';
					$str = "<ol type='1' style='font-size:10px; line-height:15px;padding-left:25px;'>";
					foreach ($othertermsPoints as $c)
					{
						$str .= "<li style='list-style-type:  circle'>" . $c->tnp_text . "</li>";
					}
					$str .= "</ol>";
					echo $str;
				}
				?>
			</div>
		</TD></TR>
</table>