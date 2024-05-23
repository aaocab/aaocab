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
	<div class="col-xs-4 col-sm-3 col-md-3" style="">
		<div class="form-group">
			<label class="control-label">Create Date Range</label>
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
	<div class="col-xs-4 col-sm-3 col-md-3" style="">
		<div class="form-group">
			<label class="control-label">Pickup Date Range</label>
			<?php
			$daterang1	 = "Select Date Range";
			$from_date1	 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
			$to_date1	 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
			if ($from_date1 != '' && $to_date1 != '')
			{
				$daterang1 = date('F d, Y', strtotime($from_date1)) . " - " . date('F d, Y', strtotime($to_date1));
			}
			?>
			<div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
				<span style="min-width: 240px"><?= $daterang1 ?></span> <b class="caret"></b>
			</div>

		</div>
		<?= $form->hiddenField($model, 'bkg_pickup_date1'); ?>
		<?= $form->hiddenField($model, 'bkg_pickup_date2'); ?>

	</div>
	<div class="col-xs-12 col-sm-2 col-md-2">   
		<label class="control-label"></label>
		<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width submitCbr')); ?>
	</div>
</div>


<?php $this->endWidget(); ?>

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
					array('name'	 => 'bkg_id', 'value'	 => function ($data) {
						echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["target" => "_blank"]);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Booking Id'),
					
				array('name'	 => 'bkg_create_date', 'value'	 => function ($data) 
					{
					echo date("d/M/y", strtotime($data[bkg_create_date])) . " " . date("h:i A", strtotime($data[bkg_create_date]));
					}
					, 'sortable'			 => true,
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
					'htmlOptions'		 => array('class' => 'text-center'),
					'header'			 => 'Create Date'),	
				array('name'	 => 'bkg_pickup_date', 'value'	 => function ($data) {

					echo date("d/M/y", strtotime($data[bkg_pickup_date])) . " " . date("h:i A", strtotime($data[bkg_pickup_date]));
						
					}, 'sortable'			 => true,
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
					'htmlOptions'		 => array('class' => 'text-center'),
					'header'			 => 'Pickup Date'),	
				array('name'	 => 'bkg_from_city_id', 'value'	 => function ($data) {

						
							echo ($data['ctyFrm']);
						
					}, 'sortable'			 => true,
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
					'htmlOptions'		 => array('class' => 'text-center'),
					'header'			 => 'From City'),	
				array('name'	 => 'bkg_to_city_id', 'value'	 => function ($data) {

						
							echo ($data['ctyTo']);
						
					}, 'sortable'			 => true,
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
					'htmlOptions'		 => array('class' => 'text-center'),
					'header'			 => 'To City'),	
				array('name'	 => 'bkg_user_fname', 'value'	 => function ($data) {

						
							echo ($data['bkg_user_fname'] ." " .$data['bkg_user_lname']);
						
					}, 'sortable'			 => true,
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
					'htmlOptions'		 => array('class' => 'text-center'),
					'header'			 => 'User Name'),	
				array('name'	 => 'bkg_contact_no', 'value'	 => function ($data) {

						
							echo ($data['bkg_contact_no']);
						
					}, 'sortable'			 => true,
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
					'htmlOptions'		 => array('class' => 'text-center'),
					'header'			 => 'User Phone'),	
				array('name'	 => 'bkg_user_email', 'value'	 => function ($data) {

						
							echo ($data['bkg_user_email']);
						
					}, 'sortable'			 => true,
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
					'htmlOptions'		 => array('class' => 'text-center'),
					'header'			 => 'User Email'),	
	
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
            $('#bkgCreateDate span').html('Select Create Date Range');
            $('#Booking_bkg_create_date1').val('');
            $('#Booking_bkg_create_date2').val('');
        });
		
		
		var start2 = '<?= ($model->bkg_pickup_date1 == '') ? date('d/m/Y', strtotime("-1 month", time())) : date('d/m/Y', strtotime($model->bkg_pickup_date1)); ?>';
        var end2 = '<?= ($model->bkg_pickup_date2 == '') ? date('d/m/Y') : date('d/m/Y', strtotime($model->bkg_pickup_date2)); ?>';

		$('#bkgPickupDate').daterangepicker(
                {
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    },
                    "showDropdowns": true,
                    "alwaysShowCalendars": true,
                    startDate: start2,
                    endDate: end2,
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


