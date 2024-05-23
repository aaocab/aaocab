<style type="text/css">
    .dlgComments .dijitDialogPaneContent {
        overflow: auto;
    }
    div .comments {
        border-bottom:1px #333 solid;
        padding:3px;
        line-height: 14px;
        font-weight: normal;
    }
    div .comments .comment {
        padding:3px;
    }
    div .comments .footer {
        padding:2px 5px;
        color: #888;
        text-align: right;
        font-style: italic;
        font-size: 0.85em;
        height: auto;
        width: auto;
    }
    .remarkbox {
        width: 100%;
        padding: 3px;
        overflow: auto;
        line-height: 14px;
        font: normal arial;
        border-radius: 5px;
        -moz-border-radius: 5px;
        border: 1px #aaa solid;
    }
</style>
<style type="text/css">
    .form-group {
        margin-bottom: 7px;
        margin-top: 15px;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    .form-horizontal .checkbox-inline {
        padding-top: 0;
    }
    #Booking_chk_user_msg {
        margin-left: 10px
    }
    .dlgComments .dijitDialogPaneContent {
        overflow: auto;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $('.bootbox').removeAttr('tabindex');
    });
</script>
<?php
$sourcelist = BookingTemp::model()->getSourceIndexed();
$followupStatus = BookingTemp::model()->getLeadStatus('follow');
$adminlist = Admins::model()->findNameList();
$followupStatusList = BookingTemp::model()->getLeadStatus();
$date = date('Y-m-d H:i:s');
?>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-xs-12">
            <div class="panel" >
                <div class="panel-body panel-body panel-no-padding">
                    <div class="panel-scroll">
                        <div class="row">
                            <div class="col-xs-12">
                                <?php
                                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                                    'id' => 'add-followup1-form',
                                    'enableClientValidation' => true,
                                    'clientOptions' => array(
                                        'validateOnSubmit' => true,
                                        'errorCssClass' => 'has-error',
                                        'afterValidate' => 'js:function(form,data,hasError){
                                        if(!hasError){
                                            $.ajax({
                                            "type":"POST",
                                            "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/lead/addfollowup', ['bkg_id' => $model->bkg_id])) . '",
                                            "data":form.serialize(),
                                                "dataType": "json",
                                                "success":function(data1){
                                                if(data1.success)
                                                {
                                                updateGrid(1);
                                                  $(".bootbox").modal("hide");

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
                                    'enableAjaxValidation' => FALSE,
                                    'errorMessageCssClass' => 'help-block',
                                    'htmlOptions' => array(
                                        'class' => 'form-horizontal'
                                    ),
                                ));
                                /* @var $form TbActiveForm */
                                ?>

                                <?= $form->hiddenField($model, 'bkg_id') ?>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                        <? $strcallbackdate = ($model->bkg_follow_up_reminder == '') ? date('Y-m-d H:i:s', strtotime('+1 hour')) : $model->bkg_follow_up_reminder; ?>

                                        <?=
                                        $form->datePickerGroup($model, 'bkg_follow_up_reminder_date', array('label' => 'Reminder Date',
                                            'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Reminder Date', 'value' => DateTimeFormat::DateTimeToDatePicker($strcallbackdate))), 'prepend' => '<i class="fa fa-calendar"></i>'));
                                        ?>

                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <?
                                        echo $form->timePickerGroup($model, 'bkg_follow_up_reminder_time', array('label' => 'Reminder Time',
                                            'widgetOptions' => array('id' => CHtml::activeId($model, "bkg_follow_up_reminder_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Reminder Time', 'value' => date('h:i A', strtotime($strcallbackdate))))));
                                        ?>   
                                    </div>
                                    <div id="errordivpdate" class="ml15 mt10 " style="color:#da4455"></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">                        
                                        <label class="control-label" >Follow up status</label>
                                        <?php
                                        $fstatusJson = VehicleTypes::model()->getJSON($followupStatus);
//
                                        $this->widget('booster.widgets.TbSelect2', array(
                                            'model' => $model,
                                            'attribute' => 'bkg_follow_up_status',
                                            'val' => $model->bkg_follow_up_status,
                                            'asDropDownList' => FALSE,
                                            'options' => array('data' => new CJavaScriptExpression($fstatusJson)),
                                            //'data' => $followupStatus,
                                            'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Follow up status', 'label' => 'Select Follow up status')
                                        ));

// echo $form->dropDownListGroup($model, 'bkg_vehicle_type_id', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control border-none border-radius'], 'data' => ['' => 'Select Cab type'] + $cartype)))
                                        ?>
                                        <span class="has-error"><? echo $form->error($model, 'bkg_follow_up_status'); ?></span>



                                    </div>
                                </div>
                                <?
                                if ($model->bkg_log_comment != '') {
                                    ?>
                                    <div class="row">                         
                                        <div class="col-xs-12">
                                            <label class="control-label" for="type">User Comment</label>
                                            <div class="remarkbox">
                                                <?= $model->bkg_log_comment; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                }
                                ?>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?= $form->textAreaGroup($model, 'new_follow_up_comment', array('widgetOptions' => array('htmlOptions' => array()))) ?>
                                    </div>
                                </div>
                                <?
                                if ($model->bkg_follow_up_comment != '') {
                                    ?>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <label class="control-label" for="type">Previous Comments</label>
                                            <div class="remarkbox">
                                                <?
                                                if (CJSON::decode($model->bkg_follow_up_comment) != '') {
                                                    $comment = CJSON::decode($model->bkg_follow_up_comment);
                                                    foreach ($comment as $cm) {
                                                        ?>
                                                        <div class="comments">
                                                            <div class="comment"><?= nl2br($cm[2]) ?></div>
                                                            <div class="footer" style="margin-bottom: 5px"><span><?= $sourcelist[$cm[3]] . "</span> | <span>" . date('d/m/Y h:i A', strtotime($cm[1])) . "</span> | <span>" . $followupStatusList[$cm[4]] . "</span> | <span>" . $adminlist[$cm[0]] ?></span>
                                                            </div>
                                                        </div>
                                                        <?
                                                    }
                                                } else {
                                                    ?><div class="p10"><?
                                                        echo $model->bkg_follow_up_comment;
                                                        ?></div><?
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div> 
                                <? }
                                ?>
                                <?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>

                                <?php $this->endWidget(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>