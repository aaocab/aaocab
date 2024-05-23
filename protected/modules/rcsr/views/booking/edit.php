<?php
$version    = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile("https://maps.googleapis.com/maps/api/js?v=3.1exp&sensor=false&");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/csrbooking.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/route.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promo.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/lookup/cities?v' . Cities::model()->getLastModified());
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/lookup/routes?v' . Route::model()->getLastModified());
$cartype    = VehicleTypes::model()->getParentVehicleTypes(1);  //VehicleTypes::model()->getVehicleTypeList();
$agentlist  = VehicleTypes::model()->getJSON(Agents::model()->getAgentList());
//$adminlist = Admins::model()->findNameList();
$statuslist = Booking::model()->getBookingStatus();
$status     = $statuslist[$model->bkg_status];
$infosource = BookingAddInfo::model()->getInfosource('admin');
if (Yii::app()->request->isAjaxRequest)
{
    $cls = "";
}
else
{
    $cls = "col-lg-6 col-md-8 col-sm-10 col-sm-12 pb10";
}
//$bookingType = array(1 => 'One way', 2 => 'Round', 3 => 'Multi City');
$bookingType = Booking::model()->booking_type;
$locked      = ' <i class="fa fa-lock"></i>';
?>
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
    .form-group {
        margin-bottom: 7px;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    .bootstrap-timepicker-widget input  {
        border: 1px #555555 solid;color: #555555;
    }
    .navbar-nav > li > a {
        padding: 6px 30px;
    }
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
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin:0;
    }
    .selectize-input {
        min-width: 0px !important; 
        width: 30% !important; 
    }
    .remarkbox{
        width: 100%; 
        padding: 3px;  
        overflow: auto; 
        line-height: 10px; 
        font: normal arial; 
        border-radius: 5px; 
        -moz-border-radius: 5px; 
        border: 1px #aaa solid;
    }
    .border-none{
        border: 0!important;
    }
    .datepicker.datepicker-dropdown.dropdown-menu ,
    .bootstrap-timepicker-widget.dropdown-menu,
    .yii-selectize.selectize-dropdown
    {z-index: 9999 !important;}

    td, th {
        padding: 10px  !important ; 
    }
</style>
<div class="row">
    <div class="col-xs-12 text-center h2 mt0">
        <label for="type" class="control-label"><span style="font-weight: normal; font-size: 30px;">Booking Id:</span> </label>
        <b><?= $model->bkg_booking_id ?></b><label><?
            if ($model->bkg_agent_id > 0)
            {
                $agentsModel = Agents::model()->findByPk($model->bkg_agent_id);
                if ($agentsModel->agt_type == 1)
                {
                    echo " (CORPORATE)";
                }
                else
                {
                    echo " (PARTNER)";
                }
            }
            ?></label>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 mb20">
        <div style="text-align: center">
            <? $button_type  = 'label-edit'; ?>
            <?= $model->getActionButton([], $button_type);
            ?>
        </div>
    </div>
