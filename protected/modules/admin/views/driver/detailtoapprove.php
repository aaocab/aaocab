<div class="row">
    <div class="panel panel-primary"> 
        <div class="panel-heading"><span style="font-weight: bold">Review and approve vehicle</span></div>

        <div class="panel-body"> </div>
        <div class="col-xs-12 mb20">
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Name</span></div>
                <div class="col-xs-7"><?= $model->drv_name ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Vendor</span></div>
                <div class="col-xs-7"><?= $model->drvVendor->vnd_name ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Phone</span></div>
                <div class="col-xs-7"><?= $model->drv_phone ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Email</span></div>
                <div class="col-xs-7"><?= $model->drv_email ?></div>
            </div>  
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Date of birth</span></div>
                <div class="col-xs-7"><?= $model->drv_dob_date ?></div>
            </div>  

            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Added On</span></div>
                <div class="col-xs-7"><?= $model->drv_created ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Address</span></div>
                <div class="col-xs-4"><?= $model->drv_address; ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">State</span></div>
                <div class="col-xs-7"><?= $model->drvState->stt_name; ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">City</span></div>
                <div class="col-xs-7"><?= $model->drvCity->cty_name ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Zip</span></div>
                <div class="col-xs-7"><?= $model->drv_zip ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Background Checked</span></div>
                <div class="col-xs-7"><?= $model->drv_bg_checked == 1 ? 'Yes' : 'No'; ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Aadhaar Card</span></div>
                <div class="col-xs-7"><a href="<?= $model->drv_aadhaar_img_path ?>" target="_blank"><?= basename($model->drv_aadhaar_img_path); ?></a></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">PAN Card</span></div>
                <div class="col-xs-7"><a href="<?= $model->drv_pan_img_path ?>"  target="_blank"><?= basename($model->drv_pan_img_path); ?></a></div>
            </div>

            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Voter ID Card</span></div>
                <div class="col-xs-7"><a href="<?= $model->drv_voter_id_img_path ?>"  target="_blank"><?= basename($model->drv_voter_id_img_path); ?></a></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">License Number</span></div>
                <div class="col-xs-7"><?= $model->drv_lic_number; ?></div>
            </div>

            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">License Expiry Date</span></div>
                <div class="col-xs-7"><?= $model->drv_lic_exp_date; ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Issue Authorisation</span></div>
                <div class="col-xs-7"><?= $model->drv_issue_auth; ?></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Photo copy of driver's license</span></div>
                <div class="col-xs-7"><a href="<?= $model->drv_licence_path ?>"  target="_blank"><?= basename($model->drv_licence_path); ?></a></div>
            </div>
            <div class="row mb10">
                <div class="col-xs-5"><span style="font-weight: bold">Driver's police verification certificate</span></div>
                <div class="col-xs-7"><a href="<?= $model->drv_police_certificate ?>" target="_blank"><?= basename($model->drv_police_certificate); ?></a></div>
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
                            "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/driver/approve', ['drvid' => $model->drv_driver_id])) . '",
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

			<?= $form->hiddenField($model, 'drv_id'); ?>
			<?= $form->hiddenField($model, 'drv_approved'); ?>
            <div class="col-xs-12 mt20"> <input class="mr5" type="checkbox" value="0" name="chk1" id="chk1" onclick="checkVerified();" <?= $modelDriver->drv_ver_adrs_proof ? 'checked' : ''; ?>><span style="font-weight: bold">Verified Address proof</span><span class="text-danger">*</span></div>
            <div class="col-xs-12"> <input class="mr5"  type="checkbox" value="0" name="chk2" id="chk2" onclick="checkVerified();"  <?= $modelDriver->drv_ver_licence ? 'checked' : ''; ?>><span style="font-weight: bold">Verified License number against License proof</span><span class="text-danger">*</span></div>
            <div class="col-xs-12">  <input class="mr5"  type="checkbox" value="0" name="chk3" id="chk3" onclick="checkVerified();"  <?= $modelDriver->drv_ver_police_certificate ? 'checked' : ''; ?>><span style="font-weight: bold">Verified Driver's police verification certificate</span><span class="text-danger"></span></div>
            <div class="panel-footer mt10" style="text-align: center">
				<?php //echo CHtml::submitButton('save', array('class' => 'btn btn-warning', 'style' => 'width: 74px;margin-right: 20px', 'id' => 'verifysave', 'name' => 'verifysave')); ?> 
				<?php echo CHtml::submitButton('approve', array('class' => 'btn btn-success', 'disabled' => 'disabled', 'id' => 'verifysubmit', 'name' => 'verifysubmit', 'style' => 'margin-right: 20px')); ?> 
				<?php echo CHtml::submitButton('reject', array('class' => 'btn btn-danger', 'style' => 'width: 74px;margin-right: 20px', 'id' => 'rejectsave', 'name' => 'rejectsave')); ?> 

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
        var verified = true;
        if (!$("#chk1").is(':checked')) {
            verified = false;
        }
        if (!$("#chk2").is(':checked')) {
            verified = false;
        }
//        if (!$("#chk3").is(':checked')) {
//            verified = false;
//        }

        if (verified)
        {
            $('#verifysubmit').removeAttr('disabled');

        } else
        {
            $('#verifysubmit').attr('disabled', 'disabled');
        }
    }
</script>