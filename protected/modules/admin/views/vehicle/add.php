<?php
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
$vtypeList	 = VehicleTypes::model()->getVehicleTypeList();
$vTypeData	 = VehicleTypes::model()->getJSON($vtypeList);
//$vtypeList1 = array();
//foreach ($vtypeList as $key => $value) {
//    $vtypeList1[] == array("id" => $key, "text" => $val);
//}
//$vtypeList2 = CJSON::encode($vtypeList1);


$color		 = array('Red' => 'Red', 'Grey' => 'Grey', 'White' => 'White');
$vendorList	 = array("" => "Select Vendor") + CHtml::listData(Vendors::model()->getAll(array('order' => 'vnd_name')), 'vnd_id', 'vnd_name');

$yearRange		 = [];
$yearRange['']	 = 'Select model year';
$dy				 = date('Y');
for ($i = $dy; $i >= $dy - 20; $i--)
{
	$yearRange[$i] = $i;
}
?>
<style>
    .form-horizontal .checkbox-inline{
        padding-top: 0
    }



</style>



<div class="row">
    <div class="col-lg-offset-1 col-lg-6 col-md-6 col-sm-8 pt20" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12">


        </div>
        <div class="row">

            <div class="col-xs-offset-2 col-xs-8">

				<?php
				$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'vehicle-form', 'enableClientValidation' => TRUE,
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
                        <div class="col-xs-12">
							<?php echo CHtml::errorSummary($model); ?> 
                            <div class='form-group'>
                                <div class="input-group">
									<? //= $form->dropDownListGroup($model, 'vhc_type_id', array('label' => '', 'widgetOptions' => array('data' => array('' => 'Select a model') + $vtypeList)))  ?>
									<?
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'vhc_type_id',
										'val'			 => $model->vhc_type_id,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($vTypeData)),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select a model')
									));
									
									?>


                                    <div class="input-group-btn">
                                        <a class="btn btn-primary " href="<?= Yii::app()->createUrl('admin/vehicle/addtype') ?>" style="text-decoration: none;"><i class="fa fa-plus"></i></a>
                                    </div> 
                                </div>
                                <span class="has-error"><? echo $form->error($model, 'vhc_type_id'); ?></span>
                            </div>
							<?=
							$form->numberFieldGroup($model, 'vhc_year', array('label'			 => '',
								'widgetOptions'	 => array('htmlOptions' => array('min' => date('Y') - 25, 'max' => date('Y')))));
							?>
							<?= $form->textFieldGroup($model, 'vhc_number', array('label' => '', 'widgetOptions' => array('data' => array('' => 'Select a model') + $vtypeList))) ?>
                            <div class='form-group'>
								<?php
//								$data	 = Vendors::model()->getJSON();
//								$this->widget('booster.widgets.TbSelect2', array(
//									'model'			 => $model,
//									'attribute'		 => 'vhc_vendor_id1',
//									'val'			 => $model->vhc_vendor_id1,
//									'asDropDownList' => FALSE,
//									'options'		 => array('data' => new CJavaScriptExpression($data)),
//									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vendor')
//								));
								
								$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
						$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'vhc_vendor_id1',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select Vendor",
					'fullWidth'			 => false,
					'htmlOptions'		 => array('width' => '100%'),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
                                              populateVendor(this, '{$model->vhc_vendor_id1}');
                        }",
				'load'			 => "js:function(query, callback){
                                            loadVendor(query, callback);
                        }",
				'render'		 => "js:{
                                                option: function(item, escape){
                                                    return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                                                },
                                                option_create: function(data, escape){
                                                    return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                                }
                                            }",
					),
				));
								?>
                                <span class="has-error"><? echo $form->error($model, 'vhc_vendor_id1'); ?></span>
                            </div>
                            <div class="mt10">

								<?= $form->textFieldGroup($model, 'vhc_color', array('label' => '', 'widgetOptions' => array())) ?>
                            </div>
							<?php
							if ($model->vhc_insurance_exp_date)
							{
								//$model->vhc_insurance_exp_date = DateTimeFormat::DateToLocale($model->vhc_insurance_exp_date);
								$model->vhc_insurance_exp_date_date = DateTimeFormat::DateToDatePicker($model->vhc_insurance_exp_date);
							}

							echo $form->datePickerGroup($model, 'vhc_insurance_exp_date_date', array('label'			 => '',
								'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
							));

							if ($model->vhc_tax_exp_date)
							{
								$model->vhc_tax_exp_date_date = DateTimeFormat::DateToDatePicker($model->vhc_tax_exp_date);
							}
							echo $form->datePickerGroup($model, 'vhc_tax_exp_date_date', array('label'			 => '',
								'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
							));


							if ($model->vhc_dop)
							{
								$model->vhc_dop_date = DateTimeFormat::DateTimeToDatePicker($model->vhc_dop);
							}
							echo $form->datePickerGroup($model, 'vhc_dop_date', array('label'			 => '',
								'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
							));
							?>

							<?= $form->radioButtonListGroup($model, 'ownership_type', array('widgetOptions' => array('data' => array(1 => 'Owned', 2 => 'Rented')), 'inline' => true)) ?>
							<?= $form->checkboxListGroup($model, 'is_attached', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Is Attached')))) ?>
                        </div>
                    </div>
                    <div class="panel-footer" style="text-align: center">
						<?php echo CHtml::submitButton($isNew, array('class' => 'btn btn-primary')); ?>
                    </div>
                </div><?php $this->endWidget(); ?>
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
    $('#Vehicles_vhc_insurance_exp_date').datepicker({
        format: 'dd/mm/yyyy'
    });
    $('#Vehicles_vhc_tax_exp_date').datepicker({
        format: 'dd/mm/yyyy'
    });
    $('#Vehicles_vhc_dop').datepicker({
        format: 'dd/mm/yyyy'
    });

</script>
