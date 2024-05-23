<?php
/* @var $model Vehicles */
if (isset($data['vhc_id']) && $data['vhc_id'] <> '')
{
	$model	 = Vehicles::model()->findByPk($data['vhc_id']);
	$vchDocs = VehicleDocs::model()->findAllByVhcId($model->vhc_id);

	if (count($vchDocs) > 0)
	{
		foreach ($vchDocs as $vchDocs)
		{
			switch ($vchDocs['vhd_type'])
			{
				case 1:
					$insuranceId			 = $vchDocs['vhd_id'];
					//$insuranceDoc = $vchDocs['vhd_file'];
					$insuranceStatus		 = $vchDocs['vhd_status'];
					$insuranceRemarks		 = $vchDocs['vhd_remarks'];
					$insuranceCreatedAt		 = $vchDocs['vhd_created_at'];
					$insuranceAppovedAt		 = $vchDocs['vhd_appoved_at'];
					$insuranceApproveBy		 = $vchDocs['vhd_approve_by'];
					//$insuranceS3Data = $vchDocs['vhd_s3_data'];
					$insuranceDoc			 = VehicleDocs::getDocPathById($vchDocs['vhd_id']);
					break;
				case 2:
					$frontLicenseId			 = $vchDocs['vhd_id'];
					//$frontLicenseDoc = $vchDocs['vhd_file'];
					$frontLicenseStatus		 = $vchDocs['vhd_status'];
					$frontLicenseRemarks	 = $vchDocs['vhd_remarks'];
					$frontLicenseCreatedAt	 = $vchDocs['vhd_created_at'];
					$frontLicenseAppovedAt	 = $vchDocs['vhd_appoved_at'];
					$frontLicenseApproveBy	 = $vchDocs['vhd_approve_by'];
					//$frontLicenseS3Data = $vchDocs['vhd_s3_data'];
					$frontLicenseDoc		 = VehicleDocs::getDocPathById($vchDocs['vhd_id']);
					break;
				case 3:
					$rearLicenseId			 = $vchDocs['vhd_id'];
					//$rearLicenseDoc = $vchDocs['vhd_file'];
					$rearLicenseStatus		 = $vchDocs['vhd_status'];
					$rearLicenseRemarks		 = $vchDocs['vhd_remarks'];
					$rearLicenseCreatedAt	 = $vchDocs['vhd_created_at'];
					$rearLicenseAppovedAt	 = $vchDocs['vhd_appoved_at'];
					$rearLicenseApproveBy	 = $vchDocs['vhd_approve_by'];
					$rearLicenseDoc			 = VehicleDocs::getDocPathById($vchDocs['vhd_id']);
					break;
				case 4:
					$pollutionId			 = $vchDocs['vhd_id'];
					//$pollutionDoc = $vchDocs['vhd_file'];
					$pollutionStatus		 = $vchDocs['vhd_status'];
					$pollutionRemarks		 = $vchDocs['vhd_remarks'];
					$pollutionCreatedAt		 = $vchDocs['vhd_created_at'];
					$pollutionAppovedAt		 = $vchDocs['vhd_appoved_at'];
					$pollutionApproveBy		 = $vchDocs['vhd_approve_by'];
					//$pollutionS3Data = $vchDocs['vhd_s3_data'];
					$pollutionDoc			 = VehicleDocs::getDocPathById($vchDocs['vhd_id']);
					break;
				case 5:
					$registrationId			 = $vchDocs['vhd_id'];
					//$registrationDoc = $vchDocs['vhd_file'];

					$registrationStatus		 = $vchDocs['vhd_status'];
					$registrationRemarks	 = $vchDocs['vhd_remarks'];
					$registrationCreatedAt	 = $vchDocs['vhd_created_at'];
					$registrationAppovedAt	 = $vchDocs['vhd_appoved_at'];
					$registrationApproveBy	 = $vchDocs['vhd_approve_by'];
					//$registrationS3Data = $vchDocs['vhd_s3_data'];
					$registrationDoc		 = VehicleDocs::getDocPathById($vchDocs['vhd_id']);
					break;
				case 6:
					$commercialPermitId		 = $vchDocs['vhd_id'];
					//$commercialPermitDoc = $vchDocs['vhd_file'];
					$commercialPermitStatus	 = $vchDocs['vhd_status'];
					$commercialPermitRemarks = $vchDocs['vhd_remarks'];
					$commercialCreatedAt	 = $vchDocs['vhd_created_at'];
					$commercialApproveAt	 = $vchDocs['vhd_appoved_at'];
					$commercialAppovedBy	 = $vchDocs['vhd_approve_by'];
					//$commercialS3Data = $vchDocs['vhd_s3_data'];
					$commercialPermitDoc	 = VehicleDocs::getDocPathById($vchDocs['vhd_id']);

					break;
				case 7:
					$fitnessCertificateId		 = $vchDocs['vhd_id'];
					//$fitnessCertificateDoc = $vchDocs['vhd_file'];
					$fitnessCertificateStatus	 = $vchDocs['vhd_status'];
					$fitnessRemarks				 = $vchDocs['vhd_remarks'];
					$fitnessCreatedAt			 = $vchDocs['vhd_created_at'];
					$fitnessAppovedAt			 = $vchDocs['vhd_appoved_at'];
					$fitnessApproveBy			 = $vchDocs['vhd_approve_by'];
					$fitnessCertificateDoc		 = VehicleDocs::getDocPathById($vchDocs['vhd_id']);
					break;
				case 8:
					$frontBoostImageId			 = $vchDocs['vhd_id'];
					//$frontBoostImageDoc = $vchDocs['vhd_file'];
					$frontBoostImageStatus		 = $vchDocs['vhd_status'];
					$frontBoostImageRemarks		 = $vchDocs['vhd_remarks'];
					$frontBoostImageCreatedAt	 = $vchDocs['vhd_created_at'];
					$frontBoostImageAppovedAt	 = $vchDocs['vhd_appoved_at'];
					$frontBoostImageApproveBy	 = $vchDocs['vhd_approve_by'];
					$frontBoostImageDoc			 = VehicleDocs::getDocPathById($vchDocs['vhd_id']);
					break;
				case 9:
					$backBoostImageId			 = $vchDocs['vhd_id'];
					//$backBoostImageDoc = $vchDocs['vhd_file'];
					$backBoostImageStatus		 = $vchDocs['vhd_status'];
					$backBoostImageRemarks		 = $vchDocs['vhd_remarks'];
					$backBoostImageAt			 = $vchDocs['vhd_created_at'];
					$backBoostImageCreatedAt	 = $vchDocs['vhd_created_at'];
					$backBoostImageAppovedAt	 = $vchDocs['vhd_appoved_at'];
					$backBoostImageApproveBy	 = $vchDocs['vhd_approve_by'];
					$backBoostImageDoc			 = VehicleDocs::getDocPathById($vchDocs['vhd_id']);
					break;
				case 10:
					$leftBoostImageId			 = $vchDocs['vhd_id'];
					//$leftBoostImageDoc = $vchDocs['vhd_file'];
					$leftBoostImageStatus		 = $vchDocs['vhd_status'];
					$leftBoostImageRemarks		 = $vchDocs['vhd_remarks'];
					$leftBoostImageCreatedAt	 = $vchDocs['vhd_created_at'];
					$leftBoostImageAppovedAt	 = $vchDocs['vhd_appoved_at'];
					$leftBoostImageApproveBy	 = $vchDocs['vhd_approve_by'];
					$leftBoostImageDoc			 = VehicleDocs::getDocPathById($vchDocs['vhd_id']);
					break;
				case 11:
					$rightBoostImageId			 = $vchDocs['vhd_id'];
					//$rightBoostImageDoc = $vchDocs['vhd_file'];
					$rightBoostImageStatus		 = $vchDocs['vhd_status'];
					$rightBoostImageRemarks		 = $vchDocs['vhd_remarks'];
					$rightBoostImageCreatedAt	 = $vchDocs['vhd_created_at'];
					$rightBoostImageAppovedAt	 = $vchDocs['vhd_appoved_at'];
					$rightBoostImageApproveBy	 = $vchDocs['vhd_approve_by'];
					$rightBoostImageDoc			 = VehicleDocs::getDocPathById($vchDocs['vhd_id']);
					break;
				case 13:

					//$registrationBackDoc = $vchDocs['vhd_file'];
					$registrationBackDoc = VehicleDocs::getDocPathById($vchDocs['vhd_id']);

					break;
				case 6:
			}
		}
	}
}

