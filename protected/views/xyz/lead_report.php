<style>
    .compact.panel .pagination {
    margin: 0;
}
.compact.panel .panel-heading+.panel-body {
    padding: 0;
}
</style>
<div class="row m0 mt20" id="passwordDiv">
    <div class="col-xs-offset-4 col-xs-4">   
            <div class="form-group row text-center">
                <input class="form-control" type="password" id="psw" name="psw" value="" placeholder="Password" required/>
            </div>
            <div class="Submit-button row text-center">
                <button type="submit" class="btn btn-primary" onclick="showDiv()">SUBMIT</button>
            </div>
    </div>
</div>
<div class="row m0 mt20" id="leadreportDiv" style="display : none;">
    <div class="col-xs-12">
        <h4 class="text-center text-primary"><b>Lead Report of Last 24 Hours</b></h4>
                <?php
                if (!empty($dataProvider)) {
                    $params = array_filter($_REQUEST);
                    $dataProvider->getPagination()->params = $params;
                    $this->widget('booster.widgets.TbGridView', array(
                        'id'=>'lead-grid',
                        'responsiveTable' => true,
                        'dataProvider' => $dataProvider,
                        'template' => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
                        'itemsCssClass' => 'table table-striped table-bordered dataTable mb0',
                        'htmlOptions' => array('class' => 'panel panel-primary  compact'),
                        'ajaxType' => 'POST',
                        'columns' => array(
                            array('name' => 'bkg_vehicle_type_id', 'value' => '$data->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Cab Type'),
                            array('name' => 'from_city', 'value' => '$data->bkgFromCity->cty_name', 'sortable' => true, 'headerHtmlOptions' => array('class' => '')),
                            array('name' => 'to_city', 'value' => '$data->bkgToCity->cty_name', 'sortable' => true, 'headerHtmlOptions' => array('class' => '')),
                            array('name' => 'bkg_pickup_date', 'value' => 'date("d/m/Y H:i:s",strtotime($data->bkg_pickup_date))', 'sortable' => true, 'headerHtmlOptions' => array('class' => '')),
                            array('name' => 'bkg_create_date', 'value' => 'date("d/m/Y H:i:s",strtotime($data->bkg_create_date))', 'sortable' => true, 'headerHtmlOptions' => array('class' => '')),
                            array('name' => 'bkg_booking_type', 'value' => '$data->getBookingType($data->bkg_booking_type)', 'sortable' => true, 'headerHtmlOptions' => array('class' => '')),
                            array('name' => 'bkg_amount', 'sortable' => true, 'headerHtmlOptions' => array('class' => '')),
                            array('name' => 'bkg_log_type', 'sortable' => true, 'headerHtmlOptions' => array('class' => '')),
                            array('name' => 'bkg_log_comment', 'sortable' => false, 'headerHtmlOptions' => array('class' => '')),
                            array('name' => 'bkg_log_phone', 'sortable' => false, 'headerHtmlOptions' => array('class' => '')),
                            array('name' => 'bkg_log_email', 'sortable' => true, 'headerHtmlOptions' => array('class' => '')),
                            array('name' => 'bkg_contact_no', 'sortable' => false, 'headerHtmlOptions' => array('class' => '')),
                            array('name' => 'bkg_user_email', 'sortable' => true, 'headerHtmlOptions' => array('class' => '')),
                            array('name' => 'bkg_follow_up_log',
                                'value' => function($data)
                                    {
                                        $valueType = $data["bkg_follow_up_by"];
                                        if ($valueType > 0)
                                        {
                                            $valueType = $data->findAdminNameList($data["bkg_follow_up_by"]) . " on " . date("d/m/Y H:i:s",strtotime($data["bkg_follow_up_on"]));
                                        }
                                        else
                                        {
                                            $valueType = '';
                                        }
                                        return $valueType;
                                    }, 
                                'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Log'),
                    )));
                }
                ?>
    </div>
</div>
<div class="row m0 mt20" id="wrongPassword" style="display : none;">
    <div class="col-xs-offset-4 col-xs-4">
        <h3>Wrong Password</h3>
        <img src="http://static.commentcamarche.net/es.ccm.net/pictures/Ud6krzOUaQiVrbx4IWkuzUrMD8vWr4qbG1wMtmWKQ94r7Doi6fybXXnACJoLFtKR-lol.png">
    </div>
</div>
<script>
    
$(document).ready(function () {
    if ($.cookie('lreportPassword') == '1001') {
        $('#leadreportDiv').show();
        $('#passwordDiv').hide();
    }
});

function showDiv() {
    var pvalue = $('#psw').val();
    if (pvalue == '1001') {
        $.cookie('lreportPassword', pvalue, { expires: 1 });
        $('#leadreportDiv').show();
        $('#passwordDiv').hide();
    }
    if (pvalue != '1001') {
       $('#wrongPassword').show();
       $('#passwordDiv').hide();
    }
}
</script>