<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="row">
    <div class="col-xs-12 col-md-8 col-md-offset-2">

		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'note-add-form', 'enableClientValidation' => TRUE,
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
				'class' => 'form-horizontal',
			),
		));
		/* @var $form TbActiveForm */
		?>

		<div class="col-xs-12">
			<div class="panel panel-default panel-border">
				<div class="panel-body">
					<div class="row mb15">
						<div class="col-xs-12 col-md-6 mb15">
							<?php
							$daterang			 = "Select Date Range";
							$fromDate			 = ($model->fromDate == '') ? '' : $model->fromDate;
							$todate				 = ($model->toDate == '') ? '' : $model->toDate;
							if ($fromDate != '' && $todate != '')
							{
								$daterang = date('F d, Y', strtotime($fromDate)) . " - " . date('F d, Y', strtotime($todate));
							}
							?>
							<label> From/To Date</label>
							<div id="DateRange" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?php
							echo $form->hiddenField($model, 'fromDate');
							echo $form->hiddenField($model, 'toDate');
							?>
						</div>
					</div>
					<div class="row mb15">
						<div class="col-xs-12 col-md-6 mb15">
							<label> From Ledger</label>
							<?php
							$paymenttypearr	 = AccountLedger::getAllLedgerIds();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'from_ledger_id',
								'val'			 => $model->from_ledger_id,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($paymenttypearr)),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'From Ledger', 'required' => true)
							));
							?>
						</div>
						<div class="col-xs-12 col-md-6 mb15">
							<label> To Ledger</label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'to_ledger_id',
								'val'			 => $model->to_ledger_id,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($paymenttypearr), 'multiple' => true, 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'From Ledger', 'required' => true, 'multiple' => 'multiple')
							));
							?>
						</div>
						<div class="col-xs-12 col-md-6 mb15" id="groupByMonth">
							<?=
							$form->radioButtonListGroup($model, 'groupby_period', array('label' => '',
								'widgetOptions'	 => array('data' => ['all'=>'All Transactions', 'month' => 'Group By Month', 'date' => 'Group By Date']), 'inline' => true, 'htmlOptions' => ['class' => 'p0']));
							?>
						</div>
						<div class="col-xs-12 col-md-6 mb15" id="groupByPartner"  style="display:none">
							<?=
							$form->checkboxListGroup($model, 'group_by_partner', array('label' => '',
								'widgetOptions'	 => array('data' => ['partner' => 'Group By Partners']), 'inline' => true, 'htmlOptions'	 => ['class' => 'p0']));
							?>
						</div>
						<div class="col-xs-12 col-md-6 mb15" id="groupByVendor"  style="display:none">
							<?=
							$form->checkboxListGroup($model, 'group_by_type', array('label'			 => '',
								'widgetOptions'	 => array('data' => ['vendor' => 'Group By Vendors']), 'inline' => true, 'htmlOptions'	 => ['class' => 'p0']));
							?>
						</div>
						<div class="col-xs-12 col-sm-3"></div>
						<div class="col-xs-12 text-center">
							<input type="submit" value="Submit" name="yt0" id="notesubmit" class="btn btn-primary pl30 pr30 btnSubmit">
						</div>
					</div>
				</div>

			</div>
		</div>

		<?php $this->endWidget(); ?>
    </div>

</div>

