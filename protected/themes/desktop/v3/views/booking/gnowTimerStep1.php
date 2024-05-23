 	 
<div class="row">
	<div class="col-12 text-center">
		<p><span class="h4">
				Looking for available cabs <br></span>
			<span class="h7">
				from  <?php echo $model->bkg_pickup_address; ?> <br>to <?php echo $model->bkgToCity->cty_name; ?> 
				<br>on <?php echo date('F j, Y', strtotime($model->bkg_pickup_date)); ?> 
				at <?php echo date('h:i A', strtotime($model->bkg_pickup_date)); ?></span></p>

	</div>
	<?
	if ($timerShow)
	{
		?>
		<div class="col-12 text-center animated-chart"> 
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
			</div>
			<p id="minMoreText" class="weight600 ">Just <3 minutes</p>
		</div>
	<? } ?>
	<p class="h4 weight600 ">Booking ID: 
		<?php echo Filter::formatBookingId($model->bkg_booking_id); ?></p>
	<p>Trip   ID: <?php echo $model->bkg_bcb_id; ?></p>
	<p class="mt-0 color-green bidCount" style="display: none;">
		<span class="count1">0</span> cab(s) found so far
	</p>

</div> 


<script type="text/javascript">
	function startTimer11(duration) {
		
		var display1 = $('#bidStep1');
		var timer1 = duration;
		$bidTimer1 = setInterval(function () {
			if (--timer1 <= 0) {
				clearInterval($bidTimer1);
			}
			display1.text(Math.floor(timer1)); //days + "d " + hours + "h "
		}, 1000);
	}
</script>