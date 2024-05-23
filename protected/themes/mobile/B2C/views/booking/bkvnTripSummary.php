<style>
    .card{
		box-shadow:0 0 0 0!important;
	}
</style>
<div class="content-boxed-widget2 mb5 list-view-panel p5 text-center font-14">
	<?php echo $this->pageTitle; ?>
</div>
<?php
/* @var $bModel Booking  */
/* @var $model Booking */
/* @var $model Booking */
$model = $bModels[0];
?>
<?php
if ($error != "")
{
	?>
	<div class="content-boxed-widget2 mb5 list-view-panel p5 text-center font-14">
		<?php
		echo $error;
		goto end;
		?>
	</div>
	<?php
}
if (!empty($model))
{
	$luggageCapacity = Stub\common\LuggageCapacity::init($model->bkgSvcClassVhcCat->scv_vct_id, $model->bkgSvcClassVhcCat->scv_scc_id, $model->bkgAddInfo->bkg_no_person);
	?>
	<div class="content-boxed-widget2 mb5 list-view-panel p5 text-center font-14">
	    <span class="font-18"><b>Trip ID:<?= $cabModel->bcb_id ?></b></span><br>
		<span class="color-gray2"><?= date('jS M Y (D) h:i A', strtotime($model->bkg_pickup_date)); ?></span><br>
	    <span style="color:red">* Driver App must be used.   </span>
	</div>
	<div class="content-boxed-widget2 mb5 list-view-panel">
	    <div class="one-half font-12">Vendor Amount:</div>
	    <div class="one-half last-column text-right"><span class="font-22">&#x20B9;<b><?= round($cabModel->bcb_vendor_amount) ?></b></span></div>
	    <div class="clear"></div>
	</div>
	<?php
	if ($model->bkgPref->bkg_is_gozonow != 1)
	{
		?>
		<div class="content-boxed-widget2 mb5 list-view-panel">
		    <div class="one-half font-12">Amount To Collect:</div>
		    <div class="one-half last-column text-right"><span class="font-22">&#x20B9;<b><?= round($model->bkgInvoice->bkg_due_amount) ?></b></span></div>
		    <div class="clear"></div>
		</div>
		<?
	}
	$showVal			 = 4;
	$pickupAddress		 = $model->bkg_pickup_address;
	$pickupAddressArr	 = explode(',', $pickupAddress);
	$cnt1				 = count($pickupAddressArr);
	$cnt1val			 = ($cnt1 > $showVal) ? ($cnt1 - $showVal) : $cnt1;
	$maskedPickupAddress = 'xxx xxx ' . implode(', ', array_slice($pickupAddressArr, $cnt1val));

	$dropAddress		 = $model->bkg_drop_address;
	$dropAddressArr		 = explode(',', $dropAddress);
	$cnt2				 = count($dropAddressArr);
	$cnt2val			 = ($cnt2 > $showVal) ? ($cnt2 - $showVal) : $cnt2;
	$maskedDropAddress	 = 'xxx xxx ' . implode(', ', array_slice($dropAddressArr, $cnt2val));
	?>
	<div class="content-boxed-widget2 mb5 list-view-panel">
	    <div class="one-half font-12">Pickup Point:</div>
	    <div class="one-half last-column text-right"><span class="font-18"> <?= $maskedPickupAddress ?> </span></div>
	    <div class="clear"></div>
	</div>
	<div class="content-boxed-widget2 mb5 list-view-panel">
	    <div class="one-half font-12">Drop Point:</div>
	    <div class="one-half last-column text-right"><span class="font-18">  <?= $maskedDropAddress ?> </span></div>
	    <div class="clear"></div>
	</div>





	<?php
	$correctimg			 = '<img src="/images/email/correct.png" style="display:inline;">';
	$crossimg			 = '<img src="/images/email/cross.png" style="display:inline;">';
	$vehicleModel		 = $model->bkgBcb->bcbCab->vhcType->vht_model;
	if ($model->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
	{
		$vehicleModel = OperatorVehicle::getCabModelName($model->bkgBcb->bcb_vendor_id, $model->bkgBcb->bcb_cab_id);
	}
	?>
	<div class="content-boxed-widget2 mb5 list-view-panel line-height16 font-14 flex">
	    <div class="one-half mr10 mb20" style="">
	        <span class="color-orange font-12">Included kms:</span> <br><b><?= $model->bkg_trip_distance; ?></b>
	    </div>
	    <div class="one-half last-column mb20">
	        <span class="color-orange font-12">Extra charge per km:</span> <br>&#x20B9;<b><?= $model->bkgInvoice->bkg_rate_per_km_extra; ?></b>
	    </div>
	    <div class="one-half mr10 mb20">
	        <span class="color-orange font-12">Service tier:</span> <br><b><?= $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . ' (' . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . $vhcModel . ')'; ?></b>
	    </div>
	    <div class="one-half last-column mb20">
	        <span class="color-orange font-12">Cab type booked:</span> <br><b><?= $model->bkgBcb->bcbCab->vhcType->vht_make; ?> - <?= $vehicleModel; ?></b>
	    </div>
	    <div class="one-half mr10 mb20">
	        <span class="color-orange font-12">Night pickup allowance:</span> <br><span><b><?= ($model->bkgInvoice->bkg_night_pickup_included > 0) ? $correctimg . ' included' : $crossimg . ' not included'; ?></b></span>
	    </div>
	    <div class="one-half last-column mb20">
	        <span class="color-orange font-12">Night drop allowance:</span> <br><b><?= ($model->bkgInvoice->bkg_night_drop_included > 0) ? $correctimg . ' included' : $crossimg . ' not included'; ?></b>
	    </div>
	    <div class="one-half mr10 mb20">
	        <span class="color-orange font-12" onclick="showIncExc('toll')"> Toll tax: <i class="far fa-plus-square ml5"></i></span> <br>
			<span><b><?= ($model->bkgInvoice->bkg_toll_tax > 0) ? $correctimg . ' included' : $crossimg . ' not included'; ?></b></span>
			<span id="tollDesc" class="hide font-10"><BR>
				<?php
				if ($model->bkgInvoice->bkg_is_toll_tax_included == 1)
				{
					?>   
					Our estimate of toll charges for travel on this route are ₹<?= ($model->bkgInvoice->bkg_toll_tax != '') ? $model->bkgInvoice->bkg_toll_tax : 0; ?>. 
					Toll taxes (even if amount is different) is already included in the trip cost<?php
				}
				else
				{
					?>
					Our estimate of toll charges  on this route are ₹<b><?= $model->bkgInvoice->bkg_toll_tax ?></b>. Any charges incurred is payable by customer.
					<?php
				}
				?>
			</span>
	    </div>
	    <div class="one-half last-column mb20">
	        <span class="color-orange font-12">MCD:</span> <br><b><?= $crossimg ?> not included</b>
	    </div>
	    <div class="one-half mr10 mb20">
	        <span class="color-orange font-12" onclick="showIncExc('parking')"> Parking charges: <i class="far fa-plus-square ml5"></i></span> <br>
			<span><b><?= ($model->bkgInvoice->bkg_parking_charge > 0) ? "&#x20B9;" . $model->bkgInvoice->bkg_parking_charge : ''; ?>&nbsp;<?= ($model->bkgInvoice->bkg_parking_charge > 0) ? $correctimg . ' included' : $crossimg . ' not included'; ?>.</b></span>
			<span id="parkingDesc" class="hide font-10"><BR>
				<?php
				if ($model->bkgInvoice->bkg_parking_charge > 0)
				{
					?> Parking charges are prepaid upto ₹<?= round($model->bkgInvoice->bkg_parking_charge) ?>.<?php } ?> 
				Customer will directly pay for parking charges after the total parking cost for the trip exceeds ₹<?= round($model->bkgInvoice->bkg_parking_charge) ?>. Driver must upload all parking receipts for payments made by drive.
			</span>
	    </div>
	    <div class="one-half last-column mb20">
	        <span class="color-orange font-12" onclick="showIncExc('state')"> State taxes: <i class="far fa-plus-square ml5"></i></span> <br>
			<span><b><?= ($model->bkgInvoice->bkg_state_tax > 0) ? $correctimg . ' included' : $crossimg . ' not included'; ?></b></span>
			<span id="stateDesc" class="hide font-10"><BR>
				<?php
				if ($model->bkgInvoice->bkg_is_state_tax_included == 1)
				{
					?>   
					Our estimate of State Tax for travel on this route are ₹<?= ($model->bkgInvoice->bkg_state_tax != '') ? $model->bkgInvoice->bkg_state_tax : 0; ?>. 
					State Taxes (even if amount is different) is already included in the trip cost<?php
				}
				else
				{
					?>
					Our estimate of State Tax on this route are ₹<b><?= $model->bkgInvoice->bkg_state_tax ?></b>. Any charges incurred is payable by customer.
					<?php
				}
				?>
			</span>
	    </div>
	    <div class="one-half mr10 mb25">
	        <span class="color-orange font-12" onclick="showIncExc('airport')"> Airport entry charges: <i class="far fa-plus-square ml5"></i></span> <br>
			<span><b><?= ($model->bkgInvoice->bkg_airport_entry_fee > 0) ? $correctimg . ' included' : $crossimg . ' not included'; ?>. </b></span>
			<span id="airportDesc" class="hide font-10"><BR>
				<?php
				if ($model->bkgInvoice->bkg_is_airport_fee_included != 1)
				{
					?>   
					Our estimate of airport entry charges on this route are ₹ <?= $model->bkgInvoice->bkg_airport_entry_fee ?> . Any charges incurred is payable by customer. <?php
				}
				else
				{
					?>

					Our estimate of airport entry charges on this route are ₹<?= ($model->bkgInvoice->bkg_airport_entry_fee != '') ? $model->bkgInvoice->bkg_airport_entry_fee : 0; ?>. 
					airport entry charges (even if amount is different) is already included in the trip cost 
					<?php
				}
				?>
			</span>
	    </div>
	    <div class="one-half last-column mb20">
	        <span class="color-orange font-12">Journey break:</span> <br><b>&nbsp;</b>
	    </div>
	    <div class="one-half mr10 mb20">
	        <span class="color-orange font-12">Number of passengers:</span> <br><b><?= ($model->bkgAddInfo->bkg_no_person == '') ? '0' : $model->bkgAddInfo->bkg_no_person; ?></b>
	    </div>
	    <div class="one-half last-column mb20">
	        <span class="color-orange font-12">Luggage:</span> <br><b><?= $model->bkgAddInfo->bkg_num_large_bag; ?></b> big bags; <b><?= $model->bkgAddInfo->bkg_num_small_bag; ?></b> small bags
	    </div>
	    <div class="clear"></div>
	</div>

	<?php
}
end:
?>



<script>

	function showIncExc(text)
	{

		if (text == "toll")
		{
			$('#tollDesc').toggleClass("hide");
		}
		if (text == "state")
		{
			$('#stateDesc').toggleClass("hide");
		}
		if (text == "airport")
		{
			$('#airportDesc').toggleClass("hide");
		}
		if (text == "parking")
		{
			$('#parkingDesc').toggleClass("hide");
		}

	}
</script>
