<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'agent-type-form', 'enableClientValidation' => FALSE,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal', 'enctype'		 => 'multipart/form-data', 'autocomplete'	 => "off",
	),
		));
/* @var $form TbActiveForm */
?>
<input type="hidden" name="agt_id" value="<?= $model->agt_id; ?>">
<?= $form->radioButtonListGroup($model, 'agt_type', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => [0 => "Travel Agent", 2 => "Authorized Reseller Agent"]), 'inline' => true)) ?>
<button type="submit" class="btn btn-primary pl40 pr40 btn-lg">Submit</button>
<?php
$this->endWidget();
