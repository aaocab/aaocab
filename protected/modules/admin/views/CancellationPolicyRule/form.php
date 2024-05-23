<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }

</style>
<div class="row">

	<?php
	$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'cancellationpolicyrule',
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => $option,
	));
	?>

	<?php echo $form->errorSummary($model); ?>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

		<?=
		$form->numberFieldGroup($model, 'cpr_charge', array('label'			 => "Cancellation Charge",
			'class'			 => "form-control",
			'widgetOptions'	 => array('htmlOptions' => array('placeholder' => 'Enter Cancellation Charge', 'min' => 0))))
		?>


    </div>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
        <div class="form-group cityinput">
			<?= $form->textFieldGroup($model, 'cpr_hours', array()) ?>
        </div>
    </div>


	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<div class="row">
			<div class="col-xs-12 "><label>Service Tier</label>
			</div>
			<div class="col-xs-12"> <?php
				$serviceTierArr	 = ServiceClass::model()->getJSON(ServiceClass::model()->getList('array'));
				$this->widget('booster.widgets.TbSelect2', array(
					'name'			 => 'cpr_service_tier',
					'model'			 => $model,
					'data'			 => $serviceTierArr,
					'value'			 => explode(',', $model->cpr_service_tier),
					'htmlOptions'	 => array(
						'multiple'		 => 'multiple',
						'placeholder'	 => 'Service Tier',
						'width'			 => '100%',
						'style'			 => 'width:100%',
					),
				));
				?>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<div class="row">
			<div class="col-xs-12 "><label>Booking Type</label></div>
			<div class="col-xs-12">  <?php
				$initiator_type	 = CancellationPolicyRule::model()->getinitiatorType();
				$this->widget('booster.widgets.TbSelect2', array(
					'name'			 => 'cpr_mark_initiator',
					'model'			 => $model,
					'data'			 => $initiator_type,
					'value'			 => explode(',', $model->cpr_mark_initiator),
					'htmlOptions'	 => array(
						'multiple'		 => 'multiple',
						'placeholder'	 => 'Mark Initiator',
						'width'			 => '100%',
						'style'			 => 'width:100%',
					),
				));
				?>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 ">
		<?php
		echo $form->checkboxGroup($model, 'cpr_is_working_hour', array());
		?>

	</div>


</div>


<div class="col-xs-12 text-center">
	<?php echo CHtml::submitButton($model->cpr_id != null ? "Update" : "Add", ['class' => 'btn  btn-primary']); ?>
</div>

<?php $this->endWidget(); ?>