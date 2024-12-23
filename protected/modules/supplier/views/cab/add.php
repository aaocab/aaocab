<style type="text/css">
    .control-label  {text-align: left!important;}
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0; padding-left: 0;}
    .selectize-input{ width:100%;}
</style>
<?php
/* @var $model Vehicles */
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
//$vtypeIdList = VehicleTypes::model()->getVehicleTypeList1();
$vtypeList	 = VehicleTypes::model()->getParentVehicleTypes(2);
$vTypeData	 = VehicleTypes::model()->getJSON($vtypeList);
//$vTypeIdData = VehicleTypes::model()->getJSON($vtypeIdList);
$color		 = array('Red' => 'Red', 'Grey' => 'Grey', 'White' => 'White');
//$vendorList = array("" => "Select Vendor") + CHtml::listData(Vendors::model()->getAll(array('order' => 'vnd_name')), 'vnd_id', 'vnd_name');

$displayBlock	 = ($isNew) ? 'none' : 'block';
$displayBtn		 = ($isNew) ? 'block' : 'none';
$yearRange		 = [];
$yearRange['']	 = 'Select model year';
$dy				 = date('Y');
for ($i = $dy; $i >= $dy - 20; $i--)
{
	$yearRange[$i] = $i;
}
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];

/*
  $insuranceDoc = VehicleDocs::model()->fetchByDoctype($model->vhc_id, 1);
  $frontLicenseDoc = VehicleDocs::model()->fetchByDoctype($model->vhc_id, 2);
  $rearLicenseDoc = VehicleDocs::model()->fetchByDoctype($model->vhc_id, 3);
  $pollutionDoc = VehicleDocs::model()->fetchByDoctype($model->vhc_id, 4);
  $registrationDoc = VehicleDocs::model()->fetchByDoctype($model->vhc_id, 5);
  $commercialPermitDoc = VehicleDocs::model()->fetchByDoctype($model->vhc_id, 6);
  $fitnessCertificateDoc = VehicleDocs::model()->fetchByDoctype($model->vhc_id, 7);
 */




foreach ($model->vehicleDocs as $vehDoc)
{
	$vehDoc['vhd_status'];

	switch ($vehDoc['vhd_type'])
	{
		case 1:
			$insuranceId				 = $vehDoc['vhd_id'];
			//$insuranceDoc				 = $vehDoc['vhd_file'];
			$insuranceStatus			 = $vehDoc['vhd_status'];
			$insuranceTempStatus		 = $vehDoc['vhd_temp_approved'];
			$insuranceRemarks			 = $vehDoc['vhd_remarks'];
			$insuranceDoc				 = VehicleDocs::getDocPathById($vehDoc['vhd_id']);
			break;
		case 2:
			$frontLicenseId				 = $vehDoc['vhd_id'];
			//$frontLicenseDoc			 = $vehDoc['vhd_file'];
			$frontLicenseStatus			 = $vehDoc['vhd_status'];
			$frontLicenseRemarks		 = $vehDoc['vhd_remarks'];
			$frontLicenseDoc			 = VehicleDocs::getDocPathById($vehDoc['vhd_id']);
			break;
		case 3:
			$rearLicenseId				 = $vehDoc['vhd_id'];
			//$rearLicenseDoc				 = $vehDoc['vhd_file'];
			$rearLicenseStatus			 = $vehDoc['vhd_status'];
			$rearLicenseRemarks			 = $vehDoc['vhd_remarks'];
			$rearLicenseDoc				 = VehicleDocs::getDocPathById($vehDoc['vhd_id']);
			break;
		case 4:
			$pollutionId				 = $vehDoc['vhd_id'];
			//$pollutionDoc				 = $vehDoc['vhd_file'];
			$pollutionStatus			 = $vehDoc['vhd_status'];
			$pollutionRemarks			 = $vehDoc['vhd_remarks'];
			$pollutionDoc				 = VehicleDocs::getDocPathById($vehDoc['vhd_id']);
			break;
		case 5:
			$registrationId				 = $vehDoc['vhd_id'];
			//$registrationDoc			 = $vehDoc['vhd_file'];
			$registrationStatus			 = $vehDoc['vhd_status'];
			$registrationTempStatus		 = $vehDoc['vhd_temp_approved'];
			$registrationRemarks		 = $vehDoc['vhd_remarks'];
			$registrationDoc			 = VehicleDocs::getDocPathById($vehDoc['vhd_id']);
			break;
		case 6:
			$commercialPermitId			 = $vehDoc['vhd_id'];
			//$commercialPermitDoc                     = $vehDoc['vhd_file'];
			$commercialPermitStatus		 = $vehDoc['vhd_status'];
			$commercialPermitRemarks	 = $vehDoc['vhd_remarks'];
			$commercialPermitDoc		 = VehicleDocs::getDocPathById($vehDoc['vhd_id']);
			break;
		case 7:
			$fitnessCertificateId		 = $vehDoc['vhd_id'];
			//$fitnessCertificateDoc		 = $vehDoc['vhd_file'];
			$fitnessCertificateStatus	 = $vehDoc['vhd_status'];
			$fitnessRemarks				 = $vehDoc['vhd_remarks'];
			$fitnessCertificateDoc		 = VehicleDocs::getDocPathById($vehDoc['vhd_id']);
			break;
		case 13:

			$registrationBacId			 = $vehDoc['vhd_id'];
			$registrationBacStatus		 = $vehDoc['vhd_status'];
			$registrationBacRemarks		 = $vehDoc['vhd_remarks'];
			$registrationBacTempStatus	 = $vehDoc['vhd_temp_approved'];
			$registrationBackDoc		 = VehicleDocs::getDocPathById($vehDoc['vhd_id']);

			break;
	}
}
$insApproveStyle = ($insuranceDoc != '' && $insuranceStatus == 0) ? "display:block;" : "display:none;";
$insRejectStyle	 = ($insuranceDoc != '' && ($insuranceStatus == 0 || $insuranceStatus == 1)) ? "display:block;" : "display:none;";
$insReloadStyle	 = ($insuranceDoc != '' && $insuranceStatus == 2) ? "display:block;" : "display:none;";
if ($insuranceDoc != '')
{
	if ($insuranceStatus == 0)
	{
		$insLabel	 = (($insuranceTempStatus == 1) ? 'Temporary Approved' : 'Not Approved');
		$ins		 = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => $insLabel];
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
		$regLabel	 = (($registrationTempStatus == 1) ? 'Temporary Approved' : 'Not Approved');
		$reg		 = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => $regLabel];
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

