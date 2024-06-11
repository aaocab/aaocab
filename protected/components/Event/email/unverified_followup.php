<?php
try
{
	$model	 = BookingTemp::model()->findByPk($id);
	$email	 = $model->bkg_user_email;
	if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
	{
		throw new Exception("Unsubscribed email", ReturnSet::ERROR_VALIDATION);
	}
	if (isset($email) && $email != '')
	{
		$emailCount			 = Booking::getConfirmBookingByContact($email);
		$params['full_name'] = $model->bkg_user_name . ' ' . $model->bkg_user_lname;
		$params['PromoImg']	 = ($emailCount == 0) ? '<a href="http://www.aaocab.com" target="_black"><img src="http://aaocab.com/images/email/save20.jpg" alt="Use SAVE20 Get 10% instant discount & 10% cashback as Gozo Coins" title="Use SAVE20 Get 10% instant discount & 10% cashback as Gozo Coins"></a>' : '';
		$fromCity			 = Cities::getName($model->bkg_from_city_id);
		$toCity				 = Cities::getName($model->bkg_to_city_id);
		$params['url']		 = Filter::shortUrl(LeadFollowup::getLeadURL($bkgId, 'e'));
		$subject			 = 'Ride from ' . $fromCity . ' => ' . $toCity . '. How can we help?';
		echo json_encode(array('type' => TemplateMaster::SEQ_EMAIL_CODE, 'templateName' => "unverified_followup", 'subject' => $subject, 'data' => $params, 'status' => true));
	}
	else
	{
		throw new Exception("cannot send email", ReturnSet::ERROR_VALIDATION);
	}
}
catch (Exception $ex)
{
	echo json_encode(array('type' => TemplateMaster::SEQ_EMAIL_CODE, 'templateName' => '', 'data' => [], 'status' => false));
}