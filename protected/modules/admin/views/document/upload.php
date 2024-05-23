<style>
    .select2-container-multi .select2-choices {
        min-height: 50px;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
	.modal {  overflow-y:auto;}
</style>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
?>
<div class="row">
    <div class="col-lg-10 col-md-6 col-sm-8 pb10 new-booking-list" style="float: none; margin: auto">

        <div class="row">
			<div class="col-xs-12">

				<?php
				$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'uploadDocument',
					'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error'
					),
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class'		 => 'form-horizontal',
						'enctype'	 => 'multipart/form-data',
						'onsubmit'	 => "return false;", /* Disable normal form submit */
						'onkeypress' => " if(event.keyCode == 13){ uploadDocument(); } ", /* Do ajax call when user presses enter key */
					),
				));
				/* @var $form TbActiveForm */
				$type			 = Document::model()->documentType();
				$documentType	 = $type[$doctype];
				?>				
				<div class="col-xs-12">
					<div class="panel panel-default panel-border">
						<div class="panel-body">
							<h3 class="pb10 mt0">Upload your <?= $documentType ?>  <?php
								if ($doctype <= 5)
								{
									echo 'Card';
								}
								?></h3>
							<div class="row">
								<?php
								if ($doctype < 6)
								{
									?>
									<div class="col-xs-12 col-sm-10">
										<label><?= $documentType ?> No</label>:
										<?php
										$columnName = Document::getFieldByType($doctype);
										if ($columnName != '')
										{
											$model->identity_no = $conmodel->$columnName;
										}
										?>
										<?= $form->textFieldGroup($model, 'identity_no', array('label' => '', 'placeholder' => '')) ?>
									</div>
								<?php } ?>
								<div class="col-xs-12 col-sm-10">
									<label>Front Link</label>:
									<?= $form->fileFieldGroup($model, 'doc_file_front_path', array('label' => '', 'widgetOptions' => array())) ?>
								</div>
								<?php
								if ($doctype < 6)
								{
									?>
									<div class="col-xs-12 col-sm-10">
										<label>Back Link</label>:
										<?= $form->fileFieldGroup($model, 'doc_file_back_path', array('label' => '', 'widgetOptions' => array())) ?>
									</div>
								<?php } ?>

								<?php
								$docUp = Document::model()->findByPk($conmodel->ctt_license_doc_id);
								if ($docUp->doc_temp_approved == 1)
								{
									$tempApproved = true;
								}
								else
								{
									$tempApproved = false;
								}
								if ($doctype == 5 && ($viewtype == 'driver' || $viewtype == ''))
								{
									?>
									<div class="col-xs-12 col-sm-10">
										<?= $form->checkboxGroup($model, 'doc_temp_approved', array('label' => 'Temporary approved', 'widgetOptions' => array('data' => array(), 'htmlOptions' => ['checked' => $tempApproved]), 'inline' => true)) ?>
									</div>

								<?php } ?>

							</div>
						</div>
					</div>
				</div>
				<div class="panel-footer" style="text-align: center">
					<?php echo CHtml::Button("Upload", array('class' => 'btn btn-primary', 'onclick' => 'uploadDocument();')); ?>
                </div>
			</div>
			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	function uploadDocument() {
		var doc_file_front_path = $('#Document_doc_file_front_path').val();
		var doc_type = $('#doc_type').val();
		var doc_file_back_path = $('#Document_doc_file_back_path').val();
		if ((doc_type == 6 || doc_type == 7) && doc_file_front_path == "") {
			bootbox.alert({message: 'Please upload <?= $documentType ?> front side', size: 'medium'});
			return false;
		}
		if (doc_file_front_path == "") {
			bootbox.alert({message: 'Please upload <?= $documentType ?> front side', size: 'medium'});
			return false;
		}
		if (doc_file_back_path == "") {
			bootbox.alert({message: 'Please upload <?= $documentType ?> back side', size: 'medium'});
			return false;
		}
		$("#uploadDocument")[0].submit();
	}
</script> 