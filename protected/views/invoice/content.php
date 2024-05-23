<?php
//$cartype	 = VehicleTypes::model()->getCarType();
//$type		 = $invoiceList['type'];
$filter		 = new Filter();
$rupees		 = 'Rupees' . ucwords($filter->convertNumberToWord($invoiceList['bkg_total_amount'])) . 'only.';
$duerupees	 = 'Rupees' . ucwords($filter->convertNumberToWord($invoiceList['bkg_due_amount'])) . 'only.';
$bkgId		 = $invoiceList['bkg_id'];
$hash		 = Yii::app()->shortHash->hash($bkgId);
if (!$isPDF)
{
	?>
	<title><?= Filter::formatBookingId($invoiceList['bkg_booking_id']); ?>-<?= $invoiceList['bkg_user_fname'] . ' ' . $invoiceList['bkg_user_lname']; ?>-<?= date('Ymd', strtotime($invoiceList['bkg_pickup_date'])); ?></title>
	<?
}

$pdfCss	 = $pdfCss1 = $pdfCss2 = "";

if ($column == 1)
{
	$pdfCss1 = $pdfCss2 = "width:100%;float:left;padding-left: 15px ;padding-right: 15px;";
}
else
{
	if ($isPDF)
	{
		$pdfCss1 = "width:47%;float:left;padding-left: 15px ;padding-right: 5px;";
		$pdfCss2 = "width:47%;float:left;padding-left: 5px ;padding-right: 15px;";
	}
	else
	{
		$pdfCss1 = "width:50%;float:left;padding-left: 15px ;padding-right: 5px;";
		$pdfCss2 = "width:50%;float:left;padding-left: 5px ;padding-right: 15px;";
	}
}
$isCorporate = false;
$topMargin	 = 0;
?>
<hr class="m10">
<div style="font-size: 11px ;line-height: 1.3em">
	<div style="width: 50%;float: left">
		<b>Traveler Information:</b><br/> 
		<?php
		$patternfname	 = "/" . $invoiceList['bkg_user_fname'] . "/i";
		$patternlname	 = "/" . $invoiceList['bkg_user_lname'] . "/i";

		if (($invoiceList['bkg_bill_company'] != '' || $invoiceList['bkg_bill_fullname'] != '') && (!preg_match($patternfname, $invoiceList['bkg_bill_fullname'])) && (!preg_match($patternlname, $invoiceList['bkg_bill_fullname'])))
		{
			?>
			<b>Company Name: </b><?= ucfirst($invoiceList['bkg_bill_fullname']); ?><br/>
		<?php } ?>
		<?php if ($invoiceList['bkg_bill_address'] != '')
		{ ?>
			<b>Address: </b><?= ucfirst($invoiceList['bkg_bill_address']); ?><br/>
		<?php } ?>
		<?php if ($invoiceList['bkg_bill_gst'] != '')
		{ ?>
			<b>GSTIN: </b><?= ucfirst($invoiceList['bkg_bill_gst']); ?><br/>
		<?php } ?>
		<b>Name: </b><?= ucfirst($invoiceList['bkg_user_fname']) . ' ' . ucfirst($invoiceList['bkg_user_lname']); ?>,</b>
		<?
		if ($invoiceList['bkg_contact_no'] != '')
		{
			?>
			<br/>
			<b>Phone: </b><?= '+' . $invoiceList['bkg_alt_country_code'] . $invoiceList['bkg_contact_no']; ?>
		<? } ?>
		<?
		if ($invoiceList['bkg_user_email'] != '')
		{
			?>
			<br/>
			<b>Email: </b><?= $invoiceList['bkg_user_email']; ?>
		<? } ?>

	</div>
	<div class="text-right "  style="width: 50%;float: left">
		<b>Invoice #:</b><span style="text-transform: uppercase "><?= date('ym', strtotime($invoiceList['bkg_pickup_date'])) . '/' . $hash ?></span><br>
		<b>Generated on: </b> <?= date('M d, Y'); ?>
	</div>
</div>

