<style>
    .form-group, .form-inline .form-control{
    }

</style>
<script>

    $(document).ready(function () {
        getDuration(<?= $model->bkg_trip_duration ?>)
    });
    function getDuration(triptime) {
        if (triptime > 0) {
            var t = "";
            var d = Math.floor(triptime / (24 * 60));
            var h = Math.floor((triptime % (24 * 60)) / 60);
            var m = Math.floor((triptime % (24 * 60)) % 60);
            if (d > 0) {
                t = t + d + "Days ";
            }
            if (h > 0) {
                t = t + h + "Hrs ";
            }
            if (m > 0) {
                t = t + m + "Min ";
            }          
            $('#time').text(t);
        }
    }






</script>
<?
//var_dump($req);
//var_dump($cabratedata);
$scityname = Cities::getName($model->bkg_from_city_id);
$dcityname = Cities::getName($model->bkg_to_city_id);

//$model=new Booking();
?>
        <div class="row mb20">
            <div class="col-xs-12 col-sm-3 col-md-3 mb15">
                <p class="m0 weight400"><b>Estimated Distance</b>: <?= $model->bkg_trip_distance ?> Km</p>
                <p class="m0 weight400"><b>Estimated Time</b>: <span id="time"></span></p>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-6">
                <h4 class="weight400 m0 mb5 text-center">One way cab</h4>
                <h1 class="weight400 m0 mb5 text-center"><b><?= $scityname . ' to ' . $dcityname ?></b></h1>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 pull-right">
                <p class="m0 weight400"><b>Pickup Date</b>: <?= date('jS M Y (l)', strtotime($model->bkg_pickup_date)) ?></p>
                <p class="m0 weight400"><b>Pickup Time</b>: <?= date('h:i A', strtotime($model->bkg_pickup_date)) ?></p>
                <a class="mt10 btn btn-primary text-right" onclick="skipPopup()" style="" href="<?= Yii::app()->createUrl('booking/cabrate', array('bkid' => $model->bkg_id)) ?>" tabindex="1"><?= $dcityname . ' to ' . $scityname ?></a>
                <a class="mt10 btn btn-primary text-right" onclick="skipPopup()" style="" href="<?= Yii::app()->createUrl('index/index', array('bkid' => $model->bkg_id)) ?>" tabindex="1">Modify Route</a>

            </div>

        </div>
        <!--        <div class="row">
                    <div class="col-xs-12">
                        <h4 class="m5 ml10">Gozo Rate Guarantee : Gozo is India's leader in low cost taxi. If you find a cheaper rate, we will beat it*.</h4>
                    </div> 
                </div>-->

        <?php
        $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id' => 'cabrate-form', 'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'errorCssClass' => 'has-error',
                'afterValidate' => 'js:function(form,data,hasError){
                if(!hasError){
                    $contact = $("#' . CHtml::activeId($model, 'bkg_contact_no') . '");
                    $code = $("#' . CHtml::activeId($model, 'bkg_country_code') . '");
                    $email = $("#' . CHtml::activeId($model, 'bkg_user_email') . '");
                    $tnss = $("#tnc").is(":checked");
                    
                    if($contact.val().trim() == "" && $email.val().trim() == "")
                    {
                        $contact.focus();
                        $("#BookingTemp_bkg_country_code_em_").text("");
                        $("#BookingTemp_bkg_country_code_em_").text("Please enter contact number or email address");
                        $("#BookingTemp_bkg_country_code_em_").show();                                
                        return false;
                    }
                    
                    if($contact.val().trim() != "" && $code.val().trim() == "")
                    {
                        $code[0].selectize.focus();
                        $("#BookingTemp_bkg_country_code_em_").text("");
                        $("#BookingTemp_bkg_country_code_em_").text("Please enter ISD code");
                        $("#BookingTemp_bkg_country_code_em_").show();
                        alert("Please enter ISD code");                            
                        return false;
                    }
                    if(!$tnss)
                    {
                        alert("You must agree to our Terms and Conditions before proceed."); 
                        return false;
                    }
                    return true;
                }
            }'
            ),
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // See class documentation of CActiveForm for details on this,
            // you need to use the performAjaxValidation()-method described there.
            'enableAjaxValidation' => false,
            'errorMessageCssClass' => 'help-block',
           // 'action' => Yii::app()->createUrl('booking/process'),
            'htmlOptions' => array(
                'class' => 'form-horizontal',
            ),
        ));
        /* @var $form TbActiveForm */
        ?>

        <div class="panel">
            <div class="panel-heading"><h5 class="m0">If there are any issues with your booking we will contact you. Please share your phone and email address below.</h5></div>
            <div class="panel-body pt0 pb0">                   
                <?= $form->hiddenField($model, 'bkg_id'); ?>
				<?=  CHtml::hiddenField('hash', Yii::app()->shortHash->hash($model->bkg_id))?>
                <?= $form->hiddenField($model, "bkg_trip_distance"); ?>
                <?= $form->hiddenField($model, "bkg_trip_duration"); ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-offset-1 col-lg-offset-1 col-md-5 col-lg-4">

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
                                        //   'sortField' => 'js:[{field:"order",direction:"asc"}]',
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
                            <div class="col-xs-9 pl0">
                                <?= $form->textField($model, 'bkg_contact_no', array('placeholder' => "Primary Mobile Number", 'class' => 'form-control')) ?>
                                <?php echo $form->error($model, 'bkg_country_code'); ?>
                                <?php echo $form->error($model, 'bkg_contact_no'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-lg-offset-1 col-md-5 col-lg-4">      
                        <?= $form->emailFieldGroup($model, 'bkg_user_email', array('label' => 'Email Address', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email Address"]), 'groupOptions' => ['class' => 'm0'])) ?>                      
                    </div>                
                </div>
                <div class="row">
                    <div class="col-xs-12 summary-div border-none">
                        <div class="checkbox ml20">
                            <input class="checkbox mt5 n" type="checkbox" id="tnc" name="tnc" value="1" required />
                            <label class="control-label p0  mt0" for="tnc" onclick="skipPopup()"> I Agree to the <a href="#" onclick="opentns()" >Terms and Conditions</a></label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row"><? foreach ($cabratedata as $key => $val) { ?>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="thumbnail p10 border-radius">
                        <img src="<?= Yii::app()->baseUrl . '/' . $val['image_path'] ?>" alt="..." class="border-black">
                        <div class="caption pl0 pr0">
                            <h4 class="text-center"><?= $val['cab_model'] ?></h4>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-6">Capacity</div>
                                        <div class="col-xs-6 text-right"><?= $val['cab_capacity'] ?> +1</div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <hr class="mt10 mb10">
                                    <div class="row">
                                        <div class="col-xs-6 ">Car type</div>
                                        <div class="col-xs-6 text-right"><?= $val['cab_type'] ?></div>
                                    </div>
                                </div>
                                <div class="col-xs-12">                                    
                                    <hr class="mt10 mb10">
                                    <div class="row">
                                        <div class="col-xs-6 mt5">Price</div>
                                        <div class="col-xs-6 text-right"><h3 class="mt0 mb5"><i class="fa fa-rupee blue2-color"></i> <span class="orange-color"><?= $val['cab_rate']; ?></span></h3></div>
                                    </div>
                                </div>
                                <div class="col-xs-12"> 
                                    <button onclick="skipPopup()" type="submit" value="<?= $val['cab_id'] ?>" name="<?= CHtml::activeName($model, "bkg_vehicle_type_id") ?>" class="btn btn-primary full-width">Book</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <? } ?>
            <input type="hidden" id="cabTypeID" name="cabTypeID" value="0"/>
        </div>
        <?php
        $this->endWidget();
        ?>
        <!--        <div class="panel">
                    <div class="panel-body pt0">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="checkbox ml20">
                                    <input class="checkbox c" type="checkbox" id="ask_customer" name="ask_customer" value="1">
                                    <label class="control-label p0 h4 mt0" for="ask_customer"> Did you find a better rate than Gozocabs?</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                * For our rate beat guarantee, please share with us a printed quote or screenshot of the rate being available. Our team will confirm that rate on the vendor site and offer you a matching or better rate.
                            </div>
                        </div>    
                    </div>
                </div>    -->
<?php
$this->renderPartial('popupform', ['model' => $model]);
?>

<script>
    $(document).ready(function () {
        $("#tnc").attr('checked', 'checked');
    });
    function opentns() {


        $href = '<?= Yii::app()->createUrl('index/tns') ?>';
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


</script>