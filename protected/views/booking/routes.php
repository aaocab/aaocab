<script type="text/javascript">

    $(document).ready(function () {
        $("#tnc").attr('checked', 'checked');


    });

    function mapInitialize() {
        var map;
        var directionsDisplay = new google.maps.DirectionsRenderer();
        var directionsService = new google.maps.DirectionsService();
        var mapOptions = {
            zoom: 6,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: new google.maps.LatLng(30.73331, 76.77942),
            mapTypeControl: false
        }
        map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        directionsDisplay.setMap(map);
        $('#map_canvas').css('height', $('#desc').height());
        var start = '<?= $fcitystate ?>';
        var end = '<?= $tcitystate ?>';
        var request = {
            origin: start,
            destination: end,
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };
        directionsService.route(request, function (response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
                var leg = response.routes[0].legs[0];


                // $('#<? //= CHtml::activeId($bmodel, "bkg_trip_distance")          ?>').val(Math.ceil(leg.distance.value / 1000));
                //$('#<? //= CHtml::activeId($bmodel, "bkg_trip_duration")          ?>').val(Math.ceil(leg.duration.value / 60));
            }
        });
    }

    function loadScript() {
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' +
                'callback=mapInitialize';
        document.body.appendChild(script);
    }
    window.onload = loadScript;
</script>
<?
//= $this->renderPartial('topSearch') 
$cabtype = VehicleTypes::model()->getCarType();
$rtArr = explode('-', $route);

Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
?>


<div class="row mb20">
    <div class="col-xs-12 col-sm-3 col-md-3 mb15">
        <p class="m0 weight400"><b>Estimated Distance</b>: <?= $bmodel->bkg_trip_distance . " Km" ?></p>
        <p class="m0 weight400"><b>Estimated Time</b>: <?
            $hr = date('G', mktime(0, $bmodel->bkg_trip_duration)) . " Hr";
            $min = (date('i', mktime(0, $bmodel->bkg_trip_duration)) != '00') ? ' ' . date('i', mktime(0, $bmodel->bkg_trip_duration)) . " min" : '';
            echo $hr . $min;
            ?>
        </p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6">
        <h3 class="weight400 m0 mb5 text-center "><?= $bmodel->bkgFromCity->cty_name . ' to ' . $bmodel->bkgToCity->cty_name ?> One way cab</h3>

    </div>
    <div class="col-xs-12 col-sm-8 col-md-8">
        <?php
        $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id' => 'booking1-form', 'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'errorCssClass' => 'has-error'
            ),
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // See class documentation of CActiveForm for details on this,
            // you need to use the performAjaxValidation()-method described there.
            'enableAjaxValidation' => false,
            'errorMessageCssClass' => 'help-block',
            'action' => Yii::app()->createUrl('booking/routes'),
            'htmlOptions' => array(
                'class' => 'form-inline',
            ),
        ));
        /* @var $form TbActiveForm */
        ?>
        <?= $form->hiddenField($bmodel, "bkg_pickup_date_time1"); ?>
        <?= $form->hiddenField($bmodel, "bkg_pickup_date_date1"); ?>
        <?= $form->hiddenField($bmodel, "bkg_country_code1"); ?>
        <?= $form->hiddenField($bmodel, "bkg_contact_no1"); ?>
        <?= $form->hiddenField($bmodel, "bkg_user_email1"); ?>
        <?= $form->hiddenField($bmodel, "bkg_search1", ['value' => '1']); ?>


        <div class="col-xs-12 col-sm-4 p5" >

            <div class="input-group col-xs-12">

                <? //= $form->dropDownListGroup($model, 'bkg_from_city_id', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control border-none border-radius'], 'data' => $fcityList))) ?>

                <?php
                $datacity = Cities::model()->getJSONRateCities();
                $this->widget('booster.widgets.TbSelect2', array(
                    'model' => $bmodel,
                    'attribute' => 'bkg_from_city_id',
                    'val' => $bmodel->bkg_from_city_id,
                    'asDropDownList' => FALSE,
                    'options' => array('data' => new CJavaScriptExpression($datacity),
                        'dropdownCssClass' => 'cityList', 'formatNoMatches' => "js:function(term){return \"Can't find the source?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000\"}"),
                    'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Source',)
                ));
                ?>

                <span class="has-error"><? echo $form->error($bmodel, 'bkg_from_city_id'); ?></span>
            </div>
        </div>

        <div class="col-xs-12 col-sm-1 mt5 p5 text-center">
            <label class="control-label ">To</label>
        </div>
        <div class="col-xs-12 col-sm-4 p5">

            <div class="input-group col-xs-12">
                <?php
                $this->widget('booster.widgets.TbSelect2', array(
                    'model' => $bmodel,
                    'attribute' => 'bkg_to_city_id',
                    'val' => $bmodel->bkg_to_city_id,
                    'asDropDownList' => FALSE,
                    'options' => array('data' => new CJavaScriptExpression(Cities::model()->getJSONRateDestinationCities($bmodel->bkg_from_city_id)),
                        'formatNoMatches' => "js:function(term){return \"Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000\"}"
                    ),
                    'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Destination')
                ));
                ?>
                <span class="has-error"><? echo $form->error($bmodel, 'bkg_to_city_id'); ?></span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-3 p5">
            <div class="input-group col-xs-offset-4 col-sm-offset-1 col-xs-4 col-sm-12 text-center">
                <button type="submit" onclick="skipPopup()" class="btn btn-primary orange-bg border-none border-radius col-xs-12">Search</button>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'cabrate-form', 'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'errorCssClass' => 'has-error',
        'afterValidate' => 'js:function(form,data,hasError){
                    if(!hasError){
                        return true
                    }
                    if(hasError){
                        var myElement = document.querySelector("#error-border");
                        myElement.style.border = "2px solid #a94442";
                    }
                    }'
    ),
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
    'errorMessageCssClass' => 'help-block',
    'htmlOptions' => array(
        'noValidate' => 'novalidate',
        'class' => 'form-horizontal',
    ),
        ));
