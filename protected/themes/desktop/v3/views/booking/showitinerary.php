<?php
if ($bmodel == null)
{
	$bmodel = $this->pageRequest->booking->getLeadModel();
}
$ctr		 = 0;
$validate	 = true;
foreach ($bmodel->bookingRoutes as $key => $route)
{
	$ctr = ($ctr + 1);
	$rut .= Cities::getName($route->brt_from_city_id) . ' - ';

	if ($route->brt_from_city_id == 0 || $route->brt_from_city_id == '')
	{
		$validate = false;
	}

	if (count($bmodel->bookingRoutes) == $ctr)
	{
		$rut .= Cities::getName($route->brt_to_city_id);
	}

	if ($key == 0)
	{
		$sdateformat = DateTime::createFromFormat('Y-m-d H:i:s', $route->brt_pickup_datetime);
		$startdate	 = strtotime($sdateformat->format('Y-m-d'));
	}
	$countend = count($bmodel->bookingRoutes) - 1;
	if ($key == $countend)
	{
		$edateformat = DateTime::createFromFormat('Y-m-d H:i:s', $route->brt_pickup_datetime);
		$enddate	 = strtotime($edateformat->format('Y-m-d'));
		$datediff	 = $enddate - $startdate;
		$noofdays	 = (round($datediff / (60 * 60 * 24)) + 1);
	}
}
if (!$validate)
{
	return;
}
$countRoute = count($bmodel->bookingRoutes) - 1;

$day = ($noofdays > 1) ? 'days' : 'day';

$tncIds	 = TncPoints::getTncIdsByStep(5);
$tncArr	 = TncPoints::getTypeContent($tncIds);
?>
<div class="container p0 mt-1 showMoreItinerary">
	<div class="card">
		<div class="card-header p15 pb0">
			<h6 class="card-title">Your trip plan: <?php echo $noofdays . ' ' . $day; ?> (<?php echo $rut; ?>)</h6>
		</div>
<ul class="timeline mb-0">

		<div class="card-body p15 mt-n1">
			<?php
			BookingRoute::validateRoutes($bmodel->bookingRoutes, $bmodel->bkg_booking_type);
			foreach ($bmodel->bookingRoutes as $key => $route)
			{
				if (!$route->validate())
				{
					continue;
				}
				$lastToCityId	 = $route->brt_to_city_id;
				$lastToCityName	 = Cities::getName($lastToCityId);
				$firstDate		 = DateTime::createFromFormat('Y-m-d H:i:s', $bmodel->bookingRoutes[0]->brt_pickup_datetime);
				$fromcity		 = Cities::getName($route->brt_from_city_id);
				$tocity			 = Cities::getName($route->brt_to_city_id);
				$date			 = DateTime::createFromFormat('Y-m-d H:i:s', $route->brt_pickup_datetime);

				$interval = $firstDate->diff($date);
				?>
				
<li class="timeline-item timeline-icon-primary pb5 active timeline-item-animation">

<div class="row">
					<div class="col-11 col-md-5 col-xl-5 mb5 text-bold-500"> Day <?= $interval->format('%d') + 1 ?>: Going from <?php echo $fromcity; ?> to <?php echo $tocity; ?></div>
					<div class="col-11 col-md-6 col-xl-6">
						<div class="row">
							<div class="col-12 col-md-6 col-xl-6 mb5"><span class="weight600">Leaving at</span> <br>
								<span class="text-nowrap"><?= date('h:i a', strtotime($route->brt_pickup_datetime)) ?></span><span class="text-nowrap"> on <?php echo date('F d, Y', strtotime($date->format('Y-m-d'))); ?></span></div>
							<div class="col-12 col-md-6 col-xl-6"><span class="weight600">Expected to reach  by</span><br>
								<span class="text-nowrap"><?php echo date('h:i a', strtotime($route->arrival_time)); ?></span>
								<span class="text-nowrap"> on <?php echo date('F d, Y', strtotime($route->arrival_time)); ?></span>
							</div>
						</div>
					</div>
					<div class="col-1 p0 mt-1">
						<a href="javascript:void(0);" onclick="removeItinerary('<?php echo $key; ?>')" class="text-danger" title="Remove"><img src="/images/bx-message-square-x.svg" alt="img" width="18" height="18"></a>
</div>
</div>

				
				<?php
			}
			$bmodel->setRoutes($bmodel->bookingRoutes);
			echo CHtml::activeHiddenField($bmodel, "bkg_route_data");
			?>
</li>
		</div>

</ul>
	</div>
	<div class="col-12 col-lg-6 offset-lg-3 mb50">
			<div class="row">
				<div class="col-2 col-lg-2 roundimage hide"><div class="round-2"><img src="/images/img-2022/sonia.jpg" alt="Sonia" title="Sonia"></div></div>
				<div class="col-10 col-lg-10 mt5 d-lg-none d-xl-none"><span class="cabcontent"></span></div>
				<div class="col-10 col-lg-10 mt-1 cabcontent d-none d-lg-block"></div>
			</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function()
	{
		let lastCityId = '<?php echo $lastToCityId; ?>';
		let lastCityName = '<?php echo $lastToCityName; ?>';
		var pickCityName = $("SELECT.ctyPickup").find(":selected").text();
		var pickCityId = $("SELECT.ctyPickup").find(":selected").val();
		let cntRoute  = '<?php echo count($bmodel->bookingRoutes); ?>';
		if(cntRoute == 0)
		{			
			let selectize = $("SELECT.ctyPickup")[0].selectize;
			selectize.clearOptions();			
			$.ajax({
				url: '/lookup/citylist1?city=',
				dataType: 'json',				
				success: function (data1)
				{   
					selectize.addOption(data1);
					selectize.refreshOptions();		
				},
				error: function (e)
				{
					alert(e);
				}
			});
			let selectize2 = $("SELECT.ctyDrop")[0].selectize;
			selectize2.clearOptions();		
			$(".showMoreItinerary").hide();
		}
		if (lastCityId != pickCityId && cntRoute > 0)
		{
			$("SELECT.ctyPickup").find(":selected").text(lastCityName);
			$("SELECT.ctyPickup").find(":selected").val(lastCityId);
			$('.ctyPickup').children('div').children('div').attr("data-value", lastCityId);
			$('.ctyPickup').children('div').children('div').html(lastCityName);
		}
		$(".showMoreItinerary").closest("form").find("INPUT[name=rdata]").val("<?= $this->pageRequest->getEncrptedData() ?>");
		bindFocus();
		
		<?php
			if($tncArr != '')
			{
		?>
		var tncval = JSON.parse('<?= $tncArr ?>');
		$('.cabcontent').html(tncval[69]);
		$('.roundimage').removeClass('hide');
		$('.cabcontentmulti').addClass('hide');
		<?php }?>
	});

	function removeItinerary(routeIndex)
	{
		var rdata = $(".showMoreItinerary").closest("form").find("INPUT[name=rdata]").val();
		$.ajax({
			type: "POST",
			url: "/booking/removeItinerary",
			data: {'routeIndex': routeIndex, 'rdata': rdata, 'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val()},
			success: function(data1)
			{
				$('.insertmore').html(data1);
			},
			error: function(error)
			{
				console.log(error);
			}
		});
	}
</script>
