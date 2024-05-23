<?php
$this->layout	 = 'column_booking';
?>

<?php
$infosource		 = BookingAddInfo::model()->getInfosource('user');
$ulmodel		 = new Users('login');
//$urmodel		 = new Users('insert');
$emailModel		 = new ContactEmail();
$userdiv		 = 'block';
if ($model->bkg_user_id != '')
{
	$email = ContactEmail::model()->getEmailByUserId($model->bkg_user_id);
}
else
{
	$email = $model->bkg_user_email;
}
$response = Contact::referenceUserData($model->bui_id, 2);
if ($response->getStatus())
{
	$phone		 = $response->getData()->phone['number'];
	$ext		 = $response->getData()->phone['ext'];
	$userName	 = $response->getData()->phone['userName'];
	$lname		 = $response->getData()->phone['lastName'];
}
if (!Yii::app()->user->isGuest)
{
	$user = Yii::app()->user->loadUser();
	if ($user->usr_contact_id)
	{
		$contactModel			 = Contact::model()->findByPk($user->usr_contact_id);
		$emailModel				 = ContactEmail::model()->findByConId($user->usr_contact_id);
		$phoneModel				 = ContactPhone::model()->findByConId($user->usr_contact_id);
		$model->bkg_user_name	 = $contactModel->ctt_first_name;
		$model->bkg_user_lname	 = $contactModel->ctt_last_name;
	}
	$model->bkg_user_id	 = Yii::app()->user->getId();
	$userdiv			 = 'none';
	$scvVctId			 = '';
	if ($model->bkg_vehicle_type_id != '')
	{
		$scvVctId = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
	}
	if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
	{
		if (Users::model()->getFbLogin($model->bkg_user_id, $email, $phone))
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
	if (Users::model()->getFbLogin($model->bkg_user_id, $email, $phone))
	{
		$userdiv = 'none';
	}
	else
	{
		$userdiv = 'block';
	}


	$emailModel->eml_email_address = $email;
//	$urmodel->usr_email		 = $email;
//	$urmodel->usr_mobile		 = $phone;
//	$urmodel->usr_country_code	 = $ext;
//	$urmodel->usr_name		 = $userName;
//	$urmodel->usr_lname		 = $lname;
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
						$this->renderPartial('partialsignin', ['model' => $ulmodel, 'emailModel' => $emailModel, 'isFlexxi' => $isFlexxi], false, true);
						?>
					</div>
					<?
					if ($scvVctId != VehicleCategory::SHARED_SEDAN_ECONOMIC)
					{
						?>
						<div class="col-xs-12">
							<div class="row guest-panel">
								<div class="col-xs-12 text-center">
									<a class="btn fb-btn text-uppercase" style="width:75%;" onclick="socailSigin('facebook')" role="button"><b><i class="fa fa-facebook" style="    margin-right: 8px;"></i>   Login with Facebook <i class="fa fa-caret-right"></i></b></a>
								</div>
							</div>
						</div>
					<? } ?>

				</div>
			</div>
		</div>

		<?php
		$dboApplicable = Filter::dboApplicable($bkgModel);
		if ($dboApplicable)
		{
			?>
			<div class="col-sm-12 text-center mt20">
				<img src="/images/doubleback_fares2.jpg" alt="" width="350" class="img-responsive">
			</div>
			<?php
		}
		?>
	</div>
</div>