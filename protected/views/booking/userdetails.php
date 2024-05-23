<?php
/* @var $model Booking */
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
$countrycode = Yii::app()->params['countrycode'];
$ccode = (int) str_replace('+', '', $countrycode);

//echo $model->bkg_id;
$fcity = Cities::getName($model->bkg_from_city_id);
$tcity = Cities::getName($model->bkg_to_city_id);

$scityname = Cities::getName($model->bkg_from_city_id);
$dcityname = Cities::getName($model->bkg_to_city_id);
$infosource = ['' => 'Select Source'] + BookingAddInfo::model()->getInfosource();
?>
<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin:0;/* <-- Apparently some margin are still there even though it's hidden */
    }
</style>

<div class="container">
    <div class="row mb20">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <h3 class="weight400 m0 mb5">One way cab</h3>
            <p class="m0 weight400"><span class="heading-inn"><?= $scityname ?> to <?= $dcityname ?></span> on <?= date('jS M Y (l)', strtotime($model->bkg_pickup_date)) ?> at <?= date('h:i A', strtotime($model->bkg_pickup_date)) ?> </p>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 text-right">
            <p class="m0 weight400"><b>Estimated Distance</b>: <span id="dist"><?= $model->bkg_trip_distance ?></span></p>
            <p class="m0 weight400"><b>Estimated Time</b>: <span id="time"><?= $model->bkg_trip_duration ?></span></p>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-8 book-panel">
            <div class="panel panel-default border-radius">
                <div class="panel-body pl30 pr30">
                    <?php
                    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                        'id' => 'booking-form', 'enableClientValidation' => true,
                        'clientOptions' => array(
                            'validateOnSubmit' => true,
                            'errorCssClass' => 'has-error',
                            'afterValidate' => 'js:function(form,data,hasError){

                            if(!hasError){
                                $contact = $("#' . CHtml::activeId($model, 'bkg_contact_no') . '");
                                $code = $("#' . CHtml::activeId($model, 'bkg_country_code') . '");
                                $altcontact = $("#' . CHtml::activeId($model->bkgUserInfo, 'bkg_alt_contact_no') . '");
                                $altcode = $("#' . CHtml::activeId($model, 'bkg_alt_country_code') . '");
                                $email = $("#' . CHtml::activeId($model, 'bkg_user_email') . '");
                                if($contact.val().trim() == "" && $email.val().trim() == "")
                                {
                                    $contact.focus();
                                    alert("Please enter contact number or email address");                                
                                    return false;
                                }
                                if($contact.val().trim() != "" && $code.val().trim() == "")
                                {
                                    $code[0].selectize.focus();
                                    alert("Please enter ISD code");                            
                                    return false;
                                }
                                if($altcontact.val().trim() != "" && $altcode.val().trim() == "")
                                {
                                    $altcode[0].selectize.focus();
                                    alert("Please enter ISD code");                            
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
                        'action' => Yii::app()->createUrl('booking/summary'),
                        'htmlOptions' => array(
                            // 'class' => 'form-horizontal',
                            'novalidate' => 'novalidate'
                        ),
                    ));
                    /* @var $form TbActiveForm */
                    ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <h4>Traveller's Information</h4>
                            <?= CHtml::errorSummary($model); ?>
                            <?= $form->hiddenField($model, 'bkg_id') ?>                        
                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <label>First Name<span style="color: red;font-size: 15px;">*</span></label>
                                    <?= $form->textFieldGroup($model, 'bkg_user_fname', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "First Name"]))) ?>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <label>Last Name<span style="color: red;font-size: 15px;">*</span></label>
                                    <?= $form->textFieldGroup($model, 'bkg_user_lname', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Last Name"]))) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <label>Primary Contact Number<span style="color: red;font-size: 15px;">*</span></label>
                                    <div class="row">   
                                        <div class="col-xs-3 isd-input pr0">
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
                                            <?php echo $form->error($model, 'bkg_country_code'); ?>
                                        </div>
                                        <div class="col-xs-9 pl5">
                                            <?= $form->numberFieldGroup($model, 'bkg_contact_no', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'placeholder' => "Primary Mobile Number"]))) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <label>Alternate Contact Number</label>
                                    <div class="row">
                                        <div class="col-xs-3 isd-input pr0"> <?php
                                            $this->widget('ext.yii-selectize.YiiSelectize', array(
                                                'model' => $model,
                                                'attribute' => 'bkg_alt_country_code',
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
                                                                obj.setValue('{$model->bkg_alt_country_code}');
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
                                                        return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
                                                        }
                                                    }",
                                                ),
                                            ));
                                            ?></div>
                                        <div class="col-xs-9 pl5">
                                            <?= $form->numberFieldGroup($model, 'bkg_alt_contact_no', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Alternate Contact Number (optional)"]))) ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <label>Email Address<span style="color: red;font-size: 15px;">*</span></label>
                                    <?= $form->emailFieldGroup($model, 'bkg_user_email', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email Address"]))) ?>
                                </div>
                                <div class="col-xs-12 col-sm-6 ">
                                    <label>How did you hear about us?</label>
                                    <?= $form->dropDownListGroup($model->bkgAddInfo, 'bkg_info_source', array('label' => '', 'widgetOptions' => array('data' => $infosource))) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 newButtonLine text-right">                           
                                    <input type="submit" value="Proceed" onclick="skipPopup()" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>

        </div>
        <div class="col-xs-12 col-sm-12 col-md-4">
            <?php
           $this->actionBilling($model->bkg_id);
            ?>
        </div>
    </div>
</div>

