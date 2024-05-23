<style>
	.table-flex {
		display: flex;
		flex-direction: column;
	}
	.tr-flex {
		display: flex;
	}
	.th-flex, .td-flex{
		flex-basis: 35%;
	}
	.thead-flex, .tbody-flex {
		overflow-y: scroll;
	}
	.tbody-flex {
		max-height: 250px;
	}
	.sticky {
		position: fixed;
		top: 115px;
		width: 100%;
		z-index: 9999;
		height: 124px;
	}
	.sticky + .content-1 {
		padding-top: 102px;
	}
	.height-1{
		height: 114px;
	}
</style>
<div class="row">
	<div class="col-xs-12  pb10">
		<?php
		$fromdate	 = date('Y-m-d', strtotime(str_replace('-', '-', $booksub->from_date)));
		$todate		 = date('Y-m-d', strtotime(str_replace('-', '-', $booksub->to_date)));
		?>
		<a href="/report/scq/cbrdetailsreport/?fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>" target="_blank"> Click To View  CBR's Details Report</a> &nbsp;&nbsp;&nbsp;&nbsp;<a href="/report/scq/serviceRequests" target="_blank"> Click To View Service Request Report</a> 
		<br>
		<a href="/report/scq/cbrStaticalDetailsData?fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>" target="_blank" > Click To View CBR Statistical Data Report</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href="/report/scq/cbrStaticalCloseData?date=<?php echo $fromdate; ?>" target="_blank" > Click To View CBR Close Statistical Data Report</a> &nbsp;&nbsp;&nbsp;&nbsp; <a target="_blank" href="/report/scq/teamStaticalData/">Click To View Team Report</a> 
	</div>
