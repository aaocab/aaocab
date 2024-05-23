<div class="panel "> 

    <div class="panel-body"> 
        <div class="mb20"><h3>Review and approve vehicle</h3></div>
        <div class="col-xs-12 mb20">
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Vehicle Number</span></div>
                <div class="col-xs-7"><?= $model->vhc_number ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Vehicle Model</span></div>
                <div class="col-xs-7"><?= $model->vhcType->vht_make . " " . $model->vhcType->vht_model ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Year</span></div>
                <div class="col-xs-7"><?= $model->vhc_year ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Color</span></div>
                <div class="col-xs-7"><?= $model->vhc_color ?></div>
            </div>  
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Tax Expiry Date</span></div>
                <div class="col-xs-7"><?= $model->vhc_tax_exp_date ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Vehicle owned or rented</span></div>
                <div class="col-xs-4"><?= $model->vhc_owned_or_rented == 1 ? 'Yes' : 'No'; ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Is exclusive to Gozo</span></div>
                <div class="col-xs-7"><?= $model->vhc_is_attached == 1 ? 'Yes' : 'No'; ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Is commercial</span></div>
                <div class="col-xs-7"><?= $modelVehicle->vhc_is_commercial == 1 ? 'Yes' : 'No'; ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Picture of front license plate</span></div>
                <div class="col-xs-7"><a href="<?= $model->vhc_front_plate ?>"  target="_blank"><?= basename($model->vhc_front_plate); ?></a></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Picture of rear license plate</span></div>
                <div class="col-xs-7"><a href="<?= $model->vhc_rear_plate ?>" target="_blank"><?= basename($model->vhc_rear_plate); ?></a></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Photo copy of valid insurance for the vehicle</span></div>
                <div class="col-xs-7"><a href="<?= $model->vhc_insurance_proof ?>"  target="_blank"><?= basename($model->vhc_insurance_proof); ?></a></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Insurance Expiry Date</span></div>
                <div class="col-xs-7"><?= $model->vhc_insurance_exp_date ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Photocopy of Pollution under control certificate</span></div>
                <div class="col-xs-7"><a href="<?= $model->vhc_pollution_certificate ?>"  target="_blank"><?= basename($model->vhc_pollution_certificate); ?></a></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Pollution under control certificate End Date</span></div>
                <div class="col-xs-7"><?= $model->vhc_pollution_exp_date; ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Photocopy of Registration certificate for the vehicle</span></div>
                <div class="col-xs-7"><a href="<?= $model->vhc_reg_certificate ?>"  target="_blank"><?= basename($model->vhc_reg_certificate); ?></a></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Registration End Date</span></div>
                <div class="col-xs-7"><?= $model->vhc_reg_exp_date; ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Photocopy of applicable commercial permits for the vehicle</span></div>
                <div class="col-xs-7"><a href="<?= $model->vhc_permits_certificate ?>" target="_blank"><?= basename($model->vhc_permits_certificate); ?></a></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">commercial permits end date</span></div>
                <div class="col-xs-7"><?= $model->vhc_commercial_exp_date; ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Photocopy of fitness certificate for the vehicle</span></div>
                <div class="col-xs-7"><a href="<?= $model->vhc_fitness_certificate ?>"  target="_blank"><?= basename($model->vhc_fitness_certificate); ?></a></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Fitness Certificate Expiry Date</span></div>
                <div class="col-xs-7"><?= $model->vhc_fitness_cert_end_date; ?></div>
            </div>  
        </div>  
        <div class="col-xs-12"> 
			<?php
			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'feedback-form',
				'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error',
					'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){
                        $.ajax({
                        "type":"POST",
                        "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/vehicle/approve', ['vhcid' => $model->vhc_vehicle_id])) . '",
                        "data":form.serialize(),
                        "dataType": "json",
                        "success":function(data1){
                        if(data1.success)
                        {
                        location.href="' . Yii::app()->createUrl('admin/vehicle/approvelist') . '";
                            }
                        else{
                        var errors = data1.errors;
                        settings=form.data(\'settings\');
                        $.each (settings.attributes, function (i) {
                        $.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
                        });
                        $.fn.yiiactiveform.updateSummary(form, errors);
                        }
                        },
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
					'class' => 'form-horizontal'
				),
			));
			?>

			<?= $form->hiddenField($model, 'vhc_id'); ?>
            <div class="col-xs-12 mt20"> <input class="mr5" type="checkbox" value="0" name="chk1" id="chk1" onclick="checkVerified();" <?= $modelVehicle->vhc_ver_number ? 'checked' : ''; ?>><span style="font-weight: bold">Verified vehicle number against Registration Certificate</span><span class="text-danger">*</span> </div>
            <div class="col-xs-12"> <input class="mr5"  type="checkbox" value="0" name="chk2" id="chk2" onclick="checkVerified();" <?= $modelVehicle->vhc_ver_model_year_color ? 'checked' : ''; ?>><span style="font-weight: bold">Model, year, color verified</span></div>
            <div class="col-xs-12">  <input class="mr5"  type="checkbox" value="0" name="chk3" id="chk3" onclick="checkVerified();" <?= $modelVehicle->vhc_ver_rc ? 'checked' : ''; ?>><span style="font-weight: bold">Verified Registration and end date</span><span class="text-danger">*</span></div>
            <div class="col-xs-12">   <input class="mr5"  type="checkbox" value="0" name="chk4" id="chk4" onclick="checkVerified();" <?= $modelVehicle->vhc_ver_front_license ? 'checked' : ''; ?>><span style="font-weight: bold">Front license plate matches Registration Certificate</span></div>
            <div class="col-xs-12">   <input class="mr5"  type="checkbox" value="0" name="chk5" id="chk5" onclick="checkVerified();" <?= $modelVehicle->vhc_ver_rear_license ? 'checked' : ''; ?>><span style="font-weight: bold">Rear license plate matches Registration Certificate</span></div>
            <div class="col-xs-12">   <input class="mr5"  type="checkbox" value="0" name="chk6" id="chk6" onclick="checkVerified();" <?= $modelVehicle->vhc_ver_license_commercial ? 'checked' : ''; ?>><span style="font-weight: bold">License plates are commercial(yellow)</span><span class="text-danger">*</span></div>
            <div class="col-xs-12">   <input class="mr5"  type="checkbox" value="0" name="chk7" id="chk7" onclick="checkVerified();" <?= $modelVehicle->vhc_ver_insurance ? 'checked' : ''; ?>><span style="font-weight: bold">Verified Insurance and end date</span><span class="text-danger">*</span></div>
            <div class="col-xs-12">   <input class="mr5"  type="checkbox" value="0"  name="chk8" id="chk8" onclick="checkVerified();" <?= $modelVehicle->vhc_ver_permit ? 'checked' : ''; ?>><span style="font-weight: bold">Verified Tourist Permit and end date</span></div>
            <div class="col-xs-12">   <input class="mr5"  type="checkbox" value="0" name="chk9" id="chk9" onclick="checkVerified();" <?= $modelVehicle->vhc_ver_fitness ? 'checked' : ''; ?>><span style="font-weight: bold">Verified fitness certificate and end date</span></div>
            <div class="panel-footer" style="text-align: center">
				<?php echo CHtml::submitButton('Save', array('class' => 'btn btn-warning', 'disabled' => 'disabled', 'id' => 'verifysave', 'style' => 'width: 74px;margin-right: 20px', 'name' => 'verifysave')); ?> 
				<?php echo CHtml::submitButton('Approve', array('class' => 'btn btn-success', 'disabled' => 'disabled', 'id' => 'verifysubmit', 'name' => 'verifysubmit')); ?>               
				<?php echo CHtml::submitButton('Reject', array('class' => 'btn btn-danger', 'id' => 'rejectsave', 'style' => 'width: 74px;margin-left: 20px', 'name' => 'rejectsave')); ?> 
            </div>

			<?php $this->endWidget(); ?>
        </div>

    </div>
</div>
<script>
    $(document).ready(function () {
        checkVerified();
    });


    function checkVerified()
    {
        $('#verifysubmit').attr('disabled', 'disabled');
        $('#verifysave').attr('disabled', 'disabled');
        var verified = true;
        if (!$("#chk1").is(':checked')) {
            verified = false;
        }
        if (!$("#chk3").is(':checked')) {
            verified = false;
        }
        if (!$("#chk6").is(':checked')) {
            verified = false;
        }
        if (!$("#chk7").is(':checked')) {
            verified = false;
        }

        if (verified)
        {
            $('#verifysubmit').removeAttr('disabled');
            $('#verifysave').removeAttr('disabled');
        } else
        {
            $('#verifysubmit').attr('disabled', 'disabled');
            $('#verifysave').removeAttr('disabled');

        }
    }
</script>