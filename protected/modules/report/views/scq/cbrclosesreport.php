<style>
    .table-flex {
        display: flex;
        flex-direction: column;
    }
    .tr-flex {
        display: flex;
    }
    .th-flex, .td-flex{
        flex-basis: 35%;
    }
    .thead-flex, .tbody-flex {
        overflow-y: scroll;
    }
    .tbody-flex {
        max-height: 250px;
    }
</style>
<div class="row">
    <div class="col-xs-12  pb10">
        <a href="/report/scq/cbrdetailsreport" target="_blank"> Click To View  CBR Details Report</a>
        <br>
        <a href="/report/scq/cbrStaticalCloseData?date=<?php echo $followup->date; ?>" target="_blank" > Click To View CBR Statistical Data Report</a>
    </div>
</div>
<div class="row"> 
    <?php
    $form          = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                     => 'booking-form', 'enableClientValidation' => true,
        'clientOptions'          => array(
            'validateOnSubmit' => true,
            'errorCssClass'    => 'has-error'
        ),
        'enableAjaxValidation'   => false,
        'errorMessageCssClass'   => 'help-block',
        'htmlOptions'            => array(
            'class' => '',
        ),
    ));
    /* @var $form TbActiveForm */
    $minDate       = date('Y-m-d H:i:s', strtotime('-30 days'));
    ?>
    <div class="col-xs-12 col-sm-3 col-md-3">
        <?=
        $form->datePickerGroup($followup, 'date', array('label'         => 'Filter By Close Date',
            'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => '01/01/2021', 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Filter By Close Date')), 'prepend'       => '<i class="fa fa-calendar"></i>'));
        ?>  
    </div>
    <div class="col-xs-12 col-sm-3 col-md-3"> 
        <div class="form-group">
            <label class="control-label" style="margin-left:5px;">Search By Queue</label>
            <?php
            $queueTypeJson = Filter::getJSON(array("1" => "New Booking", "2" => "Existing Booking", "3" => " New Vendor Attachtment", "4" => "Existing Vendor", "5" => "Advocacy", "6" => "Driver", "7" => "Payment Followup", "8" => "Corporate Sales", "9" => "Service Requests", "10" => "SOS", "11" => "Penalty Dispute", "12" => "UpSell(CNG/Value)", "13" => "Vendor Advocacy", "14" => "Dispatch", "15" => "Vendor Approval", "16" => "New Lead Booking", "17" => "New Quote Booking", "18" => "B2B Post pickup", "19" => "Booking At Risk(Bar)", "20" => "New Lead Booking(International)", "21" => "New Quote Booking(International)", "22" => "FBG", '23' => 'General Accounts','24'=>"Upsell(Value+/Select)",'25'=>"Booking Complete Review",'26'=>"Apps Help & Tech support",'27'=>"Gozo Now",'29'=>"Auto Lead Followup",'30' => "Document Approval", '31' =>  "Vendor Approval  Zone Based Inventory", '32' =>  "Critical and stress (risk) assignments(CSA)", '33' => "Airport DailyRental", '34' => "Last Min Booking", '35' => "High Price", '36' => "Driver NoShow", '37' => "Customer NoShow", '38' => "MMT Support", '39' => "Driver Car BreakDown", '40' => "Vendor Assign", '41' => "Cusomer Booking Cancel", '42' => "Spice Lead Booking", '43' => "Spice Quote Booking", '44' => "Spice Lead Booking International", '45' => "Spice Quote Booking International",'46' => "Vendor Due Amount",'51' => "Booking Reschedule",'52' => "Driver Custom Push API",'53' => "VIP/VVIP Booking"));
            $this->widget('booster.widgets.TbSelect2', array(
                'model'          => $followup,
                'attribute'      => 'queueType',
                'val'            => $followup->queueType,
                'asDropDownList' => FALSE,
                'options'        => array('data' => new CJavaScriptExpression($queueTypeJson), 'allowClear' => true),
                'htmlOptions'    => array('style' => 'width:100%', 'placeholder' => 'Select Queue')
            ));
            ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-3 col-md-3">
        <div class="form-group">
            <label class="control-label">Teams('Closed By' event only)</label>
            <?php
            $dataTeam      = Teams::getList();
            $allArr[]      = "All";
            $dataTeams     = array_merge($allArr, $dataTeam);
            $this->widget('booster.widgets.TbSelect2', array(
                'model'       => $followup,
                'attribute'   => 'scq_to_be_followed_up_by_id',
                'data'        => $dataTeams,
                'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Team(s)')
            ));
            ?>
        </div> 
    </div>

    <div class="col-xs-12 col-sm-1 col-md-1 text-center mt20 p5">   
        <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
        <?php $this->endWidget(); ?>
