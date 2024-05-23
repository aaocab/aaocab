<div class="tab-item devSecondaryTab3 active-tab" id="tab-pill-1a" style="display: block;">
    <div class="inner-tab">
        <a href="#" data-sub-tab="tab-pill-1a" class="sub-tab active-tab-pill-button active" style="width: calc(30% - 5px);">One-Way</a>
		<!--<a href="#" data-sub-tab="tab-pill-3a" class="sub-tab" style="width: calc(33.33% - 5px);">Round Trip</a>-->
        <a href="#" data-sub-tab="tab-pill-4a" class="sub-tab" style="width: calc(40% - 5px);">Round Trip</a>
		<a href="#" data-sub-tab="tab-pill-8a" class="sub-tab" style="width: calc(30% - 5px);">Packages</a>
    </div>
<!--	<div class="inner-tab">
        <a href="#" data-sub-tab="tab-pill-1a" class="sub-tab active-tab-pill-button active" style="width: calc(50% - 5px);">Personal Cab</a>
		<a href="#" data-sub-tab="tab-pill-10a" class="sub-tab" style="width: calc(50% - 5px);">Daily Shuttle</a>
    </div>-->
	<?php
	$form		 = $this->beginWidget('CActiveForm', array(
		'id'					 => 'bookingSform',
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){
							var url = "' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateSearch')) . '";
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
	?>
    <!--	<div class="inner-tab devSecondaryTab1">
                    <a href="#" class="active">Full Cab</a><a href="#">Shared Cab</a>
                    <a href="#" data-sub-tab="tab-pill-1a" class="sub-tab active-tab-pill-button active">Full Cab</a>
                    <a href="#" data-sub-tab="tab-pill-1b" class="sub-tab">Shared Cab</a>
            </div>-->
    <div class="select-box-1 bottom-20">
		<?= $form->hiddenField($model, 'bkg_booking_type', ['value' => 1, 'id' => 'bkg_booking_type1']); ?>
		<?= $form->hiddenField($model, 'bktyp', ['value' => 1, 'id' => 'bktyp1']); ?>
		<?= $form->hiddenField($model, 'bkg_transfer_type', ['id' => 'bkg_transfer_type1', 'value' => 0]); ?>
        <input type="hidden" id="step11" name="step" value="1">
        <em class="color-gray mt20 n">From</em>
		<?php
		$widgetId	 = $ctr . "_" . random_int(99999, 10000000);
		$this->widget('application.widgets.BRCities', array(
			'type'				 => 1,
			'enable'			 => ($index == 0),
			'widgetId'			 => $widgetId,
			'model'				 => $brtModel,
			'attribute'			 => 'brt_from_city_id',
			'useWithBootstrap'	 => true,
			"placeholder"		 => "Pick up city",
			'defaultOptions'	 => [
				'onFocus' => "js:function() {
						$('html, body').animate({scrollTop: $(this.\$control[0]).offset().top - 80},200);
                                                       }",
			]
		));
		?>
    </div>
    
    <div class="select-box-1 bottom-10">
        <em class="color-gray mt20 n">To</em>
		<?php
		$this->widget('application.widgets.BRCities', array(
			'type'				 => 2,
			'enable'			 => ($index == 0),
			'widgetId'			 => $widgetId,
			'model'				 => $brtModel,
			'attribute'			 => 'brt_to_city_id',
			'useWithBootstrap'	 => true,
			"placeholder"		 => "Drop-off city",
			'defaultOptions'	 => [
				'onFocus' => "js:function() {
						$('html, body').animate({scrollTop: $(this.\$control[0]).offset().top - 80},200);
                                                       }",
			]
		));
		?>
        <span class="has-error"><?php echo $form->error($brtModel, ' brt_to_city_id'); ?></span>
        <span class="has-error"><?php echo $form->error($brtModel, ' brt_pickup_date_date'); ?></span>
        <span class="has-error"><?php echo $form->error($brtModel, ' brt_pickup_date_time'); ?></span>
    </div>
    <div class="input-simple-1 has-icon input-blue bottom-10">		
        <em class="color-gray">Pick up date</em>
		<?php
        $this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'name'=>'BookingRoute[brt_pickup_date_date]',
				'value'	=> $pdate,				
				'options'=>array('showAnim'=>'slide','autoclose' => true, 'startDate' => $minDate, 'dateFormat' => 'dd/mm/yy','minDate'=> 0,'maxDate'=>"+6M"),   
				'htmlOptions' => array('required' => true, 'placeholder'  => 'Add a date','readonly'=>'readonly',								
				'class'	 => 'border-radius font-16','id'=> 'BookingRoute_brt_pickup_date_date','style'=>'z-index:100!important;font-size:16px;font-weight:bold')	
			));
		?>

    </div>
    <div class="input-simple-1 has-icon input-blue bottom-20">
        <em class="color-gray">Pick up time</em>					
		<?php
		$this->widget('ext.timepicker.TimePicker', array(
			'model'			 => $brtModel,
			'id'			 => 'brt_pickup_date_time_' . date('mdhis'),
			'attribute'		 => 'brt_pickup_date_time',
			'options'		 => ['widgetOptions' => array('options' => array()), 'startTime' => '00:00', 'dynamic' => false],
			'htmlOptions'	 => array('required' => true, 'placeholder' => 'Add a time', 'class' => 'timePickup font-16', 'readonly' => 'readonly', 'style'=>'font-size:16px; font-weight:bold')
		));
		?> 
    </div>
    <div class="clear"></div>
    <div class="content mb10 mt0 text-center">                                    
        <button type="button" class="btn-submit-orange" id="onewaybtn">Search</button>
    </div>
	<?php $this->endWidget(); ?>			
