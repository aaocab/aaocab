<?php
$infosource				 = BookingAddInfo::model()->getInfosource('user');
$hash					 = Yii::app()->request->getParam('hash');
$otherExist				 = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 'block' : 'none';
$model->bkg_chk_others	 = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 1 : 0;
$model->hash			 = Yii::app()->shortHash->hash($model->bkg_id);
$response				 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 3);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode = $response->getData()->phone['ext'];
	$email		 = $response->getData()->email['email'];
}
$refcode			 = "";
$whatappShareLink	 = "";
if ($model->bkgUserInfo->bkg_user_id > 0)
{
	$users				 = Users::model()->findByPk($model->bkgUserInfo->bkg_user_id);
	$refcode			 = $users->usr_refer_code;
	$whatappShareLink	 = users::model()->whatsappShareTemplate($refcode);
}
$bookingType = Booking::model()->getBookingType($model->bkg_booking_type);

	if ($model->quote->routeDistance->routeDesc != '')
	{
		$routeDesc = implode(' &rarr; ', $model->quote->routeDistance->routeDesc);
	}
	else
	{
		$routeDesc = $model->bkgFromCity->cty_name . ' &rarr; ' . $model->bkgToCity->cty_name;
	}
	if ($model->quote->routeDistance->tripDistance != '')
	{
		$tripDistance = $model->quote->routeDistance->tripDistance;
	}
	else
	{
		$tripDistance = $model->bkg_trip_distance;
	}
	if ($model->quote->routeDuration->durationInWords != '')
	{
		$durationInWords = $model->quote->routeDuration->durationInWords;
	}
	else
	{
		$durationInWords = round($model->bkg_trip_duration / 60) . ' hrs';
	}
	$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 3);
	if ($response->getStatus())
	{
		$contactNo	 = $response->getData()->phone['number'];
		$countryCode = $response->getData()->phone['ext'];
		$email		 = $response->getData()->email['email'];
	}
	$cnt			= count($model->bookingRoutes)-1;
	$dropDateTime	= date('Y-m-d H:i:s', strtotime($model->bookingRoutes[$cnt]->brt_pickup_datetime . ' + ' . $model->bookingRoutes[$cnt]->brt_trip_duration . ' MINUTE'));
	$routeDetails   = "";
	foreach ($model->bookingRoutes as $v)
	{
		$routeDetails .= Cities::model()->getName($v->brt_from_city_id) . ' <i class="fas fa-long-arrow-alt-right"></i> ';		
	}
	$routeDetails .= Cities::model()->getName($model->bookingRoutes[$cnt]->brt_to_city_id);
	
	$addInfo		 = $model->bkgAddInfo;
	
	if(($addInfo->bkg_spl_req_senior_citizen_trvl || $addInfo->bkg_spl_req_kids_trvl || $addInfo->bkg_spl_req_woman_trvl || $addInfo->bkg_spl_req_driver_english_speaking || $addInfo->bkg_spl_req_carrier) == 1)
	{
		$splRequest = "Some";
	}
	else{
		$splRequest = "No";
	}

	$catType = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_id;
	$bgCatType = '';
	if($catType == 1)
	{
		$bgCatType = "bg-orange";
	}
	if($catType == 2)
	{
		$bgCatType = "bg-blue3";
	}
	if($catType == 3)
	{
		$bgCatType = "bg-green";
	}
	if($catType == 4)
	{
		$bgCatType = "bg-rose";
	}
