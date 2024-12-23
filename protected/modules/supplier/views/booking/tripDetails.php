
<?php
	$model = Booking::model()->find("bkg_bcb_id=:id",["id"=>$booking['bcb_id']]);

$lastRoute		 = $model->bookingRoutes[count($model->bookingRoutes) - 1];
$startTripDate	 = $model->bookingRoutes[0]->brt_pickup_datetime;
if ($lastRoute->brt_return_date_date == NULL || $lastRoute->brt_return_date_date == '1970-01-01')
{
	$endDate = new DateTime($lastRoute->brt_pickup_datetime);
	if ($model->bkg_booking_type == 9)
	{
		$lastRoute->brt_trip_duration = 240;
	}
	if ($model->bkg_booking_type == 10)
	{
		$lastRoute->brt_trip_duration = 480;
	}
	if ($model->bkg_booking_type == 11)
	{
		$lastRoute->brt_trip_duration = 720;
	}
	if ($lastRoute->brt_trip_duration)
	{
		$endDate->add(new DateInterval('PT' . $lastRoute->brt_trip_duration . 'M'));
	}
	$endTripDate = $endDate->format('Y-m-d H:i:s');
}
else
{
	$endTripDate = $lastRoute->brt_return_datetime;
}

$quote							 = new Quote();
$quote->routeDuration			 = new RouteDuration();
$quote->routeDuration->fromDate	 = $startTripDate;
$quote->routeDuration->toDate	 = $endTripDate;
$quote->routeDuration->calculate();
$totaldays						 = $quote->routeDuration->durationInWords;
$model->bkgTrack->bkg_is_trip_verified 
?>

