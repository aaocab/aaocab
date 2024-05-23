<?php
$baseAmount			 = $invoiceList['bkg_base_amount'];
$sTax				 = $invoiceList['bkg_service_tax'];
$sTax2				 = round(($sTax * 0.2) / 6);
$sTax1				 = $sTax - (2 * $sTax2);
$dANightPickupStr	 = ( $invoiceList['bkg_night_pickup_included'] == 1) ? "Included" : "Excluded";
$dANightDropStr		 = ( $invoiceList['bkg_night_drop_included'] == 1) ? "Included" : "Excluded";

if ($invoiceList['bkg_total_amount'] > 0)
{
	$totalInWords = "Rupees " . ucwords(Filter::convertNumberToWord($invoiceList['bkg_total_amount'])) . " only";
}
else
{
	$totalInWords = "Rupees zero only";
}

if ($invoiceList['bkg_due_amount'] > 0)
{
	$dueInWords = "Rupees " . ucwords(Filter::convertNumberToWord($invoiceList['bkg_due_amount'])) . " only";
}
else
{
	$dueInWords = "Rupees zero only";
}
?>
<div style="font-size: 11px ;line-height: 1.3em">
	<div class="col-xs-12 p0 pt10">
		<table class="table table-bordered mb0" width="100%">
			<tr>
				<td colspan="2"><b>Journey Details</b></td>
			</tr>
			<tr>
				<td>Route</td>
				<td class="text-left"><?php echo $invoiceList['route']; ?></td>
			</tr>
			<tr>
				<td>Pickup/Reporting Address</td>
				<td class="text-left"><?php echo $invoiceList['bkg_pickup_address']; ?></td>
			</tr>

			<tr>
				<td>Order Number</td>
				<td class="text-left"><?php echo Filter::formatBookingId($invoiceList['bkg_booking_id']); ?></td>
			</tr>
			<?php
			if ($invoiceList['bkg_booking_type'] == 2)
			{
				?>
				<tr>
					<td>Date and Time of Onward Trip</td>
					<td class="text-left"><?= date('jS M Y (D) g:i a', strtotime($invoiceList['bkg_pickup_date'])); ?></td>
				</tr>
				<tr>
					<td>Date and Time of Return Trip</td>
					<td class="text-left"><?= date('jS M Y (D) g:i a', strtotime($invoiceList['bkg_return_date'])); ?></td>
				</tr>
				<?php
			}
			else
			{
				?>
				<tr>
					<td>Date and Time of Journey</td>
					<td class="text-left"><?php echo date('jS M Y (D) g:i a', strtotime($invoiceList['bkg_pickup_date'])); ?></td>
				</tr>
			<?php } ?>
			<tr>
				<td>Cab Details</td>
				<td class="text-left"><?php echo $invoiceList['bcb_cab_number'] . ' (' . $invoiceList["make"] . ')'; ?></td>
			</tr>
			<?php
			if ($invoiceList['bkg_trip_distance'] > 0)
			{
				?>
				<tr>
					<td>Charges (per km) after <?= number_format($invoiceList['bkg_trip_distance']) ?> kms @ Rate Rs.</td> 
					<td class="text-left"> <?= $invoiceList['bkg_rate_per_km_extra'] ?></td>
				</tr>
			<?php } ?>
		</table>
	</div>
