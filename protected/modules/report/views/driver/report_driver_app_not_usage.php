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
<?
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
'openOnFocus'		 => true, 'preload'			 => false,
'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
'addPrecedence'		 => false,];

?>
<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row"> 
                    <?php
                    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                        'id' => 'drvAppNotUsage-form', 'enableClientValidation' => true,
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

                    <div class="col-xs-12 col-sm-4 col-md-4" style="">
                        <div class="form-group">
                            <label class="control-label">Date Range</label>
                            <?php
                            $daterang = "Select Date Range";
                            $bkg_pickup_date1 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
                            $bkg_pickup_date2 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
                            if ($bkg_pickup_date1 != '' && $bkg_pickup_date2 != '') {
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
                    <div class="col-xs-3 col-sm-2 mt20">
                        <input class="form-control" type="checkbox" name="not_app_used" value="1" <?= ($appnotused == 1) ? 'checked' : '' ?>>Show only driver app not used		
                    </div>

                    <div class="row">
                        <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p4">   
                            <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
                            <?php $this->endWidget(); ?>
						<div class="col-xs-1">
							<?php
							$checkExportAccess	 = false;
							if ($roles['rpt_export_roles'] != null)
							{
								$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
							}
							if ($checkExportAccess)
							{
								echo CHtml::beginForm(Yii::app()->createUrl('report/driver/DriverAppNotUsed'), "post", []);
								?>
								<input type="hidden" id="bkg_pickup_date1" name="bkg_pickup_date1" value="<?= $model->bkg_pickup_date1 ?>"/>
								<input type="hidden" id="bkg_pickup_date2" name="bkg_pickup_date2" value="<?= $model->bkg_pickup_date2 ?>"/>
								<input type="hidden" id="not_app_used" name="not_app_used" value="<?= $appnotused ?>"/>
								<input type="hidden" id="export" name="export" value="true"/>
								<button class="btn btn-default btn-5x pr30 pl30 mt20" type="submit" style="width: 185px;">Export</button>
								<?php echo CHtml::endForm(); ?>	
							<?php } ?>
						</div>	
                    </div>

                    <div class="row" style="margin-top: 10px">
                        <div class="col-xs-12 col-sm-12 col-md-12">        
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr style="color: black;background: whitesmoke">
                                        <th class="text-center"><u>Booking Count</u></th>
                                        <th class="text-center"><u>Arrived Count</u></th>
                                        <th class="text-center"><u>Arrived Percentage</u></th>
                                        <th class="text-center"><u>Start Count</u></th>
                                        <th class="text-center"><u>End Count</u></th>
                                        <th class="text-center"><u>Start & End Count</u></th>
                                        <th class="text-center"><u>Start Percentage</u></th>
                                        <th class="text-center"><u>End Percentage</u></th>
                                        <th class="text-center"><u>Start & End Percentage</u></th>
                                    </tr>
                                </thead>
                                <tbody id="count_booking_row">                         

                                    <?php
                                    if ($datasummary != null) {
                                        ?>

                                        <tr>
                                            <td class="text-center"><?= $datasummary['total_booking'] ?></td>
                                            <td class="text-center"><?= $datasummary['arrived_count'] ?></td>
                                            <td class="text-center"><?= ($datasummary['arrived_count'] > 0) ? round((($datasummary['arrived_count'] / $datasummary['total_booking']) * 100), 2) : 0; ?> %</td>
                                            <td class="text-center"><?= $datasummary['start_count'] ?></td>
                                            <td class="text-center"><?= $datasummary['end_count'] ?></td>
                                            <td class="text-center"><?= $datasummary['start_end_count'] ?></td>
                                            <td class="text-center"><?= ($datasummary['start_count'] > 0) ? round((($datasummary['start_count'] / $datasummary['total_booking']) * 100), 2) : 0; ?> %</td>
                                            <td class="text-center"><?= ($datasummary['end_count'] > 0) ? round((($datasummary['end_count'] / $datasummary['total_booking']) * 100), 2) : 0; ?> %</td>
                                            <td class="text-center"><?= ($datasummary['start_end_count'] > 0) ? round((($datasummary['start_end_count'] / $datasummary['total_booking']) * 100), 2) : 0; ?> %</td>
                                        </tr>
<?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>


<?php
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
        //    'ajaxType' => 'POST', 
        'columns' => array(
            array('name' => 'bkg_booking_id', 'value' => function($data) {
                    if ($data['bkg_booking_id'] != '') {
                        echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
                    }
                }, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Booking Id'),
            array('name' => 'bkg_pickup_date', 'value' => '$data[bkg_pickup_date]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Pickup Date'),
            array('name' => 'bkg_trip_duration', 'value' => '$data[bkg_trip_duration]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Trip Duration(Mins)'),
            array('name' => 'vnd_name', 'value' => '$data[vnd_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Vendor Name'),
            array('name' => 'trip_arrive_time', 'value' => '($data[trip_arrive_time]=="")?0:1', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Trip arrived Using driver app'),
            array('name' => 'start_app', 'value' => '$data[start_app]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Trip started Using driver app'),
            array('name' => 'end_app', 'value' => '$data[end_app]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Trip ended Using driver app'),
            array('name' => 'app_usage', 'value' => '(($data[start_app]==1 && $data[end_app]==1)?"Yes":"No")', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Driver app used'),
            array('name' => 'vnd_phone', 'value' => function($data) {
                    if ($data['drv_contact_id'] != "") {
                        $contactVndPhnModel = ContactPhone::model()->findByContactID($data['vnd_contact_id']);
                        if ($contactVndPhnModel[0]->phn_is_verified == 1) {
                            echo trim($contactVndPhnModel[0]->phn_phone_no) . '<img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Contact Verified" width="15">';
                        } else {
                            echo trim($contactVndPhnModel[0]->phn_phone_no) . '<img src="/images/icon/unblock.png" style="cursor:pointer" title="Contact UnVerified" width="15">';
                        }
                    }
                }, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Vendor Phone'),
            array('name' => 'drv_name', 'value' => '$data[drv_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Driver Name'),
            array('name' => 'drv_phone', 'value' => function($data) {
                    if ($data['drv_contact_id'] != "") {
                        $contactDrvPhnModel = ContactPhone::model()->findByContactID($data['drv_contact_id']);
                        if ($contactDrvPhnModel[0]->phn_is_verified == 1) {
                            echo trim($contactDrvPhnModel[0]->phn_phone_no) . '<img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Contact Verified" width="15">';
                        } else {
                            echo trim($contactDrvPhnModel[0]->phn_phone_no) . '<img src="/images/icon/unblock.png" style="cursor:pointer" title="Contact UnVerified" width="15">';
                        }
                    }
                }, 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Driver Phone'),
            array('name' => 'trip_type', 'value' => function($data) {
                    #$tripSourceArr = Booking::model()->booking_platform;
                    #$tripSource =  $tripSourceArr[$data['bkg_platform']];
                    if ($data['bkg_agent_id'] != '') {
                        $agentsModel = Agents::model()->findByPk($data['bkg_agent_id']);
                        if ($agentsModel->agt_type == 1) {
                            $tripSource = "AGENT (" . ($agentsModel->agt_company) . ")";
                        } else {
                            $owner = ($agentsModel->agt_owner_name != '') ? $agentsModel->agt_owner_name : ($agentsModel->agt_fname . " " . $agentsModel->agt_lname);
                            $tripSource = "AGENT (" . ($agentsModel->agt_company . " - " . $owner) . ")";
                        }
                        return $tripSource;
                    } else {
                        if ($data['bkg_user_name'] != '') {
                            $tripSource = "B2C (" . $data['bkg_user_name'] . ")";
                        } else {
                            $tripSource = "B2C(No Name Found)";
                        }
                        return $tripSource;
                    }
                }, 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Booking Source'),
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
            $('#Booking_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
            $('#Booking_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgPickupDate span').html('Select Pickup Date Range');
            $('#Booking_bkg_pickup_date1').val('');
            $('#Booking_bkg_pickup_date2').val('');
        });
    </script>