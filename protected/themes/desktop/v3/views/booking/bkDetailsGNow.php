<?php
/** @var CActiveForm $form */
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'cabcategory',
	'enableClientValidation' => false,
	'clientOptions'			 => [
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	],
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'action'				 => Yii::app()->createUrl('booking/tierQuotes'),
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off',
	),
		));
?>


<?php
if (count($quotes) > 0)
{
	var_dump($quotes);
	?>
	<div class="container mb-2">
		<div class="alert alert-danger mb-2 text-center hide alertcatclass" role="alert"></div>

		<div class="col-12 text-center mb-3 style-widget-1"><h3 class="gothic weight600">What class of service are you looking for your <?= $categoryInfo['vct_label'] ?></h3></div>

		<div class="row">
			<?php
			foreach ($quotes as $qt)
			{

				$class = ($qt['scvVehicleServiceClass'] == 4 ) ? "scvServiceClass4" : "scvServiceClass";
				?>
				<div class="col-xl-3 col-md-6 col-sm-12">
					<div class="card text-center pt-1">
						<div class="card-header text-center pt10" style="display: inline-block;">
							<h4 class="card-title text-center weight500 text-uppercase"><?= $qt['scvVehicleClass'] ?></h4>
						</div>
						<div class="card-body">
							<p class="weight400 mb0">More comfort Reasonably priced</p>
							<p class="weight400 color-blue">
								<img src="/images/bxs-star.svg" alt="img" width="18" height="18"> <img src="/images/bxs-star.svg" alt="img" width="18" height="18"> <img src="/images/bxs-star.svg" alt="img" width="18" height="18"> <img src="/images/bxs-star.svg" alt="img" width="18" height="18">
							</p>

							<p class="mb0"><span class="font-24 weight600"><?php echo Filter::moneyFormatter($qt['baseFare']); ?></span></p>
							<p class="mb0">onwards</p>
							<div class="radio-style3">
								<div class="radio">
									<input id="cabclass<?= $qt['bkg_vehicle_type_id'] ?>" value="<?= $qt['bkg_vehicle_type_id'] ?>" type="radio" name="cabclass" class="<?= $class ?>">
									<label for="cabclass<?= $qt['bkg_vehicle_type_id'] ?>"></label>

								</div>
							</div>
						</div>

					</div>

				</div>
			<?php }
			?>
			<div class="col-xl-12 text-center">

				<input type="hidden" name="pageID" id="pageID" value="8">
				<input type="text" name="scvSclass" id="scvSclass" value="">
				<input type="submit" value="Book Now" name="yt0" id="serviceclassbtn" class="btn btn-primary pl-5 pr-5 serviceclassbtn">
				<input type="text" name="rid" value="<?= $rid; ?>" id="rid">

			</div>

		</div>
	</div>
	<?php
}
else
{
	echo "No cabs available";
}

$this->endWidget();
?>
<script>
	$(document).ready(function () {
		$('.scvServiceClass4').change(function () {
			$("#scvSclass").val(4);
		});
		$('.scvServiceClass').change(function () {
			$("#scvSclass").val("");
		});
	});
</script>