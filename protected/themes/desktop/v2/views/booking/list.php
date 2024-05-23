<?php
$bookingStatus = Booking::model()->getUserBookingStatus();
?>
<?php
foreach ($models as $key => $val)
{
	$bkid			 = $val['bkg_id'];
	$bkmodel		 = Booking::model()->findbyPk($bkid);
	$vehicleModel = $bkmodel->bkgBcb->bcbCab->vhcType->vht_model;
	if($bkmodel->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
	{
		$vehicleModel = OperatorVehicle::getCabModelName($bkmodel->bkgBcb->bcb_vendor_id, $bkmodel->bkgBcb->bcb_cab_id);
	}

	$bkgPrefmodel	 = BookingPref::model()->getByBooking($bkid);
	$cancelcharge	 = CancellationPolicyRule::getCharges($bkmodel);
	if ($cancelcharge == '')
	{
		$cancelCharge = round($val['bkg_cancel_charge']);
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
	if ($val['bkg_vht_id'] > 0)
	{
		$vehicleType = VehicleTypes::model()->findByPk($val['bkg_vht_id']);
		$vhtSelected = "-" . $vehicleType->vht_make . " " . $vehicleType->vht_model;
	}
	$cabImg	 = $val['vct_image'];
	$cabType = $val['vct_label'] . " (" . $val["scc_label"] . ")" . $vhtSelected;
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
			<div class="bg-white-box mb30 list-booking">
				<div class="list-heading">
					<div class="row">
						<div class="col-12 col-lg-8">
							<h2 class="font-18 mb0 text-uppercase"><?php echo $route ?></h2>
						</div>
						<div class="col-lg-4 text-right"><a href="<?php echo $payurl; ?>" target="_blank" bkgcode="<?php echo $val['bkg_booking_id'] ?>" title="Booking Detail" role="button"><?php echo $val['bkg_booking_id'] ?></a></div>
					</div>
				</div>
				<div class="row p15">
					<div class="col-12 col-sm-4 col-md-3 text-center">
						<img src="<?php echo '/' . $cabImg ?>" alt="Image Not Found"  class="img-fluid">
						<p class="text-uppercase font-18"><b><?php echo strtoupper($cabType); ?></b></p>
					</div>
					<div class="col-12 col-md-9">
						<div class="row">
							<div class="col-12">
								<div class="row">
									<div class="col-6">
										<b><?php echo ucfirst($firstName) . ' ' . ucfirst($lastName); ?></b><br/>
										<?php
										if ($contactNo != '')
										{
											?>
											<span class="color-gray">Mobile: <?php echo $contactNo; ?></span><br/>
										<?php } ?>
										<?php
										if ($email != '')
										{
											?>
											<span class="color-gray">Email: <?php echo $email; ?></span><br/>
										<?php } ?>
									</div>
									<div class="col-6 mt15">
										<span class="color-gray">Booked on: <?php echo date('d/m/Y', strtotime($val['bkg_create_date'])) . ', ' . date('h:i A', strtotime($val['bkg_create_date'])); ?></span><br/>
										<span class="color-gray">Pickup Date: <?php echo date('d/m/Y', strtotime($val['bkg_pickup_date'])) . ', ' . date('h:i A', strtotime($val['bkg_pickup_date'])); ?></span><br/>
										<?php
										if ($val['bkg_booking_type'] == 2 || $val['bkg_booking_type'] == 3)
										{
											//$cntRoutes = count($val->bookingRoutes) - 1;
											?>
											<span class="color-gray">Return Date:</span> <?php echo date('d/m/Y', strtotime($val['brt_pickup_datetime'])) . ', ' . date('h:i A', strtotime($val['brt_pickup_datetime'])); ?><br/>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="col-12">
								<div class="row">
									<?php
									//if ($val->bkgBcb->bcb_driver_id != '')
									if ($val['bcb_driver_id'] != '')
									{
										?>
										<div class="col-12 col-sm-6">
											<span class="color-gray">Driver</span><br>
											<b><?php echo ucwords($val['bcb_driver_name']); ?></b>
										</div>
									<?php } ?>
									<?php
									if ($val['bcb_cab_id'] != '')
									{
										?>
										<div class="col-12 col-sm-6">
											<span class="color-gray">Cab Number</span><br>
											<b><?php echo strtoupper($val['vhc_number']); ?></b>
										</div>
										<?php
									}
									?>
								</div>
								<div class="row mt20">
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
											<button type="button" class="btn btn-info text-uppercase gradient-green-light font-12   mt15" id="gznowtrack" onclick="gznowtrack(<?php echo $bkid ?>, '<?php echo $hash ?>')" title="Track Booking">Track Booking</button>
											<?php
										}
										if ($val['bkg_status'] == 1 && $date1 > $date2 && $isPromo && $val['bkg_booking_type'] != 6 && $stop == false)
										{
											?>
											<button type="button" class="btn btn-warning border-none text-uppercase mb10" id="verify" onclick="verifyBooking(<?php echo $bkid ?>, '<?php echo $hash ?>')" title="Verify Booking">Verify Booking</button>
										<?php } ?>
										<?php
										if ($val['bkg_status'] == 1 && $date1 < $date2)
										{
											?>
											<div style="cursor: text;" class="btn btn-danger gozo_red border-none text-uppercase mb10"  title="Expired">Expired</div>
										<?php } ?>
										<?php
										if ((($val['bkg_status'] == 15 || $val['bkg_status'] == 2 || $val['bkg_status'] == 3 || $val['bkg_status'] == 5) && ($val['bkg_due_amount'] > 0 || $val['bkg_due_amount'] == '')) || (!$isPromo && $val['bkg_status'] == 1 && $date1 > $date2))
										{
											?>
											<a href="<?php echo $payurl ?>" class="btn btn-primary text-uppercase gradient-green-blue font-12 border-none mt15" target="_blank"  id="payment" title="Payment">Make payment</a>
										<?php } ?>
										<?php
										if ($val['bkg_status'] == 15 || $val['bkg_status'] == 2 || $val['bkg_status'] == 3 || $val['bkg_status'] == 5)
										{
											?>
											<!--<button type="button" class="btn btn-primary text-uppercase bg-red font-12 border-none mt15" id="cancel" onclick="canBooking(<?php echo $bkid ?>)" title="Cancel Booking">Cancel Booking</button>-->
											<button type="button" class="btn btn-primary text-uppercase bg-red font-12 border-none mt15" id="cancel<?php echo $bkid ?>" onclick="checkTripStatus(<?php echo $bkid ?>)" title="Cancel Booking">Cancel Booking</button>
											<?php
										}
										$date = date('Y-m-d H:i:s');
										if (($val['bkg_status'] == 2 || $val['bkg_status'] == 15) && $val['bkg_pickup_date'] > $date)
										{
											?>
											<button type="button" class="btn btn-primary text-uppercase bg-yellow font-12 border-none mt15" id="modify" onclick="modify(<?php echo $bkid ?>)" title="Edit Booking"><i class="fas fa-pencil-alt"></i> Edit</button>
										<?php } ?>
										<?php
										$pickupDate	 = date('Y-m-d H:i:s', strtotime($val['bkg_pickup_date']));
										$pickupTime	 = new DateTime($pickupDate);
										if ($val['bkg_status'] == 2 && $pickupTime > $date2 && $bkgPrefmodel['bkg_critical_score'] <= 0.65)
										{
											?>
											<button type="button" class="btn btn-primary text-uppercase gradient-blue-darkblue font-12 border-none mt15" id="reschedule" onclick="reschedule(<?php echo $bkid ?>)" title="Reschedule Pickup Time">Reschedule Pickup Time</button>
										<?php } ?>
										<?php
										if ($val['bkg_status'] == 6 || $val['bkg_status'] == 7)
										{
											?>
											<button type="button" class="btn btn-primary text-uppercase bg-orange font-12 border-none" id="showreview" onclick="receipt(<?php echo $bkid ?>, '<?php echo Yii::app()->shortHash->hash($bkid) ?>')" title="Invoice">Invoice</button>
											<?php
											if ($isRating != false)
											{
												?>
												<button type="button" class="btn btn-primary text-uppercase bg-blue2 font-12 border-none" id="showreview" onclick="showreview(<?php echo $bkid ?>)" title="Reviewed">Show Review</button>
												<?php
											}
											else
											{
												?>
												<a class="btn btn-primary text-uppercase bg-blue2 font-12 border-none" target="_blank" href="<?php echo $link ?>">Review</a>
												<!--<button type="button" class="btn btn-warning border-none text-uppercase mb10" id="review" onclick="ratetheJourney(<? //= $bkid      ?>)" title="Review">Review</button>-->
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
							<div class="row">
								<div class="col-6">Advance Paid: &#x20B9;<b><?php echo round($val['bkg_advance_amount']) ?></b></div>
								<?php
								if ($creditsused > 0)
								{
									?>
									<div class="col-6 col-md-3">Credit Used: &#x20B9;<b><?php echo round($creditsused) ?></b></div>
								<?php } ?>
								<div class="col-6 text-right">Due Amount: &#x20B9;<b><?php echo round($val['bkg_due_amount']) ?></b></div>
								<?php
								if ($cancelcharge > 0)
								{
									?>  <div class="col-6 col-md-4">Cancellation Charge: &#x20B9;<b><?php echo $cancelcharge; ?></b></div><?php } ?>
								<!--							if ($val['bkg_cancel_charge'] > 0)
															{
																?>  <div class="col-6 col-md-4">Cancellation Charge: &#x20B9;<b><?php //echo round($val['bkg_cancel_charge'])        ?></b></div><?php //}        ?> -->
								<?php
								if ($val['bkg_refund_amount'] > 0)
								{
									?> <div class="col-6 col-md-4">Refund: &#x20B9;<b><?php echo round($val['bkg_refund_amount']) ?></b></div>
									<div class="col-12 col-md-4"> Payment Transaction ID: <b>
											<?php echo PaymentGateway::getTXNIDbyBkgId($val['bkg_id'], 1);
											?>
										</b></div>
		<?php } ?>
							</div>
						</div>
					<?php }
					?>
				</div>
				<div class="list-footer">
					<div class="row">
						<div class="col-12 col-sm-6"><p class="text-uppercase font-18 mb0">Status: <span class="<?php echo ($val['bkg_status'] <= 7) ? 'color-green' : 'red-text-color'; ?>"><?php echo $bookingStatus[$val['bkg_status']]; ?></span></p></div>
						<div class="col-12 col-sm-6 text-right font-18">
							Total amount: <b>&#x20B9;<?php
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
								?></b></div>
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
<div class="col-12 ml15 mt40 text-right">
	<?php
	$this->widget('booster.widgets.TbPager', array('pages' => $usersList->pagination));
	?>
</div>
<div class="modal fade" id="editBookingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBookingModalLabel">Edit Booking</h5>
                <button type="button" class="close mt30 n pt0" data-dismiss="modal" aria-label="Close">
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
                <h5 class="modal-title" id="cancelBookingModalLabel">Cancel Booking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                <h5 class="modal-title" id="viewBookingModalLabel">Booking Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="viewBookingModelContent">
                <div class="row"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="reviewBookingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewBookingModalLabel">Review</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="reviewBookingModelContent">
                <div class="row"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="rescheduleBooking" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rescheduleBookingLabel">Reschedule Pickup Time</h5>
                <button type="button" class="close mt30 n pt0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="rescheduleBookingContent">
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

	function checkTripStatus(booking_id)
	{
		$href = "<?php echo Yii::app()->createUrl('booking/CheckTripStatus') ?>";
		var $booking_id = booking_id;
		$("#cancel"+booking_id).prop('disabled',true);
		$.ajax({type: 'GET',
			url: $href,
			data: {"booking_id": $booking_id},
			success: function (data)
			{
				var dt = JSON.parse(data);
				var msg = dt.message;
				var retVal = confirm(msg);
				$("#cancel"+booking_id).prop('disabled',false);
				if (retVal == true) {
					canBooking($booking_id);
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
</script>