<style type="text/css">
    .picform div.row{
        background-color: #EEEEFE;
        padding-top:3px;
        padding-bottom: 3px
    }
    .modal-header{
        padding:10px;
    }
</style>
<?php
/* @var $vmodel VehicleDocs  */

$model		 = Vehicles::model()->resetScope()->findByPk($vmodel->vhd_vhc_id);
$vtypeList	 = VehicleTypes::model()->getParentVehicleTypes(2);
$vTypeData	 = VehicleTypes::model()->getJSON($vtypeList);
?>
<div class="panel">
    <div class="panel-body p0">
        <div class="col-xs-12 mt20">
            <div class="row">
                <div class="col-xs-12 col-md-7 pl0 text-center">
                    <div class="col-xs-12">
                        <div class="row">
							<?php
							$picfile	 = VehicleDocs::getDocPathById($vmodel->vhd_id);
							if ($vmodel->vhd_s3_data == '')
							{
								$picfile = VehicleDocs::getDocPathById($vmodel->vhd_id) . "?v=" . time();
							}
							$filePdf	 = '<a href="' . $picfile . '"  target="_blank"> <img src="/images/pdf.jpg"  height="100%"><br>Click to see file</a>';
							$fileImage	 = '<a href="' . $picfile . '"  target="_blank" id="vhdimage"> <img src="' . $picfile . '"  width="100%"></a>';
							echo (pathinfo($vmodel->vhd_file, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
							if ($rcback != "")
							{
								$picfile2 = VehicleDocs::getDocPathById($rcback);
								if ($vmodel->vhd_s3_data == '')
								{
									$picfile2 = VehicleDocs::getDocPathById($rcback) . "?v=" . time();
								}
								echo '<p></p><p><a href="' . $picfile2 . '"  target="_blank" id="vhdimage"> <img src="' . $picfile2 . '"  width="100%"></a></p>';
							}
							?>
							<p><a href="<?= Yii::app()->request->baseUrl . 'add?veditid=' . $vmodel->vhd_vhc_id; ?>" target="_blank">Go to vehicle edit page</a></p>
                        </div>
                    </div>
					<?php
					if (pathinfo($vmodel->vhd_file, PATHINFO_EXTENSION) != 'pdf' && $boost != 1)
					{
						?>
						<div class="col-xs-12 mt10">
							<div class="row">
								<a class="btn btn-primary" class ="rotate" id="rtleft" val="<?= $vmodel->vhd_id ?>">Rotate <i class="fa fa-rotate-270 fa-rotate-left"></i></a>
								<a class="btn btn-primary" class ="rotate" id="rtright" val="<?= $vmodel->vhd_id ?>">Rotate <i class="fa fa-rotate-90 fa-rotate-right "></i></a>
							</div>
						</div>
					<?php } ?>
				</div>
				<div class="col-xs-12 col-md-5 ">
					<?php
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'verify-form', 'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => false,
							'errorCssClass'		 => 'has-error',
							'afterValidate'		 => 'js:function(form,data,hasError){
				if(!hasError){

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
							'class' => '',
						),
					));
					/* @var $form TbActiveForm */
					?>                          
					<?= $form->hiddenField($vmodel, 'vhd_id') ?>
					<?= $form->hiddenField($vmodel, 'vhd_type') ?>
					<?php //print_r($vmodel);?>
					<div class="picform">
						<div class="row mb5">
							<div class="col-xs-5">Document Type : </div>
							<div class="col-xs-7 bold"><?= $vmodel->getDocTypeText() ?></div>
						</div>
						<div class="row mb5">
							<div class="col-xs-5">Code : </div>
							<div class="col-xs-7"><?= $model->vhc_code ?></div>
						</div>
						<div class="row mb5">      
							<div class="col-xs-5">Added by : </div>
							<div class="col-xs-7 bold"><?= $model->vendorVehicles[0]->vvhcVnd->vnd_name ?></div>
						</div>
						<div class="row mb5">
							<div class="col-xs-5">Number : </div>
							<div class="col-xs-7">
								<?= $form->textFieldGroup($model, 'vhc_number', array('label' => false, 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Vehicle Number']))) ?>
							</div>
						</div>
						<?php //if($model->vhc_type_id!=5){  ?>
						<div class="row mb5">
							<div class="col-xs-5">Cab Type: </div>
							<?php // $cabType = SvcClassVhcCat::model()->getVctSvcList('string', '', $model->vhcType->vht_VcvCatVhcType->vcv_vct_id)  ?>
							<?php
							$cabTypeArr	 = VehicleCategory::model()->getTypeClassbyid($model->vhc_id);
							$cabType	 = $cabTypeArr['vct_label'] . ' (' . $cabTypeArr['scc_label'] . ')';
							?>
							<div class="col-xs-7"><?= (($model->vhcType && $model) ? $cabType : ''); ?></div>
						</div>
						<?php //}   ?>

						<div class="row mb5">
							<div class="col-xs-5">Car Model :</div>
							<div class="col-xs-7">
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
							</div>
						</div>
						<div class="row mb5">
							<div class="col-xs-5">Manufacture Year : </div>
							<div class="col-xs-7">
								<?= $form->numberFieldGroup($model, 'vhc_year', array('label' => false, 'widgetOptions' => array('htmlOptions' => array('min' => date('Y') - 25, 'max' => date('Y'))))); ?>
							</div>
						</div>
						<div class="row mb5">
							<div class="col-xs-5">Color : </div>
							<div class="col-xs-7">
								<?= $form->textFieldGroup($model, 'vhc_color', array('label' => false, 'widgetOptions' => array())) ?>
							</div>
						</div>
						<div class="row mb5">
							<div class="col-xs-5">Seating Capacity : </div>
							<div class="col-xs-7"><?= $model->vhcType->vht_capacity; ?></div>
						</div>
						<div class="row">
							<div class="col-xs-5">Luggage Capacity : </div>
							<div class="col-xs-7"><?= $model->vhcType->vht_bag_capacity; ?> </div>
						</div>
					</div>
					<?php
					if ($boost == 0)
					{
						?>
						<div class="row bg-gray pt10 mt10">
							<?
							if ($vmodel->vhd_type == 1)
							{
							?>
							<div class="col-xs-5 ">Insurance Exp Date : </div>
							<div class="col-xs-7">
								<? $insDate = DateTimeFormat::DateToDatePicker($model->vhc_insurance_exp_date); ?>
								<?=
								$form->datePickerGroup($model, 'vhc_insurance_exp_date', array('label'			 => '',
									'widgetOptions'	 => array('options'		 => array('autoclose'	 => true,
											'format'	 => 'dd/mm/yyyy'),
										'htmlOptions'	 => array(
											'placeholder'	 => 'Insurance Exp Date',
											'value'			 => $insDate,
											'class'			 => 'input-group border-gray full-width')),
									'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?> 
							</div>
							<? } ?>
							<?
							if ($vmodel->vhd_type == 4)
							{
							?>

							<div class="col-xs-5">PUC Exp Date : </div>
							<div class="col-xs-7">
								<? $pollutionExpDate = DateTimeFormat::DateToDatePicker($model->vhc_pollution_exp_date); ?>
								<?=
								$form->datePickerGroup($model, 'vhc_insurance_exp_date', array('label'			 => '',
									'widgetOptions'	 => array('options'		 => array('autoclose'	 => true,
											'format'	 => 'dd/mm/yyyy'),
										'htmlOptions'	 => array(
											'placeholder'	 => 'PUC Exp Date',
											'value'			 => $pollutionExpDate,
											'class'			 => 'input-group border-gray full-width')),
									'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?> 
							</div>
							<? } ?>
							<?
							if ($vmodel->vhd_type == 5)
							{
							?>

							<div class="col-xs-5">Registration Exp Date : </div>
							<div class="col-xs-7">
								<? $regExpDate = DateTimeFormat::DateToDatePicker($model->vhc_reg_exp_date); ?>
								<?=
								$form->datePickerGroup($model, 'vhc_reg_exp_date', array('label'			 => '',
									'widgetOptions'	 => array('options'		 => array('autoclose'	 => true,
											'format'	 => 'dd/mm/yyyy'),
										'htmlOptions'	 => array(
											'placeholder'	 => 'Registration Exp Date',
											'value'			 => $regExpDate,
											'class'			 => 'input-group border-gray full-width')),
									'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?> 
							</div>

							<div class="col-xs-5">&nbsp;</div>
							<div class="col-xs-7">
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

							<div class="col-xs-5">Select Trip Types : </div>
							<div class="col-xs-7">
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'vhc_trip_type',
									'val'			 => explode(',', $model->vhc_trip_type),
									'data'			 => Vehicles::getTripType(),
									'htmlOptions'	 => array
										(
										'multiple'		 => 'multiple',
										'placeholder'	 => 'Select Cab Types',
										'width'			 => '100%',
										'style'			 => 'width:100%',
									),
								));
								?>
							</div>
							<div class="col-xs-12">&nbsp;</div>
							<div class="col-xs-5">Vehicle Owner First Name : </div>
							<div class="col-xs-7"><?= $form->textFieldGroup($model, 'vhc_reg_owner', array('label' => false, 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Owner First Name']))) ?></div>
							<div class="col-xs-5">Vehicle Owner Last Name : </div>
							<div class="col-xs-7"><?= $form->textFieldGroup($model, 'vhc_reg_owner_lname', array('label' => false, 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Owner Last Name']))) ?></div>

							<div class="col-xs-5">&nbsp;</div>
							<div class="col-xs-7">
								<?php
								if ($model->vhc_has_cng == 1)
								{
									$is_cng = true;
								}
								else
								{
									$is_cng = false;
								}
								?>  
								<?= $form->checkboxListGroup($model, 'vhc_has_cng', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Is CNG'), 'htmlOptions' => ['checked' => $is_cng]), 'inline' => true)) ?>
							</div>

							<div class="col-xs-12"><?= $form->radioButtonListGroup($model, 'vhc_owned_or_rented', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Owned by Me', 2 => 'Operated by Me')), 'inline' => true)) ?></div>

							<div class="col-xs-5">Date of Purchase</div>
							<div class="col-xs-7">
								<?php
								if ($model->vhc_dop)
								{
									$model->vhc_dop = DateTimeFormat::DateTimeToDatePicker($model->vhc_dop);
								}
								echo $form->datePickerGroup($model, 'vhc_dop', array('label'			 => '',
									'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
								));
								?> 
							</div>
							<? } ?>
							<?
							if ($vmodel->vhd_type == 6)
							{
							?>
							<div class="col-xs-5">Commercial Permits Exp Date : </div>
							<div class="col-xs-7">
								<? $commExpDate = ($model->vhc_commercial_exp_date) ? DateTimeFormat::DateToDatePicker($model->vhc_commercial_exp_date) : ''; ?>

								<?=
								$form->datePickerGroup($model, 'vhc_commercial_exp_date', array('label'			 => '',
									'widgetOptions'	 => array('options'		 => array('autoclose'	 => true,
											'format'	 => 'dd/mm/yyyy'),
										'htmlOptions'	 => array(
											'placeholder'	 => 'Commercial Permits Exp Date',
											'value'			 => $commExpDate,
											'class'			 => 'input-group border-gray full-width')),
									'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?> 
							</div>

							<? } ?>
							<?
							if ($vmodel->vhd_type == 7)
							{
							?>
							<div class="col-xs-5">Fitness Certificate Exp Date : </div>
							<div class="col-xs-7">
								<? $fitnessExpDate = ($model->vhc_fitness_cert_end_date) ? DateTimeFormat::DateToDatePicker($model->vhc_fitness_cert_end_date) : ''; ?>
								<?=
								$form->datePickerGroup($model, 'vhc_fitness_cert_end_date', array('label'			 => '',
									'widgetOptions'	 => array('options'		 => array('autoclose'	 => true,
											'format'	 => 'dd/mm/yyyy'),
										'htmlOptions'	 => array(
											'placeholder'	 => 'Fitness Certificate Exp Date',
											'value'			 => $fitnessExpDate,
											'class'			 => 'input-group border-gray full-width')),
									'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?> 
							</div>

							<? } ?>
						</div>
					<?php } ?>
					<?php
					if ($vmodel->getDocTypeText() == "Insurance" || $vmodel->getDocTypeText() == "Registration Certificate")
					{
						?>
						<div class="row">
							<div class="col-xs-12  pt10  bg-gray">

								<div class="row">
									<div class="col-xs-5"></div>
									<div class="col-xs-7">									
										<?php
										/* if ($vmodel->vhd_temp_approved == 1)
										  {
										  $is_approved = true;
										  }
										  else
										  {
										  $is_approved = false;
										  } */
										?>  
										<? //= $form->checkboxGroup($vmodel, 'vhd_temp_approved', array('label' => 'Temporary Approvred', 'widgetOptions' => array('data' => array(1 => 'Temporary Approvred'), 'htmlOptions' => ['checked' => $is_approved]), 'inline' => true))  ?>

										<? //$form->checkboxGroup($vmodel, 'vhd_temp_approved', []) ?>									
										<?php
										if ($vmodel->vhd_temp_approved == 1)
										{
											?>
											<span id="insurance" class="label label-info" style="display:block;float:right;">Temporary Approved</span>									
										<?php } ?>								

									</div>
								</div>
							</div>
						</div>
					<?php } ?>




					<?php
					if ($boost == 0)
					{
						?>
						<div class="row">
							<div class="col-xs-12  pt10  bg-gray">

								<div class="row">
									<div class="col-xs-12"> 
										<?= $form->textAreaGroup($vmodel, 'vhd_remarks') ?>
									</div>
								</div>

								<div class="row text-center mb5">
									<a class="btn btn-primary btn-xs pl5 pr5" id="btnAppr" name="btnAppr">Approve</a>
									<a class="btn btn-danger btn-xs pl5 pr5" id="btnDspr" name="btnDspr">Disapprove</a>
								</div>

							</div>
						</div> 
					<?php } ?>					
					<?php $this->endWidget(); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var remarks = '';
	$('#btnAppr').click(function (e) {
		remarks = $('#VehicleDocs_vhd_remarks').val();
		vhdid = $('#VehicleDocs_vhd_id').val();
		vhdType = $('#VehicleDocs_vhd_type').val();

		if ($('#Vehicles_vhc_number').val().trim() == '') {
			alert('Vehicles number is mandatory');
		} else if ($('#Vehicles_vhc_year').val().trim() == '') {
			alert('Vehicles Manufacture Year is mandatory');
		} else if ($('#Vehicles_vhc_color').val().trim() == '') {
			alert('Vehicles Color is mandatory');
		} else if ($('#Vehicles_vhc_type_id').val().trim() == '' || $('#Vehicles_vhc_type_id').val() <= 0) {
			alert('Vehicles Model is mandatory');
		} else {
			$.ajax({
				"type": "POST",
				"dataType": "json",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vehicle/approvedocimg', ['btntype' => 'approve'])) ?>",
				"data": $('#verify-form').serialize(),
				//  data: //{"btntype": 'approve', 'remarks': remarks, 'vhdid': vhdid},
				"success": function (data1) {
					if (data1.success) {
						refreshApprovalList();

						return false;
					} else {
						var strErrors = '';
						var errors = data1.errors;
						$.each(errors, function (i, val) {
							strErrors += val + '\n';
						});
						alert(strErrors);
					}
				}
			});
		}

		e.preventDefault();
		return false;
	});
	$('#btnDspr').click(function () {
		remarks = $('#VehicleDocs_vhd_remarks').val();
		// vhdid = $('#VehicleDocs_vhd_id').val();
		if (remarks.trim() != '') {
			$.ajax({
				"type": "POST",
				"dataType": "json",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vehicle/approvedocimg', ['btntype' => 'problem'])) ?>",
				// data: {"btntype": 'problem', 'remarks': remarks, 'vhdid': vhdid},
				"data": $('#verify-form').serialize(),
				"success": function (data1) {
					if (data1.success) {
						refreshApprovalList();

						return false;

					} else {
						alert(data1.errors);
					}
				}
			});
		} else {
			$('#VehicleDocs_vhd_remarks_em_').text('Remarks is required');
			$('#VehicleDocs_vhd_remarks_em_').addClass('text-danger');
			$('#VehicleDocs_vhd_remarks_em_').show();
		}
	});
	cnt = 0;
	$('#rtleft').click(function () {
		imgRotate('left');
	});
	$('#rtright').click(function () {
		imgRotate('right');
	});
	function imgRotate(rttype) {
		//alert($('#rotate').attr('val'));
		vhdid = '<?= $vmodel->vhd_id ?>';

		$.ajax({
			"type": "GET",
			"dataType": "json",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vehicle/imagerotate')) ?>",
			data: {'vhdid': vhdid, 'rttype': rttype},
			//"data": $('#verify-form').serialize(),
			"success": function (data1) {
				if (data1.success) {
					cnt++;
					var str = '<img src="' + data1.imagefile + '?v' + cnt + '"  width="100%">';
					$("#vhdimage").html(str);
				}
			}
		});
	}
</script>