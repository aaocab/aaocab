<?php

class BeforePickupOfferCommand extends BaseCommand
{

	public function actionSendOfferMessage()
	{
		$sql			 = "SELECT bkg_id
									FROM booking
									WHERE     date(bkg_pickup_date) LIKE CURDATE()
										  AND TIME_FORMAT(SUBTIME(DATE_FORMAT(bkg_pickup_date,'%h:%i'), time(SYSDATE())), '%h:%i') BETWEEN '00:00'
																												  AND '01:00'";
		$customerMessage = "A good trip deserves a 5* review. Every 5* review gets the driver Rs. 250/- on spot bonus. Take a selfie with your driver and car and post it on facebook with hashtag  #LOVEDITaaocab to get a chance at winning Rs. 5000/-  every week.";
		$driverMessage	 = "GOZO ki trip pe 5 star service dijiye, paiye Rs. 250 ka bonus, apna driver license aur bank account ki jankari GOZO ke saath file par rakhen taaki bonus amount aapke account mein jama kia ja sake";
		$ext			 = '91';
		$result			 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($result as $id)
		{
			$model		 = Booking::model()->findByPk($id);
			$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
			if ($response->getStatus())
			{
				$contactNo	 = $response->getData()->phone['number'];
				$firstName	 = $response->getData()->phone['firstName'];
				$lastName	 = $response->getData()->phone['lastName'];
			}
			$bookingID		 = $model()->bkg_booking_id;
			$drivernumber	 = $model->bkgDriver->drv_phone;
			$driverName		 = $model->bkgDriver->drv_name;
			$customerName	 = $firstName . " " . $lastName;
			$customerPhone	 = $contactNo;
			$smsCom			 = new smsWrapper();
			$smsCom->pickupOfferDriver($ext, $drivernumber, $bookingID, $driverMessage, $driverName);
			$smsCom->pickupOfferCustomer($ext, $customerPhone, $bookingID, $customerMessage, $customerName);
			//Push Notification to customer.
			$notificationId	 = substr(round(microtime(true) * 1000), -5);
			$payLoadData1	 = ['bookingId' => $model->bkg_booking_id, 'EventCode' => Booking::CODE_USER_OFFER];
			$success1		 = AppTokens::model()->notifyConsumer(
					$model->bkgUserInfo->bkg_user_id, $payLoadData1, $notificationId, $customerMessage, "Todays offer for you");
		}
	}

}

?>