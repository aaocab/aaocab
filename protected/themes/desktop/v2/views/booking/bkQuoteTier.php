<?php
/* @var $quote Quote */
if (!isset($categoryServiceClasses[$category][$class]))
{
	
}
$scvId	 = $categoryServiceClasses[$category][$class];
$sccId	 = $class;

$parentId			 = SvcClassVhcCat::getBaseSVCId($sccId, $categoryInfo->vct_id);
if($sccId==4 || $sccId==5)
{
  $scvId =  $parentId;
}
if (array_key_exists($parentId, $quotes))
{
	$quote = $quotes[$parentId];
}
else
{
	$quote = $quotes[$scvId];
}
$showNotAvailable	 = "hide";
$showModelTier		 = "";
if (!$quote->success)
{
	$showNotAvailable = "";
	goto notAvailable;
}

$showBookNowDiv = true;

$rowModelSVC = SvcClassVhcCat::getListwithModel($sccId, $categoryInfo->vct_id);
if (!$rowModelSVC || $rowModelSVC->getRowCount() == 0 || !array_key_exists($parentId, $quotes))
{
	$showModelTier	 = "hide";
	$showBookNowDiv	 = false;
	if ($sccId == 4)
	{
		$showNotAvailable = "";
		goto notAvailable;
	}
}

// Fare Breakup Tooltip
$details = $this->renderPartial("bkFareBreakup", ['quote' => $quote, 'scvid' => $scvId], true);

$promoDiscount	 = $quote->routeRates->discount;
$discBaseAmount	 = $quote->routeRates->baseAmount - $promoDiscount;
$routeRates		 = clone $quote->routeRates;
$showOrginalFare = ($promoDiscount > 0) ? "" : "hide";
$logincls		 = (Yii::app()->user->getId() == 0) ? '' : 'hide';
?>
<div class="<?= $classRatesCol ?> bg-navy-<?= $sccId ?> eco-widget car-widget-4 text-center colClassRate<?= $class ?>">
	<div class="clsBaseFare  <?= $showOrginalFare ?>">
