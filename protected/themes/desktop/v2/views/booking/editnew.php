<?php
//$cartype = VehicleTypes::model()->getParentVehicleTypes1();
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
if (Yii::app()->request->isAjaxRequest) {
    $cls = "";
} else {
    $cls = "col-lg-4 col-md-6 col-sm-8 pb10";
}
?>
<style>
    .form-horizontal .form-group{
        margin: 0;
    }
    .datepicker.datepicker-dropdown.dropdown-menu,
    .bootstrap-timepicker-widget.dropdown-menu,
    .yii-selectize.selectize-dropdown
    {z-index: 9999 !important;}
    .selectize-input {
        min-width: 0px !important; 
        width: 100% !important;
    }
    .modal-body{
        padding-bottom: 0
    }
    .modal-header{
        display:block;
    }
    .modal-dialog{ width: 68%;}
    
    @media (min-width: 768px) and (max-width: 1200px) {
        .modal-dialog{ width: 68%;}
    }
    @media (min-width: 320px) and (max-width: 767px) {
        .modal-dialog{ width: 90%; margin: 0 auto;}
    }
</style>
<div class="row">
    <div class="<?= $cls ?>" style="float: none; margin: auto">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'booking-modify-form', 'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'errorCssClass' => 'has-error',
                'afterValidate' => 'js:function(form,data,hasError){
                if(!hasError){
				 $contact = $("#fullContactNumber");
                 $code = $("#' . CHtml::activeId($model->bkgUserInfo, 'bkg_country_code') . '");
                 $email = $("#' . CHtml::activeId($model->bkgUserInfo, 'bkg_user_email') . '");    
                 $cab = $("#' . CHtml::activeId($model, 'bkg_vehicle_type_id') . '");   
                 pdate = $("#' . CHtml::activeId($model, 'bkg_pickup_date_date') . '").val();
                 ptime = $("#' . CHtml::activeId($model, 'bkg_pickup_date_time') . '").val();
                            
                    if($contact.val().trim() == "" || $email.val().trim() == "")
                    {
                        $contact.focus();
                        $("#BookingUser_bkg_contact_no_em_").text("");
                        $("#BookingUser_fullContactNumber_em_").text("Please enter contact number or email address");
                        $("#BookingUser_bkg_contact_no_em_").show();   
						alert("Please enter contact number or email address");
                        return false;
                    }
					if($contact.val().length < 10 || $contact.val().length > 12)
                    {
                        $contact.focus();  
						alert("Please enter valid contact number");
                        return false;
                    }
                    if($contact.val().trim() != "" && $code.val().trim() == "")
                    {
                        $code[0].selectize.focus();
                        $("#BookingUser_bkg_country_code_em_").text("");
                        $("#BookingUser_bkg_country_code_em_").text("Please enter ISD code");
                        $("#BookingUser_bkg_country_code_em_").show();
                        alert("Please enter ISD code");                            
                        return false;
                    }   
                    if($cab.val() == "")
                    {
                        $cab.focus();
                        $("#Booking_bkg_vehicle_type_id_em_").text("");
                        $("#Booking_bkg_vehicle_type_id_em_").text("Please select a cab");
                        $("#Booking_bkg_vehicle_type_id_em_").show();                                          
                        return false;
                    }   
                    if(pdate.trim()== "" || ptime.trim() == "")
                    {
						alert(pdate);
                        $("#Booking_bkg_pickup_date_date_em_").text("");
                        $("#Booking_bkg_pickup_date_date_em_").show();
                        $("#Booking_bkg_pickup_date_date_em_").text("Please choose your date and time for pickup");
                        alert("Please choose your date and time for pickup");                                
                        return false;
                    }
                    else
                    {
                        var dateArr = pdate.split("/");
                        var timeArr = ptime.split(" ");
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
                        var currDateTime = new Date();
                        var dateObj = new Date(Number(dateArr[2]), Number(dateArr[1]) - 1, Number(dateArr[0]), hour, min, 0);

                        if ((dateObj - currDateTime) / (1000 * 60 * 60) < 2) {
                            error = true;
                            $("#Booking_bkg_pickup_date_date_em_").show();
                            $("#Booking_bkg_pickup_date_date_em_").text("Time left for departure less than two hours");
                            
                            $("#Booking_bkg_pickup_date_date_em_").removeClass("help-block");
                            $("#Booking_bkg_pickup_date_date_em_").addClass("has-error text-danger");
                            //alert("Time left for departure less than two hours");
                          return false;
                        }
                    }
                   
                    $.ajax({
                        "type":"POST",
                        "dataType":"json",
                        "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                        "data":form.serialize(),
                        "success":function(data1){
                        if(!$.isEmptyObject(data1) && data1.success==true){
                            $bkgid = data1.id;
                            bootbox.hideAll();
                            location.reload();
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
            'enableAjaxValidation' => true,
            'errorMessageCssClass' => 'help-block',
            'htmlOptions' => array(
                'class' => 'form-horizontal',
            ),
        ));
        ?>


        <div class="col-12">
            <div class="row">
                <?php echo CHtml::errorSummary($model); ?>
                <?= $form->hiddenField($model, 'bkg_id') ?>
                <?= CHtml::hiddenField('hash', Yii::app()->shortHash->hash($model->bkg_id)) ?>
                <?php $route = BookingRoute::model()->getRouteName($model->bkg_id); ?>
                <div class="col-12 text-center font-18 mt0 mb20">
                    <b><?= $route ?></b>
                    <span class="has-error"><?php echo $form->error($model, 'bkg_from_city_id'); ?></span>
                    
                </div>
                <div class="col-6">
                    <span class="has-error"><?php echo $form->error($model, 'bkg_to_city_id'); ?></span>
                </div>
                <div class="col-12">
                    <div class="row">
                    <div class="col-12 col-sm-6">
                        <b>Estimated distance</b> : <?= $model->bkg_trip_distance . " Km"; ?>
                    </div> 
                    <div class="col-12 col-sm-6">
                        <b>Estimated duration</b> : <span id="time">
						<?php
                            $hr = date('G', mktime(0, $model->bkg_trip_duration)) . " Hr";
                            $min = (date('i', mktime(0, $model->bkg_trip_duration)) != '00') ? ' ' . date('i', mktime(0, $model->bkg_trip_duration)) . " min" : '';
                            echo $hr . $min;
                        ?></span>
                        <?= $form->hiddenField($model, 'bkg_trip_duration', array()) ?>
                    </div>
                </div>
                </div>
                <div class="col-md-6 mt10">
                    <b>Car Model</b> : <?= $cab ?>
                </div>
                <div class="col-md-6" style="display: none;">
                    <div class="row">
                    <div class="col-12 col-sm-12">   
                        <input type="hidden" value="<?= DateTimeFormat::DateTimeToDatePicker($model->bkg_pickup_date) ?>" name="Booking[bkg_pickup_date_date]" id="Booking_bkg_pickup_date_date">	
				        <input type="hidden" value="<?= date('h:i A', strtotime($model->bkg_pickup_date)) ?>" name="Booking[bkg_pickup_date_time]" id="Booking_bkg_pickup_date_time">	
                    </div>
                    <div id="errordivpdate" class="ml15 mt10 " style="color:#da4455"></div>
					</div>
                </div>
                <div class="col-12 mt10 mb20">
                <div class="row">
                    <div class="col-7">
                        <label class="control-label">Primary Contact Number </label>
                        <div class="form-group">   
                                <?php
                                //$bookingModel->bkgUserInfo->fullContactNumber= $bookingModel->bkgUserInfo->bkg_contact_no;
                                $this->widget('ext.intlphoneinput.IntlPhoneInput', array(
                                    'model' => $model->bkgUserInfo,
                                    'attribute' => 'fullContactNumber',
                                    'codeAttribute' => 'bkg_country_code',
                                    'numberAttribute' => 'bkg_contact_no',
                                    'options' => array(// optional
                                        'separateDialCode' => true,
                                        'autoHideDialCode' => true,
                                        'initialCountry' => 'in'
                                    ),
                                    'htmlOptions' => ['class' => 'yii-selectize selectized form-control', 'id' => 'fullContactNumber' . $id],
                                    'localisedCountryNames' => false, // other public properties
                                ));
                                ?>
                           
                                <?php  //= $form->textField($model->bkgUserInfo, 'bkg_contact_no', array('placeholder' => "Primary Mobile Number", 'class' => 'form-control')) ?>
                                <?php //echo $form->error($model->bkgUserInfo, 'bkg_country_code'); ?>
                                <?php echo $form->error($model->bkgUserInfo, 'fullContactNumber'); ?>
                            
                        </div>
                    </div>
                    <div class="col-5 pl0">      
						 <label class="control-label">Email Address </label>
                        <?= $form->emailField($model->bkgUserInfo, 'bkg_user_email',  ['placeholder' => "Email Address",'class' => 'form-control m0']) ?>                      
                    </div>  
                </div>
                </div>
            </div>
        </div>

        <div class="panel-footer text-center mb20">
            <?php echo CHtml::submitButton($isNew, array('class' => 'btn btn-primary text-uppercase gradient-green-blue font-14 border-none mt15')); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>
<?php echo CHtml::endForm(); ?>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $('.bootbox').removeAttr('tabindex');
    });

    $('#BookingUser_bkg_pickup_date_date').datepicker({
        format: 'dd/mm/yyyy'
    });


    $(document).ready(function () {
        $('.bootbox').removeAttr('tabindex');
    });
</script>


