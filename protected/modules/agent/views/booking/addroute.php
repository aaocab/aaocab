<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/rap.js?v=' . $version, CClientScript::POS_HEAD);

?>
<?php
/* @var $model BookingRoute */
$autoAddressJSVer = Yii::app()->params['autoAddressJSVer'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/bookingRoute.js?v=$autoAddressJSVer");

Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
$acWidgetId			 = CHtml::activeId($model, 'place') . "_" . rand(100000, 9999999);
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
$ctr = rand(0, 99) . date('mdhis');
?>
<?php
if ($btype != 4 && $btype != 5)
{
	?>
	<div class="row clsRoute ">

				<?php
				if ($btype == 2)
				{
					?>
					<div class="row">

					<?php }
					?>
					<div class="col-xs-12 col-sm-6 col-md-4 <?= $rcitiesDiv ?>" >
						<div class="input-group col-xs-12">
							<label class="control-label" id='trslabel'>From City</label>
							<?php
							$widgetId = $ctr . "_" . random_int(99999, 10000000);
							$this->widget('application.widgets.BRCities', array(
								'type'				 => 1,
								'enable'			 => ($index == 0),
								'widgetId'			 => $widgetId,
								'model'				 => $model,
								'attribute'			 => '[' . $ctr . ']brt_from_city_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select City",
								'defaultOptions'	 => [
								]
							));
							?>
							<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="input-group col-xs-12">
							<label class="control-label" id='trdlabel'>To City</label>
							<?php
							$this->widget('application.widgets.BRCities', array(
								'type'				 => 2,
								'widgetId'			 => $widgetId,
								'model'				 => $model,
								'attribute'			 => '[' . $ctr . ']brt_to_city_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select City",
								'defaultOptions'	 => [
									'onFocus' => "js:function() {
									$('html, body').animate({scrollTop: $(this.\$control[0]).offset().top - 80},200);
                                }",
								]
							));
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
							<label class="control-label ml10 n">Trip start information</label>
							<?php
						}
						?>
						<div class="row m0 <?= ($btype == 2) ? 'pt0' : '' ?>">
							<div class="col-xs-12 col-sm-6 mb5">
								<div class="form-group">
									<label class="control-label">Date</label>
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
							<div class="col-xs-12 col-sm-6">
								<div class="form-group">
								<label class="control-label mcitytimelabel">Start/Departure time</label>
								<?php
								$this->widget('booster.widgets.TbTimePicker', array(
									'model'			 => $model,
									'id'			 => 'brt_pickup_date_time_' . date('mdhis'),
									'attribute'		 => '[' . $ctr . ']brt_pickup_date_time',
									'options'		 => ['widgetOptions' => array('options' => array('defaultTime' => true, 'autoclose' => true))],
									'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Time',
										'value'			 => $model->brt_pickup_date_time,
										'class'			 => 'form-control border-radius timePickup text text-info col-xs-12')
								));
								?> 
							</div>
							</div>
						</div>


						<?php
						if ($btype == 2)
						{
							?>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 ">
							<label class="control-label  ml10 n">Trip End information</label>
							<div class="row p5 pt0">
								<div class="col-xs-12 col-sm-6 mb5">
									<div class="form-group">
										<label class="control-label">Date</label>
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
									<label class="control-label">End/Return Time</label>
									<?php
									$this->widget('booster.widgets.TbTimePicker', array(
										'model'			 => $model,
										'id'			 => 'brt_return_date_time' . date('mdhis'),
										'attribute'		 => '[' . $ctr . ']brt_return_date_time',
										'options'		 => ['widgetOptions' => array('options' => array('defaultTime' => true, 'autoclose' => true))],
										'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Time',
											'value'			 => $model->brt_return_date_time,
											'class'			 => 'form-control border-radius timeReturn text text-info col-xs-12')
									));
									?> 
								</div>
							</div>
						</div>
					<?php } ?>
				</div>  
			</div>
	<?php
}
else if ($btype == 5)
{
	?>
	<div class="row clsRoute ">
				<div class="col-xs-12 col-sm-6 col-md-4 <?= $rcitiesDiv ?>" >
					<div class="input-group col-xs-12">
						<label class="control-label" id='trslabel'>Pickup Location</label>
						<?php
						$widgetId		 = $ctr . "_" . random_int(99999, 10000000);
						$this->widget('application.widgets.BRCities', array(
							'type'				 => 1,
							'enable'			 => ($index == 0),
							'widgetId'			 => $widgetId,
							'model'				 => $model,
							'attribute'			 => '[' . $ctr . ']brt_from_city_id',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Select City",
							'defaultOptions'	 => [
							]
						));
						?>
						<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="input-group col-xs-12">
						<label class="control-label" id='trdlabel'>Select length of time for which you require a cab</label>

						<?php
						$rentalTypeArr	 = Booking::model()->rental_types;
						echo CHtml::activeDropDownList($bkgmodel, "bkg_booking_type", $rentalTypeArr,
								array('style'			 => 'width:100%', 'class'			 => 'form-control', 'placeholder'	 => 'Hr - Km',
									'onChange'		 => 'setBookingType(this)', 'id'			 => 'BookingTemp_bkg_booking_type_rental'));

//							$this->widget('application.widgets.BRCities', array(
//								'type'				 => 2,
//								'widgetId'			 => $widgetId,
//								'model'				 => $model,
//								'attribute'			 => '[' . $ctr . ']brt_to_city_id',
//								'useWithBootstrap'	 => true,
//								"placeholder"		 => "Select City",
//								'defaultOptions'	 => [
//									'onFocus' => "js:function() {
//									$('html, body').animate({scrollTop: $(this.\$control[0]).offset().top - 80},200);
//                                }",
//								]
//							));
										
						?>

		<input type="hidden" id="BookingRoute_<?= $ctr ?>_brt_to_city_id" name="BookingRoute[<?= $ctr ?>][brt_to_city_id]" value=""/>
					

					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-4 <?= $rcitiesDiv ?>">

					<div class="row m0 <?= ($btype == 2) ? 'pt0' : '' ?>">
						<div class="col-xs-12 col-sm-6 mb5">
							<div class="form-group">
								<label class="control-label">Date of pickup</label>
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
						<div class="col-xs-12 col-sm-6 p0">
							<label class="control-label mcitytimelabel">Time of pickup</label>
							<?php
							$this->widget('booster.widgets.TbTimePicker', array(
								'model'			 => $model,
								'id'			 => 'brt_pickup_date_time_' . date('mdhis'),
								'attribute'		 => '[' . $ctr . ']brt_pickup_date_time',
								'options'		 => ['widgetOptions' => array('options' => array('defaultTime' => true, 'autoclose' => true))],
								'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Time',
									'value'			 => $model->brt_pickup_date_time,
									'class'			 => 'form-control border-radius timePickup text text-info col-xs-12')
							));
							?> 
						</div>
					</div>
				</div>
			</div>
	<?php
}
else
{
$ctr			 = rand(0, 99) . date('mdhis');
if ($transfertype == 1)
{
	$heading			 = "Pickup from airport";
	$pickup				 = "Which airport are we pickup you up from";
	$toCity				 = "Going to location";
	$date				 = "departure";
	$model->airport	 = $model->brt_from_city_id;
	$model->getDestinationPlace();
	$model->place	 = $model->to_place;
}
else
{
	$heading			 = "Drop to airport";
	$pickup				 = "Which airport are we dropping you at?";
	$toCity				 = "Pickup location";
	$date				 = "pickup";
	$model->airport	 = $model->brt_to_city_id;
	$model->getSourcePlace();
	$model->place	 = $model->from_place;
}
?>
<input type="hidden" name="ctr" value="<?= $ctr ?>">
	<div class="row clsRoute ">
		<div class="col-xs-12">
				
					<div class="row">

					
					<div class="col-xs-12 col-sm-6 col-md-4 <?= $rcitiesDiv ?>" >
						<div class="input-group col-xs-12">
							<label class="control-label" id='trslabel'><?=$heading?></label>
							<?php
//							$this->widget('booster.widgets.TbSelect2', array(
//								'model'			 => $model,
//								'attribute'		 => '[' . $ctr . ']brt_from_city_id',
//								'val'			 => "$model->brt_from_city_id",
//								'asDropDownList' => FALSE,
//								'options'		 => array('data'				 => new CJavaScriptExpression($pcityList),
//									'dropdownCssClass'	 => 'cityList', 'formatNoMatches'	 => "js:function(term){return \"Can't find the source?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000\"}"),
//								'htmlOptions'	 => array('onChange' => 'pickupairport(this)', 'id' => 'brt_from_city_id_' . $ctr, 'class' => 'form-control ctyPickup ctySelect2', 'placeholder' => 'Select Source',)
//							));
							
$options			 = [];
$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'airport',
					'useWithBootstrap'	 => true,
					"placeholder"		 => $pickup,
					'fullWidth'			 => true,
					'htmlOptions'		 => array('width' => '50%'
					),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
													populateAirportList(this, '{$model->airport}');
												}",
				'load'			 => "js:function(query, callback){
													loadAirportSource(query, callback);
												}",
				'onChange'		 => "js:function(value) {
										setAddressCity('{$acWidgetId}',value);
											}",
				'render'		 => "js:{
														option: function(item, escape){
														return '<div><span class=\"\"><img src=\"/images/bxs-map.svg\" alt=\"img\" width=\"22\" height=\"22\">' + escape(item.text) +'</span></div>';
														},
														option_create: function(data, escape){
														return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
													   }
													}",
					),
				));