$regBackApproveStyle = ($registrationBackDoc != '' && $registrationBacStatus == 0) ? "display:block;" : "display:none;";
$regBackRejectStyle	 = ($registrationBackDoc != '' && ($registrationBacStatus == 0 || $registrationBacStatus == 1)) ? "display:block;" : "display:none;";
$regBackReloadStyle	 = ($registrationBackDoc != '' && $registrationBacStatus == 2) ? "display:block;" : "display:none;";
if ($registrationBackDoc != '')
{
	if ($registrationBacStatus == 0)
	{
		$regBacLabel = (($registrationBacTempStatus == 1) ? 'Temporary Approved' : 'Not Approved');
		$regBac		 = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => $regBacLabel];
	}
	else if ($registrationBacStatus == 1)
	{
		$regBac = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($registrationBacStatus == 2)
	{
		$regBac = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$regBac = '';
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
<div class="app-content content">
	<div class="content-overlay"></div>
	<div class="content-wrapper">
		<div class="content-body">
			<?php
			$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'vehicle-add-form',
				'enableClientValidation' => TRUE,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error'
				),
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
				),
			));
			/* @var $form TbActiveForm */
			?>

			<?= $form->hiddenField($model, 'vhc_id') ?>
			<?php //echo CHtml::errorSummary($model); ?>
			<div class="container">
				<h3>Add my cab</h3>
				<div class="row">
				
					<div class="col-12 col-md-6 col-xl-6 mob-view">
						<div class="card">
							<div class="col-12 text-center" >
								<div class="col-12 mb20" style="color:#008a00;text-align: center">
									<?php echo Yii::app()->user->getFlash('success'); ?>
								</div>
								<div class="col-12 mb20" style="color:#F00;text-align: center">
									<?php echo Yii::app()->user->getFlash('error'); ?>
								</div>    
							</div>		
							<div class="card-body">
								<div class="row">
									<div class="row">
									<div id="errordiv" class="col-12 text-danger text-center mb10"></div>
									<?php $arrReadOnly = ($isNew) ? [] : ['readOnly' => true]; ?>
									<div class="col-12 col-xl-6">
										<?= $form->textFieldGroup($model, 'vhc_number', array('label' => 'Vehicle Number', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Vehicle Number'] + $arrReadOnly))) ?>
									</div>
									<div class="col-12 col-xl-6">
										<label id="vhc_color_id">Cab Color</label>
									<?php
									 // echo $form->textFieldGroup($model, 'vhc_color', array('label' => 'Color', 'widgetOptions' => array())) 
										if (str_contains($model->vhc_color, '#')) { 
											$model->vhc_color = '';
										}
										$this->widget('booster.widgets.TbSelect2', array(
										   'model'			 => $model,
										   'attribute'		 => 'vhc_color',
										   //'val'			 => $model->vhc_color."",
										   'data'			 => Vehicles::colorList(),
										   'htmlOptions'	 => array(
											   'placeholder'	 => 'Select Cab Color',
											   'width'			 => '100%',
											   'style'			 => 'width:100%',
											   'id'             => 'vhc_color_1'
										   ),
									   ));
									?>
									<span class="has-error"><? echo $form->error($model, 'vhc_color'); ?></span>
									</div>
                                    <div class='col-12 col-xl-6'>
                                        <label id="vhc_type_id_id">Vehicle Type</label>
										<?php
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'vhc_type_id',
											'val'			 => $model->vhc_type_id,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($vTypeData)),
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Type', 'id' => 'vhc_type_id_1')
										));
										?>
                                        <span class="has-error"><? echo $form->error($model, 'vhc_type_id'); ?></span>
                                    </div>
									<div class="col-12 col-xl-6">
										<?=
										$form->numberFieldGroup($model, 'vhc_year', array('label'			 => 'Year',
											'widgetOptions'	 => array('htmlOptions' => array('min' => date('Y') - 25, 'max' => date('Y')))));
										?> 
									</div>
									<div class="col-12 col-xl-6">
										<?php
										if ($model->vhc_dop  &&  !preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $model->vhc_dop))
										{
											$model->vhc_dop = DateTimeFormat::DateTimeToDatePicker($model->vhc_dop);
										}
										echo $form->datePickerGroup($model, 'vhc_dop', array('label'			 => 'Date of Purchase',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
										));
										?>   <span class="has-error"><? #echo $form->error($model, 'vhc_dop'); ?></span>
									</div>
                                  </div>
								  <div class="row mb20"></div>
								  
								<div class="row">
										<div class="col-12">
										<div class="row mt15">
											<div class="col-12 label-p">
											<?php //$model->vhc_owned_or_rented = 1; ?>
												<?= $form->radioButtonListGroup($model, 'vhc_owned_or_rented', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'I own this car')), 'inline' => true)) ?>
											</div>
										</div>
										<?php if($model->vhc_id == 0 || $model->vhc_id == '' ){?>
										<div class="row" id="loudiv" style="display: <?php echo ($model->vhc_owned_or_rented==2)?'block':'none'?>">
											<div class="col-12 col-xl-6">
												<?= $form->textFieldGroup($model, 'lou_owner_name', array('label' => "Vehicle owner's first", 'widgetOptions' => array())) ?>
											</div>
											<div class="col-12 col-xl-6">
												<?= $form->textFieldGroup($model, 'lou_owner_surname', array('label' => "Vehicle owner's surname", 'widgetOptions' => array())) ?>
											</div>
											<div class="col-12 col-xl-6">
												<?= $form->textFieldGroup($model, 'lou_drv_licence', array('label' => "Vehicle owner's driving licence number", 'widgetOptions' => array())) ?>
											</div>
											<div class="col-12 col-xl-6">
												<?= $form->textFieldGroup($model, 'lou_pan', array('label' => "Vehicle owner's PAN number", 'widgetOptions' => array())) ?>
											</div>
											<div class="col-12 col-xl-6">
												<?php
												
												echo $form->datePickerGroup($model, 'lou_auth_end_date', array('label'	=> "Date until which you are authorized by vehicle's owner to operate this car",
													'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
												));
												?>
									        </div>
											<div class="col-12 col-xl-6">
												<?= $form->textFieldGroup($model, 'lou_email', array('label' => "Vehicle owner's email", 'widgetOptions' => array())) ?>
											</div>
											<div class="col-12 col-xl-6">
												<?= $form->textFieldGroup($model, 'lou_phone', array('label' => "Vehicle owner's phone", 'widgetOptions' => array())) ?>
											</div>
										</div>
										<?}?>
									</div>
									<div class="col-12 col-xl-6">
										<?= $form->textFieldGroup($model, 'vhc_reg_owner', array('label' => "Vehicle owner's name", 'widgetOptions' => array())) ?>
									</div>
									<div class="col-12 col-xl-6">
										<?= $form->textFieldGroup($model, 'vhc_reg_owner_lname', array('label' => "Vehicle owner's surname", 'widgetOptions' => array())) ?>
									</div>
									<div class="col-12 col-xl-6">
										<?php
										if ($model->vhc_reg_exp_date &&  !preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $model->vhc_reg_exp_date))
										{
											$model->vhc_reg_exp_date = DateTimeFormat::DateToDatePicker($model->vhc_reg_exp_date);
										}
										echo $form->datePickerGroup($model, 'vhc_reg_exp_date', array('label'			 => 'Registration end date',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
										));
										?>
									</div>
									<div class="col-12 col-xl-6">
										<?php
										if ($model->vhc_tax_exp_date  &&  !preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $model->vhc_tax_exp_date))
										{
											$model->vhc_tax_exp_date = DateTimeFormat::DateToDatePicker($model->vhc_tax_exp_date);
										}
										echo $form->datePickerGroup($model, 'vhc_tax_exp_date', array('label'			 => 'Tax paid until Date',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
										));
										?>
									</div>
									<div class="col-12 col-xl-6">
										<?php
										if ($model->vhc_commercial_exp_date  &&  !preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $model->vhc_commercial_exp_date))
										{
											$model->vhc_commercial_exp_date = DateTimeFormat::DateToDatePicker($model->vhc_commercial_exp_date);
										}
										echo $form->datePickerGroup($model, 'vhc_commercial_exp_date', array('label'			 => 'Commercial permit end date',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
										));
										?>
									</div>
									<div class="col-12 col-xl-6">
										<?php
										if ($model->vhc_fitness_cert_end_date  &&  !preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $model->vhc_fitness_cert_end_date))
										{
											$model->vhc_fitness_cert_end_date = DateTimeFormat::DateToDatePicker($model->vhc_fitness_cert_end_date);
										}
										echo $form->datePickerGroup($model, 'vhc_fitness_cert_end_date', array('label'			 => 'Fitness Expiry Date',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
										));
										?>
									</div>
									<div class="col-12 col-xl-6">
										<?php
										if ($model->vhc_insurance_exp_date   &&  !preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $model->vhc_insurance_exp_date))
										{
											//$model->vhc_insurance_exp_date = DateTimeFormat::DateToLocale($model->vhc_insurance_exp_date);
											$model->vhc_insurance_exp_date = DateTimeFormat::DateToDatePicker($model->vhc_insurance_exp_date);
										}
										?>

										<?=
										$form->datePickerGroup($model, 'vhc_insurance_exp_date', array('label'			 => 'Insurance Expiry Date',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
										));
										?></div>
									

									<div class="col-12 col-xl-6">
										<?php
										if ($model->vhc_pollution_exp_date   &&  !preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $model->vhc_pollution_exp_date))
										{
											$model->vhc_pollution_exp_date = DateTimeFormat::DateToDatePicker($model->vhc_pollution_exp_date);
										}
										echo $form->datePickerGroup($model, 'vhc_pollution_exp_date', array('label'			 => 'PUC expiry date',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
										));
										?>
									</div>
									
								
									
									
								</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6 col-xl-6 mob-view">
						<div class="card">
							<div class="card-body">
								<div class="row">

									<div class="col-12">       
										<label>Clear photo copy of valid insurance with end-date information</label>&nbsp;<span id="insurance" class="<?= $ins['class']; ?>" style="<?= $ins['style']; ?>;float:right;"><?= $ins['level']; ?></span> 
										<div id="insuranceDiv" style="<?php
										if ($insuranceDoc != '')
										{
											echo 'display:none';
										}
										?>"><?= $form->fileFieldGroup($model, 'vhc_insurance_proof', array('label' => '', 'widgetOptions' => array())); ?>
												 <?//= $form->checkboxListGroup($model, 'vhc_temp_insurance_approved', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Temporary Approved'), 'htmlOptions' => []), 'inline' => true)) ?>
										</div>
										<?php
										if ($insuranceDoc != '')
										{
											?>
											<div class="col-4">
												<a href="<?= $insuranceDoc ?>" target="_blank"><?= CHtml::image($insuranceDoc, $model->vhc_number, ['style' => 'width: 50px']); ?></a>
											</div>
										<?php } ?>
									</div>
