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
					$insuranceId				 = $vchDocs['vhd_id'];
					$insuranceDoc				 = $vchDocs['vhd_file'];
					$insuranceStatus			 = $vchDocs['vhd_status'];
					$insuranceRemarks			 = $vchDocs['vhd_remarks'];
					break;
				case 2:
					$frontLicenseId				 = $vchDocs['vhd_id'];
					$frontLicenseDoc			 = $vchDocs['vhd_file'];
					$frontLicenseStatus			 = $vchDocs['vhd_status'];
					$frontLicenseRemarks		 = $vchDocs['vhd_remarks'];
					break;
				case 3:
					$rearLicenseId				 = $vchDocs['vhd_id'];
					$rearLicenseDoc				 = $vchDocs['vhd_file'];
					$rearLicenseStatus			 = $vchDocs['vhd_status'];
					$rearLicenseRemarks			 = $vchDocs['vhd_remarks'];
					break;
				case 4:
					$pollutionId				 = $vchDocs['vhd_id'];
					$pollutionDoc				 = $vchDocs['vhd_file'];
					$pollutionStatus			 = $vchDocs['vhd_status'];
					$pollutionRemarks			 = $vchDocs['vhd_remarks'];
					break;
				case 5:
					$registrationId				 = $vchDocs['vhd_id'];
					$registrationDoc			 = $vchDocs['vhd_file'];
					$registrationStatus			 = $vchDocs['vhd_status'];
					$registrationRemarks		 = $vchDocs['vhd_remarks'];
					break;
				case 6:
					$commercialPermitId			 = $vchDocs['vhd_id'];
					$commercialPermitDoc		 = $vchDocs['vhd_file'];
					$commercialPermitStatus		 = $vchDocs['vhd_status'];
					$commercialPermitRemarks	 = $vchDocs['vhd_remarks'];
					break;
				case 7:
					$fitnessCertificateId		 = $vchDocs['vhd_id'];
					$fitnessCertificateDoc		 = $vchDocs['vhd_file'];
					$fitnessCertificateStatus	 = $vchDocs['vhd_status'];
					$fitnessRemarks				 = $vchDocs['vhd_remarks'];
					break;
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
<div class="row">
    <div class="panel panel-default">
        <div class="panel-body">
            <div style="border-collapse: collapse; border: 1px; " >
                <div class="row bordered" >
                    <div class="col-xs-12 text-center"> 
                        <b>Car Record Number:</b> <?= dechex($model->vhc_id); ?>, 
						<b>Car Code:</b> <?= $model->vhc_code; ?>, 
                        <b>Verified commercial:</b> <?= ($model->vhc_is_commercial > 0) ? 'Yes' : 'No'; ?><br>
						<b>Is CNG:</b> <?= ($model->vhc_has_cng > 0) ? 'Yes' : 'No'; ?>, 
						<b>Has Rooftop Carrier:</b> <?= ($model->vhc_has_rooftop_carrier > 0) ? 'Yes' : 'No'; ?><br>
                        <b>Boost Verification:</b> <?= ($data['vhs_boost_verify'] == 1) ? 'Yes' : 'No'; ?><br>
                        <b>Is Partitioned:</b> <?= ($data['vhs_is_partition'] == 1) ? 'Yes' : 'No'; ?><br>
                        <b>Approval status:</b> <?= $data['approve_status']; ?>, 
						<b>UBER Approved:</b> <?= ($model->vhc_is_uber_approved > 0) ? 'Yes' : 'No'; ?>, 
                        <b>Approved By:</b> <?= $data['approve_by_name']; ?><br>
                    </div>
                </div>
                <div class="row bordered" >
                    <div class="col-xs-6 text-left" style=" padding-left: 50px;"> 
                        <b>Contact Details</b>
                        <br>Vendor(s):  <?= $data['vnd_name']; ?>
                        <br>Car Category :
						<?php echo $data[label]; ?>
						<?php //echo VehicleTypes::model()->getCarByCarType($model->vhcType->vht_car_type); ?>
                        <br>Car Model : <?= $model->vhcType->vht_make; ?> - <?= $model->vhcType->vht_model; ?>
                        <br>Manufacture Year : <?= $model->vhc_year; ?>
                        <br>Color : <?= $model->vhc_color; ?>
                        <br><br><b>Car Model Details</b>
                        <br>Car Make:  <?= $model->vhcType->vht_make; ?>
                        <br>Car Model : <?= $model->vhcType->vht_model; ?> 
                        <br>Seating Capacity : <?= $model->vhcType->vht_capacity; ?>
                        <br>Luggage Capacity : <?= $model->vhcType->vht_bag_capacity; ?>
                        <br>Mileage/Average : <?= $model->vhcType->vht_average_mileage; ?> 
                    </div>
                    <div class="col-xs-6 text-left"> 
                        <b>Trip Performance History</b>
                        <br>Date Of Joining : <?= date('d/m/Y h:i A', strtotime($model->vhc_created_at)); ?>
                        <br>#Number Of Trips : <?= $model->vhc_total_trips; ?>
                        <br>Current Rating : <?= $model->vhc_overall_rating; ?>
                        <br>Last Trip Date : <?php
						if ($data['last_pickup_date'] != NULL)
						{
							echo date('d/m/Y h:i A', strtotime($data['last_pickup_date']));
						}
						?> 
                        <br>Last Trip Rating : <?= $data['rtg_customer_car']; ?>
						<br>Approved for trip types : <?php
						if ($data['vhc_trip_type'] <> NULL)
						{
							echo ($data['vhc_trip_type'] != 0) ? Vehicles::getType($data['vhc_trip_type']) : '';
						}
						?>  
                    </div>
                </div>
                <div class="row bordered" >
                    <div class="col-xs-12 text-left" style=" padding-left: 50px;"> 
                        <b>File Information : </b><br><br>
						<div class="row"> 
                            <div class="col-xs-2 text-left">Front number plate : </div>
                            <div class="col-xs-2 text-left">&nbsp;</div>
                            <div class="col-xs-3 text-left"><?php echo ($frontLicenseDoc != '') ? CHtml::link('Front number plate Link', $frontLicenseDoc, array('target' => '_blank')) : ''; ?></div>
                            <div class="col-xs-5 text-left">
                                <span id="frontLicense1" style="<?= $frontLicApproveStyle; ?>;float:left;"><img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $frontLicenseId; ?>', '1')" style="cursor:pointer;"></span>
                                <span id="frontLicense2" style="<?= $frontLicRejectStyle; ?>;float:left;"><img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $frontLicenseId; ?>', '2')" style="cursor:pointer;"></span>
                                <span id="frontLicense" class="<?= $frtLic['class']; ?>" style="<?= $frtLic['style']; ?>;float:left;"><?= $frtLic['level']; ?></span>                                
                                <span id="frontLicense33" style="<?= $frontLicReloadStyle; ?>;float:left;"><i><?= $frontLicenseRemarks; ?></i></span>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-xs-2 text-left">Back number plate : </div>
                            <div class="col-xs-2 text-left">&nbsp;</div>
                            <div class="col-xs-3 text-left"><?php echo ($rearLicenseDoc != '') ? CHtml::link('Back number plate Link', $rearLicenseDoc, array('target' => '_blank')) : ''; ?></div>
                            <div class="col-xs-5 text-left">
                                <span id="rearLicense1" style="<?= $reartLicApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $rearLicenseId; ?>', '1')" style="cursor:pointer;"></span>
                                <span id="rearLicense2" style="<?= $reartLicRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $rearLicenseId; ?>', '2')" style="cursor:pointer;"></span>
                                <span id="rearLicense" class="<?= $rearLic['class']; ?>" style="<?= $rearLic['style']; ?>;float:left;"><?= $rearLic['level']; ?></span>
                                <span id="rearLicense33" style="<?= $reartLicReloadStyle; ?>;float:left;"><i><?= $rearLicenseRemarks; ?></i></span>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-xs-2 text-left">Insurance expiry date : </div>
                            <div class="col-xs-2 text-left"><?php
						if ($model->vhc_insurance_exp_date != NULL)
						{
							echo date('d/m/Y h:i A', strtotime($model->vhc_insurance_exp_date));
						}
						?> </div>
                            <div class="col-xs-3 text-left"><?php echo ($insuranceDoc != '') ? CHtml::link('Insurance Certificate Link', $insuranceDoc, array('target' => '_blank')) : ''; ?></div>
                            <div class="col-xs-5 text-left">
                                <span id="insurance1" style="<?= $insApproveStyle; ?>;float:left;"><img id="insApprove" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $insuranceId; ?>', '1')" style="cursor:pointer;"></span>
                                <span id="insurance2" style="<?= $insRejectStyle; ?>;float:left;"><img id="insReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $insuranceId; ?>', '2')" style="cursor:pointer;"></span>
                                <span id="insurance" class="<?= $ins['class']; ?>" style="<?= $ins['style']; ?>;float:left;"><?= $ins['level']; ?></span>
                                <span id="insurance33" style="<?= $insReloadStyle; ?>;float:left;"><i><?= $insuranceRemarks; ?></i></span>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-xs-2 text-left">PUC expiry date: </div>
                            <div class="col-xs-2 text-left"><?php
								if ($model->vhc_pollution_exp_date != NULL)
								{
									echo date('d/m/Y h:i A', strtotime($model->vhc_pollution_exp_date));
								}
						?></div>
                            <div class="col-xs-3 text-left"><?php echo ($pollutionDoc != '') ? CHtml::link('PUC Link', $pollutionDoc, array('target' => '_blank')) : ''; ?></div>
                            <div class="col-xs-5 text-left">
                                <span id="pollution1" style="<?= $pucApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $pollutionId; ?>', '1')" style="cursor:pointer;"></span>
                                <span id="pollution2" style="<?= $pucRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $pollutionId; ?>', '2')" style="cursor:pointer;"></span>
                                <span id="pollution" class="<?= $puc['class']; ?>" style="<?= $puc['style']; ?>;float:left;"><?= $puc['level']; ?></span>
                                <span id="pollution33" style="<?= $pucReloadStyle; ?>;float:left;"><i><?= $pollutionRemarks; ?></i></span>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-xs-2 text-left">Registration end date: </div>
                            <div class="col-xs-2 text-left"><?php
								if ($model->vhc_reg_exp_date != NULL)
								{
									echo date('d/m/Y h:i A', strtotime($model->vhc_reg_exp_date));
								}
						?></div>
                            <div class="col-xs-3 text-left"><?php echo ($registrationDoc != '') ? CHtml::link('Reg Certificate Link', $registrationDoc, array('target' => '_blank')) : ''; ?></div>
                            <div class="col-xs-5" text-left>
                                <span id="registration1" style="<?= $regApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $registrationId; ?>', '1')" style="cursor:pointer;"></span>
                                <span id="registration2" style="<?= $regRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $registrationId; ?>', '2')" style="cursor:pointer;"></span>
                                <span id="registration" class="<?= $reg['class']; ?>" style="<?= $reg['style']; ?>;float:left;"><?= $reg['level']; ?></span>
                                <span id="registration33" style="<?= $regReloadStyle; ?>;float:left;"><i><?= $registrationRemarks; ?></i></span>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-xs-2 text-left">Commercial permits end date: </div>
                            <div class="col-xs-2 text-left"><?php
								if ($model->vhc_commercial_exp_date != NULL)
								{
									echo date('d/m/Y h:i A', strtotime($model->vhc_commercial_exp_date));
								}
						?></div>
                            <div class="col-xs-3 text-left"><?php echo ($commercialPermitDoc != '') ? CHtml::link('Commercial permits Link', $commercialPermitDoc, array('target' => '_blank')) : ''; ?></div>
                            <div class="col-xs-5 text-left">
                                <span id="commercialPermit1" style="<?= $permitApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $commercialPermitId; ?>', '1')" style="cursor:pointer;"></span>
                                <span id="commercialPermit2" style="<?= $permitRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $commercialPermitId; ?>', '2')" style="cursor:pointer;"></span>
                                <span id="commercialPermit" class="<?= $permit['class']; ?>" style="<?= $permit['style']; ?>;float:left;"><?= $permit['level']; ?></span>
                                <span id="commercialPermit33" style="<?= $permitReloadStyle; ?>;float:left;">&nbsp;<i><?= $commercialPermitRemarks; ?></i></span>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-xs-2 text-left">Fitness certificate end date: </div>
                            <div class="col-xs-2 text-left"><?php
								if ($model->vhc_fitness_cert_end_date != NULL)
								{
									echo date('d/m/Y h:i A', strtotime($model->vhc_fitness_cert_end_date));
								}
						?></div>
                            <div class="col-xs-3 text-left"><?php echo ($fitnessCertificateDoc != '') ? CHtml::link('Fitness Certificate Link', $fitnessCertificateDoc, array('target' => '_blank')) : ''; ?></div>
                            <div class="col-xs-5 text-left">
                                <span id="fitnessCertificate" class="<?= $fit['class']; ?>" style="<?= $fit['style']; ?>;float:left;"><?= $fit['level']; ?></span>
                                <span id="fitnessCertificate1" style="<?= $fitApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $fitnessCertificateId; ?>', '1')" style="cursor:pointer;"></span>
                                <span id="fitnessCertificate2" style="<?= $fitRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $fitnessCertificateId; ?>', '2')" style="cursor:pointer;"></span>
                                <span id="fitnessCertificate33" style="<?= $fitReloadStyle; ?>;float:left;"><i><?= $fitnessRemarks; ?></i></span>
                            </div>
                        </div>
						<div class="row"> 
                            <div class="col-xs-2 text-left">Tax Expiry date : </div>
                            <div class="col-xs-2 text-left"><?php
								if (date($model->vhc_tax_exp_date) != NULL)
								{
									echo date('d/m/Y h:i A', strtotime($model->vhc_tax_exp_date));
								}
						?></div>
                            <div class="col-xs-3 text-left"></div>
                            <div class="col-xs-5 text-left"></div>
                        </div>
                        <br> 
                    </div>
                </div>
                <div class="row bordered" >
					<div class="row" style=" padding-left: 20px;"><b>Past Trip Details :</b></div>
					<div class="row col-xs-12">
						<div class="col-xs-1 text-left" style=" padding-left: 10px;"><b>Booking ID</b></div>
						<div class="col-xs-1 text-left" ><b>Booking Type</b></div>
						<div class="col-xs-2 text-left"><b>From</b></div>
						<div class="col-xs-2 text-left"><b>To</b></div>
						<div class="col-xs-2 text-left"><b>Pickup Date</b></div>
						<div class="col-xs-1 text-center"><b>Car Rating</b></div>
						<div class="col-xs-3 text-left"><b>Car Comments</b></div>

					</div>
					<?php
					if (count($pastData) > 0)
					{
						foreach ($pastData as $pdata)
						{
							?>
							<div class="row col-xs-12">
								<div class="col-xs-1 text-left" style="padding-left: 10px;"><?= $pdata['bkg_booking_id']; ?></div>
								<div class="col-xs-1 text-left"><?= $pdata['booking_type']; ?></div>
								<div class="col-xs-2 text-left"><?= $pdata['from_city']; ?></div>
								<div class="col-xs-2 text-left"><?= $pdata['to_city']; ?></div>
								<div class="col-xs-2 text-left"><?= $pdata['bkg_pickup_date']; ?></div>
								<div class="col-xs-1 text-center"><?= $pdata['rtg_customer_car']; ?></div>
								<div class="col-xs-3 text-left"><?= $pdata['rtg_car_cmt']; ?></div>
							</div>
							<?php
						}
					}
					else
					{
						?>
						<div class="row col-xs-12 text-center"><b>No Records Yet Found.</b></div>
	<?php
}
?>    
				</div>
				<div class="col-xs-12 ">
					<div class="col-xs-12 text-center">
						<label class = "control-label h3 ">Car Log</label>
					</div>
