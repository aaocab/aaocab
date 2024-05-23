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
		<div class="col-xs-12 col-sm-3 col-md-2" >
			<div class="form-group"> 
				<label class="control-label">Group by</label><br>
				<select class="form-control" name="BookingSub[groupvar]">
					<option value="date" <?php echo ($orderby == 'date') ? 'selected' : '' ?>>Day</option>
					<option value="week" <?php echo ($orderby == 'week') ? 'selected' : '' ?>>Week</option>
					<option value="month" <?php echo ($orderby == 'month') ? 'selected' : '' ?>>Month</option>
				</select>

			</div>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-4" >

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

		<div class="col-xs-12 col-sm-4 col-md-4" style="">
			<div class="form-group">
				<label class="control-label">Pickup Date</label>
				<?php
				$daterang			 = "Select Pickup Date Range";
				$bkg_pickup_date1	 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
				$bkg_pickup_date2	 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
				if ($bkg_pickup_date1 != '' && $bkg_pickup_date2 != '')
				{
					$daterang = date('F d, Y', strtotime($bkg_pickup_date1)) . " - " . date('F d, Y', strtotime($bkg_pickup_date2));
				}
				?>
				<div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
					<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
					<span style="min-width: 240px"><?php echo $daterang ?></span> <b class="caret"></b>
				</div>
				<?php echo $form->hiddenField($model, 'bkg_pickup_date1'); ?>
				<?php echo $form->hiddenField($model, 'bkg_pickup_date2'); ?>

			</div>
			<?
			if ($error != '')
			{
				echo
				"<span class='text-danger'> $error</span>";
			}
			?>
		</div>
        <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20" style="padding: 4px;">
            <button class="btn btn-primary" type="submit" style="width: 185px;"  name="zoneSearch">Search</button> 
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
					array('name'	 => 'date', 'value'	 =>
						function ($data)
						{
							switch ($data['groupType'])
							{
								case 'date':
									echo "<nobr>" . $data['date'] . "</nobr>";
									break;
								case 'week':
									echo $data['week'];
									break;
								case 'month':
									echo "<nobr>" . $data['monthname'] . "</nobr>";
									break;
								default:
									break;
							}
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => ucfirst($orderby)),
					array('name' => 'totalBooking', 'value' => $data['totalBooking'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Total Booking'),
					array('name' => 'gozoAmount', 'value' => $data['gozoAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Gozo Amount'),
					array('name' => 'netBaseAmount', 'value' => $data['netBaseAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Net BaseAmount'),
					array('name' => 'ManualAssignPercent', 'value' => $data['ManualAssignPercent'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Manual Assign Percent'),
					array('name' => 'ManualMargin', 'value' => $data['ManualMargin'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Manual Margin'),
					array('name' => 'ManualGozoAmount', 'value' => $data['ManualGozoAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Manual Gozo Amount'),
					array('name' => 'AutoAssignPercent', 'value' => $data['AutoAssignPercent'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Auto Assign Percent'),
					array('name' => 'AutoMargin', 'value' => $data['AutoMargin'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Auto Margin'),
					array('name' => 'AutoGozoAmount', 'value' => $data['AutoGozoAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Auto Gozo Amount'),
					array('name' => 'BidAssignPercent', 'value' => $data['BidAssignPercent'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Bid Assign Percent'),
					array('name' => 'BidAssignMargin', 'value' => $data['BidAssignMargin'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Bid Assign Margin'),
					array('name' => 'BidGozoAmount', 'value' => $data['BidGozoAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Bid Gozo Amount'),
					array('name' => 'DirectAssignPercent', 'value' => $data['DirectAssignPercent'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Direct Assign Percent'),
					array('name' => 'DirectAssignMargin', 'value' => $data['DirectAssignMargin'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Direct Assign Margin'),
					array('name' => 'DirectGozoAmount', 'value' => $data['DirectGozoAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Direct Gozo Amount'),
					array('name' => 'TotalMargin', 'value' => $data['TotalMargin'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Total Margin'),
			)));
		}
		?>
    </div>
</div>
<script >
	var start = '<?php echo date('d/m/Y', strtotime('-1 month')); ?>';
	var end = '<?php echo date('d/m/Y'); ?>';
	$('#bkgPickupDate').daterangepicker(
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
		$('#BookingSub_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
		$('#BookingSub_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
		$('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
		$('#bkgPickupDate span').html('Select Pickup Date Range');
		$('#BookingSub_bkg_pickup_date1').val('');
		$('#BookingSub_bkg_pickup_date2').val('');
	});
	$('#getassignments').submit(function (event) {

		var fromDate = new Date($('#BookingSub_bkg_pickup_date1').val());
		var toDate = new Date($('#BookingSub_bkg_pickup_date2').val());

		var diffTime = Math.abs(fromDate - toDate);
		var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
		if (diffDays > 90) {
			alert("Date range should not be greater than 90 days");
			return false;
		}
	});

</script>