</div>
<div class="container">
    <?php
    $form         = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                     => 'edit-booking-form', 'enableClientValidation' => true,
        'clientOptions'          => array(
            'validateOnSubmit' => true,
            'errorCssClass'    => 'has-error',
            'afterValidate'    => 'js:function(form,data,hasError){
                if(!hasError){
                    $("#ebtnsbmt").prop( "disabled", true );
                    if(!validateEditBooking())
					{
                        $("#ebtnsbmt").prop( "disabled", false );
                        return false;                         
					}
                    $.ajax({
                    "type":"POST",
                    "dataType":"json",                  
                    "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                    "data":form.serialize(),
                    "beforeSend": function () {
                        ajaxindicatorstart("");
                    },
                    "complete": function () {  
                        ajaxindicatorstop();
                    },
                    "success":function(data1){
                        if(data1.success){
                        alert(data1.message);
                        location.href=data1.url;
                            return false;
                        } else{
                        $("#ebtnsbmt").prop( "disabled", false );
                            var errors = data1.errors;
                            settings=form.data(\'settings\');
                             $.each (settings.attributes, function (i) {
                                $.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
                              });
                              $.fn.yiiactiveform.updateSummary(form, errors);
                            } 
                        },
                     error: function(xhr, status, error){
                       var x= confirm("Network Error Occurred. Do you want to retry?");
                       if(x){
                                $("#edit-booking-form").submit();
                            }
                            else{
                            $("#ebtnsbmt").prop( "disabled", false );
                            }
                         }
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
            'class' => '',
        ),
    ));
    ?>
    <div class="row">
        <?php echo CHtml::errorSummary($model); ?>
        <?= $form->hiddenField($model, 'bkg_id', array('readonly' => true)) ?>
        <?= $form->hiddenField($model, 'lead_id', array('readonly' => true)) ?>
        <?= $form->hiddenField($model, 'bkg_user_id', array('readonly' => true)) ?>
        <? //= $form->hiddenField($model, 'bkg_refund_amount', array('readonly' => true))   ?>
        <div class="col-md-7">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default panel-border">
                        <h3 class="pl15 pb10">Booking Information</h3>
                        <div class="panel-body pt0">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Booking Type</label>

                                        <input type="hidden" id="multicityjsondata" name="multicityjsondata" value='<?= json_encode($model->preData); ?>'>
                                        <?
                                        $dataBookType = VehicleTypes::model()->getJSON($bookingType);
                                        $this->widget('booster.widgets.TbSelect2', array(
                                            'model'          => $model,
                                            'attribute'      => 'bkg_booking_type',
                                            'val'            => $model->bkg_booking_type,
                                            'asDropDownList' => FALSE,
                                            'options'        => array('data' => new CJavaScriptExpression($dataBookType)),
                                            'htmlOptions'    => array('style' => 'width:100%', 'class' => 'selectReadOnly', 'placeholder' => 'Select Booking Type')
                                        ));
                                        ?>
                                        <span class="has-error"><? echo $form->error($model, 'bkg_booking_type'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group ">
                                        <label class="control-label" for="route">Route</label>
                                        <?php
                                        if ($model->bkg_booking_type == 2 || $model->bkg_booking_type == 3)
                                        {
                                            $disable = ['disabled' => 'disabled'];
                                        }
                                        else
                                        {
                                            $disable = [];
                                        }
                                        $this->widget('booster.widgets.TbSelect2', array(
                                            'model'          => $model,
                                            'attribute'      => 'bkg_route',
                                            'val'            => $model->bkg_route,
                                            'asDropDownList' => FALSE,
                                            'options'        => array('data' => new CJavaScriptExpression('$routeList')),
                                            'htmlOptions'    => array('style' => 'width:100%', 'class' => 'selectReadOnly', 'placeholder' => 'Select Route') + $disable
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <!--                        multicity-->

                            <div class="row">
                                <div class="col-xs-12"  id='tripTablecreate' style="display: <?= ($model->bkg_booking_type == 2 || $model->bkg_booking_type == 3) ? 'block' : 'none' ?>">
                                    <?
                                    if (count($bookingRouteModel) > 0 && ($model->bkg_booking_type == 2 || $model->bkg_booking_type == 3))
                                    {
                                        ?>
                                        <h3 class="mb10 text-uppercase">Trip Info<button type="button" class="btn btn-info ml15" onclick="editmulticity()"><i class="fa fa-edit"></i> Modify Cities</button></h3>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>From</th>
                                                        <th>To</th>
                                                        <th>Date</th>
                                                        <th>Distance</th>
                                                        <th>Duration</th>
                                                        <th>Day</th></tr>
                                                </thead>
                                                <?
                                                $diffdays = 0;
                                                foreach ($bookingRouteModel as $key => $bookingRoute)
                                                {
                                                    if ($key == 0)
                                                    {
                                                        $diffdays = 1;
                                                    }
                                                    else
                                                    {
                                                        $date1      = new DateTime(date('Y-m-d', strtotime($bookingRouteModel[0]->brt_pickup_datetime)));
                                                        $date2      = new DateTime(date('Y-m-d', strtotime($bookingRoute->brt_pickup_datetime)));
                                                        $difference = $date1->diff($date2);
                                                        $diffdays   = ($difference->d + 1);
                                                    }
                                                    ?>    
                                                    <tr class="multicitydetrow">
                                                        <td id="fcitycreate<?= $key ?>"><b><?= $bookingRoute->brtFromCity->cty_name; ?></b><br><?= $bookingRoute->brt_from_location; ?></td>
                                                        <td id="tcitycreate<?= $key ?>"><b><?= $bookingRoute->brtToCity->cty_name; ?></b><br><?= $bookingRoute->brt_to_location; ?></td>
                                                        <td id="fdatecreate<?= $key ?>"><?= DateTimeFormat::DateTimeToDatePicker($bookingRoute->brt_pickup_datetime) . " " . DateTimeFormat::DateTimeToTimePicker($bookingRoute->brt_pickup_datetime); ?></td>
                                                        <td id="fdistcreate<?= $key ?>"><?= $bookingRoute->brt_trip_distance; ?> </td>
                                                        <td id="ftimecreate<?= $key ?>"><?= $bookingRoute->brt_trip_duration; ?> </td>
                                                        <td id="noOfDayscreate<?= $key ?>"><? echo $diffdays; ?> </td>
                                                    </tr>                                             
                                                    <?
                                                    $last_date = date('Y-m-d H:i:s', strtotime($bookingRoute->brt_pickup_datetime . '+ ' . $bookingRoute->brt_trip_duration . ' minute'));
                                                }
                                                ?>
                                                <tr id='insertTripRowcreate'></tr>
                                            </table>
                                            <span id='show_return_date_time'></span>
                                            <?
                                            if ($date1 != '')
                                            {
                                                $totdiff = $date1->diff(new DateTime(date('Y-m-d', strtotime($last_date))))->d + 1;
                                            }
                                            else
                                            {
                                                $totdiff = $diffdays;
                                            }
                                            ?>
                                            <h4 class="pt10">Total days for the trip: <span class="blue-color"><span id="totdayscreate"><?= $totdiff ?></span> days</span></h4>
                                        </div>

                                    <? } ?>    
                                </div>
                            </div>
                            <!--                        multicity-->

                            <div class="row" id="ctyinfo_bkg_type_1" style="display: <? echo ($model->bkg_booking_type == 1 || $model->bkg_booking_type == 4 ) ? '' : 'none' ?>">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Source City</label>
                                        <?php
                                        $this->widget('booster.widgets.TbSelect2', array(
                                            'model'          => $model,
                                            'attribute'      => 'bkg_from_city_id',
                                            'val'            => $model->bkg_from_city_id,
                                            'asDropDownList' => FALSE,
                                            'options'        => array('data' => new CJavaScriptExpression('$cityList')),
                                            'htmlOptions'    => array('style' => 'width:100%', 'class' => 'selectReadOnly', 'placeholder' => 'Select Source City')
                                        ));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Destination City</label>
                                        <?php
                                        $this->widget('booster.widgets.TbSelect2', array(
                                            'model'          => $model,
                                            'attribute'      => 'bkg_to_city_id',
                                            'val'            => $model->bkg_to_city_id,
                                            'asDropDownList' => FALSE,
                                            'options'        => array('data' => new CJavaScriptExpression('$cityList')),
                                            'htmlOptions'    => array('style' => 'width:100%', 'class' => 'selectReadOnly', 'placeholder' => 'Select Destination City')
                                        ));
                                        ?>
                                    </div>
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model, 'bkg_trip_distance', array('label' => "Estimated distance", 'widgetOptions' => array('htmlOptions' => array('class' => 'clsReadOnly', 'placeholder' => 'In Km')))) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model, 'bkg_trip_duration', array('label' => "Estimated duration", 'widgetOptions' => array('htmlOptions' => array('class' => 'clsReadOnly', 'placeholder' => 'In Min')))) ?>
                                </div>   
                            </div>
                            <div class="row"  id="pickup_div" style="display:  <?= ($model->bkg_booking_type == 1 || $model->bkg_booking_type == 4) ? 'block' : 'none' ?>">
                                <div class="col-sm-6">

                                    <?=
                                    $form->datePickerGroup($model, 'bkg_pickup_date_date', array('label'         => 'Pickup Date',
                                        'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Pickup Date', 'value' => DateTimeFormat::DateTimeToDatePicker($model->bkg_pickup_date), 'class' => 'input-group border-gray full-width')), 'prepend'       => '<i class="fa fa-calendar"></i>'));
                                    ?>

                                </div>
                                <div class="col-sm-6">

                                    <?=
                                    $form->timePickerGroup($model, 'bkg_pickup_date_time', array('label'         => 'Pickup Time',
                                        'widgetOptions' => array('id' => CHtml::activeId($model, "bkg_pickup_date_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Pickup Time', 'value' => date('h:i A', strtotime($model->bkg_pickup_date)), 'class' => 'input-group border-gray full-width'))));
                                    ?>

                                </div>
                                <div id="errordivpdate" class="ml15 mt10 " style="color:#da4455"></div>
                            </div>
                            <div class="row" style="display: none">
                                <div class="col-sm-6">
                                    <? $strrtedate   = ($model->bkg_return_date == '') ? '' : DateTimeFormat::DateTimeToDatePicker($model->bkg_return_date); ?>
                                    <?=
                                    $form->datePickerGroup($model, 'bkg_return_date_date', array('label'         => 'Return Date',
                                        'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Return Date', 'value' => $strrtedate)), 'prepend'       => '<i class="fa fa-calendar"></i>'));
                                    ?>
                                </div>
                                <div class="col-sm-6">
                                    <?=
                                    $form->timePickerGroup($model, 'bkg_return_date_time', array('label'         => 'Return Time',
                                        'widgetOptions' => array('id' => CHtml::activeId($model, "bkg_return_date_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Return Time', 'value' => date('h:i A', strtotime($model->bkg_return_date))))));
                                    ?>
                                </div>
                                <div id="errordivreturn" class="mt5 ml15" style="color:#da4455"></div>
                            </div>
                            <div class="row" id="address_div" style="display:  <?= ($model->bkg_booking_type == 1 || $model->bkg_booking_type == 4) ? 'block' : 'none' ?>">
                                <div class="col-sm-6">
                                    <?= $form->textAreaGroup($model, 'bkg_pickup_address', array('label' => 'Pick up Location', 'widgetOptions' => array('htmlOptions' => array()))) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->textAreaGroup($model, 'bkg_drop_address', array('label' => 'Drop off Location', 'widgetOptions' => array('htmlOptions' => array()))) ?>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Current Status</label>
                                        <div class="form-control"><?= $status ?></div></div>
                                    <? //= $form->textFieldGroup($model, 'bkg_status_name', array('label' => 'Current Status', 'widgetOptions' => array('htmlOptions' => array('value' => $status, 'disabled' => 'disabled'))))   ?>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label" for="car">Car Model</label>
                                        <?php
                                        $this->widget('booster.widgets.TbSelect2', array(
                                            'model'       => $model,
                                            'attribute'   => 'bkg_vehicle_type_id',
                                            'val'         => $model->bkg_vehicle_type_id,
                                            'data'        => $cartype,
                                            'htmlOptions' => array('style' => 'width:100%', 'class' => 'selectReadOnly', 'placeholder' => 'Select Car Type')
                                        ));
                                        ?>
                                        <span class="has-error"><?= $form->error($model, 'bkg_vehicle_type_id'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" style="display: none">
                                        <label class="control-label" for="ttcity">Trip Type</label>
                                        <?php
                                        $this->widget('booster.widgets.TbSelect2', array(
                                            'model'       => $model,
                                            'attribute'   => 'bkg_trip_type',
                                            'val'         => $model->bkg_trip_type,
                                            'data'        => Booking::model()->trip_type,
                                            'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Trip Type')
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <strong>Vendor: </strong><?= $model->bkgBcb->bcbVendor->vnd_name; ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <strong>Cab: </strong><?= $model->bkgBcb->bcbCab->vhc_number ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <strong>Driver: </strong><?= $model->bkgBcb->bcb_driver_name ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <strong>Driver Contact: </strong><?= $model->bkgBcb->bcb_driver_phone ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <?php
                $readonly     = true;
                $htmlReadOnly = ['readOnly' => 'readOnly'];
                if ($model->bkg_status == 1)
                {
                    $readonly     = false;
                    $htmlReadOnly = [];
                }
                ?>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default panel-border">
                        <h3 class="pl15">Payment Information</h3>
                        <div class="panel-body pt0">
                            <div class="row">
                                <?= $form->hiddenField($model, 'bkg_chargeable_distance'); ?>
                                <?= $form->hiddenField($model, 'bkg_garage_time'); ?>
                                <?= $form->hiddenField($model, 'bkg_is_toll_tax_included'); ?>
                                <?= $form->hiddenField($model, 'bkg_is_state_tax_included'); ?>
                                <?= $form->hiddenField($model, 'bkg_gozo_base_amount'); ?>


                                <?
                                $toll_checked  = ($model->bkg_is_toll_tax_included == 1) ? 'checked="checked" ' : "";
                                $state_checked = ($model->bkg_is_state_tax_included == 1) ? 'checked="checked"  ' : "";
                                ?>
                                <div class="col-xs-6">Toll tax Included <span class="checkertolltax"><input type="checkbox" name="bkg_is_toll_tax_included1" id="Booking_bkg_is_toll_tax_included1" <?= $toll_checked ?>><? //= $form->checkboxGroup($model, 'bkg_is_toll_tax_included1', array('label' => 'Toll tax Included', 'widgetOptions' => array('htmlOptions' => [])))                                                      ?></span></div>
                                <div class="col-xs-6">State tax Included <span class="checkerstatetax"><input type="checkbox" name="bkg_is_state_tax_included1" id="Booking_bkg_is_state_tax_included1" <?= $state_checked ?>><? //= $form->checkboxGroup($model, 'bkg_is_state_tax_included1', array('label' => 'State tax Included', 'widgetOptions' => array('htmlOptions' => [])))                                                      ?></span></div>                             

                            </div>
                            <div class="row">
                                <? $ratedivshow   = ($model->bkg_trip_type == 2 ) ? '' : 'hide' ?>
                                <div class="col-sm-6" >
                                    <?= $form->textFieldGroup($model, 'bkg_rate_per_km_extra', array('widgetOptions' => array('htmlOptions' => ['class' => 'clsReadOnly']))) ?>                                    
                                </div>
                                <div class="col-sm-6" id="div_rate_per_km" style="display: none">
                                    <?=
                                    $form->textFieldGroup($model, 'bkg_rate_per_km', array('widgetOptions' => array(
                                            'htmlOptions' => array('class' => 'clsReadOnly'))))
                                    ?>
                                    <div id="errordivrate" class="mt5 " style="color:#da4455"></div>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model, 'bkg_base_amount', array('label' => 'Amount', 'widgetOptions' => array('htmlOptions' => ['class' => 'clsReadOnly', 'placeholder' => 'Net Charge']))) ?>
                                    <div id="trip_rate"></div>
                                </div>


                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model, 'bkg_additional_charge_remark', array('widgetOptions' => array('htmlOptions' => ['class' => 'clsReadOnly']))) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model, 'bkg_additional_charge', array('widgetOptions' => array('htmlOptions' => ['class' => 'clsReadOnly']))) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <?
                                    $agentDisable  = ($model->bkg_agent_id > 0) ? ['readonly' => 'readonly'] : []; // || (strpos($model->bkg_promo_code, 'ADVPAID') !== false)) ? ['readonly' => 'readonly'] : [];
                                    ?>
                                    <?= $form->textFieldGroup($model, 'bkg_promo_code', array('label' => 'Promo Code', 'widgetOptions' => array('htmlOptions' => ['class' => 'clsReadOnly', 'placeholder' => 'Promo Code'] + $agentDisable))) ?>
                                </div>
                                <div class="col-sm-6">

                                    <?= $form->textFieldGroup($model, 'bkg_discount_amount', array('label' => 'Discount', 'widgetOptions' => array('htmlOptions' => ['readonly' => 'readonly', 'placeholder' => 'Discount']))) ?>
                                </div>
                            </div>
                            <?
                            if ($model->bkg_corporate_discount > 0)
                            {
                                ?>
                                <div class="row">
                                    <div class="col-sm-offset-6 col-sm-6">
                                        <?= $form->textFieldGroup($model, 'bkg_corporate_discount', array('label' => 'Corporate Discount', 'widgetOptions' => array('htmlOptions' => ['readonly' => 'readonly', 'placeholder' => 'Corporate Discount']))) ?>
                                    </div>
                                </div>
                            <? } ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model, 'bkg_vendor_amount', array('widgetOptions' => array('htmlOptions' => ['class' => 'clsReadOnly']))) ?>
                                    <input type="hidden" name="rtevndamt" id="rtevndamt"> 
                                    <?= $form->hiddenField($model, 'bkg_quoted_vendor_amount'); ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->numberFieldGroup($model, 'bkg_driver_allowance_amount', array('label' => 'Driver Allowance', 'widgetOptions' => array('htmlOptions' => ['class' => 'clsReadOnly', 'placeholder' => 'Driver allowance', 'oldamount' => 0]))); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model, 'bkg_toll_tax', array('label' => 'Toll Tax', 'widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly', 'plceholder' => 'Toll Tax')))) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model, 'bkg_state_tax', array('label' => 'State Tax', 'widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly', 'plceholder' => 'State Tax')))) ?>
                                </div>
                            </div>

                            <? //if ($model->bkg_advance_amount == '' || $model->bkg_advance_amount == 0) {     ?>
                            <div class="row">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model, 'bkg_convenience_charge', array('label' => 'Collect on delivery(COD) fee', 'widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly')))) ?>
                                    <!--                               'readonly' => 'readonly'-->
                                </div>
                            </div>
                            <? // }     ?>

                            <div class="row">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6">
                                    <?
                                    $staxrate                    = $model->getServiceTaxRate();
                                    $taxLabel                    = ($staxrate == 5) ? 'GST' : 'Service Tax ';
                                    ?>
                                    <? $model->bkg_service_tax_rate = $model->getServiceTaxRate(); ?>
                                    <?= $form->hiddenField($model, 'bkg_service_tax_rate'); ?>
                                    <?= $form->textFieldGroup($model, 'bkg_service_tax', array('label' => "$taxLabel    (rate: " . $model->getServiceTaxRate() . '%)', 'widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly')))) ?>
                                </div>
                            </div>
                            <?
                            $model11                     = clone $model;
                            $model11->calculateConvenienceFee(0);
                            $model11->calculateTotal();
                            ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label" for="amountwithoutcod">Total Amount(Without COD)</label>
                                        <input readonly="readonly" class="form-control" name="amountwithoutcod" id="amountwithoutcod" type="text" value="<?= $model11->bkg_total_amount ?>">
                                    </div> 
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model, 'bkg_total_amount', array('label' => 'Total Chargeable ' . $model->getAttributeLabel('bkg_total_amount'), 'widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly')))) ?>
                                </div>
                            </div>
                            <?
                            if ($model->bkg_corporate_credit > 0)
                            {
                                ?>
                                <div class="row">
                                    <div class="col-lg-offset-6 col-sm-6">
                                        <?= $form->textFieldGroup($model, 'bkg_corporate_credit', array('label' => 'Corporate Credits Used', 'widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly')))) ?>
                                    </div>
                                </div>
                            <? } ?>
                            <div class="row">
                                <?
                                if ($model->bkg_advance_amount > 0)
                                {
                                    ?>
                                    <div class="col-sm-6">
                                        <?= $form->textFieldGroup($model, 'bkg_advance_amount', array('label' => 'Advance Paid by User', 'widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly', 'value' => round($model->bkg_advance_amount))))) ?>
                                    </div>
                                <? } ?>
                                <?
                                if ($model->bkg_credits_used != '' && $model->bkg_credits_used > 0)
                                {
                                    ?>
                                    <div class="col-sm-6">
                                        <?= $form->textFieldGroup($model, 'bkg_credits_used', array('label' => 'Gozo Coins Applied', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Gozo Coins Applied', 'readOnly' => true]))) ?>

                                    </div>
                                <? } ?>
                                <?
                                if (($model->bkg_credits_used != '' && $model->bkg_credits_used > 0) || $model->bkg_advance_amount > 0 || $model->bkg_corporate_credit > 0)
                                {
                                    $dispdue = 'block';
                                }
                                else
                                {
                                    $dispdue = 'none';
                                }
                                ?>
                                <div class="col-sm-6" id="duediv" style="display: <?= $dispdue ?>">
                                    <?= $form->textFieldGroup($model, 'bkg_due_amount', array('widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly', 'value' => round($model->bkg_due_amount))))) ?>
                                </div>

                            </div>

                            <?
                            $tripdistance = ($model->bkg_trip_distance != '' && $model->bkg_trip_distance > 0) ? $model->bkg_trip_distance : 0;
                            if ($tripdistance > 0)
                            {
                                if ($model->bkg_rate_per_km > 0)
                                {
                                    $tripextrarate = "Note: Ext. Chrg. After " . $tripdistance . " Kms. = " . $model->bkg_rate_per_km . "/Km.";
                                }
                            }
                            ?>
                            <div class="row"><div class="col-xs-12" id="vehicle_dist_ext"><?= $tripextrarate ?></div></div>

                            <?
                            if ($model != '' && $model->bkg_user_id != '')
                            {
                                $creditVal = UserCredits::getApplicableCredits($model->bkg_user_id, $model->bkg_base_amount, true, $model->bkg_from_city_id, $model->bkg_to_city_id);
                                $creditVal = $creditVal['credits'];
                            }
                            if ($creditVal > 0 && $creditVal != '')
                            {
                                ?>                             
                                <div class="col-sm-6" style="float: right;display: none">
                                    <?= $form->hiddenField($model, 'optUseCredits', ['value' => 0]) ?>
                                    <label class="checkbox-inline Bold"><input type="checkbox" id="creditpoints" value="option2" credit="<?= $creditVal ?>">Apply Gozo Coins (<?= $creditVal . "/-" ?>)</label>
                                </div>
                            <? } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default panel-border">
                        <h3 class="pl15">Personal Information</h3>
                        <div class="panel-body pt0">
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model, 'bkg_user_name', array('label' => 'First Name', 'widgetOptions' => array('htmlOptions' => array()))) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model, 'bkg_user_lname', array('label' => 'Last Name', 'widgetOptions' => array())) ?>
                                    <div id="errordivemail" style="color:#da4455"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="control-label" >Contact Number</label>
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
                                                        'style' => 'width: 60%',
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
                                    $('#Booking_bkg_country_code')[0].selectize.setValue({$model->bkg_country_code});
                                    },                    
                                    error:function(){
                                    callback();
                                    }});
                                    });
                                    }",
                                                        'render'             => "js:{
                                    option: function(item, escape){  
                                    var class1 = (item.pcode == 91) ? '':'pl20';
                                    return '<div><span class=\"\">' + escape(item.name) +'</span></div>';

                                    },
                                                option_create: function(data, escape){
                                  $('#countrycode').val(data.pcode);

                                                 return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
                                                                                      }
                                                        }",
                                                    ),
                                                ));
                                                ?>

                                            </div>
                                            <div class="col-xs-9 col-sm-8 pl0">
                                                <?= $form->textFieldGroup($model, 'bkg_contact_no', array('label' => '', 'widgetOptions' => array('class' => ''))) ?>
                                                <div id="errordivmob" style="color:#da4455"></div>
                                            </div>                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label">Alternate Contact Number</label>
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
                                                        'style' => 'width: 50%',
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
                                         $('#Booking_bkg_alt_country_code')[0].selectize.setValue({$model->bkg_alt_country_code});
                                     },                    
                                     error:function(){
                                         callback();
                                     }});
                                            });
                                           }",
                                                        'render'             => "js:{
                                         option: function(item, escape){  
                                         var class1 = (item.pcode == 91) ? '':'pl20';
                                           return '<div><span class=\"\">' + escape(item.name) +'</span></div>';

                                    },
                                                option_create: function(data, escape){
                                  $('#countrycode').val(data.pcode);

                                                 return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
                                                                                      }
                                                        }",
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
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= $form->emailFieldGroup($model, 'bkg_user_email', array('label' => 'Email', 'widgetOptions' => array())) ?>
                                    <div id="errordivemail" style="color:#da4455"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default panel-border">
                        <h3 class="pl15">Additional Information</h3>
                        <div class="panel-body pt0">
                            <?
                            if ($model->bkg_agent_id > 0)
                            {
                                ?>
                                <div class="row">  
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <?
                                            if ($agentsModel->agt_type == 0 || $agentsModel->agt_type == 2)
                                            {
                                                ?>
                                                <label class="control-label">Linked to Partner Account</label>
                                                <div class="form-control"><?= $agentsModel->agt_company; ?></div>
                                            <? } ?>
                                            <?
                                            if ($agentsModel->agt_type == 1)
                                            {
                                                ?>
                                                <label >Linked To Corporate Account</label>
                                                <div class="form-control"><?= $agentsModel->agt_referral_code . " (" . $agentsModel->agt_company . ")"; ?></div>
                                            <? } ?>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-xs-4">To be paid by :</div>
                                    <div class="col-xs-8">
                                        <label class="checkbox-inline ">
                                            <div class="form-control"><?= ($model->bkg_corporate_remunerator == 2) ? "Company/Partner" : "Customer" ?></div>                                    </label>
                                    </div>
                                </div> 
                            <? } ?>

                            <div class="col-xs-12"> 
                                <div class="form-group"> 
                                    <label style="font-weight: bold">TAGS</label>
                                    <?php
                                    $SubgroupArray2 = Booking::model()->getTags();
                                    $this->widget('booster.widgets.TbSelect2', array(
                                        'name'        => 'bkg_tags',
                                        'model'       => $model,
                                        'data'        => $SubgroupArray2,
                                        'value'       => explode(',', $model->bkg_tags),
                                        'htmlOptions' => array(
                                            'multiple'    => 'multiple',
                                            'placeholder' => 'Enter Tags',
                                            'width'       => '100%',
                                            'style'       => 'width:100%',
                                        ),
                                    ));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label" style="text-align: left;" for="car"><nobr>How did you hear about Gozo cabs?</nobr></label>
                                    <?php
                                    $datainfo       = VehicleTypes::model()->getJSON($infosource);
                                    $this->widget('booster.widgets.TbSelect2', array(
                                        'model'          => $model,
                                        'attribute'      => 'bkg_info_source',
                                        'val'            => "'" . $model->bkg_info_source . "'",
                                        'asDropDownList' => FALSE,
                                        'options'        => array('data' => new CJavaScriptExpression($datainfo)),
                                        'htmlOptions'    => array('style' => 'width:100%', 'placeholder' => 'Select Infosource')
                                    ));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?= $form->textFieldGroup($model, 'bkg_flight_no', array('label' => 'Flight Number', 'widgetOptions' => array('htmlOptions' => array()))) ?>
                                </div>
                            </div>
                            <? $agentshow      = ($model->bkgAddInfo->bkg_info_source == 'Agent') ? '' : 'hide' ?>
                            <div class="col-sm-6 <?= $agentshow ?>" id="agent_show">
                                <!--                                    <div class="form-group">
                                                                        <label class="control-label" for="type">Partner</label>
                                <?php
