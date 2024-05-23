<?php
/** @var RouteRates $routeRates */
$routeRates = clone $quote->routeRates;
$routeRates->calculateTotal();
?>
<ul class='list-unstyled'>
	<li class='text-left'>Base Fare: <span class='float-right'>₹<?= $routeRates->baseAmount ?></span></li>
	<li class='text-left text-danger <?=($routeRates->discount > 0)?"":"hide"?>'>Discount<sup>*</sup>(Apply <?= $routeRates->promoRow['prm_code'] ?>): 
			<span class='float-right'>₹<?= $routeRates->discount; ?></span></li>
	<li class='text-left <?=($routeRates->discount > 0)?"":"hide"?>'>Discounted Base Fare: 
			<span class='float-right'>₹<?= ($routeRates->baseAmount - $routeRates->discount); ?></span></li>
	<li class='text-left <?=($routeRates->driverAllowance > 0)?"":"hide"?>'>Driver Allowance: 
			<span class='float-right'>₹<?= $routeRates->driverAllowance; ?></span></li>
	<li class='text-left'>Toll Tax (<?= ($routeRates->isTollIncluded > 0) ? "Included" : "Excluded" ?>): 
			<span class='float-right'>₹<?= ($routeRates->tollTaxAmount > 0) ? $routeRates->tollTaxAmount : "0";?></span></li>
	<li class='text-left'>State Tax (<?= ($routeRates->isStateTaxIncluded > 0) ? "Included" : "Excluded" ?>): 
			<span class='float-right'>₹<?=  ($routeRates->stateTax > 0) ?  $routeRates->stateTax : "0"; ?></span></li>
	<li class='text-left'>GST: <span class='float-right'>₹<?= $routeRates->gst ?></span></li>
	<li class='text-left'>Total Payable: <span class='float-right'>₹<?= $routeRates->totalAmount; ?></span></li>
    <li class='text-left text-success <?=($routeRates->coinDiscount > 0)?"":"hide"?>'>Gozo Coins<sup>*</sup>(Apply <?= $routeRates->promoRow['prm_code'] ?>): 
							<span class='float-right'>₹<?= $routeRates->coinDiscount; ?></span></li>

</ul>