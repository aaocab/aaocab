
<style type="text/css">
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }

    .pac-item >.pac-icon-marker{
        display: none !important;
    }
    .pac-item-query{
        padding-left: 3px;
    }
    .checkbox input[type="checkbox"], .checkbox-inline input[type="checkbox"] {
        margin-left: 20px!important;margin-top:10px
    }
</style>
<?
//Yii::app()->clientScript->registerScriptFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDhybncyDc1ddM2qzn453XqYW8ZQDm7RW8&libraries=places&', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask1.min.js');
/* @var $model BookingTemp */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$ccode = Countries::model()->getCodeList();
$additionalAddressInfo = "Building No./ Society Name";
$addressLabel = ($model->bkg_booking_type == 4) ? 'Location' : 'Address';
$infosource = BookingAddInfo::model()->getInfosource('user');
$ulmodel = new Users('login');
$urmodel = new Users('insert');
$locFrom = [];
$locTo = [];
$autocompleteFrom = 'txtpl';
$autocompleteTo = 'txtpl';
$locReadonly = ['readonly' => 'readonly'];
if ($model->bkg_transfer_type == 1) {
    $locFrom = $locReadonly;
    $autocompleteFrom = '';
}
if ($model->bkg_transfer_type == 2) {
    $locTo = $locReadonly;
    $autocompleteTo = '';
}
//$userdiv = 'block';
//if (!Yii::app()->user->isGuest) {
//    $user = Yii::app()->user->loadUser();
$userdiv = 'none';
//} else {
//    $ulmodel->usr_email = $model->bkg_user_email;
//    $urmodel->usr_email = $model->bkg_user_email;
//    $urmodel->usr_mobile = $model->bkg_contact_no;
//    $urmodel->usr_country_code = $model->bkg_country_code;
//    $urmodel->usr_name = $model->bkg_user_name;
//    $urmodel->usr_lname = $model->bkg_user_lname;
//}
/*
  @var $model Booking
 *  */
?>
<div class="" >
    <?
    /* ?>
      <div class="row" id="userdiv" style="display :<?= $userdiv ?>" >
      <div class="col-xs-12 book-panel pb0">
      <div class="panel panel-default border-radius">
      <div class="panel-body" >
      <div class="row">
      <div class="col-xs-12 col-md-8">
      <h4 class="text-uppercase">Log In</h4>
      <?
      $this->renderPartial('partialsignin', ['model' => $ulmodel], false, true);
      ?>
      </div>
      <div class="col-xs-12 col-md-4 p0">
      <div class="col-xs-5 col-sm-6 col-md-12 mb20 text-center mb2Primary Passenger Name0">
      <a class="btn btn-primary text-uppercase font11 pl10 pr10" onclick="callSignupbox()" role="button"><b>Create Account</b></a>
      </div>
      <div class="col-xs-7 col-sm-6 col-md-12 text-center ">
      <a class="btn btn-warning text-uppercase font11 pl10 pr10" onclick="hideDiv()" role="button"><b>Continue guest user</b></a>
      </div>
      </div>
      </div>
      </div>
      </div>
      </div>
      </div>
      <?php
     * 
     */
