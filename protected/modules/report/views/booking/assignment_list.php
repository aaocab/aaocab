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
			<label class="control-label">Pickup (Date Range)</label>
			<?php
			$daterang	 = "Select Date Range";
			$pickup_date1	 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
			$pickup_date2	 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
			if ($pickup_date1 != '' && $pickup_date2 != '')
			{
				$daterang = date('F d, Y', strtotime($pickup_date1)) . " - " . date('F d, Y', strtotime($pickup_date2));
			}
			?>
			<div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
				<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
			</div>
		</div>
		<?= $form->hiddenField($model, 'bkg_pickup_date1'); ?>
		<?= $form->hiddenField($model, 'bkg_pickup_date2'); ?>
	</div>
	<div class="col-xs-6 col-sm-4 col-md-4" style="">
		<div class="form-group">
			<label class="control-label">Create (Date Range)</label>
			<?php
			$daterang	 = "Select Date Range";
			$create_date1	 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
			$create_date2	 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
			if ($create_date1 != '' && $create_date2 != '')
			{
				$daterang = date('F d, Y', strtotime($create_date1)) . " - " . date('F d, Y', strtotime($create_date2));
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


	<div class="col-xs-4 col-sm-4 col-md-4" style="">
		<div class="form-group">
			<label class="control-label">Assign ( Date Range )</label>
			<?php
			$daterang	 = "Select Date Range";
			$assignedDate1	 = ($model->bkg_assigned_date1 == '') ? '' : $model->bkg_assigned_date1;
			$assignedDate2	 = ($model->bkg_assigned_date2 == '') ? '' : $model->bkg_assigned_date2;
			if ($assignedDate1 != '' && $assignedDate2 != '')
			{
				$daterang = date('F d, Y', strtotime($assignedDate1)) . " - " . date('F d, Y', strtotime($assignedDate2));
			}
			?>
			<div id="bkgAssignDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
				<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
			</div>
		</div>
		<?= $form->hiddenField($model, 'bkg_assigned_date1'); ?>
		<?= $form->hiddenField($model, 'bkg_assigned_date2'); ?>

	</div>
	

	<div class="col-xs-3 col-sm-3 col-md-3" >
		<label class="control-label">Booking Type</label><br>
		<?php
		$bookingTypesArr	 = $model->booking_type;
		$bookingTypesArr[2]	 = 'Round Trip';
		$bookingTypesArr[3]	 = 'Multi City';
		$bookingTypesArr[12] = 'Airport Package';
		asort($bookingTypesArr);
		$this->widget('booster.widgets.TbSelect2', array(
			'model'			 => $model,
			'attribute'		 => 'bkgtypes',
			'val'			 => $bkgtypes,
			'data'			 => $bookingTypesArr,
			'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
				'placeholder'	 => 'Booking Type')
		));
		?>

	</div> 

	<div class="col-xs-3 col-sm-3 col-md-3" >
		<label class="control-label">Regions</label><br>

		<?php
		$this->widget('booster.widgets.TbSelect2', array(
			'model'			 => $model,
			'attribute'		 => 'bkg_region',
			'val'			 => $regions,
			'data'			 => States::findRegionName(),
			'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
				'style'			 => 'width: 100%', 'placeholder'	 => 'Region')
		));
		?>
	</div> 


	<div class="col-xs-3 col-sm-3 col-md-3" >
		<label class="control-label">Service Tier</label><br>

		<?php
		$returnType			 = "filter";
		$serviceClassList	 = ServiceClass::model()->getList($returnType);
		$this->widget('booster.widgets.TbSelect2', array(
			'model'			 => $model,
			'attribute'		 => 'bkg_service_class',
			'val'			 => $serviceClass,
			'data'			 => $serviceClassList,
			'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
				'placeholder'	 => 'Select Service Class')
		));
		?>
	</div> 

	



	<div class="col-xs-12 col-sm-2 col-md-2">   
		<label class="control-label"></label>
		<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width submitCbr')); ?>
	</div>




</div>


<?php $this->endWidget(); ?>

