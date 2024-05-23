<style type="text/css">
    .selectize-input {
        min-width: 0px !important;
        width: 100% !important;
    }
    .bordered {
        border:1px solid #ddd;
        min-height: 45px;
        line-height: 1.1;

    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
    .new-booking-list label{ font-size: 11px;}
</style>
<?php
/* @var $model Drivers */
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
//$cityList			 = CHtml::listData(Cities::model()->findAll(array('order' => 'cty_name', 'condition' => 'cty_active=:act', 'params' => array(':act' => '1'))), 'cty_id', 'cty_name');
//$stateList			 = array("" => "Select state") + CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
//$licenseIssueList	 = array("" => "Select state") + CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_name', 'stt_name');
$mergeBorder		 = ($model->drv_id_merge != '') ? "bordered mb10" : '';
$mergeshow			 = ($model->drv_id_merge != '') ? '' : "hide";
$readOnly			 = array();
$readOnly1			 = array('label' => '', 'class' => 'form-control', 'placeholder' => 'Contact Number');
$readOnly2			 = "";

if ($model->drv_id != '')
{
	$email	 = ContactEmail::model()->getContactEmailById($model->drv_contact_id);
	$phone	 = ContactPhone::model()->getContactPhoneById($model->drv_contact_id);
	$license = $model->drvContact->ctt_license_no;
}

if ($model->isNewRecord)
{
	$title	 = "Add";
//CONFIRM
	$js		 = "if($.isFunction(window.refreshDriver)){
        window.refreshDriver();
        } else {
        window.location.reload();
        }";
}
//UPDATE
else
{
	$title		 = "Edit";
	$readOnly	 = array('htmlOptions' => array('readOnly' => 'readOnly'));
	if (isset($model->drv_phone) && $model->drv_phone != '')
	{
		$readOnly1 = array('label' => '', 'class' => 'form-control', 'placeholder' => 'Contact Number', 'readOnly' => 'true');
	}
	$readOnly2	 = 'pointer-events: none';
	$js			 = "	if($.isFunction(window.refreshDriver)){
        window.refreshDriver();
        } else {
        alert('updated');
        }";
}
$title		 = ($model->drv_id_merge != '') ? 'Merge' : $title;
$ajax		 = Yii::app()->request->isAjaxRequest;
$hideAjax	 = '';
if (Yii::app()->request->isAjaxRequest)
{
	$panelCss	 = "col-xs-12 ";
	$hideAjax	 = 'hide';
	$isNew		 = true;
}
else
{
	$panelCss = "col-lg-8 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12";
}
$displayBlock	 = ($isNew) ? 'none' : 'block';
$displayBtn		 = ($isNew) ? 'block' : 'none';


