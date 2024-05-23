<div class="row">
	<div class="col-12 text-center">
		<p><span class="h7">
				From  <?php echo $model->bkg_pickup_address; ?> <br>to <?php echo $model->bkgToCity->cty_name; ?> 
				<br>on <?php echo date('F j, Y', strtotime($model->bkg_pickup_date)); ?> 
				at <?php echo date('h:i A', strtotime($model->bkg_pickup_date)); ?></span></p>

	</div>


	<div class="col-12 text-center  container">
		<p class="h6 text-danger expireBooking" id="errorText"><br></p>
	</div>
	<div class="col-12 text-center  container  expireBooking" id="cbrbtn"> 
		<a type="button" class="btn btn-primary  "  onclick="reqCMB(1)"> Request a call back</a> 
	</div>
	<div class="col-12   text-center h4 container" id="noCabFound">
		We have not found a cab to serve you yet
	</div>
</div>
<div class="row btnDiv">
	<div class="col-xl-12  text-center  mt10">
		<button type="button" class="btn btn-primary pl-5 pr-5 text-uppercase btnnxt" id="timerresetbtn">Continue to look</button>
	</div>
</div>
<script type="text/javascript">
	$("#timerresetbtn").click(function () {
		resetBidTimer();
	});
</script>