<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 mt5">
	<?php
	$checkExportAccess	 = false;
	if ($roles['rpt_export_roles'] != null)
	{
		$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
	}
	if ($checkExportAccess)
	{
		echo CHtml::beginForm(Yii::app()->createUrl('report/booking/assignment'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
	?>
		<input type="hidden" id="create_date1" name="create_date1" value="<?= $model->bkg_create_date1; ?>"/>
		<input type="hidden" id="create_date2" name="create_date2" value="<?= $model->bkg_create_date2; ?>"/>

		<input type="hidden" id="pickup_date1" name="pickup_date1" value="<?= $model->bkg_pickup_date1; ?>"/>
		<input type="hidden" id="pickup_date2" name="pickup_date2" value="<?= $model->bkg_pickup_date2; ?>"/>

		<input type="hidden" id="assigned_date1" name="assigned_date1" value="<?= $model->bkg_assigned_date1; ?>"/>
		<input type="hidden" id="assigned_date2" name="assigned_date2" value="<?= $model->bkg_assigned_date2; ?>"/>

		<input type="hidden" id="bkg_booking_type" name="bkg_booking_type" value="<?= $model->bkg_booking_type; ?>"/>
		<input type="hidden" id="bkg_region" name="bkg_region" value="<?= $model->bkg_region; ?>"/>
		<input type="hidden" id="bkg_service_class" name="bkg_service_class" value="<?= $model->bkg_service_class; ?>"/>

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
					array('name'	 => 'trip_id', 'value'	 => function ($data) {

							echo ($data['trip_id']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Trip Id'),
					array('name'	 => 'booking_ids', 'value'	 => function ($data) {

							//echo ($data['booking_ids']);
							echo CHtml::link($data["booking_ids"], Yii::app()->createUrl("admin/booking/view", ["id" => $data['booking_ids']]), ["class" => "", "target" => "_blank"]);

						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Booking Id'),
					array('name'	 => 'create_date', 'value'	 => function ($data) {

							echo ($data['create_date']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Create Date'),
					array('name'	 => 'pickup_date', 'value'	 => function ($data) {

							echo ($data['pickup_date']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Pickup Date'),
					array('name'	 => 'bid_start_date', 'value'	 => function ($data) {

							echo ($data['bid_start_date']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Bid Start Date'),
					array('name'	 => 'Driver_Cab_Assigned_Date', 'value'	 => function ($data) {

							echo ($data['Driver_Cab_Assigned_Date']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Driver-Cab Assigned Date'),
					array('name'	 => 'Vendor_Assigned_Date', 'value'	 => function ($data) {

							echo ($data['Vendor_Assigned_Date']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Vendor Assigned Date'),
					array('name'	 => 'manual_assign_date', 'value'	 => function ($data) {

							echo ($data['manual_assign_date']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Manual Assigned Date'),
					array('name'	 => 'critical_assign_date', 'value'	 => function ($data) {

							echo ($data['critical_assign_date']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Critical Assigned Date'),
					array('name'	 => 'demSup_misfire', 'value'	 => function ($data) {

							echo ($data['demSup_misfire']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'DemSup Misfire'),
					array('name'	 => 'reconfirm', 'value'	 => function ($data) {

							echo ($data['reconfirm']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Reconfirm'),
					array('name'	 => 'booking_vendor_amount', 'value'	 => function ($data) {

							echo "<i class=\"fa fa-inr\"></i>" . ($data['booking_vendor_amount']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Booking Vendor Amount'),
					array('name'	 => 'trip_vendor_amount', 'value'	 => function ($data) {

							echo "<i class=\"fa fa-inr\"></i>" . ($data['trip_vendor_amount']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Trip Vendor Amount'),
					array('name'	 => 'booking_advanced_amount', 'value'	 => function ($data) {

							echo "<i class=\"fa fa-inr\"></i>" . ($data['booking_advanced_amount']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Booking Advanced Amount'),
					array('name'	 => 'booking_total_amount', 'value'	 => function ($data) {

							echo "<i class=\"fa fa-inr\"></i>" . ($data['booking_total_amount']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Booking Total Amount'),
					array('name'	 => 'avg_bid_amount', 'value'	 => function ($data) {

							echo "<i class=\"fa fa-inr\"></i>" . round($data['avg_bid_amount']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Avg Bid Amount'),
					array('name'	 => 'max_bid_amount', 'value'	 => function ($data) {

							echo "<i class=\"fa fa-inr\"></i>" . ($data['max_bid_amount']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Max Bid Amount'),
					array('name'	 => 'min_bid_amount', 'value'	 => function ($data) {

							echo "<i class=\"fa fa-inr\"></i>" . ($data['min_bid_amount']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Mid Bid Amount'),
					array('name'	 => 'bid_count', 'value'	 => function ($data) {

							echo ($data['bid_count']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Bid Count'),
					array('name'	 => 'scv_label', 'value'	 => function ($data) {

							echo ($data['scv_label']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Scv level'),
					array('name'	 => 'assigned_mode', 'value'	 => function ($data) {

							echo ($data['assigned_mode']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Assigned Mode'),
					array('name'	 => 'bkg_booking_type', 'value'	 => function ($data) {

							echo Booking::model()->getBookingType($data['bkg_booking_type']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Booking Type'),
					array('name'	 => 'stt_zone', 'value'	 => function ($data) {

							echo States::findUniqueZone($data['stt_zone']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'State Zone'),
			)));
		}
		?>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {

		var createStart = '<?= ($model->bkg_create_date1 == '') ? date('d/m/Y', strtotime("-1 month", time())) : date('d/m/Y', strtotime($model->bkg_create_date1)); ?>';
        var createEnd = '<?= ($model->bkg_create_date2 == '') ? date('d/m/Y') : date('d/m/Y', strtotime($model->bkg_create_date2)); ?>';
		
		var pickupStart = '<?= ($model->bkg_pickup_date1 == '') ? date('d/m/Y', strtotime("-1 month", time())) : date('d/m/Y', strtotime($model->bkg_pickup_date1)); ?>';
        var pickupEnd = '<?= ($model->bkg_pickup_date2 == '') ? date('d/m/Y') : date('d/m/Y', strtotime($model->bkg_pickup_date2)); ?>';

		var assignStart = '<?= ($model->bkg_assigned_date1 == '') ? date('d/m/Y', strtotime("-1 month", time())) : date('d/m/Y', strtotime($model->bkg_assigned_date1)); ?>';
        var assignEnd = '<?= ($model->bkg_assigned_date2 == '') ? date('d/m/Y') : date('d/m/Y', strtotime($model->bkg_assigned_date2)); ?>';


        $('#bkgCreateDate').daterangepicker(
        {
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    },
                    "showDropdowns": true,
                    "alwaysShowCalendars": true,
                    startDate: createStart,
                    endDate: createEnd,
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
            $('#bkgCreateDate span').html('Select Create Date Range');
            $('#Booking_bkg_create_date1').val('');
            $('#Booking_bkg_create_date2').val('');
        });
		

		$('#bkgPickupDate').daterangepicker(
		{
				locale: {
					format: 'DD/MM/YYYY',
					cancelLabel: 'Clear'
				},
				"showDropdowns": true,
				"alwaysShowCalendars": true,
				startDate: pickupStart,
				endDate: pickupEnd,
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				}
			}, function (start1, end1) {
			$('#Booking_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
			$('#Booking_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
			$('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
		});
		$('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
			$('#bkgPickupDate span').html('Select Pickup Date Range');
			$('#Booking_bkg_pickup_date1').val('');
			$('#Booking_bkg_pickup_date2').val('');
		});
		
		
		$('#bkgAssignDate').daterangepicker(
        {
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    },
                    "showDropdowns": true,
                    "alwaysShowCalendars": true,
                    startDate: assignStart,
                    endDate: assignEnd,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    }
                }, function (start1, end1) {
            $('#Booking_bkg_assigned_date1').val(start1.format('YYYY-MM-DD'));
            $('#Booking_bkg_assigned_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgAssignDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgAssignDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgAssignDate span').html('Select  Date Range');
            $('#Booking_bkg_assigned_date1').val('');
            $('#Booking_bkg_assigned_date2').val('');
        });

    });

</script>