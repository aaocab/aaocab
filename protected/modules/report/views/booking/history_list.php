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
			$from_date	 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
			$to_date	 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
			if ($from_date != '' && $to_date != '')
			{
				$daterang = date('F d, Y', strtotime($from_date)) . " - " . date('F d, Y', strtotime($to_date));
			}
			?>
			<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
				<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
			</div>

		</div>
		<?= $form->hiddenField($model, 'bkg_create_date1'); ?>
		<?= $form->hiddenField($model, 'bkg_create_date2'); ?>

	</div>

	<div class="col-xs-12 col-sm-2 col-md-2">   
		<label class="control-label"></label>
		<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width submitCbr')); ?>
	</div>
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
		echo CHtml::beginForm(Yii::app()->createUrl('report/booking/history'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
		?>
		<input type="hidden" id="from_date" name="from_date" value="<?= $model->bkg_create_date1; ?>"/>
		<input type="hidden" id="to_date" name="to_date" value="<?= $model->bkg_create_date2; ?>"/>
		<input type="hidden" id="export" name="export" value="true"/>
		<button class="btn btn-default" type="submit" style="width: 185px;">Export</button>
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
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 p5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name'	 => 'CreateDate', 'value'	 => function ($data) {

							echo ($data['createDate']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Create Date'),
					array('name'	 => 'MMT/IBIBO(ALL/UV/QT/SV/CN)', 'value'	 => function ($data) {

							echo ($data['AllCntMMT'] . "/" . $data['UnVerifiedCntMMT'] . "/" . $data['QuotedCntMMT'] . "/" . $data['ServedCntMMT'] . "/" . $data['CancelledCntMMT']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'MMT/IBIBO(ALL/UV/QT/SV/CN)'),
					array('name'	 => 'MMT Served % ', 'value'	 => function ($data) {

							if ($data['AllCntMMT'] > 0)
							{
								echo ROUND(100 * ($data['ServedCntMMT']) / $data['AllCntMMT']);
							}
							else
							{
								echo 0;
							}
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'MMT Served % '),

					array('name'	 => 'EMT(ALL/UV/QT/SV/CN)', 'value'	 => function ($data) {

							echo ($data['AllCntEMT'] . "/" . $data['UnVerifiedCntEMT'] . "/" . $data['QuotedCntEMT'] . "/" . $data['ServedCntEMT'] . "/" . $data['CancelledCntEMT']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'EMT(ALL/UV/QT/SV/CN)'),
					array('name'	 => 'EMT Served %', 'value'	 => function ($data) {

							if ($data['AllCntEMT'] > 0)
							{
								echo ROUND(100 * ($data['ServedCntEMT']) / $data['AllCntEMT']);
							}
							else
							{
								echo 0;
							}
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'EMT Served %'),

					array('name'	 => 'SPICE(ALL/UV/QT/SV/CN)', 'value'	 => function ($data) {

							echo ($data['AllCntSPICE'] . "/" . $data['UnVerifiedCntSPICE'] . "/" . $data['QuotedCntSPICE'] . "/" . $data['ServedCntSPICE'] . "/" . $data['CancelledCntSPICE']);

						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'SPICE(ALL/UV/QT/SV/CN)'),
					array('name'	 => 'SPICE Served %', 'value'	 => function ($data) {

							if ($data['AllCntSPICE'] > 0)
							{
								echo ROUND(100 * ($data['ServedCntSPICE']) / $data['AllCntSPICE']);
							}
							else
							{
								echo 0;
							}
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'SPICE Served %'),


					
					array('name'	 => 'B2B_OTHER(ALL/UV/QT/SV/CN)', 'value'	 => function ($data) {

							echo ($data['AllCntB2B'] . "/" . $data['UnVerifiedCntB2B'] . "/" . $data['QuotedCntB2B'] . "/" . $data['ServedCntB2B'] . "/" . $data['CancelledCntB2B']);

						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'B2B_OTHER(ALL/UV/QT/SV/CN)'),
					array('name'	 => 'B2B_OTHER Served %', 'value'	 => function ($data) {

							if ($data['AllCntB2B'] > 0)
							{
								echo ROUND(100 * ($data['ServedCntB2B']) / $data['AllCntB2B']);
							}
							else
							{
								echo 0;
							}
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'B2B_OTHER Served %'),

					
					array('name'	 => 'B2C(ALL/UV/QT/SV/CN)', 'value'	 => function ($data) {

							echo ($data['AllCntB2C'] . "/" . $data['UnVerifiedCntB2C'] . "/" . $data['QuotedCntB2C'] . "/" . $data['ServedCntB2C'] . "/" . $data['CancelledCntB2C']);

						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'B2C(ALL/UV/QT/SV/CN)'),
					array('name'	 => 'B2C Served %', 'value'	 => function ($data) {

							if ($data['AllCntB2C'] > 0)
							{
								echo ROUND(100 * ($data['ServedCntB2C']) / $data['AllCntB2C']);
							}
							else
							{
								echo 0;
							}
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'B2C Served %'),

				
				array('name'	 => 'All(ALL/UV/QT/SV/CN)', 'value'	 => function ($data) {

							echo ($data['AllCnt'] . "/" . $data['AllUnVerifiedCnt'] . "/" . $data['AllQuotedCnt'] . "/" . $data['AllServedCnt'] . "/" . $data['AllCancelledCnt']);

					}, 'sortable'			 => true,
					'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
					'htmlOptions'		 => array('class' => 'text-center'),
					'header'			 => 'All(ALL/UV/QT/SV/CN)'),
				array('name'	 => 'All Served %', 'value'	 => function ($data) {

						if ($data['AllCnt'] > 0)
						{
							echo ROUND(100 * ($data['AllServedCnt']) / $data['AllCnt']);
						}
						else
						{
							echo 0;
						}
					}, 'sortable'			 => true,
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
					'htmlOptions'		 => array('class' => 'text-center'),
					'header'			 => 'All Served %'),	
	
	
			)));
		}
		?>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {

		var start = '<?= ($model->bkg_create_date1 == '') ? date('d/m/Y', strtotime("-1 month", time())) : date('d/m/Y', strtotime($model->bkg_create_date1)); ?>';
        var end = '<?= ($model->bkg_create_date2 == '') ? date('d/m/Y') : date('d/m/Y', strtotime($model->bkg_create_date2)); ?>';


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
            $('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
            $('#Booking_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Transaction Date Range');
            $('#Booking_bkg_create_date1').val('');
            $('#Booking_bkg_create_date2').val('');
        });

    });

</script>


