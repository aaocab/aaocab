<div class="tab-item devSecondaryTab3" id="tab-pill-10a" style="display: none;">
<!--    <div class="inner-tab">
        <a href="#" data-sub-tab="tab-pill-1a" class="sub-tab" style="width: calc(30% - 5px);">One-Way</a>
		<a href="#" data-sub-tab="tab-pill-3a" class="sub-tab" style="width: calc(33.33% - 5px);">Round Trip</a>
        <a href="#" data-sub-tab="tab-pill-4a" class="sub-tab" style="width: calc(40% - 5px);">Round Trip or<br>Multi City</a>
		<a href="#" data-sub-tab="tab-pill-8a" class="sub-tab" style="width: calc(30% - 5px);">Packages</a>
    </div>-->
<!--	<div class="inner-tab">
        <a href="#" data-sub-tab="tab-pill-1a" class="sub-tab btnpersonalcab" style="width: calc(50% - 5px);">Personal Cab</a>
		<a href="#" data-sub-tab="tab-pill-10a" class="sub-tab active-tab-pill-button active" style="width: calc(50% - 5px);">Daily Shuttle</a>
    </div>-->
    <?
    $form = $this->beginWidget('CActiveForm', array(
        'id'                     => 'shuttleform',
        'enableClientValidation' => true,
        'clientOptions'          => array(
            'validateOnSubmit' => true,
            'errorCssClass'    => 'has-error',
            'afterValidate'    => 'js:function(form,data,hasError){
                        if(!hasError){
							return true;
                        }}'
        ),
        'enableAjaxValidation'   => false,
        'errorMessageCssClass'   => 'help-block',
        'action'                 => Yii::app()->createUrl('booking/booknow'),
        'htmlOptions'            => array(
            'class' => 'form-horizontal',
        ),
    ));
    /* @var $form CActiveForm */
    ?>
    
    <?= $form->hiddenField($model, 'bkg_booking_type', ['value' => 7, 'id' => 'bkg_booking_type3']); ?>
    <?= $form->hiddenField($model, 'bktyp', ['value' => 7, 'id' => 'bktyp7']); ?>
    <input type="hidden" id="step27" name="step2" value="2">
    <input type="hidden" id="step17" name="step" value="1">
    <div class="input-simple-1 has-icon input-blue bottom-30">		
        <em class="color-gray">Depart date</em>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'name'=>'BookingRoute[brt_pickup_date_date]',
				'value'	=> $pdate,				
				'options'=>array('showAnim'=>'slide','autoclose' => true, 'startDate' => $minDate, 'dateFormat' => 'dd/mm/yy','minDate'=> 0,'maxDate'=>"+6M"),   
				'htmlOptions'	 => array('required' => true, 'placeholder'	 => 'Pickup Date','readonly'=>'readonly',								
				'class'	=> 'border-radius font-16 datePickup','id'=> 'brt_pickup_date_date_shuttle','min' => $brtModel->brt_min_date,'style'=>'z-index:100 !important')	
			));
        ?>
		<input type='hidden' id="<?= 'brt_pickup_date_time_' . date('mdhis') ?>" name="BookingRoute[brt_pickup_date_time]"  value="<?= $brtModel->brt_pickup_date_time ?>" >
    </div>
	<div class="clear"></div>
        <div class="select-box-1 bottom-40">
            <em class="color-gray mt20 n">Depart from City</em>
            <select class="yii-selectize full-width inputSource pt5 pl0" name="BookingRoute[brt_from_city_id]" placeholder="Pickup City"
                    id="brt_from_city_id_shuttle" onchange="populateDropCity('<?= $brtModel->brt_to_city_id ?>')">
            </select>
        </div>
	<div class="clear"></div>
        <div class=" select-box-1 bottom-30">
            <em class="color-gray mt20 n">Arrive at City</em>
            <select class="yii-selectize full-width destSource " name="BookingRoute[brt_to_city_id]"  id="brt_to_city_id_shuttle">
            </select>
        </div>
   
    <div class="clear"></div>
    <div class="content mb10 mt0 text-center">                                    
        <button type="submit" class="btn-submit-orange">search</button>
    </div>
<?php $this->endWidget(); ?>			
</div>

<script>
   $(document).ready(function (){
		populateShuttleSource('<?= $brtModel->brt_from_city_id ?>');
	});
	
	$('#brt_pickup_date_date_shuttle').change(function () {
		$('.destSource').val('');
		populateShuttleSource();

	});
	
	function populateShuttleSource(fromCityId) {
		dateVal = $('#brt_pickup_date_date_shuttle').val();

		$('.destSource').html('');

		$.ajax({
			"type": "POST",
			dataType: 'json',
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('shuttle/getpickupcitylist')) ?>",
			data: {
				'dateVal': dateVal
			},
			"async": false,
			"success": function (data1)
			{
				$('.inputSource').html('');
				$('.inputSource').children('option').remove();
				$(".inputSource").append('<option value="">Select Pickup City</option>');
				$.each(data1, function (key, value) {
					$('.inputSource').append($("<option></option>").attr("value", key).text(value));
				});
				if(fromCityId > 0)
				{
					$('.inputSource').val(fromCityId).change();
				}
			}
		});
	}
	
	function populateDropCity(toCityId) {

		dateVal = $('#brt_pickup_date_date_shuttle').val();
		fcityVal = $('.inputSource').val();

		$.ajax({
			"type": "POST",
			dataType: 'json',
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('shuttle/getdropcitylist')) ?>",
			data: {
				'dateVal': dateVal, 'fcityVal': fcityVal
			},
			"async": false,
			"success": function (data1)
			{
				$('.destSource').html('');
				$('.destSource').children('option').remove();
				$(".destSource").append('<option value="">Select Drop City</option>');
				$.each(data1, function (key, value) {
					$('.destSource').append($("<option></option>").attr("value", key).text(value));
				});
				if(toCityId > 0)
				{
					$('.destSource').val(toCityId).change();
				}
			}
		});
	}
</script>
