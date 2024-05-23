<style>

    .modal {
		overflow-y:auto;
    }
</style>
<div class="panel">
    <div class="panel-body p0">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12 col-md-7 pl0 text-center">
                    <div class="col-xs-12">
                        <div class="row rotateImg">
							<?php
							$flag		 = 0;
							$Url		 = "";
							$imageType	 = "";
							$spiltPath	 = "";
							$ImagePath	 = "";
							if ($sidetype == 0)
							{
								$path		 = Document::getDocPathById($docModel['doc_id'], 1);
								$spiltPath	 = explode("/attachments", $path);
								$ImagePath	 = "/attachments" . $spiltPath[1];
							}
							else
							{
								$path		 = Document::getDocPathById($docModel['doc_id'], 2);
								$spiltPath	 = explode("/attachments", $path);
								$ImagePath	 = "/attachments" . $spiltPath[1];
							}
							$filePdf	 = '<a href="' . $path . '"  target="_blank"> <img src="/images/pdf.jpg"  height="100%"><br>Click to see file</a>';
							$fileImage	 = '<a href="' . $path . '"  target="_blank" id="vdimage"> <img src="' . $path . '"  width="100%"></a>';
							if ($sidetype == 0)
							{
								$imageType = pathinfo($docModel->doc_file_front_path, PATHINFO_EXTENSION);
								echo (pathinfo($docModel->doc_file_front_path, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
							}
							else
							{
								$imageType = pathinfo($docModel->doc_file_back_path, PATHINFO_EXTENSION);
								echo (pathinfo($docModel->doc_file_back_path, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
							}
							?> 
						</div>
                    </div>
					<?php
					if ($imageType != 'pdf')
					{
						?>
						<div class="col-xs-12 mt10">
							<div class="row">
								<a class="btn btn-primary" class ="rotate" id="rtleft" val="<?= $docModel->doc_id ?>">Rotate <i class="fa fa-rotate-270 fa-rotate-left"></i></a>
								<a class="btn btn-primary" class ="rotate" id="rtright" val="<?= $docModel->doc_id ?>">Rotate <i class="fa fa-rotate-90 fa-rotate-right "></i></a>
							</div>
						</div>
<?php } ?>
                </div>
                <div class="col-xs-12 col-md-5 ">
					<?php
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'verify-form',
						'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => false,
							'errorCssClass'		 => 'has-error',
							'afterValidate'		 => 'js:function(form,data,hasError){
                                        if(!hasError){                                        
                                        }
                                    }'
						),
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'htmlOptions'			 => array(
							'class' => '',
						),
					));
					/* @var $form TbActiveForm */
					?> 
					<?= $form->hiddenField($model, 'ctt_id') ?>
					<?= $form->hiddenField($docModel, 'doc_id') ?>
