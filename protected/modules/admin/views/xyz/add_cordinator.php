<div class="row">
	<?php
	/* @var $form TbActiveForm */
	$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'cordinator_form_form', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class' => 'form-horizontal',
		),
	));
	?>
	<?= $form->hiddenField($modelBookingMff, 'bmf_booking_id'); ?>
	<?
	$data	 = array_unique(CHtml::listData(BookingMff::model()->findAll(array('select' => 't.bmf_id, t.bmf_pickup_cordinator', 'distinct' => true)), 'bmf_id', 'bmf_pickup_cordinator'));
	?>
    <div class="col-xs-6 col-xs-offset-1"> 
		<?=
		$form->typeAheadGroup($modelBookingMff, 'bmf_pickup_cordinator', array('label'			 => '', 'widgetOptions'	 => array(
				'options'		 => array(
					'hint'		 => true,
					'highlight'	 => true,
					'minLength'	 => 1
				),
				'datasets'		 => ['source' => $data],
				'htmlOptions'	 => ['placeholder' => "Enter Cordinator Name"])))
		?>                      
    </div>
    <div class="col-xs-6 col-xs-offset-1"> 
		<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>
    </div>

	<?php $this->endWidget(); ?>


</div>
