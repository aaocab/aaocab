<?php

$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.min.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/login.js?v=' . $version);

$this->renderPartial('bkSummary', array('isredirct'			 => true, 'model'				 => $model,
	'creditVal'			 => $creditVal, 'model1'			 => $model1, 'showUserInfoPickup' => $showUserInfoPickup,
	'paymentdone'		 => $paymentdone, 'transid'			 => $transId, 'succ'				 => $succ,
	'promoArr'			 => $promoArr, 'userCreditStatus'	 => $userCreditStatus, 'gozocoinApply'		 => $gozocoinApply), false, false);
?>
