<?php
if ( ($model->bkgFromCity->cty_garage_address == $model->bkg_pickup_address || $model->bkg_pickup_address == '') && $model->bkg_booking_type != 4)
{
	?>
	<div class="container mt5 addressAdd">
		<div class="row">
			<div class="col-12 col-lg-10 offset-lg-1 mb30">


				<?php $this->renderPartial('pickupLocationWidget', ['model' => $model], false, false); ?>

			</div></div></div>
	 <?php
}
else
{
	$this->renderPartial("bkSummaryTripPlan", ["model" => $model], false, false);
}
?>

<script>

    $('.accordion-content').show();

</script>