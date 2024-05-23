<style>
	.time-widget-1 { 
		color: #727E8C;
	}
</style>

<div class="row">
	<div class="col-12 text-center">
		<p><span class="h4">
				Looking for available cabs <br></span>
			<span class="h7">
				<p><?php echo $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . ' (' . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . $vhcModel . ')'; ?> to travel <?php echo $model->bkg_trip_distance; ?> km<br>
					from <?php echo $model->bkg_pickup_address; ?> 
					<br>to <?php echo $model->bkgToCity->cty_name; ?> <br>on <?php echo date('F j, Y', strtotime($model->bkg_pickup_date)); ?> at <?php echo date('h:i A', strtotime($model->bkg_pickup_date)); ?></p></span></p>

		<p class="h4 weight600 ">Booking ID: 
			<?php echo Filter::formatBookingId($model->bkg_booking_id); ?></p>
		<p class="hide" >Trip   ID: <?php echo $model->bkg_bcb_id; ?></p> 
	</div>
	<div class="col-12 text-center">

		<p class="font-18 weight600 mb-0 bidCount " style="display: none"><span class="count1">0</span> cab(s) found so far</p>

		<p id="quoteFound" style="display: none">The offered quotes expire in</p>
		<p id="quoteDue" style="display: none">Looking for offers for you</p>
		<?
		if ($timerShow && $step1DiffSecs > 0)
		{ 
			?>
			<div class="svg-item">
				<svg width="100%" height="100%" viewBox="0 0 40 40" class="donut">
				<circle class="donut-hole" cx="20" cy="20" r="15.91549430918954" fill="#fff"></circle>
				<circle class="donut-ring" cx="20" cy="20" r="15.91549430918954" fill="transparent" stroke-width="3.5"></circle>
				<circle class="donut-segment donut-segment-2"
						style="animation:donut1 <?= $step1DiffSecs ?>s" cx="20" cy="20" r="15.91549430918954"		
						fill="transparent" stroke-width="3.5" 
						stroke-dasharray="200 0" stroke-dashoffset="25"></circle>
				<g class="donut-text donut-text-1 weight600 ">

				<text y="50%" transform="translate(0, 2)">
				<tspan x="50%" text-anchor="middle" class="donut-percent" id="bidStep1"><?= $step1DiffSecs ?></tspan>   
				</text>
				<text y="60%" transform="translate(0, 2)">
				<tspan x="50%" text-anchor="middle" class="donut-data">seconds</tspan>   
				</text>
				</g>
				</svg>
				<p class="font-14 weight600 text-primary" id="drvTxt">Drivers may take upto 5 minutes to respond. </p>

			</div>
			<p class="font-14 weight600 text-primary noVnd"  style="display: none">Very few suppliers are responding right now.
				<br>Our team may be able to assist better in arranging a cab for this request.
				<br>You may request a call back by tapping the button below</p>
			<div class="col-12 text-center  container  noVnd" id="cbrbtn1" style="display: none"> 
				<a type="button" class="btn btn-primary  "  onclick="reqCMB(1)"> Request a call back</a> 
			</div>

			<div class="mb-3 hide"><span id="time" class="time-widget-1">00:00</span></div>
		<? } ?>
		<div class="spinner-border text-success mt10" role="status" id="smallSpinner" style="display: none" >
			<span class="sr-only">Loading...</span>
		</div>
	</div>
</div>
<script type="text/javascript">
	function startTimer1(duration) {

		var display1 = $('#bidStep1');
		var timer1 = duration;
		$bidTimer1 = setInterval(function () {
			if (--timer1 <= 0) {
				clearInterval($bidTimer1);
				startTimer1(600);
				$('#drvTxt').text("Giving it 5 minutes more, Looking for offers for you.");
//				$('.donut-segment').attr('style',"animation:donut 300s");
//				$('.donut-segment').attr('style',"animation:donut1 300s");
//				$('.svg-item').remove();
//				$('#quoteFound').remove();
//				$('#quoteFound').remove();
//				$('#smallSpinner').show();
			}
			display1.text(Math.floor(timer1)); //days + "d " + hours + "h "
		}, 1000);

	}
	function startTimer2(duration) {
		var display2 = $('#time');
		var timer2 = duration, minutes, seconds;
		$bidTimer2 = setInterval(function () {
			minutes = parseInt(timer2 / 60, 10);
			seconds = parseInt(timer2 % 60, 10);

			minutes = minutes < 10 ? "0" + minutes : minutes;
			seconds = seconds < 10 ? "0" + seconds : seconds;

			display2.text(minutes + ":" + seconds);

			if (--timer2 < 0) {
				clearInterval($bidTimer2);
//				if ($("#bdcnts").text() == 0 || $("#list1").text() != '')
//					clearInterval($bidStartTimer);
			}
		}, 1000);
	}

</script>