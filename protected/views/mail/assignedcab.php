<?
$msg = "";

$oneWayInfo = '<h3>Important Information: </h3>
<ol type="1" style="padding-left:0px;">
    <li>Your reservation is subject to aaocab terms and conditions. (http://www.aaocab.com/terms)</li>
    <li>YOU HAVE BOOKED A ONE-WAY POINT TO POINT JOURNEY: Gozo is committed to punctuality of pickups and high quality of service for all our customers. We are able to offer one-way transfers at a very attractive price by scheduling our vehicles to serve the one-way transfer needs of multiple customers in a sequence. As an example, if you are going one-way from City  A to City B we have estimated your time of travel and have most likely scheduled our driver to pickup another customer in City B.
        <ol type="i">
            <li>
                ONE-WAY means pickup from your source-address and drop at your destination-address. 
            </li>
            <li>
                <strong>PLEASE BE READY ON TIME FOR PICKUP:</strong> In order to ensure that all our customers receive timely service, we request you to be ready on time. Your trip may be cancelled if you are not ready to travel at the scheduled time. 
            </li>
            <li>
                <strong>DRIVER WILL WAIT FOR MAX 15MIN AT PICKUP TIME:</strong> Driver will wait for you for a max of 15mins at the scheduled pickup time and place. Your trip may be canceled or rescheduled at your cost if you are not ready to leave on time.
            </li>
            <li>
                <strong>ON-JOURNEY STOPS OR WAYPOINTS ARE NOT ALLOWED UNLESS PART OF ITINERARY: </strong>If you plan to take a lunch break or stop during the journey, let us know before time so we can estimate the time of your ‘on journey’ break and avoid any scheduling issues for our vehicle. DRIVER WILL NOT STOP ON THE ROUTE UNLESS ITS PART OF ITINERARY AND WRITTEN IN THIS CONFIRMATION EMAIL.  A SINGLE 15-MINUTE COMPLIMENTARY ON JOURNEY BREAK IS INCLUDED FOR TRIPS LONGER THAN 4 HOURS.
            </li>
            <li>
                <strong>WAITING CHARGES: </strong>If the cab is requested to wait at any time along your trip (provided the cab driver is able to wait) you will be charged for waiting charges at the rate of Rs.120/hour rounded to the closest 30 minutes. 
            </li>
            <li>
                <strong>YOU ARE RESPONSIBLE FOR NIGHT DRIVING CHARGES (IF APPLICABLE): </strong>If your trip is scheduled to start between 10pm and 6am or if the trip is estimated to end post 10pm then night driving allowance charges of Rs. 250/- shall also be applicable. Check your confirmation email on whether driver allowance is included. 
            </li>
            <li> 
                <strong>REST FOR DRIVER & CHANGE OF VEHICLE:</strong> Our drivers are required to drive not longer than 4 hours continuously taking atleast a 30 minute break. Longer drives may involve a change of vehicle and driver for one-way transfers. 
            </li>
        </ol>
    <li>Your quoted rate is applicable ONLY for the exact itinerary mentioned in this reservation. </li> 
    <ol type="a">
        <li>YOUR QUOTATION is for a point to point drop only as specified by the pickup address, drop address and routing instructions (use of specified routes).</li>
        <li>Drivable (' . $model->bkg_trip_distance  . ')  kms in your quote are estimates. You will be billed for the actual distance travelled by you. If you have not provided the exact address before your pickup or if the exact address could not be determined at time of providing you the booking amount estimate then your quote includes travel from city center to city center. Distance to be driven as quoted in the booking is only applicable for travel between the addresses, waypoints and locations as exactly specified in your itinerary.</li>
        <li>Driver will not agree to any changes to route or itinerary unless the changes are made and the updated itinerary is documented in your reservation. Any changes, additions of waypoints, pick-up points, drop points, halts, destination cities or sightseeing spots are ABSOLUTELY NOT AUTHORIZED unless they are added to your itinerary and confirmed in writing through a booking confirmation email. Changes to itinerary will lead to pricing changes.</li>
        <li>It is required that the entire itinerary be documented in your reservation. The quoted price will change based on multiple factors including but not limited to the itinerary, waypoints, driving terrain, local union fees, local restrictions and estimated distances to be driven.</li>    
    </ol>
    <li>We require exact pickup and drop addresses to be provided for your itinerary before your vehicle and driver can be assigned. Unless we have these atleast 12hours before your trip the reservation will be subject to cancellation and all resulting cancellation charges are to be borne by you. Once the addresses are provided, these may cause the quotation to change.</li>
    <li>One day means one calendar day (12am midnight to 11.59pm next day).</li>
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
            <li>
                <strong>YOU AGREE TO MAKE PAYMENTS FOR YOUR TRIP AS PER THE FOLLOWING PAYMENT SCHEDULE. </strong>
                <ol type="i">
                    <li>
                        <strong>Advance payment:</strong> You are required to pay in full or a minimum specified percent of total amount as advance to confirm your booking.
                    </li>  
                    <li>
                        <strong>15% of customers remaining payable amount </strong> shall be paid to the taxi operator while boarding the cab
                    </li>  
                    <li>
                        <strong>Daily part payments: </strong> You are required to pay the remaining the amount in equal parts and is payable per day during your remaining days of the trip. The customer must settle all payment atleast 24hours last day of the trip or as requested by the taxi operator.  
                    </li>
                </ol>
            </li>
        </ol> 
    </li>
    <li>
        <strong>YOU ARE HIRING AN AC CAR. </strong>For drives in hilly regions, the air conditioning may be switched off to prevent engine overload.
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
</ol>';

$airportInfo = '<h3>Important Information: </h3>
    <ol type="1" style="padding-left:0px;">
    <li>Your reservation is subject to aaocab terms and conditions. (http://www.aaocab.com/terms)</li>
    <li>YOU HAVE BOOKED A LOCAL RENTAL FOR AIRPORT TRANSFER:Gozo is committed to punctuality of pickups and high quality of service for all our customers. We are able to offer low priced airport transfers at a very attractive price by scheduling our vehicles to serve the airport pickup and drop needs of multiple customers in a sequence. As an example, if you are going from City center to Airport we have estimated your time of travel and have most likely scheduled our driver to pickup another customer at the Airport.
        <ol type="a">
            <li>
                AIRPORT TRANSFER means pickup from your source-address and drop at your destination-address with at least one of the two locations being an airport.   </li>
            </li>
            <li>
                <strong>PLEASE BE READY ON TIME FOR PICKUP: </strong>In order to ensure that all our customers receive timely service, we request you to be ready on time. Your trip may be cancelled if you are not ready to travel at the scheduled time.
            </li>
            <li>
               <strong>WAITING CHARGES:</strong> If the cab is requested to wait at any time along your trip(provided the cab driver is able to wait) you will be charged for waiting charges at the rate of Rs.120/hour rounded to the closest 30minutes. 
            </li>
            <li>
               <strong>YOU ARE RESPONSIBLE FOR NIGHT DRIVING CHARGES (IF APPLICABLE):</strong> If your trip is scheduled to start between 10pm and 6am or if the trip is estimated to end post 10pm then night driving allowance charges of Rs. 250/- shall also be applicable. Check your confirmation email on whether driver allowance is included. 
            </li>  
        </ol>
    </li>  
    <li>
        Your quoted rate is applicable ONLY for the exact itinerary mentioned in this reservation. 
    </li>
    <li>
        AT PICKUP TIME. 
        <ol type="I">
            <li>
                You must CHECK IDENTIFICATION OF THE DRIVER AND CONFIRM THE LICENSE PLATE OF YOUR CAR AT THE START OF THE TRIP.
            </li>
            <li>
                DO NOT RIDE IF THE VEHICLE IS NOT COMMERCIALLY LICENSED. (YELLOW COLORED LICENSE PLATE WITH BLACK LETTERS). II.	DO NOT RIDE IF THE VEHICLE IS NOT COMMERCIALLY LICENSED. (YELLOW COLORED LICENSE PLATE WITH BLACK LETTERS). 
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
        <strong>YOU ARE HIRING AN AC CAR.</strong> For drives in hilly regions, the air conditioning may be switched off to prevent engine overload.
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
</ol>';

$returnInfoPerKm = '<h3>Important Information: </h3>
<ol type="1" style="padding-left:0px;">
    <li>Your reservation is subject to aaocab terms and conditions.</li>
    <li>YOU HAVE BOOKED A TIME & DISTANCE BASED RENTAL 
        <ol type="1"> 
            <li>
                <strong>TIME & DISTANCE BASED RENTAL:</strong> This quotation is for time-and-distance based rental with a exact itinerary as specified by your pickup & drop addresses, routing and cities listed on your itinerary. You may not direct the vehicle outside the bounds of the towns and cities listed in your itinerary. 
            </li>
            <li>
                <strong>SIGHTSEEING PLAN MUST BE DOCUMENTED IN ITINERARY:</strong> Your rental DOES NOT INCLUDE ANY SIGHTSEEING UNLESS EXPLICITLY NOTED IN THE ITINERARY. Any sightseeing even when listed is limited to be within the city limits of the listed cities/towns on the itinerary. Please share your complete trip plan with Gozo so we can avoid any confusion or inconvenience to you by having the trip plan clearly listed in your planned itinerary.   
            </li>
            <li>
                <strong>YOU SHALL BE BILLED FOR THE SUM OF OUR TIME & DISTANCE BASED ESTIMATE (ESTIMATED HOURS & KMS) AND ANY ADDITIONAL TIME OR DISTANCE USED BY YOU.</strong> As an example, For all time-and-distance based outstation rentals, a minimum of 250km (North India) or 300km (South India) charge will be billed per day of rental even if the vehicle is not utilized for those distances per day of rental. For all time-and-distance based local rentals, the included hours and kms is specified in your booking. 
            </li>
            <li>
                <strong>YOU AGREE TO MAKE PAYMENTS FOR YOUR TRIP AS PER THE FOLLOWING PAYMENT SCHEDULE.</strong>
                <ol type="1">
                    <li>
                        <strong>Advance payment:</strong> You are required to pay in full or a minimum specified percent of total amount as advance to confirm your booking.
                    </li>
                    <li>
                        <strong>50% of customers remaining payable amount </strong>shall be paid to the taxi operator while boarding the cab on the first day of trip
                    </li>
                    <li>
                        <strong>Daily part payments:</strong> You are required to pay the remaining the amount in equal parts and is payable per day during your remaining days of the trip. The customer must settle all payment atleast 24hours last day of the trip or as requested by the taxi operator. 
                    </li>
                </ol>
            </li>
            <li>
                Driver will not agree to any changes to itinerary unless the changes are made and the updated itinerary is documented in your reservation. Any changes, additions of waypoints, pick-up points, drop points, halts, destination cities or sightseeing spots are ABSOLUTELY NOT AUTHORIZED unless they are added to your itinerary and confirmed in writing through a booking confirmation email. Changes to itinerary will lead to pricing changes
            </li>
            <li>
                 <strong>YOU ARE RESPONSIBLE FOR DRIVERS DAY-TIME DRIVING ALLOWANCE & NIGHT DRIVING CHARGES (IF APPLICABLE): </strong>For all time-and-based rentals a Rs. 250/- daytime allowance is payable by the customer to the driver per day. If the driver is required to drive between 10pm and 6am on any day during the period of the rental then a NIGHT DRIVING ALLOWANCE of additional Rs. 250/- shall also be payable for the days when the driver was asked to drive at night. Your confirmation email clearly states whether driver allowance is included in your quotation or to be paid seperately. 
            </li>
            <li>
                It is required that the entire itinerary be documented in your reservation. The quoted price will change based on multiple factors including but not limited to the itinerary, waypoints, driving terrain, local union fees, local restrictions and estimated distances to be driven
            </li>  
        </ol>
    </li>   
    <li>
        One day means one calendar day (12am midnight to 11.59pm next day). Time-and-distance based rentals where the customers usage exceeds the original estimate by longer than 4 hours are rounded up to a full additional day. 
    </li>
    <li>
        We require exact pickup and drop addresses to be provided for your itinerary before your vehicle and driver can be assigned. Unless we have these atleast 12hours before your trip,the reservation will be subject to cancellation and all resulting cancellation charges are to be borne by you. Once the addresses are provided, these may cause the quotation to change.
    </li>
    <li>
       We take security seriously.
       <ol  type="1">
           <li>
                You MUST CHECK identification of the driver and confirm the license plate of your car AT THE START OF THE TRIP. If the Driver name and ID do not match the name provided to you by Gozo, please DO NOT RIDE unless it has been OK’ed with a new SMS directly from aaocab first. Please ensure that the license plate of the car matches the information provided to you by Gozo. Gozo only provides you taxis that carry a commercial license permit (License plate is yellow with black letters). 
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
       At pickup time, your cab will wait for a maximum of 30 minutes. If requested to wait longer (provided the cab driver is able to wait) you will be responsible for waiting charges at the rate of Rs.200/hour.
    </li>
    <li>
      <strong>YOU ARE HIRING AN AC CAR. </strong>For drives in hilly regions, the air conditioning may be switched off to prevent engine overload.    
    </li>
    <li>
        CANCELLATION: You may cancel your  reservation by logging onto www.aaocab.com and and cancelling your reservation directly. All bookings cancelled less than 24hours before a pickup shall be subject to a cancellation charge.
    </li>
    <li>
       INCLUSIONS AND EXCLUSIONS: Your reservation indicates the total number of people and the amount of luggage that the vehicle will accommodate. Please ensure you have clearly communicated the number of passengers and amount of luggage you will carry atleast 24hours before pickup . The driver will not allow any additional passengers or luggage beyond what is allowed in the category of vehicle stated in the reservation. Parking charges, Airport Entry fees or any other form of Entry fees are NOT INCLUDED in the quotation. The customer is responsible for all such additional charges incurred during the trip.
    </li>
    <li>
       When you book our Value or Economy services, we can only promise to provide a vehicle in a certain category but we cannot guarantee a specific model of vehicle. WE CANNOT GUARANTEE THE AVAILABILITY OF A SPECIFIC MODEL OR CONFIGURATION OF VEHICLE UNLESS YOU HAVE MADE RESERVATIONS AND PAID FOR A SERVICE TYPE THAT GUARANTEES A SPECIFIC VEHICLE MODEL.
    </li>
</ol>';

$payable = "";
$amount	 = "";
if ($model->bkgInvoice->bkg_advance_amount > 0 || $model->bkgInvoice->bkg_credits_used > 0)
{
	$amount	 .= "<br/><b>Advance Received: </b>Rs. " . $model->bkgInvoice->bkg_advance_amount;
	$a		 = $model->bkgInvoice->bkg_total_amount > 0 ? $model->bkgInvoice->bkg_total_amount - $model->bkgInvoice->bkg_advance_amount - $model->bkgInvoice->bkg_credits_used : $model->bkgInvoice->bkg_total_amount;
	$payable = $model->bkgInvoice->bkg_total_amount > 0 ? "<br/><b>Payable to driver: </b>Rs. " . $a : "";
}

//if ($model->bkg_trip_type == 1)
//{
	$amount .= "<br/><b>Cost: </b>Rs. " . $model->bkgInvoice->bkg_total_amount . $payable;
//}
//else
//{
//	$amount .= "<br/><b>Rate: </b>Rs. " . $model->bkg_rate_per_km . "/km";
//}


$amount .= "<br/><b>Due: </b>Rs." . $model->bkgInvoice->bkg_due_amount;

if ($model->bkgInvoice->bkg_corporate_credit > 0 && $model->bkgInvoice->bkg_corporate_remunerator == 2)
{
	$amount = "";
}
if ($arr['b2b'])
{
	$amount = "";
}

if ($model->bkg_booking_type == 1 || $model->bkg_booking_type == 3)
{
	$returnTime = "";
}
else
{
	$returnTime = '<br/><b>Return Time: </b>' . date('jS M Y (l) h:i A', strtotime($model->bkg_return_date));
}
$scvVctId = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id); 
if ($model->bkg_booking_type == 1)
{
    $bookingType = "One Way Drop";
    $dropArea    = '<br/><b>Drop Area: </b>' . $model->bkg_drop_address;
    $info	 = '';
}
elseif ($model->bkg_booking_type == 4){
    $bookingType = "Airport Drop";
    $dropArea	 = '<br/><b>Drop Area: </b>' . $model->bkg_drop_address;
    $info	 = '';  
}
elseif ($model->bkg_booking_type == 9 || $model->bkg_booking_type == 10 || $model->bkg_booking_type == 11){
    $bookingType = Booking::model()->getBookingType($model->bkg_booking_type);
    $dropArea	 = '<br/><b>Drop Area: </b>' . $model->bkg_drop_address;
    $info	 = '';
}
elseif ($model->bkg_booking_type == 2)
{
    $bookingType = "Return Trip";
    $dropArea	 = "";
    if ($model->bkgInvoice->bkg_rate_per_km_extra != null && $model->bkgInvoice->bkg_rate_per_km_extra != "")
    {
        $info = '';
    }
    else
    {
        $info = "";
    }
}
elseif ($model->bkg_booking_type == 3)
{
	$bookingType = "Multi way Trip";
	if ($model->bkgInvoice->bkg_rate_per_km_extra != null && $model->bkgInvoice->bkg_rate_per_km_extra != "")
	{
		$info = '';
	}
	else
	{
		$info = "";
	}
}
elseif ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
{
	$bookingType = "Flexxi Share Trip";
	$dropArea	 = '<br/><b>Drop Area: </b>' . $model->bkg_drop_address;
	$info		 = '';
}

$vehicleModel = $cabModel->bcbCab->vhcType->vht_model;
if($cabModel->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
{
	$vehicleModel = OperatorVehicle::getCabModelName($cabModel->bcb_vendor_id, $cabModel->bcb_cab_id);
}

$msg				 = 'Hi ' . $model->bkgUserInfo->getUsername() .
		',<br/><br/>We have an update for you.<br/>' .
		',<br/><br/>A cab has been allocated for your reservation. Driver Name, contact information & Cab license plate will be sent to you upto 4 hours before the pickup time.' .
		'<br/><b>Cab Type: </b>' . $vehicleModel .
		'<br/><b>Cab Number: </b>' . $cabModel->bcbCab->vhc_number;
$showUserInfoPickup	 = Booking::model()->customerInfoByPickup1($model->bkg_id, 6);
if ($showUserInfoPickup == 1)
{
	//$driver_phone = $cabModel->bcb_driver_phone;
       
		$driver_phone = BookingPref::getDriverNumber($model, $cabModel->bcb_driver_phone);       

//    $stateZone    = $model->bkgFromCity->ctyState->stt_zone;
//    if (($model->bkg_agent_id == null || $model->bkg_agent_id == '' || $model->bkg_agent_id == Yii::app()->params['gozoChannelPartnerId'] || (in_array($stateZone, [4, 7]) && ($model->bkg_agent_id == 450 || $model->bkg_agent_id == 18190)) ) && $model->bkg_country_code == 91)
//    if ( $model->bkg_agent_id == 450 && $model->bkg_country_code == 91)
	if ($model->bkgUserInfo->bkg_country_code == 91)
	{
		//$driver_phone = Yii::app()->params['customerToDriver'];
       $driver_phone = BookingPref::getDriverNumber($model, $cabModel->bcb_driver_phone);
		if ($model->bkg_agent_id == 450 || $model->bkg_agent_id == 18190)
		{
		//$driver_phone = Yii::app()->params['customerToDriverforMMT'];
        $driver_phone = BookingPref::getDriverNumber($model, $cabModel->bcb_driver_phone);
              
		}
	}
	$msg .= '<br/><b>Driver Name: </b>' . $cabModel->bcbDriver->drv_name . '<br/>
            <b>Driver Phone: </b>' . $driver_phone . '<br/>';
}
$payurl	= BookingUser::getPaymentLinkByEmail($model->bkg_id);
$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode = $response->getData()->phone['ext'];
}
$vehicleModel = $cabModel->bcbCab->vhcType->vht_model;
if($cabModel->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
{
	$vehicleModel = OperatorVehicle::getCabModelName($cabModel->bcb_vendor_id, $cabModel->bcb_cab_id);
}
$bookingId = Filter::formatBookingId($model->bkg_booking_id);
$msg .= '<br/>The details of your reservation request are as follows:<br/>' .
		'<br/><b>Booking ID: </b><a href='.$payurl.' target="_blank">' . $bookingId .'</a>'.
		'<br/><b>Type: </b> ' . $bookingType .
		'<br/><b>From: </b>' . $model->bkgFromCity->cty_name .
		'<br/><b>To: </b>' . $model->bkgToCity->cty_name .
		'<br/><b>Pickup Address: </b>' . $model->bkg_pickup_address .
		'<br/><b>Pickup Time: </b>' . date('jS M Y (l) ', strtotime($model->bkg_pickup_date)) . date('h:i A', strtotime($model->bkg_pickup_date)) .
		$dropArea .
		$returnTime .
		'<br/><b>Cab: </b>' . $vehicleModel .
		'<br/><b>Primary Phone: </b>' . $contactNo .
		$amount .
		'<br/><br/>';
if (($model->bkgInvoice->bkg_advance_amount == 0 || $model->bkgInvoice->bkg_advance_amount == 0.00 ) && !$arr['b2b'])
{
	$msg .= '<br/>Please pay some amount to the dirver at the start of the trip to cover petrol/diesel and other expenses. For security reasons, our drivers do not carry much cash.<br/>';
}
$promsg = '<br/>For updates and promotions, like us on <a href="http://www.facebook.com/aaocab">facebook</a> , follow us on <a href="http://www.twitter.com/aaocab">twitter</a> or <a href="https://plus.google.com/113163564383201478409">google+</a> . Who knows you might get a free ride sometime? ;)<br/><br/>';
if ($arr['b2b'])
{
	$promsg = "";
}
$msg .= '<br/>You can contact us at +91 90518-77-000 or email us at info@aaocab.com for any queries.<br/>' .
		'<br/>Regards,' .
		'<br/>aaocab<br/><br/>' . $promsg;

return $msg ;


//echo $msg;
