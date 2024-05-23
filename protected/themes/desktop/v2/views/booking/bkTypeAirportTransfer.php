<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/city.js?v=$autoAddressJSVer");

/* @var $brtRoute BookingRoute */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');


$ctr = rand(0, 99) . date('mdhis');

/* echo "<pre>";
  var_dump($brtRoute);
  die('DD'); */
?>
<input type="hidden" name="ctr" value="<?= $ctr ?>">
<div class="container clsRoute mt10">
            <div class="bg-white-box">
				<div class="row">
					<div class="col-12">		
						<label class="control-label mr10" style="font-weight: bold">Pickup Type: </label>
						<?= CHtml::activeRadioButtonList($bkgTempModel, 'bkg_transfer_type', Booking::model()->transferTypes,['separator'=>'&nbsp;&nbsp;&nbsp;']) ?>
					</div>
				</div>
                <div class="row">
					<div class="col-xs-12 col-md-7">
						<div class="row ">
					<div class="col-12 col-sm col-md-12 col-lg" >
						<label class="control-label pr15" id='trslabel'>Airport</label>
						<?php
						$options	 = [];
						$acWidgetId	 = CHtml::activeId($brtRoute, 'place');
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $brtRoute,
							'attribute'			 => 'airport',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Select Airport",
							'fullWidth'			 => true,
							'htmlOptions'		 => array('width' => '50%'
							),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
													populateAirportList(this, '{$brtRoute->airport}');
												}",
						'load'			 => "js:function(query, callback){
													loadAirportSource(query, callback);
												}",
						'onChange'		 => "js:function(value) {
												PACObject.getObject('{$acWidgetId}').initAirportBounds(value);
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
						<span class="has-error"><?php echo $form->error($brtRoute, 'airport'); ?></span>

						<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>

					</div>
					<div class="col-12 col-sm col-md-12 col-lg mb10">
						<label class="control-label pr40" id='trdlabel'>Drop</label>
						<div class="row">
							<div class="col-12">
								<?php
								$this->widget('application.widgets.PlaceAutoComplete', ['model'			 => $brtRoute, 'attribute'		 => 'place',
									'onPlaceChange'	 => "function(event, pacObject){ pacObject.validateAddress(event);}",
									'enableOnLoad'	 => false,
									'htmlOptions'	 => ['class' => "form-control", "autocomplete" => "section-new", 'placeholder' => "Location"]
								]);
								?>
								<span class="has-error"><?php echo $form->error($brtRoute, 'place'); ?></span>
							</div>
						</div>
					</div>
							</div>
					</div>
					<div class="col-12 col-md-5">
						<div class="row">
							<div class="col-12 col-sm-6 col-md-12 col-lg-6 mb10">
								<label class="control-label">Pickup date</label>
								<?php
                                echo $this->widget('zii.widgets.jui.CJuiDatePicker',array(
									'model'=>$brtRoute,
									'attribute'=>'brt_pickup_date_date',
									'options'=> array('autoclose'=> true, 'startDate' => "js:new Date('$brtRoute->brt_min_date')",'format' => 'dd/mm/yyyy'),
									'htmlOptions'=> array('required' => true,'placeholder' => 'Pickup Date','value'=> $brtRoute->brt_pickup_date_date,
									'id' => 'brt_pickup_date_date_' . date('mdhis'),'min' => $brtRoute->brt_min_date,'class'=> 'form-control datePickup border-radius')
								),true);
								?>
								<span class="has-error"><?php echo $form->error($brtRoute, 'brt_pickup_date_date'); ?></span>
							</div>
							<div class="col-12 col-sm-6 col-md-12 col-lg-6">
								<label class="control-label">Time</label>
								<?php
								$this->widget('ext.timepicker.TimePicker', array(
									'model'			 => $brtRoute,
									'id'			 => 'brt_pickup_date_time_' . date('mdhis'),
									'attribute'		 => 'brt_pickup_date_time',
									'options'		 => ['widgetOptions' => array('options' => array())],
									'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control border-radius timePickup text text-info col-xs-12')
								));
								?>
                                <span class="has-error"><?php echo $form->error($brtRoute, 'brt_pickup_date_time'); ?></span>
							</div>
						</div>
					</div>
                </div>
            </div>
</div>