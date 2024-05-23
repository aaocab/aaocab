<?php
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
/** @var Booking $model */
?>
<div class="row">
    <div class="col-xs-12">
		<?php
		/* @var $model Vendors */
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'getassignments', 'enableClientValidation' => true,
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
		<div class="row">
			<div class="col-xs-12 col-sm-6  col-md-4 col-lg-3 mb20" style="">
				<div class="form-group">
					<label class="control-label">Date range</label>
					<?php
					$daterange			 = "Select Date Range";
					$bkg_create_date1	 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
					$bkg_create_date2	 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
					if ($bkg_create_date1 != '' && $bkg_create_date2 != '')
					{
						$daterange = date('F d, Y', strtotime($bkg_create_date1)) . " - " . date('F d, Y', strtotime($bkg_create_date2));
					}
					?>
					<div id="bkgCreateDate" class="col-md-3" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?php echo $daterange ?></span> <b class="caret"></b>
					</div>
					<?php echo $form->hiddenField($model, 'bkg_create_date1'); ?>
					<?php echo $form->hiddenField($model, 'bkg_create_date2'); ?>
				</div>
			</div>
			<div class=" col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5"  >
				<button class="btn btn-primary" type="submit" style="width: 125px;" >Search</button> 
			</div>
		</div>
		<?php $this->endWidget(); ?>
</div>
<div class="row">
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$this->widget('booster.widgets.TbExtendedGridView', array(
				'responsiveTable'	 => true,
				'fixedHeader'		 => true,
				'headerOffset'		 => 110,
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
				'itemsCssClass'		 => 'table items table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name'	 => 'VisitorDate', 'value'	 => function ($data) {

							echo ($data['dt']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Visitor Date'),
					array('name' => 'redirectCount', 'value' => '$data[redirect_count]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Redirect Count'),
					array('name' => 'leadCount', 'value' => '$data[lead_count]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Lead Count'),
					array('name' => 'quoted', 'value' => '$data[quoted]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Quoted/Expired'),
					array('name' => 'confirmed', 'value' => '$data[confirmed]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Confirmed'),
					array('name' => 'completed', 'value' => '$data[completed]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Completed'),
					array('name' => 'cancelled', 'value' => '$data[cancelled]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Cancelled'),
			)));
		}
		?>
    </div>
</div>
<script>
    var start = '<?= date('d/m/Y', strtotime('-7 days')); ?>';
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
					'This Month': [moment().startOf('month'), moment()],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				}
			}, function(start1, end1)
	{
		$('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
		$('#Booking_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
		$('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#bkgCreateDate').on('cancel.daterangepicker', function(ev, picker)
	{
		$('#bkgCreateDate span').html('Select Date Range');
		$('#Booking_bkg_create_date1').val('');
		$('#Booking_bkg_create_date2').val('');
	});
</script>