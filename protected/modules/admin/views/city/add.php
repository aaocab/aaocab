<style>
    .select2-container-multi .select2-choices {
        min-height: 50px;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
</style>
<?php
$version			 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/city.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/zone.js?v=' . $version);
$autoAddressJSVer	 = Yii::app()->params['autoAddressJSVer'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/aao/hyperLocation.js?v=$autoAddressJSVer");
$stateList			 = array("" => "Select state") + CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
if ($model->cty_id > 0)
{
	$stateData				 = Cities::findStateNameById("", $model->cty_id);
	$model->cty_state_name	 = $stateData['stt_name'];
}
?>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-8 pb10 new-booking-list" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12">
			<?php
			//echo "<span style='color:#ff0000;'>Add / edit feature is disabled. Contact IT Department</span>";
			if ($status == "emlext")
			{
				echo "<span style='color:#ff0000;'>This email address is already registered. Please try again using a new email address.</span>";
			}
			elseif ($status == "added")
			{
				echo "<span style='color:#00aa00;'>Driver added successfully.</span>";
			}
			else
			{
				//do nothing
			}
			if (Yii::app()->user->hasFlash('success'))
			{
				echo Yii::app()->user->getFlash('success');
			}
			?>
        </div>
        <div class="row">

            <div class="col-xs-12">
				<?php
				$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'city-manage-form', 'enableClientValidation' => TRUE,
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
						'class' => 'form-horizontal'
					),
				));
				/* @var $form TbActiveForm */
				?>
                <div class="panel panel-default">
					<div class="panel panel-heading">ADD CITY</div>
					<div class="panel-body">
						<?php echo CHtml::errorSummary($model); ?>
                        <div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6">
								<?= $form->textFieldGroup($model, 'cty_name', array('widgetOptions' => array('htmlOptions' => ['class' => "form-control"]))) ?>
								<span id="errorctyname" style="color:#da4455"></span>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
								<?= $form->textFieldGroup($model, 'cty_alias_name', array()) ?>
                            </div>
							<div class="col-xs-12 col-sm-6 col-md-6">
								<? //= $form->textFieldGroup($model, 'cty_state_name', array('widgetOptions' => array('htmlOptions' => []))) ?>
                                <div class="form-group">
									<label class="control-label">State </label>
									<?php
									//$regionList			 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'cty_state_id',
										'val'			 => $model->cty_state_id,
										//'asDropDownList' => FALSE,
										'data'			 => States::model()->getStateList1(),
										//'options' => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
										'htmlOptions'	 => array('class'			 => 'p0',
											'style'			 => 'width: 100%', 'placeholder'	 => 'Select State')
									));
									?>
								</div>
								<div id="errorstate" class="mt0" style="color:#da4455"> </div>
							</div>


							<div class="col-xs-12 col-md-6">
								<?= $form->textAreaGroup($model, 'cty_garage_address', array('widgetOptions' => array('htmlOptions' => ['class' => "form-control autoComCities", "autocomplete" => "section-new"]))) ?>
                            </div>


                            <div class="col-xs-12 col-sm-6 col-md-6">
								<? //= $form->hiddenField($model, 'vhd_temp_id')  ?> 
								<?= $form->textFieldGroup($model, 'cty_county', array('widgetOptions' => array('htmlOptions' => ["readonly" => "readonly"]))) ?>
                            </div>
							<div class="col-xs-12 col-sm-6 col-md-6">
								<?= $form->textFieldGroup($model, 'cty_radius', array('widgetOptions' => array('htmlOptions' => ['class' => "form-control"]))) ?>
								<span id="errorctyname" style="color:#da4455"></span>
                            </div>
							<div class="col-xs-12 col-sm-6 col-md-6">
								<?= $form->textFieldGroup($model, 'cty_bounds', array('widgetOptions' => array('htmlOptions' => ['class' => "form-control"]))) ?>
								<span id="errorctyname" style="color:#da4455"></span>
                            </div>
							 <div class="col-md-4 p10">
								<?= $form->dropDownListGroup($model, 'cty_poi_type', ['label' => 'POI Type (1: Railway  2: Bus stop)', 'widgetOptions' => ['data' => ['0' => '--select--', '1' => 'Railway', '2' => 'Bus stop'], 'htmlOptions' => []]]) ?>
							</div>
                            <div class="col-xs-12 col-sm-6 ">
								<?= $form->textFieldGroup($model, 'cty_lat', array('widgetOptions' => array('htmlOptions' => ["readonly" => "readonly"]))) ?>
							</div>
							<div class="col-xs-12 col-sm-6 ">
								<?= $form->textFieldGroup($model, 'cty_long', array('widgetOptions' => array('htmlOptions' => ["readonly" => "readonly"]))) ?>
							</div>
                           
                        </div>

						<? //= $form->textAreaGroup($model, 'cty_short_desc', array('label' => ''))    ?>
						<? //= $form->textFieldGroup($model, 'cty_group', array('label' => ''))     ?>
						<?= $form->hiddenField($model, 'cty_place_id') ?>  
						<div class="row">                           
							<div class="col-xs-3 col-md-3 p10">
								<?= $form->checkboxGroup($model, 'cty_has_airport', array()) ?>
							</div>
							<div class="col-xs-3 col-md-3 p10">
								<?= $form->checkboxGroup($model, 'cty_is_airport', array()) ?>
							</div>

							<div class="col-xs-3    p10 ">
								<?= $form->checkboxGroup($model, 'cty_is_poi', array()) ?>
							</div>

						</div> 
						<div class="row">
							<!--<div class="col-xs-12 col-md-6 ">
							<? //= $form->textAreaGroup($model, 'cty_garage_address', array())   ?>
								</div>-->
							<div class="col-xs-12 col-md-6">
								<?= $form->textAreaGroup($model, 'cty_city_desc', array()) ?>
							</div>
							<div class="col-xs-12 col-md-6">
								<?= $form->textAreaGroup($model, 'cty_pickup_drop_info', array()) ?>
							</div>

							<div class="col-xs-12 col-md-6">
								<?= $form->textAreaGroup($model, 'cty_ncr', array()) ?>
							</div>
							<div class="col-xs-12 col-md-6">
								<label class="control-label">Zones <span class="required">*</span></label>
								<?php
								$loc			 = Zones::model()->getZoneList();
								$SubgroupArray	 = CHtml::listData(Zones::model()->getZoneList(), 'zon_id', function($loc) {
											return $loc->zon_name;
										});
								$this->widget('booster.widgets.TbSelect2', array(
									'attribute'		 => 'cty_zones',
									'model'			 => $model,
									'data'			 => $SubgroupArray,
									'val'			 => explode(',', $model->cty_zones),
									'htmlOptions'	 => array(
										'multiple'		 => 'multiple',
										'placeholder'	 => 'Zones',
										'width'			 => '100%',
										'style'			 => 'width:100%;'
									),
								));
								?>
								<span class="has-error"><? echo $form->error($model, 'cty_zones'); ?></span>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12 col-md-6">
								<label> Included Cab Categories</label>
								<?php
								$categoryList		 = VehicleCategory::getCat();
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'cty_included_cabCategories',
									'val'			 => explode(',', $model->cty_included_cabCategories),
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
								$tireList			 = ServiceClass::getTier();
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'cty_included_cabtires',
									'val'			 => explode(',', $model->cty_included_cabtires),
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

						<div class="row">
							<div class="col-xs-12 col-md-6">
								<label> Excluded Cab Types</label>
								<?php
								$returnType			 = "list";
								$vehicleList		 = SvcClassVhcCat::getVctSvcList($returnType);
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'cty_excluded_cabtypes',
									'val'			 => explode(',', $model->cty_excluded_cabtypes),
									'data'			 => $vehicleList,
									'htmlOptions'	 => array(
										'multiple'		 => 'multiple',
										'placeholder'	 => 'Select Cab Types',
										'width'			 => '100%',
										'style'			 => 'width:100%',
									),
								));
								?>

							</div>
							<div class="col-xs-12 col-sm-6">
								<div class="row">
									<div class="col-xs-12 "><label> Included Cab Model(s): </label>
									</div>
									<div class="col-xs-12">
										<?php
										$returnType			 = "list";
										$vehicleModelList	 = VehicleTypes::getVehicleTypeList1();
										//print_r($vehicleModelList);
										/* edit code */
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'cty_included_cabmodels',
											'val'			 => explode(',', $model->cty_included_cabmodels),
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
							<div class="col-xs-12 col-md-6"> 
								<label> Keywords</label>
								<?php
								$loc2				 = Keywords::model()->getKeyList();
								$SubgroupArray2		 = CHtml::listData(Keywords::model()->getKeyList(), 'keyword_id', function($loc2) {
											return $loc2['keyword_name'];
										});
								$model->cty_keyword_names	 = array_intersect_key($SubgroupArray2, array_flip(explode(',', $model->cty_keyword_names)));
								$this->widget('booster.widgets.TbSelect2', array(
									'asDropDownList' => false,
									'name'			 => 'cty_keyword_names',
									'model'			 => $model,
									'data'			 => $SubgroupArray2,
									'value'			 => implode(',', $model->cty_keyword_names),
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
<?php echo CHtml::endForm(); ?>
<script type="text/javascript">
    var hyperModel = new HyperLocation();
    $(document).ready(function () {

        var availableTags = [];
        var front_end_height = $(window).height();
        var footer_height = $(".footer").height();
        var header_height = $(".header").height();
        $("#errorctyname").text('');
        $("#errorstate").text('');
        hyperModel.initializeplCities();

    });

    var $cityexist = false;
    $('#<?= CHtml::activeId($model, "cty_state_id") ?>').bind("change", function () {
        checkExisting();
    });

    $('#<?= CHtml::activeId($model, "cty_name") ?>').bind("change", function () {
        checkExisting();
        populateZones();
    });

    $(document).on("getLatitudeLongitude", function (event, data) {
        latitudelongitude(data);
    });

    function checkExisting() {
        stateVal = $('#<?= CHtml::activeId($model, "cty_state_id") ?>').select2('data');
        if (stateVal) {
            state = $('#<?= CHtml::activeId($model, "cty_state_id") ?>').select2('data').id;
            stateName = $('#<?= CHtml::activeId($model, "cty_state_name") ?>').val();//$('#<? //= CHtml::activeId($model, "cty_state_id")     ?>').select2('data').text;
            city_temp_name = $('#<?= CHtml::activeId($model, "cty_temp_name") ?>').val();
            cityName = $('#<?= CHtml::activeId($model, "cty_name") ?>').val();
            if (state != '' && cityName != '')
            {
                var city = new City();
                var model = {};
                model.city = cityName;
                model.state = state;
                model.id = '<?= $_REQUEST['ctyid'] ?>';
                city.model = model;
                $(document).on("checkCityName", function (event, data) {
                    checkCities(data);
                });
                city.checkCityName();
            } else {
                $("#errorctyname").text('');
                $cityexist = false;
            }

            garageAddress = cityName + ", " + stateName + ", India";
            $('#<?= CHtml::activeId($model, "cty_garage_address") ?>').val(garageAddress).change();

            cityGarageAddress = $('#<?= CHtml::activeId($model, "cty_garage_address") ?>').val();
            var $cityLatLong = false;
            if (cityGarageAddress != '')
            {
                var city = new City();
                var model = {};
                model.cityGarageAddress = cityGarageAddress;
                if (model.cityGarageAddress == "")
                {
                    return;
                }
                city.model = model;

                city.getLatitudeLongitude();
            }

            if (state == '') {
                $("#errorstate").text('');
                $("#errorstate").text('Please select a state');
            } else {
                $("#errorstate").text('');
            }

//            if (city_temp_name.toLowerCase() != city.toLowerCase()) {
//                if (state != '' && city != '') {
//
//                    var city = new City();
//                    var model = {};
//                    model.state = state;
//                    model.city = city;
//                    city.model = model;
//                    $(document).on("checkCityName", function (event, data) {
//                        checkCities(data);
//                    });
//                    city.checkCityName();
//                }
//            } else {
//                $("#errorctyname").text('');
//                $cityexist = false;
//            }
        } else {
            $("#errorstate").text('');
            $("#errorstate").text('Please select a state');
        }
    }

    function checkCities(data)
    {
        if (data.success) {
            $("#errorctyname").text('');
            $("#errorctyname").text('City name is already added');
            $cityexist = true;
        } else {
            $("#errorctyname").text('');
            $cityexist = false;
        }
    }

    function latitudelongitude(data)
    {
        $('#<?= CHtml::activeId($model, "cty_lat") ?>').val(data.data.model.latitude).change();
        $('#<?= CHtml::activeId($model, "cty_long") ?>').val(data.data.model.longitude).change();
        $('#<?= CHtml::activeId($model, "cty_place_id") ?>').val(data.data.model.placeid).change();
    }

    $('#city-manage-form').submit(function (event) {

        if ($cityexist)
        {
            event.preventDefault();
        }
        var cityLatitude = $('#Cities_cty_lat').val();
        var cityLongitude = $('#Cities_cty_long').val();
        var cityGarageAddress = $('#Cities_cty_garage_address').val();
        if ((cityLatitude != '' && cityLongitude != '') || cityGarageAddress != '')
        {

        } else {
            alert('Please Enter Either City Lat/long Or Garage Address');
            event.preventDefault();
        }

    });


    $('#Cities_cty_name').change(function () {
        populateZones();
    });

    $('#Cities_cty_long').change(function () {
        populateZones();
    });


    $(document).on("getCityZoneList", function (event, data) {
        getZone(data);
    });

    $fireChange = true;
    function populateZones()
    {
        if (!$fireChange)
        {
            return false;
        }
        var zone = new Zone();
        var model = {};
        model.cityLatitude = $('#Cities_cty_lat').val();
        model.cityLongitude = $('#Cities_cty_long').val();
        model.city = $('#Cities_cty_name').val();
        zone.model = model;
        if (model.city !== "", model.cityLongitude !== "", model.cityLatitude !== "")
        {
            zone.getCityZoneList();
        }
    }

    function getZone(data)
    {
        $('#cty_zones').select2().val(null).trigger("change");
        $('#cty_zones').select2().val(data.data.ctyzone["0"]).trigger("change");
    }
</script>
<?php
//$api = Yii::app()->params['googleBrowserApiKey']; 
$api						 = Config::getGoogleApiKey('browserapikey');
?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>