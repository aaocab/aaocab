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

			$registrationBacId		 = $vehDoc['vhd_id'];
			$registrationBacStatus	 = $vehDoc['vhd_status'];
			$registrationBacRemarks	 = $vehDoc['vhd_remarks'];
			$registrationBacTempStatus		 = $vehDoc['vhd_temp_approved'];
			$registrationBackDoc	 = VehicleDocs::getDocPathById($vehDoc['vhd_id']);

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
		$regBacLabel	 = (($registrationBacTempStatus == 1) ? 'Temporary Approved' : 'Not Approved');
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
<div class="row">
    <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12" >
        <div class="col-xs-12 mb20" style="color:#008a00;text-align: center">
			<?php echo Yii::app()->user->getFlash('success'); ?>
        </div>
        <div class="col-xs-12 mb20" style="color:#F00;text-align: center">
			<?php echo Yii::app()->user->getFlash('error'); ?>
        </div>    
    </div>
</div>
<div class="row">
    <div class="col-lg-7 col-lg-offset-2 col-md-7 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12 pb10 new-booking-list" >
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'vehicle-form',
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
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
					<?= $form->hiddenField($model, 'vhc_id') ?>
					<?php echo CHtml::errorSummary($model); ?> 
                    <div class="text-danger" id="errordiv" style="display: none"></div>
                    <div class="col-xs-12">
                        <div class="row"> 
                            <div class="col-xs-12 col-md-6">
								<label class="control-label" for="Vendor_vhc_vendor_id1">Vendor</label>

								<?php
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'vhc_vendor_id1',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select Vendor",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('width' => '100%'),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
                                              populateVendor(this, '{$model->vhc_vendor_id1}');
                                            }",
								'load'			 => "js:function(query, callback){
                                            loadVendor(query, callback);
                                            }",
								'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                        option_create: function(data, escape){
                                        return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                        }
                                        }",
									),
								));
								?>


                                <span class="has-error"><? echo $form->error($model, 'vhc_vendor_id1'); ?></span>
                            </div>
                            <div class="col-xs-12 col-md-6">
								<?= $form->textFieldGroup($model, 'vhc_number', array('label' => 'Vehicle Number', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Vehicle Number']))) ?>
                            </div>
                        </div>
                        <div class="row" id="btnVerify" style="display:<?= $displayBtn ?>; text-align: center">
                            <div class="col-xs-12 pl0 mb20" >
                                <button type="button" class="btn btn-primary" onclick="verifyVehicle()">Proceed</button>
                            </div>
                        </div>
                        <div class="" id="vhc_detail" style="display: <?= $displayBlock ?>">
                            <div class="row"> 
                                <div class="col-xs-12 col-md-6">
                                    <div class='form-group'>
                                        <label>Vehicle Type</label>
										<?php
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'vhc_type_id',
											'val'			 => $model->vhc_type_id,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($vTypeData)),
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Type')
										));
										?>
                                        <span class="has-error"><? echo $form->error($model, 'vhc_type_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
									<?=
									$form->numberFieldGroup($model, 'vhc_year', array('label'			 => 'Year',
										'widgetOptions'	 => array('htmlOptions' => array('min' => date('Y') - 25, 'max' => date('Y')))));
									?> 
                                </div>


								<div class="col-xs-12 col-md-6">
									<label>Home City</label>



									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'vhc_home_city',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Select Source City",
										'fullWidth'			 => false,
										'htmlOptions'		 => array('width'	 => '100%',
											'id'	 => 'Vehicles_vhc_home_city'
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
                                            populateSource(this, '{$model->vhc_home_city}');
                                                }",
									'load'			 => "js:function(query, callback){
                                            loadSource(query, callback);
                                            }",
									'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
										),
									));
									?>

									<span class="has-error"><? echo $form->error($model, 'vhc_home_city'); ?></span>
                                </div>
								<div class="col-xs-12 col-md-6">
									<?= $form->textFieldGroup($model, 'vhc_color', array('label' => 'Color', 'widgetOptions' => array())) ?>
                                </div>
                                <div class="col-xs-12 col-md-6">
									<?php
									if ($model->vhc_insurance_exp_date)
									{
										//$model->vhc_insurance_exp_date = DateTimeFormat::DateToLocale($model->vhc_insurance_exp_date);
										$model->vhc_insurance_exp_date = DateTimeFormat::DateToDatePicker($model->vhc_insurance_exp_date);
									}
									?>
									<?=
									$form->datePickerGroup($model, 'vhc_insurance_exp_date', array('label'			 => 'Insurance Expiry Date',
										'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
									));
									?>
                                </div>
                                <div class="col-xs-12 col-md-6">
									<?php
									if ($model->vhc_tax_exp_date)
									{
										$model->vhc_tax_exp_date = DateTimeFormat::DateToDatePicker($model->vhc_tax_exp_date);
									}
									echo $form->datePickerGroup($model, 'vhc_tax_exp_date', array('label'			 => 'Tax Expiry Date',
										'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
									));
									?>
                                </div>
                                <div class="col-xs-12 col-md-6">
									<?php
									if ($model->vhc_dop)
									{
										$model->vhc_dop = DateTimeFormat::DateTimeToDatePicker($model->vhc_dop);
									}
									echo $form->datePickerGroup($model, 'vhc_dop', array('label'			 => 'Date of Purchase',
										'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
									));
									?>   <span class="has-error"><? #echo $form->error($model, 'vhc_dop'); ?></span>
                                </div>
                                <div class="col-xs-12 col-md-6">
									<?php
									if ($model->vhc_pollution_exp_date)
									{
										$model->vhc_pollution_exp_date = DateTimeFormat::DateToDatePicker($model->vhc_pollution_exp_date);
									}
									echo $form->datePickerGroup($model, 'vhc_pollution_exp_date', array('label'			 => 'Pollution under control certificate End Date',
										'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
									));
									?>
                                </div>
                                <div class="col-xs-12 col-md-6">
									<?php
									if ($model->vhc_reg_exp_date)
									{
										$model->vhc_reg_exp_date = DateTimeFormat::DateToDatePicker($model->vhc_reg_exp_date);
									}
									echo $form->datePickerGroup($model, 'vhc_reg_exp_date', array('label'			 => 'Registration End Date',
										'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
									));
									?>
                                </div>
                                <div class="col-xs-12 col-md-6">
									<?php
									if ($model->vhc_commercial_exp_date)
									{
										$model->vhc_commercial_exp_date = DateTimeFormat::DateToDatePicker($model->vhc_commercial_exp_date);
									}
									echo $form->datePickerGroup($model, 'vhc_commercial_exp_date', array('label'			 => 'Commercial permits end date',
										'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
									));
									?> 
                                </div>
								<div class="col-xs-12 col-md-6">
									<?php
									if ($model->vhc_fitness_cert_end_date)
									{
										$model->vhc_fitness_cert_end_date = DateTimeFormat::DateToDatePicker($model->vhc_fitness_cert_end_date);
									}
									echo $form->datePickerGroup($model, 'vhc_fitness_cert_end_date', array('label'			 => 'Fitness Certificate Expiry Date',
										'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
									));
									?>
                                </div>

                                <div class="col-xs-12 col-md-6">
									<?= $form->textFieldGroup($model, 'vhc_reg_owner', array('label' => 'First Name of Registered owner of Vehicle', 'widgetOptions' => array())) ?>
                                </div>
								<div class="col-xs-12 col-md-6">
									<?= $form->textFieldGroup($model, 'vhc_reg_owner_lname', array('label' => 'Last Name of Registered owner of Vehicle', 'widgetOptions' => array())) ?>
                                </div> 
								<div class="col-xs-12 col-md-6">

									<label>Approved for following trip types</label>
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'vhc_trip_type',
										'val'			 => explode(',', $model->vhc_trip_type),
										'data'			 => Vehicles::getTripType(),
										'htmlOptions'	 => array(
											'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Cab Types',
											'width'			 => '100%',
											'style'			 => 'width:100%',
										),
									));
									?>
									<span class="has-error"><? echo $form->error($model, 'vhc_trip_type'); ?></span>	
								</div>
								<div class="col-xs-12">
									<div class="row">
										<div class="col-xs-12">
											<label class="control-label" for="Vehicle_vhc_owned_or_rented">Vehicle is</label>
										</div>
										<div class="col-xs-12 col-md-3">
											<?php
											if ($model->vhc_is_attached == 1)
											{
												$is_attached = true;
											}
											else
											{
												$is_attached = false;
											}
											?>
											<nobr>  <?= $form->checkboxListGroup($model, 'vhc_is_attached', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Is exclusive to Gozo'), 'htmlOptions' => ['checked' => $is_attached]), 'inline' => true)) ?></nobr>
										</div>

										<div class="col-xs-12 col-md-3">
											<?php
											if ($model->vhc_is_commercial == 1)
											{
												$is_commercial = true;
											}
											else
											{
												$is_commercial = false;
											}
											?>  
											<?= $form->checkboxListGroup($model, 'vhc_is_commercial', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Is commercial'), 'htmlOptions' => ['checked' => $is_commercial]), 'inline' => true)) ?>
										</div>


										<div class="col-xs-12 col-md-3">
											<?php
											if ($model->vhc_is_uber_approved == 1)
											{
												$is_uber_approved = true;
											}
											else
											{
												$is_uber_approved = false;
											}
											?>
											<?= $form->checkboxListGroup($model, 'vhc_is_uber_approved', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Is UBER Approved'), 'htmlOptions' => ['checked' => $is_uber_approved]), 'inline' => true)) ?>
										</div>
										<div class="col-xs-12 col-md-3">
											<?php
											if ($model->vhc_has_cng == 1)
											{
												$has_cng = true;
											}
											else
											{
												$has_cng = false;
											}
											?>
											<?= $form->checkboxListGroup($model, 'vhc_has_cng', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Is CNG'), 'htmlOptions' => ['checked' => $has_cng]), 'inline' => true)) ?>
										</div>
										<div class="col-xs-12 col-md-3">
											<?php
											if ($model->vhc_has_rooftop_carrier == 1)
											{
												$has_rooftop_carrier = true;
											}
											else
											{
												$has_rooftop_carrier = false;
											}
											?>
											<?= $form->checkboxListGroup($model, 'vhc_has_rooftop_carrier', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Has Rooftop Carrier'), 'htmlOptions' => ['checked' => $has_rooftop_carrier]), 'inline' => true)) ?>
										</div>
										<div class="col-xs-12 col-md-3">
											<?php
											$model->isPartitioned = $model->vhcStat->vhs_is_partition;
											if ($model->isPartitioned == 1)
											{
												$isParitioned = true;
											}
											else
											{
												$isParitioned = false;
											}
											?>
											<?= $form->checkboxListGroup($model, 'isPartitioned', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Is Partitioned'), 'htmlOptions' => ['checked' => $isParitioned]), 'inline' => true)) ?>
										</div>
										<div class="col-xs-12 col-md-3">
											<?php
											$model->isBoostVerify = $model->vhcStat->vhs_boost_verify;
											if ($model->isBoostVerify == 1)
											{
												$isBoostVerify = true;
											}
											else
											{
												$isBoostVerify = false;
											}
											?>
											<?= $form->checkboxListGroup($model, 'isBoostVerify', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Is Boost Verify'), 'htmlOptions' => ['checked' => $isBoostVerify]), 'inline' => true)) ?>
										</div>
										<div class="col-xs-12 col-md-3">
											<?php
											if ($model->vhc_has_electric == 1)
											{
												$has_eletric = true;
											}
											else
											{
												$has_eletric = false;
											}
											?>
											<?= $form->checkboxListGroup($model, 'vhc_has_electric', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Is Electric'), 'htmlOptions' => ['checked' => $has_eletric]), 'inline' => true)) ?>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-md-9">
											<?= $form->radioButtonListGroup($model, 'vhc_owned_or_rented', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Owned by Me', 2 => 'Operated by Me')), 'inline' => true)) ?>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-md-7">       
											<label>Clear photo copy of valid insurance with end-date information</label>&nbsp;<span id="insurance" class="<?= $ins['class']; ?>" style="<?= $ins['style']; ?>;float:right;"><?= $ins['level']; ?></span> 
											<div id="insuranceDiv" style="<?php
											if ($insuranceDoc != '')
											{
												echo 'display:none';
											}
											?>"><?= $form->fileFieldGroup($model, 'vhc_insurance_proof', array('label' => '', 'widgetOptions' => array())); ?>
													 <?= $form->checkboxListGroup($model, 'vhc_temp_insurance_approved', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Temporary Approved'), 'htmlOptions' => []), 'inline' => true)) ?>
											</div>
											<?php
											if ($insuranceDoc != '')
											{
												?>
												<div class="col-xs-4">
													<a href="<?= $insuranceDoc ?>" target="_blank"><?= CHtml::image($insuranceDoc, $model->vhc_number, ['style' => 'width: 50px']); ?></a>
												</div>
											<?php } ?>
										</div>
										<div class="col-xs-12 col-md-5">
											<span id="insurance1" style="<?= $insApproveStyle; ?>;float:left;"><img id="insApprove" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $insuranceId; ?>', '1')" style="cursor:pointer;"></span>
											<span id="insurance2" style="<?= $insRejectStyle; ?>;float:left;"><img id="insReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $insuranceId; ?>', '2')" style="cursor:pointer;"></span>
											<span id="insurance3" style="<?= $insReloadStyle; ?>;float:left;"><img id="insReload" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/reload.png" alt="Reload" title="Reload" onclick="updateVehicleDocs('<?= $insuranceId; ?>', '3')" style="cursor:pointer;"></span>
											<span id="insurance33" style="<?= $insReloadStyle; ?>;float:left;"><i><?= $insuranceRemarks; ?></i></span>
										</div>
									</div>

									<div class="row">
										<div class="col-xs-12 col-md-7">
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
										<div class="col-xs-12 col-md-5">
											<span id="frontLicense1" style="<?= $frontLicApproveStyle; ?>;float:left;"><img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $frontLicenseId; ?>', '1')" style="cursor:pointer;"></span>
											<span id="frontLicense2" style="<?= $frontLicRejectStyle; ?>;float:left;"><img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $frontLicenseId; ?>', '2')" style="cursor:pointer;"></span>
											<span id="frontLicense3" style="<?= $frontLicReloadStyle; ?>;float:left;"><img id="frLicReload" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/reload.png" alt="Reload" title="Reload" onclick="updateVehicleDocs('<?= $frontLicenseId; ?>', '3')" style="cursor:pointer;"></span>
											<span id="frontLicense33" style="<?= $frontLicReloadStyle; ?>;float:left;"><i><?= $frontLicenseRemarks; ?></i></span>
										</div>   
									</div> 
									<div class="row">
										<div class="col-xs-12 col-md-7">
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
										<div class="col-xs-12 col-md-5">

											<span id="rearLicense1" style="<?= $reartLicApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $rearLicenseId; ?>', '1')" style="cursor:pointer;"></span>
											<span id="rearLicense2" style="<?= $reartLicRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $rearLicenseId; ?>', '2')" style="cursor:pointer;"></span>
											<span id="rearLicense3" style="<?= $reartLicReloadStyle; ?>;;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/reload.png" alt="Reload" title="Reload" onclick="updateVehicleDocs('<?= $rearLicenseId; ?>', '3')" style="cursor:pointer;"></span>
											<span id="rearLicense33" style="<?= $reartLicReloadStyle; ?>;float:left;"><i><?= $rearLicenseRemarks; ?></i></span>
										</div>   
									</div> 
									<div class="row">
										<div class="col-xs-12 col-md-7"> 
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
													<div class="col-xs-12">
														<a href="<?= $pollutionDoc ?>" target="_blank"><?= CHtml::image($pollutionDoc, $model->vhc_number, ['style' => 'width: 50px']); ?></a>
													</div>
												<?php } ?>
											</div>  
										</div>   
										<div class="col-xs-12 col-md-5">
											<span id="pollution1" style="<?= $pucApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $pollutionId; ?>', '1')" style="cursor:pointer;"></span>
											<span id="pollution2" style="<?= $pucRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $pollutionId; ?>', '2')" style="cursor:pointer;"></span>
											<span id="pollution3" style="<?= $pucReloadStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/reload.png" alt="Reload" title="Reload" onclick="updateVehicleDocs('<?= $pollutionId; ?>', '3')" style="cursor:pointer;"></span>
											<span id="pollution33" style="<?= $pucReloadStyle; ?>;float:left;"><i><?= $pollutionRemarks; ?></i></span>
										</div>     
									</div> 
									<div class="row">
										<div class="col-xs-12 col-md-7"> <div class="form-group">
												<label class="control-label">Photocopy of Registration certificate(Front) with readable end date</label>&nbsp;<span id="registration" class="<?= $reg['class']; ?>" style="<?= $reg['style']; ?>;float:right;"><?= $reg['level']; ?></span>
												<div id="registrationDiv" style="<?php
												if ($registrationDoc != '')
												{
													echo 'display:none';
												};
												?>"><?= $form->fileFieldGroup($model, 'vhc_reg_certificate', array('label' => '', 'widgetOptions' => array())); ?>
														 <?= $form->checkboxListGroup($model, 'vhc_temp_reg_certificate_approved', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Temporary Approved'), 'htmlOptions' => []), 'inline' => true)) ?>
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
										<div class="col-xs-12 col-md-5">
											<span id="registration1" style="<?= $regApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $registrationId; ?>', '1')" style="cursor:pointer;"></span>
											<span id="registration2" style="<?= $regRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $registrationId; ?>', '2')" style="cursor:pointer;"></span>
											<span id="registration3" style="<?= $regReloadStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/reload.png" alt="Reload" title="Reload" onclick="updateVehicleDocs('<?= $registrationId; ?>', '3')" style="cursor:pointer;"></span>
											<span id="registration33" style="<?= $regReloadStyle; ?>;float:left;"><i><?= $registrationRemarks; ?></i></span>
										</div>     
									</div> 

									<div class="row">
										<div class="col-xs-12 col-md-7"> <div class="form-group">
												<label class="control-label">Photocopy of Registration certificate(Back) with readable end date</label>&nbsp;<span id="registrationBack" class="<?= $regBac['class']; ?>" style="<?= $regBac['style']; ?>;float:right;"><?= $regBac['level']; ?></span>
												<div id="registrationBackDiv" style="<?php
												if ($registrationBackDoc != '')
												{
													echo 'display:none';
												};
												?>"><?= $form->fileFieldGroup($model, 'vhc_back_reg_certificate', array('label' => '', 'widgetOptions' => array())); ?>
														 <?= $form->checkboxListGroup($model, 'vhc_back_temp_reg_certificate_approved', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Temporary Approved'), 'htmlOptions' => []), 'inline' => true)) ?>
												</div> 
												<?php
												if ($registrationBackDoc != '')
												{
													?>
													<div class="col-xs-12">
														<a href="<?= $registrationBackDoc ?>" target="_blank"><?= CHtml::image($registrationBackDoc, $model->vhc_number, ['style' => 'width: 50px']); ?></a>
													</div>

												<?php } ?>

											</div>
										</div>
										<div class="col-xs-12 col-md-5">
											<span id="registrationBack1" style="<?= $regBackApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $registrationBacId; ?>', '1')" style="cursor:pointer;"></span>
											<span id="registrationBack2" style="<?= $regBackRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $registrationBacId; ?>', '2')" style="cursor:pointer;"></span>
											<span id="registrationBack3" style="<?= $regBackReloadStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/reload.png" alt="Reload" title="Reload" onclick="updateVehicleDocs('<?= $registrationBacId; ?>', '3')" style="cursor:pointer;"></span>
											<span id="registrationBack4" style="<?= $regBackReloadStyle; ?>;float:left;"><i><?= $registrationBacRemarks; ?></i></span>
										</div>     
									</div> 



									<div class="row">
										<div class="col-xs-12 col-md-7">
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
													<div class="col-xs-12">
														<a href="<?= $commercialPermitDoc ?>" target="_blank"><?= CHtml::image($commercialPermitDoc, $model->vhc_number, ['style' => 'width: 50px']); ?></a>
													</div>
												<?php } ?> 
											</div>
										</div>  
										<div class="col-xs-12 col-md-5">

											<span id="commercialPermit1" style="<?= $permitApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $commercialPermitId; ?>', '1')" style="cursor:pointer;"></span>
											<span id="commercialPermit2" style="<?= $permitRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $commercialPermitId; ?>', '2')" style="cursor:pointer;"></span>
											<span id="commercialPermit3" style="<?= $permitReloadStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/reload.png" alt="Reload" title="Reload" onclick="updateVehicleDocs('<?= $commercialPermitId; ?>', '3')" style="cursor:pointer;"></span>
											<span id="commercialPermit33" style="<?= $permitReloadStyle; ?>;float:left;">&nbsp;<i><?= $commercialPermitRemarks; ?></i></span>
										</div>
									</div> 
									<div class="row">
										<div class="col-xs-12 col-md-7">

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
												<div class="col-xs-12">
													<a href="<?= $fitnessCertificateDoc ?>" target="_blank"><?= CHtml::image($fitnessCertificateDoc, $model->vhc_number, ['style' => 'width: 50px']); ?></a>
												</div>
											<?php } ?> 
										</div>
										<div class="col-xs-12 col-md-5"><br>
											<span id="fitnessCertificate1" style="<?= $fitApproveStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="updateVehicleDocs('<?= $fitnessCertificateId; ?>', '1')" style="cursor:pointer;"></span>
											<span id="fitnessCertificate2" style="<?= $fitRejectStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="rejectDriverDocs('<?= $fitnessCertificateId; ?>', '2')" style="cursor:pointer;"></span>
											<span id="fitnessCertificate3" style="<?= $fitReloadStyle; ?>;float:left;"><img src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/reload.png" alt="Reload" title="Reload" onclick="updateVehicleDocs('<?= $fitnessCertificateId; ?>', '3')" style="cursor:pointer;"></span>
											<span id="fitnessCertificate33" style="<?= $fitReloadStyle; ?>;float:left;"><i><?= $fitnessRemarks; ?></i></span>
										</div>
									</div> 
									<div class="row" style="text-align: center">
										<?php echo CHtml::submitButton('submit', array('class' => 'btn btn-primary')); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php $this->endWidget(); ?>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-10 col-md-offset-0 col-sm-offset-1 col-xs-12 pb10 border border-radius" >
				<div class="row" id='vndlist'>
				</div>
			</div>
		</div>
		<script  type="text/javascript">
			$(document).ready(function () {
				var availableTags = [];
				var front_end_height = $(window).height();
				var footer_height = $(".footer").height();
				var header_height = $(".header").height();
				$("#Vehicles_vhc_type").change(function () {
					getVehicleModels();
				});

				fillvendorlist();
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
			function verifyVehicle() {
				$('#errordiv').hide();
				var vndid = $('#<?= CHtml::activeId($model, "vhc_vendor_id1") ?>').val();
				var vhcnumber = $('#<?= CHtml::activeId($model, "vhc_number") ?>').val();

				var href = '<?= Yii::app()->createUrl("admin/vehicle/checkexisting"); ?>';
				if (vhcnumber != '') {
					$.ajax({
						"url": href,
						"type": "GET",
						"dataType": "json",
						"data": {"vndid": vndid, "vhcnumber": vhcnumber},
						"success": function (data) {
							//[vhc_id] [assigned]  [vhcnumber] [vendorids] [this_vendor]

							if (data.vhc_id > 0 && data.assigned == 0) {
								filldetails(data.vhc_id);
								$('#errordiv').show();
								$('#errordiv').text('Vehicle with these details already exist. No vendor assigned yet to vehicle');
							}

							if (data.vhc_id > 0 && data.assigned > 0 && data.this_vendor != 1 && vndid != '') {
								filldetails(data.vhc_id, false);
								$('#errordiv').show();
								$('#errordiv').text('Vehicle with these details already exist. One or more vendors are already assigned. Continue to assign this vendor');
								$('#vhc_detail').show();
								$('#btnVerify').hide();
							}
							if (data.vhc_id > 0 && data.assigned > 0 && data.this_vendor != 1 && vndid == '') {
								filldetails(data.vhc_id);
								$('#errordiv').show();
								$('#errordiv').text('Vehicle with these details already exist. One or more vendors are assigned. Continue to edit vehicle details');
								$('#vhc_detail').show();
								$('#btnVerify').hide();
							}
							$('#vhc_detail').show();
							$('#btnVerify').hide();
							if (data.vhc_id > 0 && data.assigned > 0 && data.this_vendor == 1) {
								$('#errordiv').show();
								$('#errordiv').text('Vehicle with these details already exist. This Vendor is already assigned');
								$('#vhc_detail').hide();
								$('#btnVerify').show();
							}

						}
					});
				} else {
					$('#errordiv').show();
					$('#errordiv').text('Vehicle number are mandatory');
				}
				event.preventDefault();
			}
			function fillvendorlist() {
				if ($('#<?= CHtml::activeId($model, "vhc_id") ?>').val() != '') {
					var vhcid = $('#<?= CHtml::activeId($model, "vhc_id") ?>').val();
					var href = '<?= Yii::app()->createUrl("admin/vehicle/loadvendorlist"); ?>';

					$.ajax({
						"url": href,
						"type": "GET",
						"dataType": "html",
						"data": {"vhcid": vhcid},
						"success": function (data) {
							$("#vndlist").html(data);

						}
					});

				}
			}

			function populateSource(obj, cityId)
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


			function filldetails(vhcid)
			{
				var href = '<?= Yii::app()->createUrl("admin/vehicle/loadvehicle"); ?>';
				if (vhcid != '') {
					$.ajax({
						"url": href,
						"type": "GET",
						"dataType": "json",
						"data": {"vhcid": vhcid},
						"success": function (data) {



							$.each(data, function (key, value1) {
								//alert(key + ':' + value1);
								if (key == 'drv_id' && $('#<?= CHtml::activeId($model, "vhc_id") ?>').val() == '') {
									$('#<?= CHtml::activeId($model, "vhc_id") ?>').val(value1);
								} else if (key == 'vhc_vendor_id1' && $('#<?= CHtml::activeId($model, "vhc_vendor_id1") ?>').val() == '') {
									$('#<?= CHtml::activeId($model, "vhc_vendor_id1") ?>').select2('val', value1);
								} else if (key == 'vhc_type_id') {
									$('#<?= CHtml::activeId($model, "vhc_type_id") ?>').select2('val', value1);
								} else {
									$('#Vehicles_' + key).val(value1);
								}
							});
							fillvendorlist();
						}
					});
				}

			}


		</script>