<!--									<div class="col-12 hide">
										<span id="insurance1" style="<?= $insApproveStyle; ?>;float:left;"><img id="insApprove" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $insuranceId; ?>', '1')" style="cursor:pointer;"></span>
										<span id="insurance2" style="<?= $insRejectStyle; ?>;float:left;"><img id="insReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $insuranceId; ?>', '2')" style="cursor:pointer;"></span>
										<span id="insurance3" style="<?= $insReloadStyle; ?>;float:left;"><img id="insReload" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/reload.png" alt="Reload" title="Reload" onclick="updateVehicleDocs('<?= $insuranceId; ?>', '3')" style="cursor:pointer;"></span>
										<span id="insurance33" style="<?= $insReloadStyle; ?>;float:left;"><i><?= $insuranceRemarks; ?></i></span>
									</div>-->
									<div class="col-12">
										<div class="form-group">
											<label class="control-label">Full picture of cab including front license plate</label>&nbsp;<span id="frontLicense" class="<?= $frtLic['class']; ?>" style="<?= $frtLic['style']; ?>;float:right;"><?= $frtLic['level']; ?></span>
											<br>
											<div id="frontLicenseDiv" style="<?php
											if ($frontLicenseDoc != '')
											{
												echo 'display:none';
											};
											?>"><?= $form->fileFieldGroup($model, 'vhc_front_plate', array('label' => '', 'widgetOptions' => array())); ?></div> 
												 <?php
												 if ($frontLicenseDoc != '')
												 {
													 ?>
												<a href="<?= $frontLicenseDoc ?>" target="_blank"><?= CHtml::image($frontLicenseDoc, $model->vhc_number, ['style' => 'width: 50px']); ?></a>
											<?php } ?>
										</div>
									</div>       
