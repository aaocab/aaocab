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
</style>

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


	<div class="col-xs-12 col-sm-2 col-md-2">   
		<label class="control-label"></label>
		<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width submitCbr')); ?>
	</div>
	<?php $this->endWidget(); ?>

	<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 mt5">
		<?php
		$checkExportAccess = false;
		if ($roles['rpt_export_roles'] != null)
		{
			$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
		}
		if ($checkExportAccess)
		{
			echo CHtml::beginForm(Yii::app()->createUrl('report/admin/csrPerformanceReport'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
			?>
			<input type="hidden" id="from_date" name="from_date" value="<?= $followUps->from_date ?>"/>
			<input type="hidden" id="to_date" name="to_date" value="<?= $followUps->to_date ?>"/>
			<input type="hidden" id="export" name="export" value="true"/>
			<button class="btn btn-default" type="submit" style="width: 185px;">Export</button>
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
			array('name' => 'CSR_Name', 'value' => $data['CSR_Name'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-3'), 'header' => 'CSR Name'),
			array('name' => 'Total_Days', 'value' => $data['Total_Days'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Total Days', 'htmlOptions' => ["class" => "text-center"]),
			array('name'	 => 'Quote_Created', 'value'	 => function ($data) {
					echo "{$data['Quote_Created']} <span title='Self Quote Payment Followup'>({$data['self_payment_followups']})</span>";
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array("class" => "text-center"), 'header'			 => 'Quote Created', 'htmlOptions'		 => ["class" => "text-center"]),
			array('name'	 => 'Booking_Confirmed', 'value'	 => function ($data) {
					$value	 = "{$data['Booking_Confirmed']} <span><span title='Booking Cancelled'>({$data['Booking_Cancelled']})</span>";
					$value	 .= " <span title='Booking Already Served'>({$data['Booking_Served']})</span></span>";
					echo $value;
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array("class" => "text-center"), 'header'			 => 'Booking Confirmed', 'htmlOptions'		 => ["class" => "text-center"]),
			array('name'	 => 'Total_Gozo_Amount', 'value'	 => function ($data) {
					echo "{$data['Total_Gozo_Amount']} <span title='Gozo Amount already earned'>({$data['Gozo_Amount_Earned']})</span>";
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array("class" => "text-center"), 'header'			 => 'Total Gozo Amount', 'htmlOptions'		 => ["class" => "text-center"])
		)
	));
}
?>

<script>
    var start = '<?= date('d/m/Y', strtotime('-31 days')); ?>';
    var end = '<?= date('d/m/Y'); ?>';

    $('#bkgPickupDate').daterangepicker(
            {
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Clear'
                },
                dateLimit: {
                    'months': 1,
                    'days': -1
                },
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