<style>
    .panel-body{
        padding-top: 0 ;
        padding-bottom: 0;
    }
    .table>tbody>tr>th
    {
        vertical-align: middle
    }

    .table>tbody>tr>td, .table>tbody>tr>th{
        padding: 7px;
        line-height: 1.5em;
    }
</style>
<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
				<div class="row"> 
					<?php
					$zoneList	 = CHtml::listData(Zones::model()->findAll('zon_active = :act', array(':act' => '1')), 'zon_id', 'zon_name');
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'otpreport-form', 'enableClientValidation' => true,
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
					// @var $form TbActiveForm 
					?>

					<div class="col-xs-6 col-sm-4 col-lg-2">
						<div class="form-group cityinput">
							
							      <?php
									$dataZone	 = Zones::model()->getJSON($zoneList);
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $modelPref,
										'attribute'		 => 'vnp_home_zone',
										'val'			 => $modelPref->vnp_home_zone,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($dataZone), 'allowClear' => true),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Search By Home Zone')
									));
									?>
								</div>			
						</div>
						<div class="col-xs-6 col-sm-4 col-lg-2">
							<?= $form->textFieldGroup($model, 'vnd_phone', array('label' => '', 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Search By Phone']])) ?> 
						</div>
					
					
                    <div class="col-xs-6 col-sm-4 col-lg-2">   
						<?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary')); ?>
					</div>
						<?php $this->endWidget(); ?>
                </div>
                <?php
				if (!empty($dataProvider))
				{
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						//    'ajaxType' => 'POST',
						'columns'			 => array(
							array('name'				 => 'vnd_name', 'value'				 => '$data[vnd_name]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Vendor Name'),
							array('name'				 => 'phn_phone_no', 'value'				 => '$data[phn_phone_no]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Phone No.'),
							array('name'				 => 'home_zone', 'value'				 => '$data[home_zone]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Vendor Home Zone'),
							array('name'				 => 'last_login', 'value'				 => 'DateTimeFormat::DateTimeToLocale($data[last_login])', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Last Logged In'),
							array('name'				 => 'last_bidding_date', 'value'				 =>function ($data)
								{
									if ($data['last_bidding_date'] != NULL)
									{
										echo  DateTimeFormat::DateTimeToLocale($data['last_bidding_date']);
									}
									else
									{
										echo 'NA';	
									}
								}, 
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Last Bidding Date'),
							array('name'				 => 'last_booking_completed',
								'value'				 =>function ($data)
								{
									if ($data['last_booking_completed'] != NULL)
									{
										echo  DateTimeFormat::DateTimeToLocale($data['last_booking_completed']);
									}
									else
									{
										echo 'NA';	
									}
								}, 
								'sortable' => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'	=> 'Last Booking Completed On'),
							
					)));
				}
				
				?> 
            </div>  

        </div>  
    </div>
</div>


<script type="text/javascript">

	var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
	var end = '<?= date('d/m/Y'); ?>';


	$('#OtpSearchDate').daterangepicker(
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
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					'Last 2 Month': [moment().subtract(2, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					'Last 6 Month': [moment().subtract(6, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					'Last 12 Month': [moment().subtract(12, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				}
			}, function (start1, end1) {
		$('#BookingTrack_fromDate').val(start1.format('YYYY-MM-DD'));
		$('#BookingTrack_toDate').val(end1.format('YYYY-MM-DD'));
		$('#OtpSearchDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#OtpSearchDate').on('cancel.daterangepicker', function (ev, picker) {
		$('#OtpSearchDate span').html('Select Booking Date Range');
		$('#BookingTrack_fromDate').val('');
		$('#BookingTrack_toDate').val('');
	});

</script>


