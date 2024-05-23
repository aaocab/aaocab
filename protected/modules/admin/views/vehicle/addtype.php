<?php
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
$vMake	 = array(1, 2, 3, 4, 65, 7);
$vModel	 = [];
$carType = VehicleTypes::model()->getCarType();
$carType = VehicleTypes::model()->getJSON($carType);
if($model->vht_id>0)
{
   $carType1 =  VcvCatVhcType::model()->getVehicleCatId($model->vht_id);
}

$makelist	 = VehicleTypes::model()->fetchMakeList();
$modellist	 = VehicleTypes::model()->fetchModelList();
?>
<style>
    .form-horizontal .checkbox-inline{
        padding-top: 0;
        padding-left: 0!important;
    }  
    .tt-suggestion {
        font-size: 1.2em;
        line-height: 0.7em;
        padding: 0;
        margin:  0;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}

</style>

<div class="row">
    <div class="col-md-6 col-sm-8 pt20 new-booking-list" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12">

        </div>
        <div class="row">
            <div class="upsignwidt">
                <div class="col-xs-12 pb5">
					<?php
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'vehicle-type-register-form', 'enableClientValidation' => TRUE,
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
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
									<?php
									$source		 = $makelist;
									echo $form->typeAheadGroup($model, 'vht_make', array('widgetOptions'	 => array(
											'options'		 => array(
												'hint'		 => false,
												'highlight'	 => true,
												'minLength'	 => 1
											),
											'htmlOptions'	 => array('id' => 'VehicleTypes_vht_make'),
											'datasets'		 => compact('source'),
										),
										'label'			 => 'Vehicle Make',
											)
									);
									?>
									<? echo $form->error($model, 'vht_make'); ?>
                                </div>
                                <div class="col-xs-12 col-md-6">
									<?php
									$source		 = $modellist;
									echo $form->typeAheadGroup($model, 'vht_model', array('widgetOptions'	 => array(
											'options'		 => array(
												'hint'		 => false,
												'highlight'	 => true
											),
											'htmlOptions'	 => array('id' => 'VehicleTypes_vht_model'),
											'datasets'		 => compact('source')
										),
										'label'			 => 'Vehicle Model',
											)
									);
									?>
									<? echo $form->error($model, 'vht_model'); ?>
                                </div>
                            </div>
							<?php //= $form->textFieldGroup($model, 'vht_model', array('label' => ''))  ?>

							<?
							//                            $form->dropDownListGroup($model, 'vht_car_type', array('label' => '',
							//                                'widgetOptions' => array('data' => $carType)))
							?>

                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Car Type</label>
										<?
											//Modified the code block
											$vehicleCatoryModel = new VehicleCategory();
											$returnType	 = "list";
                                            $arrCatList = VcvCatVhcType::getVehicleCategories(null, $returnType);
											$vehicleList = VehicleTypes::model()->getJSON($arrCatList);
											$this->widget('booster.widgets.TbSelect2', array
											(
												'model'			 => $model,
												'attribute'		 => "carType",
												'val'			 => $carType1,
												'asDropDownList' => FALSE,
												'options'		 => array('data' => new CJavaScriptExpression($vehicleList)),
												'htmlOptions'	 => array('style' => 'width:100%', 'id'	 => 'vehicle_car_type', 'placeholder' => 'Car Type')
											));
										?>
										<? echo $form->error($model, 'carType'); ?>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
									<?= $form->numberFieldGroup($model, 'vht_capacity', array('label' => 'Seat Capacity', 'widgetOptions' => array('htmlOptions' => array('min' => 0,'max' => 30)))); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
									<?=
									$form->numberFieldGroup($model, 'vht_average_mileage', array('label'			 => 'Average Mileage',
										'widgetOptions'	 => array('htmlOptions' => array('min' => 0,'max'=>100))))
									?>
                                </div>
                                <div class="col-xs-12 col-md-6">
									<?=
									$form->numberFieldGroup($model, 'vht_estimated_cost', array('label'			 => 'Estimated Cost (per km)',
										'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max'=>50, 'placeholder' => 'Estimated cost (per km)'))));
									?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
									<?=
									$form->numberFieldGroup($model, 'vht_big_bag_capacity', array('label'			 => 'Big Bag Capacity',
										'widgetOptions'	 => array('htmlOptions' => array('min' => 0,'max' => 26, 'placeholder' => 'Big Bag Capacity'))));
									?>
                                </div>
                                
                                <div class="col-xs-12 col-md-6">
									<?=
									$form->numberFieldGroup($model, 'vht_bag_capacity', array('label'			 => 'Small Bag Capacity',
										'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'placeholder' => 'Small Bag Capacity'))));
									?>
                                </div>
                            </div>  
                            
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <label class="control-label mb5">Fuel type</label>
									<?= $form->radioButtonListGroup($model, 'vht_fuel_type', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Diesel', 2 => 'Petrol')), 'inline' => true)) ?>
									<? echo $form->error($model, 'vht_fuel_type'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer" style="text-align: center">
							<?php echo CHtml::submitButton($isNew, array('class' => 'btn btn-primary')); ?>
                        </div>
                    </div>
					<?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo CHtml::endForm(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        var availableTags = [];
        var front_end_height = $(window).height();
        var footer_height = $(".footer").height();
        var header_height = $(".header").height();
    });

    $('form').on('focus', 'input[type=number]', function (e) {
        $(this).on('mousewheel.disableScroll', function (e) {
            e.preventDefault()
        })
        $(this).on("keydown", function (event) {
            if (event.keyCode === 38 || event.keyCode === 40) {
                event.preventDefault();
            }
        });
    });
    $('form').on('blur', 'input[type=number]', function (e) {
        $(this).off('mousewheel.disableScroll');
        $(this).off('keydown');
    });
	
	$('#vehicle-type-register-form').submit(function()
	{
		var cabType = $('#vehicle_car_type').val();
		if(cabType == '')
		{
			 $("#VehicleTypes_carType_em_").text("Car type cannot be blank");
			 $("#VehicleTypes_carType_em_").css({"color": "#a94442", "display": "block"});
			 return false;
		}
		else{
			$("#VehicleTypes_carType_em_").css({"display": "none"});
		}
		return true;
	});
</script>
