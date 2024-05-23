<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
?>
<div class="row">
    <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12 pb10" style="float: none; margin: auto">

        <div class="row">
            <div class="upsignwidt">
                <div class="col-xs-12">
					<?php
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'rate-form',
						'enableClientValidation' => true,
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
						//'enctype'	 => 'multipart/form-data'
						),
					));
					/* @var $form TbActiveForm */
					?>
                    <div class="panel panel-default">
                        <div class="panel-body">
							<div class="col-xs-12">
								<?php echo CHtml::errorSummary($model); ?>
								<?php echo $form->hiddenField($model, 'pat_id');
								?>




								<div class="form-group">
									<label class="control-label">Partner</label>
									<?
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'pat_partner_id',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Select Partner",
										'fullWidth'			 => false,
										'options'			 => array('allowClear' => true),
										'htmlOptions'		 => array('width' => '100%',
										//  'id' => 'from_city_id1'
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
                                  populatePartner(this, '{$model->pat_partner_id}');
                                                }",
									'load'			 => "js:function(query, callback){
                        loadPartner(query, callback);
                        }",
									'render'		 => "js:{
                            option: function(item, escape){
                            return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                            },
                            option_create: function(data, escape){
                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                            }
                        }", 'allowClear'	 => true
										),
									));
									?>
								</div>

								<div class="form-group">
									<label class="control-label">Airport</label>
									<?php
									$data				 = $airportList;

									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'pat_city_id',
										'val'			 => $model->pat_city_id,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($data)),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Airport', 'readonly' => $readonly)
									));
									?>

									<span class="has-error"><? echo $form->error($model, 'pat_city_id'); ?></span>
								</div>

								<div class="form-group mb0"  >
									<label class=" ">Transfer   Type </label>
									<nobr>	
										<?php echo $form->radioButtonListGroup($model, 'pat_transfer_type', array('label' => '', 'widgetOptions' => array('data' => Booking::model()->transferTypes), 'inline' => true))
										?>
									</nobr>

								</div>
								<div class="form-group">
									<label class="control-label">Vehicle</label>
									<?php
									$vehicleList = SvcClassVhcCat::getVctSvcList();
									unset($vehicleList[11]);
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'pat_vehicle_type',
										'val'			 => $model->pat_vehicle_type,
										'data'			 => $vehicleList,
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Car Type', 'class' => 'route-focus')
									));
									?>
									<span class="has-error"><? echo $form->error($model, 'pat_vehicle_type'); ?></span>
									<input type="hidden" id="vehicleTypeId" value="<?= $model->pat_vehicle_type ?>">
								</div>

								<div class="row">
									<div class="col-xs-12 col-sm-6">
										<?php echo $form->textFieldGroup($model, 'pat_vendor_amount', array('label' => 'Vendor amount')) ?>
									</div>
									<div class="col-xs-12 col-sm-6">	
										<?php echo $form->textFieldGroup($model, 'pat_total_fare', array('label' => 'Total fare')) ?>
									</div>
								</div>

								<div class="row">
									<div class="col-xs-12 col-sm-6">
										<?php echo $form->textFieldGroup($model, 'pat_minimum_km', array('label' => 'Minimum Km applied')) ?>
									</div>
									<div class="col-xs-12 col-sm-6">		
										<?php echo $form->textFieldGroup($model, 'pat_extra_per_km_rate', array('label' => 'Extra rate/km  ')) ?>
									</div>
								</div>
							</div>
						</div>
						<div class="panel-footer" style="text-align: center">
							<?php echo CHtml::submitButton('Save', array('class' => 'btn btn-primary')); ?>
						</div>

                    </div><?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo CHtml::endForm(); ?>
<script>


</script>