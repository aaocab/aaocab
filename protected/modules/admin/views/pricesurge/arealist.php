<style type="text/css">
    .yii-selectize ,.selectize-input  {
        min-width: 100px!important;   
    }
</style>

<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="panel-advancedoptions" >

    <div class="row">
		<div class="col-xs-12">
			<?php
			Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
			$areatype			 = AreaSurgeFactor::model()->areatype;

			$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'areasurgesearch-form', 'enableClientValidation' => true,
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
					'class' => '',
				),
			));
			/* @var $form TbActiveForm */
			?>

			<div class="row">
				<div class="col-xs-6 col-sm-4 col-lg-2">
					<div class="form-group cityinput">
						<label class="control-label">Select From Area Type</label>
						<?php
						$dataAreaType	 = VehicleTypes::model()->getJSON($areatype);
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'asf_from_area_type',
							'val'			 => $model->asf_from_area_type,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($dataAreaType), 'allowClear' => true),
							'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Area Type', 'id' => 'AreaSurgeFactor_asf_from_area_type')
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
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Area', 'id' => 'AreaSurgeFactor_asf_from_area_id')
							));
							?>
							<? echo $form->error($model, 'asf_from_area_id'); ?>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-sm-4 col-lg-2">
					<div class="form-group cityinput">
						<label>Select To Area Type</label>
						<?php
						$dataAreaType	 = VehicleTypes::model()->getJSON($areatype);
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'asf_to_area_type',
							'val'			 => $model->asf_to_area_type,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($dataAreaType), 'allowClear' => true),
							'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Area Type', 'id' => 'AreaSurgeFactor_asf_to_area_type')
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
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Area', 'id' => 'AreaSurgeFactor_asf_to_area_id')
							));
							?>
							<? echo $form->error($model, 'asf_to_area_id'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-lg-2">
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
							'htmlOptions'	 => array('style' => 'width:100%', 'width' => '100%', 'placeholder' => 'Select Car Type')
						));
						?>
					</div>
                </div>
				<div class="col-xs-12 col-sm-4 col-lg-2">
					<div class="form-group  ">
						<label>Trip Type</label>
						<?php
						$tripType		 = Booking::model()->getBookingType();
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'asf_trip_type',
							'val'			 => $model->asf_trip_type,
							'data'			 => $tripType,
							'options'		 => array('allowClear' => true),
							'htmlOptions'	 => array('style' => 'width:100%', 'width' => '100%', 'placeholder' => 'Select Trip Type')
						));
						?>
					</div>
				</div>
			</div>
			<div class="">
				<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20">   
					<?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary full-width')); ?></div>
				<div class="col-xs-6 col-sm-4  col-lg-1 mt20">
					<a href="<?= Yii::app()->createUrl('admin/pricesurge/areasurgeform') ?>"><div class="btn btn-info"><i class="fa fa-plus"></i> Add</div></a>
				</div>
			</div>
			<?php $this->endWidget(); ?>	
		</div>
	</div>



    <div class="row">
        <div class="col-md-12">
            <div class="panel" >
                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider))
							{
								$arr = [];
								if (is_array($dataProvider->getPagination()->params))
								{
									$arr = $dataProvider->getPagination()->params;
								}
								$params1							 = $arr + array_filter($_GET + $_POST);
								/* @var $dataProvider CActiveDataProvider */
								$dataProvider->pagination->pageSize	 = 50;
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'areasurgelist',
									'ajaxUrl'			 => CHtml::normalizeUrl(Yii::app()->createUrl('admin/pricesurge/arealist', $params1)),
									'responsiveTable'	 => true,
									'dataProvider'		 => $dataProvider,
									//'filter'			 => $model,
									'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary'),
									'columns'			 => array(
										array('name' => 'asf_from_area_type', 'filter' => false, 'value' => function($data){
											return AreaSurgeFactor::model()->areatype[$data["asf_from_area_type"]];
										},'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'From Area Type'),
										array('name' => 'asf_from_area_id', 'filter' => false, 'value' => '($data["asf_from_area_type"]==3?$data["fcityName"]:(($data["asf_from_area_type"]==2)?$data["fstateName"]:(($data["asf_from_area_type"]==1)? $data["fzoneName"]:Promos::$region[$data["asf_from_area_id"]])))', 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'),'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'From Area'),
										array('name' => 'asf_to_area_type', 'filter' => false, 'value' => function($data){
											return AreaSurgeFactor::model()->areatype[$data["asf_to_area_type"]];
										}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'To Area Type'),
										array('name' => 'asf_to_area_id', 'filter' => false, 'value' => '($data["asf_to_area_type"]==3?$data["tcityName"]:(($data["asf_to_area_type"]==2)?$data["tstateName"]:(($data["asf_to_area_type"]==1)? $data["tzoneName"]:Promos::$region[$data["asf_to_area_id"]])))', 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'),'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'To Area'),
										array('name'	 => 'asf_vehicle_type', 'filter' => false, 'value'	 => function ($data) {
												if ($data["asf_vehicle_type"] > 0)
												{
													return SvcClassVhcCat::getVctSvcList("string", 0, 0, $data["asf_vehicle_type"]);
												}
												else
												{
													return "";
												}
											}, 'sortable'							 => true, 'headerHtmlOptions'					 => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions'						 => array('style' => 'text-align: center;'), 'header'							 => 'Vehicle Type'),
										array('name'	 => 'asf_trip_type', 'filter' => false, 'value'	 => function ($data) {
												if ($data["asf_trip_type"] > 0)
												{
													return Booking::model()->getBookingType($data["asf_trip_type"]);
												}
												else
												{
													return "";
												}
											},
											'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Trip Type'),
										array('name' => 'asf_value_type', 'filter' => false, 'value' => '($data[asf_value_type]==1)?"Amount":"Percentage"', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Value Type'),
										array('name' => 'asf_value', 'filter' => false, 'value' => '$data[asf_value]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Value'),
										array(
											'header'			 => 'Action',
											'class'				 => 'CButtonColumn',
											'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
											'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
											'template'			 => '{edit}{delete}',
											'buttons'			 => array(
												'edit'			 => array(
													'url'		 => 'Yii::app()->createUrl("admin/pricesurge/areasurgeform", array(\'id\' => $data[asf_id]))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/city/edit_booking.png',
													'options'	 => array('style' => 'margin-right: 4px', 'class' => 'surgeedit p0', 'title' => 'Edit', 'target' => '_blank'),
												),
												'delete'		 => array(
													'click'		 => 'function(){
                                                            $href = $(this).attr(\'href\');
                                                            jQuery.ajax({type: \'GET\',
                                                            url: $href,
                                                            "dataType": "json",
                                                            success: function (data1){
                                                                if (data1.success) {
                                                                    refreshApprovalList();
                                                                    return false;
                                                                } else {
                                                                    alert(data1.errors);
                                                                }
                                                            }
                                                        });
                                                        return false;
                                                        }',
													'url'		 => 'Yii::app()->createUrl("admin/pricesurge/areadelete1", array(\'id\' => $data[asf_id]))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/delete_booking.png',
													'label'		 => '<i class="fa fa-refresh"></i>',
													'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'surgedelete p0', 'title' => 'Delete'),
												),
												'htmlOptions'	 => array('class' => 'center'),
											))
								)));
							}
							?>
                        </div>
                    </div>
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

	function refreshApprovalList() {
		$('#areasurgelist').yiiGridView('update');
	}
</script>