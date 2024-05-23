<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
$bmodel				 = Booking::model();
$ctr				 = rand(0, 99) . date('mdhis');
?>
<input type="hidden" name="ctr" value="<?= $ctr ?>">
<div class="container mt30 clsRoute">
	<div class="bg-white-box">
		<div class="row mb20">  
			<div class="col-12 col-sm-8 col-lg-4 <?= $rcitiesDiv . $mcitiesDiv ?>" >
				<label class="control-label" id='trslabel'>Going From</label><br>
				<?php
				if ($index > 0)
				{
					echo TbHtml::activeHiddenField($model, '[' . $ctr . ']brt_from_city_id', array('id' => 'brt_from_city_id_' . $ctr));
					echo CHtml::textField('sourceCityName_' . $ctr, $sourceCityName, array('class' => 'form-control ctyPickup ctySelect2', 'readonly' => 'readonly'));
				}
				else
				{
								$widgetId = $ctr . "_" . random_int(99999, 10000000);
								$this->widget('application.widgets.BRCities', array(
									'type'				 => 1,
									'enable'			 => ($index == 0),
									'widgetId'			 => $widgetId,
									'model'				 => $model,
									'attribute'			 => '[' . $ctr . ']brt_from_city_id',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select City",
								));
								
				}
				?>
				<select class="form-control arrivecity " name="BookingRoute[<?= $ctr ?>][brt_to_city_id]"  
						id="<?= 'brt_to_city_id_' . $ctr ?>"  >
				</select>
				<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
			</div>
			<div class="col-12 col-sm-4 col-lg <?= $mcitiesDiv ?>">
				<div class="control-label mb5">Rental Types</div>
				<?php
				$rentalTypeArr				 = Booking::model()->rental_types;
				$bmodel->bkg_booking_type	 = (in_array($rentalTypeArr, ['9', '10', '11'])) ? $rentalTypeArr : $btype;
				echo CHtml::activeDropDownList($bmodel, "bkg_booking_type", $rentalTypeArr, array('style' => 'width:100%', 'class'=>'form-control', 'placeholder' => 'Hr - Km', 'onChange' => 'setBookingType(this)', 'id' => 'BookingTemp_bkg_booking_type_rental'));
				?>
			</div>

			<div class="col-12 col-sm-6 col-lg mb5 <?= $rcitiesDiv ?>">
				<label class="control-label">Depart date</label>
				<div class="input-group">
				<?php
                echo $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'model'=>$model,
						'attribute'=>'[' . $ctr . ']brt_pickup_date_date',
						'options'=> array('autoclose'=> true, 'startDate'=> "js:new Date('$model->brt_min_date')",'format'=> 'dd/mm/yyyy'),
						'htmlOptions'=> array('required' => true,'placeholder' => 'Pickup Date','value'=> $model->brt_pickup_date_date,
						'id' => 'brt_pickup_date_date_' . date('mdhis'),'min' => $model->brt_min_date,'class'=> 'form-control datePickup border-radius')
					 ),true);
				?>
				</div>
			</div>

			<div class="col-12 col-sm-6 col-lg mb5">
				<label class="control-label">Depart time</label>
				<?php
				$this->widget('ext.timepicker.TimePicker', array(
					'model'			 => $model,
					'id'			 => 'brt_pickup_date_time_' . date('mdhis'),
					'attribute'		 => '[' . $ctr . ']brt_pickup_date_time',
					'options'		 => ['widgetOptions' => array('options' => array())],
					'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control border-radius timePickup text text-info col-12')
				));
				?> 
			</div>
		</div>
	</div>
</div>
<script>
    function setBookingType(obj)
    {
        if ($(obj).val() == 9)
        {
            $('#topRouteDesc').html('Day Rental 4hr-40km');
        }
        if ($(obj).val() == 10)
        {
            $('#topRouteDesc').html('Day Rental 8hr-80km');
        }
        if ($(obj).val() == 11)
        {
            $('#topRouteDesc').html('Day Rental 12hr-120km');
        }
        $('#BookingTemp_bkg_booking_type').val($(obj).val());
    }
</script>