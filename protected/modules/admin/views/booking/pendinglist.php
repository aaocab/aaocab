<div class="row">
    <div class="col-xs-12">
		<?php
		//$stateList = CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'sms-form', 'enableClientValidation' => true,
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
        <div class="well pb20">
			<? $cls		 = "col-xs-6 col-sm-4 col-md-3 col-lg-2"; ?>
            <div class="row">
                <div class="col-xs-6 col-sm-4 col-lg-3">
					<?
					//$daterang = date('F d, Y') . " - " . date('F d, Y');
					$daterang	 = "Select Booking Date Range";
					$createdate1 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
					$createdate2 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
					if ($createdate1 != '' && $createdate2 != '')
					{
						$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
					}
					?>
					<label  class="control-label">Booking Date/Time</label>
					<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span><?= $daterang ?></span> <b class="caret"></b>
					</div>
					<?
					echo $form->hiddenField($model, 'bkg_create_date1');
					echo $form->hiddenField($model, 'bkg_create_date2');
					?>
				</div>
				<div class="col-xs-6 col-sm-4 col-lg-3">
					<?
					//$daterang = date('F d, Y') . " - " . date('F d, Y');
					$daterang	 = "Select Pickup Date Range";
					$pickupDate1 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
					$pickupDate2 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
					if ($pickupDate1 != '' && $pickupDate2 != '')
					{
						$daterang = date('F d, Y', strtotime($pickupDate1)) . " - " . date('F d, Y', strtotime($pickupDate2));
					}
					?>
					<label  class="control-label">Pickup Date/Time</label>
					<div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span><?= $daterang ?></span> <b class="caret"></b>
					</div>
					<?
					echo $form->hiddenField($model, 'bkg_pickup_date1');
					echo $form->hiddenField($model, 'bkg_pickup_date2');
					?>
				</div>
				<div class="<?= $cls ?>"> 
					<?= $form->textFieldGroup($model, 'bkg_bcb_id', array('widgetOptions' => ['htmlOptions' => []])) ?>
				</div>
				<div class="<?= $cls ?>"> 
					<?= $form->textFieldGroup($model, 'bkg_booking_id', array('widgetOptions' => ['htmlOptions' => []])) ?>
				</div>
            </div>
			<div class="row">
				<div class="<?= $cls ?> text-center mt20 pt5">
					<button class="btn btn-primary" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>
				</div>
			</div>
        </div>


		<?php $this->endWidget(); ?>
    </div>


</div>

<div class="row">
    <div class="col-xs-12">
		<?php
		$statusList = Booking::model()->getActiveBookingStatus();
		if (!empty($dataProvider))
		{
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'responsiveTable'	 => true,
				'filter'			 => $model,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                        <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                </div>
                                </div>
                                <div class='panel-body'>{items}</div>
                                <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				//     'ajaxType' => 'POST',
				'columns'			 => array(
					array('name'	 => 'bkg_bcb_id',
						'value'	 => function($data) {
							echo CHtml::link($data['bkg_bcb_id'], Yii::app()->createUrl("admin/booking/triprelatedbooking", ["tid" => $data['bkg_bcb_id']]), ["class" => "viewRelatedBooking", "onclick" => "return viewList(this)"]);
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Trip Id', 'filter'			 => false),
					array('name'	 => 'bkg_booking_id',
						'value'	 => function($data) {
							echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "return viewList(this)"]);
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Booking Id', 'filter'			 => false),
					array('name' => 'bkg_vehicle_id', 'value' => '$data["customer_name"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Customer Name', 'filter' => false),
					array('name' => 'bkg_agent_company', 'value' => '$data["channelPartner"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Channel Partner'),			
					array('name'	 => 'bkg_create_date',
						'value'	 => function ($data) {
							return DateTimeFormat::DateTimeToLocale($data['bkg_create_date']);
						}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Booking Date/Time', 'filter'			 => false),
					//   array('name' => 'bkg_pickup_address', 'value' => '$data->bkg_pickup_address', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Pickup Address'),
					array('name'	 => 'bkg_pickup_date',
						'value'	 => function ($data) {
							return DateTimeFormat::DateTimeToLocale($data['bkg_pickup_date']);
						},
						'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Pickup Date/Time', 'filter'			 => false),
					array('name' => 'bkg_vendor_name', 'value' => '$data["vnd_name"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Vendor Name'),
					array('name' => 'vhc_number', 'value' => '$data["vhc_number"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Cab Number', 'filter' => false),
					array('name'	 => 'bkg_status',
						'value'	 => function ($data) {
							echo Booking::model()->getBookingStatus($data['bkg_status']);
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Booking Status', 'filter'			 => false),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{edit}{cancel}',
						'buttons'			 => array(
							'edit'			 => array(
								'visible'	=> 'BookingInvoice::validateDateRestriction($data[\'bkg_pickup_date\'])',
								'url'		 => 'Yii::app()->createUrl("admin/booking/pendingaction", array(\'id\' => $data["bkg_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/edit_booking.png',
								'label'		 => '<i class="fa fa-edit"></i>',
								'options'	 => array('style' => '', 'onclick' => 'return pendingAction(this)', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Edit'),
							),
							'cancel'		 => array(
								'visible'	=> 'BookingInvoice::validateDateRestriction($data[\'bkg_pickup_date\'])',
								'url'		 => 'Yii::app()->createUrl("admin/booking/cancelPendingBooking", array(\'id\' => $data["bkg_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/delete_booking.png',
								'label'		 => '<i class="fa fa-remove"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Delete'),
							),
							'htmlOptions'	 => array('class' => 'center'),
						)),
			)));
		}
		?>
    </div>
</div>
<script type="text/javascript">

    function viewList(obj) {
        var href2 = $(obj).attr("href");

        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Booking Details',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
            }
        });
        return false;
    }
    function pendingAction(obj) {
        var href2 = $(obj).attr("href");
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Booking Details',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
            }
        });
        return false;
    }

    $(document).ready(function () {


        var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
        var end = '<?= date('d/m/Y'); ?>';

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
            $('#bkgCreateDate span').html('Select Booking Date Range');
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