// $model=  Booking::model()->findByPk(25157);
//   $cabRate = Rate::model()->getCabDetailsbyCities($model->bkg_from_city_id, $model->bkg_to_city_id);
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'customerinfo',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'errorCssClass' => 'has-error',
            'afterValidate' => 'js:function(form,data,hasError){
				if(!hasError){
					$.ajax({
						"type":"POST",

						"dataType":"html",
						"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('agent/booking/additionaldetail')) . '",
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
								openTab(data2,5);
								' . //trackPage(\'' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/conview')) . '\');
                                                            'disableTab(3);
							}
							else
							{
								var errors = data.errors;
								settings=form.data(\'settings\');
								$.each (settings.attributes, function (i) {
									try{
										$.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
									}catch(e)
									{
									}
								});
								$.fn.yiiactiveform.updateSummary(form, errors);
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
    ?>
    <div class="panel">            
        <div class="panel-body">   
            <input type="hidden" id="step4" name="step" value="4">
            <?= $form->hiddenField($model, 'bkg_user_id'); ?> 
            <?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id4']); ?>
            <?= $form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash']); ?>
            <?
//            $predata = $model->preData;
//            $dataa = CJSON::decode($predata);
//            var_dump($dataa);
            ?>
            <div class="col-xs-12 col-md-8">
                <?= $form->errorSummary($model); ?>
                <?= CHtml::errorSummary($model); ?>
                <h2 class="ml15 n">Traveller's Information</h2>
                <div id="error_div" style="display: none" class="alert alert-block alert-danger"></div>
                <div class="row mt10">
                    <div class="col-xs-12 col-sm-5 ">
                        <label for="inputEmail" class="control-label">Primary Passenger Name *:</label>
                    </div>
                    <div class="col-xs-12 col-sm-7">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 pr20">
                                <?= $form->textFieldGroup($model, 'bkg_user_name', array('label' => '', 'placeholder' => "First Name", 'class' => 'form-control')) ?>
                            </div>
                            <div class="col-xs-6 col-sm-6 pl20">
                                <?= $form->textFieldGroup($model, 'bkg_user_lname', array('label' => '', 'placeholder' => "Last Name", 'class' => 'form-control')) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-5 ">
                        <label for="inputEmail" class="control-label">Primary Email address *:</label>
                    </div>
                    <div class="col-xs-12 col-sm-7">
                        <?= $form->emailFieldGroup($model, 'bkg_user_email', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email Address", 'id' => CHtml::activeId($model, "bkg_user_email2")]), 'groupOptions' => ['class' => ''])) ?>                      
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-5 ">
                        <label for="inputEmail" class="control-label">Primary Contact Number *:</label>
                    </div>
                    <div class="col-xs-12 col-sm-7">
                        <div class="row">   
                            <div class="col-xs-5 isd-input">
                                <?php
//                                $this->widget('ext.yii-selectize.YiiSelectize', array(
//                                    'model' => $model,
//                                    'attribute' => 'bkg_country_code',
//                                    'useWithBootstrap' => true,
//                                    "placeholder" => "Code",
//                                    'fullWidth' => false,
//                                    'htmlOptions' => array(
//                                    ),
//                                    'defaultOptions' => array(
//                                        'create' => false,
//                                        'persist' => true,
//                                        'selectOnTab' => true,
//                                        'createOnBlur' => true,
//                                        'dropdownParent' => 'body',
//                                        'optgroupValueField' => 'pcode',
//                                        'optgroupLabelField' => 'pcode',
//                                        'optgroupField' => 'pcode',
//                                        'openOnFocus' => true,
//                                        'labelField' => 'name',
//                                        'valueField' => 'pcode',
//                                        'searchField' => 'name',
//                                        'closeAfterSelect' => true,
//                                        'addPrecedence' => false,
//                                        'onInitialize' => "js:function(){
//                                this.load(function(callback){
//                                var obj=this;                                
//                                xhr=$.ajax({
//                                    url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('index/country')) . "',
//                                    dataType:'json',        
//                                    cache: true,
//                                    success:function(results){
//                                        obj.enable();
//                                        callback(results.data);
//                                        obj.setValue('{$bmodel->bkg_country_code}');
//                                    },                    
//                                    error:function(){
//                                        callback();
//                                    }});
//                                });
//                            }",
//                                        'render' => "js:{
//                            option: function(item, escape){  
//                            return '<div><span class=\"\">' + escape(item.name) +'</span></div>';
//                            },
//                            option_create: function(data, escape){
//                            $('#countrycode').val(data.pcode);
//                            return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
//                            }
//                            }",
//                                    ),
//                                ));

                                echo $form->dropDownListGroup($model, 'bkg_country_code', array('label' => '', 'class' => 'form-control', 'widgetOptions' => array('data' => $ccode)))
                                ?>
                            </div>
                            <div class="col-xs-7 pr0">
                                <?= $form->textField($model, 'bkg_contact_no', array('placeholder' => "Primary Mobile Number", 'class' => 'form-control')) ?>
                                <?php echo $form->error($model, 'bkg_country_code'); ?>
                                <?php echo $form->error($model, 'bkg_contact_no'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-5">
                        <label for="inputEmail" class="control-label">Alternate Contact Number :</label>
                    </div>
                    <div class="col-xs-12 col-sm-7">
                        <div class="row">   
                            <div class="col-xs-5 isd-input">
                                <?php
                                echo $form->dropDownListGroup($model, 'bkg_alt_country_code', array('label' => '', 'class' => 'form-control', 'widgetOptions' => array('data' => $ccode)))
                                ?>
                            </div>
                            <div class="col-xs-7 pr0">
                                <?= $form->textField($model, 'bkg_alternate_contact', array('placeholder' => "Alternate Mobile Number", 'class' => 'form-control')) ?>
                                <?php echo $form->error($model, 'bkg_alt_country_code'); ?>
                                <?php echo $form->error($model, 'bkg_alternate_contact'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5 col-sm-5 ">

                        <label class="control-label pl0 ml0" for="BookingTemp_bkg_flight_no" id="flightlabeldivairport"  style="display: none">Enter Flight Number</label><br>

                        <label  class="control-label" id="picklabeloth">Airport Pickup?</label>
                        <label class="checkbox-inline pr30" style="padding-top: 11px;" id="chkAirport">
                            <?= $form->checkboxGroup($model, 'bkg_flight_chk', ['label' => '']) ?>
                        </label>
                    </div>
                    <div class="col-xs-7 col-sm-3 ">

                        <div id="othreq" style="display: none"> 
                            <div class="form-group" >
                                <label class="control-label pl0 ml0" for="BookingTemp_bkg_flight_no" id="flightlabeldivoth">Enter Flight Number <br></label>
                                <?= $form->textFieldGroup($model, 'bkg_flight_no', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Flight Number"]), 'groupOptions' => ['class' => 'm0'])) ?>  
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-10 ">
                        <b>
                            Please provide the exact pickup and drop addresses for your trip. 
                            The currently quoted amount is quoted from city center to city center. 
                            Exact addresses will help us provide updated more accurate fare quote for your booking. 
                            Distance driven beyond included Kms is billed as applicable. <? /* /?>at Rs. <?= $model->bkg_rate_per_km_extra ?>/Km</b><?/ */ ?>
                        </b>
                    </div>
                    <div class="col-xs-12 col-md-8 mt10">
                        <h5><b>Journey Details:</b></h5>
                    </div> 
                </div>
                <?
                $j = 0;
                $cntRt = sizeof($model->bookingRoutes);
                foreach ($model->bookingRoutes as $key => $brtRoute) {
                    $fbounds = $brtRoute->brtFromCity->cty_bounds;
                    $fboundArr = CJSON::decode($fbounds);
                    $tbounds = $brtRoute->brtToCity->cty_bounds;
                    $tboundArr = CJSON::decode($tbounds);
//                    $FLocLat = ($brtRoute->brt_from_latitude != '') ? $brtRoute->brt_from_latitude : $brtRoute->brtFromCity->cty_lat;
//                    $FLocLon = ($brtRoute->brt_from_longitude != '') ? $brtRoute->brt_from_longitude : $brtRoute->brtFromCity->cty_long;
//                    $TLocLat = ($brtRoute->brt_to_latitude != '') ? $brtRoute->brt_to_latitude : $brtRoute->brtToCity->cty_lat;
//                    $TLocLon = ($brtRoute->brt_to_longitude != '') ? $brtRoute->brt_to_longitude : $brtRoute->brtToCity->cty_long;


                    if ($j == 0) {
                        ?>       

                        <div class = "row ">
                            <div class="col-xs-12">
                                <div class = "row ">
                                    <div class="col-xs-12 col-sm-5">
                                        <label for="pickup_address" class="control-label text-left">Pickup <?= $addressLabel ?> for <?= $brtRoute->brtFromCity->cty_name ?> *:</label>
                                        <input type="hidden" id="ctyLat0" class="" value="<?= $brtRoute->brtFromCity->cty_lat ?>">
                                        <input type="hidden" id="ctyLon0" class="" value="<?= $brtRoute->brtFromCity->cty_long ?> ">
                                        <input type="hidden" id="ctyELat0" class="" value="<?= round($fboundArr['northeast']['lat'], 6) ?>">
                                        <input type="hidden" id="ctyWLat0" class="" value="<?= round($fboundArr['southwest']['lat'], 6) ?>">
                                        <input type="hidden" id="ctyELng0" class="" value="<?= round($fboundArr['northeast']['lng'], 6) ?>">
                                        <input type="hidden" id="ctyWLng0" class="" value="<?= round($fboundArr['southwest']['lng'], 6) ?>">
                                        <input type="hidden" id="ctyRad0" class="hide" value="<?= $brtRoute->brtFromCity->cty_radius ?>">
                                        <? if ($model->bkg_booking_type == '4') { ?>
                                            <?= $form->hiddenField($brtRoute, "[0]brt_from_latitude", ['id' => 'locLat0']); ?>
                                            <?= $form->hiddenField($brtRoute, "[0]brt_from_longitude", ['id' => 'locLon0']); ?>

                                        <? } ?>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 mb0 pb0">

                                        <?= $form->textAreaGroup($brtRoute, "[0]brt_from_location", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['id' => "brt_location$key", 'class' => "form-control $autocompleteFrom", 'placeholder' => "Pickup Address  (Required)"] + $locFrom))) ?>

                                        <?php
                                        //echo
                                        $form->textFieldGroup($model, 'bkg_pickup_address');

                                        //	echo $form->error($model, 'bkg_pickup_address');
                                        ?>
                                    </div>                    

                                    <div class="col-xs-3">
                                        <? //= $form->numberFieldGroup($brtRoute, "[0]brt_from_pincode", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Pincode (Optional)"]), 'groupOptions' => ['class' => 'm0'])) 
                                        ?>
                                    </div>
                                </div>
                                <? if ($model->bkg_booking_type == '4' && $model->bkg_transfer_type == '2') { ?>

                                    <div class = "row ">
                                        <div class="col-xs-12 col-sm-5 ">
                                            <label for="buildinInfo" class="control-label text-left"><?= $additionalAddressInfo ?>:</label>

                                        </div>
                                        <div class="col-xs-12 col-sm-4 mb0 pb0">
                                            <?= $form->textFieldGroup($brtRoute, "[0]brt_additional_from_address", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['id' => "brt_additional$key", 'class' => "form-control", 'placeholder' => $additionalAddressInfo]))) ?>

                                        </div>
                                    </div>
                                <? } ?>
                            </div>
                        </div>
                        <?
                    }
                    $key1 = $key + 1;
                    $j++;
                    $opt = (($key + 1) == $cntRt) ? 'Required' : 'Optional';
                    $optReq = (($key + 1) == $cntRt) ? ' *' : '';
                    ?>

                    <div class="row pt30">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-12 col-sm-5 ">
                                    <label for="pickup_address" class="control-label text-left">Drop <?= $addressLabel ?> for <?= $brtRoute->brtToCity->cty_name ?> <?= $optReq ?>:</label>
                                    <input type="hidden" id="ctyLat<?= $key + 1 ?>"  value="<?= $brtRoute->brtToCity->cty_lat ?>">
                                    <input type="hidden" id="ctyLon<?= $key + 1 ?>"  value="<?= $brtRoute->brtToCity->cty_long ?> ">
                                    <input type="hidden" id="ctyELat<?= $key + 1 ?>" value="<?= round($tboundArr['northeast']['lat'], 6) ?>">
                                    <input type="hidden" id="ctyWLat<?= $key + 1 ?>"  value="<?= round($tboundArr['southwest']['lat'], 6) ?>">
                                    <input type="hidden" id="ctyELng<?= $key + 1 ?>" value="<?= round($tboundArr['northeast']['lng'], 6) ?>">
                                    <input type="hidden" id="ctyWLng<?= $key + 1 ?>" value="<?= round($tboundArr['southwest']['lng'], 6) ?>">
                                    <input type="hidden" id="ctyRad<?= $key + 1 ?>"  value="<?= $brtRoute->brtToCity->cty_radius ?>">

                                    <? if ($model->bkg_booking_type == '4') { ?>
                                        <?= $form->hiddenField($brtRoute, "[$key1]brt_to_latitude", ['id' => "locLat$key1"]); ?>
                                        <?= $form->hiddenField($brtRoute, "[$key1]brt_to_longitude", ['id' => "locLon$key1"]); ?>
                                    <? } ?>
                                </div>

                                <div class="col-xs-12 col-sm-4">
                                    <?= $form->textAreaGroup($brtRoute, "[$key1]brt_to_location", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['id' => "brt_location$key1", 'class' => "form-control $autocompleteTo", 'placeholder' => "Drop Address  ($opt)"] + $locTo))) ?>
                                    <?php
                                    if ((($key + 1) == $cntRt)) {
                                        $form->textFieldGroup($model, 'bkg_drop_address');
                                        CHtml::error($model, 'bkg_drop_address');
//								echo $form->error($model, 'bkg_drop_address'); 
                                    }
                                    ?>
                                </div>
                                <div class="col-xs-3">
                                    <? //= $form->numberFieldGroup($brtRoute, "[$key]brt_to_pincode", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Pincode (Optional)"]), 'groupOptions' => ['class' => 'm0']))      ?>
                                </div>
                            </div>
                            <? if ($model->bkg_booking_type == '4' && $model->bkg_transfer_type == '1') { ?>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-5 ">
                                        <label for="buildinInfo" class="control-label text-left"><?= $additionalAddressInfo ?>:</label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4">
                                        <?= $form->textFieldGroup($brtRoute, "[$key1]brt_additional_to_address", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['id' => "brt_additional$key1", 'class' => "form-control", 'placeholder' => $additionalAddressInfo]))) ?>
                                    </div>
                                </div> 
                            <? } ?> 
                        </div>
                    </div>
                    <?
                }
                ?>
            </div>
            <div class="col-xs-12">
                <div class="text-center mt20">
                    <?= CHtml::submitButton('NEXT', array('class' => 'btn btn-success btn-lg pl40 pr40')); ?>
                </div>
            </div>
        </div> 
    </div>
    <?php $this->endWidget(); ?>
