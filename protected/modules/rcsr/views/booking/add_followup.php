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
$adminlist = Admins::model()->findNameList();
$statuslist = Booking::model()->getActiveBookingStatus();
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
                                    'id' => 'add-followup-form', 'enableClientValidation' => true,
                                    'clientOptions' => array(
                                        'validateOnSubmit' => true,
                                        'errorCssClass' => 'has-error',
                                        'afterValidate' => 'js:function(form,data,hasError){
                                        if(!hasError){
                                                $.ajax({
                                                "type":"POST",
                                                "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/booking/addfollowup', ['booking_id' => $model->bkg_id])) . '",
                                                "data":form.serialize(),
                                                    "dataType": "json",
                                                    "success":function(data1){
                                                        if(data1.success)
                                                        {
                                                            addFollowupSent(data1.oldStatus);
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
                                    'enableAjaxValidation' => false,
                                    'errorMessageCssClass' => 'help-block',
                                    'htmlOptions' => array(
                                        'class' => 'form-horizontal'
                                    ),
                                ));
                                /* @var $form TbActiveForm */
                                ?>
                                <div class="form-group">
                                    <label for="delete"><b>Add Followup Date : </b></label>
                                    <?=
                                    $form->datePickerGroup($model, 'bkg_followup_date', array('label' => '',
                                        'widgetOptions' => array('options' => array('autoclose' => true,
                                                'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'placeholder' => 'Followup Date',
                                                'value' => date('d/m/Y', strtotime($date)), 'id' => 'followup_date',
                                                'class' => 'datepicker')),
                                        'prepend' => '<i class="fa fa-calendar"></i>'));
                                    ?>
                                </div>
                                <div class="form-group">
                                    <label for="delete"><b>Add Followup Time : </b></label>
                                      <?=
                                        $form->timePickerGroup($model, 'bkg_followup_time', array('label' => '',
                                            'widgetOptions' => array('id' => CHtml::activeId($model, "bkg_followup_time"),
                                                'options' => array('autoclose' => true),
                                                'htmlOptions' => array('placeholder' => 'Followup Time',
                                                                        'value' => date('h:i A', strtotime($date))
                                                    )
                                                )
                                            ));
                                        ?>
                                </div>
                                <div class="form-group">
                                    <label for="delete"><b>Add Comment : </b></label>
                                    <?= $form->textAreaGroup($model, 'bkg_followup_comment', array('label' => '', 'rows' => 10, 'cols' => 50, 'placeholder' => 'Add Comment')) ?>
                                </div>
                                <div class="Submit-button" style="margin-top: 5px;"> <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?> </div>
                                <?php $this->endWidget(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>