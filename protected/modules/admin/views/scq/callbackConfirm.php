<?php
$waitTimeStr = Filter::getTimeDurationbyMinute($waitTime);
?>
<div class="  container1  ">
	<div class="panel panel-primary ">
		<div class="panel-body mt20">
			<div class="  mt10 mb20">
				<div class="col-xs-12  text-center  ">
					<span class="h5">Service request has been created  <i class="fa fa-hourglass-half text-warning"></i> </span><br>
					<span class="font-13 ">Queue no #<?php echo $queNo . ' - ' . $followupCode ?> | Save it for future reference</span>
					<br>
					<button type="button" class=" float-right btn btn-primary mt10 hide"  onclick="refreshque()">Refresh Queue </button>	


				</div>	 	
			</div>	 
		</div>
	</div>
</div> 

