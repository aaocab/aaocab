<div class="row m0">
    <div class="col-xs-12">        
        <div class="panel panel-default">
            <div class="panel-body">

                <?php
                $form             = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id'                     => 'otpreport-form', 'enableClientValidation' => true,
                    'clientOptions'          => array(
                        'validateOnSubmit' => true,
                        'errorCssClass'    => 'has-error'
                    ),
                    // Please note: When you enable ajax validation, make sure the corresponding
                    // controller action is handling ajax validation correctly.
                    // See class documentation of CActiveForm for details on this,
                    // you need to use the performAjaxValidation()-method described there.
                    'enableAjaxValidation'   => false,
                    'errorMessageCssClass'   => 'help-block',
                    'htmlOptions'            => array(
                        'class' => '',
                    ),
                ));
                // @var $form TbActiveForm 
                ?>
                <div class="row"> 

                    <div class="col-xs-12 col-sm-3" style="">
                        <div class="form-group">
                            <label class="control-label">Pickup Date Range:</label>
                            <?php
                            $daterang         = "Select Pickup Date Range";
                            $bkg_pickup_date1 = ($model->bkg_pickup_date1 == '') ? date('Y-m-d H:i:s', strtotime("-7 days")) : $model->bkg_pickup_date1;
                            $bkg_pickup_date2 = ($model->bkg_pickup_date2 == '') ? date('Y-m-d H:i:s') : $model->bkg_pickup_date2;
                            if ($bkg_pickup_date1 != '' && $bkg_pickup_date2 != '')
                            {
                                $daterang = date('F d, Y', strtotime($bkg_pickup_date1)) . " - " . date('F d, Y', strtotime($bkg_pickup_date2));
                            }
                            ?>
                            <div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
                            <?= $form->hiddenField($model, 'bkg_pickup_date1'); ?>
                            <?= $form->hiddenField($model, 'bkg_pickup_date2'); ?>

                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3">Status:
                        <?php
                        $statusJson        = Filter::getJSON(array("5" => "Allocated", "6" => "Completed", "7" => "Settled"));
                        $this->widget('booster.widgets.TbSelect2', array(
                            'model'          => $model,
                            'attribute'      => 'preData',
                            'val'            => explode(",", $model->preData),
                            'asDropDownList' => FALSE,
                            'options'        => array('data' => new CJavaScriptExpression($statusJson), 'allowClear' => true, 'multiple' => true),
                            'htmlOptions'    => array('style' => 'width:100%', 'placeholder' => 'Select Status', 'multiple' => 'multiple',)
                        ));
                        ?>
                    </div>
                </div>
                <div class="row"><div class="col-xs-12 col-sm-3 ">   
                        <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?></div>
                </div></div>				
            <?php $this->endWidget(); ?>

            <?php
            $checkExportAccess = Yii::app()->user->checkAccess("Export");
            if ($checkExportAccess)
            {
                echo CHtml::beginForm(Yii::app()->createUrl('admin/generalReport/DailyLoss'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
                ?>
                <input type="hidden" id="export" name="export" value="true"/>
                <input type="hidden" id="export_from" name="export_from" value="<?= $model->bkg_pickup_date1 ?>"/>
                <input type="hidden" id="export_to" name="export_to" value="<?= $model->bkg_pickup_date2 ?>"/>
                <input type="hidden" id="export_preData" name="export_preData" value="<?= $model->preData ?>"/>                  
                <button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
                <?php
                echo CHtml::endForm();
            }


            if (!empty($dataProvider))
            {
                $params                                = array_filter($_REQUEST);
                $dataProvider->getPagination()->params = $params;
                $dataProvider->getSort()->params       = $params;
                $this->widget('booster.widgets.TbGridView', array(
                    'responsiveTable'   => true,
                    'dataProvider'      => $dataProvider,
                    'template'          => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive table-bordered'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
                    'itemsCssClass'     => 'table table-striped table-bordered dataTable mb0',
                    'htmlOptions'       => array('class' => 'panel panel-primary  compact'),
                    //    'ajaxType' => 'POST',
                    'columns'           => array(
                        array('name'  => 'bkg_id', 'value' => function ($data) {
                                echo CHtml::link($data['bkg_id'], Yii::app()->createUrl("admin/booking/view/", ["id" => $data['bkg_id']]), ["target" => "_blank"]);
                            }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header'            => 'Booking ID'),
                        array('name'  => 'scc_label', 'value' => function ($data) {
                                echo $data['scc_label'];
                            }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header'            => 'Tier'),
                        array('name'  => 'sourceZone', 'value' => function ($data) {
                                echo $data['sourceZone'];
                            }, 'sortable'                             => true, 'headerHtmlOptions'                    => array('class' => 'col-xs-1'), 'header'                               => 'Source Zone'),
                        array('name'  => 'destinationZone', 'value' => function ($data) {

                                echo $data['destinationZone'];
                            }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'),
                            'header'            => 'Destination Zone'),
                        array('name'  => 'bkg_booking_type', 'value' => function ($data) {
                                echo $bookingType = Booking::model()->getBookingType($data['bkg_booking_type']);
                            }, 'sortable'          => true,
                            'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header'            => 'Bkg Type'),
                        array('name'  => 'bkg_create_date', 'value' => function ($data) {

                                echo $data['bkg_create_date'];
                            }, 'sortable'          => true,
                            'headerHtmlOptions' => array('class' => 'col-xs-1'),
                            'header'            => 'Create time'),
                        array('name'  => 'bkg_pickup_date', 'value' => function ($data) {

                                echo $data['bkg_pickup_date'];
                            }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'),
                            'header'            => 'Pickup time'),
                        array('name'  => 'AssigbedCount', 'value' => function ($data) {

                                echo $data['AssigbedCount'];
                            }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'),
                            'header'            => 'Assign Count'),
                        array('name'  => 'LVendorID', 'value' => function ($data) {                               
                                echo CHtml::link($data['LVendorID'], Yii::app()->createUrl("admin/vendor/view/", ["id" => $data['LVendorID']]), ["target" => "_blank"]);
                            }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'),
                            'header'            => 'Last vendor ID'),
                        array('name'  => 'FVendorID', 'value' => function ($data) {
                                echo CHtml::link($data['FVendorID'], Yii::app()->createUrl("admin/vendor/view/", ["id" => $data['FVendorID']]), ["target" => "_blank"]);
                            }, 'sortable'          => true,
                            'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header'            => 'First Vendor ID'),
                        array('name'  => 'bkg_total_amount', 'value' => function ($data) {
                                echo $data['bkg_total_amount'];
                            }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'),
                            'header'            => 'Bkg Amount'),
                        array('name'  => 'FVendorAmount', 'value' => function ($data) {
                                echo $data['AssigbedCount'] > 1 ? BookingCab::getFirstVendorAmountByBkgId($data['bkg_id'], $data['FVendorID']) : $data['FVendorAmount'];
                            }, 'sortable'          => true,
                            'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header'            => 'First VA'),
                        array('name'  => 'LVendorAmount', 'value' => function ($data) {
                                echo $data['LVendorAmount'];
                            }, 'sortable'          => true,
                            'headerHtmlOptions' => array('class' => 'col-xs-1'),
                            'header'            => 'Last VA'),
                        array('name'  => 'bkg_gozo_amount', 'value' => function ($data) {
                                echo $data['bkg_gozo_amount'];
                            }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'),
                            'header'            => 'Gozo P/Loss amount'),
                        array('name'  => 'LastAssigntype', 'value' => function ($data) {
                                echo $data['LastAssigntype'];
                            }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'),
                            'header'            => 'Last Assigntype '),
                        array('name'  => 'blg_admin_id', 'value' => function ($data) {
                                echo $data['blg_admin_id'] != null ? " Admin Id:" . $data['blg_admin_id'] : " Vedor Id:" .  $data['blg_vendor_id'];
                            }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'),
                            'header'            => 'Last Assigned by'),
                )));
            }
            ?> 
        </div>  
    </div>  
</div>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var start = '<?= date('d/m/Y'); ?>';
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
                    maxDate: moment(),
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Previous 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Previous 15 Days': [moment().subtract(15, 'days'), moment()]
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