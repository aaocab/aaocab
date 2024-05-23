<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
</style>
<?php
$GLOBALS['assignFromDate']	 = $model->from_date;
$GLOBALS['assignToDate']	 = $model->to_date;
$GLOBALS['pickupDate1']		 = $model->bkg_pickup_date1;
$GLOBALS['pickupDate2']		 = $model->bkg_pickup_date2;
$GLOBALS['region']			 = $model->region;
$GLOBALS['assignMode']		 = $model->assignMode;
$GLOBALS['isManual']		 = $model->isManual;
$GLOBALS['isCritical']		 = $model->isCritical;

?>
<div class="row">
	<div class="panel" >
		<div class="panel-body">
			<?php
			$form						 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'driverBonus-form', 'enableClientValidation' => true,
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
			<div class="col-xs-12">
				<div class="form-group">
					<div class="row">
						<div class="col-xs-12 col-sm-3">
							<label class="control-label">Assigned Date</label>
							<?php
							$daterang					 = "Select Assign Date Range";
							$from_date					 = ($model->from_date == '') ? '' : $model->from_date;
							$to_date					 = ($model->to_date == '') ? '' : $model->to_date;
							if ($from_date != '' && $to_date != '')
							{
								$daterang = date('F d, Y', strtotime($from_date)) . " - " . date('F d, Y', strtotime($to_date));
							}
							?>
							<div id="assignedDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
								<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
								<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
							</div>
						</div>

						<?= $form->hiddenField($model, 'from_date'); ?>
						<?= $form->hiddenField($model, 'to_date'); ?>
						<div class="col-xs-12 col-sm-3 col-md-3">
							<div class="form-group">
								<label class="control-label">Pickup Date</label>
								<?php
								$dateRangePickup	 = "Select Pickup Date Range";
								$bkg_pickup_date1	 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
								$bkg_pickup_date2	 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
								if ($bkg_pickup_date1 != '' && $bkg_pickup_date2 != '')
								{
									$dateRangePickup = date('F d, Y', strtotime($bkg_pickup_date1)) . " - " . date('F d, Y', strtotime($bkg_pickup_date2));
								}
								?>
								<div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
									<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
									<span style="min-width: 240px"><?= $dateRangePickup ?></span> <b class="caret"></b>
								</div>
								<?= $form->hiddenField($model, 'bkg_pickup_date1'); ?>
								<?= $form->hiddenField($model, 'bkg_pickup_date2'); ?>

							</div>
						</div>
						<div class="col-xs-12 col-sm-2 col-md-2">
							<div class="form-group">
								<label class="control-label">Team/Team Leader</label>
								<?php
								$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
									'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
									'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
									'openOnFocus'		 => true, 'preload'			 => false,
									'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
									'addPrecedence'		 => false,];
								?> 
								<?php
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'adminId',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select Team Leader",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('width'	 => '100%',
										'id'	 => 'ServiceCallQueue_adminId'
									),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
                                            populateGozen(this, '{$model->adminId}');
                                                }",
								'load'			 => "js:function(query, callback){
                                            loadGozen(query, callback);
                                            }",
								'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
									),
								));
								?>
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
								<label class="control-label">Region </label>
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'region',
									'val'			 => $model->region,
									'data'			 => Vendors::model()->getRegionList(),
									'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
										'style'			 => 'width: 100%', 'placeholder'	 => 'Select Region')
								));
								?>
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<label class="control-label">Assign Mode Wise</label>
							<?php
							$filters			 = [
								0	 => 'Auto Assign',
								1	 => 'Manual Assign',
								2	 => 'Direct Accept',
							];
							$dataPay			 = Filter::getJSON($filters);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'assignMode',
								'val'			 => $model->assignMode,
								'data'			 => $filters,
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'multiple' => 'multiple', 'placeholder' => 'Select Assign Mode')
							));
							?>	
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-lg-4 mt20"> 

							<div style="display: inline-block">
								<?php echo $form->checkboxGroup($model, 'isManual', array('label' => 'Manual Booking')) ?>
							</div>
							<div style="display: inline-block">
								<?php echo $form->checkboxGroup($model, 'isCritical', array('label' => 'Critical Booking')) ?>
							</div>
						</div>


						<div class="col-xs-12 col-sm-1 mt20">
							<button class="btn btn-primary full-width" type="submit">Search</button>
						</div>
					</div>
				</div>
			</div>
			<?php $this->endWidget(); ?>
			<div class="col-xs-12">
				<?php
				if (!empty($dataProvider))
				{
					$this->widget('booster.widgets.TbExtendedGridView', array(
						'responsiveTable'	 => true,
						'fixedHeader'		 => true,
						'headerOffset'		 => 110,
						'id'				 => 'trip-grid',
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                    <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table items table-striped table-bordered mb0',
						'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
						'columns'			 => array(
							array('name' => 'CSR', 'value' => $data['CSR'], 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'CSR'),
							array('name' => 'TeamLeader', 'value' => $data['TeamLeader'], 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'sortable' => true, 'header' => 'Team Leader'),
							array('name'	 => 'TotalAssigned', 'value'	 => function ($data) 
							{
								echo CHtml::link($data["TotalAssigned"], Yii::app()->createUrl("admin/generalReport/showBooking", ["admId" => $data['AssignedUserID'], "assignFromDate" => $GLOBALS['assignFromDate'], "assignToDate" => $GLOBALS['assignToDate'], "pickupDate1" => $GLOBALS['pickupDate1'], "pickupDate2" => $GLOBALS['pickupDate2'], "region" => $GLOBALS['region'], "assignMode" => $GLOBALS['assignMode'], "nonManualAssigned" => 0, "isManual" => $GLOBALS['isManual'], "isCritical" => $GLOBALS['isCritical']]), ['target' => '_blank']);
								
							}, 'htmlOptions'		 => array('class' => 'text-center'), 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Total Assigned'),
							array('name'	 => 'UnallocatedAssigned', 'value'	 => function ($data) 
							{
									echo ($data["TotalAssigned"] - $data["AllocatedAssigned"]);

							}, 'htmlOptions'		 => array('class' => 'text-center'), 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Unallocated Assigned'),
							array('name' => 'nonManualAssigned', 'value' => function($data){
								
								echo CHtml::link($data["nonManualAssigned"], Yii::app()->createUrl("admin/generalReport/showBooking", ["admId" => $data['AssignedUserID'], "assignFromDate" => $GLOBALS['assignFromDate'], "assignToDate" => $GLOBALS['assignToDate'], "pickupDate1" => $GLOBALS['pickupDate1'], "pickupDate2" => $GLOBALS['pickupDate2'], "region" => $GLOBALS['region'], "assignMode" => $GLOBALS['assignMode'], "nonManualAssigned" => 1, "isManual" => $GLOBALS['isManual'], "isCritical" => $GLOBALS['isCritical']]), ['target' => '_blank']);

							}, 'htmlOptions' => array('class' => 'text-center'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Non Manual Assigned'),
							array('name' => 'nonManualAssignedMargin', 'value' => $data["nonManualAssignedMargin"], 'htmlOptions' => array('class' => 'text-center'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Non Manual Assigned Margin'),
							array('name' => 'profitAssigned', 'value' => function($data){
									
									echo CHtml::link($data["profitAssigned"], Yii::app()->createUrl("admin/generalReport/showBooking", ["admId" => $data['AssignedUserID'], "assignFromDate" => $GLOBALS['assignFromDate'], "assignToDate" => $GLOBALS['assignToDate'], "pickupDate1" => $GLOBALS['pickupDate1'], "pickupDate2" => $GLOBALS['pickupDate2'], "region" => $GLOBALS['region'], "assignMode" => $GLOBALS['assignMode'], "nonManualAssigned" => 0, "isManual" => $GLOBALS['isManual'], "isCritical" => $GLOBALS['isCritical'], "isProfitAssigned" => 1]), ['target' => '_blank']);
									
							}, 'htmlOptions' => array('class' => 'text-center'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Profit Assigned'),
							array('name' => 'lossAssigned', 'value' => function($data){
								
								echo CHtml::link($data["lossAssigned"], Yii::app()->createUrl("admin/generalReport/showBooking", ["admId" => $data['AssignedUserID'], "assignFromDate" => $GLOBALS['assignFromDate'], "assignToDate" => $GLOBALS['assignToDate'], "pickupDate1" => $GLOBALS['pickupDate1'], "pickupDate2" => $GLOBALS['pickupDate2'], "region" => $GLOBALS['region'], "assignMode" => $GLOBALS['assignMode'], "nonManualAssigned" => 0, "isManual" => $GLOBALS['isManual'], "isCritical" => $GLOBALS['isCritical'], "isLossAssigned" => 1]), ['target' => '_blank']);

							}, 'htmlOptions' => array('class' => 'text-center'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Loss Assigned'),
							array('name' => 'lossPercent', 'value' => $data["lossPercent"], 'htmlOptions' => array('class' => 'text-center'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Loss Percent'),
							array('name' => 'lossMargin', 'value' => $data["lossMargin"], 'htmlOptions' => array('class' => 'text-center'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Loss Margin'),
							array('name' => 'lossGozoAmount', 'value' => $data["lossGozoAmount"], 'htmlOptions' => array('class' => 'text-right'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-right'), 'header' => 'Loss Gozo Amount'),
							array('name' => 'gozoAmount', 'value' => $data["gozoAmount"], 'htmlOptions' => array('class' => 'text-right'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-right'), 'header' => 'Gozo Amount'),
							array('name' => 'netMargin', 'value' => $data["netMargin"], 'htmlOptions' => array('class' => 'text-center'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Net Margin'),
							array('name' => 'gozoCancelled', 'value' => $data["gozoCancelled"], 'htmlOptions' => array('class' => 'text-center'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Assigned Gozo Cancelled'),
							array('name'	 => 'TotalAllocated', 'value'	 => function ($data) {
									echo ($data["TotalAllocated"]) ? $data["TotalAllocated"] : '0' . "<br>";
									echo (" <span style='white-space:nowrap'> (" . $data["ManualAssign"] . "/" . $data["AutoAssign"] . ")</span>");
								}, 'htmlOptions'		 => array('class' => 'text-center'), 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => '<span title="M=>Manual, A=>Auto">Total Allocated (M/A)</span>'),
							array('name' => 'AllocatedAssigned', 'value' => $data["AllocatedAssigned"], 'htmlOptions' => array('class' => 'text-center'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Allocated Assigned'),
							array('name' => 'AllocatedGozoCancelled', 'value' => $data["AllocatedGozoCancelled"], 'htmlOptions' => array('class' => 'text-center'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Allocated Unassigned Gozo Cancelled'),
					)));
				}

				unset($GLOBALS['assignFromDate']);
				unset($GLOBALS['assignToDate']);
				unset($GLOBALS['pickupDate1']);
				unset($GLOBALS['pickupDate2']);
				unset($GLOBALS['region']);
				unset($GLOBALS['assignMode']);
				unset($GLOBALS['isManual']);
				unset($GLOBALS['isCritical']);
				?>
				
			</div>


		</div>
	</div>
</div>
<script>
    var start = '<?= date('d/m/Y', strtotime($model->from_date)); ?>';
    var end = '<?= date('d/m/Y', strtotime($model->to_date)); ?>';
    $('#assignedDate').daterangepicker(
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
        $('#ServiceCallQueue_from_date').val(start1.format('YYYY-MM-DD'));
        $('#ServiceCallQueue_to_date').val(end1.format('YYYY-MM-DD'));
        $('#assignedDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#assignedDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#assignedDate span').html('Select Pickup Date Range');
        $('#BookingInvoice_from_date').val('');
        $('#BookingInvoice_to_date').val('');
    });
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
                    'Today': [moment(), moment()],
                    'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
                    'Next 7 Days': [moment(), moment().add(6, 'days')],
                    'Next 15 Days': [moment(), moment().add(15, 'days')],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {
        $('#ServiceCallQueue_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
        $('#ServiceCallQueue_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
        $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgPickupDate span').html('Select Pickup Date Range');
        $('#ServiceCallQueue_bkg_pickup_date1').val('');
        $('#ServiceCallQueue_bkg_pickup_date2').val('');
    });

    $gozenList = null;
    function populateGozen(obj, gozen)
    {
        $followUp.populateGozen(obj, gozen);
    }
    function loadGozen(query, callback)
    {
        $followUp.loadGozen(query, callback);
    }


    function viewBookingList(obj)
    {
        var href2 = $(obj).attr("href");
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Booking List',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
                if ($('body').hasClass("modal-open"))
                {
                    box.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }

            }
        });
        return false;
    }
</script>