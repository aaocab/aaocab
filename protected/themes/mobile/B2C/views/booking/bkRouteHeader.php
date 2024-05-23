<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>
<?php
$bookingType = Booking::model()->getBookingType($model->bkg_booking_type, 'Trip');
if ($quoteModel)
{
	$routeDesc		 = implode(' &rarr; ', $quoteModel->routeDistance->routeDesc);
	$tripDistance	 = $quoteModel->routeDistance->tripDistance;
	$durationInWords = BookingRoute::model()->populateTripduration($quoteModel->routes);
}
else if ($model)
{
	$routeDesc = $bookingType;
}
?>
<div class="demo-header header-line-1 header-logo-app mb0 mt10 n">
    <a href="Javascript:void(0)" onclick="$jsBookNow.goToPrevTab(<?php echo $prevStep; ?>);" class="header-logo-title" style="white-space: nowrap"><?=$routeDesc?> <?php echo ($quoteModel->gozoNow)?'(Gozo Now)':'' ?></a>
    <a href="Javascript:void(0)" onclick="$jsBookNow.goToPrevTab(<?php echo $prevStep; ?>);" class="header-icon header-icon-1"><i class="fa fa-angle-left"></i></a>
</div>
<?php $this->renderPartial("bkBanner", ['model' => $model]); ?>