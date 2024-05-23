<?php
/* @var $quote Quote */
if (!isset($categoryServiceClasses[$category][$class]))
{
	
}
$scvId	 = $categoryServiceClasses[$category][$class];
$sccId	 = $class;
$quote	 = $quotes[$scvId];
if ($quote->success)
{
	// Fare Breakup Tooltip
	$details = $this->renderPartial("bkFareBreakup", ['quote' => $quote, 'scvid' => $scvId], true);

	$promoDiscount	 = $quote->routeRates->discount;
	$discBaseAmount	 = $quote->routeRates->baseAmount - $promoDiscount;

	
	?>
	<div class="<?= $classRatesCol ?> bg-navy-<?= $sccId ?> eco-widget car-widget-4 text-center widget-border colClassRate<?= $class ?>">

		<?php
		if ($sccId == 4)
		{
			?>
			<span class="m0 text-uppercase text-muted" >
				<?php
//				$carModelsSelectTier = VehicleTypes::model()->getCabByVehicleTypeId($cabModel, $quote->routeRates->baseAmount, $categoryInfo->vct_id);
				$carModelsSelectTier = ServiceClassRule::model()->getCabByClassId($sccId, $quote->routeRates->baseAmount, $categoryInfo->vct_id);
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'bkg_vht_id',
					'asDropDownList' => FALSE,
					'options'		 => array('data' => new CJavaScriptExpression($carModelsSelectTier)),
					'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Model', 'class'=> 'serviceClassAreaModel', 'id' => 'service_class_area' . $scvId, 'onchange' => 'serviceClassArea(this,' . $scvId . ')')
				));
				?>
			</span>
			<span class="col-sm-12 text-danger hide srvclassarea<?= $scvId ?> " ></span>
			<?php
			/** @var RouteRates $routeRates */
			$routeRates		 = clone $quote->routeRates;
			$routeRates->calculateTotal();
			$stateTax		 = $routeRates->stateTax;
			$tollTax		 = $routeRates->tollTaxAmount;
			$driverAllowance = $routeRates->driverAllowance;
			//$gstRate		 = Filter::getServiceTaxRate();
			$gstRate		 = BookingInvoice::getGstTaxRate($quote->partnerId, $quote->tripType);
			?>
			<input type="hidden" value="<?= $quote->routeRates->baseAmount; ?>" id="baseamount<?= $scvId ?>">
			<input type="hidden" value="<?= $quote->routeRates->discount; ?>" id="discamount<?= $scvId ?>">
			<input type="hidden" value="<?= $stateTax; ?>" id="statetax<?= $scvId ?>">
			<input type="hidden" value="<?= $tollTax; ?>" id="tolltax<?= $scvId ?>">
			<input type="hidden" value="<?= $driverAllowance; ?>" id="driverallowance<?= $scvId ?>">
			<input type="hidden" value="<?= $gstRate; ?>" id="gstrate<?= $scvId ?>"> 
		<?php }
		?>

		<?php
		if ($quote->routeRates->baseAmount > $discBaseAmount || $sccId == 4)
		{
			?>
			<?php
			if ($sccId == 4)
			{
				?>
				<span class="m0 text-uppercase text-muted" id="basefarelbl<?= $scvId ?>"><b></b></span><br>
				<span style="font-size: 16px; line-height: normal; font-weight: bold;">
			<!--				<i class="fa fa-inr"></i>-->
			<? //= $quote->routeRates->baseAmount;    ?>
					<strike style="font-weight: bold;"><span id="extraamount<?= $scvId ?>"></span></strike><br>
				</span>
				<span style="font-size: 22px; color: #2458aa; line-height: normal;font-weight: bold;">
					<span id="discount<?= $scvId ?>"></span>
					<a data-toggle="popover" id="b<?= $scvId ?>"  data-placement="top" data-html="true" data-content="<?= $details ?>" style="font-size:15px;"></a>
				</span>
				<?php
			}
			else
			{
				?>
				<span class="m0 text-uppercase text-muted" ><b>Base Fare</b></span><br>
				<span style="font-size: 16px; line-height: normal; font-weight: bold;">
					<i class="fa fa-inr"></i><strike style="font-weight: bold;"><?= $quote->routeRates->baseAmount; ?></strike><br>

				</span>
			<?php } ?>
		<?php }
		?>	
		<?php
		if ($sccId != 4)
		{
			?>
			<span style="font-size: 22px; color: #2458aa; line-height: normal;font-weight: bold;">
				<i class="fa fa-inr"></i><?= $discBaseAmount ?><sup>*</sup><a data-toggle="popover" id="b<?= $scvId ?>"  data-placement="top" data-html="true" data-content="<?= $details ?>" style="font-size:15px;"><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Fare Breakup" data-placement="botton"></i></a>
			</span>
			<?php } ?>
		<div class="row">
			<?php
			if ($model->bkg_booking_type == 1 || $model->bkg_booking_type == 2 || $model->bkg_booking_type == 3)
			{
				if ($sccId != 4)
				{
					?>
					<div class="col-xs-12 mt10 text-uppercase">
						<button type="button" value="<?= $scvId ?>" dataclass= "<?= $sccId ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" kms="<?= $quote->routeDistance->tripDistance ?>" duration="<?= $quote->routeDuration->totalMinutes ?>" data-cabtype="<?= $categoryInfo->vct_label ?>" name="bookButton" class="btn next3-btn mt10" onclick="validateForm1(this);">
							<b>Book Now</b>
						</button>
					</div>

					<div class="col-xs-12 mb10"> 
						<a href="javascript:void(0)" data-cat="<?= $scvId ?>" data-class="<?= $category ?>" data-cab="<?= $scvId ?>" class="btn next6-btn font11 mt5 btnPackage pkgShow pkgCat<?= $category ?> pkgClass<?= $class ?>">Show related <br> packaged tours</a>
						<a href="javascript:void(0)" data-cat="<?= $scvId ?>" data-class="<?= $category ?>" data-cab="<?= $scvId ?>" class="btn btn-default mt5 font11  btnPackage pkgHide hide  pkgCat<?= $category ?> pkgClass<?= $class ?>">Hide related <br> packaged tours</a>
					</div>
					<?php
				}
				else
				{
					?>
					<div class="col-xs-12 mt10 text-uppercase">
						<button type="button" value="<?= $scvId ?>" dataclass="<?= $sccId ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" kms="<?= $quote->routeDistance->tripDistance ?>" duration="<?= $quote->routeDuration->totalMinutes ?>" data-cabtype="<?= $categoryInfo->vct_label ?>" name="bookButton" class="btn next3-btn mt10" onclick="validateForm1(this);">
							<b>Book Now</b>
						</button>
					</div>
					<?php
				}
			}
			else
			{
				?>						            
				<button type="button" value="<?= $scvId ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" kms="<?= $quote->routeDistance->tripDistance ?>" duration="<?= $quote->routeDuration->totalMinutes ?>" data-cabtype="<?= $categoryInfo->vct_label ?>" name="bookButton" class="btn next3-btn mt10 " onclick="validateForm1(this);">
					<b>Book Now</b>
				</button>
	<?php }
	?>							</div>
	</div>
	<?php
}
else
{
	?>
	<div class="<?= $classRatesCol ?> bg-navy-<?= $sccId ?> eco-widget car-widget-4 text-center widget-border">
		<img src="/images/no_cabs_available.png" alt="No Cabs Available">
	</div>
<?php } ?>


