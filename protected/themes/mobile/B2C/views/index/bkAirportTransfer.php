<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<div class="tab-item devSecondaryTab3" id="tab-pill-5a" style="display: none;">
<!--<div class="inner-tab">
        <a href="#" data-sub-tab="tab-pill-5a" class="sub-tab active-tab-pill-button active" style="width: calc(50% - 5px);">Airport</a>
        <a href="#" data-sub-tab="tab-pill-7a" class="sub-tab" style="width: calc(50% - 5px);">Day Rental</a>
</div>-->
<div class="inner-tab">
		<a href="#" data-subtab-sub="tab-pill-5a" class="sub-subtab  btnairporttransfer active" data-key="1" style="width: 48%;">Airport Pickup</a>
		<a href="#" data-subtab-sub="tab-pill-6a" class="sub-subtab btnairporttransfer" data-key="2" style="width: 48%">Airport Drop off</a>
</div>
	<?php
	/* @var $form CActiveForm|CWidget */
	$form			 = $this->beginWidget('CActiveForm', array(
		'id'					 => 'bookingAirform',
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){

                                            if(!hasError){
                                            return true;

                                            }
                                            }'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'action'				 => Yii::app()->createUrl('booking/booknow'),
		'htmlOptions'			 => array(
			'class' => 'form-horizontal',
		),
	));
	/* @var $form CActiveForm */
	?>


	<div class="checkboxes-demo">
		<?= $form->hiddenField($model, 'bkg_booking_type', ['value' => 4, 'id' => 'bkg_booking_type4']); ?>
		<?= $form->hiddenField($model, 'bktyp', ['value' => 4, 'id' => 'bktyp4']); ?>
		<?= $form->hiddenField($brtModel, 'brt_from_city_id', ['id' => 'ctyIdAir0']); ?>
		<?= $form->hiddenField($brtModel, 'brt_to_city_id', ['id' => 'ctyIdAir1']); ?>
		<?= $form->hiddenField($model, 'bkg_transfer_type',['value' => 1]); ?>
		<input type="hidden" id="step14" name="step" value="1">
	</div>
	<div class="select-box-1 bottom-20">		
