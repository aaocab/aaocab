<style>
	.accordion .card {
		margin-bottom: 5px;
	}
	.imgHeight{ 
		max-height: 600px;
		max-width: 100%;
	}
	.card-body{
		padding: 1rem!important; 
	}
</style> 
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/dco/register.js?v=1.07");
/** @var \Contact $cttmodel */
$vndId	 = $contactData['cr_is_vendor'];
$drvId	 = $contactData['cr_is_driver'];
$drvStr	 = '';
if ($drvId > 0)
{
	$drvModel	 = Drivers::model()->findByPk($drvId);
	$drvStr		 = 'Driver code is <span class="font-weight-bolder">' . $drvModel->drv_code . '</span>.';
}
$checkIcon			 = ' bx-check';
$alternateicon		 = ''; // bx-chevron-right';
$operatorTypeCheck	 = (is_numeric($isDCO)) ? $checkIcon : $alternateicon;
/** @var \Contact $cttModel */
$vndCreated			 = ($vndId > 0);
$hasLicense			 = (trim($cttModel->ctt_license_no) != '' && $cttModel->ctt_license_doc_id > 0);
$hasPAN				 = (trim($cttModel->ctt_pan_no) != '' && $cttModel->ctt_pan_doc_id > 0);
$hasSelfie			 = (trim($cttModel->ctt_profile_path) != '' );
$hasAadhar			 = (trim($cttModel->ctt_aadhaar_no) != '' && $cttModel->ctt_aadhar_doc_id > 0);
$hasPV				 = ( $cttModel->ctt_police_doc_id > 0);
$hasServiceType		 = ( $vndPref->vnp_oneway > 0 || $vndPref->vnp_daily_rental > 0);

$basicDataCheck			 = ($vndCreated) ? $checkIcon : $alternateicon;
$licenceCheck			 = ($hasLicense) ? $checkIcon : $alternateicon;
$panCheck				 = ($hasPAN) ? $checkIcon : $alternateicon;
$selfieCheck			 = ($hasSelfie) ? $checkIcon : $alternateicon;
$aadharCheck			 = ($hasAadhar) ? $checkIcon : $alternateicon;
$pvCheck				 = ($hasPV) ? $checkIcon : $alternateicon;
$operatingServicesCheck	 = ( $hasServiceType ) ? $checkIcon : $alternateicon;

$allUpload = ( $uploadDone == 1 ) ? $checkIcon : $alternateicon;

$disableBasic		 = (is_numeric($isDCO) ) ? "accordion collapse-icon " : '';
$disableBasicDrop	 = (is_numeric($isDCO) ) ? "collapse" : '';
$disabled			 = ($vndId > 0) ? "accordion collapse-icon " : '';
$disableDrop		 = ($vndId > 0) ? "collapse" : '';

//$canAddVehicle	 = ($vndCreated && $hasLicense && $hasPAN && $hasServiceType ) ;
$canAddVehicle	 = $vndCreated;
$vehicleAddUrl	 = ($canAddVehicle ) ? "/vehicle/info" : "#";
/** @var \Vendors $vndModel */
$msg1			 = '<div class="alert alert-primary  mb0">We have registered your request. 
			Your reference code is <span class="font-weight-bolder">' . $vndModel->vnd_code . '</span>. ' . $drvStr . ' 
			Upload all the required documents for faster approval. 
			You will get a call from us very soon.</div>';

$msg2 = '<div class="alert alert-success mb0">
Thank you for Registering with us. Your Partner ID is <span class="font-weight-bolder">' . $vndModel->vnd_code . '</span>. ' . $drvStr . ' 
We are in the process of reviewing your documents. 
We would notify you and activate your account as soon as the details provided are validated.
 </div>';

$msg = '<div class="alert  mb0"><b>Please Enter more details<b> </div>';

//if($vndModel->vnd_active==1 && $vndModel->)

$showMessage = ($vndModel->vnd_id > 0 && ($vndModel->vnd_active != 1 || $vndModel->vnd_active != 2) && $canAddVehicle);
if ($showMessage)
{
	$msg = $msg1;
	if ($uploadDone == 1)
	{
		$msg = $msg2;
	}
	?>

	<?
}
?>
<div class="container p5">
	<?php echo $msg; ?>
</div>

