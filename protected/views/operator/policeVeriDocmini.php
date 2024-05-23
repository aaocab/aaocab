<div id="policeVeriPanel" role="tabpanel" data-parent="#accordionWrapa1" aria-labelledby="policeVeri" class="collapse" style="">
	<a type="button" href="/operator/register" class="col-md-12 font-weight-bold p5"><i class="bx bx-arrow-back float-left "> </i> Go back </a>
	<div class="row"  >
		<a type="button" href="/operator/register" class="col-md-12">
			<div class="list-group-item pl10">
				<i class="bx bx-chevrons-left float-left text-success "></i>Police Verification Certificate </div> 
		</a>
	</div>
	<div class="card card-body p10" >
		<?php
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
		<?php echo $form->hiddenField($cttModel, 'ctt_id', array()) ?>
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
								<br> <img src="<?php echo $pathfront ?>" class="col-xs-12 imgPVFront imgHeight"> 
							</div>
							<?
						}
						if ($docPoliceVerModel->doc_status == 1)
						{
							echo '<div class="col-xs-12 form-control mt10 p10  text-center"><span class="text-success font-weight-bolder  ">Document approved</span></div>';
						}
						else
						{
							echo $form->fileFieldGroup($docPoliceVerModel, 'doc_file_front_path', array('label' => 'Police Verification Document Upload', 'widgetOptions' => ['htmlOptions' => ['class' => 'docPVFront']]));
						}
						?>
					</div>
				</div>
				<div class="mt20" style="text-align: center">
					<?php echo CHtml::Button("Submit", array('class' => 'btn btn-primary', 'id' => 'pvdocbtn', 'onclick' => "uploadDoc(" . $docType . ",'" . $documentType . "');")); ?>
				</div>
			</div>
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>
<script type="text/javascript">
	$('.docPVFront').change(function () {
		fileValidation(this, 'pvdocbtn');
		previewDoc(this, 'imgPVFront');
	});   
</script>