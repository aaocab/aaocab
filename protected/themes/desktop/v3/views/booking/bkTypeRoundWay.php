<?php
$minDate          = ($model->brt_min_date != '') ? $model->brt_min_date : date('Y-m-d');
$formattedMinDate = DateTimeFormat::DateToDatePicker($minDate);
//if (Yii::app()->request->cookies->contains('itineraryCookie'))
//{
//    $var                   = Yii::app()->request->cookies['itineraryCookie']->value;
//    $cookieSourceCity      = $var->source->city->id;
//    $cookieDestinationCity = $var->destination->city->id;
//}
?>

<div class="row">
	<div class="container search-panel-2 clsRoute">
		<input type="hidden" id="contenttype" value="70">
		<div class="row mb-2">
			<div class="col-12 text-center"><p class="merriw heading-line">Round trip journey</p></div>
			<div class="col-12 col-md-6 col-lg-6 col-xl-4">
				<label for="iconLeft">Source city</label>
				<?php
				$widgetId			 = $ctr . "_" . random_int(99999, 10000000);
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
				?>
			</div>
		</div>
		<div class="row mb-2 insertBeforeRound">

			<div class="col-12 col-md-6 col-lg-6 col-xl-6">
				<label for="iconLeft" class="returncab">Returning the cab to</label>
				<fieldset class="form-group position-relative">
					<input type="text" class="form-control returncab" id="iconLeft" placeholder="Original City of Deparature">

                </fieldset>

			</div>
			<div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-1">
				<label for="iconLeft">Journey end date</label>
				<?php
				echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'			 => $model,
					'attribute'		 => 'brt_return_date_date',
					'options'		 => array('autoclose' => true, 'dateFormat' => 'dd/mm/yy', 'minDate' => $formattedMinDate),
					'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Return Date',
						'value'			 => $model->brt_return_date_date, 'id'			 => 'brt_return_date_date_' . date('mdhis'),
						'class'			 => 'form-control dateReturn border-radius')
						), true);
				?>

			</div>
			<div class="col-6 col-md-6 col-lg-3 col-xl-3">
				<label for="iconLeft">Journey end time</label>
				<?php
				$this->widget('ext.timepicker.TimePicker', array(
					'model'			 => $model,
					'id'			 => 'brt_return_date_time' . date('mdhis'),
					'attribute'		 => 'brt_return_date_time',
					'options'		 => ['widgetOptions' => array('options' => array())],
					'htmlOptions'	 => array('required' => true, 'placeholder' => 'Return Time', 'class' => 'form-control border-radius timeReturn text text-info col-xs-12')
				));
				?>

			</div>
			<div class="col-12 col-lg-6 offset-lg-3 mb50">
				<div class="row">
					<div class="col-2 col-lg-2 roundimage hide"><div class="round-2"><img src="/images/img-2022/sonia.jpg" alt="Sonia" title="Sonia"></div></div>
					<div class="col-10 col-lg-10 d-lg-none d-xl-none"><span class="cabcontent"><p class="typing"></p><div class="hiders"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p></div></span></div>
					<div class="col-10 col-lg-10 cabcontent d-none d-lg-block"><p class="typing"></p><div class="hiders"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p></div></div>
				</div>
			</div>
		</div>

		<div class="row justify-center">
			<div class="col-12 cc-2">
				<div class="row">
					<div class="col-12 text-center mb10">
						<input type="button" value="Go back" rid="<?= $rid ?>"  step="<?= $pageid ?>" name="yt0" class="btn btn-light backButton">
						<?= CHtml::submitButton('NEXT', array('class' => 'btn btn-primary pl-5 pr-5', 'id' => 'roundwaybtn')); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-xl-8 offset-xl-2">

					</div>
				</div>
			</div>
		</div>
<!--		<div class="row">
<div class="col-12 text-center"><a href="https://www.gozocabs.com/terms/doubleback" target="_blank"><img src="/images/double-back-banner.webp?v=0.3" alt="Img" class="img-fluid"></a></div>
</div>-->
		
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function()
	{
		let $dt1 = $(".datePickup").datepicker({
			minDate: 0,
			dateFormat: "dd/mm/yy",
			// attach handler
			onSelect: function(dateString, instance)
			{
				let date = $dt1.datepicker('getDate');
				date.setDate(date.getDate());
				$dt2.datepicker('option', 'minDate', date);
			}
		});

		// cache the reference of input element
		var $dt2 = $(".dateReturn").datepicker({
			dateFormat: "dd/mm/yy"
		});

		departDate = $(".datePickup").datepicker("getDate");
		$(".dateReturn").datepicker();

		var tncval = JSON.parse('<?= $tncArr ?>');
		//$('.cabcontent').html(tncval[70]);
         $('.typing').html(tncval[70]);
		$('.roundimage').removeClass('hide');
	});

	$("SELECT.ctyPickup").change(function()
	{

		var dropCityId = $(this).val();
		var pickCityName = $("SELECT.ctyPickup").find(":selected").text();
		$('.returncab').val(pickCityName);
		$('.returncab').attr('readonly','true');

	});

	$('#roundwaybtn').click(function()
	{
		$("#error_div").html("");
		$("#error_div").hide();
		$('#bktyp1').val(1);
		var currFromCtyId = $('SELECT.ctyPickup').val();
		var currToCtyId = $('SELECT.ctyDrop').val();
		if (currFromCtyId == '' || currToCtyId == '')
		{
			$("#error_div").html("Please select Source/destintion city");
			$("#error_div").show();
			return false;

		}
		if ($(".datePickup").val() == '' || $(".timePickup").val() == '')
		{
			$("#error_div").html("Please select pickup date/time");
			$("#error_div").show();
			return false;
		}
		if ($(".dateReturn").val() == '' || $(".timeReturn").val() == '')
		{
			$("#error_div").html("Please select return date/time");
			$("#error_div").show();
			return false;
		}
		return true;
	});
</script>