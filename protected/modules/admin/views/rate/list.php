<?php
//$vehicleList		 = VehicleTypes::model()->getVehicleTypeList();
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<style>
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
</style>
<div id="content" class="">
    <div class="row mb50">
        <div class="col-xs-12 col-md-10 col-md-offset-1">
            <div class="panel panel-default"><div class="panel-body pb5">
					<?php
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'booking-form', 'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error'
						),
						'method'				 => 'POST',
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'htmlOptions'			 => array(
							'class' => '',
						),
					));
					?>
                    <div class="row">                      

                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="form-group cityinput">

								<?php
//								$datacity = Cities::model()->getCityByFromBooking1();
//								$this->widget('booster.widgets.TbSelect2', array(
//									'model' => $model,
//									'attribute' => 'rut_from_city_id',
//									'val' => $model->rut_from_city_id,
//									'asDropDownList' => FALSE,
//									'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true),
//									'htmlOptions' => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'From City')
//								));
///


								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'rut_from_city_id',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "From City",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('width' => '100%',
									// 'value' => $model->rut_from_city_id
									//      'id' => 'to_city_id1'
									),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
                                            populateSource(this, '{$model->rut_from_city_id}');
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
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="form-group cityinput">
								<?php
//                                $datacity = Cities::model()->getCityByToBooking1();
//                                $this->widget('booster.widgets.TbSelect2', array(
//                                    'model' => $model,
//                                    'attribute' => 'rut_to_city_id',
//                                    'val' => $model->rut_to_city_id,
//                                    'asDropDownList' => FALSE,
//                                    'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true),
//                                    'htmlOptions' => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'To City')
//                                ));
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'rut_to_city_id',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "To City",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('width'	 => '100%',
										'value'	 => $model->rut_to_city_id
									//    'id' => 'from_city_id1'
									),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
                                            populateSource(this, '{$model->rut_to_city_id}');
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
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="form-group">
								<?php
								$returnType	 = "listCategory";
								$vehicleList = SvcClassVhcCat::getVctSvcList($returnType);
								$this -> widget("booster.widgets.TbSelect2", array
								(
									"model"			 => $rateModel,
									"attribute"		 => "rte_vehicletype_id",
									"val"			 => $rateModel -> rte_vehicletype_id,
									"data"			 => $vehicleList,
									"options"		 => array("allowClear" => true),
									"htmlOptions"	 => array
									(
										"class" => "p0", 
										"style" => "width:100%", 
										"placeholder" => "Select Vehicle category"
									)
								));
								?>
                            </div>
                        </div>
						
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="form-group cityinput">
								<?php
