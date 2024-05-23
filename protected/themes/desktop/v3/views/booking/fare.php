<?php
if ($bkgid)
{
	$modelold	 = Booking::model()->findByPk($bkgid);
	$bhash		 = Yii::app()->shortHash->hash($bkgid);
}
?>
<div class="row">
	<div class="col-12 p0">   
		<?php
		if (($model->bkg_trip_distance > 0 && $modelold->bkg_trip_distance > 0) && ($model->bkg_trip_distance - $modelold->bkg_trip_distance > 0) && ($model->bkg_trip_distance > $modelold->bkg_trip_distance) && ($model->bkg_trip_distance != $modelold->bkg_trip_distance))
		{
			?>
			<div class="d-flex justify-content-between p5 vwBaseFare clsBilling ExtraKm" style="display:none;">
				<div class="sales-info d-flex align-items-center">
					<div class="sales-info-content">
						<h6 class="mb-0">Extra Km:</h6>
					</div>
				</div>
				<h6 class="mb-0 text-right"><span ><?php echo $model->bkg_trip_distance - $modelold->bkg_trip_distance; ?></span></h6>
			</div>


			<div class="d-flex justify-content-between p5 vwBaseFare clsBilling ExtraCharges" style="display:none;">
				<div class="sales-info d-flex align-items-center">
					<div class="sales-info-content">
						<h6 class="mb-0">Extra Charges:</h6>
					</div>
				</div>
				<h6 class="mb-0 text-right">
					<?php echo ROUND(($model->bkg_trip_distance - $modelold->bkg_trip_distance) * $modelold->bkgInvoice->bkg_rate_per_km_extra); ?>
				</h6>
			</div>
		<?php } ?>
		<div class="d-flex justify-content-between p5 vwBaseFare clsBilling">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0">Base fare:</h6>
				</div>
			</div>
			<h6 class="mb-0 text-right"><span class="txtBaseFare"><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_base_amount); ?></span></h6>
		</div>
		<div class="d-flex justify-content-between p5 discounttd vwDiscount clsBilling  <?= ($model->bkgInvoice->bkg_discount_amount > 0) ? '' : 'hide' ?>">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0 color-red">Discount <span class="font-11">(Promo: <strong><span class="txtPromoCode"><?= $model->bkgInvoice['bkg_promo1_code'] ?></span> </strong> )</span></h6>
				</div>
			</div>
			<h6 class="mb-0 text-right color-red">(-)<span class="discountAmount txtDiscountAmount"><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_discount_amount); ?></span></h6>
		</div>
		<div class="d-flex justify-content-between p5 discounttd clsBilling  <?= ($model->bkgInvoice->bkg_extra_discount_amount > 0) ? '' : 'hide' ?>">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0">One-Time Discount</h6>
				</div>
			</div>
			<h6 class="mb-0 text-right">(-)<span class="txtExtraDiscountAmount"><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_extra_discount_amount); ?></span></h6>
		</div>
		<div class="d-flex justify-content-between p5 discountFare discounttd vwDiscount clsBilling <?= ($model->bkgInvoice->bkg_discount_amount > 0) ? '' : 'hide' ?>">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0 font-14 weight500">Net Base Fare:</h6>
				</div>
			</div>
			<h6 class="mb-0 text-right" style="border-top: solid 1px;padding-top: 5px;padding-left: 10px;"><strong><span class="actualAmount txtDiscountedBaseAmount"><?php echo Filter::moneyFormatter(($model->bkgInvoice->bkg_base_amount - $model->bkgInvoice->bkg_discount_amount)); ?></span></strong></h6>
		</div>
		<div class="d-flex justify-content-between p5 vwAddonCharge <?= ($model->bkgInvoice->bkg_addon_charges != 0) ? '' : 'hide' ?>">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<?php
					$addonDetails	 = json_decode($model->bkgInvoice->bkg_addon_details, true);
					$addonCharge	 = (preg_match('/-/', $model->bkgInvoice->bkg_addon_charges)) ? str_replace('-', '', $model->bkgInvoice->bkg_addon_charges) : $model->bkgInvoice->bkg_addon_charges;
					$minusSymbol	 = (preg_match('/-/', $model->bkgInvoice->bkg_addon_charges)) ? '(-)' : '';
					?>
					<b>Addon charge</b>
					<?php
					$addnkey		 = array_search(1, array_column($addonDetails, 'adn_type'));
					if ($addonDetails[$addnkey]['adn_type'] == 1)
					{
						$cpDetails		 = CancellationPolicyDetails::model()->findByPk($model->bkgPref->bkg_cancel_rule_id);
						$cpAddonCharge	 = (preg_match('/-/', $addonDetails[$addnkey]['adn_value'])) ? str_replace('-', '', $addonDetails[$addnkey]['adn_value']) : $addonDetails[$addnkey]['adn_value'];
						$cpMinusSymbol	 = (preg_match('/-/', $addonDetails[$addnkey]['adn_value'])) ? '(-)' : '';
					}
					$displyaddntype1 = ($addonDetails[$addnkey]['adn_type'] == 1) ? '' : 'hide';
					?>
					<br/><i class="font-12 txtAddonLabel <?= $displyaddntype1 ?>"><?= $cpDetails->cnp_label ?>: <?= $cpMinusSymbol . " " . Filter::moneyFormatter($cpAddonCharge) ?></i>
					<?php
					$addnkey		 = array_search(2, array_column($addonDetails, 'adn_type'));
					if ($addonDetails[$addnkey]['adn_type'] == 2)
					{
						$cmLebel		 = SvcClassVhcCat::model()->findByPk($model->bkg_vehicle_type_id)->scv_label;
						$cmAddonCharge	 = (preg_match('/-/', $addonDetails[$addnkey]['adn_value'])) ? str_replace('-', '', $addonDetails[$addnkey]['adn_value']) : $addonDetails[$addnkey]['adn_value'];
						$cmMinusSymbol	 = (preg_match('/-/', $addonDetails[$addnkey]['adn_value'])) ? '(-)' : '';
					}
					$displyaddntype2 = ($addonDetails[$addnkey]['adn_type'] == 2) ? '' : 'hide';
					?>
					<br/><i class="font-12 txtCabModel <?= $displyaddntype2 ?>"><?= $cmLebel ?>: <?= $cmMinusSymbol . " " . Filter::moneyFormatter($cmAddonCharge) ?></i>
				</div>
			</div>
			<h6 class="mb-0 text-right"><span class="txtAddonCharge"><?php echo $minusSymbol . '' . Filter::moneyFormatter($addonCharge); ?></span></h6>
		</div>
		<div class="d-flex justify-content-between p5 additionalcharge hide">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0 extrachargeremark">Additional charges:</h6>
				</div>
			</div>
			<h6 class="mb-0 text-right extracharge"><?= ($model->bkgInvoice->bkg_additional_charge > 0) ? Filter::moneyFormatter($model->bkgInvoice->bkg_additional_charge) : ''; ?></h6>
		</div>
		<? $staxrate		 = $model->bkgInvoice->getServiceTaxRate(); ?>
		<div class="d-flex justify-content-between p5 <?= ($model->bkgInvoice->bkg_cgst > 0) ? '' : 'hide' ?>">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0">CGST (@<?= Yii::app()->params['cgst'] ?>%):</h6>
				</div>
			</div>
			<h6 class="mb-0 text-right"><?php echo Filter::moneyFormatter(((Yii::app()->params['cgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax) | 0); ?></h6>
		</div>
		<div class="d-flex justify-content-between p5 <?= ($model->bkgInvoice->bkg_sgst > 0) ? '' : 'hide' ?>">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0">SGST (@<?= Yii::app()->params['sgst'] ?>%):</h6>
				</div>
			</div>
			<h6 class="mb-0 text-right"><?php echo Filter::moneyFormatter(((Yii::app()->params['sgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax) | 0); ?></h6>
		</div>
		<div class="d-flex justify-content-between p5 vwDriverAllowance clsBilling <?= ($model->bkgInvoice->bkg_driver_allowance_amount > 0) ? '' : 'hide' ?>">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0">Driver allowance:</h6>
				</div>
			</div>
			<h6 class="mb-0 text-right"><span class="txtDriverAllowance"><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_driver_allowance_amount); ?></span></h6>
		</div>
		<div class="d-flex justify-content-between p5 vwTollTax clsBilling <?= ($model->bkgInvoice->bkg_toll_tax > 0) ? '' : 'hide' ?>">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0">Toll tax:</h6>
					<small class="text-muted">
						<?php
						if (($model->quote->routeRates->isTollIncluded != null && $model->quote->routeRates->isTollIncluded > 0) || $model->bkgInvoice->bkg_is_toll_tax_included > 0)
						{
							echo "(Included)";
						}
						if (($model->quote->routeRates->isTollIncluded != null && $model->quote->routeRates->isTollIncluded <= 0) || $model->bkgInvoice->bkg_is_toll_tax_included <= 0)
						{
							echo "(Excluded)";
						}
						?>
					</small>
				</div>
			</div>
			<h6 class="mb-0 text-right"><span class="tolltax txtTollTax"><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_toll_tax); ?></span></h6>
		</div>
		<div class="d-flex justify-content-between p5 vwAirportCharge clsBilling <?= ($model->bkgInvoice->bkg_airport_entry_fee > 0) ? '' : 'hide' ?>">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0">Airport entry charge:</h6>
				</div>
			</div>
			<h6 class="mb-0 text-right"><span class="txtAirportFee"><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_airport_entry_fee); ?></span></h6>
		</div>
		<div class="d-flex justify-content-between p5 vwStateTax clsBilling <?= ($model->bkgInvoice->bkg_state_tax > 0) ? '' : 'hide' ?>">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0">Other taxes:</h6>
					<small class="text-muted">(Including state tax / Green tax etc)</small>
				</div>
			</div>
			<h6 class="mb-0 text-right"><span  class="txtStateTax"><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_state_tax); ?></span></h6>
		</div>
		<div class="d-flex justify-content-between p5">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0">GST (@<?= Yii::app()->params['igst'] ?>%):</h6>
				</div>
			</div>
			<h6 class="mb-0 text-right"><span class="taxAmount txtGstAmount"><?php echo Filter::moneyFormatter(((Yii::app()->params['igst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax) | 0); ?></span></h6>
		</div>
	</div>
	<div class="border-gray-bottom mt10 mb10"></div>
	<div class="col-12 p0">
		<div class="d-flex justify-content-between p5">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0 font-14 weight500"><b>Total Fare</b></h6>
				</div>
			</div>
			<h6 class="mb-0 text-right weight500" style="border-bottom: double 5px;border-top: solid 1px;padding: 5px 0 5px 5px;"><span class="txtEstimatedAmount"><?php echo Filter::moneyFormatter(round($model->bkgInvoice->bkg_total_amount)); ?></span></h6>
		</div>
		<div class="d-flex justify-content-between p5 vwAdvancePaid <?= ($model->bkgInvoice->bkg_advance_amount > 0) ? '' : 'hide' ?>">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0"><?= ($model->bkg_agent_id == 1249 || !$model->bkg_agent_id > 0) ? 'Advance paid' : 'Advance received by partner' ?>:</h6>
				</div>
			</div>
			<h6 class="mb-0 text-right"><span  class="txtAdvancePaid"><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_advance_amount); ?></span></h6>
		</div>
		<div class="d-flex justify-content-between p5 vwDriverCollect <?= (in_array($model->bkg_status, [6,7])) ? '' : 'hide' ?>">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0">Driver collected:</h6>
				</div>
			</div>
			<h6 class="mb-0 text-right"><span  class="txtDriverCollect"><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_vendor_collected); ?></span></h6>
		</div>
		<div class="d-flex justify-content-between p5 tdcredit vwGozoCoinsUsed <?= ($model->bkgInvoice->bkg_credits_used > 0) ? '' : 'hide' ?>">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0 color-red">Gozo coins applied:</h6>
				</div>
			</div>
			<h6 class="mb-0 text-right">(-)<span class="creditUsed txtGozoCoinsUsed color-red"><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_credits_used); ?></span></h6>
		</div>
		<div class="d-flex justify-content-between p5 vwRefund <?= ($model->bkgInvoice->bkg_refund_amount > 0 && ($model->bkg_agent_id == 1249 || !$model->bkg_agent_id > 0) && $model->bkg_status == 9) ? '' : 'hide' ?>">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0"><?= ($model->bkg_agent_id == 1249 || !$model->bkg_agent_id > 0) ? 'Refund Processed' : 'Refund Processed' ?>:</h6>
				</div>
			</div>
			<h6 class="mb-0 text-right"><span  class="txtAdvancePaid"><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_refund_amount); ?></span></h6>
		</div>
		<div class="d-flex justify-content-between vwDueAmount p5 mb10 <?= ($model->bkgInvoice->bkg_total_amount == $model->bkgInvoice->bkg_due_amount) ? 'hide' : '' ?>">
			<div class="vwDueAmount"> 			
				<div class="sales-info d-flex align-items-center">
					<div class="sales-info-content">
						<h6 class="mb-0"><b> <?= ($model->bkg_agent_id == 1249 || !$model->bkg_agent_id > 0) ? 'Amount due' : 'Amount  payable to driver' ?>:</b></h6>
					</div>
				</div> 
			</div>
			<h6 class="mb-0 text-right"><b><span class="txtDueAmount"><?php echo Filter::moneyFormatter(($model->bkgInvoice->bkg_corporate_remunerator == 2 && $model->bkg_agent_id > 0) ? 0 : $model->bkgInvoice->bkg_due_amount); ?></span></b></h6>
		</div>
		<div class="d-flex justify-content-between p5  vwPromoCoins clsBilling  <?= ($model->bkgInvoice->bkg_promo1_coins > 0) ? '' : 'hide' ?>">
			<div class="sales-info d-flex align-items-center">
				<div class="sales-info-content">
					<h6 class="mb-0"><span class="color-green2 font-13"><span class="txtPromoCoins"><?= Filter::moneyFormatter($model->bkgInvoice->bkg_promo1_coins) ?> </span> Gozo coins will be credited to your account after booking completion</span></h6>
				</div>
			</div>
		</div>
	</div>
</div>