<!--									<div class="col-12 hide">
										<span id="frontLicense1" style="<?= $frontLicApproveStyle; ?>;float:left;"><img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $frontLicenseId; ?>', '1')" style="cursor:pointer;"></span>
										<span id="frontLicense2" style="<?= $frontLicRejectStyle; ?>;float:left;"><img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $frontLicenseId; ?>', '2')" style="cursor:pointer;"></span>
										<span id="frontLicense3" style="<?= $frontLicReloadStyle; ?>;float:left;"><img id="frLicReload" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/reload.png" alt="Reload" title="Reload" onclick="updateVehicleDocs('<?= $frontLicenseId; ?>', '3')" style="cursor:pointer;"></span>
										<span id="frontLicense33" style="<?= $frontLicReloadStyle; ?>;float:left;"><i><?= $frontLicenseRemarks; ?></i></span>
									</div>-->
									<div class="col-12">
										<div class="form-group">
											<label class="control-label">Full picture of cab including rear license plate</label>&nbsp;<span id="rearLicense" class="<?= $rearLic['class']; ?>" style="<?= $rearLic['style']; ?>;float:right;"><?= $rearLic['level']; ?></span><br>
											<div id="rearLicenseDiv" style="<?php
											if ($rearLicenseDoc != '')
											{
												echo 'display:none';
											};
											?>"><?= $form->fileFieldGroup($model, 'vhc_rear_plate', array('label' => '', 'widgetOptions' => array())); ?></div>
												 <?php
												 if ($rearLicenseDoc != '')
												 {
													 ?>
												<a href="<?= $rearLicenseDoc ?>" target="_blank"><?= CHtml::image($rearLicenseDoc, $model->vhc_number, ['style' => 'width: 50px']); ?></a>
											<?php } ?>
										</div>
									</div>
