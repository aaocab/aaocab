<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');

//$stateList = array("" => "Select State") + CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
//$username = Users::model()->getNameById($model->bkg_user_id);
//$routeList = Route::model()->getRouteList();
//$vendorList = CHtml::listData(Vendors::model()->getAll(array('order' => 'vnd_name')), 'vnd_id', 'vnd_name');

$cabType = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label." (". $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label.")";


if($model->bkgBcb && in_array($model->bkg_status,[5,6,7]))
{
	$vehicleModel = $model->bkgBcb->bcbCab->vhcType->vht_model;
	if($model->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
	{
		$vehicleModel = OperatorVehicle::getCabModelName($model->bkgBcb->bcb_vendor_id, $model->bkgBcb->bcb_cab_id);
	}
    $cabType = $model->bkgBcb->bcbCab->vhcType->vht_make." ".$vehicleModel;
}
//$type = VehicleTypes::model()->getCarType();
//$vehicles = Vehicles::model()->getcar($model->bkg_vehicle_type_id); 
$status = Booking::model()->getBookingStatus();
//$locked = ' <i class="fa fa-lock"></i>';
//$cityList = Cities::model()->getAllCityList();
//$bookingType = array(1 => 'One way', 2 => 'Return', 3 => 'Multi city');
$bookingType = Booking::model()->getBookingType(); 
//$locked = ' <i class="fa fa-lock"></i>';

$css = ($isAjax) ? "" : "col-lg-8 col-md-10 mt10";
$route = BookingRoute::model()->getRouteName($model->bkg_id);
/* @var $model Booking */
?>
<style>

    .border-bottom{
        padding-bottom:  10px;
        border-bottom: 1px solid #ddd
    }
    /*    .border-bottom:last-child{
            padding-bottom:  0;
            border-bottom: 0;
        }*/

    .bootstrap-timepicker-widget input  {
        border: 1px #555555 solid;color: #555555;
    }
    .navbar-nav > li > a {
        padding: 6px 30px;
    }
    label{
        font-weight: bold
    }

    .rounded {
        border:1px solid #ddd;
        border-radius: 10px;
        text-align: left;
        padding: 10px;

    }
</style>
<?php
$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode = $response->getData()->phone['ext'];
	$fname			 = $response->getData()->phone['firstName'];
	$lname			 = $response->getData()->phone['lastName'];
}
?>
<div class="row">
    <div class="<?= $css ?> col-xs-12">
        <div class="panel panel-default form-horizontal mb0">
            <div class="panel-body pb0 pt0">
                <div class="row border-bottom">
                    <div class="col-xs-6">
                        <label class="control-label" for="type">Booking Type : </label>
						<?= $bookingType[$model->bkg_booking_type] ?>
                    </div> 
                    <div class="col-xs-6 ">
                        <label class="control-label" for="route">Route : </label>
						<?= $route ?>
                    </div> 
                </div>

                <div class="row border-bottom">
                    <div class="col-xs-6">
                        <label class="control-label" for="name">Name : </label>
						<?= ucfirst($fname) . ' ' . ucfirst($lname); ?>
                    </div>
                    <div class="col-xs-6">
                        <label class="control-label" for="email">Email id : </label>
			<?php
				$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
				if ($response->getStatus())
				{
					$email	 = $response->getData()->email['email'];
				}
				?>
						<?= $email; ?>
                    </div>
                </div>
                <div class="row border-bottom">
                    <?
                        if ($contactNo != '')
                        {
                    ?>
                    <div class="col-xs-6">
                        <label class="control-label" for="contact">Contact : </label>
                        +<?= $countryCode . '-' . $contactNo; ?>
                    </div>
                    <? } ?>
                    <?
                        if ($model->bkgUserInfo->bkg_alt_contact_no != '')
                        {
                    ?>
                    <div class="col-xs-6">
                        <label class="control-label" for="alt_contact">Alternate Contact: </label>
						+<?= $model->bkgUserInfo->bkg_alt_country_code . '-' . $model->bkgUserInfo->bkg_alt_contact_no; ?>
                    </div>
                    <? } ?>


                </div>
                <div class="row border-bottom">
                    <div class="col-xs-6">
                        <label class="control-label" for="contact">Pickup Date : </label>
						<?= date('d/m/Y', strtotime($model->bkg_pickup_date)); ?>
                    </div>
                    <div class="col-xs-6">
                        <label class="control-label" for="contact">Pickup Time : </label>
						<?= date('h:i A', strtotime($model->bkg_pickup_date)); ?>
                    </div>
                </div>

                <div class="row border-bottom">
                    <div class="col-xs-6">
                        <label class="control-label" for="contact">Pickup Location : </label>
                        <br><?= $model->bkg_pickup_address; ?>
                    </div>
                    <div class="col-xs-6">
                        <label class="control-label" for="contact">Dropoff Location : </label>
                        <br><?= $model->bkg_drop_address; ?>
                    </div>      
                </div>
                <div class="row border-bottom">
                    <div class="col-xs-6">
                        <label class="control-label" for="car">Car Type : </label>
						<?= $cabType; ?>
                    </div><div class="col-xs-6">
                        <label class="control-label" for="car">Amount : </label>
						<?= "<i class='fa fa-inr'></i>" . $model->bkgInvoice->bkg_total_amount ?>
                    </div>
                </div>





				<?
				if ($model->bkgInvoice->bkg_advance_amount > 0 || $model->bkgInvoice->bkg_credits_used>0)
				{
					?>
					<div class="row border-bottom">
                                            <?if ($model->bkgInvoice->bkg_advance_amount > 0)
				                 {?>
						<div class="col-xs-6">
							<label class="control-label" for="car">Advance Paid : </label>
							<i class="fa fa-inr"></i><?= round($model->bkgInvoice->bkg_advance_amount) ?>
						</div>
                                            <? } ?>
                                             <?if ($model->bkgInvoice->bkg_credits_used>0)
				                 {?>
						<div class="col-xs-6">
							<label class="control-label" for="car">Credits Used : </label>
							<i class="fa fa-inr"></i><?= round($model->bkgInvoice->bkg_credits_used) ?>
						</div>
                                            <? } ?>
                                            <div class="col-xs-6">
							<label class="control-label" for="car">Due Amount : </label>
							<i class="fa fa-inr"></i><? if($model->bkgInvoice->bkg_due_amount>=0){
                                                     echo round($model->bkgInvoice->bkg_due_amount);
                                                    }else{
                                                      echo '0';  
                                                    } ?>
						</div>
					</div>
				<? } ?>
				<?
				$infosource = BookingAddInfo::model()->getInfosource('user');
				if ($model->bkgAddInfo->bkg_info_source != 0)
                {
					?>
					<div class="row border-bottom">
						<div class="col-xs-6">
							<label class="control-label" >Info source : </label>
							<?= $infosource[$model->bkgAddInfo->bkg_info_source] ?>
						</div>

					</div>
				<? } ?>
				<?
				//Review on complete
				if ($model->bkg_status == 6 || $model->bkg_status == 7)
				{
					?>
					<div class="row" >
						<?
						$ratingModel = Ratings::model()->getRatingbyBookingId($model->bkg_id);
						if ($ratingModel->rtg_customer_overall)
						{
							?> 
							<div class="col-xs-12 rounded">
								<div class="row">
									<?
									if ($ratingModel->rtg_customer_overall)
									{
										?> <div class='col-xs-6'>

											<?= $ratingModel->getAttributeLabel('rtg_customer_overall') ?><br>
											<?
											$this->widget('CStarRating', array(
												'model' => $ratingModel,
												'attribute' => 'rtg_customer_overall',
												'minRating' => 1,
												'maxRating' => 5,
												'starCount' => 5,
												'value' => $ratingModel->rtg_customer_overall,
												'readOnly' => true,
											));
											?></div><?
									}
									if ($ratingModel->rtg_customer_driver)
									{
										?> <div class='col-xs-6'>
											<?= $ratingModel->getAttributeLabel('rtg_customer_driver') ?><br>
											<?
											$this->widget('CStarRating', array(
												'model' => $ratingModel,
												'attribute' => 'rtg_customer_driver',
												'minRating' => 1,
												'maxRating' => 5,
												'starCount' => 5,
												'value' => $ratingModel->rtg_customer_driver,
												'readOnly' => true,
											));
											?></div><?
									}
									if ($ratingModel->rtg_customer_csr)
									{
										?> <div class='col-xs-6 mt20'>
											<?= $ratingModel->getAttributeLabel('rtg_customer_csr') ?><br>
											<?
											$this->widget('CStarRating', array(
												'model' => $ratingModel,
												'attribute' => 'rtg_customer_csr',
												'minRating' => 1,
												'maxRating' => 5,
												'starCount' => 5,
												'value' => $ratingModel->rtg_customer_csr,
												'readOnly' => true,
											));
											?></div><?
									}
									if ($ratingModel->rtg_customer_car)
									{
										?> <div class='col-xs-6 mt20'>
											<?= $ratingModel->getAttributeLabel('rtg_customer_car') ?><br>
											<?
											$this->widget('CStarRating', array(
												'model' => $ratingModel,
												'attribute' => 'rtg_customer_car',
												'minRating' => 1,
												'maxRating' => 5,
												'starCount' => 5,
												'value' => $ratingModel->rtg_customer_car,
												'readOnly' => true,
											));
											?></div><?
									}
									if ($ratingModel->rtg_customer_review)
									{
										?> <div class='col-xs-12 mt20'>
											<?= $ratingModel->getAttributeLabel('rtg_customer_review') ?><br>
											<div class="rounded"><?= $ratingModel->rtg_customer_review ?></div>
										</div><?
									}
									?>
								</div>
							</div>
							<?
						}
						else
						{
							?>

							<a class="btn btn-info btn-xs mt5" id="review" onclick="addRating(<?= $model->bkg_id ?>)" title="Add Rating Booking"><i class="fa fa-star-o"></i> Review</a> 
						<? } ?>

					</div>
				<? } ?>
            </div>
        </div>
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