<?
Yii::app()->runController('admin/vehicle/showlog/vhcId/' . $model->vhc_id . '/view/1');
?>
				</div>
			</div>    
		</div>
	</div> 
    <script  type="text/javascript">

        function rejectDriverDocs(id, status)
        {
            var href = '<?= Yii::app()->createUrl("admin/vehicle/rejectVehicleDoc"); ?>';
            jQuery.ajax({type: 'GET',
                url: href,
                data: {"vhd_id": id, "vhd_status": status},
                success: function (data)
                {
                    upsellBox = bootbox.dialog({
                        message: data,
                        title: 'Add Remarks for Reject Document',
                        onEscape: function () {
                            // user pressed escape
                        },
                    });

                }
            });
        }

        function updateVehicleDocs(id, status)
        {
            var href = '<?= Yii::app()->createUrl("admin/vehicle/updateVehicleDoc"); ?>';
            $.ajax({
                "url": href,
                "type": "GET",
                "dataType": "html",
                "data": {"vhd_id": id, "vhd_status": status},
                "success": function (data1)
                {
                    var dataSet = data1.split("~");
                    if (dataSet[1] == 1)
                    {
                        var img = dataSet[0] + dataSet[1];
                        $(dataSet[0]).show();
                        $(dataSet[0]).css("display", "block");
                        $(dataSet[0]).removeClass('label-info');
                        $(dataSet[0]).addClass('label label-success');
                        $(dataSet[0]).html("Approved");
                        $(img).hide();
                    }
                    if (dataSet[1] == 2)
                    {
                        $(dataSet[0]).show();
                        $(dataSet[0]).css("display", "block");
                        $(dataSet[0]).removeClass('label-info');
                        $(dataSet[0]).removeClass('label-success');
                        $(dataSet[0]).addClass('label label-danger');
                        $(dataSet[0]).html("Rejected");

                        var rejectImg = dataSet[0] + dataSet[1];
                        var approveImg = dataSet[0] + '1';
                        var reloadImg = dataSet[0] + '3';
                        var reloadRemarks = dataSet[0] + '33';
                        $(dataSet[0]).show();
                        $(rejectImg).hide();
                        $(approveImg).hide();
                        $(reloadImg).show();
                        $(reloadRemarks).hide();
                    } else if (dataSet[1] == 3)
                    {
                        var div = dataSet[0] + 'Div';
                        var img = dataSet[0] + dataSet[1];
                        $(dataSet[0]).hide();
                        $(div).show();
                        $(img).hide();
                    }


                }
            });
            return false;
        }
    </script>




