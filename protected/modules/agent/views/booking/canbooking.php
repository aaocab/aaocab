<?
$rDetail = CancelReasons::model()->getListbyUserType(1);
$reasonList = ['' => '< Select a reason >'] + $rDetail[0];
$reasonPHList = $rDetail[1];
$jsReasonPHList=  json_encode($reasonPHList);
?>
<div class="row">
    <div class="h4 text-center">
        <?= $bkgCode ?>
    </div>
    <?= CHtml::beginForm(Yii::app()->createUrl('agent/booking/canbooking'), "post", ['accept-charset' => "UTF-8", 'id' => "deleteForm", 'class' => "form", 'data-replace' => ".error-message p"]); ?>
    <?= CHtml::hiddenField("bk_id", $bkid, ['id' => "bk_id"]) ?>
    <div class="modal-body">
        <div class="form-group">
            <label for="delete"><b>Reason for cancellation : </b></label>
            <? //= CHtml::dropDownList('bkreason', '', ['' => '< Select a reason >'] + Booking::model()->getCancelReasonList('cancel'), ['id' => "bkreason", 'class' => "form-control", 'required' => true]) ?>
            <?//= CHtml::dropDownList('bkreason', '', ['' => '< Select a reason >'] + CancelReasons::model()->getListbyUserType(2), ['id' => "bkreason", 'class' => "form-control", 'required' => true]) ?>
        <?= CHtml::dropDownList('bkreason', '', $reasonList, ['id' => "bkreason", 'class' => "form-control", 'required' => true]) ?>
        </div>
        <div class="form-group">
            <div class="mt10" id="reasontext" style="display: none">
                <?= CHtml::textArea('bkreasontext', '', ['id' => "bkreasontext", 'class' => "form-control", 'placeholder' => 'Enter reason'])
                ?>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <?= CHtml::submitButton("SUBMIT", ['class' => "btn green"]); ?>
        <button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
    </div>
    <?= CHtml::endForm() ?>

</div>

<script>
    $(function () {
        var rpList = [];
        rpList = <?= $jsReasonPHList ?>;
        $("#bkreason").change(function () {
            var reason = $("#bkreason").val();
            if (reason != '') {				
                $("#bkreasontext").attr('placeholder', rpList[reason]);
                $("#reasontext").show();
                $("#bkreasontext").attr('required', 'required');
            }
//            if (reason != '') {
//                $href = '<?//= Yii::app()->createUrl('booking/getcanceldesctext') ?>';
//                jQuery.ajax({"dataType": "json", data: {"rval": reason}, url: $href,
//                    success: function (data1) {
//                        $("#bkreasontext").attr('placeholder', data1.rtext);
//
//                        $("#reasontext").show();
//                        $("#bkreasontext").attr('required', 'required');
//                    }
//
//
//                });
//            }
        });
    });
</script>
