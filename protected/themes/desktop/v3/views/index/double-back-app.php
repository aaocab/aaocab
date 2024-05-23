<?php
	$url = Yii::app()->createUrl('booking/doubleBackOffer', []);
	$this->renderPartial($url, false);
?>