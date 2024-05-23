<div id="aadharPanel" role="tabpanel" data-parent="#accordionWrapa1" aria-labelledby="aadhar" class="collapse" style="">
	<a type="button" href="/operator/register" class="col-md-12 font-weight-bold p5"><i class="bx bx-arrow-back float-left "> </i> Go back </a>
 	<div class="row">
		<a type="button" href="/operator/register" class="col-md-12">
			<div class="list-group-item pl10">
				<i class="bx bx-chevrons-left float-left text-success "></i> Aadhar Card</div> 
		</a>
	</div>
	<div class="card card-body  ">
		<?php
		$docType		 = Document::Document_Aadhar;
		$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'uploadDoc' . $docType,
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
										if(!hasError){ } }'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'action'				 => '/operator/uploaddoc',
			'htmlOptions'			 => array(
				'class'		 => 'form-horizontal',
				'enctype'	 => 'multipart/form-data',
				'onsubmit'	 => "return false;",
			),
		));
		/* @var $form TbActiveForm */
		$type			 = Document::model()->documentType();
		$documentType	 = $type[$docType];
		$fieldName		 = Document::getFieldByType($docType);
		?> 
		<input type="hidden" name="formType" value="pv">
		<?php echo $form->hiddenField($cttModel, 'ctt_id', array()) ?>
		<?php echo $form->hiddenField($docAdharModel, 'doc_type', array('value' => $docType)) ?>
		<div class="row">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-12 bordered">
						<?php
						if ($docAdharModel->doc_status == 1)
						{
							echo '<div class="form-control "><span class="font-weight-bolder  ">' . $cttModel->$fieldName . '</span></div>';
						}
						else
						{

							echo $form->textFieldGroup($cttModel, $fieldName, array());
						}
						?>
						<span id="errorctyname" style="color:#da4455"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 bordered">
						<?php
						$s3frontdata = $docAdharModel->doc_front_s3_data;
						$filePath1	 = $docAdharModel->doc_file_front_path;
						$s3FrontArr	 = json_decode($s3frontdata, true);
						$pathfront	 = "";
						$pathfront	 = Document::getDocPathById($docAdharModel->doc_id, 1);
						//echo $docFrontLink = ($filePath1 != '' || $s3frontdata != '') ? '<a href="' . $pathfront . '" target="_blank">Attachment Link</a>' : 'Missing';
						if ($pathfront != '')
						{
							?>
							Front Image <div class="text-center">
								<img src="<?php echo $pathfront ?>" class=" imgAadharFront imgHeight">
							</div>
							<?php
						}
						if ($docAdharModel->doc_status == 1)
						{
							echo '<div class="col-xs-12 form-control mt10 p10  text-center"><span class="text-success font-weight-bolder  ">Document approved</span></div>';
						}
						else
						{
							echo $form->fileFieldGroup($docAdharModel, 'doc_file_front_path', array('label' => 'Aadhar Front Image', 'widgetOptions' => ['htmlOptions' => ['class' => 'docAadharFront']]));
						}
						?>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 bordered">
						<?php
						$s3backdata	 = $docAdharModel->doc_back_s3_data;
						$filePath2	 = $docAdharModel->doc_file_back_path;
						$s3backArr	 = json_decode($s3backdata, true);
						$pathback	 = "";
						$pathback	 = Document::getDocPathById($docAdharModel->doc_id, 2);
						//echo $docbackLink = ($filePath2 != '' || $s3backdata != '') ? '<a href="' . $pathback . '" target="_blank">Attachment Link</a>' : 'Missing';
						if ($pathback != '')
						{
							?>
							Back Image 
							<div class="text-center">
								<img src="<?php echo $pathback ?>" class="col-sm-3 imgAadharBack imgHeight">
							</div>
							<?php
						}
						if ($docAdharModel->doc_status == 1)
						{
							echo '<div class="col-xs-12 form-control mt10 p10  text-center"><span class="text-success font-weight-bolder  ">Document approved</span></div>';
						}
						else
						{
							?>
							<?php
							echo $form->fileFieldGroup($docAdharModel, 'doc_file_back_path', array('label' => 'Aadhar Back Image', 'widgetOptions' => ['htmlOptions' => ['class' => 'docAadharBack']]));
						}
						?>
					</div>
				</div>
				<div class="mt20" style="text-align: center">
					<?php echo CHtml::Button("Submit", array('class' => 'btn btn-primary', 'id' => 'adharbtn', 'onclick' => "uploadDoc(" . $docType . ",'" . $documentType . "');")); ?>
				</div>
			</div>
		</div>
		<?php
		$this->endWidget();
		?>
	</div>
</div>
<script type="text/javascript">
	$('.docAadharFront').change(function () {
		fileValidation(this, 'adharbtn');
		previewDoc(this, 'imgAadharFront');
	});  
	$('.docAadharBack').change(function () {
		fileValidation(this, 'adharbtn');
		previewDoc(this, 'imgAadharBack');
	});      
</script>