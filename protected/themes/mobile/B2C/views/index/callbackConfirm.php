<?php
$waitTimeStr = Filter::getTimeDurationbyMinute($waitTime);
?>
<div class="content-boxed-widget text-center">
	<span class="font-18"><b>We will call you back shortly</b></span><br>
	<span class="font-11 ">Call back request number is <?php echo $queNo.' - '.$followupCode ?> <br> Calling in <?php echo $waitTimeStr; ?></span>

	<div class="content p0 bottom-0 mb20 text-center hide">
		<div class="Submit-button">
			<button type="button" class = "uppercase btn-orange shadow-medium" onclick = "refreshque()" >Refresh Queue</button>
		</div>
	</div> 
	<p> <span style="color: red;font-size: 15px;">*</span>All the calls will be recorded for training are quality assurance purposes. If your issues get resolved before our call,
		you can click the 'cancel' below.</p>
	<?php
	if ($followupId > 0)
	{

		?><span id="showAssignedMsg" class="text-center text-danger font-11"></span>
		<a class="btn-red" id="cbtn" href="javascript:void(0);" onclick = "deactivatCallBack(<?php echo $followupId; ?>)">Cancel my call back</a>
	<?php } ?>
	<input type="hidden" name="flwup" id="flwup" value="<?php echo $followupId; ?>">
	<div class="content p0 bottom-0 mb20 text-center hide">
		<div class="Submit-button">
			<button type="button" class = "uppercase btn-orange shadow-medium" aria-label="Close" >Close</button>
		</div>
	</div>
</div>

<script>
	function refreshque() {

		$href = "<?= Yii::app()->createUrl('index/refreshCMBQue') ?>";
		jQuery.ajax({type: 'GET',
			"url": $href,
			data: {},
			"dataType": "json",
			success: function (data1)
			{

				var queNo = data1.queNo;
				$('#queNum').text(queNo);
			}
		});
	}

	function deactivatCallBack(clbRef) {
		$href = "<?= Yii::app()->createUrl('index/deactivatCallBack') ?>";
		jQuery.ajax({type: 'GET',
			"url": $href,
			data: {'ismobile': 1, 'fwpId': clbRef},
			"dataType": "json",
			success: function (data1)
			{
				
				if (data1.status == 0) {
					window.location = "<?= Yii::app()->getBaseUrl(true) ?>";
				}
			}
		});
	}
</script>
