<style type="text/css">
    @media (min-width: 992px){
        .modal-lg {
            width: 95%!important;
        }
    }
    @media (min-width: 768px){
        .modal-lg {
            width: 100%;
        }
    }
    .bordered {
        border:1px solid #ddd;
        min-height: 45px;
        line-height: 1.1;

    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }

</style>
<?php
$pageno = filter_input(INPUT_GET, 'page');
$version = Yii::app()->params['customJsVersion'];
$reconfirmStatus = Booking::model()->getReconfirmStatus();
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
<div id="content" class="mt0 " style="width: 100%!important">
    <div class="row mb50">
        <div id="userView1">
            <div class=" col-xs-12">
                <div class="projects">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table>
                                        <tr>
                                            <th class="pb0" style="font-size: 16px"><u>Status</u></th>
                                        </tr>
                                        <tr>
                                            <td><b>N</b> &nbsp;&nbsp;&nbsp;NEW</td>
                                        </tr>
                                        <tr>
                                            <td><b>A</b> &nbsp;&nbsp;&nbsp;ASSIGNED</td>
                                        </tr>
                                        <tr>
                                            <td><b>O</b> &nbsp;&nbsp;&nbsp;ALLOCATED</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-sm btn-info" title="Smart Match" href="/rcsr/booking/createTrip">Create Trip</a>
                    <div class="panel panel-default">
                        <div class="panel-body" >
                            <?php
                            $reconfirmStatus = Booking::model()->getSmartMatchReconfirmStatus();
                            if (!empty($dataProvider)) {
                                $this->widget('booster.widgets.TbGridView', array(
                                    'id' => 'match-booking-grid',
                                    'responsiveTable' => true,
                                    'filter' => $model,
                                    'dataProvider' => $dataProvider,
                                    'template' => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
                                    'itemsCssClass' => 'table table-striped table-bordered mb0',
                                    'htmlOptions' => array('class' => 'table-responsive panel panel-primary  compact'),
                                    'columns' => array(
                                        array('name' => 'up_bkg_bcb_id', 'filter' => false, 'type' => 'raw', 'value' => 'CHtml::link($data["up_bkg_bcb_id"], Yii::app()->createUrl("rcsr/booking/triprelatedbooking",["tid"=>$data["up_bkg_bcb_id"]]),["class"=>"viewBooking", "onclick"=>"return viewBooking(this)"])', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Up Trip ID'),
                                        array('name' => 'up_bkg_booking_id', 'filter' => false, 'type' => 'raw', 'value' => function($data) {
                                                echo CHtml::link($data["up_bkg_booking_id"], Yii::app()->createUrl("rcsr/booking/view", ["id" => $data["up_bkg_id"]]), ["class" => "viewBooking", "onclick" => "return viewBooking(this)"]) . "<br>";
                                                 if ($data["up_booking_confirm"] == 0) {
                                                        $confirm = "RECONFIRM PENDING";
                                                    } elseif ($data["up_booking_confirm"] == 1) {
                                                        $confirm = "RECONFIRMED";
                                                    }
                                                if ($data["vendor_name"] != '') {
                                                   
                                                    $vnd_nm = explode("-",$data["vendor_name"]);
                                                    echo $vnd_nm[0] . '-' . $data["vendor_city"] . '-' . $data["vendor_total_trip"] . '-' . $data["vendor_rating"] . '-' . $confirm;
                                                }
                                                else{
                                                    echo $confirm;
                                                }
                                            }, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Up Booking ID'),
                                        array('name' => 'bkg1_total_amount', 'filter' => false, 'type' => 'raw', 'value' => function($data) {
                                                echo $data["bkg1_total_amount"] . '/' . $data['bkg1_advance_amount'] . "<br>";
                                            }, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Total / Advance'),
                                        array('name' => 'up_bkg_status', 'filter' => false, 'type' => 'raw', 'value' => function ($data) {
                                                return Booking::model()->getSmartMatchBookingStatus($data['up_bkg_status']);
                                            }, 'sortable' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Up Status'),
                                        array('name' => 'down_bkg_bcb_id', 'filter' => false, 'type' => 'raw', 'value' => 'CHtml::link($data["down_bkg_bcb_id"], Yii::app()->createUrl("rcsr/booking/triprelatedbooking",["tid"=>$data["down_bkg_bcb_id"]]),["class"=>"viewRelatedBooking", "onclick"=>"return viewBooking(this)"])', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Down Trip ID'),
                                        //array('name' => 'down_bkg_booking_id', 'type' => 'raw', 'value' => 'CHtml::link($data["down_bkg_booking_id"], Yii::app()->createUrl("rcsr/booking/view",["id"=>$data["down_bkg_id"]]),["class"=>"viewBooking", "onclick"=>"return viewBooking(this)"])', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Down Booking ID'),
                                        array('name' => 'down_bkg_booking_id', 'filter' => false, 'type' => 'raw', 'value' => function($data) {
                                                echo CHtml::link($data["down_bkg_booking_id"], Yii::app()->createUrl("rcsr/booking/view", ["id" => $data["down_bkg_id"]]), ["class" => "viewBooking", "onclick" => "return viewBooking(this)"]) . "<br>";
                                                if ($data["dn_booking_confirm"] == 0) {
                                                        $confirm = "RECONFIRM PENDING";
                                                    } elseif ($data["dn_booking_confirm"] == 1) {
                                                        $confirm = "RECONFIRMED";
                                                    }
                                                if ($data["down_booking_vendor_name"] != '') {
                                                    
                                                    $vnd_name = explode("-",$data["down_booking_vendor_name"]);
                                                    echo $vnd_name[0] . '-' . $data["down_booking_vendor_city"] . '-' . $data["down_booking_vendor_total_trips"] . '-' . $data["down_booking_vendor_rating"] . '-' . $confirm;
                                                }
                                                else{echo $confirm;}
                                            }, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Down Booking ID'),
                                        array('name' => 'bkg_total_amount', 'filter' => false, 'type' => 'raw', 'value' => function($data) {
                                                echo $data["bkg_total_amount"] . '/' . $data['bkg_advance_amount'] . "<br>";
                                            }, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Total / Advance'),
                                        array('name' => 'down_bkg_status', 'filter' => false, 'type' => 'raw', 'value' => function ($data) {
                                                return Booking::model()->getSmartMatchBookingStatus($data['down_bkg_status']);
                                            }, 'sortable' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Down Status'),
                                        array('name' => 'up_bkg_from_city_id', 'filter' => false, 'type' => 'raw', 'value' => function ($data) {
                                                if ($data['up_bkg_from_city_id'] != '' && $data['down_bkg_to_city_id'] != '') {
                                                    return $data['up_bkg_from_city'] . ' to ' . $data['up_bkg_to_city'];
                                                }
                                            }, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Up Route'),
                                        array('name' => 'down_bkg_from_city_id', 'filter' => false, 'type' => 'raw', 'value' => function ($data) {
                                                if ($data['down_bkg_from_city_id'] != '' && $data['down_bkg_to_city_id'] != '') {
                                                    return $data['down_bkg_from_city'] . ' to ' . $data['down_bkg_to_city'];
                                                }
                                            }, 'sortable' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Down Route'),
                                        array('name' => 'up_vht_model', 'filter' => false, 'type' => 'raw', 'value' => '$data["up_vht_make"]', 'sortable' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Up Cab Type'),
                                        array('name' => 'down_vht_model', 'filter' => false, 'type' => 'raw', 'value' => '$data["down_vht_make"]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Down Cab Type'),
                                        array('name' => 'up_bkg_pickup_date', 'filter' => false,
                                            'value' => function ($data) {
                                                return DateTimeFormat::DateTimeToLocale($data["up_bkg_pickup_date"]) . "   ( " . Filter::getDurationbyMinute($data["up_booking_duration"]) . " )";
                                            },
                                            'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'UP Pickup Date/Time'),
                                        array('name' => 'down_bkg_pickup_date', 'filter' => false,
                                            'value' => function ($data) {
                                                return DateTimeFormat::DateTimeToLocale($data["down_bkg_pickup_date"]);
                                            },
                                            'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'DN Pickup Date/Time'),
										array('name' => 'MatchScore', 'type'=>'raw', 'value'=> function($data){
												//.'<br>'."DS:{$data['DurationScore']} - CS:{$data['same_cab_type']} - SS:{$data['source_matching_dest']} - DS{$data['dest_matching_source']} - SAS:{$data['source_matching_adv_amt']} - DAS:{$data['dest_matching_adv_amt']}";
												return $data['MatchScore']; //
										}, 'filter' => false,
                                            'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Match Score (%)'),
                                        array('name' => 'bcbTypeMatched', 'filter' => CHtml::activeCheckBoxList($model, 'bcbTypeMatched', array('0' => 'To Be Match', '1' => 'Matched')),
                                            'value' => function($data) {
                                                if ($data["bcbTypeMatched"] == 1) {
                                                    $bcbType = 'Matched';
                                                } else {
                                                    $bcbType = 'To Be Matched';
                                                }
                                                return $bcbType;
                                            },
                                            'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Status'),
                                        array(
                                            'header' => 'Action',
                                            'class' => 'CButtonColumn',
                                            'htmlOptions' => array('style' => 'white-space:nowrap;text-align: center'),
                                            'headerHtmlOptions' => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
                                            'template' => '{matchtrip}',
                                            'buttons' => array(
                                                'matchtrip' => array(
                                                    'click' => 'function(e){
                                                            $href = $(this).attr(\'href\');
                                                            jQuery.ajax({type: "GET",
                                                                url: $href,
                                                                success: function (data){
                                                                    box = bootbox.dialog({
                                                                        message: data,
                                                                        title: "Match List",
                                                                        size: "large",
                                                                        onEscape: function () {

                                                                            // user pressed escape
                                                                        },
                                                                    });
                                                                }
                                                            });
                                                                    return false;
                                                        }',
                                                    'url' => 'Yii::app()->createUrl("rcsr/booking/matchtrip", array("up_bkg_id" => $data["up_bkg_id"],"down_bkg_id" => $data["down_bkg_id"]))',
                                                    'imageUrl' => false,
                                                    'visible' => '$data["bcbTypeMatched"] == 0',
                                                    'label' => '<i class="fa fa-check"></i>',
                                                    'options' => array('style' => 'margin: 4px', 'data-placement' => 'left', 'class' => 'btn btn-primary btn-sm mb5 mr5', 'title' => 'Match Trip'),
                                                ),
                                                'htmlOptions' => array('class' => 'center'),
                                            ))
                                )));
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
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


</script>

