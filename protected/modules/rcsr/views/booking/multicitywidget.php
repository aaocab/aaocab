<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
?><style>
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
    .dtpiker {
        position: relative;
        left: 0px;
        top: 0px;
        z-index: 99999!important;
    }
    .tmpiker {
        position: relative;
        left: 0px;
        top: 0px;
        z-index: 99999!important;
    }

    td, th {
        padding: 10px  !important ; 
    }
</style>
<div class="panel">
    <div class="panel-body">
        <?php
        ?>

        <div class="col-md-12">
            <?php if (Yii::app()->user->hasFlash('credits')) { ?>
                <div class="flash-success">
                    <div style="text-align: center;"><?php echo Yii::app()->user->getFlash('credits'); ?></div>
                </div>
            <?php } ?>



            <?php
            $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                'id' => 'multicitywidget_form',
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                    'errorCssClass' => 'has-error',
                    'afterValidate' => 'js:function(form,data,hasError)
			{
				if(!hasError)
				{                                      
                                               
				}
                               
			}'
                ),
                'enableAjaxValidation' => false,
                'errorMessageCssClass' => 'help-block',
                'htmlOptions' => array(
                    'class' => 'form-horizontal',
                ),
            ));
            /* @var $form TbActiveForm */
            ?>

            <div class="row" style="position: relative">
                <div class="col-xs-12 col-sm-6 col-md-3 mb10 p5" >
                    <div class="input-group col-xs-12">
                        <input type="hidden" id="multicitysubmit" name="multicitysubmit" value="[]">
                        <?php
                        echo $form->hiddenField($model, 'bkg_booking_type');
                        ?>

                        <?php
                        $this->widget('booster.widgets.TbSelect2', array(
                            'model' => $model,
                            'attribute' => 'bkg_from_city_id',
                            'val' => $model->bkg_from_city_id,
                            'asDropDownList' => FALSE,
                            'options' => array('data' => new CJavaScriptExpression('$cityList')),
                            'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Source City', 'id' => 'from_city')
                        ));
                        ?>
                        <?php
                        //$datacity = Cities::model()-> ();
                        // $arrCities = ['' => 'Select Source'] + CHtml::listData(Cities::model()->getServiceCity(), 'cty_id', 'cty_name');
                        // echo $form->dropDownListGroup($model, 'bkg_from_city_id', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control  border-radius', 'onchange' => 'populateData1(this,\'\')', 'id' => 'from_city'], 'data' => $arrCities))); //'data'=>$arrCities
                        ?>

                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 mb10 p5">
                    <div class="input-group col-xs-12">
                        <?php
                        $this->widget('booster.widgets.TbSelect2', array(
                            'model' => $model,
                            'attribute' => 'bkg_to_city_id',
                            'val' => $model->bkg_to_city_id,
                            'asDropDownList' => FALSE,
                            'options' => array('data' => new CJavaScriptExpression('$cityList')),
                            'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Destination City', 'id' => 'to_city')
                        ));
                        ?>
                        <?php
                        //    echo $form->dropDownListGroup($model, 'bkg_to_city_id', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['style' => 'width:100%', 'class' => 'form-control  border-radius', 'id' => 'to_city'], 'data' => ['' => '  Select Destination  '], 'data' => $arrCities)));
                        ?>

                    </div>
                </div>
                <div class="col-xs-12 col-sm-9 col-md-4 mb10">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 mb10 p5 dtpiker">
                            <?
                            $defaultDate = date('Y-m-d H:i:s', strtotime('+4 hour'));
                            $pdate = ($model->bkg_pickup_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $model->bkg_pickup_date_date;
                            $ptime = ($model->bkg_pickup_date_time == '') ? date('h:i A', strtotime('+4 hour')) : $model->bkg_pickup_date_time;
                            ?>
                            <?=
                            $form->datePickerGroup($model, 'bkg_pickup_date_date', array('label' => '',
                                'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(),
                                        'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'placeholder' => 'Pickup Date',
                                        'value' => $pdate, 'id' => 'pickup_date',
                                        'class' => 'datepicker')),
                                'prepend' => '<i class="fa fa-calendar"></i>'));
                            ?>

                        </div>
                        <input type="hidden" id="estimated_pickup_date" name="estimated_pickup_date" value="">
                        <div class="col-xs-12 col-sm-6 p5 tmpiker">
                            <?=
                            $form->timePickerGroup($model, 'bkg_pickup_date_time', array('label' => '',
                                'widgetOptions' => array('options' => array('defaultTime' => false, 'autoclose' => true),
                                    'htmlOptions' => array('required' => true, 'placeholder' => 'Pickup Time',
                                        'value' => $ptime, 'id' => 'pickup_time',
                                        'class' => 'form-control pr0 border-radius text text-info'))));
                            ?> 
                        </div>

                    </div>
                    <div  class="row">
                        <div  class="col-xs-8">
                            <?= $form->textAreaGroup($model, 'bkg_pickup_address', array('label' => 'Pick up Location', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Pickup Location', 'id' => 'pickup_address')))) ?>
                        </div>
                        <div  class="col-xs-4">
                            <?= $form->textFieldGroup($model, 'bkg_pickup_pincode', array('label' => 'Pin Code', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Pin Code', 'id' => 'pickup_pin')))) ?>             
                        </div>
                    </div>
                </div>
                <div class="col-xs-1" style="position: absolute; bottom: 40px; right: 25px">
                    <div class="btn btn-info addmoreField" id="fieldAfter" title="Add More">
                        <i class="fa fa-plus"></i>Add more
                    </div>
                </div>

            </div>




            <div class="col-xs-12">
                <div class="row" id='tripTable' style="display: none">
                    <div class="col-xs-12 float-none marginauto">

                        <div id="tripinfo_div">
                            <div class="row"> <div class="col-xs-6"><h3 class="mb10 text-uppercase">Trip Info</h3></div><div class="col-xs-6 pull-right"> <h4 class="pt10 pull-right">Total days for the trip: <span class="text-success"><span id="totdays"></span> days</span></h4></div></div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Date</th>
                                    <th>Distance</th>
                                    <th>Duration</th>
                                    <th>Day</th>
                                    </thead>
                                    <tr id='insertTripRow'></tr>
                                </table>                         
                            </div>
                        </div>

                        <div class="row" id="drop_address_div" style="display: none">
                            <div class="col-sm-6">
                                <?= $form->textAreaGroup($model, 'bkg_drop_address', array('label' => 'Drop Location', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Drop Location', 'id' => 'drop_address')))) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->textFieldGroup($model, 'bkg_pickup_pincode', array('label' => 'Pin Code', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Pin Code', 'id' => 'drop_pin')))) ?>                                 
                            </div>
                        </div>
                        <div class="row" id="return_div" style="display: none">
                            <div class="col-sm-6 dtpiker">

                                <?=
                                $form->datePickerGroup($model, 'bkg_return_date_date', array('label' => 'Return Date',
                                    'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Return Date', 'id' => 'return_date')), 'prepend' => '<i class="fa fa-calendar"></i>'));
                                ?>
                            </div>
                            <div class="col-sm-6 tmpiker">
                                <?=
                                $form->timePickerGroup($model, 'bkg_return_date_time', array('label' => 'Return Time',
                                    'widgetOptions' => array('options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Return Time', 'id' => 'return_time'))));
                                ?>
                            </div>
                            <div id="errordivreturn" class="mt5 ml15" style="color:#da4455"></div>
                        </div>

                    </div>
                </div>
            </div>  
            <div class="col-xs-12 text-center mt10" id="multisubmitbtn" style="display: none">
                <button type="button" class="btn btn-success btn-lg pl40 pr40" onclick="savedatamulticity(<?= $model->bkg_booking_type ?>)">SAVE</button>
                <? //= CHtml::submitButton('SAVE', array('class' => 'btn btn-success btn-lg pl40 pr40')); ?>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function ()
    {
        $('.bootbox').removeAttr('tabindex');

        $jsonArrMulticity = [];
        $count = 1;
        $scity = 0;
        $svalue = 0;
        jQuery('#pickup_date').datepicker({'autoclose': true, 'startDate': new Date(), 'format': 'dd/mm/yyyy', 'language': 'en'});
        jQuery('#pickup_time').timepicker({'defaultTime': false, 'autoclose': true});
        jQuery('#return_date').datepicker({'autoclose': true, 'startDate': new Date(), 'format': 'dd/mm/yyyy', 'language': 'en'});
        jQuery('#return_time').timepicker({'defaultTime': false, 'autoclose': true});
    });

    $('#fieldAfter').unbind("click").bind("click", function () {
        $('#drop_address_div').hide();
        $('#drop_address').val('');

        if ($('#from_city').val() != '') {
            if ($('#to_city').val() != '') {
                if ($('#pickup_date').val() != '') {
                    if ($('#pickup_time').val() != '') {


                        if ($count == 1 && $('#pickup_address').val() == "")
                        {
                            alert('Start Pickup location is mandatory');
                            return;
                        }
                        $('#tripTable').show();

                        if ($count > 1)
                        {
                            var fromCity = $jsonArrMulticity[($count - 2)].drop_city;
                            var pick_city_name = $jsonArrMulticity[($count - 2)].drop_city_name;
                        } else
                        {
                            var fromCity = $('#from_city').val();
                            var pick_city_name = $('#from_city').select2('data').text;
                        }
                        $jsonArrMul = [];
                        $jsonArrMul.push({
                            "pickup_city": fromCity,
                            "drop_city": $('#to_city').val(),
                            "pickup_city_name": pick_city_name,
                            "drop_city_name": $('#to_city').select2('data').text,
                            "pickup_address": $('#pickup_address').val(),
                            "drop_address": "",
                            "pickup_date": $('#pickup_date').val(),
                            "pickup_time": $('#pickup_time').val(),
                            "date": $('#pickup_date').val() + " " + $('#pickup_time').val(),
                            "duration": 0,
                            "estimated_date": $('#estimated_pickup_date').val(),
                            "distance": 0,
                            "return_date": "",
                            "return_time": "",
                            "pickup_pin": "",
                            "drop_pin": "",
                            "day": 0
                        });


                        var start_pickup_date = ($count == 1) ? $('#pickup_date').val() : $jsonArrMulticity[0].pickup_date;
                        var start_pickup_time = ($count == 1) ? $('#pickup_time').val() : $jsonArrMulticity[0].pickup_time;

                        var href = '<?= Yii::app()->createUrl("rcsr/booking/multicityvalidate"); ?>';
                        $.ajax({
                            url: href, dataType: "json",
                            data: {"multicitydata": $jsonArrMul[0], "booking_type": $('#Booking_bkg_booking_type').val(), "start_pickup_date": start_pickup_date, "start_pickup_time": start_pickup_time},
                            "success": function (data) {
                                if (data.error != 1) {
                                    if ($count == 1)
                                    {
                                        data.validate_success = true;
                                    }
                                    if (data.validate_success)
                                    {
                                        $jsonArrMulticity.push({
                                            "pickup_city": fromCity,
                                            "drop_city": $('#to_city').val(),
                                            "pickup_city_name": pick_city_name,
                                            "drop_city_name": $('#to_city').select2('data').text,
                                            "pickup_address": $('#pickup_address').val(),
                                            "drop_address": "",
                                            "pickup_date": $('#pickup_date').val(),
                                            "pickup_time": $('#pickup_time').val(),
                                            "date": data.date,
                                            "duration": data.duration,
                                            "estimated_date": $('#estimated_pickup_date').val(),
                                            "distance": data.distance,
                                            "return_date": "",
                                            "return_time": "",
                                            "pickup_pin": $('#pickup_pin').val(),
                                            "drop_pin": "",
                                            "day": data.day,
                                            "totday": data.totday
                                        });
                                        if ($count > 1)
                                        {
                                            $jsonArrMulticity[($count - 2)].drop_address = $jsonArrMulticity[($count - 1)].pickup_address;
                                            $jsonArrMulticity[($count - 2)].drop_pin = $jsonArrMulticity[($count - 1)].pickup_pin;
                                        }
                                        $('#estimated_pickup_date').val(data.estimated_date_next);
                                        $('#multicitysubmit').val(JSON.stringify($jsonArrMulticity));
                                        //new details div
                                        $('#insertTripRow').before('<tr class="multicitydetrow">' +
                                                '<td id="fcity0"></td>' +
                                                '<td id="tcity0"> </td>' +
                                                '<td id="fdate0"> </td>' +
                                                '<td id="citydist0"> </td>' +
                                                '<td id="citydura0"> </td>' +
                                                '<td id="noOfDays0"> </td>' +
                                                '</tr>');
                                        $('#fcity0').attr('id', 'fcity' + $count);
                                        $('#tcity0').attr('id', 'tcity' + $count);
                                        $('#fdate0').attr('id', 'fdate' + $count);
                                        $('#citydist0').attr('id', 'citydist' + $count);
                                        $('#citydura0').attr('id', 'citydura' + $count);
                                        $('#noOfDays0').attr('id', 'noOfDays' + $count);
                                        $('#pickadrs0').attr('id', 'pickadrs' + $count);
                                        $('#dropadrs0').attr('id', 'dropadrs' + $count);

                                        var ptripdate = $('#pickup_date').val();
                                        var ptriptime = $('#pickup_time').val();
                                        var oldtxt = $('#to_city').select2('data').text;
                                        var oldftxt = $('#from_city').select2('data').text;
                                        $('#noOfDays' + $count).text("" + data.day + "");
                                        $('#totdays').text("" + data.totday + "");
                                        if ($count > 1) {
                                            $jsonArrMulticity[($count - 2)].drop_pin = $jsonArrMulticity[($count - 1)].pickup_pin;
                                            oldftxt = $jsonArrMulticity[($count - 2)].drop_city_name;
                                            $('#tcity' + ($count - 1)).html('<b>' + $jsonArrMulticity[($count - 2)].drop_city_name + "</b><br>" + $jsonArrMulticity[($count - 2)].drop_address + " ,pin: " + $jsonArrMulticity[($count - 2)].drop_pin);
                                        }
                                        $('#fcity' + $count).html('<b>' + oldftxt + "</b><br>" + $jsonArrMulticity[($count - 1)].pickup_address + " ,pin: " + $jsonArrMulticity[($count - 1)].pickup_pin);
                                        $('#tcity' + $count).html('<b>' + oldtxt + "</b><br>" + $jsonArrMulticity[($count - 1)].drop_address + " ,pin: " + $jsonArrMulticity[($count - 1)].drop_pin);
                                        $('#fdate' + $count).text(ptripdate + " " + ptriptime);
                                        $('#citydist' + $count).text(data.distance);
                                        $('#citydura' + $count).text(data.duration);
                                        //new details div
                                        if ($count == 1) {
                                            $href = '<?= Yii::app()->createUrl('admin/city/json') ?>';
                                            jQuery.ajax({"dataType": "json", url: $href, "async": false,
                                                success: function (data1) {
                                                    $data = data1;
                                                    $('#from_city').select2({data: $data, multiple: false});

                                                },
                                                error: function (xhr, status, error)
                                                {
                                                    console.log(error);
                                                }
                                            });
                                        }
                                        $('#from_city').select2("val", $jsonArrMulticity[($count - 1)].drop_city);
                                        $('#from_city').attr('disabled', true);
                                        if ($('#Booking_bkg_booking_type').val() == 2)
                                        {
                                            $('#to_city').select2("val", $jsonArrMulticity[0].pickup_city);
                                        } else
                                        {
                                            $('#to_city').select2("val", '');
                                        }
                                        $('#pickup_date').val(data.next_pickup_date);
                                        $('#pickup_time').val(data.next_pickup_time);
                                        $('#pickup_address').val('');
                                        $('#return_time').val($jsonArrMulticity[($count - 1)].pickup_time);
                                        $('#return_date').val($jsonArrMulticity[($count - 1)].pickup_date);
                                        $('#pickup_pin').val('');
                                        if ($count > 1)
                                        {
                                            $('#multisubmitbtn').show();
                                        }
                                        $count++;
                                    } else
                                    {
                                        var est_date = $('#estimated_pickup_date').val();
                                        alert('pickup date time must be greater than estimated date time: ' + est_date);
                                    }
                                } else
                                {
                                    alert('Sorry! Your request can not be processed right now!Please try later.' + data.error);
                                }
                            }
                        });

                    } else {
                        alert('Please provide pickup time');
                    }
                } else {
                    alert('Please provide pickup date');
                }
            } else {
                alert('Please choose destination first');
            }
        } else {
            alert('Please choose source first');
        }
    });

    function savedatamulticity(booking_type)
    {
        if (booking_type == 2 && $jsonArrMulticity[($count - 2)].drop_city != $jsonArrMulticity[0].pickup_city)
        {
            alert('For round trip source and destination city must be same');
        } else {

            if ($('#drop_address_div').css('display') != 'none')
            {
                if ($('#drop_address').val() != '')
                {
                    if (booking_type == 2)
                    {
//                        if ($('#return_date').val() == '' && $('#return_time').val() == '')
//                        {
//                            alert('Return date time is mandatory');
//                            return;
//                        }
//                        else
//                        {
//                            var d1 = getDateobj($('#return_date').val(), $('#return_time').val());
//                            var d2 = getDateobj($jsonArrMulticity[($count - 2)].pickup_date, $jsonArrMulticity[($count - 2)].pickup_time);
//                            if (d1 < d2)
//                            {
//                                alert("return date time can not be less than pickup time");
//                                return;
//                            }
                        $jsonArrMulticity[($count - 2)].return_date = $('#return_date').val();
                        $jsonArrMulticity[($count - 2)].return_time = $('#return_time').val();
                        // }

                    }
                    $jsonArrMulticity[($count - 2)].drop_address = $('#drop_address').val();
                    $jsonArrMulticity[($count - 2)].drop_pin = $('#drop_pin').val();
                    $('#multicitysubmit').val(JSON.stringify($jsonArrMulticity));
                    multicitybootbox.hide();
                    multicitybootbox.remove();
                    var jsonstring = JSON.stringify($jsonArrMulticity);
                    updateMulticity(jsonstring, ($count - 2));
                    $jsonArrMulticity = [];
                    $count = 1;
                } else
                {
                    alert('Drop address is mandatory');
                }
            }
            $('#drop_address_div').show();
            if (booking_type == 2)
            {
                // $('#return_div').show();

            }
        }
    }
</script>

