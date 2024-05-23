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

.selectize-input {
	width:80% !important;
}
.autoAirMarkerLoc{
	font-size: 30px;
	color:red;
	cursor: pointer;
}
</style>
<?php
/* @var $brtRoute BookingRoute */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');


$ctr = rand(0, 99) . date('mdhis');
?>
<input type="hidden" name="ctr" value="<?= $ctr ?>">
<div class="row clsRoute ">
	<div class="col-xs-12 col-sm-11 float-none marginauto pb0">
		<div class="panel panel-default panel-border box-shadow1 gray-new-bg">
			<div class="panel-body pb0 pt0">
				<div class="col-xs-12 col-sm-6 col-md-4 p5" >
					<div class="col-xs-12">
						<label class="control-label" id='trslabel'>Airport</label>
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $bkgTempModel,
							'attribute'			 => 'bkgAirport',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Select Airport",
							'fullWidth'			 => false,
							'htmlOptions'		 => array(
								'class' => 'form-control'
							),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
								populateAirportList(this, '{$bkgTempModel->bkgAirport}');
							}",
						'load'			 => "js:function(query, callback){
								loadAirportSource(query, callback);
							}",
						'onChange'		 => "js:function(value) {
								hyperModel.changeTrDestination(value , {$brtRoute->brt_from_city_id});
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
						<span class="has-error"><? echo $form->error($bkgTempModel, 'bkg_from_city_id'); ?></span>
						<?php
								echo $form->hiddenField($brtRoute, "[" . $ctr . "]brt_from_location", ['id' => "brt_location0"]);
								echo $form->hiddenField($brtRoute, "[" . $ctr . "]brt_from_latitude", ['id' => 'locLat0']);
								echo $form->hiddenField($brtRoute, "[" . $ctr . "]brt_from_longitude", ['id' => 'locLon0']);
								echo $form->hiddenField($brtRoute, "[" . $ctr . "]brt_from_place_id", ['id' => 'locPlaceid0']);
								echo $form->hiddenField($brtRoute, "[" . $ctr . "]brt_from_formatted_address", ['id' => 'locFAdd0']);
								echo $form->hiddenField($brtRoute, "[" . $ctr . "]brt_from_city_id", ['id' => 'ctyIdAir0']);
								echo $form->hiddenField($brtRoute, "[" . $ctr . "]brt_from_is_airport", ['id' => 'isAirport0']);
								echo $form->hiddenField($brtRoute, "[" . $ctr . "]brt_from_location_cpy", ['class' => 'cpy_loc_0']);
						?>

						<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 p5">
						<label class="control-label" id='trdlabel'>Drop</label>
						<div class="row">
							<div class="col-xs-10">
								<?php
									echo $form->textFieldGroup($brtRoute, "[" . $ctr . "]brt_to_location", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['id' => "brt_location1", 'class' => "form-control autoComLoc", "autocomplete" => "section-new", 'placeholder' => "Drop Address", 'onblur'=>"hyperModel.clearAddress(this,'airport')"])));
								?>
									<?= $form->hiddenField($brtRoute, "[" . $ctr . "]brt_to_latitude", ['id' => "locLat1"]); ?>
									<?= $form->hiddenField($brtRoute, "[" . $ctr . "]brt_to_longitude", ['id' => "locLon1"]); ?>
									<?= $form->hiddenField($brtRoute, "[" . $ctr . "]brt_to_place_id", ['id' => "locPlaceid1"]); ?>
									<?= $form->hiddenField($brtRoute, "[" . $ctr . "]brt_to_formatted_address", ['id' => "locFAdd1"]); ?>
									<?= $form->hiddenField($brtRoute, "[" . $ctr . "]brt_to_city_id", ['id' => 'ctyIdAir1']); ?>
									<?= $form->hiddenField($brtRoute, "[" . $ctr . "]brt_to_is_airport", ['id' => 'isAirport1', 'value' => 0]); ?>
									<?= $form->hiddenField($brtRoute, "[" . $ctr . "]brt_to_location_cpy", ['class' => 'cpy_loc_1']); ?>
								<span class="has-error"><? echo $form->error($brtRoute, 'brt_to_city_id'); ?></span>
								<span class="has-error"><? echo $form->error($brtRoute, 'brt_pickup_date_date'); ?></span>
								<span class="has-error"><? echo $form->error($brtRoute, 'brt_pickup_date_time'); ?></span>
							</div>
							<div class="col-xs-2">
								<span class="autoAirMarkerLoc" data-lockey="1" data-toggle="tooltip" title="Select To Address on map"><img src="/images/locator_icon4.png" alt="Precise location" width="30" height="30"></span>
							</div>
						</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4">
					<div class="row p5">
						<div class="col-xs-12 col-sm-6 mb5">
							<div class="form-group mr0">
								<label class="control-label">Pickup date</label>
								<div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<?
									$this->widget('booster.widgets.TbDatePicker', array(
										'model'			 => $brtRoute,
										'attribute'		 => '[' . $ctr . ']brt_pickup_date_date',
										//'val' => $date,
										//  'label' => '',
										'options'		 => ['autoclose' => true, 'startDate' => "js:new Date('$brtRoute->brt_min_date')", 'format' => 'dd/mm/yyyy'],
										'htmlOptions'	 => array('id' => 'brt_pickup_date_date_' . date('mdhis'), 'value' => $brtRoute->brt_pickup_date_date, 'min' => $brtRoute->brt_min_date, 'placeholder' => 'Pickup Date', 'class' => 'form-control datePickup border-radius')
									));
									?>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 p0">
							<label class="control-label">Time</label>
							<?
										$this->widget('ext.timepicker.TimePicker', array(
											'model'			 => $brtRoute,
											'id'			 => 'brt_pickup_date_time_' . date('mdhis'),
											'attribute'		 => '[' . $ctr . ']brt_pickup_date_time',
											'options'		 => ['widgetOptions' => array('options' => array()), 'startTime'=> '00:00', 'dynamic'=>false],
											'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Time', 'class' => 'form-control border-radius timePickup text text-info col-xs-12')
										));
									?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