<?= $form->hiddenField($docModel, 'doc_type') ?>

                    <div class="row mb5">
                        <div class="col-xs-5">Document Name: </div>
						<?php
						$docName			 = "";
						$documentTypeName	 = "";
						if ($docModel['doc_type'] == 2)
						{
							$docName			 = $model['ctt_voter_no'];
							$documentTypeName	 .= "Voter Card";
						}
						else if ($docModel['doc_type'] == 3)
						{
							$docName			 = $model['ctt_aadhaar_no'];
							$documentTypeName	 .= "Aadhaar Card";
						}
						else if ($docModel['doc_type'] == 4)
						{
							$docName			 = $model['ctt_pan_no'];
							$documentTypeName	 .= "Pan Card";
						}
						else if ($docModel['doc_type'] == 5)
						{
							$docName			 = $model['ctt_license_no'];
							$documentTypeName	 .= "License Card";
						}
						else if ($docModel['doc_type'] == 6)
						{
							$documentTypeName .= "Memorandum";
						}
						?>
                        <div class="col-xs-7 bold"><?= $docName ?></div>
                    </div>                         
                    <div class="row mb5">
                        <div class="col-xs-5"><?= $model->ctt_user_type == 1 ? "Name" : "Business" ?>: </div>
						<div class="col-xs-7 bold"><?= $model->ctt_business_name != "" ? $model->ctt_business_name : $model->ctt_first_name . " " . $model->ctt_last_name ?></div>
                    </div> 					
					<?php
					$vendorDetails = Vendors::model()->findByVendorContactID($model->ctt_id);
					if ($vendorDetails != NULL && $page == "vendor")
					{
						?>
						<div class="row mb5">
							<div class="col-xs-5">Vendor Name : </div>
							<div class="col-xs-7"><?= $vendorDetails->vnd_name ?></div>
						</div>
						<?php
					}
					$driverDetails = Drivers::model()->findByDriverContactID($model->ctt_id);
					if ($driverDetails != NULL && $page == "driver")
					{
						?>
						<div class="row mb5">
							<div class="col-xs-5">Driver Name : </div>
							<div class="col-xs-7"><?= $driverDetails->drv_name ?></div>
						</div>

						<?php
					}
					if ($model->ctt_address != '')
					{
						?>
						<div class="row mb5">
							<div class="col-xs-5">Address : </div>
							<div class="col-xs-7"><?= $model->ctt_address ?></div>
						</div>
						<?php
					}
					if ($model->ctt_city != '')
					{
						?>
						<div class="row mb5">
							<div class="col-xs-5">City : </div>
							<div class="col-xs-7"><?php
								$cityDetails = Cities::model()->findByPk($model->ctt_city);
								echo $cityDetails->cty_name;
								?></div>
						</div>
						<?php
					}
					if ($model->ctt_state != '')
					{
						?>
						<div class="row mb5">
							<div class="col-xs-5">State : </div>
							<div class="col-xs-7"><?php
						$stateDetails = States::model()->findByPk($model->ctt_state);
						echo $stateDetails->stt_name;
						?></div>
						</div>
						<?php
					}
					if ($docModel['doc_type'] == 5)
					{
						?>
						<div class="row mb5">
							<div class="col-xs-5">License Issue Date: </div>
							<div class="col-xs-7 bold"><?= $ctt_license_exp_date	 = ($model->ctt_license_issue_date) ? DateTimeFormat::DateToDatePicker($model->ctt_license_issue_date) : '' ?></div>
						</div>
						<div class="row mb5">
							<div class="col-xs-5">License Expiry Date: </div>
							<div class="col-xs-7 bold"><?= $ctt_license_exp_date	 = ($model->ctt_license_exp_date) ? DateTimeFormat::DateToDatePicker($model->ctt_license_exp_date) : '' ?></div>
						</div>
						<div class="row mb5">
							<div class="col-xs-5">License Issuing Authority: </div>
							<div class="col-xs-7 bold"><?php
						$stateDetails			 = States::model()->findByPk($model->ctt_dl_issue_authority);
						echo $stateDetails->stt_name;
						?></div>
						</div>
<?php }
?>
                    <div class="row mb5">
						<div class="col-xs-5 ">Created Date: </div>
						<div class="col-xs-7"><?= date('d/m/Y', strtotime($model->ctt_created_date)); ?></div>
					</div> 
					<div class="row mb5">
						<div class="col-xs-5 ">Status: </div>
						<?php
						if ($docModel->doc_active == 1)
						{
							echo "<div class='col-xs-7 text-success'>Active</div>";
						}
						else
						{
							echo "<div class='col-xs-7 text-danger'>In-Active</div>";
						}
						?>	
					</div>
					<div class="row mb5">
						<div class="col-xs-5 ">Approved: </div>
						<?php
						if ($docModel->doc_status == 1)
						{
							echo "<div class='col-xs-7 text-success'>Approved</div>";
						}
						else if ($docModel->doc_status == 2)
						{
							echo "<div class='col-xs-7 text-danger'>Rejected</div>";
						}
						else
						{
							echo "<div class='col-xs-7 text-primary'>Waiting for Approval</div>";
							if ($docModel->doc_temp_approved == 1)
							{
								echo '<div class="col-xs-10 text-primary"><span id="registration" class="label label-info" style="display:block;;float:right;">Temporary Approved</span></div>';
							}
						}
						?>
					</div>