<?php
if ($invoiceList['bkg_agent_id'] > 0)
{
	$agentName = '( ' . ucfirst($invoiceList['agt_fn']) . ' ' . ucfirst($invoiceList['agt_ln']) . ' )';
	?>
	<div style="width:100%;float: left ;padding-top: 10px; font-size: 11px;line-height: 1.3em">
		<b>Billing Information:</b> <br/>
		<b>Company Name: </b><?= ucfirst($invoiceList['agt_cmpny']); ?><br/>
		<b>Billing Address: </b><?= (trim($invoiceList['agt_address']) != '') ? trim($invoiceList['agt_address']) : "--" ?><br/>
		<b>GSTIN: </b><?= (trim($invoiceList['agt_gstin']) != '') ? $invoiceList['agt_gstin'] : "--"; ?><br/>
		<b>Your Reference ID: </b>
		<?= (trim($invoiceList['bkg_agent_ref_code']) != '') ? $invoiceList['bkg_agent_ref_code'] : "--";
		?>
	</div>
<?php } ?>

<div class="row  " style="font-size: 11px;line-height: 1.3em">
	<div class="col-xs-12 table-responsive pt10">
		<table class="table table-bordered mb0">
			<tr>
				<td colspan="2"><b>Journey Details</b></td>
			</tr>
			<tr>
				<td>Route</td>
				<td class="text-left"><?= $invoiceList['route']; ?></td>
			</tr>
			<tr>
				<td>Pickup/Reporting Address</td>
				<td class="text-left"><?= $invoiceList['bkg_pickup_address']; ?></td>
			</tr>

			<tr>
				<td>Order Number</td>
				<td class="text-left"><?= Filter::formatBookingId($invoiceList['bkg_booking_id']); ?></td>
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
					<td class="text-left"><?= date('jS M Y (D) g:i a', strtotime($invoiceList['bkg_pickup_date'])); ?></td>
				</tr>
			<?php } ?>
			<tr>
				<td>Cab Details</td>
				<td class="text-left"><?= $invoiceList['bcb_cab_number'] . ' (' . $invoiceList["make"] . ')'; ?></td>
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
<?
$baseAmount			 = $invoiceList['bkg_base_amount'];
$sTax				 = $invoiceList['bkg_service_tax'];
$sTax2				 = round(($sTax * 0.2) / 6);
$sTax1				 = $sTax - (2 * $sTax2);
$dANightPickupStr	 = ( $invoiceList['bkg_night_pickup_included'] == 1) ? "Included" : "Excluded";
$dANightDropStr		 = ( $invoiceList['bkg_night_drop_included'] == 1) ? "Included" : "Excluded";
?>
<div class="row mt10"   >
	<div class="table-responsive " style=" <?= $pdfCss1 ?>">
		<table class="table table-bordered mb10">
			<tr>
				<td class="text-right"><b>Item</b></td>
				<td width="30%" class="text-right"><b>Amount (Rs.)</b></td>
			</tr>
			<tr>
				<td class="text-right">Base Fare</td>
				<td class="text-right"><b><i class="fa fa-inr"></i> <?= number_format(round($baseAmount)) ?></b></td>
			</tr>
			<?php
			if ($invoiceList['bkg_addon_charges'] != 0)
			{
				$addonDetails	 = json_decode($invoiceList['bkg_addon_details'], true);
				$addonCharge	 = (preg_match('/-/', $invoiceList['bkg_addon_charges'])) ? str_replace('-', '', $invoiceList['bkg_addon_charges']) : number_format($invoiceList['bkg_addon_charges']);
				$minusSymbol	 = (preg_match('/-/', $invoiceList['bkg_addon_charges'])) ? '(-)' : '';
				?>
				<tr>
					<td class="text-right">Addon Charge
						<?php
						$addnkey		 = array_search(1, array_column($addonDetails, 'adn_type'));
						if ($addonDetails[$addnkey]['adn_type'] == 1)
						{
							$cancelRuleId	 = BookingPref::model()->getByBooking($invoiceList['bkg_id'])->bkg_cancel_rule_id;
							$cpDetails		 = CancellationPolicyDetails::model()->findByPk($cancelRuleId);
							$cpAddonCharge	 = (preg_match('/-/', $addonDetails[$addnkey]['adn_value'])) ? str_replace('-', '', $addonDetails[$addnkey]['adn_value']) : number_format($addonDetails[$addnkey]['adn_value']);
							$cpMinusSymbol	 = (preg_match('/-/', $addonDetails[$addnkey]['adn_value'])) ? '(-)' : '';
							?>
							<br/><i class="font-12 txtAddonLabel"><?= $cpDetails->cnp_label ?>: <?= $cpMinusSymbol . " " . $cpAddonCharge ?></i>
							<?php
						}
						$addnkey = array_search(2, array_column($addonDetails, 'adn_type'));
						if ($addonDetails[$addnkey]['adn_type'] == 2)
						{
							$cmLebel		 = SvcClassVhcCat::model()->findByPk($invoiceList['bkg_vehicle_type_id'])->scv_label;
							$cmAddonCharge	 = (preg_match('/-/', $addonDetails[$addnkey]['adn_value'])) ? str_replace('-', '', $addonDetails[$addnkey]['adn_value']) : number_format($addonDetails[$addnkey]['adn_value']);
							$cmMinusSymbol	 = (preg_match('/-/', $addonDetails[$addnkey]['adn_value'])) ? '(-)' : '';
							?>
							<br/><i class="font-12 txtCabModel"><?= $cmLebel ?>: <?= $cmMinusSymbol . " " . $cmAddonCharge ?></i>
						<?php } ?>
	                </td>
					<td class="text-right"><b><i class="fa fa-inr"></i> <?= $addonCharge ?></b></td>
				</tr>
			<?php } ?>
			<tr>
				<td class="text-right">Driver Allowance
					<?
					if ($invoiceList['bkg_driver_allowance_amount'] > 0)
					{
						?>
						<br /><span style="font-size: 9px">(Night Pickup : <?= $dANightPickupStr ?> | Night Drop : <?= $dANightDropStr ?> )</span>
					<? } ?>
				</td> 
				<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_driver_allowance_amount']) ?></td>
			</tr>

			<?php
			if ($invoiceList['bkg_toll_tax'] > 0)
			{
				?>
				<tr>
					<td class="text-right">Toll Tax</td>
					<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_toll_tax']) ?></td>
				</tr>
				<?php
			}
			?>
			<?php
			if ($invoiceList['bkg_state_tax'] > 0)
			{
				?>
				<tr>
					<td class="text-right">State Tax</td>
					<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_state_tax']) ?></td>
				</tr>
				<?php
			}
			if ($invoiceList['bkg_airport_entry_fee'] > 0)
			{
				?>
				<tr>
					<td class="text-right">Airport Entry Fee</td>
					<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_airport_entry_fee']) ?></td>
				</tr>
				<?php
			}
			?>
			<tr>
				<td class="text-right">Convenience Charge</td> 
				<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_convenience_charge']) ?></td>
			</tr>

			<?php
			if ($invoiceList['bkg_extra_km'] > 0)
			{
				?>
				<tr>
					<td class="text-right">Extra kms driven</td> 
					<td class="text-right"> <?= number_format($invoiceList['bkg_extra_km']) ?> kms</td>
				</tr>
			<?php } ?>
			<?php
			if ($invoiceList['bkg_extra_km'] > 0)
			{
				?>
				<tr>
					<td class="text-right">Additional charges (for extra kms driven) @ Rate Rs. <?= $invoiceList['bkg_rate_per_km_extra'] ?></td>
					<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_extra_km_charge']) ?></td>
				</tr>
			<?php } ?>
			<?php
			if ($invoiceList['bkg_parking_charge'] > 0)
			{
				?>
				<tr>
					<td class="text-right">Other charges (Parking)</td> 
					<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_parking_charge']) ?></td>
				</tr>
			<?php } ?>
			<?php
			if ($invoiceList['bkg_extra_toll_tax'] > 0)
			{
				?>
				<tr>
					<td class="text-right">Other charges (Toll)</td> 
					<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_extra_toll_tax']) ?></td>
				</tr>
			<?php } ?>
			<?php
			if ($invoiceList['bkg_extra_state_tax'] > 0)
			{
				?>
				<tr>
					<td class="text-right">Other charges (State tax)</td> 
					<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_extra_state_tax']) ?></td>
				</tr>
			<?php } ?>
			<?php
			if ($invoiceList['bkg_additional_charge'] > 0)
			{
				?>
				<tr>
					<td class="text-right">Additional Charge @ <?= ucwords($invoiceList['bkg_additional_charge_remark']) ?></td>
					<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_additional_charge']) ?></td>
				</tr>
			<?php } ?>
			<?
			if (($invoiceList['bkg_service_tax_rate'] == 5) || (date('Y-m-d', strtotime($invoiceList['bkg_create_date'])) >= date('Y-m-d', strtotime('2017-07-01'))))
			{

				if ($invoiceList['bkg_sgst'] > 0)
				{
					?>
					<tr>
						<td class="text-right">SGST @ <?= Yii::app()->params['sgst']; ?>%</td>
						<td class="text-right"><i class="fa fa-inr"></i> <?= ((Yii::app()->params['sgst'] / $invoiceList['bkg_service_tax_rate']) * $sTax) | 0 ?></td>
					</tr>
				<? } ?>
				<?
				if ($invoiceList['bkg_cgst'] > 0)
				{
					?>
					<tr>
						<td class="text-right">CGST @ <?= Yii::app()->params['cgst']; ?>%</td>
						<td class="text-right"><i class="fa fa-inr"></i> <?= ((Yii::app()->params['cgst'] / $invoiceList['bkg_service_tax_rate']) * $sTax) | 0 ?></td>
					</tr>
				<? } ?>
				<?
				if ($invoiceList['bkg_igst'] > 0)
				{
					?>
					<tr>
						<td class="text-right">IGST @ <?= Yii::app()->params['igst']; ?>%</td>
						<td class="text-right"><i class="fa fa-inr"></i> <?= ((Yii::app()->params['igst'] / $invoiceList['bkg_service_tax_rate']) * $sTax) | 0 ?></td>
					</tr>
				<? } ?>
				<?
			}
			else
			{
				?>
				<tr>
					<td class="text-right">GST @ 5.6%</td>
					<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($sTax1) ?></td>
				</tr>
				<tr>
					<td class="text-right">Swachh Bharat Cess @ 0.2%</td>
					<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($sTax2) ?></td>
				</tr>
				<tr>
					<td class="text-right">Krishi Kalyan Cess @ 0.2%</td>
					<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($sTax2) ?></td>
				</tr>
			<? } ?>

			<?php
			if ($invoiceList['bkg_discount_amount'] > 0)
			{
				?>
				<tr>
					<td class="text-right">Discount</td>
					<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_discount_amount']) ?></td>
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
				<td class="text-right"><b><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_total_amount']) ?></b></td>
			</tr>
			<tr>
				<td colspan="2" class="text-right"><b>Total Amount (in words): <?= $rupees ?></b></td>
			</tr>
		</table>
	</div>

	<div class="table-responsive" style=" <?= $pdfCss2 ?>">
		<table class="table table-bordered mb10">
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
					<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($totPartnerCredit) ?></td>
				</tr>
				<?php
				//if (($invoiceList['bkg_advance_amount'] - $totPartnerCredit) > 0)
				//{
				?>
				<tr>
					<td class="text-right">Advance received on the booking  </td> 
					<td class="text-right"><i class="fa fa-inr"></i><?= round($totAdvanceOnline); ?>  <? //= number_format($invoiceList['bkg_advance_amount'] - $totPartnerCredit)  ?></td>
				</tr>
				<?php
				//}
			}
			else
			{
				?>
				<tr>
					<td class="text-right">Advance received on the booking  </td> 
					<td class="text-right"><i class="fa fa-inr"></i>  <?= number_format($invoiceList['bkg_advance_amount']) ?></td>
				</tr>
				<?
				if ($invoiceList['bkg_refund_amount'] > 0)
				{
					?>

					<tr>
						<td class="text-right">Refund amount  </td> 
						<td class="text-right"><i class="fa fa-inr"></i>  <?= number_format($invoiceList['bkg_refund_amount']) ?></td>
					</tr>
					<?
				}
			}
			if ($invoiceList['bkg_credits_used'] > 0)
			{
				?>
				<tr>
					<td class="text-right">Gozo coins used</td> 
					<td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_credits_used']) ?></td>
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
			<?
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
								?>
								<?= number_format($totPartnerCredit) ?>
								<?php
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
								?>
								<?= number_format($invoiceList['bkg_due_amount']) ?>
								<?php
							}
							else
							{
								echo '0';
							}
							?>
						</b></td>
				</tr>
				<?
			}
			?>	
		</table>
	</div>
</div>

