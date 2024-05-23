<div class="tab-item devSecondaryTab3" id="tab-pill-7a" style="display: none">
	<!--    <div class="inner-tab">
			<a href="#" data-sub-tab="tab-pill-5a" class="sub-tab" style="width: calc(50% - 5px);">Airport</a>
			<a href="#" data-sub-tab="tab-pill-7a" class="sub-tab active-tab-pill-button active" style="width: calc(50% - 5px);">Day Rental</a>
		</div>-->
        <div class="inner-tab">
            <a href="#" data-sub-tab="tab-pill-15a" class="sub-tab active pl0 pr0" style="width: calc(30% - 5px);">4Hr - 40Km</a>
            <a href="#" data-sub-tab="tab-pill-16a" class="sub-tab pl0 pr0" style="width: calc(30% - 5px);">8Hr - 80Km</a>
            <a href="#" data-sub-tab="tab-pill-17a" class="sub-tab pl0 pr0" style="width: calc(40% - 5px);">12Hr - 120Km</a>
        </div>

	<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id'					 => 'bookingDRform',
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
                if(!hasError){
                    var url = "' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/ValidateDayRentalSearch')) . '";
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

    <div class="select-box-1 bottom-20">
		<?= $form->hiddenField($model, 'bkg_booking_type', ['value' => 9, 'id' => 'bkg_booking_type1']); ?>
		<?= $form->hiddenField($model, 'bktyp', ['value' => 9, 'id' => 'bktyp1']); ?>
        <input type="hidden" id="step11" name="step" value="1">
        <em class="color-gray mt20 n">From</em>
		<?php
		$widgetId		 = $ctr . "_" . random_int(99999, 10000000);
		$this->widget('application.widgets.BRCities', array(
			'type'				 => 1,
			'enable'			 => ($index == 0),
			'widgetId'			 => $widgetId,
			'model'				 => $brtModel,
			'attribute'			 => 'brt_from_city_id',
			'useWithBootstrap'	 => true,
			"placeholder"		 => "Pick up city",
			'htmlOptions'		 => array('width' => '100%', 'id' => 'bkg_from_city_id_rental'),
			'defaultOptions'	 => [
				'onFocus' => "js:function() {
						$('html, body').animate({scrollTop: $(this.\$control[0]).offset().top - 80},200);
                                                       }",
			]
		));
		?>
    </div>

    <div class="select-box-1 bottom-10">
        <em class="color-gray mt20 n">Type</em>  
		<?php
//		$rentalTypeArr	 = Booking::model()->rental_types;
//		$this->widget('ext.yii-selectize.YiiSelectize', array(
//			'model'				 => $model,
//			'attribute'			 => 'bkg_booking_type',
//			'useWithBootstrap'	 => true,
//			'data'				 => $rentalTypeArr,
//			'placeholder'		 => "Hr - Km",
//			'fullWidth'			 => true,
//			'htmlOptions'		 => array('width'	 => '100%', 'id'	 => 'BookingTemp_bkg_booking_type_rental', 'class'	 => 'form-control ctySelect2',
//			),
//		));


			$rentalTypeArr	= Booking::model()->rental_types;
			$rentalTypeArr  = ['' => 'Hr - Km'] + $rentalTypeArr;
			echo $form->dropDownList($model,"bkg_booking_type",$rentalTypeArr,['id' => 'BookingTemp_bkg_booking_type_rental','class'=> 'form-control','placeholder' => 'Hr - Km','style' =>'width:100%',]);
		?>
    </div>

    <div class="input-simple-1 has-icon input-blue bottom-20">		
        <em class="color-gray mt10 n">Pick up date</em>
		<?php
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'name'			 => 'BookingRoute[brt_pickup_date_date]',
			'value'			 => $pdate,
			'options'		 => array('showAnim' => 'slide', 'autoclose' => true, 'startDate' => $minDate, 'dateFormat' => 'dd/mm/yy', 'minDate' => 0, 'maxDate' => "+6M"),
			'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Add a date', 'readonly'		 => 'readonly',
				'class'			 => 'border-radius font-16', 'id'			 => 'BookingRoute_brt_pickup_date_date_rental', 'style' => 'z-index:100 !important; font-size:16px!important; font-weight:bold!important')
		));
		?>
    </div>

    <div class="input-simple-1 has-icon input-blue bottom-20">
        <em class="color-gray mt10 n">Pick up time</em>					
		<?php
		$this->widget('ext.timepicker.TimePicker', array(
			'model'			 => $brtModel,
			'id'			 => 'brt_pickup_date_time_rental_' . date('mdhis'),
			'attribute'		 => 'brt_pickup_date_time',
			'options'		 => ['widgetOptions' => array('options' => array()), 'startTime' => '00:00', 'dynamic' => false],
			'htmlOptions'	 => array('required' => true, 'placeholder' => 'Add a time', 'class' => 'timePickup font-16', 'readonly' => 'readonly', 'style' =>'font-size:16px; font-weight:bold',)
		));
		?> 
    </div>

    <span class="has-error"><?php echo $form->error($brtModel, ' brt_to_city_id'); ?></span>
    <span class="has-error"><?php echo $form->error($brtModel, ' brt_pickup_date_date'); ?></span>
    <span class="has-error"><?php echo $form->error($brtModel, ' brt_pickup_date_time'); ?></span>
    <input type="hidden" value ="1" class="rad_chk1"/>

    <div class="clear"></div>
    <div class="content mb10 mt0 text-center">                                    
        <button type="button" class="btn-submit-orange" id="dayrentalbtn">Search</button>
    </div>
	<?php $this->endWidget(); ?>			
