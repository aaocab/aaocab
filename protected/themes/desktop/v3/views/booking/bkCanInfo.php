<?php
//getting the Price rule array for fare inclusion and exclusion
$newpriceRule = PriceRule::getByCity($model->bkg_from_city_id, $model->bkg_booking_type, $model->bkg_vehicle_type_id);
if (!empty($newpriceRule))
{
	$prarr = $newpriceRule->attributes;
}
?>
<div class="card-body" style="display: flex; flex-wrap: wrap;">
	<?php
	$correctimg	 = '<i class="arrow-1"></i>';
	$crossimg	 = '<i class="block-1"></i>';
	?>
	<div class="row d-flex justify-content-start mb10">
		<div class="col-md-4 col-sm-12">
			<div class="card mb10">
				<div class="card-body p10">
					<div class="form-group mb0">
						<h5 class="font-13 weight500"><span><?php echo ($model->bkgInvoice->bkg_is_toll_tax_included == 1) ? $correctimg : $crossimg; ?> TOLL CHARGES <?= ($model->bkgInvoice->bkg_is_toll_tax_included == 1) ? "(Already Included)" : "(Not Included)" ?></span></h5>
						<p class="mb0 line-height14">
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
						</p>

					</div>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-12">
			<div class="card mb10">
				<div class="card-body p10">
					<div class="form-group mb0">
						<h5 class="font-13 weight500"><span><?php echo ($model->bkgInvoice->bkg_is_state_tax_included == 1) ? $correctimg : $crossimg; ?> STATE CHARGES <?= ($model->bkgInvoice->bkg_is_state_tax_included == 1) ? "(Already Included)" : "(Not Included)" ?></span></h5>
						<p class="mb0 line-height14">
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
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-12">
			<div class="card mb10">
				<div class="card-body p10">
					<div class="form-group mb0">
						<p class="mb0 line-height14 weight500"><?= $crossimg; ?> MCD</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-12">
			<div class="card mb10">
				<div class="card-body p10">
					<div class="form-group mb0">
						<h5 class="font-13 weight500"><span><?php echo ($model->bkgInvoice->bkg_is_airport_fee_included == 1) ? $correctimg : $crossimg; ?> AIRPORT ENTRY CHARGES <?= ($model->bkgInvoice->bkg_is_airport_fee_included == 1) ? "(Already Included)" : "(Not Included)" ?></span></h5>
						<p class="mb0 line-height14">
							<?php
							if ($model->bkgInvoice->bkg_is_airport_fee_included != 1)
							{
								?>   
								Our estimate of airport entry charges on this route is ₹ <?= $model->bkgInvoice->bkg_airport_entry_fee ?>. Any charges incurred is payable by customer. <?php
							}
							else
							{
								?>

								Our estimate of airport entry charges on this route are ₹<?= ($model->bkgInvoice->bkg_airport_entry_fee != '') ? $model->bkgInvoice->bkg_airport_entry_fee : 0; ?>. 
								airport entry charges (even if amount is different) is already included in the trip cost 
								<?php
							}
							?>
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-12">
			<div class="card mb10">
				<div class="card-body p10">
					<div class="form-group mb0">
						<h5 class="font-13 weight500"><span><?php echo ($model->bkgInvoice->bkg_is_airport_fee_included == 1) ? $correctimg : $crossimg; ?> NIGHT PICKUP ALLOWANCE <?= ($model->bkgInvoice->bkg_night_pickup_included == 1) ? '(Prepaid)' : '' ?><br/><?= ($model->bkgInvoice->bkg_night_pickup_included == 1) ? "(Already Included)" : "(Not Included)" ?></span></h5>
						<p class="mb0 line-height14">
							<?php
							if ($model->bkgInvoice->bkg_night_pickup_included == 1)
							{
								?>
								Based on the schedule of this trip, the journey is expected to start between the hours of 10pm to 6am. As a result, night pickup charges of ₹<?= ($model->bkgInvoice->bkg_night_pickup_included == 1) ? $model->bkgInvoice->bkg_driver_allowance_amount : 0 ?> have been applied for this booking. 
								<?php
							}
							else
							{
								?>
								Night pickup charges of ₹250 will be payable if journey start between the hours of 10pm to 6am. Currently, night pickup charges of ₹<?= ($model->bkgInvoice->bkg_night_pickup_included == 1) ? $model->bkgInvoice->bkg_driver_allowance_amount : 0 ?> have been applied for this booking.
								<?php
							}
							?>
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-12">
			<div class="card mb10">
				<div class="card-body p10">
					<div class="form-group mb0">
						<h5 class="font-13 weight500"><span><?php echo ($model->bkgInvoice->bkg_is_airport_fee_included == 1) ? $correctimg : $crossimg; ?> NIGHT DROP ALLOWANCE <?= ($model->bkgInvoice->bkg_night_drop_included == 1) ? '(Prepaid)' : '' ?><br/><?= ($model->bkgInvoice->bkg_night_drop_included == 1) ? "(Already Included)" : "(Not Included)" ?></span></h5>
						<p class="mb0 line-height14">
							<?php
							if ($model->bkgInvoice->bkg_night_drop_included == 1)
							{
								?>
								Based on the schedule of this trip, the journey is expected to end between the hours of 10pm to 6am. As a result, night drop charges of ₹<?= ($model->bkgInvoice->bkg_night_pickup_included != 1 && $model->bkgInvoice->bkg_night_drop_included == 1) ? $model->bkgInvoice->bkg_driver_allowance_amount : 0 ?> have been applied for this booking.
								<?php
							}
							else
							{
								?>
								Night drop charges of ₹250 will be payable if journey ends between the hours of 10pm to 6am. Currently, night drop charges of ₹<?= ($model->bkgInvoice->bkg_night_drop_included == 1) ? $model->bkgInvoice->bkg_driver_allowance_amount : 0 ?> have been applied for this booking.
								<?php
							}
							?>
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-12">
			<div class="card mb10">
				<div class="card-body p10">
					<div class="form-group mb0">
						<h5 class="font-13 weight500"><span><?= ($model->bkgInvoice->bkg_is_parking_included == 1) ? $correctimg : $crossimg; ?> PARKING CHARGES </span></h5>
						<p class="mb0 line-height14"><?php
							if ($model->bkgInvoice->bkg_parking_charge > 0)
							{
								?> Parking charges are prepaid upto <?php echo Filter::moneyFormatter(round($model->bkgInvoice->bkg_parking_charge)); ?>.<?php } ?> 
							Customer will directly pay for parking charges after the total parking cost for the trip exceeds <?php echo Filter::moneyFormatter(round($model->bkgInvoice->bkg_parking_charge)); ?>. 
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-12">
			<div class="card mb10">
				<div class="card-body p10">
					<div class="form-group mb0">
						<p class="mb0 line-height14 weight500"><?= ($model->bkgInvoice->bkg_extra_km > 0) ? $correctimg : $crossimg ?> EXTRA CHARGES (₹<?php echo round($model->bkgInvoice->bkg_rate_per_km_extra, 2) . ' / KM beyond ' . $model->bkg_trip_distance . ' KMS)' ?>.</p>
					</div>
				</div>
			</div>
		</div>
		<?php
		if (in_array($model->bkg_booking_type, [9, 10, 11]))
		{
			?>
			<div class="col-md-4 col-sm-12">
				<div class="card">
					<div class="card-body p10">
						<div class="form-group mb0">
							<h5 class="font-13 weight500"><span><?= ($model->bkgInvoice->bkg_extra_min > 0) ? $correctimg : $crossimg; ?> EXTRA CHARGES (<?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_extra_per_min_charge) . ' / Min beyond ' . Filter::getTimeDurationbyMinute($model->bkg_trip_duration) . ')' ?>. </span></h5>
						</div>
					</div>
				</div>
			</div>
<?php } ?>

	</div>
	<p>FINAL OUTSTANDING SHALL BE COMPUTED AFTER TRIP COMPLETION. ADDITIONAL AMOUNT, IF ANY, MAY BE PAID IN CASH TO THE DRIVER DIRECTLY.</p>
</div>