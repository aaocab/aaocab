<style>
    #main {
		margin: 0;
	}

	#main #faq .card {
		margin-bottom: 30px;
		border: 0;
	}

	#main #faq .card .card-header {
		border: 0;
		-webkit-box-shadow: 0 0 20px 0 rgba(213, 213, 213, 0.5);
		box-shadow: 0 0 20px 0 rgba(213, 213, 213, 0.5);
		border-radius: 2px;
		padding: 0;
	}

	#main #faq .card .card-header .btn-header-link {
		color: #fff;
		display: block;
		text-align: left;
		background: #fedfa7;
		color: #222;
		padding: 15px;
		font-weight: 500;
	}

	#main #faq .card .card-header .btn-header-link:after {
		content: "\f107";
		font-family: 'Font Awesome 5 Free';
		font-weight: 900;
		float: right;
	}

	#main #faq .card .card-header .btn-header-link.collapsed {
		background: #1c4fa2;
		color: #fff;
	}

	#main #faq .card .card-header .btn-header-link.collapsed:after {
		content: "\f106";
	}

	#main #faq .card .collapsing {
		background: #fedfa7;
		line-height: 30px;
	}

	#main #faq .card .collapse {
		border: 0;
	}

	#main #faq .card .collapse.show {
		background: #fedfa7;
		line-height: 30px;
		color: #222;
	}
</style>

<div class="row title-widget">
    <div class="col-12">
        <div class="container">
			<?php echo $this->pageTitle; ?>
        </div>
    </div>
</div>

