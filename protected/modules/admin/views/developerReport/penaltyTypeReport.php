<?php

$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>

<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
</style>
<div class="row">
	<div class="panel" >
	<div class="panel-body">
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id' => 'penaltyTypeReport-form', 'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'errorCssClass' => 'has-error'
		),
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation' => false,
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => '',
		),
		));
		/* @var $form TbActiveForm */
		?>
		 <div class="col-xs-12 col-sm-4 col-md-4">
			<div class="form-group">
				<label class="control-label">Date Range</label>
				 <?php
				 $daterang = "Select Date Range";
				 $from_date  = ($model->from_date == '') ? '' : $model->from_date;
				 $to_date = ($model->to_date == '') ? '' : $model->to_date;
				 if ($from_date  != '' && $to_date != '')
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

		<div class="col-xs-12 col-sm-3 mt5"><br>
			<button class="btn btn-primary full-width" type="submit"  name="penaltyTypeReports">Search</button>
		</div>
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$this->widget('booster.widgets.TbGridView', array(
				'id'				 => 'route-grid',
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
				
				
				array('name' => 'penaltyType', 'value' => '$data["penaltyType"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Penalty Type Id'),
				array('name' => 'pltDesc', 'value' => '$data["pltDesc"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Penalty Type Name'),
				array('name' => 'totalCnt', 'value' => '$data["totalCnt"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Total Count'),
				array('name' => 'totalPenalty', 'value' => '$data["totalPenalty"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Total Penalty'),
			  
			)));
		}
		?>
    </div>
	
	<?php $this->endWidget(); ?>
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
        $('#AccountTransactions_from_date').val(start1.format('YYYY-MM-DD'));
        $('#AccountTransactions_to_date').val(end1.format('YYYY-MM-DD'));
        $('#bookingDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bookingDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bookingDate span').html('Select Date Range');
        $('#AccountTransactions_from_date').val('');
        $('#AccountTransactions_date').val('');
    });
</script>