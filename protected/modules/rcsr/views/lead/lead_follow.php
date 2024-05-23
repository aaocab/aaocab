<?php
$v = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/csrbooking.js?v=' . $v);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/route.js?v=' . $v);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $v);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promo.js?v=' . $v);
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');

$callback = Yii::app()->request->getParam('callback', 'loadList');


//$title = ($model->isNewRecord) ? "Create Lead" : "Lead follow up";
$js = "window.$callback();";


Yii::app()->clientScript->registerScriptFile('https://maps.googleapis.com/maps/api/js?v3', CClientScript::POS_HEAD);

if (Yii::app()->request->isAjaxRequest)
{
    $cls = "col-xs-12";
}
else
{
    $cls = "col-lg-9 col-md-8 col-sm-10";
}
$adminlist = Admins::model()->findNameList();

//$datacity = Cities::model()->getJSON();
$carTypeData        = VehicleTypes::model()->getParentVehicleTypes(1); //VehicleTypes::model()->getVehicleTypeList();
$bookingType        = Booking::model()->getBookingType();
$source             = BookingTemp::model()->getSourceIndexed('edit', $model->bkg_lead_source);
$sourcelist         = BookingTemp::model()->getSourceIndexed();
$countrycode        = Yii::app()->params['countrycode'];
$ccode              = (int) str_replace('+', '', $countrycode);
$followupStatus     = BookingTemp::model()->getLeadStatus('follow');
$followupStatusList = BookingTemp::model()->getLeadStatus();
$selectizeOptions   = ['create'             => false, 'persist'            => true, 'selectOnTab'        => true,
    'createOnBlur'       => true, 'dropdownParent'     => 'body',
    'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'      => 'id',
    'openOnFocus'        => true, 'preload'            => false,
    'labelField'         => 'text', 'valueField'         => 'id', 'searchField'        => 'text', 'closeAfterSelect'   => true,
    'addPrecedence'      => false,];
?>
<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
    .form-horizontal .form-group{
        margin-left: 0;margin-right: 0
    }
    h4.modal-title{
        font-size: 17px;
    }
    .selectize-input {
        min-width: 0px !important; 
        width: 30% !important;
    }
    .datepicker.datepicker-dropdown.dropdown-menu ,
    .bootstrap-timepicker-widget.dropdown-menu,
    .yii-selectize.selectize-dropdown
    {z-index: 9999 !important;}
    div .comments {
        border-bottom:1px #333 solid;
        padding:3px;
        line-height: 14px;
        font-weight: normal;
    }
    div .comments .comment {
        padding:3px;max-width:100%
    }
    div .comments .footer {
        padding:2px 5px;
        color: #888;
        text-align: right;
        font-style: italic;
        font-size: 0.85em;
        height: auto;
        width: auto;
    }
    .remarkbox{
        width: 100%; 
        padding: 5px;  
        overflow: auto; 
        line-height: 1.4em; 
        font: normal arial; 
        border-radius: 5px; 
        -moz-border-radius: 5px; 
        border: 1px #aaa solid;
    }
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
    .yii-selectize {

        min-width: 180px!important;
    }

</style>

