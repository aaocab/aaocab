<style>

    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }

    .modal-header{
        display:block;
    }
</style>
<?php
$rDetail = CancelReasons::model()->getListbyUserType(1);
$reasonList = ['' => '< Select a reason >'] + $rDetail[0];
$reasonPHList = $rDetail[1];
$jsReasonPHList = json_encode($reasonPHList);
?>
<div class="panel-advancedoptions">
    <div class="row">
        <div class="col-12 col-md-12 col-lg-12"> 

            <?= CHtml::beginForm(Yii::app()->createUrl('booking/canbooking'), "post", ['accept-charset' => "UTF-8", 'id' => "cancelForm", 'class' => "form", 'data-replace' => ".error-message p"]); ?>
            <?= CHtml::hiddenField("bk_id", $bkid, ['id' => "bk_id"]) ?>

            <div class="form-group">
                <div class="col-12">
                    <label for="delete"><b>Reason for cancellation : </b></label>
                    <?php //= CHtml::textArea('bkreason', '', ['id' => "bkreason", 'placeholder' => "Please write message", 'class' => "form-control", 'rows' => "3", 'cols' => "50"]) ?>
                    <?php //= CHtml::dropDownList('bkreason', '', ['' => '< Select a reason >'] + Booking::model()->getCancelReasonListForCustomer('cancel'), ['id' => "bkreason", 'class' => "form-control", 'required' => true]) ?>
                    <?= CHtml::dropDownList('bkreason', '', $reasonList, ['id' => "bkreason", 'class' => "form-control", 'required' => true]) ?>
                </div>
                <div class="col-12 mt10" id="reasontext" style="display: none">
                    <?= CHtml::textArea('bkreasontext', '', ['id' => "bkreasontext", 'class' => "form-control", 'placeholder' => 'Description'])
                    ?>
                </div>
            </div>
            <div class="Submit-button text-center mb20">
                <?php echo CHtml::submitButton("SUBMIT", ['class' => "btn btn-primary text-uppercase gradient-green-blue font-16 border-none mt5"]); ?>
            </div>
            <?= CHtml::endForm() ?>




        </div>
    </div>
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
//                $href = '<? //= Yii::app()->createUrl('booking/getcanceldesctext')  ?>';
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
