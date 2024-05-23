<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<div class="tab-item devSecondaryTab3" id="tab-pill-3a" style="display: none;">
	<div class="inner-tab">
		<a href="#" data-sub-tab="tab-pill-1a" class="sub-tab" style="width: calc(33.33% - 5px);">One-Way</a>
		<a href="#" data-sub-tab="tab-pill-3a" class="sub-tab active-tab-pill-button active" style="width: calc(33.33% - 5px);">Round Trip</a>
		<a href="#" data-sub-tab="tab-pill-4a" class="sub-tab" style="width: calc(33.33% - 5px);">Multi Way</a>
	</div>
	<?
	$form		 = $this->beginWidget('CActiveForm', array(
		'id'					 => 'bookingRform',
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form, data, hasError){
                        if(!hasError){
							var url = "' .CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateSearch')).'";
							return $jsBookNow.validateTrip(form,url);                        
                        }}'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'action'				 => Yii::app()->createUrl('booking/booknow'),
		'htmlOptions'			 => array(
			'class' => 'form-horizontal',
		),
	));
	/* @var $form CActiveForm */
	$brtModel	 = $model->bookingRoutes[0];
	?>
	<div class="select-box select-box-1">
		<em class="color-highlight">Source</em>
		<div id='bkt'>
			<?= $form->hiddenField($model, 'bkg_booking_type', ['value' => 2, 'id' => 'bkg_booking_type2']); ?>
			<?= $form->hiddenField($model, 'bktyp', ['value' => 2, 'id' => 'bktyp2']); ?>
			<?= $form->hiddenField($brtModel, 'brt_return_date_time', ['value' => '10:00 PM']); ?>
			<input type="hidden" id="step12" name="step" value="1">
			<input type="hidden" id="step22" name="step2" value="2">
		</div>
		<?php
		$this->widget('ext.yii-selectize.YiiSelectize', array(
			'model'				 => $brtModel,
			'attribute'			 => 'brt_from_city_id',
			'useWithBootstrap'	 => true,
			"placeholder"		 => "Source City",
			'fullWidth'			 => true,
			'htmlOptions'		 => array('width'	 => '50%', 'id'	 => 'bkg_from_city_id1',
			),
			'defaultOptions'	 => $selectizeOptions + array(
		'onInitialize'	 => "js:function(){
				$('.selectize-control INPUT').attr('autocomplete','new-password');
var booknow = new BookNow();

							booknow.populateSource(this, '{$brtModel->brt_from_city_id}');
						}",
		'onFocus'		 => "js:function() {
						$('html, body').animate({scrollTop: $(this.\$control[0]).offset().top - 80},100);
                                                       }",
		'load'		 => "js:function(query, callback){
							loadSource(query, callback);
						 }",
		'onChange'	 => "js:function(value) {
							changeDestination(value, \$dest_city1,'{$brtModel->brt_to_city_id}');
						}",
		'onFocus'	 => "js:function() {
						$('html, body').animate({scrollTop: $(this.\$control[0]).offset().top - 80},200);
                                                       }",
		'render'	 => "js:{
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
	<div class="select-box select-box-1">
		<em class="color-highlight">Destination</em>
		<?php
		$this->widget('ext.yii-selectize.YiiSelectize', array(
			'model'				 => $brtModel,
			'attribute'			 => 'brt_to_city_id',
			'useWithBootstrap'	 => true,
			"placeholder"		 => "Select Destination",
			'fullWidth'			 => true,
			'htmlOptions'		 => array('id'	 => 'bkg_to_city_id1', 'width'	 => '50%'
			),
			'defaultOptions'	 => $selectizeOptions + array(
		'onInitialize'	 => "js:function(){
				$('.selectize-control INPUT').attr('autocomplete','new-password');
					    \$dest_city1=this;
					    }",
		'onFocus'		 => "js:function() {
						$('html, body').animate({scrollTop: $(this.\$control[0]).offset().top - 80},200);
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
	<div class="one-half input-simple-1 has-icon input-blue bottom-30">

		<em class="color-highlight">Start Date</em>		
		<i class = "fa fa-calendar pr10 font-16 tx-gra-green"></i>
		<?php			
			$this->widget('booster.widgets.TbDatePicker',array(
					'name'=>'BookingRoute[brt_pickup_date_date]',
					'value'	=> $pdate,				
					'options'=>array('showAnim'=>'slide','autoclose' => true, 'startDate' => $minDate, 'format' => 'dd/mm/yyyy','minDate'=> 0,'maxDate'=>"+6M"),   
					'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',								
						'class'			 => 'border-radius font-16','id'=> 'Booking_bkg_pickup_date_date_2','style'=>'z-index:100 !important','readonly'=>'readonly')	
			));
		?>
		<span class="has-error"><? echo $form->error($model, 'bkg_pickup_date_date1'); ?></span>
	</div>
	<div class="one-half last-column input-simple-1 has-icon input-blue bottom-30">

		<em class="color-highlight">Start Time</em>
		<i class="fa fa-clock tx-gra-green font-16 pr20"></i>
		<?php
		$this->widget('ext.timepicker.TimePicker', array(
			'model'			 => $brtModel,
			'id'			 => 'brt_pickup_date_time_2' . date('mdhis'),
			'attribute'		 => 'brt_pickup_date_time',
			'options'		 => ['widgetOptions' => array('options' => array()), 'startTime'=> '00:00', 'dynamic'=>false],
			'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'timePickup font-16','readonly'=>'readonly')
		));
		?> 
	</div>
	<div class="clear"></div>
	<div class="input-simple-1 has-icon input-blue bottom-30">

		<em class="color-highlight">Return Date</em>		
		<i class = "fa fa-calendar pr10 font-16 tx-gra-green"></i>
		<?php		
			
			$this->widget('booster.widgets.TbDatePicker',array(
					'name'=>'BookingRoute[brt_return_date_date]',
					'value'	=> DateTimeFormat::DateTimeToDatePicker($defaultDate),				
					'options'=>array('showAnim'=>'slide','autoclose' => true, 'startDate' => $minDate, 'format' => 'dd/mm/yyyy','minDate'=> 0,'maxDate'=>"+6M"),   
					'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',								
						'class'			 => 'border-radius font-16','id'=> 'Booking_bkg_return_date_date1','style'=>'z-index:100 !important','readonly'=>'readonly'),
					
	
			));
		?>
	</div>
	<span class="has-error"><? echo $form->error($brtModel, 'brt_pickup_date_date1'); ?></span>
	<span class="has-error"><? echo $form->error($model, 'bkg_pickup_date_time1'); ?></span>

	<div class="content mb40 mt20 text-center">                                    
		<button type="submit" class="uppercase btn-orange shadow-medium">proceed</button>
	</div>
	<?php $this->endWidget(); ?>
</div>
