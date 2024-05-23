<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/v3/city.js?v=$version");

/* @var $brtRoute BookingRoute */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');

$ctr			 = rand(0, 99) . date('mdhis');
$transferType	 = $model->bkg_transfer_type;

if ($transfertype == 1)
{
	$heading			 = "Pickup from Railway Or Bus Terminal";
	$pickup				 = "Which Railway Or Bus Terminal are we pickup you up from";
	$toCity				 = "Going to location";
	$date				 = "departure";
	$brtRoute->railway	 = $brtRoute->brt_from_city_id;
	$brtRoute->getDestinationPlace();
	$brtRoute->place	 = $brtRoute->to_place;
}
else
{
	$heading			 = "Drop to Railway Or Bus Terminal";
	$pickup				 = "Which Railway Or Bus Terminal are we dropping you at?";
	$toCity				 = "Pickup location";
	$date				 = "pickup";
	$brtRoute->railway	 = $brtRoute->brt_to_city_id;
	$brtRoute->getSourcePlace();
	$brtRoute->place	 = $brtRoute->from_place;
}
?>
<input type="hidden" name="ctr" value="<?= $ctr ?>">
<div class="container mt15 clsRoute">
	<div class="bg-white-box">
		<div class="row mb-5">
			<div class="col-12 text-center"><p class="merriw heading-line"><?php echo $heading; ?></p></div>
			<div class="col-12 col-md-6 col-lg-6 offset-lg-3">
				<label for="iconLeft"><?php echo $pickup; ?></label>
				<?php
				$options			 = [];
				$acWidgetId			 = CHtml::activeId($brtRoute, 'place') . "_" . rand(100000, 9999999);
				$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $brtRoute,
					'attribute'			 => 'railway',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select Railway or Bus Terminal",
					'fullWidth'			 => true,
					'htmlOptions'		 => array('width' => '50%'
					),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
													populateRailwayList(this, '{$brtRoute->railway}');
												}",
				'load'			 => "js:function(query, callback){
													loadRailwaySource(query, callback);
												}",
				'onChange'		 => "js:function(value) {
										setAddressCity('{$acWidgetId}',value);
											}",
				'render'		 => "js:{
														option: function(item, escape){
														return '<div><span class=\"\"><img src=\"/images/bxs-map.svg\" alt=\"img\" width=\"14\" height=\"14\" class=\"p5 mr5\">' + escape(item.text) +'</span></div>';
														},
														option_create: function(data, escape){
														return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
													   }
													}",
					),
				));
				?>
				<span class="has-error"><?php echo $form->error($brtRoute, 'railway'); ?></span>

				<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
			</div>
			<div class="col-12 col-md-6 col-lg-6 offset-lg-3 mb-1">
				<label for="iconLeft"><?php echo $toCity; ?></label>
				<?php
				$this->widget('application.widgets.SelectAddress', array(
					'model'			 => $brtRoute,
					"htmlOptions"	 => ["class" => "border border-light rounded p10 text-left selectAddress item", "id" => $acWidgetId],
					'attribute'		 => "place",
					'widgetId'		 => $acWidgetId,
					'isRailway'		 => true,
					"city"			 => "{$brtRoute->railway}",
					"modalId"		 => "myAddressModal"
				));
				?>
				<span class="has-error"><?php echo $form->error($brtRoute, 'place'); ?></span>
			</div>
			<div class="col-6 col-md-6 col-lg-3 offset-lg-3">
				<label for="iconLeft">Date of <?php echo $date; ?></label>
				<?php
				$minDate			 = ($model->brt_min_date != '') ? $model->brt_min_date : date('Y-m-d');
				$formattedMinDate	 = DateTimeFormat::DateToDatePicker($minDate);
				echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'			 => $brtRoute,
					'attribute'		 => 'brt_pickup_date_date',
					'options'		 => array('autoclose' => true, 'dateFormat' => 'dd/mm/yy', 'minDate' => $formattedMinDate),
					'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date', 'value'			 => $brtRoute->brt_pickup_date_date,
						'id'			 => 'brt_pickup_date_date_' . date('mdhis'), 'min'			 => $brtRoute->brt_min_date, 'class'			 => 'form-control datePickup border-radius')
						), true);
				?>
				<span class="has-error"><?php echo $form->error($brtRoute, 'brt_pickup_date_date'); ?></span>
			</div>
			<div class="col-6 col-md-6 col-lg-3">
				<label for="iconLeft">Time of <?php echo $date; ?></label>
				<?php
				$this->widget('ext.timepicker.TimePicker', array(
					'model'			 => $brtRoute,
					'id'			 => 'brt_pickup_date_time_' . date('mdhis'),
					'attribute'		 => 'brt_pickup_date_time',
					'options'		 => ['widgetOptions' => array('options' => array())],
					'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control border-radius timePickup text text-info col-xs-12')
				));
				?>
				<span class="has-error"><?php echo $form->error($brtRoute, 'brt_pickup_date_time'); ?></span>
			</div>

		</div>

	</div>
	<div class="row m0 cc-2">
		<div class="col-12 text-center">
			<input type="button" value="Go back" rid="<?= $rid ?>"  step="<?= $pageid ?>" name="yt0" class="btn btn-light backButton">
			<?= CHtml::submitButton('NEXT', array('class' => 'btn btn-primary pl-5 pr-5', 'id' => 'btnAirTransfer')); ?>
		</div>
		<div class="col-12 col-xl-8 offset-xl-2 mt-1 mb5">
			<div class="row">
				<div class="col-2 col-lg-2 roundimage hide"><div class="round-2"><img src="/images/img-2022/sonia.jpg" alt="Sonia" title="Sonia"></div></div>
				<div class="col-10 col-lg-10 d-lg-none d-xl-none"><marquee class="cabcontent" direction="up" height="50px" scrollamount="1"></marquee></div>
				<div class="col-10 col-lg-10 cabcontent d-none d-lg-block"></div>
			</div>
		</div>
	</div>
</div>

<script>

	$(document).ready(function()
	{	
		var val = '<?= $transfertype ?>';
		if (val == 1)
		{
			var defaultVal = 94;
		}
		else
		{
			var defaultVal = 95;
		}
		var tncval = JSON.parse('<?= $tncArr ?>');
		$('.cabcontent').html(tncval[defaultVal]);
		$('.roundimage').removeClass('hide');
	});
</script>