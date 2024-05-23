<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/v3/city.js?v=$version");

/* @var $brtRoute BookingRoute */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$ctr			 = rand(0, 99) . date('mdhis');
$transferType	 = $model->bkg_transfer_type;

$pickup		 = "Where do you need an in-the-city ride?";
$fromCity	 = "From location";
$toCity		 = "To location";
$date		 = "pickup";

$brtRoute->brtFromCity	 = $brtRoute->brt_from_city_id;
//$brtRoute->getDestinationPlace();
$brtRoute->to_place		 = $brtRoute->to_place;

//$brtRoute->airport	 = $brtRoute->brt_to_city_id;
//$brtRoute->getSourcePlace();
$brtRoute->from_place	 = $brtRoute->from_place;
?>
<input type="hidden" name="ctr" value="<?= $ctr ?>">
<div class="container mt15 clsRoute">
	<div class="bg-white-box">
		<div class="row mb-5">
			<div class="col-12 text-center"><p class="merriw heading-line">Point to point (within-the-city)</p></div>
			<div class="col-12 col-md-6 col-lg-6 offset-lg-3">

				<label for="iconLeft"><?php echo $pickup; ?></label>
				<?php
				$options					 = [];
				$acWidgetId					 = CHtml::activeId($brtRoute, 'place') . "_" . rand(100000, 9999999);
				$brtRoute->brt_from_city_id	 = 30366;
				$acWidgetIdTo				 = CHtml::activeId($brtRoute, 'place') . "_" . rand(100000, 9999999);
				$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $brtRoute,
					'attribute'			 => 'brt_from_city_id',
					'useWithBootstrap'	 => true,
					
					'fullWidth'			 => true,
					'htmlOptions'		 => array(
										'placeholder'	 => 'Delhi',
										'width'			 => '100%',
										'style'			 => 'width:100%',
										'id'			 => 'brt_from_city_id',
'disabledField'=>'disabled',
									),
                  'selectedValues'     => $brtRoute->brt_from_city_id,
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
													populateCityList(this, '{$brtRoute->brt_from_city_id}');
												}",
				'load'			 => "js:function(query, callback){
                                     
													loadSource('{$brtRoute->brt_from_city_id}', callback);                
												}",


				'onChange'		 => "js:function(value) {
										setAddressCity('{$acWidgetId}',value);
                                        setAddressCity('{$acWidgetIdTo}',value);
											}",
				'render'		 => "js:{

														option: function(item, escape){

														return '<div><span class=\"\"><img src=\"/images/bxs-map.svg\" alt=\"img\" width=\"30\" height=\"30\" class=\"p5 mr5\">' + escape(item.text) +'</span></div>';
														},
														option_create: function(data, escape){
														return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
													   }
													}",
					),
				));
				?>
				<span class="has-error"><?php echo $form->error($brtRoute, 'airport'); ?></span>

				<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
			</div>
			<div class="col-12 col-md-6 col-lg-6 offset-lg-3 mb-1">
				<label for="iconLeft"><?php echo $fromCity; ?></label>
				<?php
				$this->widget('application.widgets.SelectAddress', array(
					'model'			 => $brtRoute,
					"htmlOptions"	 => ["class" => "border border-light rounded p10 text-left selectAddress item", "id" => $acWidgetId]+['platform'=>0],
					'attribute'		 => "from_place",
					'widgetId'		 => $acWidgetId,
					"city"			 => "{$brtRoute->brt_from_city_id}",
					"modalId"		 => "myAddressModal"
				));
				?>
				<span class="has-error"><?php echo $form->error($brtRoute, 'place'); ?></span>
			</div>
     
			<div class="col-12 col-md-6 col-lg-6 offset-lg-3 mb-1">
				<label for="iconLeft"><?php echo $toCity; ?></label>
				<?php


				$this->widget('application.widgets.SelectAddress', array(
					'model'			 => $brtRoute,
					"htmlOptions"	 => ["class" => "border border-light rounded p10 text-left selectAddress item", "id" => $acWidgetIdTo]+['platform'=>0],
					'attribute'		 => "to_place",
					'widgetId'		 => $acWidgetIdTo,
			       
					"city"			 => "{$brtRoute->brt_from_city_id}",
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
				$fifteenMin						 = 15 * 30;
				$timeStr						 = (ceil(strtotime($dbDate . '+15 minute') / $fifteenMin)) * $fifteenMin;
				$defaultDate					 = date('Y-m-d H:i:s', $timeStr);
				$brtRoute->brt_pickup_date_time	 = DateTimeFormat::DateTimeToTimePicker($defaultDate);

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
			<div class="col-12 col-lg-6 offset-lg-3 mb0 mt-2">
				<div class="row">
					<div class="col-2 col-lg-2 roundimage hide"><div class="round-2"><img src="/images/img-2022/sonia.jpg" alt="Sonia" title="Sonia"></div></div>
					<div class="col-10 col-lg-10 d-lg-none d-xl-none"><span class="cabcontent90"></span></div>
					<div class="col-10 col-lg-10 cabcontent90 d-none d-lg-block"></div>
				</div>
			</div>
<!--</div>-->
		</div>

	</div>
	<div class="row m0 cc-2">
		<div class="col-12 text-center pb10">
        <?php echo $form->hiddenField($bmodel, 'bkg_is_gozonow',['value' => 1]);?>
			<input type="button" value="Go back" rid="<?= $rid ?>"  step="<?= $pageid ?>" name="yt0" class="btn btn-light backButton">
			<?= CHtml::submitButton('NEXT', array('class' => 'btn btn-primary pl-5 pr-5', 'id' => 'btnAirTransfer')); ?>
		</div>
	</div> 
</div>

<script>

	$(document).ready(function()
	{

		var tncval = JSON.parse('<?= $tncArr ?>');
		$('.cabcontent90').html(tncval[93]);
		$('.roundimage').removeClass('hide');
	});
</script>