<div class="container">
	<?php
	if ($error != "")
	{
		?>

		<div class="row">
			<div class="col-12 text-center">
				<div class="bg-white-box mt20 mb20" style="min-height: 250px;">
					<div class="row">
						<div class="col-12 text-center">
							<span class="font-18"><b>Trip ID:<?= $cabModel->bcb_id ?></b></span><br>
							<span class="font-14">
								<?php
								echo $error;
								?>
							</span><br>

						</div>
					</div>
				</div>
			</div>
		</div>
		<div></div>
		<?php
		goto end;
	}
	?>
	<?php
	/* @var $bModel Booking  */
	/* @var $model Booking */


	$model = $bModels[0];
	if (!empty($model))
	{
		$luggageCapacity = Stub\common\LuggageCapacity::init($model->bkgSvcClassVhcCat->scv_vct_id, $model->bkgSvcClassVhcCat->scv_scc_id, $model->bkgAddInfo->bkg_no_person);
		?>

		<div class="row">
			<div class="col-12 text-center">
				<span class="font-18"><b>Trip ID: <?= $cabModel->bcb_id ?></b></span><br>
				<span class="font-12">

				</span><br>

				<span class="color-blue-dark"><strong><?= date('jS M Y (D) h:i A', strtotime($model->bkg_pickup_date)); ?></strong></span><br>
				<span style="color:red">* Driver App must be used.  </span>
			</div>
			<div class="col-12 mt15">
				<div class="row border-top pt10">
					<div class="col-12 col-lg-6 text-center"><span class="font-16">Vendor Amount:</span> <span class="font-22">&#x20B9;<b><?= round($cabModel->bcb_vendor_amount) ?></b></span></div>
					<?php
					if ($model->bkgPref->bkg_is_gozonow != 1)
					{
						?>
						<div class="col-12 col-lg-6 text-center"><span class="font-16">Amount To Collect:</span> <span class="font-22">&#x20B9;<b><?= round($model->bkgInvoice->bkg_due_amount) ?></b></span></div>
					<? } ?>
				</div>
			</div>

			<?
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
			<div class="col-12 mt15">
				<div class="row border-top pt10">
					<div class="col-12 col-lg-6  "><span class="font-16">Pickup Point:</span><br><span class="font-18"> <?= $maskedPickupAddress ?> </span></div>
					<div class="col-12 col-lg-6  "><span class="font-16">Drop Point:</span><br><span class="font-18">  <?= $maskedDropAddress ?> </span></div>

				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
	</div>



	<div class="col-12 text-center mt5  ">&nbsp;</div>
	<?php
	$correctimg			 = '<img src="/images/email/correct.png" height="15" width="15">';
	$crossimg			 = '<img src="/images/email/cross.png" height="15" width="15">';
	$vehicleModel		 = $model->bkgBcb->bcbCab->vhcType->vht_model;
	if ($model->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
	{
		$vehicleModel = OperatorVehicle::getCabModelName($model->bkgBcb->bcb_vendor_id, $model->bkgBcb->bcb_cab_id);
	}
	?>
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-10 offset-lg-1 mb30">
				<div class="bg-white-box">
					<div class="row flex">
						<div class="col-lg-3 mb20" style="display: flex; line-height: 18px;">
							<span class="color-gray2 font-13" style="display: contents;">Included kms:</span> <br><?= $model->bkg_trip_distance; ?>
						</div>
						<div class="col-lg-3 mb20" style="display: flex; line-height: 18px;">
							<span class="color-gray2 font-13" style="display: contents;">Extra charge per km:</span> <br>&#x20B9;<?= $model->bkgInvoice->bkg_rate_per_km_extra; ?>
						</div>
						<div class="col-lg-3 mb20" style="display: flex; line-height: 18px;">
							<span class="color-gray2 font-13" style="display: contents;">Service tier:</span> <br> 
							<?= $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . ' (' . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . $vhcModel . ')'; ?>

						</div>
						<div class="col-lg-3 mb20" style="display: flex; line-height: 18px;">
							<span class="color-gray2 font-13" style="display: contents;">Cab type booked:</span> <br><?= $model->bkgBcb->bcbCab->vhcType->vht_make; ?> - <?= $vehicleModel; ?>
						</div>
						<div class="col-lg-3 mb20" style=" line-height: 18px;">
							<span class="color-gray2 font-13" style="display: contents;"><?= ($model->bkgInvoice->bkg_night_pickup_included > 0) ? $correctimg : $crossimg; ?> Night pickup allowance:</span>  <br> <?= ($bmodel->bkgInvoice->bkg_night_pickup_included == 1) ? "Not payable by customer" : "Payable on actuals" ?>
						</div>
						<div class="col-lg-3 mb20" style="line-height: 18px;">
							<span class="color-gray2 font-13" style="display: contents;"><?= ($model->bkgInvoice->bkg_night_drop_included > 0) ? $correctimg : $crossimg; ?>  Night drop allowance:</span> <br> <?= ($model->bkgInvoice->bkg_night_drop_included == 1) ? "Not payable by customer" : "Payable on actuals" ?>
						</div>
						<div class="col-lg-3 mb20" style="line-height: 18px;">
							<span class="color-gray2 font-13" style="display: contents;" onclick="showIncExc('toll')">
								<?= ($model->bkgInvoice->bkg_is_toll_tax_included == 1) ? $correctimg : $crossimg; ?> Toll tax: <i class="far fa-plus-square ml5"></i></span> <br>
							<span><?= ($model->bkgInvoice->bkg_is_toll_tax_included == 1) ? 'Not payable by customer' : 'Payable on actuals'; ?></span>
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

						<div class="col-lg-3 mb20" style=" line-height: 18px;">
							<span class="color-gray2 font-13" style="display: contents;"><?= $crossimg ?> MCD:</span> <br>not included
						</div>
						<div class="col-lg-3 mb20" style=" line-height: 18px;">
							<span class="color-gray2 font-13" style="display: contents;" onclick="showIncExc('parking')">
								<?= ($model->bkgInvoice->bkg_parking_charge > 0) ? "&#x20B9;" . $model->bkgInvoice->bkg_parking_charge : ''; ?><?= ($model->bkgInvoice->bkg_parking_charge > 0) ? $correctimg : $crossimg; ?> Parking charges: <i class="far fa-plus-square ml5"></i></span> <br>
							<span><?= ($model->bkgInvoice->bkg_parking_charge > 0) ? "&#x20B9;" . $model->bkgInvoice->bkg_parking_charge : ''; ?><?= ($model->bkgInvoice->bkg_parking_charge > 0) ? 'included' : 'not included'; ?></span>
							<span id="parkingDesc" class="hide font-10"><BR>
								<?php
								if ($model->bkgInvoice->bkg_parking_charge > 0)
								{
									?> Parking charges are prepaid upto ₹<?= round($model->bkgInvoice->bkg_parking_charge) ?>.<?php } ?> 
								Customer will directly pay for parking charges after the total parking cost for the trip exceeds ₹<?= round($model->bkgInvoice->bkg_parking_charge) ?>. Driver must upload all parking receipts for payments made by drive.
							</span>
						</div>
						<div class="col-lg-3 mb20" style=" line-height: 18px;">
							<span class="color-gray2 font-13" style="display: contents;" onclick="showIncExc('state')"><?= ($model->bkgInvoice->bkg_is_state_tax_included == 1) ? $correctimg : $crossimg; ?> State taxes: <i class="far fa-plus-square ml5"></i></span> <br>
							<span><?= ($model->bkgInvoice->bkg_is_state_tax_included == 1) ? 'Not payable by customer' : 'Payable on actuals'; ?></span>
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
						<div class="col-lg-3 mb20" style=" line-height: 18px;">
							<span class="color-gray2 font-13" style="display: contents;" onclick="showIncExc('airport')"><?= ($model->bkgInvoice->bkg_is_airport_fee_included == 1 ) ? $correctimg : $crossimg; ?> Airport entry charges: <i class="far fa-plus-square ml5"></i></span> <br>
							<span><?= ($model->bkgInvoice->bkg_is_airport_fee_included == 1 ) ? 'Not payable by customer' : 'Payable on actuals'; ?> </span>
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
						<div class="col-lg-3 mb20" style="display: flex; line-height: 18px;">
							<span class="color-gray2 font-13" style="display: contents;">Journey break:</span> <br>
						</div>
						<div class="col-lg-3 mb20" style="display: flex; line-height: 18px;">
							<span class="color-gray2 font-13" style="display: contents;">Number of passengers:</span> <br><?= ($model->bkgAddInfo->bkg_no_person == '') ? '0' : $model->bkgAddInfo->bkg_no_person; ?>
						</div>
						<div class="col-lg-3 mb20" style="display: flex; line-height: 18px;">
							<span class="color-gray2 font-13" style="display: contents;">Luggage:</span> <br><?= $model->bkgAddInfo->bkg_num_large_bag; ?> big bags; <?= $model->bkgAddInfo->bkg_num_small_bag; ?> small bags
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	end:
	?>
</div>

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
