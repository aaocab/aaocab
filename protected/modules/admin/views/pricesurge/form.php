<?
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
$stateList			 = array("" => "Select state") + CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>

<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
    .label-box label{
        padding-left: 0;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-lg-10 col-lg-offset-1">
            <div class="panel">
                <div class="panel-body">

					<?php
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'price-surge-form-form',
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error',
							'afterValidate'		 => 'js:function(form,data,hasError)
						{
							if(!hasError)
							{ 
								$.ajax(
								{
									"type":"POST",
									"dataType":"json",                  
									"url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
									"data":form.serialize(),
									"beforeSend": function () 
									{
										ajaxindicatorstart("");
									},
									"complete": function () 
									{  
										ajaxindicatorstop();
									},
									"success":function(data1)
									{
										if(data1.success)
										{
											alert(data1.message);
										} 
										else
										{
											var errors = data1.errors;
											sejsParseings=form.data(\'sejsParseings\');
											$.each (sejsParseings.attributes, function (i) 
											{
												$.fn.yiiactiveform.updateInput (sejsParseings.attributes[i], errors, form);
											});
										  $.fn.yiiactiveform.updateSummary(form, errors);
										} 
									},
									error: function(xhr, status, error)
									{
									}
								});
							}
						}'
						),
						'enableAjaxValidation'	 => false,
					));
					?>
                    <Div class="row">
						<?php echo $form->errorSummary($model); ?>
						<?php if (Yii::app()->user->hasFlash('success')): ?>
							<div class="col-xs-12 text-success text-center">
								<?php echo Yii::app()->user->getFlash('success'); ?>
							</div>
						<?php endif; ?> 
						<?
						if (Yii::app()->user->hasFlash('error'))
						{
							?>
							<div class="col-xs-12 alert-error text-center" style="color: #ff0000">
								<?php echo Yii::app()->user->getFlash('error'); ?>
							</div>
						<? } ?> 
                    </Div>
                    <div class="row">
                        <div class="col-md-8" style="min-width: 315px">

							<?php
							$daterang	 = "Select Price Surge Date Range";
							$createdate1 = ($model->prc_from_date == '') ? '' : $model->prc_from_date;
							$createdate2 = ($model->prc_to_date == '') ? '' : $model->prc_to_date;

							if ($createdate1 != '' && $createdate2 != '')
							{
								$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
							}
							?>
                            <label  class="control-label">Price Surge Date Range</label>
                            <div id="bkgSurgeDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?
							echo $form->hiddenField($model, 'prc_from_date');
							echo $form->hiddenField($model, 'prc_to_date');
							?>
                        </div>
                    </div>
                    <div class="row mt15">
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
							<?php echo $form->dropDownListGroup($model, 'prc_value_type', ['label' => 'Value Type', 'widgetOptions' => ['data' => [1 => 'Amount', 2 => 'Percentage']]]); ?>
							<?php echo $form->error($model, 'prc_value_type'); ?>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
							<?php echo $form->numberFieldGroup($model, 'prc_value'); ?>
                        </div>
                    </div>
                    <div class="row">


                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <div class="form-group  ">
								<?php // echo $form->drop($model,'prc_vehicle_type');           ?>
                                <label>Car Type</label>
								<?php
								$returnType	 = "";
								$vehcleList	 = SvcClassVhcCat::getVctSvcList($returnType);