</div>

<script>
    var hyperModel = new HyperLocation();
    $('#BookingTemp_bkg_booking_type_rental').change(function ()
    {	
        var rentalVal = $('#BookingTemp_bkg_booking_type_rental').val();
		//alert(rentalVal);
        if(rentalVal == 9){
			$('a[data-sub-tab = "tab-pill-15a"]').addClass('active');
			$('a[data-sub-tab = "tab-pill-16a"]').removeClass('active active-tab-pill-button');
			$('a[data-sub-tab = "tab-pill-17a"]').removeClass('active active-tab-pill-button');
		}
		if(rentalVal == 10){
			$('a[data-sub-tab = "tab-pill-16a"]').addClass('active');
			$('a[data-sub-tab = "tab-pill-15a"]').removeClass('active active-tab-pill-button');
			$('a[data-sub-tab = "tab-pill-17a"]').removeClass('active active-tab-pill-button');
		}
		if(rentalVal == 11){
			$('a[data-sub-tab = "tab-pill-17a"]').addClass('active');
			$('a[data-sub-tab = "tab-pill-15a"]').removeClass('active active-tab-pill-button');
			$('a[data-sub-tab = "tab-pill-16a"]').removeClass('active active-tab-pill-button');
		}
        $('#bkg_booking_type1').val($('#BookingTemp_bkg_booking_type_rental').val());
        $('#bktyp1').val($('#BookingTemp_bkg_booking_type_rental').val());
    });

	$('a[data-sub-tab = "tab-pill-15a"]').click(function(){
		$("#BookingTemp_bkg_booking_type_rental").val(9).change();
	});
	$('a[data-sub-tab = "tab-pill-16a"]').click(function(){
		$("#BookingTemp_bkg_booking_type_rental").val(10).change();
    });
	$('a[data-sub-tab = "tab-pill-17a"]').click(function(){
		$("#BookingTemp_bkg_booking_type_rental").val(11).change();
	});
	
    $('#dayrentalbtn').click(function ()
    {
        $.ajax({
            "type": "GET",
            "async": false,
            "url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateDayRental')) ?>',
            "data": {'fromCityId': $('#bkg_from_city_id_rental').val(), 'bkType': $('#BookingTemp_bkg_booking_type_rental').val()},
            "dataType": "json",
            "success": function (data1)
            {
                if (data1.success == true)
                {
                    $('#bkg_booking_type1').val(data1.bkType);
                    $('#bktyp1').val(data1.bkType);
                    $('#OnelocLat0').val(data1.from.cty_lat);
                    $('#OnelocLon0').val(data1.from.cty_long);
                    $('#OnelocFAdd0').val(data1.from.cty_garage_address);
                    $('#Onelocation0').val(data1.from.cty_garage_address);
                    $('#OneisAirport0').val(data1.from.cty_is_airport);

                    $('#bookingDRform').submit();
                } else
                {
                    $('#bkg_booking_type1').val(data1.bkType);
                    $('#bktyp1').val(data1.bkType);
                    $('#OnelocLat0').val('');
                    $('#OnelocLon0').val('');
                    $('#OnelocFAdd0').val('');
                    $('#Onelocation0').val('');
                    $('#OneisAirport0').val('');

                    if ((($("#BookingTemp_bkg_booking_type_rental").val()) == '') || (($("#bkg_from_city_id_rental").val()) == ''))
                    {
                        var content = "You Should Enter City/Rental Trip Type.";

                        if (data1.errorMsg != '' || data1.errorMsg != undefined)
                        {
                            content = data1.errorMsg;
                        }
                        hyperModel.showErrorMsg(content);
                    }
                }
            }
        });
    });
</script>
