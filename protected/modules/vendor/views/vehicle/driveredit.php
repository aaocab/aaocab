<style type="text/css">
    .selectize-input {
        min-width: 0px !important;
        width: 30% !important; 
    }
</style>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$cityList = CHtml::listData(Cities::model()->findAll(array('order' => 'cty_name', 'condition' => 'cty_active=:act', 'params' => array(':act' => '1'))), 'cty_id', 'cty_name');
$stateList = array("" => "Select state") + CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
?>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-8 pb10" style="float: none; margin: auto">
        <div class="col-xs-12 mb20" style="color:#008a00;text-align: center">
            <?php echo Yii::app()->user->getFlash('success'); ?>
        </div>
        <div class="col-xs-12 mb20" style="color:#F00;text-align: center">
            <?php echo Yii::app()->user->getFlash('error'); ?>
        </div>
        <div style="text-align:center;" class="col-xs-12">
            <?php
            if ($status == "emlext") {
                echo "<span style='color:#ff0000;'>This email address is already registered. Please try again using a new email address.</span>";
            } elseif ($status == "added") {
                echo "<span style='color:#00aa00;'>Driver added successfully.</span>";
            } elseif ($status == "updated") {
                echo "<span style='color:#00aa00;'>Driver Modified Successfully.</span>";
            } else {
                //do nothing
            }
            ?>
        </div>
        <div class="row">
            <div class="upsignwidt11">
                <div class="col-xs-12">
                    <?php
                    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                        'id' => 'driver-register-form', 'enableClientValidation' => TRUE,
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
                        'htmlOptions' => array(
                            'class' => 'form-horizontal', 'enctype' => 'multipart/form-data'
                        ),
                    ));
                    /* @var $form TbActiveForm */
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="col-xs-12">
                                <?php echo CHtml::errorSummary($model); ?>
                                <?
                                if ($model->drv_driver_id == '') {
                                    $model->drv_driver_id = $driverId;
                                }
                                if ($model->drv_vendor_id == '') {
                                    $model->drv_vendor_id = $vendorId;
                                }
                                ?>
                                <?= $form->hiddenField($model, 'drv_driver_id') ?>
                                <?= $form->hiddenField($model, 'drv_vendor_id') ?>
                                <? //= $form->hiddenField($model, 'drv_id') ?>
                                <input type="hidden" id="countrycode" name="DriversInfo[countrycode]">
                                <?= $form->textFieldGroup($model, 'drv_name', ['label' => '<b>Name</b>']) ?>

                                <span ><b>Driver Photo</b></span><br>
                                <div class="form-group">
                                    <div class="col-xs-8">
                                        <?= $form->fileFieldGroup($model, 'drv_photo_path', array('label' => '')); ?>
                                    </div><div class="col-xs-4">
                                        <?
                                        if ($model->drv_photo_path != '') {
                                            ?>
                                        <a href="<?= $model->drv_photo_path ?>" target="_BLANK"><?= basename($model->drv_photo_path) ?>"</a>
                                        <? } ?>
                                    </div>
                                </div>
                                <?= $form->emailFieldGroup($model, 'drv_email', ['label' => '<b>Email</b>']) ?>
                                <?php
                                if ($model->drv_dob_date!='') {
                                   if ((date('Y-m-d', strtotime($model->drv_dob_date)) == date($model->drv_dob_date))) {
                                    $model->drv_dob_date = DateTimeFormat::DateToDatePicker($model->drv_dob_date);
                                    }
                                }
                                echo $form->datePickerGroup($model, 'drv_dob_date', array('label' => '<b>Date of birth</b>',
                                    'widgetOptions' => array('options' => array('autoclose' => true, 'format' => 'dd/mm/yyyy'))
                                ));
                                ?>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <label><b>Phone</b></label><br>
                                            <div class="col-xs-2 pl0">

                                                <?php
                                                $this->widget('ext.yii-selectize.YiiSelectize', array(
                                                    'model' => $model,
                                                    'attribute' => 'drv_country_code',
                                                    'useWithBootstrap' => true,
                                                    "placeholder" => "Code",
                                                    'fullWidth' => false,
                                                    'htmlOptions' => array(
                                                        'style' => 'width: 60%',
                                                    ),
                                                    'defaultOptions' => array(
                                                        'create' => false,
                                                        'persist' => true,
                                                        'selectOnTab' => true,
                                                        'createOnBlur' => true,
                                                        'dropdownParent' => 'body',
                                                        'optgroupValueField' => 'id',
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
                                     success:function(results){
                                        obj.enable();
                                        callback(results.data);
                                    $('#DriversInfo_drv_country_code')[0].selectize.setValue({$model->drv_country_code});
                                    },                    
                                    error:function(){
                                    callback();
                                    }});
                                    });
                                    }",
                                                        'render' => "js:{
                                    option: function(item, escape){  
                                    var class1 = (item.pcode == 91) ? '':'pl20';
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
                                                <span class="has-error"><? echo $form->error($model, 'drv_country_code'); ?></span>
                                            </div>
                                            <div class='col-xs-10'>
                                                <?= $form->textFieldGroup($model, 'drv_phone', array('label' => '')) ?>
                                            </div> </div>
                                    </div>
                                </div>
                                <?= $form->textAreaGroup($model, 'drv_address', ['label' => '<b>Address</b>']) ?>

                                <div class="form-group">
                                    <label>State</label>
                                    <?php
                                    $dataState = VehicleTypes::model()->getJSON($stateList);
                                    $this->widget('booster.widgets.TbSelect2', array(
                                        'model' => $model,
                                        'attribute' => 'drv_state',
                                        'val' => $model->drv_state,
                                        'asDropDownList' => FALSE,
                                        'options' => array('data' => new CJavaScriptExpression($dataState)),
                                        'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select State')
                                    ));
                                    ?>
                                    <span class="has-error"><? echo $form->error($model, 'drv_state'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>City</label>
                                    <?php
                                    $this->widget('booster.widgets.TbSelect2', array(
                                        'model' => $model,
                                        'attribute' => 'drv_city',
                                        'val' => $model->drv_city,
                                        'asDropDownList' => FALSE,
                                        'options' => array('data' => new CJavaScriptExpression('[]')),
                                        'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select City')
                                    ));
                                    ?>
                                    <span class="has-error"><? echo $form->error($model, 'drv_city'); ?></span>
                                </div>
                                <?= $form->textFieldGroup($model, 'drv_zip') ?>
                                <div class="form-group">
                                    <div class="col-xs-6"  >
                                        <?= $form->checkboxGroup($model, 'drv_bg_checked', array('value' => '1', 'uncheckValue' => '0', 'label' => 'Background checked')); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?= $form->checkboxGroup($model, 'drv_is_attached', array('value' => '1', 'uncheckValue' => '0', 'label' => 'is exclusive to Gozo')); ?>
                                    </div>  
                                </div>

                                <?= $form->textFieldGroup($model, 'drv_lic_number') ?>
                                <?= $form->textFieldGroup($model, 'drv_issue_auth') ?>
                                <?php
                                if ($model->drv_lic_exp_date) {
                                     if ((date('Y-m-d', strtotime($model->drv_lic_exp_date)) == date($model->drv_lic_exp_date))) {
                                    $model->drv_lic_exp_date = DateTimeFormat::DateToDatePicker($model->drv_lic_exp_date);
                                }
                                }
                                echo $form->datePickerGroup($model, 'drv_lic_exp_date', array(
                                    'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => '+1d', 'format' => 'dd/mm/yyyy'))
                                ));
                                ?>
                                <div class="col-xs-12"><h4>Proof of address</h4></div>
                                <span >Aadhaar Card </span><br>                         
                                <div class="form-group">
                                    <div class="col-xs-8">
                                        <?= $form->fileFieldGroup($model, 'drv_aadhaar_img_path', array('label' => '')); ?>
                                    </div>
                                    <div class="col-xs-4">
                                        <?
                                        if ($model->drv_aadhaar_img_path != '') {
                                            ?>
                                            <a href="<?= $model->drv_aadhaar_img_path ?>"  target="_blank"><?= basename($model->drv_aadhaar_img_path) ?></a>
                                        <? } ?>
                                    </div>
                                </div>
                                <span >PAN Card</span><br>
                                <div class="form-group">
                                    <div class="col-xs-8">
                                        <?= $form->fileFieldGroup($model, 'drv_pan_img_path', array('label' => '')); ?>
                                    </div>
                                    <div class="col-xs-4">
                                        <?
                                        if ($model->drv_pan_img_path != '') {
                                            ?>
                                            <a href="<?= $model->drv_pan_img_path ?>"  target="_blank"><?= basename($model->drv_pan_img_path) ?></a>
                                        <? } ?>
                                    </div>
                                </div>

                            </div>
                            <span >Voter ID Card</span><br>
                            <div class="form-group">
                                <div class="col-xs-8">
                                    <?= $form->fileFieldGroup($model, 'drv_voter_id_img_path', array('label' => '')); ?>
                                </div>
                                <div class="col-xs-4">
                                    <?
                                    if ($model->drv_voter_id_img_path != '') {
                                        ?>
                                        <a href="<?= $model->drv_voter_id_img_path ?>"  target="_blank"><?= basename($model->drv_voter_id_img_path) ?></a>
                                    <? } ?>
                                </div>
                            </div>


                            <span>Photo copy of driver's license</span>
                            <div class="form-group">
                                <div class="col-xs-8">
                                    <?= $form->fileFieldGroup($model, 'drv_licence_path', array('label' => '')); ?>
                                </div>
                                <div class="col-xs-4">
                                    <?
                                    if ($model->drv_licence_path != '') {
                                        ?>
                                        <a href="<?= $model->drv_licence_path ?>"  target="_blank"><?= basename($model->drv_licence_path) ?></a>
                                    <? } ?>
                                </div>
                            </div>