$aadharApproveStyle	 = ($aadharDoc != '' && $aadharStatus == 0) ? "display:block;" : "display:none;";
$aadharRejectStyle	 = ($aadharDoc != '' && $aadharStatus < 2) ? "display:block;" : "display:none;";
$aadharReloadStyle	 = ($aadharDoc != '' && $aadharStatus == 2) ? "display:block;" : "display:none;";
if ($aadharDoc != '')
{
	if ($aadharStatus == 0)
	{
		$aadhar = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($aadharStatus == 1)
	{
		$aadhar = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($aadharStatus == 2)
	{
		$aadhar = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$aadhar = '';
}

$aadharApproveStyle2 = ($aadharBackDoc != '' && $aadharBackStatus == 0) ? "display:block;" : "display:none;";
$aadharRejectStyle2	 = ($aadharBackDoc != '' && $aadharBackStatus < 2) ? "display:block;" : "display:none;";
$aadharReloadStyle2	 = ($aadharBackDoc != '' && $aadharBackStatus == 2) ? "display:block;" : "display:none;";
if ($aadharBackDoc != '')
{
	if ($aadharBackStatus == 0)
	{
		$aadharBack = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($aadharBackStatus == 1)
	{
		$aadharBack = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($aadharBackStatus == 2)
	{
		$aadharBack = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$aadharBack = '';
}

$panApproveStyle = ($panDoc != '' && $panStatus == 0) ? "display:block;" : "display:none;";
$panRejectStyle	 = ($panDoc != '' && $panStatus < 2) ? "display:block;" : "display:none;";
$panReloadStyle	 = ($panDoc != '' && $panStatus == 2) ? "display:block;" : "display:none;";
if ($panDoc != '')
{
	if ($panStatus == 0)
	{
		$pan = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($panStatus == 1)
	{
		$pan = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($panStatus == 2)
	{
		$pan = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$pan = '';
}

$panApproveStyle2	 = ($panBackDoc != '' && $panBackStatus == 0) ? "display:block;" : "display:none;";
$panRejectStyle2	 = ($panBackDoc != '' && $panBackStatus < 2) ? "display:block;" : "display:none;";
$panReloadStyle2	 = ($panBackDoc != '' && $panBackStatus == 2) ? "display:block;" : "display:none;";
if ($panBackDoc != '')
{
	if ($panBackStatus == 0)
	{
		$panBack = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($panBackStatus == 1)
	{
		$panBack = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($panBackStatus == 2)
	{
		$panBack = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$panBack = '';
}

$voterApproveStyle	 = ($voterDoc != '' && $voterStatus == 0) ? "display:block;" : "display:none;";
$voterRejectStyle	 = ($voterDoc != '' && $voterStatus < 2) ? "display:block;" : "display:none;";
$voterReloadStyle	 = ($voterDoc != '' && $voterStatus == 2) ? "display:block;" : "display:none;";
if ($voterDoc != '')
{
	if ($voterStatus == 0)
	{
		$voter = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($voterStatus == 1)
	{
		$voter = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($voterStatus == 2)
	{
		$voter = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$voter = '';
}

$voterApproveStyle2	 = ($voterBackDoc != '' && $voterBackStatus == 0) ? "display:block;" : "display:none;";
$voterRejectStyle2	 = ($voterBackDoc != '' && $voterBackStatus < 2) ? "display:block;" : "display:none;";
$voterReloadStyle2	 = ($voterBackDoc != '' && $voterBackStatus == 2) ? "display:block;" : "display:none;";
if ($voterBackDoc != '')
{
	if ($voterBackStatus == 0)
	{
		$voterBack = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($voterBackStatus == 1)
	{
		$voterBack = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($voterBackStatus == 2)
	{
		$voterBack = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$voterBack = '';
}

$dlApproveStyle	 = ($driverLicenseDoc != '' && $driverLicenseStatus == 0) ? "display:block;" : "display:none;";
$dlRejectStyle	 = ($driverLicenseDoc != '' && $driverLicenseStatus < 2) ? "display:block;" : "display:none;";
$dlReloadStyle	 = ($driverLicenseDoc != '' && $driverLicenseStatus == 2) ? "display:block;" : "display:none;";
if ($driverLicenseDoc != '')
{
	if ($driverLicenseStatus == 0)
	{
		$dlLabel = (($driverLicenseTempStatus == 1) ? 'Temporary Approved' : 'Not Approved');
		$dl		 = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => $dlLabel];
	}
	else if ($driverLicenseStatus == 1)
	{
		$dl = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($driverLicenseStatus == 2)
	{
		$dl = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$dl = '';
}

$pcApproveStyle	 = ($pcVerificationDoc != '' && $pcVerificationStatus == 0) ? "display:block;" : "display:none;";
$pcRejectStyle	 = ($pcVerificationDoc != '' && $pcVerificationStatus < 2) ? "display:block;" : "display:none;";
$pcReloadStyle	 = ($pcVerificationDoc != '' && $pcVerificationStatus == 2) ? "display:block;" : "display:none;";
if ($pcVerificationDoc != '')
{
	if ($pcVerificationStatus == 0)
	{
		$pc = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($pcVerificationStatus == 1)
	{
		$pc = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($pcVerificationStatus == 2)
	{
		$pc = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$pc = '';
}


$dlApproveStyle2 = ($driverLicenseDoc2 != '' && $driverLicenseStatus2 == 0) ? "display:block;" : "display:none;";
$dlRejectStyle2	 = ($driverLicenseDoc2 != '' && $driverLicenseStatus2 < 2) ? "display:block;" : "display:none;";
$dlReloadStyle2	 = ($driverLicenseDoc2 != '' && $driverLicenseStatus2 == 2) ? "display:block;" : "display:none;";
if ($driverLicenseDoc2 != '')
{
	if ($driverLicenseStatus2 == 0)
	{
		$dl2 = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	}
	else if ($driverLicenseStatus2 == 1)
	{
		$dl2 = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	}
	else if ($driverLicenseStatus2 == 2)
	{
		$dl2 = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	}
}
else
{
	$dl2 = '';
}
?>
<div class="row">
	<?php
	if (sizeof($errors) > 0)
	{
		foreach ($array as $values)
		{
			foreach ($values as $value)
			{
				?>
				<div class="col-xs-12 mb20" style="color:#F00;text-align: center">
					<?php echo 'Driver details update not success<br/>' . $value . "<br/>"; ?>
				</div>
				<?php
			}
		}
	}
	?>
</div>
<div class="row" id="errShow" >
	<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12" >
		<div class="col-xs-12 mb20" style="color:#008a00;text-align: center">
			<?php echo Yii::app()->user->getFlash('success'); ?>
		</div>
		<div class="col-xs-12 mb20" style="color:#F00;text-align: center">
			<?php echo Yii::app()->user->getFlash('error'); ?>
		</div>
	</div>    
</div>
<div class="row ">
	<div class="<?= $panelCss ?> new-booking-list pb10  " >

		<div style="text-align:center;" class="col-xs-12">
			<?php
			if ($status == "emlext")
			{
				echo "<span style='color:#ff0000;'>This email address is already registered. Please try again using a new email address.</span>";
			}
			elseif ($status == "added")
			{
				echo "<span style='color:#00aa00;'>Driver added successfully.</span>";
			}
			elseif ($status == "updated")
			{
				echo "<span style='color:#00aa00;'>Driver Modified Successfully.</span>";
			}
			else
			{
				//do nothing
			}
			?>
		</div>
		<div class="row">
			<div class="col-xs-8">
				<?php
				$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'driver-register-form',
					'enableClientValidation' => TRUE,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
						'afterValidate'		 => 'js:function(form,data,hasError)
                        {
                            var formData = new FormData(form[0]);
                            if(!hasError){
                                $.ajax({
                                "type":"POST",
                                "dataType":"json",
                                "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                                "data":formData,
                                        async: false,
                                        cache: false,
                                        contentType: false,
                                        processData: false,
								"beforeSend": function () 
								{
									ajaxindicatorstart("");
								},
								"complete": function () 
								{                     
									ajaxindicatorstop();
								},		
                            "success":function(data1){ 
                                if($.isEmptyObject(data1))
                                {
                                    ' . $js . '
                                }
                                if(data1.success)
                                {
                                    location.href="' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/driver/list')) . '";
                                        
                                }
                                else
                                {
                                    var errors = data1.error;
                                   // alert(errors);
                                   // alert(data1.errorcontact.Contact_ctt_id);
                                    var myJSON = JSON.stringify(data1);
                                   // alert(myJSON);
                                   // var errorscontact = data1.errorcontact;
                                   // alert(errorscontact);
                                    settings=form.data(\'settings\');
                                    $.each (settings.attributes, function (i) {
											try
											{
                                            $.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
                                        }
											catch(e)
											{
                                        }
                                    });
                                    $.fn.yiiactiveform.updateSummary(form, errors);
                                    $("#errordivcustom").show();
                                    //$("#errordivcustom").html(data1.errorcontact.Contact_ctt_id);
                                    $("#errordivcustom").html(errors.Drivers_drv_contact_id);
                                }},
                            });
                            
                            }
                        }'
					),
					// Please note: When you enable ajax validation, make sure the corresponding
					// controller action is handling ajax validation correctly.
					// See class documentation of CActiveForm for details on this,
					// you need to use the performAjaxValidation()-method described there.
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class'		 => 'form-horizontal',
						'enctype'	 => 'multipart/form-data',
					),
				));
				/* @var $form TbActiveForm */
				?>

				<div class="panel panel-default panel-border">
					<div class="panel-body">
						<? //= $form->hiddenField($model, 'drv_id')  ?>

						<?= $form->hiddenField($model, 'drv_id_merge') ?>
						<?= $form->hiddenField($model, 'drv_type', array('value' => '3')); ?>

						<?php echo CHtml::errorSummary($model); ?>
						<div class="text-danger" id="errordivcustom" style="display: none"></div>
						<div class="col-xs-12">
							<div class="text-danger" id="errordiv" style="display: none"></div>

							<div class="row">
								<div class="col-xs-12 col-sm-6"> 
									<div class="form-group">
										<label class="control-label" for="Drivers_drv_vendor_id1">Select Vendor</label>
										<?php
										if ($_REQUEST['vndid'] != '')
										{
											$model->drv_vendor_id1 = $_REQUEST['vndid'];
										}

										$this->widget('ext.yii-selectize.YiiSelectize', array(
											'model'				 => $model,
											'attribute'			 => 'drv_vendor_id1',
											'useWithBootstrap'	 => true,
											"placeholder"		 => "Select Vendor",
											'fullWidth'			 => false,
											'htmlOptions'		 => array('width' => '100%', 'readonly' => true),
											'defaultOptions'	 => $selectizeOptions + array(
										'onInitialize'	 => "js:function(){
                                  populateVendor(this, '{$model->drv_vendor_id1}');
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
										<span class="has-error"><? echo $form->error($model, 'drv_vendor_id1'); ?></span>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6  <?= $mergeBorder ?>"> 
									<?= $form->textFieldGroup($model, 'drv_name',array('label' => 'Name ( * )', 'widgetOptions' => array('htmlOptions' => array('value' => $model->drv_contact_name)))) ?>
								</div>
                                <input type ="hidden" value ="" id ="Drivers_drv_id" name ="drvid">
							</div>

							<div class="row" id="btnVerify" style="display:<?= $displayBtn ?>; text-align: center">
								<div class="col-xs-12 pl1 " >
									<button type="button" class="btn btn-primary" onclick="verifyDriver()">Proceed</button>
								</div>
							</div>

							<div class="" id="dvr_detail" style="display: <?= $displayBlock ?>">
								<div class="<?= $hideAjax ?>">

									<div class="row" id="contactSelectDetails">
										<div class="col-xs-12 contact_div_details"> <label>Contact Info</label></div>
										<?
										echo $form->hiddenField($model, 'drv_contact_id');
										echo $form->hiddenField($model, 'drv_contact_name');
										?>
										<div class="col-xs-12 col-sm-6 contact_div_details hide" style="background-color: lightgray;height:60px;padding-top:15px;" >

											<? //= $form->textFieldGroup($model, 'vnd_contact_name', array('label' => '','widgetOptions' => array('htmlOptions' => array('placeholder' => 'Contact Name','readonly' => 'readonly')))) ?>
											<label id="contactDetails"></label>
										</div>
										<?
										// if ($isNew != 'Approve')
//										{
										?> 
										<div class="col-xs-4 col-sm-3 viewcontctsearch hide" style="<?= $contactViewSearch; ?>;">
											<label>&nbsp;</label>
											<div>
												<button class="btn btn-info viewContact" type="button">View Contact</button></div>
										</div>

										<?php
										if ($model->drv_id != "")
										{
											?>

											<div class="col-xs-4 col-sm-3">
												<label>&nbsp;</label>
												<div>
													<a class="btn btn-info modifyContact" target="_blank" href="<?= Yii::app()->createUrl('admin/contact/form', array('drvctttype' => '1', 'type' => '1', 'ctt_id' => $model->drv_contact_id)) ?>" >Modify Contact</a></div>
											</div>
										<?php } ?>



										<div class="col-xs-4 col-sm-3 ">
											<label>&nbsp;</label>
											<div><button class="btn btn-info searchContact" type="button">Select Contact</button></div>
										</div>
										<div class="col-xs-4 col-sm-3 ">
											<label>&nbsp;</label>
											<div> 
												<a class="btn btn-primary  weight400 font-bold addContact" title="Add Contact">Add Contact</a>
											</div>
										</div>
										<?
//										}
//										else
//										{
										?>
										<!--											<div class="col-xs-4 col-sm-3">
																						<label>&nbsp;</label>
																						<div>
																							<a class="btn btn-info modifyContact" target="_blank" href="<? //= Yii::app()->createUrl('admin/contact/add', array('ctt_id' => $model->drv_contact_id))   ?>" >Modify Contact</a></div>
																					</div>-->
										<?php // }  ?>
									</div>



									<div class = "row">

										<div class="col-xs-12 col-sm-6 <?= $mergeBorder ?>"> 
											<label>Approved for following trip types *</label>
											<?php
											$this->widget('booster.widgets.TbSelect2', array(
												'model'			 => $model,
												'attribute'		 => 'drv_trip_type',
												'val'			 => explode(',', $model->drv_trip_type),
												'data'			 => Drivers::getTripType(),
												'htmlOptions'	 => array(
													'multiple'		 => 'multiple',
													'placeholder'	 => 'Approved for following trip types',
													'width'			 => '100%',
													'style'			 => 'width:100%',
												),
											));
											?>
											<span class="has-error"><? echo $form->error($model, 'drv_trip_type'); ?></span>						
										</div>
									</div>

									<div class="<?= $hideAjax ?>">
										<div class="row">
											<div class="col-xs-6 pl0 ">
												<?= $form->checkboxGroup($model, 'drv_is_uber_approved', ['label' => "Uber approved", 'groupOptions' => ['style' => 'margin:0px;padding:0px;', "class" => "checkbox-inline"]]) ?>
											</div>
										</div>


										<div class="row">
											<div class="col-xs-12 col-sm-6"> 
												<?php
												if ($model->drv_dob_date)
												{
													$model->drv_dob_date = DateTimeFormat::DateToDatePicker($model->drv_dob_date);
												}
												echo $form->datePickerGroup($model, 'drv_dob_date', array('label'			 => 'Date of Birth',
													'widgetOptions'	 => array('options' => array('autoclose' => true, 'format' => 'dd/mm/yyyy'))
												));
												?>


											</div>
											<div class="col-xs-12 col-sm-6"> 
												<?php
												if ($model->drv_doj && $model->drv_doj != '1970-01-01')
												{
													$model->drv_doj = DateTimeFormat::DateToDatePicker($model->drv_doj);
												}
												else
												{
													$model->drv_doj = '';
												}
												echo $form->datePickerGroup($model, 'drv_doj', array('label'			 => 'Date of Joining',
													'widgetOptions'	 => array('options' => array('autoclose' => true, 'endDate' => '+1d', 'format' => 'dd/mm/yyyy'))
												));
												?>

											</div> 
										</div>

										<div class="row">
											<div class="col-xs-12 col-sm-6 <?= $mergeBorder ?>"> 
												<?= $form->textFieldGroup($model, 'drv_zip') ?>
											</div>
											<div class="col-xs-12 col-sm-6"> 
												<?=
												$form->select2Group($model, 'assigned_vhc_id', array(
													'widgetOptions'	 => array('data'			 => $vehicleList, 'htmlOptions'	 => array('multiple' => 'multiple', 'placeholder' => "Assign vehicle", "style" => "width:100%", 'value' => $model->assigned_vhc_id, 'title' => "Route To"),
													), 'options'		 => array('multiple' => true),
												));
												?>

											</div>
										</div>
									</div>

									<div class="row">&nbsp;</div>


									<div class="row" style="text-align: center" id="DivDriverSubmit">
										<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary', 'name' => 'driversubmit')); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php $this->endWidget(); ?>
				</div>
			</div> 
			<div class="col-lg-4 col-md-3 col-sm-10 col-md-offset-0 col-sm-offset-1 col-xs-12 pb10 border border-radius" >
				<div class="row" id='vndlist'>

				</div>
			</div>
		</div>	
	</div>
	<?php echo CHtml::endForm(); ?>


</div>
<script type="text/javascript">
    $('input[name="driversubmit"]').click(function () {
        // alert("The paragraph was clicked.");
    });

    $(document).ready(function () {

        $("#contactDetails").html('<?= $model->drv_contact_name . ' | ' . $email . ' | ' . $phone . ' | <b>License:</b>&nbsp;' . $license ?>');
        $('#Drivers_drv_contact_id').val('<?= $model->drv_contact_id ?>');
        $('#Drivers_drv_contact_name').val('<?= $model->drv_contact_name ?>');
        //$(".contact_div_details").removeClass('hide');
        //$(".viewcontctsearch").removeClass('hide');


        //$('#Drivers_drv_phone').mask('9999999999');
        //var availableTags = [];
        //var front_end_height = $(window).height();
        // var footer_height = $(".footer").height();
        //var header_height = $(".header").height();

        fillvendorlist();
    });


    // $drv_city = <? //= ($model->drv_city == '') ?  0 : $model->drv_city   ?>;

//									    function getCityList(stateId) {
//									        var href2 = '<? //= Yii::app()->createUrl("vendor/vehicle/cityfromstate1");   ?>';
//									        $.ajax({
//									            "url": href2,
//									            "type": "GET",
//									            "dataType": "json",
//									            "data": {"id": stateId},
//									            "success": function (data1) {
//									                $data2 = data1;
//									                var placeholder = $('#<? //= CHtml::activeId($model, "drv_city")   ?>').attr('placeholder');
//									                $('#<? //= CHtml::activeId($model, "drv_city")   ?>').select2({data: $data2, placeholder: placeholder});
//									                $('#<? //= CHtml::activeId($model, "drv_city")   ?>').select2("val", $drv_city);
//									            }
//									        });
//									    }

    $drv_city = <?= ($model->drv_city == '') ? 0 : $model->drv_city ?>;

    function getCityList(stateId) {
        var href2 = '<?= Yii::app()->createUrl("vendor/vehicle/cityfromstate1"); ?>';
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "json",
            "data": {"id": stateId},
            "success": function (data1) {
                $data2 = data1;
                var placeholder = $('#<?= CHtml::activeId($model, "drv_city") ?>').attr('placeholder');
                $('#<?= CHtml::activeId($model, "drv_city") ?>').select2({data: $data2, placeholder: placeholder});
                $('#<?= CHtml::activeId($model, "drv_city") ?>').select2("val", $drv_city);
            }
        });
    }

    $("#Drivers_assigned_vhc_id1").change(function () {
        var vhcid = $("#Drivers_assigned_vhc_id1").val();
        var drvid = '<?= $model->drv_id; ?>';
        var href2 = '<?= Yii::app()->createUrl("admin/driver/checkvehiclestatus"); ?>';
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "json",
            "data": {"vhcid": vhcid, "drvid": drvid},
            "success": function (data) {
                if (data) {
                    if (confirm("The vehicle is allocated to other driver. Do you want to assign the vehicle to this driver?") == true) {

                    } else {
                        $("#Drivers_assigned_vhc_id").val('');
                    }
                }

            }
        });
    });
    function verifyDriver() {
        $('#errordiv').hide();
        var vndid = $('#<?= CHtml::activeId($model, "drv_vendor_id1") ?>').val();
        var drvname = $('#<?= CHtml::activeId($model, "drv_name") ?>').val();
        // alert(vndid);alert(drvname);
        if (drvname == '') {
            $('#errordiv').show();
            $('#errordiv').text('Name, phone number and driver licence are mandatory');
            return false;
        }
        if (vndid == '') {
            $('#errordiv').show();
            $('#errordiv').text('Please select vendor.');
            return false;
        }
        $('#dvr_detail').show();
        $('#btnVerify').hide();

        event.preventDefault();
    }

    function fillvendorlist() {
        if ($('#<?= CHtml::activeId($model, "drv_id") ?>').val() != '') {
            var dvid = $('#<?= CHtml::activeId($model, "drv_id") ?>').val();
            var href = '<?= Yii::app()->createUrl("admin/driver/loadvendorlist"); ?>';

            $.ajax({
                "url": href,
                "type": "GET",
                "dataType": "html",
                "data": {"drvid": dvid},
                "success": function (data) {

                    $("#vndlist").html(data);

                }
            });

        }
    }

//									    function filldetails(drvid) {
//									        var href = '<? //= Yii::app()->createUrl("admin/driver/loaddriver");   ?>';
//									        if (drvid != '') {
//									            $.ajax({
//									                "url": href,
//									                "type": "GET",
//									                "dataType": "json",
//									                "data": {"drvid": drvid},
//									                "success": function (data) {
//
//
//
//									                    $.each(data, function (key, value1) {
//
//									                        if (key == 'drv_id' && $('#<? //= CHtml::activeId($model, "drv_id")   ?>').val() == '') {
//									                            $('#<? //= CHtml::activeId($model, "drv_id")   ?>').val(value1);
//
//									                        } else if (key == 'drv_vendor_id1' && $('#<? //= CHtml::activeId($model, "drv_vendor_id1")   ?>').val() == '') {
//									                            $('#<? //= CHtml::activeId($model, "drv_vendor_id1")   ?>').select2('val', value1);
//
//									                        } else {
//									                            $('#Drivers_' + key).val(value1);
//									                        }
//									                    });
//									                    fillvendorlist();
//									                }
//									            });
//									        }
//									    }

    if ('<?= Yii::app()->user->getFlash('success') ?>' == '' && '<?= Yii::app()->user->getFlash('error') ?>' == '') {

        $('#errShow').hide();
    } else {

        $('#errShow').show();
    }



    $('.viewContact').click(function ()
    {
        $href = '<?= Yii::app()->createUrl('admin/contact/form') ?>';
        var contid = $("#Drivers_drv_contact_id").val();
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"ctt_id": contid, "type": 4},
            success: function (data)
            {
                box = bootbox.dialog({
                    message: data,
                    title: 'Contact View',
                    size: 'large',
                    onEscape: function ()
                    {

// user pressed escape
                    },
                });
            }
        });
    });

    $('.searchContact').click(function ()
    {
        $href = '<?= Yii::app()->createUrl('admin/contact/list') ?>';
        var contype = 1;
        var vndid = $("#Drivers_drv_vendor_id1").val();
//alert(vndid);
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"ctype": contype, "vndtype": "asgncont", "userType": "Driver"},
            success: function (data)
            {
                box = bootbox.dialog({
                    message: data,
                    title: 'Contact List',
                    size: 'large',
                    onEscape: function ()
                    {
                        $('.bootbox.modal').modal('hide');
                    },
                });
            }
        });
    });

    $('.addContact').click(function ()
    {
        debugger
        $href = '<?= Yii::app()->createUrl('admin/contact/form') ?>';
        jQuery.ajax({type: 'GET', url: $href, data: {"type": 1},
            success: function (data)
            {
                box = bootbox.dialog({
                    message: data,
                    title: 'Add Contact',
                    size: 'large',
                    onEscape: function ()
                    {
                        $('.bootbox.modal').modal('hide');
                    },
                });
            }});
    });

<?php
if ($model->drv_id == "")
{
	?>
	    $('#Drivers_drv_contact_id').val('');
	    $('#Drivers_vnd_contact_name').val('');
	    $('#contactSelectDetails').removeClass('hide');
	    $('.searchContact ').removeClass('hide');
	    $('.contact_div_details').addClass('hide');
	    $(".viewcontctsearch").addClass('hide');
<?php
}
else
{
	?>
	    $('#contactSelectDetails').removeClass('hide');
	    //$('.searchContact ').hide();
	    $('.contact_div_details').removeClass('hide');
	    $(".viewcontctsearch").hide();
	    $(".addContact").hide();
<?php } ?>
</script>