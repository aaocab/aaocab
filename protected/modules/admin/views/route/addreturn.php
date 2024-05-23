<?php
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
?>
<style>
    .checkbox-inline {
        padding-top: 0!important
    }    
</style>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-8 pb10" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12">
            <h3>Add a new vehicle type</h3>

        </div>
        <div class="row">
            <div class="upsignwidt">
                <div class="col-xs-12">
					<?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'vehicle-type-register-form', 'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error'
						),
						// Please note: When you enable ajax validation, make sure the corresponding
						// controller action is handling ajax validation correctly.
						// See class documentation of CActiveForm for details on this,
						// you need to use the performAjaxValidation()-method described there.
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'htmlOptions'			 => array(
							'class' => 'form-horizontal',
						),
					));
					/* @var $form TbActiveForm */
					?>
                    <div class="panel panel-default">
                        <div class="panel-body">
							<?php echo CHtml::errorSummary($model); ?>
							<?= $form->textFieldGroup($model, 'vht_make', array('label' => '')) ?>
							<?= $form->textFieldGroup($model, 'vht_model', array('label' => '')) ?>
							<?= $form->numberFieldGroup($model, 'vht_capacity', array('label' => '')) ?>
							<?= $form->numberFieldGroup($model, 'vht_average_mileage', array('label' => '', 'widgetOptions' => array('min' => 0))) ?>
							<?= $form->radioButtonListGroup($model, 'rfuelType', array('label' => 'Fuel type', 'widgetOptions' => array('data' => array(1 => 'Diesel', 2 => 'Petrol')), 'inline' => true)) ?>

                            <div class="panel-footer" style="text-align: center">
								<?php echo CHtml::submitButton('Add', array('class' => 'btn btn-primary')); ?>
                            </div>
                        </div>
                    </div><?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo CHtml::endForm(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#Drivers_drv_phone').mask('9999999999');
        var availableTags = [];
        var front_end_height = $(window).height();
        var footer_height = $(".footer").height();
        var header_height = $(".header").height();
    });


</script>
