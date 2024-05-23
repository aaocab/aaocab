<?php
/** @var RouteRates $routeRates */
$routeRates = clone $quote->routeRates;
$routeRates->calculateTotal();
?><div class="store-cart-total">
	Base Fare
	<span><b class="text-normal">&#x20b9</b><span class="clsBaseAmt<?= $scvId ?>"><?= $routeRates->baseAmount ?></span></span>
	<div class="clear"></div>
</div>
<?php if ($routeRates->discount > 0)
{
	?>		
	<div class="store-cart-total color-orange-dark">
		Discount (Apply <b><?= $routeRates->promoRow['prm_code']; ?></b>)
		<span class="clsDiscount<?= $scvId ?>"><b class="text-normal">&#x20b9</b><?= $routeRates->discount; ?></span>
		<div class="clear"></div>
	</div>
	<div class="store-cart-total">
		Discounted Base Fare
		<span class="clsDiscount<?= $scvId ?>"><b class="text-normal">&#x20b9</b><?= ($routeRates->baseAmount - $routeRates->discount); ?></span>
		<div class="clear"></div>
	</div>
<?php } ?>		
<?php if ($routeRates->driverAllowance > 0)
{
	?>						
	<div class="store-cart-total">
		Driver Allowance
		<span><b class="text-normal">&#x20b9</b><span class="clsDAAmt<?= $scvId ?>"><?= $routeRates->driverAllowance ?></span></span>
		<div class="clear"></div>
	</div>
	<?php } ?>
<div class="store-cart-total">
<?php $tTaxText	 = (($routeRates->isTollIncluded > 0) ? "Included" : "Excluded"); ?>		
	Toll Tax (<?= $tTaxText ?>)
	<span><b class="text-normal">&#x20b9</b><span class="clsTollAmt<?= $scvId ?>"><?= ($routeRates->tollTaxAmount > 0) ? $routeRates->tollTaxAmount : "0"; ?></span></span>
	<div class="clear"></div>
</div>
<div class="store-cart-total">
<?php $sTaxText	 = (($routeRates->isStateTaxIncluded > 0) ? "Included" : "Excluded"); ?>		
	State Tax (<?= $sTaxText ?>)
	<span><b class="text-normal">&#x20b9</b><span class="clsStateAmt<?= $scvId ?>"><?= ($routeRates->stateTax > 0) ? $routeRates->stateTax : "0"; ?></span></span>
	<div class="clear"></div>
</div>
<div class="store-cart-total">
<?php  	
	if($routeRates->isAirportEntryFeeIncluded > 0) {?>
	Airport Entry Fee (Included)
	<span><b class="text-normal">&#x20b9</b><span class="clsAirportAmt<?= $scvId ?>"><?= ($routeRates->airportEntryFee > 0) ? $routeRates->airportEntryFee : "0"; ?></span></span>
<?php }else{ ?>
	Airport Entry Fee 
	<span><b class="text-normal">&#x20b9</b><span class="clsAirportAmt<?= $scvId ?>"><?= ($routeRates->airportEntryFee > 0) ? $routeRates->airportEntryFee : "0"; ?></span></span>
        <p class="mb0 color-gray font-10 lineheight16" style="margin-top: -7px;"><i>(Pay Later if any Charges Applicable)</i></p>
<?php }?>
<div class="clear"></div>
</div>
<div class="store-cart-total">
	GST
	<span><b class="text-normal">&#x20b9</b>
		<span class="clsGstAmt<?= $scvId ?>"><?= $routeRates->gst ?></span>
		<span class="clsGstRate<?= $scvId ?> hide"><?php echo Filter::getServiceTaxRate(); ?></span>
	</span>
	<div class="clear"></div>
</div>
<div class="store-cart-total pb10" style="border-bottom: #e7e7e7 1px solid;">
	<b>Total Payable</b>
	<span>&#x20b9<b><span class="ultrabold clsTotalAmt<?= $scvId ?>"><?= $routeRates->totalAmount; ?></span></b></span>
	<div class="clear"></div>
</div>
<?php if ($routeRates->coinDiscount > 0)
{
	?>		
	<div class="store-cart-total color-green2-dark">
		Gozo Coins (Apply <b><?= $routeRates->promoRow['prm_code']; ?></b>)
		<span><b class="text-normal">&#x20b9</b><?= $routeRates->coinDiscount; ?></span>
		<div class="clear"></div>
	</div>
<?php } ?>

