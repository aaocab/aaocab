<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
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
			<div class="col-xs-12 col-sm-3 col-md-2">
				<div class="form-group"> 
					<label class="control-label">Group by</label><br>
					<select class="form-control" name="Booking[groupvar]">
						<option value="date" <?php echo ($groupBy == 'date') ? 'selected' : '' ?>>Day</option>
						<option value="week" <?php echo ($groupBy == 'week') ? 'selected' : '' ?>>Week</option>
						<option value="month" <?php echo ($groupBy == 'month') ? 'selected' : '' ?>>Month</option>
					</select>

				</div>
			</div>

			<div class="col-xs-12 col-sm-3 col-md-3">
				<div class="form-group">
					<label class="control-label">Create Date</label>
					<?php
					$daterang			 = "Select Pickup Date Range";
					$bkg_create_date1	 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
					$bkg_create_date2	 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
					if ($bkg_create_date1 != '' && $bkg_create_date2 != '')
					{
						$daterang = date('F d, Y', strtotime($bkg_create_date1)) . " - " . date('F d, Y', strtotime($bkg_create_date2));
					}
					?>
					<div id="bkgCreateDate" class="col-md-3" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?php echo $daterang ?></span> <b class="caret"></b>
					</div>
					<?php echo $form->hiddenField($model, 'bkg_create_date1'); ?>
					<?php echo $form->hiddenField($model, 'bkg_create_date2'); ?>

				</div>
			</div>
			
			<div class="col-xs-12 col-sm-3 col-md-3">
				<div class="form-group">
					<label class="control-label">Booking Status</label>
					<?php
						$bookingStatusArr = Booking::model()->getBkgStatus();
						$this->widget('booster.widgets.TbSelect2', array(
							'name'			 => 'bkg_status',
							'model'			 => $model,
							'data'			 => $bookingStatusArr,
							'value'			 => explode(',', $model->bkg_status),
							'htmlOptions'	 => array(
								'multiple'		 => 'multiple',
								'placeholder'	 => 'Status',
								'width'			 => '100%',
								'style'			 => 'width:100%',
							),
						));
					?>
				</div>
			</div>

			<div class="col-xs-12 col-sm-3 col-md-4">
				<div class="form-group">
					<label class="control-label">Partner</label>
					<?
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'bkg_agent_id',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Partner",
						'fullWidth'			 => false,
						'options'			 => array('allowClear' => true),
						'htmlOptions'		 => array('width' => '100%',
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                  populatePartner(this, '{$model->bkg_agent_id}');
                                                }",
					'load'			 => "js:function(query, callback){
                        loadPartner(query, callback);
                        }",
					'render'		 => "js:{
                            option: function(item, escape){
                            return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                            },
                            option_create: function(data, escape){
                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                            }
                        }", 'allowClear'	 => true
						),
					));
					?>
				</div>
			</div>

			<div class="col-xs-6 col-sm-3 col-md-2 text-left">
				<?php echo $form->checkboxGroup($model, 'nonAPIPartner', array('label' => 'Non API Partner')) ?>
			</div>
			<div class="col-xs-6 col-sm-3 col-md-2 text-left">
				<?php echo $form->checkboxGroup($model, 'b2cbookings', array('label' => 'B2C Only')) ?>
			</div>
			<div class="col-xs-12 col-sm-3 col-md-2 mb10 text-center">
				<button class="btn btn-primary" type="submit" style="width: 125px;" >Search</button> 
			</div>
		</div>
		</div>
<?php $this->endWidget(); ?>
	</div>
</div>
<div class="row">
    <div class="col-xs-12">
<?php
if (!empty($dataProvider))
{
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
			array('name'	 => 'date', 'value' => $data["date"], 'sortable' => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'col-xs-1 text-center'), 'header'			 => ucfirst($groupBy)),
			array('name' => 'completedAmount', 'value' => function($data){echo number_format($data['completedAmount']);}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Active Booking Amount'),
			array('name' => 'netBaseAmount', 'value' => function($data){echo number_format($data['netBaseAmount']);}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Active Net Base Amount <br>(#1)'),
			array('name' => 'advance', 'value' => function($data){echo number_format($data['advance']);}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Net Advance <br>(#2)'),
			array('name' => 'creditUsed', 'value' => function($data){echo number_format($data['creditUsed']);}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'GozoCoins Used'),
			array('name' => 'totalTollTax', 'value' => function($data){echo number_format($data['totalTollTax']);}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Toll Tax<br>(#3)'),
			array('name' => 'totalStateTax', 'value' => function($data){echo number_format($data['totalStateTax']);}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'State Tax<br>(#4)'),
			array('name' => 'totalDriverAllowance', 'value' => function($data){echo number_format($data['totalDriverAllowance']);}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Driver Allowance'),
			array('name' => 'parkingCharge', 'value' => function($data){echo number_format($data['parkingCharge']);}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Parking Charges'),
			array('name' => 'gst', 'value' => function($data){echo number_format($data['gst']);}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Served GST'),
			array('name' => 'cancelCharge', 'value' => function($data){echo number_format($data['cancelCharge']);}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Cancel Charge'),
			array('name' => 'cancelBaseFare', 'value' => function($data){echo number_format($data['cancelBaseFare']);}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Cancel Base Fare'),
			array('name' => 'cancelGST', 'value' => function($data){echo number_format($data['cancelGST']);}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Cancel GST'),
			array('name' => 'totalGst', 'value' => function($data){echo number_format($data['totalGst']);}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Total GST')
	)));
}
?>
<div class="col-xs-12"><b>#Active Booking:</b> New, Assigned, Allocated, Completed, Settled</div>
<div class="col-xs-12"><b>#1:</b> Base Amount + Additional Charge + Extra Charge + Extra Km Charge + Addon Charges + Convenience Charge - Net Discount Amount</div>
<div class="col-xs-12"><b>#2:</b> Net Advance (Advance Amount + Credits Used - Refund Amount)</div>
<div class="col-xs-12"><b>#3:</b> Toll Tax + Extra Toll Tax + Airport Entry Fee</div>
<div class="col-xs-12"><b>#4:</b> State Tax + Extra State Tax</div>
    </div>
</div>
<script >
	var start = '<?php echo date('d/m/Y', strtotime('-1 month')); ?>';
	var end = '<?php echo date('d/m/Y'); ?>';
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
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Last 15 Days': [moment().subtract(14, 'days'), moment()],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment()],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				}
			}, function (start1, end1) {
		$('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
		$('#Booking_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
		$('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
		$('#bkgCreateDate span').html('Select Create Date Range');
		$('#Booking_bkg_create_date1').val('');
		$('#Booking_bkg_create_date2').val('');
	});
	$('#getassignments').submit(function (event) {

		var fromDate = new Date($('#Booking_bkg_create_date1').val());
		var toDate = new Date($('#Booking_bkg_create_date2').val());

		var diffTime = Math.abs(fromDate - toDate);
		var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
//		if (diffDays > 90) {
//			alert("Date range should not be greater than 90 days");
//			return false;
//		}
	});

</script>