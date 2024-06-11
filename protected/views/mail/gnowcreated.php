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

<?php } ?>
<?php
$isDboMaster = Yii::app()->params['dboMaster'];
$baseURL = Yii::app()->params['fullBaseURL'];

$userName = trim($model->bkgUserInfo->getUsername());
$travellerName = Yii::app()->user->loadUser()->usr_name.' '. Yii::app()->user->loadUser()->usr_lname;
$contactName = ($userName != '')? $userName : $travellerName;

?>

<table style="width: 100%; margin: 0 auto; color: #000; font-size: 14px; font-family: 'Verdana'; line-height: 18px;">
	<tr>
		<td align="center">
			<table width="640"
				   style="color: #000; font-size: 14px; font-family: 'Arial'; line-height: 18px;
				   min-width: 360px;
				   width: 640px;
				   max-width: 640px;
				   margin: 0 auto 20px auto;">
				<tr>
					<td>
						<div style="width: 100%; position: relative; float: left; text-align: center; margin-bottom: 10px;">
							<div>
								<span style="background-color: rgb(76, 205, 116); color: #fff; font-weight: bold; border-radius: 50px; font-size: 14px; padding: 5px 10px; margin: 0 auto;">TRIP REQUEST</span>
							</div>
						</div>

						<table width="100%" style="border: #DFE4EE 1px solid; margin-bottom: 30px; padding: 0; margin: 0; margin-bottom: 20px; color: #000; font-size: 14px; font-family: 'Arial'; line-height: 18px;">
							<tr>
								<td>
									<table width="100%" style="background-color: rgb(229, 238, 255); padding: 4px 10px; font-size: 14px; font-family: 'Arial'; line-height: 18px;">
										<tr>
											<td width="50%"><span style="color: #858585; padding-right: 5px;"><?php echo ($model->bkg_status == 15) ? "Quote Id" : "Booking Id"; ?>:</span><b><a href="<?= $payurl ?>" target="_blank"><?= Filter::formatBookingId($model->bkg_booking_id); ?></a></b></td>
											<td align="right"><span style="color: #858585; padding-right: 5px;">Journey Type:</span><b><?= Booking::model()->getBookingType($model->bkg_booking_type); ?></b></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<table width="100%" style="padding: 20px 10px; font-size: 14px; font-family: 'Arial'; line-height: 18px;">
										<tr>
											<td>
												<div>
													<span style="color: #858585; padding-right: 5px;">Name</span>
													<p style="margin-bottom: 10px; margin-top: 0;"><?= $contactName ?></p>
												</div>
												<div>
													<span style="color: #858585; padding-right: 5px;">Email</span>
													<p style="margin-bottom: 10px; margin-top: 0;"><?= $model->bkgUserInfo->bkg_user_email; ?></p>
												</div>
												<div>
													<span style="color: #858585; padding-right: 5px;">Phone</span>
													<p style="margin-bottom: 10px; margin-top: 0;"><?= ($contactNo != '') ? '+' . $countryCode . ' ' . $contactNo : '' ?></p>
												</div>
												<?php if($splRequest != ''){?>
												<div>
													<span style="color: #858585; padding-right: 5px;">Special Instructions</span>
													<p style="margin-bottom: 10px; margin-top: 0;"><?
														$showInsArr	 = [];
														$showPxArr	 = [];
														if ($luggageCapacity->largeBag + $luggageCapacity->smallBag + $luggageCapacity->noOfPersons > 0)
														{
															$showVal		 = '';
															($luggageCapacity->noOfPersons > 0 ) ? $showInsArr[]	 = 'Pax: ' . $luggageCapacity->noOfPersons . ' persons (max) ' : '';
															if ($luggageCapacity->largeBag > 0 || $luggageCapacity->smallBag > 0)
															{
																$showVal	 = ' Luggage: ';
																(($luggageCapacity->largeBag != 0) ? $showPxArr[] = $luggageCapacity->largeBag . ' Big Bags ' : '');
																(($luggageCapacity->smallBag != 0) ? $showPxArr[] = $luggageCapacity->smallBag . ' Small luggage ' : '');
																$showVal	 .= implode(' | ', $showPxArr);
															}
															$showInsArr[] = $showVal;

															echo implode('<br>', $showInsArr);
														}
														else
														{
															echo 'No instructions';
														}
														?>
													</p>
												</div>
												<?php } ?>
											</td>
											<td width="50%">
												<div>
													<?php
														$serviceDesc = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
														$sccDesc = $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label;
													?>
													<div>
														<span style="color: #858585; padding-right: 5px;">Cab Type</span>
														<p style="margin-bottom: 10px; margin-top: 0;"><?= $serviceDesc . ' (' . $sccDesc . ')' ?></p>
													</div>
													<div>
														<span style="color: #858585; padding-right: 5px;">Pickup Date/Time</span>
														<p style="margin-bottom: 10px; margin-top: 0;"><?= date('jS M Y (D) h:i A', strtotime($model->bkg_pickup_date)) ?></p>
													</div>
													<div>
														<span style="color: #858585; padding-right: 5px;">Pickup Address</span>
														<p style="margin-bottom: 10px; margin-top: 0;"><?= $model->bkg_pickup_address; ?></p>
													</div>
												</div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td></td>
							</tr>
						</table>
						<table>
							<tr>
								<td style="font-size: 14px;">
									<p><?php echo "We have received your request. Your booking is not confirmed yet. We are looking for the
												  cab availability and will inform you as soon as we get the confirmation. Use this link to track your booking status";
										?>
									</p>
									<a href="<?= $payurl ?>" target="_blank"><img src="<?= $baseURL ?>/images/hotlink-ok/track_your_request_btn.png" alt="Track Your Request" /></a>
									<p style="font-size: 14px; margin-top:5px; margin-bottom: 7px;"><?php
										echo "You will receive multiple offers from the operator's. Choose the offer you like to confirm your booking.";
									?></p>
								</td>
							</tr>
						</table>
						<table width="100%" style="border: #DFE4EE 1px solid; padding: 10px; margin: 0; margin-bottom: 20px; font-size: 14px; font-family: 'Arial'; line-height: 18px;">
							<tr>
								<td>
									<div>
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
																<div style="font-size: 11px;"><b><? //= DateTimeFormat::DateTimeToDatePicker($brt->brt_pickup_datetime);  ?></b> &nbsp;<? //= DateTimeFormat::DateTimeToTimePicker($brt->brt_pickup_datetime);  ?><br>
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
						</table>
						<?php
						$userId = $model->bkgUserInfo->bkg_user_id;
						$qrCode	 = QrCode::getCode($userId);
						?>
						<table style="width: 100%; margin-bottom: 20px; font-family: 'Arial'; line-height: 18px;">
							<tr>
								<td style="width: 50%;"><a href="http://www.aaocab.com/users/refer" target="_black"><img src="<?= $baseURL ?>/images/refer.png?v=0.3" alt="Refer a friend, get cash back - it's a win-win!" title="Refer a friend, get cash back - it's a win-win!" style="width: 98%;"></a></td>
								<td style="width: 46.5%; padding: 10px; text-align: center; border: #DFE4EE 1px solid; font-size: 11px;">
									<b>This is your personalized Gozo sticker. Get ₹100 credit when your friends travel with Gozo using your QR code</b>
									<div style="width: 100%; float: left;"><a href="http://www.aaocab.com/users/getQRPathById?userId=<?= $userId ?>" target="_black"><img src="http://www.aaocab.com/users/getQRPathById?userId=<?= $userId ?>" alt="QR" title="QR" style="width: 75%;"></a><br><a href="https://gozo.cab/c/<?php echo $qrCode; ?>">https://gozo.cab/c/<?php echo $qrCode; ?></a></div>
								</td>
							</tr>
						</table>


						<table style="width: 100%; position: relative; float: left; position: relative; border: #DFE4EE 1px solid; margin-bottom: 20px; font-family: 'Arial'; line-height: 18px;">
							<tr>
								<td><a href="http://www.aaocab.com/book-cab" target="_black"><img src="<?= $baseURL ?>/images/hotlink-ok/local_rental.png" alt="Gozocabs | Loved your trip | Give us a video testimonial | 20% get cash back" title="Gozocabs | Loved your trip | Give us a video testimonial | 20% get cash back" style="width: 100%;"></a></td>
							</tr>		
						</table>
						
					</td>
				</tr>
			</table>
		<td>
	<tr>
</table>



