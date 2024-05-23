<?php
//Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');

$cityRadius = Yii::app()->params['airportCityRadius'];
?>

<?php



 
//Yii::app()->clientScript->registerPackage("jqueryV3");




$cityRadius = Yii::app()->params['airportCityRadius'];
 $brtRoutes = $model->bookingRoutes;
            /* @var $model Booking */

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
            <? if ($model->bkg_booking_type == 4) { ?>
                <div class="row m0">
                    <div class="col-xs-12 col-lg-6 col-lg-offset-3">
                        <div class="form-group">
                            <label class="control-label" for="BookingTemp_bkg_transfer_type">Transfer Type</label>
                            <input id="ytBookingTemp_bkg_transfer_type" type="hidden" value="" name="BookingTemp[bkg_transfer_type]">
                            <span id="BookingTemp_bkg_transfer_type">
                                <label class="checkbox-inline">
                                    <input onclick="changeLabelTextobj(this)" onchange="changeLabelTextobj(this)" placeholder="Transfer Type" id="BookingTemp_bkg_transfer_type_0" value="1" type="radio" name="BookingTemp[bkg_transfer_type]" checked="checked">
                                    Airport Pick Up
                                </label>
                                <label class="checkbox-inline">
                                    <input onclick="changeLabelTextobj(this)" onchange="changeLabelTextobj(this)" placeholder="Transfer Type" id="BookingTemp_bkg_transfer_type_1" value="2" type="radio" name="BookingTemp[bkg_transfer_type]">
                                    Airport Drop Off
                                </label>
                            </span>
                            <div class="help-block error" id="BookingTemp_bkg_transfer_type_em_" style="display:none">

                            </div>

                        </div>                    
                    </div>
                </div>
            <? } ?>
    <div class="row">
        <div class="col-xs-12 col-sm-8" style="margin: auto; float: none; position: relative;">
            <? if ($model->bkg_booking_type == 4) { ?>
                <div class="row">

                    <div class="col-xs-12 col-sm-6 " style="margin: auto; float: none;">
                       
<?php

//$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
//	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true,
//	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
//	'addPrecedence'		 => false,];
//$options			 = [];
//$acWidgetId			 = CHtml::activeId($brtModel, 'place') . "_" . rand(100000, 9999999);
//				$this->widget('ext.yii-selectize.YiiSelectize', array(
//					'model'				 => $brtModel,
//					'attribute'			 => 'airport',
//					'useWithBootstrap'	 => true,
//					"placeholder"		 => "Select Airport",
//					'fullWidth'			 => true,
//					'htmlOptions'		 => array('width' => '50%'
//					),
//					'defaultOptions'	 => $selectizeOptions + array(
//				'onInitialize'	 => "js:function(){
//													populateAirportList(this, '{$brtModel->airport}');
//												}",
//				'load'			 => "js:function(query, callback){
//													loadAirportSource(query, callback);
//												}",
//				'onChange'		 => "js:function(value) {
//										setAddressCity('{$acWidgetId}',value);
//											}",
//				'render'		 => "js:{
//														option: function(item, escape){
//														return '<div><span class=\"\"><img src=\"/images/bxs-map.svg\" alt=\"img\" width=\"22\" height=\"22\">' + escape(item.text) +'</span></div>';
//														},
//														option_create: function(data, escape){
//														return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
//													   }
//													}",
//					),
//				));
				?>


                    </div>
  <div class="col-xs-12 col-sm-6 " style="margin: auto; float: none;">
<?php
//$this->widget('application.widgets.SelectAddress', array(
//		'model'			 => $brtModel,
//		"htmlOptions"	 => ["class" => "border border-light  p10 text-left selectAddress item", "id" => $acWidgetId],
//		'attribute'		 => "place",
//		'widgetId'		 => $acWidgetId,
//		'isAirport'		 => true,
//		"city"			 => "{$brtModel->airport}",
//		"modalId"		 => "addressModal",
//		'viewUrl'		 => '/agent/booking/selectAddress'
//	));
				?>
                </div>
</div>
            <? } ?>

        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php
        

            $scity = '';
            $pcity = '';
            foreach ($brtRoutes as $brtRoute) {


                if ($oldRoute == null) {
                    $oldRoute = BookingRoute::model();
                }

                $brtRoute->populateMinDate($oldRoute->brt_from_city_id, $oldRoute->brt_pickup_date_date, $oldRoute->brt_pickup_date_time);

                $this->renderPartial('addroute', ['model' => $brtRoute, 'sourceCity' => $oldRoute->brt_to_city_id, 'previousCity' => $oldRoute->brt_from_city_id, 'btype' => $model->bkg_booking_type, 'bkgmodel' => $model, 'index' => 0, 'transferType' => $model->bkg_transfer_type], false, false);
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
  

</div>

<script>
    count = $("INPUT.ctyDrop").length;

    $(document).ready(function ()
    {
       
       
        callbackLogin = 'fillUserform';
        var len = $("SELECT.ctyPickup").length;
        if (len > 1)
        {
            setTimeout(function () {
                //disableRows();
                //enableRows();
            }, 200);
        }

        $airportRadius = '<?= $cityRadius ?>';

        $('#<?= CHtml::activeId($model, "brt_pickup_date_time") ?>').val('<?= date('h:i A', strtotime('+4 hour')) ?>');
        //     populateData();
    });
  

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

      
        var elems = $("SELECT.ctyDrop");
        var len = elems.length;
        count = len;

        var scity = $(elems[len - 1]).val();
        var pscity = $($("SELECT.ctyPickup")[len - 1]).val();
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
      //  alert(JSON.stringify(messages));
        
        
        if (!displayError($("#bookingtime-form"), messages)) {
            return false;
        }

        $.ajax({
            "type": "GET",
            "dataType": "html",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('agent/booking/addroute')) ?>",
            "data": {"scity": scity, "pscity": pscity, "pdate": pdate, "ptime": ptime, "index": count},
            "async": false,
            "success": function (data1)
            {

                $('#fieldBefore').show();
                $("SELECT.ctyPickup").attr('readonly', true);
                $("SELECT.ctyDrop").attr('readonly', true);
                $("INPUT.datePickup").attr('readonly', true);
                $("INPUT.timePickup").attr('readonly', true);
                $("INPUT.datePickup").datepicker("remove");
                $("INPUT.timePickup").next("span").hide();
                $('#insertBefore').before(data1);
                $('.mcitytimelabel').html("Time");
                $("SELECT.ctyPickup").attr('readonly', true);
                disableRows();
            }
        });
    });

    $('#fieldBefore').click(function () {
        var elems = $("SELECT.ctyDrop");
        var len = elems.length;
        $($(".clsRoute")[len - 1]).remove();
        enableRows();
    });

    function disableRow(i) {
        $("SELECT.ctyDrop")[i].selectize.lock();
        $($("INPUT.datePickup")[i]).attr('readonly', true);
        $($("INPUT.timePickup")[i]).attr('readonly', true);
        $($("INPUT.datePickup")[i]).datepicker("remove");
        $($("INPUT.timePickup")[i]).next("span").hide();
    }

    function disableRows() {
        var elems = $("SELECT.ctyDrop");
        var len = elems.length;
        if (len > 1)
        {
            $("SELECT.ctyPickup")[0].selectize.lock();
            for (var i = 0; i < len - 1; i++)
            {
                disableRow(i);
            }
           // $("SELECT.ctyPickup")[len - 1].selectize.lock();
            $('#fieldBefore').show();
        }
    }
    function enableRow(i) {
        $("SELECT.ctyDrop")[i].selectize.unlock();
        $($("INPUT.datePickup")[i]).attr('readonly', false);
        $($("INPUT.timePickup")[i]).attr('readonly', false);
        $($("INPUT.datePickup")[i]).datepicker(
                {'autoclose': true, 'startDate': $($("INPUT.datePickup")[i]).attr("min"), 'format': 'dd/mm/yyyy', 'language': 'en'}
        );
        $($("INPUT.timePickup")[i]).next("span").show();
    }

    function enableRows() {
        var elems = $("SELECT.ctyDrop");
        var len = elems.length;
        if (len > 1)
        {
            enableRow(len - 1);
            $("SELECT.ctyPickup")[len - 1].selectize.lock();
        } else {
            $("SELECT.ctyPickup")[len - 1].selectize.unlock();
            $("SELECT.ctyDrop")[len - 1].selectize.unlock();
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
       /// $('#' + settings.summaryID).toggle(content !== '').find('ul').html(content);
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


    if ('<?= $model->bkg_booking_type ?>' == 4)
	 {
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
            $('#trslabel').text('Drop Airport ');
            $('#trdlabel').text('Pickup Point');
        }
    }
    
    function populateSource(obj, cityId) {
        $loadCityId = cityId;
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylist1')) ?>',
                    dataType: 'json',
                    data: {
                        city: cityId
                    },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue($loadCityId);
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue($loadCityId);
            }
        });
    }
    function loadSource(query, callback) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylist1')) ?>?q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',

            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }
    function changeDestination(value, obj) {
        if (!value.length)
            return;
        obj.disable();
        obj.clearOptions();
        obj.load(function (callback) {
            //  xhr && xhr.abort();
            xhr = $.ajax({
                url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/nearestcitylist')) ?>/source/' + value,
                dataType: 'json',
                success: function (results)
                {
                    obj.enable();
                    callback(results);
                },
                error: function () {
                    callback();
                }
            });
        });
    }
function populateAirportList(obj, cityId)
	{
	
		obj.load(function(callback)
		{
			var obj = this;
			if ($sourceList == null)
			{
				xhr = $.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>',
					dataType: 'json',
					data: {
						city: cityId
					},
					//  async: false,
					success: function(results)
					{
						$sourceList = results;
						obj.enable();
						callback($sourceList);
						obj.setValue('<?= $brtRoute->airport ?>');
					},
					error: function()
					{
						callback();
					}
				});
			}
			else
			{
				obj.enable();
				callback($sourceList);
				obj.setValue('<?= $brtRoute->airport ?>');
			}
		});
	}
 function blockForm(form)
        {
            block_ele = form.closest('form');

            $(block_ele).block({
                message: '<div class="loader"></div>',
                overlayCSS: {
                    backgroundColor: "#FFF",
                    opacity: 0.8,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });
        }
  function unBlockForm()
        {
            $(block_ele).unblock();
        }
</script>