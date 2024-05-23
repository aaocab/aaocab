<?php
$unverifiedImg	 = Yii::app()->baseUrl . "/images/unverified.png";
$verifiedImg	 = Yii::app()->baseUrl . "/images/verified.png";
$isPhoneVerified = ($model->bkgUserInfo->bkg_phone_verified == 1) ? $verifiedImg : $unverifiedImg;
$isEmailVerified = ($model->bkgUserInfo->bkg_email_verified == 1) ? $verifiedImg : $unverifiedImg;

//$carType	 = VehicleTypes::model()->getVehicleTypeById($model->bkg_vehicle_type_id);
//$carType	 = $model->bkg_vehicle_type_id;
//$car_type	 = VehicleTypes::model()->getCarType($carType);

$vctId			 = $model->bkgSvcClassVhcCat->scv_vct_id;
$sccId			 = $model->bkgSvcClassVhcCat->scv_scc_id;
$car_type		 = SvcClassVhcCat::model()->getVctSvcList('string', $sccId, $vctId);
$vhcModel		 = '';
$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
if ($response->getStatus())
{
	$email	 = $response->getData()->email['email'];
}
if($sccId == 4)
{
  $vhtId = $model->bkg_vht_id;
  $vhcTypeModel = VehicleTypes::model()->findByPk($vhtId);
  $vhcModel = ' - '.$vhcTypeModel->vht_make.' '.$vhcTypeModel->vht_model;
}
$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode = $response->getData()->phone['ext'];
}
?>
<div class="col-xs-12 mb20">
	<div class="row">
	<div class="col-xs-12 col-sm-6 heading-part mb10">Traveler Information</div>
	<div class="col-xs-12 col-sm-6 payHeader additionalinfoadd hide">Special requests added successfully.</div>
	</div> 
	<div class="main_time border-blueline">
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				Passenger Name: <span class="black-color"><?= $model->bkgUserInfo->getUsername() ?></span><br>
				Email: <span class="black-color">
					<?= $email; ?>
					<img src="<?= $isEmailVerified; ?>">
				</span><br>
				Phone: <span class="black-color">+<?= $countryCode ?><?= $contactNo ?>
					<img src="<?= $isPhoneVerified; ?>">
				</span><br>
			</div>
			<div class="col-xs-12 col-sm-6">

				Cab Type: <span class="black-color"><?= $car_type.$vhcModel; ?></span><br>
				Trip Type: <span class="black-color"><?= ucwords($model->getBookingType($model->bkg_booking_type, 'Trip')) ?></span><br>
				Pickup Time: <span class="black-color"><?= date('jS M Y (l)', strtotime($model->bkg_pickup_date)) ?>, <?= date('h:i A', strtotime($model->bkg_pickup_date)) ?></span><br>
			</div>
		</div>
	</div>
</div>