<?php
$conn = new mysqli('localhost', 'root', 'anupam', 'gozocabs');
// Check connection
if ($conn->connect_error)
{
	die("Connection failed: " . $conn->connect_error);
}
$customerMessage="A good trip deserves a 5* review. Every 5* review gets the driver Rs. 250/- on spot bonus. Take a selfie with your driver and car and post it on facebook with hashtag  #LOVEDITGOZOCABS to get a chance at winning Rs. 5000/-  every week.";
$driverMessage="GOZO ki trip pe 5 star service dijiye, paiye Rs. 250 ka bonus, apna driver license aur bank account ki jankari GOZO ke saath file par rakhen taaki bonus amount aapke account mein jama kia ja sake";
$ext='91';
$getID = $conn->query("SELECT bkg_id FROM booking WHERE date(bkg_pickup_date) LIKE CURDATE() AND TIME_FORMAT(SUBTIME(bkg_pickup_time, time(SYSDATE())), '%h:%i') BETWEEN '00:00' AND '01:00'");
$rows = mysqli_fetch_array($getID);
foreach($rows as $id)
	{
		$model = Booking::model()->findByPk($id);
		$bookingID = $model()->bkg_booking_id;
		$drivernumber= $model->bkgDriver->drv_phone;
		$driverName= $model->bkgDriver->drv_name;
		$customerName = $model->bkg_user_fname;
		$customerPhone = $model->bkg_contact_no;
		$smsCom = new smsWrapper();
		$smsCom->pickupOfferDriver($ext, $drivernumber,$bookingID, $driverMessage,$driverName);
		$smsCom->pickupOfferCustomer($ext,$customerPhone,$bookingID,$customerMessage,$customerName);
		$notificationId	 = substr(round(microtime(true) * 1000), -5);
		//Push Notification to customer.
		$payLoadData1 = ['bookingId' => $model->bkg_booking_id, 'EventCode' => Booking::CODE_USER_OFFER];
		$success1 = AppTokens::model()->notifyConsumer(
				$model->bkg_user_id,
				$payLoadData1,$notificationId, $customerMessage,
				"Todays offer for you");
	}
?>