</div>
<?php
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
											<div class='panel-body table-responsive'>{items}</div>
											<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
        'itemsCssClass'     => 'table table-striped table-bordered dataTable mb0',
        'htmlOptions'       => array('class' => 'panel panel-primary  compact'),
        'columns'           =>
        array
            (
            array('name'  => 'csrName', 'value' => function ($data) {
                    $fromdate = date('Y-m-d', strtotime(str_replace('/', '-', $data['date'])));
                    $todate   = date('Y-m-d', strtotime(str_replace('/', '-', $data['date'])));
                    $result   = Admins::getProfileData($data['csrId']);
                    $teamName = $result[0]['tea_name'] != null ? "(" . $result[0]['tea_name'] . ")" : "";
                    echo CHtml::link($data['csrName'] . $teamName, Yii::app()->createUrl("admpnl/generalReport/cbrdetailsreport", ["csrId" => $data['csrId'], "event_id" => 6, "event_by" => 1, "isCreated" => 0,  "fromdate" => $fromdate, "todate" => $fromdate]), ['target' => '_blank']);
                }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header'            => 'CSR Name'),
            array('name'  => 'cnt', 'value' => function ($data) {
                    echo $data['cnt'] != null ? $data['cnt'] : 0;
                }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header'            => 'Closed Today'),
            array('name'  => 'time', 'value' => function ($data) {
                    if ($data['queueType'] == '')
                    {
                        $rowData              = ServiceCallQueue::getStaticalDataByCsr($data['csrId'], $data['queueType'], $data['fromDate'], $data['toDate']);
                        echo $rowData['MinTime'] . "/" . $rowData ['MaxTime'] . "/" . $rowData ['AvgTime'] . "/" . $rowData ['TotalTime'];
                        $GLOBALS['totalTime'] = $rowData['TotalTime'] / 60;
                    }
                    else
                    {
                        $rowData              = ServiceCallQueue::getStaticalDataByCsr($data['csrId'], $data['scq_follow_up_queue_type'], $data['fromDate'], $data['toDate']);
                        echo $rowData['MinTime'] . "/" . $rowData['MaxTime'] . "/" . $rowData ['AvgTime'] . "/" . $rowData ['TotalTime'];
                        $GLOBALS['totalTime'] = $rowData['TotalTime'] / 60;
                    }
                }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header'            => 'Time to close(min/max/avg/totalTime)Minute'),
            array('name'  => 'onlineTime', 'value' => function ($data) {
                    $date1   = DateTimeFormat::DatePickerToDate($data['date']) . ' 00:00:00';
                    $date2   = DateTimeFormat::DatePickerToDate($data['date']) . ' 23:59:59';
                    $mintues = AdminOnoff::getTotalOnlineBycsrId($data['csrId'], $date1, $date2);
                    $countHr = Filter::getTimeDurationbyMinute($mintues);
                    if ($countHr != null)
                    {
                        echo ($countHr) . "/".round((($GLOBALS['totalTime'] / ($mintues/60) ) * 100), 2) . " %";
                    }
                    else
                    {
                        echo 0;
                    }
                    unset($GLOBALS['totalTime']);
                }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header'            => 'Total Online Time(Hour) / % Time on mycall'),
            array(
                'header'            => 'Action',
                'class'             => 'CButtonColumn',
                'htmlOptions'       => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
                'headerHtmlOptions' => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
                'template'          => '{log}',
                'buttons'           => array(
                    'log'         => array(
                        'click'    => 'function(){
                                            $href = $(this).attr(\'href\');
                                            jQuery.ajax({type: \'GET\',
                                            url: $href,
                                            success: function (data)
                                            {
                                                var box = bootbox.dialog({
                                                    message: data,
                                                    title: \'Admin On/Off Log\',
                                                    size: \'medium\',
                                                    onEscape: function () {

                                                        // user pressed escape
                                                    }
                                                });
                                            }
                                        });
                                    return false;
                                }',
                        'url'      => 'Yii::app()->createUrl("admpnl/admin/adminLogTime", array("csrId" => $data[csrId],"date"=>$data[date]))',
                        'imageUrl' => Yii::app()->request->baseUrl . '\images\icon\vendor\show_log.png',
                        'label'    => '<i class="fa fa-list"></i>',
                        'options'  => array('data-toggle' => 'ajaxModal',
                            'style'       => '',
                            'class'       => 'btn btn-xs conshowlog p0',
                            'title'       => 'Admin Log'),
                    ),
                    'htmlOptions' => array('class' => 'center'),
                )
            )
        )
    ));
}
?>