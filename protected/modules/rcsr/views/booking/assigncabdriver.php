<style type="text/css">

    .form-group {
        margin-bottom: 7px;
        margin-top: 15px;

        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .form-horizontal .checkbox-inline{
        padding-top: 0;
    }
    #BookingCab_chk_user_msg{
        margin-left: 10px
    }
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
    .selectize-input {
        min-width: 100px!important;
        width: 100%!important;      
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $('.bootbox').removeAttr('tabindex');
    });
</script>

<?
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/csrbooking.js?v=' . $version);
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
?>
<?php
?>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-xs-12">
	    <?php
	    $form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'			 => 'vendors-register-form1', 'enableClientValidation' => true,
		'clientOptions'		 => array(
		    'validateOnSubmit'	 => true,
		    'errorCssClass'		 => 'has-error',
		    'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){
                            if(!getUnapprovedalert()){                         
                             return false;
                            }                         
                            $.ajax({
                            "type":"POST",
                            "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/booking/assigncabdriver', ['booking_id' => $bmodel->bkg_id])) . '",
                            "data":form.serialize(),
                            "dataType": "json",
                            "success":function(data1){
                                    if(data1.success)
                                    {                                    
                                        cabAssigned(data1.oldStatus);
                                    }
                                    else
                                    {
                                        var errors = data1.errors;
                                        
                                        settings=form.data(\'settings\');
                                        $.each (settings.attributes, function (i) 
                                        {
                                            $.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
                                        });
                                        $.fn.yiiactiveform.updateSummary(form, errors);
                                    }
                                },
                            });
                            }
                        }'
		),
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'		 => array(
		    'class' => 'form-horizontal'
		),
	    ));
	    /* @var $form TbActiveForm */
	    ?>
            <div class="panel panel-default mb0">
                <div class="panel-body">
                    <div class="row">
			<?php echo CHtml::errorSummary($model); ?>
			<?= $form->hiddenField($model, 'bcb_id') ?>
			<?= $form->hiddenField($model, 'bcb_vendor_id') ?>
                        <div style="display:none" id="isVhcApproved"></div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="input-group">
					    <?php
					    $data	 = $driverJSON;
					    $this->widget('booster.widgets.TbSelect2', array(
						'model'		 => $model,
						'attribute'	 => 'bcb_driver_id',
						'val'		 => $model->bcb_driver_id,
						'asDropDownList' => FALSE,
						'options'	 => array('data' => new CJavaScriptExpression($data)),
						'htmlOptions'	 => array('style' => 'width: 100%', 'placeholder' => 'Select Driver')
					    ));
					    ?>
                                            <div class="input-group-btn">
                                                <button class="btn btn-info" type="button" id="addDriver" >Add Driver</button>
                                            </div>
                                            <span id="markedBadDriver">
                                                <span class="fa-stack" title="Bad Driver">
                                                    <i class="fa-user-secret fa-stack-1x"></i>
                                                    <i class="fa fa-ban fa-stack-2x text-danger"></i>
                                                </span>
                                            </span>

                                        </div>

                                        <div class="has-error"><?= $form->error($model, 'bcb_driver_id') ?></div>
                                    </div>  
                                </div> 
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="input-group">
					    <?
					    $data1	 = VehicleTypes::model()->getJSON($vehicleList);
					    $this->widget('booster.widgets.TbSelect2', array(
						'model'		 => $model,
						'attribute'	 => 'bcb_cab_id',
						'val'		 => $model->bcb_cab_id,
						'asDropDownList' => FALSE,
						'options'	 => array('data' => new CJavaScriptExpression($data1)),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Cab')
					    ));
					    ?>
                                            <div class="input-group-btn">
                                                <button class="btn btn-info" type="button" id="addVehicle" >Add Cab</button>
                                            </div>
                                            <span id="markedBadCar">
                                                <span class="fa-stack" title="Bad Car">
                                                    <i class="fa fa-car fa-stack-1x"></i>
                                                    <i class="fa fa-ban fa-stack-2x text-danger"></i>
                                                </span>
                                            </span>
                                        </div>
                                        <div class="has-error"><?= $form->error($model, 'bcb_cab_id') ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
				    <?= $form->textFieldGroup($model, 'bcb_driver_phone', array('label' => '')) ?>
                                </div>
                                <div id="markedBadBox" style="display:none;" class="col-sm-6 has-error">
				    <?=
				    $form->textAreaGroup($model, 'bkg_driver_cab_message', array('label'		 => '', 'widgetOptions'	 => [
					    'htmlOptions' => ['placeholder' => 'Please explain why you want to assign this driver or cab for the booking']]))
				    ?>
                                </div>
                            </div> 
                        </div>
                        <div class="col-sm-6">
			    <?=
			    $form->checkboxListGroup($model, 'chk_user_msg', array(
				'widgetOptions'	 => array(
				    'data' => array('User', 'Driver', 'Vendor'),
				),
				'inline'	 => true,
				    )
			    );
			    ?>
                        </div>
                        <div id="logOutput"></div>
                    </div>

                </div>
                <div class="panel-footer text-center">
		    <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>
                </div>
            </div>
	    <?php $this->endWidget(); ?>
        </div>
    </div>