<?
/* @var $model BookingTemp */
?>
<div class="row">
    <div class="<?= $cls ?>" style=" float: none; margin: auto">
        <?php
        $form               = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                     => 'lead-form', 'enableClientValidation' => true,
            'clientOptions'          => array(
                'validateOnSubmit' => true,
                'errorCssClass'    => 'has-error',
                'afterValidate'    => 'js:function(form,data,hasError){
                    if(!hasError){
                    
                    $btype=$("#' . CHtml::activeId($model, 'bkg_booking_type') . '");
                    
                    $retdate = $("#' . CHtml::activeId($model, 'bkg_return_date_date') . '");
                         $contact = $("#' . CHtml::activeId($model, 'bkg_contact_no') . '");
                 $code = $("#' . CHtml::activeId($model, 'bkg_country_code') . '");
                 $email = $("#' . CHtml::activeId($model, 'bkg_user_email') . '");    
                 $cab = $("#' . CHtml::activeId($model, 'bkg_vehicle_type_id') . '");   
                 $dest = $("#' . CHtml::activeId($model, 'bkg_to_city_id') . '");  
                
                   
                   
                    if($cab.val() == "")
                    {
                        $cab.focus();
                        $("#BookingTemp_bkg_vehicle_type_id_em_").text("");
                        $("#BookingTemp_bkg_vehicle_type_id_em_").text("Please select a cab");
                        $("#BookingTemp_bkg_vehicle_type_id_em_").show();                                          
                        return false;
                    }   
    ' . /*
                  //                    if($btype.val() == "2" && $retdate.val().trim() == "")
                  //                        {
                  //                            $retdate.focus();
                  //                            $("#BookingTemp_bkg_return_date_date_em_").text("");
                  //                            $("#BookingTemp_bkg_return_date_date_em_").text("Please enter return date");
                  //                            $("#BookingTemp_bkg_return_date_date_em_").show();
                  //                            return false;
                  //                        } */'
                        $.ajax({
                        "type":"POST",
                        "dataType":"json",
                        "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                        "data":form.serialize(),
                        "success":function(data1){
                        if(!$.isEmptyObject(data1) && data1.success==true){  
                        if(data1.unbkid>0){                      
                            updateGrid(1);
                            $(".bootbox").modal("hide");
                            }       
                        else{               
                         
                            location.href="' . CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/lead/report')) . '";
                            }
                        }
                        else{ 
                            settings=form.data(\'settings\');
                            var data = data1.data;
                            $.each (settings.attributes, function (i) {
                              $.fn.yiiactiveform.updateInput (settings.attributes[i], data, form);
                            });
                            $.fn.yiiactiveform.updateSummary(form, data1);
                            }},
                        });
                    }
                }'
            ),
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // See class documentation of CActiveForm for details on this,
            // you need to use the performAjaxValidation()-method described there.
            'enableAjaxValidation'   => false,
            'errorMessageCssClass'   => 'help-block',
            'htmlOptions'            => array(
                'class' => 'form-horizontal',
            ),
        ));
        /* @var $form TbActiveForm */
        ?>
        <div class="row">
            <div class="col-xs-12 col-md-12 col-lg-6">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-default main-tab1">
                            <?php echo CHtml::errorSummary($model); ?>
                            <?
                            if ($model->isLeadlocked() == 1)
                            {
                                ?>
                                <div class="row mt10 text-center"> 
                                    <div class="col-xs-12">
                                        Lead locked by <?= $adminlist[$model->bkg_locked_by] ?> for <?= $model->bkg_lock_timeout ?>
                                    </div>
                                </div>
                            <? } ?>
                            <?
                            if ($model->bkg_assigned_to != '')
                            {
                                ?>
                                <div class="row mt10 text-center"> 
                                    <div class="col-xs-12">
                                        Lead assigned to <?= $adminlist[$model->bkg_assigned_to] ?>.
                                    </div>
                                </div>
                            <? } ?>
                            <?= $form->hiddenField($model, 'bkg_ref_booking_id', array('readonly' => true)) ?>
                            <?= $form->hiddenField($model, 'bkg_id', array('readonly' => true)) ?>
                            <div class="panel-body panel-border">
                                <h3 class="pb10 mt0">Booking Information</h3>
                                <div class="row new-tab-border-b mb15">
                                    <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Booking Type</label>
                                                <?
                                                $dataBookType    = VehicleTypes::model()->getJSON($bookingType);
                                                $this->widget('booster.widgets.TbSelect2', array(
                                                    'model'          => $model,
                                                    'attribute'      => 'bkg_booking_type',
                                                    'val'            => $model->bkg_booking_type,
                                                    'asDropDownList' => FALSE,
                                                    'options'        => array('data' => new CJavaScriptExpression($dataBookType)),
                                                    'htmlOptions'    => array('style' => 'width:100%', 'placeholder' => 'Select Booking Type')
                                                ));
