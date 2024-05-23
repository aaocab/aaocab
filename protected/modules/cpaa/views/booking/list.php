<?
$version			 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/booking.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/route.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promo.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/lookup/cities?v' . Cities::model()->getLastModified());
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/lookup/routes?v' . Route::model()->getLastModified());
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false];
?>
<style>
    .pagination{
        margin: 0
    }
    .actBtn img{
        height: 20px;
    }
	@media (max-width: 767px){
		.padding-zero{ padding: 0!important;}
		.margin-zero{ margin-left: 0; margin-right: 0;}
		#booking-list2 td{ padding-left:50%!important;}
	}
</style>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-12 mt10">
			<?php
			if (!empty($dataProvider))
			{
				$this->widget('booster.widgets.TbGridView', array(
					'id'				 => 'booking-list2',
					'responsiveTable'	 => true,
					'dataProvider'		 => $dataProvider,
					//'filter' => $model,
					'template'			 => "<div class='panel-heading padding-zero'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
					'itemsCssClass'		 => 'table table-striped table-bordered mb0',
					'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
					'columns'			 => array(
						['name'	 => 'bkg_booking_id',
							'type'	 => 'raw',
							'value'	 => function($data) {
								if ($data['bkg_booking_id'] != '')
								{
									echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("agent/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "return viewBooking(this)"]);
								}
							},
							'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Booking ID'],
						['name' => 'bkg_user_name', 'value' => '$data["bkg_user_name"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Name'],
						['name' => 'bkg_user_email', 'value' => '$data["bkg_user_email"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Email'],
						['name' => 'bkg_contact_no', 'value' => '$data["bkg_contact_no"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Phone'],
						['name' => 'bkg_from_city', 'value' => '$data["fromCities"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'From City'],
						['name' => 'bkg_to_city', 'value' => '$data["toCities"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'To City'],
						['name'	 => 'bkg_total_amount', 'value'	 => function($data) {
								echo '<i class="fa fa-inr"></i>' . round($data['bkg_total_amount']);
							}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Amount'],
						['name'	 => 'bkg_corporate_credit', 'value'	 => function($data) {
								if ($data['bkg_corporate_credit'] != 0)
								{
									echo '<nobr><i class="fa fa-inr"></i>' . round($data['bkg_corporate_credit']) . '</nobr';
								}
								else
								{
									echo '<i class="fa fa-inr"></i>' . 0;
								}
							}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Partner Credit'],
						['name'	 => 'bkg_agent_markup',
							'value'	 => function($data) {
								if ($data['bkg_booking_id'])
								{
									echo '<i class="fa fa-inr"></i>' . round($data["bkg_agent_markup"]);
								}
								else
								{
									echo "N A";
								}
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;', 'class' => ''), 'header'			 => 'Partner Commission'],
						['name'	 => 'bkg_advance_amount', 'value'	 => function($data) {
								if ($data['bkg_advance_amount'] > 0)
								{
									echo '<i class="fa fa-inr"></i>' . round($data['bkg_advance_amount']);
								}
								else
								{
									echo '<i class="fa fa-inr"></i>' . '0';
								}
							}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Advance Paid'],
						['name'	 => 'bkg_create_date',
							'value'	 => function ($data) {
								echo DateTimeFormat::DateTimeToDatePicker($data['bkg_create_date'])
								. "<br>" . DateTimeFormat::DateTimeToTimePicker($data['bkg_create_date']);
								//echo DateTimeFormat::DateTimeToLocale($data['bkg_create_date']);
							}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Booking Date/Time'],
						['name'	 => 'bkg_pickup_date',
							'value'	 => function ($data) {
								echo DateTimeFormat::DateTimeToDatePicker($data['bkg_pickup_date'])
								. "<br>" . DateTimeFormat::DateTimeToTimePicker($data['bkg_pickup_date']);
								//echo DateTimeFormat::DateTimeToLocale($data['bkg_pickup_date']);
							},
							'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Pickup Date/Time'],
						['name'	 => 'bkg_status_name', 'value'	 => function($data) {
								if ($data['bkg_status'] == 2)
								{
									echo 'Confirmed';
								}
								else
								{
									echo Booking::model()->getActiveBookingStatus($data['bkg_status']);
								}
							}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array(), 'header'			 => 'Status'],
				)));
			}
			?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
    });
    function viewBooking(obj) {
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
    function openDialog(obj)
    {
        ajaxindicatorstart("");
        try
        {
            $href = $(obj).attr("href");
            jQuery.ajax({type: "GET", "dataType": "html", url: $href,
                success: function (data)
                {
                    bootbox.dialog({
                        message: data,
                        title: $(obj).attr("modaltitle"),
                    });
                }
            });
        } catch (e) {
            alert(e);
        }
        return false;
    }
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
                    'Tommorow': [moment().add(1, 'days'), moment().add(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Next 7 Days': [moment(), moment().add(6, 'days')],
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
    $('#btnreset').click(function () {
        $(".agtSelect2").select2('val', '').trigger('change')
        $('#bkgPickupDate span').html('Select Pickup Date Range');
        $('#Booking_bkg_pickup_date1').val('');
        $('#Booking_bkg_pickup_date2').val('');
        $('#bkgCreateDate span').html('Select Booking Date Range');
        $('#Booking_bkg_create_date1').val('');
        $('#Booking_bkg_create_date2').val('');
//        $('#agtTransactionDate span').html('Select Transaction Date Range');
//        $('#Booking_agt_trans_created1').val('');
//        $('#Booking_agt_trans_created2').val('');

    });
</script>
<script>
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
    $sourceList = null;
    function populateSource(obj, cityId) {

        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1])) ?>',
                    dataType: 'json',
                    data: {
                        // city: cityId
                    },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue(cityId);
            }
        });
    }
    function loadSource(query, callback) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }

    function changeDestination(value, obj) {
        if (!value.length)
            return;
        obj.disable();
        obj.clearOptions();
        obj.load(function (callback) {
            //  xhr && xhr.abort();
            xhr = $.ajax({
                url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/nearestcitylist')) ?>/source/' + value,
                dataType: 'json',
                success: function (results)
                {
                    obj.enable();
                    callback(results);
                },
                error: function () {
                    callback();
                }
            });
        });
    }
    /* $('#Booking_bkg_from_city_id1').change(function () {	   
     $('#Booking_bkg_from_city').val($(this).val());
     });
     
     $('#Booking_bkg_to_city_id1').change(function () {
     $('#Booking_bkg_to_city').val($(this).val());
     });*/
</script>