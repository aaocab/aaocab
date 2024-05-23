<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
</style>
<div class="row">
	<div class="panel" >
		<div class="panel-body">
			<?php
			$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'driverBonus-form', 'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error'
				),
				// Please note: When you enable ajax validation, make sure the corresponding
				// controller action is handling ajax validation correctly.
				// See class documentation of CActiveForm for details on this,
				// you need to use the performAjaxValidation()-method described there.
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class' => '',
				),
			));
			/* @var $form TbActiveForm */
			?>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<div class="form-group">
					<label class="control-label">Pickup Date</label>
					<?php
					$daterang	 = "Select Date Range";
					$from_date	 = ($model->from_date == '') ? '' : $model->from_date;
					$to_date	 = ($model->to_date == '') ? '' : $model->to_date;
					if ($from_date != '' && to_date != '')
					{
						$daterang = date('F d, Y', strtotime($from_date)) . " - " . date('F d, Y', strtotime($to_date));
					}
					?>
					<div id="bookingDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
					</div>

					<?= $form->hiddenField($model, 'from_date'); ?>
					<?= $form->hiddenField($model, 'to_date'); ?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-2 col-md-2">
				<div class="form-group"> 
					<label class="control-label">Group by</label><br>
					<select class="form-control" name="BookingLog[groupBy]">
						<option value="executive" <?php echo ($model->groupBy == 'executive') ? 'selected' : '' ?>>Executive</option>
						<option value="vendor" <?php echo ($model->groupBy == 'vendor') ? 'selected' : '' ?>>Vendor</option>
						<option value="zone" <?php echo ($model->groupBy == 'zone') ? 'selected' : '' ?>>Executive, zone wise</option>
						<option value="booking" <?php echo ($model->groupBy == 'booking') ? 'selected' : '' ?>>Executive, booking wise</option>
					</select>

				</div>
			</div>


			<div class="col-xs-12 col-sm-3 mt5"><br>
				<button class="btn btn-primary full-width" type="submit"  name="accountingFlag">Search</button>
			</div>
			<?php $this->endWidget(); ?>
			<div class="col-xs-12">
				<?php
				$visibleExc	 = false;
				$visibleVnd	 = false;
				$visibleZne	 = false;
				$visibleBkg	 = false;
				$visibleCnt	 = true;
				switch ($model->groupBy)
				{
					case 'executive':
						$visibleExc	 = true;
						break;
					case 'vendor':
						$visibleVnd	 = true;
						break;
					case 'zone':
						$visibleZne	 = true;
						$visibleExc	 = true;
						break;
					case 'booking':
						$visibleExc	 = true;
						$visibleBkg	 = true;
						$visibleCnt	 = false;
						break;
					default:
						break;
				}

				if (!empty($dataProvider))
				{
					$this->widget('booster.widgets.TbGridView', array(
						'id'				 => 'trip-grid',
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                    <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered mb0',
						'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
						'columns'			 => array(
							array('name' => 'executive_name', 'value' => $data['executive_name'], 'headerHtmlOptions' => array('class' => ''), 'header' => 'Ececutive Name', 'visible' => $visibleExc),
							array('name'	 => 'bkg_booking_id', 'value'	 =>
								function ($data) {
									echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "", "target" => "_blank"]);
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Booking Id', 'visible'			 => $visibleBkg),
							array('name' => 'zon_name', 'value' => $data['zon_name'], 'headerHtmlOptions' => array('class' => ''), 'header' => 'Zone', 'visible' => $visibleZne),
							array('name' => 'vnd_code', 'value' => $data['vnd_code'], 'headerHtmlOptions' => array('class' => ''), 'header' => 'Vendor Code', 'visible' => $visibleVnd),
							array('name' => 'VendorName', 'value' => $data['VendorName'], 'headerHtmlOptions' => array('class' => ''), 'header' => 'Vendor Name', 'visible' => $visibleVnd),
							array('name' => 'cnt', 'value' => $data['cnt'], 'headerHtmlOptions' => array('class' => ''), 'header' => 'Count', 'visible' => $visibleCnt),
							array('name' => 'bkg_pickup_date', 'value' => $data["bkg_pickup_date"], 'headerHtmlOptions' => array(), 'header' => 'Pickup Date'),
					)));
				}
				?>
			</div>


		</div>
	</div>
</div>
<script>
	var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
	var end = '<?= date('d/m/Y'); ?>';
	$('#bookingDate').daterangepicker(
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
					'Last 15 Days': [moment().subtract(15, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				}
			}, function (start1, end1) {
		$('#BookingLog_from_date').val(start1.format('YYYY-MM-DD'));
		$('#BookingLog_to_date').val(end1.format('YYYY-MM-DD'));
		$('#bookingDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#bookingDate').on('cancel.daterangepicker', function (ev, picker) {
		$('#bookingDate span').html('Select Date Range');
		$('#BookingLog_from_date').val('');
		$('#BookingLog_date').val('');
	});
</script>