<!--									<div class="col-12 hide">

										<span id="rearLicense1" style="<?= $reartLicApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $rearLicenseId; ?>', '1')" style="cursor:pointer;"></span>
										<span id="rearLicense2" style="<?= $reartLicRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $rearLicenseId; ?>', '2')" style="cursor:pointer;"></span>
										<span id="rearLicense3" style="<?= $reartLicReloadStyle; ?>;;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/reload.png" alt="Reload" title="Reload" onclick="updateVehicleDocs('<?= $rearLicenseId; ?>', '3')" style="cursor:pointer;"></span>
										<span id="rearLicense33" style="<?= $reartLicReloadStyle; ?>;float:left;"><i><?= $rearLicenseRemarks; ?></i></span>
									</div>-->
									<div class="col-12"> 
										<div class="form-group">
											<label class="control-label">Photo copy of Pollution under control certificate with end date</label>&nbsp;<span id="pollution" class="<?= $puc['class']; ?>" style="<?= $puc['style']; ?>;float:right;"><?= $puc['level']; ?></span>
											<div id="pollutionDiv" style="<?php
											if ($pollutionDoc != '')
											{
												echo 'display:none';
											};
											?>"><?= $form->fileFieldGroup($model, 'vhc_pollution_certificate', array('label' => '', 'widgetOptions' => array())); ?></div>
												 <?php
												 if ($pollutionDoc != '')
												 {
													 ?>
												<div class="col-12">
													<a href="<?= $pollutionDoc ?>" target="_blank"><?= CHtml::image($pollutionDoc, $model->vhc_number, ['style' => 'width: 50px']); ?></a>
												</div>
											<?php } ?>
										</div>  
									</div>   
