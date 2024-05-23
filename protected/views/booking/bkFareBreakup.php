<?php
/** @var RouteRates $routeRates */
$routeRates = clone $quote->routeRates;
$routeRates->calculateTotal();
?>
<ul class='list-unstyled'>
	<li>Base Fare: <span class='float-right'><i class='fa fa-inr'></i><span class='clsBaseAmt<?= $scvid ?>'><?= $routeRates->baseAmount ?></span></span></li>
	
	<li class='text-danger <?=($routeRates->discount > 0)?"":"hide"?>'>Discount<sup>*</sup>(Apply <?= $routeRates->promoRow['prm_code'] ?>): 
			<span class='float-right'><i class='fa fa-inr'></i><?= $routeRates->discount; ?></span></li>

	<li class='<?=($routeRates->driverAllowance > 0)?"":"hide"?>'>Driver Allowance: 
			<span class='float-right'><i class='fa fa-inr'></i><span class='clsDAAmt<?= $scvId ?>'><?= $routeRates->driverAllowance; ?></span></span></li>
	
	<li>Toll Tax (<?= ($routeRates->isTollIncluded > 0) ? "Included" : "Excluded" ?>): 
			<span class='float-right'><i class='fa fa-inr'></i><span class='clsTollAmt<?= $scvId ?>'><?= ($routeRates->tollTaxAmount > 0) ? $routeRates->tollTaxAmount : "0";?></span></span></li>
	
	<li>State Tax (<?= ($routeRates->isStateTaxIncluded > 0) ? "Included" : "Excluded" ?>): 
			<span class='float-right'><i class='fa fa-inr'></i><span class='clsStateAmt<?= $scvId ?>'><?=  ($routeRates->stateTax > 0) ?  $routeRates->stateTax : "0"; ?></span></span></li>
	<li class ='<?php echo ($routeRates->isAirportEntryFeeIncluded > 0) ? "" : "hide" ?>'>Airport Entry Fee: 
			<span class='float-right'><i class='fa fa-inr'></i><span class='clsAirportAmt<?= $scvId ?>'><?php echo  $routeRates->airportEntryFee; ?></span></span></li>

	<li>GST: <span class='float-right'><i class='fa fa-inr'></i><span class='clsGstAmt<?= $scvId ?>'><?= $routeRates->gst ?></span></span></li>
	
	<li>Total Payable: <span class='float-right'><i class='fa fa-inr'></i><span class='clsTotalAmt<?= $scvId ?>'><?= $routeRates->totalAmount; ?></span></span></li>
	
	<li class='text-success <?=($routeRates->coinDiscount > 0)?"":"hide"?>'>Gozo Coins<sup>*</sup>(Apply <?= $routeRates->promoRow['prm_code'] ?>): 
							<span class='float-right'><i class='fa fa-inr'></i><?= $routeRates->coinDiscount; ?></span></li>

</ul>