<style>
	.packagerow:nth-child(odd) {background: #f5fdff;border-left: solid 3px #4a89dc!important}
	.packagerow:nth-child(even) {border-left: solid 3px #e96a33!important;}
</style>
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?php
$minDate		 = date('Y-m-d H:i:s ', strtotime('+4 hour'));
?>
<?= $form->hiddenField($model, 'bkg_pickup_date_time', ['value' =>  $model->bkg_pickup_date_time ]); ?>  
<div class="content-boxed-widget">
	 <div class="one-half input-simple-1 has-icon input-blue bottom-30">		
        <em class="color-highlight">Starting Date</em>	
        <i class = "fa fa-calendar pr10 font-16 tx-gra-green"></i>
	    <?php
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'name'=>'BookingTemp[bkg_pickup_date_date]',
				'value'	=> $model->bkg_pickup_date_date,				
				'options'=>array('showAnim'=>'slide','autoclose' => true, 'startDate' => $minDate, 'dateFormat' => 'dd/mm/yy','minDate'=> 0,'maxDate'=>"+6M"),   
				'htmlOptions'	 => array('required' => true, 'placeholder'	 => 'Pickup Date','readonly'=>'readonly',								
				'class'	=> 'border-radius font-16 datePickup','id'=> 'BookingTemp_bkg_pickup_date_date5','style'=>'z-index:100 !important')	
			));
		?>
	</div>
	<div class="one-half last-column input-simple-1 has-icon input-blue bottom-30">
        <em class="color-highlight">Start Time</em>
		<div class="form-control"><?= $model->bkg_pickup_date_time ?></div>
	</div>
	<div class="clear"></div>
</div>
<div class="content-boxed-widget h4 font-16"><b>Package Route Info</b></div>
<div id="packagetb">
		
		<?
		$diffdays		 = 0;
		$nightCount		 = 0;
		if ($model->preData != '')
		{
			$arrmulticitydata = $model->preData;
			foreach ($arrmulticitydata as $key => $value)
			{
				$nightCount = $nightCount + $value->nightcount;

				if ($key == 0)
				{
					$diffdays = 1;
				}
				else
				{
					$date1		 = new DateTime(date('Y-m-d', strtotime($arrmulticitydata[0]->date)));
					$date2		 = new DateTime(date('Y-m-d', strtotime($value->date)));
					$difference	 = $date1->diff($date2);
					$diffdays	 = ($difference->d + 1);
				}
				?>

				<div class="content-boxed-widget packagerow">
					<h5 class="font-16 text-uppercase" style="color: #367f95!important;"><b>Itinerary part - <?= $key + 1 ?></b></h5>
					<div class="one-half color-gray-dark">From</div>
					<div class="one-half last-column" id="fcitycreate_<?= $key ?>"><b><?= $value->pickup_city_name ?></b></div>
					<div class="one-half color-gray-dark">To</div>
					<div class="one-half last-column" id="tcitycreate_<?= $key ?>"><b><?= $value->drop_city_name ?> </b></div>
					<div class="one-half color-gray-dark">Pickup Date</div>
					<div class="one-half last-column" id="fdatecreate_<?= $key ?>"><?= DateTimeFormat::DateTimeToDatePicker($value->date) ?></div>
					<div class="one-half color-gray-dark">Distance (in Km)</div>
					<div class="one-half last-column" id="fdistcreate_<?= $key ?>"><?= number_format($value->distance); ?></div>
					<div class="one-half color-gray-dark">Duration (in Min)</div>
					<div class="one-half last-column" id="fduracreate_<?= $key ?>"><?= number_format($value->duration); ?></div>
					<div class="one-half color-gray-dark">No of Days</div>
					<div class="one-half last-column" id="noOfDayCount_<?= $key ?>"><? echo $value->daycount; ?></div>
					<div class="one-half color-gray-dark">No of Nights</div>
					<div class="one-half last-column" id="noOfNightCount_<?= $key ?>"><? echo $value->nightcount; ?></div>
					<div class="clear"></div>
				</div>
				<?
				$last_date = date('Y-m-d H:i:s', strtotime($value->date . '+ ' . $value->duration . ' minute'));
			}
		}
		?>
</div>
<script>
	$jsBookNow.disableTab('TripType');
	$("#BookingTemp_bkg_pickup_date_date5").change(function ()
	{
		assignPackageDt();
	});
	function assignPackageDt()
	{
		var date = $('#BookingTemp_bkg_pickup_date_date5').val();
		var pckageID = $("#bkg_package_id1").val();

		$href = '<?= Yii::app()->createUrl('booking/getPackageDetail') ?>';
		jQuery.ajax({
			type: 'GET',
			url: $href,
			dataType: 'json',
			data: {"pckageID": pckageID, "pickupDt": date},
			success: function (data)
			{
				var packageDel = data.multijsondata;
				$.each(packageDel, function (key, value)
				{
					$('#fdatecreate_' + key).html(value.pickup_date);
				});
			}
		});
	}

</script>