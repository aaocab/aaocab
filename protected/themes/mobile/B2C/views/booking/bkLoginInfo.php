<?php
$this->layout = 'column_booking';
$version		 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.min.js?v=' . $version);
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
$scvVctId = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id); 
?>

<?php

$infosource				 = BookingAddInfo::model()->getInfosource('user');
$ulmodel				 = new Users('login');
$urmodel				 = new Users('insert');
$userdiv = 'block';
$response	 = Contact::referenceUserData($model->bui_id, 3);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode	 = $response->getData()->phone['ext'];
	$userName	 = $response->getData()->phone['userName'];
	$lastName	 = $response->getData()->phone['lastName'];
	$email		 = $response->getData()->email['email'];
}
if (!Yii::app()->user->isGuest)
{
	$user	 = Yii::app()->user->loadUser();
	$model->bkg_user_id = Yii::app()->user->getId();
	$model->bkg_user_name	= $user->usr_name;
	$model->bkg_user_lname	= $user->usr_lname;
	$userdiv = 'none';
	if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
	{
		if (Users::model()->getFbLogin($model->bkg_user_id, $email, $contactNo))
		{
			$userdiv = 'none';
		}
		else
		{
			$userdiv = 'block';
		}
	}
}
else
{
	if (Users::model()->getFbLogin($model->bkg_user_id, $email, $contactNo))
	{
		$userdiv = 'none';
	}
	else
	{
		$userdiv = 'block';
	}
	$ulmodel->usr_email			 = $email;
	$urmodel->usr_email			 = $email;
	$urmodel->usr_mobile			 = $contactNo;
	$urmodel->usr_country_code		 = $countryCode;
	$urmodel->usr_name			 = $userName;
	$urmodel->usr_lname			 = $lastName;
}
/*
  @var $model Booking
 *  */

?>

<div id="userdiv" style="display :<?= $userdiv ?>" >
        <div class="col-xs-12 col-sm-11 col-md-4 pb0 mb20">
            <div class="main_time border-greenline book-panel">
                <div class="p10">
                    <div class="row">
                        <div class="col-xs-12 guest-login-panel">
							<?
							if ($scvVctId != VehicleCategory::SHARED_SEDAN_ECONOMIC)
							{
								?>
								<div class="heading-part mb10">Log In <i class="fa fa-sign-in"></i></div>
								<?
							}
							else
							{
								?>
								<h4 class="text-uppercase mt0 mb10 heading-part" style="text-align: center;">To create a flexxi share booking<i class="fa fa-sign-in" style="margin-left:5px;"></i></h4>
							<? } ?>
							<?
							if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
							{
								$isFlexxi = true;
							}
							else
							{
								$isFlexxi = false;
							}
							$this->renderPartial('partialsignin' . $this->layoutSufix, ['model' => $ulmodel, 'isFlexxi' => $isFlexxi], false, true);
							?>
                        </div>
			

                    </div>
                </div>
            </div>

        </div>
    </div>