
<style>

    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-xs-12"> 
            <div class="panel mb0">                  

                <div class="panel-heading text-center pt0" style="color: #000000"><?= $bkgCode ?></div>
                <div style="width: 100%; padding: 3px; overflow: auto; line-height: 10px; font: normal arial; border-radius: 5px; -moz-border-radius: 5px; border: 1px #aaa solid;color: #444;">
                    <?= CHtml::beginForm(Yii::app()->createUrl('rcsr/booking/canbooking'), "post", ['accept-charset' => "UTF-8", 'id' => "deleteForm", 'class' => "form", 'data-replace' => ".error-message p"]); ?>
                    <?= CHtml::hiddenField("bk_id", $bkid, ['id' => "bk_id"]) ?>
                    <div class="panel-body panel-no-padding">
                        <div class="form-group">

                            <label for="delete"><b>Reason for cancellation : </b></label>
                            <? //= CHtml::dropDownList('bkreason', '', ['' => '< Select a reason >'] + Booking::model()->getCancelReasonList('cancel'), ['id' => "bkreason", 'class' => "form-control", 'required' => true]) ?>
                            <?= CHtml::dropDownList('bkreason', '', ['' => '< Select a reason >'] + CancelReasons::model()->getListbyUserType(2), ['id' => "bkreason", 'class' => "form-control", 'required' => true]) ?>
                        </div>
                        <div class="form-group">
                            <div class="mt10" id="reasontext" style="display: none">
                                <?= CHtml::textArea('bkreasontext', '', ['id' => "bkreasontext", 'class' => "form-control", 'placeholder' => 'Enter reason'])
                                ?>

                            </div>
                        </div>
                        <div class="Submit-button text-center" >
                            <? echo CHtml::submitButton("SUBMIT", ['class' => "btn btn-primary mt10"]); ?>

                        </div></div>
                    <?= CHtml::endForm() ?>


                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#bkreason").change(function () {
            // if ($(this).val() == "Others") {
            //   if ($(this).val() == "4") {
            $("#reasontext").show();
            $("#bkreasontext").attr('required', 'required');
//            } else {
//                $("#reasontext").hide();
//                $("#bkreasontext").removeAttr('required');
//            }
        });
    });
</script>
