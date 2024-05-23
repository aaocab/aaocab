<?php
$this->layout = 'column1';
?>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
//Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');

//$stateList = array("" => "Select State") + CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
//$username = Users::model()->getNameById($model->bkg_user_id);
//$routeList = Route::model()->getRouteList();
//$vendorList = CHtml::listData(Vendors::model()->getAll(array('order' => 'vnd_name')), 'vnd_id', 'vnd_name');

$cabType = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc;
if ($model->bkgBcb && $model->bkg_status > 3)
{
	$vehicleModel = $model->bkgBcb->bcbCab->vhcType->vht_model;
	if($model->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
	{
		$vehicleModel = OperatorVehicle::getCabModelName($model->bkgBcb->bcb_vendor_id, $model->bkgBcb->bcb_cab_id);
	}
	$cabType = $vehicleModel;
}
//$type = VehicleTypes::model()->getCarType();
//$vehicles = Vehicles::model()->getcar($model->bkg_vehicle_type_id);
$status		 = Booking::model()->getBookingStatus();
//$locked = ' <i class="fa fa-lock"></i>';
//$cityList = Cities::model()->getAllCityList();
//$locked = ' <i class="fa fa-lock"></i>';
$bookingType = Booking::model()->getBookingType($model->bkg_booking_type, '');
$css	 = ($isAjax) ? "" : "col-lg-8 col-md-10 mt10";
$route	 = BookingRoute::model()->getRouteName($model->bkg_id);
/* @var $model Booking */
?>
<div class="menu-title">
<h2 class="font18"><b class="font-14">Booking Details for</b> <br><b class="color-green3-dark"><?=Filter::formatBookingId($model->bkg_booking_id)?></b></h2>

</div>
<div class="menu-page p10 line-height18">
	<div class="content bottom-10 pl0">
		<span class="gray-color">Booking Type</span><br>
		<?= $bookingType ?>
	</div>
	<div class="content bottom-10 pl0">
		<span class="gray-color">Name</span><br>
		<?= ucfirst($model->bkgUserInfo->bkg_user_fname) . ' ' . ucfirst($model->bkgUserInfo->bkg_user_lname); ?>
	</div>
	<?
	$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 3);
	if ($response->getStatus())
	{
		$contactNo	 = $response->getData()->phone['number'];
		$countryCode	 = $response->getData()->phone['ext'];
		$email		 = $response->getData()->email['email'];
	}
	if ($model->bkgUserInfo->bkg_user_email != '')
	{
		?>
		<div class="content bottom-10 pl0">
			<span class="gray-color">Email id</span><br>
			<?= $email; ?>
		</div>
	<? } ?>
	<?
	if ($contactNo != '')
	{
		?>
		<div class="content bottom-10 pl0">
			<span class="gray-color">Contact</span><br>
			+<?= $countryCode . '-' . $contactNo; ?>
		</div>
	<? } ?>
	<?
	if ($model->bkgUserInfo->bkg_alt_contact_no != '')
	{
		?>
		<div class="content bottom-10 pl0">
			<span class="gray-color">Alternate Contact</span><br>
			+<?= $model->bkgUserInfo->bkg_alt_country_code . '-' . $model->bkgUserInfo->bkg_alt_contact_no; ?>
		</div>
	<? } ?>
	<div class="content bottom-10 pl0">
		<span class="gray-color">Pickup Date</span><br>
		<?= date('d/m/Y', strtotime($model->bkg_pickup_date)); ?>
	</div>
	<div class="content bottom-10 pl0">
		<span class="gray-color">Pickup Time</span><br>
		<?= date('h:i A', strtotime($model->bkg_pickup_date)); ?>
	</div>
	<div class="content bottom-10 pl0">
		<span class="gray-color">Pickup Location</span><br>
		<?= $model->bkg_pickup_address; ?>
	</div>
	<div class="content bottom-10 pl0">
		<span class="gray-color">Dropoff Location</span><br>
		<?= $model->bkg_drop_address; ?>
	</div>
	<div class="content bottom-10 pl0">
		<span class="gray-color">Car Type</span><br>
		<?= $cabType; ?>
	</div>
	<div class="content bottom-10 pl0">
		<span class="gray-color">Route</span><br>
		<?= $route ?>
	</div>
	<div class="content bottom-10 pl0 pr0">
		<div class="one-half font-18"><b>Amount</b></div>
		<div class="one-half last-column text-right"><span class="color-green3-dark"><span class="font-18"><span class="inr-font">₹</span><b><?= $model->bkgInvoice->bkg_total_amount ?></b></span></span></div>
		<div class="clear"></div>
	</div>

</div>





<script>

    function addRating(booking_id) {
        $href = "<?= Yii::app()->createUrl('rating/addreview') ?>";
        var $booking_id = booking_id;
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"bkg_id": $booking_id},
            success: function (data)
            {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Review',
                    onEscape: function () {

                        // user pressed escape
                    },
                });

            }
        });
    }

</script>
