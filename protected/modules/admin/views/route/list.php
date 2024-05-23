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
</style>

<div class="row">
    <div class="col-xs-12">
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'route-form', 'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'method'				 => 'POST',
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => ''
			),
		));
		?>  <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3"> 
                <div class="form-group cityinput">
                    <label class="control-label">From</label>
					<?php
//					$datacity = Cities::model()->getCityByFromBooking1();
//					$this->widget('booster.widgets.TbSelect2', array(
//						'model' => $model,
//						'attribute' => 'rut_from_city_id',
//						'val' => $model->rut_from_city_id,
//						'asDropDownList' => FALSE,
//						'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true),
//						'htmlOptions' => array('class' => 'p0', 'style' => 'width: 100%', 
//						'placeholder' => 'From')
//					));

					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'rut_from_city_id',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "From",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width' => '100%',
						//  'id' => 'from_city_id1'
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
                </div> </div>
            <div class="col-xs-12 col-sm-4 col-md-3"> 
                <div class="form-group cityinput">
                    <label class="control-label">To</label>
					<?php
//                    $datacity = Cities::model()->getCityByToBooking1();
//                    $this->widget('booster.widgets.TbSelect2', array(
//                        'model' => $model,
//                        'attribute' => 'rut_to_city_id',
//                        'val' => $model->rut_to_city_id,
//                        'asDropDownList' => FALSE,
//                        'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true),
//                        'htmlOptions' => array('class' => 'p0', 'style' => 'width: 100%', 
//                        'placeholder' => 'To')
//                    ));




					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'rut_to_city_id',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "To",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width' => '100%',
						//  'id' => 'from_city_id1'
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
                </div> </div>
            <div class="col-xs-12 col-sm-4 col-md-3"> 
                <div class="form-group cityinput">
                    <label class="control-label">Route City</label>
					<?php
