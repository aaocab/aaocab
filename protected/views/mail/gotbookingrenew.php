<?php
/* @var $model Booking */
$routeCityList	 = $model->getTripCitiesListbyId();
$model1			 = clone $model;
$model->bkgInvoice->calculateConvenienceFee(0);
$model->bkgInvoice->calculateTotal();
//$carType= VehicleTypes::model()->getVehicleTypeById($model->bkg_vehicle_type_id);
$carType		 = $model->bkg_vehicle_type_id;

$sccLabel = SvcClassVhcCat::getVctSvcList("string", 0, 0, $carType);
//echo $sccLabel;
if ($model->bkg_booking_type != 8)
{
	$priceRule					 = AreaPriceRule::model()->getValues($model->bkg_from_city_id, $carType, $model->bkg_booking_type);
	$prr_day_driver_allowance	 = $priceRule['prr_day_driver_allowance'];
	$prr_Night_driver_allowance	 = $priceRule['prr_night_driver_allowance'];
}

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

$createTime			 = $model->bkg_create_date;
$hourdiff			 = BookingPref::model()->getWorkingHrsCreateToPickupByID($model->bkg_id);
$timeTwentyPercent	 = round($hourdiff * 0.2);
$new_time2			 = date("Y-m-d H:i:s", strtotime('+' . $timeTwentyPercent . ' hours', strtotime($createTime)));
$new_time			 = ($model->bkgTrail->bkg_quote_expire_date != '') ? $model->bkgTrail->bkg_quote_expire_date : $new_time2;
$getpickupTo42WH	 = BookingSub::model()->getpickupTo42WH($model->bkg_id, 42);
$lesserTime			 = (strtotime($new_time) < strtotime($getpickupTo42WH)) ? $new_time : $getpickupTo42WH;

$splRequest	 = $model->bkgAddInfo->getSpecialRequests();
$grossAmount = $model->bkgInvoice->calculateGrossAmount();
$advance	 = ($model->bkgInvoice->bkg_advance_amount > 0) ? $model->bkgInvoice->bkg_advance_amount : 0;
$creditsused = ($model->bkgInvoice->bkg_credits_used > 0) ? $model->bkgInvoice->bkg_credits_used : 0;
$due		 = $model1->bkgInvoice->bkg_due_amount;

$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode = $response->getData()->phone['ext'];
}

