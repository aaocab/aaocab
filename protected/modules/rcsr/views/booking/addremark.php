
<style>

    .dlgComments .dijitDialogPaneContent{
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

    .remarkbox{
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


<style>

    .form-group {
        margin-bottom: 7px;
        margin-top: 15px;

        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .form-horizontal .checkbox-inline{
        padding-top: 0;
    }
    #Booking_chk_user_msg{
        margin-left: 10px
    }
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<script>
    $(document).ready(function () {
        $('.bootbox').removeAttr('tabindex');
    });
</script>
<?php
$adminlist = Admins::model()->findNameList();
$statuslist = Booking::model()->getActiveBookingStatus();
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
                                    'id' => 'add-remark-form', 'enableClientValidation' => true,
                                    'clientOptions' => array(
                                        'validateOnSubmit' => true,
                                        'errorCssClass' => 'has-error',
                                        'afterValidate' => 'js:function(form,data,hasError){
                                            if(!hasError){
                                                $.ajax({
                                                "type":"POST",
                                                "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/booking/addremarks', ['booking_id' => $bookModel->bkg_id])) . '",
                                                "data":form.serialize(),
                                                        "dataType": "json",
                                                        "success":function(data1){
                                                                if(data1.success)
                                                                {
                                                                remarkSent(data1.oldStatus);
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
                                <?= $form->hiddenField($logModel, 'blg_booking_id') ?>
                                <div class="form-group">
                                    <label for="delete"><b>Remark Type: </b></label>
                                    <?=
                                    $form->radioButtonListGroup($logModel, 'blg_remark_type', array(
                                        'label' => '', 'widgetOptions' => array(
                                            'data' => BookingLog::model()->markRemarkBad,
                                        ),
                                        'inline' => true,
                                            )
                                    );
                                    ?>
                                </div>
                                <?php
                                if ($bookModel->bkgBcb->bcb_cab_id != NULL) {
                                    ?>
                                    <div class="form-group">
                                        <?= $form->checkBox($logModel, 'blg_mark_car', array('label' => '')); ?>&nbsp;<label for="delete"><b>Mark car bad</b></label>
                                    </div>
                                <?php }
                                ?>

                                <?php
                                if ($bookModel->bkgBcb->bcb_driver_id != NULL) {
                                    ?>
                                    <div class="form-group">
                                        <?= $form->checkBox($logModel, 'blg_mark_driver', array('label' => '')); ?>&nbsp;<label for="delete"><b>Mark driver bad</b></label>
                                    </div>
                                <?php }
                                ?>
                                <?php
                                if ($bookModel->bkg_user_id != NULL) {
                                    ?>
                                    <div class="form-group">
                                        <?= $form->checkBox($logModel, 'blg_mark_customer', array('label' => '')); ?>&nbsp;<label for="delete"><b>Mark customer bad</b></label>
                                    </div>
                                <?php }
                                ?>
                                <div class="form-group">
                                    <label for="delete"><b>Add Remark : </b></label>
                                    <?= $form->textAreaGroup($logModel, 'blg_desc', array('label' => '', 'rows' => 10, 'cols' => 50)) ?>
                                </div>
                                <div class="form-group">
                                    <label for="delete"><b>Additional Instruction to Vendor/Driver : </b></label>
                                    <div id="AdditionalInfoBlock">
                                        <?= $form->textAreaGroup($logModel, 'blg_addl_desc', array('label' => '', 'rows' => 10, 'cols' => 50)) ?>
                                    </div>
                                </div>
                                <div class="Submit-button" style="margin-top: 5px;">
                                    <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>
                                </div>
                                <?php $this->endWidget(); ?>
                            </div>
                        </div>


                        <div class="row">
                            <label class="ml15 mt10">Previous Remarks</label>
                            <div class="col-xs-12">
                                <div class="remarkbox">
                                    <?php
                                    if (count($logList) > 0) {
                                        foreach ($logList as $log):
                                            ?>
                                            <div class="comments">
                                                <div class="comment"><?= ($log['blg_desc']) ?></div>
                                                <div class="footer" style="margin-bottom: 5px"><span><?= $statuslist[$log['blg_booking_status']] . "</span> |" ?><?= " <span>" . date('d/m/Y h:i A', strtotime($log['blg_created'])) . "</span> | <span>" . $adminlist[$log['blg_admin_id']] ?></span>
                                                </div>
                                            </div>
                                            <?php
                                        endforeach;
                                    }else {
                                        ?>
                                        <div class="comments"><div class="comment"><b>No Remark Found.</b></div></div>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
