<?
$orderby	 = $vndArr['groupvar'];
?>
<div class="row m0">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row"> 
					<?php
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'booking-form', 'enableClientValidation' => true,
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

					<div class="col-xs-12 col-sm-6 col-md-5 col-lg-4">
						<div class="row ">
							<div class="col-xs-4 col-lg-5 pt5" >
								<div class="form-group"> 
									<label class="control-label">Group by</label><br>
									<select class="form-control" name="Vendors[groupvar]">
										<option value="date" <?php echo ($orderby == 'date') ? 'selected' : '' ?>>Day</option>
										<option value="week" <?php echo ($orderby == 'week') ? 'selected' : '' ?>>Week</option>
										<option value="month" <?php echo ($orderby == 'month') ? 'selected' : '' ?>>Month</option>
									</select>
								</div>
							</div>
							<div class="col-xs-8  col-lg-7 pt5">
								<?php
								$daterang	 = "Select Date Range";
								$fromDate	 = ($model->from_date == '') ? '' : $model->from_date;
								$toDate		 = ($model->to_date == '') ? '' : $model->to_date;
								if ($fromDate != '' && $toDate != '')
								{
									$daterang = date('F d, Y', strtotime($fromDate)) . " - " . date('F d, Y', strtotime($toDate));
								}
								?>
								<label  class="control-label">Attachment Date Range</label>
								<div id="vndCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
									<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
									<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
								</div>	
								<?= $form->hiddenField($model, 'from_date'); ?>
								<?= $form->hiddenField($model, 'to_date'); ?>			
							</div>
						</div> 
					</div>
					<div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
						<div class="row ">
							<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 pt5">
								<div style="display: inline-block;">
									<label class="control-label">Zone</label>
									<?php
									$datazone = Zones::model()->getZoneArrByFromBooking();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'vnd_zone',
										'val'			 => $model->vnd_zone,
										'data'			 => $datazone,
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'false',
											'placeholder'	 => 'Select Zone')
									));
									?>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6  col-md-4 col-lg-4 pt5">
								<div style="display: inline-block;">
									<label class="control-label">Region</label><br>
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'vnd_region',
										'val'			 => $model->vnd_region,
										'data'			 => Vendors::model()->getRegionList(),
										'htmlOptions'	 => array('multiple'		 => 'false',
											'style'			 => 'width: 100%', 'placeholder'	 => 'Select Region')
									));
									?>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 col-lg-4 pt5">
								<div style="display: inline-block;">
									<label class="control-label">State</label><br>
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'vnd_state',
										'val'			 => $model->vnd_state,
										'data'			 => States::model()->getStateList1(),
										'htmlOptions'	 => array('multiple'		 => 'false',
											'style'			 => 'width: 100%', 'placeholder'	 => 'Select State')
									));
									?>
								</div>
							</div>
						</div>
					</div>

					<div class="col-xs-12 col-sm-3  col-md-2 col-lg-1 mt20 pt5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>
					</div>
					<?php $this->endWidget(); ?>
				</div>

				<?php
				if (!empty($dataProvider))
				{
					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                    <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body table-responsive'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						'columns'			 => array(
							array('name'	 => 'signupDate',
								//'value' => '$data[signupDate]',
								'value'	 =>
								function ($data) {
									switch ($data['groupType'])
									{
										case 'date':
											echo "<nobr>" . $data['date'] . "</nobr>";
											break;
										case 'week':
											echo nl2br($data['week']);
											break;
										case 'month':
											echo "<nobr>" . $data['month'] . "</nobr>";
											break;
										default:
											break;
									}
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => '', 'style' => 'min-width:90px'), 'header'			 => 'Signup ' . ucwords($vndArr['groupvar'])),
							array('name' => 'totSignup', 'value' => '$data[totSignup]', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Total Vendor'),
							array('name' => 'approved', 'value' => '$data[approved]', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Approved Vendor'),
							array('name' => 'pending', 'value' => '$data[pending]', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Pending Vendor'),
							array('name' => 'rejected', 'value' => '$data[rejected]', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Rejected Vendor'),
							array('name' => 'totDCO', 'value' => '$data[totDCO]', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Total DCO'),
							array('name' => 'approvedDCO', 'value' => '$data[approvedDCO]', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Approved DCO'),
							array('name' => 'pendingDCO', 'value' => '$data[pendingDCO]', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Pending DCO'),
							array('name' => 'rejectedDCO', 'value' => '$data[rejectedDCO]', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Rejected DCO'),
							array('name' => 'bkgCnt', 'value' => '$data[bkgCnt]', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Allocated to/ Served Bookings'),
							array('name' => 'isLoggedIn', 'value' => '$data[isLoggedIn]', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Logged in any APP'),
							array('name' => 'lastLogin24Hour', 'value' => '$data[lastLogin24Hour]', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Logged in DCO APP in Last 24Hr'),
					)));
				}
				?> 
			</div>  
		</div>  
	</div>
</div>
<script>
	$(document).ready(function ()
	{
		var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
		var end = '<?= date('d/m/Y'); ?>';
		$('#vndCreateDate').daterangepicker(
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
				}, function (start1, end1) {
			$('#Vendors_from_date').val(start1.format('YYYY-MM-DD'));
			$('#Vendors_to_date').val(end1.format('YYYY-MM-DD'));
			$('#vndCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
		});
		$('#vndCreateDate').on('cancel.daterangepicker', function (ev, picker) {
			$('#vndCreateDate span').html('Select Date Range');
			$('#Vendors_from_date').val('');
			$('#Vendors_to_date').val('');
		});
	});

</script>


