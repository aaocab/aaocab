<style type="text/css">
    .bordered {
        border: 1px solid #ddd;
        min-height: 45px;
        line-height: 1.2em;
        margin-bottom: 5px;
        margin-left: 10px;
        margin-right: 10px;
        padding-bottom: 10px;
    }

	.accordion .card {
		margin-bottom: 5px;
	} 
	.imgHeight{ 
		max-height: 600px;
		max-width: 100%;
	}
</style>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/dco/vehicle.js?v=1.4");
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask1.min.js');

$checkIcon			 = ' bx-check';
$alternateicon		 = ''; // bx-chevron-right';
$basicCheck			 = ($vhcModel->vhc_type_id > 0 && $vhcModel->vhc_type_id > 0 && $vhcModel->vhc_color != '') ? $checkIcon : $alternateicon;
$numPlatePhoto		 = ($licenceFPlateDocModel->vhd_id > 0 && $licenceRPlateDocModel->vhd_id > 0) ? $checkIcon : $alternateicon;
$cabPhoto			 = ($cabFrontImageModel->vhd_id > 0 && $cabRearImageModel->vhd_id > 0) ? $checkIcon : $alternateicon;
$permitPhoto		 = ($permitDocModel->vhd_id > 0 ) ? $checkIcon : $alternateicon;
$registrationPhoto	 = ($rcFrontDocModel->vhd_id > 0 && $rcBackDocModel->vhd_id > 0 ) ? $checkIcon : $alternateicon;
$insurancePhoto		 = ($insuranceDocModel->vhd_id > 0 ) ? $checkIcon : $alternateicon;

$msg1 = '<div class="alert alert-primary  mb0">We have registered your request. 
			Your reference code is <span class="font-weight-bolder">' . $vndModel->vnd_code . '</span>. 
			Upload all the required documents for faster approval. 
			You will get a call from us very soon.</div>';

$msg2	 = '<div class="alert alert-success mb0">You have successfully uploaded documents. 
			Your reference code is <span class="font-weight-bolder">' . $vndModel->vnd_code . '</span>. 
			We are in process of reviewing and approving your documents. 
			Once you are approved, you will be able to login in our app.</div>';
$msg	 = $msg1;
if ($vhcModel->vhc_type_id > 0 && ($licenceFPlateDocModel->vhd_id > 0 && $licenceRPlateDocModel->vhd_id > 0) &&
		($cabFrontImageModel->vhd_id > 0 && $cabRearImageModel->vhd_id > 0) &&
		$permitDocModel->vhd_id > 0 && $rcFrontDocModel->vhd_id > 0 && $insuranceDocModel->vhd_id > 0)
{
	$msg = $msg2;
}

[$basicCheck, $numPlatePhoto, $cabPhoto, $permitPhoto, $registrationPhoto, $insurancePhoto];

$showMessage = (($vndModel->vnd_active != 1 || $vndModel->vnd_active != 2) );
if ($showMessage)
{
	?>
	<div class="container p5">
		<?php echo $msg ?>
	</div>
	<?
}
?>


<div class="container p5">
	<div class="row accordion-widget">
		<div class="col-12">
			<div class="accordion collapse-icon accordion-icon-rotate " id="accordionWrap2" data-toggle-hover="true">

				<div id="accordionWrapLink">

					<div class="card collapse-header"  >
						<button id="trnsport" class="card-header " data-toggle="collapse" data-target="#transportPanel" aria-expanded="false" aria-controls="transportPanel" role="button">
							<span class="collapse-title"> 
								<span class="align-middle">Enter vehicle details</span>
							</span>
							<i class="bx font-weight-bolder <?php echo $basicCheck ?> float-right text-success"></i>
						</button>

					</div>
					<?php
					if ($vhcModel->vhc_id > 0)
					{
						?>
						<div class="card collapse-header"  >
							<button id="noPlate" class="card-header  " data-toggle="collapse" data-target="#noPlatePanel" aria-expanded="false" aria-controls="noPlatePanel" role="button">
								<span class="collapse-title"> 
									<span class="align-middle">Number Plate</span>
								</span>
								<i class="bx font-weight-bolder <?php echo $numPlatePhoto ?> float-right text-success"></i>
							</button>

						</div>

						<div class="card collapse-header"  >
							<button id="cabPhoto" class="card-header" data-toggle="collapse" data-target="#cabPhotoPanel" aria-expanded="false" aria-controls="cabPhotoPanel" role="button">
								<span class="collapse-title"> 
									<span class="align-middle">Photo of your vehicle</span>
								</span>
								<i class="bx font-weight-bolder <?php echo $cabPhoto ?> float-right text-success"></i>
							</button>

						</div>
						<div class="card collapse-header"  >
							<button id="permit" class="card-header  " data-toggle="collapse" data-target="#permitPanel" aria-expanded="false" aria-controls="permitPanel" role="button">
								<span class="collapse-title"> 
									<span class="align-middle">Permit</span>
								</span>
								<i class="bx font-weight-bolder <?php echo $permitPhoto ?> float-right text-success"></i>
							</button>

						</div>
						<div class="card collapse-header"  >
							<button id="regCertificate" class="card-header  " data-toggle="collapse" data-target="#regCertificatePanel" aria-expanded="false" aria-controls="regCertificatePanel" role="button">
								<span class="collapse-title"> 
									<span class="align-middle">Registration Certificate</span>
								</span>
								<i class="bx font-weight-bolder <?php echo $registrationPhoto ?> float-right text-success"></i>
							</button>

						</div>
						<div class="card collapse-header"  >
							<button id="insurance" class="card-header  " data-toggle="collapse" data-target="#insurancePanel" aria-expanded="false" aria-controls="insurancePanel" role="button">
								<span class="collapse-title"> 
									<span class="align-middle">Vehicle Insurance</span>
								</span>
								<i class="bx font-weight-bolder <?php echo $insurancePhoto ?> float-right text-success"></i>
							</button>

						</div>
					<?php } ?>
					<div class="card" >
						<a href="<?php echo "/operator/register" ?>" id="opertorInfo"  class="card-header text-dark align-middle "    >
							<span class="collapse-title line-height-1">
								<span class="align-middle "><i class=" font-weight-bolder bx bx-chevron-left float-left "></i>Back to registration</span>
							</span>

						</a>
					</div>
				</div>
				<div  id="accordionWrapView">
					<? $this->renderPartial('transport', ['vhcModel' => $vhcModel]) ?>
					<?
					$this->renderPartial('numberplate', ['vhcModel'				 => $vhcModel,
						'licenceFPlateDocModel'	 => $licenceFPlateDocModel, 'licenceRPlateDocModel'	 => $licenceRPlateDocModel])
					?>
					<? $this->renderPartial('cabPhoto', ['vhcModel' => $vhcModel, 'cabFrontImageModel' => $cabFrontImageModel, 'cabRearImageModel' => $cabRearImageModel]) ?>
					<?php $this->renderPartial('permit', ['vhcModel' => $vhcModel, 'permitDocModel' => $permitDocModel]) ?>
					<?php $this->renderPartial('regCertificate', ['vhcModel' => $vhcModel, 'rcFrontDocModel' => $rcFrontDocModel, 'rcBackDocModel' => $rcBackDocModel]) ?>
					<? $this->renderPartial('insurance', ['vhcModel' => $vhcModel, 'insuranceDocModel' => $insuranceDocModel]) ?>

				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$("#accordionWrapLink").on('click', function () {
		$("#accordionWrapLink").hide();
	});
</script>