<div class="row">
	<div class="col-xs-12">

		<div class="projects">
			<div id="account_tab1">

				<?php
				if (!empty($dataprovider))
				{
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataprovider,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						//    'ajaxType' => 'POST',
						'columns'			 => array(
							array('name'	 => 'ldr_id', 'filter' => FALSE, 'value' => $data['ldr_id'], 'sortable'	=> false, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Id'),
							array('name'	 => 'ldr_from_date', 'filter' => FALSE, 'value'	 => function ($data) {
									echo date("d/M/Y", strtotime($data[ldr_from_date]));
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'From Date'),
							array('name'	 => 'ldr_to_date', 'filter' => FALSE, 'value'	 => function ($data) {
									echo date("d/M/Y", strtotime($data[ldr_to_date]));
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'To Date'),
							array('name'	 => 'ldr_from_ledger_id', 'filter' => FALSE, 'value'	 => function ($data)  use ($ledgers) {
									if (trim($data['ldr_from_ledger_id']) != '')
									{
										echo $ledgers[$data['ldr_from_ledger_id']];
									}
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'From Ledger'),
							array('name'	 => 'ldr_to_ledger_id', 'filter' => FALSE, 'value'	 => function ($data) use ($ledgers) {
									if (trim($data['ldr_to_ledger_id']) != '')
									{
										$arrToLedgersIds = explode(',', $data['ldr_to_ledger_id']);
										foreach ($arrToLedgersIds as $ledgerId)
										{
											$dataLedger[] = $ledgers[$ledgerId];
										}

										echo implode(', ', $dataLedger);
									}
									else
									{
										echo "-";
									}
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'To Ledger'),
							array('name'	 => 'ldr_groupby_period', 'filter' => FALSE, 'value'	 => function ($data) {
									echo ($data['ldr_groupby_period'] != '' ? $data['ldr_groupby_period'] : '-');
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Group By Period'),
							array('name'	 => 'ldr_groupby_type', 'filter' => FALSE, 'value'	 => function ($data) {
									echo ($data['ldr_groupby_type'] != '' ? $data['ldr_groupby_type'] : '-');
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Group By Type'),
							array('name'	 => 'ldr_status', 'filter' => FALSE, 'value'	 => function ($data) {
									if ($data['ldr_status'] == '1')
									{
										echo "Pending";
									}
									elseif ($data['ldr_status'] == '2')
									{
										echo "Processing";
									}
									elseif ($data['ldr_status'] == '3')
									{
										echo "Completed";
									}
									elseif ($data['ldr_status'] == '4')
									{
										echo "Failed";
									}
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Status'),
							array('name'	 => 'adm_fname', 'filter' => FALSE, 'value'	 => function ($data) {
									echo $data['adm_fname'] . " " . $data['adm_lname'];
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Created By'),
							array('name'	 => 'ldr_created_date', 'filter' => FALSE, 'value'	 => function ($data) {
									echo date("d/M/Y h:i A", strtotime($data[ldr_created_date]));
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Created On'),
							array('name'	 => 'ldr_data_filepath', 'filter' => FALSE, 'value'	 => function ($data) {
									if ($data['ldr_data_filepath'] != "")
									{
										echo "<small class='text-warning'><a href='" . Yii::app()->getBaseUrl(true) . "/" . $data['ldr_data_filepath'] . "' download><i class='fas fa-file-csv font-24 color-green pl10'></i></a></small>";
									}
									else
									{
										echo "-";
									}
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Download'),
					)));
				}
				?> 
			</div> 
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function ()
	{
		var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
		var end = '<?= date('d/m/Y'); ?>';
		$('#DateRange').daterangepicker(
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
			$('#LedgerDataRequests_fromDate').val(start1.format('YYYY-MM-DD'));
			$('#LedgerDataRequests_toDate').val(end1.format('YYYY-MM-DD'));
			$('#DateRange span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
		});
		$('#DateRange').on('cancel.daterangepicker', function (ev, picker) {
			$('#DateRange span').html('Select From Date Range');
			$('#LedgerDataRequests_fromDate').val('');
			$('#LedgerDataRequests_toDate').val('');
		});
		$(document).on('click', '.btnSubmit', function () {
			var fromDate = $("#LedgerDataRequests_fromDate").val();
			var fromLedger = $("#LedgerDataRequests_from_ledger_id").val();

			if (fromDate == '')
			{
				bootbox.alert("Please Select Date Range");
				return false;
			} else if (fromLedger == '')
			{
				bootbox.alert("Please Select From Ledger");
				return false;
			} else
			{
				return true;
			}
		});

	});

	$('#LedgerDataRequests_from_ledger_id').change(function () {
		
		$('#LedgerDataRequests_group_by_period_0').prop('checked', false);
		$('#LedgerDataRequests_group_by_type_0').prop('checked', false);
		$('#LedgerDataRequests_group_by_partner_0').prop('checked', false);
			
		var ledgerId = $('#LedgerDataRequests_from_ledger_id').val();
			
		if (ledgerId == 15) 
		{
			$("#groupByVendor").hide("slow");
			$("#groupByPartner").show("slow");
			$('#LedgerDataRequests_group_by_type_0').prop('checked',false);
			$('#LedgerDataRequests_group_by_type_0').parent('span.checked').removeClass('checked');
		} 
		else if(ledgerId == 14)
		{
			$("#groupByPartner").hide("slow");
			$("#groupByVendor").show("slow");
			$('#LedgerDataRequests_group_by_partner_0').prop('checked',false);
			$('#LedgerDataRequests_group_by_partner_0').parent('span.checked').removeClass('checked');
		}
		else
		{
			$("#groupByPartner").hide("slow");
			$("#groupByVendor").hide("slow");
			$('#LedgerDataRequests_group_by_partner_0').prop('checked',false);
			$('#LedgerDataRequests_group_by_partner_0').parent('span.checked').removeClass('checked');
			$('#LedgerDataRequests_group_by_type_0').prop('checked',false);
			$('#LedgerDataRequests_group_by_type_0').parent('span.checked').removeClass('checked');
		}
	});

	$('#LedgerDataRequests_group_by_type_0,#LedgerDataRequests_group_by_partner_0').click(function () {
		$('#LedgerDataRequests_groupby_period_1,#LedgerDataRequests_groupby_period_2').prop('checked',false);
		$('#LedgerDataRequests_groupby_period_1,#LedgerDataRequests_groupby_period_2').parent('span.checked').removeClass('checked');
	});
	
	$('#LedgerDataRequests_groupby_period_1,#LedgerDataRequests_groupby_period_2').click(function () {
		if ($(this).is(':checked'))
		{
			$('#LedgerDataRequests_group_by_partner_0').prop('checked',false);
			$('#LedgerDataRequests_group_by_partner_0').parent('span.checked').removeClass('checked');
			$('#LedgerDataRequests_group_by_type_0').prop('checked',false);
			$('#LedgerDataRequests_group_by_type_0').parent('span.checked').removeClass('checked');
		}
	});
	
</script>