<!--                            <span>Address Proof 1</span>
                            <div class="form-group">
                                <div class="col-xs-8">
                            <?= $form->fileFieldGroup($model, 'drv_adrs_proof1', array('label' => '')); ?>
                                </div>
                                <div class="col-xs-4">
                            <?
                            if ($model->drv_adrs_proof1 != '') {
                                ?>
                                                                                        <a href="<?= $model->drv_adrs_proof1 ?>"  target="_blank"><?= basename($model->drv_adrs_proof1) ?></a>
                            <? } ?>
                                </div>
                            </div>
                            <span>Address Proof 2</span>
                            <div class="form-group">
                                <div class="col-xs-8">
                            <?= $form->fileFieldGroup($model, 'drv_adrs_proof2', array('label' => '')); ?>
                                </div>
                                <div class="col-xs-4">
                            <?
                            if ($model->drv_adrs_proof2 != '') {
                                ?>
                                                                                        <a href="<?= $model->drv_adrs_proof2 ?>"  target="_blank"><?= basename($model->drv_adrs_proof2) ?></a>
                            <? } ?>
                                </div>
                            </div>-->
                            <span>Driver's police verification certificate</span>
                            <div class="form-group">
                                <div class="col-xs-8">
                                    <?= $form->fileFieldGroup($model, 'drv_police_certificate', array('label' => '')); ?>
                                </div>
                                <div class="col-xs-4">
                                    <?
                                    if ($model->drv_police_certificate != '') {
                                        ?>
                                        <a href="<?= $model->drv_police_certificate ?>"  target="_blank"><?= basename($model->drv_police_certificate) ?></a>
                                    <? } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer" style="text-align: center">
                        <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary', 'name' => 'driversubmit')); ?>
                    </div>

                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#DriversInfo_drv_phone').mask('9999999999');
        var availableTags = [];
        var front_end_height = $(window).height();
        var footer_height = $(".footer").height();
        var header_height = $(".header").height();

        if ($("#DriversInfo_drv_state").val() != '') {
            var id = $("#DriversInfo_drv_state").val();
            getCityList(id);
        }

        $("#DriversInfo_drv_state").change(function () {
            var id = $("#DriversInfo_drv_state").val();
            getCityList(id);
        });
    });


    $drv_city = <?= ($model->drv_city == '') ? 0 : $model->drv_city ?>;

    function getCityList(stateId) {
        var href2 = '<?= Yii::app()->createUrl("vendor/vehicle/cityfromstate1"); ?>';
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "json",
            "data": {"id": stateId},
            "success": function (data1) {
                $data2 = data1;
                var placeholder = $('#<?= CHtml::activeId($model, "drv_city") ?>').attr('placeholder');
                $('#<?= CHtml::activeId($model, "drv_city") ?>').select2({data: $data2, placeholder: placeholder});
                $('#<?= CHtml::activeId($model, "drv_city") ?>').select2("val", $drv_city);
            }
        });
    }

    function validateCheckHandlerss() {
        if ($("#formId").validation({errorClass: 'validationErr'})) {

            if ($('#drv_email').val() != "") {
                var pattern = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
                var retVal = pattern.test($('#drv_email').val());
                if (retVal == false)
                {
                    $('#errId').html("The email address you have entered is invalid.");
                    return false;
                }
            }
            return true;
        } else
        {
            return false;
        }
    }
</script>