//                                        $this->widget('booster.widgets.TbSelect2', array(
//                                            'model' => $model,
//                                            'attribute' => 'bkg_agent_id',
//                                            'val' => $model->bkg_agent_id,
//                                            'options' => array('data' => new CJavaScriptExpression($agentlist)),
//                                            'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Partner', 'label' => 'Partner')
//                                        ));
                                ?>
                                                                    </div>
                                                                    <span class="has-error"><? // echo $form->error($model, 'bkg_agent_id');                     ?></span>-->
                            </div>
                            <? $sourceDescShow = ($model->bkgAddInfo->bkg_info_source == 'Friend' || $model->bkgAddInfo->bkg_info_source == 'Other') ? '' : 'hide'; ?>
                            <div class="col-sm-6 <?= $sourceDescShow ?>" id="source_desc_show">
                                <div class="form-group">
                                    <label class="control-label" for="type">&nbsp;</label>
                                    <?= $form->textFieldGroup($model->bkgAddInfo, 'bkg_info_source_desc', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => '')))) ?>										
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <?php
                                    $str            = explode("\\", $model->bkg_file_path);
                                    ?>
                                    <label class="control-label" for="vendor">Attach Files</label>
                                    <a href="<?= Yii::app()->getBaseUrl(true) . $model->bkg_file_path ?>"  target="blank"><?= $str[2] ?></a><br />
                                    <?= $form->fileFieldGroup($model, 'fileImage', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control']))) ?>
                                </div>

                                <div class="col-sm-6">
                                    <label class="control-label" for="exampleInputName6"></label>
                                    <?= $form->checkboxGroup($model, 'bkg_tentative_booking', array('widgetOptions' => array('htmlOptions' => []))) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?= $form->textAreaGroup($model, 'new_remark', array('widgetOptions' => array('htmlOptions' => array()))) ?>
                                </div>
                                <div class="col-sm-12">
                                    <?= $form->textAreaGroup($model, 'bkg_instruction_to_driver_vendor', array('label' => 'Additional Instruction to Vendor/Driver', 'widgetOptions' => array('htmlOptions' => array()))) ?>
                                </div>
                                <div class="col-sm-12">
                                    <?= $form->checkboxGroup($model, 'bkg_invoice', array('widgetOptions' => array('htmlOptions' => []))) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="control-label col-xs-5">Customer Type</label>
                                <div class="col-xs-7">
                                    <?=
                                    $form->radioButtonListGroup($model, 'bkg_user_trip_type', array(
                                        'label'         => '', 'widgetOptions' => array(
                                            'data' => Booking::model()->userTripList
                                        ), 'inline'        => true,)
                                    );
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <label for="inputEmail" class="control-label col-xs-5">Send me booking confirmations by</label>
                                <div class="col-xs-7">
                                    <label class="checkbox-inline pt0">
                                        <?= $form->checkboxGroup($model, 'bkg_send_email', ['label' => 'Email']) ?>
                                    </label>
                                    <label class="checkbox-inline pt0">
                                        <?= $form->checkboxGroup($model, 'bkg_send_sms', ['label' => 'Phone']) ?>
                                    </label>
                                </div>
                            </div>

                            <div class="row mb5">
                                <label for="inputEmail" class="control-label col-xs-5">Number of Passengers</label>
                                <div class="col-xs-7">
                                    <?= $form->numberFieldGroup($model, 'bkg_no_person', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of Passengers", 'min' => 1, 'max' => 10]), 'groupOptions' => ['class' => 'm0'])) ?>                      
                                </div>
                            </div>
                            <div class="row mb5">
                                <label for="inputEmail" class="control-label col-xs-5">Number of large suitcases</label>
                                <div class="col-xs-7">
                                    <?= $form->numberFieldGroup($model, 'bkg_num_large_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of large suitcases", 'min' => 0, 'max' => 10]), 'groupOptions' => ['class' => 'm0'])) ?>                      
                                </div>
                            </div>
                            <div class="row mb5">
                                <label for="inputEmail" class="control-label col-xs-5">Number of small bags</label>
                                <div class="col-xs-7">
                                    <?= $form->numberFieldGroup($model, 'bkg_num_small_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of small bags", 'min' => 0, 'max' => 10]), 'groupOptions' => ['class' => 'm0'])) ?>                      
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-xs-6">
                                    <?= $form->numberFieldGroup($model, 'bkg_pickup_pincode', array('label' => 'Pickup Address Pin Code', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Pincode", 'min' => 100000, 'max' => 999999]), 'groupOptions' => ['class' => 'm0'])) ?>  
                                </div>
                                <div class="col-xs-6">
                                    <?= $form->numberFieldGroup($model, 'bkg_drop_pincode', array('label' => 'Drop Address Pin Code', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Pincode", 'min' => 100000, 'max' => 999999]), 'groupOptions' => ['class' => 'm0'])) ?>  
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-xs-12 special_request">
                                    <h2 class="mb10">Special Requests</h2>
                                    <div class="col-xs-12">
                                        <?= $form->checkboxGroup($model, 'bkg_spl_req_senior_citizen_trvl', []) ?>
                                        <?= $form->checkboxGroup($model, 'bkg_spl_req_kids_trvl', []) ?>
                                        <?= $form->checkboxGroup($model, 'bkg_spl_req_woman_trvl', []) ?>
                                        <?= $form->checkboxGroup($model, 'bkg_spl_req_carrier', []) ?>
                                        <?= $form->checkboxGroup($model, 'bkg_spl_req_driver_hindi_speaking', []) ?>
                                        <?= $form->checkboxGroup($model, 'bkg_spl_req_driver_english_speaking', []) ?>
                                        <?
                                        $checkedother   = ($model->bkg_spl_req_other != '') ? "'checked'=>'checked'" : '';
                                        ?>
                                        <?= $form->checkboxGroup($model, 'bkg_chk_others', ['label' => 'Others', 'widgetOptions' => array('htmlOptions' => [$checkedother])]) ?>
                                        <div id="othreq" style="display: <? echo ($model->bkg_spl_req_other != '') ? '' : 'none' ?>">
                                            <?= $form->textFieldGroup($model, 'bkg_spl_req_other', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Other Requests"]), 'groupOptions' => ['class' => 'm0'])) ?>  
                                        </div>
                                        <?= $form->checkboxGroup($model, 'bkg_add_my_trip', ['label' => 'I Will Take Journy Break','widgetOptions' =>['htmlOptions' => ['checked' => "checked"]]]) ?>
                                        <?= $form->dropDownListGroup($model, 'bkg_spl_req_lunch_break_time', ['label' => '', 'widgetOptions' => ['data' => ['0' => 'Minutes','30' => '30','60' => '60','90' => '90','120' => '120','150' => '150','180' => '180'] , 'htmlOptions' => ['readonly' => 'readonly']]]) ?>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-center pb10">
            <?= CHtml::submitButton('Submit', array('style' => 'font-size:1.4em', 'class' => 'btn btn-primary btn-lg pl50 pr50', 'id' => 'ebtnsbmt')); ?>
        </div>

    </div>
    <div class="row">
        <div class="col-xs-12">
            <label class="control-label"><h3>Booking Log</h3></label>
            <?
            Yii::app()->runController('rcsr/booking/showlog/booking_id/' . $model->bkg_id);
            ?>
        </div>
    </div>
</div>
<div id="driver1"></div>
<?php $this->endWidget(); ?>
<?php echo CHtml::endForm(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
<?
if ($model->bkg_status != 1)
{
    ?>
            $('.clsReadOnly').attr('readOnly', true);
            $('.selectReadOnly').select2('readonly', true);
<? } ?>
        $('.bootbox').removeAttr('tabindex');
        $('.glyphicon').addClass('fa').removeClass('glyphicon');
        $('.glyphicon-time').addClass('fa-clock-o').removeClass('glyphicon-time');
        //  fillDistance();
        $(document).on('hidden.bs.modal', function (e) {
            $('body').addClass('modal-open');
        });
        $('#ytBooking_bkg_add_my_trip').parent().parent().css('float','left');
        $('#Booking_bkg_spl_req_lunch_break_time').parent().css('float','right');
        
<?
if (($model->bkg_gozo_base_amount == '' || $model->bkg_gozo_base_amount == 0) && ($model->bkg_base_amount == '' || $model->bkg_base_amount == 0) && $model->bkg_status == 1)
{
    ?>
            getAmountbyCitiesnVehicle();
<? } ?>
//                if ($('#Booking_bkg_promo_code').val() != '' && ($('#Booking_bkg_discount_amount').val()==0 || $('#Booking_bkg_discount_amount').val()=='')) {
//                    
//                    getDiscount();
//                    calculateAmount();
//                }



        $('#addVendor').click(function () {
            $href = '<?= Yii::app()->createUrl('admin/vendor/addvendor') ?>';
            jQuery.ajax({type: 'POST', url: $href,
                success: function (data) {
                    box = bootbox.dialog({
                        message: data,
                        title: 'Add Vendor',
                        onEscape: function () {
                        },
                    });
                    box.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }});
        });
    });

    $('#Booking_bkg_is_toll_tax_included1').change(function ()
    {
        if ($('#Booking_bkg_is_toll_tax_included1').is(':checked'))
        {
            $('#Booking_bkg_is_toll_tax_included').val(1);
        } else
        {
            $('#Booking_bkg_is_toll_tax_included').val(0);
        }
    });
    $('#Booking_bkg_is_state_tax_included1').change(function ()
    {
        if ($('#Booking_bkg_is_state_tax_included1').is(':checked'))
        {
            $('#Booking_bkg_is_state_tax_included').val(1);
        } else
        {
            $('#Booking_bkg_is_state_tax_included').val(0);
        }
    });
    $('#creditpoints').click(function ()
    {
        calculateAmount();
    });
    function vendorcar()
    {
        $("#gozocar").toggle("slow");
        $("#vendorcar").toggle();
    }

//   $('#edit-booking-form').submit(function (event) {
//        if (validateEditBooking()) {
//        } else {
//            event.preventDefault();
//        }
//    });


    function validateEditBooking()
    {
        var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
        var primaryPhone = $('#Booking_bkg_contact_no').val();
        var email = $('#Booking_bkg_user_email').val();
        var $select = $("#Booking_bkg_country_code").selectize({
        });
        var triptype = $('#Booking_bkg_trip_type').val();
        var ratepkm = $('#Booking_bkg_rate_per_km').val();
        var bkgtype = $('#Booking_bkg_booking_type').val();
        var selectizeControl = $select[0].selectize;
        var country_code = selectizeControl.getItem(selectizeControl.getValue()).text();
        error = 0;
        $("#errordivmob").text('');
        $("#errordivemail").text('');
        $("#errordivrate").text('');
        $("#errordivreturn").text('');
        if (bkgtype == 2 && $('#Booking_bkg_return_date_date').val() == '') {
            error += 1;
            $("#errordivreturn").text('');
            $("#errordivreturn").text('Please enter Return Date and Time');
        }
        if (triptype == 2 && (ratepkm == null || ratepkm == ''))
        {
            error += 1;
            $("#errordivrate").text('');
            $("#errordivrate").text('Please enter Rate per Km');
        }
        if ((primaryPhone == '' || primaryPhone == null) && (email == '' || email == null))
        {
            error += 1;
            $("#errordivmob").text('');
            $("#errordivemail").text('');
            $("#errordivmob").text('Please enter mobile number or email address.');
        } else
        {
            if (primaryPhone != '')
            {
                if (country_code == '' || country_code == null)
                {
                    error += 1;
                    $("#errordivmob").text("please select country code.");
                } else
                {
                    var ck_indian_mobile = /^[0-9]+$/;
                    if (country_code == '91')
                    {
                        var message = 'Contact Number can contain only [0-9].';
                    }
                    if (!ck_indian_mobile.test(primaryPhone))
                    {
                        error += 1;
                        $("#errordivmob").text('');
                        $("#errordivemail").text('');
                        $("#errordivmob").text(message);
                    } else
                    {
                        error += 0;
                        $("#errordivmob").text('');
                        $("#errordivemail").text('');
                    }
                }
            } else
            {
                if (email != '')
                {
                    if (!ck_email.test(email))
                    {
                        error += 1;
                        $("#errordivmob").text('');
                        $("#errordivemail").text('');
                        $("#errordivemail").text('Invalid email address');
                    }
                }
            }
        }
        if (error > 0)
        {
            return false;
        }
        return true;
    }

    $('#addDriver11').click(function () {
        $href = '<?= Yii::app()->createUrl('admin/driver/create') ?>';
        jQuery.ajax({type: 'POST', url: $href,
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: 'Add Driver',
                    onEscape: function () {
                    },
                });

                box.on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            }});
    });
    //    refreshDriver = function () {
    //        box.modal('hide');
    //        $href = '<?= Yii::app()->createUrl('admin/driver/json') ?>';
    //        jQuery.ajax({type: 'POST', "dataType": "json", url: $href,
    //            success: function (data1) {
    //                $data = data1;
    //                $('#<?= CHtml::activeId($model, "bkg_driver_id") ?>').select2({data: $data, multiple: false});
    //            }
    //        });
    //    };
    //    refreshCab = function () {
    //        box.modal('hide');
    //        $href = '<?= Yii::app()->createUrl('admin/vehicle/json') ?>';
    //        jQuery.ajax({type: 'POST', "dataType": "json", url: $href,
    //            success: function (data1) {
    //                $data = data1;
    //                $('#<?= CHtml::activeId($model, "bkg_vehicle_id") ?>').select2({data: $data, multiple: false});
    //            }
    //        });
    //    };
    $("#Booking_bkg_pickup_date_date").change(function () {
        $("#errordivpdate").text('');
    });
    $("#Booking_bkg_from_city_id").change(function () {
        getRoute();
    });
    $("#Booking_bkg_to_city_id").change(function () {
        getRoute();
    });
    //        $("#Booking_bkg_booking_type").change(function () {
    //            getAmountbyCitiesnVehicle();
    //        });
    //        $("#Booking_bkg_trip_distance").change(function () {
    //            if ($("#Booking_bkg_trip_type").val() == 2) {
    //                getAmountbyCitiesnVehicle();
    //            }
    //        });
    $("#Booking_bkg_base_amount").change(function () {
        var promo = $('#Booking_bkg_promo_code').val();
        if (promo != '') {
            if (promo.indexOf("ADVPAID") == -1) {
                getDiscount();
            }
        }
        calculateAmount();
    });
    $("#Booking_bkg_additional_charge").change(function () {
        // calculateAmount();
        calculateEstimatedAmount();
    });
    //            $("#Booking_bkg_discount_amount").change(function () {
    //                getDiscount();
    //                calculateAmount();
    //            });

    $('#Booking_bkg_driver_allowance_amount').change(function () {
        // calculateAmount();
        calculateEstimatedAmount();
    });

    $("#BookingAddInfo_bkg_info_source").change(function () {
        var infosource = $("#BookingAddInfo_bkg_info_source").val();
        extraAdditionalInfo(infosource);
        /*
         if (infosource == 'Agent') {
         $("#agent_show").removeClass('hide');
         }
         if (infosource != 'Agent') {
         $("#agent_show").addClass('hide');
         $("#Booking_bkg_agent_id").val('');
         }*/
    });
    $('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').change(function () {
        if ($('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').is(':checked'))
        {
            $("#othreq").show();
        } else {
            $("#othreq").hide();
        }
    });

    function extraAdditionalInfo(infosource)
    {
        $("#agent_show").addClass('hide');
        $("#source_desc_show").addClass('hide');
        if (infosource == 'Agent') {
            $("#BookingAddInfo_bkg_info_source_desc").val('');
            $("#agent_show").removeClass('hide');
            $("#source_desc_show").addClass('hide');
        } else {
            $("#Booking_bkg_agent_id").val('');
            if (infosource == 'Friend') {
                $("#source_desc_show").removeClass('hide');
                $("#agent_show").addClass('hide');
                $("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "Friend's email please");
            } else if (infosource == 'Other') {
                $("#source_desc_show").removeClass('hide');
                $("#agent_show").addClass('hide');
                $("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "");
            }
        }

    }

    $("#Booking_bkg_vendor_id").change(function () {
        populateDriversbyVendor();
        populateVehiclesbyVendor();
        $("#Booking_bkg_extdriver_contact").val('');
    });

    $("#Booking_bkg_vehicle_type_id").change(function () {

        getAmountbyCitiesnVehicle();
        var promo = $('#Booking_bkg_promo_code').val();
        if (promo != '') {
            if (promo.indexOf("ADVPAID") == -1) {
                getDiscount();
            }
        }

    });
    $("#Booking_bkg_promo_code").change(function () {
        $("#Booking_bkg_discount_amount").val('');
        getDiscount();
        calculateAmount();
    });
    $("#Booking_bkg_booking_type").change(function () {
        var $bkgtype = $("#Booking_bkg_booking_type").val();
        $('#address_div').hide();
        $('#ctyinfo_bkg_type_1').hide();
        $('#pickup_div').hide();
        $('#tripTablecreate').show();

        if ($bkgtype == '1' || $bkgtype == '4') {
            $('#ctyinfo_bkg_type_1').show();
            $('#address_div').show();
            $('#pickup_div').show();
            $('#tripTablecreate').hide();
            $('.multicitydetrow').remove();
            $('#address_div').show();
            $("#multicityjsondata").val('');
        }
    });

    $('#Booking_bkg_route').bind("change", function () {
        selctRoute();
    });
    $(document).on("getRouteListbyCities", function (event, data) {
        routeCitiesList(data);
    });

    function selctRoute() {
        var city = new City();
        var model = {};
        model.routeId = $("#Booking_bkg_route").val();
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
        $("#Booking_bkg_from_city_id").val(data.data.fcity).change();
        $fireChange = true;
        $("#Booking_bkg_to_city_id").val(data.data.tcity).change();
    }
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
        model.fromCity = $("#Booking_bkg_from_city_id").val();
        model.toCity = $("#Booking_bkg_to_city_id").val();
        model.bookingType = $("#Booking_bkg_booking_type").val();
        model.pickupAddress = $("#Booking_bkg_pickup_address").val();
        model.dropAddress = $("#Booking_bkg_drop_address").val();
        model.pickupDate = $("#Booking_bkg_pickup_date_date").val();
        model.pickupTime = $("#Booking_bkg_pickup_date_time").val();
        route.model = model;
        if (model.fromCity != '' && model.toCity != '' && model.bookingType != '') {

            route.getRouteList();
        }
    }

    function routeList(data)
    {
        if (data.rutid > 0) {
            $("#Booking_bkg_route").val(data.data.rutid).change();
            $("#Booking_bkg_trip_distance").val(data.distance).change();
            $("#Booking_bkg_trip_duration").val(data.duration).change();

        } else {
            $("#Booking_bkg_route").val('').change();
            $("#Booking_bkg_trip_distance").val(data.data.distance).change();
            $("#Booking_bkg_trip_duration").val(data.data.duration).change();
        }
        getAmountbyCitiesnVehicle();
    }

    function getAmountbyCitiesnVehicle() {
        //var booking = new Booking();
		var csrbooking = new Csrbooking();
        var model = {};
        model.fromCity = $("#Booking_bkg_from_city_id").val();
        model.toCity = $("#Booking_bkg_to_city_id").val();
        model.cabType = $("#Booking_bkg_vehicle_type_id").val();
        model.tripDistance = $('#Booking_bkg_trip_distance').val();
        model.tripType = $("#Booking_bkg_trip_type").val();
        model.multiCityData = $('#multicityjsondata').val();
        model.bookingType = $('#Booking_bkg_booking_type').val();
        model.id = $('#Booking_bkg_id').val();
        model.pickupDate = $('#Booking_bkg_pickup_date_date').val();
        model.pickupTime = $('#Booking_bkg_pickup_date_time').val();
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

    $('#Booking_bkg_convenience_charge').change(function () {
        var convenience_charge = isNaN(parseInt($('#Booking_bkg_convenience_charge').val())) ? 0 : parseInt($('#Booking_bkg_convenience_charge').val());
        if (convenience_charge != 0) {
            calculateAmount();
        }
    });

    function getQoutation(data)
    {
        if (data.success)
//        if (data.data.quoteddata[data.data.cartypeid]['error'] > 0)
//        {
//            alert('Sorry! Your request can not be processed right now!Please try later.' + data.data.quoteddata[data.data.cartypeid]['error']);
//        } else
        {
            var qRouteRates = data.data.quoteddata.routeRates;
            var qRouteDistance = data.data.quoteddata.routeDistance;
            var qRouteDuration = data.data.quoteddata.routeDuration;

            $("#Booking_bkg_base_amount").val(qRouteRates.baseAmount);
            $("#Booking_bkg_toll_tax").val(qRouteRates.tollTaxAmount);
            $("#Booking_bkg_state_tax").val(qRouteRates.stateTax);
            $("#Booking_bkg_total_amount").val('');
            $("#Booking_bkg_rate_per_km_extra").val(qRouteRates.ratePerKM);
            $("#Booking_bkg_total_amount").val(qRouteRates.totalAmount).change;
            $('#Booking_bkg_gozo_base_amount').val(qRouteRates.baseAmount);
            $("#trip_rate").text('');
            if (qRouteRates.costPerKM > 0) {
                // $("#trip_rate").text('Rate : Rs.' + data.est_booking_info['km_rate'] + ' per km');
            }
            $('#Booking_bkg_service_tax').val(qRouteRates.gst);
            $('#Booking_bkg_driver_allowance_amount').val(qRouteRates.driverAllowance);
            $('#Booking_bkg_driver_allowance_amount').attr('oldamount', qRouteRates.driverAllowance);

            if (qRouteRates.isTollIncluded == 1)
            {
                $('.checkertolltax span').addClass('checked');
                $('#Booking_bkg_is_toll_tax_included').val(1);
                $('#Booking_bkg_is_toll_tax_included1').attr('checked', 'true');
                $('#Booking_bkg_is_toll_tax_included1').attr('disabled', 'disabled');
            } else
            {
                $('#Booking_bkg_is_toll_tax_included').val(0);
                $('.checkertolltax span').removeClass('checked');
                $('#Booking_bkg_is_toll_tax_included1').removeAttr('checked');
                $('#Booking_bkg_is_toll_tax_included1').removeAttr('disabled');

            }
            if (qRouteRates.isStateTaxIncluded == 1)
            {
                $('#Booking_bkg_is_state_tax_included').val(1);
                $('.checkerstatetax span').addClass('checked');
                $('#Booking_bkg_is_state_tax_included1').attr('checked', 'true');
                $('#Booking_bkg_is_state_tax_included1').attr('disabled', 'disabled');
            } else
            {
                $('#Booking_bkg_is_state_tax_included').val(0);
                $('.checkerstatetax span').removeClass('checked');
                $('#Booking_bkg_is_state_tax_included1').removeAttr('checked');
                $('#Booking_bkg_is_state_tax_included1').removeAttr('disabled');

            }
            //                        $('#driver_allowance').html(driverallowance);
            $('#Booking_bkg_chargeable_distance').val(qRouteRates.costPerKM);
            $('#Booking_bkg_garage_time').val(qRouteDuration.totalMinutes);
            $('#vehicle_rate_per_km').html(qRouteRates.costPerKM);
            $('#Booking_bkg_trip_distance').val(qRouteDistance.quotedDistance);
            $('#Booking_bkg_trip_duration').val(qRouteDuration.totalMinutes);
            $('#Booking_bkg_quoted_vendor_amount').val(qRouteRates.vendorAmount);
            var additional = Math.round($('#Booking_bkg_additional_charge').val());
            var additional = (additional == '') ? 0 : parseInt(additional);
            var venamt = qRouteRates.vendorAmount;
            if (additional > 0) {
                venamt = venamt + additional;
            }
            $('#Booking_bkg_vendor_amount').val(venamt);
            if (qRouteRates.costPerKM > 0 && $('#Booking_bkg_trip_distance').val() > 0)
            {
                $('#vehicle_dist_ext').html("Note: Ext. Chrg. After " + $('#Booking_bkg_trip_distance').val() + " Kms. = " + qRouteRates.costPerKM + "/Km.");
            } else
            {
                $('#vehicle_dist_ext').html("");
            }

            calculateEstimatedAmount();
        }
    }

    function calculateEstimatedAmount() {

        var net_amount = Math.round($('#Booking_bkg_base_amount').val());
        net_amount = (net_amount == '') ? 0 : parseInt(net_amount);
        var additional = Math.round($('#Booking_bkg_additional_charge').val());
        var additional = (additional == '') ? 0 : parseInt(additional);
        var discount_amount = Math.round($('#Booking_bkg_discount_amount').val());
        var driver_allowance = 0;
        var gozo_base_amount = Math.round($('#Booking_bkg_gozo_base_amount').val());

        var rateVendorAmount = Math.round($('#Booking_bkg_quoted_vendor_amount').val());
        net_amount = net_amount + additional;

        discount_amount = (discount_amount == '') ? 0 : parseInt(discount_amount);
        net_amount = net_amount - discount_amount;
        if ($('#Booking_bkg_driver_allowance_amount').val() != '' && $('#Booking_bkg_driver_allowance_amount').val() > 0)
        {
            //net_amount = net_amount + parseInt($('#Booking_bkg_driver_allowance_amount').val());
            driver_allowance = parseInt($('#Booking_bkg_driver_allowance_amount').val());
        }
        var advance = isNaN(parseFloat($('#Booking_bkg_advance_amount').val())) ? 0 : parseFloat($('#Booking_bkg_advance_amount').val());

        var netamount_withoutcod = net_amount;
        if (advance == 0) {
            var conFee1 = net_amount * 0.05;
            var conFee2 = 249;
            //          var conFee1 = net_amount * 0.10;
            //          var conFee2 = 499;
            if (conFee1 > conFee2) {
                var conFee = conFee2;
            } else {
                var conFee = conFee1;
            }
            //   conFee = 0 //set Convenience charge zero;
            var convenience_charge = Math.round(conFee);
            net_amount = net_amount + convenience_charge;
            $('#Booking_bkg_convenience_charge').val(convenience_charge);
        }
        var service_tax_rate = ($('#Booking_bkg_service_tax_rate').val() == '') ? 0 : $('#Booking_bkg_service_tax_rate').val();
        var service_tax_amount = 0;
        var servicetax_without_cod = 0;
        if (service_tax_rate != 0)
        {
            service_tax_amount = Math.round((net_amount * parseFloat(service_tax_rate) / 100));
            servicetax_without_cod = Math.round((netamount_withoutcod * parseFloat(service_tax_rate) / 100));

        }
        var tollTaxVal = ($('#Booking_bkg_toll_tax').val() == '') ? 0 : parseInt($('#Booking_bkg_toll_tax').val());
        var stateTaxVal = ($('#Booking_bkg_state_tax').val() == '') ? 0 : parseInt($('#Booking_bkg_state_tax').val());
        var totamountwithoutcod = netamount_withoutcod + servicetax_without_cod + tollTaxVal + stateTaxVal + driver_allowance;
        $('#amountwithoutcod').val(totamountwithoutcod);
        var net_amount = net_amount + service_tax_amount;

        var net_amount = net_amount + tollTaxVal + stateTaxVal + driver_allowance;

        var credits_used = parseInt(($('#Booking_bkg_credits_used').val() != '' && $('#Booking_bkg_credits_used').val() != undefined && $('#Booking_bkg_credits_used').val() != 'undefined') ? $('#Booking_bkg_credits_used').val() : 0);
        var credits = 0;
        if ($('#creditpoints').is(':checked'))
        {
            credits = parseInt(isNaN($('#creditpoints').attr('credit')) ? 0 : $('#creditpoints').attr('credit'));
            $('#duediv').show();
            $('#Booking_optUseCredits').val(1);
        } else
        {
            $('#duediv').hide();
            $('#Booking_optUseCredits').val(0);
        }
        if (credits_used > 0) {
            $('#duediv').show();
        }
        var refund = Math.round($('#Booking_bkg_refund_amount').val());
        refund = (refund == '') ? 0 : parseInt(refund);
        var due = net_amount - advance - credits_used - credits + refund;

        $('#Booking_bkg_convenience_charge').val(convenience_charge);
        $('#Booking_bkg_due_amount').val(due);
        $('#Booking_bkg_total_amount').val(net_amount);
        //  $('#Booking_bkg_vendor_amount').val(vendor_amount);
        $('#Booking_bkg_service_tax').val(service_tax_amount);
    }



    function calculateAmount() {

        var payment = 0;
        var net_amount = Math.round($('#Booking_bkg_base_amount').val());
        var refund = Math.round($('#Booking_bkg_refund_amount').val());
        net_amount = (net_amount == '') ? 0 : parseInt(net_amount);
        refund = (refund == '') ? 0 : parseInt(refund);
        var advance = isNaN(parseFloat($('#Booking_bkg_advance_amount').val())) ? 0 : parseFloat($('#Booking_bkg_advance_amount').val());
        var additional = Math.round($('#Booking_bkg_additional_charge').val());
        var discount_amount = Math.round($('#Booking_bkg_discount_amount').val());
        additional = (additional == '') ? 0 : parseInt(additional);
        discount_amount = (discount_amount == '') ? 0 : parseInt(discount_amount);
        net_amount = net_amount + additional;
        //  var vendor_amount = Math.round($('#rtevndamt').val());
        net_amount = net_amount - discount_amount;
        driver_allowance = 0
        if ($('#Booking_bkg_driver_allowance_amount').val() != '' && $('#Booking_bkg_driver_allowance_amount').val() > 0)
        {
            // net_amount = net_amount + parseInt($('#Booking_bkg_driver_allowance_amount').val());
            driver_allowance = parseInt($('#Booking_bkg_driver_allowance_amount').val());
        }

        var net_without_cod = net_amount;

        // var convenience_charge = isNaN(parseInt($('#Booking_bkg_convenience_charge').val())) ? 0 : parseInt($('#Booking_bkg_convenience_charge').val());
        //                if(convenience_charge>0){
        //                   net_amount = net_amount + convenience_charge;
        //                }
        if (advance == 0) {
            var conFee1 = net_amount * 0.05;
            var conFee2 = 249;
            //        var conFee1 = net_amount * 0.10;
            //         var conFee2 = 499;
            if (conFee1 > conFee2) {
                var conFee = conFee2;
            } else {
                var conFee = conFee1;
            }
            //  conFee = 0 //set Convenience charge zero;
            var convenience_charge = Math.round(conFee);
            net_amount = net_amount + convenience_charge;
            $('#Booking_bkg_convenience_charge').val(convenience_charge);
        }
        var service_tax_rate = $('#Booking_bkg_service_tax_rate').val();
        var service_tax_amount = 0;
        var service_tax_withoutcod = 0;
        if (service_tax_rate != 0)
        {
            service_tax_amount = Math.round((net_amount * parseFloat(service_tax_rate) / 100));
            service_tax_withoutcod = Math.round((net_without_cod * parseFloat(service_tax_rate) / 100));
        }
        var tot_without_cod = net_without_cod + service_tax_withoutcod;
        var tollTaxVal = ($('#Booking_bkg_toll_tax').val() == '') ? 0 : parseInt($('#Booking_bkg_toll_tax').val());
        var stateTaxVal = ($('#Booking_bkg_state_tax').val() == '') ? 0 : parseInt($('#Booking_bkg_state_tax').val());
        $('#amountwithoutcod').val(tot_without_cod);
        var bkg_total_amount = net_amount + service_tax_amount + tollTaxVal + stateTaxVal + driver_allowance;
        var credits_used = parseInt(($('#Booking_bkg_credits_used').val() != '' && $('#Booking_bkg_credits_used').val() != undefined && $('#Booking_bkg_credits_used').val() != 'undefined') ? $('#Booking_bkg_credits_used').val() : 0);
        if ($('#creditpoints').is(':checked'))
        {
            payment = parseInt(isNaN($('#creditpoints').attr('credit')) ? 0 : $('#creditpoints').attr('credit'));
            $('#duediv').show();
            $('#Booking_optUseCredits').val(1);
        } else
        {
            $('#duediv').hide();
            $('#Booking_optUseCredits').val(0);
        }
        if (credits_used > 0) {
            $('#duediv').show();
        }
        var due = bkg_total_amount - advance - credits_used - payment + refund;
        $('#Booking_bkg_convenience_charge').val(convenience_charge);
        $('#Booking_bkg_due_amount').val(due);
        $('#Booking_bkg_total_amount').val(bkg_total_amount);
        // $('#Booking_bkg_vendor_amount').val(vendor_amount);
        $('#Booking_bkg_service_tax').val(service_tax_amount);
    }
    function getDiscount() {

        pdate = $("#Booking_bkg_pickup_date_date").val();
        ptime = $('#<?= CHtml::activeId($model, "bkg_pickup_date_time") ?>').val();
        if (pdate == '' && ptime == '') {
            $("#errordivpdate").text('');
            $("#errordivpdate").text('Please enter Pickupdate/Time');
        }

        if (pdate != '' && ($("#Booking_bkg_promo_code").val() != '' || $("#Booking_bkg_discount_amount").val() != '') && $("#Booking_bkg_base_amount").val() != '') {
            getDiscountbyCodenAmount($("#Booking_bkg_promo_code").val(), $("#Booking_bkg_base_amount").val());
        }
    }


    function getDiscountbyCodenAmount(code, amount) {

        var promo = new Promo();
        var model = {};
        //model.userId = $("#Booking_bkg_user_id").val();
        model.pickupDate = $("#Booking_bkg_pickup_date_date").val();
        model.pickupTime = $('#<?= CHtml::activeId($model, "bkg_pickup_date_time") ?>').val();
        model.code = code;
        model.amount = amount;
        model.fromCityId = $("#Booking_bkg_from_city_id").val();
        model.toCityId = $("#Booking_bkg_to_city_id").val();
        model.bkgId = $('#Booking_bkg_id').val();
        promo.model = model;


        if (code != '' && amount > 0) {
            $(document).on("getPromoCode", function (event, data) {
                promoCode(data);
            });
            promo.getPromoCode();
        } else if ($("#Booking_bkg_discount_amount").val() != '' && $("#Booking_bkg_promo_code").val() != '') {
            $("#Booking_bkg_discount_amount").val('');
            $("#Booking_bkg_total_amount").val($("#Booking_bkg_base_amount").val());
        } else if ($("#Booking_bkg_discount_amount").val() != '' && $("#Booking_bkg_base_amount").val() != '') {
            calculateAmount();
        }
    }

    function promoCode(data)
    {
        $("#Booking_bkg_discount_amount").val('');
        if (data.data.discount > 0) {
            $("#Booking_bkg_discount_amount").val(data.data.discount);
        } else {
            $("#Booking_bkg_discount_amount").val(0);
        }
        calculateAmount();
    }

    function getCarmodel() {
        if ($("#Booking_bkg_route").val() != '')
        {
            var rtid = $("#Booking_bkg_route").val();
            var href = '<?= Yii::app()->createUrl("rcsr/booking/getcarmodel"); ?>';
            $.ajax({
                url: href,
                dataType: "json",
                data: {"rt_id": rtid},
                "success": function (data) {
                    $("#Booking_bkg_total_amount").val('0');
                    $('#Booking_bkg_vehicle_type_id').append($('<option>').text('Select Car Model').attr('value', ''));
                    $.each(data, function (key, value) {
                        $('#Booking_bkg_vehicle_type_id').append($('<option>').text(value).attr('value', key));
                    });
                }
            });
        }
    }
    ;
    $('#Booking_bkg_pickup_date_date').datepicker({
        format: 'dd/mm/yyyy'
    });
    $('#Booking_bkg_return_date_date').datepicker({
        format: 'dd/mm/yyyy'
    });
    //            function mapInitialize(start, end) {
    //                $dist = '';
    //                var map;
    //                var directionsDisplay = new google.maps.DirectionsRenderer();
    //                var directionsService = new google.maps.DirectionsService();
    //                var mapOptions = {
    //                    zoom: 6,
    //                    mapTypeId: google.maps.MapTypeId.ROADMAP,
    //                    center: new google.maps.LatLng(30.73331, 76.77942),
    //                    mapTypeControl: false
    //                };
    //                map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
    //                directionsDisplay.setMap(map);
    //                $('#map_canvas').css('height', $('#desc').height());
    //                var request = {
    //                    origin: start,
    //                    destination: end,
    //                    travelMode: google.maps.DirectionsTravelMode.DRIVING
    //                };
    //                directionsService.route(request, function (response, status) {
    //                    if (status == google.maps.DirectionsStatus.OK) {
    //                        directionsDisplay.setDirections(response);
    //                        var leg = response.routes[0].legs[0];
    //                        diststr = leg.distance.text;
    //                        distval = diststr.replace(',', '');
    //                        distkm = getApplicableDistance(distval);
    //                        $dist = parseInt(distkm) * ($('#Booking_bkg_booking_type').val());
    //                        $time = leg.duration.value;
    //                        $time = Math.ceil($time / 60);
    //                        $('#Booking_bkg_trip_distance').val($dist);
    //                        $('#Booking_bkg_trip_duration').val($time);
    //                        getAmountbyCitiesnVehicle();
    //                        calculatefare();
    //                    }
    //                });
    //            }
    //            function getApplicableDistance(dist) {
    //                distkm = parseInt(dist) + 15;
    //                distkm = (Math.ceil(distkm / 10)) * 10;
    //                return distkm;
    //            }
    //            function fillDistance() {
    //                diststr = $('#Booking_bkg_trip_distance').val();
    //                distval = diststr.replace(',', '');
    //                $dist = parseInt(distval);
    //                $('#trip_distance').val($dist);
    //            }

    function editmulticity()
    {
        var $bkgtype = $("#Booking_bkg_booking_type").val();
        $('#ctyinfo_bkg_type_1').hide();

        $href = '<?= Yii::app()->createUrl('rcsr/booking/multicityform', ['bookingType' => '']) ?>' + $bkgtype;
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data) {

                multicitybootbox = bootbox.dialog({
                    message: data,
                    size: 'large',
                    title: 'Add pickup info',
                    onEscape: function () {
                        multicitybootbox.hide();
                        multicitybootbox.remove();

                    },
                });
                multicitybootbox.on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });


            }
        });
    }

    function updateMulticity(data, tot)
    {
        var data = $.parseJSON(data);
        $('#tripTablecreate').show();
        $('.multicitydetrow').remove();
        $('#address_div').hide();

        $('#Booking_bkg_pickup_date_date').val(data[0].pickup_date).change();
        $('#Booking_bkg_pickup_date_time').val(data[0].pickup_time).change();
        $('#Booking_bkg_pickup_address').val(data[0].pickup_address).change();
        $('#Booking_bkg_drop_address').val(data[tot].drop_address).change();
        $('#Booking_bkg_pickup_pincode').val(data[0].pickup_pin).change();
        $('#Booking_bkg_drop_pincode').val(data[tot].drop_pin).change();
        $("#Booking_bkg_from_city_id").select2("val", data[0].pickup_city).change();
        $("#Booking_bkg_to_city_id").select2("val", data[tot].drop_city).change();
        $("#multicityjsondata").val(JSON.stringify(data)).change();
        $("#ctyinfo_bkg_type_1").hide();
        $('#show_return_date_time').html("");
        if ($('#Booking_bkg_booking_type').val() == 2)
        {
            $('#Booking_bkg_return_date_time').val(data[tot].return_time).change();
            $('#Booking_bkg_return_date_date').val(data[tot].return_date).change();
            //    $('#show_return_date_time').html("<b>return date:<b> " + data[tot].return_date + " " + data[tot].return_time);
        }

        var total_distance = 0;
        var total_duration = 0;
        for (var i = 1; i <= tot + 1; i++)
        {
            $('#insertTripRowcreate').before('<tr class="multicitydetrow">' +
                    '<td id="fcitycreate0"></td>' +
                    '<td id="tcitycreate0"> </td>' +
                    '<td id="fdatecreate0"> </td>' +
                    '<td id="distancecreate0"> </td>' +
                    '<td id="durationcreate0"> </td>' +
                    '<td id="noOfDayscreate0"> </td>' +
                    '</tr>');
            $('#fcitycreate0').attr('id', 'fcitycreate' + i);
            $('#tcitycreate0').attr('id', 'tcitycreate' + i);
            $('#fdatecreate0').attr('id', 'fdatecreate' + i);
            $('#distancecreate0').attr('id', 'distancecreate' + i);
            $('#durationcreate0').attr('id', 'durationcreate' + i);
            $('#pickadrscreate0').attr('id', 'pickadrscreate' + i);
            $('#dropadrscreate0').attr('id', 'dropadrscreate' + i);
            $('#noOfDayscreate0').attr('id', 'noOfDayscreate' + i);
            $('#noOfDayscreate' + i).text('1');

            total_distance = (total_distance + parseInt(data[(i - 1)].distance));
            total_duration = (total_duration + parseInt(data[(i - 1)].duration));

            $('#noOfDayscreate' + i).text(data[(i - 1)].day);
            $('#totdayscreate').text(data[(i - 1)].totday);
            $('#fcitycreate' + i).html('<b>' + data[(i - 1)].pickup_city_name + '</b><br>' + data[(i - 1)].pickup_address + " ,pin: " + data[(i - 1)].pickup_pin);
            $('#tcitycreate' + i).html('<b>' + data[(i - 1)].drop_city_name + '</b><br>' + data[(i - 1)].drop_address + " ,pin: " + data[(i - 1)].drop_pin);
            $('#fdatecreate' + i).text(data[(i - 1)].pickup_date + " " + data[(i - 1)].pickup_time);
            $('#distancecreate' + i).text(data[(i - 1)].distance);
            $('#durationcreate' + i).text(data[(i - 1)].duration);
        }


        $('#Booking_bkg_trip_distance').val(total_distance);
        $('#Booking_bkg_trip_duration').val(total_duration);
        getAmountbyCitiesnVehicle();

    }

    function  getDateobj(pdpdate, ptptime)
    {
        var date = pdpdate;
        var time = ptptime;
        var dateArr = date.split("/");
        var timeArr = time.split(" ");
        var mer = timeArr[1];
        var temp = timeArr[0].split(":");
        var hour = Number(temp[0]);
        var min = Number(temp[1]);
        if (mer == "PM") {
            if (hour != 12) {
                hour = 12 + hour;
            }
        } else if (hour == 12) {
            hour = 0;
        }
        //  var currDateTime = new Date();
        var dateObj = new Date(Number(dateArr[2]), Number(dateArr[1]) - 1, Number(dateArr[0]), hour, min, 0);
        return dateObj;
    }


    $('#<?= CHtml::activeId($model, 'bkg_flight_no') ?>').mask('XXXX-XXXXXX', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            },
            'X': {
                pattern: /[0-9A-Za-z]/, optional: true
            },
        },
        placeholder: "__ __ __ ____",
        clearIfNotMatch: true
    });
    $('form').on('focus', 'input[type=number]', function (e) {
        $(this).on('mousewheel.disableScroll', function (e) {
            e.preventDefault()
        })
        $(this).on("keydown", function (event) {
            if (event.keyCode === 38 || event.keyCode === 40) {
                event.preventDefault();
            }
        });
    });
    $('form').on('blur', 'input[type=number]', function (e) {
        $(this).off('mousewheel.disableScroll');
        $(this).off('keydown');
    });
    $("#Booking_bkg_spl_req_lunch_break_time").change(function(){
        calculateEstimatedAmount();
    });

</script>
<input id="map_canvas" type="hidden">
<?
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>