?>
							<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>

						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="input-group col-xs-12">
							<label class="control-label" id='trdlabel'>To City</label>
							<?php
//							$this->widget('booster.widgets.TbSelect2', array(
//								'model'			 => $model,
//								'attribute'		 => '[' . $ctr . ']brt_to_city_id',
//								'val'			 => "$model->brt_to_city_id",
//								'asDropDownList' => FALSE,
//								'options'		 => array('data'				 => new CJavaScriptExpression($cityList),
//									'formatNoMatches'	 => "js:function(term){return \"Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000\"}"
//								),
//								'htmlOptions'	 => array('onChange' => 'dropairport(this)', 'id' => 'brt_to_city_id_' . $ctr, 'class' => 'form-control ctyDrop ctySelect2', 'placeholder' => 'Select Destination')
//							));
							?>
<?php
							$this->widget('application.widgets.SelectAddress', array(
		'model'			 => $model,
		"htmlOptions"	 => ["class" => "border border-light  p10 text-left selectAddress item", "id" => $acWidgetId],
		'attribute'		 => "place",
		'widgetId'		 => $acWidgetId,
		'isAirport'		 => true,
		"city"			 => "{$model->airport}",
		"modalId"		 => "addressModal",
		'viewUrl'		 => '/agent/booking/selectAddress'
	));
				?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-4 <?= $rcitiesDiv ?>">
						
						<div class="row m0" >
							<div class="col-xs-12 col-sm-6 mb5">
								<div class="form-group">
									<label class="control-label">Date</label>
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
							<div class="col-xs-12 col-sm-6">
								<div class="form-group">
								<label class="control-label">Start/Departure time</label>
								<?php
								$this->widget('booster.widgets.TbTimePicker', array(
									'model'			 => $model,
									'id'			 => 'brt_pickup_date_time_' . date('mdhis'),
									'attribute'		 => '[' . $ctr . ']brt_pickup_date_time',
									'options'		 => ['widgetOptions' => array('options' => array('defaultTime' => true, 'autoclose' => true))],
									'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Time',
										'value'			 => $model->brt_pickup_date_time,
										'class'			 => 'form-control border-radius timePickup text text-info col-xs-12')
								));
								?>
								</div>
							</div>
						</div>
				</div>
					
					</div>
					
					
</div>
</div>
<?php } ?>
<script>
    $sourceList = null;
    $loadCityId = 0;
function setBookingType(obj)
{
$('#BookingTemp_bkg_booking_type').val($(obj).val());
}

function pickupairport(obj)
{	
	//alert("ftg");
	if($('#BookingTemp_bkg_transfer_type_0').is(":checked") === true)
	{
		$('.ctyDrop').val('');
	}
}

function dropairport(obj)
{
	//alert("212");
	if($('#BookingTemp_bkg_transfer_type_1').is(":checked") === true)
	{
		$('.ctyPickup').val('');
	}
}


function loadAirportSource(query, callback)
	{
		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>?q=' + encodeURIComponent(query),
			type: 'GET',
			dataType: 'json',
			error: function()
			{
				callback();
			},
			success: function(res)
			{
				callback(res);
			}
		});
	}
</script>