//$form->dropDownList($model, 'bkg_booking_type', $bookingType, array('empty' => 'Select Booking Type', 'label' => 'Booking Type', 'class' => 'form-control'))
                                                ?>
                                                <span class="has-error">
                                                    <? echo $form->error($model, 'bkg_booking_type'); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Car Model</label>
                                                <?php
                                                $carTypeDataJSON = VehicleTypes::model()->getJSON($carTypeData);
                                                $this->widget('booster.widgets.TbSelect2', array(
                                                    'model'          => $model,
                                                    'attribute'      => 'bkg_vehicle_type_id',
                                                    'val'            => $model->bkg_vehicle_type_id,
                                                    //'asDropDownList' => FALSE,
                                                    //'data' => $carTypeData,
                                                    'asDropDownList' => FALSE,
                                                    'options'        => array('data' => new CJavaScriptExpression($carTypeDataJSON)),
                                                    'htmlOptions'    => array('style'       => 'width:100%',
                                                        'placeholder' => 'Select Car Type', 'label'       => 'Car Model')
                                                ));

                                                // echo $form->dropDownListGroup($model, 'bkg_vehicle_type_id', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control border-none border-radius'], 'data' => ['' => 'Select Cab type'] + $cartype)))
                                                ?>
                                                <span class="has-error"><? echo $form->error($model, 'bkg_vehicle_type_id'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row   ">
                                    <div class="col-xs-12 col-sm-6  ">
                                        <div class="form-group cityinput">
                                            <label>Source City</label>
                                            <?php
//                                                $this->widget('booster.widgets.TbSelect2', array(
//                                                    'model' => $model,
//                                                    'attribute' => 'bkg_from_city_id',
//                                                    'val' => $model->bkg_from_city_id,
//                                                    'asDropDownList' => FALSE,
//                                                    // 'data' => $cityList2,
//                                                    'options' => array('data' => new CJavaScriptExpression('$cityList')),
//                                                    'htmlOptions' => array('style' => 'width:100%',
//                                                     'placeholder' => 'Select Source City')
//                                                ));
                                            $this->widget('ext.yii-selectize.YiiSelectize', array(
                                                'model'            => $model,
                                                'attribute'        => 'bkg_from_city_id',
                                                'useWithBootstrap' => true,
                                                "placeholder"      => "Select Source City",
                                                'fullWidth'        => false,
                                                'htmlOptions'      => array('width' => '100%',
                                                //  'id' => 'from_city_id1'
                                                ),
                                                'defaultOptions'   => $selectizeOptions + array(
                                            'onInitialize' => "js:function(){
                                  populateSource(this, '{$model->bkg_from_city_id}');
                                                }",
                                            'load'         => "js:function(query, callback){
                        loadSource(query, callback);
                        }",
                                            'render'       => "js:{
                            option: function(item, escape){
                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                            },
                            option_create: function(data, escape){
                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                            }
                        }",
                                                ),
                                            ));
                                            ?>
                                            <span class="has-error"><? echo $form->error($model, 'bkg_from_city_id'); ?></span>

                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group cityinput">
                                            <label>Destination City</label>
                                            <?php
