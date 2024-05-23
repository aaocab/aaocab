<?php
$bookingStatus	 = Booking::model()->getUserBookingStatus();
$reconfirmStatus = Booking::model()->getReconfirmStatus();
?>
<?php
foreach ($models as $key => $val)
{
	$bkid			 = $val['bkg_id'];
	$bkmodel		 = Booking::model()->findbyPk($bkid);
	$vehicleModel	 = $bkmodel->bkgBcb->bcbCab->vhcType->vht_model;
	if ($bkmodel->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
	{
		$vehicleModel = OperatorVehicle::getCabModelName($bkmodel->bkgBcb->bcb_vendor_id, $bkmodel->bkgBcb->bcb_cab_id);
	}
	$bkgPrefmodel = BookingPref::model()->getByBooking($bkid);
	if ($bkmodel->bkg_status == 9)
	{
		if ($val['bkg_cancel_charge'] > 0)
		{
			$cancelcharge = round($val['bkg_cancel_charge'] + $val['bkg_cancel_gst']);
		}
		else
		{
			$cancelcharge = CancellationPolicyRule::getCharges($bkmodel);
			if ($cancelcharge == '')
			{
				$cancelcharge = round($val['bkg_cancel_charge']);
			}
		}
	}


	$rescheduleFrom = "";
	if ($bkmodel->bkgPref->bpr_rescheduled_from > 0)
	{
		$prevBooking	 = Booking::model()->findByPk($bkmodel->bkgPref->bpr_rescheduled_from);
		$rescheduleFrom	 = ($prevBooking->bkg_status == 9) ? "(previous booking id: " . $prevBooking->bkg_booking_id . " - reschedule completed)" : "(previuos booking id: " . $prevBooking->bkg_booking_id . " - reschedule initiated)";
	}
	else
	{
		$bkgPrefModelRes = BookingPref::model()->with('bprBkg')->findBySql("SELECT bpr_bkg_id FROM `booking_pref` WHERE booking_pref.bpr_rescheduled_from = {$bkid};");
		if ($bkgPrefModelRes != '')
		{
			$rescheduleFrom = (!in_array($bkgPrefModelRes->bprBkg->bkg_status, [1, 15, 9, 10])) ? "(new booking id: " . Booking::model()->getCodeById($bkgPrefModelRes->bpr_bkg_id) . " - reschedule completed)" : "(new booking id: " . Booking::model()->getCodeById($bkgPrefModelRes->bpr_bkg_id) . " - reschedule initiated)";
		}
	}
	$route		 = BookingRoute::model()->getRouteName($bkid);
	$hash		 = Yii::app()->shortHash->hash($bkid);
	$payurl		 = Yii::app()->createAbsoluteUrl('booking/paynow', ['id' => $bkid, 'hash' => $hash]);
	$response	 = Contact::referenceUserData($val['bui_id'], 3);
	if ($response->getStatus())
	{
		$contactNo	 = $response->getData()->phone['number'];
		$countryCode = $response->getData()->phone['ext'];
		$firstName	 = $response->getData()->email['firstName'];
		$lastName	 = $response->getData()->email['lastName'];
		$email		 = $response->getData()->email['email'];
	}
	//$cabImg	 = $val->bkgVehicleType->vht_image;
	//$cabType = $val->bkgVehicleType->vht_model;
	$vhtSelected = "";
	if ($val['bkg_vht_id'] > 0 && $val['vct_id']!=5 && $val['vct_id']!=6)
	{
		$vehicleType = VehicleTypes::model()->findByPk($val['bkg_vht_id']);
		$vhtSelected = "-" . $vehicleType->vht_make . " " . $vehicleType->vht_model;
	}
	$cabImg	 = $val['vct_image'];
	$catLabel = SvcClassVhcCat::getCatrgoryLabel($val['bkg_vehicle_type_id'],true);
	$cabType = $catLabel. $vhtSelected;
	//if ($val->bkgBcb && $val['bkg_status'] > 3 && $val['bkg_status'] <= 7)
	if ($val['bkg_bcb_id'] && $val['bkg_status'] > 3 && $val['bkg_status'] <= 7)
	{
		//$cabImg	 = $val->bkgBcb->bcbCab->vhcType->vht_image;
		$bookingCab	 = BookingCab::model()->findByPk($val['bkg_bcb_id']);
		$cabType	 = $bookingCab->bcbCab->vhcType->vht_make . " " . $vehicleModel;
		$cabImg		 = $val['vct_image'];
		//$cabType = $val['vct_label'] . "  (" . $val["scc_label"] . ")";
	}
	$uniqueid	 = Booking::model()->generateLinkUniqueid($bkid);
	$link		 = Yii::app()->createAbsoluteUrl('/' . '/r/' . $uniqueid);
	$isRating	 = Ratings::model()->getRatingbyBookingId($bkid);
	$creditsused = ($val['bkg_credits_used'] > 0) ? $val['bkg_credits_used'] : 0;
	?>
	<div class="row">
		<div class="col-12">
			<div class="card mb20">
				<div class="card-body p15">
					<div class="row">
						<div class="col-6 col-xl-2 p0">
							<div class="view-id"><a href="<?php echo $payurl; ?>" target="_blank" bkgcode="<?php echo $val['bkg_booking_id'] ?>" title="Booking Detail" role="button"><?php echo Filter::formatBookingId($val['bkg_booking_id']) ?></a></div>
							<img src="<?php echo '/' . $cabImg ?>" alt="Image Not Found"  class="img-fluid">

						</div>
						<div class="col-12 col-xl-10">
							<div class="row">
								<div class="col-12 mb15">
									<h2 class="font-18 mb0 text-uppercase weight600"><?php echo $route ?></h2>
									<div class="color-gray font-12 mb5"><?php echo $rescheduleFrom; ?></div>
									<div class="font-13">
										<span class="color-gray pr20"><img src="/images/bx-alarm2.svg" alt="img" width="12" height="12"> Booked on: <?php echo date('d/m/Y', strtotime($val['bkg_create_date'])) . ', ' . date('h:i A', strtotime($val['bkg_create_date'])); ?></span>
										<span class="color-gray m-block"><img src="/images/bx-calendar.svg" alt="img" width="12" height="12"> Pickup Date: <?php echo date('d/m/Y', strtotime($val['bkg_pickup_date'])) . ', ' . date('h:i A', strtotime($val['bkg_pickup_date'])); ?></span>
										<?php
										if ($val['bkg_booking_type'] == 2 || $val['bkg_booking_type'] == 3)
										{
											//$cntRoutes = count($val->bookingRoutes) - 1;
											?>
											<span class="color-gray">Return Date:</span> <?php echo date('d/m/Y', strtotime($val['brt_pickup_datetime'])) . ', ' . date('h:i A', strtotime($val['brt_pickup_datetime'])); ?><br/>
										<?php } ?>
									</div>
								</div>
								<div class="col-12 col-xl-9">
									<p class="text-uppercase font-18 mb5"><b><?php echo strtoupper($cabType); ?></b></p>
									<p class="font-13 mb5">
										<b><?php echo ucfirst($firstName) . ' ' . ucfirst($lastName); ?></b><br/>
										<?php
										if ($contactNo != '')
										{
											?>
											<span class="color-gray pr20"><img src="/images/bxs-phone2.svg" alt="img" width="12" height="12"> Mobile: <?php echo $countryCode . '-' . $contactNo; ?></span>
										<?php } ?>
										<?php
										if ($email != '')
										{
											?>
											<span class="color-gray pr20"><img src="/images/bx-envelope.svg" alt="img" width="12" height="12"> Email: <?php echo $email; ?></span>
										<?php } ?>
										<?php
										if ($val['bkg_is_gozonow'] == 1 && $val['bkg_pickup_date'] > Filter::getDBDateTime() && $val['bkg_status'] == 2 && $val['bkg_reconfirm_flag'] == 0)
										{
											$bidCount = BookingCab::getTotalBidCountByBkg($bkid);
											?>
											<span class="color-gray m-block"><img src="/images/gavel.svg" alt="img" width="12" height="12"> Track Offer: <a href="javascript:void(0);" onclick="gznowtrack('<?php echo $bkid ?>', '<?php echo $hash ?>')"><?php echo $bidCount; ?></a></span><br/>
										<?php } ?>
									</p>
									<?php
									if ($val['bkg_status'] <= 7)
									{
										$classStatus = 'color-green';
									}
									else
									{
										$classStatus = 'color-red';
									}
									if ($bkgPrefmodel['bkg_is_gozonow'] == 1 && (!in_array($val['bkg_status'], [8, 9])) && $val['bkg_reconfirm_flag'] == 0)
									{
										$classStatus = 'color-red';
									}
									?>
									<p class="mb10"><span class="color-gray">Status:</span> <span class="<?php echo $classStatus; ?>">
											<?php
											if ($bkgPrefmodel['bkg_is_gozonow'] == 1 && (!in_array($val['bkg_status'], [8, 9])))
											{
												echo $reconfirmStatus[$val['bkg_reconfirm_flag']];
											}
											else
											{
												echo $bookingStatus[$val['bkg_status']];
											}
											?>
										</span></p>
								</div>
								<div class="col-12 col-xl-3 text-right show-widget">
									<span class="font-11">Total amount:</span><br> 
									<span class="font-24">
										<b>&#x20B9;
											<?php
											if ($val['bkg_due_amount'] != '')
											{
												if ($val['bkg_due_amount'] >= 0)
												{
													echo round($val['bkg_total_amount']);
												}
												else
												{
													echo '0';
												}
											}
											else
											{
												echo round($val['bkg_total_amount']);
											}
											?>
										</b>
									</span>
								</div>
								<div class="col-12">
									<div class="row">
										<?php
										//if ($val->bkgBcb->bcb_driver_id != '')
										if ($val['bcb_driver_id'] != '')
										{
											?>
											<div class="col-12 col-lg-4">
												<span class="color-gray">Driver:</span>
												<b><?php echo ucwords($val['bcb_driver_name']); ?></b>
											</div>
										<?php } ?>
										<?php
										if ($val['bcb_cab_id'] != '')
										{
											?>
											<div class="col-12 col-lg-4">
												<span class="color-gray">Cab Number:</span>
												<b><?php echo strtoupper($val['vhc_number']); ?></b>
											</div>
											<?php
										}
										?>
									</div>
									<div class="row">
										<div class="col-12 text-right">
											<?php
											$date1	 = date('Y-m-d H:i:s', strtotime($val['bkg_pickup_date'] . '- 120 minute'));
											$date1	 = new DateTime($date1);
											$date2	 = date('Y-m-d H:i:s');
											$date2	 = new DateTime($date2);
											$stop	 = true;
											$isPromo = BookingSub::model()->getApplicable($val['bkg_from_city_id'], $val['bkg_to_city_id'], 3);
											if ($val['bkg_is_gozonow'] == 1 && $val['bkg_pickup_date'] > Filter::getDBDateTime() && $val['bkg_status'] == 2 && $val['bkg_reconfirm_flag'] == 0)
											{
												?>
												<button type="button" class="btn btn-info text-uppercase gradient-green-light font-12" id="gznowtrack" onclick="gznowtrack(<?php echo $bkid ?>, '<?php echo $hash ?>')" title="Track Offer">Track Offer</button>
												<?php
											}
											if ($val['bkg_status'] == 1 && $date1 > $date2 && $isPromo && $val['bkg_booking_type'] != 6 && $stop == false)
											{
												?>
												<button type="button" class="btn btn-outline-warning font-12 mb5 pl10 pr10" id="verify" onclick="verifyBooking(<?php echo $bkid ?>, '<?php echo $hash ?>')" title="Verify Booking"><img src="/images/bx-check2.svg" alt="img" width="14" height="14"> Verify Booking</button>
											<?php } ?>
											<?php
											if ($val['bkg_status'] == 1 && $date1 < $date2)
											{
												?>
												<div style="cursor: text;" class="btn btn-outline-danger font-12 mb5 pl10 pr10" title="Expired">Expired</div>
											<?php } ?>
											<?php
											if (in_array($val['bkg_status'], [8, 9]) && $bkgPrefmodel['bkg_is_gozonow'] == 1)
											{
												?>
												<a data-toggle="ajaxModal"   rel="popover" data-placement="left" class="btn btn-primary font-12 mb0 pb5 pl10 pr15 " title="New Booking" onClick="return reqCMB(1, '<?php echo $bkid ?>')" href="#"><img src="/images/bxs-phone.svg" alt="img" width="16" height="16">Request a call</a>

											<?php } ?>
											<?php
											if ((($val['bkg_status'] == 15 || $val['bkg_status'] == 2 || $val['bkg_status'] == 3 || $val['bkg_status'] == 5) && ($val['bkg_due_amount'] > 0 || $val['bkg_due_amount'] == '')) || (!$isPromo && $val['bkg_status'] == 1 && $date1 > $date2))
											{
												?>
												<a href="<?php echo $payurl ?>" class="btn btn-outline-primary font-12 mb5 pl10 pr10" target="_blank"  id="payment" title="Payment"><span>&#x20b9;</span>Make payment</a>
											<?php } ?>
											<?php
											if ($val['bkg_status'] == 15 || $val['bkg_status'] == 2 || $val['bkg_status'] == 3 || $val['bkg_status'] == 5)
											{
												?>
												<!--<button type="button" class="btn btn-primary text-uppercase bg-red font-12 border-none mt15" id="cancel" onclick="canBooking(<?php echo $bkid ?>)" title="Cancel Booking">Cancel Booking</button>-->
												<button type="button" class="btn btn-outline-danger font-12 mb5 pl10 pr10" id="cancel" onclick="checkTripStatus(<?php echo $bkid ?>)" title="Cancel Booking"><img src="/images/bx-x.svg" alt="img" width="14" height="14"> Cancel Booking</button>
												<?php
											}
											$date = date('Y-m-d H:i:s');
											if (($val['bkg_status'] == 2 || $val['bkg_status'] == 15) && $val['bkg_pickup_date'] > $date)
											{
												?>
												<button type="button" class="btn btn-outline-success font-12 mb5 pl10 pr10" id="modify" onclick="modify(<?php echo $bkid ?>)" title="Edit Booking"><img src="/images/bx-edit-alt.svg" alt="img" width="14" height="14"> Edit</button>
											<?php } ?>
											<?php
//										$pickupDate	 = date('Y-m-d H:i:s', strtotime($val['bkg_pickup_date']));
//										$pickupTime	 = new DateTime($pickupDate);
//										if ($val['bkg_status'] == 2 && $pickupTime > $date2 && $bkgPrefmodel['bkg_critical_score'] <= 0.65 && $bkgPrefmodel['bkg_is_gozonow'] == 0)
//										{
											?>
	<!--											<button type="button" class="btn btn-outline-success font-12 mb5 pl10 pr10" id="reschedule" onclick="reschedule(<?php echo $bkid ?>)" title="Reschedule Pickup Time">Reschedule Pickup Time</button>-->
											<?php // } ?>
											<?php
											//$getBookingLogInfo	 = BookingLog::model()->getRescheduleTimeLog($bkid);
											if (in_array($val['bkg_status'], [2, 3, 5]) && $bkgPrefmodel->bpr_rescheduled_from == 0 && $bkgPrefmodel->bkg_is_gozonow != 1 && $val['bkg_pickup_date'] > $date)
											{
												?>
												<button type="button" class="btn btn-outline-success font-12 mb5 pl10 pr10" id="reschedule" onclick="reschedulePopup(<?php echo $bkid ?>)" title="Reschedule Booking">Reschedule Booking</button>
											<?php } ?>
											<?php
											if ($val['bkg_advance_amount'] > 0)
											{
												?>
												<button type="button" class="btn btn-outline-primary font-12 mb5 pl10 pr10" onclick="showPayment(<?php echo $bkid ?>, '<?php echo Yii::app()->shortHash->hash($bkid) ?>')" title="Invoice">Payment Details</button>
											<?php } ?>
											<?php
											if ($val['bkg_status'] == 6 || $val['bkg_status'] == 7)
											{
												?>
												<button type="button" class="btn btn-outline-primary font-12 mb5 pl10 pr10" id="showreview" onclick="receipt(<?php echo $bkid ?>, '<?php echo Yii::app()->shortHash->hash($bkid) ?>')" title="Invoice">Invoice</button>
												<?php
												if ($isRating != false)
												{
													?>
													<button type="button" class="btn btn-outline-primary font-12 mb5 pl10 pr10" id="showreview" onclick="showreview(<?php echo $bkid ?>)" title="Reviewed">Show Review</button>
													<?php
												}
												else
												{
													?>
													<a class="btn btn-outline-primary font-12 mb5 pl10 pr10" target="_blank" href="<?php echo $link ?>">Review</a>
													<!--<button type="button" class="btn btn-warning border-none text-uppercase mb10" id="review" onclick="ratetheJourney(<? //= $bkid                                                            ?>)" title="Review">Review</button>-->
													<?php
												}
											}
											?>     
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
						if ($val['bkg_advance_amount'] > 0)
						{
							?>
							<div class="col-12  pt10 pb10">
								<div class="row  ">
									<div class="col-6 col-lg-3">Advance Paid: &#x20B9;<b><?php echo round($val['bkg_advance_amount']) ?></b></div>
									<?php
//									$payIDs = PaymentGateway::getTransCodebyBkgId($val['bkg_id'], 2);
//									if ($payIDs)
//									{
//										echo '<div class="col-12 col-lg-6">Payment Transaction ID(s): <b>' . $payIDs . '</b></div>';
//									}

									if ($creditsused > 0)
									{
										?>
										<div class="col-6 col-lg-3">Credit Used: &#x20B9;<b><?php echo round($creditsused) ?></b></div>
										<?php
									}
									if ($val['bkg_status'] != 9)
									{
										?>
										<div class="col-6 col-lg-3">Due Amount: &#x20B9;<b><?php echo round($val['bkg_due_amount']) ?></b></div>
										<?php
									}
									if ($cancelcharge > 0 && $val['bkg_status'] == 9)
									{
										?>  
										<div class="col-6 col-lg-4">Cancellation Charge: &#x20B9;<b><?php echo $cancelcharge; ?></b>
										</div><?php } ?>
									<!--							if ($val['bkg_cancel_charge'] > 0)
																{
																?>  <div class="col-6 col-md-4">Cancellation Charge: &#x20B9;<b><?php //echo round($val['bkg_cancel_charge'])                                                   ?></b></div><?php //}                                                   ?> -->

									<?php
									if ($val['bkg_refund_amount'] > 0)
									{
										?>


										<div class="col-12 col-lg-3"> Refund: &#x20B9;
											<b>
												<?php echo round($val['bkg_refund_amount']) ?>
											</b>
										</div>
										<?php
//										if ($val['bkg_status'] != 9)
//										{
//											$refundIDs = PaymentGateway::getTransCodebyBkgId($val['bkg_id'], 1);
//											if ($refundIDs)
//											{
//												echo '<div class="col-12 col-lg-6">Refund Transaction ID(s): <b>' . $refundIDs . '</b></div>';
//											}
//										}
										?>


									<?php }
									?>
								</div>
							</div>
						<?php }
						?>
						<div class="list-footer">
							<div class="row">
								<div class="col-12 col-sm-6"></div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php
if (empty($models))
{
	?>
	<div class="row">
		<div class="col-12">
			<div class="list_booking">
				<div class="list_heading text-center pt20 pb20" style="background: #f77026; color: #fff;">
					<b>Sorry!! No records found</b>
				</div>            
			</div>
		</div>
	</div>  
<?php } ?>
<div class="col-12 mt20 mb20">
	<?php
	$this->widget('booster.widgets.TbPager', array('pages' => $usersList->pagination));
	?>
</div>
<div class="modal fade" id="editBookingModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-16" id="editBookingModalLabel">Edit Booking</h5>
				<button type="button" class="close mt30 n" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="editBookingModelContent">
				<div class="row"></div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="cancelBookingModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-16" id="cancelBookingModalLabel">Cancel Booking</h5>
				<button type="button" class="close mt15 n" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="cancelBookingModelContent">
				<div class="row"></div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="viewBookingModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-16" id="viewBookingModalLabel">Booking Details</h5>
				<button type="button" class="close mt30 n" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="viewBookingModelContent">
				<div class="row"></div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="viewPaymentModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header pt0 pb0">
				<h5 class="modal-title font-16"  >Payment Details</h5>
				<button type="button" class="close  " data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="viewPaymentContent">
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="reviewBookingModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-16" id="reviewBookingModalLabel">Review</h5>
				<button type="button" class="close mt30 n" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="reviewBookingModelContent">
				<div class="row"></div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="rescheduleBooking" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-16" id="rescheduleBookingLabel">Reschedule Booking</h5>
				<button type="button" class="close mt30 n pt0" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="rescheduleBookingContent">
				<div class="row"></div>
			</div>
			<div class="modal-body hide" id="rescheduleBookingDeatils">
				<div class="row"></div>
			</div>
		</div>
	</div>
</div> 
<script>
	$(document).ready(function () {
		var front_end_height = parseInt($(window).outerHeight(true));
		var footer_height = parseInt($("#footer").outerHeight(true));
		var header_height = parseInt($("#header").outerHeight(true));
		var ch = (front_end_height - (header_height + footer_height + 23));
		$("#content").attr("style", "height:" + ch + "px;");
	});
	function canBooking(booking_id) {
		$href = "<?php echo Yii::app()->createUrl('booking/canbooking') ?>";
		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"booking_id": $booking_id},
			success: function (data)
			{
				$('#cancelBookingModal').removeClass('fade');
				$('#cancelBookingModal').css('display', 'block');
				$('#cancelBookingModelContent').html(data);
				$('#cancelBookingModal').modal('show');
			}
		});
	}
	function viewBooking(obj) {
		var href2 = $(obj).attr("href");
		var bcode = $(obj).attr("bkgcode");
		$('#viewBookingModalLabel').html('Booking Details for ' + bcode);
		$.ajax({
			"url": href2,
			"type": "GET",
			"dataType": "html",
			"success": function (data) {
				$('#viewBookingModal').removeClass('fade');
				$('#viewBookingModal').css('display', 'block');
				$('#viewBookingModelContent').html(data);
				$('#viewBookingModal').modal('show');
			}
		});
		return false;
	}
	function ratetheJourney(booking_id) {
		$href = "<?php echo Yii::app()->createUrl('rating/addreview') ?>";
		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": $booking_id},
			success: function (data)
			{
				var box = bootbox.dialog({
					message: data,
					title: 'Review',
					onEscape: function () {
					},
				});
			}
		});
	}
	function showreview(booking_id) {
		$href = "<?php echo Yii::app()->createUrl('rating/showreview') ?>";
		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": $booking_id},
			success: function (data)
			{
				$('#reviewBookingModal').removeClass('fade');
				$('#reviewBookingModal').css('display', 'block');
				$('#reviewBookingModelContent').html(data);
				$('#reviewBookingModal').modal('show');
			}
		});
	}
	function showPayment(booking_id) {
		$href = "<?php echo Yii::app()->createUrl('booking/showPaymentDetails') ?>";
		var $booking_id = booking_id;
		var form = $('#viewPaymentModal');
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": $booking_id},
			"beforeSend": function ()
			{
				blockForm(form);
			},
			"complete": function ()
			{
				unBlockForm(form);
			},
			success: function (data)
			{
				$('#viewPaymentModal').removeClass('fade');
				$('#viewPaymentModal').css('display', 'block');
				$('#viewPaymentContent').html(data);
				$('#viewPaymentModal').modal('show');
			}
		});
	}
	function receipt(booking_id, hsh)
	{
		$href = "<?php echo Yii::app()->createUrl('booking/receipt') ?>";
		var $booking_id = booking_id;
		window.open($href + "/bkg/" + $booking_id + "/hsh/" + hsh, '_blank');
	}
	function verifyBooking(booking_id, hash) {
		$href = "<?php echo Yii::app()->createUrl('booking/verifybooking') ?>";
		var $booking_id = booking_id;
		var $hash = hash;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"booking_id": $booking_id},
			success: function (data)
			{
				if (data == true) {
					//                    alert('Booking verified successfully');
					//                    location.reload();
					confirmBooking($booking_id, $hash);
				} else {
					alert('Insufficient data. Please contact our customer support.');
				}
			}
		});
	}
	function confirmBooking($booking_id, $hash) {
		var href1 = '<?php echo Yii::app()->createUrl('booking/confirmmobile') ?>';
		jQuery.ajax({'type': 'GET', 'url': href1,
			'data': {'bid': $booking_id, 'manual': 'manual', 'hsh': $hash},
			success: function (data) {
				box = bootbox.dialog({
					message: data,
					title: '',
					size: 'medium',
					onEscape: function () {
					}
				});
			},
			error: function (xhr, ajaxOptions, thrownError) {
			}
		});
	}
	function modify(booking_id) {
		$href = "<?php echo Yii::app()->createUrl('booking/editnew') ?>";
		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": $booking_id},
			success: function (data)
			{
				$('#editBookingModal').removeClass('fade');
				$('#editBookingModal').css('display', 'block');
				$('#editBookingModelContent').html(data);
				$('#editBookingModal').modal('show');
			}
		});
	}

	function reschedule(booking_id) {
		$href = "<?php echo Yii::app()->createUrl('booking/editpickuptime') ?>";
		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": $booking_id},
			success: function (data)
			{
				$('#rescheduleBooking').removeClass('fade');
				$('#rescheduleBooking').css('display', 'block');
				$('#rescheduleBookingContent').html(data);
				$('#rescheduleBooking').modal('show');
			}
		});
	}

	function reschedulePopup(booking_id) {
		$href = "<?php echo Yii::app()->createUrl('booking/reschedulebooking') ?>";
		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": $booking_id},
			success: function (data)
			{
				$('#rescheduleBooking').removeClass('fade');
				$('#rescheduleBooking').css('display', 'block');
				$('#rescheduleBookingContent').html(data);
				if (!$('#rescheduleBookingDeatils').hasClass('hide'))
				{
					$('#rescheduleBookingDeatils').addClass("hide");
				}
				$('#rescheduleBookingContent').removeClass("hide");
				$('#rescheduleBookingLabel').html("Reschedule Booking");
				$('#rescheduleBooking').modal('show');
			}
		});
	}

	function checkTripStatus(booking_id)
	{
		$href = "<?php echo Yii::app()->createUrl('booking/CheckTripStatus') ?>";
		var $booking_id = booking_id;
		$.ajax({type: 'GET',
			url: $href,
			data: {"booking_id": $booking_id},
			success: function (data)
			{
				var dt = JSON.parse(data);
				var msg = dt.message;
				if (dt.success == false) {
					var retVal = confirm(msg);
				}
				if (dt.success == true) {
					canBooking($booking_id);
					return true;
				}
				if (retVal == true && dt.success == false) {
					scqBooking($booking_id, msg);
					return true;
				} else {
					// needtoknow();
					return false;
				}
			}
		});
	}

	function gznowtrack(booking_id, hsh)
	{
		$href = "<?php echo Yii::app()->createUrl('gznow') ?>";
		var $booking_id = booking_id;
		window.open($href + "/" + $booking_id + "/" + hsh, '_blank');
	}

	function scqBooking(booking_id, msg) {
		$href = "<?php echo Yii::app()->createUrl('booking/autofurcustomer') ?>";
		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"booking_id": $booking_id, "message": msg},
			success: function (data1)
			{
				data = JSON.parse(data1);
				if (data.success)
				{
					toastr['info']('Your call back request has been generated', {
						closeButton: true,
						tapToDismiss: false,
						timeout: 500000
					});
				}
			}
		});
	}
</script>