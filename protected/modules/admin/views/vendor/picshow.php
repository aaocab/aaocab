<?
/* @var $vmodel VendorDocs  */
?>
<div class="panel">
    <div class="panel-body p0">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12 col-md-7 pl0 text-center">
                    <div class="col-xs-12">
                        <div class="row">
							<?
							$filePdf	 = '<a href="' . $vmodel->vd_file . '"  target="_blank"> <img src="/images/pdf.jpg"  height="100%"><br>Click to see file</a>';
							$fileImage	 = '<a href="' . $vmodel->vd_file . '"  target="_blank" id="vdimage"> <img src="' . $vmodel->vd_file . '"  width="100%" id="vdimage"></a>';
							echo (pathinfo($vmodel->vd_file, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
							?>
                        </div>
                    </div>
					<? if (pathinfo($vmodel->vd_file, PATHINFO_EXTENSION) != 'pdf')
					{
						?>
						<div class="col-xs-12 mt10">
							<div class="row">
								<a class="btn btn-primary" class ="rotate" id="rtleft" val="<?= $vmodel->vd_id ?>">Rotate <i class="fa fa-rotate-270 fa-rotate-left"></i></a>
								<a class="btn btn-primary" class ="rotate" id="rtright" val="<?= $vmodel->vd_id ?>">Rotate <i class="fa fa-rotate-90 fa-rotate-right "></i></a>
							</div>
						</div>
<? } ?>
                </div>
                <div class="col-xs-12 col-md-5 ">
					<?php
					$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
					<?= $form->hiddenField($vmodel, 'vd_id') ?>
                    <?= $form->hiddenField($vmodel, 'vd_type') ?>
                    <div class="row mb5">
                        <div class="col-xs-5">Document Type : </div>
                        <div class="col-xs-7 bold"><?= $vmodel->getDocType() . ' ' . $vmodel->getDocSubType() ?></div>
                    </div>

                    <div class="row mb5">
                        <div class="col-xs-5">Name : </div>
                        <div class="col-xs-7"><?= $model->vnd_name ?></div>
                    </div>

                    <div class="row mb5">
                        <div class="col-xs-5">Mobile : </div>
						<?
						$ccode	 = '';
						if ($model->vnd_phone_country_code != '')
						{
							$ccode = $model->vnd_phone_country_code . '-';
						}
						?>
                        <div class="col-xs-7"><?= $ccode . $model->vnd_phone ?></div>
					<? ?>
                    </div>
					<? if ($model->vnd_email != '')
					{
						?>
						<div class="row mb5">
							<div class="col-xs-5">Email : </div>
							<div class="col-xs-7"><?= $model->vnd_email ?></div>
						</div>
<? } if ($model->vnd_address != '')
{
	?>
						<div class="row mb5">
							<div class="col-xs-5">Address : </div>
							<div class="col-xs-7"><?= $model->vnd_address ?></div>
						</div>
<? } if ($model->vendorCities->cty_name != '')
{
	?>
						<div class="row mb5">
							<div class="col-xs-5">City : </div>
							<div class="col-xs-7"><?= $model->vendorCities->cty_name ?></div>
						</div>
<? } ?>
<? if ($vmodel->vd_type == 1)
{
	?>
						<div class="row bg-gray pt10 mt10">
							<div class="col-xs-5 ">Agreement Date : </div>
							<div class="col-xs-7">

								<? $agrDate = ($model->vnd_agreement_date) ? DateTimeFormat::DateTimeToDatePicker($model->vnd_agreement_date) : ''; ?>
								<?=
								$form->datePickerGroup($model, 'vnd_agreement_date', array('label'			 => '',
									'widgetOptions'	 => array('options'		 => array('autoclose'	 => true,
											'format'	 => 'dd/mm/yyyy'),
										'htmlOptions'	 => array(
											'placeholder'	 => 'Agreement Date',
											'value'			 => $agrDate,
											'class'			 => 'input-group border-gray full-width')),
									'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?> 
							</div>
						</div>           
							<? } ?>      
<? if ($vmodel->vd_type == 2)
{
	?>
						<div class="row bg-gray pt10 mt10">
							<div class="col-xs-12"> 
	<?= $form->textFieldGroup($model, 'vnd_voter_no') ?>
							</div>
						</div>
<? } ?>   
					<? if ($vmodel->vd_type == 3)
					{
						?>
						<div class="row bg-gray pt10 mt10">
							<div class="col-xs-12"> 
								<?= $form->textFieldGroup($model, 'vnd_aadhaar_no') ?>
							</div>
						</div>
					<? } ?>   
					<? if ($vmodel->vd_type == 4)
					{
						?>
						<div class="row bg-gray pt10 mt10">
							<div class="col-xs-12"> 
	<?= $form->textFieldGroup($model, 'vnd_pan_no') ?>
							</div>
						</div>
							<? } ?>   

							<? if ($vmodel->vd_type == 5)
							{
								?>
						<div class="row bg-gray pt10 mt10">
							<div class="col-xs-5 ">Licence Exp Date : </div>
							<div class="col-xs-7">
								<? $lncDate = ($model->vnd_license_exp_date) ? DateTimeFormat::DateToDatePicker($model->vnd_license_exp_date) : ''; ?>
								<?=
								$form->datePickerGroup($model, 'vnd_license_exp_date', array('label'			 => '',
									'widgetOptions'	 => array('options'		 => array('autoclose'	 => true,
											'format'	 => 'dd/mm/yyyy'),
										'htmlOptions'	 => array(
											'placeholder'	 => 'Licence Exp Date',
											'value'			 => $lncDate,
											'class'			 => 'input-group border-gray full-width')),
									'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?> 
							</div>
						</div>           
						<div class="row bg-gray  ">
							<div class="col-xs-12"> 
										<?= $form->textFieldGroup($model, 'vnd_license_no') ?>
							</div>
						</div>
<? } ?>   


                    <div class="row">
                        <div class="col-xs-12 bg-gray pt10">
                            <div class="row">
                                <div class="col-xs-12"> 
					<?= $form->textAreaGroup($vmodel, 'vd_remarks') ?>
                                </div>
                            </div>
                            <div class="row text-center mb5">
                                <a class="btn btn-primary btn-xs pl5 pr5" id="btnAppr" name="btnAppr">Approve</a>
                                <a class="btn btn-danger btn-xs pl5 pr5" id="btnDspr" name="btnDspr">Disapprove</a>
                            </div>
                        </div>
                    </div> 
<?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var remarks = '';
    $('#btnAppr').click(function (e) {


        $.ajax({
            "type": "POST",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vendor/approvedocimg', ['btntype' => 'approve'])) ?>",
            "data": $('#verify-form').serialize(),
            //  data: //{"btntype": 'approve', 'remarks': remarks, 'vdid': vdid},
            "success": function (data1) {
                if (data1.success) {
                    refreshApprovalList();

                    return false;
                } else {
                    alert(data1.errors);
                }
            }
        });
        e.preventDefault();
        return false;
    });
    $('#btnDspr').click(function () {
        remarks = $('#VendorDocs_vd_remarks').val();
        if (remarks.trim() != '') {
            $.ajax({
                "type": "POST",
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vendor/approvedocimg', ['btntype' => 'problem'])) ?>",
                // data: {"btntype": 'problem', 'remarks': remarks, 'vdid': vdid},
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
            $('#VendorDocs_vd_remarks_em_').text('Remarks is required');
            $('#VendorDocs_vd_remarks_em_').addClass('text-danger');
            $('#VendorDocs_vd_remarks_em_').show();
        }
    });

    $('#rtleft').click(function () {
        imgRotate('left');
    });
    $('#rtright').click(function () {
        imgRotate('right');
    });
    function imgRotate(rttype) {
        //alert($('#rotate').attr('val'));
        picpath = '<?= $vmodel->vd_file ?>';
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/index/imagerotate')) ?>",
            data: {'picpath': picpath, 'rttype': rttype},

            "success": function (data1) {
                if (data1.success) {

                    $("#vdimage").html('<img src="' + data1.imagefile + '"  width="100%">');
                }
            }
        });
    }
</script>