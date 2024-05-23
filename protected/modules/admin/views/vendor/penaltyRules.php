<div class="row">
	<div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array
					(
					array('name' => 'plt_desc', 'value' => '$data[plt_desc]', 'headerHtmlOptions' => array(), 'header' => 'Penalty Rule'),
					array('name'	 => 'plt_value_type',
						'value'	 => function ($data) {
							if ($data['plt_value_type'] == 1)
							{
								echo "Percent";
							}
							elseif ($data['plt_value_type'] == 2)
							{
								echo "Fixed";
							}
						},
						'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Type'),
					array('name' => 'plt_min_value', 'value' => '$data[plt_min_value]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Min Amount'),
					array('name' => 'plt_max_value', 'value' => '$data[plt_max_value]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Max Amount'),
					array('name' => 'plt_value', 'value' => '$data[plt_value]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Amount'),
					array('name'	 => 'plt_create_date', 'value'	 => function ($data) {
							return DateTimeFormat::DateTimeToLocale($data['plt_create_date']);
						},
						'sortable'			 => true, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Created At'),
			)));
		}
		?>

    </div>


</div>

<script>

	$(document).ready(function () {
		var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
		var end = '<?= date('d/m/Y'); ?>';

		$('#sendDate').daterangepicker(
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
						'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					}
				}, function (start1, end1) {
			$('#SmsLog_sendDate1').val(start1.format('YYYY-MM-DD'));
			$('#SmsLog_sendDate2').val(end1.format('YYYY-MM-DD'));
			$('#sendDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
		});
		$('#sendDate').on('cancel.daterangepicker', function (ev, picker) {
			$('#sendDate span').html('Select Send Date Range');
			$('#SmsLog_sendDate1').val('');
			$('#SmsLog_sendDate2').val('');
		});
	});







</script>