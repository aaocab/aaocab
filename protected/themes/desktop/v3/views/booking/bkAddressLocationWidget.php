<?php
if (($model->bkgFromCity->cty_garage_address == $model->bkg_pickup_address || $model->bkg_pickup_address == '' || $model->bkgToCity->cty_garage_address == $model->bkg_drop_address || $model->bkg_drop_address == '') && $model->bkg_booking_type != 4)
{
	?>
	<div class="container mt5 addressAdd">
		<div class="row">
			<div class="col-12 col-lg-10 offset-lg-1 mb30">
				<div class="bg-white-box">
					<div class="row">
						<div class="col-9 heading-part mb10"><b>UPDATE YOUR PICKUP & DROP ADDRESSES1</b></div>
						<div class="col-3 text-center">
							<button type="button" id="saveNewAddreses" class="btn btn-effect-ripple btn-success p5 mt10" name="saveNewAddreses" onclick="saveAddressesByRoutes();">Save Addresses</button>
						</div>
					</div>
					<?php $this->renderPartial('pickupLocationWidget', ['model' => $model], false, false); ?>
				</div>
			</div></div></div>
	<input type="hidden" value="<?php echo ($model->bkgFromCity->cty_garage_address == $model->bkg_pickup_address || $model->bkg_pickup_address == '' || $model->bkgToCity->cty_garage_address == $model->bkg_drop_address || $model->bkg_drop_address == '') ? '0' : '1' ?>" class="isPickupAdrsCls" name="isPickupAdrsCls">
	<?php
}
?>