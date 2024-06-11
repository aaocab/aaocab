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
			<div class="col-xs-12 col-sm-2 col-md-3">
				<div class="form-group">
					<label class="control-label">Date Range</label>
					<?php
					$daterang	 = "Select Date Range";
					$from_date	 = ($model->from_date == '') ? '' : $model->from_date;
					$to_date	 = ($model->to_date == '') ? '' : $model->to_date;
					if ($from_date != '' && $to_date != '')
					{
						$daterang = date('F d, Y', strtotime($from_date)) . " - " . date('F d, Y', strtotime($to_date));
					}
					?>
					<div id="createdDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
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
					<select class="form-control" name="VendorCoins[groupBy]">
						<option value="date" <?php echo ($model->groupBy == 'date') ? 'selected' : '' ?>>Day</option>
						<option value="week" <?php echo ($model->groupBy == 'week') ? 'selected' : '' ?>>Week</option>
						<option value="month" <?php echo ($model->groupBy == 'month') ? 'selected' : '' ?>>Month</option>
						<option value="vnd_id" <?php echo ($model->groupBy == 'vnd_id') ? 'selected' : '' ?>>Vendor</option>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-2 col-md-2">
				<div class="form-group"> 
					<label class="control-label">Vendor Status</label><br>
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'vndStatus',
						'data'			 => Vendors::model()->getStatusList(),
						'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Status'),
						'options'		 => array('allowClear' => true)
					));
					?>
				</div>
			</div>
			<div class="col-xs-12 col-md-2 mt5"><br>
				<button class="btn btn-primary full-width" type="submit"  name="accountingFlag">Search</button>
			</div>
			<?php $this->endWidget(); ?>
			<div class="col-xs-12">
				<?php
				$visible	 = false;
				$visibleDate = true;
				if ($model->groupBy == "vnd_id")
				{
					$visible	 = true;
					$visibleDate = false;
				}
				if (!empty($dataProvider))
				{
					$this->widget('booster.widgets.TbGridView', array(
						'id'				 => 'vendorCoin-grid',
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
							array('name'	 => 'date', 'value'	 => function ($data) use ($model) {
									if ($model->groupBy == "month")
									{
										echo $data['month'];
									}
									elseif ($model->groupBy == "week")
									{
										echo nl2br($data['weekLabel']);
									}
									else
									{
										echo $data['date'];
									}
								}, 'htmlOptions'		 => array('class' => 'text-center'), 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Date', 'visible'			 => $visibleDate),
							array('name'	 => 'vnd_code', 'value'	 => function($data) {
									echo CHtml::link($data["vnd_code"], Yii::app()->createUrl("admin/vendor/view", ["code" => $data['vnd_code']]), ["class" => "", 'target' => '_blank']);
								}, 'sortable'			 => false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Vendor Code', 'visible'			 => $visible),
							array('name' => 'vnd_name', 'value' => $data["vnd_name"], 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Vendor Name', 'visible' => $visible),
							array('name' => 'vrs_vnd_overall_rating', 'value' => $data["vrs_vnd_overall_rating"], 'sortable' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Rating', 'visible' => $visible),
							array('name'	 => 'vrs_first_approve_date', 'value'	 => function($data) {
									if ($data["vrs_first_approve_date"] != null && $data["vrs_first_approve_date"] != '')
									{
										echo date("d/M/Y", strtotime($data["vrs_first_approve_date"]));
									}
									else
									{
										echo "-";
									}
								}, 'sortable'			 => false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Approved On', 'visible'			 => $visible),
							array('name' => 'cntBkg', 'value' => $data["cntBkg"], 'htmlOptions' => array('class' => 'text-right'), 'headerHtmlOptions' => array('class' => 'text-right'), 'header' => 'Booking Count', 'visible' => $visible),
							array('name' => 'Rating', 'value' => $data["Rating"], 'htmlOptions' => array('class' => 'text-center', 'style' => 'background-color : #e7ede6'), 'headerHtmlOptions' => array('class' => 'text-center col-xs-1'), 'header' => '5 Star Rating'),
							array('name' => 'Dot', 'value' => $data["Dot"], 'htmlOptions' => array('class' => 'text-center', 'style' => 'background-color : #e7ede6'), 'headerHtmlOptions' => array('class' => 'text-center col-xs-1'), 'header' => 'DriverAppUsage (Start & End Both)<br />+<br />OnTimeTripArrived'),
							array('name' => 'GozoNow', 'value' => $data["GozoNow"], 'htmlOptions' => array('class' => 'text-center', 'style' => 'background-color : #e7ede6'), 'headerHtmlOptions' => array('class' => 'text-center col-xs-1'), 'header' => 'On Serving <br />Gozonow Booking'),
							array('name' => 'credited', 'value' => $data["credited"], 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center col-xs-1'), 'header' => 'Credit'),
							array('name' => 'debited', 'value' => $data["debited"], 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center col-xs-1'), 'header' => 'Debit'),
							array('name' => 'balance', 'value' => $data["balance"], 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center col-xs-1'), 'header' => 'Balance'),
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
    $('#createdDate').daterangepicker(
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
        $('#VendorCoins_from_date').val(start1.format('YYYY-MM-DD'));
        $('#VendorCoins_to_date').val(end1.format('YYYY-MM-DD'));
        $('#createdDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
</script>