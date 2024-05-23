<?php
	
	if(in_array($model->bkg_status, [6,7]))
	{
		$txtLebel = "Adv + Driver Collected";
		$amountPaid = $model->bkgInvoice->bkg_advance_amount + $model->bkgInvoice->bkg_vendor_collected;
	}
	else
	{
		$txtLebel = "Adv paid";
		$amountPaid = $model->bkgInvoice->bkg_advance_amount;
	}
?>

<div class="card-body pt5">
	<div class="row">
		<div class="col-12 text-right pb5">
			<!--<a href="#" class="float-right edit-icons">edit</a>-->
		</div>
		<div class="col-12 col-xl-8">
			<div class="row">
				<div class="col-4 pr0">
					<p class="mb0"><span class="font-22 weight600"><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_total_amount); ?></span></p>
					<p class="mb0 weight400 lineheight14">Total fare</p>
				</div>
				<div class="col-4 pr0 text-center">
					<p class="mb0"><span class="font-22 weight600"><?php echo Filter::moneyFormatter(round($amountPaid)); ?></span></p>
					<p class="mb0 weight400 lineheight14"><?php echo $txtLebel; ?></p>
				</div>
				<div class="col-4 pl0 text-right">
					<p class="mb0"><span class="font-22 weight600"><?php echo ($model->bkgInvoice->bkg_due_amount > 0) ? Filter::moneyFormatter(round($model->bkgInvoice->bkg_due_amount)) : '0'; ?></span></p>
					<p class="mb0 weight400 lineheight14">Pay to driver</p>
				</div>
			</div>
			<hr>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-xl-8">
			<div class="d-flex justify-content-between mb-1 lineheight14">
				<div class="sales-info d-flex align-items-center">
					<div class="sales-info-content">
						<span class="mb-0">Distance quoted of the trip:</span><br>
						<small class="text-muted font-10">(based on pickup and drop addresses provided)</small>
					</div>
				</div>
				<span class="mb-0 text-right"><b><?php echo $model->bkg_trip_distance; ?></b> Km<br><small class="text-muted font-10">(Charges after <?php echo $model->bkg_trip_distance; ?> Km @ ₹<?php echo round($model->bkgInvoice->bkg_rate_per_km_extra, 2); ?>/km)</small></span>
			</div>
			<div class="d-flex justify-content-between my-1">
				<div class="sales-info d-flex align-items-center">
					<div class="sales-info-content">
						<span class="mb-0">Total days for the trip:</span>
					</div>
				</div>
				<span class="mb-0 text-right"><b><?php echo $durationInWords ?></b></span>
			</div>
			<div class="d-flex justify-content-between my-1">
			<div class="col-12"  >
				<?= $this->renderPartial("fare", ["model" => $model]); ?>
			</div></div>
			<? /* /?>
			  <div class="d-flex justify-content-between my-1 ">
			  <div class="sales-info d-flex align-items-center">
			  <div class="sales-info-content">
			  <span class="mb-0">Base fare:</span>
			  </div>
			  </div>
			  <span class="mb-0 text-right"><b><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_base_amount); ?></b></span>
			  </div>
			  <div class="d-flex justify-content-between my-1 <?= ($model->bkgInvoice->bkg_additional_charge > 0) ? '' : 'hide' ?>">
			  <div class="sales-info d-flex align-items-center">
			  <div class="sales-info-content">
			  <span class="mb-0">Additional Charge:</span>
			  </div>
			  </div>
			  <span class="mb-0 text-right"><b><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_additional_charge); ?></b></span>
			  </div>
			  <div class="d-flex justify-content-between my-1 <?= ($model->bkgInvoice->bkg_discount_amount > 0) ? '' : 'hide' ?>">
			  <div class="sales-info d-flex align-items-center">
			  <div class="sales-info-content">
			  <span class="mb-0">Discount <span class="font-11">(Promo: <strong><span class="txtPromoCode color-green"><?= $model->bkgInvoice['bkg_promo1_code'] ?></span> </strong> )</span>:</span>
			  </div>
			  </div>
			  <span class="mb-0 text-right">(-)<b><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_discount_amount); ?></b></span>
			  </div>
			  <div class="d-flex justify-content-between my-1 <?= ($model->bkgInvoice->bkg_extra_discount_amount > 0) ? '' : 'hide' ?>">
			  <div class="sales-info d-flex align-items-center">
			  <div class="sales-info-content">
			  <span class="mb-0">One-Time Discount :</span>
			  </div>
			  </div>
			  <span class="mb-0 text-right">(-)<b><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_extra_discount_amount); ?></b></span>
			  </div>
			  <div class="d-flex justify-content-between my-1 <?= ($model->bkgInvoice->bkg_discount_amount > 0) ? '' : 'hide' ?>">
			  <div class="sales-info d-flex align-items-center">
			  <div class="sales-info-content">
			  <span class="mb-0 weight500">Net Base Fare:</span>
			  </div>
			  </div>
			  <span class="mb-0 text-right"><b><?php echo Filter::moneyFormatter(($model->bkgInvoice->bkg_base_amount - $model->bkgInvoice->bkg_discount_amount)); ?></b></span>
			  </div>
			  <div class="d-flex justify-content-between my-1 <?= ($model->bkgInvoice->bkg_addon_charges != 0) ? '' : 'hide' ?>">
			  <div class="sales-info d-flex align-items-center">
			  <div class="sales-info-content">
			  <span class="mb-0">Addon charge:</span>
			  </div>
			  </div>
			  <span class="mb-0 text-right"><b><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_addon_charges); ?></b></span>
			  </div>
			  <div class="d-flex justify-content-between my-1 <?= ($model->bkgInvoice->bkg_driver_allowance_amount > 0) ? '' : 'hide' ?>">
			  <div class="sales-info d-flex align-items-center">
			  <div class="sales-info-content">
			  <span class="mb-0">Driver allowance:</span>
			  </div>
			  </div>
			  <span class="mb-0 text-right"><b><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_driver_allowance_amount); ?></b></span>
			  </div>
			  <div class="d-flex justify-content-between my-1">
			  <div class="sales-info d-flex align-items-center">
			  <div class="sales-info-content">
			  <span class="mb-0">State tax:</span>
			  </div>
			  </div>
			  <span class="mb-0 text-right"><b><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_state_tax); ?></b></span>
			  </div>
			  <div class="d-flex justify-content-between my-1">
			  <div class="sales-info d-flex align-items-center">
			  <div class="sales-info-content">
			  <span class="mb-0">Toll tax:</span>
			  </div>
			  </div>
			  <span class="mb-0 text-right"><b><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_toll_tax); ?></b></span>
			  </div>
			  <?php
			  $staxrate	 = $model->bkgInvoice->getServiceTaxRate();
			  $taxLabel	 = ($staxrate == 5) ? 'GST' : 'Service Tax ';
			  if ($model->bkgInvoice->bkg_sgst > 0)
			  {
			  ?>
			  <div class="d-flex justify-content-between my-1">
			  <div class="sales-info d-flex align-items-center">
			  <div class="sales-info-content">
			  <span class="mb-0">SGST (@<?= Yii::app()->params['sgst'] ?>%):</span>
			  </div>
			  </div>
			  <span class="mb-0 text-right"><b><?php echo Filter::moneyFormatter(((Yii::app()->params['sgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0); ?></b></span>
			  </div>
			  <?php
			  }
			  if ($model->bkgInvoice->bkg_cgst > 0)
			  {
			  ?>
			  <div class="d-flex justify-content-between my-1">
			  <div class="sales-info d-flex align-items-center">
			  <div class="sales-info-content">
			  <span class="mb-0">CGST (@<?= Yii::app()->params['cgst'] ?>%):</span>
			  </div>
			  </div>
			  <span class="mb-0 text-right"><b><?php echo Filter::moneyFormatter(((Yii::app()->params['cgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0); ?></b></span>
			  </div>
			  <?php
			  }
			  if ($model->bkgInvoice->bkg_igst > 0)
			  {
			  ?>
			  <div class="d-flex justify-content-between my-1">
			  <div class="sales-info d-flex align-items-center">
			  <div class="sales-info-content">
			  <span class="mb-0">IGST (@<?= Yii::app()->params['igst'] ?>%):</span>
			  </div>
			  </div>
			  <span class="mb-0 text-right"><b><?php echo Filter::moneyFormatter(((Yii::app()->params['igst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0); ?></b></span>
			  </div>
			  <?php
			  }
			  if ($staxrate != 5)
			  {
			  ?>
			  <div class="d-flex justify-content-between my-1">
			  <div class="sales-info d-flex align-items-center">
			  <div class="sales-info-content">
			  <span class="mb-0">Other taxes:</span>
			  <small class="text-muted">(Including State Tax / Green Tax etc)</small>
			  </div>
			  </div>
			  <span class="mb-0 text-right"><b><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_service_tax); ?></b></span>
			  </div>
			  <?php
			  }
			  if ($model->bkgInvoice->bkg_extra_min > 0)
			  {
			  ?>

			  <div class="d-flex justify-content-between my-1">
			  <div class="sales-info d-flex align-items-center">
			  <div class="sales-info-content">
			  <span class="mb-0">Extra Minutes Charge:</span>
			  </div>
			  </div>
			  <span class="mb-0 text-right"><b><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_extra_per_min_charge); ?></b></span>
			  </div>
			  <?php
			  }
			  ?>

			  <?/ */ ?>
		</div>
	</div>
</div>