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
					$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'blockedVnd-form', 'enableClientValidation' => true,
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


					<div class="col-xs-12 col-sm-4 col-md-4" style="">
                        <div class="form-group">
                            <label class="control-label">Pickup Date</label>
							<?php
							$daterang		 = "Select Date Range";
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
							<?= $form->hiddenField($model, 'bkg_pickup_date1'); ?>
							<?= $form->hiddenField($model, 'bkg_pickup_date2'); ?>

                        </div></div>
					<div class="col-xs-12 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label class="control-label">Vendor Name</label>
							<?php
							$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
								'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
								'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
								'openOnFocus'		 => true, 'preload'			 => false,
								'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
								'addPrecedence'		 => false,];
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'vendor_name',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Vendor",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%'),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                  populateVendor(this, '{$model->vendor_name}');
                                }",
							'load'			 => "js:function(query, callback){
                                loadVendor(query, callback);
                                }",
							'render'		 => "js:{
                                option: function(item, escape){
                                return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                                },
                                option_create: function(data, escape){
                                return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                }
                                }",
								),
							));
							?>

                        </div></div>
                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
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
							array('name' => 'bkg_booking_id', 'value' => function ($data) {
							echo CHtml::link($data["bkg_booking_id"], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "", 'target' => '_blank']);
							}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Booking Id'),
							array('name' => 'bkg_pickup_date', 'value' => '$data[bkg_pickup_date]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Pickup Date'),
							
							array('name' => 'disabled_by', 'value' => function ($data) {
								$eventId = BookingLog::DRIVER_APP_USAGE;
								$checkUser = BookingLog::getDataByEventId($eventId,$data['bkg_id']);
								$admDetails = Admins::getById($checkUser);
								echo $admDetails['gozen'];
							}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Disabled By'),


//							array('name' => 'adminName', 'value' => '$data[adminName]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Created By'),
							array('name' => 'vnd_name', 'value' => '$data[vnd_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Vendor Name'),
							array('name' => 'drv_name', 'value' => '$data[drv_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Driver Name'),
							array('name' => 'drs_total_trips', 'value' => '$data[drs_total_trips]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Total Trips'),
							array('name' => 'drs_last_logged_in', 'value' => '$data[drs_last_logged_in]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Last Date App Usage'),
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
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Last 15 Days': [moment().subtract(15, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				}
			}, function (start1, end1) {
		$('#BookingLog_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
		$('#BookingLog_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
		$('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
		$('#bkgPickupDate span').html('Select Date Range');
		$('#BookingLog_bkg_pickup_date1').val('');
		$('#BookingLog_bkg_pickup_date12').val('');
	});
</script>