<div class="row">
<?php

$routeRates = $arrQuoteCat;

?><div class="col-12 mb10">
	Base Fare
        <span class="float-right"><span class="clsBaseAmt<?= $scvId ?>"><?php echo Filter::moneyFormatter($routeRates->baseFare); ?></span></span>
</div>
<?php if ($routeRates->discount > 0)
{
	?>		
	<div class="col-12 mb10">
		Discount (Apply <b><?= $routeRates->promoRow['prm_code']; ?></b>)
		<span class="float-right clsDiscount<?= $scvId ?>"><?php echo Filter::moneyFormatter($routeRates->discount); ?></span>
	</div>
	<div class="col-12 mb10">
		Discounted Base Fare
		<span class="float-right clsDiscount<?= $scvId ?>"><?php echo Filter::moneyFormatter(($routeRates->baseFare - $routeRates->discount)); ?></span>
	</div>
<?php } ?>		
<?php if ($routeRates->driverAllowance > 0)
{
	?>						
	<div class="col-12 mb10">
		Driver Allowance
                <span class="float-right"><span class="clsDAAmt<?= $scvId ?>"><?php echo Filter::moneyFormatter($routeRates->driverAllowance); ?></span></span>
	</div>
	<?php } ?>
<div class="col-12 mb10">
<?php $tTaxText	 = (($routeRates->tollIncluded > 0) ? "Included" : "Excluded"); ?>		
	Toll Tax (<?= $tTaxText ?>)
        <span class="float-right"><span class="clsTollAmt<?= $scvId ?>"><?php echo Filter::moneyFormatter(($routeRates->tollTax > 0) ? $routeRates->tollTax : "0"); ?></span></span>
</div>
<div class="col-12 mb10">
<?php $sTaxText	 = (($routeRates->isStateTaxIncluded > 0) ? "Included" : "Excluded"); ?>		
	State Tax (<?= $sTaxText ?>)
        <span class="float-right"><span class="clsStateAmt<?= $scvId ?>"><?php echo Filter::moneyFormatter(($routeRates->stateTax > 0) ? $routeRates->stateTax : "0"); ?></span></span>
</div>
<div class="col-12 mb10">
<?php  	
	if($routeRates->isAirportEntryFeeIncluded > 0) {?>
    Airport Entry Fee (Included)
    <span class="float-right"><span class="clsAirportAmt<?= $scvId ?>"><?php echo Filter::moneyFormatter(($routeRates->airportEntryFee > 0) ? $routeRates->airportEntryFee : "0"); ?></span></span>
<?php }else{ ?>
        Airport Entry Fee
        <span class="float-right"><span class="clsAirportAmt<?= $scvId ?>"><?php echo Filter::moneyFormatter(($routeRates->airportEntryFee > 0) ? $routeRates->airportEntryFee : "0"); ?></span></span>
        <p class="mb0 color-gray font-10 lineheight18" style="margin-top: -7px;"><i>(Pay Later if any Charges Applicable)</i></p>
<?php }?>
</div>
<div class="col-12 mb10">
	GST
        <span class="float-right"><span class="clsGstAmt<?= $scvId ?>"><?php echo Filter::moneyFormatter($routeRates->gst); ?></span>
		<span class="clsGstRate<?= $scvId ?> hide"><?php echo Filter::getServiceTaxRate(); ?></span>
	</span>
</div>
<div class="col-12 mb10 pb10 font-16" style="border-bottom: #e7e7e7 1px solid;">
	<b>Total Payable</b>
        <span class="float-right"><b><span class="ultrabold clsTotalAmt<?= $scvId ?>"><?php echo Filter::moneyFormatter($routeRates->totalAmount); ?></span></b></span>
</div>
<?php if ($routeRates->gozoCoins > 0)
{
	?>		
	<div class="col-12 mb10">
		Gozo Coins (Apply <b><?= $routeRates->promo->code; ?></b>)
                <span class="float-right"><?php echo Filter::moneyFormatter($routeRates->gozoCoins); ?></span>
	</div>
<?php } ?>
<?php 
$luggage = explode("||",$cabData);
?>
<div class="col-12 mb10">
    <span class="float-right">Luggage Capacity : <?php echo $luggage[1]; ?> Large bag(s) or <?php echo $luggage[0]; ?> Small bag(s)</span>		
	</div> 
</div>