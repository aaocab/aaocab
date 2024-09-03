<?
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
					Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/city.js?v=' . $version);
					$areatype			 = AreaSurgeFactor::model()->areatype;

					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'area-surge-form',
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
                    <div class="row">
						<?php echo $form->errorSummary($model); ?>
						<?php if (Yii::app()->user->hasFlash('success')): ?>
							<div class="col-xs-6 text-success text-center mb10">
								<?php echo Yii::app()->user->getFlash('success'); ?>
							</div>
						<?php endif; ?> 
						<?
						if (Yii::app()->user->hasFlash('error'))
						{
							?>
							<div class="col-xs-6 alert-error text-center mb10" style="color: #ff0000">
								<?php echo Yii::app()->user->getFlash('error'); ?>
							</div>
						<? } ?> 
                    </div>
					<div class="row">


                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <div class="form-group  ">
								<label>Select From Area Type</label>
								<?php
								$dataAreaType	 = VehicleTypes::model()->getJSON($areatype);
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'asf_from_area_type',
									'val'			 => $model->asf_from_area_type,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($dataAreaType)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Area Type', 'required' => true, 'id' => 'AreaSurgeFactor_asf_from_area_type')
								));
								?>
								<?php echo $form->error($model, 'asf_from_area_type'); ?>

                            </div>
						</div>

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4  ">
                            <div class="form-group cityinput">
								<label>Select From Area</label>
								<?php
								$areaFromArr	 = '[]';
								?>
								<div id="witharea" style="display:none;">
									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'asf_area_id1',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Select Area",
										'fullWidth'			 => false,
										'options'			 => array('allowClear' => true),
										'htmlOptions'		 => array('width' => '100%',
										//'id'	 => 'from_city_id1'
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
																populateSource(this, '{$model->asf_from_area_id}');
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
								</div>
								<div id="withoutarea"> 
									<?php
									if ($model->asf_from_area_type == 1)
									{
										$areaFromArr = Zones::model()->getJSON();
									}
									else if ($model->asf_from_area_type == 2)
									{
										$areaFromArr = States::model()->getJSON();
									}
									else if ($model->asf_from_area_type == 3)
									{
										$areaFromArr = Cities::getAllCityListDrop();
									}
									else if ($model->asf_from_area_type == 4)
									{
										$areaFromArr = Promos::getRegionJSON();
									}
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'asf_from_area_id',
										'val'			 => $model->asf_from_area_id,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($areaFromArr)),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Area', 'required' => true, 'id' => 'AreaSurgeFactor_asf_from_area_id')
									));
									?>
									<? echo $form->error($model, 'asf_from_area_id'); ?>
								</div>
                            </div>
                        </div>


                    </div>

					<div class="row">


                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <div class="form-group  ">
								<label>Select To Area Type</label>
								<?php
								$dataAreaType	 = VehicleTypes::model()->getJSON($areatype);
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'asf_to_area_type',
									'val'			 => $model->asf_to_area_type,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($dataAreaType)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Area Type', 'required' => true, 'id' => 'AreaSurgeFactor_asf_to_area_type')
								));
								?>
								<?php echo $form->error($model, 'asf_to_area_type'); ?>

                            </div>
						</div>

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4  ">
                            <div class="form-group cityinput">
								<label>Select To Area</label>
								<?php
								$areaToArr	 = '[]';
								?>
								<div id="withtoarea" style="display:none;">
									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'asf_area_id2',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Select Area",
										'fullWidth'			 => false,
										'options'			 => array('allowClear' => true),
										'htmlOptions'		 => array('width' => '100%',
										//'id'	 => 'from_city_id1'
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
																populateSource(this, '{$model->asf_to_area_id}');
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
								</div>
								<div id="withouttoarea"> 
									<?php
									if ($model->asf_to_area_type == 1)
									{
										$areaToArr = Zones::model()->getJSON();
									}
									else if ($model->asf_to_area_type == 2)
									{
										$areaToArr = States::model()->getJSON();
									}
									else if ($model->asf_to_area_type == 3)
									{
										$areaToArr = Cities::getAllCityListDrop();
									}
									else if ($model->asf_to_area_type == 4)
									{
										$areaToArr = Promos::getRegionJSON();
									}
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'asf_to_area_id',
										'val'			 => $model->asf_to_area_id,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($areaToArr)),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Area', 'required' => true, 'id' => 'AreaSurgeFactor_asf_to_area_id')
									));
									?>
									<? echo $form->error($model, 'asf_to_area_id'); ?>
								</div>
                            </div>
                        </div>


                    </div>
                    <div class="row mt15">
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
							<?php echo $form->dropDownListGroup($model, 'asf_value_type', ['label' => 'Value Type', 'required' => true, 'widgetOptions' => ['data' => [1 => 'Amount', 2 => 'Percentage']]]); ?>
							<?php echo $form->error($model, 'asf_value_type'); ?>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
							<?php echo $form->numberFieldGroup($model, 'asf_value'); ?>
                        </div>
                    </div>
                    <div class="row">


                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <div class="form-group  ">
								<label>Car Type</label>
								<?php 
								$returnType		 = "";
								$vehcleList		 = SvcClassVhcCat::getVctSvcList($returnType);

								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'asf_vehicle_type',
									'val'			 => $model->asf_vehicle_type,
									'data'			 => $vehcleList,
									'options'		 => array('allowClear' => true),
									'htmlOptions'	 => array('style' => 'width:100%', 'width' => '100%', 'required' => true, 'placeholder' => 'Select Car Type')
								));
								?>
								<?php echo $form->error($model, 'asf_vehicle_type'); ?>
                            </div></div>

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4  ">
                            <div class="form-group cityinput">
								<label>Trip Type</label>
								<?php
								$tripType		 = Booking::model()->getBookingType();
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'asf_trip_type',
									'val'			 => $model->asf_trip_type,
									'data'			 => $tripType,
									'options'		 => array('allowClear' => true),
									'htmlOptions'	 => array('style' => 'width:100%', 'width' => '100%', 'required' => true, 'placeholder' => 'Select Trip Type')
								));
								?>
								<?php echo $form->error($model, 'asf_trip_type'); ?>
                            </div>
                        </div>


                    </div>
					<div class="row mt20"> 
                        <div class="col-xs-12 text-center">
							<?php echo CHtml::submitButton('Save', ['class' => 'btn btn-info btn-lg']); ?>
						</div>
                    </div>
					<?php $this->endWidget(); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
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

	var city = new City();
	$('#<?= CHtml::activeId($model, 'asf_from_area_type') ?>').change(function ()
	{
		var model = {}
		var area = $('#<?= CHtml::activeId($model, 'asf_from_area_type') ?>').val();
		if (area != 3) {
			$('#withoutarea').show();
			$('#witharea').hide();
			model.area = area;
			model.id = 'AreaSurgeFactor_asf_from_area_id';
			model.multiple = false;
			city.model = model;
			city.showArea();
		} else {
			$('#withoutarea').hide();
			$('#witharea').show();
		}
	});

	$('#<?= CHtml::activeId($model, 'asf_to_area_type') ?>').change(function ()
	{
		var model = {}
		var area = $('#<?= CHtml::activeId($model, 'asf_to_area_type') ?>').val();
		if (area != 3) {
			$('#withouttoarea').show();
			$('#withtoarea').hide();
			model.area = area;
			model.id = 'AreaSurgeFactor_asf_to_area_id';
			model.multiple = false;
			city.model = model;
			city.showArea();
		} else {
			$('#withouttoarea').hide();
			$('#withtoarea').show();
		}
	});
</script>