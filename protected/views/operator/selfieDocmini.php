<div id="selfiePanel" role="tabpanel" data-parent="#accordionWrapa1" aria-labelledby="selfie" class="collapse" style="">
	<a type="button" href="/operator/register" class="col-md-12 font-weight-bold p5"><i class="bx bx-arrow-back float-left "> </i> Go back </a>
	<div class="row">
		<a type="button" href="/operator/register" class="col-md-12">
			<div class="list-group-item pl10">
				<i class="bx bx-chevrons-left float-left text-success "></i> Selfie with ID </div> 
		</a>
	</div>
	<div class="card card-body  ">
		<?php
		$form1		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'uploadSelfie',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
										if(!hasError){ } }'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'action'				 => '/operator/uploadselfie',
			'htmlOptions'			 => array(
				'class'		 => 'form-horizontal',
				'enctype'	 => 'multipart/form-data',
				'onsubmit'	 => "return false;",
			),
		));
		/* @var $form TbActiveForm */
		$existImage	 = ($cttModel->ctt_profile_path != '');
		?> 
		<input type="hidden" name="formType" value=""><? //aadhar ?>
		<?php echo $form1->hiddenField($cttModel, 'ctt_id', array()); ?> 

		<?php
		if ($errorMsg != '')
		{
			?>
			<div class="row">
				<div class="col-sm-12 text-danger">
					<?php echo $errorMsg ?>
				</div></div>
			<?php
		}
		?>

		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<div class="row">
					<div class="col-xs-12 col-md-12">
						<?php
						if ($cttModel->ctt_profile_path != '')
						{
							$path = Document::getImagePath($cttModel->ctt_profile_path);
						}
						?>
						<br>
						<img id="Contact_ctt_profile_path_preview" src="<?php echo $path ?>" class="imgHeight selfieImgPreview">
						<div id="imagePreview">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12  col-md-12 ">
						<?php
						echo $form1->fileFieldGroup($cttModel, 'ctt_profile_path', array('label' => '', 'widgetOptions' => ['htmlOptions' => ['class' => 'docSelfie']]));
						?>
					</div>
				</div>

				<div class=" mt20" style="text-align: center">
					<?php
					echo CHtml::Button("Submit", array('class' => 'btn btn-primary', 'id' => 'selfiebtn', 'onclick' => "submitselfie($existImage)"));
					?>
				</div>

			</div>
			<?php
			$this->endWidget();
			?>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('.docSelfie').change(function () {
		fileValidation(this, 'selfiebtn');
		previewDoc(this, 'selfieImgPreview');
	});   

</script>