
<div class="container p15">
	<div class="row">
		<div class="col-12">  
			<div class="card">  
				<div type="button" class="list-group-item list-group-item-action pl10"><a href="/operator/register"><i class="bx bx-chevrons-left float-left text-success "></i></a>Police Verification Certificate </div> 

				<div id="driverLicense" class="card-body">
					<div class="formBody"><?php
						$docType = Document::Document_Police_Verification_Certificate;
						$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
							'id'					 => 'uploadDoc' . $docType,
							'enableClientValidation' => true,
							'clientOptions'			 => array(
								'validateOnSubmit'	 => true,
								'errorCssClass'		 => 'has-error',
								'afterValidate'		 => 'js:function(form,data,hasError){
										if(!hasError){ }	 }'
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
						<?php echo $form->hiddenField($docPoliceVerModel, 'doc_type', array('value' => $docType)) ?>
						<div class="row">
							<div class="col-sm-12">
								<div class="row">
									<div class="col-xs-12 col-sm-6 col-md-6">
										<?php
										$s3frontdata	 = $docPoliceVerModel->doc_front_s3_data;
										$filePath1		 = $docPoliceVerModel->doc_file_front_path;
										$s3FrontArr		 = json_decode($s3frontdata, true);
										$pathfront		 = "";
										$pathfront		 = Document::getDocPathById($docPoliceVerModel->doc_id, 1);
										//	echo $docFrontLink = ($filePath1 != '' || $s3frontdata != '') ? '<a href="' . $pathfront . '" target="_blank">Attachment Link</a>' : 'Missing';
										if ($pathfront != '')
										{
											?>
											<div class="col-xs-12 mt10 mb10 ">
												<br> <img src="<?php echo $pathfront ?>" class="col-sm-3"> 
											</div>
											<?
										}
//				else
										{
											echo $form->fileFieldGroup($docPoliceVerModel, 'doc_file_front_path', array('label' => 'Police Verification Document Upload', 'widgetOptions' => ['htmlOptions' => []]));
										}
										?>
									</div>
								</div>
								<div class="mt20" style="text-align: center">
									<?php echo CHtml::Button("Upload", array('class' => 'btn btn-primary', 'onclick' => "uploadDoc(" . $docType . ",'" . $documentType . "');")); ?>
								</div>
							</div>
						</div>
						<?php $this->endWidget(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/dco/register.js");
?>