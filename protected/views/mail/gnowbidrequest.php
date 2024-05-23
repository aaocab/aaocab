<?php
/* @var $model Booking */
$routeCityList	 = $model->getTripCitiesListbyId();
$model1			 = clone $model;
$model->bkgInvoice->calculateConvenienceFee(0);
$model->bkgInvoice->calculateTotal();
//$carType= VehicleTypes::model()->getVehicleTypeById($model->bkg_vehicle_type_id);
$carType		 = $model->bkg_vehicle_type_id;
$dataList		 = BookingVendorRequest::getGNowAcceptedList($model->bkg_bcb_id);

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

<?php } ?>
<?php
$isDboMaster = Yii::app()->params['dboMaster'];
$baseURL = Yii::app()->params['fullBaseURL'];
?>

<table align="center"
	width="640"
	style="color: #000; font-size: 14px; font-family: 'Arial'; line-height: 18px;
	min-width: 360px;
	width: 640px;
	max-width: 640px;
	margin: 0 auto 20px auto;">
	<tr>
		<td align="center"><strong style="font-size:16px;"><span style="background-color: rgb(76, 205, 116); color: #fff; font-weight: bold; border-radius: 50px; font-size: 14px; padding: 5px 10px; margin: 0 auto;">&nbsp; Bid Received &nbsp;</span></strong></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table align="center" width="100%" style="border:#DFE4EE 1px solid; padding: 4px 10px; font-size: 14px; font-family: 'Arial'; line-height: 18px;">
				<tr style="background-color: rgb(229, 238, 255); padding: 5px;">
					<td align="center"><b>Operator</b></td>
					<td align="center"><b>Cab Arrives In</b></td>
					<td align="center"><b>Amount</b></td>
					<td align="center"><b>Cab</b></td>
				</tr>
				<?php
				foreach ($dataList['data'] as $key => $value)
				{
					$minutes = round((strtotime($value["reachingAtTime"]) - time()) / 60);
					?>
					<tr>
						<td align="center" style="border:#ddf0ff 1px solid;"><?= $value['vnd_code']; ?></td>
						<td align="center" style="border:#ddf0ff 1px solid;"><?= $minutes ?> mins</td>
						<td align="center" style="border:#ddf0ff 1px solid;">₹<?= $value['totalCalculated']; ?></td>
						<td align="center" style="border:#ddf0ff 1px solid;"><?php echo $value['vht_make'] ?> - <?php echo $value['vht_model']; ?></td>
					</tr>
				<?php }
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td align="left" valign="middle"><div style="margin-top: 10px;"><a href="<?= $payurl ?>" target="_blank"><img src="<?= $baseURL ?>/images/hotlink-ok/track_your_request_btn.png" alt="Track Your Trip" /></a></div></td>
	</tr>

	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="5" style="border: #DFE4EE 1px solid; padding:20px 10px 20px 10px; font-size: 14px; line-height: 18px;">
				<tr>
					<td valign="top">
						<div>
							<span style="color:#858585;padding-right:5px"><?php echo ($model->bkg_status == 15) ? "Quote Id" : "Booking Id"; ?></span>
							<p style="margin-top: 0;"><a href="<?= $payurl ?>" target="_blank" style="color: #0077ff;"><b><?= Filter::formatBookingId($model->bkg_booking_id); ?></b></a></p>
						</div>

						<div>
							<span style="color:#858585;padding-right:5px">Name</span>
							<p style="margin-top: 0;"><?= $model->bkgUserInfo->getUsername() ?></p>
						</div>

						<div>
							<span style="color:#858585;padding-right:5px">Email</span>
							<p style="margin-top: 0;"><?= $model->bkgUserInfo->bkg_user_email; ?></p>
						</div>

						<div>
							<span style="color:#858585;padding-right:5px">Phone</span>
							<p style="margin-top: 0;"><?= ($contactNo != '') ? '+' . $countryCode . ' ' . $contactNo : '' ?></p>
						</div>
					</td>
					<td valign="top" width="50%">
						<div>
							<?php
