<?php
$totalAdvance				 = PaymentGateway::model()->getTotalAdvance($model->bkg_id);
$vehicalModel				 = new VehicleTypes();
$vctId						 = $model->bkgSvcClassVhcCat->scv_vct_id;
$car_type					 = SvcClassVhcCat::model()->getVctSvcList('string', '', $vctId);
$cabModel					 = VehicleCategory::model()->findByPk($vctId);
$model->bkgInvoice->calculateConvenienceFee(0);
$model->bkgInvoice->calculateTotal();
$priceRule					 = AreaPriceRule::model()->getValues($model->bkg_from_city_id, $model->bkg_vehicle_type_id, $model->bkg_booking_type);
$prr_day_driver_allowance	 = $priceRule['prr_day_driver_allowance'];
$prr_Night_driver_allowance	 = $priceRule['prr_night_driver_allowance'];
//$cancelTimes				 = CancellationPolicyRule::getCancelationTimeRange($model->bkg_id, 1);
$cancelTimes_new			 = CancellationPolicy::initiateRequest($model);
$freeAction					 = 0;
$paidAction					 = 0;
if (date('Y-m-d h:i:s') > date('Y-m-d h:i:s', strtotime('-24 hour', strtotime($model->bkg_pickup_date))))
{
	$freeAction = 0;
}
if (date('Y-m-d h:i:s') > date('Y-m-d h:i:s', strtotime('-6 hour', strtotime($model->bkg_pickup_date))))
{
	$paidAction = 1;
}
//getting the Price rule array for fare inclusion and exclusion
$newpriceRule = PriceRule::getByCity($model->bkg_from_city_id, $model->bkg_booking_type, $model->bkg_vehicle_type_id);
if (!empty($newpriceRule))
{
	$prarr = $newpriceRule->attributes;
}
?>
<div class="card-body">
	<div class="row">
		<?php if($model->bkgTrack->btk_drv_details_viewed == 0){ ?>
		<div class="col-12 col-md-6 col-xl-4 freecancellation">
			<div class="card border shadow-none mb-1 app-file-info">
				<div class="card-header gradient-1 p5"></div>
				<div class="card-body p-50">
					<div class="app-file-recent-details mt-1">
						<div class="app-file-name font-weight-bold text-center mb-1">Free cancellation period</div>
						<div class="d-inline-block font-11"><?php echo date('d M Y H:i a', strtotime($model->bkg_create_date)); ?></div>
						<div class="d-inline-block font-11"><i class="fas fa-arrow-right"></i></div>
						<div class="d-inline-block font-11"><?php echo date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[0])); ?></div>

					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6 col-xl-4 cancharge">
			<div class="card border shadow-none mb-1 app-file-info">
				<div class="card-header gradient-2 p5"></div>
				<div class="card-body p-50">
					<div class="app-file-recent-details mt-1">
						<div class="app-file-name font-weight-bold text-center mb-1">Cancellation Charge: <?php echo Filter::moneyFormatter(array_values($cancelTimes_new->slabs)[1]); ?></div>
						<div class="d-inline-block font-11"><?php echo date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[0])); ?></div>
						<div class="d-inline-block font-11"><i class="fas fa-arrow-right"></i></div>
						<div class="d-inline-block font-11"><?php echo date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[1])); ?></div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<div class="col-12 col-md-6 col-xl-4">
			<div class="card border shadow-none mb-1 app-file-info">
				<div class="card-header gradient-3 p5"></div>
				<div class="card-body p-50">
					<div class="app-file-recent-details mt-1">
						<div class="app-file-name font-weight-bold text-center mb-1">No Refund</div>
						<div class="d-inline-block font-11 drvdetailsviewedtime"><?php echo ($model->bkgTrack->btk_drv_details_viewed_datetime != '')? date('d M Y H:i a', strtotime($model->bkgTrack->btk_drv_details_viewed_datetime)): date('d M Y H:i a', strtotime($model->bkg_pickup_date)); ?></div>
						<div class="d-inline-block font-11"><i class="fas fa-arrow-right"></i></div>
						<div class="d-inline-block font-11">After this</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	$cancellationPoints = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_CANCELLATION); //print_r($cancellationPoints);
	if (count($cancellationPoints) > 0)
	{
		echo "<ol style='font-size:10px; line-height:15px;padding-left:25px;'>";
		foreach ($cancellationPoints as $c)
		{
			echo "<li class= 'text-uppercase' style='list-style-type:  circle'>" . $c['tnp_text'] . "</li>";
		}
		echo "</ol>";
	}
	?>
</div>