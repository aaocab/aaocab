  
<div class="panel ">    
	<div class="panel panel-heading text-center">Upload Images</div>
	<div class="panel-body ">  
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'packageImage', 'enableClientValidation' => flase,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data',
			),
		));
		/* @var $form TbActiveForm */
		?>


		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<div>Upload Image for   

					<?= $form->radioButtonListGroup($model, 'pci_image_type', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => [1 => 'Header', 2 => 'Routes']), 'inline' => true)) ?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3">

				<?= $form->fileFieldGroup($model, 'pci_images', array('label' => '', 'widgetOptions' => array())); ?>
			</div></div>
		<div class="row">
			<div class="col-xs-12 text-center pb10 mr30">
				<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>
			</div>
		</div>

		<?php $this->endWidget(); ?>
	</div>
</div>