<div class="app-content content">
	<div class="content-overlay"></div>
	<div class="content-wrapper">
		<div class="content-body">
			<section id="dashboard-analytics">
				<div class="container">
					<?php if ($booking != '')
					{ ?>
						<div class="row d-flex justify-content-center">
							<div class="col-12 mob-view">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<div class="col-12 col-lg-6 col-xl-6">
												<p class="mb0"><span>Trip id:</span> <b><?= $booking['bcb_id'] ?></b></p>
												<div>
													<form class="form-horizontal" enctype="multipart/form-data" id="bookingsavedaddress" action="booking/address" method="post">
														

														<ul class="timeline mb-0">
															<?php
															$routes = \Beans\booking\Route::setDataByBooking($model->bkg_id);

															$routeDetails = "";
															foreach ($routes as $key => $v)
															{
																$routeDetails .= "<li class='timeline-item active pb5' >" . $v->source->city->name . "<br><span class=\"text-muted font-12\">" . $v->source->address . '</span></li> ';
															}
															$routeDetails .= "<li class='timeline-item2 active pb5' >" . $v->destination->city->name . "<br><span class=\"text-muted font-12\">" . $v->destination->address . '</span></li> ';

															echo $routeDetails;
															?>
														</ul>
													</form>
												</div>

											</div>
											<div class="col-12 col-lg-6 col-xl-6">
												<p class="text-right mb5">Total duration: <b><?php echo $totaldays; ?></b></p>
												<div class="card card-r1 bg-green mb10 float-right">
													<div class="card-body p10">
														<div class="align-items-center">
															<?php foreach ($routes as $key => $v)
															{
																?>
																<div class="user-analytics mr10 ml10 pb10">
																	<div class="row m0">
																		<div class="col-12 p0 line-height-14"><span class="align-middle"><span class="font-10"><?php
																					$date			 = new DateTime($v->pickupTime);
																					$formattedDate	 = $date->format('F j, Y');
																					echo $formattedDate;
																					?></span><br><b><?= $date->format('l'); ?></b><br><span class="font-10"><?= $date->format('h:i A'); ?></span></span></div>
																	</div>
																</div>
																<div class="sessions-analytics pt10">
																	<img src="/images/icon4.svg" alt="" width="10" >
																</div>
																<?}?>
																<div class="user-analytics mr10 ml10 pb10">
																	<div class="row m0">
																		<div class="col-12 p0 line-height-14"><span class="align-middle"><span class="font-10"><?php
																					$date			 = new DateTime($v->endTime);
																					$formattedDate	 = $date->format('F j, Y');
																					echo $formattedDate;
																					?></span><br><b><?= $date->format('l'); ?></b><br><span class="font-10"><?= $date->format('h:i A'); ?></span></span></div>
																	</div>
																</div>
															</div>
														</div>
													</div>

												</div>

											</div>
											<div class="row mt15">
												<div class="col-12 col-lg-9 col-xl-9">
													<div class="row">
														<div class="col-12">
															<div class="d-inline-block mb10">
																<div class="d-flex market-statistics-1 mr10" style="position: relative;">
																	<div class="p5"><i class='bx bx-car font-24 text-muted'></i></div>
																	<div class="statistics-data my-auto">
																		<div class="statistics-date"><small>Cab</small></div>
																		<div class="statistics line-height-16"><span class="mr-50 text-bold-600"><?= $booking['scv_label'] ?></span></div>
																	</div>
																	<div class="resize-triggers"><div class="expand-trigger"><div style="width: 224px; height: 55px;"></div></div><div class="contract-trigger"></div></div></div>
															</div>
															<div class="d-inline-block mb10">
																<div class="d-flex market-statistics-1 mr10" style="position: relative;">
																	<div class="p5"><i class='bx bx-credit-card-front font-24 text-muted'></i></div>
																	<div class="statistics-data my-auto">
																		<div class="statistics-date"><small>Cab number</small></div>
																		<div class="statistics line-height-16"><span class="mr-50 text-bold-600"><?= $booking['bcb_cab_number'] ?></span></div>
																	</div>
																	<div class="resize-triggers"><div class="expand-trigger"><div style="width: 224px; height: 55px;"></div></div><div class="contract-trigger"></div></div></div>
															</div>
															<div class="d-inline-block mb10">
																<div class="d-flex market-statistics-1 mr10" style="position: relative;">
																	<div class="p5"><i class='bx bx-user font-24 text-muted'></i></div>
																	<div class="statistics-data my-auto">
																		<div class="statistics-date"><small>Driver name</small></div>
																		<div class="statistics line-height-16"><span class="mr-50 text-bold-600"><?= $booking['drv_name'] ?></span></div>
																	</div>
																	<div class="resize-triggers"><div class="expand-trigger"><div style="width: 224px; height: 55px;"></div></div><div class="contract-trigger"></div></div></div>
															</div>
															<div class="d-inline-block mb10">
																<div class="d-flex market-statistics-1 mr10" style="position: relative;">
																	<div class="p5"><i class='bx bx-phone-call font-24 text-muted'></i></div>
																	<div class="statistics-data my-auto">
																		<div class="statistics-date"><small>Driver's phone number</small></div>
																		<div class="statistics line-height-16"><span class="mr-50 text-bold-600"><?= $booking['bcb_driver_phone'] ?></span></div>
																	</div>
																	<div class="resize-triggers"><div class="expand-trigger"><div style="width: 224px; height: 55px;"></div></div><div class="contract-trigger"></div></div></div>
															</div>
														</div>
													</div>
												</div>
												<?php if(!in_array($booking['bkg_status'],[2,9])){?>
												<div class="col-12 col-lg-3 col-xl-3">
													<div class="row">
														<div class="col-6 text-right pr0">Vendor amount<br><span class="font-24"><b>&#x20b9;<?= $booking['bkg_vendor_amount'] ?></b></span></div>
														<div class="col-6 text-right pl5">Amount to collect<br><span class="font-24"><b>&#x20b9;<?= $booking['bkg_vendor_collected'] ?></b></span></div>
													</div>
												</div>
												<?}?>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12 mob-view">
									<div class="card">
										<div class="card-header pb10">
											<h4 class="card-title"><?php $arrScv			 = explode('(', $booking['scv_label']);
																			echo $arrScv[0]; ?> <div class="badge badge-pill badge-warning mr-1 font-10"><?= str_replace(")", "", $arrScv[1]) ?></div></h4>
										</div>
										<div class="card-body">
											<div class="badge badge-pill badge-light-primary mr-1 mb-1"><?php echo ($booking['is_agent']==1)?"B2B":$booking['isb2b']; ?></div>
											<div class="badge badge-pill badge-light-success mr-1 mb-1"><?=($model->bkgTrack->bkg_is_trip_verified == 1)?"OTP VERIFIED":""?></div>
											<div class="badge badge-pill badge-light-danger mb-1"><?php echo ($model->bkgPref->bkg_driver_app_required == 1) ? 'Driver app is required' : '' ?></div>
											<div class="row">
												<div class="col-12">
													<div class="d-inline-block mb10">
														<div class="d-flex market-statistics-1 mr10" style="position: relative;">
															<div class="p5"><i class='bx bx-map font-24 text-muted'></i></div>
															<div class="statistics-data my-auto">
																<div class="statistics-date"><small>Trip type</small></div>
																<div class="statistics line-height-16"><span class="mr-50 text-bold-600 font-12"><?php echo Booking::model()->booking_type[$booking['bkg_booking_type']] ?></span></div>
															</div>
															<div class="resize-triggers"><div class="expand-trigger"><div style="width: 224px; height: 55px;"></div></div><div class="contract-trigger"></div></div></div>
													</div>
													<div class="d-inline-block mb10">
														<div class="d-flex market-statistics-1 mr10" style="position: relative;">
															<div class="p5"><i class='bx bx-credit-card-front font-24 text-muted'></i></div>
															<div class="statistics-data my-auto">
																<div class="statistics-date"><small>Booking id</small></div>
																<div class="statistics line-height-16 font-12"><span class="mr-50 text-bold-600"><?=$model->bkg_booking_id?></span></div>
															</div>
															<div class="resize-triggers"><div class="expand-trigger"><div style="width: 224px; height: 55px;"></div></div><div class="contract-trigger"></div></div></div>
													</div>
													<div class="d-inline-block mb10">
														<div class="d-flex market-statistics-1 mr10" style="position: relative;">
															<div class="p5"><i class="bx bx-tachometer font-24 text-muted"></i></div>
															<div class="statistics-data my-auto">
																<div class="statistics-date"><small>KM limit</small></div>
																<div class="statistics line-height-16 font-12"><span class="mr-50 text-bold-600"><?=$model->bkg_trip_distance?> Km</span></div>
															</div>
															<div class="resize-triggers"><div class="expand-trigger"><div style="width: 224px; height: 55px;"></div></div><div class="contract-trigger"></div></div></div>
													</div>
													<div class="d-inline-block mb10">
														<div class="d-flex market-statistics-1 mr10" style="position: relative;">
															<div class="p5"><i class='bx bx-bolt-circle font-24 text-muted'></i></div>
															<div class="statistics-data my-auto">
																<div class="statistics-date"><small>Extra km charge</small></div>
																<div class="statistics line-height-16 font-12"><span class="mr-50 text-bold-600"><?=$model->bkgInvoice->bkg_rate_per_km_extra?></span></div>
															</div>
															<div class="resize-triggers"><div class="expand-trigger"><div style="width: 224px; height: 55px;"></div></div><div class="contract-trigger"></div></div></div>
													</div>
													<div class="d-inline-block mb10">
														<div class="d-flex market-statistics-1 mr10" style="position: relative;">
															<div class="p5"><i class='bx bx-receipt font-24 text-muted'></i></div>
															<div class="statistics-data my-auto">
																<div class="statistics-date"><small>Toll tax</small></div>
																<div class="statistics line-height-16 font-12"><span class="mr-50 text-bold-600"><?= ($model->bkgInvoice->bkg_is_toll_tax_included == 1) ? 'Included' : 'Not Included' ?> &#x20b9;<?=$model->bkgInvoice->bkg_toll_tax?></span></div>
															</div>
															<div class="resize-triggers"><div class="expand-trigger"><div style="width: 224px; height: 55px;"></div></div><div class="contract-trigger"></div></div></div>
													</div>
													<div class="d-inline-block mb10">
														<div class="d-flex market-statistics-1 mr10" style="position: relative;">
															<div class="p5"><i class='bx bx-receipt font-24 text-muted'></i></div>
															<div class="statistics-data my-auto">
																<div class="statistics-date"><small>State tax</small></div>
																<div class="statistics line-height-16 font-12"><span class="mr-50 text-bold-600"><?= ($model->bkgInvoice->bkg_is_state_tax_included == 1) ? 'Included' : 'Not Included' ?> &#x20b9;<?=$model->bkgInvoice->bkg_state_tax?></span></div>
															</div>
															<div class="resize-triggers"><div class="expand-trigger"><div style="width: 224px; height: 55px;"></div></div><div class="contract-trigger"></div></div></div>
													</div>
													<div class="d-inline-block mb10">
														<div class="d-flex market-statistics-1 mr10" style="position: relative;">
															<div class="p5"><i class='bx bx-user-circle font-24 text-muted'></i></div>
															<div class="statistics-data my-auto">
																<div class="statistics-date"><small>Driver allowance</small></div>
																<div class="statistics line-height-16 font-12"><span class="mr-50 text-bold-600"><?= ($model->bkgInvoice->bkg_night_pickup_included == 1 || $model->bkgInvoice->bkg_night_drop_included == 1) ? 'Included' : 'Not Included' ?> &#x20b9;<?=$model->bkgInvoice->bkg_driver_allowance_amount?></span></div>
															</div>
															<div class="resize-triggers"><div class="expand-trigger"><div style="width: 224px; height: 55px;"></div></div><div class="contract-trigger"></div></div></div>
													</div>
													<div class="d-inline-block mb10">
														<div class="d-flex market-statistics-1 mr10" style="position: relative;">
															<div class="p5"><i class='bx bx-bolt-circle font-24 text-muted'></i></div>
															<div class="statistics-data my-auto">
																<div class="statistics-date"><small>Parking charge</small></div>
																<div class="statistics line-height-16 font-12"><span class="mr-50 text-bold-600"><?= ($model->bkgInvoice->bkg_is_parking_included == 1) ? 'Included' : 'Not Included' ?> &#x20b9;<?=$model->bkgInvoice->bkg_parking_charge?></span></div>
															</div>
															<div class="resize-triggers"><div class="expand-trigger"><div style="width: 224px; height: 55px;"></div></div><div class="contract-trigger"></div></div></div>
													</div>
													<div class="d-inline-block mb10">
														<div class="d-flex market-statistics-1 mr10" style="position: relative;">
															<div class="p5"><i class='bx bx-bolt-circle font-24 text-muted'></i></div>
															<div class="statistics-data my-auto">
																<div class="statistics-date"><small>Airport Entry fee</small></div>
																<div class="statistics line-height-16 font-12"><span class="mr-50 text-bold-600"><?= ($model->bkgInvoice->bkg_is_airport_fee_included == 1) ? 'Included' : 'Not Included' ?> &#x20b9;<?= ($model->bkgInvoice->bkg_airport_entry_fee > 0) ? $model->bkgInvoice->bkg_airport_entry_fee : 0; ?></span></div>
													</div>
													<div class="resize-triggers"><div class="expand-trigger"><div style="width: 224px; height: 55px;"></div></div><div class="contract-trigger"></div></div></div>
											</div>
										</div>
									</div>
								
									<div class="row pull-right">
									<?php if($model->bkg_status==2){?>
										<?php if($booking['is_biddable']==0){?><button class="btn btn-success btn-sm m5" data-toggle ="modal" data-target ="#acceptBid">ACCEPT AT &#x20B9;<?=$booking['acptAmount']?></button><?}?>
										<?php if($booking['is_biddable']==0 || $booking['is_biddable']==1){?><button class="btn btn-secondary btn-sm m5" data-toggle ="modal" data-target ="#default">BID</button><?}?>
										<?php if($booking['is_biddable']==0 || $booking['is_biddable']==1){?><button class="btn btn-danger btn-sm m5"  onclick="checkBid(0);">DENY</button><?}?>
									<?}?>
									<?php if($model->bkg_status==3){?>
										<a href="<?= Yii::app()->createUrl('supplier/booking/assigncab?id='.$booking['bcb_id'])?>"><button class="btn btn-success btn-sm m5">Assign Cab</button></a>
									<?}?>
									</div>
								
								</div>
							</div>
						</div>
					</div>
					<?}else{?>
					<div class="text-center">Invalid Data</div>
					<?}?>
				</div>
			</section>
		</div>
	</div>
</div>

<div class="modal modal-borderless text-left" id="default" tabindex="-1" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="false">
		<div class="modal-dialog modal-dialog-scrollable">
			<div class="modal-content">
			<form id="bidform">
				<div class="modal-header">
					<h3 class="modal-title" id="myModalLabel1">Bid your best price</h3>
					<button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
						<i class="bx bx-x"></i>
					</button>
 
				</div>
				<div class="modal-body">
					<div class="col-12">
					    <input type="hidden" name="YII_CSRF_TOKEN" value= "<?= Yii::app()->request->csrfToken ?>">
						<input type="hidden" name="action" value="1">
						<input type="hidden" name="tripId" id="bcbId" value="<?=$booking['bcb_id']?>"">
						<input type="number" title="Please enter exactly 6 digits"  name="amount" id="recommAmt" value="<?=$booking['recommended_vendor_amount']?>" class="form-control"  onkeydown="limit(this, 6);" onkeyup="limit(this, 6);" required>
					</div>
					<div class="col-12"><span id="prevBidAmt" style="<?=($booking['bvr_bid_amount']>0)?'display: block':'display: none'?>">Your previous bid amount is &#x20B9;<?=$booking['bvr_bid_amount']?></span></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary ml-1" data-dismiss="modal" onclick="checkBid(1);">
						<i class="bx bx-check d-block d-sm-none"></i>
						<span class="d-none d-sm-block">Bid</span>
					</button>
				</div>
			</form>
			</div>
		</div>
</div>

<div class="modal text-left" id="acceptBid" tabindex="-1" aria-labelledby="myModalLabel19" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
		<div class="modal-content">
		<form id="bidacceptform">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel19">Are you sure ?</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i class="bx bx-x"></i>
				</button>
			</div>
			<div class="modal-body">
				<input type="hidden" name="YII_CSRF_TOKEN" value= "<?= Yii::app()->request->csrfToken ?>">
				<input type="hidden" name="action" value="2">
				<input type="hidden" name="tripId" id="bcbIdAcceptBid" value="<?=$booking['bcb_id']?>">
				<input type="hidden" name="amount" id="acceptAmtVal" value="<?=$booking['acptAmount']?>">
				Accept this trip at &#x20B9;<span id="acceptAmt"><?=$booking['acptAmount']?></span>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light-secondary btn-sm" data-dismiss="modal">
					<i class="bx bx-x d-block d-sm-none"></i>
					<span class="d-sm-block d-none">Close</span>
				</button>
				<button type="button" class="btn btn-primary ml-1 btn-sm" data-dismiss="modal" onclick="checkBid(2);">
					<i class="bx bx-check d-block d-sm-none"></i>
					<span class="d-sm-block d-none">Accept</span>
				</button>
			</div>
		</form>
		</div>
	</div>
</div>
<script>
	function checkBid(type)
	{debugger;
		let formData = $("#bidform").serialize();
		if(type == 2)
		{
			formData = $("#bidacceptform").serialize();
		}
		if(type == 0)
		{
			var con = confirm("Are you sure to deny this bid?");
			if(con)
			{
				formData = {'tripId':'<?=$booking['bcb_id']?>','action':0,"YII_CSRF_TOKEN": '<?= Yii::app()->request->csrfToken ?>'};
				applyBid(formData);
			}
			else
			{
				return false;
			}
		}
		else
		{
			applyBid(formData);
		}
		
		return;
	}
	function applyBid(formData)
	{
		$.ajax({
			"type": "POST",
			"url": "<?= Yii::app()->createUrl('supplier/booking/bidaccept')?>",
			'dataType': "json",
			"data": formData,
			"success": function (data1) { 
				if (data1.success) 
				{
					alert(data1.message);
					if(formData.action == 0){
						location.href = '<?= Yii::app()->createUrl('supplier/booking/list?status=new') ?>';
						return;
					}
					location.reload();
				} 
				else
				{
					alert(data1.errors[0]);
				}
				return;
			}
		});
	}
	
	function limit(element, max) {    
		var max_chars = max;
		if(element.value.length > max_chars) {
			element.value = element.value.substr(0, max_chars);
		}
	}
</script>