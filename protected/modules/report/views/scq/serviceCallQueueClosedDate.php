<div class='row p15'>
	<?php
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'booking-form',
			'enableClientValidation' => true,
		//		'method'				 => 'post',
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
	<div class="col-xs-6 col-sm-4 col-md-4" style="">
		<div class="form-group">
			<label class="control-label">Date Range</label>
			<?php
			$daterang	 = "Select Date Range";
			$from_date	 = ($followUps->from_date == '') ? '' : $followUps->from_date;
			$to_date	 = ($followUps->to_date == '') ? '' : $followUps->to_date;
			if ($from_date != '' && $to_date != '')
			{
				$daterang = date('F d, Y', strtotime($from_date)) . " - " . date('F d, Y', strtotime($to_date));
			}
			?>
			<div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
				<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
			</div>
			<?= $form->hiddenField($followUps, 'from_date'); ?>
			<?= $form->hiddenField($followUps, 'to_date'); ?>

		</div>
	</div>
	<div class="col-xs-12 col-sm-2 col-md-4">
        <div class="form-group">
            <label class="control-label">Teams</label>
            <?php
            $fetchList = Teams::getList();
            $this->widget('booster.widgets.TbSelect2', array(
                'model'       => $followUps,
                'attribute'   => 'teamList',
                'val'         => $followUps->teamList,
                'data'        => [-1 => 'All'] + $fetchList,
                'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Teams', 'multiple' => 'multiple')
            ));
            ?>
        </div> 
    </div>

	<div class="col-xs-12 col-sm-2 col-md-2">   
		<label class="control-label"></label>
		<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width submitCbr')); ?>
	</div>
	<?php $this->endWidget(); ?>
	<div class="col-xs-1">
	<?php
		$checkExportAccess = false;
		if ($roles['rpt_export_roles'] != null)
		{
			$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
		}
		if ($checkExportAccess)
		{

			echo CHtml::beginForm(Yii::app()->createUrl('report/scq/ServiceCallQueueByClosedDate'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
			?>
			<input type="hidden" id="export_fromdate" name="export_fromdate" value="<?= date("Y-m-d", strtotime($followUps->from_date)); ?>"/>
			<input type="hidden" id="export_todate" name="export_todate" value="<?= date("Y-m-d", strtotime($followUps->to_date)); ?>"/>
			<input type="hidden" id="export_team" name="export_team" value="<?= implode(",", $followUps->teamList); ?>"/>
			<input type="hidden" id="export" name="export" value="true"/>
			<button class="btn btn-default btn-5x pr30 pl30 mt10" type="submit" style="width: 185px;">Export</button>
			<?php
			echo CHtml::endForm();
		}
	?>
	</div>
</div>

<?php
if (!empty($dataProvider))
{

	$params									 = array_filter($_REQUEST);
	$dataProvider->getPagination()->params	 = $params;
	$dataProvider->getSort()->params		 = $params;
	$this->widget('booster.widgets.TbGridView', array(
		'responsiveTable'	 => true,
		'dataProvider'		 => $dataProvider,
		'template'			 => "<div class='panel-heading'><div class='row m0'>
													<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
											</div></div>
											<div class='panel-body table-responsive'>{items}</div>
											<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
		'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
		'columns'			 =>
		array
			(
//				array('name'	 => 'adm_id', 'value'	 => $data['adm_id'], 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Admin Id'),
			array('name' => 'FollowupId', 'value' => function ($data) {
				echo CHtml::link($data['FollowupId'], Yii::app()->createUrl("aaohome/scq/view", ["id" => $data['FollowupId']]), ["class" => "viewFollowup", "onclick" => "", 'target' => '_blank']);
			},
			'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Followup ID'),
			array('name' => 'ItemID', 'value' => function ($data) {
				echo CHtml::link($data['ItemID'], Yii::app()->createUrl("aaohome/booking/view", ["id" => $data['ItemID']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
			}, 
			'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Item ID', 'htmlOptions' => ["class" => "text-center"]),
			array('name' => 'followUpdDate', 'value' => function ($data) {
					echo ($data['followUpdDate'])?date('d/m/y H:i:s', strtotime($data['followUpdDate'])) : '';
				},
				'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-3'), 'header' => 'Follow Up Date'),
			array('name' => 'QueueType', 'value' => $data['QueueType'], 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Queue Type'),
			array('name' => 'CreateDate', 'value' => function ($data) {
					echo ($data['CreateDate'] != '')?date('d/m/y H:i:s', strtotime($data['CreateDate'])) : '';
				}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-3'), 'header' => 'Create Date'),
			array('name' => 'Create By', 'value' => $data['Create By'], 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Create By'),
			array('name' => 'Creation Comment', 'value' => $data['Creation Comment'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-6'), 'header' => 'Creation Comment'),
			array('name' => 'Assigned CSR(Employee ID)', 'value' => $data['Assigned CSR(Employee ID)'], 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Assigned CSR(Employee ID)'),
			array('name' => 'Assign Date', 'value' => function ($data) {
						echo ($data['Assign Date'] != '')? date('d/m/y H:i:s', strtotime($data['Assign Date'])) : '';
				},
			'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-3'), 'header' => 'Assign Date'),
			array('name' => 'Time to Assign(Mintue)', 'value' => $data['Time to Assign(Mintue)'], 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Time to Assign(Mintue)'),
			array('name' => 'Closed Date (CSR)', 'value' => function ($data) {
					echo ($data['Closed Date (CSR)']) ? date('d/m/y H:i:s', strtotime($data['Closed Date (CSR)'])) : '';
				},
				'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-3'), 'header' => 'Closed Date (CSR)'),
			array('name' => 'Time to Close(Mintue)', 'value' => $data['Time to Close(Mintue)'], 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Time to Close(Mintue)'),
			array('name' => 'Disposition Comments', 'value' => $data['Disposition Comments'], 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Disposition Comments'),
			
		)
	));
}
?>

<script>
    var start = '<?= date('d/m/Y', strtotime('-15 days')); ?>';
    var end = '<?= date('d/m/Y'); ?>';

    $('#bkgPickupDate').daterangepicker(
            {
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Clear'
                },
//                dateLimit: {
//                    'months': 1,
//                    'days': -1
//                },
                "showDropdowns": true,
                "alwaysShowCalendars": true,
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 31 Days': [moment().subtract(31, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function (start1, end1)
    {
        $('#from_date, #ServiceCallQueue_from_date').val(start1.format('YYYY-MM-DD'));
        $('#to_date, #ServiceCallQueue_to_date').val(end1.format('YYYY-MM-DD'));
        $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker)
    {
        $('#bkgPickupDate span').html('Select Pickup Date Range');
        $('#from_date, #ServiceCallQueue_from_date').val('');
        $('#to_date, #ServiceCallQueue_to_date').val('');
    });
</script>

