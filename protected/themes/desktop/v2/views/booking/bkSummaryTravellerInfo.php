<?php
$unverifiedImg	 = Yii::app()->baseUrl . "/images/unverified.png";
$verifiedImg	 = Yii::app()->baseUrl . "/images/verified.png";
$isPhoneVerified = ($model->bkgUserInfo->bkg_phone_verified == 1) ? $verifiedImg : $unverifiedImg;
$isEmailVerified = ($model->bkgUserInfo->bkg_email_verified == 1) ? $verifiedImg : $unverifiedImg;

//$carType	 = VehicleTypes::model()->getVehicleTypeById($model->bkg_vehicle_type_id);
//$car_type	 = VehicleTypes::model()->getCarType($carType);
$vctId		 = $model->bkgSvcClassVhcCat->scv_vct_id;
$sccId		 = $model->bkgSvcClassVhcCat->scv_scc_id;
$car_type	 = $model->bkgSvcClassVhcCat->scv_label;
$luggageCapacity = Stub\common\LuggageCapacity::init($vctId, $sccId, 1);
//$vhcModel	 = '';
//if ($sccId == 4)
//{
//	$vhtId			 = $model->bkg_vht_id;
//	$vhcTypeModel	 = VehicleTypes::model()->findByPk($vhtId);
//	$vhcModel		 = ' - ' . $vhcTypeModel->vht_make . ' ' . $vhcTypeModel->vht_model;
//}
?>

<div class="col-12 mb20">
	<div class="alert alert-primary mb10 additionalinfoadd hide">Special requests added successfully.</div>
	<div class="bg-white-box">		
		<div class="row">
			<div class="col-12 font-20 mb10 text-uppercase"><b>Traveller Information</b></div>
			<div class="col-md-2"><div class="car_box1"><img src="/images/cabs/car-etios.jpg" alt="" class="img-thumbnail border-none"></div></div>
			<div class="col-md-10">
				<div class="row">
					<div class="col-md-12 mb5"><span class="font-24 text-uppercase color-green"><b><?= $car_type?></b></span></div>
                                        <div class="col-md-12 font-13">
                                            <span class="color-gray"> Luggage Capacity :</span> <span class="black-color">
                                            <?= (($luggageCapacity->largeBag != 0) ? $luggageCapacity->largeBag . ' Large bag(s) or' : '') ?>
                                            <?= (($luggageCapacity->smallBag != 0) ? $luggageCapacity->smallBag . ' Small bag(s) ' : '') ?>
                                            </span>
                                        </div>
					<div class="col-md-6 font-13">	
						<div class="mt5"><span class="color-gray">Passenger Name:</span> <span class="black-color"><b><?= $model->bkgUserInfo->getUsername() ?></b></span></div>
						<div class="mt5"><span class="color-gray">Email:</span> <span class="black-color">
							<?= $model->bkgUserInfo->bkg_user_email; ?>
							<img src="<?= $isEmailVerified; ?>">
						</span></div>
						<div class="mt5 row">
							<div class="col-md-12">
	                           <span class="color-gray">Pickup Time:&nbsp;</span><span class="black-color font-12"><span></span><?= date('jS M Y, l', strtotime($model->bkg_pickup_date)) ?></span>,</div>						
						</div>	
						<div class="mt3 row">
							<div class="col-md-3"><span class=""></span></div> 
							<div class="col-md-9"><span>&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="font-12"><?= date('h:i A', strtotime($model->bkg_pickup_date)) ?></span></div>							
						</div>
					</div>
					<div class="col-md-6 pl0 pr0 font-13">
						<div><span class="color-gray">Trip Type:</span> <span class="black-color"><b><?= ucwords($model->getBookingType($model->bkg_booking_type, 'Trip')) ?></b></span></div>
						<div class="mt5"><span class="color-gray">Phone:</span> <span class="black-color">+<?= $model->bkgUserInfo->bkg_country_code ?><?= $model->bkgUserInfo->bkg_contact_no ?>
							<img src="<?= $isPhoneVerified; ?>">
							</span>
						</div>
						<?php						
						$cnt			= count($model->bookingRoutes)-1;
						$dropDateTime	= date('Y-m-d H:i:s', strtotime($model->bookingRoutes[$cnt]->brt_pickup_datetime . ' + ' . $model->bookingRoutes[$cnt]->brt_trip_duration . ' MINUTE'));
						?>
						<div class="mt5 row">
							<div class="col-md-12"><span class="color-gray">Drop Time:&nbsp;</span><span class="black-color"></span><span class="font-12"><?= date('jS M Y, l', strtotime($dropDateTime)) ?>,</span></div>												
						</div>
						<div class="mt3 row">
							<div class="col-md-2"><span class=""></span></div> 
							<div class="col-md-10"><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;<span class="black-color font-12"><?= date('h:i A', strtotime($dropDateTime)) ?></span></div>							
						</div>						
					</div> 
				</div>
			</div>
		</div>
	</div>
</div>