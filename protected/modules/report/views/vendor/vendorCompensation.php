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
			'id'					 => 'vndCompensation', 'enableClientValidation' => true,
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
			<div class="col-xs-8 col-sm-8  col-md-4 col-lg-3 mb15" style="">
				<div class="form-group">
					<label class="control-label">Pickup Date</label>
					<?php
					$daterang			 = "Select Pickup Date Range";
					$fromDate			 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
					$toDate				 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
					if ($fromDate != '' && $toDate != '')
					{
						$daterang = date('F d, Y', strtotime($fromDate)) . " - " . date('F d, Y', strtotime($toDate));
					}
					?>
					<div id="bkgCreateDate" class="col-md-3" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?php echo $daterang ?></span> <b class="caret"></b>
					</div>
					<?php echo $form->hiddenField($model, 'bkg_pickup_date1'); ?>
					<?php echo $form->hiddenField($model, 'bkg_pickup_date2'); ?>

				</div>
			</div>

			<div class="col-xs-8 col-sm-8  col-md-4 col-lg-3 mb15" style="">
				<div class="form-group">
					<label class="control-label">Compensation Date</label>
					<?php
					$daterang				 = "Select Compensation Date Range";
					$fromCompensationDate	 = ($model->compensationdate1 == '') ? '' : $model->compensationdate1;
					$toCompensationDate		 = ($model->compensationdate2 == '') ? '' : $model->compensationdate2;
					if ($fromCompensationDate != '' && $toCompensationDate != '')
					{
						$daterang = date('F d, Y', strtotime($fromCompensationDate)) . " - " . date('F d, Y', strtotime($toCompensationDate));
					}
					?>
					<div id="bkgCompensationDate" class="col-md-3" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?php echo $daterang ?></span> <b class="caret"></b>
					</div>
					<?php echo $form->hiddenField($model, 'compensationdate1'); ?>
					<?php echo $form->hiddenField($model, 'compensationdate2'); ?>

				</div>
			</div>

			<div class="col-xs-12 col-sm-2 col-md-2">
				<?= $form->textFieldGroup($model, 'bkg_booking_id', array('label' => 'Booking Id', 'htmlOptions' => array('placeholder' => 'Search By Booking Id'))) ?>
			</div>
			<div class="col-xs-12 col-sm-3">
				<div class="form-group">
					<label class="control-label">Vendors</label>
					<?php
					$data = Vendors::model()->getJSONAllVendorsbyQuery('', '', '1');

					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'bcb_vendor_id',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Vendor",
						'fullWidth'			 => false,
						'options'			 => array('allowClear' => true),
						'htmlOptions'		 => array('width' => '100%',
						//  'id' => 'from_city_id1'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                  populateVendor(this, '{$model->bcb_vendor_id}');
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
                        }", 'allowClear'	 => true
						),
					));
					?>						
				</div> 
            </div>
			<div class="col-xs-12 col-sm-4 col-md-2"> 
				<div class="form-group">
					<label class="control-label">Booking Status</label>
					<?php
					$bookingStatusArr	 = Booking::model()->getBookingStatus();
					unset($bookingStatusArr[1], $bookingStatusArr[15], $bookingStatusArr[13], $bookingStatusArr[8], $bookingStatusArr[4], $bookingStatusArr[10], $bookingStatusArr[11], $bookingStatusArr[12], $bookingStatusArr[13]);
					$datainfo			 = VehicleTypes::model()->getJSON($bookingStatusArr);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'bkg_status',
						'val'			 => explode(",", $model->bkg_status),
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($datainfo), 'allowClear' => true, 'multiple' => true),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Status', 'multiple' => 'multiple')
					));
					?>
				</div> 
			</div>
			<div class="  col-xs-6 col-sm-6 col-md-2 col-lg-1 mt20"  >
				<button class="btn btn-primary" type="submit" style="width: 125px;" >Search</button> 
			</div>
		</div>	
		<?php
		$this->endWidget();
		$checkExportAccess	 = false;
		if ($roles['rpt_export_roles'] != null)
		{
			$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
		}
		if ($checkExportAccess)
		{
			echo CHtml::beginForm(Yii::app()->createUrl('report/vendor/Compensation'), "post", []);
			?>
			<input type="hidden" id="export" name="export" value="true"/>
			<input type="hidden" id="bkg_pickup_date1" name="bkg_pickup_date1" value="<?php echo $model->bkg_pickup_date1; ?>"/>
			<input type="hidden" id="bkg_pickup_date2" name="bkg_pickup_date2" value="<?php echo $model->bkg_pickup_date2; ?>"/>
			<input type="hidden" id="compensationdate1" name="compensationdate1" value="<?php echo $model->compensationdate1; ?>"/>
			<input type="hidden" id="compensationdate2" name="compensationdate2" value="<?php echo $model->compensationdate2; ?>"/>
			<input type="hidden" id="bkg_booking_id" name="bkg_booking_id" value="<?php echo $model->bkg_booking_id; ?>"/>
			<input type="hidden" id="bkg_status" name="bkg_status" value="<?php echo $model->bkg_status; ?>"/>
			<input type="hidden" id="bcb_vendor_id" name="bcb_vendor_id" value="<?php echo $model->bcb_vendor_id; ?>"/>
			<button class="btn btn-default btn-5x pr30 pl30 mt20" type="submit" style="width: 185px;">Export</button>
			<?php echo CHtml::endForm(); ?>	
		<?php } ?>
	</div>
	<?php
		if ($error != '')
		{
			echo
			"<span class='text-danger'> $error</span>";
		}
	?>
