<div class="container-fluid">
    <div class="row"> 
        <?php
        if (!empty($dataProvider))
        {
            $this->widget('booster.widgets.TbGridView', array(
                'responsiveTable' => true,
                'dataProvider'    => $dataProvider,
                'template'        => "<div class='panel-heading'><div class='row m0'>
                                     <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                     </div></div>
                                     <div class='panel-body table-responsive'>{items}</div>
                                     <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
                'itemsCssClass'   => 'table table-striped table-bordered dataTable mb0',
                'htmlOptions'     => array('class' => 'panel panel-primary  compact'),
                'columns'         => array(
                    array('name'  => 'scq_id', 'value' => function ($data) {
                            $result = ServiceCallQueue::getPrevAndForwardScq($data['flwUpId']);
                            if ($data['flwUpId'] > 0)
                            {
                                echo CHtml::link($data['flwUpId'], Yii::app()->createUrl("admin/scq/view", ["id" => $data['flwUpId']]), ['target' => '_blank']);
                                if ($result['prevScq'] > 0 || $result['nextScq'] > 0)
                                {
                                    echo "<br> Preceded/Followed By: <br>";
                                }
                                if ($result['prevScq'] > 0 && $result['nextScq'] > 0)
                                {
                                    echo CHtml::link($result['prevScq'], Yii::app()->createUrl("admin/scq/view", ["id" => $result['prevScq']]), ['target' => '_blank']) . "( PREV ) , ";
                                    echo CHtml::link($result['nextScq'], Yii::app()->createUrl("admin/scq/view", ["id" => $result['nextScq']]), ['target' => '_blank']) . "( NEXT ) ";
                                }
                                elseif ($result['prevScq'] > 0)
                                {
                                    echo CHtml::link($result['prevScq'], Yii::app()->createUrl("admin/scq/view", ["id" => $result['prevScq']]), ['target' => '_blank']) . "( PREV )  ";
                                }
                                elseif ($result['nextScq'] > 0)
                                {
                                    echo CHtml::link($result['nextScq'], Yii::app()->createUrl("admin/scq/view", ["id" => $result['nextScq']]), ['target' => '_blank']) . "( NEXT ) ";
                                }
                                else
                                {
                                    echo " ";
                                }
                            }
                        }, 'sortable'          => true,
                        'headerHtmlOptions' => array('class' => 'col-xs-3'), 'header'            => 'ID'),
                    array('headerHtmlOptions' => array('class' => 'col-xs-2'), 'name'              => 'Contact Name', 'type'              => 'raw', 'value'             => function ($data) {


                            if ($data['cttId'] != '')
                            {
                                echo CHtml::link($data['contactName'] . "(" . $data['cttId'] . ")", Yii::app()->createUrl("admin/contact/form", ["ctt_id" => $data['cttId']]), ["onclick" => "", 'target' => '_blank']) . "<br>";
                            }
                            if ($data['cr_is_partner'] != null && $data['cr_is_partner'] != "")
                            {
                                echo CHtml::link("View Agent", Yii::app()->createUrl("admin/agent/view", ["agent" => $data['cr_is_partner']]), ["onclick" => "", 'target' => '_blank']) . "<br>";
                            }
                            if ($data['cr_is_driver'] != null && $data['cr_is_driver'] != "")
                            {
                                echo CHtml::link("View Driver", Yii::app()->createUrl("admin/driver/view", ["id" => $data['cr_is_driver']]), ["onclick" => "", 'target' => '_blank']) . "<br>";
                            }
                            if ($data['cr_is_vendor'] != null && $data['cr_is_vendor'] != "")
                            {
                                echo CHtml::link("View Vendor", Yii::app()->createUrl("admin/vendor/view", ["id" => $data['cr_is_vendor']]), ["onclick" => "", 'target' => '_blank']) . "<br>";
                            }
                        }),
                    array('name'  => 'scq_to_be_followed_up_with_value', 'value' =>
                        function ($data) {
                            if ($data["scq_to_be_followed_up_with_type"] == 2 && $data["scq_to_be_followed_up_with_value"] > 0)
                            {
                                echo $data["scq_to_be_followed_up_with_value"];
                            }
                            else if ($data["scq_to_be_followed_up_with_type"] == 1 && $data["scq_to_be_followed_up_with_value"] > 0)
                            {
                                $arrPhoneByPriority = Contact::getPhoneNoByPriority($data["scq_to_be_followed_up_with_value"]);
                                echo $arrPhoneByPriority['phn_phone_country_code'] . " " . $arrPhoneByPriority['phn_phone_no'];
                            }
                            else
                            {
                                if ($data['cttId'] != '')
                                {
                                    $arrPhoneByPriority = Contact::getPhoneNoByPriority($data['cttId']);
                                    echo $arrPhoneByPriority['phn_phone_country_code'] . " " . $arrPhoneByPriority['phn_phone_no'];
                                }
                                else
                                {
                                    echo "NA";
                                }
                            }
                        },
                        'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header'            => 'Contact No.'),
                    array('name'  => 'tea_name', 'value' =>
                        function ($data) {

                            if ($data["teamName"] != null)
                            {
                                echo $data["teamName"];
                            }
                            else
                            {
                                if ($data['scq_to_be_followed_up_by_type'] == 2)
                                {
                                    $adminDetails = Admins::model()->getById($data['scq_to_be_followed_up_by_id'], 1);
                                    echo $adminDetails['gozen'];
                                }
                            }
                        },
                        'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header'            => 'Team Name/CSR'),
                    array('name' => 'dataSource', 'value' => '$data["dataSource"]', 'headerHtmlOptions' => array('class' => ''), 'header' => 'Data Source'),
                    array('headerHtmlOptions' => array('class' => 'col-xs-3'), 'name'              => 'Reference Id', 'type'              => 'raw', 'value'             => function ($data) {
                            if ($data['followUpTypeId'] == 30 || $data['followUpTypeId'] == 15)
                            {
                                $jsonDecode      = json_decode($data['scq_additional_param']);
                                $driverContactId = $jsonDecode->driverContactId;
                                if ($driverContactId > 0)
                                {
                                    echo CHtml::link("Driver Pending Docs", Yii::app()->createUrl("admin/driver/docapprovallist", ["ctt_id" => $driverContactId]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
                                    echo "<br>";
                                }
                                $vehicleIds = $jsonDecode->vehicleIds;
                                if ($vehicleIds > 0)
                                {
                                    echo CHtml::link("Vehicle Pending Docs", Yii::app()->createUrl("admin/vehicle/docapprovallist", ["cabid" => $vehicleIds]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
                                }
                                $vendorId = $jsonDecode->VendorUpdateService;
                                if ($vendorId > 0)
                                {
                                    echo CHtml::link("Vendor Update Services", Yii::app()->createUrl("admin/vendor/add", ["agtid" => $vendorId]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank', "title" => "Vendor has requested for his changed in service update"]);
                                }
                            }
                            else if ($data['followUpTypeId'] != '' && ($data['followUpTypeId'] == 2 || $data['followUpTypeId'] == 4 || ($data['followUpTypeId'] == 1 && $data['scq_ref_type'] == 2 ) || ($data['followUpTypeId'] == 9 || $data['followUpRefId'] != null ) ))
                            {
                                echo CHtml::link($data['followUpRefId'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['followUpRefId']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
                                echo "<br>";
                            }
                            else if ($data['followUpTypeId'] != '' && $data['followUpTypeId'] == 1)
                            {
                                echo CHtml::link('Click To Book', Yii::app()->createUrl("admin/booking/create"), ["onclick" => "", 'target' => '_blank']);
                            }
                            else if ($data['followUpTypeId'] != '' && $data['followUpTypeId'] == 3)
                            {
                                echo CHtml::link('Add Vendor', Yii::app()->createUrl("admin/vendor/add"), ["onclick" => "", 'target' => '_blank']);
                            }
                        }),
                    array('name' => 'fwp_ref_type', 'value' => '$data["followUpType"]', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Reference Type'),
                    array('name' => 'callerType', 'value' => '$data["callerType"]', 'headerHtmlOptions' => array('class' => 'col-xs-5'), 'header' => 'Caller Type'),
                    array('name' => 'fwp_desc', 'value' => '$data["callerQuery"]', 'headerHtmlOptions' => array('class' => 'col-xs-5'), 'header' => 'Description'),
                    //array('name' => 'fwp_assigned_csr', 'value' => '$data["csrId"]', 'headerHtmlOptions' => array('class' => 'col-xs-5'), 'header' => 'Assigned CSR'),
                    array('name' => 'csrName', 'value' => '$data["csrName"]', 'headerHtmlOptions' => array('class' => 'col-xs-5'), 'header' => 'CSR Name'),
                    array('name' => 'fwp_csr_remarks', 'value' => '$data["flwRemarks"]', 'headerHtmlOptions' => array('class' => 'col-xs-5'), 'header' => 'Csr Remarks'),
                    array('name' => 'fwp_prefered_time', 'value' => '$data["flwPreferedTime"]', 'headerHtmlOptions' => array('class' => 'col-xs-5'), 'header' => 'Time'),
                    array('name' => 'fwp_follow_up_status', 'value' => '$data["followUpStatus"]', 'headerHtmlOptions' => array('class' => 'col-xs-5'), 'header' => 'Status'),
                    array(
                        'header'            => 'Action',
                        'class'             => 'CButtonColumn',
                        'htmlOptions'       => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
                        'headerHtmlOptions' => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
                        'template'          => '{add1}',
                        'buttons'           => array(
                            'add1' => array(
                                'click'    => 'function(e){
                                                    $href = $(this).attr(\'href\');
                                                    $.ajax({ 
                                                    url: $href,
                                                    success: function (data)
                                                    {
                                                        var box = bootbox.dialog({
							                                 message: data,
                                                            title: "Close follow up",
															size: "large",
                                                            onEscape: function () {

                                                                window.location.reload();
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
                                'visible'  => '($data["isMycall"] == "1" && $data["scq_status"] == 1) ? true : false',
                                'url'      => 'Yii::app()->createUrl("admin/scq/add", array("Id" => $data["flwUpId"],"isMycall"=>$data["isMycall"]))',
                                'imageUrl' => false,
                                'label'    => '<i class="fa fa-plus"></i>',
                                'options'  => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs addfollow p0', 'title' => 'Add'),
                            ),
                        ))
            )));
        }
        ?> 
    </div> 
</div>