<!--		<em class="color-gray mt20 n" id="slabel">From the Airport</em>-->
		<?php
		$options	 = [];
		$acWidgetId	 = CHtml::activeId($brtModel, 'place');
		$this->widget('ext.yii-selectize.YiiSelectize', array(
			'model'				 => $brtModel,
			'attribute'			 => 'airport',
			'useWithBootstrap'	 => true,
			"placeholder"		 => "Select Airport",
			'fullWidth'			 => false,
			'htmlOptions'		 => array('width' => '50%'
			),
			'defaultOptions'	 => $selectizeOptions + array(
		'onInitialize'	 => "js:function(){
							bookingModel.populateAirportList(this, '{$model->bkgAirport}');
						}",
		'load'			 => "js:function(query, callback){
							bookingModel.loadAirportSource(query, callback);
						}",
		'onChange'		 => "js:function(value) {
												PACObject.getObject('{$acWidgetId}').initAirportBounds(value);
												PACObject.getObject('{$acWidgetId}').disable();
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
		<span class="has-error"><? echo $form->error($brtModel, 'brt_from_city_id'); ?></span>
	</div>
	<div class="select-box-1 bottom-20 border-none">		
		<em class="color-gray mt20 n dlabel" id="dlabel">Drop Address</em>
		<?php
		$this->widget('application.widgets.PlaceAutoComplete', ['model'			 => $brtModel, 'attribute'		 => 'place',
			'onPlaceChange'	 => "function(event, pacObject){ pacObject.validateAddress(event);}",
			'enableOnLoad'	 => false, 'isMobileView'	 => true, 'textArea'=>false,
			'htmlOptions'	 => ['class'			 => "customised-input", "autocomplete"	 => "section-new", 'placeholder'	 => "Location",
				'onclick'		 => 'PACObject.getObject("' . CHtml::activeId($brtModel, 'place') . '").openMobileMap()'
			]
		]);
		?>
		<span class="has-error"><?php echo $form->error($brtModel, 'brt_to_city_id'); ?></span>
		<span class="has-error"><?php echo $form->error($brtModel, 'brt_pickup_date_date'); ?></span>
		<span class="has-error"><?php echo $form->error($brtModel, 'brt_pickup_date_time'); ?></span>
		<input type="hidden" value ="1" class="rad_chk1"/>
	</div>
    <div class="clear"></div>
	<div class="input-simple-1 has-icon input-blue bottom-20 top-10">

		<em class="color-gray mt10 n">Pick up date</em>
		<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'name'=>'BookingRoute[brt_pickup_date_date]',
				'value'	=> $pdate,				
				'options'=>array('showAnim'=>'slide','autoclose' => true, 'startDate' => $minDate, 'dateFormat' => 'dd/mm/yy','minDate'=> 0,'maxDate'=>"+6M"),   
				'htmlOptions' => array('required' => true, 'placeholder'  => 'Pickup Date','readonly'=>'readonly',								
				'class'	 => 'border-radius font-16','id'=> 'Booking_bkg_pickup_date_date_11','style'=>'z-index:100 !important')	
			));

		?>
	</div>
	<div class="last-column input-simple-1 has-icon input-blue bottom-10 top-10">

		<em class="color-gray mt10 n">Pick up time</em>
		<?php
		$this->widget('ext.timepicker.TimePicker', array(
			'model'			 => $brtModel,
			'id'			 => 'brt_pickup_date_time_4' . date('mdhis'),
			'attribute'		 => 'brt_pickup_date_time',
			'options'		 => ['widgetOptions' => array('options' => array()), 'startTime'=> '00:00', 'dynamic'=>false],
			'htmlOptions'	 => array('required' => true, 'placeholder' => 'Add a time', 'class' => 'timePickup font-16','readonly'=>'readonly', 'style'=>'font-size:16px; font-weight:bold')
		));
		?> 
	</div>
<!--		<div style="width:20%;float: left;display: table;height: 25%;">
			<div class="font-18 text-center mt0" style="display: table-cell;vertical-align: middle;">
				<span style="border-radius: 50%;padding: 13px;background-color: #357e95;cursor: pointer;box-shadow: 10px 5px 17px grey;"><i class="fas fa-exchange-alt fa-rotate-90" onclick="hyperModel.swap()" style="color:#fff;"></i></span>
			</div>
		</div>-->
	<div class="clear"></div>
        <div class="mb0 text-center mt20">
            <!--                                    <a href="#" class="button shadow-medium button-full button-round button-orange-3d button-orange uppercase ultrabold">Button</a>-->
            <button type="button" id="btnTransfer" class="btn-submit-orange">Search</button>
        </div>
	<a href="#" data-menu="map-marker" class="hide" id="booknow-map-marker"></a>
	<?php $this->endWidget(); ?>
</div>

<script>
	//hyperModel.initializeplAirport();
	$("#brt_location1").blur(function(){
	  if($("#locLat0").val()=="")
	  {
		 var content ="Please select proper source address";
		//alert("");
		$jsBookNow.showErrorMsg(content); 
	  }
	});
	$("#brt_location0").focus(function(){
		//if($("#locLat0").val()=="")
		//{
		$('#brt_location1').val('');
		//}
	});
	
	$('#btnTransfer').click(function(){
		$.ajax({
			"type":"POST",
			"async":false,
			"url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateAirport')) ?>',
			"data": $('#bookingAirform').serialize(),
			"dataType": "json",
			"success":function(data1)
			{
				if(data1.success)
				{
					if(data1.hasOwnProperty("errors"))
					{
						$("#bkg_booking_type4").val(1);
					}
					
					$('#bookingAirform').submit();
				}
				else
				{
					var errors = data1.errors;
					var content = "";
					for(var key in errors)
					{
						$.each(errors[key], function (j, message) {
							content = content + message + '<br>';
							});
					}
					$jsBookNow.showErrorMsg(content); 
				}  
			}

		});

	});
</script>