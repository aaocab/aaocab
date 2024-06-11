
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

<div class="container search-panel-2 mt15 clsRoute">
	<div class="row">
		<input type="hidden" id="contenttype" value="69">
		<div class="col-12 text-center"><p class="merriw heading-line">One way trip</p></div>
		<div class="col-12 col-md-6 col-lg-6 col-xl-4">
			<label for="iconLeft">Source city</label>
			<?php
			$widgetId			 = $ctr . "_" . random_int(99999, 10000000);
           // $model->brt_from_city_id = $cookieSourceCity;
			$this->widget('application.widgets.BRCities', array(
				'type'				 => 1,
				'enable'			 => ($index == 0),
				'widgetId'			 => $widgetId,
				'model'				 => $model,
				'attribute'			 => 'brt_from_city_id',
				'useWithBootstrap'	 => true,
                'isCookieActive'              =>     true,
                 'cookieSource'              =>     $cookieSourceCity,
				"placeholder"		 => "Select City",
			));
			?>
			<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
		</div>
		<div class="col-12 col-md-6 col-lg-6 col-xl-4">
			<label for="iconLeft">Destination city</label>
			<?php
          //   $model->brt_to_city_id = $cookieDestinationCity;
			$this->widget('application.widgets.BRCities', array(
				'type'				 => 2,
				'widgetId'			 => $widgetId,
				'model'				 => $model,
				'attribute'			 => 'brt_to_city_id',
				'useWithBootstrap'	 => true,
                 'isCookieActive'              =>     true,
                 'cookieDestination'              =>     $cookieDestinationCity,
				"placeholder"		 => "Select City",
			));
			?>
		</div>
		<div class="col-6 col-md-6 col-lg-6 col-xl-2">
			<label for="iconLeft">Date of departure</label>
			<?php
			$minDate			 = ($model->brt_min_date != '') ? $model->brt_min_date : date('Y-m-d');
			$formattedMinDate	 = DateTimeFormat::DateToDatePicker($minDate);
			echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'model'			 => $model,
				'attribute'		 => 'brt_pickup_date_date',
				'options'		 => array('autoclose' => true, 'dateFormat' => 'dd/mm/yy', 'minDate' => $formattedMinDate),
				'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
					'value'			 => $model->brt_pickup_date_date, 'id'			 => 'brt_pickup_date_date_' . $widgetId,
					'class'			 => 'form-control datePickup border-radius')
					), true);
			?>
		</div>
		<div class="col-6 col-md-6 col-lg-6 col-xl-2 mb-2">
			<label for="iconLeft">Time of departure</label>
			<?php
			$this->widget('ext.timepicker.TimePicker', array(
				'model'			 => $model,
				'id'			 => 'brt_pickup_date_time_' . $widgetId,
				'attribute'		 => 'brt_pickup_date_time',
				'options'		 => ['widgetOptions' => array('options' => array())],
				'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control border-radius timePickup text text-info col-xs-12')
			));
			?>
		</div>
		<div class="col-12 col-lg-6 offset-lg-3 mb20">
			<div class="row">
				<div class="col-2 col-lg-2 roundimage hide"><div class="round-2"><img src="/images/img-2022/sonia.jpg" alt="Sonia" title="Sonia"></div></div>
                <div class="col-10 col-lg-10 mt5 d-lg-none d-xl-none"><span class="cabcontent"><p class="typing"></p><div class="hiders"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p></div></span></div>
				<div class="col-10 col-lg-10 mt-1 cabcontent d-none d-lg-block"><p class="typing"></p><div class="hiders"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p></div></div>
			</div>
		</div>
	</div>
<div class="row">
<div class="col-12">
	<div class="row m0 justify-center cc-2 pb10">
		<div class="col-12 text-center">
<!--            <input type="hidden" name="cookieDate" id="cookieDate" value="<?php echo $dateVar[0];?>">-->
			<input type="button" value="Go back" rid="<?= $rid ?>"  step="<?= $pageid ?>" name="yt0" class="btn btn-light backButton">
			<?= CHtml::submitButton('Next', array('class' => 'btn btn-primary pl-5 pr-5', 'id' => 'onewaybtn')); ?>
		</div>
	</div>
</div>
</div>
<!--<div class="row mb30">
<div class="col-12 text-center"><a href="http://www.aaocab.com/terms/doubleback" target="_blank"><img src="/images/double-back-banner.webp?v=0.3" alt="Img" class="img-fluid"></a></div>
</div>-->
</div>
<script type="text/javascript">
	$(document).ready(function()
	{
        var date1 = new Date("<?php echo Filter::getDBDateTime(); ?>");
        var date2 = new Date("<?php echo $var->pickupTime; ?>");
        var diffDays = parseInt((date2 - date1) / (1000 * 60 * 60 * 24), 10); 

       
        if(diffDays>0)
        {
        $('input[name="BookingRoute[brt_pickup_date_date]"]').val('<?php echo $dateVar[0]; ?>');       
        }
        var tncval = JSON.parse('<?= $tncArr ?>');
      // $('.cabcontent').html(tncval[68]);
       $('.typing').html(tncval[68]);
        $('.roundimage').removeClass('hide');
	});


</script>