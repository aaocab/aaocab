<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?php
/* @var $brtRoute BookingRoute */

$ctr		 = rand(0, 99) . date('mdhis');
?>
<input type="hidden" name="ctr" value="<?= $ctr ?>">
<div class="content-boxed-widget mobile-type clsRoute">
<?php   if ($bkgTempModel->bkg_booking_type == 4)
		{
?>
			<div class="content p0">
				<div class="one-half">
					<input id="BookingTemp_bkg_transfer_type_0" value="1" type="radio" name="BookingTemp[bkg_transfer_type]"> Airport Pickup
				</div>
				<div class="one-half last-column">
					<input id="BookingTemp_bkg_transfer_type_1" value="2" type="radio" name="BookingTemp[bkg_transfer_type]"> Airport Drop off
				</div>
				<div class="clear"></div>
			</div>

<?php } ?>
	<div class="select-box select-box-1 mt20 select-type">
<!--		<em id='trslabel' class="color-gray">From the Airport</em>-->
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
		<span class="has-error"><? echo $form->error($brtRoute, 'brt_from_city_id'); ?></span>
		<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
	</div>


	<div class="select-box select-box-1 mt30 select-type">
		<em id='trdlabel' class="color-gray">Drop Address</em> 
		<?php
		$this->widget('application.widgets.PlaceAutoComplete', ['model'			 => $brtRoute, 'attribute'		 => 'place',
			'onPlaceChange'	 => "function(event, pacObject){ pacObject.validateAddress(event);}",
			'enableOnLoad'	 => false, 'isMobileView'	 => true, 'textArea'=>false,
			'htmlOptions'	 => ['class'			 => "customised-input border-none", "autocomplete"	 => "section-new", 'placeholder'	 => "Location",
				
			]
		]);
		?>

		<span class="has-error"><?php echo $form->error($brtRoute, 'brt_to_city_id'); ?></span>
		<span class="has-error"><?php echo $form->error($brtRoute, 'brt_pickup_date_date'); ?></span>
		<span class="has-error"><?php echo $form->error($brtRoute, 'brt_pickup_date_time'); ?></span>
	</div>

		<div class="input-simple-1 has-icon input-blue bottom-20">
			<em class="color-gray">Start Date</em>
			<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name'			 => 'BookingRoute[brt_pickup_date_date]',
				'value'			 => $brtRoute->brt_pickup_date_date,
				'options'		 => array('showAnim' => 'slide', 'autoclose' => true, 'startDate' => "js:new Date('$brtRoute->brt_min_date')", 'dateFormat' => 'dd/mm/yy', 'minDate' => 0, 'maxDate' => "+6M"),
				'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date', 'readonly'		 => 'readonly',
					'class'			 => 'border-radius font-16', 'id'			 => 'dateAirTRway', 'style'			 => 'z-index:100 !important')
			));
			?>
		</div>
	
		<div class="input-simple-1 has-icon input-blue bottom-20">
			<em class="color-gray">Start Time</em>

			<?php
			$this->widget('ext.timepicker.TimePicker', array(
				'model'			 => $brtRoute,
				'id'			 => 'brt_pickup_date_time_' . date('mdhis'),
				'attribute'		 => 'brt_pickup_date_time',
				'options'		 => ['widgetOptions' => array('options' => array()), 'startTime' => '00:00', 'dynamic' => false],
				'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'timePickup font-16', 'readonly' => 'readonly')
			));
			?> 
		</div>
	<a href="#" data-menu="map-marker" class="hide" id="booknow-map-marker"></a>
	<div class="clear"></div>
</div>