//                                $datacity = Cities::model()->getCityByBooking1();
//                                $this->widget('booster.widgets.TbSelect2', array(
//                                    'model' => $model,
//                                    'attribute' => 'rut_route_city_id',
//                                    'val' => $model->rut_route_city_id,
//                                    'asDropDownList' => FALSE,
//                                    'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true),
//                                    'htmlOptions' => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Route City')
//                                ));
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'rut_route_city_id',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Route City",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('width' => '100%',
									//  'id' => 'from_city_id1'
									),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
                                            populateSource(this, '{$model->rut_route_city_id}');
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
                        </div>

                    </div>
					<div class="row">
					    <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="form-group">
								<?php
								$returnType	 = "listClass";
								$vehicleList = SvcClassVhcCat::getVctSvcList($returnType);
								$this -> widget("booster.widgets.TbSelect2", array
								(
									"model"			 => $serviceClassModel,
									"attribute"		 => "scc_id",
									"val"			 => $serviceClassModel -> scc_id,
									"data"			 => $vehicleList,
									"options"		 => array("allowClear" => true),
									"htmlOptions"	 => array
									(
										"class" => "p0", 
										"style" => "width:100%", 
										"placeholder" => "Select Class"
									)
								));
								?>
                            </div>
                        </div>
						
						<div class="col-xs-12 col-sm-4 col-md-3">
							<div class="form-group">
								<?php
									$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
									'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
									'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
									'openOnFocus'		 => true, 'preload'			 => false,
									'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
									'addPrecedence'		 => false,];
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'rut_source_zone',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Source Zone",
										'fullWidth'			 => false,
										'options'			 => array('allowClear' => true),
										'htmlOptions'		 => array('width'	 => '100%'),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
													populateZone(this, '{$model->rut_source_zone}');
														}",
									'load'			 => "js:function(query, callback){
													loadZone(query, callback);
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
						</div>

						<div class="col-xs-12 col-sm-4 col-md-3"> 
							<div class="form-group">
								<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'rut_destination_zone',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Destination Zone",
										'fullWidth'			 => false,
										'options'			 => array('allowClear' => true),
										'htmlOptions'		 => array('width'	 => '100%'),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
													populateZone(this, '{$model->rut_destination_zone}');
														}",
									'load'			 => "js:function(query, callback){
													loadZone(query, callback);
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
						</div>
					</div>

					
					
					
					

                    <div class="row">
                        <div class="col-xs-12 col-sm-offset-4 col-sm-4 col-md-offset-4 col-lg-offset-5 col-md-4 col-lg-2">
                            <button class="btn btn-primary" style="width: 100%;" type="submit" name="bookingSearch">Search</button>
                        </div>
                    </div>

					<?php $this->endWidget(); ?>
                </div></div></div>

        <div class="row">
            <div class="col-xs-12 col-md-10 col-md-offset-1">
				<?php
				if (!empty($dataProvider))
				{
					$this->widget('booster.widgets.TbExtendedGridView', array(
						'id'				 => 'rate-grid',
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered mb0',
						'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
						//    'ajaxType' => 'POST',
						'columns'			 => array(
//							array('name' => 'rteVehicletype.vht_model', 'value' => '$data["vehicleModel"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Vehicle Model'),
							array('name' => 'sccLabel', 'value' => '$data["sccLabel"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Vehicle Class'),
							array('name' => 'vcLabel', 'value' => '$data["vcLabel"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Vehicle Category'),
							array('name' => 'routeFromName', 'value' => '$data["routeFromName"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'From'),
							array('name' => 'routeCityName', 'value' => '$data["routeCityName"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'To'),
							//	array('name' => 'rte_excl_amount', 'value' => '$data->rte_excl_amount', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Exclusive Fare'),
							array('name' => 'rte_vendor_amount', 'value' => '$data["rateVendorAmount"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Vendor Amount'),
							array('name' => 'rateStateTax', 'value' => '$data["rateStateTax"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'State Tax'),
							array('name' => 'rte_toll_tax', 'value' => '$data["rateTollTax"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Toll Tax'),
							array('name'				 => 'rte_amount', 'value'				 => '$data["amount"]',
								//		'class' => 'booster.widgets.TbEditableColumn',
//								'editable' => array(
//									'type' => 'text',
//									'url' => Yii::app()->createUrl('admin/rate/update')
//								),
								'sortable'			 => true, 'htmlOptions'		 => array('style' => 'max-width:200px', 'class' => 'text-center'), 'headerHtmlOptions'	 => array('style' => 'max-width:200px', 'class' => 'text-center'), 'header'			 => 'Fare'),
							array('name' => 'routeCreateDate', 'value' => 'date("d/M/Y",strtotime($data["routeCreateDate"]))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Created on'),
							array('name' => 'routeModifiedDate', 'value' => 'date("d/M/Y",strtotime($data["routeModifiedDate"]))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Updated on'),
							array(
								'header'			 => 'Action',
								'class'				 => 'CButtonColumn',
								'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
								'template'			 => '{edit}{delete}{log}',
								'buttons'			 => array(
									'edit'			 => array(
										'url'		 => 'Yii::app()->createUrl("admin/rate/entry", array("id" => $data["routeId"]))',
										'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\rate_list\edit_booking.png',
										'label'		 => '<i class="fa fa-edit"></i>',
										'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Edit'),
									),
									'delete'		 => array(
										'click'		 => 'function(){
                                  var con = confirm("Are you sure you want to delete this rate?"); 
                                  if(con){
                                    $href = $(this).attr(\'href\');
                                    $.ajax({
                                        url: $href,
                                        success: function(result){
                                            if(result == "true"){
                                                $(\'#rate-grid\').yiiGridView(\'update\');
                                            }else{
                                                alert(\'Sorry error occured\');
                                            }

                                        },
                                        error: function(xhr, status, error){
                                            alert(\'Sorry error occured\');
                                        }
                                    });
                                    }
                                    return false;
                                    }',
										'url'		 => 'Yii::app()->createUrl("admin/rate/del", array("rteid" => $data["routeId"]))',
										'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\rate_list\customer_cancel.png',
										'label'		 => '<i class="fa fa-remove"></i>',
										'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs condelete p0', 'title' => 'Delete'),
									),
									'log'			 => array(
										'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data)
                                                    {

                                                        var box = bootbox.dialog({
                                                            message: data,
                                                            title: \'Rate Log\',
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
										'url'		 => 'Yii::app()->createUrl("admin/rate/showlog", array("rteid" => $data["routeId"]))',
										'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\rate_list\show_log.png',
										'label'		 => '<i class="fa fa-list"></i>',
										'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conshowlog p0', 'title' => 'Show Log'),
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
</script>