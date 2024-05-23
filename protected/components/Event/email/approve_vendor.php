<?php
try
{
	$model			 = Vendors::model()->findByPk($id);
	$contactId		 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
	$contactModel	 = Contact::model()->getContactDetails($contactId);
	$email			 = ContactEmail::getPrimaryEmail($contactId);
	$arrData		 = [
		'vnd_id'			 => $id,
		'full_name'			 => $contactModel['ctt_first_name'] . '' . $contactModel['ctt_last_name'],
		'email'				 => $email,
		'video_link'		 => 'https://youtu.be/AfbwgIJN0H0',
		'app_link'			 => 'https://play.google.com/store/apps/details?id=com.gozocabs.vendor&hl=en',
		'driver_app_link'	 => 'https://play.google.com/store/apps/details?id=com.gozocabs.driver&hl=en_US'
	];
	echo json_encode(array('type' => TemplateMaster::SEQ_EMAIL_CODE, 'templateName' => "approve_vendor", 'data' => $arrData, 'status' => true));
}
catch (Exception $ex)
{
	echo json_encode(array('type' => TemplateMaster::SEQ_EMAIL_CODE, 'templateName' => '', 'data' => [], 'status' => false));
}