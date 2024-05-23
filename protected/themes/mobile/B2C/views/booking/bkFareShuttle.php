<div class="store-cart-total">
	Base Fare
	<span><b class="text-normal">&#x20b9</b><?= $shuttle['slt_base_fare'] ?></span>
	<div class="clear"></div>
</div>
<div class="store-cart-total">
	GST(<?= Filter::getServiceTaxRate(); ?>%)
	<span><b class="text-normal">&#x20b9</b><?= $shuttle['slt_gst'] ?></span>
	<div class="clear"></div>
</div>
<?php  if ($routeRates->driverAllowance > 0) { ?>						
			<div class="store-cart-total">
				Driver Allowance
				<span><b class="text-normal">&#x20b9</b><?= $shuttle['slt_driver_allowance']?></span>
				<div class="clear"></div>
			</div>
<?php } ?>
<div class="store-cart-total">
    Toll Tax (Included)
	<span><b class="text-normal">&#x20b9</b><?= $shuttle['slt_toll_tax'] ?></span>
	<div class="clear"></div>
</div>
<div class="store-cart-total">
    State Tax (Included)
	<span><b class="text-normal">&#x20b9</b><?= $shuttle['slt_state_tax'] ?></span>
	<div class="clear"></div>
</div>
<div class="store-cart-total">
	<b>Fare Per Seat</b>
	<span>&#x20b9<b><?= $shuttle['slt_price_per_seat'] ?></b></span>
	<div class="clear"></div>
</div>


