<?php
Logger::trace(json_encode($params));
if (!$model)
{
	if ((trim($params['arr']->bkgId)) != '')
	{
		
		$model		 = Booking::model()->findByPk($params['arr']->bkgId);
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

if ($params['resheduledMsg'] != '')
{
	$resheduledMsg = $params['resheduledMsg'];
}

Logger::trace("Booking ID: " . json_encode($model));

Logger::warning("NULL model", true);
/* @var $model Booking */
$routeCityList	 = $model->getTripCitiesListbyId();
$model1			 = clone $model;
$model->bkgInvoice->calculateConvenienceFee(0);
$model->bkgInvoice->calculateTotal();
//$carType= VehicleTypes::model()->getVehicleTypeById($model->bkg_vehicle_type_id);
$carType		 = $model->bkg_vehicle_type_id;

$sccLabel	 = SvcClassVhcCat::getVctSvcList("string", 0, 0, $carType);
$payurl		 = ($model->bkg_agent_id == NULL) ? ($payurl) : ("#");
if ($model->bkg_booking_type != 8)
{
	$priceRule					 = AreaPriceRule::model()->getValues($model->bkg_from_city_id, $carType, $model->bkg_booking_type);
	$prr_day_driver_allowance	 = $priceRule['prr_day_driver_allowance'];
	$prr_Night_driver_allowance	 = $priceRule['prr_night_driver_allowance'];
}

$fileLink	 = Yii::app()->createAbsoluteUrl('booking/invoice?bkg=' . $model->bkg_id . '&hsh=' . Yii::app()->shortHash->hash($model->bkg_id));
$file		 = "<a href='$fileLink' target='_blank'>Download your invoice here</a>";

$policyType			 = CancellationPolicyDetails::model()->findByPk($model->bkgPref->bkg_cancel_rule_id)->cnp_label;
$sccDesc			 = $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_desc;
$arrServiceDesc		 = json_decode($sccDesc);
$arrServiceDesc[1]	 = $policyType;
$serviceDesc		 = '';
foreach ($arrServiceDesc as $key => $value)
{
	if ($key != 0)
	{
		$serviceDesc .= ', ';
	}
	$serviceDesc .= $value;
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
$cancelTimes = CancellationPolicyRule::getCancelationTimeRange($model->bkg_id, 1);

$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode = $response->getData()->phone['ext'];
}
$baseURL = Yii::app()->params['fullBaseURL'];
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
						<!--		<div style="width: 100%; position: relative; float: left;">
									<div style="float: left;"><a href="http://www.aaocab.com/" target="_blank"><img src="/images/logo2_old.png?v=0.3" alt="aaocab" title="aaocab" width="130"></a></div>
									<div style="float: right;">
										<div style="width: 100%; font-size: 13px; margin-bottom: 5px;"><img src="/images/ind-flag.png" alt="img" width="20"> (+91) 90518 77000</div>
										<div style="width: 100%; font-size: 13px;"><img src="/images/world.png" alt="img" width="20"> (+1) 650-741-GOZO</div>
									</div>
								</div>-->
						<div style="width: 100%; position: relative; float: left; text-align: center;">
							<div>
								<span style="background-color: rgb(76, 205, 116); color: #fff; font-weight: bold; border-radius: 50px; font-size: 14px; padding: 5px 10px; margin: 0 auto;">RESERVATION CONFIRMED</span>
							</div>
										<p style="margin-top: 10px;color: red"><?php 
			if ($resheduledMsg != "")
			{
				echo $resheduledMsg;
			}
			?></p>
							<?php
							if ($model->bkgTrail->btr_is_dbo_applicable == 1)
							{
								?>
	                            <p class="text-center"><a href="<?php echo $baseURL ?>/terms/doubleback" target="_black"><img src="<?php echo $baseURL ?>/images/dbo.png"></a></p>
							<?php }
							?>
							<p style="margin-top: 15px;">
								Thank you for choosing aaocab. We have confirmed your reservation request. You will receive the cab details at least 2 hours before your scheduled pickup time.
							</p>		
							<?php
							if ($otp != '')
							{
								?>
								<p>Please use OTP: <b><?= $otp ?></b> at the time of pickup. Please don't share OTP before boarding the cab.</p>
<?php } ?>
						</div>

						<table width="100%" style="border: #DFE4EE 1px solid; margin-bottom: 30px; padding: 0; margin: 0; margin-bottom: 20px; color: #000; font-size: 14px; font-family: 'Arial'; line-height: 18px;">
							<tr>
								<td>
									<table width="100%" style="background-color: rgb(229, 238, 255); padding: 4px 10px; font-size: 14px; font-family: 'Arial'; line-height: 18px;">
										<tr>
											<td width="50%"><span style="color: #858585; padding-right: 5px;">Booking Id:</span><b><?= Filter::formatBookingId($model->bkg_booking_id); ?></b></td>
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
													<p style="margin-bottom: 10px; margin-top: 0;"><?= $model->bkgUserInfo->getUsername() ?></p>
												</div>
												<div>
													<span style="color: #858585; padding-right: 5px;">Email</span>
													<p style="margin-bottom: 10px; margin-top: 0;"><?= $model->bkgUserInfo->bkg_user_email; ?></p>
												</div>
												<div>
													<span style="color: #858585; padding-right: 5px;">Phone</span>
													<p style="margin-bottom: 10px; margin-top: 0;"><?= ($contactNo != '') ? '+' . $countryCode . ' ' . $contactNo : '' ?></p>
												</div>
												<?php
												if ($splRequest != '')
												{
													?>
													<div>
														<span style="color: #858585; padding-right: 5px;">Special Instructions</span>
														<p style="margin-bottom: 10px; margin-top: 0;"><?= $splRequest; ?></p>
													</div>
<?php } ?>
											</td>
											<td width="50%">
												<div>

													<div>
														<span style="color: #858585; padding-right: 5px;">Cab Type</span>
														<p style="margin-bottom: 10px; margin-top: 0;"><?php $vhtModel	 = ($model->bkgSvcClassVhcCat->scc_ServiceClass->scc_id == 4) ? ' - ' . $model->bkgVehicleType->vht_make . ' ' . $model->bkgVehicleType->vht_model : ''; ?>
<?= $model->bkgSvcClassVhcCat->scv_label .' '. $vhtModel ?></p>
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

						<table width="100%" style="border: #DFE4EE 1px solid; padding: 10px; margin: 0; margin-bottom: 20px; font-size: 14px; font-family: 'Arial'; line-height: 18px;">
							<tr>
								<td>
									<div>
										<p style="font-size: 14px;"><b>Itinerary</b></p>
										<div style="width: 100%;">
											<?php
											$last		 = 0;
											$tdays		 = '';
											$cntBrt		 = count($model->bookingRoutes);
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
																<div style="font-size: 11px;"><b></b> &nbsp;<br>
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
						$userId	 = $model->bkgUserInfo->bkg_user_id;
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
								<td><a href="http://www.aaocab.com/book-cab" target="_black"><img src="<?= $baseURL ?>/images/hotlink-ok/local_rental.png" alt="aaocab | Loved your trip | Give us a video testimonial | 20% get cash back" title="aaocab | Loved your trip | Give us a video testimonial | 20% get cash back" style="width: 100%;"></a></td>
							</tr>		
						</table>
						<table cellpadding="6" style="width: 100%; position: relative; border: #DFE4EE 1px solid; margin-bottom: 20px; font-size: 14px;">
							<tr>
								<td><span style="color: #575757;">Base Fare</span></td><td align="right">&#x20b9;<?= number_format($model->bkgInvoice->bkg_base_amount); ?></td>
							</tr>
							<?php
							if ($model->bkgInvoice->bkg_addon_charges > 0)
							{
								?>
								<tr>
									<td><span style="color: #575757;">Addon Charges</span> </td>
									<td align="right">&#x20b9;<?= number_format($model->bkgInvoice->bkg_addon_charges); ?> </td>
								</tr>	
								<tr>
									<td colspan="2" style="color: #707070;font-size: 12px; padding:0 0 5px 5px"><?php
										$addonDetails	 = json_decode($model->bkgInvoice->bkg_addon_details, true);
										$addonCharge	 = (preg_match('/-/', $model->bkgInvoice->bkg_addon_charges)) ? str_replace('-', '', $model->bkgInvoice->bkg_addon_charges) : $model->bkgInvoice->bkg_addon_charges;
										$minusSymbol	 = (preg_match('/-/', $model->bkgInvoice->bkg_addon_charges)) ? '(-)' : '';

										$addnkey = array_search(1, array_column($addonDetails, 'adn_type'));
										if ($addonDetails[$addnkey]['adn_type'] == 1)
										{
											$cpDetails		 = CancellationPolicyDetails::model()->findByPk($model->bkgPref->bkg_cancel_rule_id);
											$cpAddonCharge	 = (preg_match('/-/', $addonDetails[$addnkey]['adn_value'])) ? str_replace('-', '', $addonDetails[$addnkey]['adn_value']) : $addonDetails[$addnkey]['adn_value'];
											$cpMinusSymbol	 = (preg_match('/-/', $addonDetails[$addnkey]['adn_value'])) ? '(-)' : '';
										}
										$shOpt = 0;
										if ($addonDetails[$addnkey]['adn_type'] == 1)
										{
											$shOpt = 1;
											echo $cpDetails->cnp_label . ":" . $cpMinusSymbol . " " . Filter::moneyFormatter($cpAddonCharge);
										}

										$addnkey = array_search(2, array_column($addonDetails, 'adn_type'));
										if ($addonDetails[$addnkey]['adn_type'] == 2)
										{
											$cmLebel		 = SvcClassVhcCat::model()->findByPk($model->bkg_vehicle_type_id)->scv_label;
											$cmAddonCharge	 = (preg_match('/-/', $addonDetails[$addnkey]['adn_value'])) ? str_replace('-', '', $addonDetails[$addnkey]['adn_value']) : $addonDetails[$addnkey]['adn_value'];
											$cmMinusSymbol	 = (preg_match('/-/', $addonDetails[$addnkey]['adn_value'])) ? '(-)' : '';
										}
										if (($addonDetails[$addnkey]['adn_type'] == 2))
										{
											if ($shOpt == 1)
											{
												echo "<br>";
											}
											echo $cmLebel . ": " . $cmMinusSymbol . " " . Filter::moneyFormatter($cmAddonCharge);
										}
										?>

									</td>
								</<tr> 		
									<?php
								}
								if ($model->bkgInvoice->bkg_discount_amount != 0)
								{
									?>
								<tr>
									<td><span style="color: #575757;"><i>Discount Amount (Code : <?= $model->bkgInvoice->bkg_promo1_code ?>)</i></span></td><td align="right"><span style="color: red;">&#x20b9;(-)<?= number_format($model->bkgInvoice->bkg_discount_amount) ?></span></td>
								</tr>			
								<?php
							}
							if ($model->bkgInvoice->bkg_extra_discount_amount != 0)
							{
								?>
								<tr>
									<td><span style="color: #575757;">One-Time Price Adjustment</span></td><td align="right">&#x20b9;(-)<?= number_format($model->bkgInvoice->bkg_extra_discount_amount) ?></td>
								</tr>			
								<?php
							}
							?>
							<tr>
								<td><span style="color: #575757;">Amount (Excl Tax)</span></td><td align="right"><span style="border-top: #000 2px solid; padding-top: 3px;"><b>&#x20b9;<?= ($grossAmount - $model->bkgInvoice->bkg_convenience_charge); ?></b></span></td>
							</tr>
							<?php
							if ($model->bkgInvoice->bkg_additional_charge > 0)
							{
								?>
								<tr>
									<td><span style="color: #575757;">Additional Charge</span></td><td align="right">&#x20b9;<?= number_format($model->bkgInvoice->bkg_additional_charge) ?></td>
								</tr>			
								<?php
							}
							if ($model->bkgInvoice->bkg_driver_allowance_amount > 0)
							{
								?>
								<tr>
									<td><span style="color: #575757;">Driver  Allowance</span></td><td align="right">&#x20b9;<?= number_format($model->bkgInvoice->bkg_driver_allowance_amount); ?></td>
								</tr>			
<?php } ?>
							<tr>			
								<td><span style="color: #575757;">Toll Tax <?= ($model->bkgInvoice->bkg_is_toll_tax_included == 1) ? "(Included)" : "(Excluded)" ?></span></td><td align="right">&#x20b9;<?= ($model->bkgInvoice->bkg_toll_tax != '') ? $model->bkgInvoice->bkg_toll_tax : 0; ?></td>
							</tr>
							<tr>			
								<td><span style="color: #575757;">State Tax <?= ($model->bkgInvoice->bkg_is_state_tax_included == 1) ? "(Included)" : "(Excluded)" ?></span></td><td align="right">&#x20b9;<?= ($model->bkgInvoice->bkg_state_tax != '') ? $model->bkgInvoice->bkg_state_tax : 0; ?></td>
							</tr>
							<tr>
								<td><span style="color: #575757;">Airport Entry Fee <?= ($model->bkgInvoice->bkg_is_airport_fee_included == 1) ? "(Included)" : "(Excluded)" ?></span></td><td align="right">&#x20b9;<?= ($model->bkgInvoice->bkg_airport_entry_fee != '') ? $model->bkgInvoice->bkg_airport_entry_fee : 0; ?></td>
							</tr>
							<tr>
								<?php
								$staxrate	 = $model->bkgInvoice->getServiceTaxRate();
								$taxLabel	 = ($staxrate == 5) ? 'GST' : 'Service Tax ';
								?>
								<?php
								if ($model->bkgInvoice->bkg_cgst > 0)
								{
									?>
									<td><span style="color: #575757;">CGST (@<?= Yii::app()->params['cgst'] ?>%)</span></td><td align="right">&#x20b9;<?= ((Yii::app()->params['cgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax) | 0; ?></td>
									<?php
								}
								if ($model->bkgInvoice->bkg_sgst > 0)
								{
									?>
									<td><span style="color: #575757;">SGST (@<?= Yii::app()->params['sgst'] ?>%)</span></td><td>&#x20b9;<?= ((Yii::app()->params['sgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax) | 0; ?></td>
									<?php
								}
								if ($model->bkgInvoice->bkg_igst > 0)
								{
									?>
									<td><span style="color: #575757;">GST (@<?= Yii::app()->params['igst'] ?>%)</span></td><td align="right">&#x20b9;<?= ((Yii::app()->params['igst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax) | 0; ?></td>
								<?php } ?>
								<?php
								if ($staxrate != 5)
								{
									?>
									<td><span style="color: #575757;"><?= $taxLabel ?></span></td><td align="right">&#x20b9;<?= $model->bkgInvoice->bkg_service_tax; ?></td>
								</tr>

<?php } ?>
							<tr style="background: rgb(28, 79, 162); color: #fff;">
								<td style="padding: 10px;"><b style="font-size: 14px;">Total Amount</b></td> <td align="right" style="padding: 10px; font-size: 14px;"><b>&#x20b9;<?= number_format($model->bkgInvoice->bkg_total_amount) ?></b></td>
							</tr>
							<tr>
								<?php
								if ($model1->bkgInvoice->bkg_convenience_charge > 0)
								{
									?>
									<td><span style="color: #575757;">Applicable  Collect on Delivery(COD) fee (to be waived if advance payment is received  48hours before start of trip)</span></td>	
									<td><span style="color: #575757;">Total  cost (if advance payment  not received)</span></td><td align="right">&#x20b9;<?= number_format($model1->bkgInvoice->bkg_total_amount); ?></td>
								</tr>
								<tr>	
									<?php
								}
								if ($advance > 0)
								{
									?>
									<td style="padding: 6px 10px;"><span style="color: #575757;">Advance payment received</span></td><td align="right">&#x20b9;<?= number_format($advance) ?></td>
							<?php } ?> 	
							</tr>
							<?php
							if ($model->bkgInvoice->bkg_credits_used > 0)
							{
								?>	
								<tr>
									<td style="padding: 6px 10px;"><span style="color: #575757;">Gozo coins applied:</span></td><td align="right"><span style="color: red;"><b>&#x20b9;(-)<?= number_format($model->bkgInvoice->bkg_credits_used); ?></b></span></td>
								</tr>
<?php } ?>
							<tr>			

								<td style="padding: 6px 10px;"><span style="color: #575757;">Amount Due</span></td><td align="right"><b>&#x20b9;<?= number_format($due); ?></b></td>
							</tr>	
						</table>

						<table cellpadding="6" style="width: 100%; position: relative; border: #DFE4EE 1px solid; margin-bottom: 20px; font-size: 14px;">
							<tr>Billing Details</tr>
							<tr>			
								<td style="padding: 6px 10px;"><span style="color: #575757;">Name:</span></td><td align="right"><?= $model->bkgUserInfo->bkg_bill_fullname; ?></td>
							</tr>
							<tr>			
								<td style="padding: 6px 10px;"><span style="color: #575757;">GSTN: </span></td><td align="right"><?= $model->bkgUserInfo->bkg_bill_gst ?></td>
							</tr>
							<tr>	
								<?php
								$billingAdrs = "";
								if ($model->bkgUserInfo->bkg_bill_address != "")
								{
									$billingAdrs = $model->bkgUserInfo->bkg_bill_address;
								}
								if ($model->bkgUserInfo->bkg_bill_city != '' && $billingAdrs != '')
								{
									$billingAdrs .= ", " . $model->bkgUserInfo->bkg_bill_city;
								}
								if ($model->bkgUserInfo->bkg_bill_state != '' && $billingAdrs != '')
								{
									$billingAdrs .= ", " . $model->bkgUserInfo->bkg_bill_state;
								}
								if ($model->bkgUserInfo->bkg_bill_country != '' && $billingAdrs != '')
								{
									$billingAdrs .= ", " . $model->bkgUserInfo->bkg_bill_country;
								}
								if ($model->bkgUserInfo->bkg_bill_postalcode != '' && $billingAdrs != '')
								{
									$billingAdrs .= ", " . $model->bkgUserInfo->bkg_bill_postalcode;
								}
								?>
								<td style="padding: 6px 10px;"><span style="color: #575757;">Address:</span></td><td align="right"><?= $billingAdrs ?></td>
							</tr>
						</table>

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
							<table width="100%" style="font-size: 14px; margin-top: 10px;">
								<tr>
									<td width="50%"><b>SPECIAL INSTRUCTION & ADVISORIES THAT MAY AFFECT YOUR PLANNED TRAVEL</b></td>
								</tr>
							</table>
							<div style="width: 100%; float: left; margin-top: 10px;">

								<table align="center" width="100%" style="border:#DFE4EE 1px solid; padding: 4px 10px; font-size: 14px; font-family: 'Arial'; line-height: 18px;">
									<tr style="background-color: rgb(229, 238, 255); padding: 5px;">
										<td align="center" width="20%"><b>Place</b></td>
										<td align="center"><b>Note</b></td>
									</tr>
									<?php
									for ($i = 0; $i < count($note); $i++)
									{
										?>
										<tr>
											<td align="left"  style="padding-left:5px; padding-bottom: 20px;"><?php
												if ($note[$i]['dnt_area_type'] == 3)
												{
													?>
													<?= ($note[$i]['cty_name']) ?>
													<?php
												}
												else if ($note[$i]['dnt_area_type'] == 2)
												{
													?>
													<?= ($note[$i]['dnt_state_name']) ?>
													<?php
												}
												else if ($note[$i]['dnt_area_type'] == 1)
												{
													?>
													<?= ($note[$i]['dnt_zone_name']) ?>
													<?php
												}
												else if ($note[$i]['dnt_area_type'] == 0)
												{
													?>
													<?= "Applicable to all" ?>
													<?php
												}
												else if ($note[$i]['dnt_area_type'] == 4)
												{
													?>
													<?= Promos::$region[$note[$i]["dnt_area_id"]] ?>
													<?php
												}
												?></td>
											<td align="left" style="padding-left:5px; padding-bottom: 20px;"><?= ($note[$i]['dnt_note']) ?></td>
										</tr>
									<?php } ?>
								</table>
							</div>
						<?php } ?>
						<div style="width: 100%; float: left; margin-top: 10px;">
<!--                            Your auto-generated invoice can be viewed <a href="#" style="color:#0279E8;"><b></b></a> after the trip is completed-->
                             <a href="#" style="color:#0279E8;"><b><?= $file; ?></b></a> once your trip is completed.
                        </div>
						<div style="width: 100%; float: left; margin-top: 10px;">
							<?php
							$correctimg	 = '<img src="' . $baseURL . '/images/email/correct-icon.png" alt="img" height="12">';
							$crossimg	 = '<img src="' . $baseURL . '/images/email/cross-iocn.png" alt="img" height="12">';
							?>
							<table width="100%" style="font-size: 14px; margin-top: 10px; margin-bottom: 10px;">
								<tr>
									<td width="50%"><b>Fare Inclusions and Exclusions</b></td>
									<td align="right"><?= $correctimg ?>&nbsp; Included <?= $crossimg ?>&nbsp; Excluded</td>
								</tr>
							</table>
							<p style="margin-bottom: 10px;"><?= ($model->bkgInvoice->bkg_is_toll_tax_included == 1) ? $correctimg : $crossimg ?>&nbsp; Toll Taxes<br>
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
							</p>
							<p style="margin-bottom: 10px;"><?= ($model->bkgInvoice->bkg_is_state_tax_included == 1) ? $correctimg : $crossimg ?>&nbsp; State Taxes<br>
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
							</p>
							<p style="margin-bottom: 10px;"><?= $crossimg ?>&nbsp; MCD</p>
							<p style="margin-bottom: 10px;"><?= ($model->bkgInvoice->bkg_is_airport_fee_included == 1) ? $correctimg : $crossimg ?>&nbsp; Airport Entry Charges<?= (($model->bkgInvoice->bkg_is_airport_fee_included == 1) ? '(Rs.' . $model->bkgInvoice->bkg_airport_entry_fee . ')' : ''); ?><br>
								[ <?php
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
								?> ]
							</p>
							<p style="margin-bottom: 10px;"><?= ($model->bkgInvoice->bkg_night_pickup_included > 0) ? $correctimg : $crossimg; ?>&nbsp; Night Pickup Charges 
								<?php echo (!empty($prarr['prr_night_start_time'] && !empty($prarr['prr_night_end_time'])) ? "(" . date("g A", strtotime($prarr['prr_night_start_time'])) . " - " . date("g A", strtotime($prarr['prr_night_end_time'])) . ")" : '') . (!empty($prarr['prr_night_driver_allowance']) ? " - Rs." . $prarr['prr_night_driver_allowance'] : ''); ?></p>
							<p style="margin-bottom: 10px;"><?= ($model->bkgInvoice->bkg_night_drop_included > 0) ? $correctimg : $crossimg; ?>&nbsp; Night Drop Charges 
								<?php echo (!empty($prarr['prr_night_start_time'] && !empty($prarr['prr_night_end_time'])) ? "(" . date("g A", strtotime($prarr['prr_night_start_time'])) . " - " . date("g A", strtotime($prarr['prr_night_end_time'])) . ")" : '') . (!empty($prarr['prr_night_driver_allowance']) ? " - Rs." . $prarr['prr_night_driver_allowance'] : ''); ?></p>
							<p style="margin-bottom: 10px;"><?= ($model->bkgInvoice->bkg_trip_waiting_charge > 0) ? $correctimg : $crossimg ?>&nbsp; Waiting Charges</p>
							<p style="margin-bottom: 10px;"><?= ($model->bkgInvoice->bkg_extra_km > 0) ? $correctimg : $crossimg ?>&nbsp; Extra Charges <?= '(Rs.' . $model->bkgInvoice->bkg_rate_per_km_extra . ' / KM beyond ' . $model->bkg_trip_distance . ' KMS).' ?></p>
							<p style="margin-bottom: 10px;"><?= $crossimg ?>&nbsp; Green Tax</p>
							<p style="margin-bottom: 10px;"><?= $crossimg ?>&nbsp; Entry Taxes / Charges</p>
							<p style="margin-bottom: 10px;"><?= ($model->bkgInvoice->bkg_parking_charge == 1) ? $correctimg : $crossimg; ?>&nbsp; Parking Charges<br>
								[ <?php
								if ($model->bkgInvoice->bkg_parking_charge > 0)
								{
									?> Parking charges are prepaid upto ₹<?= round($model->bkgInvoice->bkg_parking_charge) ?>.<?php } ?> 
								Customer will directly pay for parking charges after the total parking cost for the trip exceeds ₹<?= round($model->bkgInvoice->bkg_parking_charge) ?>. Driver must upload all parking receipts for payments made by drive.
								]
							</p>
							<p style="margin-bottom: 10px;">Final outstanding shall be computed after trip completion. Additional amount, if 
								any, may be paid in cash to the driver directly.</p>
							<p><span style="font-size: 14px; margin-top: 10px;"><b>Cancellation Information</b></span> (Booking created at <?= date('d M Y h:i A', strtotime($model->bkg_create_date)); ?>)</p>
							<?php $cancelTimes_new = CancellationPolicy::initiateRequest($model); ?>
							<table align="center" width="380" style="max-width: 380px; width: 380px; margin: 0 auto; font-size: 14px;">
								<tr>
									<td width="100%" style="border: #efefef 3px solid; border-left: #4CCD74 3px solid; text-align: center; padding: 10px; width: 100%;">
										<span style="font-size: 14px;"><b>Free cancellation period</b></span>
										<?php if ($model->bkgTrail->bkg_confirm_datetime == array_keys($cancelTimes_new->slabs)[0])
										{
											?>
											<p>--</p>
										<?php }
										else
										{ ?>
											<p style="margin-bottom: 0;"><?= date('d M Y H:i a', strtotime(($model->bkgTrail->bkg_confirm_datetime != '') ? $model->bkgTrail->bkg_confirm_datetime : $model->bkg_create_date)); ?>&nbsp;<span><img src="<?= $baseURL ?>/images/email/transfer.png" alt="img" style="margin: 0 10px;"></span>&nbsp;<?= date('d M Y h:i a', strtotime(array_keys($cancelTimes_new->slabs)[0])) ?></p>
<?php } ?>
									</td>
								</tr>
								<tr>
									<td width="100%" style="border: #efefef 3px solid; border-left: #f39132 3px solid; text-align: center; padding: 10px; margin-top: 2px;">
										<span style="font-size: 14px;"><b>Cancellation Charge : &#x20b9;<?= array_values($cancelTimes_new->slabs)[1]; ?></b></span>
										<p style="margin-bottom: 0;"><?= date('d M Y h:i a', strtotime(array_keys($cancelTimes_new->slabs)[0])) ?>&nbsp;<span><img src="<?= $baseURL ?>/images/email/transfer.png" alt="img" style="margin: 0 10px;"></span>&nbsp;<?= date('d M Y h:i a', strtotime(array_keys($cancelTimes_new->slabs)[1])) ?></p>
									</td>
								</tr>
								<tr>
									<td width="100%" style="border: #efefef 3px solid; border-left: #EF2B2B 3px solid; text-align: center; padding: 10px; margin-top: 2px;">
										<span style="font-size: 14px;"><b>Cancellation Charge : &#x20b9;<?= $cancelTimes_new->slabs[-1] ?> </b></span>
										<p style="margin-bottom: 0;">after <?= date('d M Y h:i a', strtotime(array_keys($cancelTimes_new->slabs)[1])); ?></p>
									</td>
								</tr>
							</table>

							<div style="width: 100%; float: left; position: relative;">
								<?php
								if (count($cancellationPoints) > 0)
								{
									echo "<ul style='padding-left: 15px;'>";
									foreach ($cancellationPoints as $c)
									{
										echo "<li style='padding-bottom: 10px;'>" . $c['tnp_text'] . "</li>";
									}
									echo "</ul>";
								}
								?>
								<p style="font-size: 14px; margin-top: 10px; margin-bottom: 10px;"><b>Boarding Checks</b></p>
								<?php
								if (count($boardingcheckPoints) > 0)
								{
									echo "<ul style='padding-left: 15px;'>";
									foreach ($boardingcheckPoints as $c)
									{
										echo "<li style='padding-bottom: 10px;'>" . $c['tnp_text'] . "</li>";
									}
									echo "</ul>";
								}
								?>	

								<p style="font-size: 14px; margin-top: 10px; margin-bottom: 10px;"><b>On Trip Dos & Donts</b></p>
								<?php
								if (count($dosdontsPoints) > 0)
								{
									echo "<ul style='padding-left: 15px;'>";
									foreach ($dosdontsPoints as $c)
									{
										echo "<li style='padding-bottom: 10px;'>" . $c['tnp_text'] . "</li>";
									}
									echo "</ul>";
								}
								?>

								<p style="font-size: 14px; margin-top: 10px; margin-bottom: 10px;"><b>Other Terms</b></p>
								<?php
								if (count($othertermsPoints) > 0)
								{
									$str = '';
									$str = "<ul type='1' style='padding-left: 15px;'>";
									foreach ($othertermsPoints as $c)
									{
										$str .= "<li style='padding-bottom: 10px;'>" . $c['tnp_text'] . "</li>";
									}
									$str .= "</ul>";
									echo $str;
								}
								?>
							</div>
						</div>
					</td>
				</tr>
			</table>
		<td>
	<tr>
</table>