<?php
if ($docModel['doc_file_front_path'] != '')
{
	?>
						<div class="row mb5">
							<div class="col-xs-5">Front Side : </div>
							<div class="col-xs-7">
								<?php
								$flag++;
//								if (substr_count($docModel['doc_file_front_path'], "attachments") > 0)
//								{
//									//$UrlPath = $docModel['doc_file_front_path'];
//									$UrlPath = Document::getDocPathById($docModel['doc_id'], 1);
//                                                                        $spilt	 = explode("/attachments", $UrlPath);
//									$Image	 = "/attachments" . $spilt[1];
//									$file	 = '<a href="' . $UrlPath . '" target="_blank"> ' . $documentTypeName . ' front side</a>';
//									echo $file;
//								}
//								else
//								{
//									$UrlPath = AttachmentProcessing::ImagePath($docModel['doc_file_front_path']);
//									$spilt	 = explode("/assets", $UrlPath);
//									$Image	 = "/assets" . $spilt[1];
//									$file	 = '<a href="' . $UrlPath . '" target="_blank"> ' . $documentTypeName . ' front side</a>';
//									echo $file;
//								}
								$UrlPath = Document::getDocPathById($docModel['doc_id'], 1);
								$spilt	 = explode("/attachments", $UrlPath);
								$Image	 = "/attachments" . $spilt[1];
								$file	 = '<a href="' . $UrlPath . '" target="_blank"> ' . $documentTypeName . ' front side</a>';
								echo $file;
								?>
							</div>
						</div>
<?php } ?>
							<?php
							if ($docModel['doc_file_back_path'] != '')
							{
								?>
						<div class="row mb5">
							<div class="col-xs-5">Back Side : </div>
							<div class="col-xs-7">
								<?php
								$flag++;
//								if (substr_count($docModel['doc_file_back_path'], "attachments") > 0)
//								{
//									$UrlPath = $docModel['doc_file_back_path'];
//									$spilt	 = explode("/attachments", $UrlPath);
//									$Image	 = "/attachments" . $spilt[1];
//									$file	 = '<a href="' . $UrlPath . '" target="_blank"> ' . $documentTypeName . ' back side</a>';
//									echo $file;
//								}
//								else
//								{
//									$UrlPath = AttachmentProcessing::ImagePath($docModel['doc_file_back_path']);
//									$spilt	 = explode("/assets", $UrlPath);
//									$Image	 = "/assets" . $spilt[1];
//									$file	 = '<a href="' . $UrlPath . '" target="_blank"> ' . $documentTypeName . ' back side</a>';
//									echo $file;
//								}
								$UrlPath = Document::getDocPathById($docModel['doc_id'], 2);
								$spilt	 = explode("/attachments", $UrlPath);
								$Image	 = "/attachments" . $spilt[1];
								$file	 = '<a href="' . $UrlPath . '" target="_blank"> ' . $documentTypeName . ' back side</a>';
								echo $file;
								?>
							</div>
						</div>
							<?php } ?>	