$rtInfoArr	 = $model->getRoutesInfobyId();
$rutInfoText = "";
if (sizeof($rtInfoArr) > 0 && $rtInfoArr[0]['rut_special_remarks'])
{
	foreach ($rtInfoArr as $info)
	{
		$rutInfoText .= "<li  type='1'>" . $info['rut_special_remarks'] . "</li>";
	}
	?>

	<? } ?>
	<?
	$oneWayInfo = '
	<ol type="1" style="font-size:10px; line-height:15px;padding-left:5px;">
	<li>Your reservation is subject to Gozocabs terms and conditions. (http://www.aaocab.com/terms)</li>
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
	<strong>WAITING CHARGES: </strong>If the cab is requested to wait at any time along your trip(provided the cab driver is able to wait) you will be charged for waiting charges at the rate of Rs.300/hour rounded to the closest 30minutes. 
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
	<li>Drivable (' . $model->bkg_trip_distance . ')  kms in your quote are estimates. You will be billed for the actual distance travelled by you. If you have not provided the exact address before your pickup or if the exact address could not be determined at time of providing you the booking amount estimate then your quote includes travel from city center to city center. Distance to be driven as quoted in the booking is only applicable for travel between the addresses, waypoints and locations as exactly specified in your itinerary.</li>
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
	<strong>50% of customers remaining payable amount </strong> shall be paid to the taxi operator while boarding the cab
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
	INCLUSIONS AND EXCLUSIONS: Your reservation indicates the total number of people and the amount of luggage that the vehicle will accommodate. Please ensure you have clearly communicated the number of passengers and amount of luggage you will carry atleast 24hours before pickup . The driver will not allow any additional passengers or luggage beyond what is allowed in the category of vehicle stated in the reservation. Parking charges or any other form of Entry fees are NOT INCLUDED in the quotation. The customer is responsible for all such additional charges incurred during the trip.
	</li>
	<li>
	When you book our Value or Economy services, we can only promise to provide a vehicle in a certain category but we cannot guarantee a specific model of vehicle. WE CANNOT GUARANTEE THE AVAILABILITY OF A SPECIFIC MODEL OR CONFIGURATION OF VEHICLE UNLESS YOU HAVE MADE RESERVATIONS AND PAID FOR A SERVICE TYPE THAT GUARANTEES A SPECIFIC VEHICLE MODEL.
	</li>
	<li>
	It is required that the entire itinerary be documented in your reservation. The quoted price will change based on multiple factors including but not limited to the itinerary, waypoints, driving terrain, local union fees, local restrictions and estimated distances to be driven.
	</li>
	</ol>';

	$airportInfo = '<ol type="1" style="font-size:10px; line-height:15px;padding-left:5px;">
	<li>Your reservation is subject to Gozocabs terms and conditions. (http://www.aaocab.com/terms)</li>
	<li>YOU HAVE BOOKED A LOCAL RENTAL FOR AIRPORT TRANSFER:Gozo is committed to punctuality of pickups and high quality of service for all our customers. We are able to offer low priced airport transfers at a very attractive price by scheduling our vehicles to serve the airport pickup and drop needs of multiple customers in a sequence. As an example, if you are going from City center to Airport we have estimated your time of travel and have most likely scheduled our driver to pickup another customer at the Airport.
	<ol type="a">
	<li>
	AIRPORT TRANSFER means pickup from your source-address and drop at your destination-address with at least one of the two locations being an airport.   </li>
	</li>
	<li>
	<strong>PLEASE BE READY ON TIME FOR PICKUP: </strong>In order to ensure that all our customers receive timely service, we request you to be ready on time. Your trip may be cancelled if you are not ready to travel at the scheduled time.
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

	$returnInfoPerKm = '
	<ol type="1" style="font-size:10px; line-height:15px;padding-left:5px;">
	<li>Your reservation is subject to Gozocabs terms and conditions.</li>
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
	<ol type="1">
	<li>
	You MUST CHECK identification of the driver and confirm the license plate of your car AT THE START OF THE TRIP. If the Driver name and ID do not match the name provided to you by Gozo, please DO NOT RIDE unless it has been OK’ed with a new SMS directly from Gozocabs first. Please ensure that the license plate of the car matches the information provided to you by Gozo. Gozo only provides you taxis that carry a commercial license permit (License plate is yellow with black letters). 
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

	$flexxiInfo	 = '
	<ol type="1" style="font-size:10px; line-height:15px;padding-left:5px;">
	<li>Your reservation is subject to Gozocabs terms and conditions.  (http://www.aaocab.com/terms)</li>
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
	$isDboMaster = Yii::app()->params['dboMaster'];
	?>

	<table style="width:98%;border: #d4d4d4 0px solid;border-collapse: collapse; font-family: 'Arial'; font-size: 12px; padding: 5px; color: #000;"  bgcolor="#fff" align="center" cellpadding="5" cellspacing="0">
	    <tr>
	        <td align="center"><p style="font-size: 13px;"><strong style="font-size:16px;"><?php
						if ($model->bkgInvoice->bkg_advance_amount > 0)
						{
							echo 'RESERVATION CONFIRMED';
						}
						else
						{
							if ($model->bkg_status == 15)
							{
								echo 'QUOTATION FOR YOUR TRIP';
							}
							else
							{
								echo 'RESERVATION CREATED';
							}
						}
						?></strong><br /><?php
					if ($model->bkgInvoice->bkg_advance_amount > 0)
					{
						echo 'Thank you for choosing GozoCabs. We have confirmed your reservation request.You will receive the cab details at least 3 hours before your scheduled pickup time.';
					}
					else
					{
						if ($model->bkg_status == 15)
						{
							if ($isDboMaster == 0)
							{
								echo 'Here is your quotation as requested. <a href="' . $payurl . '">Confirm this booking by making a payment before <strong>' . date('jS M Y (D) h:i A', strtotime($new_time)) . '</strong></a>. This price is subject to change unless payment is received via the link above. Booking will be confirmed as soon as payment is received.';
							}
							if ($isDboMaster == 1 && $timediff < 42)
							{
								$termsUrl = Filter::shortUrl('http://www.aaocab.com/terms/doubleback');
								echo 'Here is your quotation as requested. <a href="' . $payurl . '">Confirm this booking by making a payment  before  <strong>' . date('jS M Y (D) h:i A', strtotime($new_time)) . '</strong> </a>. This price is subject to change unless payment is received via the link above. Booking will be confirmed as soon as payment is received . This booking does NOT qualify for our <a href=" '.$termsUrl.' ">DOUBLE-BACK</a> program.';
							}
							if ($isDboMaster == 1 && $timediff > 42)
							{
								echo 'Here is your quotation as requested. This price is subject to change unless payment is received via the link above. Booking will be confirmed as soon as payment is received.';
							}
						}
						else
						{
							echo 'Thank  you for choosing GozoCabs. We have received your reservation request.';
						}
					}
					?>
				</p></td>
	    </tr>
		<?
		if ($otp != '' && $model->bkg_status != 15)
		{
		?>
		<tr>
			<td class="text-center p10 mb10"><div style="background: #f25656!important; color: #fff!important; text-align: center!important; padding: 8px 0; margin: 0 3px -13px 3px;">Please use OTP: <b><?= $otp ?></b> at the time of pickup. Please don't share OTP before boarding the cab.</td>
		</tr>
		<? } ?>
	    <tr>
	        <td>
	            <table width="100%" border="0">
	                <tr>
	                    <td>
	                        <table width="100%" border="1" style="border-collapse: collapse;" cellpadding="5" bordercolor="#CCCCCC">
	                            <tr>
									<td width="40%"><strong style="color:#7a7a7a"><?php echo ($model->bkg_status == 15) ? "Quote id" : "Booking id"; ?></strong></td>
									<td>
										<a href="<?= $payurl ?>" target="_blank"><?= Filter::formatBookingId($model->bkg_booking_id); ?></a>
									</td>

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
	                                <td><?= $model->bkg_pickup_address; ?></td>
	                            </tr>

								<tr>
	                                <td><strong style="color:#7a7a7a">SPECIAL INSTRUCTIONS:</strong></td>
	                                <td>Pax: <?= $luggageCapacity->noOfPersons ?> persons (max) | Luggage: 
									<?=(($luggageCapacity->largeBag !=0)?$luggageCapacity->largeBag. ' Big Bags /':'') ?>
									<?=(($luggageCapacity->smallBag !=0)?$luggageCapacity->smallBag. ' Small luggage (max)':'') ?>
									<!--<?//= $luggageCapacity->largeBag ?> Big Bags / <?//= $luggageCapacity->smallBag ?> Small luggage (max)-->
									</td>
	                            </tr>
								<?php
								$sccDesc		 = $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_desc;
								$arrServiceDesc	 = json_decode($sccDesc);
								$serviceDesc	 = '';
								foreach ($arrServiceDesc as $key => $value)
								{
									if ($key != 0)
									{
										$serviceDesc .= ', ';
									}
									$serviceDesc .= $value;
								}
								?>
								<tr>
	                                <td><strong style="color:#7a7a7a">Cab Type Info:</strong></td>
	                                <td><?= $serviceDesc ?></td>
	                            </tr>
	                        </table></td>
	                    <td width="40%" align="center" valign="top"  style="padding-left: 10px;">
	                        <table width="100%" border="0">
	                            <tr>
	                                <td><strong style="color:#7a7a7a">STATUS: </strong><?php
										if ($model->bkg_reconfirm_flag == 0)
										{
											if ($model->bkg_status == 15)
											{
												//echo '<font style="color:red"><b>QUOTED, PAYMENT PENDING</b></font>';
												echo '<font style="color:red"><a href=' . $payurl . ' target="_blank" style="color:red; text-decoration: none;font-size:12px;"> <b>QUOTED, BOOKING NOT CONFIRMED</b><br/><b> Check driver, car info and other details.</a></b></font>';
											}
											else
											{
												echo '<font style="color:red"><b>RECONFIRM PENDING</b></font>';
											}
										}
										else if ($model->bkg_reconfirm_flag == 1)
										{
											echo '<font style="color:green"><b>BOOKING CONFIRMED</b></font>';
										}
										?></td>
	                            </tr>
	                            <tr>
	                                <td><strong style="color:#7a7a7a">ADVANCE  RECEIVED :</strong> <?= number_format($advance) ?></td>
	                            </tr>
	                            <tr>
	<!--                                <td><a href="http://www.aaocab.com/just1" target="_blank"><img src="http://www.aaocab.com/images/price-guarantee-img.jpg?v1.1" alt="Price Guarantee"  /></a></td>-->
									<?php
									$dboApplicable = Filter::dboApplicable($model);
									if ($dboApplicable)
									{
										?>
										<td><a href="http://www.aaocab.com/terms/doubleback" target="_blank"><img src="http://www.aaocab.com/images/double_hard_cash_refund2.png?v1.2" alt="Double Back Guarantee"  /></a></td>
										<?php
									}
									else
									{
										?>
										<td><a href="<?= $refCodeUrl; ?>" target="_blank"><img src="http://www.aaocab.com/images/hotlink-ok/refer_friend.png?v1.4" alt="Refer Friend"  /></a></td>
										<?php
									}
									?>


								</tr>
	                        </table>
	                    </td>
	                </tr>
	            </table></td>
	    </tr>

	    <tr>
	        <td align="center">
	            <div>
	                <table width="100%" border="0">
	                    <tr>
							<?php
							if ($due > 0)
							{
								?>
								<td align="left" valign="middle"><a href="<?= $payurl ?>" target="_blank"><img src="https://aaocab.com/images/hotlink-ok/paynow_btn.png" alt="Pay Now" /></a></td>
							<?php }
							?>
							<?php
							if ($model->bkg_status == 15)
							{
								?>
								<td align="left"><span style="font-size:15px; font-weight:bold; color: red;">YOUR PRICE LOCK EXPIRES at <?= date('jS M Y (D) h:i A', strtotime($new_time)); ?> 
									</span><br />
								</td>
								<?php
							}
							else
							{
								?>
								<td align="left"><span style="font-size:15px; font-weight:bold;">TO LOCK YOUR PRICE AND RECONFIRM YOUR BOOKING</span></td>
							<?php } ?>
	                    </tr>
	                </table>
	            </div>
	        </td>
	    </tr>
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
					<?php }
					?>
	            </table>
	        </td>
	    </tr>
		<tr>
	        <td>&nbsp;</td>
	    </tr>
		<?php
		foreach ($model->bookingRoutes as $key => $bookingRoute)
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
		$note				 = DestinationNote::model()->showBookingNotes($locationArr, $dateArr, $showNoteTo			 = 1);
		?>
		<?php
		if (!empty($note))
		{
			?>
		    <tr>
		        <td><strong style="font-size:16px;">SPECIAL INSTRUCTION & ADVISORIES THAT MAY AFFECT YOUR PLANNED TRAVEL</strong></td>
		    </tr>
		    <tr>
		        <td>
		            <table width="100%" border="1" style="border-collapse: collapse;" cellpadding="4" bordercolor="#CCCCCC">
		                <tr>
		                    <td align="left" bgcolor="#FF6600" style="padding-left:5px;"><span class="style1">Place</span></td>
		                    <td align="left" bgcolor="#FF6600" style="padding-left:5px;"><span class="style1">Note</span></td>
		                </tr>
						<?php
						for ($i = 0; $i < count($note); $i++)
						{
							?>
							<tr>
								<td align="left"  style="padding-left:5px;"><?php if ($note[$i]['dnt_area_type'] == 3)
				{ ?>
										<?= ($note[$i]['cty_name']) ?>
									<?php }
									else if ($note[$i]['dnt_area_type'] == 2)
									{ ?>
										<?= ($note[$i]['dnt_state_name']) ?>
									<?php }
									else if ($note[$i]['dnt_area_type'] == 0)
									{ ?>
										<?= "Applicable to all" ?>
			<?php }
			else if ($note[$i]['dnt_area_type'] == 4)
			{ ?>
								<?= Promos::$region[$note[$i]["dnt_area_id"]] ?>
				<?php
			}
			?></td>
								<td align="left" style="padding-left:5px;"><?= ($note[$i]['dnt_note']) ?></td>
							</tr>
		<?php }
		?>
		            </table>
		        </td>
		    </tr>
	<?php } ?>
	    <tr>
	        <td>&nbsp;</td>
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
	                    <td align="left"><strong style="color:#7a7a7a">Cab type & Class </strong></td>
	                    <td align="left">
	<?php $vhtModel = ($model->bkgSvcClassVhcCat->scc_ServiceClass->scc_id == 4) ? ' - ' . $model->bkgVehicleType->vht_make . ' ' . $model->bkgVehicleType->vht_model : ''; ?>
	<?= SvcClassVhcCat::getCatrgoryLabel($model->bkg_vehicle_type_id, true) . $vhtModel ?></td>
	                </tr>
	                <tr>
	                    <td align="left"><strong style="color:#7a7a7a">Pickup  Address</strong></td>
	                    <td align="left"><?= $model->bkg_pickup_address; ?></td>
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
	                    <td align="left"><strong style="color:#7a7a7a">Pickup  Date Time</strong></td>
	                    <td align="left"><?= date('jS M Y (D) h:i A', strtotime($model->bkg_pickup_date)); ?></td>
	                </tr>
					<?php
					if (($model->bkg_booking_type == 2 || $model->bkg_booking_type == 3) && ($model->bkg_return_date != NULL || $model->bkg_return_date != ''))
					{
						?>
						<tr>
							<td align="left"><strong style="color:#7a7a7a">Return  Date Time</strong></td>
							<td align="left"><?php ($model->bkg_return_date!='')? date('jS M Y (D) h:i A', strtotime($model->bkg_return_date)):""; ?></td>
						</tr>
	<?php }
	?> 
	                <tr>
	                    <td align="left"><strong style="color:#7a7a7a">Kms Included  in your quotation</strong></td>
	                    <td align="left"><?= $model->bkg_trip_distance; ?> km</td>
	                </tr>
	                <tr>
	                    <td align="left"><strong style="color:#7a7a7a">Charges  beyond <?= $model->bkg_trip_distance ?> Km</strong></td>
	                    <td align="left"><?= $model->bkgInvoice->bkg_rate_per_km_extra; ?> / km</td>
	                </tr>

					<?php
					if ($model->bkgInvoice->bkg_night_pickup_included == 1 && $model->bkg_booking_type == 1)
					{
						$isAllowencePickupText = "Night pickup allowance included (pickup time is between 10pm and 6am).";
					}if ($model->bkgInvoice->bkg_night_drop_included == 1 && $model->bkg_booking_type == 1)
					{
						if ($isAllowencePickupText != "")
						{
							$br = "<br />";
						}
						else
						{
							$br = "";
						}
						$isAllowenceDropOffText = "Night dropoff allowance included (drop off time is between 10am and 6am).";
					}
					?>



					<tr>
	                    <td align="left"><strong style="color:#7a7a7a">Included with quotation</strong></td>
	                    <td align="left">Upto <?= $model->bkg_trip_distance; ?> kms for the exact itinerary listed below. NO route deviations allowed unless listed in itinerary <?php
							if ($model->bkgInvoice->bkg_is_toll_tax_included == 1 && $model->bkgInvoice->bkg_is_state_tax_included == 1)
							{
								echo '; Toll & state taxes';
							}
							if ($model->bkgInvoice->bkg_is_airport_fee_included == 1)
							{
								echo ', Airport Entry Charges';
							}
//						if($model->bkg_booking_type == 2)
//						{
//							echo "<br />"."Drivers(Day)allowance is included";
//						}
							if ($model->bkg_booking_type == 1)
							{
								echo "<br />" . $isAllowencePickupText . $br . $isAllowenceDropOffText;
							}
							if ($prr_day_driver_allowance > 0 && ( $model->bkg_booking_type == 2 || $model->bkg_booking_type == 3))
							{
								echo "<br />" . "Drivers daytime allowance of Rs. " . $prr_day_driver_allowance . " per day is included in quotation";
							}
							?>

	                    </td>
	                </tr>
	                <tr>
	                    <td align="left"><strong style="color:#7a7a7a">Additional charges paid</strong></td>
	                    <td align="left">  
							<?php
							$splRequest = 0;
							if ($model->bkgUserInfo->bkg_country_code != '91' && $model->bkgPref->bkg_send_sms == 1)
							{
								echo "International SMS fee (Rs. 99/-)";
								if ($model->bkgAddInfo->bkg_spl_req_carrier != 0 || $model->bkgAddInfo->bkg_spl_req_lunch_break_time != 0)
								{
									echo ';<br>';
								}
								$splRequest = 1;
							}
							if ($model->bkgAddInfo->bkg_spl_req_lunch_break_time != 0)
							{
								echo $model->bkgAddInfo->bkg_spl_req_lunch_break_time . ' minutes break during journey (Rs. ' . $model->bkgAddInfo->bkg_spl_req_lunch_break_time * 5 . '/-)';
								if ($model->bkgAddInfo->bkg_spl_req_carrier != 0)
								{
									echo ';<br>';
								}
								$splRequest = 1;
							}
							if ($model->bkgAddInfo->bkg_spl_req_carrier != 0)
							{
								echo 'Carrier requested in car (Rs. 150/-)';
								$splRequest = 1;
							}
							if ($splRequest == 0)
							{
								echo 'No special requests received';
							}
							?>
	                    </td>
	                </tr>

					<tr>
	                    <td align="left"><strong style="color:#7a7a7a">Customer to pay separately</strong></td>
	                    <td align="left">Any charges or parking charges; Any parking charges <?php
							if ($model->bkgInvoice->bkg_is_toll_tax_included == 0 && $model->bkgInvoice->bkg_is_state_tax_included == 0)
							{
								echo '; Toll & state taxes';
							}
							if ($model->bkgInvoice->bkg_is_airport_fee_included == 0)
							{
								echo '; Airport Charges';
							}
							?>
							<?php
							$night_driver_allowance_txt = ($prr_Night_driver_allowance > 0) ? "of Rs. " . $prr_Night_driver_allowance : '';
							if ($model->bkgInvoice->bkg_night_drop_included == 0 && $model->bkgInvoice->bkg_night_pickup_included == 1)
							{
								if ($model->bkg_booking_type == 1)
								{
									echo "<br />" . " Night drop allowance " . $night_driver_allowance_txt . " to be paid if drop off happens between (10pm and 6am)";
								}
								else
								{
									echo "<br />" . "Night drop allowance " . $night_driver_allowance_txt . " to be paid to driver for each night when driving between the hours of 10pm and 6am. ";
								}
							}
							if ($model->bkgInvoice->bkg_night_pickup_included == 0 && $model->bkgInvoice->bkg_night_drop_included == 1)
							{
								if ($model->bkg_booking_type == 1)
								{
									echo "<br />" . " Night pickup allowance " . $night_driver_allowance_txt . " to be paid if drop off happens between (10pm and 6am)";
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
									echo "<br />" . " Night driving allowance " . $night_driver_allowance_txt . " to be paid if pickup or drop off happens between (10pm and 6am)";
								}
								else
								{
									echo "<br />" . "Night driving allowance " . $night_driver_allowance_txt . " to be paid to driver for each night when driving between the hours of 10pm and 6am. ";
								}
							}
							if ($model->bkgInvoice->bkg_night_pickup_included == 1 && $model->bkgInvoice->bkg_night_drop_included == 1)
							{
								echo" ";
							}
//						$night_driver_allowance_txt = ($prr_Night_driver_allowance>0) ? "of Rs. ".$prr_Night_driver_allowance : '';
//						if($model->bkg_booking_type == 1)
//						{
//							echo  "<br />"." Night driving allowance ".$night_driver_allowance_txt." to be paid if pickup or drop off happens between (10pm and 6am)";
//						}
//						if($model->bkg_booking_type == 2 || $model->bkg_booking_type == 3)
//						{
//							echo "<br />"."Night driving allowance ".$night_driver_allowance_txt." to be paid to driver for each night when driving between the hours of 10pm and 6am. ";
//						}
							?>


						</td>
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

	if ($model->bkgInvoice->bkg_addon_charges > 0)
	{
		?>
						<tr>
							<td align="left"><strong style="color:#7a7a7a">Cancellation Addon Charge</strong></td>
							<td align="left">Rs.<strong><em><?= number_format($model->bkgInvoice->bkg_addon_charges); ?></em></strong></td>
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
	                <tr>
	                    <td align="left"><strong style="color:#7a7a7a">Airport  Entry Fee <?= ($model->bkgInvoice->bkg_is_airport_fee_included == 1) ? "(Included)" : "(Excluded)" ?></strong></td>
	                    <td align="left">Rs.<strong><em><?= ($model->bkgInvoice->bkg_airport_entry_fee != '') ? $model->bkgInvoice->bkg_airport_entry_fee : 0; ?></em></strong></td>
	                </tr> 
					<?
					//$staxrate	 = $model->bkgInvoice->getServiceTaxRate();
					$serviceTaxRate				 = BookingInvoice::getGstTaxRate($model->bkg_agent_id, $model->bkg_booking_type);
					$staxrate    = ($serviceTaxRate == 0)? 1 : $serviceTaxRate;
					$taxLabel	 = ($serviceTaxRate == 5) ? 'GST' : 'Service Tax ';
					?>
					<?
					if ($model->bkgInvoice->bkg_cgst > 0)
					{
					?>
					<tr>
						<td align="left"><strong style="color:#7a7a7a">CGST (@<?= Yii::app()->params['cgst'] ?>%)</strong></td>
						<td align="left">Rs.<strong><em><?= ((Yii::app()->params['cgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></em></strong></td>
					</tr>
					<? } ?>
					<?
					if ($model->bkgInvoice->bkg_sgst > 0)
					{
					?>
					<tr>
						<td align="left"><strong style="color:#7a7a7a">SGST (@<?= Yii::app()->params['sgst'] ?>%)</strong></td>
						<td align="left">Rs.<strong><em><?= ((Yii::app()->params['sgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></em></strong></td>
					</tr>
					<? } ?>
					<?
					if ($model->bkgInvoice->bkg_igst > 0)
					{
					?>
					<tr>
						<td align="left"><strong style="color:#7a7a7a">IGST (@<?= Yii::app()->params['igst'] ?>%)</strong></td>
						<td align="left">Rs.<strong><em><?= ((Yii::app()->params['igst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></em></strong></td>
					</tr>
					<? } ?>
					<?
					if ($serviceTaxRate != 5)
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
					<?
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
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td style="margin-top: 10px;"><a href="http://www.aaocab.com/day-rental" target="_blank"><img src="http://www.aaocab.com/images/hotlink-ok/local_rental.png?v1.3" alt="Email"  /></a></td>
    </tr>
	<?php
	if ($model->bkg_status != 15)
	{
		?>
		<tr>
			<td style="font-size: 13px;"><br><strong><b>Your auto-generated invoice can be viewed :</b> <?= $file; ?></strong><br><br></td>
		</tr>
<?php } ?>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><strong style="font-size:16px;"><u>Important terms and conditions for your trip. </u></strong></td>
    </tr>
    <tr>
        <td>
            <div style="margin-top: 10px; line-height: 20px;">
				<?
				$info = "";
				if ($model->bkg_booking_type == 1)
				{
				$info = $oneWayInfo;
				}
				else if ($model->bkg_booking_type == 4)
				{
				$info = $airportInfo;
				}
				else if ($model->bkg_booking_type == 9 || $model->bkg_booking_type == 10 || $model->bkg_booking_type == 11 || $model->bkg_booking_type == 2 || $model->bkg_booking_type == 3)
				{
				$info = $returnInfoPerKm;
				}
				else
				{
				// $bookingType = "Return Trip";
				$dropArea = "";
				if ($model->bkgInvoice->bkg_rate_per_km_extra != null && $model->bkgInvoice->bkg_rate_per_km_extra != "")
				{
				$info = $returnInfoPerKm;
				}
				else
				{
				$info = "";
				}
				$scvVctId = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
				if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
				{
				$info = $flexxiInfo;
				}
				}
				echo $info;
				?>
            </div>
        </td>
    </tr>
</table>


