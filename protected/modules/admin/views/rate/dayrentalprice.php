<div class="row">
	<div class="panel" >
	<div class="panel-body">
		
			<div class="col-xs-12">
				<div class="form-group">
					<div class="row">
						<?php
						$carType     = SvcClassVhcCat::model()->getVctSvcList();
						$stateList	 = CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
						$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id' => 'penaltyTypeReport-form', 'enableClientValidation' => true,
						'clientOptions' => array(
							'validateOnSubmit' => true,
							'errorCssClass' => 'has-error'
						),
						// Please note: When you enable ajax validation, make sure the corresponding
						// controller action is handling ajax validation correctly.
						// See class documentation of CActiveForm for details on this,
						// you need to use the performAjaxValidation()-method described there.
						'enableAjaxValidation' => false,
						'errorMessageCssClass' => 'help-block',
						'htmlOptions' => array(
							'class' => '',
						),
						));
						/* @var $form TbActiveForm */
						?>
						<div class="col-xs-12 col-sm-2 mb10">
							<label class="control-label">Trip Type</label>
							<?php
							$tripTypeList			 = ([9 => 'Day Rental 4-40',10 => 'Day Rental 8-80', 11 => 'Day Rental 12-120']);

							$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'prr_trip_type',
									'val'			 => $model->prr_trip_type,
									'data'			 => $tripTypeList,
									'options'		 => array('allowClear' => true),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder'	 => 'Select Trip Type')
								));
							?>
						</div>
						
						<div class="col-xs-12 col-sm-2 mb10">
							<label class="control-label">Cab Type</label>
							<?php
							$dataCatType = VehicleTypes::model()->getJSON($carType);
							$this->widget('booster.widgets.TbSelect2', array(
												   'model'          => $model,
												   'attribute'      => 'apr_cab_type',
												   'val'            => $model->apr_cab_type,
												   'asDropDownList' => FALSE,
												   'options'        => array('data' => new CJavaScriptExpression($dataCatType), 'allowClear' => true),
												   'htmlOptions'    => array('style' => 'width:100%', 'placeholder' => 'Select Cab Type')
											   ));
							?>
						</div>
						
						<div class="col-xs-12 col-sm-2 mb10">
							<label class="control-label">Area Type</label>
							<?php
							$areaTypeList	 = AreaPriceRule::model()->areatype;
							$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'areaType',
									'val'			 => $model->areaType,
									'data'			 => $areaTypeList,
									'options'		 => array('allowClear' => true),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder'	 => 'Select Area Type')
								));
							?>
						</div>

						<div class="col-xs-12 col-sm-2 mb10">
							<label class="control-label">State</label>
							<?php
							$dataState	 = VehicleTypes::model()->getJSON($stateList);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'cty_state_id',
								'val'			 => $model->cty_state_id,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataState), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select State')
							));
							?>
						</div>

						<div class="col-xs-12 col-sm-2 mb10">
							<label class="control-label">Zone</label>
									<?php
										$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
										'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
										'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
										'openOnFocus'		 => true, 'preload'			 => false,
										'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
										'addPrecedence'		 => false,];
										$this->widget('ext.yii-selectize.YiiSelectize', array(
											'model'				 => $model,
											'attribute'			 => 'sourcezone',
											'useWithBootstrap'	 => true,
											"placeholder"		 => "Select Zone",
											'fullWidth'			 => false,
											'options'			 => array('allowClear' => true),
											'htmlOptions'		 => array('width'	 => '100%'),
											'defaultOptions'	 => $selectizeOptions + array(
										'onInitialize'	 => "js:function(){
														populateZone(this, '{$model->sourcezone}');
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
						<div class="col-xs-12 col-md-2 mb10">
							<label class="control-label">City</label>
							<?php
                            $this->widget('ext.yii-selectize.YiiSelectize', array(
                                'model'            => $model,
                                'attribute'        => 'city_id',
                                'useWithBootstrap' => true,
                                "placeholder"      => "City",
                                'fullWidth'        => false,
                                'htmlOptions'      => array('width' => '100%',
                                //  'id' => 'from_city_id1'
                                ),
                                'defaultOptions'   => $selectizeOptions + array(
                            'onInitialize' => "js:function(){
                                  populateSourceCity(this, '{$model->city_id}');
                                                }",
                            'load'         => "js:function(query, callback){
                                loadSourceCity(query, callback);
                                }",
                            'render'       => "js:{
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
						<div class="col-xs-12 col-md-1 mt20">
							<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary', 'value' => 'Search')); ?>
						</div>	
							<?php $this->endWidget(); ?>
						<div class="col-xs-12 col-md-2 mt20">	
							<?php
							$checkExportAccess = Yii::app()->user->checkAccess("exportRate");
							if ($checkExportAccess)
							{
								//CHtml::beginForm(Yii::app()->createUrl('admin/report/booking'), "post", ['style' => "margin-bottom: 10px;"]);
								?>
								<?= CHtml::beginForm(Yii::app()->createUrl('admin/rate/dayRentalPrice'), "post", ['style' => "margin-bottom: 10px;"]); ?>
								<input type="hidden" id="export2" name="export2" value="true"/>
								<input type="hidden" id="export_triptype" name="export_triptype" value="<?= $model->prr_trip_type ?>"/>
								<input type="hidden" id="export_cabtype" name="export_cabtype" value="<?= $model->apr_cab_type ?>"/>
								<input type="hidden" id="export_areatype" name="export_areatype" value="<?= $model->areaType ?>"/>
								<input type="hidden" id="export_stateid" name="export_stateid" value="<?= $model->cty_state_id ?>"/>
								<input type="hidden" id="export_zone" name="export_zone" value="<?= $model->sourcezone ?>"/>
								<input type="hidden" id="export_cityid" name="export_cityid" value="<?= $model->city_id ?>"/>
								<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
								<?php
								echo CHtml::endForm();
							}
							?>
						</div>
					</div>
					
                </div>
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
					array('name'	 => 'date', 'value'	 =>
						function ($data) {
							$areatype	 = AreaPriceRule::model()->areatype;
							echo $areatype[$data["apr_area_type"]];
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header' => 'Area Type'),
					array('name'	 => 'apr_area_id', 'value'	 =>
						function ($data) {
							$areatype	 = AreaPriceRule::getNameByData($data['apr_area_id'], $data['apr_area_type']);
							echo $areatype;
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header' => 'Area Name'),	
					array('name'	 => 'prr_trip_type', 'value'	 =>
						function ($data) {
							switch ($data['prr_trip_type'])
							{
								case '9':
									echo "Day Rental 4-40";
									break;
								case '10':
									echo "Day Rental 8-80";
									break;
								case '11':
									echo "Day Rental 12-120";
									break;
								default:
									break;
							}
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header' => 'Trip Type'),
				array('name' => 'prr_cab_type', 'value' => 
						function ($data) {
							$catName = SvcClassVhcCat::model()->getVctSvcList("string", 0, 0, $data['prr_cab_type']);
							echo $catName;
						}, 
						'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header' => 'Cab Type'),		
				array('name' => 'prr_rate_per_km', 'value' => '$data["prr_rate_per_km"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'htmlOptions'		 => array('class' => 'text-right'), 'header' => 'Rate Per Km'),
				array('name' => 'prr_rate_per_minute', 'value' => '$data["prr_rate_per_minute"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'htmlOptions'		 => array('class' => 'text-right'), 'header' => 'Rate Per Minute'),
				array('name' => 'prr_rate_per_km_extra', 'value' => '$data["prr_rate_per_km_extra"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'htmlOptions'		 => array('class' => 'text-right'), 'header' => 'Rate Per Km Extra'),
				array('name' => 'prr_rate_per_minute_extra', 'value' => '$data["prr_rate_per_minute_extra"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'htmlOptions'		 => array('class' => 'text-right'), 'header' => 'Rate Per Minute Extra'),
				array('name' => 'prr_min_km', 'value' => '$data["prr_min_km"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'htmlOptions'		 => array('class' => 'text-center'), 'header' => 'Minimum Kilometer'),
				array('name' => 'prr_min_duration', 'value' => '$data["prr_min_duration"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'htmlOptions'		 => array('class' => 'text-center'), 'header' => 'Minimum Duration'),
				array('name' => 'prr_min_base_amount', 'value' => '$data["prr_min_base_amount"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'htmlOptions'		 => array('class' => 'text-right'), 'header' => 'Minimum Base Amount'),
				array('name' => 'prr_min_km_day', 'value' => '$data["prr_min_km_day"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'htmlOptions'		 => array('class' => 'text-center'), 'header' => 'Minimum Km Day'),
				array('name' => 'prr_max_km_day', 'value' => '$data["prr_max_km_day"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Maximum Km Day'),
				array('name' => 'prr_day_driver_allowance', 'value' => '$data["prr_day_driver_allowance"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'htmlOptions'		 => array('class' => 'text-right'), 'header' => 'Day Driver Allowance'),
				array('name' => 'prr_night_driver_allowance', 'value' => '$data["prr_night_driver_allowance"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'htmlOptions'		 => array('class' => 'text-right'), 'header' => 'Night Driver Allowance'),
				array('name' => 'prr_driver_allowance_km_limit', 'value' => '$data["prr_driver_allowance_km_limit"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'htmlOptions'		 => array('class' => 'text-center'), 'header' => 'Driver Allowance Km Limit'),
//				array('name' => 'prr_id', 'value' => '$data["prr_id"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'htmlOptions'		 => array('class' => 'text-center'), 'header' => 'Id')
				array(
								'header'			 => 'Action',
								'class'				 => 'CButtonColumn',
								'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
								'template'			 => '{log}',
								'buttons'			 => array(
									'log'			 => array(
										'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data)
                                                    {

                                                        var box = bootbox.dialog({
                                                            message: data,
                                                            title: \'Price Rule Log\',
															size: \'large\',
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
										'url'		 => 'Yii::app()->createUrl("admin/pricerule/showlog", array("prrid" => $data["apr_id"]))',
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