<style>
    span.stars, span.stars span {
        display: block;
        background: url(http://localhost:82/images/stars.png) 0 -16px repeat-x;
        width: 80px;
        height: 16px;        
    }
    span.stars span {
        background-position: 0 0;
    }
    span.stars span :hover{
        background-position: 0 0;
    }

</style>
<?php
$modeJson = VehicleTypes::model()->getJSON(PaymentGateway::model()->getModeList());
?>

<div class="container">
        <div class="portlet light">    
            <div class="portlet-body">   
                        <div class="row bordered pt10">
                            <?php
                            $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                                'id' => 'booking-form111', 'enableClientValidation' => true,
                                'clientOptions' => array(
                                    'validateOnSubmit' => true,
                                    'errorCssClass' => 'has-error',
                                ),
                                // Please note: When you enable ajax validation, make sure the corresponding
                                // controller action is handling ajax validation correctly.
                                // See class documentation of CActiveForm for details on this,
                                // you need to use the performAjaxValidation()-method described there.
                                'enableAjaxValidation' => false,
                                'errorMessageCssClass' => 'help-block',
                                'htmlOptions' => array(
                                    'class' => '',
                                ),
                            ));
                            /* @var $form TbActiveForm */
                            ?>

                            <div class="  col-sm-6 col-md-4 col-lg-4">
                                <?= $form->textFieldGroup($model, 'search', array('label' => "Search by Booking or Traveller's  information", 'htmlOptions' => array('placeholder' => 'search by booking id or other information'))) ?>

                                <? //= $form->textFieldGroup($model, 'bkg_booking_id', array('htmlOptions' => array()))   ?>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Transaction Mode</label>
                                    <?php
                                    $this->widget('booster.widgets.TbSelect2', array(
                                        'model' => $model,
                                        'attribute' => 'trans_mode',
                                        'val' => $model->trans_mode,
                                        'asDropDownList' => FALSE,
                                        'options' => array('data' => new CJavaScriptExpression($modeJson), 'allowClear' => true),
                                        'htmlOptions' => array('class' => 'agtSelect2', 'style' => 'width:100%', 'placeholder' => 'Mode')
                                    ));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Booking Status</label>
                                    <?php
                                    $this->widget('booster.widgets.TbSelect2', array(
                                        'model' => $model,
                                        'attribute' => 'bkg_status',
                                        'val' => $model->bkg_status,
                                        'asDropDownList' => FALSE,
                                        'options' => array('data' => new CJavaScriptExpression($statusJSON), 'allowClear' => true),
                                        'htmlOptions' => array('class' => 'agtSelect2', 'style' => 'width:100%', 'placeholder' => 'Select Status')
                                    ));
                                    ?>
                                </div>
                            </div>

                            <? /*
                              <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 hide">
                              <?= $form->textFieldGroup($model, 'traveller_name', array('htmlOptions' => array())) ?>
                              </div>
                              <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 hide">
                              <?= $form->textFieldGroup($model, 'bkg_contact_no1', array('htmlOptions' => array())) ?>
                              </div>
                              <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 hide">
                              <?= $form->textFieldGroup($model, 'bkg_user_email1', array('htmlOptions' => array())) ?>
                              </div>
                             */ ?>



                            <div class="col-sm-6 col-md-4 ">
                                <div class="form-group">
                                    <label  class="control-label">Booking Date</label>
                                    <?
                                    $daterang = "Select Booking Date Range";
                                    $createdate1 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
                                    $createdate2 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
                                    if ($createdate1 != '' && $createdate2 != '') {
                                        $daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
                                    }
                                    ?>

                                    <div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                        <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                                    </div>
                                    <?
                                    echo $form->hiddenField($model, 'bkg_create_date1');
                                    echo $form->hiddenField($model, 'bkg_create_date2');
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 ">
                                <div class="form-group">
                                    <label  class="control-label">Pickup Date</label>
                                    <?
                                    $daterange = "Select Pickup Date Range";
                                    $pickupdate1 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
                                    $pickupdate2 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
                                    if ($pickupdate1 != '' && $pickupdate2 != '') {
                                        $daterange = date('F d, Y', strtotime($pickupdate1)) . " - " . date('F d, Y', strtotime($pickupdate2));
                                    }
                                    ?>

                                    <div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                        <span style="min-width: 240px"><?= $daterange ?></span> <b class="caret"></b>
                                    </div>
                                    <?
                                    echo $form->hiddenField($model, 'bkg_pickup_date1');
                                    echo $form->hiddenField($model, 'bkg_pickup_date2');
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 ">
                                <div class="form-group">
                                    <label  class="control-label">Transaction Date</label>
                                    <?
                                    $daterange = "Select Transaction Date Range";
                                    $transdate1 = ($model->agt_trans_created1 == '') ? '' : $model->agt_trans_created1;
                                    $transdate2 = ($model->agt_trans_created2 == '') ? '' : $model->agt_trans_created2;
                                    if ($transdate1 != '' && $transdate2 != '') {
                                        $daterange = date('F d, Y', strtotime($transdate1)) . " - " . date('F d, Y', strtotime($transdate2));
                                    }
                                    ?>

                                    <div id="agtTransactionDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                        <span style="min-width: 240px"><?= $daterange ?></span> <b class="caret"></b>
                                    </div>
                                    <?
                                    echo $form->hiddenField($model, 'agt_trans_created1');
                                    echo $form->hiddenField($model, 'agt_trans_created2');
                                    ?>
                                </div>
                            </div>

                            <div class="col-xs-12 text-center ">  
                                <button class="btn btn-warning mt5" type="reset" style="width: 140px;"  name="reset" id="btnreset">Clear</button>
                                <button class="btn btn-primary mt5" type="submit" style="width: 140px;"  name="bookingSearch">Search</button>
                            </div>

                            <?php $this->endWidget(); ?>
                        </div>
                    </div> 
                </div>
 
        <?php
        if (!empty($dataProvider)) {
            $this->widget('booster.widgets.TbGridView', array(
                'id' => 'booking-list',
                'responsiveTable' => true,
                'dataProvider' => $dataProvider,
                //'filter' => $model,
                'template' => "<div class='panel-heading'><div class='row m0'>
                        <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                        </div></div>
                        <div class='panel-body'>{items}</div>
                        <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
                'itemsCssClass' => 'table table-striped table-bordered mb0',
                'htmlOptions' => array('class' => 'table-responsive panel panel-primary'),
                'columns' => array(
                    ['name' => 'bkg_id',
                        'type' => 'raw',
                        'value' => function($data) {
                            if ($data['bkg_booking_id'] != '') {
                                echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("agent/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "return viewBooking(this)"]);

                                echo "<br>" . $data["bkg_user_name"];
                            }
                        },
                        'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Booking ID'],
                    ['name' => 'adm_fname',
                        'value' => function($data) {
                            if ($data['bkg_booking_id']) {
                                echo 'Self';
                            } else {
                                echo $data["adm_fname"];
                            }
                        },
                        'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Added by'],
                    ['name' => 'agt_trans_created',
                        'value' => function ($data) {
                            echo DateTimeFormat::DateTimeToDatePicker($data['agt_trans_created'])
                            . "<br>" . DateTimeFormat::DateTimeToTimePicker($data['agt_trans_created']);
                        }, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Transaction Date'],
                    ['name' => 'bkg_agent_markup',
                        'value' => function($data) {
                            if ($data['bkg_booking_id']) {
                                echo '<i class="fa fa-inr"></i>' . round($data["bkg_agent_markup"]);
                            } else {
                                echo "N A";
                            }
                        }, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center hide'), 'htmlOptions' => array('style' => 'text-align: center;', 'class' => 'hide'), 'header' => 'Agent Commission'],
                    ['name' => 'bkg_total_amount', 'value' => function($data) {
                            if ($data['bkg_booking_id']) {
                                echo '<i class="fa fa-inr"></i>' . round($data['bkg_total_amount']);
                            } else {
                                echo "N A";
                            }
                        }, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Booking Amount'],
                    ['name' => 'agt_trans_mode',
                        'value' => function($data) {
                            echo $mode = AccountTransactions::model()->getModeList($data['agt_trans_mode']);
                        }, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Transaction Mode'],
                    ['name' => 'agt_trans_amount', 'value' => function($data) {
                            $amount = $data['agt_trans_amount'] | 0;
                            echo '<nobr><i class="fa fa-inr"></i>' . round($amount) . '</nobr';
                        }, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Agent Credit'],
                    ['name' => 'tot_trans_amount', 'value' => function($data) {
                            echo '<i class="fa fa-inr"></i>' . round($data['tot_trans_amount']);
                        }, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Balance on Date'],
                    ['name' => 'bkg_advance_amount', 'value' => function($data) {
                            if ($data['bkg_booking_id']) {
                                if ($data['bkg_advance_amount'] > 0) {
                                    echo '<i class="fa fa-inr"></i>' . round($data['bkg_advance_amount']);
                                } else {
                                    echo '<i class="fa fa-inr"></i>' . '0';
                                }
                            } else {
                                echo "N A";
                            }
                        }, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Advance Paid'],
                    ['name' => 'bkg_create_date',
                        'value' => function ($data) {

                            if ($data['bkg_booking_id']) {
                                echo DateTimeFormat::DateTimeToDatePicker($data['bkg_create_date'])
                                . "<br>" . DateTimeFormat::DateTimeToTimePicker($data['bkg_create_date']);
                            } else {
                                echo "N A";
                            }
                        }, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Booking Date/Time'],
                    ['name' => 'bkg_pickup_date',
                        'value' => function ($data) {
                            if ($data['bkg_booking_id']) {
                                echo DateTimeFormat::DateTimeToDatePicker($data['bkg_pickup_date'])
                                . "<br>" . DateTimeFormat::DateTimeToTimePicker($data['bkg_pickup_date']);
                            } else {
                                echo "N A";
                            }
                        },
                        'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Pickup Date/Time'],
                    ['name' => 'bkg_status_name', 'value' => function($data) {
                            if ($data['bkg_booking_id']) {
                                if ($data['bkg_status'] == 2) {
                                    echo 'Confirmed';
                                } else {
                                    echo Booking::model()->getActiveBookingStatus($data['bkg_status']);
                                }
                            } else {
                                echo "N A";
                            }
                        }, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array(), 'header' => 'Status'],
            )));
        }
        ?>
</div>


<script type="text/javascript">
    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';



    $('#agtTransactionDate').daterangepicker(
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
        $('#Booking_agt_trans_created1').val(start1.format('YYYY-MM-DD'));
        $('#Booking_agt_trans_created2').val(end1.format('YYYY-MM-DD'));
        $('#agtTransactionDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#agtTransactionDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#agtTransactionDate span').html('Select Transaction Date Range');
        $('#Booking_agt_trans_created1').val('');
        $('#Booking_agt_trans_created2').val('');
    });


    $('#bkgCreateDate').daterangepicker({
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

        $('#bkgPickupDate span').html('Select Pickup Date Range');
        $('#Booking_bkg_pickup_date1').val('');
        $('#Booking_bkg_pickup_date2').val('');
        $('#bkgCreateDate span').html('Select Booking Date Range');
        $('#Booking_bkg_create_date1').val('');
        $('#Booking_bkg_create_date2').val('');
        $('#agtTransactionDate span').html('Select Transaction Date Range');
        $('#Booking_agt_trans_created1').val('');
        $('#Booking_agt_trans_created2').val('');
        $(".agtSelect2").select2('val', '').trigger('change');
    });

    function openDialog(obj)
    {
        try
        {
            $href = $(obj).attr("href");
            jQuery.ajax({type: "GET", "dataType": "html", url: $href, success: function (data)
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


</script>
