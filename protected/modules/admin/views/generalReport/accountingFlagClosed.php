<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
</style>
<div class="row">
	<div class="panel" >
		<div class="panel-body">
	      <div class="col-xs-12" style="margin-bottom:50px">  
						<div class="col-xs-3"> 
							<?= CHtml::beginForm(Yii::app()->createUrl('admpnl/generalReport/accountingFlagClosedReport'), "post", ['style' => "margin-bottom: 10px;"]); ?>
							<input type="hidden" id="export" name="export" value="true"/>
							<input type="hidden" id="export_search" name="export_search" value="<?= $model->search ?>"/>
							<input type="hidden" id="export_from_date" name="export_from_date" value="<?= $model->from_date ?>"/>
							<input type="hidden" id="export_to_date" name="export_to_date" value="<?= $model->to_date ?>"/>
							<button class="btn btn-default" type="submit" style="width: 185px;">Export Table</button>
							<?= CHtml::endForm() ?>
						</div>
                    
					</div>
			<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id' => 'driverBonus-form', 'enableClientValidation' => true,
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
		 <div class="col-xs-12 col-sm-6 col-md-6">
			<div class="form-group">
				<div class="col-xs-12 col-sm-6">
				<label class="control-label">Accounting Flag Closed Date Range</label>
				 <?php
				 $daterang = "Select Date Range";
				 $from_date  = ($model->from_date == '') ? '' : $model->from_date;
				 $to_date = ($model->to_date == '') ? '' : $model->to_date;
				 if ($from_date  != '' && to_date != '')
				 {
					$daterang = date('F d, Y', strtotime($from_date)) . " - " . date('F d, Y', strtotime($to_date));
				 }
				 ?>
				 <div id="bookingDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
					 <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
					 <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
				 </div>
				 </div>
				
				 <?= $form->hiddenField($model, 'from_date'); ?>
				<?= $form->hiddenField($model, 'to_date'); ?>
			</div>
		 </div>
		
		 
		<div class="col-xs-12 col-sm-3 mt5"><br>
			<button class="btn btn-primary full-width" type="submit"  name="accountingFlag">Search</button>
		</div>
<?php $this->endWidget(); ?>
	               
			<div class="col-xs-12">
				<?php
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
							array('name' => 'flagClosingDate', 'value' => $data['flagClosingDate'], 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Flag Closing Date'),
							//array('name' => 'adminId', 'value' => function($data) { echo CHtml::link($data["bkg_booking_id"], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "", "target" => "_blank"]);}, 'headerHtmlOptions' => array(), 'header' => 'Booking Id'),
							array('name' => 'adminId', 'value' => $data['adminId'], 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Admin Id'),
							array('name' => 'adminEmail', 'value' => '$data["adminEmail"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Admin Email'),

							array('name' => 'adminName', 'value' => '$data["adminName"]', 'headerHtmlOptions' => array(), 'header' => 'Admin Name'),
							array('name' => 'totalFlagClosed', 'value' => '$data["totalFlagClosed"]', 'headerHtmlOptions' => array(), 'header' => 'Total Flag Closed'),
							//array('name' => 'adminId', 'value' => function($data) { echo CHtml::link($data["bkg_booking_id"], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "", "target" => "_blank"]);}, 'headerHtmlOptions' => array(), 'header' => 'Booking Id'),
							
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