//                    $datacity = Cities::model()->getCityByBooking1();
//                    $this->widget('booster.widgets.TbSelect2', array(
//                        'model' => $model,
//                        'attribute' => 'rut_route_city_id',
//                        'val' => $model->rut_route_city_id,
//                        'asDropDownList' => FALSE,
//                        'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true),
//                        'htmlOptions' => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Route City')
//                    ));
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
                </div> </div>

			<div class="col-xs-12 col-md-9">
				<div class="row">
					<div class="col-xs-12 col-sm-4">
						<div class="form-group"> 
							<label class="control-label">Source Zone</label>
							<?php
							$SubgroupArray = CHtml::listData(Zones::model()->getZoneList(), 'zon_id', function ($loc) {
										return $loc->zon_name;
									});

							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'data'				 => $SubgroupArray,
								'attribute'			 => 'rut_source_zone',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Source Zone",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%',
								//  'id' => 'from_city_id1'
								),
								'defaultOptions'	 => $selectizeOptions,
							));
							?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-4">
						<div class="form-group"> 
							<label class="control-label">Destination Zone</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'data'				 => $SubgroupArray,
								'attribute'			 => 'rut_destination_zone',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Destination Zone",
								'fullWidth'			 => false,
								'value'				 => $model->rut_destination_zone,
								'htmlOptions'		 => array('width' => '100%',
								//  'id' => 'from_city_id1'
								),
								'defaultOptions'	 => $selectizeOptions,
							));
							?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-4">
						<div class="form-group"> 
							<label class="control-label">Status</label>
							<?php
							$filters = [
								0	 => 'Inactive',
								1	 => 'Active',
								2	 => 'Disable',
							];
							$dataPay = Filter::getJSON($filters);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'rut_active',
								'val'			 => $model->rut_active,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select Status')
							));
							?>
						</div>
					</div>

				</div>
			</div>

			<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">
                <button class="btn btn-primary full-width" type="submit"  name="bookingSearch">Search</button>
            </div>
        </div>

		<?php $this->endWidget(); ?>
    </div>
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$this->widget('booster.widgets.TbGridView', array(
				'id'				 => 'route-grid',
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name' => 'FromCity', 'value' => '$data[FromCity]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'From'),
					array('name' => 'ToCity', 'value' => '$data[ToCity]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'To'),
					array('name'	 => 'rut_estm_distance', 'value'	 => function ($data) {
							echo $data["rut_estm_distance"] == null ? " 0 Km" : $data["rut_estm_distance"] . " Km";
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Distance'),
					array('name'	 => 'rut_estm_time',
						'value'	 => function ($data) {
							$hr	 = date('G', mktime(0, $data['rut_estm_time'])) . " Hr ";
							$min = (date('i', mktime(0, $data['rut_estm_time'])) != '00') ? '  ' . date('i', mktime(0, $data['rut_estm_time'])) . " min" : '';
							return $hr . '  ' . $min;
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Estimated Time'),
					array('name'				 => 'rut_create_date', 'sortable'			 => true, 'headerHtmlOptions'	 => array(),
						'value'				 => function ($data) {
							echo DateTimeFormat::DateTimeToLocale($data['rut_create_date']);
						},
						'header' => 'Create Date'),
					array('name'	 => 'rut_active', 'filter' => FALSE, 'value'	 => function ($data) {
							if ($data['rut_active'] == '2')
							{
								echo "Disable";
							}
							else if ($data['rut_active'] == '1')
							{
								echo "Active";
							}
							else
							{
								echo "Inactive";
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Status'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{edit}{addrate}{disable}{enable}{log}{disabledynamicrut}{enabledynamicrut}',
						'buttons'			 => array(
							'edit'				 => array(
								'url'		 => 'Yii::app()->createUrl("admin/route/add", array("rid" => $data[rut_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\route_list\edit_booking.png',
								'label'		 => '<i class="fa fa-edit"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Edit'),
							),
							'addrate'			 => array(
								'url'		 => 'Yii::app()->createUrl("admin/rate/entry", array("id" => $data["rut_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/add_credits.png',
								'label'		 => '<i class="fa fa-edit"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Add Rate'),
							),
							'disable'			 => array(
								'click'		 => 'function(){
                                  var con = confirm("Are you sure you want to disable this route?"); 
                                  if(con){
                                    $href = $(this).attr(\'href\');
                                    $.ajax({
                                        url: $href,
                                        success: function(result){
                                            if(result == "true"){
                                                $(\'#route-grid\').yiiGridView(\'update\');
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
								'url'		 => 'Yii::app()->createUrl("admin/route/changestatus", array("activateid" =>$data["rut_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\route_list\active.png',
								'visible'	 => '$data["rut_active"]==1?true:false;',
								'label'		 => '<i class="fa fa-toggle-off"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conDelete p0', 'title' => 'Disable'),
							),
							'enable'			 => array(
								'click'		 => 'function(){
                                    var con = confirm("Are you sure you want to enable this route?");
                                    if(con){
                                    $href = $(this).attr(\'href\');
                                    $.ajax({
                                        url: $href,
                                        success: function(result){
                                            if(result == "true"){
                                                $(\'#route-grid\').yiiGridView(\'update\');
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
								'url'		 => 'Yii::app()->createUrl("admin/route/changestatus", array("disableid" => $data["rut_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\route_list\inactive.png',
								'visible'	 => '$data["rut_active"]==2?true:false;',
								'label'		 => '<i class="fa fa-toggle-on"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conÉnable p0', 'title' => 'Enable'),
							),
							/* overide dynamic routes */
							'disabledynamicrut'	 => array(
								'click'		 => 'function(){
                                  var con1 = confirm("Are you sure you want to disable this dynamic route?"); 
                                  if(con1){
                                    $href = $(this).attr(\'href\');
                                    $.ajax({
                                        url: $href,
                                        success: function(result){
                                            if(result == "true"){
                                                $(\'#route-grid\').yiiGridView(\'update\');
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
								'url'		 => 'Yii::app()->createUrl("admin/route/overidedynamicrut", array("activateDynamucRutId" => $data["rut_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\base_price_on.png',
								'visible'	 => '$data["rut_override_dr"]==0 OR $data["rut_override_dr"]==1?true:false;',
								'label'		 => '<i class="fa fa-toggle-off"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs dynamicDisabled p0', 'title' => 'Disabled Dynamic (Demand) Pricing'),
							),
							'enabledynamicrut'	 => array(
								'click'		 => 'function(){
                                    var con1 = confirm("Are you sure you want to enable this dynamic route?");
                                    if(con1){
                                    $href = $(this).attr(\'href\');
                                    $.ajax({
                                        url: $href,
                                        success: function(result){
                                            if(result == "true"){
                                                $(\'#route-grid\').yiiGridView(\'update\');
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
								'url'		 => 'Yii::app()->createUrl("admin/route/overidedynamicrut", array("disableDynamucRutId" => $data["rut_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\base_price_off.png',
								'visible'	 => '$data["rut_override_dr"]==2?true:false;',
								'label'		 => '<i class="fa fa-toggle-on"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs dynamicÉnable p0', 'title' => 'Enable Dynamic (Demand) Pricing'),
							),
							/* overide dynamic routes */
							'routerate'			 => array(
								'click' => 'function(){
                                                        $href = $(this).attr(\'href\');
                                                        jQuery.ajax({type: \'GET\',
                                                        url: $href,
                                                        success: function (data){
                                                            bootbox.dialog({
                                                                message: data,
                                                                title: \'Route Rate\',
                                                                onEscape: function () {
                                                                    // user pressed escape
                                                                }
                                                            });
                                                        }
                                                    });
                                                    return false;
                                                    }',
							),
							'log'				 => array(
								'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data)
                                                    {

                                                        var box = bootbox.dialog({
                                                            message: data,
                                                            title: \'Route Log\',
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
								'url'		 => 'Yii::app()->createUrl("admin/route/showlog", array("rutid" => $data["rut_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\rate_list\show_log.png',
								'label'		 => '<i class="fa fa-list"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conshowlog p0', 'title' => 'Show Log'),
							),
							'htmlOptions'		 => array('class' => 'center'),
						))
			)));
		}
		?>
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