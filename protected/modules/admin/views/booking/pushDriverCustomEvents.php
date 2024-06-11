<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/booking.js?v=' . $version);

if (Yii::app()->request->isAjaxRequest)
{
    $cls = "";
}
else
{
    $cls = "col-lg-6 col-md-8 col-sm-10 col-sm-12 pb10";
}
$bookingType         = Booking::model()->booking_type;  
$locked              = ' <i class="fa fa-lock"></i>';
$amtToCollected = $model->bkgInvoice->bkg_total_amount - $model->bkgInvoice->bkg_advance_amount;
?>
<style type="text/css">
    @media (min-width: 300px){
        .modal-lg {
            width: 50%!important;
        }
    }
    @media (min-width: 300px){
        .modal-lg {
            width: 60%;
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

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin:0;
    }
    .selectize-input {
        min-width: 0px !important; 
        width: 30% !important; 
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
    <div class="col-xs-10 text-center h4 mt0">
        <b>Driver Custom Event Trigger</b>
    </div>
</div>

<div class="">
    <?php
    $form                = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                     => 'custom-push-form', 'enableClientValidation' => true,
        'clientOptions'          => array(
            'validateOnSubmit'   => true,
            'errorCssClass'      => 'has-error',
            
        ),
        'enableAjaxValidation'   => false,
        'errorMessageCssClass'   => 'help-block',
        'htmlOptions'            => array(
            'class' => 'form-horizontal'
        ),
    ));
    ?>
    <div class="row">
        <div class="col-xs-12">
            <?php
            echo $form->errorSummary($model);
            echo CHtml::errorSummary($model)
            ?>
            <?= $form->hiddenField($model, 'bkg_id', array('readonly' => true)) ?>
            <?= $form->hiddenField($model, 'bkg_booking_id', array('readonly' => true)) ?>
        </div>

        <div class="col-xs-12">
            <div class="panel panel-default panel-border">
                <div class="col-xs-12 alert alert-block alert-danger" href="er" id = "diverr" style="display: none"></div>
                <h3 class="pl15 msg"></h3>

                <div class="panel-body pt0">
                    <div class="row">
                        <?
                        $showdiv1            = ($model->bkg_booking_type != '') ? $model->bkg_booking_type : 1;
                        $leftForPickupData   = BookingTrackLog::model()->getByBookingId($model->bkg_id, BookingTrack::GOING_FOR_PICKUP);
                        $leftForPickupCheck  = ($leftForPickupData != NULL) ? 'checked' : '';
                        $leftForPickupEnabled    = ($leftForPickupData != NULL) ? 'disabled' : '';
                        $driverArrivedData   = BookingTrackLog::model()->getByBookingId($model->bkg_id, BookingTrack::DRIVER_ARRIVED);
                        $driverArrivedCheck  = ($driverArrivedData != NULL) ? 'checked' : '';
                        $driverArrivedEnabled    = ($driverArrivedData != NULL) ? 'disabled' : '';
                        $tripStart           = BookingTrackLog::model()->getByBookingId($model->bkg_id, BookingTrack::TRIP_START);
                        $tripStartCheck      = ($tripStart != NULL) ? 'checked' : '';
                        $tripStartEnabled    = ($tripStart != NULL) ? 'disabled' : '';
                        $tripEnd             = BookingTrackLog::model()->getByBookingId($model->bkg_id, BookingTrack::TRIP_STOP);
                        $tripEndCheck        = ($tripEnd != NULL) ? 'checked' : '';
                        $tripEndEnabled  = ($tripEnd != NULL) ? 'disabled' : '';
                        ?>
                        <div class=" col-md-12">
                            <div class="row">                           
                                <div class="col-xs-3 col-md-3 p10">
                                    <?=
                                    //$form->checkboxGroup($model, 'bkg_left_For_Pickup', array('label' => '', 'widgetOptions' => array('data' => array(BookingTrack::GOING_FOR_PICKUP => 'left for pickup'), 'htmlOptions' => ['disabled' => $leftForPickupCheck]), 'inline' => true));
                                    $form->checkboxGroup($model, 'bkg_left_For_Pickup', array('label' => 'Left for pickup','widgetOptions' => array('htmlOptions' => ['checked' => $leftForPickupCheck, 'disabled' => $leftForPickupEnabled])));
                                    ?>
                                </div>

                                <div class="col-xs-3 col-md-3 p10">
                                    <?=
                                    //$form->checkboxGroup($model, 'bkg_arrived', array('label' => '', 'widgetOptions' => array('data' => array(BookingTrack::DRIVER_ARRIVED => 'driver arrived'), 'htmlOptions' => ['disabled' => $driverArrivedCheck]), 'inline' => true));
                                    $form->checkboxGroup($model, 'bkg_arrived', array('label' => 'Arrived','widgetOptions' => array('htmlOptions' => ['checked' => $driverArrivedCheck , 'disabled' => $driverArrivedEnabled])));
                                    ?>
                                </div>

                                <div class="col-xs-3 col-md-3 p10">
                                    <?=
                                    //$form->checkboxGroup($model, 'bkg_trip_start', array('label' => '', 'widgetOptions' => array('data' => array(BookingTrack::TRIP_START => 'trip start'), 'htmlOptions' => ['checked' => $tripStartCheck]), 'inline' => true));
                                    $form->checkboxGroup($model, 'bkg_trip_start', array('label' => 'Trip start','widgetOptions' => array('htmlOptions' => ['checked' => $tripStartCheck , 'disabled' => $tripStartEnabled])));
                                   ?>
                                </div>

                                <div class="col-xs-3 col-md-3 p10">
                                    <?=
                                    //$form->checkboxGroup($model, 'bkg_trip_end', array('label' => '', 'widgetOptions' => array('data' => array(BookingTrack::TRIP_START => 'trip start'), 'htmlOptions' => ['checked' => $tripStartCheck]), 'inline' => true));
                                    $form->checkboxGroup($model, 'bkg_trip_end', array('label' => 'Trip End','widgetOptions' => array('htmlOptions' => ['checked' => $tripEndCheck , 'disabled' => $tripEndEnabled])));
                                    ?>
                                </div>

                            </div> 
                        </div>

                        <div class="row" id="pickup_div">

                            <div class="col-sm-5">
                                <? $strpickdate      = ($model->bkg_pickup_date == '') ? date('Y-m-d H:i:s', strtotime('+4 hour')) : $model->bkg_pickup_date; ?>
                                <?=
                                $form->datePickerGroup($model, 'bkg_pickup_date_date', array('label'             => 'Date',
                                    'widgetOptions'  => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Pickup Date', 'value' => DateTimeFormat::DateTimeToDatePicker($strpickdate), 'class' => 'input-group border-gray full-width')), 'prepend'       => '<i class="fa fa-calendar"></i>'));
                                ?>
                            </div>

                            <div class="col-sm-5">
                                <?
                                echo $form->timePickerGroup($model, 'bkg_pickup_date_time', array('label'            => 'Time',
                                    'widgetOptions'  => array('id' => CHtml::activeId($model, "bkg_pickup_date_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Pickup Time', 'value' => date('h:i A', strtotime($strpickdate)), 'class' => 'input-group border-gray full-width'))));
                                ?>
                                <?php
                                echo $form->hiddenField($model, 'bkg_agent_id', ['value' => $model->bkg_agent_id]);
                                ?>
                            </div>

                            <div class="col-sm-5 lat"> 
                                <?= $form->textFieldGroup($model, 'bkg_pickup_lat', array('label' => 'Latitude','widgetOptions' => array('htmlOptions' => []))) ?>
                            </div>

                            <div class="col-sm-5 long"> 
                                <?= $form->textFieldGroup($model, 'bkg_pickup_long', array('label' => 'Longitude','widgetOptions' => array('htmlOptions' => []))) ?>
                            </div>

                            <div class="col-sm-5 tripStartProperty" > 
                                <?//= $form->textFieldGroup($model, 'bkg_start_odometer', array('label' => 'Start Odometer','widgetOptions' => array('htmlOptions' => []))) ?>
                                <?= $form->textFieldGroup($model, 'bkg_start_odometer', array('label' => "Start Odometer", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => '')))) ?>
                            </div>

                            <div class="col-sm-5 tripStartProperty" > 
                                <?//= $form->textFieldGroup($model, 'bkg_trip_otp', array('label' => 'Trip OTP','widgetOptions' => array('htmlOptions' => ['onchange'=>'getTripOtpVerified()']))) ?>
                                <?= $form->textFieldGroup($model, 'bkg_trip_otp', array('label' => "Trip OTP", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => ''), array()))) ?>
                            </div>

                            <div class="tripEndProperty" style="display:none" >

                            <div class="col-sm-5 " > 
                                <?= $form->textFieldGroup($model, 'bkg_dropup_lat', array('label' => 'Latitude','widgetOptions' => array('htmlOptions' => array('value'=> $model->bkg_dropup_lat)))) ?>
                            </div>

                            <div class="col-sm-5 " > 
                                <?= $form->textFieldGroup($model, 'bkg_dropup_long', array('label' => 'Latitude','widgetOptions' => array('htmlOptions' => array('value'=> $model->bkg_dropup_long)))) ?>
                            </div>

                            <div class="col-sm-5 " > 
                                <?= $form->textFieldGroup($model, 'bkg_vendor_collected', array('label' => 'Driver Collected', 'widgetOptions' => array('htmlOptions' => array('value'=> $amtToCollected)))) ?>
                            </div>

                            <div class="col-sm-5 " > 
                                <?//= $form->textFieldGroup($model, 'bkg_end_odometer', array('label' => 'End Odometer','widgetOptions' => array('htmlOptions' => []))) ?>
                                <?= $form->textFieldGroup($model, 'bkg_end_odometer', array('label' => "End Odometer", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => '')))) ?>
                            </div>

                            <div class="col-sm-5 " > 
                                <?//= $form->textFieldGroup($model, 'bkg_extra_km', array('label' => 'Extra Km','widgetOptions' => array('htmlOptions' => []))) ?>
                                <?= $form->textFieldGroup($model, 'bkg_extra_km', array('label' => "Extra Km", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => '')))) ?>
                            </div>

                            <div class="col-sm-5 " >
                                <?//= $form->textFieldGroup($model, 'bkg_extra_km_charge', array('label' => 'Extra Km charge', 'widgetOptions' => array('htmlOptions' => []))) ?>
                                <?= $form->textFieldGroup($model, 'bkg_extra_km_charge', array('label' => "Extra Km charge", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => '')))) ?>
                            </div>

                            <div class="col-sm-5 " > 
                                <?//= $form->textFieldGroup($model, 'bkg_extra_toll_tax', array('label' => 'Extra Toll Tax', 'widgetOptions' => array('htmlOptions' => []))) ?>
                                <?= $form->textFieldGroup($model, 'bkg_extra_toll_tax', array('label' => "Extra Toll Tax", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => '')))) ?>
                            </div>

                            
                            <div class="col-sm-5 " >
                                <?//= $form->textFieldGroup($model, 'bkg_extra_state_tax', array('label' => 'Extra State Tax', 'widgetOptions' => array('htmlOptions' => []))) ?>
                                <?= $form->textFieldGroup($model, 'bkg_extra_state_tax', array('label' => "Extra State Tax", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => '')))) ?>
                            </div>

                            <div class="col-sm-5 " > 
                                <?//= $form->textFieldGroup($model, 'bkg_extra_min', array('label' => 'Extra Min', 'widgetOptions' => array('htmlOptions' => []))) ?>
                                <?= $form->textFieldGroup($model, 'bkg_extra_min', array('label' => "Extra Min", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => '')))) ?>
                            </div>
                            
                            <div class="col-sm-5 " >
                                <?//= $form->textFieldGroup($model, 'bkg_extra_total_min_charge', array('label' => 'Extra Min Charge', 'widgetOptions' => array('htmlOptions' => []))) ?>
                                <?= $form->textFieldGroup($model, 'bkg_extra_total_min_charge', array('label' => "Extra Min Charge", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => '')))) ?>
                            </div>
