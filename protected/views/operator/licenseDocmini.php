<style>

	.bordered {
		border: 0px solid #dfe3e7!important;
	}
</style>
<div id="driverLicensePanel" role="tabpanel" data-parent="#accordionWrapa1" aria-labelledby="driverLicense" class="collapse" style="">
	<a type="button" href="/operator/register" class="col-md-12 font-weight-bold p5"><i class="bx bx-arrow-back float-left "> </i> Go back </a>
	<?php
	$docTypeLic		 = Document::Document_Licence;
//	$docTypePAN		 = Document::Document_Pan;
	$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'uploadDoc' . $docTypeLic,
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
								if(!hasError){ }	 }'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'action'				 => '/operator/uploadlicense',
		'htmlOptions'			 => array(
			'class'		 => 'form-horizontal',
			'enctype'	 => 'multipart/form-data',
			'onsubmit'	 => "return false;",
		),
	));
	/* @var $form TbActiveForm */
	$type			 = Document::model()->documentType();
	$documentTypeLic = $type[$docTypeLic];
	$documentTypePAN = $type[$docTypePAN];
	$LicfieldName	 = Document::getFieldByType($docTypeLic);
//	$PANfieldName	 = Document::getFieldByType($docTypePAN);
	?> 
	<?php echo $form->hiddenField($cttModel, 'ctt_id', array()) ?>
	<input type="hidden" name="formType" value="selfie">
	<input type="hidden" name="isDCO" value="<?php echo $isDCO ?>">
	<div class="row" id="licblock">
		<a type="button" href="/operator/register" class="col-md-12">
			<div class="list-group-item pl10">
				<i class="bx bx-chevrons-left float-left text-success "></i> Driving License Info</div> 
		</a>
	</div>

	<div class="card card-body p10" >
		<?php
		if ($errorMsg != '')
		{
			?>
			<div class="row">
				<div class="col-sm-12 alert alert-danger mb0">
					<?php echo $errorMsg ?>
				</div></div>
			<?php
		}
		?>

		<div class="row mt10" >
			<div class="col-sm-12">

				<?php
				if ($docLicenseModel->doc_status == 1)
				{
					echo '<div class="form-control "><span class="font-weight-bolder  ">' . $cttModel->$LicfieldName . '</span></div>';
				}
				else
				{
					echo $form->textFieldGroup($cttModel, $LicfieldName, array());
				}
				?>
				<span id="errorctyname" style="color:#da4455"></span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6">
				<?php
				$s3frontdata = $docLicenseModel->doc_front_s3_data;
				$filePath1	 = $docLicenseModel->doc_file_front_path;
				$s3FrontArr	 = json_decode($s3frontdata, true);
				$pathfront	 = "";
				$pathfront	 = Document::getDocPathById($docLicenseModel->doc_id, 1);
				//echo $docFrontLink = ($filePath1 != '' || $s3frontdata != '') ? '<a href="' . $pathfront . '" target="_blank">Attachment Link</a>' : 'Missing';
				if ($pathfront != '')
				{
					?>
					<br>Front Image  <br><img src="<?php echo $pathfront ?>" class="imgHeight imgLicenseFront">
					<?php
				}

				if ($docLicenseModel->doc_status == 1)
				{
					echo '<div class="col-xs-12 form-control mt10 p10  text-center"><span class="text-success font-weight-bolder  ">Document approved</span></div>';
				}
				else
				{

					echo $form->fileFieldGroup($docLicenseModel, "[$docTypeLic]doc_file_front_path", array('label' => 'Front Image Upload', 'widgetOptions' => ['htmlOptions' => ['class' => 'docLicenseFront']]));
				}
				?>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6">
				<?php
				$s3backdata	 = $docLicenseModel->doc_back_s3_data;
				$filePath2	 = $docLicenseModel->doc_file_back_path;
				$s3backArr	 = json_decode($s3backdata, true);
				$pathback	 = "";
				$pathback	 = Document::getDocPathById($docLicenseModel->doc_id, 2);

				//echo $docbackLink = ($filePath2 != '' || $s3backdata != '') ? '<a href="' . $pathback . '" target="_blank">Attachment Link</a>' : 'Missing';
				if ($pathback != '')
				{
					?>
					<br>Back Image <br><img src="<?php echo $pathback ?>" class="imgHeight imgLicenseBack">
					<?
				}
				if ($docLicenseModel->doc_status == 1)
				{
					echo '<div class="col-xs-12 form-control mt10 p10  text-center"><span class="text-success font-weight-bolder  ">Document approved</span></div>';
				}
				else
				{
					echo $form->fileFieldGroup($docLicenseModel, "[$docTypeLic]doc_file_back_path", array('label' => 'Back Image Upload', 'widgetOptions' => ['htmlOptions' => ['class' => 'docLicenseBack']]));
				}
				?>
			</div>
		</div>
		<div class="row mt10">
			<div class="col-sm-12">
				<div class="row ">
					<div class="col-sm-12 col-sm-6 col-md-6">


						<div class="form-group">
							<label class="control-label">License Expiry date </label>

							<?php
							if ($docLicenseModel->doc_status == 1)
							{
								echo '<div class="form-control "><span class="font-weight-bolder  ">' . DateTimeFormat::DateToDatePicker($cttModel->ctt_license_exp_date) . '</span></div>';
							}
							else
							{
								if ($cttModel->ctt_license_exp_date)
								{
									$cttModel->ctt_license_exp_date = DateTimeFormat::DateToDatePicker($cttModel->ctt_license_exp_date);
								}
								$curTime = DBUtil::getCurrentTime();
								$minDate = date("d/m/Y", strtotime($curTime . " +1 DAY"));
								echo
								$form->datePickerGroup($cttModel, 'ctt_license_exp_date', array(
									'label'			 => '',
									'widgetOptions'	 => array(
										'options'		 => array('autoclose'	 => true,
											'startDate'	 => $minDate, 'format'	 => 'dd/mm/yyyy'),
										'htmlOptions'	 => array(
											'readonly'		 => true,
											'placeholder'	 => 'Licence Exp Date',
											'value'			 => $cttModel->ctt_license_exp_date == "" ? $minDate : $cttModel->ctt_license_exp_date,
											'class'			 => 'input-group border-gray full-width')),
								));
							}
							?>
						</div>
					</div> 
				</div>
			</div>
		</div> 
	</div>


	<div class="col-sm-12">
		<div class=" mt20 mb10" style="text-align: center">
			<?php
			echo CHtml::Button("Submit", array('class' => 'btn btn-primary', 'id' => 'licbtn', 'onclick' => "uploadDoc(" . $docTypeLic . ",'" . $documentTypeLic . "');"));
			?>
		</div>

	</div>
	<?php $this->endWidget(); ?>
	<div class="col-sm-12">
		<div class=" mt20 mb10" style="text-align: center">
			<a type="button" href="/operator/register" class = "btn btn-primary"  >Proceed without save</a>
		</div>

	</div>
</div>  
<script type="text/javascript">
	$('.docLicenseFront').change(function () {
		fileValidation(this, 'licbtn');
		previewDoc(this, 'imgLicenseFront');
	});  
	$('.docLicenseBack').change(function () {
		fileValidation(this, 'licbtn');
		previewDoc(this, 'imgLicenseBack');
	});   
</script>


