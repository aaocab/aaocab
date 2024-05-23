<?php
//Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');

$cityRadius = Yii::app()->params['airportCityRadius'];
?>
<style>
    .isd-input .selectize-input{
        min-width: 70px ;
        border-radius: 0;
    }
    .form-horizontal .checkbox-inline {
        padding-top: 0;
    }
    input[type="radio"]{
        margin-top: 0;
    }
</style>
<div class="col-md-12">
    <?php
    if (Yii::app()->user->hasFlash('credits')) {
        ?>
        <div class="flash-success">
            <div style="text-align: center;"><?php echo Yii::app()->user->getFlash('credits'); ?></div>
        </div>
    <?php } ?>

    <div class="row">
        <h3 class="m0 mb10 text-uppercase pt30 p5">Enter Customer Details</h3>
    </div>
    <?php
    // refreshDetails(data2.booking);
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'bookingtime-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'errorCssClass' => 'has-error',
            'afterValidate' => 'js:function(form,data,hasError){
				if(!hasError){
					$.ajax({
						"type":"POST",

						"dataType":"html",
						"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('agent/booking/route')) . '",
						"data":form.serialize(),
                        "beforeSend": function(){
                            ajaxindicatorstart("");
                        },
                        "complete": function(){
                            ajaxindicatorstop();
                        },
						"success":function(data2){
							var data = "";
							var isJSON = false;
							try {
								data = JSON.parse(data2);
								isJSON = true;
							} catch (e) {

							}
							if(!isJSON){
								openTab(data2,3);
								' . //trackPage(\'' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/rtview')) . '\');
            'disableTab(3);
							}
							else
							{
								var errors = data.errors;
								settings=form.data(\'settings\');
								$.each (settings.attributes, function (i) {
									$.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
								});
								$.fn.yiiactiveform.updateSummary(form, errors);
								messages = errors;
								content = \'\';
								var summaryAttributes = [];
								for (var i in settings.attributes) {
									if (settings.attributes[i].summary) {
										summaryAttributes.push(settings.attributes[i].id);
									}
								}
								displayError(form, messages);
							}             
						},
						error: function (xhr, ajaxOptions, thrownError) 
						{
								alert(xhr.status);
								alert(thrownError);
						}
					});
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
    $models = $model->bookingRoutes;
    array_push($models, $model);
    ?>
    <?= $form->errorSummary($model); ?>
    <?= $form->hiddenField($model, 'bkg_booking_type'); ?>
    <?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id1', 'class' => 'clsBkgID']); ?>           
    <?= $form->hiddenField($model, 'hash', ['id' => 'hash1']); ?>     
    <input type="hidden" id="step1" name="step" value="1">
    <div class="row">
        <div class="col-xs-12 col-sm-8" style="margin: auto; float: none; position: relative;">
            <? if ($model->bkg_booking_type == 4) { ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 " style="margin: auto; float: none;">
                        <?= $form->radioButtonListGroup($model, 'bkg_transfer_type', array('widgetOptions' => array('data' => Booking::model()->transferTypes, 'htmlOptions' => ['onclick' => 'changeLabelTextobj(this)', 'onchange' => 'changeLabelTextobj(this)']), 'inline' => true)) ?>
                    </div>
                </div>
            <? } ?>
            <div class="row">
                <div class="col-xs-12 col-sm-6 ptl0 pb0 pl5 pr5">
                    <label class="control-label">Primary Contact Number </label>
                    <div class="form-group">   
                        <div class="col-xs-3 isd-input">
                            <?php
                            $this->widget('ext.yii-selectize.YiiSelectize', array(
                                'model' => $model,
                                'attribute' => 'bkg_country_code',
                                'useWithBootstrap' => true,
                                "placeholder" => "Code",
                                'fullWidth' => false,
                                'htmlOptions' => array(
                                ),
                                'defaultOptions' => array(
                                    'create' => false,
                                    'persist' => true,
                                    'selectOnTab' => true,
                                    'createOnBlur' => true,
                                    'dropdownParent' => 'body',
                                    'optgroupValueField' => 'pcode',
                                    'optgroupLabelField' => 'pcode',
                                    'optgroupField' => 'pcode',
                                    'openOnFocus' => true,
                                    'labelField' => 'pcode',
                                    'valueField' => 'pcode',
                                    'searchField' => 'name',
                                    'closeAfterSelect' => true,
                                    'addPrecedence' => false,
                                    'onInitialize' => "js:function(){
                                this.load(function(callback){
                                var obj=this;                                
                                xhr=$.ajax({
                                    url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('index/country')) . "',
                                    dataType:'json',        
                                    cache: true,
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
                                    'render' => "js:{
                            option: function(item, escape){  
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
                        <div class="col-xs-9 pl15">
                            <?= $form->textField($model, 'bkg_contact_no', array('placeholder' => "Primary Mobile Number", 'class' => 'form-control')) ?>
                            <?= $form->error($model, 'bkg_country_code'); ?>
                            <?= $form->error($model, 'bkg_contact_no'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 pl20 pr20">      
                    <?= $form->emailFieldGroup($model, 'bkg_user_email', array('label' => 'Email Address', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email Address", 'id' => CHtml::activeId($model, "bkg_user_email1")]))) ?>                      
                </div>                
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php
            $brtRoutes = $model->bookingRoutes;
            /* @var $model Booking */
            if ($model->bkg_id > 0) {
                //$brtRoutes = BookingRoute::model()->getAllByBkgid($model->bkg_id);
            }
            if (empty($brtRoutes)) {
                $brtRoutes = [];
                $brtModel = BookingRoute::model();
                $defaultDate = date('Y-m-d H:i:s', strtotime('+7 days 6am'));
                $mindate = date('Y-m-d', strtotime('+4 hours'));
                $brtModel->brt_min_date = $mindate;
                $brtModel->brt_from_city_id = $model->bkg_from_city_id;
                $tcity = $model->bkg_to_city_id;
//				$brtModel->brt_to_city_id = $model->bkg_to_city_id;
                $brtModel->brt_pickup_date_date = $model->bkg_pickup_date_date;
                $brtModel->brt_pickup_date_time = $model->bkg_pickup_date_time;
                $pdate = ($brtModel->brt_pickup_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $brtModel->brt_pickup_date_date;
                $ptime = ($brtModel->brt_pickup_date_time == '') ? date('h:i A', strtotime('6am')) : $brtModel->brt_pickup_date_time;
                $brtModel->brt_pickup_date_date = $pdate;
                $brtModel->brt_pickup_date_time = $ptime;
                $brtRoutes[] = $brtModel;
            }
            $scity = '';
            $pcity = '';
            foreach ($brtRoutes as $brtRoute) {


                if ($oldRoute == null) {
                    $oldRoute = BookingRoute::model();
                }
                $form->error($brtRoute, 'brt_from_city_id');
                $form->error($brtRoute, 'brt_to_city_id');
                $form->error($brtRoute, 'brt_pickup_date_date');
                $form->error($brtRoute, 'brt_pickup_date_time');
                $brtRoute->populateMinDate($oldRoute->brt_from_city_id, $oldRoute->brt_pickup_date_date, $oldRoute->brt_pickup_date_time);
                $this->renderPartial('addroute', ['model' => $brtRoute, 'sourceCity' => $oldRoute->brt_to_city_id, 'previousCity' => $oldRoute->brt_from_city_id, 'btype' => $model->bkg_booking_type], false, false);
                if ($model->bkg_booking_type <> 3) {
                    break;
                }
                $oldRoute = $brtRoute;
            }
            ?>
            <span id='insertBefore'></span> 
        </div>
    </div>
    <?
    if ($model->bkg_booking_type == 3) {
        ?>
        <div class="row float-right clsMulti" style="white-space: nowrap">
            <div class="col-xs-12 pr0">
                <a class="btn btn-primary addmoreField weight400 font-bold btn-sm" id="fieldAfter" title="Add More">
                    Add route</a>
                <a class="btn btn-danger btn-sm" id="fieldBefore" title="Remove" style="display: none">Remove last route</a>
            </div>
        </div>
    <? } ?>
    <div class="col-xs-12 text-center mt10 mb10" >
        <?= CHtml::submitButton('NEXT', array('class' => 'btn btn-success btn-lg pl40 pr40')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>


<script>
    count = $("INPUT.ctyDrop").length;

    $(document).ready(function ()
    {
        callbackLogin = 'fillUserform';
        var len = $("INPUT.ctyPickup").length;
        if (len > 1)
        {
            setTimeout(function () {
                disableRows();
                enableRows();
            }, 200);
        }

        $airportRadius = '<?= $cityRadius ?>';

        $('#<?= CHtml::activeId($model, "brt_pickup_date_time") ?>').val('<?= date('h:i A', strtotime('+4 hour')) ?>');
        //     populateData();
    });
    $('.nav-tabs a[href="#menu1"] span[id="btype"]').text('<?= Booking::model()->getBookingType($model->bkg_booking_type) ?> TRIP');
    $('.nav-tabs li[id="l11"] a[href="#menu1"] span[id="btype"]').html('<i class="<?= Booking::model()->getBookingTypeicon($model->bkg_booking_type) ?>"></i>');


    function populateDatarut()
    {


        $scity = $($("INPUT.ctyPickup")[count - 1]).val();
        $tcity = $($("INPUT.ctyDrop")[count - 1]);
        $tcity.select2('val', '').trigger("change");
        if ($scity !== "")
        {

            $.ajax({
                "type": "GET",
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getnearest')) ?>",
                "data": {"source": $scity},
                "async": false,
                "success": function (data1)
                {
                    $data2 = data1;

                    var placeholder = $tcity.attr('placeholder');
                    $tcity.select2({data: $data2, placeholder: placeholder, formatNoMatches: function (term) {
                            return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
                        }});
                }
            });
        }
    }




    $('#fieldAfter').click(function () {


        var elems = $("INPUT.ctyDrop");
        var len = elems.length;
        count = len;

        var scity = $(elems[len - 1]).val();
        var pscity = $($("INPUT.ctyPickup")[len - 1]).val();
        var pdate = $($("INPUT.datePickup")[len - 1]).val();
        var ptime = $($("INPUT.timePickup")[len - 1]).val();
        messages = {};
        if (pdate == "") {
            messages["<?= CHtml::activeId($brtModel, "brt_pickup_date_date") ?>"] = [];
            messages["<?= CHtml::activeId($brtModel, "brt_pickup_date_date") ?>"].push("Please enter pickup date");
        }

        if (pscity == '')
        {
            messages["<?= CHtml::activeId($brtModel, "brt_from_city_id") ?>"] = [];
            messages["<?= CHtml::activeId($brtModel, "brt_from_city_id") ?>"].push("Please select source city");
        }

        if (scity == '')
        {
            messages["<?= CHtml::activeId($brtModel, "brt_to_city_id") ?>"] = [];
            messages["<?= CHtml::activeId($brtModel, "brt_to_city_id") ?>"].push("Please select your destination");
        }
        if (!displayError($("#bookingtime-form"), messages)) {
            return false;
        }

        $.ajax({
            "type": "GET",
            "dataType": "html",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/addroute')) ?>",
            "data": {"scity": scity, "pscity": pscity, "pdate": pdate, "ptime": ptime},
            "async": false,
            "success": function (data1)
            {

                $('#fieldBefore').show();
                $("INPUT.ctyPickup").select2('readonly', true);
                $("INPUT.ctyDrop").select2('readonly', true);
                $("INPUT.datePickup").attr('readonly', true);
                $("INPUT.timePickup").attr('readonly', true);
                $("INPUT.datePickup").datepicker("remove");
                $("INPUT.timePickup").next("span").hide();
                $('#insertBefore').before(data1);
                $("INPUT.ctyPickup").select2('readonly', true);

            }
        });
    });

    $('#fieldBefore').click(function () {
        var elems = $("INPUT.ctyDrop");
        var len = elems.length;
        $($(".clsRoute")[len - 1]).remove();
        enableRows();
    });

    function disableRow(i) {
        $($("INPUT.ctyPickup")[i]).select2('readonly', true);
        $($("INPUT.ctyDrop")[i]).select2('readonly', true);
        $($("INPUT.datePickup")[i]).attr('readonly', true);
        $($("INPUT.timePickup")[i]).attr('readonly', true);
        $($("INPUT.datePickup")[i]).datepicker("remove");
        $($("INPUT.timePickup")[i]).next("span").hide();
    }

    function disableRows() {
        var elems = $("INPUT.ctyDrop");
        var len = elems.length;
        if (len > 1)
        {
            for (var i = 0; i < len - 1; i++)
            {
                disableRow(i);
            }
            $($("INPUT.ctyPickup")[len - 1]).select2('readonly', true);
            $('#fieldBefore').show();
        } else
        {

        }
    }
    function enableRow(i) {
        $($("INPUT.ctyDrop")[i]).select2('readonly', false);
        $($("INPUT.datePickup")[i]).attr('readonly', false);
        $($("INPUT.timePickup")[i]).attr('readonly', false);
        $($("INPUT.datePickup")[i]).datepicker(
                {'autoclose': true, 'startDate': $($("INPUT.datePickup")[i]).attr("min"), 'format': 'dd/mm/yyyy', 'language': 'en'}
        );
        $($("INPUT.timePickup")[i]).next("span").show();
    }

    function enableRows() {
        var elems = $("INPUT.ctyDrop");
        var len = elems.length;
        if (len > 1)
        {
            enableRow(len - 1);
            $($("INPUT.ctyPickup")[len - 1]).select2('readonly', true);
        } else {
            $("INPUT.ctyPickup").select2('readonly', false);
            $("INPUT.ctyDrop").select2('readonly', false);
            $("INPUT.datePickup").attr('readonly', false);
            $("INPUT.timePickup").attr('readonly', false);
            $("INPUT.timePickup").next("span").show();
            var min = new Date($("INPUT.datePickup").attr('min'));
            $("INPUT.datePickup").datepicker(
                    {'autoclose': true, 'startDate': min, 'format': 'dd/mm/yyyy', 'language': 'en'}
            );
            $('#fieldBefore').hide();
            return false;
        }
    }
    function displayError(form, messages) {
        settings = form.data('settings');
        content = "";
        for (var key in messages) {
            $.each(messages[key], function (j, message) {
                content = content + '<li>' + message + '</li>';
            });
        }
        $('#' + settings.summaryID).toggle(content !== '').find('ul').html(content);
        return (content == "");
    }
//////////
    function resetTransferSelects() {

        // $("INPUT.ctyPickup").select2('val', '').trigger("change");
        $($("INPUT.ctyPickup")[count - 1]).select2('val', '').trigger("change");
        $($("INPUT.ctyDrop")[count - 1]).select2('val', '').trigger("change");
        $($("INPUT.ctyPickup")[count - 1]).val('').change();
        $($("INPUT.ctyDrop")[count - 1]).val('').change();

        $($("INPUT.ctyPickup")[count - 1]).select2({'data': [], formatNoMatches: function (term) {
                return "Please, first choose your destination airport.<br><i>If you need any help, please call now</i> (+91) 90518-77-000";
            }}, null, false);
        $($("INPUT.ctyDrop")[count - 1]).select2({'data': [], formatNoMatches: function (term) {
                return "Please, first choose your pickup airport.<br><i>If you need any help, please call now</i> (+91) 90518-77-000";
            }}, null, false);


    }

    $('#<?= CHtml::activeId($model, "bkg_transfer_type_0") ?>').click(function () {

        resetTransferSelects();
        if ($('#<?= CHtml::activeId($model, "bkg_transfer_type_0") ?>').is(':checked')) {
            transferOpt1();
            // populateDataTrP();

        }
    });
    $('#<?= CHtml::activeId($model, "bkg_transfer_type_1") ?>').click(function () {

        resetTransferSelects();
        if ($('#<?= CHtml::activeId($model, "bkg_transfer_type_1") ?>').is(':checked')) {
            transferOpt2();
            //populateDataTrD();
        }
    });
    function populateDataTrP() {

        $scity = $($("INPUT.ctyPickup")[count - 1]);
        $tcity = $($("INPUT.ctyDrop")[count - 1]);

        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>",
            "success": function (data1)
            {
                $data2 = data1;
                var placeholder = $scity.attr('placeholder');
                $scity.select2({data: $data2, placeholder: placeholder, formatNoMatches: function (term) {
                        return "Can't find the source?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
                    }}).on('change', function (e)
                {
                    populateDataTrOthersF();
                });

            }
        });
    }

    function populateDataTrD() {

        $scity = $($("INPUT.ctyPickup")[count - 1]);
        $tcity = $($("INPUT.ctyDrop")[count - 1]);

        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>",
            "success": function (data1)
            {
                $data2 = data1;
                var placeholder = $tcity.attr('placeholder');
                $tcity.select2({data: $data2,
                    placeholder: placeholder,
                    formatNoMatches: function (term) {
                        return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
                    }}).on('change', function (e) {
                    populateDataTrOthersT();
                });
            }
        });
    }
    function populateDataTrOthersF()
    {

        var $scityVal = $($("INPUT.ctyPickup")[count - 1]).val();
        var $fcityVal = $('<?= $model->bkg_from_city_id ?>');
        var $tcity = $($("INPUT.ctyDrop")[count - 1]);



        //  $tcity.select2('val', '').trigger("change");
        if (($scityVal > 0 || $fcityVal > 0) && $('#<?= CHtml::activeId($model, "bkg_transfer_type_0") ?>').is(':checked'))
        {
            $scityVal = ($scityVal > 0) ? $scityVal : $fcityVal;
            $.ajax({
                "type": "GET",
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportnearest')) ?>",
                "data": {"source": $scityVal},
                "async": false,
                "success": function (data1)
                {
                    $data2 = data1;
                    var placeholder = $tcity.attr('placeholder');
                    $tcity.select2({data: $data2, placeholder: placeholder, formatNoMatches: function (term) {
                            return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
                        }});
                }
            });
        }
    }


    function populateDataTrOthersT()
    {


        var $scity = $("INPUT.ctyPickup");
        var $dcityVal = $($("INPUT.ctyDrop")[count - 1]).val();
        var $tcityVal = $('<?= $model->bkg_to_city_id ?>');
        // $scity.select2('val', '').trigger("change");
        if (($tcityVal > 0 || $dcityVal > 0) && $('#<?= CHtml::activeId($model, "bkg_transfer_type_1") ?>').is(':checked'))
        {

            $tcityVal = ($dcityVal > 0) ? $dcityVal : $tcityVal;

            $.ajax({
                "type": "GET",
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportnearest')) ?>",
                "data": {"source": $tcityVal},
                "async": false,
                "success": function (data1)
                {
                    $data2 = data1;
                    var placeholder = $scity.attr('placeholder');
                    $scity.select2({data: $data2, placeholder: placeholder, formatNoMatches: function (term) {
                            return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
                        }});
                }
            });
        }
    }



    function transferFunctions() {
        if ('<?= $model->bkg_transfer_type ?>' > 0) {
            if ('<?= $model->bkg_transfer_type ?>' == 1) {
                alert('t1');
            }
            if ('<?= $model->bkg_transfer_type ?>' == 2) {
                alert('t2');
            }
        } else {
            alert('other');
            $scity = $("INPUT.ctyPickup");
            $tcity = $($("INPUT.ctyDrop")[count - 1]);
            toCity = [];
            var placeholder1 = $scity.attr('placeholder');
            var placeholder2 = $tcity.attr('placeholder');
            $scity.select2({data: function () {
                    return {results: toCity};
                }, placeholder: placeholder1, formatNoMatches: function (term) {
                    return "Can't find the Source?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
                }});
            $tcity.select2({data: [], placeholder: placeholder2, formatNoMatches: function (term) {
                    return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
                }});
        }
    }
    function populateDataEmpty() {

        $scity = $($("INPUT.ctyPickup")[count - 1]);

        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getempty')) ?>",
            "success": function (data1)
            {
                $data2 = data1;
                var placeholder = $tcity.attr('placeholder');
                $scity.select2({data: $data2, placeholder: placeholder, formatNoMatches: function (term) {
                        return "Can't find the location?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
                    }});

            }
        });
    }


    function transferOpt1() {
        var pTrcity = $($("INPUT.ctyPickup")[count - 1]).val();
        var dTrcity = $($("INPUT.ctyDrop")[count - 1]).val();

        changeLabelText('1');
        if (pTrcity > 0) {
            setTimeout(function () {
                resetTransferSelects();
                populateDataTrP();
                setTimeout(function () {
                    $($("INPUT.ctyPickup")[count - 1]).val(pTrcity).trigger('change');
                    if (dTrcity > 0) {
                        $($("INPUT.ctyDrop")[count - 1]).val(dTrcity).trigger('change');
                    }

                }, 400);

            }, 400);

        } else {
            setTimeout(function () {
                resetTransferSelects();
                populateDataTrP();
                $("#dlabel11").text('Pickup Airport');
            }, 300);

        }
    }
    function transferOpt2() {
        if ($($("INPUT.ctyDrop")[count - 1]).val() != '' || '<?= $model->bkg_to_city_id ?>' != '') {
            var toCity = '<?= $model->bkg_to_city_id ?>';
            var fromCity = $($("INPUT.ctyPickup")[count - 1]).val();

            changeLabelText('2');
            setTimeout(function () {
                resetTransferSelects();
                populateDataTrD();
                setTimeout(function () {
                    $($("INPUT.ctyDrop")[count - 1]).val(toCity).trigger('change');
                    if (fromCity != '') {
                        $($("INPUT.ctyPickup")[count - 1]).val(fromCity).trigger('change');
                    }
                }, 400);
            }, 400);

        } else {

            setTimeout(function () {
                resetTransferSelects();
                populateDataTrD();
            }, 300);

        }
    }


    if ('<?= $model->bkg_booking_type ?>' == 4) {

        if ('<?= $model->bkg_transfer_type ?>' > 0) {


            if ('<?= $model->bkg_transfer_type ?>' == 1) {
                transferOpt1();

            }
            if ('<?= $model->bkg_transfer_type ?>' == 2) {
                transferOpt2();
            }
        } else {
            $('#<?= CHtml::activeId($model, "bkg_transfer_type_0") ?>').attr('checked', 'checked');
            transferOpt1();
//            setTimeout(function () {
//                resetTransferSelects();
//                $($("INPUT.ctyPickup")[count - 1]).select2({'data': [], formatNoMatches: function (term) {
//                        return "Please choose your transfer type to proceed.<br><i>For help, Call now</i> (+91) 90518-77-000";
//                    }}, null, false);
//                $($("INPUT.ctyDrop")[count - 1]).select2({'data': [], formatNoMatches: function (term) {
//                        return "Please choose your transfer type to proceed.<br><i>For help, Call now</i> (+91) 90518-77-000";
//                    }}, null, false);
//            }, 500);
        }
    } else {
        $($("INPUT.ctyPickup")[count - 1]).change(function () {
            populateDatarut();
        });

    }
    function changeLabelTextobj(trobj) {
        var trval = trobj.value;
        changeLabelText(trval);
    }
    function changeLabelText(trvalue) {
        if (trvalue == '0') {
            $('#trslabel').text('Pickup Point');
            $('#trdlabel').text('Drop Point');
        }
        if (trvalue == '1') {
            $('#trslabel').text('Pickup Airport');
            $('#trdlabel').text('Drop Point');
        }
        if (trvalue == '2') {
            $('#trslabel').text('Pickup Point');
            $('#trdlabel').text('Destination Airport');
        }
    }

</script>