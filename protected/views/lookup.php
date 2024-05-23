<?php
/* @var $this LookupController */
/* @var $model Lookup */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lookup-lookup-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'lkp_desc'); ?>
		<?php echo $form->textField($model,'lkp_desc'); ?>
		<?php echo $form->error($model,'lkp_desc'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lkp_user_desc'); ?>
		<?php echo $form->textField($model,'lkp_user_desc'); ?>
		<?php echo $form->error($model,'lkp_user_desc'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lkp_value'); ?>
		<?php echo $form->textField($model,'lkp_value'); ?>
		<?php echo $form->error($model,'lkp_value'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lkp_group'); ?>
		<?php echo $form->textField($model,'lkp_group'); ?>
		<?php echo $form->error($model,'lkp_group'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lkp_category'); ?>
		<?php echo $form->textField($model,'lkp_category'); ?>
		<?php echo $form->error($model,'lkp_category'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lkp_active'); ?>
		<?php echo $form->textField($model,'lkp_active'); ?>
		<?php echo $form->error($model,'lkp_active'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->