<!--									<div class="col-12 hide">
										<span id="pollution1" style="<?= $pucApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $pollutionId; ?>', '1')" style="cursor:pointer;"></span>
										<span id="pollution2" style="<?= $pucRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $pollutionId; ?>', '2')" style="cursor:pointer;"></span>
										<span id="pollution3" style="<?= $pucReloadStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/reload.png" alt="Reload" title="Reload" onclick="updateVehicleDocs('<?= $pollutionId; ?>', '3')" style="cursor:pointer;"></span>
										<span id="pollution33" style="<?= $pucReloadStyle; ?>;float:left;"><i><?= $pollutionRemarks; ?></i></span>
									</div>-->
									<div class="col-12"> <div class="form-group">
											<label class="control-label">Photocopy of Registration certificate (Front) with readable end date</label>&nbsp;<span id="registration" class="<?= $reg['class']; ?>" style="<?= $reg['style']; ?>;float:right;"><?= $reg['level']; ?></span>
											<div id="registrationDiv" style="<?php
											if ($registrationDoc != '')
											{
												echo 'display:none';
											};
											?>"><?= $form->fileFieldGroup($model, 'vhc_reg_certificate', array('label' => '', 'widgetOptions' => array())); ?>
													 <?//= $form->checkboxListGroup($model, 'vhc_temp_reg_certificate_approved', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Temporary Approved'), 'htmlOptions' => []), 'inline' => true)) ?>
											</div> 
											<?php
											if ($registrationDoc != '')
											{
												?>
												<div class="col-xs-12">
													<a href="<?= $registrationDoc ?>" target="_blank"><?= CHtml::image($registrationDoc, $model->vhc_number, ['style' => 'width: 50px']); ?></a>
												</div>

											<?php } ?>

										</div>
									</div>
<!--									<div class="col-xs-12 hide">
										<span id="registration1" style="<?= $regApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $registrationId; ?>', '1')" style="cursor:pointer;"></span>
										<span id="registration2" style="<?= $regRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $registrationId; ?>', '2')" style="cursor:pointer;"></span>
										<span id="registration3" style="<?= $regReloadStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/reload.png" alt="Reload" title="Reload" onclick="updateVehicleDocs('<?= $registrationId; ?>', '3')" style="cursor:pointer;"></span>
										<span id="registration33" style="<?= $regReloadStyle; ?>;float:left;"><i><?= $registrationRemarks; ?></i></span>
									</div>-->
									<div class="col-12"> <div class="form-group">
											<label class="control-label">Photocopy of Registration certificate (Back) with readable end date</label>&nbsp;<span id="registrationBack" class="<?= $regBac['class']; ?>" style="<?= $regBac['style']; ?>;float:right;"><?= $regBac['level']; ?></span>
											<div id="registrationBackDiv" style="<?php
											if ($registrationBackDoc != '')
											{
												echo 'display:none';
											};
											?>"><?= $form->fileFieldGroup($model, 'vhc_back_reg_certificate', array('label' => '', 'widgetOptions' => array())); ?>
													 <?//= $form->checkboxListGroup($model, 'vhc_back_temp_reg_certificate_approved', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Temporary Approved'), 'htmlOptions' => []), 'inline' => true)) ?>
											</div> 
											<?php
											if ($registrationBackDoc != '')
											{
												?>
												<div class="col-12">
													<a href="<?= $registrationBackDoc ?>" target="_blank"><?= CHtml::image($registrationBackDoc, $model->vhc_number, ['style' => 'width: 50px']); ?></a>
												</div>

											<?php } ?>

										</div>
									</div>
<!--									<div class="col-12 hide">
										<span id="registrationBack1" style="<?= $regBackApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $registrationBacId; ?>', '1')" style="cursor:pointer;"></span>
										<span id="registrationBack2" style="<?= $regBackRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $registrationBacId; ?>', '2')" style="cursor:pointer;"></span>
										<span id="registrationBack3" style="<?= $regBackReloadStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/reload.png" alt="Reload" title="Reload" onclick="updateVehicleDocs('<?= $registrationBacId; ?>', '3')" style="cursor:pointer;"></span>
										<span id="registrationBack4" style="<?= $regBackReloadStyle; ?>;float:left;"><i><?= $registrationBacRemarks; ?></i></span>
									</div>-->
									<div class="col-12">
										<div class="form-group">
											<label class="control-label">Photocopy of applicable commercial permits with readable end date</label>&nbsp;<span id="commercialPermit" class="<?= $permit['class']; ?>" style="<?= $permit['style']; ?>;float:right;"><?= $permit['level']; ?></span>
											<div id="commercialPermitDiv" style="<?php
											if ($commercialPermitDoc != '')
											{
												echo 'display:none';
											};
											?>"><?= $form->fileFieldGroup($model, 'vhc_permits_certificate', array('label' => '', 'widgetOptions' => array())); ?></div>
												 <?php
												 if ($commercialPermitDoc != '')
												 {
													 ?>
												<div class="col-12">
													<a href="<?= $commercialPermitDoc ?>" target="_blank"><?= CHtml::image($commercialPermitDoc, $model->vhc_number, ['style' => 'width: 50px']); ?></a>
												</div>
											<?php } ?> 
										</div>
									</div>  