?>
<div class="container p0 mb-2">
	<div class="row"> 
		<div class="col-12 text-center">
				<p class="merriw heading-line">Trip Booked!</p>  
			
		</div>
		<div class="col-12 col-lg-10 offset-lg-1">
			<div class="row">
				<div class="col-12 col-xl-7">
                                    <div class="row">
                                        <div class="col-12 mb5 d-flex justify-content-between"><span class="text-muted d-flex">Booking ID</span><br> <span class="text-bold-500 d-flex"><?php echo Filter::formatBookingId($model->bkg_booking_id); ?></span></div>
                                        <div class="col-12 mb5 d-flex justify-content-between"><span class="text-muted d-flex">Traveller's name</span><br> <span class="text-bold-500 d-flex"><?php echo $model->bkgUserInfo->bkg_user_fname . ' ' . $model->bkgUserInfo->bkg_user_lname ?></span></div>
                                        <div class="col-12 mb5 d-flex justify-content-between"><span class="text-muted d-flex">Email</span><br> <span class="text-bold-500 d-flex"><?php echo $email ?><?/*= ($model->bkgUserInfo->bkg_email_verified == 1) ? " <i class='fa fa-check btn-success ' title='verified'></i>" : " <i class='fa fa-remove btn-danger' title='not verified'></i>"*/ ?></span></div>
                                        <div class="col-12 mb5 d-flex justify-content-between"><span class="text-muted d-flex">Phone</span><br> <span class="text-bold-500 d-flex">+<?php echo $countryCode ?>-<?= $contactNo ?><?/*= ($model->bkgUserInfo->bkg_phone_verified == 1) ? " <i class='fa fa-check btn-success' title='verified'></i>" : " <i class='fa fa-remove btn-danger' title='not verified'></i>"*/ ?></span></div>
                                    </div>
                                    <ul class="list-unstyled">
                                        <li class="mb5 mt-1"><span class="text-muted font-16 weight500">Itinerary summary</span>:</li>
                                        <li><span class="font-16 lineheight24"><b><?php echo $routeDetails; ?></b></span>
                                            <span class="pill-1 <?= $bgCatType ?> mr-1 font-10" style="white-space: nowrap;"><?php echo $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . " (" . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . ")"; ?></span>
                                        </li>
                                    </ul>
				</div>
                            <div class="col-12 col-xl-5 list-4styled">
                                <ul>
                                    <li class="d-flex">
                                        <span class="font-18 w-100 weight600">Total fare</span><span class="font-18 text-bold-500 flex-shrink-1"><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_total_amount); ?></span></li>
                                    <li class="d-flex"><span class="font-18 w-100">Paid</span> <span class="font-18 text-bold-500 flex-shrink-1"><?php echo Filter::moneyFormatter(round($model->bkgInvoice->bkg_advance_amount)); ?></span></li>
                                    <li class="d-flex"><span class="font-18 w-100">Pay to driver</span> <span class="font-18 text-bold-500 flex-shrink-1"><?php echo ($model->bkgInvoice->bkg_due_amount > 0) ? Filter::moneyFormatter(round($model->bkgInvoice->bkg_due_amount)) : '0'; ?></span></li>
                                </ul>
                            </div>
			</div>
			<div class="row accordion-widget">
				<div class="col-12" id="accordion-icon-wrapper">
                    <div class="accordion collapse-icon accordion-icon-rotate" id="accordionWrapa2" data-toggle-hover="true">
						<div class="card collapse-header">
                            <div id="heading12" class="card-header" data-toggle="collapse" data-target="#accordion12" aria-expanded="false" aria-controls="accordion12" role="tablist">
                                <span class="collapse-title">
                                    <span class="align-middle weight500">Billing details</span><br>
                                </span>
                            </div>
                            <div id="accordion12" role="tabpane12" data-parent="#accordionWrapa2" aria-labelledby="heading12" class="collapse" style=""> 
								<?php
								$this->renderPartial("bkBillingDetails", ["model" => $model], false);
								?>
							</div>
						</div>

						<div class="card collapse-header">
							<div id="heading5" class="card-header collapsed" data-toggle="collapse" data-target="#accordion5" aria-expanded="false" aria-controls="accordion5" role="tablist">
								<span class="collapse-title">
									<span class="align-middle weight500"><?= $splRequest ?> special services requested</span><br>
									<p class="font-12 mb0 text-muted"> 
										<span class="sq_src <?php echo ($model->bkgAddInfo->bkg_spl_req_senior_citizen_trvl == 1) ? "" : "hide" ?>">Senior citizen traveling,</span> 
										<span class="sq_kid <?php echo ($model->bkgAddInfo->bkg_spl_req_kids_trvl == 1) ? "" : "hide" ?>">Kids on board,</span>
										<span class="sq_wot <?php echo ($model->bkgAddInfo->bkg_spl_req_woman_trvl == 1) ? "" : "hide" ?>">Women traveling,</span>
										<span class="sq_esd <?php echo ($model->bkgAddInfo->bkg_spl_req_driver_english_speaking == 1) ? "" : "hide" ?>">English-speaking driver required,</span> 
										<span class="sq_esd <?php echo ($model->bkgAddInfo->bkg_spl_req_carrier == 1) ? "" : "hide" ?>">Require vehicle with Carrier,</span>
									</p>     
								</span>
							</div>
							<div id="accordion5" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading5" class="collapse" style="">
								<?php
								$this->renderPartial("bkSpecialRequest", ["model" => $model], false);
								?> 
							</div>
						</div>
						<div class="card collapse-header">
							<div id="heading6" class="card-header collapsed" data-toggle="collapse" role="button" data-target="#accordion6" aria-expanded="false" aria-controls="accordion6">
								<span class="collapse-title">
									<span class="align-middle weight500">24 Hour cancellation policy</span>
								</span>
							</div>
							<div id="accordion6" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading6" class="collapse" aria-expanded="false" style="">
								<?php
								$this->renderPartial("bkCanPolicy", ["model" => $model], false);
								?>
							</div>
						</div>
						<div class="card collapse-header">
							<div id="heading7" class="card-header collapsed" data-toggle="collapse" role="button" data-target="#accordion7" aria-expanded="false" aria-controls="accordion7">
								<span class="collapse-title">
									<span class="align-middle weight500">Fare inclusions/exclusions</span>
								</span>
							</div>
							<div id="accordion7" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading7" class="collapse" aria-expanded="false" style="">
								<?php
								$this->renderPartial("bkCanInfo", ["model" => $model], false);
								?>
							</div>
						</div>
						<div class="card collapse-header">
							<div id="heading9" class="card-header" data-toggle="collapse" role="button" data-target="#accordion9" aria-expanded="false" aria-controls="accordion9">
								<span class="collapse-title">
									<span class="align-middle weight500">Boarding checks</span>
								</span>
							</div>
							<div id="accordion9" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading9" class="collapse" aria-expanded="false">
								<?php
								$this->renderPartial("bkBoardingCheck", ["model" => $model], false);
								?>
							</div>
						</div>
						<div class="card collapse-header">
							<div id="heading10" class="card-header" data-toggle="collapse" role="button" data-target="#accordion10" aria-expanded="false" aria-controls="accordion10">
								<span class="collapse-title">
									<span class="align-middle weight500">On trip do's &amp; don'ts</span>
								</span>
							</div>
							<div id="accordion10" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading10" class="collapse" aria-expanded="false">
								<?php
								$this->renderPartial("bkDonts", ["model" => $model], false);
								?>
							</div>
						</div>
						<div class="card collapse-header">
							<div id="heading11" class="card-header" data-toggle="collapse" role="button" data-target="#accordion11" aria-expanded="false" aria-controls="accordion11">
								<span class="collapse-title">
									<span class="align-middle weight500">Other terms</span>
								</span>
							</div>
							<div id="accordion11" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading11" class="collapse" aria-expanded="false">
								<?php
								$this->renderPartial("bkAdvisory", ["model" => $model], false);
								?>
                            </div>
                        </div>
						<div class="card collapse-header">
							<div id="heading13" class="card-header" data-toggle="collapse" role="button" data-target="#accordion13" aria-expanded="false" aria-controls="accordion13">
								<span class="collapse-title">
									<span class="align-middle weight500">Travel advisories &amp; restrictions</span>
								</span>
							</div>
							<div id="accordion13" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading13" class="collapse" aria-expanded="false">
								<?php
								//$this->renderPartial("bkAdvisory", ["model" => $model], false);
								echo "Comming soon..";
								?>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
<!--			<div class="row mt-2">
				<div class="col-5">
					<p class="mb0 text-uppercase weight500 lineheight14">Total fare</p>
					<p class="mb0"><span class="font-20">&#x20B9;</span><span class="font-24 weight600"><?/*= $model->bkgInvoice->bkg_total_amount; */?></span></p>
				</div>
			</div>	-->
		</div>

	</div>
</div>

</div>