<div class="container p5">
	<div class="row accordion-widget">
		<div class="col-12">
			<div class="accordion collapse-icon accordion-icon-rotate " id="accordionWrapa1" data-toggle-hover="true">
				<div id="accordionWrapLink">
					<div class="card collapse-header"  >
						<button  id="optype" class="card-header text-dark" data-toggle="collapse" data-target="#optypePanel" aria-expanded="false" aria-controls="optypePanel" role="button">

							<span class="collapse-title"> 
								<span class="align-middle">Operator Type</span>
							</span>
							<i class="bx font-weight-bolder <?= $operatorTypeCheck ?> float-right text-success"></i>
						</button>
					</div>
					<?
					if (is_numeric($isDCO))
					{
						?>
						<div class="card collapse-header" >
							<button id="basicInfo" class="basicInfoVal card-header text-dark" data-toggle="<?= $disableBasicDrop ?>" data-target="#basicInfoPanel" aria-expanded="false" aria-controls="basicInfoPanel" role="button">
								<span class="collapse-title">
									<span class="align-middle">Basic Info</span>
								</span>
								<i class="bx font-weight-bolder <?= $basicDataCheck ?> float-right text-success"></i>
							</button>
						</div>
						<?
					}
					if ($vndId > 0)
					{
						?>
						<div class="card collapse-header">
							<button id="optServices" class="card-header   text-dark" data-toggle="<?= $disableDrop ?>" data-target="#optServicesPanel" aria-expanded="false" aria-controls="optServicesPanel" role="tablist">
								<span class="collapse-title">
									<span class="align-middle">Operating Services</span>
								</span>
								<i class="bx font-weight-bolder <?= $operatingServicesCheck ?> float-right text-success"></i>
							</button>

						</div>
						<div class="card collapse-header ">
							<button id="panDoc" class="card-header  text-dark" data-toggle="<?= $disableDrop ?>" data-target="#panDocPanel" aria-expanded="false" aria-controls="panDocPanel" role="tablist">
								<span class="collapse-title">
									<span class="align-middle">PAN Card Info</span>
								</span>
								<i class="bx font-weight-bolder <?= $panCheck ?> float-right text-success"></i>
							</button>
						</div>
						<div class="card collapse-header ">
							<button id="driverLicense" class="card-header  text-dark" data-toggle="<?= $disableDrop ?>" data-target="#driverLicensePanel" aria-expanded="false" aria-controls="driverLicensePanel" role="tablist">
								<span class="collapse-title">
									<span class="align-middle">Driving License</span>
								</span>
								<i class="bx font-weight-bolder <?= $licenceCheck ?> float-right text-success"></i>
							</button>
						</div>

						<div class="card collapse-header">
							<button id="selfie" class="card-header collapsed text-dark" data-toggle="<?= $disableDrop ?>" data-target="#selfiePanel" aria-expanded="false" aria-controls="selfiePanel"  >
								<span class="collapse-title">
									<span class="align-middle">Selfie with ID  </span>
								</span>
								<i class="bx font-weight-bolder <?= $selfieCheck ?> float-right text-success"></i>
							</button>

						</div>

						<?php
						//	if (!in_array($alternateicon, [$licenceCheck, $aadharCheck, $operatingServicesCheck]))
						{
							?>

							<div class="card collapse-header hide">
								<button id="aadhar" class="card-header collapsed text-dark" data-toggle="<?= $disableDrop ?>" data-target="#aadharPanel" aria-expanded="false" aria-controls="aadharPanel" role="tablist">
									<span class="collapse-title">
										<span class="align-middle">Aadhar Card </span>
									</span>
									<i class="bx font-weight-bolder <?= $aadharCheck ?> float-right text-success"></i>
								</button>
							</div>

							<div class="card collapse-header">
								<button id="policeVeri" class="card-header  collapsed text-dark" data-toggle="<?= $disableDrop ?>" data-target="#policeVeriPanel" aria-expanded="false" aria-controls="policeVeriPanel" role="tablist">
									<span class="collapse-title">
										<span class="align-middle">Police Verification Certificate (Optional) </span>
									</span>
									<i class="bx font-weight-bolder <?= $pvCheck ?> float-right text-success"></i>
								</button>
							</div>
							<div class="card" >
								<a href="<?php echo $vehicleAddUrl ?>" id="vhcInfo"  class="card-header collapsed text-dark" data-toggle="collapse"   >
									<span class="collapse-title1">
										<span class=" align-middle ">Add Vehicle Info  </span>
									</span>
									<i class="bx font-weight-bolder <?= $allUpload ?> float-right text-success"></i>

								</a>
							</div>
							<?
						}
					}
					?>
				</div>

				<div  id="accordionWrapView">

					<? $this->renderPartial('operatorTypemini', ['isDCO' => $isDCO]) ?>
					<? $this->renderPartial('basicInfomini', ['isDCO' => $isDCO, 'cttModel' => $cttModel, 'errorMsg' => $errorMsg]) ?>
					<?
					if ($vndId > 0)
					{
						?>
						<? $this->renderPartial('operatingServicesmini', ['cttModel' => $cttModel, 'vndPref' => $vndPref]) ?>
						<? $this->renderPartial('aadharDocmini', ['cttModel' => $cttModel, 'docAdharModel' => $docAdharModel, 'errorMsg' => $errorMsg]) ?>
						<? $this->renderPartial('selfieDocmini', ['cttModel' => $cttModel, 'errorMsg' => $errorMsg]) ?>
						<? $this->renderPartial('licenseDocmini', ['cttModel' => $cttModel, 'docLicenseModel' => $docLicenseModel, 'errorMsg' => $errorMsg]) ?>
						<? $this->renderPartial('panDocmini', ['cttModel' => $cttModel, 'docPANModel' => $docPANModel, 'errorMsg' => $errorMsg]) ?>
						<? $this->renderPartial('policeVeriDocmini', ['cttModel' => $cttModel, 'docPoliceVerModel' => $docPoliceVerModel, 'errorMsg' => $errorMsg]) ?>
						<?
					}
					?>

				</div>

			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var formType;
	$(document).ready(function () {

//		$(".basicInfoVal").removeClass("collapsed");
		formType = '<?php echo $formType ?>';
		if (formType != '') {
			$("#accordionWrapLink").hide();
		}

		switch (formType) {
			case 'opt':
				$("#optypePanel").addClass("show");
				break;
			case 'bi':
				$("#basicInfoPanel").addClass("show");
				break;
			case 'opserv':
				$("#optServicesPanel").addClass("show");
				break;
			case 'lic':
				$("#driverLicensePanel").addClass("show");
				break;
			case 'pan':
				$("#panDocPanel").addClass("show");
				break;
			case 'selfie':
				$("#selfiePanel").addClass("show");
				break;
			case 'aadhar':
				$("#aadharPanel").addClass("show");
				break;
			case 'pv':
				$("#policeVeriPanel").addClass("show");
				break;

			default:
				break;
		}

	});
	$("#accordionWrapLink").on('click', function () {
		$("#accordionWrapLink").hide();
	});


	$("#vhcInfo").on('click', function () {
		location.href = this.href;
	});
</script>