<?php
$version	 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/route.js?v=' . $version);
$callback	 = Yii::app()->request->getParam('callback', 'loadList');
$title		 = ($model->isNewRecord) ? "Add" : "Edit";
$js			 = "window.$callback();";

Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
//$cityList	 = CHtml::listData(Cities::model()->findAll(array('order' => 'cty_name', 'condition' => 'cty_active=:act', 'params' => array(':act' => '1'))), 'cty_id', 'cty_name');
$rtime = $model->estmTime();
if (Yii::app()->request->getParam('rid') != "")
{
	$readonly = true;
}
else
{
	$readonly = false;
}
if (Yii::app()->request->isAjaxRequest)
{
	$cls = "";
}
else
{
	$cls = "col-lg-6 col-md-8 col-sm-12 pb10";
}
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
    .tt-suggestion {
        font-size: 1.2em;
        line-height: 0.7em;
        padding: 0;
        margin:  0;
    }
    /*    .form-control{
			padding: 0px 10px!important;
		}*/
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
</style>

<div class="row">
    <div class="<?= $cls ?> new-booking-list" style="float: none; margin: auto">
		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array
			(
			"id"					 => "route-form",
			"enableClientValidation" => true,
			"clientOptions"			 => array
				(
				"validateOnSubmit"	 => true,
				"errorCssClass"		 => "has-error",
				"afterValidate"		 => 'js:function(form,data,hasError)
			{
				console.log(form.serialize());
                if(!hasError)
				{
                    $.ajax(
					{
                        "type":"POST",
                        "dataType":"json",
                        "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                        "data":form.serialize(),
                        "success":function(response)
						{
                            if(!$.isEmptyObject(response) && response.success==true)
							{
								alert(response.message);
                                $rut_id = response.id;
                                ' . $js . '
                            }
                            else
							{
                                alert(response.message);
                                settings=form.data(\'settings\');
                                var data = response.data;
                                
								$.each (settings.attributes, function (i) 
								{
                                    $.fn.yiiactiveform.updateInput (settings.attributes[i], data, form);
                                });
                                            
								$.fn.yiiactiveform.updateSummary(form, response);
                            }
						},
                    });
                }
            }'
			),
			// Please note: When you enable ajax validation, make sure the corresponding
			// controller action is handling ajax validation correctly.
			// See class documentation of CActiveForm for details on this,
			// you need to use the performAjaxValidation()-method described there.
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array
				(
				'class' => 'form-horizontal',
			),
		));
		/* @var $form TbActiveForm */
		?>
        <div class="panel panel-white">
            <div class="panel-body">
				<?php echo CHtml::errorSummary($model); ?>
                <div class="has-error" id="duplicate_err" style="display: none">Route already exist.
                </div>
                <div class="form-group">
                    <div class="text-right">
                        <button class="btn btn-primary " id="addCity">Add City</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-lg-6 mt5">
						<?php
						if ($isNew == 'Add')
						{
							?>
							<div class="form-group cityinput">
								<?php
//                        $this->widget('booster.widgets.TbSelect2', array(
//                            'model' => $model,
//                            'attribute' => 'rut_from_city_id',
//                            'val' => $model->rut_from_city_id,
//                            'asDropDownList' => FALSE,
//                            'options' => array('data' => new CJavaScriptExpression($datacity)),
//                            'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Source City', 'readonly' => $readonly, 'title' => "Route From"),
//                        ));


								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'rut_from_city_id',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Source City",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('width'	 => '100%',
										'id'	 => 'Route_rut_from_city_id'
									),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
                                  populateSource(this, '{$model->rut_from_city_id}' );
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
								<?php echo $form->error($model, 'rut_from_city_id'); ?>

							</div>
							<?
							}
							else
							{
							echo $form->hiddenField($model, 'rut_from_city_id');
							$ctyFrom = Cities::getName($model->rut_from_city_id);
							?>
							<div class="form-control"><?= $ctyFrom ?></div>
							<? }
							?>
							<input type="hidden" id="selectedFromID" value="<?= $model->rut_from_city_id ?>">
						</div>
						<div class="col-xs-12 c0l-sm-8 col-lg-6 mt5 form-group">
							<?php
							if ($isNew == 'Add')
							{
								?>
								<div class="form-group cityinput">
									<?php
//                        $this->widget('booster.widgets.TbSelect2', array(
//                            'model' => $model,
//                            'attribute' => 'rut_to_city_id',
//                            'val' => $model->rut_to_city_id,
//                            'asDropDownList' => FALSE,
//                            'options' => array('data' => new CJavaScriptExpression($datacity)),
//                            'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Destination City', 'readonly' => $readonly, 'title' => "Route To"),
//                        ));


									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'rut_to_city_id',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Destination City",
										'fullWidth'			 => false,
										'htmlOptions'		 => array('width'	 => '100%',
											'id'	 => 'Route_rut_to_city_id'
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
                                  populateSource(this, '{$model->rut_to_city_id}' );
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
									<?php echo $form->error($model, 'rut_to_city_id'); ?>
								</div>
								<?
								}
								else
								{
								echo $form->hiddenField($model, 'rut_to_city_id');
								$ctyTo = Cities::getName($model->rut_to_city_id);
								?>
								<div class="form-control"><?= $ctyTo ?></div>
								<? }
								?>
								<input type="hidden" id="selectedToID" value="<?= $model->rut_to_city_id ?>">

							</div>

							<div class="col-xs-12 col-sm-6 mt5">
								<label>Route Actual Distance :</label>
								<?= $form->textFieldGroup($model, 'rut_actual_distance', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('readonly' => true)))) ?>
							</div>
							<div class="col-xs-12 col-sm-6 mt5">
								<label>Route Actual Time :</label>
								<?= $form->textFieldGroup($model, 'rut_actual_time', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('readonly' => true)))) ?>
							</div>

							<div class="col-xs-12 col-sm-6 mt5 hide">
								<label>Route Estimate Distance (Expected):</label>
								<?= $form->textFieldGroup($model, 'rut_estm_distance_exp', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('readonly' => true)))) ?>
							</div>
							<div class="col-xs-12 col-sm-6 mt5 hide">
								<label>Route Estimate Time (Expected): </label>
								<?= $form->textFieldGroup($model, 'rut_estm_time_min', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('readonly' => true)))) ?>
							</div>
							<div class="col-xs-12 col-sm-6 mt5">
								<label>Route Estimate Distance (In Km):</label>
								<?= $form->textFieldGroup($model, 'rut_estm_distance', array('label' => '', 'widgetOptions' => array())) ?>
							</div>
							<div class="col-xs-12 col-sm-6 mt5">
								<label>Route Estimate Time (In Mins):</label>
								<?= $form->textFieldGroup($model, 'rut_estm_time', array('label' => '')) ?>
							</div>

							<div class="col-xs-12 col-sm-6 mt5">
								<label>Route Path: </label>
								<?= $form->textFieldGroup($model, 'rut_name', array('label' => '')) ?>
							</div>
							<div class="col-xs-12 col-sm-6 mt5">
								<label>Route Special Remarks: </label>
								<?= $form->textAreaGroup($model, 'rut_special_remarks', array('label' => '')) ?>
							</div>

							<div class="col-xs-12 col-sm-6">
								<div class="row">
									<div class="col-xs-12 "><label> Keywords: </label>
									</div>
									<div class="col-xs-12"> <?php
										$loc2			 = Keywords::model()->getKeyList();
										$SubgroupArray2	 = CHtml::listData(Keywords::model()->getKeyList(), 'keyword_id', function($loc2) {
													return $loc2['keyword_name'];
												});
										$model->rut_keyword_names	 = array_intersect_key($SubgroupArray2, array_flip(explode(',', $model->rut_keyword_names)));
										$this->widget('booster.widgets.TbSelect2', array(
											'asDropDownList' => false,
											'name'			 => 'rut_keyword_names',
											'model'			 => $model,
											'data'			 => $SubgroupArray2,
											'value'			 => implode(',', $model->rut_keyword_names),
											'options'		 => array(
												'tags'				 => array_values($SubgroupArray2),
												'placeholder'		 => 'Keywords',
												'width'				 => '100%',
												'tokenSeparators'	 => array(',', ' ')
											)
												)
										);
										?>
									</div>
								</div>
							</div>

							<div class="col-xs-12 col-sm-6 mt5">
								<label>Route Title: </label>
								<?= $form->textFieldGroup($model, 'rut_title', array('label' => '')) ?>
							</div>



							<div class="row">
								<div class="col-xs-12 col-sm-6 mt5 ">
									<?= $form->checkboxGroup($model, 'rut_is_promo_code_apply', array()) ?>
								</div>
								<div class="col-xs-12 col-sm-6 mt5">
									<?= $form->checkboxGroup($model, 'rut_is_promo_gozo_coins_apply', array()) ?>
								</div>
								<div class="col-xs-12 col-sm-6 mt5">

									<?= $form->checkboxGroup($model, 'rut_is_cod_apply', array()) ?>

								</div>
							</div>

							<div class="col-xs-12 col-md-12">
								<div class="row">
									<div class="col-xs-12 col-md-6"> 
										<label> Included Cab Categories</label>
										<?php
										$categoryList				 = VehicleCategory::getCat();
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'rut_included_cabCategories',
											'val'			 => explode(',', $model->rut_included_cabCategories),
											'data'			 => $categoryList,
											'htmlOptions'	 => array(
												'multiple'		 => 'multiple',
												'placeholder'	 => 'Select Categories',
												'width'			 => '100%',
												'style'			 => 'width:100%',
											),
										));
										?>
									</div>

									<div class="col-xs-12 col-md-6"> 
										<label>  Included Cab Tire</label>
										<?php
										$tireList					 = ServiceClass::getTier();
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'rut_included_cabtires',
											'val'			 => explode(',', $model->rut_included_cabtires),
											'data'			 => $tireList,
											'htmlOptions'	 => array(
												'multiple'		 => 'multiple',
												'placeholder'	 => 'Select Tire',
												'width'			 => '100%',
												'style'			 => 'width:100%',
											),
										));
										?>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6">
								<div class="row">
									<div class="col-xs-12 "><label> Excluded Cab Types: </label>
									</div>
									<div class="col-xs-12">
										<?php
										$returnType					 = "list";
										$vehcleList					 = SvcClassVhcCat::getVctSvcList($returnType);
										/* edit code */
										$this->widget("booster.widgets.TbSelect2", array
											(
											"model"			 => $model,
											"attribute"		 => "rut_excluded_cabtypes",
											"val"			 => explode(",", $model->rut_excluded_cabtypes),
											// 'asDropDownList' => FALSE,
											"data"			 => $vehcleList,
											//  'options' => array('data' => new CJavaScriptExpression($datacity)),
											"htmlOptions"	 => array
												(
												"multiple"		 => "multiple",
												"placeholder"	 => "Select Cab Types",
												"width"			 => "100%",
												"style"			 => "width:100%",
											),
										));
										?>
									</div>
								</div>
							</div>

							<div class="col-xs-12 col-sm-6">
								<div class="row">
									<div class="col-xs-12 "><label> Included Cab Model(s): </label>
									</div>
									<div class="col-xs-12">
										<?php
										$returnType					 = "list";
										$vehicleModelList			 = VehicleTypes::getVehicleTypeList1();
										//print_r($vehicleModelList);
										/* edit code */
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'rut_included_cabmodels',
											'val'			 => explode(',', $model->rut_included_cabmodels),
											'data'			 => $vehicleModelList,
											'htmlOptions'	 => array(
												'multiple'		 => 'multiple',
												'placeholder'	 => 'Select Cab Models',
												'width'			 => '100%',
												'style'			 => 'width:100%',
											),
										));
										?>
									</div>
								</div>
							</div>

							<div class="col-xs-12 col-sm-12 mt5">
								<label>Route Description: </label>
								<?= $form->textAreaGroup($model, 'rut_info', array('label' => '')) ?>
							</div>

						</div>

						<?= $form->hiddenField($model, 'rut_return_name', array('label' => '')) ?>
						<?php
						if ($_REQUEST['rid'] == '')
						{
							?>
							<input type="checkbox" name="returncheck" id="returncheck"  value="1">
							Check this to add return route also
						<?php } ?>
					</div>
					<div class="panel-footer" style="text-align: center">
						<?php echo CHtml::submitButton($isNew, array('class' => 'btn btn-primary')); ?>
					</div>
				</div>
				<?php $this->endWidget(); ?>
			</div>
		</div>

		<?php echo CHtml::endForm(); ?>
		<script src="<?= (Yii::app()->request->getIsSecureConnection() ? "https" : "http") ?>://maps.google.com/maps?file=api&v=2&sensor=false" 
		type="text/javascript"></script> 
		<script type="text/javascript">
		    $rut_id = '';
		    $(document).ready(function () {
		        getCitynames();
		        var front_end_height = $(window).height();
		        var footer_height = $(".footer").height();
		        var header_height = $(".header").height();
		    });
		    $('#<?= CHtml::activeId($model, "rut_from_city_id") ?>').change(function () {
		        if ('<?= $isNew ?>' == 'Add') {
		            setroute();
		        } else {
		            setroute1();
		        }

		    });
		    $('#<?= CHtml::activeId($model, "rut_to_city_id") ?>').change(function () {
		        if ('<?= $isNew ?>' == 'Add') {
		            setroute();
		        } else {
		            setroute1();
		        }

		    });

		    function setroute1()
		    {
		        model.city1 = $('#<?= CHtml::activeId($model, "rut_from_city_id") ?>').val();
		        model.city2 = $('#<?= CHtml::activeId($model, "rut_to_city_id") ?>').val();
		        $(document).on("getRouteDistTime", function (event, data) {
		            routeDistanceTime(data);
		        });
		        city.getRouteDistTime();
		    }
		    function setroute()
		    {
		        var city = new City();
		        var model = {};
		        model.city1 = $('#<?= CHtml::activeId($model, "rut_from_city_id") ?>').val();
		        model.city2 = $('#<?= CHtml::activeId($model, "rut_to_city_id") ?>').val();
		        // fromCity1 = $('#<? //= CHtml::activeId($model, "rut_from_city_id")                                       ?>').select2('data').text;

		        var selectFrom = $('#<?= CHtml::activeId($model, "rut_from_city_id") ?>').selectize({});
		        var selectize1Control = selectFrom[0].selectize;
		        var fromCity1 = selectize1Control.getItem(selectize1Control.getValue()).text();
		        var lowerIndex1 = (fromCity1.indexOf('(') == -1) ? fromCity1.indexOf(',') : fromCity1.indexOf('(')
		        model.fromCity = fromCity1.substr(0, lowerIndex1);
		        if ($('#<?= CHtml::activeId($model, "rut_to_city_id") ?>').selectize({}) != null) {

		            //  toCity2 = $('#<? //= CHtml::activeId($model, "rut_to_city_id")                                     ?>').select2('data').text;


		            var selectTo = $('#<?= CHtml::activeId($model, "rut_to_city_id") ?>').selectize({});
		            var selectize2Control = selectTo[0].selectize;
		            var toCity2 = selectize2Control.getItem(selectize2Control.getValue()).text();
		            var lowerIndex2 = (toCity2.indexOf('(') == -1) ? toCity2.indexOf(',') : toCity2.indexOf('(');
		            model.toCity = toCity2.substr(0, lowerIndex2);
		        }

		        city.model = model;
		        if (model.fromCity != '' && model.toCity != '')
		        {
		            $(document).on("getRouteDistTime", function (event, data) {
		                routeDistanceTime(data);
		            });
		            city.getRouteDistTime();
		        }

		        if (model.city1 > 0 && model.city2 > 0) {
		            $(document).on("getRouteName", function (event, data) {
		                routeName(data);
		            });
		            city.getRouteName();
		        }
		    }

		    function routeDistanceTime(data)
		    {
		        $('#<?= CHtml::activeId($model, "rut_estm_distance") ?>').val(data.data.distance);
		        $('#<?= CHtml::activeId($model, "rut_estm_time") ?>').val(data.data.duration);
		    }

		    function routeName(data)
		    {
		        if (data['success'] == 1)
		        {
		            $('#duplicate_err').hide();
		            $('#Route_rut_name').val(data.data['route']);
		            $('#Route_rut_return_name').val(data.data['return_route']);
		        }
		        if (data['success'] == 0)
		        {
		            $('#duplicate_err').show();
		        }
		    }


		    $("#Route_rut_from_city_id").change(function () {
		        getCitynames();
		    });
		    $("#Route_rut_to_city_id").change(function () {
		        getCitynames();
		    });

		    function getCitynames() {
		        var city = new City();
		        var model = {};
		        model.fromCity = $("#Route_rut_from_city_id").val();
		        model.toCity = $("#Route_rut_to_city_id").val();
		        city.model = model;
		        if (model.fromCity != '' && model.toCity != '') {
		            $(document).on("getCitiesName", function (event, data) {
		                citiesList(data);
		            });
		            city.getCitiesName();
		        }
		    }

		    function citiesList(data)
		    {
		        fromCity = data.data.fromCity;
		        toCity = data.data.toCity;
		        mapInitialize(fromCity, toCity);
		    }


		    function mapInitialize(start, end) {
		        //        var map = new GMap2(document.getElementById("map"));
		        //        var directions = new GDirections(map);
		        //        directions.load("from: " + start + " to: " + end);
		        //        GEvent.addListener(directions, "load", function () {
		        //            // Display the distance from the GDirections.getDistance() method:
		        //            dist = directions.getDistance().meters;
		        // 
		        //            // Display the duration from the GDirections.getDuration() method:
		        //            time1 = directions.getDuration().seconds;
		        //            calculation(dist, time1);
		        //        });

		        $.ajax({
		            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/getapidist')) ?>?start=' + start + '&end=' + end,
		            type: 'GET',
		            dataType: 'json',

		            error: function () {
		                //				callback();
		            },
		            success: function (res) {
		                calculation(res.dist, res.time)
		            }
		        });




		    }
		    function calculation(dist, timea1) {
		        actualDistKm = dist;
		        distkm = actualDistKm + 15;
		        distkm = (Math.ceil(distkm / 10)) * 10;
		        time1 = timea1 * 60;
		        actualTime = Math.ceil(time1 / 60);
		        time1 = time1 + 1800;
		        hours = parseInt(time1 / 3600) % 24;
		        minutes = Math.ceil(time1 / 60) % 60;
		        minutes = (Math.ceil(minutes / 10)) * 10;
		        if (minutes >= 60) {
		            minutes = minutes % 60;
		            hours = hours + 1;
		        }
		        fmin = (hours * 60) + minutes;
		        if (hours != 0 && minutes != 0) {
		            timeformat = hours + " Hr " + minutes + " min";
		        }
		        if (hours == 0 && minutes != 0) {
		            timeformat = minutes + " min";
		        }
		        if (hours != 0 && minutes == 0) {
		            timeformat = hours + " Hr";
		        }

		        $('#Route_rut_actual_distance').val(actualDistKm).change();
		        $('#Route_rut_actual_time').val(actualTime);
		        rutdst = ($('#Route_rut_estm_distance').val() > 0) ? $('#Route_rut_estm_distance').val() : distkm;
		        ruttime = ($('#Route_rut_estm_time').val() > 0) ? $('#Route_rut_estm_time').val() : fmin;
		        $('#Route_rut_estm_distance').val(rutdst);
		        $('#Route_rut_estm_distance_exp').val(distkm);
		        $('#Route_rut_estm_time_min').val(timeformat);
		        $('#Route_rut_estm_time').val(ruttime);

		    }
		    function calculation1(dist, time1) {
		        actualDistKm = Math.ceil(dist / 1000);
		        distkm = actualDistKm + 15;
		        distkm = (Math.ceil(distkm / 10)) * 10;
		        actualTime = Math.ceil(time1 / 60);
		        time1 = time1 + 1800;
		        hours = parseInt(time1 / 3600) % 24;
		        minutes = Math.ceil(time1 / 60) % 60;
		        minutes = (Math.ceil(minutes / 10)) * 10;
		        if (minutes >= 60) {
		            minutes = minutes % 60;
		            hours = hours + 1;
		        }
		        fmin = (hours * 60) + minutes;
		        if (hours != 0 && minutes != 0) {
		            timeformat = hours + " Hr " + minutes + " min";
		        }
		        if (hours == 0 && minutes != 0) {
		            timeformat = minutes + " min";
		        }
		        if (hours != 0 && minutes == 0) {
		            timeformat = hours + " Hr";
		        }

		        $('#Route_rut_actual_distance').val(actualDistKm);
		        $('#Route_rut_actual_time').val(actualTime);
		        rutdst = ($('#Route_rut_estm_distance').val() > 0) ? $('#Route_rut_estm_distance').val() : distkm;
		        ruttime = ($('#Route_rut_estm_time').val() > 0) ? $('#Route_rut_estm_time').val() : fmin;
		        $('#Route_rut_estm_distance').val(rutdst);
		        $('#Route_rut_estm_distance_exp').val(distkm);
		        $('#Route_rut_estm_time_min').val(timeformat);
		        $('#Route_rut_estm_time').val(ruttime);

		    }
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
		    $('#addCity').click(function () {
		        $href = '<?= Yii::app()->createUrl('admin/city/create') ?>';
		        jQuery.ajax({type: 'GET', url: $href,
		            success: function (data) {
		                box = bootbox.dialog({
		                    message: data,
		                    title: 'Add City',
		                    onEscape: function () {
		                        // user pressed escape
		                    },
		                });
		            }
		        });
		    });
		    refreshCity = function () {
		        box.modal('hide');
		        $href = '<?= Yii::app()->createUrl('admin/city/json') ?>';
		        jQuery.ajax({type: 'POST', "dataType": "json", url: $href,
		            success: function (data1) {
		                $data = data1;
		                $('#<?= CHtml::activeId($model, "rut_from_city_id") ?>').select2({data: $data, multiple: false});
		                $('#<?= CHtml::activeId($model, "rut_to_city_id") ?>').select2({data: $data, multiple: false});
		            }
		        });
		    };
		    function loadScript() {
		        GUnload();
		    }
		    loadList = function () {
		<?php
		if ($model->isNewRecord)
		{
			?>
			        location.href = "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/route/list')) ?>";
			<?php
		}
		else
		{
			echo 'alert("Data modified successfully");';
		}
		?>
    };
    window.onload = loadScript;
    $(document).ready(function () {
        $('.bootbox').removeAttr('tabindex');
    });
</script>
<input id="map" type="hidden">