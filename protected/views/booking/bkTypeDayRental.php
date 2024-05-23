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
</style>
<?php
/* @var $brtRoute BookingRoute */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
$bmodel = Booking::model();
$ctr = rand(0, 99) . date('mdhis');
?>
<input type="hidden" name="ctr" value="<?= $ctr ?>">
<div class="row clsRoute ">
		<div class="col-xs-12 float-none marginauto pb0 padding_zero">
			<div class="panel panel-default panel-border box-shadow1 gray-new-bg">
				<div class="panel-body pb0 pt0">
					<?php
					if ($btype == 2)
					{
						?>
						<div class="row">

						<? }
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
										"placeholder"		 => "Select Source",
										'fullWidth'			 => true,
										'htmlOptions'		 => array('id' => 'brt_from_city_id_' . $ctr, 'class' => 'form-control ctyPickup ctySelect2', "autocomplete" => "new-password"),
										'defaultOptions'	 => $selectizeOptions + array(
										'onInitialize'	 => "js:function(){\$jsBookNow.populateSource(this, '{$model->brt_from_city_id}', '{$btype}');
																$('.selectize-control INPUT').attr('autocomplete','new-password');                            
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
									echo $form->hiddenField($model, "[" . $ctr . "]brt_from_formatted_address", ['id' => 'OnelocFAdd0']);
									echo $form->hiddenField($model, "[" . $ctr . "]brt_from_is_airport", ['id' => 'OneisAirport0']);
								}
								
								}
								?>
								<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
							</div>
						</div>
							<select class="form-control arrivecity " name="BookingRoute[<?= $ctr ?>][brt_to_city_id]"  
							   id="<?= 'brt_to_city_id_' . $ctr ?>"  >
							</select>
							<div class="col-xs-12 col-sm-6 col-md-4 p5 <?= $mcitiesDiv ?>">
									<div class="col-xs-12 pr0 mr0"><div class="control-label">Select Rental Types</div>
										<?php
									$rentalTypeArr = Booking::model()->rental_types;
									$bmodel->bkg_booking_type = (in_array($rentalTypeArr,['9','10','11'])) ?$rentalTypeArr:$btype;
									$this->widget('booster.widgets.TbSelect2', array
											(
												'model'			 => $bmodel,
												'attribute'		 => "bkg_booking_type",
												//'val'			 => ($btype == '') ? $btype : $rentalTypeArr,
												'asDropDownList' => true,
												'data'			 => $rentalTypeArr,
												'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Hr - Km','onChange'=>'setBookingType(this)','id'=>'BookingTemp_bkg_booking_type_rental')
											));
									?>
									</div>
							</div>
						<?
						if ($btype == 2)
						{
							?>
						</div>
						<div class="row">
							<?
						}
						?>
						<div class="col-xs-12 col-sm-12 col-md-4 <?= $rcitiesDiv ?>">
							<?
							if ($btype == 2)
							{
								?>
								<label class="control-label ml10 n mb10 "><b>Trip start information</b></label>
								<?
							}
							?>
							<div class="row p5 <?= ($btype == 2) ? 'pt0' : '' ?>">
								<div class="col-xs-12 <?= ($btype == 3) ? 'col-sm-4' : 'col-sm-6' ?> mb5  ">
									<div class="form-group ">
										<label class="control-label">Depart date</label>
										<div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<?
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
									<?
									$this->widget('ext.timepicker.TimePicker', array(
										'model'			 => $model,
										'id'			 => 'brt_pickup_date_time_' . date('mdhis'),
										'attribute'		 => '[' . $ctr . ']brt_pickup_date_time',
										'options'		 => ['widgetOptions' => array('options' => array()), 'startTime'=> '00:00', 'dynamic'=>false],
										'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control border-radius timePickup text text-info col-xs-12')
									));
									?> 
								</div>
								<?
								if ($btype == 3)
								{
									echo TbHtml::activeHiddenField($model, '[' . $ctr . ']estArrTime', array('id' => 'estArrTime_' . $index));
									?>
									<div class="col-xs-12 col-md-6 col-lg-4 p5 pt0 pl0  ">
										<label class="control-label">Est Arrival Time</label>
										<input  type='hidden'     id="hidden_estArrTime_<?= $index ?>"  value="<?= ($model->estArrTime == '') ? 'to be calculated' : $estArrTime ?>" >
										<label class="p0" id="lab_estArrTime_<?= $index ?>">to be calculated</label>

									</div>
									<?
								}
								?>

							</div>


							<?
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
												<?
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
										<?
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
						<? } ?>
					</div>  
				</div>
			</div>
		</div>
	</div>
<script>
	function setBookingType(obj)
	{
		if($(obj).val()==9)
		{
			$('#topRouteDesc').html('Day Rental 4hr-40km');
		}
		if($(obj).val()==10){
			$('#topRouteDesc').html('Day Rental 8hr-80km');
		}
		if($(obj).val()==11){
			$('#topRouteDesc').html('Day Rental 12hr-120km');
		}
		$('#BookingTemp_bkg_booking_type').val($(obj).val());
	}
</script>