<!--		<span class="m0 text-uppercase text-muted" id="basefarelbl<?= $scvId ?>"><b>Base Fare</b></span><br>-->
		WAS: <span class="fare-1 font-24 color-gray">
		&#x20B9;<strike class="clsOriginalFare<?= $scvId ?>"><?= $quote->routeRates->baseAmount; ?></strike><br>
	</span>
	</div>
	<span class="fare-2">
		NOW: <b class="font-18 color-orange" id="discount<?= $parentId ?>">&#x20B9;<?= $discBaseAmount ?></b>
                <sup><i type="button" class="farebreakup fas fa-info-circle font-12" id="b<?= $parentId ?>"  data-toggle="tooltip" data-placement="bottom" data-html="true"  title="<?= $details ?>"></i></sup>
		<?php
		if ($model->bkg_booking_type == 1 || $model->bkg_booking_type == 2 || $model->bkg_booking_type == 3)
		{
			?>
		<a href="javascript:void(0);" data-value="<?= $scvId ?>" dataclass= "<?= $sccId ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" kms="<?= $quote->routeDistance->tripDistance ?>" duration="<?= $quote->routeDuration->totalMinutes ?>" data-cabtype="<?= $categoryInfo->vct_label ?>" class="nav-link font-14 pl0 pr0 login <?= $logincls ?>" onclick="validateForm1(this);">Login to save upto 20%</a>
		<?php } ?>
	</span>
	<div class="row">
		<?php
		if (in_array($model->bkg_booking_type, [1, 2, 3]))
		{
			?>
				<div class="col-12 text-uppercase">
					<button type="button" value="<?= $scvId ?>" dataclass= "<?= $sccId ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" kms="<?= $quote->routeDistance->tripDistance ?>" duration="<?= $quote->routeDuration->totalMinutes ?>" data-cabtype="<?= $categoryInfo->vct_label ?>" name="bookButton" class="btn next3-btn mt10 btnCabType<?=$parentId?>" onclick="validateForm1(this, '<?php echo $sccId ?>');">
						<b>Reserve</b>
					</button>
				</div>

		<div class="col-12 mb10"> 
				<div class="mt5"><a href="javascript:void(0)" data-cat="<?= $scvId ?>" data-class="<?= $category ?>" data-cab="<?= $scvId ?>" class="btnPackage pkgShow hide pkgCat<?= $category ?> pkgClass<?= $class ?>">See package tours</a></div>
				<div class=""><a href="javascript:void(0)" data-cat="<?= $scvId ?>" data-class="<?= $category ?>" data-cab="<?= $scvId ?>" class="btnPackage pkgHide hide  pkgCat<?= $category ?> pkgClass<?= $class ?>">Hide package tours</a></div>
			</div>
			<?php
		}
		else
		{
			?>
		<div class="col-12">
				<button type="button" value="<?= $parentId ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" kms="<?= $quote->routeDistance->tripDistance ?>" duration="<?= $quote->routeDuration->totalMinutes ?>" data-cabtype="<?= $categoryInfo->vct_label ?>" name="bookButton" class="btn next3-btn mt10 ml20 btnCabType<?=$parentId?>" onclick="validateForm1(this);">
					<b>Reserve</b>
				</button>
			</div>
		<?php }
		?>							</div>
	<span class="m0 text-uppercase text-muted <?= $showModelTier ?>" >
		<select class="form-control serviceClassAreaModel font-11"  id="service_class_area<?php echo $scvId; ?>" onchange="serviceClassArea(this, '<?php echo $parentId ?>')" style="width: 100%;">
			<option value="">Select model</option>
			<?php
			$carModelsSelectTier = CJSON::decode($carModelsSelectTier);
			foreach ($rowModelSVC as $row)
			{
				$scvIdVht		 = $row['scv_id'];
				$scvIdVhtParent	 = $parentId;
				$parentPrice	 = $quotes[$scvIdVhtParent]->routeRates->baseAmount;
				$diff			 = $quotes[$scvIdVht]->routeRates->baseAmount - $parentPrice
				?>
				<option value="<?= $row['scv_id'] ?>" baseAmount="<?= $quotes[$scvIdVht]->routeRates->baseAmount ?>" discAmount="<?= $quotes[$scvIdVht]->routeRates->discount ?>" scvModel="<?= $row['scv_model'] ?>"><?php echo "{$row['vht_name']} (₹{$diff})"; ?></option>
			<?php } ?>
		</select>
		
	</span>
	<span class="col-md-12 text-danger mr5 hide srvclassarea<?= $scvId ?> " ></span>
	<?php
	/** @var RouteRates $routeRates */
	//$routeRates		 = clone $quote->routeRates;
	$routeRates->calculateTotal();
	$stateTax		 = $routeRates->stateTax;
	$tollTax		 = $routeRates->tollTaxAmount;
	$driverAllowance = $routeRates->driverAllowance;
	//$gstRate		 = Filter::getServiceTaxRate();
	$gstRate				 = BookingInvoice::getGstTaxRate($quote->partnerId, $quote->tripType);
	?>
	<input type="hidden" value="<?= $routeRates->baseAmount; ?>" id="baseamount<?= $scvId ?>">
	<input type="hidden" value="<?= $routeRates->discount; ?>" id="discamount<?= $scvId ?>">
	<input type="hidden" value="<?= $stateTax; ?>" id="statetax<?= $scvId ?>">
	<input type="hidden" value="<?= $tollTax; ?>" id="tolltax<?= $scvId ?>">
	<input type="hidden" value="<?= $driverAllowance; ?>" id="driverallowance<?= $scvId ?>">
	<input type="hidden" value="<?= $gstRate; ?>" id="gstrate<?= $scvId ?>">
</div>
<?php
notAvailable:
?>
<div class="<?= $classRatesCol ?> bg-navy-<?= $sccId ?> eco-widget car-widget-4 text-center widget-border <?= $showNotAvailable ?>">
	<img src="/images/sold_out.png?v=0.1" alt="No Cabs Available" style="width: 100%">
</div>


