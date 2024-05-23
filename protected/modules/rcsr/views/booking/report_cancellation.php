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
                    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                        'id' => 'booking-form', 'enableClientValidation' => true,
                        'clientOptions' => array(
                            'validateOnSubmit' => true,
                            'errorCssClass' => 'has-error'
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
                       <div class="col-xs-6 col-sm-4 col-lg-3">
                            <?
                            //$daterang = date('F d, Y') . " - " . date('F d, Y');
                            $daterang = "Select Date Range";
                            $createdate1 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
                            $createdate2 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
                            if ($createdate1 != '' && $createdate2 != '') {
                                $daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
                            }
                            ?>
                            <label  class="control-label">From & To Date Selection</label>
                            <div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span><?= $daterang ?></span> <b class="caret"></b>
                            </div>
                            <?
                            echo $form->hiddenField($model, 'bkg_create_date1');
                            echo $form->hiddenField($model, 'bkg_create_date2');
                            ?>
                        </div>
                      
                    
                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
                        <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
                    <?php $this->endWidget(); ?>
                </div>
                <?php
                $checkContactAccess = Yii::app()->user->checkAccess("bookingContactAccess");
                if (!empty($dataProvider)) {

                    $params = array_filter($_REQUEST);
                    $dataProvider->getPagination()->params = $params;
                    $dataProvider->getSort()->params = $params;
                    $this->widget('booster.widgets.TbGridView', array(
                        'responsiveTable' => true,
                        'dataProvider' => $dataProvider,
                        'template' => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
                        'itemsCssClass' => 'table table-striped table-bordered dataTable mb0',
                        'htmlOptions' => array('class' => 'panel panel-primary  compact'),
                        'columns' => array(
                            array('name' => 'bkg_booking_id', 'value' => function($data){
                                   echo CHtml::link($data["bkg_booking_id"], Yii::app()->createUrl("rcsr/booking/view", ["id" => $data['bkg_id']]), ["class" => "", "onclick" => "return viewDetail(this)"]); 
                                }, 'sortable' => true,
                                'headerHtmlOptions' => array('class' => 'col-xs-1'),
                                'header' => 'Booking ID'),
                            array('name' => 'bkg_booking_type', 'value' => function($data){
                                    echo Booking::model()->getBookingType($data[bkg_booking_type]);
                                 },
                                'sortable' => true,
                                'headerHtmlOptions' => array('class' => 'col-xs-1'),
                                'header' => 'Booking Type'),
                            array('name' => 'booking_route', 'value' => '$data[booking_route]',
                                'sortable' => true,
                                'headerHtmlOptions' => array('class' => 'col-xs-2'),
                                'header' => 'Booking Route'),         
                            array('name' => 'bkg_create_date', 'value' => 'date("d-m-Y H:i:s",strtotime($data[bkg_create_date]))',
                                'sortable' => true,
                                'headerHtmlOptions' => array('class' => 'col-xs-1'),
                                'header' => 'Booking Date/Time'),             
                            array('name' => 'bkg_pickup_date', 'value' => 'date("d-m-Y H:i:s",strtotime($data[bkg_pickup_date]))',
                                'sortable' => true,
                                'headerHtmlOptions' => array('class' => 'col-xs-1 text-left'),
                                'header' => 'Pickup Date/Time'),
                            array('name' => 'blg_created', 'value' => 'date("d-m-Y H:i:s",strtotime($data[blg_created]))',
                                'sortable' => true,
                                'headerHtmlOptions' => array('class' => 'col-xs-1'),
                                'header' => 'Cancellation Date/Time'),             
                            array('name' => 'cnr_reason', 'value' => '$data[cnr_reason]',
                                'sortable' => true,
                                'headerHtmlOptions' => array('class' => 'col-xs-2'),
                                'header' => 'Cancel Reason'),
                            array('name' => 'bkg_cancel_delete_reason', 'value' => '$data[bkg_cancel_delete_reason]',
                                'sortable' => true,
                                'headerHtmlOptions' => array('class' => 'col-xs-2'),
                                'header' => 'Cancel Description'),             
                            array('name' => 'bkg_total_amount', 'value' => function($data)
                            {
                                echo number_format($data['bkg_total_amount'],2);
                            },
                            'sortable' => true,
                            'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'),
                            'htmlOptions' => array('class' => 'text-right'),
                            'header' => 'Amount'),    
                    )));
                }
                ?> 
            </div>  

        </div>  
    </div>
</div>
<script>
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
            $('#bkgCreateDate span').html('Select Date Range');
            $('#Booking_bkg_create_date1').val('');
            $('#Booking_bkg_create_date2').val('');
            
        });


    });
    function viewDetail(obj) {
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
                if ($('body').hasClass("modal-open"))
                {
                    box.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }

            }
        });
        return false;
    }
</script>
