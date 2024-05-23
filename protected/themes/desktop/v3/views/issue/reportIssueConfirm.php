<?php 
if($isIssue == 1)
{
    $data = json_decode($returnSet, true);
    $message = ($data['success'] && $eventType == 301)?"Issue reported successfully for ".$data['message']." You will receive a call back shortly.":$data['message'];
}
else
{
    $message = "Issue reported successfully. You will receive a call back shortly.";
}
?>
<div class="container1  success-issue">
	<div class="panel panel-primary ">
		<div class="panel-body ">
			<div class="  mt10 mb20">
				<div class="col-xs-12  text-center  ">
					<span class="color-green2 font-18"><?php echo $message; ?></span><br>
<!--				<span class="font-13">We will call back as soon as possible</span>-->
				</div>	 	
			</div>	 
		</div>
	</div>
</div> 