</div>

<script>
    $('#onewaybtn').click(function ()
    {
		var currFromCtyId  = $('SELECT.ctyPickup').val();
			var currToCtyId    = $('SELECT.ctyDrop').val();
			if(currFromCtyId=='' || currToCtyId=='')
			{
				alert("Please select source/destintion city");
				return false;
				
			}
			if($(".datePickup").val()=='' || $(".timePickup").val() == '')
			{
				alert("Please select pickup date/time");
				return false;
			}
        $.ajax({
            "type": "GET",
            "async": false,
            "url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateOneway')) ?>',
            "data": {'fromCityId': $('#BookingRoute_brt_from_city_id').val(), 'toCityId': $('#BookingRoute_brt_to_city_id').val()},
            "dataType": "json",
            "success": function (data1)
            {
                if (data1.success == true)
                {
                    $('#bkg_booking_type1').val(data1.bkType);
                    $('#bkg_transfer_type1').val(data1.transferType);
                    $('#OnelocLat0').val(data1.from.cty_lat);
                    $('#OnelocLon0').val(data1.from.cty_long);
                    $('#OnelocFAdd0').val(data1.from.cty_garage_address);
                    $('#Onelocation0').val(data1.from.cty_garage_address);
                    $('#OneisAirport0').val(data1.from.cty_is_airport);

                    $('#OnelocLat1').val(data1.to.cty_lat);
                    $('#OnelocLon1').val(data1.to.cty_long);
                    $('#OnelocFAdd1').val(data1.to.cty_garage_address);
                    $('#Onelocation1').val(data1.to.cty_garage_address);
                    $('#OneisAirport1').val(data1.to.cty_is_airport);
                }
                else
                {
                    $('#bkg_booking_type1').val(1);
                    $('#bkg_transfer_type1').val(0);
                    $('#OnelocLat0').val('');
                    $('#OnelocLon0').val('');
                    $('#OnelocFAdd0').val('');
                    $('#Onelocation0').val('');
                    $('#OneisAirport0').val('');

                    $('#OnelocLat1').val('');
                    $('#OnelocLon1').val('');
                    $('#OnelocFAdd1').val('');
                    $('#Onelocation1').val('');
                    $('#OneisAirport1').val('');
                }
                $('#bookingSform').submit();
            }

        });
    });
</script>
