<div class="container p15">
	<div class="row">
		<div class="col-12">  
			<div class="card">  
				<div type="button" class="list-group-item list-group-item-action pl10"><a href="/operator/register"><i class="bx bx-chevrons-left float-left text-success "></i></a>Selfie with ID  </div> 

				<div id="basicInfoPanel" class="card-body">
					<div class="formBody">
						<?php
						$form1 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
						?> 
						<?php echo $form1->hiddenField($cttmodel, 'ctt_id', array()) ?> 
						<div class="row">
							<div class="col-sm-12">
								<?php
								if ($cttmodel->ctt_profile_path != '')
								{
									$path = Document::getImagePath($cttmodel->ctt_profile_path)
									?>
									<br><img src="<?php echo $path ?>" class="col-sm-3">
								<?php } ?>
								<div class="row">
									<div class="col-xs-12 col-sm-6 col-md-6">
										<?php
										echo $form1->fileFieldGroup($cttmodel, 'ctt_profile_path', array('label' => '', 'widgetOptions' => ['htmlOptions' => []]));
										?>
									</div>
								</div>
								<div class=" mt20" style="text-align: center">
									<?php
									echo CHtml::Button("Upload", array('class' => 'btn btn-primary', 'onclick' => "submitselfie()"));
									?>
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
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/dco/register.js?" . rand(1, 999));
?>