</div>
<div class="row"> 
	<?php
	$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'booking-form', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class' => '',
		),
	));
	/* @var $form TbActiveForm */
	$minDate	 = date('Y-m-d H:i:s', strtotime('-30 days'));
	?>
	<div class="col-xs-12 col-sm-4 col-md-3">
		<?=
		$form->datePickerGroup($booksub, 'date', array('label'			 => 'Select Date',
			'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => '01/01/2021', 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Select Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
		?>  
	</div>
	<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
		<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
	<?php $this->endWidget(); ?>
</div>
<div class="h5 mt0">Format : ALL (Web|Admin|IVR)</div>
<table class="table table-bordered">
	<thead>
		<tr style="color: black;background: whitesmoke" class="header" id="myHeader">
			<th class="text-center height-1" width="36%"><u>Queue<br/>(Currently Serving CBR's )</u></th>
			<th class="text-center" width="8%" ><u>Total CBR's<br/>(Created Today)</u></th>
			<th class="text-center" width="8%" ><u>CBR's Active<br/>(All)</u></th>
			<th class="text-center" width="8%" ><u>CBR's Active<br/>(Created Before Today)</u></th>
			<th class="text-center" width="8%" ><u>CBRs Active<br/>(Created Today)</u></th>
			<th class="text-center" width="8%" ><u>Assignable Now</u></th>
			<th class="text-center" width="8%" ><u>CSR's Online <br/>(Assigned Now)</u></th>
			<th class="text-center" width="8%" ><u>CBR's Closed<br/>(Today)</u></th>
			<th class="text-center" width="8%" ><u>Total CSR's<br/>(Today)</u></th>

		</tr>

	</thead>
	<tbody>
		<?php
		$count		 = 0;

		foreach ($data as $val)
		{
			$total_cbr_created_today			 = $val['total_cbr_created_today'] != NULL ? $val['total_cbr_created_today'] : 0;
			$total_cbr_created_today7			 = $val['total_cbr_created_today7'] != NULL ? $val['total_cbr_created_today7'] : 0;
			$cbr_active_all						 = $val['cbr_active_all'] != NULL ? $val['cbr_active_all'] : 0;
			$count								 += $cbr_active_all;
			$cbr_active_all7					 = $val['cbr_active_all7'] != NULL ? $val['cbr_active_all7'] : 0;
			$cbr_active_created_before_today	 = $val['cbr_active_created_before_today'] != NULL ? $val['cbr_active_created_before_today'] : 0;
			$cbr_active_created_before_today7	 = $val['cbr_active_created_before_today7'] != NULL ? $val['cbr_active_created_before_today7'] : 0;
			$cbr_active_created_today			 = $val['cbr_active_created_today'] != NULL ? $val['cbr_active_created_today'] : 0;
			$cbr_active_created_today7			 = $val['cbr_active_created_today7'] != NULL ? $val['cbr_active_created_today7'] : 0;
			$total_assignaable_now				 = $val['total_assignaable_now'] != NULL ? $val['total_assignaable_now'] : 0;
			$total_assignaable_now7				 = $val['total_assignaable_now7'] != NULL ? $val['total_assignaable_now7'] : 0;
			$cbr_closed_today					 = $val['cbr_closed_today'] != NULL ? $val['cbr_closed_today'] : 0;
			$cbr_closed_today7					 = $val['cbr_closed_today7'] != NULL ? $val['cbr_closed_today7'] : 0;
			$total_cbr_created_today6			 = $val['total_cbr_created_today6'] != NULL ? $val['total_cbr_created_today6'] : 0;
			$cbr_active_all6					 = $val['cbr_active_all6'] != NULL ? $val['cbr_active_all6'] : 0;
			$cbr_active_created_before_today6	 = $val['cbr_active_created_before_today6'] != NULL ? $val['cbr_active_created_before_today6'] : 0;
			$cbr_active_created_today6			 = $val['cbr_active_created_today6'] != NULL ? $val['cbr_active_created_today6'] : 0;
			$total_assignaable_now6				 = $val['total_assignaable_now6'] != NULL ? $val['total_assignaable_now6'] : 0;
			$cbr_closed_today6					 = $val['cbr_closed_today6'] != NULL ? $val['cbr_closed_today6'] : 0;
			?>
			<tr class="content-1">
				<td class="text-center" width="37%"><?= $val['followUpType'] . "<br>" ?>
					<?php
					$getcurrentlyServingCBR				 = ServiceCallQueue::currentlyServingCBR($val['scq_follow_up_queue_type']);

					if ($getcurrentlyServingCBR)
					{
						echo date("d/M/Y h:i a", strtotime($getcurrentlyServingCBR)) . "<br>";
					}

					if ($val['scq_follow_up_queue_type'] != 9)
					{
						$result	 = ServiceCallQueue::getQueueNameByQueueId($val['scq_follow_up_queue_type']);
						$arr	 = "";
						foreach ($result as $vals)
						{

							$arr .= $vals['tqm_tea_name'] . ",";
						}
						$teamName	 = rtrim($arr, ',');
						$res		 = explode(",", $teamName, 2);
						$team		 = $res[0] != null ? '<b>Primary</b>: ' . $res[0] : "";
						if ($res[1] != null)
						{
							$team .= "<br>" . '<b>Backup</b>: ' . $res[1];
						}
						echo $team;
					}
					$primaryTeamId = ServiceCallQueue::getTeamPrimaryIdByQueueId($val['scq_follow_up_queue_type']);
					?>
				</td>
				<td class="text-center" width="8%">
					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=1&event_by=1&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $total_cbr_created_today ?></a>
					(<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=1&event_by=2&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= ($total_cbr_created_today - $total_cbr_created_today7 - $total_cbr_created_today6) ?></a>|
					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=1&event_by=3&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $total_cbr_created_today7 ?></a>|
					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=1&event_by=4&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $total_cbr_created_today6 ?></a>)
				</td>
				<td class="text-center" width="8%">
					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=2&event_by=1&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $cbr_active_all ?></a>
					(<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=2&event_by=2&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= ($cbr_active_all - $cbr_active_all7 - $cbr_active_all6) ?></a>|
					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=2&event_by=3&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $cbr_active_all7 ?></a>|
					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=2&event_by=4&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $cbr_active_all6 ?></a>)
				</td>				
				<td class="text-center" width="8%">


					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=3&event_by=1&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $cbr_active_created_before_today ?></a>
					(<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=3&event_by=2&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= ($cbr_active_created_before_today - $cbr_active_created_before_today7 - $cbr_active_created_before_today6) ?></a>|
					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=3&event_by=3&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $cbr_active_created_before_today7 ?></a>|
					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=3&event_by=4&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $cbr_active_created_before_today6 ?></a>)
				</td>
				<td class="text-center" width="8%">
					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=4&event_by=1&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $cbr_active_created_today ?></a>
					(<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=4&event_by=2&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= ($cbr_active_created_today - $cbr_active_created_today7 - $cbr_active_created_today6) ?></a>|
					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=4&event_by=3&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $cbr_active_created_today7 ?></a>|
					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=4&event_by=4&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $cbr_active_created_today6 ?></a>)
				</td>
				<td class="text-center" width="8%">
					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=5&event_by=1&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $total_assignaable_now ?></a>
					(<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=5&event_by=2&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= ($total_assignaable_now - $total_assignaable_now7 - $total_assignaable_now6) ?></a>|
					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=5&event_by=3&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $total_assignaable_now7 ?></a>|
					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=5&event_by=4&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $total_assignaable_now6 ?></a>)
				</td>
				<td class="text-center" width="8%"><?php
				if ($val['cdt_id'] == 0)
				{
					echo "NA";
				}
				else
				{
						?>
						<a target="_blank" href="/admpnl/scq/onlineCsr/?cdt_id=<?= $val['cdt_id']; ?>"><?= ServiceCallQueue::getOnlineByCatDepart($val['cdt_id']) ?></a>                
					<?php }
					?>
					(<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=7&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $val['total_assigned_csr_today'] ?></a>)
					<?php ?></td>
				<td class="text-center" width="8%">
					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=6&event_by=1&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $cbr_closed_today ?></a>
					(<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=6&event_by=2&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= ($cbr_closed_today - $cbr_closed_today7 - $cbr_closed_today6) ?></a>|
					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=6&event_by=3&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $cbr_closed_today7 ?></a>|
					<a target="_blank" href="/report/scq/cbrdetailsreport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&event_id=6&event_by=4&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate; ?>"><?= $cbr_closed_today6 ?></a>)					
				</td>
				<td class="text-center" width="8%"><?php
				if ($val['total_csr_today'] != NULL)
				{
					$dateClose = date('d/m/Y', strtotime(str_replace('-', '/', $booksub->from_date)))
						?>
						<a target="_blank" href="/report/scq/cbrCloseReport/?queueType=<?php echo $val['scq_follow_up_queue_type']; ?>&date=<?php echo $dateClose; ?>&team=<?= $primaryTeamId ?>"><?= $val['total_csr_today'] ?></a>
						<?php
					}
					else
					{
						echo "0";
					}
					?>
				</td>
			</tr>
					<?php
				}
				?>
		<?php
		if (empty($data))
		{
			?>
			<tr>
				<td class="text-center" colspan="7"><?= "No Record Found"; ?></td>

			</tr>
<?php } ?>
	</tbody>
</table>
<b>Total CBR's (Follow-up requests)  in Queue : <?php echo $count; ?> </b></h3>
<script>
    window.onscroll = function () {
        myFunction()
    };

    var header = document.getElementById("myHeader");
    var sticky = header.offsetTop;

    function myFunction() {
        if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
        } else {
            header.classList.remove("sticky");
        }
    }
</script>