//							$sccDesc		 = $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_desc;
//							$arrServiceDesc	 = json_decode($sccDesc);
//							$serviceDesc	 = '';
//							foreach ($arrServiceDesc as $key => $value)
//							{
//								if ($key != 0)
//								{
//									$serviceDesc .= ', ';
//								}
//								$serviceDesc .= $value;
//							}

							$serviceDesc = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
							$sccDesc = $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label;
							?>
							<span style="color:#858585;padding-right:5px">Cab Type</span>
							<p style="margin-top: 0;"><?= $serviceDesc . ' (' . $sccDesc . ')' ?></p>
						</div>

						<div>
							<span style="color:#858585;padding-right:5px">Pickup Date/Time</span>
							<p style="margin-top: 0;"><?= date('jS M Y (D) h:i A', strtotime($model->bkg_pickup_date)) ?></p>
						</div>

						<div>
							<span style="color:#858585;padding-right:5px">Pickup Address</span>
							<p style="margin-top: 0;"><?= $model->bkg_pickup_address; ?></p>
						</div>
						<?php if($luggageCapacity){?>
							<div>
								<span style="color:#858585;padding-right:5px">Special Instructions</span>
								<p style="margin-top: 0;">
									Pax: <?= $luggageCapacity->noOfPersons ?> persons (max) | Luggage: 
									<?= (($luggageCapacity->largeBag != 0) ? $luggageCapacity->largeBag . ' Big Bags /' : '') ?>
									<?= (($luggageCapacity->smallBag != 0) ? $luggageCapacity->smallBag . ' Small luggage (max)' : '') ?>
									<!--<? //= $luggageCapacity->largeBag   ?> Big Bags / <? //= $luggageCapacity->smallBag   ?> Small luggage (max)-->
								</p>
							</div>
						<?php } ?>
					</td>
				</tr>
				<tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table width="100%" style="border: #DFE4EE 1px solid; padding: 10px; margin: 0; margin-bottom: 20px; font-size: 14px; font-family: 'Arial'; line-height: 18px;">
				<tr>
					<td>
							<p style="font-size: 14px;"><b>Itinerary</b></p>
							<div style="width: 100%;">
								<?php
								$last	 = 0;
								$tdays	 = '';
								$cntBrt	 = count($model->bookingRoutes);
								foreach ($model->bookingRoutes as $k => $brt)
								{

									if ($k == 0)
									{
										$tdays = 1;
									}
									else
									{
										$date1		 = new DateTime(date('Y-m-d', strtotime($model->bookingRoutes[0]->brt_pickup_datetime)));
										$date2		 = new DateTime(date('Y-m-d', strtotime($brt->brt_pickup_datetime)));
										$difference	 = $date1->diff($date2);
										$tdays		 = ($difference->d + 1);
									}
									if (in_array($model->bkg_booking_type, [9, 10, 11]))
									{
										$brt->brt_trip_distance	 = $model->bkg_trip_distance;
										$brt->brt_trip_duration	 = $model->bkg_trip_duration;
									}

									$locAddress	 = ($brt->brt_from_location == '') ? $brt->brtFromCity->cty_name : $brt->brt_from_location;
									$locAddress	 .= ($brt->brt_from_latitude > 0) ? '' : ', ' . $brt->brtFromCity->cty_name;
									?>
									<table style="width: 100%;">
										<tr>
											<td style="width: 35%;">
												<div style="font-size: 11px;"><?= DateTimeFormat::DateTimeToDatePicker($brt->brt_pickup_datetime); ?> &nbsp;<?= DateTimeFormat::DateTimeToTimePicker($brt->brt_pickup_datetime); ?><br>
													<img src="<?= $baseURL ?>/images/icon-km.png?v=0.1" alt="img" width="12"> <span style="margin-right: 10px;"><b><?= ($brt->brt_trip_distance < $model->bkg_trip_distance && in_array($model->bkg_booking_type, [1, 4, 12])) ? $model->bkg_trip_distance : $brt->brt_trip_distance ?> KM</b></span> &nbsp;&nbsp;<img src="<?= $baseURL ?>/images/icon-time.png" alt="img" width="12"> <b><?= BookingRoute::model()->formatTripduration($brt->brt_trip_duration, 1); ?></b></div>
											</td>

											<td style="width: 64%; font-size: 14px;">
												<div style="background: url(<?= $baseURL ?>/images/location-icon.png?v=0.4) top left no-repeat; background-size: 16px; padding-left: 25px; padding-bottom: 20px;"><?= $locAddress; ?></div>
											</td>
										</tr>
									</table>
									<table width="100%">
										<tr>
											<?php
											if ($k == ($cntBrt - 1))
											{

												$locAddress	 = ($brt->brt_to_location == '') ? $brt->brtToCity->cty_name : $brt->brt_to_location;
												$locAddress	 .= ($brt->brt_to_latitude > 0) ? '' : ', ' . $brt->brtToCity->cty_name;
												?>
												<td style="width: 35%;">
													<div style="font-size: 11px;"><b><? //= DateTimeFormat::DateTimeToDatePicker($brt->brt_pickup_datetime);   ?></b> &nbsp;<? //= DateTimeFormat::DateTimeToTimePicker($brt->brt_pickup_datetime);   ?><br>
														<span style="margin-right: 10px;"><b></b></span> <b></b>
													</div>
												</td>

												<td style="width: 64%; font-size: 14px;">
													<div style="background: url(<?= $baseURL ?>/images/location_orange.png?v=0.3) top left no-repeat; background-size: 16px; padding-left: 25px; padding-bottom: 15px;"><?= $locAddress; ?></div>
												</td>
												<?php
											}
										}
										?>
									</tr>
								</table>
							</div>
							<div style="width: 100%; float: left; overflow: hidden;">
								<span style="padding-right: 20px;">Quoted Distance: <b><?= $model->bkg_trip_distance ?> Km</b></span>
								<span>Estimated Duration: <b><?= round($model->bkg_trip_duration / 60) . ' Hours'; ?></b></span>
							</div>
						</td>	
					</tr>	
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<?php
//		foreach ($model->bookingRoutes as $key => $bookingRoute)
//		{
//			$pickupCity[]	 = $bookingRoute->brt_from_city_id;
//			$dropCity[]		 = $bookingRoute->brt_to_city_id;
//			$pickup_date[]	 = $bookingRoute->brt_pickup_datetime;
//			$temp_last_date	 = strtotime($bookingRoute->brt_pickup_datetime) + $bookingRoute->brt_trip_duration;
//			$drop_date_time	 = date('Y-m-d H:i:s', $temp_last_date);
//		}
//		$pickup_date_time	 = $pickup_date[0];
//		$locationArr		 = array_unique(array_merge($pickupCity, $dropCity));
//		$dateArr			 = array($pickup_date_time, $drop_date_time);
//		$note				 = DestinationNote::model()->showBookingNotes($locationArr, $dateArr, $showNoteTo			 = 1);
	?>
</table>


