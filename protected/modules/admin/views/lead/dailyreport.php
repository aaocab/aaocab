<style>
	.ranges li:last-child { display: none; }
</style>
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
?>

<div class="panel panel-default">
    <div class="panel-body">
		<div class="col-xs-6 col-sm-4 col-lg-3">
			<?php
			$daterang	 = "Select Lead Date Range";
			$createdate1 = ($model->fromDate == '') ? '' : $model->fromDate;
			$createdate2 = ($model->toDate == '') ? '' : $model->toDate;
			if ($createdate1 != '' && $createdate2 != '')
			{
				$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
			}
			?>
			<label  class="control-label">Lead Date Range</label>
			<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
				<span><?= $daterang ?></span> <b class="caret"></b>
			</div>
			<?php
			echo $form->hiddenField($model, 'fromDate');
			echo $form->hiddenField($model, 'toDate');
			?>
		</div>
        <div class="col-xs-12 pt10">
			<label  class="control-label"></label>
			<?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary btn-5x pr30 pl30')); ?>
        </div>
    </div>
</div>

<div class="panel">
	<label class="label-info p5" style="color: #ffffff">Zone Wise Report</label>
	<div class="panel-body">
		<div class="row" style="margin-top: 10px">  
			<div class="col-xs-12 col-sm-12 col-md-12">       
				<table class="table table-bordered">
					<thead>
						<tr style="color: black;background: whitesmoke">
							<th><u>Status</u></th>
							<th><u>North</u></th>
							<th><u>South</u></th>
							<th><u>West</u></th>
							<th><u>East</u></th>
							<th><u>Central</u></th>
							<th><u>North-East</u></th>
							<th><u>Total</u></th>
						</tr>
					</thead>
					<tbody id="count_booking_row">                         

						<?php
						foreach ($countReports as $countReport)
						{
							?>
							<tr>
								<td><?= $countReport['status'] ?></td>
								<td><?= $countReport['north'] ?></td>
								<td><?= $countReport['south'] + (isset($countReport['kerela']) ? $countReport['kerela'] : 0) ?></td>
								<td><?= $countReport['west'] ?></td>
								<td><?= $countReport['east'] ?></td>
								<td><?= $countReport['central'] ?></td>
								<td><?= $countReport['northeast'] ?></td>
								<td><?= $countReport['north'] + $countReport['south'] + (isset($countReport['kerela']) ? $countReport['kerela'] : 0) + $countReport['west'] + $countReport['east'] + $countReport['central'] + $countReport['northeast'] ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="panel">
	<label class="label-info p5" style="color: #ffffff">CSR Lead Report</label>
	<div class="panel-body">
		<?php
		if ($dataProvider != "")
		{
			$this->widget('booster.widgets.TbGridView', [
				'id'				 => 'credits-grid',
				'dataProvider'		 => $dataProvider,
				'responsiveTable'	 => true,
				'filter'			 => $model,
				'ajaxUrl'			 => Yii::app()->createUrl('admpnl/lead/dailyleadreport', ['fromDate' => $model->fromDate, 'toDate' => $model->toDate]),
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'template'			 => "<div class='panel-heading'><div class='row m0'>
            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
            </div></div>
            <div class='panel-body'>{items}</div>
            <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'columns'			 => [
					['name' => 'executive', 'value' => '$data[executive]', 'headerHtmlOptions' => ['class' => 'col-xs-2']],
					['name' => 'cntDays','filter' => false, 'value' => '$data[cntDays]', 'headerHtmlOptions' => ['class' => 'col-xs-1']],
					['name' => 'cntTotalQuoted', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'Quote Created'],
					['name' => 'cntTotalQuoteConfirmed', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'header' => 'Quote Confirmed'],
					['name' => 'cntTotalConfirmed', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'header' => 'Confirmed Payment Followup'],
					['name' => 'total_lead_followed', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'Lead Followed'],
					['name' => 'total_lead_followed_distinct', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'Unique Lead Followed'],
					['name' => 'cntBookingFollowup', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'Quote Followed'],
					['name' => 'cntUniqueBookingFollowup', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'Unique Quote Followed'],
					['name' => 'total_inactive', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'Inactive'],
					['name' => 'converted_ratio', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'header' => 'Converted Ratio (Total Lead Quote Created/Unique Followed)'],
					['name' => 'inactive_ratio', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'header' => 'Inactive Ratio (Inactive/(Active Followed + Inactive Followed))'],
					['name' => 'active_ratio', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'header' => 'Active ratio (Active/(Active Followed + Inactive Followed))'],
					['name'	 => 'inactive_stage_breakdown', 'filter' => false, 'value'	 => function ( $data) {
							$followupDetails = '';
							if ($data['adminid'] != '')
							{
								$followupDetails = BookingTemp::findFollowupStageBreakdown($data['adminid'], $data['fromDate'], $data['toDate'], 'inactive');
								$head			 = "<tr><th>Lead Followup Status</th><th>Lead Funnel stage</th><th>State</th></tr>";
								$body			 = "";
								foreach ($followupDetails as $followupDetail)
								{
									$followupStatus	 = BookingTemp::getLeadStatus();
									$body			 .= "<tr><td>" . $followupStatus[$followupDetail['followupStatus']] . "</td><td>" . $followupDetail['followupCnt'] . "</td><td>" . $followupDetail['status'] . "</td></tr>";
								}
								echo "<table border='1px'><thead>" . $head . "</thead><tbody>" . $body . "</tbody></table>";
							}
						}, 'headerHtmlOptions'	 => ['class' => 'col-xs-1'], 'header'			 => 'Inactive Stage Breakdown'],
					['name'	 => 'active_stage_breakdown', 'filter' => false, 'value'	 => function ( $data) {
							$followupDetails = '';
							if ($data['adminid'] != '')
							{
								$followupDetails = BookingTemp::findFollowupStageBreakdown($data['adminid'], $data['fromDate'], $data['toDate'], 'active');
							}
							if ($followupDetails != '')
							{
								$head	 = "<tr><th>Lead Followup Status</th><th>Lead Funnel stage</th><th>State</th></tr>";
								$body	 = "";
								foreach ($followupDetails as $followupDetail)
								{
									$followupStatus	 = BookingTemp::getLeadStatus();
									$body			 .= "<tr><td>" . $followupStatus[$followupDetail['followupStatus']] . "</td><td>" . $followupDetail['followupCnt'] . "</td><td>" . $followupDetail['status'] . "</td></tr>";
								}
								echo "<table border='1px'><thead>" . $head . "</thead><tbody>" . $body . "</tbody></table>";
							}
						}, 'headerHtmlOptions'	 => ['class' => 'col-xs-1'], 'header'			 => 'Active Stage Breakdown']
				]
			]);
		}
		if ($createTable != '' && $confirmTable != '' && $leadTable != '' && $bookingFollowupTable != '')
		{
			LeadLog::deleteTemporaryTable($createTable, $confirmTable, $leadTable, $bookingFollowupTable);
		}
		?>
	</div>
</div>


<?php $this->endWidget(); ?>

<script type="text/javascript">
    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';

    $('#bkgCreateDate').daterangepicker(
            {
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Clear'
                },
                "showDropdowns": true,
                "alwaysShowCalendars": true,
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                }
            }, function (start1, end1) {
        $('#LeadLog_fromDate').val(start1.format('YYYY-MM-DD'));
        $('#LeadLog_toDate').val(end1.format('YYYY-MM-DD'));
        $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgCreateDate span').html('Select Lead Date Range');
        $('#LeadLog_fromDate').val('');
        $('#LeadLog_toDate').val('');
    });

</script>


