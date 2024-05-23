<?php
$version          = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/csrbooking.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/route.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promo.js?v=' . $version);
$selectizeOptions = ['create'             => false, 'persist'            => true, 'selectOnTab'        => true,
    'createOnBlur'       => true, 'dropdownParent'     => 'body',
    'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'      => 'id',
    'openOnFocus'        => true, 'preload'            => false,
    'labelField'         => 'text', 'valueField'         => 'id', 'searchField'        => 'text', 'closeAfterSelect'   => true,
    'addPrecedence'      => false,];
$cartype          = VehicleTypes::model()->getParentVehicleTypes(1);
// = Agents::model()->getAgentList();
$status           = Booking::model()->getBookingStatus();

$bookingType  = Booking::model()->getBookingType();
//print_r($bookingType);
$infosource   = BookingAddInfo::model()->getInfosource('admin');
$countrycode  = Yii::app()->params['countrycode'];
$ccode        = (int) str_replace('+', '', $countrycode);
$showadd      = 0;
?>
<style>
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
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin:0;
    }
    .selectize-input {
        min-width: 0px !important; 
        width: 30% !important;
    }
    .checkbox label {
        padding-left: 0px;
    }

    td, th {
        padding: 10px  !important ; 
    }
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
</style>
<div class="container">
    <?php
    $form         = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                     => 'booking-form', 'enableClientValidation' => true,
        'clientOptions'          => array(
            'validateOnSubmit' => true,
            'errorCssClass'    => 'has-error',
            'afterValidate'    => 'js:function(form,data,hasError){
                if(!hasError){
                    $("#btnsbmt").prop( "disabled", true );
                    if(!validateBooking())
					{
                        $("#btnsbmt").prop( "disabled", false );
                        return false;                         
					}
                    $.ajax({
                    "type":"POST",
                    "dataType":"json",
                    async: false,
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
                        $("#btnsbmt").prop( "disabled", false );
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
                                $("#booking-form").submit();
                            }
                            else{
                            $("#btnsbmt").prop( "disabled", false );
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

        <?= $form->hiddenField($model, 'lead_id', array('readonly' => true)) ?>
        <?= $form->hiddenField($model->bkgUserInfo, 'bkg_user_id'); ?>
        <?= $form->hiddenField($model, 'bkg_from_city_id'); ?>
        <?= $form->hiddenField($model, 'bkg_to_city_id'); ?>
        <?= $form->hiddenField($model, 'routeProcessed'); ?>
        <div class="col-md-7">
            <?=
            $form->errorSummary($model);
            echo CHtml::errorSummary($model)
            ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default panel-border">
                       <h3 class="pl15 pb10">Booking Information</h3>
                        <div class="panel-body pt0">
                            <div class="row">
                                <div class="col-sm-6">
                                    
                                    <?
                                    if($package){
                                    ?>
                                    <div class="form-group">
                                       
                                     <?= $form->textFieldGroup($model, 'bkg_booking_type', array('label' => "Booking Type", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Package')))) ?>
                                    </div>
                                    <?}else{?>
                                    <div class="form-group">
                                        <label class="control-label" for="exampleInputName6">Booking Type</label>
                                        <input type="hidden" id="multicityjsondata" name="multicityjsondata" value='<?= json_encode($model->preData); ?>'>
                                        <?
                                        $dataBookType = VehicleTypes::model()->getJSON($bookingType);
                                        $this->widget('booster.widgets.TbSelect2', array(
                                            'model'          => $model,
                                            'attribute'      => 'bkg_booking_type',
                                            'val'            => ($model->bkg_booking_type == '') ? 1 : $model->bkg_booking_type,
                                            'asDropDownList' => FALSE,
                                            'options'        => array('data' => new CJavaScriptExpression($dataBookType)),
                                            'htmlOptions'    => array('style' => 'width:100%', 'placeholder' => 'Select Booking Type', 'class' => 'input-group')
                                        ));
                                        ?>
                                        <span class="btn-info p5 mt10 col-xs-3" id="addmulticities" style="display: none">add cities</span>
                                        <span class="has-error"><? echo $form->error($model, 'bkg_booking_type'); ?></span>
                                    </div>
                                    <? }?>
                                </div>
                                <div class="col-sm-6 hide">
                                    <div class="form-group">
                                        <label class="control-label" for="exampleInputCompany6">Route</label>
                                        <?php
                                        $this->widget('booster.widgets.TbSelect2', array(
                                            'model'          => $model,
                                            'attribute'      => 'bkg_route',
                                            'val'            => $model->bkg_route,
                                            'asDropDownList' => FALSE,
                                            'options'        => array('data' => new CJavaScriptExpression('$routeList')),
                                            'htmlOptions'    => array('style' => 'width:100%', 'placeholder' => 'Select Route')
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>
                             <!--package-->
                             <div class="row" id='packageTablecreate' style="display: <?= ($package != '') ? 'block' : 'none' ?>">
                                 <div class="col-xs-12 table-responsive" >
                                     <div class="float-none marginauto">
                                         <h3 class="mb10 text-uppercase">PACKAGE ITINERARY  <button type="button" class="btn btn-info ml15" onclick="editmulticity()"><i class="fa fa-edit"></i></button></h3>
                                         <table class="table-bordered11" border="1" CELLPADDING="10" width="100%">
                                             <tr><td>
                                                     <div class="row">
                                                         <?
                                                         if (!empty($packagedt))
                                                         {

                                                             $i     = 0;
                                                             $items = '<div class="">';
                                                             foreach ($packagedt as $drv)
                                                             {
                                                                 ?>
                                                                 <div class="col-xs-12 col-sm-12 col-md-12 pt10">
                                                                     <div class="col-xs-12 "><b>Day: <?= $drv['pcd_day_serial'] ?></b> <?= $drv['pcd_from_location'] . " To " . $drv['pcd_to_location']; ?></div>
                                                                 </div>
                                                                 <div class="col-xs-12 col-sm-12 col-md-12 pt10">
                                                                     <div class="col-xs-12 "><b>Description: </b> <?= $drv['pcd_description']; ?></div>
                                                                 </div>
                                                                 <?
                                                             }
                                                         }
                                                         ?>


                                                     </div>
                                                 </td></tr>
                                         </table>
                                          <div class="mt10" id=''></div>
                                     </div>
                                 </div>
                             </div>
                            
                            
                            
                            
                            
                            <!--                            multicity-->
                            <div class="row" id='tripTablecreate' style="display: <?= ($model->preData != '') ? 'block' : 'none' ?>">
                                <div class="col-xs-12 table-responsive" >
                                    <div class="float-none marginauto">
                                        <h3 class="mb10 text-uppercase">Trip Info  <button type="button" class="btn btn-info ml15" onclick="editmulticity()"><i class="fa fa-edit"></i></button></h3>

                                        <table class="table-bordered11" border="1" CELLPADDING="10" width="100%">
                                            <thead>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Date</th>
                                            <th>Distance</th>
                                            <th>Duration</th>
                                            <th>Day</th>
                                            </thead>
                                            <?
                                            $diffdays     = 0;
                                            if ($model->preData != '')
                                            {

                                                $arrmulticitydata = $model->preData;
                                                foreach ($arrmulticitydata as $key => $value)
                                                {
                                                    if ($key == 0)
                                                    {
                                                        $diffdays = 1;
                                                    }
                                                    else
                                                    {
//                                                    $ddays = strtotime($value->date) - strtotime($arrmulticitydata[0]->date);
//                                                    $diffdays = ceil(abs($ddays) / (60 * 60 * 24)) + 1;
                                                        $date1      = new DateTime(date('Y-m-d', strtotime($arrmulticitydata[0]->date)));
                                                        $date2      = new DateTime(date('Y-m-d', strtotime($value->date)));
                                                        $difference = $date1->diff($date2);
                                                        $diffdays   = ($difference->d + 1);
                                                    }
                                                    ?>
                                                    <tr class="multicitydetrow">
                                                        <td id="fcitycreate<?= $key ?>"><b><?= $value->pickup_city_name ?></b><br><?= $value->pickup_address; ?></td>
                                                        <td id="tcitycreate<?= $key ?>"><b><?= $value->drop_city_name ?> </b><br><?= $value->drop_address; ?></td>
                                                        <td id="fdatecreate<?= $key ?>"><?= $value->pickup_date . " " . $value->pickup_time ?></td>
                                                        <td id="fdistcreate<?= $key ?>"><?= $value->distance; ?> </td>
                                                        <td id="fduracreate<?= $key ?>"><?= $value->duration; ?> </td>
                                                        <td id="noOfDayscreate<?= $key ?>"><? echo $diffdays; ?> </td>
                                                    </tr>
                                                    <?
                                                    $last_date = date('Y-m-d H:i:s', strtotime($value->date . '+ ' . $value->duration . ' minute'));
                                                }
                                            }
                                            ?>
                                            <tr id='insertTripRowcreate'></tr>
                                        </table>
                                        <div class="mt10" id='show_return_date_time'></div>
                                        <?
                                        if ($model->preData != '')
                                        {
                                            if ($date1 != '')
                                            {
                                                $totdiff = $date1->diff(new DateTime(date('Y-m-d', strtotime($last_date))))->d + 1;
                                            }
                                            else
                                            {
                                                $totdiff = $diffdays;
                                            }
                                        }
                                        ?>
                                        <h4>Total days for the trip: <span class="blue-color"><span id="totdayscreate"><?= $totdiff ?></span> days</span></h4>

                                    </div>
                                </div>
                            </div>
                            <?
                            $showdiv = ($model->bkg_booking_type != '' ) ? $model->bkg_booking_type : 1;
                            ?>
                            <!-- multicity-->
                            <div class="row" id="ctyinfo_bkg_type_1"  style="display: <? echo ($showdiv == 1) ? 'block' : 'none' ?>">
                                <div class="col-sm-6 ">
                                    <div class="form-group cityinput">
                                        <label class="control-label" for="exampleInputName6">Source City</label>
                                        <?php
//                                        $this->widget('booster.widgets.TbSelect2', array(
//                                            'model' => $model,
//                                            'attribute' => 'bkg_from_city_id',
//                                            'val' => $model->bkg_from_city_id,
//                                            'asDropDownList' => FALSE,
//                                            'options' => array('data' => new CJavaScriptExpression('$cityList')),
//                                            'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Source City')
//                                        ));
                                        $this->widget('ext.yii-selectize.YiiSelectize', array(
                                            'model'            => $model,
                                            'attribute'        => 'bkg_from_city_id1',
                                            'useWithBootstrap' => true,
                                            "placeholder"      => "Select Source City",
                                            'fullWidth'        => false,
                                            'htmlOptions'      => array('width' => '100%',
                                                'id'    => 'Booking_bkg_from_city_id1'
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
                                <div class="col-sm-6">
                                    <div class="form-group cityinput">
                                        <label class="control-label" for="exampleInputCompany6">Destination City</label>
                                        <?php
//                                        $this->widget('booster.widgets.TbSelect2', array(
//                                            'model' => $model,
//                                            'attribute' => 'bkg_to_city_id',
//                                            'val' => $model->bkg_to_city_id,
//                                            'asDropDownList' => FALSE,
//                                            'options' => array('data' => new CJavaScriptExpression('$cityList')),
//                                            'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Destination City')
//                                        ));


                                        $this->widget('ext.yii-selectize.YiiSelectize', array(
                                            'model'            => $model,
                                            'attribute'        => 'bkg_to_city_id1',
                                            'useWithBootstrap' => true,
                                            "placeholder"      => "Select Destination City",
                                            'fullWidth'        => false,
                                            'htmlOptions'      => array('width' => '100%',
                                                'id'    => 'Booking_bkg_to_city_id1'
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

                            <div class="row" id="pickup_div" style="display: <? echo ($showdiv == 1) ? 'block' : 'none' ?>">
                                <div class="col-sm-6">

                                    <? $strpickdate = ($model->bkg_pickup_date == '') ? date('Y-m-d H:i:s', strtotime('+4 hour')) : $model->bkg_pickup_date; ?>
                                    <?=
                                    $form->datePickerGroup($model, 'bkg_pickup_date_date', array('label'         => 'Pickup Date',
                                        'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Pickup Date', 'value' => DateTimeFormat::DateTimeToDatePicker($strpickdate), 'class' => 'input-group border-gray full-width')), 'prepend'       => '<i class="fa fa-calendar"></i>'));
                                    ?>
                                </div>

                                <div class="col-sm-6">

                                    <?
                                    echo $form->timePickerGroup($model, 'bkg_pickup_date_time', array('label'         => 'Pickup Time',
                                        'widgetOptions' => array('id' => CHtml::activeId($model, "bkg_pickup_date_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Pickup Time', 'value' => date('h:i A', strtotime($strpickdate)), 'class' => 'input-group border-gray full-width'))));
                                    ?>

                                </div>
                                <div id="errordivpdate" class="ml15 mt10" style="color:#da4455"></div>
                            </div>
                            <div class="row" style="display: none">
                                <div class="col-sm-6">
                                    <?
                                    $strrtedate  = ($model->bkg_return_date == '') ? '' : strtotime($model->bkg_return_date);
                                    if ($model->bkg_return_date != '')
                                    {
                                        $model->bkg_return_date = DateTimeFormat::DateTimeToDatePicker($model->bkg_return_date);
                                    }
                                    ?>
                                    <?=
                                    $form->datePickerGroup($model, 'bkg_return_date_date', array('label'         => 'Return Date',
                                        'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Return Date', 'value' => $model->bkg_return_date)), 'prepend'       => '<i class="fa fa-calendar"></i>'));
                                    ?>
                                </div>
                                <div class="col-sm-6">
                                    <?=
                                    $form->timePickerGroup($model, 'bkg_return_date_time', array('label'         => 'Return Time',
                                        'widgetOptions' => array('options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Return Time', 'id' => 'Booking_bkg_return_date_time', 'value' => date('h:i A', $strrtedate)))));
                                    ?>
                                </div>
                                <div id="errordivreturn" class="mt5 ml15" style="color:#da4455"></div>
                            </div>
                            <?
                            if ($model->lead_id > 0)
                            {
                                $showadd = 1;
                            }
                            ?>
                            <div class="row" id="address_div" style="display: <? echo ($showdiv == 1 || $showadd == 1) ? 'block' : 'none' ?>">
                                <div class="col-sm-6">
                                    <?= $form->textAreaGroup($model, 'bkg_pickup_address', array('label' => 'Pick up Location', 'widgetOptions' => array('htmlOptions' => array()))) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->textAreaGroup($model, 'bkg_drop_address', array('label' => 'Drop off Location', 'widgetOptions' => array('htmlOptions' => array()))) ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model, 'bkg_trip_distance', array('label' => "Estimated distance", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'In Km')))) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model, 'bkg_trip_duration', array('label' => "Estimated duration", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'In Min')))) ?>
                                </div>   
                            </div>
                            <div class="row">
<!--                                <div class="col-sm-6">
                                    <div class="form-group" style="display: none">
                                        <label class="control-label" for="exampleInputCompany6">Trip Type</label>
                                        <?php
//                                        $this->widget('booster.widgets.TbSelect2', array(
//                                            'model'       => $model,
//                                            'attribute'   => 'bkg_trip_type',
//                                            'val'         => $model->bkg_trip_type,
//                                            'data'        => Booking::model()->trip_type,
//                                            'htmlOptions' => array('style' => 'width:190px', 'placeholder' => 'Select Trip Type')
//                                        ));
                                        ?>
                                    </div>
                                </div>-->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label" for="exampleInputCompany6">Car Model</label>
                                        <?php
                                        $this->widget('booster.widgets.TbSelect2', array(
                                            'model'       => $model,
                                            'attribute'   => 'bkg_vehicle_type_id',
                                            'val'         => $model->bkg_vehicle_type_id,
                                            'data'        => $cartype,
                                            'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Car Type')
                                        ));
                                        ?>
                                        <span class="has-error"><? echo $form->error($model, 'bkg_vehicle_type_id'); ?></span>
                                    </div>
                                </div>
                            </div>

							
							
						
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
                            <div class="row">
                             <?
                                if ($model->trip_user == '')
                                {
                                    $model->trip_user = 1;
                                }
                                ?>

								
      
							<div class="form-group">
								<input id="ytBooking_trip_user" type="hidden" value="" name="Booking[trip_user]">
								<span id="Booking_trip_user"><label class="checkbox-inline">
										<div class="radio" id="uniform-Booking_trip_user_0">
											<span class="checked">
												<input  placeholder="Trip User" id="Booking_trip_user_0" value="1" checked="checked" type="radio" name="Booking[trip_user]">
											</span></div>Gozo</label>
								</span>

							</div>	
							
							
							
							
							</div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="errorShow" style="display: none">
                <div class="col-xs-12">
                    <div class="panel panel-default panel-border">

                        <div class="panel-body ">
                            <div class="row">
                                <div class="col-xs-12 text-danger" id="errorMsg"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default panel-border">
                        <h3 class="pl15">Payment Information</h3>
                        <div class="panel-body pt0">
                            <div class="row">

                                <?= $form->hiddenField($model->bkgInvoice, 'bkg_chargeable_distance'); ?>
                                <?= $form->hiddenField($model->bkgInvoice, 'bkg_garage_time'); ?>
                                <?= $form->hiddenField($model->bkgInvoice, 'bkg_is_toll_tax_included'); ?>
                                <?= $form->hiddenField($model->bkgInvoice, 'bkg_is_state_tax_included'); ?>
                                <?= $form->hiddenField($model->bkgInvoice, 'bkg_gozo_base_amount'); ?>


                                <?
                                $toll_checked  = ($model->bkgInvoice->bkg_is_toll_tax_included == 1) ? 'checked="checked"  disabled="disabled"' : "";
                                $state_checked = ($model->bkgInvoice->bkg_is_state_tax_included == 1) ? 'checked="checked" disabled="disabled"' : "";
                                ?>

                                <div class="col-xs-6">Toll tax Included <span class="checkertolltax"><input type="checkbox" name="bkg_is_toll_tax_included1" id="Booking_bkg_is_toll_tax_included1" <?= $toll_checked ?>></span></div>
                                <div class="col-xs-6">State tax Included <span class="checkerstatetax"><input type="checkbox" name="bkg_is_state_tax_included1" id="Booking_bkg_is_state_tax_included1" <?= $state_checked ?>></span></div>                             

                            </div>
                            <div class="row">
                                <? //$ratedivshow   = ($model->bkg_trip_type == 2 ) ? '' : 'hide' ?>
                                <div class="col-sm-6" >
                                    <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_rate_per_km_extra', array('widgetOptions' => array())) ?>                                   
                                </div>
                                <div class="col-sm-6" id="div_rate_per_km"  style="display: none">
                                    <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_rate_per_km', array('widgetOptions' => array())) ?>
                                    <div id="errordivrate" class="mt5 " style="color:#da4455"></div>
                                </div>
                                <div class="col-sm-6">
                                    <?
                                    $readonly      = [];
                                    if (!Yii::app()->user->checkAccess('accountEdit'))
                                    {
                                        $readonly = ['readonly' => 'readonly'];
                                    }
                                    ?>
                                    <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_base_amount', array('label' => 'Amount', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Net Charge'] + $readonly))) ?>
                                    <div id="trip_rate"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_additional_charge_remark', array('widgetOptions' => array('htmlOptions' => []))) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_additional_charge', array('widgetOptions' => array('htmlOptions' => []))) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <?
                                    $agentDisable                = ($model->bkg_agent_id > 0) ? ['readonly' => 'readonly'] : [];
                                    ?>
                                    <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_promo1_code', array('label' => 'Promo Code', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Promo Code'] + $agentDisable))) ?>
                                    <span class="text-danger" id="promocreditsucc"></span>
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label" for="exampleInputName6">Discount</label>
                                    <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_discount_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['readonly' => 'readonly', 'placeholder' => 'Discount']))) ?>
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-sm-6">
                                    <?= $form->hiddenField($model->bkgInvoice, 'bkg_vendor_amount', array('widgetOptions' => array('htmlOptions' => []))) ?>
                                </div>
                                <input type="hidden" name="rtevndamt" id="rtevndamt">
                                <?= $form->hiddenField($model->bkgInvoice, 'bkg_quoted_vendor_amount'); ?>

                                <div class="col-sm-6  "><?= $form->numberFieldGroup($model->bkgInvoice, 'bkg_driver_allowance_amount', array('label' => 'Driver Allowance', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Driver allowance', 'oldamount' => 0]))); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_toll_tax', array('widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly', 'plceholder' => 'Toll Tax')))) ?>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_state_tax', array('widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly', 'plceholder' => 'State Tax')))) ?>
                                </div>
                            </div>

                            <?