//$cartype	 = VehicleTypes::model()->getCarType();
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'prc_vehicle_type',
									'val'			 => $model->prc_vehicle_type,
									'data'			 => $vehcleList,
									'options'		 => array('allowClear' => true),
									'htmlOptions'	 => array('style' => 'width:100%', 'width' => '100%', 'placeholder' => 'Select Car Type')
								));
								?>
								<?php echo $form->error($model, 'prc_vehicle_type'); ?>
                            </div></div>

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4  ">
                            <div class="form-group cityinput">
								<?php // echo $form->drop($model,'prc_vehicle_type');          ?>
                                <label>Trip Type</label>
								<?php
								$tripType	 = Booking::model()->getBookingType();
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'prc_trip_type',
									'val'			 => $model->prc_trip_type,
									'data'			 => $tripType,
									'options'		 => array('allowClear' => true),
									'htmlOptions'	 => array('style' => 'width:100%', 'width' => '100%', 'placeholder' => 'Select Trip Type')
								));
								?>
								<?php echo $form->error($model, 'prc_trip_type'); ?>
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-8 mt10">
							<?php echo $form->textAreaGroup($model, 'prc_desc'); ?>
                        </div>
                    </div>

                    <div class ="row">
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
							<?= $form->numberFieldGroup($model, 'prc_priority_score', array('widgetOptions' => array('htmlOptions' => ['min' => 0, 'max' => 999]), 'groupOptions' => ['class' => 'm0'])) ?>                      
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <div class="form-group cityinput">
								<?php // echo $form->drop($model,'prc_vehicle_type');           ?>
                                <label>Availability Type</label>
								<?php
								$avlType	 = [1 => 'Available', 0 => 'Not Available'];
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'prc_is_available',
									'val'			 => $model->prc_is_available,
									'data'			 => $avlType,
									'htmlOptions'	 => array('style' => 'width:100%', 'width' => '100%', 'placeholder' => 'Availability Type')
								));
								?>
								<?php echo $form->error($model, 'prc_is_available'); ?>
                            </div>
                        </div>
                    </div>


					<div class="row">
						<div class="col-xs-6 col-md-4">
                            <label>Cab Categories</label>
                            
                   
                            
					<?php
                   // $categoryList = VehicleCategory::getCat();
                        $returnType	 = "category";
							$vehicleList = SvcClassVhcCat::getVctSvcList($returnType);
							unset($vehicleList[11]);
                         $this->widget('booster.widgets.TbSelect2', array(
                             'model'       => $model,
                             'attribute'   => 'prc_cab_categories',
                             'val'         => explode(',', $model->prc_cab_categories),
                             'data'        => $vehicleList,
                             'htmlOptions' => array(
                                 'multiple'    => 'multiple',
                                 'placeholder' => 'Select Categories',
                                 'width'       => '100%',
                                 'style'       => 'width:100%',
                             ),
                         ));
                    ?>
					</div>
					<div class="col-xs-6 col-md-4 ">
						<label>Cab/ Service Tiers</label>
					<?php
                    $tireList = ServiceClass::getTier();
                    $this->widget('booster.widgets.TbSelect2', array(
                        'model' => $model,
                        'attribute' => 'prc_cab_tiers',
                        'val' => explode(',', $model->prc_cab_tiers),
                        'data' => $tireList,
                        'htmlOptions' => array(
                            'multiple' => 'multiple',
                            'placeholder' => 'Select Tire',
                            'width' => '100%',
                            'style' => 'width:100%',
                        ),
                    ));
					?>
					</div>
					<div class="col-xs-6 col-md-4 "></div>
					<div class="col-xs-6 col-md-4">
							<div class="row">
									<div class="col-xs-12 "><label> Cab Model(s): </label>
									</div>
									<div class="col-xs-12">
					<?php
                    $returnType = "list";
                    $vehicleModelList = VehicleTypes::getVehicleTypeList1();

                    $this->widget('booster.widgets.TbSelect2', array(
                        'model' => $model,
                        'attribute' => 'prc_cab_models',
                        'val' => explode(',', $model->prc_cab_models),
                        'data' => $vehicleModelList,
                        'htmlOptions' => array(
                            'multiple' => 'multiple',
                            'placeholder' => 'Select Cab Models',
                            'width' => '100%',
                            'style' => 'width:100%',
                        ),
                    ));
					?>
					</div>
			</div>
	</div>
					
					</div>
                    <div class="row label-box">
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
							<?= $form->checkboxGroup($model, 'prc_is_gnow_applicable', []) ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
							<?= $form->checkboxGroup($model, 'prc_is_package', array()) ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 p5">
							<?= $form->checkboxGroup($model, 'prc_override_dz', array()) ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 p5">
							<?= $form->checkboxGroup($model, 'prc_override_de', array()) ?>
                        </div>
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 p5">
							<?= $form->checkboxGroup($model, 'prc_override_ddv2', array()) ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 p5">
							<?= $form->checkboxGroup($model, 'prc_override_ds', array()) ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 p5">
							<?= $form->checkboxGroup($model, 'prc_override_profitability', array()) ?>
                        </div>
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 p5">
							<?= $form->checkboxGroup($model, 'prc_override_ddsbp', array()) ?>
                        </div>

                    </div>


                    <div class="row label-box">
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 p5  hide">
							<?= $form->checkboxGroup($model, 'prc_sold_out', array()) ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 p5">
							<?= $form->checkboxGroup($model, 'prc_delete_cache_onrefresh', array()) ?>
                        </div>
                    </div>

                    <div class="row">
	                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <div class="form-group  ">
                                <label>Surge Reason </label>
								<?php
								$surgeReason	 = PriceSurge::getSurgeReason();
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'prc_surge_reason',
									'val'			 => $model->prc_surge_reason,
									'data'			 => $surgeReason,
									'options'		 => array('allowClear' => true),
									'htmlOptions'	 => array('style' => 'width:100%', 'width' => '100%', 'placeholder' => 'Select Surge Reason')
								));
								?>
								<?php echo $form->error($model, 'prc_surge_reason'); ?>
                            </div>
                        </div>

					    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <div class="form-group  ">
                                <label>Select Region </label>
								<?php
								$regionList	 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'prc_region',
									'val'			 => $model->prc_region,
									//'asDropDownList' => FALSE,
									'data'			 => Vendors::model()->getRegionList(),
									'options'		 => array('allowClear' => true),
									'htmlOptions'	 => array('style' => 'width: 100%', 'placeholder' => 'Select Region')
								));
								?>
								<?php echo $form->error($model, 'prc_region'); ?>
                            </div></div>
                    </div>




                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <div class="form-group cityinput">
                                <label>Source State</label>
								<?php
								$dataState	 = VehicleTypes::model()->getJSON($stateList);
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'prc_source_state',
									'val'			 => $model->prc_source_state,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($dataState), 'allowClear' => true),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select State')
								));
								?> 
								<?php echo $form->error($model, 'prc_source_state'); ?>
                            </div></div>

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <div class="form-group cityinput">
                                <label>Destination State</label>
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'prc_destination_state',
									'val'			 => $model->prc_destination_state,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($dataState), 'allowClear' => true),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select State')
								));
								?> 
								<?php echo $form->error($model, 'prc_destination_state'); ?>
                            </div></div>


                    </div>



                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <div class="form-group cityinput">
                                <label>Source City</label>
								<?php
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'prc_source_city',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select Source City",
									'fullWidth'			 => false,
									'options'			 => array('allowClear' => true),
									'htmlOptions'		 => array('width'	 => '100%',
										'id'	 => 'from_city_id1'
									),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
                                  populateSource(this, '{$model->prc_source_city}');
                                                }",
								'load'			 => "js:function(query, callback){
                        loadSource(query, callback);
                        }",
								'render'		 => "js:{
                            option: function(item, escape){
                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                            },
                            option_create: function(data, escape){
                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                            }
                        }", 'allowClear'	 => true
									),
								));
								?>
								<?php echo $form->error($model, 'prc_source_city'); ?>
                            </div>
                        </div> 

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <div class="form-group cityinput">
                                <label>Destination City</label>
								<?php
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'prc_destination_city',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select Destination City",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('width'	 => '100%',
										'id'	 => 'to_city_id1'
									),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
                                  populateSource(this, '{$model->prc_destination_city}');
                                                }",
								'load'			 => "js:function(query, callback){
                                loadSource(query, callback);
                                }",
								'render'		 => "js:{
                                option: function(item, escape){
                                return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                },
                                option_create: function(data, escape){
                                return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                }
                                }",
									),
								));
								?>
								<?php echo $form->error($model, 'prc_destination_city'); ?>
                            </div>
                        </div>

                    </div>



                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 ">
                            <div class="form-group cityinput">
								<?php // echo $form->textFieldGroup($model,'prc_source_zone');         ?>
                                <label>Source Zone</label>
								<?php
								$loc			 = Zones::model()->getZoneListforPriceSurge();
								$SubgroupArray	 = CHtml::listData(Zones::model()->getZoneListforPriceSurge(), 'zon_id', function ($loc) {
											return '[' . $loc['state_name'] . '] - ' . $loc['zon_name'];
										});
								$this->widget('booster.widgets.TbSelect2', array(
									'name'			 => 'srhSourceZone',
									'model'			 => $model,
									'data'			 => $SubgroupArray,
									'value'			 => $model->srhSourceZone,
									'options'		 => array('allowClear' => true),
									'htmlOptions'	 => array(
										'placeholder'	 => 'Source Zone',
										'width'			 => '100%',
										'style'			 => 'width:100%',
									),
								));
								?>
								<?php echo $form->error($model, 'srhSourceZone'); ?>
                            </div>

							<?php
							$this->widget("booster.widgets.TbSelect2", array
								(
								"model"			 => $model,
								"attribute"		 => 'prc_source_zone',
								"val"			 => explode(",", $model->prc_source_zone),
								"htmlOptions"	 => array
									(
									"multiple"		 => "multiple",
									"placeholder"	 => "Select NearBy zone",
									"width"			 => "100%",
									"style"			 => "width:100%",
								),
							));
							?>
							<?php echo $form->error($model, 'prc_source_zone'); ?>
							<?php
