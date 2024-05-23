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
			<label class="control-label">Create (Date Range)</label>
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
		echo CHtml::beginForm(Yii::app()->createUrl('report/booking/cancellationList'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
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
					array('name'	 => 'bkg_id', 'value'	 => function ($data) 
						{
							echo CHtml::link($data['bkg_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]));

						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Bkg Id'),
					array('name'	 => 'UserName', 'value'	 => function ($data) 
						{

							echo ($data['bkg_user_fname'] . " " . $data['bkg_user_lname']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'User Name'),
					array('name'	 => 'bkg_booking_type', 'value'	 => function ($data) 
						{

							echo Booking::model()->getBookingType($data['bkg_booking_type']);

						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Booking Type'),

					array('name'	 => 'stt_zone', 'value'	 => function ($data) 
						{

							echo States::findRegionName($data['stt_zone']);

						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Region'),
	
					array('name'	 => 'bkg_create_date', 'value'	 => function ($data) 
						{

							echo ($data['bkg_create_date']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Create Date'),	
					array('name'	 => 'bkg_pickup_date', 'value'	 => function ($data) 
						{

							echo ($data['bkg_pickup_date']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Pickup Date'),	
					array('name'	 => 'Booking_Route', 'value'	 => function ($data) 
						{
							
							echo ($data['from_cty_name'] . " -> " . $data['to_cty_name']);

						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Booking Route'),	
					array('name'	 => 'bkg_trip_arrive_time', 'value'	 => function ($data) 
						{

							echo ($data['Arrival_Time']);

						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Arrival Time'),	
					array('name'	 => 'btr_cancel_date', 'value'	 => function ($data) 
						{

							echo ($data['Cancel_Date']);

						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Cancel Date'),	
					array('name'	 => 'bkg_total_amount', 'value'	 => function ($data) 
						{

							echo "<i class=\"fa fa-inr\"></i>" . ($data['TotalAmount']);

						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Total Amount'),	
					array('name'	 => 'DeleteReason', 'value'	 => function ($data) 
						{

							echo ($data['DeleteReason']);

						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Delete Reason'),	
					array('name'	 => 'CancelReason', 'value'	 => function ($data) 
						{

							echo ($data['CancelReason']);

						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Cancel Reason'),	
					array('name'	 => 'Refund_Amount', 'value'	 => function ($data) 
						{

							echo "<i class=\"fa fa-inr\"></i>" . ($data['Refund_Amount']);

						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Refund Amount'),

					array('name'	 => 'Cancel_By', 'value'	 => function ($data) 
						{

							echo ($data['Cancel_By']);

						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Cancel By'),
					
					array('name'	 => 'Cancel_Charge', 'value'	 => function ($data) 
						{

							if($data['Cancel_Charge'] > 0)
							{
								echo "<i class=\"fa fa-inr\"></i>" . $data['Cancel_Charge'];
							}
							else
							{
								echo "";
							}
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Cancel Charge'),	

					array('name'				 => 'is_dbo',
						  'value'				 => function ($data)
							{
								if($data['is_dbo'] > 0) 
								{
									echo "ON";
								}
								else
								{
									echo "OFF";
								}
							},
							'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'DBO Status'),	
						
	
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