<!--									<div class="col-12 hide">

										<span id="commercialPermit1" style="<?= $permitApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $commercialPermitId; ?>', '1')" style="cursor:pointer;"></span>
										<span id="commercialPermit2" style="<?= $permitRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $commercialPermitId; ?>', '2')" style="cursor:pointer;"></span>
										<span id="commercialPermit3" style="<?= $permitReloadStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/reload.png" alt="Reload" title="Reload" onclick="updateVehicleDocs('<?= $commercialPermitId; ?>', '3')" style="cursor:pointer;"></span>
										<span id="commercialPermit33" style="<?= $permitReloadStyle; ?>;float:left;">&nbsp;<i><?= $commercialPermitRemarks; ?></i></span>
									</div>-->
									<div class="col-12">

										<label class="control-label">Photocopy of fitness certificate with readable end date</label>&nbsp;<span id="fitnessCertificate" class="<?= $fit['class']; ?>" style="<?= $fit['style']; ?>;float:right;"><?= $fit['level']; ?></span>
										<div id="fitnessCertificateDiv" style="<?php
										if ($fitnessCertificateDoc != '')
										{
											echo 'display:none';
										};
										?>"><?php echo $form->fileFieldGroup($model, 'vhc_fitness_certificate', array('label' => '', 'widgetOptions' => array())); ?></div>
											 <?php
											 if ($fitnessCertificateDoc != '')
											 {
												 ?>
											<div class="col-12">
												<a href="<?= $fitnessCertificateDoc ?>" target="_blank"><?= CHtml::image($fitnessCertificateDoc, $model->vhc_number, ['style' => 'width: 50px']); ?></a>
											</div>
										<?php } ?> 
									</div>
<!--									<div class="col-12"><br>
										<span id="fitnessCertificate1" style="<?= $fitApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $fitnessCertificateId; ?>', '1')" style="cursor:pointer;"></span>
										<span id="fitnessCertificate2" style="<?= $fitRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $fitnessCertificateId; ?>', '2')" style="cursor:pointer;"></span>
										<span id="fitnessCertificate3" style="<?= $fitReloadStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/reload.png" alt="Reload" title="Reload" onclick="updateVehicleDocs('<?= $fitnessCertificateId; ?>', '3')" style="cursor:pointer;"></span>
										<span id="fitnessCertificate33" style="<?= $fitReloadStyle; ?>;float:left;"><i><?= $fitnessRemarks; ?></i></span>
									</div>-->
									<div class="col-12" style="text-align: center">
										<?php
										if ($isNew)
										{
											echo CHtml::submitButton('submit', array('class' => 'btn btn-primary btn-lg font-16', 'onClick' => 'return verifyVehicle();'));
										}
										else
										{
											echo CHtml::submitButton('submit', array('class' => 'btn btn-primary btn-lg font-16'));
										}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php $this->endWidget(); ?>

		</div>
	</div>