</div>



<script type="text/javascript">

    var ctLat = 0.0;
    var ctLon = 0.0;
    var ctELat = 0.0;
    var ctELng = 0.0;
    var ctWLat = 0.0;
    var ctWLng = 0.0;

    function initializepl() {
        var acInputs = document.getElementsByClassName("txtpl");
        var len = acInputs.length;
        var i = 0;
        var j = 0;
        if ('<?= $model->bkg_booking_type ?>' == '4') {
            len = 1;
        }

        var eastboundLat = 0.0;
        var eastboundLon = 0.0;
        var westboundLat = 0.0;
        var westboundLon = 0.0;
        latlongdiff = 0.00;
        var pluslatlongdiff = latlongdiff;
        var minuslatlongdiff = 0.0 - latlongdiff;

        for (i; i < len; i++) {

            if ('<?= $model->bkg_transfer_type ?>' == '1') {
                $('#locLat0').val($('#ctyLat0').val()).change();
                $('#locLon0').val($('#ctyLon0').val()).change();

                i = 1;
            } else {
                if ('<?= $model->bkg_transfer_type ?>' == '2') {
                    $('#locLat1').val($('#ctyLat1').val()).change();
                    $('#locLon1').val($('#ctyLon1').val()).change();
                }
                j = i;
            }
            var cLatId = 'ctyLat' + i;
            var cLonId = 'ctyLon' + i;
            var cELatId = 'ctyELat' + i;
            var cELonId = 'ctyELng' + i;
            var cWLatId = 'ctyWLat' + i;
            var cWLonId = 'ctyWLng' + i;
            var locLat = 'locLat' + i;
            var locLon = 'locLon' + i;



            if ($('#' + cELatId).val() > 0) {
                latLngType = 1;
            } else {
                latLngType = 2;
            }
            if (latLngType == 1) {
                westboundLat = $('#' + cELatId).val() - minuslatlongdiff;
                westboundLon = $('#' + cELonId).val() - minuslatlongdiff;
                eastboundLat = $('#' + cWLatId).val() - pluslatlongdiff;
                eastboundLon = $('#' + cWLonId).val() - pluslatlongdiff;
                //                alert(westboundLat - eastboundLat);
                //                alert(westboundLon - eastboundLon);

            } else if (latLngType == 2) {
                ctLat = $('#' + cLatId).val();
                ctLon = $('#' + cLonId).val();

                eastboundLat = ctLat - 0.05;
                eastboundLon = ctLon - 0.05;
                westboundLat = ctLat - 0.0 + 0.05;//parseFloat
                westboundLon = ctLon - 0.0 + 0.05;
                //                alert(westboundLat - eastboundLat);
                //                alert(westboundLon - eastboundLon);
            }
            // alert(eastboundLat + ' : ' + eastboundLon + ' : ' + westboundLat + ' : ' + westboundLon);
            var defaultBounds = new google.maps.LatLngBounds(
                    new google.maps.LatLng(eastboundLat, eastboundLon),
                    new google.maps.LatLng(westboundLat, westboundLon));
            var options = {
                types: [],
                bounds: defaultBounds,
                strictBounds: 1,
                componentRestrictions: {country: 'IN'}
            };

            var autocomplete = new google.maps.places.Autocomplete(acInputs[j], options);
            autocomplete.inputId = acInputs[j].id;

            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                if ('<?= $model->bkg_booking_type ?>' == '4') {

                    var place = autocomplete.getPlace();
                    //var address = place.formatted_address;
                    var latitude = place.geometry.location.lat();
                    var longitude = place.geometry.location.lng();
                    //                var mesg = "Address: " + address;
                    //                mesg += "\nLatitude: " + latitude;
                    //                mesg += "\nLongitude: " + longitude;
                    //  alert(mesg);
                    if (latitude > 0 && longitude > 0) {
                        $('#' + locLat).val(latitude).change();
                        $('#' + locLon).val(longitude).change();
                    }
                }

            });
        }
    }
    $(document).ready(function () {
        $('.bootbox').removeAttr('tabindex');
        disableTab(4);
        callbackLogin = 'fillUserform';

        $('#<?= CHtml::activeId($model->bkgAddInfo, "bkg_info_source") ?>').change(function () {
            var infosource = $('#<?= CHtml::activeId($model->bkgAddInfo, "bkg_info_source") ?>').val();
            extraAdditionalInfo(infosource);
        });
        if ('<?= $model->bkg_booking_type ?>' == 4) {
            $('#chkAirport').hide();
            $('#picklabeloth').hide();
            $('#flightlabeldivoth').hide();
            $('#othreq').show();
            $('#flightlabeldivairport').show();
        }
        initializepl();
    });
    $('.nav-tabs a[href="#menu3"]  span[id="bcabs"]').html('BY <?= SvcClassVhcCat::model()->getVctSvcList('string', '', $model->bkgSvcClassVhcCat->scv_vct_id); ?>');

    $('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').change(function () {
        if ($('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').is(':checked'))
        {
            $("#othreq").show();
        } else {
            $("#othreq").hide();
        }
    });
    $('#<?= CHtml::activeId($model, "bkg_send_email") ?>').change(function () {
        if ($('#<?= CHtml::activeId($model, "bkg_send_email") ?>').is(':checked') && $('#<?= CHtml::activeId($model, "bkg_user_email") ?>').val() == '') {

            var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";

            txt += "<li>Please provide email address.</li>";

            txt += "</ul>";
            $("#error_div").show();
            $("#error_div").html(txt);
        }

    });
    $('#<?= CHtml::activeId($model, "bkg_send_sms") ?>').change(function () {
        if ($('#<?= CHtml::activeId($model, "bkg_send_sms") ?>').is(':checked') && $('#<?= CHtml::activeId($model, "bkg_contact_no") ?>').val() == '') {

            var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
            txt += "<li>Please provide contact number.</li>";
            txt += "</ul>";
            $("#error_div").show();
            $("#error_div").html(txt);
        }
    });


    function extraAdditionalInfo(infosource)
    {
        $("#source_desc_show").addClass('hide');
        if (infosource == 'Friend') {
            $("#source_desc_show").removeClass('hide');
            $("#agent_show").addClass('hide');
            $('#<?= CHtml::activeId($model->bkgAddInfo, "bkg_info_source_desc") ?>').attr('placeholder', "Friend's email please");
        } else if (infosource == 'Other') {
            $("#source_desc_show").removeClass('hide');
            $("#agent_show").addClass('hide');
            $('#<?= CHtml::activeId($model->bkgAddInfo, "bkg_info_source_desc") ?>').attr('placeholder', "");
        }

    }


    function validateBothCheck() {
        if (!$('#<?= CHtml::activeId($model, "bkg_send_sms") ?>').is(':checked') && !$('#<?= CHtml::activeId($model, "bkg_send_email") ?>').is(':checked')) {

            var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
            txt += "<li>Please check one of the communication media to send notifications.</li>";
            txt += "</ul>";
            $("#error_div").show();
            $("#error_div").html(txt);
        }

    }

    function saveBooking() {
        $('#customerinfo').submit();
        $.ajax({
            "type": "POST",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/additionaldetail')) ?>",
            "data": $("#customerinfo").serialize(),
            "success": function (data2) {
                if (data2.success) {
                    trackPage('<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/finalbook')) ?>');
                    $("#final").show();
                    $("#error_div").hide();
                    openTab(data2.res, data2.type, 5);
                    refreshLasttab(data2.data);
                    // alert(data2);
                } else {
                    var errors = data2.errors;

                    var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
                    $.each(errors, function (key, value) {
                        txt += "<li>" + value + "</li>";
                    });
                    txt += "</ul>";
                    $("#error_div").show();
                    $("#error_div").html(txt);

                }
            }
        });
    }
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

    function callSigninbox()
    {
        var uemail = $('#uemail').text();
        $href = '<?= Yii::app()->createUrl('users/partialsignin', ['callback' => 'refreshUserdata']) ?>';
        jQuery.ajax({type: 'GET', url: $href, "data": {"uemail": uemail},
            success: function (data)
            {
                signinbox = bootbox.dialog({
                    message: data,
                    title: 'Login',
                    onEscape: function ()
                    {
                        signinbox.modal('hide');
                    }
                });
            }
        });
    }

    var refreshUserdata = function ()
    {

        refreshNavbar();

        $('#<?= CHtml::activeId($model, "bkg_user_id") ?>').val($userid);
        if ($userid > 0)
        {
            $('#userdiv').hide();
            $('#welcomediv').show();

            fillUserdata();
            // signinbox.modal('hide');
            //  bootbox.hideAll();
            //signupbox.modal('hide');
        } else
        {
            $('#userdiv').show();
            $('#welcomediv').hide();

        }
    };

    function hideDiv() {
        $('#userdiv').fadeOut(800);
    }

    function callSignupbox()
    {
        var uemail = $('#uemail').text();
        var ucode = $('#ucode').text();
        var ucontact = $('#ucontact').text();
        $href = '<?= Yii::app()->createUrl('users/partialsignup', ['callback' => 'refreshNavbar(data1)']) ?>';
        jQuery.ajax({type: 'GET', url: $href, "data": {"uemail": uemail, "ucode": ucode, "ucontact": ucontact},
            success: function (data)
            {
                signupbox = bootbox.dialog({
                    message: data,
                    title: 'Register',
                    onEscape: function ()
                    {
                        signupbox.modal('hide');
                    }
                });

            }
        });
    }


    $('#<?= CHtml::activeId($model, "bkg_flight_chk") ?>').change(function () {
        if ($('#<?= CHtml::activeId($model, "bkg_flight_chk") ?>').is(':checked'))
        {
            $("#othreq").show();
        } else {
            $("#othreq").hide();
        }
    });
    $('#<?= CHtml::activeId($model, "bkg_flight_no") ?>').mask('XXXX-XXXXXX', {
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
    }
    );



</script>
