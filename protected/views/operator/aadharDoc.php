
<div class="container p15">
	<div class="row">
		<div class="col-12">  
			<div class="card">  
				<div type="button" class="list-group-item list-group-item-action pl10"><a href="/operator/register"><i class="bx bx-chevrons-left float-left text-success "></i></a>Aadhar Card </div> 

				<div id="driverLicense" class="card-body">
					<div class="formBody">
						<?php
						$docType = Document::Document_Aadhar;
						$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
						<?php echo $form->hiddenField($cttmodel, 'ctt_id', array()) ?>
						<?php echo $form->hiddenField($docAdharModel, 'doc_type', array('value' => $docType)) ?>
						<div class="row">
							<div class="col-sm-12">
								<div class="row">
									<div class="col-sm-12">
										<?php echo $form->textFieldGroup($cttmodel, $fieldName, array()) ?>
										<span id="errorctyname" style="color:#da4455"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-6 col-md-6">
										<?php
										$s3frontdata	 = $docAdharModel->doc_front_s3_data;
										$filePath1		 = $docAdharModel->doc_file_front_path;
										$s3FrontArr		 = json_decode($s3frontdata, true);
										$pathfront		 = "";
										$pathfront		 = Document::getDocPathById($docAdharModel->doc_id, 1);
										//echo $docFrontLink = ($filePath1 != '' || $s3frontdata != '') ? '<a href="' . $pathfront . '" target="_blank">Attachment Link</a>' : 'Missing';
										if ($pathfront != '')
										{
											?>
											<br>Front Image<img src="<?php echo $pathfront ?>" class="col-sm-3">
											<?php
										}
//											else
										{
											?>
											<?php
											echo $form->fileFieldGroup($docAdharModel, 'doc_file_front_path', array('label' => 'Front Image Upload', 'widgetOptions' => ['htmlOptions' => []]));
										}
										?>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6">
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
											<br>Back Image <img src="<?php echo $pathback ?>" class="col-sm-3">
											<?php
										}
										// else
										{
											?>
											<?php
											echo $form->fileFieldGroup($docAdharModel, 'doc_file_back_path', array('label' => 'Back Image Upload', 'widgetOptions' => ['htmlOptions' => []]));
										}
										?>
									</div>
								</div>
								<div class="mt20" style="text-align: center">
									<?php echo CHtml::Button("Upload", array('class' => 'btn btn-primary', 'onclick' => "uploadDoc(" . $docType . ",'" . $documentType . "');")); ?>
								</div>
							</div>
						</div>
						<?php
						$this->endWidget();
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/dco/register.js");
?>