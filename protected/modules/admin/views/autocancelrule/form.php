<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }

</style>
<div class="row">

	<?php
	$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'autocancelrule',
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
		$form->numberFieldGroup($model, 'acr_time_create', array('label'			 => "Create Time",
			'class'			 => "form-control",
			'widgetOptions'	 => array('htmlOptions' => array('placeholder' => 'Enter Create Time', 'min' => 0))))
		?>


    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">


		<?=
		$form->numberFieldGroup($model, 'acr_time_to_pickup', array('label'			 => "Pickup Time",
			'class'			 => "form-control",
			'widgetOptions'	 => array('htmlOptions' => array('placeholder' => 'Enter Pickup Time', 'min' => 0))))
		?>




    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<?=
		$form->numberFieldGroup($model, 'acr_time_confirm', array('label'			 => "Confirm Time",
			'class'			 => "form-control",
			'widgetOptions'	 => array('htmlOptions' => array('placeholder' => 'Enter Confirm Time', 'min' => 0))))
		?>

    </div>

	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<?=
		$form->numberFieldGroup($model, 'acr_time_bidstarted', array('label'			 => "Bid Start Time",
			'class'			 => "form-control",
			'widgetOptions'	 => array('htmlOptions' => array('placeholder' => 'Enter Bid Start Time', 'min' => 0))))
		?>

    </div>


    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
        <div class="form-group cityinput">
			<?= $form->textFieldGroup($model, 'acr_cs', array()) ?>
        </div>
    </div> 

	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
        <div class="form-group cityinput">
			<?= $form->textFieldGroup($model, 'acr_rule_rank', array()) ?>
        </div>
    </div> 

	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<div class="row">
			<div class="col-xs-12 "><label>Service Tier</label>
			</div>
			<div class="col-xs-12"> <?php
				$serviceTierArr		 = ServiceClass::model()->getJSON(ServiceClass::model()->getList('array'));
				$this->widget('booster.widgets.TbSelect2', array(
					'name'			 => 'acr_service_tier',
					'model'			 => $model,
					'data'			 => $serviceTierArr,
					'value'			 => explode(',', $model->acr_service_tier),
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
				$booking_type		 = Booking::model()->getBookingType();
				$this->widget('booster.widgets.TbSelect2', array(
					'name'			 => 'acr_bkg_type',
					'model'			 => $model,
					'data'			 => $booking_type,
					'value'			 => explode(',', $model->acr_bkg_type),
					'htmlOptions'	 => array(
						'multiple'		 => 'multiple',
						'placeholder'	 => 'Booking Type',
						'width'			 => '100%',
						'style'			 => 'width:100%',
					),
				));
				?>
			</div>
		</div>
	</div>



</div>
<div class="row">
	<div class="col-xs-12">

		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
			<div class="row">
				<div class="col-xs-12 "><label>Cancel Value</label>
				</div>
				<div class="col-xs-12"> <?php
					$cancelValueList	 = AutoCancelRule::model()->getCancelType();
					$cancelValueListJson = AutoCancelRule::model()->getJSON($cancelValueList);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'acr_auto_cancel_value',
						'val'			 => "{$model->acr_auto_cancel_value}",
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($cancelValueListJson), 'allowClear' => true),
						'htmlOptions'	 => array('style' => 'width:100%;', 'placeholder' => 'Cancel Value')
					));
					?>
				</div>
			</div>
		</div>

		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
			<div class="row">
				<div class="col-xs-12 "><label>Cancel Reason</label>
				</div>
				<div class="col-xs-12"> <?php
					$cancelList			 = CHtml::listData(CancelReasons::model()->findAll(array('order'		 => 'cnr_id',
										'condition'	 => 'cnr_active=:cnr_active and  cnr_show_admin=:cnr_show_admin and cnr_show_user=:cnr_show_user ',
										'params'	 => array(':cnr_active' => 1, ':cnr_show_user' => 0, ':cnr_show_admin' => 0))), 'cnr_id', 'cnr_reason');

					$cancelListJson = AutoCancelRule::model()->getJSON($cancelList);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'acr_auto_cancel_code',
						'val'			 => "{$model->acr_auto_cancel_code}",
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($cancelListJson), 'allowClear' => true),
						'htmlOptions'	 => array('style' => 'width:100%;', 'placeholder' => 'Cancel Reason')
					));
					?>
				</div>
			</div>
		</div>



		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 ">


			<?php
			echo $form->hiddenField($model, 'acr_demsupmisfire');
			echo $form->checkboxGroup($model, 'acr_demsupmisfire', array());
			?>

		</div>
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 ">
			<?= $form->checkboxGroup($model, 'acr_is_assigned', array()) ?>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
			<?= $form->checkboxGroup($model, 'acr_is_allocated', array()) ?>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
			<?= $form->checkboxGroup($model, 'acr_addresses_given') ?>
		</div>
	</div>
</div>

<div class="col-xs-12 text-center">
	<?php echo CHtml::submitButton($model->acr_id != null ? "Update" : "Add", ['class' => 'btn  btn-primary']); ?>
</div>

<?php $this->endWidget(); ?>