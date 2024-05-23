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
	
	<div class="col-xs-4 col-sm-4 col-md-4" style="">
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

	<div class="col-xs-4 col-sm-2 col-md-2">   
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
		echo CHtml::beginForm(Yii::app()->createUrl('report/agent/activeChannelPartner'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
	?>
		<input type="hidden" id="create_date1" name="create_date1" value="<?= $model->bkg_create_date1; ?>"/>
		<input type="hidden" id="create_date2" name="create_date2" value="<?= $model->bkg_create_date2; ?>"/>
		<input type="hidden" id="pickup_date1" name="pickup_date1" value="<?= $model->bkg_pickup_date1; ?>"/>
		<input type="hidden" id="pickup_date2" name="pickup_date2" value="<?= $model->bkg_pickup_date2; ?>"/>

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
					array('name'	 => 'ChannelPartner', 'value'	 => function ($data) {

							echo CHtml::link($data["ChannelPartner"], Yii::app()->createUrl("admin/agent/bookinghistory", ["agent" => $data['bkg_agent_id']]), ["class" => "", "target" => "_blank"]);

						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Channel Partner'),
					array('name'	 => 'Total_Gozo_Amount', 'value'	 => function ($data) {

							echo ($data['Total_Gozo_Amount']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Total Gozo Amount'),	
					array('name'	 => 'Completed_Cnt', 'value'	 => function ($data) {

							echo ($data['Completed_Cnt']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Completed Count'),
					array('name'	 => 'CancelledCnt', 'value'	 => function ($data) {

							echo ($data['CancelledCnt']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Cancelled Count'),
					array('name'	 => 'Gozo_Account_Manager', 'value'	 => function ($data) 
					{

							echo ($data['Gozo_Account_Manager']!='') ? $data['Gozo_Account_Manager'] : $data['Agent_Name'];
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Gozo Account Manager'),
					array('name'	 => 'Last_Booking_Received_On', 'value'	 => function ($data) {

							echo ($data['Last_Booking_Received_On']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Last Booking Received'),
					array('name'	 => 'Months_Since_Last_Booking', 'value'	 => function ($data) {

							echo ($data['Months_Since_Last_Booking']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Months Since Last Booking'),


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
		
		
	

    });

</script>