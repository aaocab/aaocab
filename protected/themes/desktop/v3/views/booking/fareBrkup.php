<?php
	$objFare = $serviceTier->fare;
	$staxrate						 = BookingInvoice::getGstTaxRate($partnerId, $tripType);

	if ($serviceTier->discountedFare != null)
	{
		$objFare = $serviceTier->discountedFare;
	}

	$isFromAirport	 = Cities::model()->findByPk($routes[0]->source->code)->cty_is_airport;
	$isDropAirport	 = Cities::model()->findByPk($routes[0]->destination->code)->cty_is_airport;
?>
<div class="invoice-subtotal">
	<div class="invoice-calc d-flex justify-content-between"><span>Base Fare:</span> <span><?php echo Filter::moneyFormatter($objFare->baseFare); ?></span></div>
	<?php  if ($objFare->promo) {?>
    <div class="invoice-calc d-flex justify-content-between color-red"><span> Promo (<?php echo $objFare->promo; ?>)*:</span> 
							<span class="float-right color-red weight500"><?php echo Filter::moneyFormatter($objFare->discount); ?></span></div>

	<?php }?>	
    <hr class="mt5 mb5">
	<div class="invoice-calc d-flex justify-content-between <?= ($objFare->discount > 0) ? '' : 'hide'; ?>"><span><b>Net Base Fare: </b></span>
			<span><strong><?php echo Filter::moneyFormatter(($objFare->baseFare - $objFare->discount)); ?></strong></span></div>

	<div class="invoice-calc d-flex justify-content-between <?= ($objFare->driverAllowance > 0) ? '' : 'hide' ?>"><span>Driver Allowance:</span>
			<span><?php echo Filter::moneyFormatter($objFare->driverAllowance); ?></span></div>

	<div class="invoice-calc d-flex justify-content-between"><span>Toll Tax (<?php echo ($objFare->tollIncluded > 0) ? "Included" : "Excluded"; ?>): </span>
			<span><?php echo Filter::moneyFormatter(($objFare->tollTax > 0) ? $objFare->tollTax : "0"); ?></span></div>

	<div class="invoice-calc d-flex justify-content-between <?= ($objFare->stateTax > 0) ? '' : 'hide' ?>"><span>State Tax (<?php echo ($objFare->stateTaxIncluded > 0) ? "Included" : "Excluded"; ?>): </span>
			<span><?php echo Filter::moneyFormatter(($objFare->stateTax > 0) ? $objFare->stateTax : "0"); ?></span></div>

	<div class="invoice-calc d-flex justify-content-between <?= ($isFromAirport == 1 || $isDropAirport == 1)? '':'hide' ?>"><span>Airport Entry Fee (<?php echo ($objFare->isAirportEntryFeeIncluded > 0) ? "Included" : "Pay Later if any Charges Applicable"; ?>): </span>
			<span><?php echo Filter::moneyFormatter(($objFare->airportEntryFee > 0) ? $objFare->airportEntryFee : "0"); ?></span></div>

	<div class="invoice-calc d-flex justify-content-between"><span>GST (<?php echo $staxrate; ?>)%:</span><span><?php echo Filter::moneyFormatter($objFare->gst); ?></span></div>
<hr class="mt5 mb5">
	<div class="invoice-calc d-flex justify-content-between weight500"><span><b>Total Payable:</b></span> <span class="weight500"><strong><?php echo Filter::moneyFormatter($objFare->totalAmount); ?></strong></span></div>

	<?php  if ($objFare->promoCoins) {?>
    <div class="invoice-calc d-flex justify-content-between font-12 mt5 color-green2"><?php echo Filter::moneyFormatter($objFare->promoCoins); ?> GozoCoins will be credited to your account after booking completion </div>
    <?php }?>						
</div>