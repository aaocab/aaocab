<?php
//if (Yii::app()->request->cookies->contains('itineraryCookie'))
//{
//    $var                   = Yii::app()->request->cookies['itineraryCookie']->value;
//    $cookieSourceCity      = $var->source->city->id;
//    $cookieDestinationCity = $var->destination->city->id;
//}

?>

<div id="insertBefore">
	<div class="container search-panel-2 clsRoute">
		<input type="hidden" id="contenttype" value="68">
		<div class="row mt-2">
			<div class="col-12 text-center"><p class="merriw heading-line">Multi-day multi-city journey</p></div>
			<div class="col-12 col-md-6 col-lg-6 col-xl-4">
				<label for="iconLeft">Source city</label>
				<?php
				$widgetId			 = $ctr . "_" . random_int(99999, 10000000);
                    $this->widget('application.widgets.BRCities', array(
        'type'             => 1,
        'enable'           => ($index == 0),
        'widgetId'         => $widgetId,
        'model'            => $model,
        'attribute'        => 'brt_from_city_id',
        'isCookieActive'   => true,
        'cookieSource'     => $cookieSourceCity,
        'useWithBootstrap' => true,
       
        "placeholder"      => "Select City",
));
				?>

				<?//= CHtml::hiddenField('multiroutes[]', $multiroutes, array('id' => 'multiroutes')) ?>
			</div>
			<div class="col-12 col-md-6 col-lg-6 col-xl-4">
				<label for="iconLeft">Destination city</label>
				<?php
				$this->widget('application.widgets.BRCities', array(
                    'type'             => 2,
                    'widgetId'         => $widgetId,
                    'model'            => $model,
                    'attribute'        => 'brt_to_city_id',
                    'isCookieActive'   => true,
                    'cookieDestination'     => $cookieDestinationCity,
                    'useWithBootstrap' => true,
                   
                    "placeholder"      => "Select City",
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
						'value'			 => $model->brt_pickup_date_date, 'id'			 => 'brt_pickup_date_date_' . date('mdhis'),
						'class'			 => 'form-control datePickup border-radius')
						), true);
				?>
			</div>
			<div class="col-6 col-md-6 col-lg-6 col-xl-2">
				<label for="iconLeft">Time of departure</label>
				<?php
				$this->widget('ext.timepicker.TimePicker', array(
					'model'			 => $model,
					'id'			 => 'brt_pickup_date_time_' . date('mdhis'),
					'attribute'		 => 'brt_pickup_date_time',
					'options'		 => ['widgetOptions' => array('options' => array())],
					'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control border-radius timePickup text text-info col-xs-12')
				));
				$model->estArrTime	 = $estArrTime;
				?>
			</div>
			<input type="hidden" name="arivaltime" id="arivaltime"  value="<?php echo $model->estArrTime; ?>">
		</div>
	</div>
	<div class="col-12 text-center mt-2">
		<a href="Javascript:void(0)" class="btn btn-light mr-1 mb-1 addmoreField" onclick="$jsBooking.addRouteNew($('#bookingItinerary'));">ADD TO PLAN</a>
	</div>
	<div class="col-12 col-lg-6 offset-lg-3 mb20 cabcontentmulti">
			<div class="row">
				<div class="col-2 col-lg-2 roundimage hide"><div class="round-2"><img src="/images/img-2022/sonia.jpg" alt="Sonia" title="Sonia"></div></div>
				<div class="col-10 col-lg-10 mt5 d-lg-none d-xl-none"><span class="cabcontent"><p class="typing"></p><div class="hiders"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p></div></span></div>
				<div class="col-10 col-lg-10 mt-1 cabcontent d-none d-lg-block"><p class="typing"></p><div class="hiders"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p></div></div>
			</div>
	</div>
	<div class="insertmore">
		<?= $this->renderFile($this->getViewFile('showitinerary'), []) ?>
	</div>
</div>
<!--<div class="row">
<div class="col-12 text-center"><a href="http://www.aaocab.com/terms/doubleback" target="_blank"><img src="/images/double-back-banner.webp?v=0.3" alt="Img" class="img-fluid"></a></div>
</div>-->
<script type="text/javascript">
	$(document).ready(function()
	{
		<?php
			if($tncArr != '')
			{
		?>
		var tncval = JSON.parse('<?= $tncArr ?>');
		//$('.cabcontent').html(tncval[69]);
       $('.typing').html(tncval[69]);
		$('.roundimage').removeClass('hide');
			<?php }?>
	});
</script>
<style>
@keyframes typing {
    from { width: 100% }
    to { width: 0 }
    
}
.typing {
  top: 0;
  margin: 0;
  z-index: -1;
}


.hiders {
  margin: 0;
  position: absolute;
  top: 0;
  width: 100%;
}


.hiders p {
  position: relative;
  clear: both;
  margin: 0;
  float: right; /* makes animation go left-to-right */
  width:0; /* graceful degradation: if animation doesn't work, these are invisible by default */
  background: white; /* same as page background */
  animation: typing 2s steps(30, end);
  animation-fill-mode: both;  /* load first keyframe on page load, leave on last frame at end */
}
  
.hiders p:nth-child(2) {
  animation-delay: 2s;
}
.hiders p:nth-child(3) {
  animation-delay: 4s;
}
.hiders p:nth-child(4) {
  animation-delay: 6s;
}
.hiders p:nth-child(5) {
  animation-delay: 8s;
}
.hiders p:nth-child(6) {
  animation-delay: 10s;
}
.hiders p:nth-child(7) {
  animation-delay: 12s;
}
.hiders p:nth-child(8) {
  animation-delay: 14s;
}
.hiders p:nth-child(9) {
  animation-delay: 16s;
}
.hiders p:nth-child(10) {
  animation-delay: 18s;
}
</style>
