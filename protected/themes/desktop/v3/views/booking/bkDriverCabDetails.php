<div class="card-body pt5">

	<?php 
	$driverId = $model->bkgBcb->bcb_driver_id;
	
	if($driverId != ''){
		$drvContactId = ContactProfile::getByDrvId($driverId);
		$vehicleModel = $model->bkgBcb->bcbCab->vhcType->vht_model;
		if($model->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
		{
			$vehicleModel = OperatorVehicle::getCabModelName($model->bkgBcb->bcb_vendor_id, $model->bkgBcb->bcb_cab_id);
		}
	?>
	<div class="row mt-1">
		<div class="col-12 col-xl-2 p5 pl15">
			<span class="font-12 text-muted">Cab number</span><br>
			<span class="font-20 weight600"><?= $model->bkgBcb->bcbCab->vhc_number ?></span>
			<span><?= '('. $model->bkgBcb->bcbCab->vhcType->vht_make.' '.$vehicleModel.')'; ?></span>
		</div>

		<div class="col-5 col-xl-4 p5 pl15">
			<span class="font-12 text-muted">Driver name</span><br>
			<span class="font-18"><?= $model->bkgBcb->bcbDriver->drv_name; ?></span>
		</div>
		<div class="col-7 col-xl-4 p5">
			<span class="font-12 text-muted">Driver Phone</span><br>
			<?php 
			
			$driver_phone	        = $model->bkgBcb->bcbDriver->drvContact->getContactDetails($drvContactId); //Yii::app()->params['customerToDriver'];			
			$drvno					=($driver_phone['phn_phone_no'] !='') ? '+' . $driver_phone['phn_phone_country_code'] .'-'. $driver_phone['phn_phone_no'] : "";
			$driverPhone			= BookingPref::getDriverNumber($model, $drvno); 
			?>
			<span class="font-18">
			    <a href="tel:<?= $driverPhone; ?>" class="color-black">
				<?php
					echo $driverPhone;
					//echo '+' . $driver_phone['phn_phone_country_code'] .'-'. $driverPhone;
				?>
				</a>
			</span>
		</div>
		<?php
//			if ($model->bkgTrack->btk_drv_details_viewed != 0)
//			{
		?>
			<div class="col-12"><span class="mb-1 text-normal">Driver contact details & the assigned cab could change before pickup. Before contacting the driver be sure to check here for the most current information.</span></div>
			<div class="col-12 text-center mt-1"><span class="badge badge-pill badge-light-danger mb-1 text-normal hide" id="driverDetails">** Cancellation charges will now apply if booking is cancelled</span></div>
		<?php // } ?>
	</div>
		<?php } else{?>
			<div class="row mt-1">
				<div class="col-12"><span class="mb-1 text-normal">Not yet assigned - please check again later (2 hours before the pickup time).</span><br>
				</div>
			</div>
		<?php } ?>
</div>