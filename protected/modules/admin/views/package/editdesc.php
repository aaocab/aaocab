<style type="text/css">
	input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
.form-group {
        

        margin-left: 0 !important;
        margin-right: 0 !important;
    }
</style>
<div class="panel ">    

	<div class="panel-body ">  
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'packagedesc', 'enableClientValidation' => flase,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => 'form-horizontal',
			),
		));
		/* @var $form TbActiveForm */
		?>

		
		<div class="row">
			<div class="col-xs-12 col-sm-6 ">				 
				<?= $form->textFieldGroup($model, 'pck_name', array('widgetOptions' => array())) ?>
			</div>
			<div class="col-xs-12 col-sm-6 ">				 
				<?= $form->textFieldGroup($model, 'pck_url', array('widgetOptions' => array('htmlOptions' => array('readOnly' => 'readOnly')))) ?>
			</div>
			<div class="col-xs-12 col-sm-12 ">				 
				<?= $form->textAreaGroup($model, 'pck_inclusions', array('widgetOptions' => array())) ?>
			</div>
			<div class="col-xs-12 col-sm-12">				 
				<?= $form->textAreaGroup($model, 'pck_exclusions', array('widgetOptions' => array())) ?>
			</div>
		</div>



		<div class="row">
			<div class="col-xs-12 col-sm-12 ">
				<?= $form->textAreaGroup($model, 'pck_desc', array('widgetOptions' => array('htmlOptions' => array('style'=>'height:70px')))) ?>
			</div>
			<div class="col-xs-12 col-sm-12">
				<?= $form->textAreaGroup($model, 'pck_notes', array('widgetOptions' => array('htmlOptions' => array('style'=>'height:70px')))) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-6">

				<?= $form->numberFieldGroup($model, 'pck_min_included', array('label' => "Package Durations(In minutes) ", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Package Duration', 'class' => '  m0')))) ?>  
			</div>
			<div class="col-xs-12 col-sm-6">

				<?= $form->numberFieldGroup($model, 'pck_km_included', array('label' => "Total Km ", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Total Km', 'class' => '  m0')))) ?>  
			</div>

		</div>

		<div class="row">
			<div class="col-xs-12 text-center pb10">
				<?= CHtml::SubmitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>
			</div>
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>
<script>
   
	$('form').on('focus', 'input[type=number]', function (e)
	{
		$(this).on('mousewheel.disableScroll', function (e)
		{
			e.preventDefault();
		});
		$(this).on("keydown", function (event)
		{
			if (event.keyCode === 38 || event.keyCode === 40)
			{
				event.preventDefault();
			}
		});
	});
	$('form').on('blur', 'input[type=number]', function (e)
	{
		$(this).off('mousewheel.disableScroll');
		$(this).off('keydown');
	});
</script>