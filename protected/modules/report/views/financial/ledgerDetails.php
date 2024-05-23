<style>
    .search-form ul{
        list-style: none ;
        margin-bottom: 20px;
        vertical-align: bottom
    }
    .search-form ul li{
        padding: 0;
    }
    .panel-body {
        border: 1px #eeeeee solid;
        padding: 15px!important;
    }
    .panel-heading {
        padding: 10px 15px!important;
    }
    .pagination {
        margin: 0;
    }
    .table{
        margin-bottom: 5px;
    }
</style>
<?php
$pageno		 = Yii::app()->request->getParam('page');
$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'showLedger', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class' => 'form-horizontal'
	),
		));
/* @var $form TbActiveForm */
?>
<div class="col-xs-12">
    <div class="panel panel-default">

        <div class="panel panel-body">

            <div class="col-xs-12 pb20">
				<?php
				$daterang	 = "Select Transaction Date Range";
				$createdate1 = ($model->trans_create_date1 == '') ? '' : $model->trans_create_date1;
				$createdate2 = ($model->trans_create_date2 == '') ? '' : $model->trans_create_date2;
				if ($createdate1 != '' && $createdate2 != '')
				{
					$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
				}
				?>
                <div class="col-xs-4" mt30">

					<label  class="control-label pb10">Transaction Date</label>
					<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
					</div>
					<?php
					echo $form->hiddenField($model, 'trans_create_date1');
					echo $form->hiddenField($model, 'trans_create_date2');
					?>
                </div>
				<div class="col-xs-4 mt30">
					<p></p>
					<?php
					$paymenttypearr		 = AccountLedger::getAllLedgerIds();
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'apg_ledger_type_id',
						'val'			 => $model->apg_ledger_id,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($paymenttypearr), 'multiple' => ''),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Ledger1', 'id' => 'PaymentGateway_apg_ledger_type_id', 'required' => true)
					));
					?>

				</div>
				<div class="col-xs-4 mt30">
					<p></p>
					<?php
					$paymenttypearr		 = AccountLedger::getAllLedgerIds();
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'apg_ledger_type_ids',
						'val'			 => explode(",", $model->apg_ledger_type_ids),
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($paymenttypearr), 'multiple' => true, 'allowClear' => true),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Ledger 2', 'id' => 'PaymentGateway_apg_ledger_type_ids', 'multiple' => 'multiple')
					));
					?>
				</div>
				<div class="col-xs-6 mt30">
					<label  class="control-label"></label>
					<button class="btn btn-primary mt5" type="submit" style="width: 185px; padding: 7px 10px;"  name="bookingSearch">Search</button>
				</div>
            </div>
        </div>
		<?php
		$this->endWidget();
		?>
		<div class="projects">
			<div id="account_tab1">
				<?php
				$checkExportAccess	 = false;
				if ($roles['rpt_export_roles'] != null)
				{
					$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
				}
				if ($checkExportAccess)
				{
					?>
					<?= CHtml::beginForm(Yii::app()->createUrl('report/financial/ledgerList'), "post", ['style' => "margin-bottom: 10px;"]); ?>
					<input type="hidden" id="export1" name="export1" value="true"/>
					<input type="hidden" id="export_from1" name="export_from1" value="<?= $model->trans_create_date1 ?>"/>
					<input type="hidden" id="export_to1" name="export_to1" value="<?= $model->trans_create_date2 ?>"/>
					<input type="hidden" id="ledger1" name="ledger1" value="<?= $model->apg_ledger_type_id ?>"/>
					<input type="hidden" id="ledger2" name="ledger2" value="<?= $model->apg_ledger_type_ids ?>"/>
					<?php
					if (!empty($dataProvider))
					{
						?>	
						<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
						<?php
					}
					echo CHtml::endForm();
				}
				if (!empty($dataProvider))
				{


					$this->widget('booster.widgets.TbGridView', array(
						'id'				 => 'transaction-grid',
						'responsiveTable'	 => true,
						'ajaxUrl'			 => CHtml::normalizeUrl(Yii::app()->createUrl('admin/account/accountlist', $dataProvider->getPagination()->params)),
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
										<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
								</div></div>
								<div class='panel-body'>{items}</div>
								<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary'),
						'columns'			 => array(
							array('name'	 => 'date',
								'value'	 => function ($data) {
									echo $data["act_date"];
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array('style' => 'text-align:left'), 'header'			 => 'Date'),
							array('name'	 => 'ledgerName',
								'value'	 => function ($data) {
									echo $data["ledgerName"];
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array('style' => 'text-align:left'), 'header'			 => 'Ledger1'),
							array('name'	 => 'BookingId', 'filter' => false,
								'value'	 => function ($data) {
									if ($data['bkg_id'] != "")
									{
										echo '<nobr><a href="../booking/view?id=' . $data['bkg_id'] . '">' . $data['bkg_booking_id'] . '</a></nobr>';
									}
									else
									{
										echo '<nobr><a href="../booking/view?id=' . $data['bkg_id2'] . '">' . $data['bkg_booking_id2'] . '</a></nobr>';
									}
								}
								, 'sortable'			 => true, 'headerHtmlOptions'	 => array('style' => 'text-align:left'),
								'visible'			 => '($data["bkg_booking_id"] != NULL || $data["bkg_booking_id"] != " ")',
								'header'			 => 'BookingId'),
							array('name'	 => 'vendor', 'filter' => false,
								'value'	 => function ($data) {
									if ($data['vnd_code'] != "")
									{
										echo '<nobr><a href="../vendor/view?code=' . $data['vnd_code'] . '">' . $data['vendorName'] . '</a></nobr>';
									}
									else
									{
										echo '<nobr><a href="../vendor/view?code=' . $data['vnd_code2'] . '">' . $data['vendorName2'] . '</a></nobr>';
									}
								}
								, 'sortable'			 => true, 'headerHtmlOptions'	 => array('style' => 'text-align:left'),
								'header' => 'VendorName'),
							array('name'	 => 'agent', 'filter' => false,
								'value'	 => function ($data) {
									if ($data['agent'] != "")
									{
										echo '<nobr>' . $data['agent'] . '</nobr>';
									}
									else
									{
										echo '<nobr>' . $data['agent2'] . '</nobr>';
									}
								}
								, 'sortable'			 => true, 'headerHtmlOptions'	 => array('style' => 'text-align:left'),
								'header' => 'Agent'),
							array('name'	 => 'ledgerName2', 'filter' => false,
								'value'	 => function ($data) {
									echo '<nobr>' . $data['ledgerName2'] . '</nobr>';
								}
								, 'sortable'			 => false, 'headerHtmlOptions'	 => array('style' => 'text-align:left'), 'htmlOptions'		 => array('class' => 'text-left'), 'header'			 => 'Ledger2'),
							array('name'	 => 'act_remarks', 'filter' => false, 'value'	 => function ($data) {
									echo $data['act_remarks'];
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('style' => 'text-align:left'), 'htmlOptions'		 => array('class' => 'text-left'), 'header'			 => 'Remarks'),
							array('name'	 => 'trans_amount', 'filter' => false, 'value'	 => function ($data) {
									echo '<nobr><i class="fa fa-inr"></i>' . $data['amount'] . '</nobr>';
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('style' => 'text-align:right'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Amount'),
					)));
				}
				?>
			</div>
			<div id="account_tab2" style="display: block;" >
			</div>
		</div>
    </div>

</div>

<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
<script>
    $(document).ready(function () {

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
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    }
                }, function (start1, end1) {
            $('#PaymentGateway_trans_create_date1').val(start1.format('YYYY-MM-DD'));
            $('#PaymentGateway_trans_create_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Transaction Date Range');
            $('#PaymentGateway_trans_create_date1').val('');
            $('#PaymentGateway_trans_create_date2').val('');
        });


    });
    $('#showLedger').submit(function (event) {

        var fromDate = new Date($('#PaymentGateway_trans_create_date1').val());

        var toDate = new Date($('#PaymentGateway_trans_create_date2').val());

        var diffTime = Math.abs(fromDate - toDate);
        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        if (diffDays > 90) {
            alert("Date range should not be greater than 90 days");
            return false;
        }
        var ledger = $('#PaymentGateway_apg_ledger_type_id').val();

        if (ledger == "")
        {
            alert("Ledger type should be choose");
            return false;
        }

    });









</script>
