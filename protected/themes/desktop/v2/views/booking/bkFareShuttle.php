 <ul class='list-unstyled'>
	<li>Base Fare: <span class='float-right'>&#x20B9;<?= $shuttle['slt_base_fare'] ?></span></li>
	<li>GST(<?= Filter::getServiceTaxRate(); ?>%): <span class='float-right'>&#x20B9;<?= $shuttle['slt_gst'] ?></span></li>
	<li class='<?= ($routeRates->driverAllowance > 0) ? "" : "hide" ?>'>Driver Allowance: 
		<span class='float-right'>&#x20B9;<?= $shuttle['slt_driver_allowance'] ?></span></li>
	<li>Toll Tax (Included): <span class='float-right'>&#x20B9;<?= $shuttle['slt_toll_tax'] ?></span></li>
	<li>State Tax (Included): <span class='float-right'>&#x20B9;<?= $shuttle['slt_state_tax'] ?></span></li>
	<li>Fare Per Seat: <span class='float-right'>&#x20B9;<?= $shuttle['slt_price_per_seat'] ?></span></li>
</ul>