<?php
if ($docModel['doc_type'] == 2)
{
	?>
						<div class="row bg-gray pt10 mt10">
							<div class="col-xs-12"> 
	<?= $form->textFieldGroup($model, 'ctt_voter_no') ?>
							</div>
						</div>
<?php } ?>   
					<?php
					if ($docModel['doc_type'] == 3)
					{
						?>
						<div class="row bg-gray pt10 mt10">
							<div class="col-xs-12"> 
								<?= $form->textFieldGroup($model, 'ctt_aadhaar_no') ?>
							</div>
						</div>
					<?php } ?>   
					<?php
					if ($docModel['doc_type'] == 4)
					{
						?>
						<div class="row bg-gray pt10 mt10">
							<div class="col-xs-12"> 
								<?= $form->textFieldGroup($model, 'ctt_pan_no') ?>
							</div>
						</div>
							<?php } ?> 
							<?php
							if ($docModel['doc_type'] == 5)
							{
								?>
						<div class="row bg-gray pt10 mt10">
							<div class="col-xs-5 ">License Expiry date:</div>
							<div class="col-xs-7">								
								<?php
								if ($model->ctt_license_exp_date)
								{
									$model->ctt_license_exp_date = DateTimeFormat::DateToDatePicker($model->ctt_license_exp_date);
								}
								?>							
								<?=
								$form->datePickerGroup($model, 'ctt_license_exp_date', array(
									'label'			 => '',
									'widgetOptions'	 => array(
										'options'		 => array('autoclose'	 => true,
											'startDate'	 => date("d/m/Y"), 'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array(
											'readonly'		 => true,
											'placeholder'	 => 'Licence Exp Date',
											'value'			 => $model->ctt_license_exp_date == "" ? date("d/m/Y") : $model->ctt_license_exp_date,
											'class'			 => 'input-group border-gray full-width')),
									'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?>
							</div>
						</div> 




						<div class="row bg-gray">
							<div class="col-xs-12"> 
								<div class="col-xs-5">Trip Type :</div>
								<div class="col-xs-7">

									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'ctt_trip_type',
										'val'			 => explode(',', $model->ctt_trip_type),
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
							</div>
							<div class="col-xs-12"> 
	<?= $form->textFieldGroup($model, 'ctt_license_no') ?>
							</div>
							<div class="col-xs-12"> 
	<?= $form->textFieldGroup($model, 'ctt_first_name', array('label' => "First Name (as shown on DL)")) ?>
							</div>
							<div class="col-xs-12"> 
									<?= $form->textFieldGroup($model, 'ctt_last_name', array('label' => "Last Name (as shown on DL)")) ?>
							</div>
						</div>
								<?php } ?>
					<div class="row">
                        <div class="col-xs-12 bg-gray pt10">
                            <div class="row">
                                <div class="col-xs-12"> <?= $form->textAreaGroup($docModel, 'doc_remarks', array('label' => "Remark", 'widgetOptions' => array('htmlOptions' => array('value' => "", 'placeholder' => "Remark", 'id' => "doc_remarks")))) ?></div>
                            </div>
                            <div class="row text-center mb5">
								<span id="StatusCheck" docType="<?= $docModel['doc_type'] ?>" docFlag="<?= $flag ?>" contactType="<?= $contactType ?>" style="display: none;" ></span>
								<?php
								if ($docModel->doc_status == 1)
								{
									echo '<a class="btn btn-danger btn-xs pl5 pr5" id="btnDspr" name="btnDspr">Disapprove</a>';
								}
								else if ($docModel->doc_status == 2)
								{
									echo '<a class="btn btn-success btn-xs pl5 pr5" id="btnAppr" name="btnAppr">Approve</a>';
								}
								else
								{
									echo '<a class="btn btn-danger btn-xs pl5 pr5" id="btnDspr" name="btnDspr">Disapprove</a> <a class="btn btn-success btn-xs pl5 pr5" id="btnAppr" name="btnAppr">Approve</a> ';
								}
								?>
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
	var docFlag = $("#StatusCheck").attr('docFlag');
	var contactType = $("#StatusCheck").attr('contactType');
	var docType = '<?php echo $doctype; ?>';
	var sidetype = '<?php echo $sidetype; ?>';
	var frontPath = '<?php echo $docModel['doc_file_front_path']; ?>';
	$('#btnAppr').click(function (e) {
		if (docType == 3 && docFlag != 2) {
			bootbox.dialog({
				title: 'Alert',
				message: '<div class="alert alert-danger"><strong>Warnng!</strong> Both side file  need to be upload for document approval</div>',
				closeButton: false,
				size: 'medium',
				buttons: {
					Ok: {
						label: "Ok",
						className: 'btn-primary',
						callback: function () {

						}
					}
				}
			});
			e.preventDefault();
			return false;
		} else if ((docType == 2 || docType == 4 || docType == 5) && sidetype == 1 && frontPath === "") {
			bootbox.dialog({
				title: 'Alert',
				message: '<div class="alert alert-danger"> <strong> Warnng!</strong> Front side file  need to be upload for document approval</div>',
				closeButton: false,
				size: 'medium',
				buttons: {
					Ok: {
						label: "Ok",
						className: 'btn-primary',
						callback: function () {

						}
					}
				}
			});
			e.preventDefault();
			return false;
		} else {
			if (docType == 5 && contactType == 1 && $.trim($("#Contact_ctt_trip_type").val()) == "") {
				bootbox.dialog({
					title: 'Alert',
					message: '<div class="alert alert-danger"><strong>Warnng! </strong> You need to select trip type</div>',
					closeButton: false,
					size: 'medium',
					buttons: {
						Ok: {
							label: "Ok",
							className: 'btn-primary',
							callback: function () {

							}
						}
					}
				});
				e.preventDefault();
				return false;
			} else {
				$.ajax({
					"type": "POST",
					"dataType": "json",
					"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/document/approvedoc', ['btntype' => 'approve'])) ?>",
					"data": $('#verify-form').serialize(),
					"success": function (data) {
						if (data.success) {
							refreshApprovalList();
							return false;
						} else {
							alert(JSON.stringify(data.message));
						}
					}
				});
			}
		}
		e.preventDefault();
		return false;
	});
	$('#btnDspr').click(function (e) {
		remarks = $('#doc_remarks').val();
		if (remarks.trim() != '') {
			$.ajax({
				"type": "POST",
				"dataType": "json",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/document/approvedoc', ['btntype' => 'problem'])) ?>",
				"data": $('#verify-form').serialize(),
				"success": function (data) {
					if (data.success) {
						var html = '<span class="label label-danger"> Rejected</span><br><span><i>' + remarks + '</i></span>';
						$("#doc" + docType).html(html);
						refreshApprovalList();
						return false;
					} else {
						alert(JSON.stringify(data.message));
					}
				}
			});
		} else {
			$('#Document_doc_remarks_em_').text('Remarks is required');
			$('#Document_doc_remarks_em_').addClass('text-danger');
			$('#Document_doc_remarks_em_').show();
		}
		e.preventDefault();
		return false;
	});
	$('#rtleft').click(function () {
		imgRotate('left');
	});
	$('#rtright').click(function () {
		imgRotate('right');
	});
	function imgRotate(rttype) {
		docid = '<?= $docModel['doc_id'] ?>';
		docType = '<?= $sidetype ?>';
		cttId = '<?= $model['ctt_id'] ?>';
		$.ajax({
			"type": "GET",
			"dataType": "json",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/index/imagerotate')) ?>",
			data: {'docid': docid, 'rttype': rttype, 'docType': docType, 'cttId' : cttId},
			"success": function (data) {
				if (data.success) {
					$("#vdimage").html('<img src="' + data.imagefile + '"  width="100%">');
				}
			}
		});
	}
	function refreshApprovalList() {
		box.modal('hide');
		$('#Document_doc_type').val('').trigger('change.select2');
		if ($('#vendorListGrid').length)
		{
			$('#vendorListGrid').yiiGridView('update');
		}
	}
</script>