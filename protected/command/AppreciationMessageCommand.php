<?php

class AppreciationMessageCommand extends BaseCommand
{

	public function actionSendAppreciationMessage()
	{
		$model	 = Booking::model()->appreciationMessage();
		$ext	 = '91';
		foreach ($model as $val)
		{
			$bookingID		 = $val['bkg_booking_id'];
			$vendorNumber	 = $val['vnd_phone'];
			$vendorName		 = $val['vnd_name'];
			$driverName		 = $val['bcb_driver_name'];
			$driverNumber	 = $val['bcb_driver_phone'];
			$msgCom			 = new smsWrapper();
			$msgCom->sendAppreciationMessageVendor($ext, $vendorNumber, 'Vendor', $bookingId, $vendorName, $driverName);
			$msgCom->sendAppreciationMessageDriver($ext, $driverNumber, 'Driver', $bookingId, $driverName);
		}
	}

	/**
	 * @deprecated since 24/07/2020
	 * @author ramala
	 */
	public function actionSendPromo()
	{
		$data	 = Yii::app()->db->createCommand('SELECT booking.bkg_booking_id bookingId,
														CONCAT(users.usr_name, " ", users.usr_lname) name,
														users.usr_email email,
														CONCAT(users.usr_country_code, "", users.usr_mobile) phone,
														rtg_customer_review review,
														rtg_customer_overall star,
														rtg_customer_date date
												 FROM `ratings`
													  LEFT JOIN booking ON bkg_id = rtg_booking_id
													  LEFT JOIN booking_user ON booking_user.bui_bkg_id = booking.bkg_id
													  LEFT JOIN users ON user_id = booking_user.bkg_user_id
												 WHERE     rtg_customer_overall = 5
													   AND usr_active = 1
													   AND date(rtg_customer_date) > DATE_SUB(CURRENT_DATE, INTERVAL 60 DAY)
												 ORDER BY rtg_customer_date DESC')->queryAll();
		$body	 = 'Date: 05-08-2017<br>
                    Dear Sir/Madam,<br>
                    <p>We were greatly enthused by your review on our website.</p>
                    <p>##REVIEW##</p>
                    <p>Please help us spread the good word. Click here <a href="https://goo.gl/OW4gUc" target="_BLANK">https://goo.gl/OW4gUc</a> and <a href="https://goo.gl/oOkGNt" target="_BLANK">https://goo.gl/oOkGNt</a> to go to our TripAdvisor and Google+ Review pages, scroll down to the Write Review button and share your experience with fellow avid travellers.
                    As a thanks giving gesture, we shall be issuing a Rs.500 Discount Coupon and crediting your Gozo Account with 500 Gozo Coins (subject to use of 250 Gozo Coins at a time), which may be redeemed in any of your future Bookings with us, upon advise of review posts from your end.</p>
                    <p>Thanking you for all your support.</p><br><br>
                    Regards<br>
                    for aaocab
                    ';

		foreach ($data as $value)
		{
			$mail		 = new EIMailer();
			$mail->clearView();
			$mail->clearLayout();
			$reviewText	 = '';
			if ($value["review"] != '')
			{
				$reviewText = "\"" . $value['review'] . "\"";
			}
			$body		 = str_replace('##REVIEW##', $reviewText, $body);
			$mail->setBody($body);
			$mail->setTo($value['email'], $value['name']);
			$subject	 = 'Your Review Matters';
			$mail->setSubject($subject);
			//  if ($mail->Sendmail(0)) {
			$delivered	 = "Email sent successfully";
			//  } else {
			$delivered	 = "Email not sent";
			//  }
			echo $value['bookingId'] . "--";
			echo $delivered . "\n";
		}
	}

}

?>