<?php
 
$checkDate = '2020-03-31 23:59:59';
if ($model->bkg_pickup_date > $checkDate)
{

//$version = Yii::app()->params['siteJSVersion'];
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.min.js?v=' . $version);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/login.js?v=' . $version);

	$this->renderPartial('bkReview', array('hash'				 => $model->hash, 'isredirct'			 => true, 'model'				 => $model,
		'creditVal'			 => $creditVal, 'model1'			 => $model1, 'showUserInfoPickup' => $showUserInfoPickup,
		'paymentdone'		 => $paymentdone, 'transid'			 => $transId, 'succ'				 => $succ, 'note'				 => $note,
		'promoArr'			 => $promoArr, 'userCreditStatus'	 => $userCreditStatus, 'gozocoinApply'		 => $gozocoinApply,
		'refcode'			 => $refcode, 'walletBalance'		 => $walletBalance, 'whatappShareLink'	 => $whatappShareLink,
		'applicableAddons'	 => $applicableAddons, 'routeRatesArr'		 => $routeRatesArr, 'actiondone'		 => $actiondone,'isRescheduled'=>$isRescheduled), false, false);
}
else
{
	echo "<br><h4><b>This booking is not active.</b></h4><br>";
}
?>