//                                                $this->widget('booster.widgets.TbSelect2', array(
//                                                    'model' => $model,
//                                                    'attribute' => 'bkg_to_city_id',
//                                                    'val' => $model->bkg_to_city_id,
//                                                    'asDropDownList' => FALSE,
//                                                    //'data' => $cityList2,
//                                                    'options' => array('data' => new CJavaScriptExpression('$cityList')),
//                                                    'htmlOptions' => array('style' => 'width:100%', 
//                                                    'placeholder' => 'Select Destination City')
//                                                ));
                                            $this->widget('ext.yii-selectize.YiiSelectize', array(
                                                'model'            => $model,
                                                'attribute'        => 'bkg_to_city_id',
                                                'useWithBootstrap' => true,
                                                "placeholder"      => "Select Destination City",
                                                'fullWidth'        => false,
                                                'htmlOptions'      => array('width' => '100%',
                                                //  'id' => 'from_city_id1'
                                                ),
                                                'defaultOptions'   => $selectizeOptions + array(
                                            'onInitialize' => "js:function(){
                                  populateSource(this, '{$model->bkg_to_city_id}');
                                                }",
                                            'load'         => "js:function(query, callback){
                                loadSource(query, callback);
                                }",
                                            'render'       => "js:{
                                option: function(item, escape){
                                return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                },
                                option_create: function(data, escape){
                                return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                }
                                }",
                                                ),
                                            ));
                                            ?>
                                            <span class="has-error"><? echo $form->error($model, 'bkg_to_city_id'); ?></span>

                                        </div>
                                    </div>
                                </div>
                                <div class="row new-tab-border-b mt10">
                                    <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label> Estimated distance :  <span id="dist"><?= $model->bkg_trip_distance; ?></span></label>
                                                <input type="hidden" id="trip_distance">
                                                <?= $form->hiddenField($model, 'bkg_trip_distance', array()) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label> Estimated duration : <span id="time"><?= $model->bkg_trip_duration; ?></span></label>
                                                <?= $form->hiddenField($model, 'bkg_trip_duration', array()) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row new-tab-border-b mt10">
                                    <div class="col-xs-12"><?
                                        if ($model->bkg_route_data != '')
                                        {
                                            $route_data = json_decode($model->bkg_route_data, true);

                                            foreach ($route_data as $k => $v)
                                            {
                                                $bookingRoute             = new BookingRoute();
                                                $bookingRoute->attributes = $v;
                                                $bookingRoutes[]          = $bookingRoute;
                                            }
                                            ?>
                                            <div class="table-responsive">
                                                <table id="summary" class="table table-bordered">

                                                    <tr class="tr_gray">
                                                        <th>From</th>
                                                        <th>To</th>
                                                        <th>Departure Date Time</th>
                                                        <th>Distance</th>
                                                        <th>Duration</th> 
                                                        <th>Days</th>
                                                    </tr>
                                                    <tbody>
                                                        <?
                                                        $last  = 0;
                                                        $tdays = '';
                                                        foreach ($bookingRoutes as $k => $brt)
                                                        {
                                                            if ($k == 0)
                                                            {
                                                                $datediff1 = 0;
                                                            }
                                                            else
                                                            {
                                                                $datediff1 = strtotime($bookingRoutes[$k]->brt_pickup_datetime) - strtotime($bookingRoutes[$k - 1]->brt_pickup_datetime);
                                                            }
                                                            $tdays                       = floor(($datediff1 / 3600) / 24) + 1;
                                                            $last                        = $k;
                                                            ?>
                                                            <tr>
                                                                <td><?= $brt->brtFromCity->cty_name ?>
                                                                    <br><?= $brt->brt_from_location ?>
                                                                </td>
                                                                <td><?= $brt->brtToCity->cty_name ?>
                                                                    <br><?= $brt->brt_to_location ?>
                                                                </td>
                                                                <td><?= DateTimeFormat::DateTimeToDatePicker($brt->brt_pickup_datetime); ?> <?= DateTimeFormat::DateTimeToTimePicker($brt->brt_pickup_datetime); ?></td>
                                                                <td><?= $brt->brt_trip_distance ?> Km</td>
                                                                <td><?= round($brt->brt_trip_duration / 60) . ' hours'; ?></td>
                                                                <td><?= $tdays ?></td>
                                                            </tr>

                                                            <?
                                                            $model->bkg_return_date_date = DateTimeFormat::DateTimeToDatePicker($brt->brt_pickup_datetime);
                                                            $model->bkg_return_date_time = DateTimeFormat::DateTimeToTimePicker($brt->brt_pickup_datetime);
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?= $form->hiddenField($model, 'bkg_return_date_date', array()) ?>
                                            <?= $form->hiddenField($model, 'bkg_return_date_time', array()) ?>
                                            <?
                                        }
                                        ?>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Pickup Date</label>
                                                <? $strpickdate = ($model->bkg_pickup_date == '') ? date('Y-m-d H:i:s', strtotime('+4 hour')) : $model->bkg_pickup_date; ?>

                                                <?=
                                                $form->datePickerGroup($model, 'bkg_pickup_date_date', array('label'         => '',
                                                    'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Pickup Date', 'value' => DateTimeFormat::DateTimeToDatePicker($strpickdate))), 'prepend'       => '<i class="fa fa-calendar"></i>'));
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Pickup Time</label>
                                                <?
                                                echo $form->timePickerGroup($model, 'bkg_pickup_date_time', array('label'         => '',
                                                    'widgetOptions' => array('id' => CHtml::activeId($model, "bkg_pickup_date_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Pickup Time', 'value' => date('h:i A', strtotime($strpickdate))))));
                                                ?>   
                                            </div>
                                        </div>
                                    </div>
                                    <div id="errordivpdate" class="ml15 mt10 " style="color:#da4455"></div>
                                </div>
                                <div class="row ">
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Pick up Location</label>
                                                <?= $form->textAreaGroup($model, 'bkg_pickup_address', array('label' => '', 'widgetOptions' => array('htmlOptions' => array()))) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Drop off Location</label>
                                                <?= $form->textAreaGroup($model, 'bkg_drop_address', array('label' => '', 'widgetOptions' => array('htmlOptions' => array()))) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Amount</label>
                                                <?= $form->textFieldGroup($model, 'bkg_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Net Charge']))) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Sources</label>
                                                <?php
                                                $datainfo    = VehicleTypes::model()->getJSON($source);

                                                $this->widget('booster.widgets.TbSelect2', array(
                                                    'model'          => $model,
                                                    //    'attribute' => 'bkg_log_type_txt',
                                                    //   'val' => "'" . $model->bkg_log_type_txt . "'",
                                                    'attribute'      => 'bkg_lead_source',
                                                    'val'            => $model->bkg_lead_source,
                                                    'asDropDownList' => FALSE,
                                                    'options'        => array('data' => new CJavaScriptExpression($datainfo)),
                                                    'htmlOptions'    => array('style' => 'width:100%', 'placeholder' => 'Select Source')
                                                ));
                                                ?>
                                                <span class="has-error"><? echo $form->error($model, 'bkg_lead_source'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 col-lg-6">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-default main-tab1">
                            <div class="panel-body panel-border">
                                <h3 class=" mt0">Customer Information</h3>
                                <div class="row new-tab-border-b">
                                    <div class="col-xs-12 col-sm-6">
                                        <label>First Name</label>
                                        <?= $form->textFieldGroup($model, 'bkg_user_name', array('label' => '')) ?>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <label>Last Name</label>
                                        <?= $form->textFieldGroup($model, 'bkg_user_lname', array('label' => '')) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                        <label>Contact Number</label>
                                        <div class="row">
                                            <div class="form-group ">
                                                <div class="col-xs-3 col-sm-4">                                          
                                                    <?php
                                                    $this->widget('ext.yii-selectize.YiiSelectize', array(
                                                        'model'            => $model,
                                                        'attribute'        => 'bkg_country_code',
                                                        'useWithBootstrap' => true,
                                                        "placeholder"      => "Code",
                                                        'fullWidth'        => false,
                                                        'htmlOptions'      => array(
                                                        ),
                                                        'defaultOptions'   => array(
                                                            'create'             => false,
                                                            'persist'            => true,
                                                            'selectOnTab'        => true,
                                                            'createOnBlur'       => true,
                                                            'dropdownParent'     => 'body',
                                                            'optgroupValueField' => 'id',
                                                            'optgroupLabelField' => 'pcode',
                                                            'optgroupField'      => 'pcode',
                                                            'openOnFocus'        => true,
                                                            'labelField'         => 'pcode',
                                                            'valueField'         => 'pcode',
                                                            'searchField'        => 'name',
                                                            //   'sortField' => 'js:[{field:"order",direction:"asc"}]',
                                                            'closeAfterSelect'   => true,
                                                            'addPrecedence'      => false,
                                                            'onInitialize'       => "js:function(){
                                                this.load(function(callback){
                                                var obj=this;                                
                                                 xhr=$.ajax({
                                         url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('index/country')) . "',
                                         dataType:'json',                  
                                         success:function(results){
                                             obj.enable();
                                             callback(results.data);
                                              obj.setValue('{$model->bkg_country_code}');
                                         },                    
                                         error:function(){
                                             callback();
                                         }});
                                        });
                                        }",
                                                            'render'             => "js:{
                                             option: function(item, escape){                      
                                             return '<div><span class=\"\">' + escape(item.name) +'</span></div>';                          
                                        },
                                    option_create: function(data, escape){
                                    return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
                                            }
                                        }",
                                                        ),
                                                    ));
                                                    ?>
                                                </div>
                                                <div class="col-xs-9 col-sm-8 ">
                                                    <?= $form->textFieldGroup($model, 'bkg_contact_no', array('label' => '', 'widgetOptions' => array('class' => ''))) ?>
                                                    <div id="errordivmob" style="color:#da4455"></div>
                                                </div>                                    
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Alternate Contact Number</label>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <div class="col-xs-3 col-sm-4">
                                                            <?php
                                                            $this->widget('ext.yii-selectize.YiiSelectize', array(
                                                                'model'            => $model,
                                                                'attribute'        => 'bkg_alt_country_code',
                                                                'useWithBootstrap' => true,
                                                                "placeholder"      => "Code",
                                                                'fullWidth'        => false,
                                                                'htmlOptions'      => array(
                                                                ),
                                                                'defaultOptions'   => array(
                                                                    'create'             => false,
                                                                    'persist'            => true,
                                                                    'selectOnTab'        => true,
                                                                    'createOnBlur'       => true,
                                                                    'dropdownParent'     => 'body',
                                                                    'optgroupValueField' => 'id',
                                                                    'optgroupLabelField' => 'pcode',
                                                                    'optgroupField'      => 'pcode',
                                                                    'openOnFocus'        => true,
                                                                    'labelField'         => 'pcode',
                                                                    'valueField'         => 'pcode',
                                                                    'searchField'        => 'name',
                                                                    'closeAfterSelect'   => true,
                                                                    'addPrecedence'      => false,
                                                                    'onInitialize'       => "js:function(){
                                                            this.load(function(callback){
                                                            var obj=this;                                
                                                             xhr=$.ajax({
                                                     url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('index/country')) . "',
                                                     dataType:'json',                  
                                                     success:function(results){
                                                         obj.enable();
                                                         callback(results.data);
                                                         obj.setValue('{$model->bkg_alt_country_code}');

                                                     },                    
                                                     error:function(){
                                                         callback();
                                                     }});
                                                     });
                                                     }",
                                                                    'render'             => "js:{
                                                    option: function(item, escape){
                                                    return '<div><span class=\"\">' + escape(item.name) +'</span></div>';
                                                    },
                                                                option_create: function(data, escape){
                                                                return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
                                                }}",
                                                                ),
                                                            ));
                                                            ?>
                                                        </div>
                                                        <div class="col-xs-9 col-sm-8 pl0">
                                                            <?= $form->textFieldGroup($model, 'bkg_alternate_contact', array('label' => '', 'widgetOptions' => array())) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-xs-12 col-sm-6">
                                        <label>User Email</label>
                                        <?= $form->textFieldGroup($model, 'bkg_user_email', array('label' => '')) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="panel panel-default main-tab1">
                            <div class="panel-body panel-border">
                                <h3 class="pb10 mt0">Follow up Information</h3>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Reminder Date</label>
                                                <? $strcallbackdate = ($model->bkg_follow_up_reminder == '') ? date('Y-m-d H:i:s', strtotime('+1 hour')) : $model->bkg_follow_up_reminder; ?>
                                                <?=
                                                $form->datePickerGroup($model, 'bkg_follow_up_reminder_date', array('label'         => '',
                                                    'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Reminder Date', 'value' => DateTimeFormat::DateTimeToDatePicker($strcallbackdate))), 'prepend'       => '<i class="fa fa-calendar"></i>'));
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Reminder Time</label>
                                                <?
                                                echo $form->timePickerGroup($model, 'bkg_follow_up_reminder_time', array('label'         => '',
                                                    'widgetOptions' => array('id' => CHtml::activeId($model, "bkg_follow_up_reminder_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Reminder Time', 'value' => date('h:i A', strtotime($strcallbackdate))))));
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Follow up status</label>
                                                <?php
                                                $fstatusJson     = VehicleTypes::model()->getJSON($followupStatus);
//
                                                $this->widget('booster.widgets.TbSelect2', array(
                                                    'model'          => $model,
                                                    'attribute'      => 'bkg_follow_up_status',
                                                    'val'            => $model->bkg_follow_up_status,
                                                    'asDropDownList' => FALSE,
                                                    'options'        => array('data' => new CJavaScriptExpression($fstatusJson)),
                                                    //'data' => $followupStatus,
                                                    'htmlOptions'    => array('style' => 'width:100%', 'placeholder' => 'Select Follow up status', 'label' => 'Select Follow up status')
                                                ));

// echo $form->dropDownListGroup($model, 'bkg_vehicle_type_id', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control border-none border-radius'], 'data' => ['' => 'Select Cab type'] + $cartype)))
                                                ?>
                                                <span class="has-error"><? echo $form->error($model, 'bkg_follow_up_status'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                    if ($model->bkg_log_comment != '')
                                    {
                                        ?>
                                        <div class="row">                         
                                            <div class="col-xs-12">
                                                <label class="control-label" for="type">User Comment</label>
                                                <div class="remarkbox">
                                                    <?= $model->bkg_log_comment; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?
                                    }
                                    ?>
                                </div>
                                <div class="row mt10">
                                    <div class="col-xs-12">
                                        <label>Follow up comment</label>
                                        <?= $form->textAreaGroup($model, 'new_follow_up_comment', array('label' => '')) ?>
                                    </div>
                                </div>
                                <?
                                if ($model->bkg_follow_up_comment != '')
                                {
                                    ?>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <label class="control-label" for="type">Previous Comments</label>
                                            <div class="remarkbox">
                                                <?
                                                if (CJSON::decode($model->bkg_follow_up_comment) != '')
                                                {
                                                    $comment = CJSON::decode($model->bkg_follow_up_comment);
                                                    foreach ($comment as $cm)
                                                    {
                                                        ?>
                                                        <div class="comments">
                                                            <div class="comment"><?= nl2br($cm[2]) ?></div>
                                                            <div class="footer" style="margin-bottom: 5px"><span><?= $sourcelist[$cm[3]] . "</span> | <span>" . date('d/m/Y h:i A', strtotime($cm[1])) . "</span> | <span>" . $followupStatusList[$cm[4]] . "</span> | <span>" . $adminlist[$cm[0]] ?></span>
                                                            </div>
                                                        </div>
                                                        <?
                                                    }
                                                }
                                                else
                                                {
                                                    ?><div class="p10"><?
                                                    echo $model->bkg_follow_up_comment;
                                                    ?></div><?
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div> 
                                <? }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt10">
            <div class="col-xs-12 text-center">
                <?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>
                <?
                if ($unbkid == '')
                {
                    ?>
                    <a class="btn btn-info btn-sm ml5" onclick="convertToBooking()" title="Convert To Booking" style="">Convert To Booking</a>
                <? } ?>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
    <div class="panel-footer text-center hide">
        <div class="btn-group1">
            <a class="btn btn-info btn-sm mb5 mr5" id="bkaction_12615_0" onclick="adminAction(0, 12615, 2);" title="Assign Vendor" style="">Assign CSR</a>
            <a class="btn btn-warning btn-sm mb5 mr5" id="bkaction_12615_1" onclick="adminAction(1, 12615, 2);" title="Customer Cancel" style="">Cancel Lead</a>
            <a class="btn btn-danger btn-sm mb5 mr5" id="bkaction_12615_2" onclick="adminAction(2, 12615, 2);" title="Delete Booking" style="">Delete Lead</a>
            <a class="btn btn-danger btn-sm mb5 mr5" id="bkaction_12615_2" onclick="adminAction(2, 12615, 2);" title="Delete Booking" style="">Convert To Booking</a>
        </div>
    </div>
</div>
<script>
    $('.bootbox').removeAttr('tabindex');
    $('#<?= CHtml::activeId($model, "bkg_pickup_date_time") ?>').val();
    $('#<?= CHtml::activeId($model, "bkg_from_city_id") ?>').change(function () {
        getRoute();
        // getCitynames();
        getAmountbyCitiesnVehicle();
    });
    $('#<?= CHtml::activeId($model, "bkg_to_city_id") ?>').change(function () {
        getRoute();
        //getCitynames();

        getAmountbyCitiesnVehicle();
    });
    $('#<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>').change(function () {
        getCarmodel();
        //getAmountbyCitiesnVehicle();
    });
    $('#<?= CHtml::activeId($model, "bkg_booking_type") ?>').change(function () {
        var $bkg_type = $('#<?= CHtml::activeId($model, "bkg_booking_type") ?>').val();
        if ($bkg_type == '2') {
            $("#return_div").removeClass('hide');
        }
        if ($bkg_type == '1') {
            $('#<?= CHtml::activeId($model, "bkg_return_date_date") ?>').val('');
            $('#<?= CHtml::activeId($model, "bkg_return_date_time") ?>').val('');
            $("#return_div").addClass('hide');
        }
        //  getCitynames();
        getAmountbyCitiesnVehicle();
    });
    $('#<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>').change(function () {
        // getAmount();
        getAmountbyCitiesnVehicle();
        //    getRatePerKM();
    });
    $('#<?= CHtml::activeId($model, "bkg_trip_distance") ?>').change(function () {
        getAmountbyCitiesnVehicle();
    });

    $(document).on("getRouteList", function (event, data) {
        routeList(data);
    });

    function getRoute() {
        if (!$fireChange)
        {
            return false;
        }
        var route = new Route();
        var model = {};
        model.fromCity = $('#<?= CHtml::activeId($model, "bkg_from_city_id") ?>').val();
        model.toCity = $('#<?= CHtml::activeId($model, "bkg_to_city_id") ?>').val();
        model.bookingType = $('#<?= CHtml::activeId($model, "bkg_booking_type") ?>').val();
        model.pickupAddress = $('#<?= CHtml::activeId($model, "bkg_pickup_address") ?>').val();
        model.dropAddress = $('#<?= CHtml::activeId($model, "bkg_drop_address") ?>').val();
        model.pickupDate = $('#<?= CHtml::activeId($model, "bkg_pickup_date_date") ?>').val();
        model.pickupTime = $('#<?= CHtml::activeId($model, "bkg_pickup_date_time") ?>').val();
        route.model = model;
        if (model.fromCity != '' && model.toCity != '' && model.bookingType != '') {

            route.getRouteList();
        }
    }

    function routeList(data)
    {
        if (data.rutid > 0) {
            $('#<?= CHtml::activeId($model, "bkg_route_id") ?>').val(data.data.rutid).change();
            $('#dist').text(data.distance);
            $('#time').text(data.duration);
            $('#<?= CHtml::activeId($model, "bkg_trip_distance") ?>').val(data.data.distance).change();
            $('#<?= CHtml::activeId($model, "bkg_trip_duration") ?>').val(data.data.duration).change();
        } else {
            $('#<?= CHtml::activeId($model, "bkg_route_id") ?>').val('').change();
            $('#<?= CHtml::activeId($model, "bkg_trip_distance") ?>').val(data.data.distance).change();
            $('#<?= CHtml::activeId($model, "bkg_trip_duration") ?>').val(data.data.duration).change();
            $('#dist').text(data.data.distance);
            $('#time').text(data.data.duration);

        }
        getAmountbyCitiesnVehicle();
    }


    function getAmountbyCitiesnVehicle() {
         var csrbooking = new Csrbooking();
        var model = {};
        model.fromCity = $('#<?= CHtml::activeId($model, "bkg_from_city_id") ?>').val();
        model.toCity = $('#<?= CHtml::activeId($model, "bkg_to_city_id") ?>').val();
        model.cabType = $('#<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>').val();
        model.tripDistance = $('#<?= CHtml::activeId($model, "bkg_trip_distance") ?>').val();
        model.pickupAddress = $('#<?= CHtml::activeId($model, "bkg_pickup_address") ?>').val();
        model.dropupAddress = $('#<?= CHtml::activeId($model, "bkg_drop_address") ?>').val();
        model.pickupDate = $('#<?= CHtml::activeId($model, "bkg_pickup_date_date") ?>').val();
        model.pickupTime = $('#<?= CHtml::activeId($model, "bkg_pickup_date_time") ?>').val();
        model.YII_CSRF_TOKEN = $('input[name="YII_CSRF_TOKEN"]').val();
        csrbooking.model = model;
        if (model.fromCity != '' && model.toCity != '' && model.cabType != '')
        {
            $(document).on("getQoute", function (event, data) {
                getQoutation(data);
            });
            csrbooking.getQoute();
        }
    }

    function getQoutation(data)
    {

        var qRouteRates = data.data.quoteddata.routeRates;
//        var qRouteDistance = data.data.quoteddata.routeDistance;
//        var qRouteDuration = data.data.quoteddata.routeDuration;
        $('#<?= CHtml::activeId($model, "bkg_amount") ?>').val(qRouteRates.totalAmount);
        $("#trip_rate").text('');
        if (qRouteRates.ratePerKM > 0) {
            $("#trip_rate").text('Rate : Rs.' + qRouteRates.ratePerKM + ' per km').change();
        }

    }

    $('#BookingTemp_bkg_route_id').bind("change", function () {
        selctRoute();
    });
    $(document).on("getRouteListbyCities", function (event, data) {
        routeCitiesList(data);
    });

    function selctRoute() {
        var city = new City();
        var model = {};
        model.routeId = $('#<?= CHtml::activeId($model, "bkg_route_id") ?>').val();
        if (model.routeId == "")
        {
            return;
        }
        city.model = model;
        city.getRouteListbyCities();
    }

    $fireChange = true;
    function routeCitiesList(data)
    {
        $fireChange = false;
        $("#BookingTemp_bkg_from_city_id").val(data.data.fcity).change();
        $fireChange = true;
        $("#BookingTemp_bkg_to_city_id").val(data.data.tcity).change();
    }
    function getCarmodel() {
        if ($('#<?= CHtml::activeId($model, "bkg_route_id") ?>').val() != '')
        {
            //getCitynames();
            var rtid = $('#<?= CHtml::activeId($model, "bkg_route_id") ?>').val();
            var href = '<?= Yii::app()->createUrl("rcsr/booking/getcarmodel"); ?>';
            $.ajax({
                url: href, dataType: "json",
                data: {"rt_id": rtid},
                "success": function (data) {
                    $('#<?= CHtml::activeId($model, "bkg_amount") ?>').val('0');
                    $('#BookingTemp_bkg_vehicle_type_id').append($('<option>').text('Select Car Model').attr('value', ''));
                    $.each(data, function (key, value) {
                        $('#BookingTemp_bkg_vehicle_type_id').append($('<option>').text(value).attr('value', key));
                    });
                }
            });
        }
    }
    ;
    $('#BookingTemp_bkg_pickup_date_date').datepicker({
        format: 'dd/mm/yyyy'
    });
    $('#BookingTemp_bkg_return_date_date').datepicker({
        format: 'dd/mm/yyyy'
    });
    function getApplicableDistance(dist) {
        distkm = parseInt(dist) + 15;
        distkm = (Math.ceil(distkm / 10)) * 10;
        return distkm;
    }

    function convertToBooking() {
        var href = "<?= CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/lead/converttobooking')) ?>";
        $.ajax({
            url: href, dataType: "json", type: "POST", "data": $('#lead-form').serialize(),
            "success": function (data1) {
                if (data1.success == true) {
                    $('.bootbox').modal('hide');
                    window.location.href = '<?= Yii::app()->createUrl("rcsr/booking/convert", ["lead_id" => '']); ?>' + data1.leadid;//                  
                } else {
                    alert('There was some problem in data saving');
                }
            }
        });
    }
    $sourceList = null;
    function populateSource(obj, cityId) {

        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
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

</script>
<input id="map_canvas" type="hidden" >

