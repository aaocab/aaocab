

<?php
// if (Yii::app()->request->cookies->contains('itineraryCookie'))
//            {
//     
//                $var     = Yii::app()->request->cookies['itineraryCookie']->value;
//                $dateVar = explode(" ", Filter::getDateFormatted($var->pickupTime));
//
//                $cookieSourceCity      = $var->source->city->id;
//                $cookieDestinationCity = $var->destination->city->id;
//
//               
//            }
        

?>
<div class="container clsRoute">
	<div class="row mb-2 radio-style4">
		<div class="col-12 text-center"><p class="merriw heading-line">Local cab rental</p></div>
		<div class="col-12 col-md-6 col-lg-6 offset-lg-3">
			<label for="iconLeft">Pickup city</label>
			<?php
			$ctr		 = rand(0, 99) . date('mdhis');
			$widgetId	 = $ctr . "_" . random_int(99999, 10000000);
			$this->widget('application.widgets.BRCities', array(
				'type'				 => 1,
				'enable'			 => ($index == 0),
				'widgetId'			 => $widgetId,
				'model'				 => $model,
				'attribute'			 => 'brt_from_city_id',
				'useWithBootstrap'	 => true,
                 'isCookieActive'              =>     true,
                 'cookieSource'              =>     $cookieSourceCity,
				"placeholder"		 => "Select City where you need the cab?",
			));
			?>
		</div>
		<div class="col-12 col-md-6 col-lg-6 offset-lg-3 mb-1 radio">
			<span class="font-1rem">Select length of time for which you require a cab</span>
<?php
$bmodel						 = Booking::model();
$rentalTypeArr				 = Booking::model()->rental_types;
$bmodel->bkg_booking_type	 = (in_array($rentalTypeArr, ['10', '11'])) ? $rentalTypeArr : $btype;
echo CHtml::activeDropDownList($bmodel, "bkg_booking_type", $rentalTypeArr,
    array('style' => 'width:100%', 
        'class' => 'form-control',
        'placeholder' => 'Hr - Km',
        'onChange' => 'setBookingType(this)',
        'id' => 'BookingTemp_bkg_booking_type_rental'));
?>
		</div>
		<div class="col-6 col-md-6 col-lg-3 offset-lg-3 mb-2">
			<label for="iconLeft">Date of pickup</label>
<?php
$minDate					 = ($model->brt_min_date != '') ? $model->brt_min_date : date('Y-m-d');
$formattedMinDate			 = DateTimeFormat::DateToDatePicker($minDate);
echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
	'model'			 => $model,
	'attribute'		 => 'brt_pickup_date_date',
	'options'		 => array('autoclose' => true, 'dateFormat' => 'dd/mm/yy', 'minDate' => $formattedMinDate),
	'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date', 'value'			 => $model->brt_pickup_date_date,
		'id'			 => 'brt_pickup_date_date_' . date('mdhis'), 'min'			 => $model->brt_min_date, 'class'			 => 'form-control datePickup border-radius')
		), true);
?>
		</div>
		<div class="col-6 col-md-6 col-lg-3 mb-2">
			<label for="iconLeft">Time of pickup</label>
<?php
$this->widget('ext.timepicker.TimePicker', array(
	'model'			 => $model,
	'id'			 => 'brt_pickup_date_time_' . date('mdhis'),
	'attribute'		 => 'brt_pickup_date_time',
	'options'		 => ['widgetOptions' => array('options' => array())],
	'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control border-radius timePickup text text-info col-12')
));
?>
		</div>
		<div class="col-12 col-lg-6 offset-lg-3">
			<div class="row">
				<div class="col-2 col-lg-2 roundimage hide"><div class="round-2"><img src="/images/img-2022/sonia.jpg" alt="Sonia" title="Sonia"></div></div>
				<div class="col-10 col-lg-10 d-lg-none d-xl-none"><span class="cabcontent"><p class="typing"></p><div class="hiders"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p></div></span></div>
				<div class="col-10 col-lg-10 cabcontent d-none d-lg-block"><p class="typing"></p><div class="hiders"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p></div></div>
			</div>
		</div>
	</div>
<div class="row">
<div class="col-12">
	<div class="row m0 cc-2">
		<div class="col-12 text-center pb10">
			<input type="button" value="Go back" rid="<?= $rid ?>"  step="<?= $pageid ?>" name="yt0" class="btn btn-light backButton">
<?= CHtml::submitButton('NEXT', array('class' => 'btn btn-primary pl-5 pr-5', 'id' => 'dayrentalbtn')); ?>
		</div>
	</div>
</div>
</div>
<!--<div class="row mb30">
<div class="col-12 text-center"><a href="http://www.aaocab.com/terms/doubleback" target="_blank"><img src="/images/double-back-banner.webp?v=0.3" alt="Img" class="img-fluid"></a></div>
</div>-->
</div>
<script type="text/javascript">

    function setBookingType(obj)
    {
		//alert(obj);
		$("input[name='BookingTemp[bkg_booking_type]']").val($(obj).val()).change();
        $('#BookingTemp_bkg_booking_type').val($(obj).val());
    }

    $('#dayrentalbtn').click(function ()
    {
        $("#error_div").html("");
        $("#error_div").hide();
        var currFromCtyId = $('SELECT.ctyPickup').val();
        if (currFromCtyId == '')
        {
            $("#error_div").html("Please select Source city");
            $("#error_div").show();
            return false;

        }
        if ($(".datePickup").val() == '' || $(".timePickup").val() == '')
        {
            $("#error_div").html("Please select pickup date/time");
            $("#error_div").show();
            return false;
        }
        return true;
    });

    $(document).ready(function ()
    {
        var tncval = JSON.parse('<?= $tncArr ?>');
       // $('.cabcontent').html(tncval[73]);
        $('.typing').html(tncval[73]);
        $('.roundimage').removeClass('hide');
    });

</script>