$insApproveStyle = ($insuranceDoc != '' && $insuranceStatus == 0) ? "display:block;" : "display:none;";
$insRejectStyle	 = ($insuranceDoc != '' && ($insuranceStatus == 0 || $insuranceStatus == 1)) ? "display:block;" : "display:none;";
$insReloadStyle	 = ($insuranceDoc != '' && $insuranceStatus == 2) ? "display:block;" : "display:none;";
if ($insuranceDoc != '')
{
	if ($insuranceStatus == 0)
	{
		$ins = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($insuranceStatus == 1)
	{
		$ins = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($insuranceStatus == 2)
	{
		$ins = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$ins = '';
}
$frontLicApproveStyle	 = ($frontLicenseDoc != '' && $frontLicenseStatus == 0) ? "display:block;" : "display:none;";
$frontLicRejectStyle	 = ($frontLicenseDoc != '' && ($frontLicenseStatus == 0 || $frontLicenseStatus == 1)) ? "display:block;" : "display:none;";
$frontLicReloadStyle	 = ($frontLicenseDoc != '' && $frontLicenseStatus == 2) ? "display:block;" : "display:none;";
if ($frontLicenseDoc != '')
{
	if ($frontLicenseStatus == 0)
	{
		$frtLic = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($frontLicenseStatus == 1)
	{
		$frtLic = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($frontLicenseStatus == 2)
	{
		$frtLic = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$frtLic = '';
}
$reartLicApproveStyle	 = ($rearLicenseDoc != '' && $rearLicenseStatus == 0) ? "display:block;" : "display:none;";
$reartLicRejectStyle	 = ($rearLicenseDoc != '' && ($rearLicenseStatus == 0 || $rearLicenseStatus == 1)) ? "display:block;" : "display:none;";
$reartLicReloadStyle	 = ($rearLicenseDoc != '' && $rearLicenseStatus == 2) ? "display:block;" : "display:none;";
if ($rearLicenseDoc != '')
{
	if ($rearLicenseStatus == 0)
	{
		$rearLic = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($rearLicenseStatus == 1)
	{
		$rearLic = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($rearLicenseStatus == 2)
	{
		$rearLic = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$rearLic = '';
}
$pucApproveStyle = ($pollutionDoc != '' && $pollutionStatus == 0) ? "display:block;" : "display:none;";
$pucRejectStyle	 = ($pollutionDoc != '' && ($pollutionStatus == 0 || $pollutionStatus == 1)) ? "display:block;" : "display:none;";
$pucReloadStyle	 = ($pollutionDoc != '' && $pollutionStatus == 2) ? "display:block;" : "display:none;";
if ($pollutionDoc != '')
{
	if ($pollutionStatus == 0)
	{
		$puc = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($pollutionStatus == 1)
	{
		$puc = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($pollutionStatus == 2)
	{
		$puc = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$puc = '';
}
$regApproveStyle = ($registrationDoc != '' && $registrationStatus == 0) ? "display:block;" : "display:none;";
$regRejectStyle	 = ($registrationDoc != '' && ($registrationStatus == 0 || $registrationStatus == 1)) ? "display:block;" : "display:none;";
$regReloadStyle	 = ($registrationDoc != '' && $registrationStatus == 2) ? "display:block;" : "display:none;";
if ($registrationDoc != '')
{
	if ($registrationStatus == 0)
	{
		$reg = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($registrationStatus == 1)
	{
		$reg = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($registrationStatus == 2)
	{
		$reg = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$reg = '';
}
$permitApproveStyle	 = ($commercialPermitDoc != '' && $commercialPermitStatus == 0) ? "display:block;" : "display:none;";
$permitRejectStyle	 = ($commercialPermitDoc != '' && ($commercialPermitStatus == 0 || $commercialPermitStatus == 1)) ? "display:block;" : "display:none;";
$permitReloadStyle	 = ($commercialPermitDoc != '' && $commercialPermitStatus == 2) ? "display:block;" : "display:none;";
if ($commercialPermitDoc != '')
{
	if ($commercialPermitStatus == 0)
	{
		$permit = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($commercialPermitStatus == 1)
	{
		$permit = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($commercialPermitStatus == 2)
	{
		$permit = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$permit = '';
}
$fitApproveStyle = ($fitnessCertificateDoc != '' && $fitnessCertificateStatus == 0) ? "display:block;" : "display:none;";
$fitRejectStyle	 = ($fitnessCertificateDoc != '' && ($fitnessCertificateStatus == 0 || $fitnessCertificateStatus == 1)) ? "display:block;" : "display:none;";
$fitReloadStyle	 = ($fitnessCertificateDoc != '' && $fitnessCertificateStatus == 2) ? "display:block;" : "display:none;";
if ($fitnessCertificateDoc != '')
{
	if ($fitnessCertificateStatus == 0)
	{
		$fit = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($fitnessCertificateStatus == 1)
	{
		$fit = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($fitnessCertificateStatus == 2)
	{
		$fit = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$fit = '';
}

$frontApproveStyle	 = ($frontBoostImageDoc != '' && $frontBoostImageStatus == 0) ? "display:block;" : "display:none;";
$frontRejectStyle	 = ($frontBoostImageDoc != '' && ($frontBoostImageStatus == 0 || $frontBoostImageStatus == 1)) ? "display:block;" : "display:none;";
$frontReloadStyle	 = ($frontBoostImageDoc != '' && $frontBoostImageStatus == 2) ? "display:block;" : "display:none;";
if ($frontBoostImageDoc != '')
{
	if ($frontBoostImageStatus == 0)
	{
		$front = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($frontBoostImageStatus == 1)
	{
		$front = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($frontBoostImageStatus == 2)
	{
		$front = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$front = '';
}

$backApproveStyle	 = ($backBoostImageDoc != '' && $backBoostImageStatus == 0) ? "display:block;" : "display:none;";
$backRejectStyle	 = ($backBoostImageDoc != '' && ($backBoostImageStatus == 0 || $backBoostImageStatus == 1)) ? "display:block;" : "display:none;";
$backReloadStyle	 = ($backBoostImageDoc != '' && $backBoostImageStatus == 2) ? "display:block;" : "display:none;";
if ($backBoostImageDoc != '')
{
	if ($backBoostImageStatus == 0)
	{
		$back = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($backBoostImageStatus == 1)
	{
		$back = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($backBoostImageStatus == 2)
	{
		$back = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$back = '';
}

$leftApproveStyle	 = ($leftBoostImageDoc != '' && $leftBoostImageStatus == 0) ? "display:block;" : "display:none;";
$leftRejectStyle	 = ($leftBoostImageDoc != '' && ($leftBoostImageStatus == 0 || $leftBoostImageStatus == 1)) ? "display:block;" : "display:none;";
$leftReloadStyle	 = ($leftBoostImageDoc != '' && $leftBoostImageStatus == 2) ? "display:block;" : "display:none;";
if ($leftBoostImageDoc != '')
{
	if ($leftBoostImageStatus == 0)
	{
		$left = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($leftBoostImageStatus == 1)
	{
		$left = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($leftBoostImageStatus == 2)
	{
		$left = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$left = '';
}


$rightApproveStyle	 = ($rightBoostImageDoc != '' && $rightBoostImageStatus == 0) ? "display:block;" : "display:none;";
$rightRejectStyle	 = ($rightBoostImageDoc != '' && ($rightBoostImageStatus == 0 || $rightBoostImageStatus == 1)) ? "display:block;" : "display:none;";
$rightReloadStyle	 = ($rightBoostImageDoc != '' && $rightBoostImageStatus == 2) ? "display:block;" : "display:none;";
if ($rightBoostImageDoc != '')
{
	if ($rightBoostImageStatus == 0)
	{
		$right = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($rightBoostImageStatus == 1)
	{
		$right = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($rightBoostImageStatus == 2)
	{
		$right = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$right = '';
}
?>
<style>
    @media (min-width: 992px){
        .modal-lg {
            width: 95%!important;
        }
    }
    @media (min-width: 768px){
        .modal-lg {
            width: 100%;
        }
    }
    .bordered {
        border: 1px solid #ddd;
        min-height: 45px;
        line-height: 1.2em;
        margin-bottom: 10px;
        margin-left: 10px;
        margin-right: 10px;
        padding-bottom: 10px;

    }

</style>
<div class="row widget-tab-content mb30">
    <div class="col-xs-12">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-lg-3">
                    <!-- Nav tabs -->
                    <div class="widget-tab-box">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="p15 pl20 ml5"><b>Cab's Information</b></li>
                            <li role="presentation" class="active"><a href="#carDetails" aria-controls="carDetails" role="tab" data-toggle="tab">Car Details</a></li>
                            <li role="presentation"><a href="#carDocuments" aria-controls="carDocuments" role="tab" data-toggle="tab">Car Documents</a></li>
                            <li role="presentation"><a href="#activityLogs" aria-controls="activityLogs" role="tab" data-toggle="tab">Activity Log</a></li>
                            <li role="presentation"><a href="#bookingHistory" aria-controls="bookingHistory" role="tab" data-toggle="tab">Booking History</a></li>
                            <li role="presentation"><a href="#lou" aria-controls="lou" role="tab" data-toggle="tab">LOU</a></li>                            
                            <li role="presentation"><a href="#odometerHistory" aria-controls="odometerHistory" role="tab" data-toggle="tab">Odometer History</a></li>
                            <li role="presentation"><a href="#reviewHistory" aria-controls="reviewHistory" role="tab" data-toggle="tab">Review History</a></li>
							<li role="presentation"><a href="#documentLog" aria-controls="documentLog" role="tab" data-toggle="tab">Document Log</a></li>

                        </ul>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-8 col-lg-9">
                    <!-- Tab panes -->
                    <div class="widget-tab-box">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="carDetails">
                                <div class="panel">
                                    <div class="panel-heading p0 pt5"><?= $model->vhcType->vht_make; ?> <?= $model->vhcType->vht_model; ?> - <?php echo $data[label]; ?> (<?= $model->vhc_number; ?>)</div>
                                    <div class="panel-body p0 pt20">
                                        <div class="row">
                                            <div class="col-xs-12 mb30">
												<?php
												$date1		 = date_create(date("Y-M-d"));
												$date2		 = date_create($data['vhs_boost_approved_date']);
												$diff2		 = date_diff($date1, $date2);
												//if($diff2->days >15){
												$daysdiff	 = 15 - $diff2->days;
												$boostdiff	 = $daysdiff . ' days';
												$boostdiff1	 = abs($daysdiff) . ' days';
												//}
												//else{
												//	$boostdiff = $diff2->format("%R%a days");
												//} 
												if ($data['vhc_is_freeze'] == 1)
												{
													echo '<span class="btn-4 mr15">Frozen</span>';
												}
												?>
                                                <span class="<?= ($data['approve_status'] == 'Verified' || $data['approve_status'] == 'Approved' ) ? 'btn-5' : 'btn-4'; ?> mr15"><?= $data['approve_status']; ?></span>
                                                <span class=" <?= ($data['vhs_boost_approved_date'] != '' && $diff2->days > 15 ) ? 'btn-4' : 'btn-5'; ?> mr15" <?= ($data['vhs_boost_enabled'] == 1 ) ? '' : 'hidden'; ?>><?= ($daysdiff >= 0 ? 'Boost expires in ' . $boostdiff : 'Boost expired ' . $boostdiff1 . ' ago' ) ?></span>
												<?php /* if($data['vhs_boost_enabled'] == 1){ ?>
												  <span class="<?= ($data['vhs_boost_verify'] == 1) ? 'btn-5' : 'btn-4'; ?> mr15">Boost ver. <?= ($data['vhs_boost_verify'] == 1) ? 'Completed' : 'pending'; ?></span>
												  <?php } */ ?>
												<span class="<?= ($data['vhs_boost_enabled'] == 1 && $daysdiff >= 0) ? 'btn-5' : 'btn-4'; ?> mr15"><?= ($data['vhs_boost_enabled'] == 1 && $daysdiff >= 0) ? 'Boosted' : 'Non-Boosted'; ?></span> 
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="col-xs-12 col-md-8">
                                                        <div class="widget-tab-box2">
                                                            <div class="row mb20">
                                                                <div class="col-xs-12 col-md-8">
                                                                    <h1 class="mb5"><i class="fas fa-car-side"></i> Car Details</h1>
																	<?php
																	if ($model->vhc_year != "")
																	{
																		$date	 = date("Y");
																		$diff	 = $model->vhc_year;
																		$diffe	 = $date - $diff;
																	}
																	else
																	{
																		$diffe = 0;
																	}

																	if ($model->vhc_total_trips != "")
																	{
																		$vhc_total_trips = $model->vhc_total_trips;
																	}
																	else
																	{
																		$vhc_total_trips = 0;
																	}
//                                                                    $years = floor($diff / (365 * 60 * 60 * 24));
																	?>
                                                                    <p class="color-gray">DOJ: <b><?= date('d M Y', strtotime($model->vhc_created_at)); ?></b> | <b><?= $diffe; ?>+</b> year old | <b><?= $vhc_total_trips; ?>+</b> trip completed</p>
                                                                </div>  
                                                                <div class="col-xs-12 col-md-4 text-right">
                                                                    <p class="mb0">
                                                                        <i class="fas fa-star color-<?= ($model->vhc_overall_rating >= 1) ? 'yellow' : 'gray'; ?>"></i>
                                                                        <i class="fas fa-star color-<?= ($model->vhc_overall_rating >= 2) ? 'yellow' : 'gray'; ?>"></i>
                                                                        <i class="fas fa-star color-<?= ($model->vhc_overall_rating >= 3) ? 'yellow' : 'gray'; ?>"></i>
                                                                        <i class="fas fa-star color-<?= ($model->vhc_overall_rating >= 4) ? 'yellow' : 'gray'; ?>"></i>
                                                                        <i class="fas fa-star color-<?= ($model->vhc_overall_rating >= 5) ? 'yellow' : 'gray'; ?>"></i>
                                                                    </p>
																	<?php
																	if ($model->vhc_overall_rating)
																	{
																		$vhc_overall_rating = $model->vhc_overall_rating;
																	}
																	else
																	{
																		$vhc_overall_rating = 0;
																	}
																	?>                                                                  
                                                                    <p class="color-gray"><?= $vhc_overall_rating; ?>/5 Rating <?php
																		if ($data['countRating'] != '')
																		{
																			echo '(' . $data['countRating'] . ' people)';
																		}
																		else
																		{
																			echo '';
																		}
																		?><!--(57 people)--></p>
                                                                </div>  
                                                            </div> 
                                                            <div class="row mb10">
                                                                <div class="col-xs-4">
                                                                    <p class="mb0 color-gray">Car record number</p>
                                                                    <p class="font-14"><b><?= $model->vhc_number; ?></b></p>
                                                                </div>
                                                                <div class="col-xs-3">
                                                                    <p class="mb0 color-gray">Car code</p>
                                                                    <p class="font-14"><b><?= $model->vhc_code; ?></b></p>
                                                                </div>
                                                                <div class="col-xs-5">
                                                                    <p class="mb0 color-gray">Last trip date</p>
                                                                    <p class="font-14"><b><?php
																			if ($data['last_pickup_date'] != '')
																			{
																				echo date('d/m/Y h:i A', strtotime($data['last_pickup_date']));
																			}
																			else
																			{
																				echo "-";
																			}
																			?></b></p>
                                                                </div>

                                                                <div class="col-xs-12">
                                                                    <p class="mb0 color-gray">Last odometer reading</p>
                                                                    <p class="font-14"><B><?php echo ($model->vhc_end_odometer != null ? $model->vhc_end_odometer : '-'); ?></B>
																		<?php /*  $odo_last_exists = current(array_filter($pastData, function($item) {
																		  return (isset($item['bkg_start_odometer']) || $item['bkg_start_odometer']!=NULL || isset($item['bkg_end_odometer']) || $item['bkg_end_odometer']!=NULL);
																		  }));
																		  //                                                                echo '<pre>';print_r($odo_last_exists);
																		  if($odo_last_exists!='' || count($odo_last_exists) > 0)
																		  {
																		  ?>
																		  <b> Start Reading : <?php echo (isset($odo_last_exists['bkg_start_odometer']))?$odo_last_exists['bkg_start_odometer']:' - '; ?><br>
																		  End Reading : <?php echo (isset($odo_last_exists['bkg_end_odometer']))?$odo_last_exists['bkg_end_odometer']:' - '; ?>
																		  </b>
																		  <?php } else { echo '-'; } */ ?>
                                                                    </p>
                                                                </div>

                                                            </div>
                                                            <div class="row mb20">
                                                                <div class="col-xs-4">
                                                                    <div class="row">
                                                                        <div class="col-xs-4 pr0"><div class="icon-bg"><img src="/images/gas-station.svg" alt="" width="20"></div></div>
                                                                        <div class="col-xs-8 pl0">
                                                                            <p class="mb0 color-gray lineheight14">Mileage</p>
                                                                            <p class="font-14 lineheight14"><b><?= $model->vhcType->vht_average_mileage; ?> MPG</b></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-4">
                                                                    <div class="row">
                                                                        <div class="col-xs-4 pr0"><div class="icon-bg"><i class="fas fa-users font-16 pt5"></i></div></div>
                                                                        <div class="col-xs-8 pl0">
                                                                            <p class="mb0 color-gray lineheight14">Passengers</p>
                                                                            <p class="font-14 lineheight14"><b><?php
																					if ($model->vhcType->vht_capacity != '')
																					{
																						echo $model->vhcType->vht_capacity;
																					}
																					else
																					{
																						echo "0";
																					}
																					?></b></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-4">
                                                                    <div class="row">
                                                                        <div class="col-xs-4 pr0"><div class="icon-bg"><i class="fas fa-suitcase font-16 pt5"></i></div></div>
                                                                        <div class="col-xs-8 pl0">
                                                                            <p class="mb0 color-gray lineheight14">Luggages</p>
                                                                            <p class="font-14 lineheight14"><b><?php
																					if ($model->vhcType->vht_bag_capacity != '')
																					{
																						echo $model->vhcType->vht_bag_capacity;
																					}
																					else
																					{
																						echo "0";
																					}
																					?></b></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb10">
                                                                <div class="col-xs-12 mb30">
                                                                    <h2>Tags</h2>
                                                                    <span class="tags-btn" <?php
																	if ($model->vhc_has_rooftop_carrier > 0)
																	{
																		?> style="background:#48b9a7"> <i class="fas fa-check mr5"></i> <?php
																		  }
																		  else
																		  {
																			  ?> style="background:#ff4646"> <i class="fas fa-times mr5"></i> <?php } ?> Rooftop Carriers</span>
                                                                    <span class="tags-btn" <?php
																	if ($model->vhc_has_cng > 0)
																	{
																		?> style="background:#48b9a7"> <i class="fas fa-check mr5"></i> <?php
																		  }
																		  else
																		  {
																			  ?> style="background:#ff4646"> <i class="fas fa-times mr5"></i> <?php } ?> CNG</span>
                                                                    <span class="tags-btn" <?php
																	if ($data['vhs_is_partition'] == 1)
																	{
																		?> style="background:#48b9a7"> <i class="fas fa-check mr5"></i> <?php
																		  }
																		  else
																		  {
																			  ?> style="background:#ff4646"> <i class="fas fa-times mr5"></i> <?php } ?> Partitioned</span>
                                                                    <span class="tags-btn" <?php
																	if ($model->vhc_is_commercial > 0)
																	{
																		?> style="background:#48b9a7"> <i class="fas fa-check mr5"></i> <?php
																		  }
																		  else
																		  {
																			  ?> style="background:#ff4646"> <i class="fas fa-times mr5"></i> <?php } ?> Commercial</span><br><br>
                                                                    <span class="tags-btn" <?php
																	if ($model->vhc_is_uber_approved > 0)
																	{
																		?> style="background:#48b9a7"> <i class="fas fa-check mr5"></i> <?php
																		  }
																		  else
																		  {
																			  ?> style="background:#ff4646"> <i class="fas fa-times mr5"></i> <?php } ?> UBER Approved</span>
																	<span class="tags-btn" <?php
																	if ($model->vhc_has_electric > 0)
																	{
																		?> style="background:#48b9a7"> <i class="fas fa-check mr5"></i> <?php
																		  }
																		  else
																		  {
																			  ?> style="background:#ff4646"> <i class="fas fa-times mr5"></i> <?php } ?> Electric</span>
                                                                </div>
                                                                <div class="col-xs-12"> 
                                                                    <h2>Trip Type</h2> 
																	<?php
//                                                                    $available_trip_type
																	if ($data['vhc_trip_type'] <> NULL)
																	{
																		$available_trip_type = Vehicles::getTripType();
																		Vehicles::getType($data['vhc_trip_type']);

																		$vhc_trip_type		 = explode(",", Vehicles::getType($data['vhc_trip_type']));
																		$vhc_trip_type_id	 = explode(",", $data['vhc_trip_type']);

																		for ($i = 1; $i <= count($available_trip_type); $i++)
																		{
																			if ($vhc_trip_type_id[$i - 1] == 1)
																			{
																				$data1 = "yes";
																			}
																			else if ($vhc_trip_type_id[$i - 1] == 2)
																			{
																				$data2 = "yes";
																			}
																			else if ($vhc_trip_type_id[$i - 1] == 3)
																			{
																				$data3 = "yes";
																			}
																		}
																		?>
																		<span class="tags-btn2" <?php
																		if ($data1 == "yes")
																		{
																			?>  style="background:#48b9a7"> <i class="fas fa-check mr5"></i><?php
																			  }
																			  else
																			  {
																				  ?> style="background:#ff4646"> <i class="fas fa-times mr5"></i><?php } ?>Outstation</span>
																		<span class="tags-btn2" <?php
																		if ($data2 == "yes")
																		{
																			?>  style="background:#48b9a7"> <i class="fas fa-check mr5"></i><?php
																			  }
																			  else
																			  {
																				  ?> style="background:#ff4646"> <i class="fas fa-times mr5"></i><?php } ?>Local</span>
																		<span class="tags-btn2" <?php
																		if ($data3 == "yes")
																		{
																			?>  style="background:#48b9a7"> <i class="fas fa-check mr5"></i><?php
																			  }
																			  else
																			  {
																				  ?> style="background:#ff4646"> <i class="fas fa-times mr5"></i><?php } ?>Airport Transfer</span>
																			<?php
//                                                                        }
																		}
																		?> 
                                                                </div>



                                                            </div>
                                                        </div>
                                                    </div>
													<div class="col-xs-12 col-md-4">
														<div class="row">
															<div class="col-xs-12 mb20">
																<div class="widget-tab-box2 link-infos">
																	<h1 class="font-16">Actions</h1>
																	<ul class="pl0">
																		<li class="mb5"><a href="<?php echo Yii::app()->createUrl("/admpnl/vehicle/docapprovallist", ['cabid' => $model->vhc_id]) ?>" target="_blank"><i class="fas fa-plus mr5 font-11"></i>Show and approve doc</a></li>
																	</ul>
																	<ul class="pl0">
																		<li class="mb5"><a href="<?php echo Yii::app()->createUrl("/admpnl/vehicle/add", ['veditid' => $model->vhc_id]) ?>" target="_blank"><i class="fas fa-plus mr5 font-11"></i>Edit Vehicle</a></li>
																	</ul>
																	<ul class="pl0">
																		<?php
																		$callDeleteUrl	 = Yii::app()->createUrl("admpnl/vehicle/delvehicle", array("vid" => $model->vhc_id));
																		?>
																		<li class="mb5"><a onclick="deleteVehicle(this);return false;" data-title="delete" href ="<?php echo $callDeleteUrl; ?>"><i class="fas fa-plus mr5 font-11"></i> Delete Vehicle</a></li>
																	</ul>  
																	<ul class="pl0">
																		<?php
																		$callMarkBadUrl	 = Yii::app()->createUrl("admpnl/vehicle/markedbadlist", array("vhc_id" => $model->vhc_id));
																		?>
																		<li class="mb5"><a onclick="markBad(this);return false;" data-title="Mark Bad Vehicle" href="<?php echo $callMarkBadUrl; ?>"><i class="fas fa-plus mr5 font-11"></i>Marked Bad Vehicle</a></li>
																	</ul>
																	<ul class="pl0">
																		<?php
																		$callFreezeUrl	 = Yii::app()->createUrl("admpnl/vehicle/freeze", array("vhc_id" => $model->vhc_id, "vhc_is_freeze" => $model->vhc_is_freeze));
																		if ($model->vhc_is_freeze == 1)
																		{
																			$objtitle = 'Unfreeze';
																		}
																		else
																		{
																			$objtitle = "Freeze";
																		}
																		?>
																		<li class="mb5"><a onclick="freezeVehicle(this);return false;" data-title="<?= $objtitle ?>" href ="<?php echo $callFreezeUrl; ?>"><i class="fas fa-plus mr5 font-11"></i><?= $objtitle ?> Vehicle</a></li>
																	</ul>
																	<ul class="pl0">
																		<?php
																		$callStaticalUrl = Yii::app()->createUrl("admpnl/vehicle/updatedetails", array("vhc_id" => $model->vhc_id));
																		?>
																		<li class="mb5"><a onclick="staticalData(this);return false;" data-title="Update Statistical Data" href="<?php echo $callStaticalUrl; ?>"><i class="fas fa-plus mr5 font-11"></i>Update Statistical Data</a></li>
																	</ul>
																	<ul class="pl0">
																		<li class="mb5"><a onclick="addremark(this);return false;" data-title="Add Remark" href ="<?php echo Yii::app()->createUrl("admpnl/vehicle/addremark", array("vhc_id" => $model->vhc_id)); ?>"><i class="fas fa-plus mr5 font-11"></i>Add Remark</a></li>
																	</ul>

																</div>
															</div>



														</div>
													</div>
                                                    <div class="col-xs-12 col-md-4">
                                                        <div class="widget-tab-box2">
                                                            <h1 class="font-16">Current Vendor Details</h1>
															<?php
															$vnd_name		 = explode(",", $data['vnd_name']);
															$vnd_code		 = explode(",", $data['vnd_code']);
															for ($i = 0; $i < count($vnd_name); $i++)
															{
																?>
																<div class="row mb20">
																	<div class="col-xs-3 pr0">
																		<div class="tags-btn3"><?php
																			$nameData = explode("_", $vnd_name[$i]);
																			echo VendorProfile::model()->getVendorNameInitials($nameData[0]);
																			?></div>
																	</div>
																	<div class="col-xs-9 pl5">
																		<h2 class="mt5 mb0"><?= $vnd_name[$i] ?></h2>
																		<p class="mb5"><a target="_blank"  href="<?= Yii::app()->createUrl('admin/vendor/profile', ['code' => $vnd_code[$i]]) ?>" target="_blank"><?= $vnd_code[$i] ?></a></p>

																	</div>
																</div>
															<?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="carDocuments">
                                <div class="panel">
                                    <div class="panel-heading p0 pt5"><?= $model->vhcType->vht_make; ?> <?= $model->vhcType->vht_model; ?> - <?php echo $data[label]; ?> (<?= $model->vhc_number; ?>)</div>
                                    <div class="panel-body p0 pt20">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="row mb20">
                                                            <div class="col-xs-12 widget-tab-box3">
                                                                <h1 class="mb5">Car Documents</h1>
                                                            </div> 
                                                        </div>
                                                        <div class="row" style="display: flex; flex-wrap: wrap; ">
															<?php // if($frontLicenseDoc != '' || $rearLicenseDoc != '' || $frontLicenseApproveBy != '' || $rearLicenseApproveBy != '' || $frontLicenseCreatedAt != "" || $rearLicenseCreatedAt != ""){    ?>
                                                            <div class="col-xs-12 col-md-4 widget-tab-box4">
                                                                <div class="panel">
                                                                    <div class="panel-body p15 pt0">
                                                                        <div class="row">
                                                                            <div class="col-xs-12 bg-blue">
                                                                                <h3 class="mt10 mb0">License Documents
                                                                                    <!--<a href="#" class="pull-right btn-6">Approved</a>-->
                                                                                </h3>
                                                                                <!--<p class="font-11 mb5"><span class="color-gray">Uploaded on</span> <b>12 mar 2021</b></p>-->                                                                                                                                                                
                                                                                <div class="row">
                                                                                    <div class="col-xs-6 text-center p5">

                                                                                        <a target="_blank"  href="<?= $frontLicenseDoc; ?>"><div class="image-box text-center"><img src="<?php echo ($frontLicenseDoc != '') ? $frontLicenseDoc : '/images/no-image.png'; ?>" alt=""></div></a>
                                                                                        <span class="font-10"><?php echo ($frontLicenseDoc != '') ? CHtml::link('Front number plate', $frontLicenseDoc, array('target' => '_blank')) : ''; ?></span>
                                                                                    </div> 
                                                                                    <div class="col-xs-6 text-center p5">
                                                                                        <a target="_blank"  href="<?= $rearLicenseDoc; ?>"><div class="image-box text-center"><img src="<?php echo ($rearLicenseDoc != '') ? $rearLicenseDoc : '/images/no-image.png'; ?>" alt=""></div></a>
                                                                                        <span class="font-10"><?php echo ($rearLicenseDoc != '') ? CHtml::link('Back number plate Link', $rearLicenseDoc, array('target' => '_blank')) : ''; ?></span>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                            <div class="col-xs-12 pt10">

                                                                                <p><span class="color-gray">Approved by</span>
                                                                                    <br>
                                                                                    <b><?php
																						if ($frontLicenseApproveBy != '' || $rearLicenseApproveBy != '')
																						{
																							echo Admins::model()->getFullNameById($frontLicenseApproveBy);
																							echo "<br>";
																							echo Admins::model()->getFullNameById($rearLicenseApproveBy);
																						}
																						else
																						{
																							echo "N/A";
																						}
																						?></b>
                                                                                </p>
                                                                                <p><span class="color-gray">Uploaded at</span>
                                                                                    <br>
                                                                                    <b><?php
																						if ($frontLicenseCreatedAt == NULL && $rearLicenseCreatedAt == NULL)
																						{
																							echo "N/A";
																						}
																						if ($frontLicenseCreatedAt != NULL)
																						{
																							echo date('d/m/Y h:i A', strtotime($frontLicenseCreatedAt));
																						}
																						echo "<br>";
																						if ($rearLicenseCreatedAt != NULL)
																						{
																							echo date('d/m/Y h:i A', strtotime($rearLicenseCreatedAt));
																						}
																						?></b>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
															<?php // }    ?>

															<?php
															if ($frontBoostImageDoc != '' || $frontBoostImageApproveBy != '' || $frontBoostImageCreatedAt != '' || $frontBoostImageRemarks != '')
															{
																?>
																<div class = "col-xs-12 col-md-4 widget-tab-box4">
																	<div class = "panel">
																		<div class = "panel-body p15 pt0">
																			<div class = "row">
																				<div class = "col-xs-12 bg-blue">
																					<h3 class = "mt10 mb0">Front Boost Image <a href = "#" class = "pull-right"><span id = "pollution" class = "<?= $front['class']; ?>" style = "<?= $front['style']; ?>;float:left;"><?= $front['level'];
																?></span></a></h3>
																					<div class="row">
																						<div class="col-xs-6 text-center p5">
																							<a target="_blank"  href="<?= $frontBoostImageDoc; ?>"><div class="image-box text-center"><img src="<?php echo ($frontBoostImageDoc != '') ? $frontBoostImageDoc : '/images/no-image.png'; ?>" alt=""></div></a>
																							<!--<span class="font-10"><?php echo ($frontBoostImageDoc != '') ? CHtml::link('Front Boost Image', $frontBoostImageDoc, array('target' => '_blank')) : ''; ?></span>-->
																						</div>

																					</div>
																				</div>
																				<div class="col-xs-12 pt10">
																					<p><span class="color-gray">Approved by</span>
																						<br>
																						<b><?php
																							if ($frontBoostImageApproveBy != '')
																							{
																								echo Admins::model()->getFullNameById($frontBoostImageApproveBy);
																							}
																							else
																							{
																								echo "N/A";
																							}
																							?></b>
																					</p>
																					<p><span class="color-gray">Uploaded at</span>
																						<br>
																						<b><?php
																							if ($frontBoostImageCreatedAt != '')
																							{
																								echo date('d/m/Y h:i A', strtotime($frontBoostImageCreatedAt));
																							}
																							else
																							{
																								echo "N/A";
																							}
																							?></b>
																					</p>
																					<?php
																					if ($frontBoostImageRemarks != '')
																					{
																						?>
																						<p><span class="color-gray">Remarks</span>
																							<br>
																							<span id="pollution33" style="<?= $frontReloadStyle; ?>;float:left;"><i><?= $frontBoostImageRemarks; ?></i></span>
																						</p>
																					<?php } ?>
																				</div>
																			</div>
																		</div>
																	</div>
																</div> 
															<?php } ?>
															<?php
															if ($backBoostImageDoc != '' || $backBoostImageApproveBy != '' || $backBoostImageCreatedAt != '' || $backBoostImageRemarks != '')
															{
																?>
																<div class = "col-xs-12 col-md-4 widget-tab-box4">
																	<div class = "panel">
																		<div class = "panel-body p15 pt0">
																			<div class = "row">
																				<div class = "col-xs-12 bg-blue">
																					<h3 class = "mt10 mb0">Back Boost Image <a href = "#" class = "pull-right"><span id = "pollution" class = "<?= $back['class']; ?>" style = "<?= $back['style']; ?>;float:left;"><?= $back['level'];
																?></span></a></h3>
																					<div class="row">
																						<div class="col-xs-6 text-center p5">
																							<a target="_blank"  href="<?= $backBoostImageDoc; ?>"><div class="image-box text-center"><img src="<?php echo ($backBoostImageDoc != '') ? $backBoostImageDoc : '/images/no-image.png'; ?>" alt=""></div></a>
																							<!--<span class="font-10"><?php echo ($backBoostImageDoc != '') ? CHtml::link('Back Boost Image', $backBoostImageDoc, array('target' => '_blank')) : ''; ?></span>-->
																						</div>

																					</div>
																				</div>
																				<div class="col-xs-12 pt10">
																					<p><span class="color-gray">Approved by</span>
																						<br>
																						<b><?php
																							if ($backBoostImageApproveBy != '')
																							{
																								echo Admins::model()->getFullNameById($backBoostImageApproveBy);
																							}
																							else
																							{
																								echo "N/A";
																							}
																							?></b>
																					</p>
																					<p><span class="color-gray">Uploaded at</span>
																						<br>
																						<b><?php
																							if ($backBoostImageCreatedAt != '')
																							{
																								echo date('d/m/Y h:i A', strtotime($backBoostImageCreatedAt));
																							}
																							else
																							{
																								echo "N/A";
																							}
																							?></b>
																					</p>
																					<?php
																					if ($backBoostImageRemarks != '')
																					{
																						?>
																						<p><span class="color-gray">Remarks</span>
																							<br>
																							<span id="pollution33" style="<?= $backReloadStyle; ?>;float:left;"><i><?= $backBoostImageRemarks; ?></i></span>
																						</p>
																					<?php } ?>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															<?php } ?>
															<?php
															if ($lefttBoostImageDoc != '' || $lefttBoostImageApproveBy != '' || $lefttBoostImageCreatedAt != '' || $lefttBoostImageRemarks != '')
															{
																?>
																<div class = "col-xs-12 col-md-4 widget-tab-box4">
																	<div class = "panel">
																		<div class = "panel-body p15 pt0">
																			<div class = "row">
																				<div class = "col-xs-12 bg-blue">
																					<h3 class = "mt10 mb0">Left Boost Image <a href = "#" class = "pull-right"><span id = "pollution" class = "<?= $left['class']; ?>" style = "<?= $left['style']; ?>;float:left;"><?= $left['level'];
																?></span></a></h3>
																					<div class="row">
																						<div class="col-xs-6 text-center p5">
																							<a target="_blank"  href="<?= $leftBoostImageDoc; ?>"><div class="image-box text-center"><img src="<?php echo ($leftBoostImageDoc != '') ? $leftBoostImageDoc : '/images/no-image.png'; ?>" alt=""></div></a>
																							<!--<span class="font-10"><?php echo ($leftBoostImageDoc != '') ? CHtml::link('Left Boost Image', $leftBoostImageDoc, array('target' => '_blank')) : ''; ?></span>-->
																						</div>

																					</div>
																				</div>
																				<div class="col-xs-12 pt10">
																					<p><span class="color-gray">Approved by</span>
																						<br>
																						<b><?php
																							if ($leftBoostImageApproveBy != '')
																							{
																								echo Admins::model()->getFullNameById($leftBoostImageApproveBy);
																							}
																							else
																							{
																								echo "N/A";
																							}
																							?></b>
																					</p>
																					<p><span class="color-gray">Uploaded at</span>
																						<br>
																						<b><?php
																							if ($leftBoostImageCreatedAt != '')
																							{
																								echo date('d/m/Y h:i A', strtotime($leftBoostImageCreatedAt));
																							}
																							else
																							{
																								echo "N/A";
																							}
																							?></b>
																					</p>
																					<?php
																					if ($leftBoostImageRemarks != '')
																					{
																						?>
																						<p><span class="color-gray">Remarks</span>
																							<br>
																							<span id="pollution33" style="<?= $leftReloadStyle; ?>;float:left;"><i><?= $leftBoostImageRemarks; ?></i></span>
																						</p>
																					<?php } ?>
																				</div>
																			</div>
																		</div>
																	</div>
																</div> 
															<?php } ?>
															<?php
															if ($rightBoostImageDoc != '' || $rightBoostImageApproveBy != '' || $rightBoostImageCreatedAt != '' || $rightBoostImageRemarks != '')
															{
																?>
																<div class = "col-xs-12 col-md-4 widget-tab-box4">
																	<div class = "panel">
																		<div class = "panel-body p15 pt0">
																			<div class = "row">
																				<div class = "col-xs-12 bg-blue">
																					<h3 class = "mt10 mb0">Right Boost Image <a href = "#" class = "pull-right"><span id = "pollution" class = "<?= $right['class']; ?>" style = "<?= $right['style']; ?>;float:right;"><?= $right['level'];
																?></span></a></h3>

																					<div class="row">
																						<div class="col-xs-6 text-center p5">
																							<a target="_blank"  href="<?= $rightBoostImageDoc; ?>"><div class="image-box text-center"><img src="<?php echo ($rightBoostImageDoc != '') ? $rightBoostImageDoc : '/images/no-image.png'; ?>" alt=""></div></a>
																							<!--<span class="font-10"><?php echo ($rightBoostImageDoc != '') ? CHtml::link('Right Boost Image', $rightBoostImageDoc, array('target' => '_blank')) : ''; ?></span>-->
																						</div>

																					</div>
																				</div>
																				<div class="col-xs-12 pt10">
																					<p><span class="color-gray">Approved by</span>
																						<br>
																						<b><?php
																							if ($rightBoostImageApproveBy != '')
																							{
																								echo Admins::model()->getFullNameById($rightBoostImageApproveBy);
																							}
																							else
																							{
																								echo "N/A";
																							}
																							?></b>
																					</p>
																					<p><span class="color-gray">Uploaded at</span>
																						<br>
																						<b><?php
																							if ($rightBoostImageCreatedAt != '')
																							{
																								echo date('d/m/Y h:i A', strtotime($rightBoostImageCreatedAt));
																							}
																							else
																							{
																								echo "N/A";
																							}
																							?></b>
																					</p>
																					<?php
																					if ($rightBoostImageRemarks != '')
																					{
																						?>
																						<p><span class="color-gray">Remarks</span>
																							<br>
																							<span id="pollution33" style="<?= $rightReloadStyle; ?>;float:right;"><i><?= $rightBoostImageRemarks; ?></i></span>
																						</p>
																					<?php } ?>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>     
															<?php } ?>
															<?php
															if ($leftBoostImageDoc != '' || $leftBoostImageApproveBy != '' || $leftBoostImageCreatedAt != '' || $leftBoostImageRemarks != '')
															{
																?>
																<div class = "col-xs-12 col-md-4 widget-tab-box4">
																	<div class = "panel">
																		<div class = "panel-body p15 pt0">
																			<div class = "row">
																				<div class = "col-xs-12 bg-blue">
																					<h3 class = "mt10 mb0">Left Boost Image <a href = "#" class = "pull-right">
																							<span id = "pollution" class = "<?= $left['class']; ?>" style = "<?= $left['style']; ?>;float:left;"><?= $left['level']; ?></span></a></h3>
																								<!--<span id="pollution1" style="<?= $leftApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $leftBoostImageId; ?>', '1')" style="cursor:pointer;"></span>-->
																								<!--<span id="pollution2" style="<?= $leftRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $leftBoostImageId; ?>', '2')" style="cursor:pointer;"></span>-->
																								<!--                                                                                <p class="font-11 mb5"><span class="color-gray">Uploaded on</span> <b><?php
//                                                                                        if ($model->vhc_reg_exp_date != NULL) {
//                                                                                            echo date('d/m/Y h:i A', strtotime($pollutionCreatedAt));
//                                                                                        } else {
//                                                                                            echo "N/A";
//                                                                                        }
																					?>-->
																					<div class="row">
																						<div class="col-xs-6 text-center p5">
																							<a target="_blank"  href="<?= Yii::app()->createUrl($leftBoostImageDoc); ?>"><div class="image-box text-center"><img src="<?php echo ($leftBoostImageDoc != '') ? $leftBoostImageDoc : '/images/no-image.png'; ?>" alt=""></div></a>
																							<!--<span class="font-10"><?php echo ($leftBoostImageDoc != '') ? CHtml::link('Left Boost Image', $rightBoostImageDoc, array('target' => '_blank')) : ''; ?></span>-->
																						</div>

																					</div>
																				</div>
																				<div class="col-xs-12 pt10">
																					<p><span class="color-gray">Left Boost expiry</span>
																						<br>
																						<b>N/A</b>
																					</p>
																					<p><span class="color-gray">Approved by</span>
																						<br>
																						<b><?php
																							if ($leftBoostImageApproveBy != '')
																							{
																								echo Admins::model()->getFullNameById($leftBoostImageApproveBy);
																							}
																							else
																							{
																								echo "N/A";
																							}
																							?></b>
																					</p>
																					<p><span class="color-gray">Uploaded at</span>
																						<br>
																						<b><?php
																							if ($leftBoostImageCreatedAt != '')
																							{
																								echo date('d/m/Y h:i A', strtotime($leftBoostImageCreatedAt));
																							}
																							else
																							{
																								echo "N/A";
																							}
																							?></b>
																					</p>
																					<?php
																					if ($leftBoostImageRemarks != '')
																					{
																						?>
																						<p><span class="color-gray">Remarks</span>
																							<br>
																							<span id="pollution33" style="<?= $leftReloadStyle; ?>;float:right;"><i><?= $leftBoostImageRemarks; ?></i></span>
																						</p>
																					<?php } ?>
																				</div>
																			</div>
																		</div>
																	</div>
																</div> 
															<?php } ?>

                                                            <div class="col-xs-12 col-md-4 widget-tab-box4">
                                                                <div class="panel">
                                                                    <div class="panel-body p15 pt0">
                                                                        <div class="row">
                                                                            <div class="col-xs-12 bg-blue"> 
                                                                                <h3 class="mt10 mb0">Registration Certificate <a href="#" class="pull-right"><span id="registration" class="<?= $reg['class']; ?>" style="<?= $reg['style']; ?>;float:left;"><?= $reg['level']; ?></span>
<!--                                                                                    <span id="registration1" style="<?= $regApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $registrationId; ?>', '1')" style="cursor:pointer;"></span>
                                                                                    <span id="registration2" style="<?= $regRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $registrationId; ?>', '2')" style="cursor:pointer;"></span>-->
                                                                                    </a></h3>
<!--                                                                                <p class="font-11 mb6"><span class="color-gray">Uploaded on</span> <b> <?php
//                                                                                        if (date($model->vhc_tax_exp_date) != NULL) {
//                                                                                            echo date('d/m/Y h:i A', strtotime($registrationCreatedAt));
//                                                                                        } else {
//                                                                                            echo "N/A";
//                                                                                        }
																				?> </b></p>-->

																				<div class="row">
                                                                                    <div class="col-xs-6 text-center p5">
                                                                                        <a target="_blank"  href="<?= $registrationDoc; ?>"><div class="image-box text-center"><img src="<?php echo ($registrationDoc != '') ? $registrationDoc : '/images/no-image.png'; ?>" alt=""></div></a>
                                                                                        <span class="font-10"><?php echo ($registrationDoc != '') ? CHtml::link('Front registration certificate', $registrationDoc, array('target' => '_blank')) : ''; ?></span>
                                                                                    </div> 
                                                                                    <div class="col-xs-6 text-center p5">
                                                                                        <a target="_blank"  href="<?= $registrationBackDoc; ?>"><div class="image-box text-center"><img src="<?php echo ($registrationBackDoc != '') ? $registrationBackDoc : '/images/no-image.png'; ?>" alt=""></div></a>
                                                                                        <span class="font-10"><?php echo ($registrationBackDoc != '') ? CHtml::link('Back registration certificate', $registrationBackDoc, array('target' => '_blank')) : ''; ?></span>
                                                                                    </div>

                                                                                </div>

                                                                            </div>
                                                                            <div class="col-xs-12 pt10">
                                                                                <p><span class="color-gray">Registration expiry</span>
                                                                                    <br>
                                                                                    <b><?php
																						if ($model->vhc_reg_exp_date != NULL)
																						{
																							echo date('d/m/Y h:i A', strtotime($model->vhc_reg_exp_date));
																						}
																						else
																						{
																							echo "N/A";
																						}
																						?></b>
                                                                                </p>
                                                                                <p><span class="color-gray">Approved By</span>
                                                                                    <br>
                                                                                    <b><?php
																						if ($registrationApproveBy != '')
																						{
																							echo Admins::model()->getFullNameById($registrationApproveBy);
																						}
																						else
																						{
																							echo "N/A";
																						}
																						?></b>
                                                                                </p>
                                                                                <p><span class="color-gray">Uploaded At</span>
                                                                                    <br>
                                                                                    <b><?php
																						if ($registrationCreatedAt != '')
																						{
																							echo date('d/m/Y h:i A', strtotime($registrationCreatedAt));
																						}
																						else
																						{
																							echo "N/A";
																						}
																						?></b>
                                                                                </p>
																				<?php
																				if ($registrationRemarks != '')
																				{
																					?>
																					<p><span class="color-gray">Remarks</span>
																						<br>                                                                                                                                                                        
																						<span id="registration33" style="<?= $regReloadStyle; ?>;float:left;"><i><?= $registrationRemarks; ?></i></span>
																					</p>
																				<?php } ?>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            

                                                            <div class="col-xs-12 col-md-4 widget-tab-box4">
                                                                <div class="panel">
                                                                    <div class="panel-body p15 pt0">
                                                                        <div class="row">
                                                                            <div class="col-xs-12 bg-blue">
                                                                                <h3 class="mt10 mb0">Insurance Certificate <a href="#" class="pull-right "><span id="insurance" class="<?= $ins['class']; ?>" style="<?= $ins['style']; ?>;float:left;"><?= $ins['level']; ?></span></a></h3>
                                                                                <!--<span id="insurance1" style="<?= $insApproveStyle; ?>;float:left;"><img id="insApprove" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $insuranceId; ?>', '1')" style="cursor:pointer;"></span>-->
                                                                    <!--<span id="insurance2" style="<?= $insRejectStyle; ?>;float:left;"><img id="insReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $insuranceId; ?>', '2')" style="cursor:pointer;"></span>-->
<!--                                                                                <p class="font-11 mb5"><span class="color-gray">Uploaded on</span> <b><?php
//                                                                                        if ($model->vhc_reg_exp_date != NULL) {
//                                                                                            echo date('d/m/Y h:i A', strtotime($insuranceCreatedAt));
//                                                                                        } else {
//                                                                                            echo "N/A";
//                                                                                        }
																				?></b></p>-->

                                                                                <div class="row">
                                                                                    <div class="col-xs-6 text-center p5">
																						<a target="_blank"  href="<?= $insuranceDoc; ?>"><div class="image-box text-center"><img src="<?php echo ($insuranceDoc != '') ? $insuranceDoc : '/images/no-image.png'; ?>" alt=""></div></a>
																					<!--<span class="font-10"><?php echo ($insuranceDoc != '') ? CHtml::link('Insurance Certificate', $insuranceDoc, array('target' => '_blank')) : ''; ?></span>-->
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-xs-12 pt10">
                                                                                <p><span class="color-gray">Insurance expiry</span>
                                                                                    <br>
                                                                                    <b><?php
																						if ($model->vhc_insurance_exp_date != '')
																						{
																							echo date('d/m/Y h:i A', strtotime($model->vhc_insurance_exp_date));
																						}
																						else
																						{
																							echo "N/A";
																						}
																						?> </b>
                                                                                </p>
                                                                                <p><span class="color-gray">Approved by</span>
                                                                                    <br>
                                                                                    <b><?php
																						if ($insuranceApproveBy != '')
																						{
																							echo Admins::model()->getFullNameById($insuranceApproveBy);
																						}
																						else
																						{
																							echo "N/A";
																						}
																						?></b>
                                                                                </p>
                                                                                <p><span class="color-gray">Uploaded at</span>
                                                                                    <br>
                                                                                    <b><?php
																						if ($insuranceCreatedAt != '')
																						{
																							echo date('d/m/Y h:i A', strtotime($insuranceCreatedAt));
																						}
																						else
																						{
																							echo "N/A";
																						}
																						?></b>
                                                                                </p>
																				<?php
																				if ($insuranceRemarks != '')
																				{
																					?>
																					<p><span class="color-gray">Remarks</span>
																						<br>
																						<span id="insurance33" style="<?= $insReloadStyle; ?>;float:left;"><i><?= $insuranceRemarks; ?></i></span>
																					</p>
																				<?php } ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-md-4 widget-tab-box4">
                                                                <div class="panel">
                                                                    <div class="panel-body p15 pt0">
                                                                        <div class="row">
                                                                            <div class="col-xs-12 bg-blue">
                                                                                <h3 class="mt10 mb0">PUC Certificate <a href="#" class="pull-right"><span id="pollution" class="<?= $puc['class']; ?>" style="<?= $puc['style']; ?>;float:left;"><?= $puc['level']; ?></span></a></h3>
                                                                                <!--<span id="pollution1" style="<?= $pucApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $pollutionId; ?>', '1')" style="cursor:pointer;"></span>-->
                                                                    <!--<span id="pollution2" style="<?= $pucRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $pollutionId; ?>', '2')" style="cursor:pointer;"></span>-->
<!--                                                                                <p class="font-11 mb5"><span class="color-gray">Uploaded on</span> <b><?php
//                                                                                        if ($model->vhc_reg_exp_date != NULL) {
//                                                                                            echo date('d/m/Y h:i A', strtotime($pollutionCreatedAt));
//                                                                                        } else {
//                                                                                            echo "N/A";
//                                                                                        }
																				?></b></p>-->

                                                                                <div class="row">
                                                                                    <div class="col-xs-6 text-center p5">
																						<a target="_blank"  href="<?= $pollutionDoc; ?>"><div class="image-box text-center"><img src="<?php echo ($pollutionDoc != '') ? $pollutionDoc : '/images/no-image.png'; ?>" alt=""></div></a>
																					<!--<span class="font-10"><?php echo ($pollutionDoc != '') ? CHtml::link('PUC Certificate', $pollutionDoc, array('target' => '_blank')) : ''; ?></span>-->
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                            <div class="col-xs-12 pt10">
                                                                                <p><span class="color-gray">PUC expiry</span>
                                                                                    <br>
                                                                                    <b><?php
																						if ($model->vhc_pollution_exp_date != NULL)
																						{
																							echo date('d/m/Y h:i A', strtotime($model->vhc_pollution_exp_date));
																						}
																						else
																						{
																							echo "N/A";
																						}
																						?></b>
                                                                                </p>
                                                                                <p><span class="color-gray">Approved by</span>
                                                                                    <br>
                                                                                    <b><?php
																						if ($pollutionApproveBy != '')
																						{
																							echo Admins::model()->getFullNameById($pollutionApproveBy);
																						}
																						else
																						{
																							echo "N/A";
																						}
																						?></b>
                                                                                </p>
                                                                                <p><span class="color-gray">Uploaded at</span>
                                                                                    <br>
                                                                                    <b><?php
																						if ($pollutionCreatedAt != '')
																						{
																							echo date('d/m/Y h:i A', strtotime($pollutionCreatedAt));
																						}
																						else
																						{
																							echo "N/A";
																						}
																						?></b>
                                                                                </p>
																				<?php
																				if ($pollutionRemarks != '')
																				{
																					?>
																					<p><span class="color-gray">Remarks</span>
																						<br>
																						<span id="pollution33" style="<?= $pucReloadStyle; ?>;float:left;"><i><?= $pollutionRemarks; ?></i></span>
																					</p>
																				<?php } ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>         



                                                            <div class="col-xs-12 col-md-4 widget-tab-box4">
                                                                <div class="panel">
                                                                    <div class="panel-body p15 pt0">
                                                                        <div class="row">
                                                                            <div class="col-xs-12 bg-blue">
                                                                                <h3 class="mt10 mb0">Commercial Certificate <a href="#" class="pull-right"><span id="commercialPermit" class="<?= $permit['class']; ?>" style="<?= $permit['style']; ?>;float:left;"><?= $permit['level']; ?></span></a></h3>
                                                                                <!--<span id="commercialPermit1" style="<?= $permitApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $commercialPermitId; ?>', '1')" style="cursor:pointer;"></span>-->
                                                                    <!--<span id="commercialPermit2" style="<?= $permitRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $commercialPermitId; ?>', '2')" style="cursor:pointer;"></span>-->
<!--                                                                                <p class="font-11 mb5"><span class="color-gray">Uploaded on</span> <b><?php
//                                                                                        if ($model->vhc_reg_exp_date != NULL) {
//                                                                                            echo date('d/m/Y h:i A', strtotime($commercialCreatedAt));
//                                                                                        } else {
//                                                                                            echo "N/A";
//                                                                                        }
																				?></b></p>-->


                                                                                <div class="row">
                                                                                    <div class="col-xs-6 text-center p5">
																						<a target="_blank"  href="<?= $commercialPermitDoc; ?>"><div class="image-box text-center"><img src="<?php echo ($commercialPermitDoc != '') ? $commercialPermitDoc : '/images/no-image.png'; ?>" alt=""></div></a>
																					<!--<span class="font-10"><?php echo ($commercialPermitDoc != '') ? CHtml::link('Commercial permits', $commercialPermitDoc, array('target' => '_blank')) : ''; ?></span>-->
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                            <div class="col-xs-12 pt10">
                                                                                <p><span class="color-gray">Commercial permits expiry</span>
                                                                                    <br>
                                                                                    <b><?php
																						if ($model->vhc_commercial_exp_date != NULL)
																						{
																							echo date('d/m/Y h:i A', strtotime($model->vhc_commercial_exp_date));
																						}
																						else
																						{
																							echo "N/A";
																						}
																						?></b>
                                                                                </p>
                                                                                <p><span class="color-gray">Approved by</span>
                                                                                    <br>
                                                                                    <b><?php
																						if ($commercialAppovedBy != '')
																						{
																							echo Admins::model()->getFullNameById($commercialAppovedBy);
																						}
																						else
																						{
																							echo "N/A";
																						}
																						?></b>
                                                                                </p>
                                                                                <p><span class="color-gray">Uploaded at</span>
                                                                                    <br>
                                                                                    <b><?php
																						if ($commercialApproveAt != '')
																						{
																							echo date('d/m/Y h:i A', strtotime($commercialApproveAt));
																						}
																						else
																						{
																							echo "N/A";
																						}
																						?></b>
                                                                                </p>
																				<?php
																				if ($commercialPermitRemarks != '')
																				{
																					?>
																					<p><span class="color-gray">Remarks</span>
																						<br>
																						<span id="commercialPermit33" style="<?= $permitReloadStyle; ?>;float:left;">&nbsp;<i><?= $commercialPermitRemarks; ?></i></span>
																					</p>
																				<?php } ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-md-4 widget-tab-box4">
                                                                <div class="panel">
                                                                    <div class="panel-body p15 pt0">
                                                                        <div class="row">
                                                                            <div class="col-xs-12 bg-blue">
                                                                                <h3 class="mt10 mb0">Fitness Certificate <a href="#" class="pull-right"><span id="fitnessCertificate" class="<?= $fit['class']; ?>" style="<?= $fit['style']; ?>;float:left;"><?= $fit['level']; ?></span></a></h3>
                                                                                <!--<span id="fitnessCertificate1" style="<?= $fitApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $fitnessCertificateId; ?>', '1')" style="cursor:pointer;"></span>-->
                                                                    <!--<span id="fitnessCertificate2" style="<?= $fitRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $fitnessCertificateId; ?>', '2')" style="cursor:pointer;"></span>-->
<!--                                                                                <p class="font-11 mb5"><span class="color-gray">Uploaded on</span> <b><?php
//                                                                                        if ($model->vhc_reg_exp_date != NULL) {
//                                                                                            echo date('d/m/Y h:i A', strtotime($fitnessCreatedAt));
//                                                                                        } else {
//                                                                                            echo "N/A";
//                                                                                        }
																				?></b></p>-->
																				<?php
//                                                                                    $s3FrontLicArr = json_decode($frontLicenseS3Data, true);
//
//                                                                                    if (sizeof($s3FrontLicArr) > 0) {
//                                                                                        $spaceFile = \Stub\common\SpaceFile::populate($frontLicenseS3Data);
//                                                                                        $frontLicenseDoc = $spaceFile->getURL();
//                                                                                    }
																				?>
                                                                                <div class="row">
                                                                                    <div class="col-xs-6 text-center p5">
																						<a target="_blank"  href="<?= $fitnessCertificateDoc; ?>"><div class="image-box text-center"><img src="<?php echo ($fitnessCertificateDoc != '') ? $fitnessCertificateDoc : '/images/no-image.png'; ?>" alt=""></div></a>
																					<!--<span class="font-10"><?php echo ($fitnessCertificateDoc != '') ? CHtml::link('Front number plate', $fitnessCertificateDoc, array('target' => '_blank')) : ''; ?></span>-->
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-xs-12 pt10">
                                                                                <p><span class="color-gray">Fitness Certificate expiry</span>
                                                                                    <br>
                                                                                    <b><?php
																						if ($model->vhc_fitness_cert_end_date != NULL)
																						{
																							echo date('d/m/Y h:i A', strtotime($model->vhc_fitness_cert_end_date));
																						}
																						else
																						{
																							echo "N/A";
																						}
																						?></b>
                                                                                </p>
                                                                                <p><span class="color-gray">Approved by</span>
                                                                                    <br>
                                                                                    <b><?php
																						if ($fitnessApproveBy != '')
																						{
																							echo Admins::model()->getFullNameById($fitnessApproveBy);
																						}
																						else
																						{
																							echo "N/A";
																						}
																						?></b>
                                                                                </p>
                                                                                <p><span class="color-gray">Uploaded at</span>
                                                                                    <br>
                                                                                    <b><?php
																						if ($fitnessCreatedAt != '')
																						{
																							echo date('d/m/Y h:i A', strtotime($fitnessCreatedAt));
																						}
																						else
																						{
																							echo "N/A";
																						}
																						?></b>
                                                                                </p>
																				<?php
																				if ($fitnessRemarks != '')
																				{
																					?>
																					<p><span class="color-gray">Remarks</span>
																						<br>
																						<span id="fitnessCertificate33" style="<?= $fitReloadStyle; ?>;float:left;"><i><?= $fitnessRemarks; ?></i></span>
																					</p>
																				<?php } ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-md-4 widget-tab-box4">
                                                                <div class="panel">
                                                                    <div class="panel-body p15 pt0">
                                                                        <div class="row">
                                                                            <div class="col-xs-12 bg-blue">
                                                                                <h3 class="mt10 mb10">Tax Certificate 
                                                                                    <!--<a href="#" class="pull-right btn-6">Approved</a>-->
                                                                                </h3>
<!--                                                                                <p class="font-11 mb5"><span class="color-gray">Uploaded on</span> <b><?= $insuranceCreatedAt; ?></b></p>-->
                                                                            </div>
                                                                            <div class="col-xs-12 pt10">
                                                                                <p><span class="color-gray">Tax Certificate expiry</span>
                                                                                    <br>
                                                                                    <b><?php
																						if (date($model->vhc_tax_exp_date) != NULL)
																						{
																							echo date('d/m/Y h:i A', strtotime($model->vhc_tax_exp_date));
																						}
																						else
																						{
																							echo "N/A";
																						}
																						?></b>
                                                                                </p>
                                                                                <p><span class="color-gray">Approved by</span>
                                                                                    <br>
                                                                                    <b>N/A</b>

                                                                                </p>
                                                                                <p><span class="color-gray">Uploaded at</span>
                                                                                    <br>
                                                                                    <b>N/A</b>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="activityLogs">
                                <div class="panel">
                                    <div class="panel-heading p0 pt5"><?= $model->vhcType->vht_make; ?> <?= $model->vhcType->vht_model; ?> - <?php echo $data[label]; ?> (<?= $model->vhc_number; ?>)</div>
                                    <div class="panel-body p0 pt20">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="row">
                                                            <div class="col-xs-12 widget-tab-box3 ">
																<div class="row">
																	<!--                                                                        <div class="col-xs-6"><h1 class="mb5">Activity Log</h1></div>-->

																	<div class="col-xs-12 table-responsive table-style pt0 pb0">
																		<?php
																		Yii::app()->runController('admin/vehicle/showlog/vhcId/' . $model->vhc_id . '/view/1');
																		?>
																	</div>
																</div>
                                                            </div> 
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="bookingHistory">
                                <div class="panel">
                                    <div class="panel-heading p0 pt5"><?= $model->vhcType->vht_make; ?> <?= $model->vhcType->vht_model; ?> - <?php echo $data[label]; ?> (<?= $model->vhc_number; ?>)</div>
                                    <div class="panel-body p0 pt20"  >
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="row mb20">
                                                    <div class="col-xs-12 widget-tab-box3">
                                                        <div class="widget-tab-box2 p0">
                                                            <div class="row">
                                                                <!--<div class="col-xs-6"><h1 class="mb5">Booking History</h1></div>-->
                                                                <div class="col-xs-12 table-responsive table-style">
                                                                    <!--<div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">-->
                                                                    <div class="table-responsive panel panel-primary mb0 compact" id="driverlog-grid">
                                                                        <div class="panel-heading">
                                                                            <div class="row m0">
                                                                                <div class="row" style=" padding-left: 20px;">Booking History:</div>
                                                                                <div class="col-xs-12 col-sm-6 pr0"></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <table class="table table-striped table-bordered mb0 table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="col-xs-2" id="driverlog-grid_c0">Booking ID</b</th>
                                                                                        <th class="col-xs-2" id="driverlog-grid_c1">Booking Type</th>
                                                                                        <th class="col-xs-2" id="driverlog-grid_c2">From</th>
                                                                                        <th class="col-xs-2" id="driverlog-grid_c3">To</th>
                                                                                        <th class="col-xs-2" id="driverlog-grid_c4">Pickup Date</th>
                                                                                        <th class="col-xs-2" id="driverlog-grid_c4">Car Rating</th>
                                                                                        <th class="col-xs-4" id="driverlog-grid_c4">Car Comments</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
																					<?php
																					if (count($pastData) > 0)
																					{
																						foreach ($pastData as $pdata)
																						{
																							?>
																							<tr class="odd">

																								<td><?= CHtml::link($pdata["bkg_booking_id"], Yii::app()->createUrl("admin/booking/view", ["id" => $pdata['bkg_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']) ?></td>
																								<td><?= $pdata['booking_type']; ?></td>
																								<td><?= $pdata['from_city']; ?></td>
																								<td><?= $pdata['to_city']; ?></td>
																								<td><?= date("d/M/Y h:i A", strtotime($pdata['bkg_pickup_date'])); ?></td>
																								<td><?= $pdata['rtg_customer_car']; ?></td>
																								<td><?= $pdata['rtg_car_cmt']; ?></td>
																							</tr>
																							<?php
																						}
																					}
																					else
																					{
																						?>
																					<td colspan="7" class="empty"><span class="empty">No results found.</span></td>
																					<?php
																				}
																				?>   
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div role="tabpanel" class="tab-pane" id="lou"> 
                                <div class="panel">
                                    <div class="panel-heading p0 pt5"><?= $model->vhcType->vht_make; ?> <?= $model->vhcType->vht_model; ?> - <?php echo $data[label]; ?> (<?= $model->vhc_number; ?>)</div>

                                    <div class="panel-body p0 pt20"  >
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="row mb20">
                                                    <div class="col-xs-12 widget-tab-box3">
                                                        <div class="widget-tab-box2 p0">
                                                            <div class="row">
                                                                <!--<div class="col-xs-6"><h1 class="mb5">LOU Data</h1></div>-->
                                                                <div class="col-xs-12 table-responsive table-style">
                                                                    <!--<div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">-->
                                                                    <div class="table-responsive panel panel-primary mb0 compact" id="driverlog-grid">
                                                                        <div class="panel-heading">
                                                                            <div class="row m0">
                                                                                <div class="row" style=" padding-left: 20px;"><b>LOU Data:</b></div>
                                                                                <div class="col-xs-12 col-sm-6 pr0"></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <table class="table table-striped table-bordered mb0 table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <td><b>Vendor's name</b></td>
                                                                                        <td><b>Owner</b></td>
                                                                                        <td><b>Valid date</b></td>
                                                                                        <td><b>Proof</b></td>
                                                                                        <td><b>Approval Status</b></td>
                                                                                        <td><b>Approved by</b></td>
                                                                                        <td><b>LOU req</b></td>
                                                                                        <td><b>Expiry date</b></td>
                                                                                        <td><b>Driver license</b></td>
                                                                                        <td><b>PAN</b></td>
                                                                                    </tr>
																					<?php
																					if (count($louData) > 0)
																					{
																						foreach ($louData as $loudata)
																						{
																							?>
																							<tr class="odd">
																								<td><?= $loudata['vnd_name']; ?></td>
																								<td><?= $loudata['vhc_owner']; ?></td>
																								<td><?= $loudata['vvhc_lou_approve_date']; ?></td>
																								<td><?php
																									if ($loudata['proof_path'] != "")
																									{
																										?><a><img src="\images\icon\doc_img.png" onclick="showpic('<?= $loudata['proof_id']; ?>', 'Proof')"></a><?php
																									}
																									else
																									{
																										echo "N/A";
																									}
																									?></td>
																								<td><?= $loudata['lou_status']; ?></td>
																								<td><?= $loudata['vvhc_lou_approve_name']; ?></td>
																								<td><?= ($loudata['vvhc_is_lou_required'] == 0) ? "No" : "Yes"; ?></td>
																								<td><?= $loudata['vvhc_lou_expire_date']; ?></td>
																								<td><?php
																									if ($loudata['license_path'] != "")
																									{
																										?><a><img src="\images\icon\doc_img.png" onclick="showpic('<?= $loudata['license_id']; ?>', 'License')"></a><?php
																									}
																									else
																									{
																										echo "N/A";
																									}
																									?></td>
																								<td><?php
																									if ($loudata['pan_path'] != "")
																									{
																										?><a><img src="\images\icon\doc_img.png" onclick="showpic('<?= $loudata['pan_id']; ?>', 'Pan')"></a><?php
																									}
																									else
																									{
																										echo "N/A";
																									}
																									?></td>
																							</tr>
																							<?php
																						}
																					}
																					else
																					{
																						?>
																					<td colspan="10" class="empty"><span class="empty">No results found.</span></td>
																					<?php
																				}
																				?>   
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            <div role="tabpanel" class="tab-pane" id="odometerHistory">
                                <div class="panel">
                                    <div class="panel-heading p0 pt5"><?= $model->vhcType->vht_make; ?> <?= $model->vhcType->vht_model; ?> - <?php echo $data[label]; ?> (<?= $model->vhc_number; ?>)</div>
                                    <div class="panel-body p0 pt20"  >
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="row mb20">
                                                    <div class="col-xs-12 widget-tab-box3">
                                                        <div class="widget-tab-box2 p0">
                                                            <div class="row">
                                                                <!--<div class="col-xs-6"><h1 class="mb5">Booking History</h1></div>-->
                                                                <div class="col-xs-12 table-responsive table-style">
                                                                    <!--<div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">-->
                                                                    <div class="table-responsive panel panel-primary mb0 compact" id="driverlog-grid">
                                                                        <div class="panel-heading">
                                                                            <div class="row m0">
                                                                                <div class="row" style=" padding-left: 20px;">Odometer History:</div>
                                                                                <div class="col-xs-12 col-sm-6 pr0"></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <table class="table table-striped table-bordered mb0 table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="col-xs-2" id="driverlog-grid_c0">Date & Time</b</th>
                                                                                        <th class="col-xs-2" id="driverlog-grid_c1">Odometer Start Reading</th>
                                                                                        <th class="col-xs-2" id="driverlog-grid_c1">Odometer End Reading</th>
                                                                                        <th class="col-xs-2" id="driverlog-grid_c3">Booking Id</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
																					<?php
																					if (count($odoData) > 0)
																					{
																						foreach ($odoData as $odata)
																						{
																							?>
																							<tr class="odd">
																								<td><?= $odata['bkg_pickup_date']; ?></td>
																								<td><a onclick="showodopic('<?= $odata['bkg_id']; ?>', '101')"><?= $odata['bkg_start_odometer']; ?></a></td>
																								<td><a onclick="showodopic('<?= $odata['bkg_id']; ?>', '104')"><?= $odata['bkg_end_odometer']; ?></a></td>
																								<td><?= $odata['bkg_booking_id']; ?></td>
																							</tr>
																							<?php
																						}
																					}
																					else
																					{
																						?>
																					<td colspan="7" class="empty"><span class="empty">No results found.</span></td>
																					<?php
																				}
																				?>   
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane" id="reviewHistory">
                                <div class="panel">
                                    <div class="panel-heading p0 pt5"><?= $model->vhcType->vht_make; ?> <?= $model->vhcType->vht_model; ?> - <?php echo $data[label]; ?> (<?= $model->vhc_number; ?>)</div>
                                    <div class="panel-body p0 pt20"  >
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="row mb20">
                                                    <div class="col-xs-12 widget-tab-box3">
                                                        <div class="widget-tab-box2 p0">
                                                            <div class="row">
                                                                <!--<div class="col-xs-6"><h1 class="mb5">Booking History</h1></div>-->
                                                                <div class="col-xs-12 table-responsive table-style">
                                                                    <!--<div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">-->
                                                                    <div class="table-responsive panel panel-primary mb0 compact" id="driverlog-grid">
                                                                        <div class="panel-heading">
                                                                            <div class="row m0">
                                                                                <div class="row" style=" padding-left: 20px;">Rating History:</div>
                                                                                <div class="col-xs-12 col-sm-6 pr0"></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <table class="table table-striped table-bordered mb0 table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="col-xs-2" id="driverlog-grid_c0">Date & Time</b</th>
                                                                                        <th class="col-xs-2" id="driverlog-grid_c1">Booking Id</th>
                                                                                        <th class="col-xs-2" id="driverlog-grid_c2">Rating(1-5)</th>
                                                                                        <th class="col-xs-2" id="driverlog-grid_c3">Review Text</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
																					<?php
																					if (count($revData) > 0)
																					{
																						foreach ($revData as $rdata)
																						{
																							?>
																							<tr class="odd">
																								<td><?= $rdata['rtg_customer_date']; ?></td>
																								<td><?= $rdata['bkg_booking_id']; ?></td>
																								<td><?= $rdata['rtg_customer_car']; ?></td>
																								<td><?= $rdata['rtg_car_cmt']; ?></td>
																							</tr>
																							<?php
																						}
																					}
																					else
																					{
																						?>
																					<td colspan="7" class="empty"><span class="empty">No results found.</span></td>
																					<?php
																				}
																				?>   
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
								
                             <div role="tabpanel" class="tab-pane" id="documentLog">
                                <div class="panel">
                                    <div class="panel-heading p0 pt5"><?= $model->vhcType->vht_make; ?> <?= $model->vhcType->vht_model; ?> - <?php echo $data[label]; ?> (<?= $model->vhc_number; ?>)</div>
                                    <div class="panel-body p0 pt20">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="row">
                                                            <div class="col-xs-12 widget-tab-box3 ">
																<div class="row">
																	<!--                                                                        <div class="col-xs-6"><h1 class="mb5">Activity Log</h1></div>-->

																	<div class="col-xs-12 table-responsive table-style pt0 pb0">
																		<?php
																		Yii::app()->runController('admin/vehicle/showDocumentLog/vhcId/' . $model->vhc_id . '/view/1');
																		?>
																	</div>
																</div>
                                                            </div> 
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	function showpic(id, doctype) {
		$.ajax({
			"type": "GET",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/document/showdocument')) ?>",
			"data": {"docid": id},

			success: function (data) {
				box = bootbox.dialog({
					message: data,
					className: "bootbox-xs",
					title: "<span class='text-center'>" + doctype + "</span>",
					size: "large",
					onEscape: function () {
						box.modal('hide');
					}
				}).on('shown.bs.modal', function () {
					box.removeAttr("tabindex");
				});
			}
		});
	}
</script>
<script>
	function showodopic(id, odotype) {
		$.ajax({
			"type": "GET",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vehicle/OdometerView')) ?>",
			"data": {"id": id, "type": odotype},

			success: function (data) {
				box = bootbox.dialog({
					message: data,
					className: "bootbox-xs",
					title: "<span class='text-center'>Odometer Reading</span>",
					size: "large",
					onEscape: function () {
						box.modal('hide');
					}
				}).on('shown.bs.modal', function () {
					box.removeAttr("tabindex");
				});
			}
		});
	}

	function deleteVehicle(obj)
	{
		var con = confirm("Are you sure you want to delete this vehicle?");
		if (con) {
			try
			{
				$href = $(obj).attr("href");
				$.ajax({
					url: $href,
					dataType: "json",
					tilte: "delete vehicle",
					success: function (result) {
						if (result.success) {
							window.location.replace("/admpnl/vehicle/list");
						} else {
							alert('Sorry error occured');
						}
					},
					error: function (xhr, status, error) {
						alert('Sorry error occured');
					}
				});
			} catch (e)
			{
				alert(e);
			}
		}
		return false;
	}

	function markBad(obj)
	{
		try
		{
			$href = $(obj).attr("href");
			jQuery.ajax({type: "GET", url: $href, success: function (data)
				{
					bootbox.dialog({
						message: data,
						size: "large",
						className: "bootbox-sm",
						title: "Mark Bad Vehicle",
						success: function (result) {
							if (result.success) {
								//alert('Done Successfully');

							} else {
								alert('Sorry error occured');
							}
						},
						error: function (xhr, status, error) {
							alert('Sorry error occured');
						}
					});
				}});
		} catch (e)
		{
			alert(e);
		}
		return false;
	}

	function freezeVehicle(obj)
	{
		var objtitle = $(obj).data('title');
		var con = confirm("Are you sure you want to " + objtitle + " this vehicle?");
		if (con) {
			try
			{
				$href = $(obj).attr("href");
				jQuery.ajax({type: "GET", url: $href, success: function (data)
					{
						bootbox.dialog({
							message: data,

							className: "bootbox-sm",
							title: objtitle,
							success: function (result) {
								debugger;
								if (result.success) {
									alert('Done Successfully');

								} else {
									alert('Sorry error occured');
								}
							},
							error: function (xhr, status, error) {
								alert('Sorry error occured');
							}
						});
					}});
			} catch (e)
			{
				alert(e);
			}
		}
		return false;
	}
	function staticalData(obj)
	{
		var con = confirm("Are you sure you want to update statistical data?");
		if (con) {
			try
			{
				$href = $(obj).attr("href");
				$.ajax({
					url: $href,
					dataType: "json",
					tilte: "Statistical vehicle",
					success: function (result) {
						if (result.success) {
							bootbox.alert(result.message, function () {
								refreshVehicleView();
							});
						} else {
							alert('Sorry error occured');
						}
					},
					error: function (xhr, status, error) {
						alert('Sorry error occured');
					}
				});
			} catch (e)
			{
				alert(e);
			}
		}
		return false;
	}
	function refreshVehicleView() {
		location.reload();
	}
	function addremark(obj) {
		try
		{
			$href = $(obj).attr("href");
			jQuery.ajax({type: "GET", url: $href, success: function (data)
				{
					bootbox.dialog({
						message: data,
						className: "bootbox-sm",
						title: "Add Remark",
						success: function (result) {
							if (result.success) {
								
							} else {
								alert('Sorry error occured');
							}
						},
						error: function (xhr, status, error) {
							alert('Sorry error occured');
						}
					});
				}});
		} catch (e)
		{
			alert(e);
		}
		return false;
	}
</script>