//       $this->widget('booster.widgets.TbSelect2', array(
//           'name' => 'prc_source_zone',
//           'model' => $model,
//          // 'data' => $SubgroupAjsStray,
//           'value' => $model->prc_source_zone,
//           'options' => array('allowClear' => true),
//           'htmlOptions' => array(
//               "multiple" => "multiple",
//               'placeholder' => 'Source Zone',
//               'width' => '100%',
//               'style' => 'width:100%',
//           ),
//       ));
							?>
							<?php //echo $form->error($model, 'prc_source_zone');    ?>

                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <div class="form-group cityinput">
								<?php // echo $form->textFieldGroup($model,'prc_destination_zone');          ?>
                                <label>Destination Zone</label>
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'name'			 => 'srhDestinationZone',
									'model'			 => $model,
									'data'			 => $SubgroupArray,
									'value'			 => $model->srhDestinationZone,
									'options'		 => array('allowClear' => true),
									'htmlOptions'	 => array(
										'placeholder'	 => 'Destination Zone',
										'width'			 => '100%',
										'style'			 => 'width:100%',
									),
								));
								?>
								<?php echo $form->error($model, 'srhDestinationZone'); ?>

                            </div>
							<?php
							$this->widget("booster.widgets.TbSelect2", array
								(
								"model"			 => $model,
								"attribute"		 => "prc_destination_zone",
								"val"			 => explode(",", $model->prc_destination_zone),
								// 'asDropDownList' => FALSE,
								"data"			 => '',
								//  'options' => array('data' => new CJavaScriptExpression($datacity)),
								"htmlOptions"	 => array
									(
									"multiple"		 => "multiple",
									"placeholder"	 => "Select NearBy zone",
									"width"			 => "100%",
									"style"			 => "width:100%",
								),
							));
							?>





                        </div>
                    </div>


                    <div class="row mt20"> 
                        <div class="col-xs-12 text-center">
							<?php echo CHtml::submitButton('Save', ['class' => 'btn btn-info btn-lg']); ?>
                            <!--hidden-->
							<?php
							$model->sourceZones		 = $model->prc_source_zone;
							echo $form->hiddenField($model, 'sourceZones');
							?>
							<?php
							$model->destinationZones = $model->prc_destination_zone;
							echo $form->hiddenField($model, 'destinationZones');
							?>

                        </div>
                    </div>
					<?php $this->endWidget(); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
	var end = '<?= date('d/m/Y'); ?>';
	var zones;
	$(document).ready(function () {

		getSourceZoneArr();
		getDestinationZoneArr();



	});


	$('#bkgSurgeDate').daterangepicker(
			{
				locale: {
					format: 'DD/MM/YYYY',
					cancelLabel: 'Clear'
				},
				"showDropdowns": true,
				"alwaysShowCalendars": true,
				startDate: start,
				endDate: end,
				ranges: {
					'Today': [moment(), moment()],
//                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
//                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
//                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
//                    'This Month': [moment().startOf('month'), moment().endOf('month')],
//                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],

					'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
					'Next 7 Days': [moment(), moment().add(6, 'days')],
					'Next 30 Days': [moment(), moment().add(29, 'days')],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],

				}
			}, function (start1, end1) {
		$('#PriceSurge_prc_from_date').val(start1.format('YYYY-MM-DD'));
		$('#PriceSurge_prc_to_date').val(end1.format('YYYY-MM-DD'));
		$('#bkgSurgeDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#bkgSurgeDate').on('cancel.daterangepicker', function (ev, picker) {
		$('#bkgSurgeDate span').html('Select Price Surge Date Range');
		$('#PriceSurge_prc_from_date').val('');
		$('#PriceSurge_prc_to_date').val('');
	});
	$sourceList = null;


	function populateSource(obj, cityId) {
		obj.load(function (callback) {
			var obj = this;
			if ($sourceList == null) {
				xhr = $.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
					dataType: 'json',
					data: {
						// city: cityId
					},
					//  async: false,
					success: function (results) {

						$sourceList = results;
						obj.enable();
						callback($sourceList);
						obj.setValue(cityId);
					},
					error: function () {
						callback();
					}
				});
			} else {
				obj.enable();
				callback($sourceList);
				obj.setValue(cityId);
			}
		});
	}
	function loadSource(query, callback) {
		//	if (!query.length) return callback();
		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
			type: 'GET',
			dataType: 'json',
			global: false,
			error: function () {
				callback();
			},
			success: function (res) {
				callback(res);
			}
		});
	}

	$('#PriceSurge_prc_source_zone').on('click', function (event) {
		event.preventDefault();
		var dtbyCross = $('#PriceSurge_prc_source_zone').select2('data');
		var jsStr = JSON.stringify(Object.assign(dtbyCross));
		var ids = '';
		var jsParse = JSON.parse(jsStr);
		var separater = ',';
		$.each(jsParse, function (index, itemData) {
			if (index + 1 == jsParse.length)
			{
				separater = '';
			}
			ids += itemData.id + separater;
		});
		$('#PriceSurge_sourceZones').val(ids);
	});

	$('#PriceSurge_prc_destination_zone').on('click', function (event) {
		event.preventDefault();
		var dtbyCross = $('#PriceSurge_prc_destination_zone').select2('data');
		var jsStr = JSON.stringify(Object.assign(dtbyCross));
		var ids = '';
		var jsParse = JSON.parse(jsStr);
		var separater = ',';
		$.each(jsParse, function (index, itemData) {
			if (index + 1 == jsParse.length)
			{
				separater = '';
			}
			ids += itemData.id + separater;
		});
		$('#PriceSurge_destinationZones').val(ids);
	});




	$("#srhSourceZone").change(function () {

		var zid = $(this).val();
		$existData = $('#PriceSurge_prc_source_zone').select2('data');

		var href2 = '<?= Yii::app()->createUrl("admpnl/pricesurge/getNearByZone"); ?>';
		$.ajax({
			"url": href2,
			"type": "GET",
			"dataType": "json",
			"data": {"id": zid},
			"success": function (data1) {
				$data = data1;
				$mergeData = $.merge($data, $existData);

				$('#PriceSurge_prc_source_zone').data().select2.updateSelection($mergeData);
				var jsStr = JSON.stringify(Object.assign($mergeData));
				var ids = '';
				var jsParse = JSON.parse(jsStr);
				var separater = ',';
				$.each(jsParse, function (index, itemData) {
					if (index + 1 == jsParse.length)
					{
						separater = '';
					}
					ids += itemData.id + separater;
				});
				$('#PriceSurge_sourceZones').val(ids);
			}
		});
	});

	$("#srhDestinationZone").change(function () {
		var zid = $(this).val();
		$existData = $('#PriceSurge_prc_destination_zone').select2('data');
		var href2 = '<?= Yii::app()->createUrl("admpnl/pricesurge/getNearByZone"); ?>';
		$.ajax({
			"url": href2,
			"type": "GET",
			"dataType": "json",
			"data": {"id": zid},
			"success": function (data1) {
				$data = data1;
				$mergeData = $.merge($data, $existData);
				$('#PriceSurge_prc_destination_zone').data().select2.updateSelection($mergeData);
				var jsStr = JSON.stringify(Object.assign($mergeData));
				var ids = '';
				var jsParse = JSON.parse(jsStr);
				var separater = ',';
				$.each(jsParse, function (index, itemData) {
					if (index + 1 == jsParse.length)
					{
						separater = '';
					}
					ids += itemData.id + separater;
				});

				$('#PriceSurge_destinationZones').val(ids);
			}
		});
	});

	function getSourceZoneArr()
	{
		var editIDs = '<?= $model->prc_source_zone ?>';
		if (editIDs != '')
		{
			var href2 = '<?= Yii::app()->createUrl("admpnl/pricesurge/getZoneArr"); ?>';
			$.ajax({
				"url": href2,
				"type": "GET",
				"dataType": "json",
				"data": {"ids": editIDs},
				"success": function (data1) {
					var szones = data1;
					$('#PriceSurge_prc_source_zone').data().select2.updateSelection(szones);
				}
			});
		}
	}

	function getDestinationZoneArr()
	{
		var editIDs = '<?= $model->prc_destination_zone ?>';
		if (editIDs != '')
		{
			var href2 = '<?= Yii::app()->createUrl("admpnl/pricesurge/getZoneArr"); ?>';
			$.ajax({
				"url": href2,
				"type": "GET",
				"dataType": "json",
				"data": {"ids": editIDs},
				"success": function (data1) {
					var dzones = data1;
					$('#PriceSurge_prc_destination_zone').data().select2.updateSelection(dzones);
				}
			});
		}
	}




</script>