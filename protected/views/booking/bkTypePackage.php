<?php
$minDate		 = date('Y-m-d H:i:s ', strtotime('+4 hour'));
?>
<?= $form->hiddenField($model, 'bkg_pickup_date_time', ['value' =>  $model->bkg_pickup_date_time ]); ?>  
<div class="row">
	<div class="col-xs-6 col-lg-4">
		<label>Starting Date</label>
		<?=
		$form->datePickerGroup($model, 'bkg_pickup_date_date', array('label'			 => '',
			'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
					'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
					'value'			 => $pdate, 'id'			 => 'BookingTemp_bkg_pickup_date_date5',
					'class'			 => 'border-radius ')), 'groupOptions'	 => ['class' => 'm0'], 'prepend'		 => '<i class="fa fa-calendar"></i>'));
		?>
	</div>
	<div class="col-xs-6 col-lg-4">
		<label>Default Time</label>
		<div class="form-control"><?= $model->bkg_pickup_date_time ?></div>
	</div>
</div>
<div class="h4">Package Route Info</div>
<div class="table">
	<table class="table-bordered table-responsive" border="1"  width="100%" id="packagetb">
		<tr>
			<th>From</th>
			<th>To</th>						 
			<th>Pickup Date</th>
			<th>Distance (in Km)</th>
			<th>Duration (in Min)</th>
			<th>No of Days</th>
			<th>No of Nights</th>
		</tr>
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

				<tr class="packagerow" >
					<td id="fcitycreate_<?= $key ?>"><b><?= $value->pickup_city_name ?></b></td>
					<td id="tcitycreate_<?= $key ?>"><b><?= $value->drop_city_name ?> </b></td>

					<td id="fdatecreate_<?= $key ?>"><?= DateTimeFormat::DateTimeToDatePicker($value->date) ?></td>
					<td id="fdistcreate_<?= $key ?>"><?= number_format($value->distance); ?></td>
					<td id="fduracreate_<?= $key ?>"><?= number_format($value->duration); ?></td>
					<td id="noOfDayCount_<?= $key ?>"><? echo $value->daycount; ?> </td>
					<td id="noOfNightCount_<?= $key ?>"><? echo $value->nightcount; ?> </td>
				</tr>
				<?
				$last_date = date('Y-m-d H:i:s', strtotime($value->date . '+ ' . $value->duration . ' minute'));
			}
		}
		?> 
	</table>
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