</div>
<div class="row">
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			Logger::profile("Starting GridView");
			$this->widget('booster.widgets.TbExtendedGridView', array(
				'responsiveTable'	 => true,
				'fixedHeader'		 => true,
				'headerOffset'		 => 110,
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
				'itemsCssClass'		 => 'table items table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name'	 => 'bookingId', 'value'	 => function ($data) {
							if ($data['bookingId'] != '')
							{
								echo CHtml::link($data['bookingId'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkgId']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Booking Id'),
					array('name' => 'bkg_pickup_date', 'value' => '$data[bkg_pickup_date]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Pickup Date'),
					array('name' => 'btr_cancel_date', 'value' => '$data[btr_cancel_date]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Cancel Date'),
					array('name' => 'vndCompensationDate', 'value' => '$data[vndCompensationDate]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Compensation Date'),
					array('name' => 'vnd_name', 'value' => '$data[vnd_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-left'), 'header' => 'Vendor Name'),
					array('name' => 'bcb_vendor_amount', 'value' => '$data[bcb_vendor_amount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Vendor Amount'),
					array('name' => 'bkg_cancel_charge', 'value' => '$data[cancelcharge]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Cancellation Charge'),
					array('name' => 'vndCompensation', 'value' => '$data[vndCompensation]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Vendor Compensation'),
					array('name'	 => 'bkg_status', 'value'	 => function ($data) {
							echo Booking::model()->getActiveBookingStatus($data['bkg_status']);
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Booking Status'),
					array('name'	 => 'compensationCond', 'value'	 => function ($data) {
							$model	 = Booking::model()->findByPk($data['bkgId']);
							$remarks = '';
							if ($model->bkgBcb->bcb_trip_type != 1)
							{
								$getCustomerCancellationAmt = AccountTransactions::getCancellationCharge($model->bkg_id);
								if ($getCustomerCancellationAmt > 0)
								{
									$arr	 = BookingInvoice::calculateVendorCompensation($model);
									$remarks = $arr['remarks'];
								}
							}
							echo $remarks;
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Compensation Condition'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{remove}',
						'buttons'			 => array(
							'remove'		 => array(
								'click'		 => 'function(){
                                                            var con = confirm("Are you sure you want to remove this commission?"); 
                                                              if(con){
                                                                    $href = $(this).attr(\'href\');
                                                                    $.ajax({
                                                                        url: $href,
                                                                        dataType: "json",
                                                                        success: function(result){
                                                                                if(result.success){
                                                                                        alert(result.message);
                                                                                        location.reload();
                                                                                }else{
                                                                                        alert(result.message);
                                                                                }

                                                                        },
                                                                        error: function(xhr, status, error){
                                                                                alert(\'Sorry error occured\');
                                                                        }
                                                                    });
                                                                    }
                                                                    return false;
                                                    }',
								'url'		 => 'Yii::app()->createUrl("admin/booking/RemoveVendorCompensation", array(\'bkgid\' => $data[bkgId]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\delete_booking.png',
								'label'		 => '<i class="fa fa-type"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Remove'),
							),
							'htmlOptions'	 => array('class' => 'center'),
						))
			)));
		}
		Logger::profile("Ending GridView");
		?>
    </div>
</div>
<script >
    var start = '<?php echo date('d/m/Y', strtotime('-2 day')); ?>';
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
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 2 Days': [moment().subtract(1, 'days'), moment()],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'Last 10 week': [moment().subtract(10, 'weeks').startOf('isoWeek'), moment()],
                    'This Month': [moment().startOf('month'), moment()],
                    'Last Month To Date': [moment().subtract(1, 'month').startOf('month'), moment()],
                    'Last 5 month': [moment().subtract(5, 'month').startOf('month'), moment()]
                }
            }, function (start1, end1)
    {
        $('#Booking_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
        $('#Booking_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
        $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });

    $('#bkgCompensationDate').daterangepicker(
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
                    'Last 2 Days': [moment().subtract(1, 'days'), moment()],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'Last 10 week': [moment().subtract(10, 'weeks').startOf('isoWeek'), moment()],
                    'This Month': [moment().startOf('month'), moment()],
                    'Last Month To Date': [moment().subtract(1, 'month').startOf('month'), moment()],
                    'Last 5 month': [moment().subtract(5, 'month').startOf('month'), moment()]
                }
            }, function (start1, end1)
    {
        $('#Booking_compensationdate1').val(start1.format('YYYY-MM-DD'));
        $('#Booking_compensationdate2').val(end1.format('YYYY-MM-DD'));
        $('#bkgCompensationDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });


    $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker)
    {
        $('#bkgCreateDate span').html('Select Create Date Range');
        $('#Booking_bkg_pickup_date1').val('');
        $('#Booking_bkg_pickup_date2').val('');
    });

    $('#bkgCompensationDate').on('cancel.daterangepicker', function (ev, picker)
    {
        $('#bkgCompensationDate span').html('Select Compensation Date Range');
        $('#Booking_compensationdate1').val('');
        $('#Booking_compensationdate2').val('');
    });

    $('#getassignments').submit(function (event)
    {

        var fromDate = new Date($('#Booking_create_from_date').val());
        var toDate = new Date($('#Booking_create_to_date').val());

        var diffTime = Math.abs(fromDate - toDate);
        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        if (diffDays > 92)
        {
            alert("Date range should not be greater than 90 days");
            return false;
        }
    });

</script>