<style>
    .list_booking{
        /**background: #fff;
        -webkit-box-shadow: 0px 0px 17px 0px rgba(0,0,0,0.21);
        -moz-box-shadow: 0px 0px 17px 0px rgba(0,0,0,0.21);
        box-shadow: 0px 0px 17px 0px rgba(0,0,0,0.21);**/
        border: #e9e9e9 1px solid;
        margin: 20px 0 0 0;
    }
    .list_heading{ background: #EFEFEF; overflow: hidden;}
    .gray-color{ color: #848484;}
    .gozo_green{ background: #48b9a7;}
    .gozo_bluecolor{ color: #0766bb;}
    .gozo_greencolor{ color:#48b9a7;}
    .gozo_red{ background: #f34747;}
    .text_right{ text-align: right;}
    .margin_top{ margin-top: 40px;}
    .car_img{ overflow: hidden;}
    .car_img img{ width: 100%;}
    @media (max-width: 768px) {
        .text_right{ text-align: center;}
        .margin_top{ margin-top: 10px;}
    }
</style>
<?php
$bookingStatus = Booking::model()->getUserBookingStatus();
?>
<?php
foreach ($models as $key => $val)
{
	//print'<pre>';print_r($val);
	$bkid		 = $val['bkg_id'];
	$bkmodel		 = Booking::model()->findbyPk($bkid);
	$vehicleModel = $bkmodel->bkgBcb->bcbCab->vhcType->vht_model;
	if($bkmodel->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
	{
		$vehicleModel = OperatorVehicle::getCabModelName($bkmodel->bkgBcb->bcb_vendor_id, $bkmodel->bkgBcb->bcb_cab_id);
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
		<div class="col-xs-12">
			<div class="list_booking">
				<div class="list_heading">
					<div class="row">
						<div class="col-xs-12 col-sm-8">
							<h3 class="mt15 pl15 text-uppercase">
								<?php echo  $route ?>
							</h3>
						</div>
						<div class="hidden-xs col-sm-4 col-md-4 text_right mt10 mb10"><a class="btn comm-btn mr10" onclick="return viewBooking(this)" href="<?php echo  Yii::app()->createUrl('booking/view', array('bookingID' => $val['bkg_id'])) ?>" bkgcode="<?php echo  $val['bkg_booking_id'] ?>" title="Booking Detail" role="button"><?php echo  Filter::formatBookingId($val['bkg_booking_id']); ?></a></div>
					</div>
				</div>
				<div class="row p15">
					<div class="col-xs-12 col-sm-3 col-md-2 text-center car_img">
						<figure><img src="<?php echo  '/' . $cabImg ?>" alt="Image Not Found"></figure>
						<h5 class="gray-color"><?php echo  $cabType; ?></h5>
					</div>
					<div class="col-xs-12 col-sm-5 col-md-5">
						<b><?php echo  ucfirst($firstName) . ' ' . ucfirst($lastName); ?></b><br/>
						<?
						if ($contactNo != '')
						{
							?>
							<span class="gray-color">Mobile:</span> <?php echo  $contactNo; ?><br/>
						<? } ?>
						<?
						if ($email != '')
						{
							//$email = ContactEmail::model()->getEmailByBookingUserId($val['bui_id']);
							?>
							<span class="gray-color">Email:</span> <?php echo  $email; ?><br/>
						<? } ?>
						<span class="gray-color">Booked on:</span> <?php echo  date('d/m/Y', strtotime($val['bkg_create_date'])) . ', ' . date('h:i A', strtotime($val['bkg_create_date'])); ?><br/>
						<span class="gray-color">Pickup Date:</span> <?php echo  date('d/m/Y', strtotime($val['bkg_pickup_date'])) . ', ' . date('h:i A', strtotime($val['bkg_pickup_date'])); ?><br/>
						<?
						if ($val['bkg_booking_type'] == 2 || $val['bkg_booking_type'] == 3)
						{
							//$cntRoutes = count($val->bookingRoutes) - 1;
							?>
							<span class="gray-color">Return Date:</span> <?php echo  date('d/m/Y', strtotime($val['brt_pickup_datetime'])) . ', ' . date('h:i A', strtotime($val['brt_pickup_datetime'])); ?><br/>
						<? } ?>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-5">
						<div class="row">
							<?
							//if ($val->bkgBcb->bcb_driver_id != '')
							if ($val['bcb_driver_id'] != '')
							{
								?>
								<div class="col-xs-12 col-sm-6">
									<span class="gray-color">Driver</span><br>
									<b><?php echo  ucwords($val['bcb_driver_name']); ?></b>
								</div>
							<? } ?>
							<?
							if ($val['bcb_cab_id'] != '')
							{
								?>
								<div class="col-xs-12 col-sm-6">
									<span class="gray-color">Cab Number</span><br>
									<b><?php echo  strtoupper($val['vhc_number']); ?></b>
								</div>
								<?
							}
							?>
						</div>
						<div class="row margin_top text_right">
							<div class="col-xs-12">
								<?
								$date1	 = date('Y-m-d H:i:s', strtotime($val['bkg_pickup_date'] . '- 120 minute'));
								$date1	 = new DateTime($date1);
								$date2	 = date('Y-m-d H:i:s');
								$date2	 = new DateTime($date2);
								$stop	 = true;
								$isPromo = BookingSub::model()->getApplicable($val['bkg_from_city_id'], $val['bkg_to_city_id'], 3);
								if ($val['bkg_status'] == 1 && $date1 > $date2 && $isPromo && $val['bkg_booking_type'] != 6 && $stop == false)
								{
									?>
									<button type="button" class="btn btn-warning border-none text-uppercase mb10" id="verify" onclick="verifyBooking(<?php echo  $bkid ?>, '<?php echo  $hash ?>')" title="Verify Booking">Verify Booking</button>
								<? } ?>
								<?
								if ($val['bkg_status'] == 1 && $date1 < $date2)
								{
									?>
									<div style="cursor: text;" class="btn btn-danger gozo_red border-none text-uppercase mb10"  title="Expired">Expired</div>
								<? } ?>
								<?
								if ((($val['bkg_status'] == 15 || $val['bkg_status'] == 2 || $val['bkg_status'] == 3 || $val['bkg_status'] == 5) && ($val['bkg_due_amount'] > 0 || $val['bkg_due_amount'] == '')) || (!$isPromo && $val['bkg_status'] == 1 && $date1 > $date2))
								{
									?>
									<a href="<?php echo  $payurl ?>" class="white-color btn btn-success gozo_green border-none text-uppercase mb10" target="_blank"  id="payment" title="Payment">Make payment</a>
								<? } ?>
								<?
								if ($val['bkg_status'] == 15 || $val['bkg_status'] == 2 || $val['bkg_status'] == 3 || $val['bkg_status'] == 5)
								{
									?>
									<button type="button" class="btn btn-danger gozo_red border-none text-uppercase mb10" id="cancel" onclick="canBooking(<?php echo  $bkid ?>)" title="Cancel Booking">Cancel Booking</button>
								<? } ?>
								<?
								if (($val['bkg_status'] == 1) && $date1 > $date2)
								{
									?>
									<button type="button" class="btn btn-danger gozo_red border-none text-uppercase mb10" id="cancel" onclick="canBooking(<?php echo  $bkid ?>)" title="Cancel Booking">Cancel Booking</button>
								<? } ?>
								<?
								$date = date('Y-m-d H:i:s');
								if (($val['bkg_status'] == 1 || $val['bkg_status'] == 2 || $val['bkg_status'] == 15) && $val['bkg_pickup_date'] > $date)
								{
									?>
									<button type="button" class="btn btn-warning border-none text-uppercase mb10" id="modify" onclick="modify(<?php echo  $bkid ?>)" title="Edit Booking">Edit</button>
								<? } ?>
								<?
								if ($val['bkg_status'] == 6 || $val['bkg_status'] == 7)
								{
									?>
									<button type="button" class="btn comm-btn border-none text-uppercase mb10" id="showreview" onclick="receipt(<?php echo  $bkid ?>, '<?php echo  Yii::app()->shortHash->hash($bkid) ?>')" title="Invoice">Invoice</button>
									<?
									if ($isRating != false)
									{
										?>
										<button type="button" class="btn comm2-btn border-none text-uppercase mb10" id="showreview" onclick="showreview(<?php echo  $bkid ?>)" title="Reviewed">Show Review</button>
										<?
									}
									else
									{
										?>
										<a class="btn btn-warning border-none text-uppercase mb10" target="_blank" href="<?php echo  $link ?>">Review</a>
										<!--<button type="button" class="btn btn-warning border-none text-uppercase mb10" id="review" onclick="ratetheJourney(<? //= $bkid          ?>)" title="Review">Review</button>-->
										<?
									}
								}
								?>    
							</div>
						</div>

					</div>
					<?
					//if ($val->bkg_advance_amount > 0)
					if ($val['bkg_advance_amount'] > 0)
					{
						?>
						<div class="row">
							<div class="col-xs-12  pt10 pb10">
								<div class="col-xs-6 col-md-3">Advance Paid: <b style="font-size: 1.2em; color: #48b9a7;padding-left: 5px"><i class="fa fa-inr"></i><?php echo  round($val['bkg_advance_amount']) ?></b></div>
								<?php
								if ($creditsused > 0)
								{
									?>
									<div class="col-xs-6 col-md-3">Credit Used: <b style="font-size: 1.2em; color: #48b9a7;padding-left: 5px"><i class="fa fa-inr"></i><?php echo  round($creditsused) ?></b></div>
								<?php } ?>	
								<div class="col-xs-6 col-md-3">Due Amount: <b style="font-size: 1.2em;color: #48b9a7;padding-left: 5px"><i class="fa fa-inr"></i><?php echo  round($val['bkg_due_amount']) ?></b></div>
								<?
								if ($val['bkg_cancel_charge'] > 0)
								{
									?>  <div class="col-xs-6 col-md-3">Cancellation Charge: <b style="font-size: 1.2em;color: #48b9a7;padding-left: 5px"><i class="fa fa-inr"></i><?php echo  round($val['bkg_cancel_charge']) ?></b></div><? } ?> 
										<?php
										if ($val['bkg_refund_amount'] > 0)
										{
											?> <div class="col-xs-6 col-md-3 ">Refund: <b style="font-size: 1.2em;color: #48b9a7;padding-left: 5px"><i class="fa fa-inr"></i><?php echo  round($val['bkg_refund_amount']) ?></b></div>
									<div class="col-xs-12 col-md-6"> Payment Transaction ID: <b style="font-size: 1.2em;color: #48b9a7;padding-left: 5px">
											<?php echo  PaymentGateway::getTXNIDbyBkgId($val['bkg_id'], 1);
											?>

										</b></div>
								<? } ?>
							</div>
						</div>
					<? }
					?>
					<!--
					if ($val->bkg_refund_amount > 0) { ?>
								<div class="col-xs-10 table-responsive"><label>Refund Details</label>
									<table class="table border-gray">
										<thead>
										<th>Transaction ID</th>
									 
										<th>Amount</th>
										<th>Description</th>
										<th>Date</th>
										</thead>
										<tbody>
					<?
//                                    $transactionModel = Transactions::model()->findAll('trans_booking_id=:booking AND trans_status=1 AND trans_mode=1', ['booking' => $val->bkg_id]);
//                                    foreach ($transactionModel as $value) {
					?>
												<tr>
													<td><? //= $value->trans_txn_id            ?></td>                             
													<td><? //=abs($value->trans_amount)            ?></td>
													<td><? //= $value->trans_response_message           ?></td>
													<td><? //=date("d/m/Y H:i A",  strtotime($value->trans_complete_datetime));            ?></td>
												</tr>
					<? //}    ?>
										</tbody>
									</table>
								</div>
					<? //}     ?> -->

				</div>
				<div class="list_heading">
					<div class="row">
						<div class="col-xs-12 col-sm-4"><h5 class="mt15 pl15 text-uppercase">Status: <span class="<?php echo  ($val['bkg_status'] <= 7) ? 'gozo_greencolor' : 'red-color'; ?>"><?php echo  $bookingStatus[$val['bkg_status']]; ?></span></h5></div>
						<div class="col-xs-12 col-sm-8 text_right mt10 mb10 pr30">Total amount: <b style="font-size: 20px; color: #48b9a7;padding-left: 5px"><i class="fa fa-inr"></i>
								<?
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
						<div class="col-xs-12 hidden-sm hidden-md hidden-lg text_right mt10 mb10"><a class="btn btn-primary mr10" onclick="return viewBooking(this)" href="<?php echo  Yii::app()->createUrl('booking/view', array('bookingID' => $val['bkg_id'])) ?>" bkgcode="<?php echo  $val['bkg_booking_id'] ?>"   title="Booking Detail" role="button"><?php echo  Filter::formatBookingId($val['bkg_booking_id']); ?></a></div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?
if (empty($models))
{
	?>
	<div class="row">
		<div class="col-xs-12">
			<div class="list_booking">
				<div class="list_heading text-center pt20 pb20" style="background: #f77026; color: #fff;">
					<b>Sorry!! No records found</b>
				</div>            
			</div>
		</div>
	</div>  
<? } ?>
<div class="col-xs-12 ml15 mt40 text-right">
	<?php
	$this->widget('booster.widgets.TbPager', array('pages' => $usersList->pagination));
	?>
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
        $href = "<?php echo  Yii::app()->createUrl('booking/canbooking') ?>";
        var $booking_id = booking_id;
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"booking_id": $booking_id},
            success: function (data)
            {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Cancel Booking',
                    onEscape: function () {
                    },
                });
            }
        });
    }
    function viewBooking(obj) {
        var href2 = $(obj).attr("href");
        var bcode = $(obj).attr("bkgcode");

        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Booking Details for ' + bcode,
                    onEscape: function () {
                    },
                });
            }
        });
        return false;
    }
    function ratetheJourney(booking_id) {
        $href = "<?php echo  Yii::app()->createUrl('rating/addreview') ?>";
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
        $href = "<?php echo  Yii::app()->createUrl('rating/showreview') ?>";
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
    function receipt(booking_id, hsh)
    {
        $href = "<?php echo  Yii::app()->createUrl('booking/receipt') ?>";
        var $booking_id = booking_id;
        window.open($href + "/bkg/" + $booking_id + "/hsh/" + hsh, '_blank');
    }
    function verifyBooking(booking_id, hash) {
        $href = "<?php echo  Yii::app()->createUrl('booking/verifybooking') ?>";
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
        var href1 = '<?php echo  Yii::app()->createUrl('booking/confirmmobile') ?>';
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
<? /* /?>
  function edit(booking_id) {
  $href = "<?php echo  Yii::app()->createUrl('booking/edit') ?>";
  var $booking_id = booking_id;
  jQuery.ajax({type: 'GET',
  url: $href,
  data: {"bkg_id": $booking_id},
  success: function (data)
  {
  var box = bootbox.dialog({
  message: data,
  title: 'Edit Booking',
  onEscape: function () {
  },
  });
  }
  });
  } <?/ */ ?>
    function modify(booking_id) {
        $href = "<?php echo  Yii::app()->createUrl('booking/editnew') ?>";
        var $booking_id = booking_id;
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"bkg_id": $booking_id},
            success: function (data)
            {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Edit Booking',
                    onEscape: function () {
                    },
                });
            }
        });
    }
</script>