</div>

<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>

<script>
    $(document).ready(function () {
        $("#markedBadDriver").hide();
        $("#markedBadCar").hide();
        if ($("#BookingCab_bcb_driver_id").val() != '' || $("#BookingCab_bcb_cab_id").val() != '') {
            driverDetails();
            checkCabTimeOverlap();
        }

        $("#BookingCab_chk_user_msg_0").attr('checked', 'checked');
        $("#BookingCab_chk_user_msg_1").attr('checked', 'checked');

    });
    $("#BookingCab_bcb_driver_id").change(function () {
        driverDetails();
    });
    $("#BookingCab_bcb_cab_id").change(function () {
        cabDetails();
        checkCabTimeOverlap();
    });

    function driverDetails() {
        var csrbooking = new Csrbooking();
        var model = {};
        model.driverId = $("#BookingCab_bcb_driver_id").val();
        model.vehicleId = $("#BookingCab_bcb_cab_id").val();
        csrbooking.model = model;

        $(document).on("driverCabDetails", function (event, data) {
            if ($("#BookingCab_bcb_driver_phone").val() == '') {
                $("#BookingCab_bcb_driver_phone").val(data.data.drvContact);
            }
            cabDrvDetails(data);
        });
        csrbooking.driverCabDetails();
    }
    function cabDetails() {
        var csrbooking = new Csrbooking();
        var model = {};
        model.driverId = $("#BookingCab_bcb_driver_id").val();
        model.vehicleId = $("#BookingCab_bcb_cab_id").val();
        csrbooking.model = model;
        $(document).on("driverCabDetails", function (event, data) {
            cabDrvDetails(data);
        });
        csrbooking.driverCabDetails();
    }


    function cabDrvDetails(data)
    {

        var driverBad = data.data.drvMarkBad;
        var carBad = data.data.carMarkBad;

        var vhcApproved = data.data.isVhcApproved;
        var drvApproved = data.data.isDrvApproved;

        checkRemarkBox(driverBad, carBad, vhcApproved, drvApproved, data.logOutput);
    }


    $('#addDriver').click(function () {
        agtid = $('#BookingCab_bcb_vendor_id').val();
        $href = '<?= Yii::app()->createUrl('rcsr/driver/create') ?>';
        jQuery.ajax({type: 'GET', url: $href, data: {"agtid": agtid},
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: 'Add Driver',
                    onEscape: function () {
                        // user pressed escape
                    },
                });

                box.on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            }});
    });
    function getUnapprovedalert() {
        vhcid = $("#BookingCab_bcb_cab_id").val();
        appres = false;
        if (vhcid > 0) {
            $href = '<?= Yii::app()->createUrl('rcsr/vehicle/checkapprovedntottrips') ?>';
            jQuery.ajax({type: 'GET', url: $href,
                "dataType": "json",
                async: false,
                data: {"vhcid": vhcid},
                success: function (data) {
//                    if (data.showMessage) {
//                        box = bootbox.confirm({
//                            title: 'Need papers for this car',
//                            message: 'We need papers for this car. Allowing assignment but papers must be submitted within 24 hours',
//                            buttons: {
//                                'cancel': {
//                                    label: 'Cancel',
//                                    className: 'btn-default text-center',
//                                },
//                                'confirm': {
//                                    label: 'Ok',
//                                    className: 'btn-danger text-center',
//                                }
//                            },
//                            callback: function (result) {
//                                alert(result);
//                                if (result) {
//                                    appres = "a";
//                                } else {
//                                    appres = "b";
//                                }
//                                alert("in callback " + appres);
//
//                            }
//                        });
//
//                    }
                    if (data.showMessage) {
                        var con = confirm("We need papers for this car. Allowing assignment but papers must be submitted within 24 hours");
                        if (con) {
                            appres = true;
                        } else {
                            appres = false;
                        }
                    } else {
                        appres = true;
                    }
                }
            });
            return appres;
        } else {
            alert("select a cab");
        }
    }

    function showMessage() {
        bootbox.confirm({
            size: "medium",
            message: "We need papers for this car. Allowing assignment but papers must be submitted within 24 hours",
            buttons: {
                confirm: {
                    label: 'OK',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'CANCEL',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {

                return result;

            }
        });
    }

    $('#addVehicle').click(function () {
        agtid = $('#BookingCab_bcb_vendor_id').val();
        vhtid = <?= $bmodel->bkg_vehicle_type_id ?>;
        $href = '<?= Yii::app()->createUrl('rcsr/vehicle/create') ?>';
        jQuery.ajax({type: 'GET', url: $href, data: {"agtid": agtid, "vhtid": vhtid},
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: 'Add Cab',
                    onEscape: function () {
                        // user pressed escape
                    },
                });

                box.on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            }});
    });

    function checkRemarkBox(drvBad, cabBad, vhcApproved, drvApproved, logOutput) {
        $("#markedBadDriver").hide();
        $("#markedBadCar").hide();
        $('#BookingCab_bcb_cab_id_em_').hide();
        $('#BookingCab_bcb_driver_id_em_').hide();

        if (vhcApproved != 1 || drvApproved != 1) {
            if (vhcApproved != 1) {
                $('#BookingCab_bcb_cab_id_em_').text('Cab is not approved');
                $('#BookingCab_bcb_cab_id_em_').addClass('text-danger');
                $('#BookingCab_bcb_cab_id_em_').show();
            }
            if (drvApproved != 1) {
                $('#BookingCab_bcb_driver_id_em_').text('Driver is not approved');
                $('#BookingCab_bcb_driver_id_em_').addClass('text-danger');
                $('#BookingCab_bcb_driver_id_em_').show();
            }
            $("#markedBadBox").show();
        } else if (drvBad > 0 || cabBad > 0) {
            $("#markedBadBox").show();
            if (drvBad > 0) {
                $("#markedBadDriver").show();
            } else {
                $("#markedBadDriver").hide();
            }
            if (cabBad > 0) {
                $("#markedBadCar").show();
            } else {
                $("#markedBadCar").hide();
            }
        } else {
            $("#markedBadBox").hide();
        }
        if ($.fn.yiiGridView != undefined && $.fn.yiiGridView.settings['bookingmarkedbadGrid'] != undefined) {
            $(document).off('click.yiiGridView', $.fn.yiiGridView.settings['bookingmarkedbadGrid'].updateSelector);
        }
        $('#logOutput').html(logOutput);
    }

    function checkCabTimeOverlap() {
        var bcbid = $("#BookingCab_bcb_id").val();
        var cabid = $("#BookingCab_bcb_cab_id").val();
        if (cabid > 0) {
            $href = '<?= Yii::app()->createUrl('rcsr/booking/checkcabtimeoverlap') ?>';
            jQuery.ajax({type: 'GET', "dataType": "json", url: $href, data: {"bcbid": bcbid, "cabid": cabid},
                success: function (data1) {
                    if (data1.overlapTrips > 0) {
                        $pretext = $('#BookingCab_bcb_cab_id_em_').text();
                        $errText = 'This Cab is already assigned to other booking for the trip duration.';
                        $textVal = ($pretext != '') ? '<br>' + $errText : $errText;
                        $('#BookingCab_bcb_cab_id_em_').html($textVal);
                        $('#BookingCab_bcb_cab_id_em_').addClass('text-danger');
                        $('#BookingCab_bcb_cab_id_em_').show();
                        $("#markedBadBox").show();
                    }
                }
            });
        }
    }
    refreshDriver = function () {
        agtid = $('#BookingCab_bcb_vendor_id').val();
        box.modal('hide');
        $href = '<?= Yii::app()->createUrl('rcsr/driver/json') ?>';
        jQuery.ajax({type: 'GET', "dataType": "json", url: $href, data: {"agtid": agtid},
            success: function (data1) {
                $data = data1;
                $('#<?= CHtml::activeId($model, "bcb_driver_id") ?>').select2({data: $data, multiple: false});
            }
        });
    };
    refreshCab = function () {
        agtid = $('#BookingCab_bcb_vendor_id').val();
        vhtid = <?= $bmodel->bkg_vehicle_type_id ?>;
        box.modal('hide');
        $href = '<?= Yii::app()->createUrl('rcsr/vehicle/json') ?>';
        jQuery.ajax({type: 'GET', "dataType": "json", url: $href, data: {"agtid": agtid},
            success: function (data1) {
                $data = data1;
                $('#<?= CHtml::activeId($model, "bcb_cab_id") ?>').select2({data: $data, multiple: false});
            }
        });
    };
</script>