</div>
<script  type="text/javascript">
	$sourceList = null;
	$(document).ready(function () {
	var availableTags = [];
	var front_end_height = $(window).height();
	var footer_height = $(".footer").height();
	var header_height = $(".header").height();
	$("#Vehicles_vhc_type").change(function () {
	getVehicleModels();
	});
	});


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

	function getVehicleModels()
	{
	var vhcTypeId = $("#Vehicles_vhc_type").val();

	var href2 = '<?= Yii::app()->createUrl("admin/vehicle/vehiclemodelbytype"); ?>';
	$.ajax({
	"url": href2,
	"type": "GET",
	"dataType": "json",
	"data": {"vhcTypeId": vhcTypeId},
	"success": function (data1)
	{
	$data2 = data1;
	var placeholder = $('#<?= CHtml::activeId($model, "vhc_type_id") ?>').attr('placeholder');
	$('#<?= CHtml::activeId($model, "vhc_type_id") ?>').select2({data: $data2, placeholder: placeholder});

	}
	});
	}


	$('#Vehicles_vhc_insurance_exp_date').datepicker({
	format: 'dd/mm/yyyy'
	});
	$('#Vehicles_vhc_tax_exp_date').datepicker({
	format: 'dd/mm/yyyy'
	});
	$('#Vehicles_vhc_dop').datepicker({
	format: 'dd/mm/yyyy'
	});

	$('#<?= CHtml::activeId($model, 'vhc_number') ?>').mask('AA 0Z YYY 0000', {
	translation: {
	'Z': {
	pattern: /[0-9]/, optional: true
	},
	'Y': {
	pattern: /[A-Za-z]/, optional: true
	},
	'X': {
	pattern: /[0-9A-Za-z]/, optional: true
	},
	'A': {
	pattern: /[A-Za-z]/, optional: false
	},
	},
	placeholder: "__ __ __ ____",
	clearIfNotMatch: true
	});
	function  verifyVehicle() 
	{
		$('#errordiv').hide();
		$('#errordiv').html('');
		$('#Vehicles_vhc_type_id_em_').html('');
		$('#vhc_type_id_id').removeClass('text-danger');
		$('#Vehicles_vhc_color_em_').html('');
		$('#vhc_color_id').removeClass('text-danger');
		var vndid = '<?= $vndId ?>';
		var vhcnumber = $('#<?= CHtml::activeId($model, "vhc_number") ?>').val();

		var href = '<?= Yii::app()->createUrl("supplier/cab/checkexisting"); ?>';
		if (vhcnumber != '') 
		{
			$.ajax({
			"url": href,
			"type": "GET",
			"dataType": "json",
			"data": {"vndid": vndid, "vhcnumber": vhcnumber},
			"success": function (data) 
			{
				debugger;
				let error = 0;
				if (data.vhc_id > 0 && data.assigned == 0) 
				{
					$('#errordiv').show();
					$('#errordiv').text('Vehicle with these details already exist. No vendor assigned yet to vehicle');
					error++;
				}

				if (data.vhc_id > 0 && data.assigned > 0 && data.this_vendor != 1 && vndid != '') 
				{
					$('#errordiv').show();
					$('#errordiv').text('Vehicle with these details already exist. One or more vendors are already assigned. Continue to assign this vendor');
					$('#vhc_detail').show();
					$('#btnVerify').hide();
					error++;
				}
				if (data.vhc_id > 0 && data.assigned > 0 && data.this_vendor != 1 && vndid == '') {
					$('#errordiv').show();
					$('#errordiv').text('Vehicle with these details already exist. One or more vendors are assigned. Continue to edit vehicle details');
					$('#vhc_detail').show();
					$('#btnVerify').hide();
					error++;
				}
				$('#vhc_detail').show();
				$('#btnVerify').hide();
				if (data.vhc_id > 0 && data.assigned > 0 && data.this_vendor == 1) 
				{
					$('#errordiv').show();
					$('#errordiv').text('Vehicle with these details already exist. This Vendor is already assigned');
					$('#vhc_detail').hide();
					$('#btnVerify').show();
					error++;
				}

				let vhc_type_id_id = $('#vhc_type_id_1').val();
				if(vhc_type_id_id==null || vhc_type_id_id==undefined || vhc_type_id_id=='')
				{
					$('#vhc_type_id_id').addClass('text-danger');
					$('#Vehicles_vhc_type_id_em_').html('Vehicle type is mandatory').show();
					error++;
				}
				let vhc_color_1 = $('#vhc_type_id_1').val();
				if(vhc_color_1==null || vhc_color_1==undefined || vhc_color_1=='')
				{
					$('#vhc_color_id').addClass('text-danger');
					$('#Vehicles_vhc_color_em_').html('Cab color is mandatory').show();
					error++;
				}

				if(error > 0)
				{
				$(window).scrollTop(0);
				return false;
				}
				$('form#vehicle-add-form').submit();
				}
				});
			} 
			else 
			{
				let errMessage = 'Vehicle number is mandatory';
				$('#errordiv').show();
				$('#errordiv').html(errMessage);
				return false;
			}
			event.preventDefault();
	}

	function populateSource(obj, cityId='')
	{

	obj.load(function (callback)
	{
	var obj = this;
	if ($sourceList == null)
	{
	var urlCity = '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>';
	xhr = $.ajax({
	url: urlCity,
	dataType: 'json',
	data: {
	},
	success: function (results)
	{
	$sourceList = results;
	obj.enable();
	callback($sourceList);
	obj.setValue(cityId);
	},
	error: function ()
	{
	callback();
	}
	});
	} else
	{
	obj.enable();
	callback($sourceList);
	obj.setValue(cityId);
	}
	});
	}

	function loadSource(query, callback)
	{

	$.ajax({
	url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=0&q=' + encodeURIComponent(query),
	type: 'GET',
	dataType: 'json',
	global: false,
	error: function ()
	{
	callback();
	},
	success: function (res)
	{
	callback(res);
	}
	});
	}

	
	$("#Vehicles_vhc_owned_or_rented_1").on("click", function(){
		 $('#loudiv').show();
	});
	$("#Vehicles_vhc_owned_or_rented_0").on("click", function(){
		 $('#loudiv').hide();
		 $('#Vehicles_lou_owner_name').val('');
		 $('#Vehicles_lou_owner_surname').val('');
		 $('#Vehicles_lou_drv_licence').val('');
		 $('#Vehicles_lou_pan').val('');
		 $('#Vehicles_lou_auth_end_date').val('');
		 $('#Vehicles_lou_email').val('');
		 $('#Vehicles_lou_phone').val('');
	});
</script>