<style>
    .full-width {
		width: 300px
	}
	@media (min-width: 1523px) and (max-width: 1636px) {
		.full-width {
			width: 270px !important;
		}
		@media (min-width: 1388px) and (max-width: 1524px) {
			.full-width {
				width: 250px !important;
			}
			@media (min-width: 768px) and (max-width: 1387px) {
				.full-width {
					width: 248px !important;
				}
			}
			@media (min-width: 320px) and (max-width: 767px) {
				.full-width {
					width: 220px !important;
				}
			}
		}
	}
	.timePickup ,.datePickup,.input-group-addon  {
		padding:6px 8px;
	}

</style>
<?php
/* @var $model BookingRoute */
$bmodel = Booking::model();
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
if ($sourceCity == "")
{
	$cityList	 = Cities::model()->getJSONAirportCitiesAll();
	$pcityList	 = $cityList;
}
else
{
	$model->brt_from_city_id = $sourceCity;
	$cmodel					 = Cities::model()->getDetails($sourceCity);
	$sourceCityName			 = $cmodel->cty_name . ', ' . $cmodel->ctyState->stt_name;
	$pcityList				 = Cities::model()->getJSONNearestAll($previousCity);
}
if ($model->brt_from_city_id != '')
{
	$cityList = Cities::model()->getJSONNearestAll($model->brt_from_city_id);
}
$rcitiesDiv	 = '';
$rtimeDiv	 = "  col-md-4";
if ($btype == 2)
{
	$rcitiesDiv	 = "  col-md-offset-2";
	$rtimeDiv	 = "  col-md-12";
}
if ($btype == 3)
{
	$mcitiesDiv = "  col-md-4";
}
//echo $model->estArrTime[$index];
$ctr = rand(0, 99) . date('mdhis');
?>
<?php
if ($btype == 7)
{

	$minDate = ($model->brt_min_date != '') ? $model->brt_min_date : date('Y-m-d');
	?>
	<div class="row clsRoute ">
		<div class="col-xs-12 float-none marginauto pb0 padding_zero">
			<div class="panel panel-default panel-border box-shadow1 gray-new-bg">
				<div class="panel-body pb0 pt0">

					<div class="col-xs-12 col-sm-12 col-md-4 p5 ">

						<div class="form-group   mr0 ml0">
							<label class="control-label">Depart date</label>
							<div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<?php
								$this->widget('booster.widgets.TbDatePicker', array(
									'model'			 => $model,
									'attribute'		 => '[' . $ctr . ']brt_pickup_date_date',
									//'val' => $date,
									//  'label' => '',
									'options'		 => ['autoclose' => true, 'startDate' => "js:new Date('$minDate')", 'format' => 'dd/mm/yyyy'],
									'htmlOptions'	 => array('id' => 'brt_pickup_date_date_shuttle', 'value' => $model->brt_pickup_date_date, 'min' => $model->brt_min_date, 'placeholder' => 'Pickup Date', 'class' => 'form-control datePickup border-radius')
								));
								?>
							</div>
						</div>

						<input type='hidden' id="<?= 'brt_pickup_date_time_' . date('mdhis') ?>" name="BookingRoute[<?= $ctr ?>][brt_pickup_date_time]"  value="<?= $model->brt_pickup_date_time ?>" >

					</div> 
					<div class="col-xs-12 col-sm-6 col-md-4 p5  " >
						<div class="input-group col-xs-12">

							<label class="control-label" id='trslabel'>Going From</label><br>
							<select class="form-control inputSource " name="BookingRoute[<?= $ctr ?>][brt_from_city_id]"  
									id="<?= 'brt_from_city_id_' . $ctr ?>" onchange="populateDropCity()" >
							</select>


							<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4 p5  ">
						<div class="input-group col-xs-12">
							<label class="control-label" id='trdlabel'>Going To</label><br>
							<select class="form-control destSource " name="BookingRoute[<?= $ctr ?>][brt_to_city_id]"  
									id="<?= 'brt_to_city_id_' . $ctr ?>"  >
							</select>
						</div>
					</div>


				</div>
			</div>
		</div>
	</div>
	<?php
}
else if ($btype != 4 && $btype != 7 && $btype != 9 && $btype != 10 && $btype != 11)
{
	?>
	<div class="row clsRoute ">
		<div class="col-xs-12 float-none marginauto pb0 padding_zero">
			<div class="panel panel-default panel-border box-shadow1 gray-new-bg">
				<div class="panel-body pb0 pt0">
					<?php
					if ($btype == 2)
					{
						?>
						<div class="row">

						<?php }
						?>
						<div class="col-xs-12 col-sm-6 col-md-4 p5 <?= $rcitiesDiv . $mcitiesDiv ?>" >
							<div class="input-group col-xs-12">
								<label class="control-label" id='trslabel'>Going From</label><br>
								<?php
								if ($index > 0)
								{
									echo TbHtml::activeHiddenField($model, '[' . $ctr . ']brt_from_city_id', array('id' => 'brt_from_city_id_' . $ctr));
									echo CHtml::textField('sourceCityName_' . $ctr, $sourceCityName, array('class' => 'form-control ctyPickup ctySelect2', 'readonly' => 'readonly'));
								}
								else
								{
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => '[' . $ctr . ']brt_from_city_id',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Select City",
										'fullWidth'			 => true,
										'htmlOptions'		 => array('id' => 'brt_from_city_id_' . $ctr, 'class' => 'form-control ctyPickup ctySelect2', "autocomplete" => "new-password"),
										'defaultOptions'	 => $selectizeOptions + array(
										'onInitialize'	 => "js:function(){\$jsBookNow.populateSource(this, '{$model->brt_from_city_id}', '{$btype}');
																$('.selectize-control INPUT').attr('autocomplete','new-password');                            
															}",
									'load'			 => "js:function(query, callback){\$jsBookNow.loadSource(query, callback);
																}",
									'onChange'		 => "js:function(value) {" . '$jsBookNow.changeDestination' . "(value, \$dest_city, '{$model->brt_to_city_id}'), '{$btype}';}",
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
									
								if($btype == 1){
									echo $form->hiddenField($model, "[" . $ctr . "]brt_from_latitude", ['id' => 'OnelocLon0']);
									echo $form->hiddenField($model, "[" . $ctr . "]brt_from_longitude", ['id' => 'OnelocLat0']);
									echo $form->hiddenField($model, "[" . $ctr . "]brt_from_location", ['id' => 'Onelocation0']);
									echo $form->hiddenField($model, "[" . $ctr . "]brt_from_place_id", ['id' => 'OnePlaceId0']);
									echo $form->hiddenField($model, "[" . $ctr . "]brt_from_formatted_address", ['id' => 'OnelocFAdd0']);
									echo $form->hiddenField($model, "[" . $ctr . "]brt_from_is_airport", ['id' => 'OneisAirport0']);
								}
								
								}
								?>
								<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-4 p5 <?= $mcitiesDiv ?> dayrentalcity">
							<div class="input-group col-xs-12">
							
								
                               <label class="control-label" id='trdlabel'>Going To</label><br>
								<?php
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => '[' . $ctr . ']brt_to_city_id',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select City",
									'fullWidth'			 => true,
									'htmlOptions'		 => array('id' => 'brt_to_city_id_' . $ctr, 'class' => 'form-control ctyDrop ctySelect2 arrivedcity', "autocomplete" => "new-password"),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
										$('.selectize-control INPUT').attr('autocomplete','new-password');                            
                                        \$dest_city=this;
                                                                                            }",
								'render'		 => "js:{
                                                 option: function(item, escape){                      
                                                         return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';                          
                                                 },
                                                 option_create: function(data, escape){
                                                      return '<div>' +'<span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(data.text) + '</span></div>';
                                                }
                                            }",
									),
								));
								?>
								<?php if($btype == 1){ ?>
									<?= $form->hiddenField($model, "[" . $ctr . "]brt_to_latitude", ['id' => 'OnelocLat1']); ?>
									<?= $form->hiddenField($model, "[" . $ctr . "]brt_to_longitude", ['id' => 'OnelocLon1']); ?>
									<?= $form->hiddenField($model, "[" . $ctr . "]brt_to_location", ['id' => 'Onelocation1']); ?>
									<?= $form->hiddenField($model, "[" . $ctr . "]brt_to_place_id", ['id' => 'OnePlaceId1']); ?>
									<?= $form->hiddenField($model, "[" . $ctr . "]brt_to_formatted_address", ['id' => 'OnelocFAdd1']); ?>
									<?= $form->hiddenField($model, "[" . $ctr . "]brt_to_is_airport", ['id' => 'OneisAirport1']); ?>
								<?php }	
								?>
							</div>
						</div>

						<?php
						if ($btype == 2)
						{
							?>
						</div>
						<div class="row">
							<?php
						}
						?>
						<div class="col-xs-12 col-sm-12 col-md-4 <?= $rcitiesDiv ?>">
							<?php
							if ($btype == 2)
							{
								?>
								<label class="control-label ml10 n mb10 "><b>Trip start information</b></label>
								<?php
							}
							?>
							<div class="row p5 <?= ($btype == 2) ? 'pt0' : '' ?>">
								<div class="col-xs-12 <?= ($btype == 3) ? 'col-sm-4' : 'col-sm-6' ?> mb5  ">
									<div class="form-group ">
										<label class="control-label">Depart date</label>
										<div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<?php
											$this->widget('booster.widgets.TbDatePicker', array(
												'model'			 => $model,
												'attribute'		 => '[' . $ctr . ']brt_pickup_date_date',
												//'val' => $date,
												//  'label' => '',
												'options'		 => ['autoclose' => true, 'startDate' => "js:new Date('$model->brt_min_date')", 'format' => 'dd/mm/yyyy'],
												'htmlOptions'	 => array('id' => 'brt_pickup_date_date_' . date('mdhis'), 'value' => $model->brt_pickup_date_date, 'min' => $model->brt_min_date, 'placeholder' => 'Pickup Date', 'class' => 'form-control datePickup border-radius')
											));
											?>
										</div>
									</div>
								</div>

								<div class="col-xs-12 <?= ($btype == 3) ? 'col-sm-4' : 'col-sm-6' ?> p5 pt0">
									<label class="control-label">Depart time</label>
									<?php
									$this->widget('ext.timepicker.TimePicker', array(
										'model'			 => $model,
										'id'			 => 'brt_pickup_date_time_' . date('mdhis'),
										'attribute'		 => '[' . $ctr . ']brt_pickup_date_time',
										'options'		 => ['widgetOptions' => array('options' => array()), 'startTime'=> '00:00', 'dynamic'=>false],
										'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control border-radius timePickup text text-info col-xs-12')
									));
									?> 
								</div>
								<?php
								if ($btype == 3)
								{
									echo TbHtml::activeHiddenField($model, '[' . $ctr . ']estArrTime', array('id' => 'estArrTime_' . $index));
									?>
									<div class="col-xs-12 col-md-6 col-lg-4 p5 pt0 pl0  ">
										<label class="control-label">Est Arrival Time</label>
										<input  type='hidden'     id="hidden_estArrTime_<?= $index ?>"  value="<?= ($model->estArrTime == '') ? 'to be calculated' : $estArrTime ?>" >
										<label class="p0" id="lab_estArrTime_<?= $index ?>">to be calculated</label>

									</div>
									<?php
								}
								?>

							</div>


							<?php
							if ($btype == 2)
							{
								?>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-4 ">
								<label class="control-label ml10 n mb10"><b>Trip End information</b></label>
								<div class="row p5 pt0">
									<div class="col-xs-12 col-sm-6 mb5">
										<div class="form-group">
											<label class="control-label">Return date</label>
											<div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<?php
												$this->widget('booster.widgets.TbDatePicker', array(
													'model'			 => $model,
													'attribute'		 => '[' . $ctr . ']brt_return_date_date',
													//'val' => $date,
													//  'label' => '',
													'options'		 => ['autoclose' => true, 'startDate' => "js:new Date('$model->brt_min_date')", 'format' => 'dd/mm/yyyy'],
													'htmlOptions'	 => array('id' => 'brt_return_date_date_' . date('mdhis'), 'value' => $model->brt_return_date_date, 'min' => $model->brt_min_date, 'placeholder' => 'Return Date', 'class' => 'form-control dateReturn border-radius')
												));
												?>
											</div>
										</div>
									</div>
									<div class="col-xs-12 col-sm-6 p0">
										<label class="control-label">Return time</label>
										<?php
										$this->widget('ext.timepicker.TimePicker', array(
											'model'			 => $model,
											'id'			 => 'brt_return_date_time' . date('mdhis'),
											'attribute'		 => '[' . $ctr . ']brt_return_date_time',
											'options'		 => ['widgetOptions' => array('options' => array()), 'startTime'=> '00:00', 'dynamic'=>false],
											'htmlOptions'	 => array('required' => true, 'placeholder' => 'Return Time', 'class' => 'form-control border-radius timeReturn text text-info col-xs-12')
										));
										?> 
									</div>
								</div>
							</div>
						<?php } ?>
					</div>  
				</div>
			</div>
		</div>
	</div>
	<?php
}
else if ($btype == 9 || $btype == 10 || $btype == 11)
{
	$this->renderPartial('bkTypeDayRental', ['model' => $model, 'btype' => $btype, 'pcityList' => $pcityList, 'cityList' => $cityList, 'index' => 0, 'bkgTempModel' => $bkgTempModel,'form' => $form, 'selectizeOptions' => $selectizeOptions], false, false);
}
else if ($btype == 4)
{
	$this->renderPartial('bkTypeAirportTransfer', ['brtRoute' => $model, 'pcityList' => $pcityList, 'cityList' => $cityList, 'btype' => $btype, 'index' => 0, 'bkgTempModel' => $bkgTempModel,'form' => $form, 'selectizeOptions' => $selectizeOptions], false, false);
}
?>
<script>
	$sourceList = null;
	$loadCityId = 0;
	$(document).ready(function ()
	{
		populateShuttleSource();
		bttype = '<?= $btype ?>';
		tocity = '<?= $model->brt_from_city_id?>';
		if(bttype == 9 || bttype == 10 || bttype == 11)
		{
			$('.arrivecity').children('option').remove();
			$('.arrivecity').addClass('hide');
			$('.arrivecity').append('<option value="' + tocity + '"> ' + tocity + ' </option>');
								  
								  
		}
	});
	$('#lab_estArrTime_' + (<?= $index - 1 ?>)).html($('#hidden_estArrTime_' + <?= $index ?>).val());
	$('#brt_pickup_date_date_shuttle').change(function () {
		$('.destSource').val('');
		populateShuttleSource();

	});



	function populateShuttleSource() {
		fromCity = '<?= $model->brt_from_city_id ?>';
		dateVal = $('#brt_pickup_date_date_shuttle').val();
		$('.inputSource').val('');
		$('.destSource').html('');

		$.ajax({
			"type": "POST",
			dataType: 'json',
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('shuttle/getpickupcitylist')) ?>",
			data: {
				'dateVal': dateVal
			},
			"async": false,
			"success": function (data1)
			{

				$('.inputSource').children('option').remove();
				$(".inputSource").append('<option value="">Select City</option>');
				$.each(data1, function (key, value) {
					if (fromCity != '' && fromCity == key) {
						$('.inputSource').append($("<option></option>").attr("value", key).attr("selected", "selected").text(value));
					} else {
						$('.inputSource').append($("<option></option>").attr("value", key).text(value));
					}

				});
			}

		});
		if (fromCity != '') {
			populateDropCity();
		}
	}
	function populateDropCity() {
		toCity = '<?= $model->brt_to_city_id ?>';
		dateVal = $('#brt_pickup_date_date_shuttle').val();
		fcityVal = $('.inputSource').val();
		$('.destSource').val('');
		if ($('#BookingTemp_bkg_booking_type').val() == 7 && fcityVal > 0) {
			$.ajax({
				"type": "POST",
				dataType: 'json',
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('shuttle/getdropcitylist')) ?>",
				data: {
					'dateVal': dateVal, 'fcityVal': fcityVal
				},
				"async": false,
				"success": function (data1)
				{

					$('.destSource').children('option').remove();
					$(".destSource").append('<option value="">Select City</option>');
					$.each(data1, function (key, value) {
						if (toCity != '' && toCity == key) {
							$('.destSource').append($("<option></option>").attr("value", key).attr("selected", "selected").text(value));
						} else {

							$('.destSource').append($("<option></option>").attr("value", key).text(value));
						}
					});
				}
			});
		}
	}
	 
	function getDestination()
	{
		var toCity = $('#brt_from_city_id_'+'<?= $ctr?>').val();
			$('.arrivecity').children('option').remove();
			$('.arrivecity').addClass('hide');
			$('.arrivecity').append('<option value="' + toCity + '"> ' + toCity + ' </option>');
	}
</script>