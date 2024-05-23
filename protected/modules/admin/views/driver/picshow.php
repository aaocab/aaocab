<?
/* @var $dmodel DriverDocs  */
?>
<div class="panel">
    <div class="panel-body p0">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12 col-md-7 pl0 text-center">
                    <div class="col-xs-12">
                        <div class="row">
							<?
							$filePdf	 = '<a href="' . $dmodel->drd_file . '"  target="_blank"> <img src="/images/pdf.jpg"  height="100%"><br>Click to see file</a>';
							$fileImage	 = '<a href="' . $dmodel->drd_file . '"  target="_blank" id="drdimage"> <img src="' . $dmodel->drd_file . '"  width="100%" id="drdimage"></a>';
							echo (pathinfo($dmodel->drd_file, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
							?>
                        </div>
                    </div>
					<? if (pathinfo($dmodel->drd_file, PATHINFO_EXTENSION) != 'pdf')
					{
						?>
						<div class="col-xs-12 mt10">
							<div class="row">
								<a class="btn btn-primary" class ="rotate" id="rtleft" val="<?= $dmodel->drd_id ?>">Rotate <i class="fa fa-rotate-270 fa-rotate-left"></i></a>
								<a class="btn btn-primary" class ="rotate" id="rtright" val="<?= $dmodel->drd_id ?>">Rotate <i class="fa fa-rotate-90 fa-rotate-right "></i></a>
							</div>
						</div>
<? } ?>
                </div>
                <div class="col-xs-12 col-md-5 ">
					<?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
					<?= $form->hiddenField($dmodel, 'drd_id') ?>
<?= $form->hiddenField($dmodel, 'drd_type') ?>
                    <div class="row mb5">
                        <div class="col-xs-5">Document Type : </div>
                        <div class="col-xs-7 bold"><?= $dmodel->getDocType() . ' ' . $dmodel->getDocSubType() ?></div>
                    </div>
					 <div class="row mb5">
                        <div class="col-xs-5">Code : </div>
                        <div class="col-xs-7 bold"><?= $model->drv_code; ?></div>
                    </div>
					
                    <div class="row mb5">      
                        <div class="col-xs-5">Added by : </div>
                        <div class="col-xs-7 bold"><?= $model->vendorDrivers[0]->vdrvVnd->vnd_name ?></div>
                    </div>
                    <div class="row mb5">
                        <div class="col-xs-5">Name : </div>
                        <div class="col-xs-7"><?= $model->drv_name ?></div>
                    </div>
                    <div class="row mb5">
                        <div class="col-xs-5">Mobile : </div>
                        <div class="col-xs-7"><?= $model->drv_country_code . '-' . $model->drv_phone ?></div>
                    </div>
					<? if ($model->drv_email != '')
					{
						?>
						<div class="row mb5">
							<div class="col-xs-5">Email : </div>
							<div class="col-xs-7"><?= $model->drv_email ?></div>
						</div>
<? } if ($model->drv_address != '')
{
	?>
						<div class="row mb5">
							<div class="col-xs-5">Address : </div>
							<div class="col-xs-7"><?= $model->drv_address ?></div>
						</div>
<? } if ($model->driverCity->cty_name != '')
{
	?>
						<div class="row mb5">
							<div class="col-xs-5">City : </div>
							<div class="col-xs-7"><?= $model->driverCity->cty_name . ', ' . $model->driverState->stt_name ?></div>
						</div>
							<? } ?>
							<? if ($dmodel->drd_type == 1)
							{
								?>
						<div class="row bg-gray pt10 mt10">
							<div class="col-xs-12"> 
						<?= $form->textFieldGroup($model, 'drv_voter_id') ?>
							</div>
						</div>
							<? } ?>   
<? if ($dmodel->drd_type == 2)
{
	?>
						<div class="row bg-gray pt10 mt10">
							<div class="col-xs-12"> 
	<?= $form->textFieldGroup($model, 'drv_pan_no') ?>
							</div>
						</div>
<? } ?>   
					<? if ($dmodel->drd_type == 3)
					{
						?>
						<div class="row bg-gray pt10 mt10">
							<div class="col-xs-12"> 
	<?= $form->textFieldGroup($model, 'drv_aadhaar_no') ?>
							</div>
						</div>
							<? } ?>   
							<? if ($dmodel->drd_type == 4 || $dmodel->drd_type == 6)
							{
								?>
						<div class="row bg-gray pt10 mt10">
							<div class="col-xs-5 ">Licence Exp Date : </div>
							<div class="col-xs-7">
								<? $lncDate = ($model->drv_lic_exp_date) ? DateTimeFormat::DateToDatePicker($model->drv_lic_exp_date) : ''; ?>

								<?=
								$form->datePickerGroup($model, 'drv_lic_exp_date', array('label'			 => '',
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
										<?= $form->textFieldGroup($model, 'drv_lic_number') ?>
							</div>
						</div>
<? } ?>                       
                    <div class="row">
                        <div class="col-xs-12 bg-gray pt10">
                            <div class="row">
                                <div class="col-xs-12"> 
					<?= $form->textAreaGroup($dmodel, 'drd_remarks') ?>
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
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/driver/approvedocimg', ['btntype' => 'approve'])) ?>",
            "data": $('#verify-form').serialize(),
            //  data: //{"btntype": 'approve', 'remarks': remarks, 'drdid': drdid},
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
        remarks = $('#DriverDocs_drd_remarks').val();
        if (remarks.trim() != '') {
            $.ajax({
                "type": "POST",
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/driver/approvedocimg', ['btntype' => 'problem'])) ?>",
                // data: {"btntype": 'problem', 'remarks': remarks, 'drdid': drdid},
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
            $('#DriverDocs_drd_remarks_em_').text('Remarks is required');
            $('#DriverDocs_drd_remarks_em_').addClass('text-danger');
            $('#DriverDocs_drd_remarks_em_').show();
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
        picpath = '<?= $dmodel->drd_file ?>';
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/index/imagerotate')) ?>",
            data: {'picpath': picpath, 'rttype': rttype},

            "success": function (data1) {
                if (data1.success) {

                    $("#drdimage").html('<img src="' + data1.imagefile + '"  width="100%">');
                }
            }
        });
    }
</script>