//  if ($model->bkg_advance_amount == '' || $model->bkg_advance_amount == 0) {
                            ?>
                            <div class="row">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_convenience_charge', array('label' => 'Collect on delivery(COD) fee', 'widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly')))) ?>                                 
                                </div>
                            </div>
                            <?
                            //$staxrate                    = Filter::getServiceTaxRate();
							$staxrate				 = BookingInvoice::getGstTaxRate($model->bkg_agent_id, $model->bkg_booking_type);
                            $taxLabel                    = ($staxrate == 5) ? 'GST' : 'Service Tax ';
                            ?>

                            <div class="row">
                                <div class="col-sm-6 pull-right">
                                    <? $model->bkgInvoice->bkg_service_tax_rate = $staxrate; ?>
                                    <?= $form->hiddenField($model->bkgInvoice, 'bkg_service_tax_rate'); ?>
                                    <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_service_tax', array('label' => "$taxLabel    (rate: " . $staxrate . '%)', 'widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly')))) ?>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label" for="amountwithoutcod">Total Amount(Without COD)</label>
                                        <input readonly="readonly" class="form-control" name="amountwithoutcod" id="amountwithoutcod" type="text" value="0">
                                    </div> 
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_total_amount', array('label' => 'Total Chargeable ' . $model->bkgInvoice->getAttributeLabel('bkg_total_amount'), 'widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly')))) ?>
                                </div>
                            </div>

                            <?
                            $tripdistance = ($model->bkg_trip_distance != '' && $model->bkg_trip_distance > 0) ? $model->bkg_trip_distance : 0;
                            if ($tripdistance > 0)
                            {
                                if ($model->bkgInvoice->bkg_rate_per_km > 0)
                                {
                                    $tripextrarate = "Note: Ext. Chrg. After " . $tripdistance . " Kms. = " . $model->bkgInvoice->bkg_rate_per_km . "/Km.";
                                }
                            }
                            ?>
                            <div class="row" id="vehicle_dist_ext"><?= $tripextrarate ?>  </div>
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
                                    <label class="control-label" for="exampleInputName6">Contact Number</label>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <?php
                                                $this->widget('ext.yii-selectize.YiiSelectize', array(
                                                    'model'            => $model->bkgUserInfo,
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
                                                                                          obj.setValue('{$model->bkgUserInfo->bkg_country_code}');
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
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <?= $form->textFieldGroup($model->bkgUserInfo, 'bkg_contact_no', array('label' => '', 'widgetOptions' => array('class' => '', 'htmlOptions' => array('onchange' => 'showlinkedUser()')))) ?>
                                                <div id="errordivmob" style="color:#da4455"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label" for="exampleInputName6">Alternate Contact Number</label>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <?php
                                                $this->widget('ext.yii-selectize.YiiSelectize', array(
                                                    'model'            => $model->bkgUserInfo,
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
                                                                                         obj.setValue('{$model->bkgUserInfo->bkg_alt_country_code}');

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
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <?= $form->textFieldGroup($model->bkgUserInfo, 'bkg_alt_contact_no', array('label' => '', 'widgetOptions' => array())) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= $form->emailFieldGroup($model->bkgUserInfo, 'bkg_user_email', array('label' => 'Email', 'widgetOptions' => array('htmlOptions' => array('class' => '', 'onchange' => 'showlinkedUser()')))); ?>
                                    <div id="errordivemail" style="color:#da4455"></div>
                                </div>
                                <div class="col-xs-12" id="linkedusers">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model->bkgUserInfo, 'bkg_user_fname', array('label' => "First Name", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'First Name')))) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->textFieldGroup($model->bkgUserInfo, 'bkg_user_lname', array('label' => 'Last Name', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Last Name')))) ?>
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
                            <div class="row">
                                <div class="col-xs-12"><label class="control-label" style="text-align: left;" for="exampleInputName6">Booking Preference</label></div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12"> 
                                    <div class="form-group"> 
                                        <label >Add Tags</label>
                                        <?php
                                        $SubgroupArray2 = Booking::model()->getTags() + [0 => ''];
                                        $this->widget('booster.widgets.TbSelect2', array(
                                            'name'        => 'bkg_tags',
                                            'model'       => $model,
                                            'data'        => $SubgroupArray2,
                                            // 'value' => explode(',', $model->bkg_tags),
                                            'htmlOptions' => array(
                                                'multiple'    => 'multiple',
                                                'placeholder' => 'Add keywords that you may use to search for this booking later',
                                                'width'       => '100%',
                                                'style'       => 'width:100%',
                                            ),
                                        ));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label" style="text-align: left;" for="exampleInputName6"><nobr>How did you hear about Gozo cabs?</nobr></label>
                                        <?php
                                        $datainfo       = VehicleTypes::model()->getJSON($infosource);
                                        $this->widget('booster.widgets.TbSelect2', array(
                                            'model'          => $model->bkgAddInfo,
                                            'attribute'      => 'bkg_info_source',
                                            'val'            => "'" . $model->bkgAddInfo->bkg_info_source . "'",
                                            'asDropDownList' => FALSE,
                                            'options'        => array('data' => new CJavaScriptExpression($datainfo)),
                                            'htmlOptions'    => array('style' => 'width:100%', 'placeholder' => 'Select Infosource')
                                        ));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <?= $form->textFieldGroup($model->bkgAddInfo, 'bkg_flight_no', array('label' => 'Flight Number', 'widgetOptions' => array('htmlOptions' => array()))) ?>
                                    </div>
                                </div>
                                <? $sourceDescShow = ($model->bkgAddInfo->bkg_info_source == 'Friend' || $model->bkgAddInfo->bkg_info_source == 'Other') ? '' : 'hide'; ?>
                                <div class="col-sm-6 <?= $sourceDescShow ?>" id="source_desc_show">
                                    <div class="form-group">
                                        <label class="control-label" for="type">&nbsp;</label>
                                        <?= $form->textFieldGroup($model->bkgAddInfo, 'bkg_info_source_desc', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => '')))) ?>										
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="control-label" for="exampleInputName6"></label>
                                    <?= $form->fileFieldGroup($model, 'fileImage', array('label' => '', 'widgetOptions' => array('htmlOptions' => []))) ?>
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label" for="exampleInputName6"></label>
                                    <?= $form->checkboxGroup($model->bkgPref, 'bkg_tentative_booking', array('widgetOptions' => array('htmlOptions' => []))) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?= $form->textAreaGroup($model, 'bkg_remark', array('label' => 'Enter booking remarks', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'booking remarks are internal to Gozo. These are not shared with Agent, vendor or driver',)))) ?>
                                </div>
                                <div class="col-sm-12">
                                    <?= $form->textAreaGroup($model, 'bkg_instruction_to_driver_vendor', array('label' => 'Instructions to Vendor/Driver', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Add special requirements or booking instructions for vendor/driver here')))) ?>
                                </div>
                                <div class="col-sm-12">
                                    <?= $form->checkboxGroup($model->bkgPref, 'bkg_invoice', array('widgetOptions' => array('htmlOptions' => []))) ?>
                                </div>
                            </div>
                            <div class="row">
                                <label for="inputEmail" class="control-label col-xs-5">Customer Type</label>
                                <div class="col-xs-7">
                                    <?=
                                    $form->radioButtonListGroup($model->bkgAddInfo, 'bkg_user_trip_type', array(
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
                                        <?= $form->checkboxGroup($model->bkgPref, 'bkg_send_email', ['label' => 'Email']) ?>
                                    </label>
                                    <label class="checkbox-inline pt0">
                                        <?= $form->checkboxGroup($model->bkgPref, 'bkg_send_sms', ['label' => 'Phone']) ?>
                                    </label>
                                </div>
                            </div>

                            <div class="row mb5">
                                <label for="inputEmail" class="control-label col-xs-5">Number of Passengers</label>
                                <div class="col-xs-7">
                                    <?= $form->numberFieldGroup($model->bkgAddInfo, 'bkg_no_person', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of Passengers", 'min' => 1, 'max' => 10]), 'groupOptions' => ['class' => 'm0'])) ?>                      
                                </div>
                            </div>
                            <div class="row mb5">
                                <label for="inputEmail" class="control-label col-xs-5">Number of large suitcases</label>
                                <div class="col-xs-7">
                                    <?= $form->numberFieldGroup($model->bkgAddInfo, 'bkg_num_large_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of large suitcases", 'min' => 0, 'max' => 10]), 'groupOptions' => ['class' => 'm0'])) ?>                      
                                </div>
                            </div>
                            <div class="row mb5">
                                <label for="inputEmail" class="control-label col-xs-5">Number of small bags</label>
                                <div class="col-xs-7">
                                    <?= $form->numberFieldGroup($model->bkgAddInfo, 'bkg_num_small_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of small bags", 'min' => 0, 'max' => 10]), 'groupOptions' => ['class' => 'm0'])) ?>                      
                                </div>
                            </div>
<!--                            <div class="row ">
                                <div class="col-xs-6">
                                    <?= $form->numberFieldGroup($model, 'bkg_pickup_pincode', array('label' => 'Pickup Address Pin Code', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Pincode", 'min' => 100000, 'max' => 999999]), 'groupOptions' => ['class' => 'm0'])) ?>  
                                </div>
                                <div class="col-xs-6">
                                    <?= $form->numberFieldGroup($model, 'bkg_drop_pincode', array('label' => 'Drop Address Pin Code', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Pincode", 'min' => 100000, 'max' => 999999]), 'groupOptions' => ['class' => 'm0'])) ?>  
                                </div>
                            </div>-->
                            <div class="row ">
                                <div class="col-xs-12 special_request">
                                    <h2 class="mb10">Special Requests</h2>
                                    <div class="col-xs-12">
                                        <?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_senior_citizen_trvl', []) ?>
                                        <?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_kids_trvl', []) ?>
                                        <?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_woman_trvl', []) ?>
                                        <?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_carrier', []) ?>
                                        <?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_driver_hindi_speaking', []) ?>
                                        <?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_driver_english_speaking', []) ?>
                                        <?= $form->checkboxGroup($model, 'bkg_chk_others', ['label' => 'Others']) ?>
                                        <div id="othreq" style="display: none">
                                            <?= $form->textFieldGroup($model->bkgAddInfo, 'bkg_spl_req_other', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Other Requests"]), 'groupOptions' => ['class' => 'm0'])) ?>  
                                        </div>
                                        <?= $form->checkboxGroup($model, 'bkg_add_my_trip', ['label' => 'I Will Take Journy Break','widgetOptions' =>['htmlOptions' => ['checked' => "checked"]]]) ?>
                                        <?= $form->dropDownListGroup($model->bkgAddInfo, 'bkg_spl_req_lunch_break_time', ['label' => '', 'widgetOptions' => ['data' => ['0' => 'Minutes','30' => '30','60' => '60','90' => '90','120' => '120','150' => '150','180' => '180'] , 'htmlOptions' => []]]) ?>
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
        <input type="hidden" id="agentnotifydata" name="agentnotifydata" value='<?= json_encode($model->agentNotifyData); ?>'>
        <div class="col-xs-12 text-center pb10">
            <?= CHtml::submitButton('Submit', array('style' => 'font-size:1.4em', 'class' => 'btn btn-primary btn-lg pl50 pr50', 'id' => 'btnsbmt')); ?>
        </div>
    </div>
    <div id="driver1"></div>
    <?php $this->endWidget(); ?>
</div>

<script type="text/javascript">
    $sourceList = null;
    $(document).ready(function () {
        jQuery('#Booking_bkg_pickup_date_date').datepicker({'autoclose': true, 'startDate': new Date(), 'format': 'dd/mm/yyyy', 'language': 'en'});
        jQuery('#Booking_bkg_pickup_date_time').timepicker({'defaultTime': false, 'autoclose': true});
        jQuery('#Booking_bkg_return_date_time').timepicker({'defaultTime': false, 'autoclose': true});
        $rate = 0;
        $dist = '';
        $time = '';
        $('.glyphicon').addClass('fa').removeClass('glyphicon');
        $('.glyphicon-time').addClass('fa-clock-o').removeClass('glyphicon-time');
        $isLeadLoad =<?= ($model->lead_id != "") ? "true" : "false" ?>;
        if ($isLeadLoad) {
            $("#Booking_bkg_booking_type").click();
        }
        $isCopyLoad =<?= ($_REQUEST['booking_id'] > 0) ? "true" : "false" ?>;
        if ($isCopyLoad) {
            $("#Booking_bkg_booking_type").change();
        }
        $('#ytBooking_bkg_add_my_trip').parent().parent().css('float','left');
        $('#Booking_bkg_spl_req_lunch_break_time').parent().css('float','right');
<?
if ($model->lead_id > 0 && $model->bkg_vehicle_type_id > 0)
{
    ?>
            getAmountbyCitiesnVehicle();
<? } ?>

<?
if ($_REQUEST['booking_id'] > 0 && $model->bkg_vehicle_type_id > 0)
{
    ?>
            getDiscount();
            calculateAmount();
<? } ?>

        $(document).on('click', '#Booking_bkg_is_state_tax_included1', function () {
            if ($('#Booking_bkg_is_state_tax_included1').is(':checked')) {
                $('#BookingInvoice_bkg_is_state_tax_included').val(1);
                $('#BookingInvoice_bkg_state_tax').removeAttr('readOnly');
            } else {
                $('#BookingInvoice_bkg_is_state_tax_included').val(0);
                $('#BookingInvoice_bkg_state_tax').attr('readOnly', 'readOnly');
                $('#BookingInvoice_bkg_state_tax').val(0).change();
            }
        });
        $(document).on('click', '#Booking_bkg_is_toll_tax_included1', function () {
            if ($('#Booking_bkg_is_toll_tax_included1').is(':checked')) {
                $('#BookingInvoice_bkg_is_toll_tax_included').val(1);
                $('#BookingInvoice_bkg_toll_tax').removeAttr('readOnly');

            } else {
                $('#BookingInvoice_bkg_is_toll_tax_included').val(0);
                $('#BookingInvoice_bkg_toll_tax').attr('readOnly', 'readOnly');
                $('#BookingInvoice_bkg_toll_tax').val(0).change();
            }
        });



<?
if ($model->bkg_agent_id > 0)
{
    ?>
            var agent_id = '<?= $model->bkg_agent_id; ?>';
            onAgentSelected(agent_id);
<? } ?>

    });

    function validateBooking()
    {
        var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
        var primaryPhone = $('#BookingUser_bkg_contact_no').val();
        var email = $('#BookingUser_bkg_user_email').val();
        //var triptype = $('#Booking_bkg_trip_type').val();
        var ratepkm = $('#BookingInvoice_bkg_rate_per_km').val();
        var bkgtype = $('#Booking_bkg_booking_type').val();
        var select = $("#BookingUser_bkg_country_code").selectize({});
        var selectizeControl = select[0].selectize;
        var country_code = selectizeControl.getItem(selectizeControl.getValue()).text();
        error = 0;
        $("#errordivmob").text('');
        $("#errordivemail").text('');
        $("#errordivrate").text('');
        $("#errordivreturn").text('');
        $('#errordivemailcrp').text('');
        if (bkgtype == 2 && $('#Booking_bkg_return_date_date').val() == '') {
            error += 1;
            $("#errordivreturn").text('');
            $("#errordivreturn").text('Please enter Return Date and Time');
        }
        if ((primaryPhone == '' || primaryPhone == null) && (email == '' || email == null))
        {
            error += 1;
            $("#errordivmob").text('');
            $("#errordivemail").text('');
            $("#errordivmob").text('Please enter contact number or email address.');
        } else
        {
            if (primaryPhone != '')
            {
                if (country_code == '' || country_code == null)
                {
                    error += 1;
                    $("#errordivmob").text("Please select country code.");
                } else
                {
                    error += 0;
                    $("#errordivmob").text('');
                    $("#errordivemail").text('');
                }
            } else
            {
                if (email != '')
                {
                    if (!ck_email.test(email)) {
                        error += 1;
                        $("#errordivmob").text('');
                        $("#errordivemail").text('');
                        $("#errordivemail").text('Invalid email address');
                    }
                }
            }
        }


        if ($('#BookingInvoice_bkg_total_amount').val() <= 0 || $('#BookingInvoice_bkg_total_amount').val() == '' || $('#BookingInvoice_bkg_total_amount').val() == 'undefined')
        {
            error += 1;
            alert("Total chargeable amount is mandatory");
        }

        if ($('#Booking_bkg_vehicle_type_id').val() <= 0 || $('#Booking_bkg_vehicle_type_id').val() == '' || $('#Booking_bkg_vehicle_type_id').val() == 'undefined' || $('#Booking_bkg_vehicle_type_id').val() == null || $('#Booking_bkg_vehicle_type_id').val() == 'null')
        {
            error += 1;
            alert("Please select vehicle type.");
        }
        var trip_user = $("input[name=\'Booking[trip_user]\']:checked").val();
//        if (trip_user == 3 && ($('#corporate_id').val() == '' || $('#corporate_id').val() == null || $('#corporate_id').val() == 'undefined')) {
//            error += 1;
//            alert("Please select corporate.");
//        }

        if ((trip_user == 2) && ($('#bkg_agent_id').val() == '' || $('#bkg_agent_id').val() == null || $('#bkg_agent_id').val() == 'undefined')) {
            error += 1;
            alert("Link to Channel Partner.");
        }

        $('#mobcopybooking').html("");
        var val = $('#Booking_bkg_copybooking_phone').val();
        if (val != '' && val != null && val != "" && val != undefined) {
            if (/^\d{10}$/.test(val)) {
                // value is ok, use it
            } else {
                error += 1;
                $('#mobcopybooking').html("Number must be of 10 digit.");
                $('#Booking_bkg_copybooking_phone').focus();
            }
        }

        if ($('#uniform-Booking_bkg_copybooking_issms>span').hasClass('checked')) {
            $('input[name="Booking[bkg_copybooking_issms]"]').val(1).trigger('change');
        } else {
            $('input[name="Booking[bkg_copybooking_issms]"]').val(0).trigger('change');
        }

        if (error > 0)
        {
            return false;
        }

        return true;
    }


    $("#Booking_bkg_pickup_date_date").change(function () {
        getAmountbyCitiesnVehicle();
        getDiscount();
        $("#errordivpdate").text('');
    });
    $('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').change(function () {
        if ($('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').is(':checked'))
        {
            $("#othreq").show();
        } else {
            $("#othreq").hide();
        }
    });
//    $('#corporate_id').change(function () {
//        getAgentDetails($("#corporate_id").select2("val"));
//        getAgentBaseDiscFare();
//        calculateAmount();
//    });
    $('#bkg_agent_id').change(function () {
        onAgentSelected($("#bkg_agent_id").select2("val"));
        getAmountbyCitiesnVehicle();

    });

    function onAgentSelected(agtId)
    {
        getAgentDetails(agtId);
        getAgentBaseDiscFare();
        calculateAmount();
        $('#corp_addt_details').addClass('hide');
        if ($('#agt_type').val() == 1) {
            $('#corp_addt_details').removeClass('hide');
        }

        var trip_user = $("input[name=\'Booking[trip_user]\']:checked").val();
        if ((trip_user == 2) && $('#agt_type').val() != 1 && $('#bkg_agent_id').val() != '' && $('#bkg_agent_id').val() != null && $('#bkg_agent_id').val() != undefined && $('#bkg_agent_id').val() != '0' && $('#bkg_agent_id').val() != 0)
        {
            var totalAmount = parseInt(Math.round($('#BookingInvoice_bkg_total_amount').val()));
            $('#Booking_agentCreditAmount').val(totalAmount);
            $('#div_due_amount').removeClass('hide');
            $('#id_due_amount').html(0);
        }
    }

    $("#Booking_bkg_from_city_id").change(function () {
        getRoute();
    });
    $("#Booking_bkg_to_city_id").change(function () {
        getRoute();
    });
    $("#Booking_bkg_booking_type").change(function () {
        getAmountbyCitiesnVehicle();
    });
    $("#BookingInvoice_bkg_base_amount").change(function () {
        getDiscount();
        calculateAmount();
    });
    $("#BookingInvoice_bkg_additional_charge").change(function () {
        calculateAmount();
    });
    $("#BookingInvoice_bkg_discount_amount").change(function () {
        getDiscount();
        calculateAmount();
    });
//    $("#Booking_bkg_trip_type").change(function () {
//        var $triptype = $("#Booking_bkg_trip_type").val();
//        if ($triptype == 2) {
//            //   $("#div_rate_per_km").removeClass('hide');
//            getAmountbyCitiesnVehicle();
//        }
//        if ($triptype == 1) {
//            //   $("#div_rate_per_km").addClass('hide');
//            $("#Booking_bkg_rate_per_km").val('');
//        }
//    });
    $("#BookingAddInfo_bkg_info_source").change(function () {
        var infosource = $("#BookingAddInfo_bkg_info_source").val();
        extraAdditionalInfo(infosource);
    });
    function extraAdditionalInfo(infosource)
    {
        $("#source_desc_show").addClass('hide');
        if (infosource == 'Agent') {
            $("#BookingAddInfo_bkg_info_source_desc").val('');
            $("#source_desc_show").addClass('hide');
        } else {
            if (infosource == 'Friend') {
                $("#source_desc_show").removeClass('hide');
                $("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "Friend's email please");
            } else if (infosource == 'Other') {
                $("#source_desc_show").removeClass('hide');
                $("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "");
            }
        }
    }

    $("#Booking_bkg_vehicle_type_id").change(function () {
        getAmountbyCitiesnVehicle();
        getDiscount();
    });
    $("#BookingInvoice_bkg_promo1_code").change(function () {
        $("#BookingInvoice_bkg_discount_amount").val('');
        getDiscount();
    });
    $('#BookingInvoice_bkg_driver_allowance_amount').change(function () {
        calculateAmount();
    });
    $('#BookingInvoice_bkg_toll_tax').change(function () {
        calculateAmount();
    });
    $('#BookingInvoice_bkg_state_tax').change(function () {
        calculateAmount();
    });
    $("#Booking_bkg_booking_type").click(function () {

        var $bkgtype = $("#Booking_bkg_booking_type").val();
        $('#addmulticities').hide();
        $('#ctyinfo_bkg_type_1').hide();
        $('#address_div').hide();
        if ($bkgtype == '1') {
            $isLeadLoad = false;
            $("#Booking_bkg_return_date_date").val('');
            $("#Booking_bkg_return_date_time").val('');
            $("#Booking_bkg_route").removeAttr('disabled');
            $('#ctyinfo_bkg_type_1').show();
            $('#addmulticities').hide();
            // $('.multicitydetrow').remove();
            $('#tripTablecreate').hide();
            $('#pickup_div').show();
            $('#address_div').show();
            $("#multicityjsondata").val('');
            $('.multicitydetrow').remove();
        }
        if ($bkgtype == '2') {
            if ($isLeadLoad)
            {
                $isLeadLoad = false;
                return;
            }
            $('#pickup_div').hide();
            $('#Booking_bkg_route').attr('disabled', 'disabled');
            $href = '<?= Yii::app()->createUrl('rcsr/booking/multicityform', ['bookingType' => '']) ?>' + $bkgtype;
            jQuery.ajax({type: 'GET', url: $href,
                success: function (data) {
                    multicitybootbox = bootbox.dialog({
                        message: data,
                        size: 'large',
                        title: 'Add pickup info',
                        onEscape: function () {
                            $('#addmulticities').show();
                            multicitybootbox.hide();
                            multicitybootbox.remove();
                        },
                    });
                    multicitybootbox.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }});
        }



        if ($bkgtype == '3')
        {
            if ($isLeadLoad)
            {
                $isLeadLoad = false;
                return;
            }
            $('#pickup_div').hide();
            $('#Booking_bkg_route').attr('disabled', 'disabled');
            $href = '<?= Yii::app()->createUrl('rcsr/booking/multicityform', ['bookingType' => '']) ?>' + $bkgtype;
            jQuery.ajax({type: 'GET', url: $href, success: function (data) {

                    $('#multicityjsondata').val('');
                    multicitybootbox = bootbox.dialog({
                        message: data,
                        size: 'large',
                        title: 'Add pickup info',
                        onEscape: function () {
                            $('#addmulticities').show();
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
        // getRoute();
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
        getAmountbyCitiesnVehicle();
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

    function getDiscount() {
        pdate = $("#Booking_bkg_pickup_date_date").val();
        ptime = $('#<?= CHtml::activeId($model, "bkg_pickup_date_time") ?>').val();
        if (pdate == '' && ptime == '') {
            $("#errordivpdate").text('');
            $("#errordivpdate").text('Please enter Pickupdate/Time');
        }
        if (pdate != '' && ($("#BookingInvoice_bkg_promo1_code").val() != '' || $("#BookingInvoice_bkg_discount_amount").val() != '') && $("#BookingInvoice_bkg_base_amount").val() != '') {
            getDiscountbyCodenAmount($("#BookingInvoice_bkg_promo1_code").val(), $("#BookingInvoice_bkg_base_amount").val());
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
        promo.model = model;
        if (code != '' && amount > 0) {
            $(document).on("getPromoCode", function (event, data) {
                promoCode(data);
            });
            promo.getPromoCode();
        } else if ($("#BookingInvoice_bkg_discount_amount").val() != '' && $("#BookingInvoice_bkg_promo1_code").val() != '') {
            $("#BookingInvoice_bkg_discount_amount").val('');
            $("#BookingInvoice_bkg_total_amount").val($("#BookingInvoice_bkg_base_amount").val());
        } else if ($("#BookingInvoice_bkg_discount_amount").val() != '' && $("#BookingInvoice_bkg_base_amount").val() != '') {
            calculateAmount();
        }
    }

    function promoCode(data)
    {
        if (data.success) {
            $("#BookingInvoice_bkg_discount_amount").val('');
            if (data.data.discount > 0) {
                $("#BookingInvoice_bkg_discount_amount").val(data.data.discount);
            } else {
                if (data.data.promoCredits > 0) {
                    $('#promocreditsucc').html('Promo applied successfully.<br> User got Gozo Coins worth Rs.' + data.data.promoCredits + '.<br> He/She may redeem these Gozo Coins against his/her next bookings with us.');
                    //     $('#promocreditsucc').delay(15000).fadeOut();
                }
                $("#BookingInvoice_bkg_discount_amount").val(0);
            }
            calculateAmount();
        }
    }

    function getAmountbyCitiesnVehicle() {
        var csrbooking = new Csrbooking();
        var model = {};
        $userType = $("input[name='Booking[trip_user]']:checked").val();
        if ($userType == 2) {
            model.agentId = $('#bkg_agent_id').val();
        }
        model.fromCity = $("#Booking_bkg_from_city_id").val();
        model.toCity = $("#Booking_bkg_to_city_id").val();
        model.cabType = $("#Booking_bkg_vehicle_type_id").val();
        model.tripDistance = $('#Booking_bkg_trip_distance').val();
       // model.tripType = $("#Booking_bkg_trip_type").val();
        model.multiCityData = $('#multicityjsondata').val();
        model.bookingType = $('#Booking_bkg_booking_type').val();
        model.pickupAddress = $('#Booking_bkg_pickup_address').val();
        model.dropupAddress = $('#Booking_bkg_drop_address').val();
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

    function getQoutation(data)
    {
        $("#errorShow").hide();
        $("#errorMsg").html('');
        if (!data.data.quoteddata.success)
        {
            $("#errorShow").show();
            $("#errorMsg").html('Alert : ' + data.data.quoteddata.errorText);
//            alert('Sorry! Your request can not be processed right now!Please try later.' + data.data.quoteddata.errorText);
        }

        // alert(data.data.cartypeid);
        var qRouteRates = data.data.quoteddata.routeRates;
        var qRouteDistance = data.data.quoteddata.routeDistance;
        var qRouteDuration = data.data.quoteddata.routeDuration;
        $("#BookingInvoice_bkg_base_amount").val(qRouteRates.baseAmount);
		$("#BookingInvoice_bkg_base_amount").attr('readOnly', 'readOnly');
        $("#BookingInvoice_bkg_toll_tax").val(qRouteRates.tollTaxAmount | 0);
        $("#BookingInvoice_bkg_state_tax").val(qRouteRates.stateTax | 0);
        $("#BookingInvoice_bkg_rate_per_km_extra").val(qRouteRates.ratePerKM);
		$("#BookingInvoice_bkg_rate_per_km_extra").attr('readOnly', 'readOnly');
        $("#BookingInvoice_bkg_total_amount").val(qRouteRates.totalAmount);
        $('#BookingInvoice_bkg_gozo_base_amount').val(qRouteRates.baseAmount);
        $("#trip_rate").text('');
//            if (qRouteRates.costPerKM > 0) {
//                //  $("#trip_rate").text('Rate : Rs.' + data.est_booking_info['km_rate'] + ' per km');
//            }
        $('#BookingInvoice_bkg_service_tax').val(qRouteRates.gst);
        $('#BookingInvoice_bkg_driver_allowance_amount').val(qRouteRates.driverAllowance);
        $('#BookingInvoice_bkg_driver_allowance_amount').attr('oldamount', qRouteRates.driverAllowance);
		$('#BookingInvoice_bkg_driver_allowance_amount').attr('readOnly', 'readOnly');
		if (data.data.distArr != '') {
            var distArrVal = data.data.distArr;
            $.each(distArrVal, function (k, v) {
                $('#fdistcreate' + k).text(v['dist']);
                $('#fduracreate' + k).text(v['dura']);
            });
        }
        if (qRouteRates.isTollIncluded == 1)
        {
            $('.checkertolltax span').addClass('checked');
            $('#BookingInvoice_bkg_is_toll_tax_included').val(1);
            $('#Booking_bkg_is_toll_tax_included1').attr('checked', 'true');
            $('#Booking_bkg_is_toll_tax_included1').attr('disabled', 'disabled');
            $('#BookingInvoice_bkg_toll_tax').attr('readOnly', 'readOnly');
        } else
        {
            $('#BookingInvoice_bkg_is_toll_tax_included').val(0);
            $('.checkertolltax span').removeClass('checked');
            $('#Booking_bkg_is_toll_tax_included1').attr('checked', 'false');
            $('#Booking_bkg_is_toll_tax_included1').removeAttr('disabled');
        }
        if (qRouteRates.isStateTaxIncluded == 1)
        {
            $('#BookingInvoice_bkg_is_state_tax_included').val(1);
            $('.checkerstatetax span').addClass('checked');
            $('#Booking_bkg_is_state_tax_included1').attr('checked', 'true');
            $('#Booking_bkg_is_state_tax_included1').attr('disabled', 'disabled');
            $('#BookingInvoice_bkg_state_tax').attr('readOnly', 'readOnly');
        } else
        {
            $('#BookingInvoice_bkg_is_state_tax_included').val(0);
            $('.checkerstatetax span').removeClass('checked');
            $('#Booking_bkg_is_state_tax_included1').attr('checked', 'false');
            $('#Booking_bkg_is_state_tax_included1').removeAttr('disabled');
        }

        $('#BookingInvoice_bkg_rate_per_km').val(qRouteRates.costPerKM);
        $('#BookingInvoice_bkg_chargeable_distance').val(qRouteRates.quotedDistance);
        $('#rtevndamt').val(qRouteRates.vendorAmount);
        //$('#BookingTrack_bkg_garage_time').val(qRouteDuration.totalMinutes);
        $('#Booking_bkg_trip_distance').val(qRouteDistance.quotedDistance);
        $('#Booking_bkg_trip_duration').val(qRouteDuration.totalMinutes);
        $('#BookingInvoice_bkg_quoted_vendor_amount').val(qRouteRates.vendorAmount);
        if (qRouteRates.costPerKM > 0 && $('#Booking_bkg_trip_distance').val() > 0)
        {
            $('#vehicle_dist_ext').html("Note: Ext. Chrg. After " + $('#Booking_bkg_trip_distance').val() + " Kms. = " + qRouteRates.costPerKM + "/Km.");
        } else
        {
            $('#vehicle_dist_ext').html("");
        }
        getAgentBaseDiscFare();
        calculateAmount();
        var trip_user = $("input[name=\'Booking[trip_user]\']:checked").val();
        if ((trip_user == 2) && $('#bkg_agent_id').val() != '' && $('#bkg_agent_id').val() != null && $('#bkg_agent_id').val() != undefined && $('#bkg_agent_id').val() != '0' && $('#bkg_agent_id').val() != 0)
        {
            var totalAmount = parseInt(Math.round($('#BookingInvoice_bkg_total_amount').val()));
            $('#Booking_agentCreditAmount').val(totalAmount);
            $('#div_due_amount').removeClass('hide');
            $('#id_due_amount').html(0);
        }
        
        $("#Booking_routeProcessed").val('');
        if (data.data.processedRoute != '')
        {            
            $("#Booking_routeProcessed").val(data.data.processedRoute);           
        }

    }
//    }

//    function reverseCalculate() {
//        var amount = parseInt($('#Booking_bkg_total_amount').val());
//        //    var driver_allowance=0;
//        amount = isNaN(amount) ? 0 : amount;
//        var tax_rate = $('#Booking_bkg_service_tax_rate').val();
//        var netAmount = Math.round(amount / (1 + (tax_rate / 100)));
//        var sTax = amount - netAmount;
//        if ($('#Booking_bkg_driver_allowance_amount').val() != '' && $('#Booking_bkg_driver_allowance_amount').val() > 0)
//        {
    //            netAmount = netAmount - parseInt($('#Booking_bkg_driver_allowance_amount').val());
    //            //       driver_allowance=parseInt($('#Booking_bkg_driver_allowance_amount').val());
//        }
    //        var discount = parseInt($('#Booking_bkg_discount_amount').val());
    //        discount = isNaN(discount) ? 0 : discount;
//        var chargeAmount = netAmount + Math.round(discount);
    //        var additional = parseInt($('#Booking_bkg_additional_charge').val());
    //        additional = isNaN(additional) ? 0 : additional;
//        var gAmount = chargeAmount - Math.round(additional);
    //        var vendorAmount = Math.round(chargeAmount * 0.9);
    //        //    vendorAmount=vendorAmount+driver_allowance;
    //        $('#Booking_bkg_base_amount').val(gAmount);
    //        $('#Booking_bkg_service_tax').val(sTax);
//        $('#Booking_bkg_vendor_amount').val(vendorAmount);
//    }
    var previousAddToMyTrip=0;
    var addToMyTripFixedMin=30;
    var addToMyTripFixedAmount=150;
    function calculateAmount() {
        var gross_amount = Math.round($('#BookingInvoice_bkg_base_amount').val());
        var trip_user = $("input[name=\'Booking[trip_user]\']:checked").val();
        gross_amount = (gross_amount == '') ? 0 : parseInt(gross_amount);
        var additional = Math.round($('#BookingInvoice_bkg_additional_charge').val());
        var additional = (additional == '') ? 0 : parseInt(Math.round(additional-previousAddToMyTrip));
        var addToMyTripInMin=$('#BookingAddInfo_bkg_spl_req_lunch_break_time').val(),addToMyTrip;
        addToMyTrip=addToMyTripFixedAmount * (addToMyTripInMin/addToMyTripFixedMin);
        previousAddToMyTrip=addToMyTrip;
        var addToMyTripForVendor = addToMyTrip != '0' ? (addToMyTrip*60)/100 : '0';
        var rateVendorAmount = Math.round($('#rtevndamt').val());
        var vendor_amount = Math.round(rateVendorAmount + (addToMyTripInMin != '0' ? addToMyTripForVendor : 0) + additional);
        additional = Math.round(additional + addToMyTrip);
        var discount_amount = Math.round($('#BookingInvoice_bkg_discount_amount').val());
        var driver_allowance = 0;
        var gozo_base_amount = Math.round($('#BookingInvoice_bkg_gozo_base_amount').val());
        gross_amount = Math.round(gross_amount + additional);
        discount_amount = (discount_amount == '') ? 0 : parseInt(discount_amount);
        gross_amount = gross_amount - discount_amount;
        if ($('#BookingInvoice_bkg_driver_allowance_amount').val() != '' && $('#BookingInvoice_bkg_driver_allowance_amount').val() > 0)
        {
            // gross_amount = gross_amount + parseInt($('#Booking_bkg_driver_allowance_amount').val());
            driver_allowance = parseInt($('#BookingInvoice_bkg_driver_allowance_amount').val());
        }
        var conFee1 = gross_amount * 0.05;
        var conFee2 = 249;
        //  var conFee1 = gross_amount * 0.10;
        //   var conFee2 = 499;
        var conFee = 0;
        if (conFee1 > conFee2) {
            conFee = conFee2;
        } else {
            conFee = conFee1;
        }
//        if ($('#corporate_id').val() != '' && $('#corporate_id').val() != null && $('#corporate_id').val() != undefined && $('#corporate_id').val() != '0' && $('#corporate_id').val() != 0)
//        {
//            conFee = 0;
//            $('#agtnotification').removeClass('hide');
//        }

        if ((trip_user == 2) && ($('#bkg_agent_id').val() != '' && $('#bkg_agent_id').val() != null && $('#bkg_agent_id').val() != undefined && $('#bkg_agent_id').val() != '0' && $('#bkg_agent_id').val() != 0)) {
            if ($('#agt_type').val() == 1) {
                conFee = 0;
                $('#agtnotification').removeClass('hide');
            } else {
                conFee = 0;
                $('#divpaidby').removeClass('hide');
                $('#agtnotification').removeClass('hide');
                showAgentCreditDiv();
            }
        }

        //    conFee=0 //set Convenience charge zero;
        var convenience_charge = Math.round(conFee);
        var service_tax_rate = ($('#BookingInvoice_bkg_service_tax_rate').val() == '') ? 0 : $('#BookingInvoice_bkg_service_tax_rate').val();
        var service_tax_amount = 0;
        if (service_tax_rate != 0)
        {
            service_tax_amount = Math.round((gross_amount * parseFloat(service_tax_rate) / 100));
        }

        var tollTaxVal = ($('#BookingInvoice_bkg_toll_tax').val() == '') ? 0 : parseInt($('#BookingInvoice_bkg_toll_tax').val());
        var stateTaxVal = ($('#BookingInvoice_bkg_state_tax').val() == '') ? 0 : parseInt($('#BookingInvoice_bkg_state_tax').val());
        var amountwithoutconvenienc = gross_amount + service_tax_amount + tollTaxVal + stateTaxVal + driver_allowance;
        $('#amountwithoutcod').val(amountwithoutconvenienc);
        gross_amount = gross_amount + convenience_charge;
        $('#BookingInvoice_bkg_convenience_charge').val(convenience_charge);
        service_tax_amount = 0;
        if (service_tax_rate != 0)
        {
            service_tax_amount = Math.round((gross_amount * parseFloat(service_tax_rate) / 100));
        }
        var net_amount = gross_amount + service_tax_amount;
        var net_amount = net_amount + tollTaxVal + stateTaxVal + driver_allowance;
        $('#BookingInvoice_bkg_additional_charge').val(additional);
        addToMyTripInMin!='0' ? $('#BookingInvoice_bkg_additional_charge_remark').val("Customer will pay " + addToMyTripInMin + ' minutes Journey Break'):$('#BookingInvoice_bkg_additional_charge_remark').val('');
        $('#BookingInvoice_bkg_total_amount').val(net_amount);
        $('#BookingInvoice_bkg_vendor_amount').val(vendor_amount);
		$('#BookingInvoice_bkg_vendor_amount').hide();
        $('#BookingInvoice_bkg_service_tax').val(service_tax_amount);
        if ((trip_user == 2) && $('#agt_type').val() != 1 && $('#bkg_agent_id').val() != '' && $('#bkg_agent_id').val() != null && $('#bkg_agent_id').val() != undefined && $('#bkg_agent_id').val() != '0' && $('#bkg_agent_id').val() != 0)
        {
            $('#Booking_agentCreditAmount').attr('max', net_amount);
            var corpCredit = Math.round($('#Booking_agentCreditAmount').val());
            corpCredit = (corpCredit == '') ? 0 : parseInt(corpCredit);
            var due_amt = parseInt(net_amount) - corpCredit;
            $('#id_due_amount').html(due_amt);
        } else {
            $('#Booking_agentCreditAmount').val('');
            $('#div_due_amount').addClass('hide');
            $('#id_due_amount').html(net_amount);
        }

    }

    $('#Booking_agentCreditAmount').change(function () {
        calculateAmount();
    });
    function calculatefare() {
        getDiscount();
        calculateAmount();
    }

    function getAmount() {
        if ($("#Booking_bkg_route_id").val() != '' && $("#Booking_bkg_vehicle_type_id").val() != '')
        {
            model.routeId = $("#Booking_bkg_route_id").val();
            model.vehicleId = $("#Booking_bkg_vehicle_type_id").val();
            $(document).on("amount", function (event, data) {
                getCalculateAmount(data);
            });
            booking.amount();
        }
    }
    ;
    function getCalculateAmount(data) {
        $("#BookingInvoice_bkg_total_amount").val('0');
        $("#BookingInvoice_bkg_base_amount").val('0');
        alert(data.data.routeRate);
        if (data.data.routeRate) {
            $("#BookingInvoice_bkg_base_amount").val(data.data.routeRate).change();
            getDiscount();
            calculateAmount();
        }
    }

    $('#Booking_bkg_pickup_date_date').datepicker({
        format: 'dd/mm/yyyy'
    });
    $('#Booking_bkg_return_date_date').datepicker({
        format: 'dd/mm/yyyy'
    });
    function getApplicableDistance(dist) {
        distkm = parseInt(dist) + 15;
        distkm = (Math.ceil(distkm / 10)) * 10;
        return distkm;
    }

    $('#addCity').unbind("click").bind("click", function () {
        $href = '<?= Yii::app()->createUrl('rcsr/city/create') ?>';
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: 'Add City',
                    onEscape: function () {
                        box.hide();
                        box.remove();
                    },
                });
            }});
    });
    refreshCity = function () {
        box.hide();
        box.remove();
        $href = "<?= Yii::app()->createUrl('rcsr/city/json') ?>";
        jQuery.ajax({type: 'GET', dataType: 'json', url: $href, async: false,
            success: function (data1) {
                $('#<?= CHtml::activeId($model, "bkg_from_city_id") ?>').select2({data: data1, multiple: false});
                $('#<?= CHtml::activeId($model, "bkg_to_city_id") ?>').select2({data: data1, multiple: false});
            },
            error: function (e) {
                //alert(e);
            }
        });
    };
    $('#addmulticities').click(function () {
        var $bkgtype = $("#Booking_bkg_booking_type").val();
        $('#addmulticities').hide();
        $('#ctyinfo_bkg_type_1').hide();
        if ($bkgtype == '2') {

            $('#address_div').hide();
            $('#Booking_bkg_route').attr('disabled', 'disabled');
            $href = '<?= Yii::app()->createUrl('rcsr/booking/multicityform', ['bookingType' => '']) ?>' + $bkgtype;
            jQuery.ajax({type: 'GET', url: $href,
                success: function (data) {

                    multicitybootbox = bootbox.dialog({
                        message: data,
                        size: 'large',
                        title: 'Add pickup info',
                        onEscape: function () {
                            $('#addmulticities').show();
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

        if ($bkgtype == '3')
        {

            $('#Booking_bkg_route').attr('disabled', 'disabled');
            $('#address_div').hide();
            $href = '<?= Yii::app()->createUrl('rcsr/booking/multicityform', ['bookingType' => '']) ?>' + $bkgtype;
            jQuery.ajax({type: 'GET', url: $href,
                success: function (data) {

                    multicitybootbox = bootbox.dialog({
                        message: data,
                        size: 'large',
                        title: 'Add pickup info',
                        onEscape: function () {
                            $('#addmulticities').show();
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
    });
    function loadScript() {
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?';
        document.body.appendChild(script);
    }
    window.onload = loadScript;
    function updateMulticity(data, tot)
    {
        var data = $.parseJSON(data);
        $('#tripTablecreate').show();
        $('#insertTripRowcreate').html('');
        $('.multicitydetrow').remove();
        $('#address_div').hide();
        $('#Booking_bkg_pickup_date_date').val(data[0].pickup_date);
        $('#Booking_bkg_pickup_date_time').val(data[0].pickup_time);
        $('#Booking_bkg_pickup_address').val(data[0].pickup_address);
        $('#Booking_bkg_drop_address').val(data[tot].drop_address);
        //$('#Booking_bkg_pickup_pincode').val(data[0].pickup_pin);
        //$('#Booking_bkg_drop_pincode').val(data[tot].drop_pin);
//        $("#Booking_bkg_from_city_id").select2("val", data[0].pickup_city);
//        $("#Booking_bkg_to_city_id").select2("val", data[tot].drop_city);
        $("#Booking_bkg_from_city_id").val(data[0].pickup_city);
        $("#Booking_bkg_to_city_id").val(data[tot].drop_city);
        $("#multicityjsondata").val(JSON.stringify(data));
        $("#ctyinfo_bkg_type_1").hide();
        $('#show_return_date_time').html("");
        if ($('#Booking_bkg_booking_type').val() == 2)
        {
            $('#Booking_bkg_return_date_time').val(data[tot].return_time);
            $('#Booking_bkg_return_date_date').val(data[tot].return_date);
            //$('#show_return_date_time').html("<b>Return Date:<b> " + data[tot].return_date + " " + data[tot].return_time);
        }

        var total_distance = 0;
        var total_duration = 0;
        for (var i = 1; i <= tot + 1; i++) {
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
            $('#fcitycreate' + i).html('<b>' + data[(i - 1)].pickup_city_name + '</b><br> ' + data[(i - 1)].pickup_address + " ,pin: " + data[(i - 1)].pickup_pin);
            $('#tcitycreate' + i).html('<b>' + data[(i - 1)].drop_city_name + '</b><br> ' + data[(i - 1)].drop_address + " ,pin: " + data[(i - 1)].drop_pin);
            $('#fdatecreate' + i).text(data[(i - 1)].pickup_date + " " + data[(i - 1)].pickup_time);
            $('#distancecreate' + i).text(data[(i - 1)].distance);
            $('#durationcreate' + i).text(data[(i - 1)].duration);
        }
        $('#Booking_bkg_trip_distance').val(total_distance);
        $('#Booking_bkg_trip_duration').val(total_duration);
        getAmountbyCitiesnVehicle();
    }

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

    $('#<?= CHtml::activeId($model->bkgAddInfo, 'bkg_flight_no') ?>').mask('XXXX-XXXXXX', {
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
    function showlinkedUser() {
        var phone = $('#BookingUser_bkg_contact_no').val();
        var email = $('#BookingUser_bkg_user_email').val();
		var code  = $('#BookingUser_bkg_country_code').val();
        if ((phone != '' && phone != null && phone != undefined) || (email != '' && email != null && email != undefined)) {
            var href1 = '<?= Yii::app()->createUrl("rcsr/user/linkedusers"); ?>';
            $.ajax({url: href1,
                dataType: "json",
                // async: false,
                data: {"phone": phone, "email": email, "code": code},
                "success": function (data) {
                    if (data.success) {
                        var users = data.users;
                        var html = '';
                        $.each(users, function (key, value) {
                            html = html + '\
                                    <div class="p5" style="font-size: 1.1em">\n\
                            <a href="#" class="ml5" onclick="showUserDet(\'' + value["id"] + '\')">' + value['email'] + '</a><span id="spnLinkUser' + key + '" class="linkuserbtn bg-warning m5" phone="' + value['phone'] + '" email="' + value['email'] + '" fname="' + value['fname'] + '" lname="' + value['lname'] + '" onclick="linkUser(this,\'' + value["id"] + '\')"><i class="fa fa-check"></i></span><br>\n\
</div>';
                        });
                        $('#linkedusers').html('<div class="panel panel-primary panel-border compact"><div class="panel-heading" style="min-height:0">Existing Users (tick to link): </div><div class="panel-body">' + html + '</div></div>');
                        var userCount = data.userCount;
                        if (userCount > 0) {
                            $("#spnLinkUser0").click();
                        }
                    } else {
                        if(data.error == "[]")
                        {
							 $('#linkedusers').html('');
						}
                        else
                        {
                            var errors = JSON.parse(data.error);
                            $.each(errors, function(k,v){
                                alert(v);
                            });
                        }
                    }
                }
            });
        } else {
            $('#linkedusers').html('');
        }

    }
    function showUserDet(user) {
        if (user > 0)
        {
            jQuery.ajax({type: 'GET',
                url: '<?= Yii::app()->createUrl('rcsr/user/details') ?>',
                dataType: 'html',
                data: {"user": user},
                success: function (data)
                {
                    showuser = bootbox.dialog({
                        message: data,
                        title: 'User Details',
                        size: 'large', onEscape: function () {
                        }
                    });
                    showuser.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                    return true;
                },
                error: function (x) {
                    alert(x);
                }
            });
        }
    }

    function linkUser(obj, userId) {
        if ($(obj).hasClass('bg-warning'))
        {
            $('.linkuserbtn').removeClass('bg-success');
            $('.linkuserbtn').addClass('bg-warning');
            $('#BookingUser_bkg_user_id').val(userId);
            $(obj).removeClass('bg-warning');
            $(obj).addClass('bg-success');
            var chngEmail = $(obj).attr('email');
            var chngPhone = $(obj).attr('phone');
            var chngFname = $(obj).attr('fname');
            var chngLname = $(obj).attr('lname');
            var phone = $('#BookingUser_bkg_contact_no').val();
            var email = $('#BookingUser_bkg_user_email').val();
            if (chngEmail != '' && chngEmail != null && chngEmail != undefined && email == "") {
                $('#BookingUser_bkg_user_email').val(chngEmail);
            }
            if (chngFname != '' && chngFname != null && chngFname != undefined) {
                $('#BookingUser_bkg_user_fname').val(chngFname);
            }
            if (chngLname != '' && chngLname != null && chngLname != undefined) {
                $('#BookingUser_bkg_user_lname').val(chngLname);
            }
            if (chngPhone != '' && chngPhone != null && chngPhone != undefined && chngPhone != "null" && phone == "") {
                $('#BookingUser_bkg_contact_no').val(chngPhone);
            }
        } else
        {
            $('#BookingUser_bkg_user_id').val('');
            $(obj).removeClass('bg-success');
            $(obj).addClass('bg-warning');
        }
    }

    


    function showAgentCreditDiv()
    {
        var agentPaymentBy = $("input[name=\'Booking[agentBkgAmountPay]\']:checked").val();
        if (agentPaymentBy == 1) {
            $('#divAgentCredit').addClass('hide');
            $('#div_due_amount').addClass('hide');
            $('#Booking_agentCreditAmount').val("");
        }
        if (agentPaymentBy == 2) {
            $('#divAgentCredit').removeClass('hide');
            $('#div_due_amount').removeClass('hide');
        }
    }

    function getAgentBaseDiscFare() {
        var base_fare = Math.round($('#BookingInvoice_bkg_gozo_base_amount').val());
        var trip_user = $("input[name=\'Booking[trip_user]\']:checked").val();
        var agt_type = $("#agt_type").val();
        var agt_commisssion_value = $('#agt_commission_value').val();
        var agt_commission = $('#agt_commission').val();
        if (base_fare != '' && base_fare != null && base_fare != undefined && base_fare != 0 && base_fare != '0') {
            if (
                    (agt_commisssion_value != '' && agt_commisssion_value != null && agt_commisssion_value != undefined && agt_commisssion_value != "null") &&
                    (agt_commission != '' && agt_commission != null && agt_commission != undefined && agt_commission != "null") &&
                    (trip_user == 2 && agt_type != 2 && agt_type != '' && ($('#bkg_agent_id').val() != '' && $('#bkg_agent_id').val() != null && $('#bkg_agent_id').val() != undefined && $('#bkg_agent_id').val() != '0' && $('#bkg_agent_id').val() != 0))
                    )

            {
                agt_commisssion_value = parseInt(Math.round(agt_commisssion_value));
                var totalAmount = Math.round($('#BookingInvoice_bkg_total_amount').val());
                totalAmount = (totalAmount == '') ? 0 : parseInt(totalAmount);
                var vendorAmount = Math.round($('#BookingInvoice_bkg_vendor_amount').val());
                vendorAmount = (vendorAmount == '') ? 0 : parseInt(vendorAmount);
                var gozo_amount = totalAmount - vendorAmount;
                if (agt_commisssion_value == 1) {
                    var agentMarkup = Math.round(base_fare * (agt_commission / 100));
                } else {
                    var agentMarkup = agt_commission;
                }
                if (agentMarkup > gozo_amount) {
                    base_fare = base_fare - gozo_amount;
                } else {
                    base_fare = base_fare - Math.round(agentMarkup);
                }
                $('#BookingInvoice_bkg_base_amount').val(base_fare);
            } else {
                $('#BookingInvoice_bkg_base_amount').val(base_fare);
            }
        }
    }
    function getAgentDetails(agtId) {

        if (agtId != '' && agtId != null) {
            jQuery.ajax({type: 'GET',
                url: '<?= Yii::app()->createUrl('rcsr/agent/agentsbytype') ?>',
                dataType: 'json',
                data: {"agt_id": agtId},
                async: false,
                success: function (data)
                {
                    if (data.type == 2) {
                        $('#agent_notify_option').removeClass('hide');
                        $('#agt_type').val(data.notifyDetails.agt_type);
                        $('#Booking_bkg_copybooking_name').val(data.notifyDetails.agt_copybooking_name);
                        $('#Booking_bkg_copybooking_email').val(data.notifyDetails.agt_copybooking_email);
                        $('#Booking_bkg_copybooking_phone').val(data.notifyDetails.agt_copybooking_phone);
                        $('#agt_commission_value').val(data.notifyDetails.agt_commission_value);
                        $('#agt_commission').val(data.notifyDetails.agt_commission);
                        var $select = $("#Booking_bkg_copybooking_country").selectize();
                        var selectize = $select[0].selectize;
                        selectize.setValue(data.notifyDetails.agt_phone_country_code);
                    }
                    $('#booking_ref_code_div').removeClass('hide');
                },
                error: function (x) {
                    alert(x);
                }
            });
        }
    }

    function shownotifyopt() {
        var agent_id = $("#bkg_agent_id").select2("val");
//        if ($('#bkg_agent_id').val() == '' || $('#bkg_agent_id').val() == null || $('#bkg_agent_id').val() == 'undefined') {
//            var agent_id = $("#corporate_id").select2("val");
//        }
        var agentnotifydata = $('#agentnotifydata').val();
        jQuery.ajax({type: 'POST',
            url: '<?= Yii::app()->createUrl('rcsr/agent/bookingmsgdefaults') ?>',
            dataType: 'html',
            data: {"agent_id": agent_id, "notifydata": agentnotifydata, "YII_CSRF_TOKEN": $('input[name="YII_CSRF_TOKEN"]').val()},
            success: function (data)
            {
                shownotifydiag = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function () {
                    }
                });
                shownotifydiag.on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
                return true;
            },
            error: function (x) {
                alert(x);
            }
        });
    }

    function savenotifyoptions()
    {
        jQuery.ajax({type: 'POST',
            url: '<?= Yii::app()->createUrl('rcsr/agent/bookingmsgdefaults') ?>',
            dataType: 'json',
            data: $('#agent-notification-form').serialize(),
            success: function (data)
            {
                $('#agentnotifydata').val(JSON.stringify(data.data));
                bootbox.hideAll();
                alert('Notification details saved successfully.');
                return false;
            },
            error: function (x) {
                alert(x);
            }
        });
        return false;
    }
    function populateSource(obj, cityId) {

        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1])) ?>',
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
    $('#Booking_bkg_from_city_id1').change(function () {
        $('#Booking_bkg_from_city_id').val($('#Booking_bkg_from_city_id1').val()).change();
    });
    $('#Booking_bkg_to_city_id1').change(function () {
        $('#Booking_bkg_to_city_id').val($('#Booking_bkg_to_city_id1').val()).change();
    });
    $('#Booking_preData').change(function () {
        alert('predata');
    });
    
    $("#BookingAddInfo_bkg_spl_req_lunch_break_time").change(function(){
    var brkType=$('#Booking_bkg_booking_type').val();
    var source=$('#Booking_bkg_from_city_id1').val();
    var destination=$('#Booking_bkg_to_city_id1').val();
    var vehicle=$('#Booking_bkg_vehicle_type_id').val();
    if(brkType=='1' && source!='' && destination!='' && vehicle!=''){
        calculateAmount();
    }
    else{
        $("#BookingAddInfo_bkg_spl_req_lunch_break_time").val('0');
    }
    
        
    });
    
</script>

<input id="map_canvas" type="hidden">
