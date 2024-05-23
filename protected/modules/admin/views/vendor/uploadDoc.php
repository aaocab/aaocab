<?php
$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'driver-register-form',
	'enableClientValidation' => TRUE,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => 'form-horizontal',
		'enctype'	 => 'multipart/form-data',
	),
		));
/* @var $form TbActiveForm */
?>
<div class="row">
	<div class="col-md-12">            
		<div class="panel" >
			<div class="panel-body ">
				<?php
				$docType = Document::model()->docType();
				$k		 = 0;
				foreach ($vmodel as $dType => $subTypeArr)
				{
					foreach ($subTypeArr as $subType)
					{
						$k++;
						?>
						<div class="row">
							<div class="col-xs-2 mt5"><?php echo $docType[$dType][$subType]; ?>: 	

							</div>
							<?php echo $form->hiddenField($model, $k . "[vd_type]", array('value' => $dType)); ?>
							<?php echo $form->hiddenField($model, $k . "[vd_sub_type]", array('value' => $subType)); ?>
							<div class="col-xs-4">
								<?=
								$form->fileFieldGroup($model, $k . "[vd_file]", array('label' => '', 'widgetOptions' => array()));
								?>
							</div>
						</div>
						<?php
					}
					?>
					<?php
				}
				?>
				<div class="row">
					<div class="col-xs-12 text-center pb10">
						<?php echo CHtml::submitButton($isNew, array('class' => 'btn  btn-primary')); ?>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
<?php $this->endWidget(); ?>
