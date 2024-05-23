<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
?>
<div class="row">
    <div class="col-xs-12">
		<?php
		/* @var $model Vendors */
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'getleadconversion', 'enableClientValidation' => true,
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
		<div class="col-xs-12 col-sm-2 col-md-2 pr0" >
			<div class="form-group"> 
				<label class="control-label">Group by</label><br>
				<select class="form-control" name="PaymentGateway[groupvar]" id="callqueuegroupvar">
					<option value="date" <?php echo ($groupBy == 'date') ? 'selected' : '' ?>>Day</option>
					<option value="week" <?php echo ($groupBy == 'week') ? 'selected' : '' ?>>Week</option>
					<option value="month" <?php echo ($groupBy == 'month') ? 'selected' : '' ?>>Month</option>
				</select>

			</div>
		</div>

		<div class="col-xs-12 col-sm-6 col-md-3" style="">
			<div class="form-group">
				<label class="control-label">Date Range</label>
				<?php
				$daterang			 = "Select Date Range";
				$trans_create_date1	 = ($model->trans_create_date1 == '') ? '' : $model->trans_create_date1;
				$trans_create_date2	 = ($model->trans_create_date2 == '') ? '' : $model->trans_create_date2;
				if ($trans_create_date1 != '' && $trans_create_date2 != '')
				{
					$daterang = date('F d, Y', strtotime($trans_create_date1)) . " - " . date('F d, Y', strtotime($trans_create_date2));
				}
				?>
				<div id="apgDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
					<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
					<span style="min-width: 240px"><?php echo $daterang ?></span> <b class="caret"></b>
				</div>
				<?php echo $form->hiddenField($model, 'trans_create_date1'); ?>
				<?php echo $form->hiddenField($model, 'trans_create_date2'); ?>
			</div>
			<?php
			if ($error != '')
			{
				echo
				"<span class='text-danger'> $error</span>";
			}
			?>
		</div>
		<div class="col-xs-12 col-sm-2 col-md-2">
			<label class="control-label">Filter By Type</label>
			<?php
			$paymentTypeJson = PaymentType::model()->getList();
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'apg_ptp_id',
				'val'			 => $model->apg_ptp_id,
				'data'			 => $paymentTypeJson,
				'options'		 => ['allowClear' => true],
				'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Type')
			));
			?>
		</div>
        <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-1 text-center" style="padding: 4px;">
            <button class="btn btn-primary" type="submit" style="width: 125px;" >Search</button> 
        </div>
		<br/><br/>
		<?php
		$this->endWidget();

		$checkExportAccess = false;
		if ($roles['rpt_export_roles'] != null)
		{
			$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
		}
		if ($checkExportAccess)
		{
			?>

			<?= CHtml::beginForm(Yii::app()->createUrl('report/financial/paymentSummaryReport'), "post", []); ?>
			<input type="hidden" id="export" name="export" value="true"/>
			<input type="hidden" id="trans_create_date1" name="trans_create_date1" value="<?= $model->trans_create_date1 ?>"/>
			<input type="hidden" id="trans_create_date2" name="trans_create_date2" value="<?= $model->trans_create_date2 ?>"/>
			<input type="hidden" id="apg_ptp_id" name="apg_ptp_id" value="<?= $model->apg_ptp_id ?>"/>
			<input type="hidden" id="groupBy" name="groupBy" value="<?= $groupBy ?>"/>
			<button class="btn btn-default" type="submit">Export Below Table</button>

			<?php
			echo CHtml::endForm();
		}
		?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$params									 = array_filter($_REQUEST);
			$dataProvider->getPagination()->params	 = $params;
			$dataProvider->getSort()->params		 = $params;
			$this->widget('booster.widgets.TbExtendedGridView', array(
				'id'				 => 'pgGrid',
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
							<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
							</div></div>
							<div class='panel-body table-responsive'>{items}</div>
							<div class='panel-footer'>
							<div class='row'><div class='col-xs-12 col-sm-6 p5'>{summary}</div>
							<div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table  table-bordered dataTable mb0',
				'htmlOptions'		 => array('class' => 'panel panel-primary compact'),
				'columns'			 => array(
					array('name'	 => 'date',
						'value'	 => function ($data) {
							echo $data["date"];
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => ucfirst($groupBy)),
					array('name'	 => 'apg_ptp_id',
						'value'	 => function ($data) use ($paymentTypeJson) {
							echo $paymentTypeJson[$data['apg_ptp_id']];
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Type'),
					array('name'				 => 'receive',
						'value'				 => $data['receive'],
						'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Received'),
					array('name'				 => 'refund',
						'value'				 => $data['refund'],
						'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Refund'),
					array('name'				 => 'net',
						'value'				 => $data['net'],
						'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Net'),
			)));
		}
		?>
    </div>
</div>
<script >
    var start = '<?php echo date('d/m/Y', strtotime('-1 day')); ?>';
    var end = '<?php echo date('d/m/Y', strtotime('-1 day')); ?>';

    $('#apgDate').daterangepicker(
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
                    'Last 2 Days': [moment().subtract(1, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                    'Last 6 week': [moment().subtract(6, 'weeks').startOf('isoWeek'), moment()],
                    'This Month': [moment().startOf('month'), moment()],
                    'Last 2 Month': [moment().subtract(2, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1)
    {
        $('#PaymentGateway_trans_create_date1').val(start1.format('YYYY-MM-DD'));
        $('#PaymentGateway_trans_create_date2').val(end1.format('YYYY-MM-DD'));
        $('#apgDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#apgDate').on('cancel.daterangepicker', function (ev, picker)
    {
        $('#apgDate span').html('Select Pickup Date Range');
        $('#PaymentGateway_trans_create_date1').val('');
        $('#PaymentGateway_trans_create_date2').val('');
    });
    $('#getassignments').submit(function (event)
    {

        var fromDate = new Date($('#PaymentGateway_trans_create_date1').val());
        var toDate = new Date($('#PaymentGateway_trans_create_date2').val());

        var diffTime = Math.abs(fromDate - toDate);
        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        if (diffDays > 90)
        {
            alert("Date range should not be greater than 90 days");
            return false;
        }
    });

</script>