/* @var $form TbActiveForm */
?>
<div class="panel">            
    <div class="panel-body pt0 pb0">                   
        <?= $form->hiddenField($bmodel, 'bkg_id'); ?>
        <?= $form->hiddenField($bmodel, "bkg_trip_distance"); ?>
        <?= $form->hiddenField($bmodel, "bkg_trip_duration"); ?>
        <?= $form->hiddenField($bmodel, "bkg_from_city_id", ['value' => $rmodel->rutFromCity->cty_id]); ?>
        <?= $form->hiddenField($bmodel, "bkg_to_city_id", ['value' => $rmodel->rutToCity->cty_id]); ?>
        <div id="error-border" style="<?= (CHtml::errorSummary($bmodel) != '') ? "border:2px solid #a94442" : "" ?>" class="m10 p10">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-offset-1 col-lg-offset-1 col-md-10 col-lg-10 ml0">
                    <h5 >If there are any issues with your booking we will contact you. Please share your phone and email address below.</h5>
                </div> 
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="col-xs-12 col-sm-6 col-lg-3 pl0">
                        <label class="control-label">Primary Contact Number </label>
                        <div class="form-group">   
                            <div class="col-xs-3 isd-input">
                                <?php
                                $this->widget('ext.yii-selectize.YiiSelectize', array(
                                    'model' => $bmodel,
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
                                        obj.setValue('{$bmodel->bkg_country_code}');
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
                            <div class="col-xs-9 pl0 pr0 pl15" id="error-show">
                                <?= $form->textField($bmodel, 'bkg_contact_no', array('placeholder' => "Primary Mobile Number", 'class' => 'form-control')) ?>
                                <?= $form->error($bmodel, 'bkg_country_code'); ?>
                                <?= $form->error($bmodel, 'bkg_contact_no'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-lg-3 pl0 pr0">      
                        <?= $form->emailFieldGroup($bmodel, 'bkg_user_email', array('label' => 'Email Address', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email Address"]), 'groupOptions' => ['class' => 'm0'])) ?>                      
                    </div>                

                    <div class="col-xs-12 col-sm-6  col-lg-3">
                        <?
//$strpickdate = date('Y-m-d H:i:s', strtotime('+4 hour')); 
                        $strpickdate = ($bmodel->bkg_pickup_date == '') ? date('Y-m-d H:i:s', strtotime('+4 hour')) : $bmodel->bkg_pickup_date;
                        ?>
                        <?=
                        $form->datePickerGroup($bmodel, 'bkg_pickup_date_date', array('label' => 'Choose Date',
                            'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date('Y-m-d H:i:s', strtotime('+4 hour')),
                                    'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'placeholder' => 'Pickup Date',
                                    'class' => 'input-group border-gray full-width')),
                            'prepend' => '<i class="fa fa-calendar"></i>'));
                        ?>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-lg-3">
                        <?=
                        $form->timePickerGroup($bmodel, 'bkg_pickup_date_time', array('label' => 'Choose Time',
                            'widgetOptions' => array('options' => array('defaultTime' => false, 'autoclose' => true),
                                'htmlOptions' => array('required' => true, 'placeholder' => 'Pickup Time',
                                    //'id'=> CHtml::activeId($bmodel, "bkg_pickup_date_time"),
                                    'class' => 'bootstrap-timepicker input-group border-gray full-width'))));
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 summary-div border-none">
                    <div class="checkbox ml20">
                        <?= $form->checkboxGroup($bmodel, 'bkg_tnc', ['label' => 'I Agree to the <a href="#" onclick="opentns()" >Terms and Conditions</a>']) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row m10">
            <?
            foreach ($cabratedata as $key => $val) {
                ?>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="thumbnail p10 border-radius">
                        <figure><img src="<?= Yii::app()->baseUrl . '/' . $val->svcClassVhcCat->scc_VehicleCategory->vct_image ?>" alt="Car Image" class="border-black"></figure>
                        <div class="caption pl0 pr0">
                            <h4 class="text-center"><?= strtoupper($val->svcClassVhcCat->scc_VehicleCategory->vct_label) ?></h4>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-6">Capacity</div>
                                        <div class="col-xs-6 text-right"><?= $val->svcClassVhcCat->scc_VehicleCategory->vct_capacity ?> +1</div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <hr class="mt5 mb5">
                                    <div class="row">
                                        <div class="col-xs-8">Driver Allowance</div>
                                        <div class="col-xs-4 text-right">Included</div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <hr class="mt5 mb5">
                                    <div class="row">
                                        <div class="col-xs-6">Service-Tax</div>
                                        <div class="col-xs-6 text-right">Included</div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <hr class="mt5 mb5">
                                    <div class="row">
                                        <div class="col-xs-6">Toll-Tax</div>
                                        <div class="col-xs-6 text-right">Included</div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <hr class="mt5 mb5">
                                    <div class="row">
                                        <div class="col-xs-6">State-Tax</div>
                                        <div class="col-xs-6 text-right">Included</div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <hr class="mt5 mb5">
                                    <div class="row">
                                        <div class="col-xs-4">Parking</div>
                                        <div class="col-xs-8 text-right">Extra (if applicable)</div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <hr class="mt5 mb5">
                                    <div class="row">
                                        <div class="col-xs-5" style="height: 50px;">Car Model</div>
                                        <div class="col-xs-7 text-right" style="height: 50px;"><?= $val->svcClassVhcCat->scc_VehicleCategory->vct_label . ' ' . $val->svcClassVhcCat->scc_VehicleCategory->vct_desc ?></div>
                                    </div>
                                </div>
                                <div class="col-xs-12">                                    
                                    <hr class="mt5 mb5">
                                    <div class="row">
                                        <div class="col-xs-6">Price</div><span name="rtBase" vht="<?= $val->svcClassVhcCat->scv_id ?>" rate="<?= $val->rte_amount ?>"></span>
                                        <div class="col-xs-6 text-right"><h3><i class="fa fa-rupee blue2-color"></i> <span class="orange-color" id="rt-<?= $val->svcClassVhcCat->scv_id ?>"><?= $val->rte_amount * $hike; ?></span></h3></div>
                                    </div>
                                </div>
                                <div class="col-xs-12"> 
                                    <button type="submit" value="<?= $val->svcClassVhcCat->scv_id ?>" name="<?= CHtml::activeName($bmodel, "bkg_vehicle_type_id") ?>" class="btn btn-primary full-width" onclick="skipPopup()">Book</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <? } ?>
        </div>
        <?
        if ($rmodel->rut_special_remarks != '') {
            ?>
            <div class="row m10">
                <div class="col-xs-12 mb10">
                    <div class="list-group-item list-group-item-info">
                        <b><?= $rmodel->rut_special_remarks ?></b>
                    </div>
                </div>
            </div>
        <? } ?>
    </div> 
</div>
<?php $this->endWidget(); ?>

<section id="section2">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <h4>Pickup or Drop anywhere in <?= $rmodel->rutFromCity->cty_name ?></h4>
                <div class="span3 feature">
                    <?= $rmodel->rutFromCity->cty_pickup_drop_info ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3">
                <h4>Other Parts of NCR?</h4>
                <div class="span3 feature">
                    <?= $rmodel->rutFromCity->cty_ncr ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3">
                <h4>Pickup or Drop anywhere in <?= $rmodel->rutToCity->cty_name ?></h4>
                <div class="span3 feature">
                    <?= $rmodel->rutToCity->cty_pickup_drop_info ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3">
                <h4>Discount for Return Trip</h4>
                <div class="span3 feature">
                    Get a flat &#x20B9;  200/- discount for return transfer with the same vehicle and the same way.
                </div>
            </div>
        </div>
    </div>
    <div class="container newline mt20">
        <div class="row">
            <div id="desc" class="col-xs-12 col-sm-7 col-md-8 feature">Distance: <b><?= $bmodel->bkg_trip_distance . " Km" ?></b>
                <br/>Book <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi for One Way Drop at very affordable rates. For <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> trip, Cabs available from all parts of <?= $rmodel->rutFromCity->cty_name ?>, <?= $rmodel->rutFromCity->cty_pickup_drop_info ?>. 
                Worried about your extra luggage? Just inform us and we will arrange for a cab with a carrier for your luggage. 
                Now book a confirmed One-way cab from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> online in four easy steps.<br><br><br>
                <b>About <?= $rmodel->rutFromCity->cty_name ?> : </b><?= $rmodel->rutFromCity->cty_city_desc ?><br><br><br>
            </div>
            <div class="col-xs-12 col-sm-5 col-md-4 offset1">
                <div  id="map_canvas" style="height: 350px;"></div>
            </div>

            <div id="desc1" class="col-xs-12 feature">
                <br><br><b><?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> : </b><?= $rmodel->rutToCity->cty_city_desc ?>
            </div>
        </div>
    </div>
</section>
<?php
$this->renderPartial('popupform', ['model' => $model]);
?>
<script>

    function returnRoute() {
        // skipPopup();
        var pickdate = $("#<?= CHtml::activeId($bmodel, 'bkg_pickup_date_date') ?>").val();
        var picktime = $("#<?= CHtml::activeId($bmodel, 'bkg_pickup_time') ?>").val();
        $href = "<?= Yii::app()->createUrl($rtArr[1] . '-' . $rtArr[0], array('pickdate' => date('YmdHis', strtotime($bmodel->bkg_pickup_date)))) ?>";
        window.open($href, '_parent');
    }

    function modifyRoute() {
        //  skipPopup();
        $href = '<?= Yii::app()->createUrl('booking/modifyroute') ?>';
        var fcity = $("#<?= CHtml::activeId($bmodel, 'bkg_from_city_id') ?>").val();
        var tcity = $("#<?= CHtml::activeId($bmodel, 'bkg_to_city_id') ?>").val();

        jQuery.ajax({type: 'POST',
            data: {"fcity": fcity, "tcity": tcity}, url: $href,
            success: function (data) {
                bookBox = bootbox.dialog({
                    message: data,
                    title: 'Modify Route',
                    onEscape: function () {
                        // user pressed escape
                    }
                });
                bookBox.on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                    $(this).data('bs.modal', null);
                });

            }});
//        var pickdate = $("#<?= CHtml::activeId($bmodel, 'bkg_pickup_date_date') ?>").val();
//        var dparts = pickdate.split("");
//        pdate = new Date(dparts[2], dparts[1] - 1, dparts[0]);
//
//        var picktime = $("#<?= CHtml::activeId($bmodel, 'bkg_pickup_time') ?>").val();
//        $href = "<?= Yii::app()->createUrl('index/index', array('rut_id' => $rmodel->rut_id)) ?>";
//        window.open($href + '/pdate/' + pdate + '/ptime/' + picktime, '_parent');
    }

    function opentns() {


        $href = '<?= Yii::app()->createUrl('index/tns') ?>';
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    onEscape: function () {
                        box.modal('hide');
                    }
                });
            }
        });
    }

    $('#ask_customer').change(function () {
        if ($("#ask_customer").is(':checked'))
        {
            $href = '<?= Yii::app()->createUrl('booking/cabratepartial') ?>';
            jQuery.ajax({type: 'POST', url: $href,
                success: function (data) {
                    box = bootbox.dialog({
                        message: data,
                        title: '',
                        onEscape: function () {
                            box.modal('hide');
                        }
                    });
                }
            });
        }
    });
    function getRouteName() {

        fcity = $('#<?= CHtml::activeId($bmodel, "bkg_from_city_id") ?>').val();
        tcity = $('#<?= CHtml::activeId($bmodel, "bkg_to_city_id") ?>').val();

        if (fcity != '' && tcity != '') {
            $.ajax({
                "type": "GET",
                "dataType": "json",
                async: false,
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/getroutename')) ?>",
                "data": {"fcity": fcity, 'tcity': tcity},
                success: function (data1)
                {
                    if (data1.rutname) {
                        document.getElementById('booking1-form').action = "/" + data1.rutname;
                    }

                }
            });
        }
    }
    $('#<?= CHtml::activeId($bmodel, "bkg_from_city_id") ?>').change(function () {
        alert('called in view routes');
        populateData();
    });

    $('#<?= CHtml::activeId($bmodel, "bkg_pickup_date_date") ?>').change(function () {

        $elems = $('span[name=rtBase]');
        $pdate = $('#<?= CHtml::activeId($bmodel, "bkg_pickup_date_date") ?>').val();
        $fcity = $('#<?= CHtml::activeId($bmodel, "bkg_from_city_id") ?>').val();
        $tcity = $('#<?= CHtml::activeId($bmodel, "bkg_to_city_id") ?>').val();
        $hike = 1;
        if ($pdate == '13/08/2016' || $pdate == '14/08/2016' || $pdate == '15/08/2016' || $pdate == '16/08/2016' || $pdate == '17/08/2016') {
            $hike = 1.2;
        }


        if ($pdate == "10/09/2016" || $pdate == "11/09/2016")
        {
            if (($fcity == "30366" || $fcity == "30407" || $fcity == "30921") && ($tcity == "30883" || $tcity == "30832" || $tcity == "30938" || $tcity == "30939"))
            {
                $hike = 1.13;
            }

        }

        var elem;
        var vht;
        var rate;
        var rtRate;
        for (var k = 0; k < $elems.length; k++)
        {
            elem = $elems[k];
            rate = elem.getAttribute("rate");
            vht = elem.getAttribute("vht");

            rtRate = Math.round(rate * $hike);

            $('#rt-' + vht).text(rtRate);

        }
    });


    function populateDataxxx()
    {

        $scity = $('#<?= CHtml::activeId($bmodel, "bkg_from_city_id") ?>').val();

        if ($scity !== "")
        {

            $.ajax({
                "type": "GET",
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getdestination')) ?>",
                "data": {"source": $scity},
                "async": false,
                "success": function (data1)
                {
                    $data2 = data1;

                    var placeholder = $('#<?= CHtml::activeId($bmodel, "bkg_to_city_id") ?>').attr('placeholder');
                    $('#<?= CHtml::activeId($bmodel, "bkg_to_city_id") ?>').select2({data: $data2, placeholder: placeholder, formatNoMatches: function (term) {
                            return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
                        }});

                }
            });
        }
    }
    $(function () {
        $('#booking1-form').submit(function (event) {
            skipPopup();
            fcity = $('#<?= CHtml::activeId($bmodel, "bkg_from_city_id") ?>').val();
            tcity = $('#<?= CHtml::activeId($bmodel, "bkg_to_city_id") ?>').val();


            $('#<?= CHtml::activeId($bmodel, "bkg_pickup_date_time1") ?>').val($('#yw3').val());
            $('#<?= CHtml::activeId($bmodel, "bkg_pickup_date_date1") ?>').val($('#<?= CHtml::activeId($bmodel, "bkg_pickup_date_date") ?>').val());
            $('#<?= CHtml::activeId($bmodel, "bkg_country_code1") ?>').val($('#<?= CHtml::activeId($bmodel, "bkg_country_code") ?>').val());
            $('#<?= CHtml::activeId($bmodel, "bkg_contact_no1") ?>').val($('#<?= CHtml::activeId($bmodel, "bkg_contact_no") ?>').val());
            $('#<?= CHtml::activeId($bmodel, "bkg_user_email1") ?>').val($('#<?= CHtml::activeId($bmodel, "bkg_user_email") ?>').val());


            if (fcity != '' || tcity != '') {
                if (fcity == "")
                {
                    $('#<?= CHtml::activeId($bmodel, "bkg_from_city_id_em_") ?>').show();
                    $('#from_city').addClass('has-error');
                    error = true;
                    event.preventDefault();
                } else
                {
                    $('#<?= CHtml::activeId($bmodel, "bkg_from_city_id_em_") ?>').hide();
                    $('#from_city').removeClass('has-error');
                }

                if (tcity == "")
                {
                    $('#<?= CHtml::activeId($bmodel, "to_city_id_em_") ?>').show();
                    $('#to_city').addClass('has-error');
                    error = true;
                    event.preventDefault();
                } else
                {
                    $('#<?= CHtml::activeId($bmodel, "to_city_id_em_") ?>').hide();
                    $('#to_city').removeClass('has-error');
                }
                getRouteName();
            }
        });
    });







</script>
