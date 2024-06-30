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
<div class="row">
    <div class="col-xs-12  pb10">
        <a href="/report/scq/cbrdetailsreport" target="_blank"> Click To View  CBR Details Report</a>
        <br>
        <a href="/report/scq/cbrStaticalCloseData?date=<?php echo $followup->date; ?>" target="_blank" > Click To View CBR Statistical Data Report</a>
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
	?>
    <div class ="col-xs-12 col-sm-2 col-md-3">

		<div class="form-group">
			<label  class="control-label">Filter By Date </label>
			<?php
			$daterang	 = "Select Date Range";
			if (isset($followUps->from_date) && $followUps->from_date != "")
			{
				$createdate1 = date("Y-m-d", strtotime($followUps->from_date));
			}
			else
			{
				$createdate1 = date("Y-m-d");
			}
			if (isset($followUps->to_date) && $followUps->to_date != "")
			{
				$createdate2 = date("Y-m-d", strtotime($followUps->to_date));
			}
			else
			{
				$createdate2 = date("Y-m-d");
			}
			if ($createdate1 != '' && $createdate2 != '')
			{
				$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
			}
			?>
			<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
				<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
			</div>
			<input type="hidden" value="<?php echo $createdate1; ?>" id="ServiceCallQueue_fromdate" name="ServiceCallQueue[from_date]" >
			<input type="hidden" value="<?php echo $createdate2; ?>" id="ServiceCallQueue_todate" name="ServiceCallQueue[to_date]" >									
		</div>

	</div>


    <div class="col-xs-12 col-sm-1 col-md-1 text-center mt20 p5">   
		<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
	<?php
	$this->endWidget();
	$checkExportAccess = false;
	if ($roles['rpt_export_roles'] != null)
	{
		$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
	}
	if ($checkExportAccess)
	{

		echo CHtml::beginForm(Yii::app()->createUrl('report/scq/dispatchFollowUp'), "post", []);
		?>
		<div class="col-xs-12 col-sm-2 col-md-2">   
			<label class="control-label"></label>
			<input type="hidden" id="export" name="export" value="true"/>			
			<input type="hidden" id="export_fromdate" name="export_fromdate" value="<?= date("Y-m-d", strtotime($followUps->from_date)); ?>"/>
			<input type="hidden" id="export_todate" name="export_todate" value="<?= date("Y-m-d", strtotime($followUps->to_date)); ?>"/>
			<button class="btn btn-default" type="submit">Export Below Table</button>
		</div>
		<?php
		echo CHtml::endForm();
	}
	?>
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
			array('name'	 => 'bkg_id', 'value'	 =>
				function ($data) {
					if ($data['bkg_id'] != null)
					{
						echo CHtml::link($data['bkg_id'], Yii::app()->createUrl("aaohome/booking/view", ["id" => $data['bkg_id']]), ['target' => '_blank']);
					}
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Booking Id'),
			array('name' => 'bkg_pickup_date', 'value' => '$data[bkg_pickup_date]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Pickup Date'),
			array('name' => 'AUTO', 'value' => '$data[AUTO]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Auto Vendor Followup'),
			array('name' => 'MANUAL', 'value' => '$data[MANUAL]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Manual Vendor Followup'),
			array('name' => 'GOZEN', 'value' => '$data[GOZEN]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Gozen'),
			array('name' => 'ClosedDate', 'value' => '$data[ClosedDate]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'CBR CLOSED DATE'),
		)
	));
}
?>
<script type="text/javascript">
    var start = '<?= date("d/m/Y", strtotime($createdate1)); ?>';
    var end = '<?= date("d/m/Y", strtotime($createdate2)); ?>';

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
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {
        $('#ServiceCallQueue_fromdate').val(start1.format('YYYY-MM-DD'));
        $('#ServiceCallQueue_todate').val(end1.format('YYYY-MM-DD'));
        $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgCreateDate span').html('Select Booking Date Range');
        $('#ServiceCallQueue_fromdate').val('');
        $('#ServiceCallQueue_todate').val('');
    });

</script>