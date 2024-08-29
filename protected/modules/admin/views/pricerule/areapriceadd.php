<?
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<style type="text/css">
    .select2-container-multi .select2-choices {
        min-height: 50px;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
    .hide {
        display:none;
    }
</style>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/city.js?v=' . $version);
if ($error != '')
{
	?>  
	<div class="col-xs-12 text-danger text-center"><?= $error ?></div> 
	<?php
}
else
{
	$carType		 = SvcClassVhcCat::model()->getVctSvcList();
	$areatype		 = AreaPriceRule::model()->areatype;
	$area			 = 0;
	//$dataCatType = PriceRule::model()->getDefaultJSON();
	//$tripType    = Booking::model()->getBookingType();
	//$cityList    = Cities::model()->getAllCityList();
	//$stateList   = array("" => "Select state") + CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
	?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-8 pb10 new-booking-list" style="float: none; margin: auto;">
			<div class="row">
				<div class="upsignwidt">
					<div class="col-xs-6 col-sm-12 col-md-12">
						<div class="col-xs-12">
							<?php
							$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
								'id'					 => 'addpricerule-manage-form', 'enableClientValidation' => TRUE,
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
									<?php echo CHtml::errorSummary($model); ?>
									<div class="form-group">

										<div class="row">
											<div class="col-xs-12 col-md-4" id="demo"> 
												<label class="control-label" id="errMsg"> Select Cab Type </label>
												<?php
												$dataCabType	 = VehicleTypes::model()->getJSON($carType);
												$this->widget('booster.widgets.TbSelect2', array(
													'model'			 => $model,
													'attribute'		 => 'apr_cab_type',
													'val'			 => $model->apr_cab_type,
													'asDropDownList' => FALSE,
													'options'		 => array('data' => new CJavaScriptExpression($dataCabType)),
													'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Cab Type', 'id' => 'AreaPriceRule_apr_cab_type')
												));
												?>
												<? echo $form->error($model, 'apr_cab_type'); ?>
											</div>

											<div class="col-xs-12 col-md-4">
												<label class="control-label" id="errMsg1"> Select Area Type </label>
												<?
												$dataAreaType	 = VehicleTypes::model()->getJSON($areatype);
												$this->widget('booster.widgets.TbSelect2', array(
													'model'			 => $model,
													'attribute'		 => 'apr_area_type',
													'val'			 => $model->apr_area_type,
													'asDropDownList' => FALSE,
													'options'		 => array('data' => new CJavaScriptExpression($dataAreaType)),
													'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Area Type', 'id' => 'AreaPriceRule_apr_area_type')
												));
												?>
												<? echo $form->error($model, 'apr_area_type'); ?>
											</div>

											<div class="col-xs-12 col-md-4" id="fromArea">
												<div class="form-group">
													<label class="control-label" id="errMsg2" >Select Area</label>
													<?php
													$areaFromArr	 = '[]';
													?>
													<div id="witharea" style="display:none;">
														<?php
														$this->widget('ext.yii-selectize.YiiSelectize', array(
															'model'				 => $model,
															'attribute'			 => 'apr_area_id1',
															'useWithBootstrap'	 => true,
															"placeholder"		 => "Select Area",
															'fullWidth'			 => false,
															'options'			 => array('allowClear' => true),
															'htmlOptions'		 => array('width' => '100%',
															//'id'	 => 'from_city_id1'
															),
															'defaultOptions'	 => $selectizeOptions + array(
														'onInitialize'	 => "js:function(){
																populateSource(this, '{$model->apr_area_id1}');
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
														$this->widget('booster.widgets.TbSelect2', array(
															'model'			 => $model,
															'attribute'		 => 'apr_area_id',
															'val'			 => $model->apr_area_id,
															'asDropDownList' => FALSE,
															'options'		 => array('data' => new CJavaScriptExpression($areaFromArr)),
															'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Area', 'id' => 'AreaPriceRule_apr_area_id')
														));
														?>
														<? echo $form->error($model, 'apr_area_id'); ?>
													</div>
												</div>
											</div>

										</div>

										<div class="panel-footer" style="text-align: center">
											<?php echo CHtml::submitButton('Find', array('class' => 'btn btn-info pl30 pr30', 'name' => 'findBtn', 'id' => 'findbtn')); ?>
										</div>
									</div>
								</div>
								<div class="panel panel-default hide" id='dataBlock'>
									<div class="panel-body">
										<strong>Price Rule List</strong>
									</div>
								</div>

								<?php $this->endWidget(); ?>
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

			$('#<?= CHtml::activeId($model, 'apr_area_type') ?>').change(function ()
			{
				var model = {}
				var area = $('#<?= CHtml::activeId($model, 'apr_area_type') ?>').val();
				if (area != 3) {
					$('#withoutarea').show();
					$('#witharea').hide();
					model.area = area;
					model.id = 'AreaPriceRule_apr_area_id';
					model.multiple = false;
					city.model = model;
					city.showArea();
				} else {
					$('#withoutarea').hide();
					$('#witharea').show();
				}
			});

			$('#findbtn').click(function () {
				var cabType = $('#AreaPriceRule_apr_cab_type').val();
				var areaType = $('#AreaPriceRule_apr_area_type').val();
				var areaId = (areaType == 3) ? $('#AreaPriceRule_apr_area_id1').val() : $('#AreaPriceRule_apr_area_id').val();
				var error = 0;
				//var areaId   = $('#AreaPriceRule_apr_area_id').val();
				if (cabType == '')
				{
					$("#AreaPriceRule_apr_cab_type_em_").text("Car type cannot be blank.");
					$("#AreaPriceRule_apr_cab_type_em_").css({"color": "#a94442", "display": "block"});
					$("#errMsg").css({"color": "#f25656"});
					error = error + 1;
				} else {
					$("#AreaPriceRule_apr_cab_type_em_").css({"display": "none"});
					$("#errMsg").css({"color": "#46c27c"});
					error = 0;
				}
				if (areaType == '')
				{
					$("#AreaPriceRule_apr_area_type_em_").text("Area type cannot be blank.");
					$("#AreaPriceRule_apr_area_type_em_").css({"color": "#a94442", "display": "block"});
					$("#errMsg1").css({"color": "#f25656"});
					error = error + 1;
				} else {
					$("#AreaPriceRule_apr_area_type_em_").css({"display": "none"});
					$("#errMsg1").css({"color": "#46c27c"});
					error = 0;
				}

				if (areaId == '')
				{
					$("#AreaPriceRule_apr_area_id_em_").text("Area cannot be blank.");
					$("#AreaPriceRule_apr_area_id_em_").css({"color": "#a94442", "display": "block"});
					$("#errMsg2").css({"color": "#f25656"});
					error = error + 1;
				} else {
					$("#AreaPriceRule_apr_area_id_em_").css({"display": "none"});
					$("#errMsg2").css({"color": "#46c27c"});
					error = 0;
				}

				if (error > 0)
				{
					return false;
				}
				piceEdit();
				return false;
			});

			function  piceEdit()
			{
				var areaType = $('#AreaPriceRule_apr_area_type').val();
				var areaId = (areaType == 3) ? $('#AreaPriceRule_apr_area_id1').val() : $('#AreaPriceRule_apr_area_id').val();

				$.ajax({
					"type": "GET",
					"async": false,
					"url": '<?= Yii::app()->createUrl('admin/pricerule/edit') ?>',
					"data": {'areaCab': $('#AreaPriceRule_apr_cab_type').val(), 'areaType': $('#AreaPriceRule_apr_area_type').val(), 'areaId': areaId},
					"dataType": "html",
					"success": function (data1)
					{
						$('#dataBlock').html(data1);
						$('#dataBlock').removeClass('hide');
						$('#dataBlock').css({'display': 'block'});
					}
				});
			}
		</script>
	<? } ?>
