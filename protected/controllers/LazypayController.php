<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class LazypayController extends BaseController
{

	public function actionCheckeligiblitycall()
	{
//		$bkmodel->bkgFromCity->cty_name . '/' . $bkmodel->bkgToCity->cty_name . '/' . $bkmodel->bkg_booking_id;
		$mobile		 = Yii::app()->request->getParam('phone', 0);
		$email		 = Yii::app()->request->getParam('email', 0);
		$orderAmount = Yii::app()->request->getParam('amount', 0);
		$bkgid		 = Yii::app()->request->getParam('bkgid', 0);

		$param_list["phone"]	 = $mobile;
		$param_list['email']	 = $email;
		$param_list['amount']	 = $orderAmount;
		$response				 = Yii::app()->lazypay->checkEligibility($param_list);
		$resArr					 = json_decode($response, true);
		$result					 = ($resArr['eligibility']) ? 'Approved' : 'Rejected';
		$desc					 = '(' . $mobile . ' - ' . $email . ' - Rs.' . $orderAmount . ') - ' . $result;
		if($bkgid>0)
		{
			BookingLog::model()->createLog($bkgid, "LazyPay Eligiblity Check -  $desc", UserInfo::getInstance(), BookingLog::LAZYPAY_ELIGIBILITY_CHECK, '', $params);
		}
		echo $response;
		Yii::app()->end();
	}

}