</div>
<div style="background: none; margin-top: 10px;">
	<div style="width: 49%; float: left;">
		<table class="table table-bordered mb10" width="100%" >
			<tr>
				<td class="text-right"><b>Item</b></td>
				<td width="30%" class="text-right"><b>Amount (Rs.)</b></td>
			</tr>
			<tr>
				<td class="text-right">Base Fare</td>
				<td class="text-right"><b><i class="fa fa-inr"></i> <?= number_format(round($baseAmount)) ?></b></td>
			</tr>
			<tr>
				<td class="text-right">Driver Allowance
					<?php
					if ($invoiceList['bkg_driver_allowance_amount'] > 0)
					{
						?>
						<br /><span style="font-size: 9px">(Night Pickup : <?= $dANightPickupStr ?> | Night Drop : <?= $dANightDropStr ?> )</span>
						<? } ?>
					</td> 
					<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_driver_allowance_amount']) ?></td>
				</tr>

				<?php
				if ($invoiceList['bkg_addon_charges'] > 0)
				{
					?>
					<tr>
						<td class="text-right" style="border-width: 0">Cancellation Addon Charge</td> 
						<td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_addon_charges']) ?></td>
					</tr>
					<?php
				}
				if ($invoiceList['bkg_toll_tax'] > 0)
				{
					?>
					<tr>
						<td class="text-right">Toll Tax</td>
						<td class="text-right"><i class="fa fa-inr"></i> <?php echo number_format($invoiceList['bkg_toll_tax']) ?></td>
					</tr>
					<?php
				}

				if ($invoiceList['bkg_state_tax'] > 0)
				{
					?>
					<tr>
						<td class="text-right">State Tax</td>
						<td class="text-right"><i class="fa fa-inr"></i> <?php echo number_format($invoiceList['bkg_state_tax']) ?></td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td class="text-right">Convenience Charge</td> 
					<td class="text-right"><i class="fa fa-inr"></i> <?php echo number_format($invoiceList['bkg_convenience_charge']) ?></td>
				</tr>

				<?php
				if ($invoiceList['bkg_extra_km'] > 0)
				{
					?>
					<tr>
						<td class="text-right">Extra kms driven</td> 
						<td class="text-right"> <?php echo number_format($invoiceList['bkg_extra_km']) ?> kms</td>
					</tr>
					<?php
				}
				if ($invoiceList['bkg_extra_km'] > 0)
				{
					?>
					<tr>
						<td class="text-right">Additional charges (for extra kms driven) @ Rate Rs. <?= $invoiceList['bkg_rate_per_km_extra'] ?></td>
						<td class="text-right"><i class="fa fa-inr"></i> <?php echo number_format($invoiceList['bkg_extra_km_charge']) ?></td>
					</tr>
					<?php
				}
				if ($invoiceList['bkg_parking_charge'] > 0)
				{
					?>
					<tr>
						<td class="text-right">Other charges (Parking)</td> 
						<td class="text-right"><i class="fa fa-inr"></i> <?php echo number_format($invoiceList['bkg_parking_charge']) ?></td>
					</tr>
					<?php
				}
				if ($invoiceList['bkg_extra_toll_tax'] > 0)
				{
					?>
					<tr>
						<td class="text-right">Other charges (Toll)</td> 
						<td class="text-right"><i class="fa fa-inr"></i> <?php echo number_format($invoiceList['bkg_extra_toll_tax']) ?></td>
					</tr>
					<?php
				}

				if ($invoiceList['bkg_extra_state_tax'] > 0)
				{
					?>
					<tr>
						<td class="text-right">Other charges (State tax)</td> 
						<td class="text-right"><i class="fa fa-inr"></i> <?php echo number_format($invoiceList['bkg_extra_state_tax']) ?></td>
					</tr>
					<?php
				}

				if (($invoiceList['bkg_service_tax_rate'] == 5) || (date('Y-m-d', strtotime($invoiceList['bkg_create_date'])) >= date('Y-m-d', strtotime('2017-07-01'))))
				{

					if ($invoiceList['bkg_sgst'] > 0)
					{
						?>
						<tr>
							<td class="text-right">SGST @ <?= Yii::app()->params['sgst']; ?>%</td>
							<td class="text-right"><i class="fa fa-inr"></i> <?php echo ((Yii::app()->params['sgst'] / $invoiceList['bkg_service_tax_rate']) * $sTax) ?></td>
						</tr>
						<? } ?>
						<?
						if ($invoiceList['bkg_cgst'] > 0)
						{
						?>
						<tr>
							<td class="text-right">CGST @ <?= Yii::app()->params['cgst']; ?>%</td>
							<td class="text-right"><i class="fa fa-inr"></i> <?php echo ((Yii::app()->params['cgst'] / $invoiceList['bkg_service_tax_rate']) * $sTax) ?></td>
						</tr>
						<? } ?>
						<?
						if ($invoiceList['bkg_igst'] > 0)
						{
						?>
						<tr>
							<td class="text-right">IGST @ <?= Yii::app()->params['igst']; ?>%</td>
							<td class="text-right"><i class="fa fa-inr"></i> <?php echo ((Yii::app()->params['igst'] / $invoiceList['bkg_service_tax_rate']) * $sTax) ?></td>
						</tr>
						<? } ?>
						<?
						if ($model->bkgInvoice->bkg_extra_min > 0)
						{
						if (in_array($model->bkg_booking_type, [9, 10, 11]))
						{
						?>
						<tr>
							<td class="text-right">Extra Minutes(<?= $model->bkgInvoice->bkg_extra_min; ?> Min)</td>
							<td class="text-right"><i class="fa fa-inr"></i><?= $model->bkgInvoice->bkg_extra_per_min_charge; ?></td>
						</tr>
						<? } }?>
						<?
						}
						else
						{
						?>
						<tr>
							<td class="text-right">GST @ 5.6%</td>
							<td class="text-right"><i class="fa fa-inr"></i> <?php echo number_format($sTax1) ?></td>
						</tr>
						<tr>
							<td class="text-right">Swachh Bharat Cess @ 0.2%</td>
							<td class="text-right"><i class="fa fa-inr"></i> <?php echo number_format($sTax2) ?></td>
						</tr>
						<tr>
							<td class="text-right">Krishi Kalyan Cess @ 0.2%</td>
							<td class="text-right"><i class="fa fa-inr"></i> <?php echo number_format($sTax2) ?></td>
						</tr>
						<? } ?>
						<?php
						if ($invoiceList['bkg_additional_charge'] > 0)
						{
							?>
							<tr>
								<td class="text-right">Additional Charge @ <?= ucwords($invoiceList['bkg_additional_charge_remark']) ?></td>
								<td class="text-right"><i class="fa fa-inr"></i> <?php echo number_format($invoiceList['bkg_additional_charge']) ?></td>
							</tr>
						<?php } ?>
						<?php
						if ($invoiceList['bkg_discount_amount'] > 0)
						{
							?>
							<tr>
								<td class="text-right">Discount</td>
								<td class="text-right"><i class="fa fa-inr"></i> <?php echo number_format($invoiceList['bkg_discount_amount']) ?></td>
							</tr>
						<?php
						}
						if ($invoiceList['bkg_extra_discount_amount'] > 0)
						{
							?>
							<tr>
								<td class="text-right">One-Time Price Adjustment:</td>
								<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_extra_discount_amount']) ?></td>
							</tr>
			<?php } ?>

						<tr>
							<td class="text-right"><b>Total Amount (in figures)</b></td>
							<td class="text-right"><b><i class="fa fa-inr"></i> <?php echo number_format($invoiceList['bkg_total_amount']) ?></b></td>
						</tr>
						<tr>
							<td colspan="2" class="text-right"><b>Total Amount (in words): <?php echo $totalInWords ?></b></td>
						</tr>
					</table>
				</div>
				<div style="width: 49%; float: left; margin-left: 12px;">
					<table class="table table-bordered" width="100%">
						<tr>
							<td class="text-right"><b>Item</b></td>
							<td class="text-right"><b>Amount (Rs.)</b></td>
						</tr>

						<?php
						if ($invoiceList['bkg_agent_id'] > 0)
						{
							$totPartnerCredit = ($totPartnerCredit == '') ? 0 : $totPartnerCredit;
							?>
							<tr>
								<td class="text-right"><?= ucfirst($invoiceList['agt_cmpny']) ?> credits/coins used</td> 
								<td class="text-right"><i class="fa fa-inr"></i> <?php echo number_format($totPartnerCredit) ?></td>
							</tr>
							<tr>
								<td class="text-right">Advance received on the booking  </td> 
								<td class="text-right"><i class="fa fa-inr"></i><?= round($totAdvanceOnline); ?>  <? //= number_format($invoiceList['bkg_advance_amount'] - $totPartnerCredit)   ?></td>
							</tr>
							<?php
						}
						else
						{
							?>
							<tr>
								<td class="text-right">Advance received on the booking  </td> 
								<td class="text-right"><i class="fa fa-inr"></i>  <?= number_format($invoiceList['bkg_advance_amount']) ?></td>
							</tr>
							<?php
							if ($invoiceList['bkg_refund_amount'] > 0)
							{
								?>
								<tr>
									<td class="text-right">Refund amount  </td> 
									<td class="text-right"><i class="fa fa-inr"></i>  <?php echo number_format($invoiceList['bkg_refund_amount']) ?></td>
								</tr>
								<?php
							}
						}
						if ($invoiceList['bkg_credits_used'] > 0)
						{
							?>
							<tr>
								<td class="text-right">Gozo coins used</td> 
								<td class="text-right"><i class="fa fa-inr"></i> <?php echo number_format($invoiceList['bkg_credits_used']) ?></td>
							</tr>
							<? } ?>
							<tr>
								<td class="text-right">Cash collected by driver</td> 
								<td class="text-right">
									<i class="fa fa-inr"></i> 
									<?php
									if ($invoiceList['bkg_vendor_collected'] > 0)
									{
										?>
										<?= number_format($invoiceList['bkg_vendor_collected']) ?>
										<?php
									}
									else
									{
										echo '0';
									}
									?>
								</td>
							</tr>
							<?php
							if ($invoiceList['bkg_agent_id'] > 0)
							{
								?>
								<tr>
									<td class="text-right"><b>Partner Due (in figures)</b></td>
									<td class="text-right"><b>  
											<i class="fa fa-inr"></i>
											<?php
											if ($totPartnerCredit > 0)
											{
												echo number_format($totPartnerCredit);
											}
											else
											{
												echo '0';
											}
											?>
										</b>
									</td>
								</tr>
								<?
								}
								else
								{
								?>	
								<tr>
									<td class="text-right"><b>Total Amount Due (in figures)</b></td>
									<td class="text-right"><b> 
											<i class="fa fa-inr"></i>
											<?php
											if ($invoiceList['bkg_due_amount'] > 0)
											{
												echo number_format($invoiceList['bkg_due_amount']);
											}
											else
											{
												echo '0';
											}
											?>
										</b></td>
								</tr>
								<?php
							}
							?>	
		</table>
	</div>
</div>