</div>
                            <div id="errordivpdate" class="ml15 mt10" style="color:#da4455"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 text-center pb10">
                <? //= CHtml::submitButton('Submit', array('style' => 'font-size:1.4em', 'class' => 'btn btn-primary btn-lg pl50 pr50', 'id' => 'ebtnsbmt')); ?>
           <input type="button" class = "btn btn-primary btn-lg pl50 pr50" name="ebtnsbmt" id="ebtnsbmt" value="submit" >

 </div>

        </div>
    </div>
    <div id="driver1"></div>
    <?php $this->endWidget(); ?>
    <?php echo CHtml::endForm(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function ()
    {
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
        $(document).on('hidden.bs.modal', function (e)
        {
            $('body').addClass('modal-open');
        });
    });
    
    
    

    function customPushFormSubmit()
    {
        debugger;
         var form = $('form#custom-push-form');
           $.ajax({
            "type": "POST",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/pushDriverCustomEvents')) ?>",
            "data": form.serialize(),
            "dataType": "json",
            "success": function (data)
            {
                if (data.success == true)
                {
                    $('.bootbox').modal('hide');
                    window.location.reload(true);
                }else{
                   
                    $('.msg').text(data.message);
                }
            },
            "error": function (errors)
            {
                alert(errors);
                $('.bootbox').modal('hide');
            }
        });
    }
    $('.tripStartProperty').hide();
    
    
    $("#Booking_bkg_trip_start").click(function(){
    if (!$('#Booking_bkg_left_For_Pickup').prop('checked'))
    {
        alert("Please check Left For Pickup Option");//customPushFormSubmit();
        return false;
    }
     var status = this.checked;
            if (status === true) {
               $('.tripStartProperty').show('slow');
        
            } else {
               $('.tripStartProperty').hide('slow');

            }
    });
    
    $("#Booking_bkg_trip_end").click(function(){
         if (!$('#Booking_bkg_trip_start').prop('checked'))
    {
        alert("Please check Trip start first");//customPushFormSubmit();
        return false;
    }
     var endstatus = this.checked;
      $('.tripEndProperty').hide();
            if (endstatus === false) {
               $('.tripEndProperty').hide();
            } else {
               $('.tripEndProperty').show();
               $('.lat').hide();
               $('.long').hide();
            }
    });
    
    
    
    $( "#Booking_bkg_end_odometer" ).change(function () {
            var startOdoMeter = '<?php echo $model->bkgTrack->bkg_start_odometer ?>';
            var ratePerKmExtra = '<?php echo $model->bkgInvoice->bkg_rate_per_km_extra ?>';
            var amtToCollected = '<?php echo $amtToCollected ?>';
            var endOdoMeter = $("#Booking_bkg_end_odometer").val();
            var extraKm = endOdoMeter - startOdoMeter;
            var extraKmCharge = extraKm * ratePerKmExtra;
            var totalDriverCollected = (+amtToCollected) + extraKmCharge;
            
            $("#Booking_bkg_extra_km").val(extraKm);
            $("#Booking_bkg_extra_km_charge").val(extraKmCharge);
            $("#Booking_bkg_vendor_collected").val(totalDriverCollected);
     });
     
     $( "#Booking_bkg_extra_toll_tax" ).change(function () {
         var extraTollTax = $("#Booking_bkg_extra_toll_tax").val();
         var amtToCollected = $("#Booking_bkg_vendor_collected").val();
         var totalDriverCollected = (+amtToCollected) + (+extraTollTax);
         $("#Booking_bkg_vendor_collected").val(totalDriverCollected);
     });
     
     $( "#Booking_bkg_extra_state_tax" ).change(function () {
         var extraStateTax = $("#Booking_bkg_extra_state_tax").val();
         var amtToCollected = $("#Booking_bkg_vendor_collected").val();
         var totalDriverCollected = (+amtToCollected) + (+extraStateTax);
         $("#Booking_bkg_vendor_collected").val(totalDriverCollected);
     });
     
     $( "#Booking_bkg_extra_min" ).change(function () {
         var extraPerMinCharge = '<?php echo $model->bkgInvoice->bkg_extra_per_min_charge ?>';
         var extraMin = $("#Booking_bkg_extra_min").val();
         var extraPerMinCharge = extraPerMinCharge * extraMin;
         var amtToCollected = $("#Booking_bkg_vendor_collected").val();
         var totalDriverCollected = (+amtToCollected) + (+extraPerMinCharge);
         $("#Booking_bkg_extra_total_min_charge").val(extraPerMinCharge);
         $("#Booking_bkg_vendor_collected").val(totalDriverCollected);
     });
     
     
    
    function getTripOtpVerified()
    {
        debugger;
        var verifyOtp=<?php echo $model->bkgTrack->bkg_trip_otp ?>;
        var odoMeter = $("#Booking_bkg_start_odometer").val();
        var otp = $("#Booking_bkg_trip_otp").val();
        if((odoMeter == '') || (odoMeter == null) )
        {
            alert("Enter odometer value");
            return false;
        }
        else if($.isNumeric(odoMeter) == false)
        {
            alert("Please enter numeric value");
            return false;
        }
        else if (otp != verifyOtp || (otp == '') || (otp == null))
        {
            alert("Enter Valid OTP");
            return false;
        }
        else
        {
            return true;
        }
        
    }

$("#Booking_bkg_arrived").click(function(){
    if (!$('#Booking_bkg_left_For_Pickup').prop('checked'))
    {
        alert("Please check Left For Pickup Option");//customPushFormSubmit();
        return false;
    }
    
    });


    $("#ebtnsbmt").click(function(){
           
            var tripStartCheck = '<?=$tripStartCheck?>';
            //alert(tripStartCheck);
            if (!$('#Booking_bkg_left_For_Pickup').prop('checked'))
              {
                  alert("Please check Left For Pickup Option");//customPushFormSubmit();
                  return false;
              }
              if ($('#Booking_bkg_trip_start').prop('checked')&& tripStartCheck == "")
              {
                var success =  getTripOtpVerified();
                if(!success)
                {
                    return false;
                }
              }
            customPushFormSubmit();
    
    });
    
</script>
<input id="map_canvas" type="hidden">
<?
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>