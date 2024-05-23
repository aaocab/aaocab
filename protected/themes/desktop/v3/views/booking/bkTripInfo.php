<?php
	$bookingType = Booking::model()->getBookingType($model->bkg_booking_type);
	$catClassType = $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_id;
	$bgCatType = '';
	if($catClassType == 1)
	{
		$bgCatType = "bg-orange color-white";
	}
	if($catClassType == 2)
	{
		$bgCatType = "bg-blue color-white";
	}
	if($catClassType == 4)
	{
		$bgCatType = "bg-blue5 color-white";
	}
	if($catClassType == 6)
	{
		$bgCatType = "bg-green2 color-white";
	}

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
		//$durationInWords = round($model->bkg_trip_duration / 60) . ' hrs';
		$durationInWords  = Filter::getDurationbyMinute($model->bkg_trip_duration);
	}
	$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 3);
	if ($response->getStatus())
	{
		$contactNo	 = $response->getData()->phone['number'];
		$countryCode = $response->getData()->phone['ext'];
		$email		 = $response->getData()->email['email'];
	}
	$cnt			= count($model->bookingRoutes)-1;
	/** @var Booking $model */
	$dropDateTime	= date('Y-m-d H:i:s', strtotime($model->bkg_pickup_date . ' + ' . $model->bkg_trip_duration . ' MINUTE'));
//	$dropDateTime	= date('Y-m-d H:i:s', strtotime($model->bookingRoutes[$cnt]->brt_pickup_datetime . ' + ' . $model->bookingRoutes[$cnt]->brt_trip_duration . ' MINUTE'));
	$routeDetails   = "";
	foreach ($model->bookingRoutes as $v)
	{
		$routeDetails .= Cities::model()->getName($v->brt_from_city_id) . ' - ';		
	}
	$routeDetails .= Cities::model()->getName($model->bookingRoutes[$cnt]->brt_to_city_id);

	$cabtype = ($model->bkg_vht_id > 0)?$model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label. " - " .$model->bkgSvcClassVhcCat->scv_label:$model->bkgSvcClassVhcCat->scv_label;

   if($model->bkgUserInfo->bkg_user_id>0)
   {
		$contactId = Users::getContactByUserId($model->bkgUserInfo->bkg_user_id);
		if($contactId>0)
		{
			$categoryId = ContactPref::model()->find('cpr_ctt_id=:id',['id'=>$contactId])->cpr_category;
			$catCss =  "";
			if($categoryId>0)
			{
				$category = UserCategoryMaster::model()->findByPk($categoryId)->ucm_label;
				if($category!='')
				{
					$catCss = UserCategoryMaster::getColorByid($categoryId);
				}
			}
		}
	}

?>
<div class="col-12">
	<div class="widget-img"><img src="<?php echo '/'.$model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_image;?>" width="220" alt=""></div>
        <div class="widget-card-view">
            <div class="row">
                <div class="col-12">
            <h6 class="weight400">Itinerary summary</h6>
                </div>
            </div>
            <div class="row">
				<div class="col-12"><b><span class="font-16 lineheight24"><?php echo $routeDetails; ?></span></b>
					<span class="pill-1 <?= $bgCatType ?> mr-1 mb5 font-10"><?php echo $cabtype; ?></span>

				</div>
                <div class="col-12 col-xl-10">
                    <div class="row">
					    <div class="col-12 col-xl-12 mb10">
                            <span class="font-12 text-muted">Booking ID</span><br>
                            <?php echo Filter::formatBookingId($model->bkg_booking_id); ?>
                        </div>
                        <div class="col-6 col-xl-6 mb10">
                            <span class="font-12 text-muted">Included distance</span><br>
                            <?php echo $tripDistance; ?> Kms
                        </div>
                        <div class="col-6 col-xl-6 mb10 text-right">
                            <span class="font-12 text-muted">Estimated duration</span><br>
                            <?php echo $durationInWords; ?> +/-30min
                        </div>

						<div class="col-6 mb10">
                            <span class="text-muted font-12">Trip type</span><br>
                            <?php echo $bookingType; ?> <?php echo ($model->bkg_booking_type==15)?"":"trip"; ?>
                        </div>
						
                        <div class="col-6 mb10 text-right">
							<?php if(in_array($model->bkg_status, [2, 3, 5]) && $model->bkg_reconfirm_flag == 1){ ?>
                            <span class="text-muted font-12">OTP (One time password)</span><br>
                            <span class="pill-2"><?= $model->bkgTrack->bkg_trip_otp?></span>
							<?php } ?>
                        </div>					

                        <div class="col-6 col-xl-6 mb-1">
                            <span class="text-muted font-12">Trip start time</span><br>
                            <?= date('jS M Y', strtotime($model->bkg_pickup_date)) ?>, <?= date('l', strtotime($model->bkg_pickup_date)) ?> <?= date('h:i A', strtotime($model->bkg_pickup_date)) ?>
                        </div>
                        <div class="col-6 col-xl-6 mb-2 text-right">
                            <span class="text-muted font-12">Estimated trip end time</span><br>
                            <?= date('jS M Y', strtotime($dropDateTime)) ?>, <?= date('l', strtotime($dropDateTime)) ?> <?= date('h:i A', ceil(strtotime($dropDateTime) / 1800) * 1800) ?>
                        </div>
                        <div class="col-12 col-xl-12 mb5 d-flex justify-content-between">
                            <span class="text-muted font-12">Traveller's name</span>
                            <span class="text-bold-500 trvlrname"><?= $model->bkgUserInfo->getUsername() ?><span class="ml5"><?=!empty($category) ? "<img src='/images/{$catCss}' alt='' width='20' title='{$category}'>" : ""; ?></span>	</span>
                        </div>
                        <div class="col-12 col-xl-12 mb5 d-flex justify-content-between">
                            <span class="text-muted font-12">Email</span>
                            <span class="text-bold-500 trvlremail"><?= (count(Yii::app()->user->loadUser()) > 0 && (Yii::app()->user->loadUser()->user_id == $model->bkgUserInfo->bkg_user_id)) ? $email : Filter::maskEmalAddress($email) ?></span>
                        </div>
                        <div class="col-12 col-xl-12 mb5 d-flex justify-content-between">
                            <span class="text-muted font-12">Phone</span>
                            <span class="text-bold-500 trvlrphone">+<?= $countryCode ?>-<?= (count(Yii::app()->user->loadUser()) > 0 && (Yii::app()->user->loadUser()->user_id == $model->bkgUserInfo->bkg_user_id)) ? $contactNo : Filter::maskPhoneNumber($contactNo) ?></span>
                        </div>
                    </div>
                </div>                
            </div>
		</div>		
</div>