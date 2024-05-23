<?php
$waitTimeStr = Filter::getTimeDurationbyMinute($waitTime);
?>
<div class="  container1  ">
	<div class="panel panel-primary ">
		<div class="panel-body ">
			<div class="  mt10 mb20">
				<div class="col-xs-12  text-center  ">
					<span class="h5">We will call you back shortly  <i class="fa fa-hourglass-half text-warning"></i> </span><br>
					<span class="font-13 ">Queue no #<?php echo $queNo.' - '.$followupCode ?> | Calling in <?php echo $waitTimeStr; ?></span>
					<br>
					<button type="button" class=" float-right btn btn-primary mt10 hide"  onclick="refreshque()">Refresh Queue </button>	
					<span class="font-14"><span style="color: red;font-size: 15px;">*</span>All the calls will be recorded for training are quality assurance purposes. If your issues get resolved before our call,
						you can click the 'cancel' below.</span>
					<br>
					<?php
					if ($followupId > 0)
					{
						?>
						<a class="text-danger" type="button"  onclick="deactivateCMB(<?php echo $followupId; ?>)" >Cancel my call back</a>
					<?php }
					?>					 
				</div>	 	
			</div>	 
		</div>
	</div>
</div> 
<script>
    function refreshque() {

        $href = "<?= Yii::app()->createUrl('scq/refreshCMBQue') ?>";
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


    function deactivateCMB(followupId)
    {
        var answer = window.confirm("If you cancel your callback then you will lose your queue and go to last.")
        if (answer)
        {
            $href = "<?= Yii::app()->createUrl('scq/deactivatCallBack') ?>";
            jQuery.ajax({type: 'GET', url: $href, data: {"clbRef":followupId},
                success: function (data)
                {
                    $('.modal').modal('hide');
                }
            });

        }
    }
</script>
