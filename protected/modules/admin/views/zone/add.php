<?php
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
?>
<div class="row">
    <div class="col-lg-offset-1 col-lg-6 col-md-6 col-sm-8 pt20" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12">
			<?php
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
			?>
        </div>
        <div class="row">
            <div class="col-xs-12">
				<?php
				$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'zone-manage-form', 'enableClientValidation' => TRUE,
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
                    <div class="panel-body">
                        <div class="col-xs-12">
							<?php //echo CHtml::errorSummary($model);    ?>                           
							<?= $form->textFieldGroup($model, 'zon_name') ?>
                            <div class="row">
                                <div id="errorzonname" class="mt10 n" style="color:#da4455"></div>
                            </div>
                            <div class="form-group">

								<?php
								//$vendorCity			 = (Cities::model()->getCityArrDistinct());
								$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
									'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
									'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
									'openOnFocus'		 => true, 'preload'			 => false,
									'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
									'addPrecedence'		 => false,];
								?>
								<label>Zone Cities</label>
								<?php
								//echo $model->vnd_city;
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'vnd_city',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select City",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('multiple'		 => 'multiple',
										'placeholder'	 => 'Select Cities',
										'width'			 => '100%',
										'style'			 => 'width:100%',
										'id'			 => 'zone_city_name'
									),
									'selectedValues'	 => ($model->vnd_city != "" ? explode(",", $model->vnd_city) : ''),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
														populateSourceCityForZone(this, '{$model->vnd_city}','{$model->zon_lat}','{$model->zon_long}');
													}",
								'load'			 => "js:function(query, callback){
														loadSourceCityForZone(query, callback,'{$model->zon_lat}','{$model->zon_long}');
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
										//'multiple' =>true, 
								));
								?>
                                <span class="has-error"><?php  echo $form->error($model, 'vnd_city'); ?></span>
                            </div>

                            <div class="row">
								<div class="col-xs-5  pl20"> <label style="font-weight: bold">Latitude </label><?= $form->textFieldGroup($model, 'zon_lat', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Latitude')))) ?></div>    
								<div class="col-xs-5  pl20"> <label style="font-weight: bold">Longitude </label><?= $form->textFieldGroup($model, 'zon_long', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Longitude')))) ?></div>
								<div class="col-xs-5  pl20"> <label style="font-weight: bold">Zone Price Rule </label><?= $form->textFieldGroup($model, 'zon_price_rule', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Price Rule')))) ?></div>
							</div>

                            <div class="col-xs-6  pl20 pr20">
                                <div class="row">
                                    <label style="font-weight: bold">Proposed Rate</label>
                                </div>

                                <div class="row">
                                    <div class="col-xs-10  pl20"> <label style="font-weight: bold">Sedan </label><?= $form->textFieldGroup($model, 'zon_home_median_sedan', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Proposed Sedan Rate', 'readonly' => true)))) ?></div>    
                                </div>

                                <div class="row">
                                    <div class="col-xs-10  pl20"> <label style="font-weight: bold">Compact </label> <?= $form->textFieldGroup($model, 'zon_home_median_compact', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Proposed Compact Rate', 'readonly' => true)))) ?></div> 
                                </div>

                                <div class="row">
                                    <div class="col-xs-10  pl20"><label style="font-weight: bold">SUV </label><?= $form->textFieldGroup($model, 'zon_home_median_suv', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Proposed Suv Rate', 'readonly' => true)))) ?></div>
                                </div>
                            </div>

                            <div class="col-xs-6  pl20 pr20">
                                <div class="row">
                                    <label style="font-weight: bold">Own Rate</label>
                                </div>    

                                <div class="row">
                                    <label style="font-weight: bold">Sedan </label><?= $form->textFieldGroup($model, 'zon_home_sedan_own_rate', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Own Sedan Rate')))) ?>
                                </div>

                                <div class="row">
                                    <label style="font-weight: bold">Compact </label> <?= $form->textFieldGroup($model, 'zon_home_compact_own_rate', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Own Compact Rate')))) ?> 
                                </div>

                                <div class="row">
                                    <label style="font-weight: bold">SUV </label><?= $form->textFieldGroup($model, 'zon_home_suv_own_rate', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Own Suv Rate')))) ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6 mt5 ">
									<?= $form->checkboxGroup($model, 'zon_is_promo_code_apply', array()) ?>
                                </div>
                                <div class="col-xs-12 col-sm-6 mt5">
									<?= $form->checkboxGroup($model, 'zon_is_promo_gozo_coins_apply', array()) ?>
                                </div>
                                <div class="col-xs-12 col-sm-6 mt5">
									<?= $form->checkboxGroup($model, 'zon_is_cod_apply', array()) ?>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-6">
                                <div class="row">
                                    <div class="col-xs-12 "><label> Excluded Cab Types</label>
                                    </div>
                                    <div class="col-xs-12">
										<?php
										$returnType			 = "list";
										$vehicleList		 = SvcClassVhcCat::getVctSvcList($returnType);
										/* edit code */
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'zon_excluded_cabtypes',
											'val'			 => explode(',', $model->zon_excluded_cabtypes),
											// 'asDropDownList' => FALSE,
											'data'			 => $vehicleList,
											//  'options' => array('data' => new CJavaScriptExpression($datacity)),
											'htmlOptions'	 => array(
												'multiple'		 => 'multiple',
												'placeholder'	 => 'Select Cab Types',
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
											'attribute'		 => 'zon_included_cabmodels',
											'val'			 => explode(',', $model->zon_included_cabmodels),
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


							<div class="col-xs-12 col-sm-12">
								<div class="row">
									<div class="col-xs-12 col-sm-6 mt5 ">
										<label> Included Cab Categories</label>
										<?php
										$categoryList		 = VehicleCategory::getCat();
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'zon_included_cabCategories',
											'val'			 => explode(',', $model->zon_included_cabCategories),
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
									<div class="col-xs-12 col-sm-6 mt5">
										<label>  Included Cab Tire</label>
										<?php
										$tireList			 = ServiceClass::getTier();
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'zon_included_cabtires',
											'val'			 => explode(',', $model->zon_included_cabtires),
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


							<div class="col-xs-12 col-sm-12">
								<div class="row">
									<div class="col-xs-12 col-sm-6 mt5 ">
										<label> Hilly Factor</label>
										<?php
										$hillyFactor		 = zones::getHillyFactor();
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'zon_hilly_factor',
											'val'			 => $model->zon_hilly_factor,
											'data'			 => $hillyFactor,
											'options'		 => array('allowClear' => false),
											'htmlOptions'	 => array('style' => 'width:100%', 'width' => '100%', 'placeholder' => 'Select Hilly factor')
										));
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
</div>
<?php echo CHtml::endForm(); ?>
<!-- following Scripts have been added for ajax based populating Cities zone wise -->
<script type="text/javascript">
    $sourceList = null;
    function populateSourceCityForZone(obj, cityId, lat, long) {
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistzone')) ?>' + '?apshow=1&lat=' + lat + '&long=' + long + '&city=' + cityId,
                    dataType: 'json',
                    data: {
                        // city: cityId
                    },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        if (cityId != "" && cityId != null) {
                            cityArr = cityId.split(",");
                            obj.setValue(cityArr);
                        }
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
    function loadSourceCityForZone(query, callback, lat, long) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistzone')) ?>?apshow=1&lat=' + lat + '&long=' + long + '&q=' + encodeURIComponent(query),
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